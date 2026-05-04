<x-app-layout>
<style>
    @keyframes pageFadeUp {
        from { opacity: 0; transform: translateY(35px) scale(.97); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    @keyframes profileFloat {
        0%,100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-12px) rotate(1deg); }
    }

    @keyframes premiumGlow {
        0%,100% {
            box-shadow:
                0 0 35px rgba(16,185,129,.22),
                0 0 80px rgba(34,211,238,.10),
                inset 0 0 0 1px rgba(255,255,255,.08);
        }
        50% {
            box-shadow:
                0 0 80px rgba(34,211,238,.38),
                0 0 130px rgba(16,185,129,.22),
                inset 0 0 0 1px rgba(45,212,191,.22);
        }
    }

    @keyframes shineMove {
        0% { transform: translateX(-140%) skewX(-15deg); }
        100% { transform: translateX(140%) skewX(-15deg); }
    }

    @keyframes orbMove {
        0%,100% { transform: translateY(0) translateX(0) scale(1); }
        50% { transform: translateY(-24px) translateX(18px) scale(1.08); }
    }

    @keyframes modalPop {
        from { opacity: 0; transform: translateY(35px) scale(.92); filter: blur(8px); }
        to { opacity: 1; transform: translateY(0) scale(1); filter: blur(0); }
    }

    @keyframes pulseRing {
        0% { transform: scale(.96); opacity: .75; }
        70% { transform: scale(1.13); opacity: 0; }
        100% { transform: scale(1.13); opacity: 0; }
    }

    @keyframes floatBadge {
        0%,100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }

    .page-animate {
        animation: pageFadeUp .8s ease both;
    }

    .premium-panel {
        position: relative;
        overflow: hidden;
        background:
            linear-gradient(135deg, rgba(255,255,255,.12), rgba(255,255,255,.04)),
            radial-gradient(circle at top left, rgba(16,185,129,.18), transparent 35%),
            radial-gradient(circle at bottom right, rgba(34,211,238,.16), transparent 35%);
        border: 1px solid rgba(255,255,255,.12);
        backdrop-filter: blur(22px);
    }

    .premium-panel::before {
        content: "";
        position: absolute;
        top: 0;
        left: -55%;
        width: 45%;
        height: 100%;
        background: linear-gradient(120deg, transparent, rgba(255,255,255,.18), transparent);
        transform: translateX(-140%) skewX(-15deg);
        pointer-events: none;
    }

    .premium-panel:hover::before {
        animation: shineMove 1.15s ease;
    }

    .premium-profile-card {
        position: relative;
        overflow: hidden;
        animation: premiumGlow 4.5s ease-in-out infinite;
    }

    .premium-avatar {
        animation: profileFloat 4.2s ease-in-out infinite;
    }

    .avatar-ring {
        position: relative;
    }

    .avatar-ring::after {
        content: "";
        position: absolute;
        inset: -8px;
        border-radius: 34px;
        border: 2px solid rgba(52,211,153,.45);
        animation: pulseRing 2.8s ease-out infinite;
        pointer-events: none;
    }

    .orb {
        animation: orbMove 7s ease-in-out infinite;
    }

    .modal-card {
        animation: modalPop .38s cubic-bezier(.2,.8,.2,1) both;
    }

    .premium-btn {
        transition: all .25s ease;
    }

    .premium-btn:hover {
        transform: translateY(-2px) scale(1.025);
    }

    .premium-btn:active {
        transform: scale(.97);
    }

    .premium-input {
        width: 100%;
        border-radius: 18px;
        border: 1px solid rgba(255,255,255,.14);
        background: rgba(255,255,255,.08);
        padding: 14px 16px;
        color: white;
        outline: none;
        transition: .25s ease;
        font-weight: 700;
    }

    .premium-input::placeholder {
        color: rgba(203,213,225,.65);
    }

    .premium-input:focus {
        border-color: rgba(34,211,238,.75);
        background: rgba(255,255,255,.12);
        box-shadow: 0 0 0 4px rgba(34,211,238,.10), 0 0 28px rgba(34,211,238,.24);
    }

    .premium-label {
        display: block;
        margin-bottom: 8px;
        color: rgb(203 213 225);
        font-size: 13px;
        font-weight: 900;
    }

    .info-card {
        border-radius: 22px;
        border: 1px solid rgba(255,255,255,.12);
        background: rgba(255,255,255,.07);
        padding: 18px;
        backdrop-filter: blur(18px);
        transition: .25s ease;
    }

    .info-card:hover {
        transform: translateY(-5px);
        background: rgba(255,255,255,.11);
        box-shadow: 0 18px 45px rgba(0,0,0,.22);
    }

    .floating-badge {
        animation: floatBadge 3.2s ease-in-out infinite;
    }
</style>

<div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 py-10 px-4 sm:px-6">

    {{-- Animated Background --}}
    <div class="absolute -top-44 -left-44 w-[540px] h-[540px] rounded-full bg-emerald-500/20 blur-3xl orb"></div>
    <div class="absolute top-36 -right-44 w-[540px] h-[540px] rounded-full bg-cyan-500/20 blur-3xl orb"></div>
    <div class="absolute bottom-0 left-1/3 w-[580px] h-[580px] rounded-full bg-blue-500/10 blur-3xl orb"></div>

    <div class="pointer-events-none absolute inset-0 opacity-[.08]"
         style="background-image: linear-gradient(rgba(255,255,255,.7) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.7) 1px, transparent 1px); background-size: 46px 46px;">
    </div>

    <div class="relative z-10 max-w-6xl mx-auto page-animate">

        @php
            $photoPath = $patient->profile_photo ?? auth()->user()->profile_photo ?? null;

            $patientName = $patient->name ?? auth()->user()->name ?? 'Patient';
            $patientEmail = auth()->user()->email ?? 'N/A';

            $patientPhone = $patient->phone ?? 'N/A';
            $patientAge = $patient->age ?? 'N/A';
            $patientBlood = $patient->blood_group ?? 'N/A';
            $patientGender = $patient->gender ?? 'N/A';
            $patientEmergency = $patient->emergency_contact ?? 'N/A';
            $patientAddress = $patient->address ?? 'No address added yet.';

            $hasAllergy = $patient->has_allergy ?? false;
            $hasDiabetes = $patient->has_diabetes ?? false;
            $hasBloodPressure = $patient->has_blood_pressure ?? false;
        @endphp

        {{-- Header --}}
        <div class="premium-panel rounded-[34px] p-7 sm:p-9 shadow-2xl text-white mb-8">
            <div class="relative z-10">
                <span class="floating-badge inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-500/20 border border-emerald-300/20 text-emerald-200 text-sm font-black mb-4">
                    👤 Patient Profile
                </span>

                <h1 class="text-4xl sm:text-5xl font-black tracking-tight">
                    My Personal Information
                </h1>

                <p class="text-slate-300 mt-3 max-w-2xl">
                    View your encrypted patient profile, health details and emergency information.
                </p>
            </div>
        </div>
                {{-- ULTRA PREMIUM VIEW PROFILE SECTION --}}
        <div class="premium-profile-card mb-8 rounded-[36px] bg-gradient-to-r from-emerald-500/20 via-cyan-500/20 to-blue-500/20 border border-emerald-300/20 p-6 sm:p-8 shadow-2xl text-white backdrop-blur-xl">

            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(52,211,153,.18),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(34,211,238,.18),transparent_35%)]"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-7">

                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                    <div class="premium-avatar avatar-ring shrink-0">
                        @if($photoPath)
                            <img src="{{ route('secure.file.show', [
                                    'folder' => 'patient-profiles',
                                    'filename' => basename($photoPath)
                                ]) }}"
                                 loading="lazy"
                                 class="h-32 w-32 rounded-[32px] object-cover border-4 border-emerald-300 shadow-2xl">
                        @else
                            <div class="h-32 w-32 rounded-[32px] bg-gradient-to-br from-emerald-400 to-cyan-500 flex items-center justify-center text-6xl font-black border-4 border-emerald-300 shadow-2xl">
                                {{ strtoupper(substr($patientName, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <div class="text-center sm:text-left">
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-400/10 border border-emerald-300/20 text-emerald-200 text-sm font-black">
                            🔐 Encrypted Patient Profile
                        </div>

                        <h2 class="mt-4 text-3xl sm:text-5xl font-black tracking-tight">
                            {{ $patientName }}
                        </h2>

                        <p class="text-slate-300 mt-2 font-semibold">
                            {{ $patientEmail }}
                        </p>

                        <div class="mt-4 flex flex-wrap justify-center sm:justify-start gap-2">
                            <span class="px-4 py-2 rounded-full bg-white/10 border border-white/10 text-xs font-black">
                                🩸 Blood: {{ $patientBlood }}
                            </span>

                            <span class="px-4 py-2 rounded-full bg-white/10 border border-white/10 text-xs font-black">
                                🎂 Age: {{ $patientAge }}
                            </span>

                            <span class="px-4 py-2 rounded-full bg-white/10 border border-white/10 text-xs font-black">
                                ⚧ Gender: {{ $patientGender }}
                            </span>

                            <span class="px-4 py-2 rounded-full bg-white/10 border border-white/10 text-xs font-black">
                                📞 {{ $patientPhone }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row lg:flex-col xl:flex-row gap-3">
                    <button type="button"
                            onclick="openPatientEditModal()"
                            class="premium-btn px-6 py-4 rounded-2xl bg-white/10 border border-white/10 text-white font-black hover:bg-white/20 transition text-center shadow-xl">
                        ✨ Edit Profile
                    </button>

                    <button type="button"
                            onclick="openPatientModal()"
                            class="premium-btn px-6 py-4 rounded-2xl bg-gradient-to-r from-emerald-400 to-cyan-400 text-slate-950 font-black shadow-[0_0_35px_rgba(45,212,191,.32)] transition text-center">
                        👁 View Profile
                    </button>
                </div>

            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-2xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 px-5 py-4 font-black shadow-xl">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 rounded-2xl bg-red-500/15 border border-red-500/30 text-red-300 px-5 py-4 font-black shadow-xl">
                ⚠️ {{ session('error') }}
            </div>
        @endif

        {{-- PREMIUM PROFILE SUMMARY --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            <div class="info-card text-white shadow-2xl">
                <p class="text-slate-400 text-sm font-black">🩸 Blood Group</p>
                <h3 class="mt-3 text-4xl font-black">
                    {{ $patientBlood }}
                </h3>
            </div>

            <div class="info-card text-white shadow-2xl">
                <p class="text-slate-400 text-sm font-black">🎂 Age</p>
                <h3 class="mt-3 text-4xl font-black">
                    {{ $patientAge }}
                </h3>
            </div>

            <div class="info-card text-white shadow-2xl">
                <p class="text-slate-400 text-sm font-black">🚨 Emergency Contact</p>
                <h3 class="mt-3 text-2xl font-black break-words">
                    {{ $patientEmergency }}
                </h3>
            </div>
        </div>

        {{-- HEALTH CONDITIONS --}}
        <div class="premium-panel rounded-[34px] p-6 sm:p-7 shadow-2xl text-white mb-8">
            <div class="relative z-10">
                <div class="flex items-center justify-between gap-4 flex-wrap mb-5">
                    <h3 class="text-2xl font-black">Health Conditions</h3>
                    <span class="px-4 py-2 rounded-full bg-white/10 border border-white/10 text-xs font-black text-slate-200">
                        Encrypted Medical Status
                    </span>
                </div>

                <div class="flex flex-wrap gap-3">
                    <span class="px-5 py-3 rounded-full border text-sm font-black
                        {{ $hasAllergy ? 'bg-red-500/20 text-red-200 border-red-300/30' : 'bg-white/10 text-slate-300 border-white/10' }}">
                        🌿 Allergy: {{ $hasAllergy ? 'Yes' : 'No' }}
                    </span>

                    <span class="px-5 py-3 rounded-full border text-sm font-black
                        {{ $hasDiabetes ? 'bg-yellow-500/20 text-yellow-200 border-yellow-300/30' : 'bg-white/10 text-slate-300 border-white/10' }}">
                        🍬 Diabetes: {{ $hasDiabetes ? 'Yes' : 'No' }}
                    </span>

                    <span class="px-5 py-3 rounded-full border text-sm font-black
                        {{ $hasBloodPressure ? 'bg-orange-500/20 text-orange-200 border-orange-300/30' : 'bg-white/10 text-slate-300 border-white/10' }}">
                        ❤️ Blood Pressure: {{ $hasBloodPressure ? 'Yes' : 'No' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- ADDRESS --}}
        <div class="premium-panel rounded-[34px] p-6 sm:p-7 shadow-2xl text-white mb-10">
            <div class="relative z-10">
                <h3 class="text-2xl font-black mb-3">📍 Address</h3>

                <p class="text-slate-300 font-semibold leading-relaxed">
                    {{ $patientAddress }}
                </p>
            </div>
        </div>
                {{-- ULTRA PREMIUM VIEW PROFILE MODAL --}}
        <div id="patientProfileModal"
             class="fixed inset-0 z-50 hidden items-center justify-center bg-black/75 backdrop-blur-md px-4 py-6">

            <div class="modal-card relative w-full max-w-5xl max-h-[92vh] overflow-y-auto rounded-[36px] bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 border border-emerald-300/25 p-6 sm:p-8 shadow-[0_0_90px_rgba(16,185,129,.25)] text-white">

                <div class="absolute -top-32 -left-32 h-72 w-72 rounded-full bg-emerald-400/20 blur-3xl"></div>
                <div class="absolute -bottom-32 -right-32 h-72 w-72 rounded-full bg-cyan-400/20 blur-3xl"></div>

                <button type="button"
                        onclick="closePatientModal()"
                        class="premium-btn absolute top-5 right-5 z-20 w-12 h-12 rounded-2xl bg-white/10 hover:bg-red-500/80 text-white font-black border border-white/10">
                    ✕
                </button>

                <div class="relative z-10">
                    <div class="mb-7">
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-500/20 border border-emerald-300/20 text-emerald-200 text-sm font-black">
                            👁 Complete Patient Overview
                        </span>

                        <h2 class="mt-4 text-3xl sm:text-4xl font-black">
                            View Profile
                        </h2>

                        <p class="mt-2 text-slate-300">
                            Full encrypted patient information with emergency and health details.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-[260px_1fr] gap-7">

                        {{-- Left Identity Card --}}
                        <div class="rounded-[30px] bg-white/10 border border-white/10 p-6 text-center shadow-2xl">
                            <div class="premium-avatar avatar-ring inline-block">
                                @if($photoPath)
                                    <img src="{{ route('secure.file.show', [
                                            'folder' => 'patient-profiles',
                                            'filename' => basename($photoPath)
                                        ]) }}"
                                         class="w-36 h-36 rounded-[32px] object-cover border-4 border-emerald-300 shadow-2xl mx-auto">
                                @else
                                    <div class="w-36 h-36 rounded-[32px] bg-gradient-to-br from-emerald-400 to-cyan-500 flex items-center justify-center text-6xl font-black border-4 border-emerald-300 mx-auto">
                                        {{ strtoupper(substr($patientName, 0, 1)) }}
                                    </div>
                                @endif
                            </div>

                            <h3 class="mt-6 text-3xl font-black break-words">
                                {{ $patientName }}
                            </h3>

                            <p class="mt-2 text-slate-300 text-sm break-words font-semibold">
                                {{ $patientEmail }}
                            </p>

                            <div class="mt-5 inline-flex px-4 py-2 rounded-full bg-emerald-400/10 border border-emerald-300/20 text-emerald-200 text-xs font-black">
                                🔐 Secured Profile
                            </div>
                        </div>

                        {{-- Right Information Grid --}}
                        <div class="space-y-6">

                            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                                <div class="info-card">
                                    <p class="text-slate-400 text-xs font-black">Full Name</p>
                                    <h4 class="mt-2 text-xl font-black break-words">{{ $patientName }}</h4>
                                </div>

                                <div class="info-card">
                                    <p class="text-slate-400 text-xs font-black">Email</p>
                                    <h4 class="mt-2 text-base font-black break-words">{{ $patientEmail }}</h4>
                                </div>

                                <div class="info-card">
                                    <p class="text-slate-400 text-xs font-black">Phone</p>
                                    <h4 class="mt-2 text-xl font-black break-words">{{ $patientPhone }}</h4>
                                </div>

                                <div class="info-card">
                                    <p class="text-slate-400 text-xs font-black">Blood Group</p>
                                    <h4 class="mt-2 text-2xl font-black">{{ $patientBlood }}</h4>
                                </div>

                                <div class="info-card">
                                    <p class="text-slate-400 text-xs font-black">Age</p>
                                    <h4 class="mt-2 text-2xl font-black">{{ $patientAge }}</h4>
                                </div>

                                <div class="info-card">
                                    <p class="text-slate-400 text-xs font-black">Gender</p>
                                    <h4 class="mt-2 text-2xl font-black">{{ $patientGender }}</h4>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="info-card">
                                    <p class="text-slate-400 text-xs font-black">Emergency Contact</p>
                                    <h4 class="mt-2 text-xl font-black break-words">
                                        {{ $patientEmergency }}
                                    </h4>
                                </div>

                                <div class="info-card">
                                    <p class="text-slate-400 text-xs font-black">Address</p>
                                    <h4 class="mt-2 text-base font-black leading-relaxed break-words">
                                        {{ $patientAddress }}
                                    </h4>
                                </div>
                            </div>

                            <div class="rounded-[28px] border border-white/10 bg-white/7 p-5 shadow-2xl backdrop-blur-xl">
                                <div class="flex items-center justify-between gap-3 flex-wrap mb-4">
                                    <h3 class="text-xl font-black">Health Conditions</h3>
                                    <span class="px-3 py-1 rounded-full bg-white/10 border border-white/10 text-xs font-black text-slate-300">
                                        Medical Summary
                                    </span>
                                </div>

                                <div class="flex flex-wrap gap-3">
                                    <span class="px-4 py-2 rounded-full border text-sm font-black
                                        {{ $hasAllergy ? 'bg-red-500/20 text-red-200 border-red-300/30' : 'bg-white/10 text-slate-300 border-white/10' }}">
                                        🌿 Allergy: {{ $hasAllergy ? 'Yes' : 'No' }}
                                    </span>

                                    <span class="px-4 py-2 rounded-full border text-sm font-black
                                        {{ $hasDiabetes ? 'bg-yellow-500/20 text-yellow-200 border-yellow-300/30' : 'bg-white/10 text-slate-300 border-white/10' }}">
                                        🍬 Diabetes: {{ $hasDiabetes ? 'Yes' : 'No' }}
                                    </span>

                                    <span class="px-4 py-2 rounded-full border text-sm font-black
                                        {{ $hasBloodPressure ? 'bg-orange-500/20 text-orange-200 border-orange-300/30' : 'bg-white/10 text-slate-300 border-white/10' }}">
                                        ❤️ Blood Pressure: {{ $hasBloodPressure ? 'Yes' : 'No' }}
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="mt-8 flex flex-col sm:flex-row justify-end gap-3">
                        <button type="button"
                                onclick="openPatientEditModal(); closePatientModal();"
                                class="premium-btn px-6 py-3 rounded-2xl bg-white/10 border border-white/10 text-white font-black hover:bg-white/20">
                            ✨ Edit This Profile
                        </button>

                        <button onclick="closePatientModal()"
                                class="premium-btn px-7 py-3 rounded-2xl bg-gradient-to-r from-emerald-400 to-cyan-400 text-slate-950 font-black shadow-xl">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
                {{-- ULTRA PREMIUM EDIT PROFILE MODAL --}}
        <div id="patientEditModal"
             class="fixed inset-0 z-50 hidden items-center justify-center bg-black/75 backdrop-blur-md px-4 py-6">

            <div class="modal-card relative w-full max-w-5xl max-h-[92vh] overflow-y-auto rounded-[36px] bg-gradient-to-br from-slate-950 via-slate-900 to-cyan-950 border border-cyan-300/25 p-6 sm:p-8 shadow-[0_0_90px_rgba(34,211,238,.22)] text-white">

                <div class="absolute -top-32 -left-32 h-72 w-72 rounded-full bg-cyan-400/20 blur-3xl"></div>
                <div class="absolute -bottom-32 -right-32 h-72 w-72 rounded-full bg-emerald-400/20 blur-3xl"></div>

                <button type="button"
                        onclick="closePatientEditModal()"
                        class="premium-btn absolute top-5 right-5 z-20 w-12 h-12 rounded-2xl bg-white/10 hover:bg-red-500/80 text-white font-black border border-white/10">
                    ✕
                </button>

                <div class="relative z-10">
                    <div class="mb-7">
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-cyan-500/20 border border-cyan-300/20 text-cyan-200 text-sm font-black">
                            ✨ Edit Encrypted Profile
                        </span>

                        <h2 class="mt-4 text-3xl sm:text-4xl font-black">
                            Edit Profile
                        </h2>

                        <p class="mt-2 text-slate-300">
                            Update your personal, emergency and health information.
                        </p>
                    </div>

                    <form method="POST"
                          action="{{ route('patient.my-profile.update') }}"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 lg:grid-cols-[260px_1fr] gap-7">

                            {{-- Profile Photo Upload --}}
                            <div class="rounded-[30px] bg-white/10 border border-white/10 p-6 text-center shadow-2xl">
                                <div class="premium-avatar avatar-ring inline-block">
                                    @if($photoPath)
                                        <img id="profilePreview"
                                             src="{{ route('secure.file.show', [
                                                    'folder' => 'patient-profiles',
                                                    'filename' => basename($photoPath)
                                                ]) }}"
                                             class="w-36 h-36 rounded-[32px] object-cover border-4 border-cyan-300 shadow-2xl mx-auto">
                                    @else
                                        <div id="profileInitialPreview"
                                             class="w-36 h-36 rounded-[32px] bg-gradient-to-br from-emerald-400 to-cyan-500 flex items-center justify-center text-6xl font-black border-4 border-cyan-300 mx-auto">
                                            {{ strtoupper(substr($patientName, 0, 1)) }}
                                        </div>

                                        <img id="profilePreview"
                                             src=""
                                             class="hidden w-36 h-36 rounded-[32px] object-cover border-4 border-cyan-300 shadow-2xl mx-auto">
                                    @endif
                                </div>

                                <h3 class="mt-6 text-2xl font-black">
                                    Profile Photo
                                </h3>

                                <p class="mt-2 text-sm text-slate-300">
                                    Upload a clear patient photo.
                                </p>

                                <label class="premium-btn mt-5 inline-flex cursor-pointer items-center justify-center px-5 py-3 rounded-2xl bg-white/10 border border-white/10 text-white font-black hover:bg-white/20">
                                    📸 Choose Photo
                                    <input type="file"
                                           name="profile_photo"
                                           accept="image/*"
                                           class="hidden"
                                           onchange="previewPatientPhoto(event)">
                                </label>
                            </div>

                            {{-- Form Fields --}}
                            <div class="space-y-6">

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="premium-label">Full Name</label>
                                        <input type="text"
                                               name="name"
                                               value="{{ old('name', $patientName) }}"
                                               class="premium-input"
                                               placeholder="Enter full name">
                                    </div>

                                    <div>
                                        <label class="premium-label">Phone Number</label>
                                        <input type="text"
                                               name="phone"
                                               value="{{ old('phone', $patient->phone ?? '') }}"
                                               class="premium-input"
                                               placeholder="Enter phone number">
                                    </div>

                                    <div>
                                        <label class="premium-label">Age</label>
                                        <input type="number"
                                               name="age"
                                               value="{{ old('age', $patient->age ?? '') }}"
                                               class="premium-input"
                                               placeholder="Enter age">
                                    </div>

                                    <div>
                                        <label class="premium-label">Blood Group</label>
                                        <select name="blood_group" class="premium-input">
                                            <option class="text-black" value="">Select Blood Group</option>
                                            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $blood)
                                                <option class="text-black" value="{{ $blood }}"
                                                    {{ old('blood_group', $patient->blood_group ?? '') == $blood ? 'selected' : '' }}>
                                                    {{ $blood }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="premium-label">Gender</label>
                                        <select name="gender" class="premium-input">
                                            <option class="text-black" value="">Select Gender</option>
                                            @foreach(['Male', 'Female', 'Other'] as $gender)
                                                <option class="text-black" value="{{ $gender }}"
                                                    {{ old('gender', $patient->gender ?? '') == $gender ? 'selected' : '' }}>
                                                    {{ $gender }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="premium-label">Emergency Contact</label>
                                        <input type="text"
                                               name="emergency_contact"
                                               value="{{ old('emergency_contact', $patient->emergency_contact ?? '') }}"
                                               class="premium-input"
                                               placeholder="Emergency contact number">
                                    </div>
                                </div>

                                <div>
                                    <label class="premium-label">Address</label>
                                    <textarea name="address"
                                              rows="4"
                                              class="premium-input resize-none"
                                              placeholder="Enter full address">{{ old('address', $patient->address ?? '') }}</textarea>
                                </div>

                                {{-- Health Condition Switches --}}
                                <div class="rounded-[28px] bg-white/10 border border-white/10 p-5 shadow-2xl">
                                    <div class="flex items-center justify-between gap-3 flex-wrap mb-4">
                                        <h3 class="text-xl font-black">Health Conditions</h3>
                                        <span class="px-3 py-1 rounded-full bg-white/10 border border-white/10 text-xs font-black text-slate-300">
                                            Yes / No
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <label class="cursor-pointer rounded-2xl bg-white/10 border border-white/10 p-4 hover:bg-white/15 transition">
                                            <input type="hidden" name="has_allergy" value="0">
                                            <input type="checkbox"
                                                   name="has_allergy"
                                                   value="1"
                                                   class="mr-2 accent-emerald-400"
                                                   {{ old('has_allergy', $hasAllergy) ? 'checked' : '' }}>
                                            <span class="font-black">🌿 Allergy</span>
                                        </label>

                                        <label class="cursor-pointer rounded-2xl bg-white/10 border border-white/10 p-4 hover:bg-white/15 transition">
                                            <input type="hidden" name="has_diabetes" value="0">
                                            <input type="checkbox"
                                                   name="has_diabetes"
                                                   value="1"
                                                   class="mr-2 accent-emerald-400"
                                                   {{ old('has_diabetes', $hasDiabetes) ? 'checked' : '' }}>
                                            <span class="font-black">🍬 Diabetes</span>
                                        </label>

                                        <label class="cursor-pointer rounded-2xl bg-white/10 border border-white/10 p-4 hover:bg-white/15 transition">
                                            <input type="hidden" name="has_blood_pressure" value="0">
                                            <input type="checkbox"
                                                   name="has_blood_pressure"
                                                   value="1"
                                                   class="mr-2 accent-emerald-400"
                                                   {{ old('has_blood_pressure', $hasBloodPressure) ? 'checked' : '' }}>
                                            <span class="font-black">❤️ Blood Pressure</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-2">
                                    <button type="button"
                                            onclick="closePatientEditModal()"
                                            class="premium-btn px-6 py-3 rounded-2xl bg-white/10 border border-white/10 text-white font-black hover:bg-white/20">
                                        Cancel
                                    </button>

                                    <button type="submit"
                                            class="premium-btn px-7 py-3 rounded-2xl bg-gradient-to-r from-emerald-400 to-cyan-400 text-slate-950 font-black shadow-xl">
                                        Save Changes
                                    </button>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
    // OPEN / CLOSE VIEW MODAL
    window.openPatientModal = function () {
        const modal = document.getElementById('patientProfileModal');
        if (!modal) return;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    };

    window.closePatientModal = function () {
        const modal = document.getElementById('patientProfileModal');
        if (!modal) return;

        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    };

    // OPEN / CLOSE EDIT MODAL
    window.openPatientEditModal = function () {
        const modal = document.getElementById('patientEditModal');
        if (!modal) return;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    };

    window.closePatientEditModal = function () {
        const modal = document.getElementById('patientEditModal');
        if (!modal) return;

        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    };

    // ESC KEY CLOSE
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closePatientModal();
            closePatientEditModal();
        }
    });

    // CLICK OUTSIDE CLOSE
    document.addEventListener('click', function (event) {
        const profileModal = document.getElementById('patientProfileModal');
        const editModal = document.getElementById('patientEditModal');

        if (profileModal && !profileModal.classList.contains('hidden') && event.target === profileModal) {
            closePatientModal();
        }

        if (editModal && !editModal.classList.contains('hidden') && event.target === editModal) {
            closePatientEditModal();
        }
    });

    // IMAGE PREVIEW (ULTRA PREMIUM)
    window.previewPatientPhoto = function (event) {
        const file = event.target.files[0];
        if (!file) return;

        const preview = document.getElementById('profilePreview');
        const initial = document.getElementById('profileInitialPreview');

        if (file.size > 2 * 1024 * 1024) {
            alert("Image must be under 2MB");
            event.target.value = "";
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');

            if (initial) {
                initial.classList.add('hidden');
            }
        };
        reader.readAsDataURL(file);
    };

    // SMOOTH MODAL ANIMATION RESET
    const resetAnimation = (modalId) => {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        const card = modal.querySelector('.modal-card');
        if (!card) return;

        card.style.animation = 'none';
        card.offsetHeight; // trigger reflow
        card.style.animation = null;
    };

    // Apply animation reset on open
    const originalOpenProfile = window.openPatientModal;
    window.openPatientModal = function () {
        originalOpenProfile();
        resetAnimation('patientProfileModal');
    };

    const originalOpenEdit = window.openPatientEditModal;
    window.openPatientEditModal = function () {
        originalOpenEdit();
        resetAnimation('patientEditModal');
    };
</script>

</x-app-layout>