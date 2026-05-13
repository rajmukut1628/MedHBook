<x-app-layout>

@php
    $doctorSearch = request('doctor_search');
    $selectedDoctorId = old('doctor_id', request('doctor_id'));
    $selectedChamberIndex = old('chamber_index', request('chamber_index'));

    $allDoctors = collect($doctors ?? []);
    $filteredDoctors = collect();

    if ($doctorSearch) {
        $search = strtolower(trim($doctorSearch));

        $map = [
            'cardiology' => ['heart', 'cardio', 'cardi', 'chest', 'বুক', 'হৃদ', 'হার্ট'],
            'neurology' => ['brain', 'head', 'মাথা', 'neuro', 'migraine', 'স্নায়ু'],
            'dermatology' => ['skin', 'চামড়া', 'চর্ম', 'rash', 'derma'],
            'ophthalmologist' => ['eye', 'চোখ', 'vision'],
            'medicine' => ['fever', 'cold', 'জ্বর', 'কাশি', 'general'],
            'pediatrician' => ['child', 'baby', 'kids', 'শিশু'],
            'orthopedic' => ['bone', 'joint', 'হাড়', 'back pain'],
            'dentist' => ['tooth', 'teeth', 'দাঁত', 'tooth pain'],
            'ent specialist' => ['ear', 'nose', 'throat', 'কান', 'নাক', 'গলা'],
            'gynecologist' => ['pregnancy', 'period', 'গর্ভ'],
            'nephrologist' => ['kidney', 'কিডনি'],
            'endocrinologist' => ['diabetes', 'ডায়াবেটিস'],
            'gastroenterologist' => ['stomach', 'পেট', 'gas', 'acidity'],
        ];

        $matched = [];

        foreach ($map as $specialist => $keywords) {
            foreach ($keywords as $word) {
                if (str_contains($search, $word)) {
                    $matched[] = $specialist;
                }
            }
        }

        $filteredDoctors = $allDoctors->filter(function ($doctor) use ($search, $matched) {
            $name = strtolower($doctor->name ?? '');
            $email = strtolower($doctor->email ?? '');
            $phone = strtolower($doctor->phone ?? '');
            $specialist = strtolower($doctor->specialist ?? '');
            $specialization = strtolower($doctor->specialization ?? '');
            $chamber = strtolower($doctor->chamber_address ?? '');

            $normalMatch =
                str_contains($name, $search) ||
                str_contains($email, $search) ||
                str_contains($phone, $search) ||
                str_contains($specialist, $search) ||
                str_contains($specialization, $search) ||
                str_contains($chamber, $search);

            $mappedMatch = false;

            foreach ($matched as $sp) {
                if (
                    str_contains($specialist, $sp) ||
                    str_contains($specialization, $sp)
                ) {
                    $mappedMatch = true;
                    break;
                }
            }

            return $normalMatch || $mappedMatch;
        })->take(10);
    }

    $chambers = [];

    if (isset($selectedDoctor) && $selectedDoctor) {
        $chambers = $selectedDoctor->display_chambers ?? [];

        if (empty($chambers)) {
            $chambers = [
                [
                    'address' => $selectedDoctor->chamber_address ?? 'Not added',
                    'working_days' => $selectedDoctor->working_days ?? [],
                    'start_time' => $selectedDoctor->start_time ?? null,
                    'end_time' => $selectedDoctor->end_time ?? null,
                    'fee' => $selectedDoctor->consultation_fee ?? 0,
                ]
            ];
        }
    }

    $selectedChamberAddress = old('chamber_address', request('chamber_address'));
    $selectedChamber = null;

    if (!empty($chambers)) {
        if ($selectedChamberAddress) {
            foreach ($chambers as $index => $chamber) {
                if (($chamber['address'] ?? '') === $selectedChamberAddress) {
                    $selectedChamber = $chamber;
                    $selectedChamberIndex = $index;
                    break;
                }
            }
        }

        if (!$selectedChamber && $selectedChamberIndex !== null && $selectedChamberIndex !== '') {
            $index = (int) $selectedChamberIndex;

            if (isset($chambers[$index])) {
                $selectedChamber = $chambers[$index];
                $selectedChamberAddress = $selectedChamber['address'] ?? '';
            }
        }

        if (!$selectedChamber) {
            $selectedChamber = $chambers[0] ?? null;
            $selectedChamberIndex = 0;
            $selectedChamberAddress = $selectedChamber['address'] ?? '';
        }
    }

    $days = $selectedChamber['working_days'] ?? [];
    $startTime = $selectedChamber['start_time'] ?? '';
    $endTime = $selectedChamber['end_time'] ?? '';
    $fee = $selectedChamber['fee'] ?? 0;
@endphp
<style>
    @keyframes pageFadeUp {
        from { opacity: 0; transform: translateY(24px) scale(.985); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    @keyframes orbFloat {
        0%,100% { transform: translateY(0) translateX(0); }
        50% { transform: translateY(-18px) translateX(12px); }
    }

    @keyframes shineMove {
        0% { transform: translateX(-130%); }
        100% { transform: translateX(130%); }
    }

    @keyframes glowPulse {
        0%,100% { box-shadow: 0 0 30px rgba(16,185,129,.20); }
        50% { box-shadow: 0 0 75px rgba(34,211,238,.32); }
    }

    .appointment-page {
        animation: pageFadeUp .7s ease both;
    }

    .appointment-orb {
        animation: orbFloat 7s ease-in-out infinite;
    }

    .glass-card {
        background:
            linear-gradient(135deg, rgba(15,23,42,.88), rgba(15,23,42,.62)),
            radial-gradient(circle at top left, rgba(16,185,129,.18), transparent 35%),
            radial-gradient(circle at bottom right, rgba(34,211,238,.14), transparent 38%);
        border: 1px solid rgba(255,255,255,.13);
        backdrop-filter: blur(26px);
        -webkit-backdrop-filter: blur(26px);
    }

    .premium-card {
        position: relative;
        overflow: hidden;
        transition: all .3s ease;
    }

    .premium-card::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(120deg, transparent, rgba(255,255,255,.14), transparent);
        transform: translateX(-130%);
    }

    .premium-card:hover::before {
        animation: shineMove 1.1s ease;
    }

    .premium-card:hover {
        transform: translateY(-5px) scale(1.01);
        border-color: rgba(45,212,191,.45);
    }

    .hero-glow {
        animation: glowPulse 4s ease-in-out infinite;
    }

    .premium-btn {
        transition: all .25s ease;
    }

    .premium-btn:hover {
        transform: translateY(-2px) scale(1.02);
    }

    .premium-btn:active {
        transform: scale(.97);
    }

    .premium-input {
        background: rgba(255,255,255,.10);
        border: 1px solid rgba(255,255,255,.18);
        color: white;
    }

    .premium-input:focus {
        border-color: rgba(45,212,191,.9);
        box-shadow: 0 0 0 4px rgba(45,212,191,.16);
        outline: none;
    }

    .premium-input option {
        background: #0f172a;
        color: #ffffff;
    }

    .slot-select option:disabled {
        color: #94a3b8;
    }

    .section-title {
        color: white;
        font-weight: 900;
        letter-spacing: -.02em;
    }
</style>

<div class="relative min-h-screen overflow-hidden bg-slate-950 py-10 px-4 sm:px-6 lg:px-8">

    {{-- Animated Background --}}
    <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950"></div>
    <div class="absolute -top-44 -left-44 w-[540px] h-[540px] rounded-full bg-emerald-500/20 blur-3xl appointment-orb"></div>
    <div class="absolute top-24 -right-44 w-[540px] h-[540px] rounded-full bg-cyan-500/18 blur-3xl appointment-orb"></div>
    <div class="absolute bottom-0 left-1/3 w-[620px] h-[620px] rounded-full bg-blue-500/10 blur-3xl appointment-orb"></div>

    <div class="relative z-10 max-w-6xl mx-auto appointment-page">

        {{-- Header --}}
        <div class="glass-card hero-glow rounded-[34px] p-7 sm:p-8 text-white shadow-2xl mb-8">
            <span class="inline-flex px-4 py-2 rounded-full bg-emerald-500/15 border border-emerald-300/20 text-emerald-200 text-sm font-black mb-4">
                📅 Premium Appointment Booking
            </span>

            <h1 class="text-4xl md:text-5xl font-black">
                Book Appointment
            </h1>

            <p class="text-slate-300 mt-3 max-w-3xl leading-7">
                Select a doctor, choose the correct chamber, pick an available date, and book a valid 5-minute slot.
            </p>
        </div>

        @if(session('success'))
            <div class="mb-5 p-4 rounded-2xl bg-emerald-500/20 text-emerald-200 border border-emerald-400/30 font-bold">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-5 p-4 rounded-2xl bg-red-500/20 text-red-200 border border-red-400/30 font-bold">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-5 p-4 rounded-2xl bg-red-500/20 text-red-200 border border-red-400/30">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li class="font-bold">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Doctor Search Section --}}
        <div class="glass-card rounded-[32px] p-6 shadow-2xl mb-8">
            <form method="GET" action="{{ route('appointments.create') }}">
                <label class="block text-sm font-black text-white mb-2">
                    Search Doctor / Specialist / Problem
                </label>

                <div class="flex flex-col sm:flex-row gap-3">
                    <input type="text"
                           name="doctor_search"
                           value="{{ request('doctor_search') }}"
                           placeholder="Search: heart, cardio, brain, skin, fever, child, doctor name..."
                           class="premium-input w-full rounded-2xl px-5 py-4 placeholder:text-slate-400">

                    <button type="submit"
                            class="premium-btn px-7 py-4 rounded-2xl bg-gradient-to-r from-emerald-500 to-cyan-500 text-white font-black shadow-xl">
                        Search
                    </button>

                    <a href="{{ route('appointments.create') }}"
                       class="premium-btn px-6 py-4 rounded-2xl bg-white/10 border border-white/10 text-white font-black hover:bg-white/20 text-center">
                        Reset
                    </a>
                </div>
            </form>

            <div class="mt-4 flex flex-wrap gap-2">
                @foreach(['heart','cardio','brain','skin','fever','child','dentist','eye','kidney','diabetes'] as $quick)
                    <a href="{{ route('appointments.create', ['doctor_search' => $quick]) }}"
                       class="px-3 py-1 rounded-full bg-white/10 border border-white/10 text-slate-200 text-xs font-bold hover:bg-emerald-500/20 hover:text-emerald-200 transition">
                        {{ $quick }}
                    </a>
                @endforeach
            </div>
        </div>
                {{-- Search Result Doctors --}}
        @if($doctorSearch)
            <div class="mb-8">
                <div class="flex items-center justify-between gap-4 mb-4">
                    <div>
                        <h2 class="text-2xl font-black text-white">
                            Search Results
                        </h2>

                        <p class="text-slate-400 text-sm mt-1">
                            Select one doctor to continue booking.
                        </p>
                    </div>

                    <span class="px-4 py-2 rounded-full bg-emerald-500/15 border border-emerald-300/20 text-emerald-200 text-sm font-black">
                        {{ $filteredDoctors->count() }} Found
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

                    @forelse($filteredDoctors as $doctor)
                        @php
                            $isSelected = isset($selectedDoctor) && $selectedDoctor && (int) $selectedDoctor->id === (int) $doctor->id;
                            $doctorSpecialist = $doctor->specialist ?? $doctor->specialization ?? 'Specialist N/A';
                            $doctorChambers = $doctor->display_chambers ?? [];

                            if (empty($doctorChambers)) {
                                $doctorChambers = [
                                    [
                                        'fee' => $doctor->consultation_fee ?? 0,
                                    ]
                                ];
                            }

                            $firstFee = collect($doctorChambers)->pluck('fee')->filter()->min() ?? ($doctor->consultation_fee ?? 0);
                        @endphp

                        <a href="{{ route('appointments.create', [
                                'doctor_search' => request('doctor_search'),
                                'doctor_id' => $doctor->id,
                            ]) }}"
                           class="premium-card rounded-[30px] p-6 shadow-2xl border
                                  {{ $isSelected
                                        ? 'bg-emerald-500/20 border-emerald-300/50'
                                        : 'bg-white/10 border-white/10 hover:bg-white/15' }}">

                            <div class="relative z-10 text-white">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="w-16 h-16 rounded-3xl bg-gradient-to-br from-emerald-400 to-cyan-500 flex items-center justify-center text-3xl shadow-xl">
                                        👨‍⚕️
                                    </div>

                                    @if($isSelected)
                                        <span class="px-3 py-1 rounded-full bg-emerald-500 text-white text-xs font-black">
                                            Selected
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full bg-white/10 border border-white/10 text-slate-200 text-xs font-bold">
                                            Select
                                        </span>
                                    @endif
                                </div>

                                <h3 class="mt-5 text-2xl font-black">
                                    Dr. {{ $doctor->name }}
                                </h3>

                                <p class="mt-1 text-emerald-200 font-bold">
                                    {{ $doctorSpecialist }}
                                </p>

                                <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                                    <div class="rounded-2xl bg-black/20 border border-white/10 p-3">
                                        <p class="text-slate-400 text-xs font-bold">Experience</p>
                                        <p class="text-white font-black mt-1">
                                            {{ $doctor->experience ?? 0 }} Years
                                        </p>
                                    </div>

                                    <div class="rounded-2xl bg-black/20 border border-white/10 p-3">
                                        <p class="text-slate-400 text-xs font-bold">Fee From</p>
                                        <p class="text-white font-black mt-1">
                                            ৳ {{ $firstFee }}
                                        </p>
                                    </div>
                                </div>

                                <p class="mt-4 text-slate-300 text-sm leading-6">
                                    {{ \Illuminate\Support\Str::limit($doctor->chamber_address ?? 'Chamber information available after selection.', 90) }}
                                </p>
                            </div>
                        </a>
                    @empty
                        <div class="md:col-span-2 xl:col-span-3 glass-card rounded-[30px] p-8 text-center text-white shadow-2xl">
                            <div class="mx-auto w-20 h-20 rounded-[28px] bg-white/10 border border-white/10 flex items-center justify-center text-4xl">
                                🔍
                            </div>

                            <h3 class="mt-5 text-3xl font-black">
                                No doctor found
                            </h3>

                            <p class="mt-2 text-slate-400">
                                Try searching by specialist, symptom, doctor name, or keyword like heart, skin, fever, brain.
                            </p>
                        </div>
                    @endforelse

                </div>
            </div>
        @endif

        {{-- Appointment Form Only After Doctor Selection --}}
        @if(isset($selectedDoctor) && $selectedDoctor)
            <div class="glass-card rounded-[34px] shadow-2xl overflow-hidden">

                <div class="px-6 py-5 border-b border-white/10">
                    <h2 class="text-xl font-black text-white">
                        Appointment Information
                    </h2>

                    <p class="text-slate-400 text-sm mt-1">
                        Chamber will auto-load from selected doctor profile. Choose date to generate 5-minute slots.
                    </p>
                </div>

                <form action="{{ route('appointments.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf

                    <input type="hidden"
                           name="doctor_id"
                           id="doctor_id"
                           value="{{ $selectedDoctor->id }}">

                    <input type="hidden"
                           name="chamber_index"
                           id="chamber_index"
                           value="{{ $selectedChamberIndex }}">

                    <div class="rounded-[30px] bg-emerald-500/10 border border-emerald-400/20 p-5">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-5">
                            <div>
                                <h3 class="text-xl font-black text-white">
                                    Dr. {{ $selectedDoctor->name }}
                                </h3>

                                <p class="text-emerald-200 font-bold mt-1">
                                    {{ $selectedDoctor->specialist ?? $selectedDoctor->specialization ?? 'Specialist' }}
                                </p>
                            </div>

                            <div class="px-4 py-2 rounded-full bg-cyan-500/15 border border-cyan-300/20 text-cyan-200 text-sm font-black">
                                Selected Chamber #{{ ((int) $selectedChamberIndex) + 1 }}
                            </div>
                        </div>

                        <div class="mt-5">
                            <label class="block text-sm font-black text-white mb-2">
                                Select Chamber
                            </label>

                            <select name="chamber_address"
                                    id="chamber_address"
                                    required
                                    class="premium-input w-full rounded-2xl px-4 py-3">

                                <option value="">Select Chamber</option>

                                @foreach($chambers as $index => $chamber)
                                    <option value="{{ $chamber['address'] ?? '' }}"
                                            data-index="{{ $index }}"
                                            {{ (string) $selectedChamberAddress === (string) ($chamber['address'] ?? '') ? 'selected' : '' }}>
                                        Chamber #{{ $index + 1 }} — {{ $chamber['address'] ?? 'Not added' }} — ৳ {{ $chamber['fee'] ?? 0 }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="mt-5 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="rounded-2xl bg-slate-950/40 border border-white/10 p-4">
                                <p class="text-slate-400 text-sm font-bold">Working Days</p>
                                <p class="text-white font-black mt-2">
                                    {{ count($days) ? implode(', ', $days) : 'Not Added' }}
                                </p>
                            </div>

                            <div class="rounded-2xl bg-slate-950/40 border border-white/10 p-4">
                                <p class="text-slate-400 text-sm font-bold">Available Time</p>
                                <p class="text-white font-black mt-2">
                                    {{ $startTime ?: 'Not Set' }} - {{ $endTime ?: 'Not Set' }}
                                </p>
                            </div>

                            <div class="rounded-2xl bg-slate-950/40 border border-white/10 p-4">
                                <p class="text-slate-400 text-sm font-bold">Visit Fee</p>
                                <p class="text-white font-black mt-2">
                                    ৳ {{ $fee }}
                                </p>
                            </div>
                        </div>
                    </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-black text-white mb-2">
                                Appointment Date
                            </label>

                            <input type="date"
                                   id="appointment_date"
                                   name="appointment_date"
                                   min="{{ date('Y-m-d') }}"
                                   value="{{ old('appointment_date', request('appointment_date')) }}"
                                   required
                                   class="premium-input w-full rounded-2xl px-4 py-3">
                        </div>

                        <div>
                            <label class="block text-sm font-black text-white mb-2">
                                Select 5-Minute Slot
                            </label>

                            <select name="appointment_time"
                                    id="appointment_time"
                                    required
                                    class="premium-input slot-select w-full rounded-2xl px-4 py-3">

                                <option value="">Select Slot</option>

                                @if(isset($availableSlots) && count($availableSlots))
                                    @foreach($availableSlots as $slot)
                                        @php
                                            $isBooked = in_array($slot, $bookedSlots ?? []);
                                        @endphp

                                        <option value="{{ $slot }}"
                                                {{ old('appointment_time') == $slot ? 'selected' : '' }}
                                                {{ $isBooked ? 'disabled' : '' }}>
                                            {{ $slot }} {{ $isBooked ? '(Booked)' : '' }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>
                                        Select valid chamber and appointment date first
                                    </option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <div id="dayWarning"
                         class="hidden rounded-2xl bg-red-500/20 border border-red-400 text-red-200 p-4 font-bold">
                        Doctor is unavailable on selected day. Please choose a valid working day.
                    </div>

                    <div>
                        <label class="block text-sm font-black text-white mb-2">
                            Problem / Notes
                        </label>

                        <textarea name="problem"
                                  rows="4"
                                  class="premium-input w-full rounded-2xl px-4 py-3"
                                  placeholder="Write symptoms or notes...">{{ old('problem') }}</textarea>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 pt-4">
                        <button type="submit"
                                id="submitBtn"
                                class="premium-btn inline-flex justify-center px-7 py-4 rounded-2xl bg-gradient-to-r from-emerald-500 to-cyan-500 text-white font-black shadow-xl">
                            Confirm Appointment
                        </button>

                        <a href="{{ route('find.doctors') }}"
                           class="premium-btn inline-flex justify-center px-7 py-4 rounded-2xl bg-white/10 border border-white/10 text-white font-black hover:bg-white/20 transition">
                            Back
                        </a>
                    </div>
                </form>
            </div>
        @else
            <div class="glass-card rounded-[30px] p-8 text-center text-white shadow-2xl mb-8">
                <div class="mx-auto w-20 h-20 rounded-[28px] bg-white/10 border border-white/10 flex items-center justify-center text-4xl">
                    🔎
                </div>

                <h3 class="mt-2 text-3xl font-black">
                    Select a doctor first
                </h3>

                <p class="text-slate-400 mt-2">
                    Search and select your preferred doctor to start booking.
                </p>
            </div>
        @endif

    </div>
</div>

<script>
    const allowedDays = @json($days);
    const dateInput = document.getElementById('appointment_date');
    const dayWarning = document.getElementById('dayWarning');
    const submitBtn = document.getElementById('submitBtn');
    const chamberSelect = document.getElementById('chamber_address');
    const chamberIndexInput = document.getElementById('chamber_index');

    function disableSubmit() {
        if (!submitBtn) return;

        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
    }

    function enableSubmit() {
        if (!submitBtn) return;

        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    }

    function checkSelectedDay() {
        if (!dateInput || !dateInput.value) {
            if (dayWarning) dayWarning.classList.add('hidden');
            enableSubmit();
            return false;
        }

        const selected = new Date(dateInput.value + 'T00:00:00');
        const dayName = selected.toLocaleDateString('en-US', { weekday: 'long' });

        if (allowedDays.length > 0 && !allowedDays.includes(dayName)) {
            if (dayWarning) dayWarning.classList.remove('hidden');
            disableSubmit();
            return false;
        }

        if (dayWarning) dayWarning.classList.add('hidden');
        enableSubmit();
        return true;
    }

    function reloadSlots() {
        const doctorId = document.getElementById('doctor_id')?.value;
        const date = document.getElementById('appointment_date')?.value;
        const chamber = document.getElementById('chamber_address')?.value;
        const selectedOption = chamberSelect?.options[chamberSelect.selectedIndex];
        const selectedIndex = selectedOption?.dataset?.index ?? chamberIndexInput?.value ?? '';
        const doctorSearch = @json(request('doctor_search'));

        if (!doctorId) return;

        let url = "{{ route('appointments.create') }}?doctor_id=" + encodeURIComponent(doctorId);

        if (doctorSearch) {
            url += "&doctor_search=" + encodeURIComponent(doctorSearch);
        }

        if (selectedIndex !== '') {
            url += "&chamber_index=" + encodeURIComponent(selectedIndex);
        }

        if (chamber) {
            url += "&chamber_address=" + encodeURIComponent(chamber);
        }

        if (date) {
            url += "&appointment_date=" + encodeURIComponent(date);
        }

        window.location.href = url;
    }

    if (chamberSelect) {
        chamberSelect.addEventListener('change', function () {
            const selectedOption = chamberSelect.options[chamberSelect.selectedIndex];

            if (chamberIndexInput && selectedOption?.dataset?.index !== undefined) {
                chamberIndexInput.value = selectedOption.dataset.index;
            }

            reloadSlots();
        });
    }

    if (dateInput) {
        dateInput.addEventListener('change', function () {
            const isAvailable = checkSelectedDay();

            if (isAvailable) {
                reloadSlots();
            }
        });

        checkSelectedDay();
    }
</script>

</x-app-layout>