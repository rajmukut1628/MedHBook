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
        $selectedChamber = null;
        $availableSlots = [];
        $bookedSlots = [];

        $doctors = Doctor::where('verification_status', 'approved')->latest()->get();

        if ($request->filled('doctor_id')) {
            $selectedDoctor = Doctor::where('verification_status', 'approved')
                ->findOrFail($request->doctor_id);

            $selectedChamber = $this->resolveSelectedChamber(
                $selectedDoctor,
                $request->input('chamber_index'),
                $request->input('chamber_address')
            );

            if ($request->filled('appointment_date') && $selectedChamber) {
                $availableSlots = $this->generateAvailableSlots(
                    $selectedDoctor,
                    $request->appointment_date,
                    $selectedChamber
                );

                $bookedSlots = Appointment::where('doctor_id', $selectedDoctor->id)
                    ->whereDate('appointment_date', $request->appointment_date)
                    ->where('chamber_address', $selectedChamber['address'] ?? $request->input('chamber_address'))
                    ->whereNotIn('status', ['Rejected', 'Cancelled'])
                    ->pluck('appointment_time')
                    ->map(fn ($time) => Carbon::parse($time)->format('H:i'))
                    ->toArray();
            }
        }

        return view('appointments.create', compact(
            'doctors',
            'selectedDoctor',
            'selectedChamber',
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
            'chamber_index' => ['nullable'],
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

        $doctor = Doctor::where('verification_status', 'approved')
            ->find($request->doctor_id);

        if (!$doctor) {
            return back()->withInput()->with('error', 'This doctor is not approved yet.');
        }

        $selectedChamber = $this->resolveSelectedChamber(
            $doctor,
            $request->input('chamber_index'),
            $request->input('chamber_address')
        );

        if (!$selectedChamber) {
            return back()->withInput()->with('error', 'Please select a valid chamber.');
        }

        $this->validateDoctorSchedule(
            $doctor,
            $request->appointment_date,
            $request->appointment_time,
            $selectedChamber
        );

        $selectedTime = Carbon::parse($request->appointment_time)->format('H:i');
        $selectedChamberAddress = $selectedChamber['address'] ?? $request->chamber_address;

        $alreadyBooked = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', $request->appointment_date)
            ->where('chamber_address', $selectedChamberAddress)
            ->whereTime('appointment_time', $selectedTime)
            ->whereNotIn('status', ['Rejected', 'Cancelled'])
            ->exists();

        if ($alreadyBooked) {
            return back()->withInput()->with('error', 'This time slot is already booked. Please select another slot.');
        }

        Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $selectedTime,
            'chamber_address' => $selectedChamberAddress,
            'problem' => $request->problem,
            'status' => 'Pending',
        ]);

        return redirect()
            ->route('appointments.index')
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
            'chamber_address' => ['nullable', 'string'],
            'chamber_index' => ['nullable'],
        ]);

        $doctor = Doctor::where('verification_status', 'approved')
            ->findOrFail($appointment->doctor_id);

        $selectedChamber = $this->resolveSelectedChamber(
            $doctor,
            $request->input('chamber_index'),
            $request->input('chamber_address') ?: $appointment->chamber_address
        );

        if (!$selectedChamber) {
            return back()->withInput()->with('error', 'Please select a valid chamber.');
        }

        $this->validateDoctorSchedule(
            $doctor,
            $request->appointment_date,
            $request->appointment_time,
            $selectedChamber
        );

        $selectedTime = Carbon::parse($request->appointment_time)->format('H:i');
        $selectedChamberAddress = $selectedChamber['address'] ?? $appointment->chamber_address;

        $alreadyBooked = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', $request->appointment_date)
            ->where('chamber_address', $selectedChamberAddress)
            ->whereTime('appointment_time', $selectedTime)
            ->where('id', '!=', $appointment->id)
            ->whereNotIn('status', ['Rejected', 'Cancelled'])
            ->exists();

        if ($alreadyBooked) {
            return back()->withInput()->with('error', 'This time slot is already booked. Please select another slot.');
        }

        $appointment->update([
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $selectedTime,
            'chamber_address' => $selectedChamberAddress,
            'status' => 'Rescheduled',
        ]);

        return back()->with('success', 'Appointment rescheduled successfully.');
    }

    private function resolveSelectedChamber($doctor, $chamberIndex = null, $chamberAddress = null): ?array
    {
        $chambers = $doctor->display_chambers ?? [];

        if (is_string($chambers)) {
            $chambers = json_decode($chambers, true) ?: [];
        }

        if (empty($chambers)) {
            $chambers = [
                [
                    'address' => $doctor->chamber_address ?? 'Not added',
                    'working_days' => $doctor->working_days ?? [],
                    'start_time' => $doctor->start_time ?? null,
                    'end_time' => $doctor->end_time ?? null,
                    'fee' => $doctor->consultation_fee ?? 0,
                ]
            ];
        }

        if ($chamberAddress) {
            foreach ($chambers as $chamber) {
                if (($chamber['address'] ?? '') === $chamberAddress) {
                    return $chamber;
                }
            }
        }

        if ($chamberIndex !== null && $chamberIndex !== '') {
            $index = (int) $chamberIndex;

            if (isset($chambers[$index])) {
                return $chambers[$index];
            }
        }

        return $chambers[0] ?? null;
    }

    private function validateDoctorSchedule($doctor, $date, $time, ?array $selectedChamber = null): void
    {
        $selectedDay = Carbon::parse($date)->format('l');
        $selectedTime = Carbon::parse($time)->format('H:i');

        $workingDays = $selectedChamber['working_days'] ?? $doctor->working_days;

        if (is_string($workingDays)) {
            $workingDays = json_decode($workingDays, true) ?: [];
        }

        if (!$workingDays || !in_array($selectedDay, $workingDays)) {
            abort(422, 'Doctor is not available on selected day for this chamber.');
        }

        $startRaw = $selectedChamber['start_time'] ?? $doctor->start_time;
        $endRaw = $selectedChamber['end_time'] ?? $doctor->end_time;

        $startTime = $startRaw ? Carbon::parse($startRaw)->format('H:i') : null;
        $endTime = $endRaw ? Carbon::parse($endRaw)->format('H:i') : null;

        if (!$startTime || !$endTime) {
            abort(422, 'Doctor schedule time is not set for this chamber.');
        }

        if ($selectedTime < $startTime || $selectedTime >= $endTime) {
            abort(
                422,
                'Doctor available time for this chamber is ' .
                Carbon::parse($startTime)->format('g:i A') .
                ' - ' .
                Carbon::parse($endTime)->format('g:i A')
            );
        }

        $availableSlots = $this->generateAvailableSlots($doctor, $date, $selectedChamber);

        if (!in_array($selectedTime, $availableSlots)) {
            abort(422, 'Invalid appointment slot selected.');
        }
    }

    private function generateAvailableSlots($doctor, $date, ?array $selectedChamber = null): array
    {
        $selectedDay = Carbon::parse($date)->format('l');

        $workingDays = $selectedChamber['working_days'] ?? $doctor->working_days;

        if (is_string($workingDays)) {
            $workingDays = json_decode($workingDays, true) ?: [];
        }

        if (!$workingDays || !in_array($selectedDay, $workingDays)) {
            return [];
        }

        $startRaw = $selectedChamber['start_time'] ?? $doctor->start_time;
        $endRaw = $selectedChamber['end_time'] ?? $doctor->end_time;

        if (!$startRaw || !$endRaw) {
            return [];
        }

        $start = Carbon::parse($startRaw)->second(0);
        $end = Carbon::parse($endRaw)->second(0);

        $slots = [];

        while ($start < $end) {
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