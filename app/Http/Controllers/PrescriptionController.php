<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function create(Request $request)
    {
        if (auth()->user()->role === 'patient') {
            abort(403, 'Patients cannot create prescriptions.');
        }

        $selectedPatient = null;

        if ($request->filled('patient_id')) {
            $selectedPatient = Patient::with('user')->findOrFail($request->patient_id);

            if (
                auth()->user()->role === 'doctor' &&
                !session()->get('doctor_verified_patient_' . $selectedPatient->id)
            ) {
                return redirect()
                    ->route('doctor.search.patient')
                    ->with('error', 'Please verify patient privacy key first.');
            }
        }

        return view('prescriptions.create', compact('selectedPatient'));
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

        Prescription::create([
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
            'prescription_date' => $request->prescription_date,
            'diagnosis' => $request->diagnosis,
            'medicines' => $request->medicines,
            'advice' => $request->advice,
            'next_visit_date' => $request->next_visit_date,
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