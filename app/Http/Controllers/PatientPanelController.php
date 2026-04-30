<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Http\Request;

class PatientPanelController extends Controller
{
    public function dashboard()
    {
        return view('patient.dashboard');
    }

    public function doctors(Request $request)
    {
        $search = strtolower($request->search);

        $symptomMap = [
            'fever' => ['medicine', 'general', 'internal'],
            'cold' => ['medicine', 'general', 'ent'],
            'cough' => ['medicine', 'chest', 'pulmonology'],
            'heart' => ['cardiology', 'cardiologist'],
            'chest pain' => ['cardiology', 'cardiologist'],
            'skin' => ['dermatology', 'dermatologist'],
            'rash' => ['dermatology', 'dermatologist'],
            'eye' => ['eye', 'ophthalmology'],
            'tooth' => ['dental', 'dentist'],
            'teeth' => ['dental', 'dentist'],
            'bone' => ['orthopedic', 'orthopedics'],
            'joint pain' => ['orthopedic', 'orthopedics'],
            'pregnancy' => ['gynecology', 'gynecologist'],
            'child' => ['pediatric', 'pediatrician'],
            'mental' => ['psychiatry', 'psychiatrist'],
            'depression' => ['psychiatry', 'psychiatrist'],
            'kidney' => ['nephrology', 'nephrologist'],
            'diabetes' => ['endocrinology', 'endocrinologist'],
            'stomach' => ['gastroenterology', 'gastroenterologist'],
            'ear' => ['ent'],
            'nose' => ['ent'],
            'throat' => ['ent'],
        ];

        $query = Doctor::query();

        if ($search) {
            $query->where(function ($q) use ($search, $symptomMap) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('specialization', 'like', '%' . $search . '%');

                foreach ($symptomMap as $symptom => $specialists) {
                    if (str_contains($search, $symptom)) {
                        foreach ($specialists as $specialist) {
                            $q->orWhere('specialization', 'like', '%' . $specialist . '%');
                        }
                    }
                }
            });
        }

        $doctors = $query->latest()->get();

        return view('patient.doctors', compact('doctors', 'search'));
    }

    public function appointments()
    {
        $patient = Patient::where('email', auth()->user()->email)->first();

        $appointments = Appointment::with(['patient', 'doctor'])
            ->when($patient, function ($q) use ($patient) {
                $q->where('patient_id', $patient->id);
            })
            ->latest()
            ->get();

        return view('patient.appointments', compact('appointments'));
    }

    public function cancel(Appointment $appointment)
    {
        $appointment->update([
            'status' => 'Cancelled',
        ]);

        return back()->with('success', 'Appointment Cancelled Successfully');
    }
}