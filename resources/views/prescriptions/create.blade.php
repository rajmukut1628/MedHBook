<x-app-layout>
@php
    $verifiedMode = isset($selectedPatient) && $selectedPatient && isset($isPrivacyVerified) && $isPrivacyVerified;

    $alreadyVerifiedPatient = null;

    if ($verifiedMode) {
        $alreadyVerifiedPatient = [
            'id' => $selectedPatient->id,
            'patient_code' => 'P' . $selectedPatient->id,
            'name' => $selectedPatient->user->name ?? $selectedPatient->name ?? 'N/A',
            'email' => $selectedPatient->user->email ?? $selectedPatient->email ?? 'N/A',
            'phone' => $selectedPatient->phone ?? 'N/A',
            'blood_group' => $selectedPatient->blood_group ?? 'N/A',
            'gender' => $selectedPatient->gender ?? 'N/A',
        ];
    }
@endphp

<style>
    @keyframes mhbFloat {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }

    @keyframes mhbPulseGlow {
        0%, 100% {
            box-shadow: 0 0 25px rgba(16, 185, 129, .18),
                        0 0 60px rgba(20, 184, 166, .10);
        }
        50% {
            box-shadow: 0 0 35px rgba(16, 185, 129, .35),
                        0 0 90px rgba(20, 184, 166, .18);
        }
    }

    @keyframes mhbShine {
        0% { transform: translateX(-140%) rotate(12deg); opacity: 0; }
        35% { opacity: .55; }
        100% { transform: translateX(170%) rotate(12deg); opacity: 0; }
    }

    @keyframes mhbFadeUp {
        from {
            opacity: 0;
            transform: translateY(18px) scale(.98);
        }
        to {
            opacity: 1;
            transform: translateY(0px) scale(1);
        }
    }

    .mhb-prescription-page {
        position: relative;
        overflow: hidden;
    }

    .mhb-prescription-page::before {
        content: "";
        position: absolute;
        inset: -260px -180px auto auto;
        width: 520px;
        height: 520px;
        background: radial-gradient(circle, rgba(16, 185, 129, .30), transparent 65%);
        filter: blur(10px);
        pointer-events: none;
    }

    .mhb-prescription-page::after {
        content: "";
        position: absolute;
        left: -220px;
        bottom: -260px;
        width: 620px;
        height: 620px;
        background: radial-gradient(circle, rgba(59, 130, 246, .18), transparent 68%);
        filter: blur(14px);
        pointer-events: none;
    }

    .mhb-hero-card {
        position: relative;
        overflow: hidden;
        animation: mhbFadeUp .65s ease both;
    }

    .mhb-hero-card::after {
        content: "";
        position: absolute;
        top: -80px;
        left: -120px;
        width: 120px;
        height: 220%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,.18), transparent);
        animation: mhbShine 5s ease-in-out infinite;
        pointer-events: none;
    }

    .mhb-glow-card {
        animation: mhbPulseGlow 4s ease-in-out infinite;
    }

    .mhb-float {
        animation: mhbFloat 4.5s ease-in-out infinite;
    }

    .mhb-fade-up {
        animation: mhbFadeUp .7s ease both;
    }

    .mhb-input {
        transition: .25s ease;
    }

    .mhb-input:focus {
        transform: translateY(-1px);
        box-shadow: 0 0 0 4px rgba(16, 185, 129, .12),
                    0 18px 50px rgba(0,0,0,.25);
    }

    .mhb-premium-btn {
        position: relative;
        overflow: hidden;
    }

    .mhb-premium-btn::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,.18), transparent);
        transform: translateX(-120%);
        transition: .5s ease;
    }

    .mhb-premium-btn:hover::after {
        transform: translateX(120%);
    }
</style>

<div class="mhb-prescription-page min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 py-10 px-5 sm:px-6">
    <div class="relative z-10 max-w-7xl mx-auto">

        <div class="mhb-hero-card mb-8 rounded-[2rem] border border-white/10 bg-white/[0.07] backdrop-blur-2xl shadow-[0_30px_100px_rgba(0,0,0,.45)] p-6 sm:p-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-400/10 border border-emerald-300/20 text-emerald-200 text-xs font-black uppercase tracking-[.25em]">
                        <span class="h-2 w-2 rounded-full bg-emerald-400 shadow-[0_0_18px_rgba(52,211,153,.9)]"></span>
                        MedHBook Digital Prescription
                    </div>

                    <h1 class="mt-5 text-3xl sm:text-5xl font-black text-white tracking-tight">
                        Create Prescription
                    </h1>

                    <p class="text-slate-300 mt-3 max-w-2xl leading-relaxed">
                        Create a secure digital prescription for a verified patient with premium clinical workflow, privacy protection and professional medical record management.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('doctor.dashboard') }}"
                       class="mhb-premium-btn inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-white/10 text-white border border-white/15 hover:bg-white/15 shadow-xl transition font-black">
                        ← Back
                    </a>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-4 mt-8">
                <div class="rounded-3xl bg-slate-950/50 border border-white/10 p-5">
                    <p class="text-slate-400 text-xs font-black uppercase tracking-widest">Security Mode</p>
                    <h3 class="text-white font-black text-xl mt-2">
                        {{ $verifiedMode ? 'Verified Access' : 'Locked Access' }}
                    </h3>
                    <p class="text-emerald-200 text-sm mt-1">
                        {{ $verifiedMode ? 'Privacy key already verified.' : 'Patient privacy key required.' }}
                    </p>
                </div>

                <div class="rounded-3xl bg-slate-950/50 border border-white/10 p-5">
                    <p class="text-slate-400 text-xs font-black uppercase tracking-widest">Patient Status</p>
                    <h3 class="text-white font-black text-xl mt-2">
                        {{ $verifiedMode ? 'Selected' : 'Not Selected' }}
                    </h3>
                    <p class="text-slate-300 text-sm mt-1">
                        {{ $verifiedMode ? ($selectedPatient->user->name ?? $selectedPatient->name ?? 'N/A') : 'Search and verify patient first.' }}
                    </p>
                </div>

                <div class="rounded-3xl bg-slate-950/50 border border-white/10 p-5">
                    <p class="text-slate-400 text-xs font-black uppercase tracking-widest">Prescription</p>
                    <h3 class="text-white font-black text-xl mt-2">
                        {{ $verifiedMode ? 'Unlocked' : 'Locked' }}
                    </h3>
                    <p class="text-cyan-200 text-sm mt-1">
                        Professional digital prescription form.
                    </p>
                </div>
            </div>
        </div>
                @if(session('error'))
            <div class="mhb-fade-up mb-6 rounded-3xl bg-red-500/10 border border-red-400/30 text-red-100 p-5 font-bold shadow-[0_20px_70px_rgba(239,68,68,.12)]">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="mhb-fade-up mb-6 rounded-3xl bg-emerald-500/10 border border-emerald-400/30 text-emerald-100 p-5 font-bold shadow-[0_20px_70px_rgba(16,185,129,.12)]">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mhb-fade-up mb-6 rounded-3xl bg-red-500/10 border border-red-400/30 text-red-100 p-5 shadow-[0_20px_70px_rgba(239,68,68,.12)]">
                <ul class="list-disc ml-5">
                    @foreach($errors->all() as $error)
                        <li class="font-semibold">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST"
              action="{{ route('prescriptions.store') }}"
              class="mhb-fade-up relative overflow-hidden rounded-[2rem] bg-white/[0.08] backdrop-blur-2xl border border-white/15 shadow-[0_35px_120px_rgba(0,0,0,.45)] p-5 sm:p-8">

            @csrf

            <input type="hidden"
                   name="patient_id"
                   id="patient_id"
                   value="{{ $verifiedMode ? $selectedPatient->id : '' }}">

            @if(!$verifiedMode)
                <div class="grid lg:grid-cols-3 gap-6">

                    <div class="lg:col-span-2 rounded-[2rem] bg-slate-950/60 border border-white/10 p-6 shadow-[inset_0_1px_0_rgba(255,255,255,.05)]">
                        <div class="flex items-center justify-between gap-4 mb-5">
                            <div>
                                <h2 class="text-2xl font-black text-white">Find Patient</h2>
                                <p class="text-slate-400 text-sm mt-1">
                                    Search by patient ID, name, email or phone.
                                </p>
                            </div>

                            <div class="hidden sm:flex h-14 w-14 items-center justify-center rounded-2xl bg-cyan-400/10 border border-cyan-300/20 text-2xl mhb-float">
                                🔎
                            </div>
                        </div>

                        <label class="text-sm font-black text-slate-200 mb-2 block">
                            Search Patient
                        </label>

                        <input type="text"
                               id="patientSearch"
                               autocomplete="off"
                               placeholder="Example: P2, Xavier, email or phone"
                               class="mhb-input w-full rounded-2xl bg-slate-950/80 border border-white/15 text-white px-5 py-4 outline-none focus:border-emerald-400 placeholder:text-slate-500">

                        <div id="searchStatus" class="text-sm text-slate-400 mt-4 font-semibold">
                            Type patient ID, name, email or phone to search.
                        </div>

                        <div id="searchResults" class="mt-5 space-y-3"></div>
                    </div>

                    <div class="rounded-[2rem] bg-slate-950/60 border border-white/10 p-6 shadow-[inset_0_1px_0_rgba(255,255,255,.05)]">
                        <div class="flex items-center justify-between gap-4 mb-5">
                            <div>
                                <h2 class="text-2xl font-black text-white">Privacy Lock</h2>
                                <p class="text-slate-400 text-sm mt-1">
                                    Verify patient key to unlock.
                                </p>
                            </div>

                            <div class="h-14 w-14 flex items-center justify-center rounded-2xl bg-emerald-400/10 border border-emerald-300/20 text-2xl mhb-float">
                                🔐
                            </div>
                        </div>

                        <div id="selectedPatientBox"
                             class="hidden mb-5 rounded-3xl bg-emerald-500/10 border border-emerald-400/20 p-5">
                            <p class="text-xs text-emerald-200 font-black uppercase tracking-widest">
                                Selected Patient
                            </p>

                            <h4 id="selectedPatientName"
                                class="text-white text-xl font-black mt-2"></h4>

                            <p id="selectedPatientCode"
                               class="text-slate-300 text-sm mt-1"></p>

                            <p id="selectedPatientEmail"
                               class="text-slate-400 text-sm mt-1"></p>
                        </div>

                        <label class="text-sm font-black text-slate-200 mb-2 block">
                            Patient Privacy Key
                        </label>

                        <input type="text"
                               name="privacy_key"
                               id="privacy_key"
                               placeholder="Enter patient privacy key"
                               class="mhb-input w-full rounded-2xl bg-slate-950/80 border border-white/15 text-white px-5 py-4 outline-none focus:border-emerald-400 placeholder:text-slate-500">

                        <button type="button"
                                id="verifyBtn"
                                disabled
                                class="mt-4 w-full rounded-2xl bg-emerald-600/40 text-white font-black py-4 cursor-not-allowed">
                            Verify & Unlock
                        </button>

                        <div id="verifyMessage"
                             class="mt-4 text-sm font-bold text-slate-300">
                            Select patient first.
                        </div>
                    </div>

                </div>
            @else
                <div class="mhb-glow-card relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-emerald-500/15 via-cyan-500/10 to-slate-950/50 border border-emerald-300/20 p-6 sm:p-8 mb-6">
                    <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                        <div>
                            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-400/10 border border-emerald-300/20 text-emerald-100 text-xs font-black uppercase tracking-[.2em]">
                                <span class="h-2 w-2 rounded-full bg-emerald-400 shadow-[0_0_20px_rgba(52,211,153,1)]"></span>
                                Verified Patient
                            </div>

                            <h2 class="text-3xl sm:text-4xl font-black text-white mt-4">
                                {{ $selectedPatient->user->name ?? $selectedPatient->name ?? 'N/A' }}
                            </h2>

                            <p class="text-slate-300 mt-2 font-semibold">
                                Patient ID:
                                <span class="text-emerald-200 font-black">P{{ $selectedPatient->id }}</span>
                            </p>

                            <p class="text-slate-400 mt-1">
                                {{ $selectedPatient->user->email ?? $selectedPatient->email ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-3 min-w-full sm:min-w-[360px]">
                            <div class="rounded-3xl bg-slate-950/50 border border-white/10 p-4">
                                <p class="text-slate-400 text-xs font-black uppercase">Phone</p>
                                <p class="text-white font-black mt-1">
                                    {{ $selectedPatient->phone ?? 'N/A' }}
                                </p>
                            </div>

                            <div class="rounded-3xl bg-slate-950/50 border border-white/10 p-4">
                                <p class="text-slate-400 text-xs font-black uppercase">Blood</p>
                                <p class="text-white font-black mt-1">
                                    {{ $selectedPatient->blood_group ?? 'N/A' }}
                                </p>
                            </div>

                            <div class="rounded-3xl bg-slate-950/50 border border-white/10 p-4">
                                <p class="text-slate-400 text-xs font-black uppercase">Gender</p>
                                <p class="text-white font-black mt-1">
                                    {{ $selectedPatient->gender ?? 'N/A' }}
                                </p>
                            </div>

                            <div class="rounded-3xl bg-slate-950/50 border border-white/10 p-4">
                                <p class="text-slate-400 text-xs font-black uppercase">Access</p>
                                <p class="text-emerald-200 font-black mt-1">
                                    Unlocked
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
                        <div id="patientInfoCard"
                 class="{{ $verifiedMode ? '' : 'hidden' }} mt-6 rounded-[2rem] bg-slate-950/45 border border-emerald-300/20 p-5 sm:p-6 shadow-[0_25px_90px_rgba(16,185,129,.10)]">
                <div class="flex items-center justify-between gap-4 mb-5">
                    <div>
                        <p class="text-emerald-200 text-xs font-black uppercase tracking-[.2em]">
                            Patient Summary
                        </p>
                        <h3 class="text-white text-2xl font-black mt-1">
                            Clinical Identity Card
                        </h3>
                    </div>

                    <div class="hidden sm:flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-400/10 border border-emerald-300/20 text-2xl">
                        🧾
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-5 gap-4 text-white">
                    <div class="rounded-3xl bg-white/[0.06] border border-white/10 p-4">
                        <p class="text-xs text-emerald-200 font-black uppercase tracking-widest">Patient ID</p>
                        <p id="infoCode" class="font-black mt-2">
                            {{ $verifiedMode ? 'P'.$selectedPatient->id : '' }}
                        </p>
                    </div>

                    <div class="rounded-3xl bg-white/[0.06] border border-white/10 p-4">
                        <p class="text-xs text-emerald-200 font-black uppercase tracking-widest">Name</p>
                        <p id="infoName" class="font-black mt-2">
                            {{ $verifiedMode ? ($selectedPatient->user->name ?? $selectedPatient->name ?? 'N/A') : '' }}
                        </p>
                    </div>

                    <div class="rounded-3xl bg-white/[0.06] border border-white/10 p-4">
                        <p class="text-xs text-emerald-200 font-black uppercase tracking-widest">Phone</p>
                        <p id="infoPhone" class="font-black mt-2">
                            {{ $verifiedMode ? ($selectedPatient->phone ?? 'N/A') : '' }}
                        </p>
                    </div>

                    <div class="rounded-3xl bg-white/[0.06] border border-white/10 p-4">
                        <p class="text-xs text-emerald-200 font-black uppercase tracking-widest">Blood Group</p>
                        <p id="infoBlood" class="font-black mt-2">
                            {{ $verifiedMode ? ($selectedPatient->blood_group ?? 'N/A') : '' }}
                        </p>
                    </div>

                    <div class="rounded-3xl bg-white/[0.06] border border-white/10 p-4">
                        <p class="text-xs text-emerald-200 font-black uppercase tracking-widest">Gender</p>
                        <p id="infoGender" class="font-black mt-2">
                            {{ $verifiedMode ? ($selectedPatient->gender ?? 'N/A') : '' }}
                        </p>
                    </div>
                </div>
            </div>

            <div id="lockedNotice"
                 class="{{ $verifiedMode ? 'hidden' : '' }} mt-8 rounded-[2rem] bg-yellow-500/10 border border-yellow-400/25 p-6 text-yellow-100 font-black shadow-[0_25px_90px_rgba(234,179,8,.08)]">
                <div class="flex items-start gap-4">
                    <div class="h-12 w-12 shrink-0 flex items-center justify-center rounded-2xl bg-yellow-400/10 border border-yellow-300/20 text-2xl">
                        🔒
                    </div>
                    <div>
                        <h3 class="text-xl text-white font-black">Prescription form is locked</h3>
                        <p class="text-yellow-100/80 mt-1">
                            Search patient and verify patient privacy key first. After verification, the full prescription form will unlock automatically.
                        </p>
                    </div>
                </div>
            </div>

            <div id="prescriptionFormArea"
                 class="{{ $verifiedMode ? '' : 'hidden' }} mt-8">

                <div class="mb-6 rounded-[2rem] bg-gradient-to-r from-emerald-500/10 via-cyan-500/10 to-blue-500/10 border border-white/10 p-5 sm:p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <p class="text-emerald-200 text-xs font-black uppercase tracking-[.25em]">
                                Prescription Workspace
                            </p>
                            <h2 class="text-2xl sm:text-3xl text-white font-black mt-2">
                                Write Medical Prescription
                            </h2>
                            <p class="text-slate-400 mt-1">
                                Fill diagnosis, medicines, advice and next visit information.
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <div class="px-4 py-3 rounded-2xl bg-slate-950/50 border border-white/10 text-slate-200 text-sm font-bold">
                                Secure Session
                            </div>
                            <div class="px-4 py-3 rounded-2xl bg-emerald-500/10 border border-emerald-300/20 text-emerald-200 text-sm font-bold">
                                Verified Patient
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 space-y-6">

                        <div class="grid md:grid-cols-2 gap-5">
                            <div class="rounded-[1.7rem] bg-slate-950/55 border border-white/10 p-5 shadow-[inset_0_1px_0_rgba(255,255,255,.05)]">
                                <label class="text-sm font-black text-slate-200 mb-2 block">
                                    Prescription Date
                                </label>

                                <input type="date"
                                       name="prescription_date"
                                       value="{{ date('Y-m-d') }}"
                                       class="mhb-input w-full rounded-2xl bg-slate-950/80 border border-white/15 text-white px-5 py-4 outline-none focus:border-emerald-400"
                                       required>
                            </div>

                            <div class="rounded-[1.7rem] bg-slate-950/55 border border-white/10 p-5 shadow-[inset_0_1px_0_rgba(255,255,255,.05)]">
                                <label class="text-sm font-black text-slate-200 mb-2 block">
                                    Next Visit Date
                                </label>

                                <input type="date"
                                       name="next_visit_date"
                                       class="mhb-input w-full rounded-2xl bg-slate-950/80 border border-white/15 text-white px-5 py-4 outline-none focus:border-emerald-400">
                            </div>
                        </div>

                        <div class="rounded-[1.7rem] bg-slate-950/55 border border-white/10 p-5 shadow-[inset_0_1px_0_rgba(255,255,255,.05)]">
                            <div class="flex items-center justify-between gap-3 mb-3">
                                <label class="text-sm font-black text-slate-200 block">
                                    Diagnosis
                                </label>
                                <span class="text-xs text-slate-500 font-bold">Required</span>
                            </div>

                            <textarea name="diagnosis"
                                      rows="5"
                                      placeholder="Write diagnosis details..."
                                      class="mhb-input w-full rounded-2xl bg-slate-950/80 border border-white/15 text-white px-5 py-4 outline-none focus:border-emerald-400 placeholder:text-slate-500"
                                      required></textarea>
                        </div>
                                                <div class="rounded-[1.7rem] bg-slate-950/55 border border-white/10 p-5 shadow-[inset_0_1px_0_rgba(255,255,255,.05)]">
                            <div class="flex items-center justify-between gap-3 mb-3">
                                <label class="text-sm font-black text-slate-200 block">
                                    Medicines
                                </label>
                                <span class="text-xs text-slate-500 font-bold">Required</span>
                            </div>

                            <textarea name="medicines"
                                      rows="6"
                                      placeholder="Example: Napa 500mg - 1+0+1 after meal - 5 days"
                                      class="mhb-input w-full rounded-2xl bg-slate-950/80 border border-white/15 text-white px-5 py-4 outline-none focus:border-emerald-400 placeholder:text-slate-500"
                                      required></textarea>
                        </div>

                        <div class="rounded-[1.7rem] bg-slate-950/55 border border-white/10 p-5 shadow-[inset_0_1px_0_rgba(255,255,255,.05)]">
                            <div class="flex items-center justify-between gap-3 mb-3">
                                <label class="text-sm font-black text-slate-200 block">
                                    Advice
                                </label>
                                <span class="text-xs text-slate-500 font-bold">Optional</span>
                            </div>

                            <textarea name="advice"
                                      rows="5"
                                      placeholder="Write lifestyle advice, tests, rest, diet or warning signs..."
                                      class="mhb-input w-full rounded-2xl bg-slate-950/80 border border-white/15 text-white px-5 py-4 outline-none focus:border-emerald-400 placeholder:text-slate-500"></textarea>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="rounded-[2rem] bg-gradient-to-br from-slate-950/70 via-slate-900/70 to-emerald-950/60 border border-white/10 p-6 shadow-[0_25px_90px_rgba(0,0,0,.28)]">
                            <div class="h-16 w-16 rounded-3xl bg-emerald-400/10 border border-emerald-300/20 flex items-center justify-center text-3xl mhb-float">
                                💊
                            </div>

                            <h3 class="text-white text-2xl font-black mt-5">
                                Prescription Tips
                            </h3>

                            <div class="mt-5 space-y-4">
                                <div class="rounded-2xl bg-white/[0.05] border border-white/10 p-4">
                                    <p class="text-emerald-200 text-sm font-black">Diagnosis</p>
                                    <p class="text-slate-400 text-sm mt-1">
                                        Write short but clear clinical diagnosis.
                                    </p>
                                </div>

                                <div class="rounded-2xl bg-white/[0.05] border border-white/10 p-4">
                                    <p class="text-cyan-200 text-sm font-black">Medicine Format</p>
                                    <p class="text-slate-400 text-sm mt-1">
                                        Mention dose, timing, meal instruction and duration.
                                    </p>
                                </div>

                                <div class="rounded-2xl bg-white/[0.05] border border-white/10 p-4">
                                    <p class="text-blue-200 text-sm font-black">Follow Up</p>
                                    <p class="text-slate-400 text-sm mt-1">
                                        Add next visit date if patient needs review.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-[2rem] bg-emerald-500/10 border border-emerald-300/20 p-6 shadow-[0_25px_90px_rgba(16,185,129,.10)]">
                            <p class="text-emerald-200 text-xs font-black uppercase tracking-[.2em]">
                                Privacy Protected
                            </p>

                            <h3 class="text-white text-xl font-black mt-3">
                                Secure Prescription Flow
                            </h3>

                            <p class="text-slate-300 text-sm leading-relaxed mt-3">
                                This prescription will be linked with the selected verified patient and current authenticated doctor account.
                            </p>
                        </div>

                        <button type="submit"
                                class="mhb-premium-btn w-full rounded-[1.7rem] bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 hover:from-emerald-500 hover:via-teal-500 hover:to-cyan-500 text-white font-black py-5 shadow-[0_25px_80px_rgba(16,185,129,.25)] transition">
                            Save Prescription
                        </button>

                        <p class="text-center text-slate-500 text-xs font-semibold">
                            Make sure all prescription information is accurate before saving.
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
let selectedPatient = null;
let searchTimer = null;

const patientSearch = document.getElementById('patientSearch');
const searchResults = document.getElementById('searchResults');
const searchStatus = document.getElementById('searchStatus');
const patientIdInput = document.getElementById('patient_id');
const privacyKeyInput = document.getElementById('privacy_key');
const verifyBtn = document.getElementById('verifyBtn');
const verifyMessage = document.getElementById('verifyMessage');

const selectedPatientBox = document.getElementById('selectedPatientBox');
const selectedPatientName = document.getElementById('selectedPatientName');
const selectedPatientCode = document.getElementById('selectedPatientCode');
const selectedPatientEmail = document.getElementById('selectedPatientEmail');

const patientInfoCard = document.getElementById('patientInfoCard');
const lockedNotice = document.getElementById('lockedNotice');
const prescriptionFormArea = document.getElementById('prescriptionFormArea');

const alreadyVerifiedPatient = @json($alreadyVerifiedPatient);

document.addEventListener('DOMContentLoaded', function () {
    if (alreadyVerifiedPatient) {
        selectedPatient = alreadyVerifiedPatient;
        patientIdInput.value = alreadyVerifiedPatient.id;

        if (patientInfoCard) {
            unlockPrescription(
                alreadyVerifiedPatient,
                'Patient already verified from profile. Prescription unlocked.'
            );
        }
    }
});

if (patientSearch) {
    patientSearch.addEventListener('input', function () {
        clearTimeout(searchTimer);

        const query = this.value.trim();

        resetVerificationOnly();

        if (query.length < 1) {
            searchResults.innerHTML = '';
            searchStatus.innerText = 'Type patient ID, name, email or phone to search.';
            return;
        }

        searchStatus.innerText = 'Searching patient...';

        searchTimer = setTimeout(() => {
            fetch(`{{ route('prescriptions.patient.search') }}?q=${encodeURIComponent(query)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                searchResults.innerHTML = '';

                if (!data.length) {
                    searchStatus.innerText = 'No patient found.';
                    return;
                }

                searchStatus.innerText = `${data.length} patient found. Click one patient to select.`;

                data.forEach(patient => {
                    const item = document.createElement('button');
                    item.type = 'button';
                    item.className = 'w-full text-left rounded-3xl bg-white/[0.07] border border-white/10 hover:border-emerald-400/60 hover:bg-emerald-400/10 p-5 transition shadow-[0_15px_45px_rgba(0,0,0,.18)]';

                    item.innerHTML = `
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <div class="text-white font-black text-lg">${escapeHtml(patient.name)}</div>
                                <div class="text-slate-300 text-sm mt-1">
                                    ${escapeHtml(patient.patient_code)} • ${escapeHtml(patient.email)} • ${escapeHtml(patient.phone)}
                                </div>
                            </div>
                            <div class="px-4 py-2 rounded-2xl bg-emerald-500/10 border border-emerald-300/20 text-emerald-200 font-black text-sm">
                                Select
                            </div>
                        </div>
                    `;

                    item.addEventListener('click', () => selectPatient(patient));
                    searchResults.appendChild(item);
                });
            })
            .catch(() => {
                searchStatus.innerText = 'Search failed. Please try again.';
            });
        }, 350);
    });
}

function selectPatient(patient) {
    selectedPatient = patient;

    if (patientIdInput) {
        patientIdInput.value = patient.id;
    }

    if (selectedPatientBox) {
        selectedPatientBox.classList.remove('hidden');
    }

    if (selectedPatientName) {
        selectedPatientName.innerText = patient.name ?? 'N/A';
    }

    if (selectedPatientCode) {
        selectedPatientCode.innerText = patient.patient_code ?? ('P' + patient.id);
    }

    if (selectedPatientEmail) {
        selectedPatientEmail.innerText = patient.email ?? 'N/A';
    }

    if (privacyKeyInput) {
        privacyKeyInput.disabled = false;
        privacyKeyInput.value = '';
        privacyKeyInput.placeholder = 'Enter patient privacy key';
    }

    if (verifyBtn) {
        verifyBtn.disabled = false;
        verifyBtn.innerText = 'Verify & Unlock';
        verifyBtn.className = 'mhb-premium-btn mt-4 w-full rounded-2xl bg-gradient-to-r from-emerald-600 to-cyan-600 hover:from-emerald-500 hover:to-cyan-500 text-white font-black py-4 cursor-pointer shadow-[0_20px_60px_rgba(16,185,129,.22)] transition';
    }

    if (verifyMessage) {
        verifyMessage.innerText = 'Enter privacy key and click verify.';
        verifyMessage.className = 'mt-4 text-sm font-bold text-slate-300';
    }

    if (searchStatus) {
        searchStatus.innerText = 'Patient selected. Now verify privacy key.';
    }
}

if (verifyBtn) {
    verifyBtn.addEventListener('click', function () {
        if (!selectedPatient) {
            verifyMessage.innerText = 'Please select a patient first.';
            return;
        }

        const privacyKey = privacyKeyInput.value.trim();

        if (!privacyKey) {
            verifyMessage.innerText = 'Please enter patient privacy key.';
            verifyMessage.className = 'mt-4 text-sm font-bold text-yellow-200';
            return;
        }

        verifyBtn.disabled = true;
        verifyBtn.innerText = 'Verifying...';

        fetch(`{{ route('prescriptions.patient.verify') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': `{{ csrf_token() }}`,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                patient_id: selectedPatient.id,
                privacy_key: privacyKey
            })
        })
        .then(async res => {
            const data = await res.json();

            if (!res.ok) {
                throw data;
            }

            unlockPrescription(data.patient, data.message);
        })
        .catch(err => {
            verifyBtn.disabled = false;
            verifyBtn.innerText = 'Verify & Unlock';
            verifyBtn.className = 'mhb-premium-btn mt-4 w-full rounded-2xl bg-gradient-to-r from-emerald-600 to-cyan-600 hover:from-emerald-500 hover:to-cyan-500 text-white font-black py-4 cursor-pointer shadow-[0_20px_60px_rgba(16,185,129,.22)] transition';

            verifyMessage.innerText = err.message || 'Invalid privacy key.';
            verifyMessage.className = 'mt-4 text-sm font-bold text-red-300';
        });
    });
}

function unlockPrescription(patient, message) {
    selectedPatient = patient;

    if (patientIdInput) {
        patientIdInput.value = patient.id;
    }

    if (selectedPatientBox) {
        selectedPatientBox.classList.remove('hidden');
    }

    if (selectedPatientName) {
        selectedPatientName.innerText = patient.name ?? 'N/A';
    }

    if (selectedPatientCode) {
        selectedPatientCode.innerText = patient.patient_code ?? ('P' + patient.id);
    }

    if (selectedPatientEmail) {
        selectedPatientEmail.innerText = patient.email ?? 'N/A';
    }

    if (verifyBtn) {
        verifyBtn.disabled = true;
        verifyBtn.innerText = 'Verified';
        verifyBtn.className = 'mt-4 w-full rounded-2xl bg-emerald-500 text-white font-black py-4 cursor-default shadow-[0_20px_60px_rgba(16,185,129,.20)]';
    }

    if (verifyMessage) {
        verifyMessage.innerText = message ?? 'Patient verified. Prescription unlocked.';
        verifyMessage.className = 'mt-4 text-sm font-bold text-emerald-200';
    }

    const infoCode = document.getElementById('infoCode');
    const infoName = document.getElementById('infoName');
    const infoPhone = document.getElementById('infoPhone');
    const infoBlood = document.getElementById('infoBlood');
    const infoGender = document.getElementById('infoGender');

    if (infoCode) infoCode.innerText = patient.patient_code ?? ('P' + patient.id);
    if (infoName) infoName.innerText = patient.name ?? 'N/A';
    if (infoPhone) infoPhone.innerText = patient.phone ?? 'N/A';
    if (infoBlood) infoBlood.innerText = patient.blood_group ?? 'N/A';
    if (infoGender) infoGender.innerText = patient.gender ?? 'N/A';

    if (patientInfoCard) {
        patientInfoCard.classList.remove('hidden');
    }

    if (lockedNotice) {
        lockedNotice.classList.add('hidden');
    }

    if (prescriptionFormArea) {
        prescriptionFormArea.classList.remove('hidden');
    }
}

function resetVerificationOnly() {
    selectedPatient = null;

    if (patientIdInput) {
        patientIdInput.value = '';
    }

    if (selectedPatientBox) {
        selectedPatientBox.classList.add('hidden');
    }

    if (patientInfoCard) {
        patientInfoCard.classList.add('hidden');
    }

    if (prescriptionFormArea) {
        prescriptionFormArea.classList.add('hidden');
    }

    if (lockedNotice) {
        lockedNotice.classList.remove('hidden');
    }

    if (privacyKeyInput) {
        privacyKeyInput.disabled = false;
        privacyKeyInput.value = '';
        privacyKeyInput.placeholder = 'Enter patient privacy key';
    }

    if (verifyBtn) {
        verifyBtn.disabled = true;
        verifyBtn.innerText = 'Verify & Unlock';
        verifyBtn.className = 'mt-4 w-full rounded-2xl bg-emerald-600/40 text-white font-black py-4 cursor-not-allowed';
    }

    if (verifyMessage) {
        verifyMessage.innerText = 'Select patient first.';
        verifyMessage.className = 'mt-4 text-sm font-bold text-slate-300';
    }
}

function escapeHtml(text) {
    return String(text ?? '').replace(/[&<>"']/g, function (m) {
        return ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        })[m];
    });
}
</script>
</x-app-layout>