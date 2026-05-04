<?php

namespace App\Http\Controllers;

use App\Models\MedicalDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MedicalDocumentController extends Controller
{
    private string $disk = 'local';

    public function index()
    {
        $documents = MedicalDocument::where('user_id', Auth::id())
            ->latest()
            ->get();

        $types = [
            'Prescription',
            'MRI',
            'CT Scan',
            'X-Ray',
            'Blood Report',
            'Doctor Digital Prescription PDF',
            'Other',
        ];

        $groupedDocuments = collect($types)->mapWithKeys(function ($type) use ($documents) {
            return [
                $type => $documents->filter(fn ($doc) => $this->normalizeType($doc->document_type) === $type),
            ];
        });

        return view('medical-documents.index', compact('documents', 'groupedDocuments'));
    }

    public function create()
    {
        return view('medical-documents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'document_type' => ['required', 'string', 'max:100'],
            'doctor_name' => ['nullable', 'string', 'max:255'],
            'hospital_name' => ['nullable', 'string', 'max:255'],
            'document_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],

            'medical_files' => ['required', 'array', 'min:1'],
            'medical_files.*' => [
                'required',
                'file',
                'max:51200',
                'mimes:pdf,jpg,jpeg,png,webp,doc,docx,xls,xlsx,txt,zip',
            ],
        ]);

        $type = $this->normalizeType($request->document_type);

        if ($type === 'Doctor Digital Prescription PDF') {
            return back()
                ->withInput()
                ->with('error', 'Patient cannot upload Doctor Digital Prescription PDF. Doctor will create it online.');
        }

        DB::beginTransaction();

        try {
            $uploadedCount = 0;
            $files = $request->file('medical_files');

            foreach ($files as $file) {
                $safeOriginal = $this->cleanFileName($file->getClientOriginalName());

                /*
                |--------------------------------------------------------------------------
                | Encrypted File Name
                |--------------------------------------------------------------------------
                | Original extension রাখছি না, তাই Windows preview দেখাবে না।
                | Example: mhb_17_20260504_xxxxx.mhb
                */
                $fileName = 'mhb_' .
                    Auth::id() . '_' .
                    now()->format('YmdHis') . '_' .
                    Str::random(24) .
                    '.mhb';

                $path = 'private/medical-documents/user-' . Auth::id() . '/' . $fileName;

                /*
                |--------------------------------------------------------------------------
                | File Encryption
                |--------------------------------------------------------------------------
                | Original file content read করে encrypt করা হচ্ছে।
                | PC folder এ encrypted .mhb file থাকবে।
                | Windows preview/image/pdf preview দেখাবে না।
                */
                $originalContent = file_get_contents($file->getRealPath());
                $encryptedContent = Crypt::encryptString(base64_encode($originalContent));

                Storage::disk($this->disk)->put($path, $encryptedContent);

                MedicalDocument::create([
                    'user_id' => Auth::id(),

                    'title' => count($files) > 1
                        ? $request->title . ' - ' . $safeOriginal
                        : $request->title,

                    'document_type' => $type,
                    'doctor_name' => $request->doctor_name,
                    'hospital_name' => $request->hospital_name,
                    'document_date' => $request->document_date,
                    'notes' => $request->notes,

                    'encrypted_name' => $fileName,
                    'original_name' => $safeOriginal,

                    'storage_disk' => $this->disk,
                    'storage_path' => $path,

                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),

                    'encryption_mode' => 'laravel_crypt_v1',
                ]);

                $uploadedCount++;
            }

            DB::commit();

            return redirect()
                ->route('medical-documents.index')
                ->with('success', $uploadedCount . ' encrypted document(s) uploaded successfully.');

        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Upload failed: ' . $e->getMessage());
        }
    }

    public function show(MedicalDocument $medicalDocument)
    {
        $this->authorizeOwner($medicalDocument);

        return view('medical-documents.show', [
            'medicalDocument' => $medicalDocument,
        ]);
    }
        public function download(MedicalDocument $medicalDocument): StreamedResponse
    {
        $this->authorizeOwner($medicalDocument);

        $disk = $medicalDocument->storage_disk ?: $this->disk;
        $path = $medicalDocument->storage_path;

        if (!$path || !Storage::disk($disk)->exists($path)) {
            abort(404, 'File not found.');
        }

        /*
        |--------------------------------------------------------------------------
        | Decrypt File Before Download
        |--------------------------------------------------------------------------
        | PC folder এ .mhb encrypted file থাকবে।
        | Download করলে original PDF/JPG/PNG/DOC file হিসেবে return হবে।
        */
        if ($medicalDocument->encryption_mode === 'laravel_crypt_v1') {
            $encryptedContent = Storage::disk($disk)->get($path);

            $decryptedBase64 = Crypt::decryptString($encryptedContent);
            $originalContent = base64_decode($decryptedBase64);

            return response()->streamDownload(function () use ($originalContent) {
                echo $originalContent;
            }, $medicalDocument->original_name ?: 'medical-document', [
                'Content-Type' => $medicalDocument->file_type ?: 'application/octet-stream',
                'X-Content-Type-Options' => 'nosniff',
                'Cache-Control' => 'private, no-store, no-cache, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);
        }

        return Storage::disk($disk)->download(
            $path,
            $medicalDocument->original_name ?: 'medical-document',
            [
                'Content-Type' => $medicalDocument->file_type ?: 'application/octet-stream',
                'X-Content-Type-Options' => 'nosniff',
                'Cache-Control' => 'private, no-store, no-cache, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]
        );
    }

    public function destroy(MedicalDocument $medicalDocument)
    {
        $this->authorizeOwner($medicalDocument);

        DB::beginTransaction();

        try {
            $disk = $medicalDocument->storage_disk ?: $this->disk;

            if ($medicalDocument->storage_path && Storage::disk($disk)->exists($medicalDocument->storage_path)) {
                Storage::disk($disk)->delete($medicalDocument->storage_path);
            }

            $medicalDocument->delete();

            DB::commit();

            return redirect()
                ->route('medical-documents.index')
                ->with('success', 'Document deleted successfully.');

        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Delete failed: ' . $e->getMessage());
        }
    }

    private function authorizeOwner(MedicalDocument $medicalDocument): void
    {
        if (!Auth::check()) {
            abort(403, 'Please login first.');
        }

        if ((int) $medicalDocument->user_id !== (int) Auth::id()) {
            abort(403, 'Unauthorized access.');
        }
    }

    private function normalizeType(?string $type): string
    {
        $type = strtolower(trim((string) $type));

        return match ($type) {
            'prescription', 'prescriptions' => 'Prescription',
            'mri', 'mri report', 'mri scan' => 'MRI',
            'ct', 'ct scan', 'ct report' => 'CT Scan',
            'x-ray', 'xray', 'x ray', 'x-ray report' => 'X-Ray',
            'blood', 'blood report', 'blood test', 'blood test report' => 'Blood Report',
            'doctor digital prescription pdf',
            'digital prescription',
            'digital prescription pdf',
            'doctor prescription pdf',
            'doctor online prescription' => 'Doctor Digital Prescription PDF',
            'other' => 'Other',
            default => 'Other',
        };
    }

    private function cleanFileName(string $name): string
    {
        $name = basename($name);
        $name = preg_replace('/[^A-Za-z0-9.\-_ ]/', '_', $name);
        $name = trim($name);

        return $name === '' ? 'medical-document' : Str::limit($name, 180, '');
    }
}