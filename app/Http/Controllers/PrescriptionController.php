<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\MedicalDocument;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PrescriptionController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $prescriptions = Prescription::with(['patient.user', 'doctor.user'])
            ->when($user->role === 'patient', function ($query) use ($user) {
                $patient = Patient::where('user_id', $user->id)->first();

                if ($patient) {
                    $query->where('patient_id', $patient->id);
                } else {
                    $query->whereRaw('1 = 0');
                }
            })
            ->when($user->role === 'doctor', function ($query) use ($user) {
                $doctor = Doctor::where('user_id', $user->id)
                    ->orWhere('email', $user->email)
                    ->first();

                if ($doctor) {
                    $query->where('doctor_id', $doctor->id);
                } else {
                    $query->whereRaw('1 = 0');
                }
            })
            ->latest()
            ->get();

        return view('prescriptions.index', compact('prescriptions'));
    }

    /**
     * Create Prescription Page
     * If patient_id is provided and privacy is already verified,
     * the prescription form will open directly without asking
     * for privacy verification again.
     */
    public function create(Request $request)
    {
        if (auth()->user()->role === 'patient') {
            abort(403, 'Patients cannot create prescriptions.');
        }

        $selectedPatient = null;
        $isPrivacyVerified = false;

        if ($request->filled('patient_id')) {
            $selectedPatient = Patient::with('user')
                ->findOrFail($request->patient_id);

            // Check session
            $isPrivacyVerified = session()->get(
                'doctor_verified_patient_' . $selectedPatient->id,
                false
            );

            // If doctor came from verified patient profile,
            // session will exist and form will open directly.
            // If not verified, redirect to search page.
            if (
                auth()->user()->role === 'doctor' &&
                !$isPrivacyVerified
            ) {
                return redirect()
                    ->route('doctor.search.patient')
                    ->with('error', 'Please verify patient privacy key first.');
            }
        }

        return view('prescriptions.create', compact(
            'selectedPatient',
            'isPrivacyVerified'
        ));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role === 'patient') {
            abort(403, 'Patients cannot create prescriptions.');
        }

        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'prescription_date' => 'required|date',
            'diagnosis' => 'required|string',
            'medicines' => 'required|string',
            'advice' => 'nullable|string',
            'next_visit_date' => 'nullable|date',
        ]);

        $patient = Patient::findOrFail($request->patient_id);

        // Doctor must verify privacy first
        if (
            auth()->user()->role === 'doctor' &&
            !session()->get('doctor_verified_patient_' . $patient->id)
        ) {
            return redirect()
                ->route('doctor.search.patient')
                ->with('error', 'Please verify patient privacy key first.');
        }

        $doctor = Doctor::where('user_id', auth()->id())
            ->orWhere('email', auth()->user()->email)
            ->first();

        if (!$doctor) {
            return back()
                ->withInput()
                ->with('error', 'Doctor profile not found.');
        }

        $prescription = Prescription::create([
    'doctor_id' => $doctor->id,
    'patient_id' => $patient->id,
    'prescription_date' => $request->prescription_date,
    'diagnosis' => $request->diagnosis,
    'medicines' => $request->medicines,
    'advice' => $request->advice,
    'next_visit_date' => $request->next_visit_date,
]);

$prescription->load(['patient.user', 'doctor.user']);

$pdf = Pdf::loadView('prescriptions.pdf', compact('prescription'))
    ->setPaper('a4', 'portrait');

$pdfContent = $pdf->output();

$fileName = 'doctor_prescription_' .
    $patient->user_id . '_' .
    $prescription->id . '_' .
    now()->format('YmdHis') . '_' .
    Str::random(16) .
    '.mhb';

$originalName = 'doctor-prescription-' . $prescription->id . '.pdf';

$path = 'private/medical-documents/user-' . $patient->user_id . '/' . $fileName;

$encryptedContent = Crypt::encryptString(base64_encode($pdfContent));

Storage::disk('local')->put($path, $encryptedContent);

MedicalDocument::create([
    'user_id' => $patient->user_id,
    'title' => 'Doctor Digital Prescription #' . $prescription->id,
    'document_type' => 'Doctor Digital Prescription PDF',
    'doctor_name' => $doctor->name ?? $doctor->user->name ?? auth()->user()->name,
    'hospital_name' => $doctor->chamber_address ?? null,
    'document_date' => $request->prescription_date,
    'notes' => 'Generated by doctor from MedHBook digital prescription system.',

    'encrypted_name' => $fileName,
    'original_name' => $originalName,

    'storage_disk' => 'local',
    'storage_path' => $path,

    'file_type' => 'application/pdf',
    'file_size' => strlen($pdfContent),

    'encryption_mode' => 'laravel_crypt_v1',
]);

        return redirect()
            ->route('prescriptions.index')
            ->with('success', 'Prescription created successfully.');
    }

    public function show(Prescription $prescription)
    {
        $this->authorizePrescriptionAccess($prescription);

        $prescription->load(['patient.user', 'doctor.user']);

        return view('prescriptions.show', compact('prescription'));
    }

    public function edit(Prescription $prescription)
    {
        if (auth()->user()->role === 'patient') {
            abort(403, 'Patients cannot edit prescriptions.');
        }

        $patients = Patient::with('user')->latest()->get();

        return view('prescriptions.edit', compact('prescription', 'patients'));
    }

    public function update(Request $request, Prescription $prescription)
    {
        if (auth()->user()->role === 'patient') {
            abort(403, 'Patients cannot update prescriptions.');
        }

        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'prescription_date' => 'required|date',
            'diagnosis' => 'required|string',
            'medicines' => 'required|string',
            'advice' => 'nullable|string',
            'next_visit_date' => 'nullable|date',
        ]);

        $prescription->update([
            'patient_id' => $request->patient_id,
            'prescription_date' => $request->prescription_date,
            'diagnosis' => $request->diagnosis,
            'medicines' => $request->medicines,
            'advice' => $request->advice,
            'next_visit_date' => $request->next_visit_date,
        ]);

        return redirect()
            ->route('prescriptions.index')
            ->with('success', 'Prescription updated successfully.');
    }

    public function destroy(Prescription $prescription)
    {
        if (auth()->user()->role === 'patient') {
            abort(403, 'Patients cannot delete prescriptions.');
        }

        $prescription->delete();

        return redirect()
            ->route('prescriptions.index')
            ->with('success', 'Prescription deleted successfully.');
    }

    public function downloadPdf(Prescription $prescription)
    {
        $this->authorizePrescriptionAccess($prescription);

        $prescription->load(['patient.user', 'doctor.user']);

        $pdf = Pdf::loadView('prescriptions.pdf', compact('prescription'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('prescription-' . $prescription->id . '.pdf');
    }

    private function authorizePrescriptionAccess(Prescription $prescription): void
    {
        $user = auth()->user();

        if ($user->role === 'patient') {
            $patient = Patient::where('user_id', $user->id)->first();

            if (!$patient || (int) $prescription->patient_id !== (int) $patient->id) {
                abort(403);
            }

            return;
        }

        if ($user->role === 'doctor') {
            $doctor = Doctor::where('user_id', $user->id)
                ->orWhere('email', $user->email)
                ->first();

            if (!$doctor || (int) $prescription->doctor_id !== (int) $doctor->id) {
                abort(403);
            }

            return;
        }

        if (in_array($user->role, ['admin', 'super_admin'])) {
            return;
        }

        abort(403);
    }
}