<?php

namespace App\Http\Controllers;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DoctorController extends Controller
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

    private function approvedDoctorQuery()
    {
        return Doctor::query()
            ->with('user')
            ->where('verification_status', 'approved')
            ->where(function ($query) {
                $query->whereHas('user', function ($userQuery) {
                    $userQuery->where('status', 'active');
                })
                ->orWhereDoesntHave('user');
            });
    }

    private function symptomSpecialistMap(): array
    {
        return [
            'cardiology' => [
                'heart', 'cardio', 'cardi', 'chest', 'chest pain',
                'বুক', 'বুক ব্যথা', 'হৃদ', 'হার্ট'
            ],

            'neurology' => [
                'brain', 'head', 'headache', 'মাথা', 'মাথা ব্যথা',
                'neuro', 'migraine', 'স্নায়ু'
            ],

            'dermatology' => [
                'skin', 'চামড়া', 'চর্ম', 'rash', 'allergy', 'derma'
            ],

            'ophthalmologist' => [
                'eye', 'eyes', 'চোখ', 'vision', 'sight'
            ],

            'medicine' => [
                'fever', 'cold', 'cough', 'জ্বর', 'কাশি', 'general',
                'general physician'
            ],

            'pediatrician' => [
                'child', 'baby', 'kids', 'শিশু', 'children'
            ],

            'orthopedic' => [
                'bone', 'joint', 'হাড়', 'back pain', 'knee', 'leg pain'
            ],

            'dentist' => [
                'tooth', 'teeth', 'দাঁত', 'tooth pain', 'dental'
            ],

            'ent specialist' => [
                'ear', 'nose', 'throat', 'কান', 'নাক', 'গলা', 'ent'
            ],

            'gynecologist' => [
                'pregnancy', 'period', 'গর্ভ', 'women', 'female'
            ],

            'nephrologist' => [
                'kidney', 'কিডনি'
            ],

            'endocrinologist' => [
                'diabetes', 'ডায়াবেটিস', 'thyroid'
            ],

            'gastroenterologist' => [
                'stomach', 'পেট', 'gas', 'acidity', 'gastric'
            ],
        ];
    }

    private function matchedSpecialists(string $search): array
    {
        $matched = [];

        foreach ($this->symptomSpecialistMap() as $specialist => $keywords) {
            foreach ($keywords as $word) {
                if (str_contains($search, strtolower($word))) {
                    $matched[] = $specialist;
                }
            }
        }

        return array_values(array_unique($matched));
    }

    private function rememberDoctorView(int $doctorId): void
    {
        $recent = session()->get('recent_viewed_doctors', []);

        $recent = array_values(array_filter($recent, function ($id) use ($doctorId) {
            return (int) $id !== (int) $doctorId;
        }));

        array_unshift($recent, $doctorId);

        session()->put('recent_viewed_doctors', array_slice($recent, 0, 10));
    }
        private function orderByRecentViewed($query)
    {
        $recentIds = session()->get('recent_viewed_doctors', []);

        if (!empty($recentIds)) {
            $ids = implode(',', array_map('intval', $recentIds));

            $query->orderByRaw("FIELD(id, {$ids}) DESC");
        }

        return $query;
    }

    public function index(Request $request)
{
    $query = Doctor::with('user')->latest();

    if ($request->filled('search')) {
        $search = trim($request->search);

        $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhere('phone', 'LIKE', "%{$search}%")
                ->orWhere('specialist', 'LIKE', "%{$search}%")
                ->orWhere('specialization', 'LIKE', "%{$search}%")
                ->orWhere('verification_status', 'LIKE', "%{$search}%");
        });
    }

    if ($request->filled('specialty')) {
        $specialty = trim($request->specialty);

        $query->where(function ($q) use ($specialty) {
            $q->where('specialist', $specialty)
                ->orWhere('specialization', $specialty);
        });
    }

    if ($request->filled('status')) {
        $query->where('verification_status', $request->status);
    }

    $specialties = Doctor::query()
    ->select('specialist')
    ->whereNotNull('specialist')
    ->where('specialist', '!=', '')
    ->pluck('specialist')
    ->merge(
        Doctor::query()
            ->select('specialization')
            ->whereNotNull('specialization')
            ->where('specialization', '!=', '')
            ->pluck('specialization')
    )
    ->map(fn ($value) => trim($value))
    ->filter()
    ->unique()
    ->sort()
    ->values();

    $totalDoctors = Doctor::count();
    $approvedDoctors = Doctor::where('verification_status', 'approved')->count();
    $pendingDoctors = Doctor::where('verification_status', 'pending')->count();
    $rejectedDoctors = Doctor::where('verification_status', 'rejected')->count();

    $doctors = $query->paginate(25)->withQueryString();

    return view('doctors.index', compact(
        'doctors',
        'specialties',
        'totalDoctors',
        'approvedDoctors',
        'pendingDoctors',
        'rejectedDoctors'
    ));
}

    public function findDoctors(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Patient Side Find Doctors
        |--------------------------------------------------------------------------
        | Only 10 approved + active doctors will be visible as suggestions.
        | Search result also returns top 10 relevant doctors only.
        | Recent viewed doctors will get priority when no search is used.
        |--------------------------------------------------------------------------
        */

        $query = $this->approvedDoctorQuery();

        if ($request->filled('search')) {
            $search = strtolower(trim($request->search));
            $matched = $this->matchedSpecialists($search);

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

            $doctors = $query
                ->latest()
                ->limit(10)
                ->get();
        } else {
            $doctors = $this->orderByRecentViewed($query)
                ->latest()
                ->limit(10)
                ->get();
        }

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

            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'cv' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png,webp', 'max:10240'],
        ]);

        $email = strtolower($request->email);

        $profilePhotoPath = null;
        $cvPath = null;

        if ($request->hasFile('profile_photo')) {
            $profilePhotoPath = $this->encryptUpload(
                $request->file('profile_photo'),
                'private/doctor-photos/user-new',
                'doctor_photo'
            );
        }

        if ($request->hasFile('cv')) {
            $cvPath = $this->encryptUpload(
                $request->file('cv'),
                'private/doctor-cvs',
                'doctor_cv'
            );
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $email,
            'email_verified_at' => now(),
            'password' => Hash::make($request->password),
            'role' => 'doctor',
            'status' => 'active',
            'profile_photo' => $profilePhotoPath,
        ]);
                if ($profilePhotoPath) {
            $newPath = str_replace('user-new', 'user-' . $user->id, $profilePhotoPath);

            Storage::disk($this->disk)->makeDirectory(dirname($newPath));
            Storage::disk($this->disk)->move($profilePhotoPath, $newPath);

            $profilePhotoPath = $newPath;

            $user->update([
                'profile_photo' => $profilePhotoPath,
            ]);
        }

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
            'profile_photo' => $profilePhotoPath,
            'cv' => $cvPath,
            'verification_status' => 'approved',
        ]);

        return redirect()
            ->route('doctors.index')
            ->with('success', 'Doctor account created successfully.');
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

            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'cv' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png,webp', 'max:10240'],
        ]);

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

        $profilePhotoPath = $doctor->profile_photo;
        $cvPath = $doctor->cv;

        if ($request->hasFile('profile_photo')) {
            $this->deletePrivateFile($profilePhotoPath);

            $profilePhotoPath = $this->encryptUpload(
                $request->file('profile_photo'),
                'private/doctor-photos/user-' . $doctor->user_id,
                'doctor_photo'
            );
        }

        if ($request->hasFile('cv')) {
            $this->deletePrivateFile($cvPath);

            $cvPath = $this->encryptUpload(
                $request->file('cv'),
                'private/doctor-cvs',
                'doctor_cv'
            );
        }

        $doctor->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'specialist' => $request->specialist,
            'specialization' => $request->specialist,
            'degree' => $request->degree,
            'qualification' => $request->qualification,
            'experience' => $request->experience ?? 0,

            'chambers' => $chambers,
            'chamber_address' => collect($chambers)->pluck('address')->implode("\n"),

            'profile_photo' => $profilePhotoPath,
            'cv' => $cvPath,
        ]);

        if ($doctor->user) {
            $doctor->user->update([
                'name' => $request->name,
                'profile_photo' => $profilePhotoPath,
            ]);
        }

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Doctor profile, encrypted photo, encrypted CV, and chambers updated successfully.');
    }

    public function updateSchedule(Request $request)
    {
        $request->validate([
            'chamber_addresses' => ['nullable', 'string'],
            'working_days' => ['required', 'array'],
            'start_time' => ['required'],
            'end_time' => ['required', 'after:start_time'],
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
            'cv' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png,webp', 'max:10240'],
        ]);

        $doctor = Doctor::where('user_id', auth()->id())
            ->orWhere('email', auth()->user()->email)
            ->first();

        if (!$doctor) {
            return back()->with('error', 'Doctor profile not found.');
        }

        $photoPath = $doctor->profile_photo;
        $cvPath = $doctor->cv;

        if ($request->hasFile('profile_photo')) {
            $this->deletePrivateFile($photoPath);

            $photoPath = $this->encryptUpload(
                $request->file('profile_photo'),
                'private/doctor-photos/user-' . auth()->id(),
                'doctor_photo'
            );
        }

        if ($request->hasFile('cv')) {
            $this->deletePrivateFile($cvPath);

            $cvPath = $this->encryptUpload(
                $request->file('cv'),
                'private/doctor-cvs',
                'doctor_cv'
            );
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
            'cv' => $cvPath,
        ]);

        if ($doctor->user) {
            $doctor->user->update([
                'profile_photo' => $photoPath,
            ]);
        }

        return back()->with('success', 'Doctor profile updated with encrypted files successfully.');
    }

    public function approve(Doctor $doctor)
    {
        $doctor->update([
            'verification_status' => 'approved',
        ]);

        if ($doctor->user) {
            $doctor->user->update([
                'status' => 'active',
            ]);
        }

        return back()->with('success', 'Doctor approved successfully.');
    }

    public function reject(Doctor $doctor)
    {
        $doctor->update([
            'verification_status' => 'rejected',
        ]);

        if ($doctor->user) {
            $doctor->user->update([
                'status' => 'blocked',
            ]);
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

        $this->rememberDoctorView($doctor->id);

        return view('doctors.public-profile', compact('doctor'));
    }

    public function destroy(Doctor $doctor)
    {
        $this->deletePrivateFile($doctor->profile_photo);
        $this->deletePrivateFile($doctor->cv);

        if ($doctor->user) {
            $this->deletePrivateFile($doctor->user->profile_photo);
            $doctor->user->delete();
        }

        $doctor->delete();

        return redirect()
            ->route('doctors.index')
            ->with('success', 'Doctor deleted successfully.');
    }
}