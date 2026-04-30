<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
   public function edit(Request $request)
{
    $doctor = null;

    if (auth()->user()->role === 'doctor') {
        $doctor = \App\Models\Doctor::where('user_id', auth()->id())->first();
    }

    return view('profile.edit', [
        'user' => $request->user(),
        'doctor' => $doctor,
    ]);
}
    public function updateDoctorSchedule(Request $request)
{
    $request->validate([
        'chamber_addresses' => 'nullable|string',
        'working_days' => 'nullable|array',
        'start_time' => 'nullable',
        'end_time' => 'nullable',
        'consultation_fee' => 'nullable|numeric|min:0',
    ]);

    $doctor = \App\Models\Doctor::firstOrCreate(
        ['user_id' => auth()->id()],
        [
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
        ]
    );

    $doctor->update([
        'chamber_addresses' => $request->chamber_addresses,
        'working_days' => $request->working_days ?? [],
        'start_time' => $request->start_time,
        'end_time' => $request->end_time,
        'consultation_fee' => $request->consultation_fee ?? 0,
    ]);

    return redirect()->route('profile.edit')->with('success', 'Doctor schedule saved successfully!');
}
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
