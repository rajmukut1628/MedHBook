<x-app-layout>
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 py-10 px-6">
    <div class="max-w-5xl mx-auto">

        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-extrabold text-white">Prescription Details</h1>
                <p class="text-slate-300 mt-2">Patient medical prescription summary</p>
            </div>

            <a href="{{ route('prescriptions.index') }}"
               class="px-5 py-3 rounded-2xl bg-white/10 text-white border border-white/20 hover:bg-white/20">
                Back
            </a>
        </div>

        <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl shadow-2xl p-8">

            @php
                $patientName = $prescription->patient->name
                    ?? optional($prescription->patient->user)->name
                    ?? 'Patient';

                $doctorName = $prescription->doctor->name
                    ?? optional($prescription->doctor->user)->name
                    ?? 'Doctor Name Not Found';

                $doctorSpecialist = $prescription->doctor->specialist
                    ?? $prescription->doctor->specialization
                    ?? 'Specialist';
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

                <div class="rounded-2xl bg-white/5 p-5 border border-white/10">
                    <p class="text-slate-400 text-sm">Patient Name</p>
                    <h3 class="text-xl font-bold text-white mt-1">
                        {{ $patientName }}
                    </h3>
                </div>

                <div class="rounded-2xl bg-white/5 p-5 border border-white/10">
                    <p class="text-slate-400 text-sm">Doctor Name</p>
                    <h3 class="text-xl font-bold text-white mt-1">
                        {{ $doctorName }}
                    </h3>
                    <p class="text-emerald-300 text-sm mt-1">
                        {{ $doctorSpecialist }}
                    </p>
                </div>

                <div class="rounded-2xl bg-white/5 p-5 border border-white/10">
                    <p class="text-slate-400 text-sm">Prescription Date</p>
                    <h3 class="text-xl font-bold text-white mt-1">
                        {{ $prescription->prescription_date ? \Carbon\Carbon::parse($prescription->prescription_date)->format('d M, Y') : 'N/A' }}
                    </h3>
                </div>

                <div class="rounded-2xl bg-white/5 p-5 border border-white/10">
                    <p class="text-slate-400 text-sm">Next Visit</p>
                    <h3 class="text-xl font-bold text-white mt-1">
                        {{ $prescription->next_visit_date ? \Carbon\Carbon::parse($prescription->next_visit_date)->format('d M, Y') : 'Not Set' }}
                    </h3>
                </div>

            </div>

            <div class="space-y-6">

                <div class="rounded-2xl bg-white/5 p-6 border border-white/10">
                    <h2 class="text-lg font-bold text-emerald-300 mb-3">Diagnosis</h2>
                    <p class="text-slate-200 whitespace-pre-line">
                        {{ $prescription->diagnosis }}
                    </p>
                </div>

                <div class="rounded-2xl bg-white/5 p-6 border border-white/10">
                    <h2 class="text-lg font-bold text-blue-300 mb-3">Medicines</h2>
                    <p class="text-slate-200 whitespace-pre-line">
                        {{ $prescription->medicines }}
                    </p>
                </div>

                <div class="rounded-2xl bg-white/5 p-6 border border-white/10">
                    <h2 class="text-lg font-bold text-yellow-300 mb-3">Advice</h2>
                    <p class="text-slate-200 whitespace-pre-line">
                        {{ $prescription->advice ?? 'No advice added.' }}
                    </p>
                </div>

            </div>

            <div class="mt-8 flex gap-4">
                <a href="{{ route('prescriptions.index') }}"
                   class="px-6 py-3 rounded-2xl bg-white/10 text-white border border-white/20">
                    Back List
                </a>

                <a href="{{ route('prescriptions.create') }}"
                   class="px-6 py-3 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-bold">
                    New Prescription
                </a>
            </div>

        </div>

    </div>
</div>
</x-app-layout>