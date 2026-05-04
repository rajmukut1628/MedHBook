<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'patient') {
            $patient = Patient::where('user_id', $user->id)
                ->orWhere('email', $user->email)
                ->first();

            $appointments = Appointment::with(['patient', 'doctor'])
                ->where('patient_id', $patient?->id)
                ->latest()
                ->get();

            return view('appointments.index', compact('appointments'));
        }

        if ($user->role === 'doctor') {
            $doctor = Doctor::where('user_id', $user->id)
                ->orWhere('email', $user->email)
                ->first();

            $appointments = Appointment::with(['patient', 'doctor'])
                ->where('doctor_id', $doctor?->id)
                ->latest()
                ->get();

            return view('appointments.index', compact('appointments'));
        }

        $appointments = Appointment::with(['patient', 'doctor'])->latest()->get();

        return view('appointments.index', compact('appointments'));
    }

    public function create(Request $request)
    {
        $selectedDoctor = null;
        $availableSlots = [];
        $bookedSlots = [];

        if ($request->doctor_id) {
            $selectedDoctor = Doctor::findOrFail($request->doctor_id);

            if ($request->appointment_date) {
                $availableSlots = $this->generateAvailableSlots(
                    $selectedDoctor,
                    $request->appointment_date
                );

                $bookedSlots = Appointment::where('doctor_id', $selectedDoctor->id)
                    ->where('appointment_date', $request->appointment_date)
                    ->whereNotIn('status', ['Rejected', 'Cancelled'])
                    ->pluck('appointment_time')
                    ->map(fn ($time) => Carbon::parse($time)->format('H:i'))
                    ->toArray();
            }
        }

        $doctors = Doctor::where('verification_status', 'approved')
            ->latest()
            ->get();

        return view('appointments.create', compact(
            'doctors',
            'selectedDoctor',
            'availableSlots',
            'bookedSlots'
        ));
    }

    public function store(Request $request)
    {
       $request->validate([
    'doctor_id' => ['required', 'exists:doctors,id'],
    'appointment_date' => ['required', 'date'],
    'appointment_time' => ['required'],
    'chamber_address' => ['required', 'string'], 
    'problem' => ['nullable', 'string', 'max:1000'],
]);
        $user = auth()->user();

        if ($user->role !== 'patient') {
            abort(403, 'Only patient can book appointment.');
        }

        $patient = Patient::where('user_id', $user->id)
            ->orWhere('email', $user->email)
            ->first();

        if (!$patient) {
            $patient = Patient::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => 'N/A',
            ]);
        }

        $doctor = Doctor::findOrFail($request->doctor_id);

        $this->validateDoctorSchedule(
            $doctor,
            $request->appointment_date,
            $request->appointment_time
        );

        $selectedTime = Carbon::parse($request->appointment_time)->format('H:i');

        $alreadyBooked = Appointment::where('doctor_id', $doctor->id)
            ->where('appointment_date', $request->appointment_date)
            ->whereTime('appointment_time', $selectedTime)
            ->whereNotIn('status', ['Rejected', 'Cancelled'])
            ->exists();

        if ($alreadyBooked) {
            return back()
                ->withInput()
                ->with('error', 'This time slot is already booked. Please select another slot.');
        }

       Appointment::create([
    'patient_id' => $patient->id,
    'doctor_id' => $doctor->id,
    'appointment_date' => $request->appointment_date,
    'appointment_time' => $selectedTime,
    'chamber_address' => $request->chamber_address, // 🔥 ADD
    'problem' => $request->problem,
    'status' => 'Pending',
]);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment booked successfully. Please wait for doctor approval.');
    }

    public function approve(Appointment $appointment)
    {
        $this->doctorCanUpdate($appointment);

        $appointment->update([
            'status' => 'Approved',
        ]);

        return back()->with('success', 'Appointment approved successfully.');
    }

    public function reject(Appointment $appointment)
    {
        $this->doctorCanUpdate($appointment);

        $appointment->update([
            'status' => 'Rejected',
        ]);

        return back()->with('success', 'Appointment rejected successfully.');
    }

    public function cancel(Appointment $appointment)
    {
        $user = auth()->user();

        if ($user->role === 'patient') {
            $patient = Patient::where('user_id', $user->id)
                ->orWhere('email', $user->email)
                ->first();

            if ($appointment->patient_id !== $patient?->id) {
                abort(403);
            }
        }

        $appointment->update([
            'status' => 'Cancelled',
        ]);

        return back()->with('success', 'Appointment cancelled successfully.');
    }

    public function reschedule(Request $request, Appointment $appointment)
    {
        $this->doctorCanUpdate($appointment);

        $request->validate([
            'appointment_date' => ['required', 'date'],
            'appointment_time' => ['required'],
        ]);

        $doctor = Doctor::findOrFail($appointment->doctor_id);

        $this->validateDoctorSchedule(
            $doctor,
            $request->appointment_date,
            $request->appointment_time
        );

        $selectedTime = Carbon::parse($request->appointment_time)->format('H:i');

        $alreadyBooked = Appointment::where('doctor_id', $doctor->id)
            ->where('appointment_date', $request->appointment_date)
            ->whereTime('appointment_time', $selectedTime)
            ->where('id', '!=', $appointment->id)
            ->whereNotIn('status', ['Rejected', 'Cancelled'])
            ->exists();

        if ($alreadyBooked) {
            return back()
                ->withInput()
                ->with('error', 'This time slot is already booked. Please select another slot.');
        }

        $appointment->update([
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $selectedTime,
            'status' => 'Rescheduled',
        ]);

        return back()->with('success', 'Appointment rescheduled successfully.');
    }

    private function validateDoctorSchedule($doctor, $date, $time)
    {
        $selectedDay = Carbon::parse($date)->format('l');
        $selectedTime = Carbon::parse($time)->format('H:i');

        $workingDays = $doctor->working_days;

        if (is_string($workingDays)) {
            $workingDays = json_decode($workingDays, true);
        }

        if (!$workingDays || !in_array($selectedDay, $workingDays)) {
            abort(422, 'Doctor is not available on selected day.');
        }

        $startTime = $doctor->start_time
            ? Carbon::parse($doctor->start_time)->format('H:i')
            : null;

        $endTime = $doctor->end_time
            ? Carbon::parse($doctor->end_time)->format('H:i')
            : null;

        if (!$startTime || !$endTime) {
            abort(422, 'Doctor schedule time is not set.');
        }

        if ($selectedTime < $startTime || $selectedTime > $endTime) {
            abort(422, 'Doctor available time is ' . $startTime . ' - ' . $endTime);
        }

        $availableSlots = $this->generateAvailableSlots($doctor, $date);

        if (!in_array($selectedTime, $availableSlots)) {
            abort(422, 'Invalid appointment slot selected.');
        }
    }

    private function generateAvailableSlots($doctor, $date)
    {
        $selectedDay = Carbon::parse($date)->format('l');

        $workingDays = $doctor->working_days;

        if (is_string($workingDays)) {
            $workingDays = json_decode($workingDays, true);
        }

        if (!$workingDays || !in_array($selectedDay, $workingDays)) {
            return [];
        }

        if (!$doctor->start_time || !$doctor->end_time) {
            return [];
        }

        $start = Carbon::parse($doctor->start_time);
        $end = Carbon::parse($doctor->end_time);

        $slots = [];

        while ($start <= $end) {
            $slots[] = $start->format('H:i');
            $start->addMinutes(5);
        }

        return $slots;
    }

    private function doctorCanUpdate(Appointment $appointment)
    {
        $user = auth()->user();

        if ($user->role === 'admin' || $user->role === 'super_admin') {
            return true;
        }

        if ($user->role !== 'doctor') {
            abort(403);
        }

        $doctor = Doctor::where('user_id', $user->id)
            ->orWhere('email', $user->email)
            ->first();

        if ($appointment->doctor_id !== $doctor?->id) {
            abort(403);
        }

        return true;
    }
}