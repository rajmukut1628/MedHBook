<x-app-layout>
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 py-10 px-6">
    <div class="max-w-7xl mx-auto space-y-8">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">
            <div>
                <span class="inline-flex px-4 py-2 rounded-full bg-emerald-500/20 text-emerald-300 text-sm font-bold mb-3">
                    👨‍👩‍👧 My Patients
                </span>

                <h1 class="text-4xl font-black text-white">Patient Records</h1>
                <p class="text-slate-300 mt-2">
                    Patients who booked appointments with you.
                </p>
            </div>

            <a href="{{ route('doctor.dashboard') }}"
               class="px-6 py-3 rounded-2xl bg-emerald-600 text-white font-bold hover:bg-emerald-700 transition">
                Back to Dashboard
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="p-6 rounded-3xl bg-white/10 border border-white/10">
                <p class="text-slate-400 text-sm font-bold">Total Patients</p>
                <h2 class="text-4xl font-black text-white mt-2">{{ $patients->count() }}</h2>
            </div>

            <div class="p-6 rounded-3xl bg-white/10 border border-white/10">
                <p class="text-slate-400 text-sm font-bold">Doctor</p>
                <h2 class="text-xl font-black text-emerald-300 mt-2">
                    {{ $doctor->name ?? auth()->user()->name }}
                </h2>
            </div>

            <div class="p-6 rounded-3xl bg-white/10 border border-white/10">
                <p class="text-slate-400 text-sm font-bold">Specialty</p>
                <h2 class="text-xl font-black text-cyan-300 mt-2">
                    {{ $doctor->specialist ?? 'Not Added' }}
                </h2>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            @forelse($patients as $patient)

                @php
                    $lastAppointment = \App\Models\Appointment::where('doctor_id', $doctor->id ?? null)
                        ->where('patient_id', $patient->id)
                        ->latest()
                        ->first();

                    $totalAppointments = \App\Models\Appointment::where('doctor_id', $doctor->id ?? null)
                        ->where('patient_id', $patient->id)
                        ->count();
                @endphp

                <div class="p-6 rounded-3xl bg-white/10 border border-white/10 shadow-xl hover:bg-white/[0.13] transition">
                    <div class="flex items-start justify-between gap-5">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-cyan-500 flex items-center justify-center text-white text-2xl font-black">
                                {{ strtoupper(substr($patient->name, 0, 1)) }}
                            </div>

                            <div>
                                <h2 class="text-2xl font-black text-white">
                                    {{ $patient->name }}
                                </h2>
                                <p class="text-slate-300 text-sm mt-1">
                                    {{ $patient->email ?? 'No email' }}
                                </p>
                            </div>
                        </div>

                        <span class="px-4 py-2 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-black">
                            {{ $totalAppointments }} Visits
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6 text-sm">
                        <div class="p-4 rounded-2xl bg-white/10">
                            <p class="text-slate-400 font-bold">Phone</p>
                            <p class="text-white mt-1">{{ $patient->phone ?? 'N/A' }}</p>
                        </div>

                        <div class="p-4 rounded-2xl bg-white/10">
                            <p class="text-slate-400 font-bold">Gender / Age</p>
                            <p class="text-white mt-1">
                                {{ $patient->gender ?? 'N/A' }}
                                /
                                {{ $patient->age ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="p-4 rounded-2xl bg-white/10">
                            <p class="text-slate-400 font-bold">Last Appointment</p>
                            <p class="text-white mt-1">
                                @if($lastAppointment)
                                    {{ \Carbon\Carbon::parse($lastAppointment->appointment_date)->format('d M Y') }}
                                    •
                                    {{ \Carbon\Carbon::parse($lastAppointment->appointment_time)->format('h:i A') }}
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>

                        <div class="p-4 rounded-2xl bg-white/10">
                            <p class="text-slate-400 font-bold">Last Status</p>
                            <p class="mt-1">
                                @if($lastAppointment)
                                    <span class="px-3 py-1 rounded-full text-xs font-black
                                        @if($lastAppointment->status === 'Approved')
                                            bg-emerald-500/20 text-emerald-300
                                        @elseif($lastAppointment->status === 'Pending')
                                            bg-yellow-500/20 text-yellow-300
                                        @else
                                            bg-red-500/20 text-red-300
                                        @endif">
                                        {{ $lastAppointment->status }}
                                    </span>
                                @else
                                    <span class="text-white">N/A</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="mt-5 p-4 rounded-2xl bg-white/10">
                        <p class="text-slate-400 font-bold text-sm">Last Problem / Notes</p>
                        <p class="text-slate-200 mt-2 leading-7">
                            {{ $lastAppointment->problem ?? 'No notes added.' }}
                        </p>
                    </div>
                </div>

            @empty

                <div class="lg:col-span-2 p-16 rounded-3xl bg-white/10 border border-white/10 text-center">
                    <div class="text-6xl mb-5">👨‍👩‍👧</div>

                    <h2 class="text-2xl font-black text-white">
                        No patients found yet
                    </h2>

                    <p class="text-slate-300 mt-2">
                        Patients will appear here after booking appointments.
                    </p>
                </div>

            @endforelse

        </div>

    </div>
</div>
</x-app-layout>