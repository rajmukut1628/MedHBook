<x-app-layout>
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 py-10 px-6">
    <div class="max-w-5xl mx-auto">

        <div class="rounded-[32px] bg-white/10 border border-white/10 p-8 shadow-2xl text-white mb-8">
            <span class="inline-flex px-4 py-2 rounded-full bg-emerald-500/20 text-emerald-300 text-sm font-bold mb-4">
                👤 Patient Profile
            </span>

            <h1 class="text-4xl font-black">My Personal Information</h1>
            <p class="text-slate-300 mt-2">
                Update your health profile, emergency contact and personal details.
            </p>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-2xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 px-5 py-4 font-bold">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 rounded-2xl bg-red-500/15 border border-red-500/30 text-red-300 px-5 py-4 font-bold">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST"
              action="{{ route('patient.my-profile.update') }}"
              enctype="multipart/form-data"
              class="rounded-[32px] bg-white/10 border border-white/10 p-8 shadow-2xl">
            @csrf

            <div class="flex flex-col md:flex-row gap-8">

                <div class="md:w-1/3">
                    <div class="rounded-[28px] bg-white/10 border border-white/10 p-6 text-center text-white">

                        @if($patient->profile_photo)
                            <img src="{{ asset('storage/'.$patient->profile_photo) }}"
                                 class="mx-auto h-32 w-32 rounded-full object-cover border-4 border-emerald-400 shadow-xl">
                        @else
                            <div class="mx-auto h-32 w-32 rounded-full bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center text-5xl font-black border-4 border-emerald-300">
                                {{ strtoupper(substr($patient->name ?? auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif

                        <h2 class="text-2xl font-black mt-5">
                            {{ $patient->name ?? auth()->user()->name }}
                        </h2>

                        <p class="text-slate-300 mt-1">
                            {{ auth()->user()->email }}
                        </p>

                        <div class="mt-5">
                            <label class="block text-left text-sm font-bold text-slate-200 mb-2">
                                Upload Profile Picture
                            </label>

                            <input type="file"
                                   name="profile_photo"
                                   accept="image/*"
                                   class="w-full rounded-2xl bg-white/90 px-4 py-3 text-slate-800">
                        </div>

                        @error('profile_photo')
                            <p class="text-red-300 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="md:w-2/3 grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>
                        <label class="text-white font-bold">Full Name</label>
                        <input type="text"
                               name="name"
                               value="{{ old('name', $patient->name ?? auth()->user()->name) }}"
                               required
                               class="mt-2 w-full rounded-2xl bg-white/90 border-white/10 px-5 py-3 text-slate-900">
                        @error('name')
                            <p class="text-red-300 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-white font-bold">Phone</label>
                        <input type="text"
                               name="phone"
                               value="{{ old('phone', $patient->phone) }}"
                               class="mt-2 w-full rounded-2xl bg-white/90 border-white/10 px-5 py-3 text-slate-900">
                        @error('phone')
                            <p class="text-red-300 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-white font-bold">Age</label>
                        <input type="number"
                               name="age"
                               value="{{ old('age', $patient->age) }}"
                               min="0"
                               max="120"
                               class="mt-2 w-full rounded-2xl bg-white/90 border-white/10 px-5 py-3 text-slate-900">
                        @error('age')
                            <p class="text-red-300 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-white font-bold">Gender</label>
                        <select name="gender"
                                class="mt-2 w-full rounded-2xl bg-white/90 border-white/10 px-5 py-3 text-slate-900">
                            <option value="">Select Gender</option>
                            <option value="Male" {{ old('gender', $patient->gender) === 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $patient->gender) === 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('gender', $patient->gender) === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-white font-bold">Blood Group</label>
                        <select name="blood_group"
                                class="mt-2 w-full rounded-2xl bg-white/90 border-white/10 px-5 py-3 text-slate-900">
                            <option value="">Select Blood Group</option>
                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $group)
                                <option value="{{ $group }}" {{ old('blood_group', $patient->blood_group) === $group ? 'selected' : '' }}>
                                    {{ $group }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-white font-bold">Emergency Contact</label>
                        <input type="text"
                               name="emergency_contact"
                               value="{{ old('emergency_contact', $patient->emergency_contact) }}"
                               class="mt-2 w-full rounded-2xl bg-white/90 border-white/10 px-5 py-3 text-slate-900">
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-white font-bold">Address</label>
                        <textarea name="address"
                                  rows="3"
                                  class="mt-2 w-full rounded-2xl bg-white/90 border-white/10 px-5 py-3 text-slate-900">{{ old('address', $patient->address) }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-white font-bold block mb-3">Health Conditions</label>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                            <label class="rounded-2xl bg-white/10 border border-white/10 p-4 text-white flex items-center gap-3 cursor-pointer">
                                <input type="checkbox"
                                       name="has_allergy"
                                       value="1"
                                       {{ old('has_allergy', $patient->has_allergy) ? 'checked' : '' }}
                                       class="rounded">
                                <span class="font-bold">Allergy</span>
                            </label>

                            <label class="rounded-2xl bg-white/10 border border-white/10 p-4 text-white flex items-center gap-3 cursor-pointer">
                                <input type="checkbox"
                                       name="has_diabetes"
                                       value="1"
                                       {{ old('has_diabetes', $patient->has_diabetes) ? 'checked' : '' }}
                                       class="rounded">
                                <span class="font-bold">Diabetes</span>
                            </label>

                            <label class="rounded-2xl bg-white/10 border border-white/10 p-4 text-white flex items-center gap-3 cursor-pointer">
                                <input type="checkbox"
                                       name="has_blood_pressure"
                                       value="1"
                                       {{ old('has_blood_pressure', $patient->has_blood_pressure) ? 'checked' : '' }}
                                       class="rounded">
                                <span class="font-bold">Blood Pressure</span>
                            </label>

                        </div>
                    </div>

                </div>
            </div>

            <div class="flex flex-wrap gap-3 mt-8">
                <button type="submit"
                        class="px-7 py-3 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-black shadow-lg hover:scale-105 transition">
                    Save Profile
                </button>

                <a href="{{ route('patient.dashboard') }}"
                   class="px-7 py-3 rounded-2xl bg-white/10 text-white border border-white/10 font-bold">
                    Back to Dashboard
                </a>
            </div>

        </form>

    </div>
</div>
</x-app-layout>