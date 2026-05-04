<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class FirebaseAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'idToken' => 'required',
            'role' => 'required|in:patient,doctor',
        ]);

        $user = User::firstOrCreate(
            ['email' => 'firebase_test_user@gmail.com'],
            [
                'name' => 'Google User',
                'password' => Hash::make(Str::random(24)),
                'role' => $request->role,
                'status' => 'active',
            ]
        );

        Auth::login($user);

        return response()->json([
            'success' => true,
            'redirect' => route('dashboard'),
        ]);
    }
}