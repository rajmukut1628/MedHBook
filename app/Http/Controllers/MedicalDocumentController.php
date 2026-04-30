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

    public function index()
    {
        $types = $this->types;

        $documents = MedicalDocument::where('user_id', auth()->id())
            ->latest()
            ->get()
            ->groupBy('document_type');

        $patient = Patient::where('user_id', auth()->id())->first();

        if ($patient) {
            $prescriptions = Prescription::with(['doctor.user'])
                ->where('patient_id', $patient->id)
                ->latest()
                ->get()
                ->map(function ($prescription) {
                    return (object) [
                        'id' => $prescription->id,
                        'title' => 'Digital Prescription #' . $prescription->id,
                        'document_date' => $prescription->prescription_date,
                        'created_at' => $prescription->created_at,
                        'file_type' => 'PDF',
                        'file_size' => 'Generated PDF',
                        'is_prescription_pdf' => true,
                    ];
                });

            $existing = $documents->get('Doctor Digital Prescription PDF', collect());

            $documents['Doctor Digital Prescription PDF'] = $existing
                ->merge($prescriptions)
                ->sortByDesc('created_at')
                ->values();
        }

        return view('medical_documents.index', compact('documents', 'types'));
    }

    public function create()
    {
        $types = $this->types;

        return view('medical_documents.create', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'document_type' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'document_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp,doc,docx,xls,xlsx,txt,zip', 'max:10240'],
        ]);

        $file = $request->file('file');

        $encryptedContent = Crypt::encrypt(file_get_contents($file->getRealPath()));

        $fileName = Str::uuid() . '.enc';
        $path = 'medical-documents/' . auth()->id() . '/' . $fileName;

        Storage::disk('local')->put($path, $encryptedContent);

        MedicalDocument::create([
            'user_id' => auth()->id(),
            'document_type' => $request->document_type,
            'title' => $request->title,
            'document_date' => $request->document_date,
            'notes' => $request->notes,
            'file_path' => $path,
            'file_type' => strtolower($file->getClientOriginalExtension()),
            'file_size' => round($file->getSize() / 1024, 2) . ' KB',
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'is_encrypted' => true,
        ]);

        return redirect()->route('medical-documents.index')
            ->with('success', 'Medical document uploaded securely with encryption.');
    }

    public function show(MedicalDocument $medicalDocument)
    {
        if ($medicalDocument->user_id !== auth()->id() && auth()->user()->role !== 'super_admin') {
            abort(403);
        }

        return view('medical_documents.show', compact('medicalDocument'));
    }

    public function download(MedicalDocument $medicalDocument)
    {
        if ($medicalDocument->user_id !== auth()->id() && auth()->user()->role !== 'super_admin') {
            abort(403);
        }

        if (!$medicalDocument->file_path || !Storage::disk('local')->exists($medicalDocument->file_path)) {
            abort(404, 'File not found.');
        }

        $encryptedContent = Storage::disk('local')->get($medicalDocument->file_path);
        $decryptedContent = Crypt::decrypt($encryptedContent);

        $fileName = $medicalDocument->original_name
            ?? Str::slug($medicalDocument->title) . '.' . $medicalDocument->file_type;

        return response($decryptedContent)
            ->header('Content-Type', $medicalDocument->mime_type ?? 'application/octet-stream')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    public function destroy(MedicalDocument $medicalDocument)
    {
        if ($medicalDocument->user_id !== auth()->id() && auth()->user()->role !== 'super_admin') {
            abort(403);
        }

        if ($medicalDocument->file_path && Storage::disk('local')->exists($medicalDocument->file_path)) {
            Storage::disk('local')->delete($medicalDocument->file_path);
        }

        $medicalDocument->delete();

        return redirect()->route('medical-documents.index')
            ->with('success', 'Medical document deleted successfully.');
    }
}