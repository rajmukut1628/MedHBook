<?php

namespace App\Http\Controllers;

use App\Models\DoctorProfile;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DoctorPanelController extends Controller
{
    private string $disk = 'local';

    public function dashboard()
    {
        return view('doctor.panel.dashboard');
    }

    public function profile()
    {
        $profile = DoctorProfile::where('user_id', auth()->id())->first();

        return view('doctor.panel.profile', compact('profile'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'qualification' => ['nullable', 'string', 'max:255'],
            'degrees' => ['nullable', 'string', 'max:255'],
            'experience' => ['nullable', 'integer', 'min:0'],
            'consultation_fee' => ['nullable', 'numeric', 'min:0'],
            'chamber_address' => ['nullable', 'string'],
            'languages' => ['nullable', 'string', 'max:255'],
            'online_status' => ['nullable'],
        ]);

        $profile = DoctorProfile::where('user_id', auth()->id())->first();

        $data = [
            'qualification' => $request->qualification,
            'degrees' => $request->degrees,
            'experience' => $request->experience ?? 0,
            'consultation_fee' => $request->consultation_fee ?? 0,
            'chamber_address' => $request->chamber_address,
            'languages' => $request->languages,
            'online_status' => $request->has('online_status') ? 1 : 0,
        ];

        if ($request->hasFile('photo')) {
            if ($profile && $profile->photo && Storage::disk($this->disk)->exists($profile->photo)) {
                Storage::disk($this->disk)->delete($profile->photo);
            }

            $data['photo'] = $request->file('photo')->store(
                'private/doctor-photos/user-' . auth()->id(),
                $this->disk
            );
        }

        DoctorProfile::updateOrCreate(
            ['user_id' => auth()->id()],
            $data
        );

        return redirect()
            ->route('doctor.profile')
            ->with('success', 'Profile Updated Successfully');
    }

    public function schedule()
    {
        $schedules = DoctorSchedule::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('doctor.panel.schedule', compact('schedules'));
    }

    public function storeSchedule(Request $request)
    {
        $request->validate([
            'schedule_date' => ['nullable', 'date'],
            'day_of_week' => ['nullable', 'string'],
            'start_time' => ['required'],
            'end_time' => ['required', 'after:start_time'],
        ]);

        if (!$request->schedule_date && !$request->day_of_week) {
            return back()->with('error', 'Please select either schedule date or day of week.');
        }

        $overlapQuery = DoctorSchedule::where('user_id', auth()->id())
            ->where('start_time', '<', $request->end_time)
            ->where('end_time', '>', $request->start_time);

        if ($request->schedule_date) {
            $overlapQuery->where('schedule_date', $request->schedule_date);
        } else {
            $overlapQuery->where('day_of_week', $request->day_of_week);
        }

        if ($overlapQuery->exists()) {
            return back()->with('error', 'This slot overlaps with another schedule.');
        }

        DoctorSchedule::create([
            'user_id' => auth()->id(),
            'schedule_date' => $request->schedule_date,
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_recurring' => $request->has('is_recurring') ? 1 : 0,
            'is_emergency' => $request->has('is_emergency') ? 1 : 0,
            'is_active' => 1,
        ]);

        return redirect()
            ->route('doctor.schedule')
            ->with('success', 'Schedule Slot Created Successfully');
    }

    public function appointments()
    {
        return view('doctor.panel.appointments');
    }
}