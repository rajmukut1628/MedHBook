<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\MedicalDocument;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DoctorDashboardController extends Controller
{
    private function currentDoctor()
    {
        return Doctor::where('user_id', auth()->id())
            ->orWhere('email', auth()->user()->email)
            ->first();
    }

    private function appointmentDateColumn()
    {
        if (Schema::hasColumn('appointments', 'appointment_date')) {
            return 'appointment_date';
        }

        if (Schema::hasColumn('appointments', 'date')) {
            return 'date';
        }

        return null;
    }

    private function appointmentTimeColumn()
    {
        if (Schema::hasColumn('appointments', 'appointment_time')) {
            return 'appointment_time';
        }

        if (Schema::hasColumn('appointments', 'time')) {
            return 'time';
        }

        return null;
    }

    public function dashboard()
    {
        $doctor = $this->currentDoctor();

        $appointments = collect();
        $todayAppointments = collect();
        $upcomingAppointments = collect();

        $patientsCount = 0;
        $pendingCount = 0;
        $approvedCount = 0;
        $rejectedCount = 0;
        $cancelledCount = 0;
        $todayCount = 0;
        $earningsEstimate = 0;

        if ($doctor) {
            $dateColumn = $this->appointmentDateColumn();
            $timeColumn = $this->appointmentTimeColumn();

            $appointments = Appointment::with(['patient', 'doctor'])
                ->where('doctor_id', $doctor->id)
                ->latest()
                ->get();

            if ($dateColumn) {
                $todayQuery = Appointment::with(['patient', 'doctor'])
                    ->where('doctor_id', $doctor->id)
                    ->whereDate($dateColumn, today());

                if ($timeColumn) {
                    $todayQuery->orderBy($timeColumn);
                }

                $todayAppointments = $todayQuery->get();

                $upcomingQuery = Appointment::with(['patient', 'doctor'])
                    ->where('doctor_id', $doctor->id)
                    ->whereDate($dateColumn, '>=', today())
                    ->whereNotIn('status', ['Rejected', 'Cancelled', 'rejected', 'cancelled'])
                    ->orderBy($dateColumn);

                if ($timeColumn) {
                    $upcomingQuery->orderBy($timeColumn);
                }

                $upcomingAppointments = $upcomingQuery->take(6)->get();
            }

            $patientsCount = $appointments->pluck('patient_id')->unique()->count();
            $pendingCount = $appointments->whereIn('status', ['Pending', 'pending'])->count();
            $approvedCount = $appointments->whereIn('status', ['Approved', 'approved'])->count();
            $rejectedCount = $appointments->whereIn('status', ['Rejected', 'rejected'])->count();
            $cancelledCount = $appointments->whereIn('status', ['Cancelled', 'cancelled'])->count();
            $todayCount = $todayAppointments->count();
            $earningsEstimate = $approvedCount * ($doctor->consultation_fee ?? 0);
        }

        return view('doctor.dashboard', compact(
            'doctor',
            'appointments',
            'todayAppointments',
            'upcomingAppointments',
            'patientsCount',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'cancelledCount',
            'todayCount',
            'earningsEstimate'
        ));
    }
        public function patients()
    {
        $doctor = $this->currentDoctor();

        $patients = collect();

        if ($doctor) {
            $patients = Patient::with('user')
                ->whereIn('id', function ($query) use ($doctor) {
                    $query->select('patient_id')
                        ->from('appointments')
                        ->where('doctor_id', $doctor->id);
                })
                ->latest()
                ->get();
        }

        return view('doctor.patients', compact('patients', 'doctor'));
    }

    public function searchPatient()
    {
        $doctor = $this->currentDoctor();

        $patients = Patient::with('user')
            ->latest()
            ->get();

        return view('doctor.search-patient', compact('patients', 'doctor'));
    }

    public function verifyPatientPrivacy(Request $request, Patient $patient)
    {
        $request->validate([
            'privacy_key' => ['required', 'string'],
        ]);

        if (strtoupper(trim($request->privacy_key)) !== strtoupper($patient->privacyKey())) {
            return back()->with('error', 'Invalid privacy key.');
        }

        session()->put('doctor_verified_patient_' . $patient->id, true);

        return redirect()
            ->route('doctor.patient.profile', $patient->id)
            ->with('success', 'Patient verified successfully.');
    }

    public function verifiedPatientProfile(Patient $patient)
    {
        if (!session()->get('doctor_verified_patient_' . $patient->id)) {
            return redirect()
                ->route('doctor.search.patient')
                ->with('error', 'Please verify patient privacy key first.');
        }

        $patient->load('user');

        return view('doctor.patient-profile', compact('patient'));
    }

    public function verifiedPatientDocuments(Patient $patient)
    {
        if (!session()->get('doctor_verified_patient_' . $patient->id)) {
            return redirect()
                ->route('doctor.search.patient')
                ->with('error', 'Please verify patient privacy key first.');
        }

        $patient->load('user');

        $documents = MedicalDocument::where('user_id', $patient->user_id)
            ->latest()
            ->get()
            ->groupBy('document_type');

        return view('doctor.patient-documents', compact('patient', 'documents'));
    }

    public function overview()
    {
        return $this->dashboard();
    }
}