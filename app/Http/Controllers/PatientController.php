<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PatientController extends Controller
{
    private string $disk = 'local';

    private function encryptUpload($file, string $folder, string $prefix): string
    {
        $fileName = $prefix . '_' . time() . '_' . Str::random(20) . '.mhb';
        $path = $folder . '/' . $fileName;

        $content = file_get_contents($file->getRealPath());
        $encrypted = Crypt::encryptString(base64_encode($content));

        Storage::disk($this->disk)->put($path, $encrypted);

        return $path;
    }

    private function deletePrivateFile(?string $path): void
    {
        if ($path && Storage::disk($this->disk)->exists($path)) {
            Storage::disk($this->disk)->delete($path);
        }
    }

    public function index(Request $request)
    {
        $query = Patient::query()
            ->with('user')
            ->latest('id');

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%")
                    ->orWhere('gender', 'LIKE', "%{$search}%")
                    ->orWhere('blood_group', 'LIKE', "%{$search}%")
                    ->orWhere('privacy_key', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%")
                            ->orWhere('status', 'LIKE', "%{$search}%");
                    });

                if (preg_match('/^P(\d+)$/i', $search, $matches)) {
                    $q->orWhere('id', $matches[1]);
                }
            });
        }

        if ($request->filled('status')) {
            $query->whereHas('user', function ($userQuery) use ($request) {
                $userQuery->where('status', $request->status);
            });
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $totalPatients = Patient::count();

        $activePatients = Patient::whereHas('user', function ($q) {
            $q->where('status', 'active');
        })->count();

        $suspendedPatients = Patient::whereHas('user', function ($q) {
            $q->where('status', 'suspended');
        })->count();

        $blockedPatients = Patient::whereHas('user', function ($q) {
            $q->whereIn('status', ['blocked', 'inactive']);
        })->count();

        $patients = $query
            ->paginate(25)
            ->withQueryString();

        return view('patients.index', compact(
            'patients',
            'totalPatients',
            'activePatients',
            'suspendedPatients',
            'blockedPatients'
        ));
    }
        public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email|unique:patients,email',
            'password' => 'required|string|min:6',
            'phone'    => 'nullable|string|max:30',
            'age'      => 'nullable|integer|min:0|max:120',
            'gender'   => 'nullable|string|max:30',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => strtolower($request->email),
            'email_verified_at' => now(),
            'password' => Hash::make($request->password),
            'role' => 'patient',
            'status' => 'active',
        ]);

        Patient::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'email' => strtolower($request->email),
            'phone' => $request->phone,
            'age' => $request->age,
            'gender' => $request->gender,
            'privacy_key' => strtoupper('PH-' . $user->id . '-' . substr(md5(uniqid()), 0, 6)),
        ]);

        return redirect()->route('patients.index')
            ->with('success', 'Patient account created successfully. Patient can login using email and password.');
    }

    public function show($id)
    {
        $patient = Patient::with('user')->findOrFail($id);
        $search = request('search');

        $doctors = Doctor::query()
            ->when($search, function ($query) use ($search) {
                $keywordMap = [
                    'heart' => 'Cardiology',
                    'cardio' => 'Cardiology',
                    'হৃদরোগ' => 'Cardiology',
                    'হৃদ' => 'Cardiology',
                    'neuro' => 'Neurology',
                    'brain' => 'Neurology',
                    'head' => 'Neurology',
                    'মাথা' => 'Neurology',
                    'স্নায়ু' => 'Neurology',
                    'skin' => 'Dermatology',
                    'চর্ম' => 'Dermatology',
                    'child' => 'Pediatrics',
                    'শিশু' => 'Pediatrics',
                ];

                $mappedSpecialty = $keywordMap[strtolower($search)] ?? $search;

                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('specialization', 'LIKE', "%{$search}%")
                    ->orWhere('specialization', 'LIKE', "%{$mappedSpecialty}%");
            })
            ->latest()
            ->get();

        return view('patients.show', compact('patient', 'doctors'));
    }

    public function edit($id)
    {
        $patient = Patient::with('user')->findOrFail($id);

        return view('patients.edit', compact('patient'));
    }
        public function update(Request $request, $id)
    {
        $patient = Patient::with('user')->findOrFail($id);

        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'nullable|email|unique:patients,email,' . $patient->id,
            'phone'  => 'nullable|string|max:30',
            'age'    => 'nullable|integer|min:0|max:120',
            'gender' => 'nullable|string|max:30',
        ]);

        $patient->update([
            'name' => $request->name,
            'email' => $request->email ? strtolower($request->email) : $patient->email,
            'phone' => $request->phone,
            'age' => $request->age,
            'gender' => $request->gender,
        ]);

        if ($patient->user) {
            $patient->user->update([
                'name' => $request->name,
                'email' => $request->email ? strtolower($request->email) : $patient->user->email,
            ]);
        }

        return redirect()->route('patients.index')
            ->with('success', 'Patient updated successfully.');
    }

    public function myProfile()
    {
        $patient = Patient::firstOrCreate(
            ['user_id' => auth()->id()],
            [
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ]
        );

        return view('patient.my-profile', compact('patient'));
    }

    public function updateMyProfile(Request $request)
    {
        $patient = Patient::firstOrCreate(
            ['user_id' => auth()->id()],
            [
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ]
        );

        $request->validate([
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'age' => ['nullable', 'integer', 'min:0', 'max:120'],
            'gender' => ['nullable', 'string', 'max:30'],
            'blood_group' => ['nullable', 'string', 'max:10'],
            'address' => ['nullable', 'string', 'max:500'],
            'has_allergy' => ['nullable', 'boolean'],
            'has_diabetes' => ['nullable', 'boolean'],
            'has_blood_pressure' => ['nullable', 'boolean'],
            'emergency_contact' => ['nullable', 'string', 'max:30'],
        ]);

        $photoPath = $patient->profile_photo;

        if ($request->hasFile('profile_photo')) {
            $this->deletePrivateFile($photoPath);

            $photoPath = $this->encryptUpload(
                $request->file('profile_photo'),
                'private/patient-profiles/user-' . auth()->id(),
                'patient_photo'
            );
        }

        $patient->update([
            'profile_photo' => $photoPath,
            'name' => $request->name,
            'email' => auth()->user()->email,
            'phone' => $request->phone,
            'age' => $request->age,
            'gender' => $request->gender,
            'blood_group' => $request->blood_group,
            'address' => $request->address,
            'has_allergy' => $request->boolean('has_allergy'),
            'has_diabetes' => $request->boolean('has_diabetes'),
            'has_blood_pressure' => $request->boolean('has_blood_pressure'),
            'emergency_contact' => $request->emergency_contact,
        ]);

        auth()->user()->update([
            'name' => $request->name,
            'profile_photo' => $photoPath,
        ]);

        return back()->with('success', 'Patient profile updated successfully.');
    }
        public function regeneratePrivacyKey()
    {
        $patient = Patient::where('user_id', auth()->id())->first();

        if (!$patient) {
            return back()->with('error', 'Patient profile not found.');
        }

        $patient->update([
            'privacy_key' => strtoupper(
                'PH-' . $patient->id . '-' . substr(md5(uniqid()), 0, 6)
            ),
        ]);

        return back()->with('success', 'Privacy key regenerated successfully.');
    }

    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);

        $this->deletePrivateFile($patient->profile_photo);

        if ($patient->user) {
            $this->deletePrivateFile($patient->user->profile_photo);
            $patient->user->delete();
        }

        $patient->delete();

        return redirect()->route('patients.index')
            ->with('success', 'Patient deleted successfully.');
    }
}