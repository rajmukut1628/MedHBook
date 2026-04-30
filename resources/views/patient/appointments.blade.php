<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8">
        <div class="max-w-7xl mx-auto">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5 mb-8">
                <div>
                    <span class="inline-flex items-center px-4 py-2 rounded-full bg-emerald-100 text-emerald-700 text-sm font-extrabold mb-3">
                        📅 Appointments
                    </span>

                    <h1 class="text-4xl font-black text-slate-900">
                        My Appointments
                    </h1>

                    <p class="text-slate-500 mt-2">
                        Manage your doctor appointments and join online calls easily.
                    </p>
                </div>

                <a href="{{ route('appointments.create') }}"
                   class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-extrabold shadow-xl shadow-emerald-200 hover:-translate-y-1 transition">
                    + Add Appointment
                </a>
            </div>

            <div class="bg-white/85 backdrop-blur-xl rounded-[28px] shadow-2xl shadow-slate-200 border border-white overflow-hidden">

                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-black text-slate-800">
                            Appointments List
                        </h2>
                        <p class="text-sm text-slate-500 mt-1">
                            All your booked appointments are shown below.
                        </p>
                    </div>

                    <div class="hidden sm:flex items-center gap-2 px-4 py-2 rounded-full bg-slate-100 text-slate-700 font-bold">
                        Total: {{ $appointments->count() }}
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[900px]">
                        <thead>
                            <tr class="bg-slate-50 text-left">
                                <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-slate-500">ID</th>
                                <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-slate-500">Patient</th>
                                <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-slate-500">Doctor</th>
                                <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-slate-500">Date</th>
                                <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-slate-500">Time</th>
                                <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-slate-500">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse($appointments as $appointment)
                                <tr class="hover:bg-emerald-50/70 transition">
                                    <td class="px-6 py-5 font-bold text-slate-600">
                                        #{{ $appointment->id }}
                                    </td>

                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-600 text-white flex items-center justify-center font-black shadow-lg">
                                                {{ strtoupper(substr($appointment->patient->name ?? 'P', 0, 1)) }}
                                            </div>

                                            <div>
                                                <p class="font-black text-slate-800">
                                                    {{ $appointment->patient->name ?? '-' }}
                                                </p>
                                                <p class="text-xs text-slate-400">
                                                    Patient
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-5">
                                        <div>
                                            <p class="font-black text-slate-800">
                                                {{ $appointment->doctor->name ?? '-' }}
                                            </p>
                                            <p class="text-xs text-slate-400">
                                                Doctor
                                            </p>
                                        </div>
                                    </td>

                                    <td class="px-6 py-5 text-slate-600 font-semibold">
                                        {{ $appointment->appointment_date ?? '-' }}
                                    </td>

                                    <td class="px-6 py-5">
                                        <span class="px-4 py-2 rounded-full bg-blue-100 text-blue-700 text-sm font-black">
                                            {{ $appointment->appointment_time ?? '-' }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-5">
                                        <a href="https://meet.google.com"
                                           target="_blank"
                                           class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-black shadow-lg shadow-blue-200 hover:-translate-y-1 transition">
                                            Join Call
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-20 text-center">
                                        <div class="w-20 h-20 mx-auto rounded-3xl bg-emerald-100 flex items-center justify-center text-4xl mb-5">
                                            📅
                                        </div>

                                        <h3 class="text-2xl font-black text-slate-800">
                                            No appointments found
                                        </h3>

                                        <p class="text-slate-500 mt-2">
                                            When you book an appointment, it will appear here.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>