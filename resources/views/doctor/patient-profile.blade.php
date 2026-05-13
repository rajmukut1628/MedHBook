<x-app-layout>
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 py-10 px-6">
    <div class="max-w-6xl mx-auto space-y-8">

        <div class="bg-white/10 border border-white/10 rounded-3xl p-8 text-white shadow-2xl">
            <p class="text-emerald-300 font-bold mb-2">Verified Patient Profile</p>

            <h1 class="text-4xl font-black">
                {{ $patient->name ?? $patient->user->name ?? 'Patient' }}
            </h1>

            <p class="text-slate-300 mt-2">
                Patient ID: P{{ $patient->id }}
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-white/10 border border-white/10 rounded-3xl p-6">
                <p class="text-slate-400 text-sm font-bold">Email</p>
                <h3 class="text-white font-black mt-2">
                    {{ $patient->email ?? $patient->user->email ?? 'N/A' }}
                </h3>
            </div>

            <div class="bg-white/10 border border-white/10 rounded-3xl p-6">
                <p class="text-slate-400 text-sm font-bold">Phone</p>
                <h3 class="text-white font-black mt-2">
                    {{ $patient->phone ?? 'N/A' }}
                </h3>
            </div>

            <div class="bg-white/10 border border-white/10 rounded-3xl p-6">
                <p class="text-slate-400 text-sm font-bold">Blood Group</p>
                <h3 class="text-white font-black mt-2">
                    {{ $patient->blood_group ?? 'N/A' }}
                </h3>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <a href="{{ route('doctor.patient.medical-documents', $patient->id) }}"
               class="bg-white/10 border border-white/10 rounded-3xl p-8 text-white hover:bg-white/15 transition shadow-xl">
                <div class="text-5xl mb-4">📂</div>
                <h2 class="text-2xl font-black">View Medical Documents</h2>
                <p class="text-slate-300 mt-2">
                    View patient uploaded medical documents securely.
                </p>
            </a>

            <a href="{{ route('prescriptions.create', ['patient_id' => $patient->id]) }}"
               class="bg-white/10 border border-white/10 rounded-3xl p-8 text-white hover:bg-white/15 transition shadow-xl">
                <div class="text-5xl mb-4">💊</div>
                <h2 class="text-2xl font-black">Create Prescription</h2>
                <p class="text-slate-300 mt-2">
                    Create online prescription for this verified patient.
                </p>
            </a>
        </div>

        <a href="{{ route('doctor.search.patient') }}"
           class="inline-block px-6 py-3 rounded-2xl bg-white/10 border border-white/10 text-white font-bold">
            Back
        </a>

    </div>
</div>
</x-app-layout>