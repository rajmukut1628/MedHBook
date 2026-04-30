<x-app-layout>
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 py-10 px-6">

    @php
        $doctor = \App\Models\Doctor::where('user_id', auth()->id())
            ->orWhere('email', auth()->user()->email)
            ->first();

        $specialties = [
            'Cardiologist','Neurologist','Medicine','Dermatologist','Orthopedic',
            'Dentist','ENT Specialist','Gynecologist','Urologist','Pediatrician',
            'Ophthalmologist','Gastroenterologist','Psychiatrist','Pulmonologist',
            'Endocrinologist','Nephrologist','Oncologist'
        ];
    @endphp

    <div class="max-w-5xl mx-auto">

        <div class="mb-8 rounded-[32px] bg-gradient-to-r from-emerald-500/20 via-cyan-500/20 to-blue-500/20 border border-white/10 p-8 shadow-2xl">
            <div class="flex items-center gap-5">
                <div class="h-20 w-20 rounded-3xl bg-gradient-to-br from-emerald-400 to-cyan-500 flex items-center justify-center text-4xl shadow-xl">
                    🩺
                </div>

                <div>
                    <h1 class="text-4xl font-black text-white">Edit Doctor Profile</h1>
                    <p class="text-slate-300 mt-2">Update your professional information, photo and bio.</p>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-2xl bg-emerald-500/20 border border-emerald-400/30 text-emerald-200 px-5 py-4 font-bold">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 rounded-2xl bg-red-500/20 border border-red-400/30 text-red-200 px-5 py-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li class="font-bold">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST"
              action="{{ route('doctor.my-profile.update') }}"
              enctype="multipart/form-data"
              class="rounded-[32px] bg-white/10 backdrop-blur-xl border border-white/10 p-8 shadow-2xl space-y-7">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-1">
                    <div class="rounded-3xl bg-slate-950/50 border border-white/10 p-6 text-center">
                        @if($doctor && $doctor->profile_photo)
                            <img src="{{ asset('storage/' . $doctor->profile_photo) }}"
                                 class="mx-auto h-36 w-36 rounded-3xl object-cover border-4 border-emerald-400 shadow-xl">
                        @else
                            <div class="mx-auto h-36 w-36 rounded-3xl bg-gradient-to-br from-emerald-500 to-cyan-500 flex items-center justify-center text-white text-5xl font-black shadow-xl">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif

                        <h3 class="mt-5 text-2xl font-black text-white">
                            Dr. {{ auth()->user()->name }}
                        </h3>

                        <p class="text-emerald-300 font-bold mt-1">
                            {{ $doctor->specialist ?? 'Doctor' }}
                        </p>

                        <label class="mt-6 block cursor-pointer rounded-2xl bg-white/10 border border-white/10 px-5 py-4 text-white font-bold hover:bg-white/20 transition">
                            Upload New Photo
                            <input type="file" name="profile_photo" accept="image/*" class="hidden">
                        </label>

                        <p class="text-xs text-slate-400 mt-3">
                            JPG, PNG, WEBP accepted. Max 2MB.
                        </p>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-5">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-200 mb-2">Phone</label>
                            <input name="phone"
                                   value="{{ old('phone', $doctor->phone ?? '') }}"
                                   placeholder="Phone number"
                                   class="w-full rounded-2xl bg-slate-950/60 border border-white/10 text-white px-5 py-4 focus:ring-2 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-200 mb-2">Specialty</label>
                            <select name="specialist"
                                    required
                                    class="w-full rounded-2xl bg-slate-950/60 border border-white/10 text-white px-5 py-4 focus:ring-2 focus:ring-emerald-500">
                                @foreach($specialties as $specialty)
                                    <option value="{{ $specialty }}"
                                        {{ old('specialist', $doctor->specialist ?? '') === $specialty ? 'selected' : '' }}>
                                        {{ $specialty }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-200 mb-2">Degree</label>
                            <input name="degree"
                                   value="{{ old('degree', $doctor->degree ?? '') }}"
                                   placeholder="MBBS, FCPS, MD"
                                   class="w-full rounded-2xl bg-slate-950/60 border border-white/10 text-white px-5 py-4 focus:ring-2 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-200 mb-2">Qualification</label>
                            <input name="qualification"
                                   value="{{ old('qualification', $doctor->qualification ?? '') }}"
                                   placeholder="Senior Consultant, Specialist"
                                   class="w-full rounded-2xl bg-slate-950/60 border border-white/10 text-white px-5 py-4 focus:ring-2 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-200 mb-2">Experience</label>
                            <input type="number"
                                   name="experience"
                                   min="0"
                                   value="{{ old('experience', $doctor->experience ?? 0) }}"
                                   class="w-full rounded-2xl bg-slate-950/60 border border-white/10 text-white px-5 py-4 focus:ring-2 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-200 mb-2">Gender</label>
                            <select name="gender"
                                    class="w-full rounded-2xl bg-slate-950/60 border border-white/10 text-white px-5 py-4 focus:ring-2 focus:ring-emerald-500">
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('gender', $doctor->gender ?? '') === 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender', $doctor->gender ?? '') === 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('gender', $doctor->gender ?? '') === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-200 mb-2">Blood Group</label>
                            <select name="blood_group"
                                    class="w-full rounded-2xl bg-slate-950/60 border border-white/10 text-white px-5 py-4 focus:ring-2 focus:ring-emerald-500">
                                <option value="">Select Blood Group</option>
                                @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                    <option value="{{ $bg }}" {{ old('blood_group', $doctor->blood_group ?? '') === $bg ? 'selected' : '' }}>
                                        {{ $bg }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-200 mb-2">Doctor Bio</label>
                        <textarea name="bio"
                                  rows="5"
                                  placeholder="Write short professional bio..."
                                  class="w-full rounded-2xl bg-slate-950/60 border border-white/10 text-white px-5 py-4 focus:ring-2 focus:ring-emerald-500">{{ old('bio', $doctor->bio ?? '') }}</textarea>
                    </div>

                </div>
            </div>

            <div class="flex flex-wrap gap-4 justify-between pt-4 border-t border-white/10">
                <a href="{{ route('profile.edit') }}"
                   class="px-7 py-3 rounded-2xl bg-white/10 border border-white/10 text-white font-bold hover:bg-white/20 transition">
                    ← Back
                </a>

                <button type="submit"
                        class="px-8 py-3 rounded-2xl bg-gradient-to-r from-emerald-500 to-cyan-500 text-white font-black shadow-xl hover:scale-[1.02] transition">
                    Save Doctor Profile
                </button>
            </div>
        </form>
    </div>
</div>
</x-app-layout>