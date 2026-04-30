<x-app-layout>
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 py-10 px-6">
    <div class="max-w-7xl mx-auto space-y-8">

        <div class="bg-white/10 border border-white/10 rounded-3xl p-8 shadow-2xl">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">
                <div>
                    <span class="inline-flex px-4 py-2 rounded-full bg-emerald-500/20 text-emerald-300 text-sm font-bold mb-3">
                        📊 Doctor Overview
                    </span>

                    <h1 class="text-4xl font-black text-white">
                        Workspace Summary
                    </h1>

                    <p class="text-slate-300 mt-2">
                        Appointment, patient and performance overview.
                    </p>
                </div>

                <a href="{{ route('doctor.dashboard') }}"
                   class="px-6 py-3 rounded-2xl bg-emerald-600 text-white font-bold hover:bg-emerald-700">
                    Back to Dashboard
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-5">

            <div class="bg-white/10 border border-white/10 rounded-3xl p-6 shadow-xl">
                <p class="text-slate-400 font-bold">Appointments</p>
                <h2 class="text-4xl font-black text-white mt-2">{{ $totalAppointments ?? 0 }}</h2>
            </div>

            <div class="bg-white/10 border border-white/10 rounded-3xl p-6 shadow-xl">
                <p class="text-slate-400 font-bold">Patients</p>
                <h2 class="text-4xl font-black text-cyan-300 mt-2">{{ $totalPatients ?? 0 }}</h2>
            </div>

            <div class="bg-yellow-500/20 border border-yellow-400/20 rounded-3xl p-6 shadow-xl">
                <p class="text-yellow-200 font-bold">Pending</p>
                <h2 class="text-4xl font-black text-yellow-300 mt-2">{{ $pending ?? 0 }}</h2>
            </div>

            <div class="bg-emerald-500/20 border border-emerald-400/20 rounded-3xl p-6 shadow-xl">
                <p class="text-emerald-200 font-bold">Approved</p>
                <h2 class="text-4xl font-black text-emerald-300 mt-2">{{ $approved ?? 0 }}</h2>
            </div>

            <div class="bg-red-500/20 border border-red-400/20 rounded-3xl p-6 shadow-xl">
                <p class="text-red-200 font-bold">Rejected</p>
                <h2 class="text-4xl font-black text-red-300 mt-2">{{ $rejected ?? 0 }}</h2>
            </div>

        </div>

        <div class="bg-white/10 border border-white/10 rounded-3xl shadow-2xl p-6">
            <h2 class="text-2xl font-black text-white mb-6">
                Latest Appointments
            </h2>

            <div class="space-y-4">
                @forelse($latestAppointments ?? [] as $appointment)
                    <div class="bg-white/10 border border-white/10 rounded-2xl p-5 text-white">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <h3 class="text-xl font-black">
                                    {{ $appointment->patient->name ?? 'Unknown Patient' }}
                                </h3>

                                <p class="text-slate-300 mt-1">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}
                                    •
                                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                </p>

                                <p class="text-slate-400 text-sm mt-2">
                                    {{ $appointment->problem ?: 'No problem note added.' }}
                                </p>
                            </div>

                            <span class="px-4 py-2 rounded-full text-sm font-black
                                @if($appointment->status === 'Approved')
                                    bg-emerald-500/20 text-emerald-300
                                @elseif($appointment->status === 'Rejected')
                                    bg-red-500/20 text-red-300
                                @elseif($appointment->status === 'Cancelled')
                                    bg-red-500/20 text-red-300
                                @else
                                    bg-yellow-500/20 text-yellow-300
                                @endif">
                                {{ $appointment->status ?? 'Pending' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-14">
                        <div class="text-6xl mb-4">📅</div>
                        <h3 class="text-xl font-black text-white">No appointments found</h3>
                        <p class="text-slate-300 mt-2">Latest appointments will appear here.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
</x-app-layout>