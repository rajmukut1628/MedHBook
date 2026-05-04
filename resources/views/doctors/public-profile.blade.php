<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white leading-tight">
            Doctor Profile
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @php
                $doctorPhoto = $doctor->profile_photo ?? optional($doctor->user)->profile_photo ?? null;
                $doctorCv = $doctor->cv ?? null;

                $chambers = $doctor->display_chambers ?? [];

                if (empty($chambers)) {
                    $chambers = [
                        [
                            'address' => $doctor->chamber_address ?? 'Not added',
                            'working_days' => $doctor->working_days ?? [],
                            'start_time' => $doctor->start_time ?? null,
                            'end_time' => $doctor->end_time ?? null,
                            'fee' => $doctor->consultation_fee ?? 0,
                        ]
                    ];
                }

                $lowestFee = collect($chambers)->pluck('fee')->filter()->min() ?? ($doctor->consultation_fee ?? 0);
            @endphp

            {{-- HERO SECTION --}}
            <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl shadow-2xl overflow-hidden">
                <div class="h-44 bg-gradient-to-r from-emerald-500 via-cyan-500 to-blue-500"></div>

                <div class="p-8 -mt-20">
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">

                        <div class="flex flex-col md:flex-row md:items-end gap-6">
                            @if($doctorPhoto)
                                <img src="{{ route('secure.file.show', [
                                        'folder' => 'doctor-photos',
                                        'filename' => basename($doctorPhoto)
                                    ]) }}"
                                     class="w-36 h-36 rounded-3xl object-cover border-4 border-white shadow-2xl">
                            @else
                                <div class="w-36 h-36 rounded-3xl bg-slate-900 border-4 border-white flex items-center justify-center text-white text-5xl font-black shadow-2xl">
                                    {{ strtoupper(substr($doctor->name, 0, 1)) }}
                                </div>
                            @endif

                            <div>
                                <div class="flex items-center gap-3 flex-wrap">
                                    <h1 class="text-4xl font-black text-white">
                                        Dr. {{ $doctor->name }}
                                    </h1>

                                    @if($doctor->verification_status === 'approved')
                                        <span class="px-4 py-1 rounded-full bg-emerald-500 text-white text-sm font-bold">
                                            Verified
                                        </span>
                                    @endif

                                    <span class="px-4 py-1 rounded-full bg-cyan-500/20 border border-cyan-300/30 text-cyan-200 text-sm font-bold">
                                        🔐 Secure Profile
                                    </span>
                                </div>

                                <p class="text-emerald-300 text-xl font-bold mt-2">
                                    {{ $doctor->specialist ?? $doctor->specialization ?? 'Doctor' }}
                                </p>

                                <p class="text-slate-300 mt-1">
                                    {{ $doctor->email }}
                                </p>

                                @if($doctorCv)
                                    <a href="{{ route('secure.file.download', [
                                            'folder' => 'doctor-cvs',
                                            'filename' => basename($doctorCv)
                                        ]) }}"
                                       class="inline-flex mt-4 px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-black transition">
                                        Download CV
                                    </a>
                                @endif
                            </div>
                        </div>

                        @auth
                            @if(auth()->user()->role === 'patient')
                                <a href="{{ route('appointments.create', ['doctor_id' => $doctor->id]) }}"
                                   class="px-7 py-4 rounded-2xl bg-gradient-to-r from-emerald-500 to-cyan-500 text-white font-black shadow-xl hover:scale-[1.03] transition text-center">
                                    Book Appointment
                                </a>
                            @endif
                        @endauth

                    </div>
                </div>
            </div>

            {{-- STATS CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl p-6 shadow-xl">
                    <p class="text-slate-400 text-sm font-bold">Specialty</p>
                    <h3 class="text-white text-xl font-black mt-2">
                        {{ $doctor->specialist ?? $doctor->specialization ?? 'Not added' }}
                    </h3>
                </div>

                <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl p-6 shadow-xl">
                    <p class="text-slate-400 text-sm font-bold">Experience</p>
                    <h3 class="text-white text-xl font-black mt-2">
                        {{ $doctor->experience ?? 0 }} Years
                    </h3>
                </div>

                <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl p-6 shadow-xl">
                    <p class="text-slate-400 text-sm font-bold">Starting Fee</p>
                    <h3 class="text-white text-xl font-black mt-2">
                        ৳ {{ $lowestFee }}
                    </h3>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                {{-- PROFESSIONAL INFO --}}
                <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl p-8 shadow-2xl">
                    <h2 class="text-2xl font-black text-white mb-6">
                        Professional Information
                    </h2>

                    <div class="space-y-5">
                        <div>
                            <p class="text-slate-400 text-sm font-bold">Degree</p>
                            <p class="text-white text-lg mt-1">{{ $doctor->degree ?? 'Not added' }}</p>
                        </div>

                        <div>
                            <p class="text-slate-400 text-sm font-bold">Qualification</p>
                            <p class="text-white text-lg mt-1">{{ $doctor->qualification ?? 'Not added' }}</p>
                        </div>

                        <div>
                            <p class="text-slate-400 text-sm font-bold">Phone</p>
                            <p class="text-white text-lg mt-1">{{ $doctor->phone ?? 'Not added' }}</p>
                        </div>

                        <div>
                            <p class="text-slate-400 text-sm font-bold">Gender</p>
                            <p class="text-white text-lg mt-1">{{ $doctor->gender ?? 'Not added' }}</p>
                        </div>

                        <div>
                            <p class="text-slate-400 text-sm font-bold">Blood Group</p>
                            <p class="text-white text-lg mt-1">{{ $doctor->blood_group ?? 'Not added' }}</p>
                        </div>

                        <div>
                            <p class="text-slate-400 text-sm font-bold">CV Status</p>
                            <p class="text-white text-lg mt-1">
                                {{ $doctorCv ? 'Encrypted CV Available' : 'No CV Uploaded' }}
                            </p>
                        </div>
                    </div>
                </div>
                                {{-- ULTRA PREMIUM CHAMBERS --}}
                <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl p-8 shadow-2xl">
                    <div class="flex items-center justify-between gap-4 mb-6">
                        <div>
                            <h2 class="text-2xl font-black text-white">
                                Chambers & Schedule
                            </h2>
                            <p class="text-slate-400 mt-1 text-sm">
                                Available locations, visiting days, time and fees.
                            </p>
                        </div>

                        <span class="px-4 py-2 rounded-full bg-emerald-500/20 text-emerald-300 text-sm font-bold">
                            {{ count($chambers) }} Chamber{{ count($chambers) > 1 ? 's' : '' }}
                        </span>
                    </div>

                    <div class="space-y-5">
                        @foreach($chambers as $index => $chamber)
                            <div class="rounded-3xl bg-slate-950/60 border border-white/10 p-6 hover:border-emerald-400/50 hover:bg-slate-950/80 transition shadow-xl">
                                <div class="flex items-center justify-between gap-4 mb-4">
                                    <h3 class="text-xl font-black text-white">
                                        🏥 Chamber #{{ $index + 1 }}
                                    </h3>

                                    <span class="px-4 py-1 rounded-full bg-cyan-500/20 text-cyan-200 border border-cyan-400/30 text-sm font-bold">
                                        ৳ {{ $chamber['fee'] ?? 0 }}
                                    </span>
                                </div>

                                <div class="space-y-4">
                                    <div>
                                        <p class="text-slate-400 text-sm font-bold">Address</p>
                                        <p class="text-white mt-1 whitespace-pre-line leading-7">
                                            {{ $chamber['address'] ?? 'Not added' }}
                                        </p>
                                    </div>

                                    <div>
                                        <p class="text-slate-400 text-sm font-bold mb-2">Working Days</p>

                                        <div class="flex flex-wrap gap-2">
                                            @forelse(($chamber['working_days'] ?? []) as $day)
                                                <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-200 border border-emerald-400/30 text-sm font-bold">
                                                    {{ $day }}
                                                </span>
                                            @empty
                                                <span class="text-white">Not added</span>
                                            @endforelse
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="rounded-2xl bg-white/5 border border-white/10 p-4">
                                            <p class="text-slate-400 text-sm font-bold">Start Time</p>
                                            <p class="text-white font-black mt-1">
                                                {{ $chamber['start_time'] ?? 'Not set' }}
                                            </p>
                                        </div>

                                        <div class="rounded-2xl bg-white/5 border border-white/10 p-4">
                                            <p class="text-slate-400 text-sm font-bold">End Time</p>
                                            <p class="text-white font-black mt-1">
                                                {{ $chamber['end_time'] ?? 'Not set' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- ABOUT DOCTOR --}}
            <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl p-8 shadow-2xl">
                <h2 class="text-2xl font-black text-white mb-4">
                    About Doctor
                </h2>

                <p class="text-slate-300 leading-8 text-lg">
                    {{ $doctor->bio ?? 'No bio added yet.' }}
                </p>
            </div>

            {{-- SECURITY NOTICE --}}
            <div class="bg-emerald-500/10 border border-emerald-300/20 rounded-3xl p-6 shadow-2xl">
                <h2 class="text-xl font-black text-emerald-200">
                    🔐 Secure Doctor Files
                </h2>

                <p class="text-slate-300 mt-2">
                    Doctor profile photo and CV are stored in encrypted private storage.
                    They cannot be previewed from the PC folder or opened through public storage links.
                </p>
            </div>

            {{-- PATIENT BOOKING CTA --}}
            @auth
                @if(auth()->user()->role === 'patient')
                    <div class="bg-gradient-to-r from-emerald-500/20 to-cyan-500/20 border border-emerald-400/30 rounded-3xl p-8 shadow-2xl">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">
                            <div>
                                <h2 class="text-2xl font-black text-white">
                                    Ready to book an appointment?
                                </h2>
                                <p class="text-slate-300 mt-2">
                                    Choose your preferred chamber and continue booking.
                                </p>
                            </div>

                            <a href="{{ route('appointments.create', ['doctor_id' => $doctor->id]) }}"
                               class="px-7 py-4 rounded-2xl bg-gradient-to-r from-emerald-500 to-cyan-500 text-white font-black shadow-xl hover:scale-[1.03] transition text-center">
                                Book Appointment
                            </a>
                        </div>
                    </div>
                @endif
            @endauth

            {{-- ACTIONS --}}
            <div class="flex flex-wrap gap-4">
                <a href="{{ url()->previous() }}"
                   class="px-6 py-3 rounded-2xl bg-white/10 border border-white/10 text-white font-bold hover:bg-white/20 transition">
                    Back
                </a>

                @if($doctorCv)
                    <a href="{{ route('secure.file.download', [
                            'folder' => 'doctor-cvs',
                            'filename' => basename($doctorCv)
                        ]) }}"
                       class="px-6 py-3 rounded-2xl bg-blue-600 text-white font-black hover:bg-blue-700 transition">
                        Download CV
                    </a>
                @endif

                @auth
                    @if(auth()->user()->role === 'patient')
                        <a href="{{ route('appointments.create', ['doctor_id' => $doctor->id]) }}"
                           class="px-6 py-3 rounded-2xl bg-emerald-600 text-white font-black hover:bg-emerald-700 transition">
                            Book Appointment
                        </a>
                    @endif
                @endauth
            </div>

        </div>
    </div>
</x-app-layout>