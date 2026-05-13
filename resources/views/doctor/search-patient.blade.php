<x-app-layout>
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 py-10 px-6">
    <div class="max-w-6xl mx-auto">

        {{-- HEADER --}}
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-extrabold text-white">Search Patient</h1>
                <p class="text-slate-300 mt-2">
                    Search by Patient ID, Name, Email or Phone and verify privacy key to access secure profile.
                </p>
            </div>

            <a href="{{ route('doctor.dashboard') }}"
               class="px-5 py-3 rounded-xl bg-white/10 text-white border border-white/20 hover:bg-white/20 transition">
                Back
            </a>
        </div>

        {{-- ALERTS --}}
        @if(session('success'))
            <div class="mb-6 rounded-2xl bg-emerald-500/10 border border-emerald-400/30 text-emerald-200 p-5 font-bold">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 rounded-2xl bg-red-500/10 border border-red-400/30 text-red-200 p-5 font-bold">
                {{ session('error') }}
            </div>
        @endif

        {{-- SEARCH BOX --}}
        <div class="bg-white/10 border border-white/20 rounded-3xl p-6 mb-8 shadow-2xl">
            <input type="text"
                   id="liveSearch"
                   placeholder="Search: P1 / Salman / email / phone"
                   class="w-full rounded-2xl bg-slate-950/80 border border-white/20 text-white px-5 py-4 focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 outline-none">
        </div>

        {{-- START MESSAGE --}}
        <div id="startMessage" class="text-center text-slate-300 mt-10 text-xl font-bold">
            Search patient to continue.
        </div>

        {{-- PATIENT GRID --}}
        <div id="patientGrid" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            @forelse($patients as $patient)
                @php
                    $uid = 'P' . $patient->id;
                    $name = $patient->name ?? optional($patient->user)->name ?? 'Patient';
                    $email = $patient->email ?? optional($patient->user)->email ?? '';
                    $phone = $patient->phone ?? '';
                    $gender = $patient->gender ?? 'N/A';
                    $bloodGroup = $patient->blood_group ?? 'N/A';
                    $age = $patient->age ?? 'N/A';
                @endphp

                <div class="patient-card hidden bg-white/10 border border-white/20 rounded-3xl p-6 text-white shadow-xl hover:scale-[1.02] hover:bg-white/15 transition"
                     data-search="{{ strtolower($uid.' '.$name.' '.$email.' '.$phone) }}">

                    <div class="inline-flex px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/20 text-emerald-300 text-xs font-black mb-4">
                        Unique ID: {{ $uid }}
                    </div>

                    <h2 class="text-2xl font-extrabold">
                        {{ $name }}
                    </h2>

                    <div class="mt-4 space-y-2 text-slate-300 text-sm">
                        <p><strong>Email:</strong> {{ $email ?: 'N/A' }}</p>
                        <p><strong>Phone:</strong> {{ $phone ?: 'N/A' }}</p>
                        <p><strong>Age:</strong> {{ $age }}</p>
                        <p><strong>Gender:</strong> {{ $gender }}</p>
                        <p><strong>Blood Group:</strong> {{ $bloodGroup }}</p>
                        <p>
                            <strong>Joined:</strong>
                            {{ $patient->created_at
                                ? \Carbon\Carbon::parse($patient->created_at)->format('d M Y')
                                : 'N/A' }}
                        </p>
                    </div>

                    {{-- PRIVACY VERIFICATION --}}
                    <div class="mt-6">
                        <form method="POST"
                              action="{{ route('doctor.patient.verify.privacy', $patient->id) }}">
                            @csrf

                            <label class="block text-sm font-bold text-slate-300 mb-2">
                                Patient Privacy Key
                            </label>

                            <input type="text"
                                   name="privacy_key"
                                   placeholder="Enter Privacy Key"
                                   class="w-full mb-4 rounded-xl bg-slate-950/80 border border-white/20 text-white px-4 py-3 focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 outline-none"
                                   required>

                            <button type="submit"
                                    class="w-full rounded-xl bg-gradient-to-r from-emerald-600 to-cyan-600 py-3 font-extrabold text-white shadow-lg hover:from-emerald-500 hover:to-cyan-500 transition">
                                👤 View Profile
                            </button>
                        </form>
                    </div>

                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white/10 border border-white/10 rounded-3xl p-10 text-center text-slate-300">
                        No patients found.
                    </div>
                </div>
            @endforelse

        </div>

        {{-- NO RESULT --}}
        <div id="noResult"
             class="hidden text-center text-slate-300 mt-10 text-xl font-bold">
            No patient found
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('liveSearch');
    const grid = document.getElementById('patientGrid');
    const cards = document.querySelectorAll('.patient-card');
    const noResult = document.getElementById('noResult');
    const startMessage = document.getElementById('startMessage');

    input.addEventListener('keyup', function () {
        const value = this.value.toLowerCase().trim();
        let found = 0;

        if (value === '') {
            grid.classList.add('hidden');
            startMessage.classList.remove('hidden');
            noResult.classList.add('hidden');

            cards.forEach(card => {
                card.classList.add('hidden');
            });

            return;
        }

        startMessage.classList.add('hidden');
        grid.classList.remove('hidden');

        cards.forEach(card => {
            const data = card.getAttribute('data-search') || '';

            if (data.includes(value)) {
                card.classList.remove('hidden');
                found++;
            } else {
                card.classList.add('hidden');
            }
        });

        noResult.classList.toggle('hidden', found > 0);
    });
});
</script>
</x-app-layout>