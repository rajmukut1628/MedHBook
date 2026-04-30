<x-app-layout>
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 py-10 px-6">
    <div class="max-w-5xl mx-auto">

        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-extrabold text-white">Create Prescription</h1>
                <p class="text-slate-300 mt-2">Search patient by ID and prescribe easily</p>
            </div>

            <a href="{{ route('doctor.dashboard') }}"
               class="px-5 py-3 rounded-xl bg-white/10 text-white border border-white/20 hover:bg-white/20">
                Back
            </a>
        </div>

        @if(session('error'))
            <div class="mb-6 rounded-2xl bg-red-500/10 border border-red-400/30 text-red-200 p-5 font-bold">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('prescriptions.store') }}"
              class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-8 shadow-2xl">
            @csrf

            {{-- Privacy Key --}}
            <div class="mb-6">
                <label class="text-sm font-bold text-slate-200 mb-2 block">
                    Patient Privacy Key
                </label>

                <input type="text"
                       name="privacy_key"
                       placeholder="Enter patient privacy key"
                       class="w-full rounded-2xl bg-slate-900/80 border border-white/20 text-white px-4 py-3">
            </div>

            <div class="grid md:grid-cols-2 gap-6">

                {{-- SEARCH + SELECT --}}
                <div>
                    <label class="text-sm font-bold text-slate-200 mb-2 block">
                        Search Patient (P1, P2, Name)
                    </label>

                    <input type="text"
                           id="patientSearch"
                           placeholder="Example: P1 or Salman"
                           class="w-full mb-3 rounded-2xl bg-slate-900/80 border border-white/20 text-white px-4 py-3">

                    <select name="patient_id"
                            id="patientSelect"
                            class="w-full rounded-2xl bg-slate-900/80 border border-white/20 text-white px-4 py-3"
                            required>

                        <option value="">Choose Patient</option>

                        @foreach($patients as $patient)
                            @php
                                $uid = 'P' . $patient->id;
                                $name = $patient->name ?? $patient->user->name ?? 'Patient';
                            @endphp

                            <option value="{{ $patient->id }}"
                                data-search="{{ strtolower($uid.' '.$name) }}">
                                {{ $uid }} - {{ $name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- DATE --}}
                <div>
                    <label class="text-sm font-bold text-slate-200 mb-2 block">
                        Prescription Date
                    </label>

                    <input type="date"
                           name="prescription_date"
                           value="{{ date('Y-m-d') }}"
                           class="w-full rounded-2xl bg-slate-900/80 border border-white/20 text-white px-4 py-3">
                </div>

                {{-- DIAGNOSIS --}}
                <div class="md:col-span-2">
                    <label class="text-sm font-bold text-slate-200 mb-2 block">
                        Diagnosis
                    </label>

                    <textarea name="diagnosis"
                              rows="4"
                              class="w-full rounded-2xl bg-slate-900/80 border border-white/20 text-white px-4 py-3"></textarea>
                </div>

                {{-- MEDICINES --}}
                <div class="md:col-span-2">
                    <label class="text-sm font-bold text-slate-200 mb-2 block">
                        Medicines
                    </label>

                    <textarea name="medicines"
                              rows="5"
                              class="w-full rounded-2xl bg-slate-900/80 border border-white/20 text-white px-4 py-3"></textarea>
                </div>

                {{-- ADVICE --}}
                <div class="md:col-span-2">
                    <label class="text-sm font-bold text-slate-200 mb-2 block">
                        Advice
                    </label>

                    <textarea name="advice"
                              rows="4"
                              class="w-full rounded-2xl bg-slate-900/80 border border-white/20 text-white px-4 py-3"></textarea>
                </div>

                {{-- NEXT VISIT --}}
                <div>
                    <label class="text-sm font-bold text-slate-200 mb-2 block">
                        Next Visit Date
                    </label>

                    <input type="date"
                           name="next_visit_date"
                           class="w-full rounded-2xl bg-slate-900/80 border border-white/20 text-white px-4 py-3">
                </div>

            </div>

            <div class="mt-8 text-right">
                <button type="submit"
                        class="px-8 py-3 rounded-2xl bg-emerald-600 text-white font-bold">
                    Save Prescription
                </button>
            </div>

        </form>

    </div>
</div>

<script>
document.getElementById('patientSearch').addEventListener('keyup', function () {
    let search = this.value.toLowerCase();
    let options = document.querySelectorAll('#patientSelect option');

    options.forEach((option, index) => {
        if(index === 0) return;

        let text = option.getAttribute('data-search');
        option.style.display = text.includes(search) ? 'block' : 'none';
    });
});
</script>

</x-app-layout>