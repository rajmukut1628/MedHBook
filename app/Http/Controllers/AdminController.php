<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403);
        }

        $admins = User::where('role', 'admin')->get();
        return view('admins.index', compact('admins'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403);
        }

        return view('admins.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403);
        }

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'status' => 'active',
        ]);

        return redirect()->route('admins.index')->with('success', 'Admin created successfully');
    }

    public function destroy(User $admin)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403);
        }

        if ($admin->role == 'admin') {
            $admin->delete();
        }

        return redirect()->route('admins.index')->with('success', 'Admin deleted successfully');
    }
}