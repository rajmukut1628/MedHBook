<x-app-layout>
<div class="min-h-screen relative overflow-hidden bg-[#020617] py-10 px-6">

    {{-- ANIMATED BACKGROUND --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute top-1/4 -right-32 w-[32rem] h-[32rem] bg-cyan-500/15 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-0 left-1/3 w-[28rem] h-[28rem] bg-blue-500/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(16,185,129,0.12),transparent_30%),radial-gradient(circle_at_bottom_right,rgba(6,182,212,0.12),transparent_35%)]"></div>
    </div>

    <div class="relative max-w-7xl mx-auto space-y-8">

        {{-- HERO HEADER --}}
        <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-white/[0.08] backdrop-blur-2xl shadow-[0_30px_100px_rgba(0,0,0,0.45)]">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 via-transparent to-cyan-500/10"></div>
            <div class="absolute -right-20 -top-20 w-72 h-72 bg-emerald-400/20 rounded-full blur-3xl"></div>

            <div class="relative p-8 lg:p-10">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">

                    <div>
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-500/15 border border-emerald-400/20 text-emerald-200 text-sm font-black mb-4 shadow-lg">
                            <span class="relative flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-300 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-300"></span>
                            </span>
                            Doctor Workspace
                        </div>

                        <h1 class="text-4xl lg:text-5xl font-black text-white tracking-tight">
                            Welcome, Dr. {{ auth()->user()->name }}
                        </h1>

                        <p class="text-slate-300 mt-3 text-lg max-w-2xl leading-8">
                            Manage appointments, prescriptions, profile and schedule from one premium medical command center.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 min-w-[280px]">
                        <div class="rounded-3xl bg-slate-950/50 border border-white/10 p-5 shadow-2xl">
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Today</p>
                            <h3 class="text-3xl font-black text-white mt-2">
                                {{ $todayAppointments->count() }}
                            </h3>
                            <p class="text-emerald-300 text-sm font-bold mt-1">Appointments</p>
                        </div>

                        <div class="rounded-3xl bg-slate-950/50 border border-white/10 p-5 shadow-2xl">
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Upcoming</p>
                            <h3 class="text-3xl font-black text-white mt-2">
                                {{ $upcomingAppointments->count() }}
                            </h3>
                            <p class="text-cyan-300 text-sm font-bold mt-1">Scheduled</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        @if(!$doctor)
            <div class="p-6 rounded-3xl bg-red-500/15 border border-red-400/30 text-red-200 font-bold shadow-2xl">
                Doctor profile not found. Please create/update doctor profile first.
            </div>
        @endif
                {{-- PREMIUM ACTION CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <a href="{{ route('appointments.index') }}"
               class="doctor-action-card group relative overflow-hidden rounded-[2rem] border border-white/10 bg-white/[0.08] backdrop-blur-2xl p-7 text-white shadow-[0_25px_70px_rgba(0,0,0,0.35)] transition duration-500 hover:-translate-y-2 hover:border-emerald-400/40">

                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
                <div class="absolute -right-14 -top-14 w-40 h-40 rounded-full bg-emerald-400/20 blur-3xl group-hover:bg-emerald-400/30 transition"></div>

                <div class="relative">
                    <div class="w-16 h-16 rounded-3xl bg-emerald-500/15 border border-emerald-400/20 flex items-center justify-center text-4xl shadow-xl group-hover:scale-110 transition">
                        📅
                    </div>

                    <h2 class="text-2xl font-black mt-6">
                        Appointments
                    </h2>

                    <p class="text-slate-300 mt-3 leading-7">
                        Approve, reject, review and manage all appointment requests from one place.
                    </p>

                    <div class="mt-6 inline-flex items-center gap-2 text-emerald-300 font-black">
                        Open Appointments
                        <span class="group-hover:translate-x-2 transition">→</span>
                    </div>
                </div>
            </a>

            <a href="{{ route('profile.edit') }}"
               class="doctor-action-card group relative overflow-hidden rounded-[2rem] border border-white/10 bg-white/[0.08] backdrop-blur-2xl p-7 text-white shadow-[0_25px_70px_rgba(0,0,0,0.35)] transition duration-500 hover:-translate-y-2 hover:border-cyan-400/40">

                <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
                <div class="absolute -right-14 -top-14 w-40 h-40 rounded-full bg-cyan-400/20 blur-3xl group-hover:bg-cyan-400/30 transition"></div>

                <div class="relative">
                    <div class="w-16 h-16 rounded-3xl bg-cyan-500/15 border border-cyan-400/20 flex items-center justify-center text-4xl shadow-xl group-hover:scale-110 transition">
                        🧑‍⚕️
                    </div>

                    <h2 class="text-2xl font-black mt-6">
                        Profile & Schedule
                    </h2>

                    <p class="text-slate-300 mt-3 leading-7">
                        Update profile, specialty, chamber, working days and available time slots.
                    </p>

                    <div class="mt-6 inline-flex items-center gap-2 text-cyan-300 font-black">
                        Update Profile
                        <span class="group-hover:translate-x-2 transition">→</span>
                    </div>
                </div>
            </a>

            <a href="{{ route('doctor.search.patient') }}"
               class="doctor-action-card group relative overflow-hidden rounded-[2rem] border border-white/10 bg-white/[0.08] backdrop-blur-2xl p-7 text-white shadow-[0_25px_70px_rgba(0,0,0,0.35)] transition duration-500 hover:-translate-y-2 hover:border-blue-400/40">

                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
                <div class="absolute -right-14 -top-14 w-40 h-40 rounded-full bg-blue-400/20 blur-3xl group-hover:bg-blue-400/30 transition"></div>

                <div class="relative">
                    <div class="w-16 h-16 rounded-3xl bg-blue-500/15 border border-blue-400/20 flex items-center justify-center text-4xl shadow-xl group-hover:scale-110 transition">
                        💊
                    </div>

                    <h2 class="text-2xl font-black mt-6">
                        Prescription
                    </h2>

                    <p class="text-slate-300 mt-3 leading-7">
                        Search patient, verify privacy key, view profile and create secure prescription.
                    </p>

                    <div class="mt-6 inline-flex items-center gap-2 text-blue-300 font-black">
                        Start Secure Flow
                        <span class="group-hover:translate-x-2 transition">→</span>
                    </div>
                </div>
            </a>

        </div>
                {{-- APPOINTMENTS SECTION --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">

            {{-- TODAY'S APPOINTMENTS --}}
            <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-white/[0.08] backdrop-blur-2xl p-6 shadow-[0_30px_90px_rgba(0,0,0,0.4)]">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 via-transparent to-transparent"></div>

                <div class="relative">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="text-blue-300 text-sm font-black uppercase tracking-[0.2em]">
                                Live Schedule
                            </p>
                            <h2 class="text-3xl font-black text-white mt-1">
                                Today's Appointments
                            </h2>
                        </div>

                        <div class="px-4 py-2 rounded-2xl bg-blue-500/15 border border-blue-400/20 text-blue-200 text-sm font-black shadow-lg">
                            {{ $todayAppointments->count() }} Today
                        </div>
                    </div>

                    <div class="space-y-4 max-h-[700px] overflow-y-auto pr-1">
                        @forelse($todayAppointments as $appointment)
                            <div class="group rounded-3xl bg-slate-950/40 border border-white/10 p-5 hover:border-blue-400/30 hover:bg-slate-950/60 transition-all duration-300 shadow-xl">
                                <div class="flex items-start justify-between gap-4">

                                    <div class="flex-1">
                                        <h3 class="text-white text-xl font-black">
                                            {{ $appointment->patient->name ?? 'Patient' }}
                                        </h3>

                                        <p class="text-slate-300 text-sm mt-2 leading-6">
                                            {{ $appointment->problem ?: 'No clinical notes provided.' }}
                                        </p>

                                        <div class="mt-3 inline-flex px-3 py-1 rounded-full bg-white/5 border border-white/10 text-slate-300 text-xs font-bold">
                                            Status: {{ ucfirst($appointment->status) }}
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <div class="px-4 py-2 rounded-2xl bg-blue-500/15 border border-blue-400/20 text-blue-200 font-black whitespace-nowrap shadow-lg">
                                            {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @empty
                            <div class="rounded-3xl bg-slate-950/30 border border-white/10 p-8 text-center">
                                <div class="text-5xl mb-3">📅</div>
                                <h3 class="text-xl font-black text-white mb-2">
                                    No Appointments Today
                                </h3>
                                <p class="text-slate-300">
                                    Your schedule is clear for today.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
                        {{-- UPCOMING APPOINTMENTS --}}
            <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-white/[0.08] backdrop-blur-2xl p-6 shadow-[0_30px_90px_rgba(0,0,0,0.4)]">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 via-transparent to-transparent"></div>

                <div class="relative">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="text-emerald-300 text-sm font-black uppercase tracking-[0.2em]">
                                Future Schedule
                            </p>
                            <h2 class="text-3xl font-black text-white mt-1">
                                Upcoming Appointments
                            </h2>
                        </div>

                        <div class="px-4 py-2 rounded-2xl bg-emerald-500/15 border border-emerald-400/20 text-emerald-200 text-sm font-black shadow-lg">
                            {{ $upcomingAppointments->count() }} Upcoming
                        </div>
                    </div>

                    <div class="space-y-4 max-h-[700px] overflow-y-auto pr-1">
                        @forelse($upcomingAppointments as $appointment)
                            <div class="group rounded-3xl bg-slate-950/40 border border-white/10 p-5 hover:border-emerald-400/30 hover:bg-slate-950/60 transition-all duration-300 shadow-xl">
                                <div class="flex items-start justify-between gap-4">

                                    <div class="flex-1">
                                        <h3 class="text-white text-xl font-black">
                                            {{ $appointment->patient->name ?? 'Patient' }}
                                        </h3>

                                        <p class="text-slate-300 text-sm mt-2">
                                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}
                                            • {{ ucfirst($appointment->status) }}
                                        </p>

                                        <p class="text-slate-400 text-xs mt-3 leading-6">
                                            {{ $appointment->problem ?: 'No clinical notes provided.' }}
                                        </p>
                                    </div>

                                    <div class="text-right">
                                        <div class="px-4 py-2 rounded-2xl bg-emerald-500/15 border border-emerald-400/20 text-emerald-200 font-black whitespace-nowrap shadow-lg">
                                            {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @empty
                            <div class="rounded-3xl bg-slate-950/30 border border-white/10 p-8 text-center">
                                <div class="text-5xl mb-3">🗓️</div>
                                <h3 class="text-xl font-black text-white mb-2">
                                    No Upcoming Appointments
                                </h3>
                                <p class="text-slate-300">
                                    Future appointments will appear here automatically.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
</x-app-layout>