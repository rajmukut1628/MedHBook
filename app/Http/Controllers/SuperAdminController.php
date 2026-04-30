<?php

namespace App\Http\Controllers;

use App\Models\User;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        return view('superadmin.dashboard');
    }

    public function admins()
    {
        $admins = User::where('role', 'admin')->latest()->get();

        return view('superadmin.admins', compact('admins'));
    }

    public function createAdmin()
    {
        return view('superadmin.create-admin');
    }

    public function editAdmin($id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);

        return view('superadmin.edit-admin', compact('admin'));
    }

    public function deleteAdmin($id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);
        $admin->delete();

        return back()->with('success', 'Admin deleted successfully.');
    }
}