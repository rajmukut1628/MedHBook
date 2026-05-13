<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white leading-tight">
            Doctor Profile
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @php
                $doctorPhoto = $doctor->profile_photo ?? optional($doctor->user)->profile_photo ?? null;

                $chambers = $doctor->display_chambers ?? [];

                if (empty($chambers)) {
                    $chambers = [
                        [
                            'address' => $doctor->chamber_address ?? 'Not added',
                            'working_days' => $doctor->working_days ?? [],
                            'start_time' => $doctor->start_time ?? null,
                            'end_time' => $doctor->end_time ?? null,
                            'fee' => $doctor->consultation_fee ?? 0,
                        ]
                    ];
                }

                $lowestFee = collect($chambers)->pluck('fee')->filter()->min() ?? ($doctor->consultation_fee ?? 0);
                $firstChamberIndex = count($chambers) > 0 ? 0 : null;
            @endphp

            {{-- HERO SECTION --}}
            <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl shadow-2xl overflow-hidden">
                <div class="h-44 bg-gradient-to-r from-emerald-500 via-cyan-500 to-blue-500"></div>

                <div class="p-8 -mt-20">
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">

                        <div class="flex flex-col md:flex-row md:items-end gap-6">
                            @if($doctorPhoto)
                                <img src="{{ route('secure.file.show', [
                                        'folder' => 'doctor-photos',
                                        'filename' => basename($doctorPhoto)
                                    ]) }}"
                                     class="w-36 h-36 rounded-3xl object-cover border-4 border-white shadow-2xl">
                            @else
                                <div class="w-36 h-36 rounded-3xl bg-slate-900 border-4 border-white flex items-center justify-center text-white text-5xl font-black shadow-2xl">
                                    {{ strtoupper(substr($doctor->name, 0, 1)) }}
                                </div>
                            @endif

                            <div>
                                <div class="flex items-center gap-3 flex-wrap">
                                    <h1 class="text-4xl font-black text-white">
                                        Dr. {{ $doctor->name }}
                                    </h1>

                                    @if($doctor->verification_status === 'approved')
                                        <span class="px-4 py-1 rounded-full bg-emerald-500 text-white text-sm font-bold">
                                            Verified
                                        </span>
                                    @endif

                                    <span class="px-4 py-1 rounded-full bg-cyan-500/20 border border-cyan-300/30 text-cyan-200 text-sm font-bold">
                                        🔐 Secure Profile
                                    </span>
                                </div>

                                <p class="text-emerald-300 text-xl font-bold mt-2">
                                    {{ $doctor->specialist ?? $doctor->specialization ?? 'Doctor' }}
                                </p>

                                <p class="text-slate-300 mt-1">
                                    {{ $doctor->email }}
                                </p>

                                <p id="selectedChamberText" class="text-cyan-200 mt-3 text-sm font-bold">
                                    Selected Chamber: Chamber #1
                                </p>
                            </div>
                        </div>

                        @auth
                            @if(auth()->user()->role === 'patient')
                                <a id="topBookBtn"
                                   href="{{ route('appointments.create', ['doctor_id' => $doctor->id, 'chamber_index' => $firstChamberIndex]) }}"
                                   class="px-7 py-4 rounded-2xl bg-gradient-to-r from-emerald-500 to-cyan-500 text-white font-black shadow-xl hover:scale-[1.03] transition text-center">
                                    Book Appointment
                                </a>
                            @endif
                        @endauth

                    </div>
                </div>
            </div>

            {{-- STATS CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl p-6 shadow-xl">
                    <p class="text-slate-400 text-sm font-bold">Specialty</p>
                    <h3 class="text-white text-xl font-black mt-2">
                        {{ $doctor->specialist ?? $doctor->specialization ?? 'Not added' }}
                    </h3>
                </div>

                <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl p-6 shadow-xl">
                    <p class="text-slate-400 text-sm font-bold">Experience</p>
                    <h3 class="text-white text-xl font-black mt-2">
                        {{ $doctor->experience ?? 0 }} Years
                    </h3>
                </div>

                <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl p-6 shadow-xl">
                    <p class="text-slate-400 text-sm font-bold">Starting Fee</p>
                    <h3 class="text-white text-xl font-black mt-2">
                        ৳ {{ $lowestFee }}
                    </h3>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                {{-- PROFESSIONAL INFO --}}
                <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl p-8 shadow-2xl">
                    <h2 class="text-2xl font-black text-white mb-6">
                        Professional Information
                    </h2>

                    <div class="space-y-5">
                        <div>
                            <p class="text-slate-400 text-sm font-bold">Degree</p>
                            <p class="text-white text-lg mt-1">{{ $doctor->degree ?? 'Not added' }}</p>
                        </div>

                        <div>
                            <p class="text-slate-400 text-sm font-bold">Qualification</p>
                            <p class="text-white text-lg mt-1">{{ $doctor->qualification ?? 'Not added' }}</p>
                        </div>

                        <div>
                            <p class="text-slate-400 text-sm font-bold">Phone</p>
                            <p class="text-white text-lg mt-1">{{ $doctor->phone ?? 'Not added' }}</p>
                        </div>

                        <div>
                            <p class="text-slate-400 text-sm font-bold">Gender</p>
                            <p class="text-white text-lg mt-1">{{ $doctor->gender ?? 'Not added' }}</p>
                        </div>

                        <div>
                            <p class="text-slate-400 text-sm font-bold">Blood Group</p>
                            <p class="text-white text-lg mt-1">{{ $doctor->blood_group ?? 'Not added' }}</p>
                        </div>
                    </div>
                </div>
                                {{-- CHAMBERS --}}
                <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl p-8 shadow-2xl">
                    <div class="flex items-center justify-between gap-4 mb-6">
                        <div>
                            <h2 class="text-2xl font-black text-white">
                                Chambers & Schedule
                            </h2>
                            <p class="text-slate-400 mt-1 text-sm">
                                Select one chamber, then use the top Book Appointment button.
                            </p>
                        </div>

                        <span class="px-4 py-2 rounded-full bg-emerald-500/20 text-emerald-300 text-sm font-bold">
                            {{ count($chambers) }} Chamber{{ count($chambers) > 1 ? 's' : '' }}
                        </span>
                    </div>

                    <div class="space-y-5">
                        @foreach($chambers as $index => $chamber)
                            <button type="button"
                                    data-chamber-index="{{ $index }}"
                                    data-book-url="{{ route('appointments.create', ['doctor_id' => $doctor->id, 'chamber_index' => $index]) }}"
                                    class="chamber-card w-full text-left rounded-3xl bg-slate-950/60 border border-white/10 p-6 hover:border-emerald-400/50 hover:bg-slate-950/80 transition shadow-xl {{ $index === 0 ? 'selected-chamber' : '' }}">

                                <div class="flex items-center justify-between gap-4 mb-4">
                                    <h3 class="text-xl font-black text-white">
                                        🏥 Chamber #{{ $index + 1 }}
                                    </h3>

                                    <span class="px-4 py-1 rounded-full bg-cyan-500/20 text-cyan-200 border border-cyan-400/30 text-sm font-bold">
                                        ৳ {{ $chamber['fee'] ?? 0 }}
                                    </span>
                                </div>

                                <div class="space-y-4">
                                    <div>
                                        <p class="text-slate-400 text-sm font-bold">Address</p>
                                        <p class="text-white mt-1 whitespace-pre-line leading-7">
                                            {{ $chamber['address'] ?? 'Not added' }}
                                        </p>
                                    </div>

                                    <div>
                                        <p class="text-slate-400 text-sm font-bold mb-2">Working Days</p>

                                        <div class="flex flex-wrap gap-2">
                                            @forelse(($chamber['working_days'] ?? []) as $day)
                                                <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-200 border border-emerald-400/30 text-sm font-bold">
                                                    {{ $day }}
                                                </span>
                                            @empty
                                                <span class="text-white">Not added</span>
                                            @endforelse
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="rounded-2xl bg-white/5 border border-white/10 p-4">
                                            <p class="text-slate-400 text-sm font-bold">Start Time</p>
                                            <p class="text-white font-black mt-1">
                                                {{ $chamber['start_time'] ?? 'Not set' }}
                                            </p>
                                        </div>

                                        <div class="rounded-2xl bg-white/5 border border-white/10 p-4">
                                            <p class="text-slate-400 text-sm font-bold">End Time</p>
                                            <p class="text-white font-black mt-1">
                                                {{ $chamber['end_time'] ?? 'Not set' }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="pt-2">
                                        <span class="select-label inline-flex px-4 py-2 rounded-2xl bg-white/10 text-white font-black text-sm">
                                            {{ $index === 0 ? 'Selected Chamber' : 'Select This Chamber' }}
                                        </span>
                                    </div>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- ABOUT DOCTOR --}}
            <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl p-8 shadow-2xl">
                <h2 class="text-2xl font-black text-white mb-4">
                    About Doctor
                </h2>

                <p class="text-slate-300 leading-8 text-lg">
                    {{ $doctor->bio ?? 'No bio added yet.' }}
                </p>
            </div>
            {{-- ACTIONS --}}
            <div class="flex flex-wrap gap-4">
                <a href="{{ url()->previous() }}"
                   class="px-6 py-3 rounded-2xl bg-white/10 border border-white/10 text-white font-bold hover:bg-white/20 transition">
                    Back
                </a>
            </div>

        </div>
    </div>

    <style>
        .selected-chamber{
            border-color:rgba(16,185,129,.9) !important;
            background:linear-gradient(135deg,rgba(16,185,129,.20),rgba(6,182,212,.12)) !important;
            box-shadow:0 0 0 3px rgba(16,185,129,.18), 0 20px 45px rgba(0,0,0,.28);
        }
    </style>

    <script>
document.addEventListener('DOMContentLoaded', function () {
    const chamberCards = document.querySelectorAll('.chamber-card');
    const bookBtn = document.getElementById('topBookBtn');
    const selectedText = document.getElementById('selectedChamberText');

    const authRole = @json(auth()->check() ? auth()->user()->role : null);

    chamberCards.forEach(function (card) {
        card.addEventListener('click', function () {
            const chamberIndex = card.dataset.chamberIndex;
            const bookUrl = card.dataset.bookUrl;

            chamberCards.forEach(function (item) {
                item.classList.remove('selected-chamber');

                const label = item.querySelector('.select-label');
                if (label) {
                    label.innerText = 'Select This Chamber';
                }
            });

            card.classList.add('selected-chamber');

            const selectedLabel = card.querySelector('.select-label');
            if (selectedLabel) {
                selectedLabel.innerText = 'Selected Chamber';
            }

            if (selectedText) {
                selectedText.innerText = 'Selected Chamber: Chamber #' + (parseInt(chamberIndex) + 1);
            }

            if (authRole === 'patient') {
                if (bookBtn && bookUrl) {
                    bookBtn.href = bookUrl;
                }

                window.location.href = bookUrl;
            }
        });
    });
});
</script>
</x-app-layout>