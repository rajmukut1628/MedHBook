<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white leading-tight">
            My Profile
        </h2>
    </x-slot>

    <div class="py-10 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @php
                $doctor = null;

                if (Auth::user()->role === 'doctor') {
                    $doctor = \App\Models\Doctor::where('user_id', Auth::id())
                        ->orWhere('email', Auth::user()->email)
                        ->first();
                }

                $userPhoto = Auth::user()->profile_photo ?? null;
                $doctorPhoto = $doctor->profile_photo ?? null;
                $doctorCv = $doctor->cv ?? null;
            @endphp

            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-500/20 text-emerald-200 border border-emerald-400/30 font-bold">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 rounded-2xl bg-red-500/20 text-red-200 border border-red-400/30 font-bold">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 rounded-2xl bg-red-500/20 text-red-200 border border-red-400/30">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- PROFILE HERO CARD --}}
            <div class="relative overflow-hidden rounded-[32px] border border-white/10 bg-white/10 backdrop-blur-xl shadow-2xl">
                <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/20 via-cyan-500/10 to-blue-500/20"></div>

                <div class="relative p-6 sm:p-8">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

                        <div class="flex items-center gap-5 flex-wrap">

                            {{-- SECURE PROFILE PHOTO --}}
                            @if(Auth::user()->role === 'doctor' && $doctorPhoto)
                                <img src="{{ route('secure.file.show', [
                                        'folder' => 'doctor-photos',
                                        'filename' => basename($doctorPhoto)
                                    ]) }}"
                                     loading="lazy"
                                     class="w-28 h-28 rounded-3xl object-cover border-4 border-emerald-400 shadow-xl">
                            @elseif($userPhoto)
                                <img src="{{ route('secure.file.show', [
                                        'folder' => 'profile-pictures',
                                        'filename' => basename($userPhoto)
                                    ]) }}"
                                     loading="lazy"
                                     class="w-28 h-28 rounded-3xl object-cover border-4 border-emerald-400 shadow-xl">
                            @else
                                <div class="w-28 h-28 rounded-3xl bg-gradient-to-br from-emerald-500 to-cyan-500 flex items-center justify-center text-white text-5xl font-black shadow-xl">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif

                            <div>
                                <div class="flex items-center gap-3 flex-wrap">
                                    <h3 class="text-3xl sm:text-4xl font-black text-white">
                                        {{ Auth::user()->name }}
                                    </h3>

                                    <span class="px-4 py-1 rounded-full bg-emerald-500 text-white font-bold text-xs uppercase">
                                        {{ str_replace('_', ' ', Auth::user()->role) }}
                                    </span>

                                    <span class="px-4 py-1 rounded-full bg-cyan-500/20 border border-cyan-300/30 text-cyan-200 font-bold text-xs uppercase">
                                        🔐 Secure
                                    </span>
                                </div>

                                <p class="text-gray-300 mt-2">
                                    {{ Auth::user()->email }}
                                </p>

                                @if(Auth::user()->role === 'doctor' && $doctor)
                                    <p class="text-emerald-300 font-bold mt-2">
                                        {{ $doctor->specialist ?? $doctor->specialization ?? 'Doctor' }}

                                        @if($doctor->experience)
                                            • {{ $doctor->experience }} Years Experience
                                        @endif
                                    </p>

                                    @if($doctorCv)
                                        <div class="mt-3">
                                            <a href="{{ route('secure.file.download', [
                                                    'folder' => 'doctor-cvs',
                                                    'filename' => basename($doctorCv)
                                                ]) }}"
                                               class="inline-block px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 transition">
                                                Download My CV
                                            </a>
                                        </div>
                                    @endif

                                    <div class="mt-2 text-xs text-emerald-300 font-bold">
                                        🔐 Profile photo and CV are stored in encrypted private storage.
                                    </div>
                                @else
                                    <div class="mt-2 text-xs text-emerald-300 font-bold">
                                        🔐 Profile photo is stored in encrypted private storage.
                                    </div>
                                @endif
                            </div>
                        </div>
                                                @if(Auth::user()->role === 'doctor' && $doctor)
                            <a href="{{ route('doctor.public.profile', $doctor->id) }}"
                               class="px-6 py-3 rounded-2xl bg-white/10 border border-white/10 text-white font-black hover:bg-white/20 transition text-center">
                                View Public Profile
                            </a>
                        @endif

                    </div>
                </div>
            </div>

            {{-- DOCTOR PREMIUM PROFILE SUMMARY --}}
            @if(Auth::user()->role === 'doctor' && $doctor)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <div class="p-6 rounded-3xl bg-white/10 backdrop-blur-xl border border-white/10 shadow-xl">
                        <p class="text-slate-400 text-sm font-bold">Specialty</p>
                        <h3 class="text-white text-2xl font-black mt-2">
                            {{ $doctor->specialist ?? $doctor->specialization ?? 'Not Added' }}
                        </h3>
                    </div>

                    <div class="p-6 rounded-3xl bg-white/10 backdrop-blur-xl border border-white/10 shadow-xl">
                        <p class="text-slate-400 text-sm font-bold">Experience</p>
                        <h3 class="text-white text-2xl font-black mt-2">
                            {{ $doctor->experience ?? 0 }} Years
                        </h3>
                    </div>

                    <div class="p-6 rounded-3xl bg-white/10 backdrop-blur-xl border border-white/10 shadow-xl">
                        <p class="text-slate-400 text-sm font-bold">Blood Group</p>
                        <h3 class="text-white text-2xl font-black mt-2">
                            {{ $doctor->blood_group ?? 'Not Added' }}
                        </h3>
                    </div>

                </div>
            @endif

            {{-- SECURITY SUMMARY --}}
            <div class="p-6 rounded-3xl bg-emerald-500/10 backdrop-blur-xl border border-emerald-300/20 shadow-xl">
                <h3 class="text-xl font-black text-emerald-200">
                    🔐 Private & Encrypted File Security
                </h3>

                <p class="text-slate-300 mt-2">
                    Profile photos, doctor photos and CV files are stored in private encrypted storage.
                    They cannot be previewed from the PC folder or opened through public storage links.
                </p>
            </div>

            {{-- PROFILE SETTINGS HUB --}}
            <div>
                <div class="mb-5">
                    <h3 class="text-3xl font-black text-white">
                        Profile Settings
                    </h3>
                    <p class="text-slate-400 mt-2">
                        Manage your profile, security and account settings from one place.
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">

                    {{-- EDIT DOCTOR PROFILE --}}
                    @if(Auth::user()->role === 'doctor' && $doctor)
                        <div class="p-6 bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl shadow-xl hover:scale-[1.02] transition">
                            <div class="h-14 w-14 rounded-2xl bg-blue-500/20 flex items-center justify-center text-3xl mb-5">
                                🩺
                            </div>

                            <h3 class="text-xl font-black text-white">Edit Doctor Profile</h3>
                            <p class="text-slate-300 mt-2">
                                Update specialty, qualification, experience, encrypted photo and encrypted CV.
                            </p>

                            <a href="{{ route('profile.doctor.edit') }}"
                               class="mt-5 inline-block px-6 py-3 bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-xl font-bold">
                                Edit Profile
                            </a>
                        </div>

                        {{-- MANAGE CHAMBERS --}}
                        <div class="p-6 bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl shadow-xl hover:scale-[1.02] transition">
                            <div class="h-14 w-14 rounded-2xl bg-emerald-500/20 flex items-center justify-center text-3xl mb-5">
                                🏥
                            </div>

                            <h3 class="text-xl font-black text-white">Manage Chambers</h3>
                            <p class="text-slate-300 mt-2">
                                Add multiple chambers, working days, time and fee.
                            </p>

                            <a href="{{ route('doctors.edit', $doctor->id) }}"
                               class="mt-5 inline-block px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-xl font-bold">
                                Manage Chambers
                            </a>
                        </div>
                    @endif

                    {{-- ACCOUNT INFO --}}
                    <div class="p-6 bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl shadow-xl hover:scale-[1.02] transition">
                        <div class="h-14 w-14 rounded-2xl bg-purple-500/20 flex items-center justify-center text-3xl mb-5">
                            👤
                        </div>

                        <h3 class="text-xl font-black text-white">Account Info</h3>
                        <p class="text-slate-300 mt-2">
                            Update your account name, email and encrypted profile picture.
                        </p>

                        <a href="{{ route('profile.info.edit') }}"
                           class="mt-5 inline-block px-6 py-3 bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded-xl font-bold">
                            Update Info
                        </a>
                    </div>

                    {{-- PASSWORD --}}
                    <div class="p-6 bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl shadow-xl hover:scale-[1.02] transition">
                        <div class="h-14 w-14 rounded-2xl bg-orange-500/20 flex items-center justify-center text-3xl mb-5">
                            🔐
                        </div>

                        <h3 class="text-xl font-black text-white">Security</h3>
                        <p class="text-slate-300 mt-2">
                            Change your password and keep your account safe.
                        </p>

                        <a href="{{ route('profile.password.edit') }}"
                           class="mt-5 inline-block px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-xl font-bold">
                            Change Password
                        </a>
                    </div>

                    {{-- DELETE ACCOUNT --}}
                    <div class="p-6 bg-white/10 backdrop-blur-xl border border-red-500/30 rounded-3xl shadow-xl hover:scale-[1.02] transition">
                        <div class="h-14 w-14 rounded-2xl bg-red-500/20 flex items-center justify-center text-3xl mb-5">
                            ⚠️
                        </div>

                        <h3 class="text-xl font-black text-red-400">Danger Zone</h3>
                        <p class="text-slate-300 mt-2">
                            Delete your account permanently.
                        </p>

                        <a href="{{ route('profile.delete.confirm') }}"
                           class="mt-5 inline-block px-6 py-3 bg-red-600 text-white rounded-xl font-bold">
                            Delete Account
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>