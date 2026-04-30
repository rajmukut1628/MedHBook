<?php

namespace App\Http\Middleware;

use App\Models\Doctor;
use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Doctor approval check
        if ($user->role === 'doctor') {
            $doctor = Doctor::where('user_id', $user->id)
                ->orWhere('email', $user->email)
                ->first();

            if (!$doctor) {
                auth()->logout();

                return redirect()->route('login')->withErrors([
                    'email' => 'Doctor profile not found. Please contact admin.',
                ]);
            }

            if ($doctor->verification_status !== 'approved') {
                auth()->logout();

                return redirect()->route('login')->withErrors([
                    'email' => 'Your doctor account is pending admin approval.',
                ]);
            }
        }

        // Super Admin can access everything
        if ($user->role === 'super_admin') {
            return $next($request);
        }

        // Normal role check
        if (!in_array($user->role, $roles)) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}