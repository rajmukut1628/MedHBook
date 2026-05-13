<x-app-layout>
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 py-10 px-6">
    <div class="max-w-7xl mx-auto space-y-8">

        {{-- HEADER --}}
        <div class="bg-white/10 border border-white/10 rounded-3xl p-8 shadow-2xl text-white">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <span class="inline-flex px-4 py-2 rounded-full bg-emerald-500/20 text-emerald-300 text-sm font-bold mb-3">
                        🔐 Verified Access
                    </span>

                    <h1 class="text-4xl font-black">
                        {{ $patient->name ?? optional($patient->user)->name ?? 'Patient' }}
                    </h1>

                    <p class="text-slate-300 mt-2">
                        Patient ID: P{{ $patient->id }} • Secure Medical Documents
                    </p>
                </div>

                <a href="{{ route('doctor.patient.profile', $patient->id) }}"
                   class="px-6 py-3 rounded-2xl bg-white/10 border border-white/10 text-white font-bold hover:bg-white/20 transition">
                    Back to Profile
                </a>
            </div>
        </div>

        {{-- DOCUMENT GROUPS --}}
        @forelse($documents as $type => $group)
            <div class="bg-white/10 border border-white/10 rounded-3xl p-6 shadow-2xl">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-2xl font-black text-white">
                        {{ $type }}
                    </h2>

                    <span class="px-4 py-2 rounded-full bg-cyan-500/20 text-cyan-200 text-sm font-bold">
                        {{ $group->count() }} File{{ $group->count() > 1 ? 's' : '' }}
                    </span>
                </div>

                <div class="space-y-4">
                    @foreach($group as $document)
                        <div class="p-5 rounded-2xl bg-white/5 border border-white/10">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                                <div>
                                    <h3 class="text-white font-black text-lg">
                                        {{ $document->title }}
                                    </h3>

                                    <div class="mt-2 space-y-1 text-sm text-slate-300">
                                        <p>
                                            <strong>Original File:</strong>
                                            {{ $document->original_name ?? 'N/A' }}
                                        </p>

                                        <p>
                                            <strong>Date:</strong>
                                            {{ $document->document_date
                                                ? \Carbon\Carbon::parse($document->document_date)->format('d M Y')
                                                : 'N/A' }}
                                        </p>

                                        <p>
                                            <strong>Doctor:</strong>
                                            {{ $document->doctor_name ?? 'N/A' }}
                                        </p>

                                        <p>
                                            <strong>Hospital:</strong>
                                            {{ $document->hospital_name ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-3">
                                    <a href="{{ route('medical-documents.show', $document->id) }}"
                                       class="px-5 py-3 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-500 transition">
                                        View
                                    </a>

                                    <a href="{{ route('medical-documents.download', $document->id) }}"
                                       class="px-5 py-3 rounded-xl bg-emerald-600 text-white font-bold hover:bg-emerald-500 transition">
                                        Download
                                    </a>
                                </div>

                            </div>

                            @if($document->notes)
                                <div class="mt-4 p-4 rounded-2xl bg-white/5 border border-white/10">
                                    <p class="text-slate-400 text-sm font-bold mb-1">Notes</p>
                                    <p class="text-slate-300">{{ $document->notes }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="bg-white/10 border border-white/10 rounded-3xl p-10 text-center shadow-2xl">
                <div class="text-6xl mb-4">📂</div>
                <h2 class="text-3xl font-black text-white mb-2">
                    No Medical Documents Found
                </h2>
                <p class="text-slate-300">
                    This patient has not uploaded any medical documents yet.
                </p>
            </div>
        @endforelse

        {{-- BACK --}}
        <div>
            <a href="{{ route('doctor.search.patient') }}"
               class="inline-flex px-6 py-3 rounded-2xl bg-white/10 border border-white/10 text-white font-bold hover:bg-white/20 transition">
                ← Back to Search Patient
            </a>
        </div>

    </div>
</div>
</x-app-layout>