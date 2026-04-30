<x-app-layout>
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 py-10 px-6">

    <div class="max-w-7xl mx-auto space-y-8">

        <div class="bg-white/10 border border-white/10 rounded-3xl p-8 shadow-2xl text-white">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <span class="inline-flex px-4 py-2 rounded-full bg-emerald-500/20 text-emerald-300 text-sm font-bold mb-3">
                        🩺 Doctor Workspace
                    </span>

                    <h1 class="text-4xl font-black">
                        Welcome, Dr. {{ auth()->user()->name }}
                    </h1>

                    <p class="text-slate-300 mt-2">
                        Manage appointments, patients, schedule and profile from one dashboard.
                    </p>
                </div>

                <div class="flex gap-3 flex-wrap">
                    <a href="{{ route('profile.edit') }}"
                       class="px-5 py-3 rounded-2xl bg-emerald-600 text-white font-bold">
                        Update Profile
                    </a>

                    <a href="{{ route('appointments.index') }}"
                       class="px-5 py-3 rounded-2xl bg-white/10 text-white font-bold border border-white/10">
                        View Appointments
                    </a>

                    <a href="{{ route('prescriptions.create') }}"
                       class="px-5 py-3 rounded-2xl bg-blue-600 text-white font-bold">
                        Create Prescription
                    </a>

                    <a href="{{ route('doctor.search.patient') }}"
                       class="px-5 py-3 rounded-2xl bg-purple-600 text-white font-bold">
                        Search Patient
                    </a>
                </div>
            </div>
        </div>

        @if(!$doctor)
            <div class="p-6 rounded-3xl bg-red-500/20 border border-red-400/30 text-red-200 font-bold">
                Doctor profile not found. Please create/update doctor profile first.
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-5">

            <div class="p-6 rounded-3xl bg-white/10 border border-white/10 shadow-xl">
                <p class="text-slate-400 text-sm font-bold">Today</p>
                <h2 class="text-4xl font-black text-white mt-2">{{ $todayCount }}</h2>
                <p class="text-slate-300 text-sm mt-2">Appointments</p>
            </div>

            <div class="p-6 rounded-3xl bg-white/10 border border-white/10 shadow-xl">
                <p class="text-slate-400 text-sm font-bold">Pending</p>
                <h2 class="text-4xl font-black text-yellow-300 mt-2">{{ $pendingCount }}</h2>
                <p class="text-slate-300 text-sm mt-2">Requests</p>
            </div>

            <div class="p-6 rounded-3xl bg-white/10 border border-white/10 shadow-xl">
                <p class="text-slate-400 text-sm font-bold">Approved</p>
                <h2 class="text-4xl font-black text-emerald-300 mt-2">{{ $approvedCount }}</h2>
                <p class="text-slate-300 text-sm mt-2">Bookings</p>
            </div>

            <div class="p-6 rounded-3xl bg-white/10 border border-white/10 shadow-xl">
                <p class="text-slate-400 text-sm font-bold">Patients</p>
                <h2 class="text-4xl font-black text-cyan-300 mt-2">{{ $patientsCount }}</h2>
                <p class="text-slate-300 text-sm mt-2">Unique</p>
            </div>

            <div class="p-6 rounded-3xl bg-white/10 border border-white/10 shadow-xl">
                <p class="text-slate-400 text-sm font-bold">Earnings</p>
                <h2 class="text-3xl font-black text-white mt-2">৳ {{ $earningsEstimate }}</h2>
                <p class="text-slate-300 text-sm mt-2">Estimate</p>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

            <a href="{{ route('appointments.index') }}" class="bg-white/10 border border-white/10 p-7 rounded-3xl shadow-xl hover:scale-[1.02] transition text-white">
                <div class="text-4xl mb-4">📅</div>
                <h2 class="text-2xl font-black">Appointments</h2>
                <p class="text-slate-300 mt-2">Approve, reject or review appointment requests.</p>
            </a>

            <a href="{{ route('doctor.patients') }}" class="bg-white/10 border border-white/10 p-7 rounded-3xl shadow-xl hover:scale-[1.02] transition text-white">
                <div class="text-4xl mb-4">👨‍👩‍👧</div>
                <h2 class="text-2xl font-black">Patients</h2>
                <p class="text-slate-300 mt-2">View patients who booked with you.</p>
            </a>

            <a href="{{ route('profile.edit') }}" class="bg-white/10 border border-white/10 p-7 rounded-3xl shadow-xl hover:scale-[1.02] transition text-white">
                <div class="text-4xl mb-4">🧑‍⚕️</div>
                <h2 class="text-2xl font-black">Profile & Schedule</h2>
                <p class="text-slate-300 mt-2">Update DP, specialty, chamber, days and slots.</p>
            </a>

            <a href="{{ route('prescriptions.create') }}" class="bg-white/10 border border-white/10 p-7 rounded-3xl shadow-xl hover:scale-[1.02] transition text-white">
                <div class="text-4xl mb-4">💊</div>
                <h2 class="text-2xl font-black">Prescription</h2>
                <p class="text-slate-300 mt-2">Create and manage patient prescriptions instantly.</p>
            </a>

            <a href="{{ route('doctor.search.patient') }}" class="bg-white/10 border border-white/10 p-7 rounded-3xl shadow-xl hover:scale-[1.02] transition text-white">
                <div class="text-4xl mb-4">🔎</div>
                <h2 class="text-2xl font-black">Search Patient</h2>
                <p class="text-slate-300 mt-2">Find patient profile and create digital prescription.</p>
            </a>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <div class="bg-white/10 border border-white/10 rounded-3xl p-6 shadow-2xl">
                <h2 class="text-2xl font-black text-white mb-5">Today’s Appointments</h2>

                <div class="space-y-4">
                    @forelse($todayAppointments as $appointment)
                        <div class="p-4 rounded-2xl bg-white/10 border border-white/10">
                            <div class="flex justify-between gap-4">
                                <div>
                                    <h3 class="text-white font-black">
                                        {{ $appointment->patient->name ?? 'Patient' }}
                                    </h3>
                                    <p class="text-slate-300 text-sm mt-1">
                                        {{ $appointment->problem ?: 'No notes' }}
                                    </p>
                                </div>

                                <span class="text-blue-300 font-black">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-300">No appointments today.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white/10 border border-white/10 rounded-3xl p-6 shadow-2xl">
                <h2 class="text-2xl font-black text-white mb-5">Upcoming Appointments</h2>

                <div class="space-y-4">
                    @forelse($upcomingAppointments as $appointment)
                        <div class="p-4 rounded-2xl bg-white/10 border border-white/10">
                            <div class="flex justify-between gap-4">
                                <div>
                                    <h3 class="text-white font-black">
                                        {{ $appointment->patient->name ?? 'Patient' }}
                                    </h3>
                                    <p class="text-slate-300 text-sm mt-1">
                                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}
                                        • {{ $appointment->status }}
                                    </p>
                                </div>

                                <span class="text-emerald-300 font-black">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-300">No upcoming appointments.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</div>
</x-app-layout>