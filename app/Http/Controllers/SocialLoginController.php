<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function redirectToGoogle(Request $request)
    {
        $role = $request->query('role', 'patient');

        if (!in_array($role, ['patient', 'doctor'])) {
            $role = 'patient';
        }

        session(['google_register_role' => $role]);

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();

        $role = session('google_register_role', 'patient');
        session()->forget('google_register_role');

        if (!in_array($role, ['patient', 'doctor'])) {
            $role = 'patient';
        }

        $email = strtolower($googleUser->getEmail());

        $user = User::where('email', $email)->first();

        if (!$user) {
            $user = User::create([
                'name' => $googleUser->getName() ?? 'Google User',
                'email' => $email,
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => Hash::make(Str::random(32)),
                'role' => $role,
                'status' => $role === 'doctor' ? 'pending' : 'active',
            ]);
        } else {
            $user->update([
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
            ]);
        }

        Auth::login($user);

        return redirect()->route('dashboard');
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

        $email = strtolower($firebaseUser['email']);
        $name = $firebaseUser['displayName'] ?? 'Google User';
        $googleId = $firebaseUser['localId'] ?? null;
        $avatar = $firebaseUser['photoUrl'] ?? null;
        $role = $request->role ?? 'patient';

        $user = User::where('email', $email)->first();

        if (!$user) {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'google_id' => $googleId,
                'avatar' => $avatar,
                'password' => Hash::make(Str::random(32)),
                'role' => $role,
                'status' => $role === 'doctor' ? 'pending' : 'active',
            ]);
        } else {
            $user->update([
                'google_id' => $googleId,
                'avatar' => $avatar,
            ]);
        }

        Auth::login($user);

        return response()->json([
            'message' => 'Login successful.',
            'redirect' => route('dashboard'),
        ]);
    }
}