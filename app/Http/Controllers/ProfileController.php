<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Doctor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    private string $disk = 'local';

    public function edit(Request $request): View
    {
        $doctor = null;

        if ($request->user()->role === 'doctor') {
            $doctor = Doctor::where('user_id', auth()->id())->first();
        }

        return view('profile.edit', [
            'user' => $request->user(),
            'doctor' => $doctor,
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->fill($request->validated());

        /*
        |--------------------------------------------------------------------------
        | 🔐 Encrypt Profile Photo Upload
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('profile_photo')) {

            $request->validate([
                'profile_photo' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            ]);

            // old file delete
            if (!empty($user->profile_photo) && Storage::disk($this->disk)->exists($user->profile_photo)) {
                Storage::disk($this->disk)->delete($user->profile_photo);
            }

            $file = $request->file('profile_photo');

            // 🔥 encrypted filename
            $fileName = 'profile_' . $user->id . '_' . Str::random(20) . '.mhb';

            $path = 'private/profile-pictures/user-' . $user->id . '/' . $fileName;

            // 🔐 encrypt file content
            $content = file_get_contents($file->getRealPath());
            $encrypted = Crypt::encryptString(base64_encode($content));

            Storage::disk($this->disk)->put($path, $encrypted);

            $user->profile_photo = $path;
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function updateDoctorSchedule(Request $request): RedirectResponse
    {
        $request->validate([
            'chamber_addresses' => ['nullable', 'string'],
            'working_days' => ['nullable', 'array'],
            'start_time' => ['nullable'],
            'end_time' => ['nullable'],
            'consultation_fee' => ['nullable', 'numeric', 'min:0'],
        ]);

        $doctor = Doctor::firstOrCreate(
            ['user_id' => auth()->id()],
            [
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ]
        );

        $doctor->update([
            'chamber_addresses' => $request->chamber_addresses,
            'chamber_address' => $request->chamber_addresses,
            'working_days' => $request->working_days ?? [],
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'consultation_fee' => $request->consultation_fee ?? 0,
        ]);

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Doctor schedule saved successfully!');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // delete profile photo
        if (!empty($user->profile_photo) && Storage::disk($this->disk)->exists($user->profile_photo)) {
            Storage::disk($this->disk)->delete($user->profile_photo);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}