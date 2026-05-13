<?php

use App\Http\Controllers\PrivateFileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MedicalDocumentController;
use App\Http\Controllers\DoctorDashboardController;
use App\Http\Controllers\SocialLoginController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\PatientSettingsController;
use App\Http\Controllers\FirebaseAuthController;

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Google / Firebase Login
|--------------------------------------------------------------------------
*/

Route::get('/auth/google/{role?}', [SocialLoginController::class, 'redirectToGoogle'])
    ->name('google.redirect');

Route::get('/auth/google/callback', [SocialLoginController::class, 'handleGoogleCallback'])
    ->name('google.callback');

Route::post('/firebase/google-login', [FirebaseAuthController::class, 'login'])
    ->name('firebase.google.login');

Route::get('/google-config-test', function () {
    dd(config('services.google'));
});

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'check.status'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard Redirect Logic
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->role === 'super_admin') {
            return redirect()->route('superadmin.dashboard');
        }

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'doctor') {
            return redirect()->route('doctor.dashboard');
        }

        return redirect()->route('patient.dashboard');
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Profile Settings Pages
    |--------------------------------------------------------------------------
    */

    Route::get('/profile/doctor/edit', function () {
        return view('profile.doctor-edit');
    })->middleware('role:doctor')->name('profile.doctor.edit');

    Route::get('/profile/info', function () {
        return view('profile.edit-info', [
            'user' => auth()->user(),
        ]);
    })->name('profile.info.edit');

    Route::get('/profile/password', function () {
        return view('profile.change-password');
    })->name('profile.password.edit');

    Route::get('/profile/delete', function () {
        return view('profile.delete');
    })->name('profile.delete.confirm');

    /*
    |--------------------------------------------------------------------------
    | Doctor Public Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/doctor-profile/{doctor}', [DoctorController::class, 'publicProfile'])
        ->name('doctor.public.profile');

    /*
    |--------------------------------------------------------------------------
    | Main Resources
    |--------------------------------------------------------------------------
    */

    Route::resource('patients', PatientController::class);
    Route::resource('doctors', DoctorController::class);
    Route::resource('appointments', AppointmentController::class);
    Route::resource('prescriptions', PrescriptionController::class);


    Route::get('/prescription/patient-search/live', [PrescriptionController::class, 'livePatientSearch'])
    ->middleware('role:doctor,admin,super_admin')
    ->name('prescriptions.patient.search');

Route::post('/prescription/patient-verify-privacy', [PrescriptionController::class, 'verifyPatientPrivacyForPrescription'])
    ->middleware('role:doctor,admin,super_admin')
    ->name('prescriptions.patient.verify');
        /*
    |--------------------------------------------------------------------------
    | Patient Only Medical Documents Routes
    |--------------------------------------------------------------------------
    | Important:
    | Medical Documents feature is ONLY for patient accounts.
    | Admin, Super Admin, and Doctor cannot access these routes.
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:patient'])->group(function () {
        Route::resource('medical-documents', MedicalDocumentController::class);

        Route::get('/medical-documents/{medicalDocument}/download', [MedicalDocumentController::class, 'download'])
            ->name('medical-documents.download');

        Route::get('/medical-documents/{medicalDocument}/metadata', [MedicalDocumentController::class, 'metadata'])
            ->name('medical-documents.metadata');
    });

    /*
    |--------------------------------------------------------------------------
    | Secure Private File Routes
    |--------------------------------------------------------------------------
    | Login required. Individual controller logic handles path/security.
    |--------------------------------------------------------------------------
    */

    Route::get('/secure-file/{folder}/{filename}', [PrivateFileController::class, 'show'])
        ->where('filename', '.*')
        ->name('secure.file.show');

    Route::get('/secure-file-download/{folder}/{filename}', [PrivateFileController::class, 'download'])
        ->where('filename', '.*')
        ->name('secure.file.download');

    /*
    |--------------------------------------------------------------------------
    | Prescription Secure Download
    |--------------------------------------------------------------------------
    */

    Route::get('/prescriptions/{prescription}/download', [PrescriptionController::class, 'downloadPdf'])
        ->name('prescriptions.download');

    /*
    |--------------------------------------------------------------------------
    | Patient Routes
    |--------------------------------------------------------------------------
    */

    Route::get('/find-doctors', [DoctorController::class, 'findDoctors'])
        ->middleware('role:patient')
        ->name('find.doctors');

    Route::post('/patient/privacy-key/regenerate', [PatientController::class, 'regeneratePrivacyKey'])
        ->middleware('role:patient')
        ->name('patient.privacy-key.regenerate');

    Route::get('/patient/settings', [PatientSettingsController::class, 'index'])
        ->middleware('role:patient')
        ->name('patient.settings');

    Route::get('/patient/dashboard', function () {
        $patient = Patient::where('user_id', auth()->id())->first();

        return view('patient.dashboard', [
            'patient' => $patient,
            'privacyKey' => $patient ? $patient->privacyKey() : null,
        ]);
    })->middleware('role:patient')->name('patient.dashboard');

    Route::get('/patient/my-profile', [PatientController::class, 'myProfile'])
        ->middleware('role:patient')
        ->name('patient.my-profile');

    Route::post('/patient/my-profile', [PatientController::class, 'updateMyProfile'])
        ->middleware('role:patient')
        ->name('patient.my-profile.update');
            /*
    |--------------------------------------------------------------------------
    | Doctor Routes
    |--------------------------------------------------------------------------
    */

    Route::get('/doctor/dashboard', [DoctorDashboardController::class, 'dashboard'])
        ->middleware('role:doctor')
        ->name('doctor.dashboard');

    Route::get('/doctor/patients', [DoctorDashboardController::class, 'patients'])
        ->middleware('role:doctor')
        ->name('doctor.patients');

    Route::get('/doctor/overview', [DoctorDashboardController::class, 'overview'])
        ->middleware('role:doctor')
        ->name('doctor.overview');

    Route::get('/doctor/search-patient', [DoctorDashboardController::class, 'searchPatient'])
        ->middleware('role:doctor')
        ->name('doctor.search.patient');

    Route::post('/doctor/patient/{patient}/verify-privacy', [DoctorDashboardController::class, 'verifyPatientPrivacy'])
        ->middleware('role:doctor')
        ->name('doctor.patient.verify.privacy');
        
        Route::get('/doctor/patient/{patient}/profile', [DoctorDashboardController::class, 'verifiedPatientProfile'])
    ->middleware('role:doctor')
    ->name('doctor.patient.profile');

Route::get('/doctor/patient/{patient}/medical-documents', [DoctorDashboardController::class, 'verifiedPatientDocuments'])
    ->middleware('role:doctor')
    ->name('doctor.patient.medical-documents');

    Route::post('/doctor/my-profile/update', [DoctorController::class, 'updateMyProfile'])
        ->middleware('role:doctor')
        ->name('doctor.my-profile.update');

    Route::post('/doctor/schedule/update', [DoctorController::class, 'updateSchedule'])
        ->middleware('role:doctor')
        ->name('doctor.schedule.update');

    /*
    |--------------------------------------------------------------------------
    | Appointment Actions
    |--------------------------------------------------------------------------
    */

    Route::patch('/appointments/{appointment}/approve', [AppointmentController::class, 'approve'])
        ->name('appointments.approve');

    Route::patch('/appointments/{appointment}/reject', [AppointmentController::class, 'reject'])
        ->name('appointments.reject');

    Route::patch('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])
        ->name('appointments.cancel');

    Route::patch('/appointments/{appointment}/reschedule', [AppointmentController::class, 'reschedule'])
        ->name('appointments.reschedule');

    /*
    |--------------------------------------------------------------------------
    | Admin Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard', [
            'patients' => Patient::count(),
            'doctors' => Doctor::count(),
        ]);
    })->middleware('role:admin')->name('admin.dashboard');

    /*
    |--------------------------------------------------------------------------
    | Admin / Super Admin Controls
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin,super_admin')->group(function () {

        Route::post('/doctors/{doctor}/approve', [DoctorController::class, 'approve'])
            ->name('doctors.approve');

        Route::post('/doctors/{doctor}/reject', [DoctorController::class, 'reject'])
            ->name('doctors.reject');

        Route::patch('/admin/users/{user}/suspend', function (Request $request, User $user) {

            $request->validate([
                'duration_type'  => 'required|in:days,months',
                'duration'       => 'required|integer|min:1|max:365',
                'suspend_reason' => 'nullable|string|max:1000',
            ]);

            if (!in_array($user->role, ['patient', 'doctor'])) {
                return back()->with('error', 'Only patient or doctor can be suspended.');
            }

            $suspendedUntil = $request->duration_type === 'days'
                ? now()->addDays((int) $request->duration)
                : now()->addMonths((int) $request->duration);

            $user->update([
                'status' => 'suspended',
                'suspended_until' => $suspendedUntil,
                'suspend_reason' => $request->suspend_reason,
            ]);

            return back()->with('success', 'User suspended successfully.');
        })->name('admin.users.suspend');

        Route::patch('/admin/users/{user}/unsuspend', function (User $user) {

            if (!in_array($user->role, ['patient', 'doctor'])) {
                return back()->with('error', 'Only patient or doctor can be unsuspended.');
            }

            $user->update([
                'status' => 'active',
                'suspended_until' => null,
                'suspend_reason' => null,
            ]);

            return back()->with('success', 'User unsuspended successfully.');
        })->name('admin.users.unsuspend');
    });
        /*
    |--------------------------------------------------------------------------
    | Super Admin Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:super_admin')->group(function () {

        Route::get('/superadmin/dashboard', function () {
            return view('superadmin.dashboard', [
                'users' => User::count(),
                'admins' => User::where('role', 'admin')->count(),
                'patients' => User::where('role', 'patient')->count(),
                'doctors' => User::where('role', 'doctor')->count(),
            ]);
        })->name('superadmin.dashboard');

        Route::get('/superadmin/admins', function () {
            $admins = User::where('role', 'admin')->latest()->get();
            return view('superadmin.admins', compact('admins'));
        })->name('superadmin.admins');

        Route::get('/superadmin/admins/create', function () {
            return view('superadmin.create-admin');
        })->name('superadmin.admin.create');

        Route::post('/superadmin/admins', function (Request $request) {

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
                'status' => 'required|in:active,inactive,blocked',
            ]);

            User::create([
                'name' => $request->name,
                'email' => strtolower($request->email),
                'email_verified_at' => now(),
                'password' => Hash::make($request->password),
                'role' => 'admin',
                'status' => $request->status,
            ]);

            return redirect()->route('superadmin.admins')
                ->with('success', 'Admin created successfully.');
        })->name('superadmin.admin.store');

        Route::get('/superadmin/admins/{id}/edit', function ($id) {
            $admin = User::where('role', 'admin')->findOrFail($id);
            return view('superadmin.edit-admin', compact('admin'));
        })->name('superadmin.admin.edit');

        Route::put('/superadmin/admins/{id}', function (Request $request, $id) {

            $admin = User::where('role', 'admin')->findOrFail($id);

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $admin->id,
                'status' => 'required|in:active,inactive,blocked',
                'password' => 'nullable|string|min:6|confirmed',
            ]);

            $data = [
                'name' => $request->name,
                'email' => strtolower($request->email),
                'status' => $request->status,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $admin->update($data);

            return redirect()->route('superadmin.admins')
                ->with('success', 'Admin updated successfully.');
        })->name('superadmin.admin.update');

        Route::delete('/superadmin/admins/{id}', function ($id) {

            $admin = User::where('role', 'admin')->findOrFail($id);
            $admin->delete();

            return redirect()->route('superadmin.admins')
                ->with('success', 'Admin deleted successfully.');
        })->name('superadmin.admin.delete');
    });

    /*
    |--------------------------------------------------------------------------
    | Breeze Profile Core Routes
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

}); // END AUTH GROUP

require __DIR__ . '/auth.php';