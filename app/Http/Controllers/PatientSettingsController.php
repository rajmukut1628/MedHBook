<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PatientSettingsController extends Controller
{
    public function index()
    {
        return view('patient.settings');
    }
}