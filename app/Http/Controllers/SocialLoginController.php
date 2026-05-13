<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function redirectToGoogle($role = 'patient')
    {
        if (!in_array($role, ['patient', 'doctor'])) {
            $role = 'patient';
        }

        session()->put('google_register_role', $role);

        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $selectedRole = session()->pull('google_register_role', 'patient');

            if (!in_array($selectedRole, ['patient', 'doctor'])) {
                $selectedRole = 'patient';
            }

            $email = strtolower(trim($googleUser->getEmail()));

            if (!$email) {
                return redirect()
                    ->route('login')
                    ->with('error', 'Google email not found. Please try again.');
            }

            /*
            |--------------------------------------------------------------------------
            | Find existing user safely
            |--------------------------------------------------------------------------
            | Case-insensitive email check to prevent duplicate email issue.
            |--------------------------------------------------------------------------
            */
            $user = User::whereRaw('LOWER(email) = ?', [$email])->first();

            /*
            |--------------------------------------------------------------------------
            | New Google Registration
            |--------------------------------------------------------------------------
            */
            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName() ?: 'Google User',
                    'email' => $email,
                    'email_verified_at' => now(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => Hash::make(Str::random(40)),
                    'role' => $selectedRole,
                    'status' => $selectedRole === 'doctor' ? 'pending' : 'active',
                ]);

                if ($selectedRole === 'patient') {
                    Patient::firstOrCreate(
                        ['user_id' => $user->id],
                        [
                            'name' => $user->name,
                            'email' => $user->email,
                        ]
                    );

                    return redirect()
                        ->route('login')
                        ->with('success', 'Patient registration successful. Your Google email is verified. Please login with Google now.');
                }

                if ($selectedRole === 'doctor') {
                    Doctor::firstOrCreate(
                        ['user_id' => $user->id],
                        [
                            'name' => $user->name,
                            'email' => $user->email,
                            'verification_status' => 'pending',
                        ]
                    );

                    return redirect()
                        ->route('login')
                        ->with('success', 'Doctor registration successful. Your account is pending admin approval. After admin approval, you can login with Google.');
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Existing Google User
            |--------------------------------------------------------------------------
            */
            $user->update([
                'email' => strtolower(trim($user->email)),
                'email_verified_at' => $user->email_verified_at ?: now(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
            ]);

            if ($user->role === 'patient') {
                Patient::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'name' => $user->name,
                        'email' => $user->email,
                    ]
                );
            }

            if ($user->role === 'doctor') {
                Doctor::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'name' => $user->name,
                        'email' => $user->email,
                        'verification_status' => 'pending',
                    ]
                );

                if ($user->status !== 'active') {
                    return redirect()
                        ->route('login')
                        ->with('error', 'Your doctor account is pending admin approval. Please wait until admin approves your account.');
                }
            }

            if ($user->status !== 'active') {
                return redirect()
                    ->route('login')
                    ->with('error', 'Your account is not active. Please contact admin.');
            }

            Auth::login($user, true);
            request()->session()->regenerate();

            return redirect()
                ->route('dashboard')
                ->with('success', 'Google login successful.');

        } catch (\Illuminate\Database\QueryException $e) {
            if ((int) $e->errorInfo[1] === 1062) {
                return redirect()
                    ->route('login')
                    ->with('error', 'This email is already registered. Please login with Google using the same email or use your existing account.');
            }

            return redirect()
                ->route('login')
                ->with('error', 'Google authentication database error: ' . $e->getMessage());

        } catch (\Throwable $e) {
            return redirect()
                ->route('login')
                ->with('error', 'Google authentication failed: ' . $e->getMessage());
        }
    }
        public function firebaseGoogleLogin(Request $request)
    {
        $request->validate([
            'idToken' => ['required', 'string'],
            'role' => ['nullable', 'in:patient,doctor'],
        ]);

        $apiKey = config('services.firebase.api_key');

        $response = Http::post("https://identitytoolkit.googleapis.com/v1/accounts:lookup?key={$apiKey}", [
            'idToken' => $request->idToken,
        ]);

        if (!$response->successful()) {
            return response()->json([
                'message' => 'Invalid Firebase token.',
            ], 401);
        }

        $firebaseUser = $response->json('users.0');

        if (!$firebaseUser || empty($firebaseUser['email'])) {
            return response()->json([
                'message' => 'Google email not found.',
            ], 422);
        }

        $email = strtolower(trim($firebaseUser['email']));
        $name = $firebaseUser['displayName'] ?? 'Google User';
        $googleId = $firebaseUser['localId'] ?? null;
        $avatar = $firebaseUser['photoUrl'] ?? null;
        $role = $request->role ?? 'patient';

        if (!in_array($role, ['patient', 'doctor'])) {
            $role = 'patient';
        }

        $user = User::whereRaw('LOWER(email) = ?', [$email])->first();

        if (!$user) {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'email_verified_at' => now(),
                'google_id' => $googleId,
                'avatar' => $avatar,
                'password' => Hash::make(Str::random(40)),
                'role' => $role,
                'status' => $role === 'doctor' ? 'pending' : 'active',
            ]);

            if ($role === 'patient') {
                Patient::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'name' => $user->name,
                        'email' => $user->email,
                    ]
                );
            }

            if ($role === 'doctor') {
                Doctor::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'name' => $user->name,
                        'email' => $user->email,
                        'verification_status' => 'pending',
                    ]
                );
            }

            return response()->json([
                'message' => $role === 'doctor'
                    ? 'Doctor registration successful. Account pending admin approval.'
                    : 'Patient registration successful. Please login now.',
                'redirect' => route('login'),
            ]);
        }

        $user->update([
            'email' => strtolower(trim($user->email)),
            'email_verified_at' => $user->email_verified_at ?: now(),
            'google_id' => $googleId,
            'avatar' => $avatar,
        ]);

        if ($user->role === 'patient') {
            Patient::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            );
        }

        if ($user->role === 'doctor') {
            Doctor::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $user->name,
                    'email' => $user->email,
                    'verification_status' => 'pending',
                ]
            );

            if ($user->status !== 'active') {
                return response()->json([
                    'message' => 'Your doctor account is pending admin approval.',
                    'redirect' => route('login'),
                ], 403);
            }
        }

        if ($user->status !== 'active') {
            return response()->json([
                'message' => 'Your account is not active. Please contact admin.',
                'redirect' => route('login'),
            ], 403);
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        return response()->json([
            'message' => 'Login successful.',
            'redirect' => route('dashboard'),
        ]);
    }
}