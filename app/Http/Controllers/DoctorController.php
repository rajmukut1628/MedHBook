<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DoctorController extends Controller
{
    public function index()
    {
        $query = Doctor::with('user')->latest();

        if (request('search')) {
            $search = request('search');

            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%")
                    ->orWhere('specialist', 'LIKE', "%{$search}%")
                    ->orWhere('specialization', 'LIKE', "%{$search}%")
                    ->orWhere('verification_status', 'LIKE', "%{$search}%");
            });
        }

        $doctors = $query->paginate(9);

        return view('doctors.index', compact('doctors'));
    }

    public function findDoctors(Request $request)
    {
        $query = Doctor::where('verification_status', 'approved');

        if ($request->filled('search')) {
            $search = strtolower(trim($request->search));

            $map = [
                'cardiology' => ['heart', 'cardio', 'cardi', 'chest', 'বুক', 'হৃদ', 'হার্ট'],
                'neurology' => ['brain', 'head', 'মাথা', 'neuro', 'migraine', 'স্নায়ু'],
                'dermatology' => ['skin', 'চামড়া', 'চর্ম', 'rash', 'derma'],
                'ophthalmologist' => ['eye', 'চোখ', 'vision'],
                'medicine' => ['fever', 'cold', 'জ্বর', 'কাশি', 'general'],
                'pediatrician' => ['child', 'baby', 'kids', 'শিশু'],
                'orthopedic' => ['bone', 'joint', 'হাড়', 'back pain'],
                'dentist' => ['tooth', 'teeth', 'দাঁত', 'tooth pain'],
                'ent specialist' => ['ear', 'nose', 'throat', 'কান', 'নাক', 'গলা'],
                'gynecologist' => ['pregnancy', 'period', 'গর্ভ'],
                'nephrologist' => ['kidney', 'কিডনি'],
                'endocrinologist' => ['diabetes', 'ডায়াবেটিস'],
                'gastroenterologist' => ['stomach', 'পেট', 'gas', 'acidity'],
            ];

            $matched = [];

            foreach ($map as $specialist => $keywords) {
                foreach ($keywords as $word) {
                    if (str_contains($search, $word)) {
                        $matched[] = $specialist;
                    }
                }
            }

            $query->where(function ($q) use ($search, $matched) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%")
                    ->orWhere('specialist', 'LIKE', "%{$search}%")
                    ->orWhere('specialization', 'LIKE', "%{$search}%")
                    ->orWhere('chamber_address', 'LIKE', "%{$search}%");

                if (!empty($matched)) {
                    foreach ($matched as $sp) {
                        $q->orWhere('specialist', 'LIKE', "%{$sp}%")
                            ->orWhere('specialization', 'LIKE', "%{$sp}%");
                    }
                }
            });
        }

        $doctors = $query->latest()->get();

        return view('patient.find-doctors', compact('doctors'));
    }

    public function create()
    {
        return view('doctors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email', 'unique:doctors,email'],
            'phone' => ['required', 'string', 'max:20'],
            'specialist' => ['required', 'string', 'max:255'],
            'degree' => ['nullable', 'string', 'max:255'],
            'qualification' => ['nullable', 'string', 'max:255'],
            'experience' => ['nullable', 'integer', 'min:0'],
            'license_number' => ['nullable', 'string', 'max:255'],
            'chamber_address' => ['nullable', 'string'],
            'password' => ['required', 'min:6'],
        ]);

        $email = strtolower($request->email);

        $user = User::create([
            'name' => $request->name,
            'email' => $email,
            'email_verified_at' => now(),
            'password' => Hash::make($request->password),
            'role' => 'doctor',
            'status' => 'active',
        ]);

        Doctor::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'email' => $email,
            'phone' => $request->phone,
            'specialist' => $request->specialist,
            'specialization' => $request->specialist,
            'degree' => $request->degree,
            'qualification' => $request->qualification,
            'experience' => $request->experience ?? 0,
            'license_number' => $request->license_number,
            'chamber_address' => $request->chamber_address,
            'verification_status' => 'approved',
        ]);

        return redirect()->route('doctors.index')
            ->with('success', 'Doctor account created successfully. Doctor can login using email and password.');
    }

    public function show(Doctor $doctor)
    {
        $doctor->load('user');

        return view('doctors.show', compact('doctor'));
    }

    public function edit(Doctor $doctor)
    {
        $doctor->load('user');

        return view('doctors.edit', compact('doctor'));
    }

    public function update(Request $request, Doctor $doctor)
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'phone' => ['required', 'string', 'max:20'],
        'specialist' => ['required', 'string', 'max:255'],
        'degree' => ['nullable', 'string', 'max:255'],
        'qualification' => ['nullable', 'string', 'max:255'],
        'experience' => ['nullable', 'integer', 'min:0'],
    ]);

    /*
    |--------------------------------------------------------------------------
    | MULTI CHAMBER PROCESSING 🔥
    |--------------------------------------------------------------------------
    */

    $chambers = [];

    if ($request->has('chambers')) {
        foreach ($request->chambers as $chamber) {

            if (!empty($chamber['address'])) {
                $chambers[] = [
                    'address' => $chamber['address'],
                    'working_days' => $chamber['working_days'] ?? [],
                    'start_time' => $chamber['start_time'] ?? null,
                    'end_time' => $chamber['end_time'] ?? null,
                    'fee' => $chamber['fee'] ?? 0,
                ];
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SAVE DATA
    |--------------------------------------------------------------------------
    */

    $doctor->update([
        'name' => $request->name,
        'phone' => $request->phone,
        'specialist' => $request->specialist,
        'specialization' => $request->specialist,
        'degree' => $request->degree,
        'qualification' => $request->qualification,
        'experience' => $request->experience ?? 0,

        // 🔥 NEW FIELD (JSON)
        'chambers' => $chambers,

        // backward compatibility
        'chamber_address' => collect($chambers)->pluck('address')->implode("\n"),
    ]);

    if ($doctor->user) {
        $doctor->user->update([
            'name' => $request->name,
        ]);
    }

    return redirect()->route('profile.edit')
    ->with('success', 'Doctor profile and chambers updated successfully.');
}

    public function updateSchedule(Request $request)
    {
        $request->validate([
            'chamber_addresses' => ['nullable', 'string'],
            'working_days' => ['required', 'array'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'consultation_fee' => ['required', 'numeric', 'min:0'],
        ]);

        $doctor = Doctor::where('user_id', auth()->id())
            ->orWhere('email', auth()->user()->email)
            ->first();

        if (!$doctor) {
            return back()->with('error', 'Doctor profile not found.');
        }

        $doctor->update([
            'chamber_address' => $request->chamber_addresses,
            'working_days' => $request->working_days,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'consultation_fee' => $request->consultation_fee,
        ]);

        return back()->with('success', 'Schedule updated successfully.');
    }

    public function updateMyProfile(Request $request)
    {
        $request->validate([
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', 'string', 'max:50'],
            'blood_group' => ['nullable', 'string', 'max:10'],
            'specialist' => ['required', 'string', 'max:255'],
            'degree' => ['nullable', 'string', 'max:255'],
            'qualification' => ['nullable', 'string', 'max:255'],
            'experience' => ['nullable', 'integer', 'min:0'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $doctor = Doctor::where('user_id', auth()->id())
            ->orWhere('email', auth()->user()->email)
            ->first();

        if (!$doctor) {
            return back()->with('error', 'Doctor profile not found.');
        }

        $photoPath = $doctor->profile_photo;

        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('doctor-profiles', 'public');
        }

        $doctor->update([
            'phone' => $request->phone,
            'gender' => $request->gender,
            'blood_group' => $request->blood_group,
            'specialist' => $request->specialist,
            'specialization' => $request->specialist,
            'degree' => $request->degree,
            'qualification' => $request->qualification,
            'experience' => $request->experience ?? 0,
            'bio' => $request->bio,
            'profile_photo' => $photoPath,
        ]);

        return back()->with('success', 'Doctor profile updated successfully.');
    }

    public function approve(Doctor $doctor)
    {
        $doctor->update(['verification_status' => 'approved']);

        if ($doctor->user) {
            $doctor->user->update(['status' => 'active']);
        }

        return back()->with('success', 'Doctor approved successfully.');
    }

    public function reject(Doctor $doctor)
    {
        $doctor->update(['verification_status' => 'rejected']);

        if ($doctor->user) {
            $doctor->user->update(['status' => 'blocked']);
        }

        return back()->with('success', 'Doctor rejected successfully.');
    }

    public function block(Doctor $doctor)
    {
        return $this->reject($doctor);
    }

    public function publicProfile(Doctor $doctor)
    {
        $doctor->load('user');

        return view('doctors.public-profile', compact('doctor'));
    }

    public function destroy(Doctor $doctor)
    {
        if ($doctor->user) {
            $doctor->user->delete();
        }

        $doctor->delete();

        return redirect()->route('doctors.index')->with('success', 'Doctor deleted successfully.');
    }
}