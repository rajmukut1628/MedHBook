<?php

namespace App\Http\Controllers;

use App\Models\MedicalDocument;
use App\Models\Patient;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MedicalDocumentController extends Controller
{
    private array $types = [
        'X-Ray',
        'Prescription',
        'MRI',
        'CT Scan',
        'Blood Report',
        'Doctor Digital Prescription PDF',
        'Other',
    ];

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $types = $this->types;

        $documents = MedicalDocument::where('user_id', auth()->id())
            ->latest()
            ->get()
            ->groupBy('document_type');

        return view('medical_documents.index', compact('documents', 'types'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $types = $this->types;

        return view('medical_documents.create', compact('types'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE (ENCRYPT + PRIVATE STORAGE)
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'document_type' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'document_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'file' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png,webp,doc,docx,txt',
                'max:10240'
            ],
        ]);

        $file = $request->file('file');

        // 🔐 Encrypt file content
        $encryptedContent = Crypt::encrypt(file_get_contents($file->getRealPath()));

        // 🔑 Generate secure filename
        $encryptedName = Str::uuid() . '.enc';

        // 📁 Private storage path
        $path = 'medical-documents/' . auth()->id() . '/' . $encryptedName;

        // 🔒 Store in PRIVATE disk
        Storage::disk('local')->put($path, $encryptedContent);

        // 💾 Save DB
        MedicalDocument::create([
            'user_id'        => auth()->id(),
            'document_type'  => $request->document_type,
            'title'          => $request->title,
            'document_date'  => $request->document_date,
            'notes'          => $request->notes,

            'encrypted_name' => $encryptedName,
            'original_name'  => $file->getClientOriginalName(),

            'storage_disk'   => 'local',
            'storage_path'   => $path,

            'file_type'      => strtolower($file->getClientOriginalExtension()),
            'file_size'      => $file->getSize(),
            'encryption_mode'=> 'server_side',
        ]);

        return redirect()->route('medical-documents.index')
            ->with('success', '✅ Document uploaded securely (encrypted).');
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW (NO FILE ACCESS HERE)
    |--------------------------------------------------------------------------
    */
    public function show(MedicalDocument $medicalDocument)
    {
        if ($medicalDocument->user_id !== auth()->id()) {
            abort(403);
        }

        return view('medical_documents.show', compact('medicalDocument'));
    }

    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD (SECURE + DECRYPT)
    |--------------------------------------------------------------------------
    */
    public function download(MedicalDocument $medicalDocument)
    {
        // ❌ Admin / Developer blocked
        if ($medicalDocument->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        if (!Storage::disk($medicalDocument->storage_disk)
            ->exists($medicalDocument->storage_path)) {
            abort(404, 'File not found.');
        }

        // 🔐 Read encrypted
        $encryptedContent = Storage::disk($medicalDocument->storage_disk)
            ->get($medicalDocument->storage_path);

        // 🔓 Decrypt
        $decryptedContent = Crypt::decrypt($encryptedContent);

        $fileName = $medicalDocument->original_name
            ?? 'document.' . $medicalDocument->file_type;

        return response($decryptedContent)
            ->header('Content-Type', 'application/octet-stream')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function destroy(MedicalDocument $medicalDocument)
    {
        if ($medicalDocument->user_id !== auth()->id()) {
            abort(403);
        }

        if (Storage::disk($medicalDocument->storage_disk)
            ->exists($medicalDocument->storage_path)) {
            Storage::disk($medicalDocument->storage_disk)
                ->delete($medicalDocument->storage_path);
        }

        $medicalDocument->delete();

        return redirect()->route('medical-documents.index')
            ->with('success', '🗑️ Document deleted securely.');
    }
}