<x-app-layout>
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 py-10 px-6">
    <div class="max-w-6xl mx-auto">

        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-extrabold text-white">Search Patient</h1>
                <p class="text-slate-300 mt-2">Type patient name, email, phone or unique ID instantly</p>
            </div>

            <a href="{{ route('doctor.dashboard') }}"
               class="px-5 py-3 rounded-xl bg-white/10 text-white border border-white/20">
                Back
            </a>
        </div>

        <div class="bg-white/10 border border-white/20 rounded-3xl p-6 mb-8">
            <input type="text"
                   id="liveSearch"
                   placeholder="Search: P1 / Salman / email / phone"
                   class="w-full rounded-2xl bg-slate-950/80 border border-white/20 text-white px-5 py-4 focus:ring-2 focus:ring-emerald-400">
        </div>

        <div id="patientGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            @foreach($patients as $patient)
                @php
                    $uid = 'P' . $patient->id;
                    $name = $patient->name ?? $patient->user->name ?? 'Patient';
                    $email = $patient->email ?? $patient->user->email ?? '';
                    $phone = $patient->phone ?? '';
                @endphp

                <div class="patient-card bg-white/10 border border-white/20 rounded-3xl p-6 text-white"
                     data-search="{{ strtolower($uid.' '.$name.' '.$email.' '.$phone) }}">

                    <div class="text-sm text-emerald-300 font-bold mb-2">
                        Unique ID: {{ $uid }}
                    </div>

                    <h2 class="text-2xl font-extrabold">{{ $name }}</h2>

                    <div class="mt-4 space-y-2 text-slate-300 text-sm">
                        <p><strong>Email:</strong> {{ $email ?: 'N/A' }}</p>
                        <p><strong>Phone:</strong> {{ $phone ?: 'N/A' }}</p>
                        <p><strong>Joined:</strong> {{ $patient->created_at ? \Carbon\Carbon::parse($patient->created_at)->format('d M Y') : 'N/A' }}</p>
                    </div>

                    <div class="mt-5">
                        <form method="GET" action="{{ route('prescriptions.create') }}">
                            <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                            <input type="text"
                                   name="privacy_key"
                                   placeholder="Enter Privacy Key"
                                   class="w-full mb-3 rounded-xl bg-slate-950/80 border border-white/20 text-white px-4 py-3"
                                   required>

                            <button type="submit"
                                    class="w-full rounded-xl bg-emerald-600 py-3 font-extrabold text-white">
                                Create Prescription
                            </button>
                        </form>
                    </div>

                </div>
            @endforeach

        </div>

        <div id="noResult" class="hidden text-center text-slate-300 mt-10 text-xl font-bold">
            No patient found
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('liveSearch');
    const cards = document.querySelectorAll('.patient-card');
    const noResult = document.getElementById('noResult');

    input.addEventListener('keyup', function () {
        const value = this.value.toLowerCase().trim();
        let found = 0;

        cards.forEach(card => {
            const data = card.getAttribute('data-search');

            if (data.includes(value)) {
                card.style.display = 'block';
                found++;
            } else {
                card.style.display = 'none';
            }
        });

        noResult.classList.toggle('hidden', found > 0);
    });
});
</script>
</x-app-layout>