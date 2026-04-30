<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Doctor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Email verification link থেকে আসলে আগে verify complete করবে
        if (session()->has('url.intended')) {
            return redirect()->intended();
        }

        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')
                ->with('warning', 'Please verify your email address first.');
        }

        // Doctor approval check
        if ($user->role === 'doctor') {
            $doctor = Doctor::where('user_id', $user->id)
                ->orWhere('email', $user->email)
                ->first();

            if (!$doctor) {
                Auth::logout();

                return redirect()->route('login')->withErrors([
                    'email' => 'Doctor profile not found. Please contact admin.',
                ]);
            }

            if ($doctor->verification_status !== 'approved') {
                Auth::logout();

                return redirect()->route('login')->withErrors([
                    'email' => 'Your doctor account is pending admin approval.',
                ]);
            }
        }

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
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}