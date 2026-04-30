<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:patient,doctor'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $role = $request->role;

        $user = User::create([
            'name' => $request->name,
            'email' => strtolower($request->email),
            'role' => $role,
            'status' => $role === 'doctor' ? 'pending' : 'active',
            'password' => Hash::make($request->password),
        ]);

        if ($role === 'doctor') {
            Doctor::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => null,
                    'specialist' => 'Pending',
                    'specialization' => 'Pending',
                    'verification_status' => 'pending',
                ]
            );
        }

        if ($role === 'patient') {
            Patient::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            );
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('verification.notice')
            ->with('success', 'Account created successfully! Please verify your email address.');
    }
}