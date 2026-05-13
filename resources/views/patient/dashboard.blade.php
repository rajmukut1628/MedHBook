<x-app-layout>

<style>
    @keyframes mhbFadeUp {
        from { opacity: 0; transform: translateY(18px) scale(.985); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    @keyframes mhbFloat {
        0%,100% { transform: translate3d(0,0,0); }
        50% { transform: translate3d(14px,-18px,0); }
    }

    @keyframes mhbGlow {
        0%,100% {
            box-shadow:
                0 0 28px rgba(16,185,129,.13),
                inset 0 0 0 1px rgba(255,255,255,.08);
        }
        50% {
            box-shadow:
                0 0 70px rgba(34,211,238,.25),
                inset 0 0 0 1px rgba(45,212,191,.22);
        }
    }

    @keyframes mhbShine {
        0% { transform: translateX(-140%) skewX(-12deg); }
        100% { transform: translateX(150%) skewX(-12deg); }
    }

    @keyframes mhbBorderMove {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .mhb-patient-page {
        animation: mhbFadeUp .55s ease both;
    }

    .mhb-orb {
        animation: mhbFloat 7s ease-in-out infinite;
    }

    .mhb-shell {
        min-height: 100vh;
        background:
            radial-gradient(circle at 12% 15%, rgba(16,185,129,.18), transparent 28%),
            radial-gradient(circle at 88% 20%, rgba(34,211,238,.13), transparent 30%),
            radial-gradient(circle at 50% 95%, rgba(59,130,246,.10), transparent 36%),
            linear-gradient(135deg, #020617, #031414 45%, #020617);
    }

    .mhb-glass {
        background:
            linear-gradient(145deg, rgba(15,23,42,.76), rgba(2,6,23,.62)),
            radial-gradient(circle at top left, rgba(16,185,129,.12), transparent 40%),
            radial-gradient(circle at bottom right, rgba(34,211,238,.10), transparent 45%);
        border: 1px solid rgba(255,255,255,.10);
        backdrop-filter: blur(22px);
        -webkit-backdrop-filter: blur(22px);
    }

    .mhb-hero,
    .mhb-card {
        position: relative;
        overflow: hidden;
    }

    .mhb-hero {
        animation: mhbGlow 4.5s ease-in-out infinite;
    }

    .mhb-hero::before,
    .mhb-card::before {
        content: "";
        position: absolute;
        inset: 0;
        width: 45%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,.13), transparent);
        transform: translateX(-140%) skewX(-12deg);
        pointer-events: none;
    }

    .mhb-hero:hover::before,
    .mhb-card:hover::before {
        animation: mhbShine 1s ease;
    }

    .mhb-card {
        min-height: 170px;
        padding: 20px !important;
        transition: all .32s ease;
    }

    .mhb-card:hover {
        transform: translateY(-5px);
        border-color: rgba(45,212,191,.35);
        filter: saturate(1.08);
    }

    .mhb-card-border {
        position: relative;
    }

    .mhb-card-border::after {
        content: "";
        position: absolute;
        inset: -1px;
        border-radius: inherit;
        padding: 1px;
        background: linear-gradient(120deg, rgba(16,185,129,.55), transparent, rgba(34,211,238,.45), transparent);
        background-size: 250% 250%;
        animation: mhbBorderMove 5s ease infinite;
        -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        pointer-events: none;
        opacity: .65;
    }

    .mhb-icon {
        width: 44px;
        height: 44px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
        margin-bottom: 14px;
        border: 1px solid rgba(255,255,255,.13);
        background: rgba(255,255,255,.12);
    }

    .mhb-btn {
        transition: all .25s ease;
    }

    .mhb-btn:hover {
        transform: translateY(-2px);
    }

    .mhb-btn:active {
        transform: scale(.96);
    }

    @media(max-width: 768px) {
        .mhb-card {
            min-height: 150px;
            padding: 18px !important;
        }
    }
</style>

<div class="relative mhb-shell overflow-hidden py-6 px-4 sm:px-6">

    {{-- Animated Background --}}
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-40 -left-40 w-[470px] h-[470px] rounded-full bg-emerald-500/18 blur-3xl mhb-orb"></div>
        <div class="absolute top-24 -right-40 w-[480px] h-[480px] rounded-full bg-cyan-500/14 blur-3xl mhb-orb" style="animation-delay:1s;"></div>
        <div class="absolute bottom-0 left-1/3 w-[520px] h-[520px] rounded-full bg-blue-500/10 blur-3xl mhb-orb" style="animation-delay:2s;"></div>

        <div class="absolute inset-0 opacity-[.045]"
             style="background-image:
             linear-gradient(rgba(255,255,255,.7) 1px, transparent 1px),
             linear-gradient(90deg, rgba(255,255,255,.7) 1px, transparent 1px);
             background-size: 44px 44px;">
        </div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto space-y-5 mhb-patient-page">

        {{-- HERO --}}
        <div class="mhb-hero mhb-glass mhb-card-border rounded-[28px] p-5 sm:p-6 lg:p-7 text-white shadow-2xl">
            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                <div>
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-500/12 border border-emerald-300/20 text-emerald-200 text-xs font-black mb-3 shadow-[0_0_30px_rgba(16,185,129,.12)]">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-400"></span>
                        </span>
                        Patient Control Center
                    </span>

                    <h1 class="text-3xl md:text-4xl font-black tracking-tight">
                        Patient Dashboard
                    </h1>

                    <p class="mt-2 text-slate-300 text-sm md:text-base max-w-3xl leading-6">
                        Welcome back,
                        <span class="text-emerald-300 font-black">{{ auth()->user()->name }}</span>.
                        Manage doctors, appointments, encrypted documents, profile and privacy key.
                    </p>
                </div>

                <div class="grid grid-cols-3 gap-3 min-w-full sm:min-w-[380px] lg:min-w-[410px]">
                    <div class="rounded-2xl border border-white/10 bg-white/[.055] p-4">
                        <p class="text-2xl font-black text-emerald-300">24/7</p>
                        <p class="text-[11px] text-slate-400 font-bold mt-1">Access</p>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-white/[.055] p-4">
                        <p class="text-2xl font-black text-cyan-300">Safe</p>
                        <p class="text-[11px] text-slate-400 font-bold mt-1">Reports</p>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-white/[.055] p-4">
                        <p class="text-2xl font-black text-amber-300">Fast</p>
                        <p class="text-[11px] text-slate-400 font-bold mt-1">Booking</p>
                    </div>
                </div>

            </div>
        </div>
                {{-- SESSION MESSAGES --}}
        @if(session('success'))
            <div class="rounded-[22px] bg-emerald-500/14 border border-emerald-300/30 text-emerald-100 px-5 py-4 font-black shadow-[0_0_40px_rgba(16,185,129,.14)]">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-[22px] bg-red-500/14 border border-red-300/30 text-red-100 px-5 py-4 font-black shadow-[0_0_40px_rgba(239,68,68,.14)]">
                ⚠️ {{ session('error') }}
            </div>
        @endif

        {{-- DASHBOARD CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">

            {{-- Privacy Key --}}
            <div class="mhb-card mhb-glass mhb-card-border rounded-[24px] text-white shadow-[0_22px_70px_rgba(0,0,0,.34)]">
                <div class="relative z-10 h-full flex flex-col">

                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="mhb-icon bg-gradient-to-br from-amber-300/95 to-yellow-500/95 text-slate-950 shadow-[0_0_35px_rgba(251,191,36,.30)]">
                                🔐
                            </div>

                            <h2 class="text-xl font-black tracking-tight">Privacy Key</h2>

                            <p class="mt-1 text-slate-300 text-sm leading-5">
                                Share only with trusted doctors.
                            </p>
                        </div>

                        <span class="px-3 py-1 rounded-full bg-amber-400/10 border border-amber-300/20 text-amber-200 text-[10px] font-black uppercase tracking-[.18em]">
                            Private
                        </span>
                    </div>

                    <div class="mt-4 rounded-2xl bg-black/28 border border-amber-300/20 p-4 shadow-inner">
                        <p class="text-amber-200 text-[10px] font-black uppercase tracking-[.24em]">
                            Your Key
                        </p>

                        <div class="mt-2 font-black text-amber-100 break-all text-lg sm:text-xl tracking-[.14em] leading-tight">
                            {{ $privacyKey ?? '------' }}
                        </div>
                    </div>

                    <div class="mt-auto pt-4 flex flex-wrap gap-2">
                        <button type="button"
                                class="mhb-btn px-4 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white text-xs font-black shadow-[0_14px_35px_rgba(16,185,129,.25)]"
                                onclick="navigator.clipboard.writeText('{{ $privacyKey ?? '' }}'); this.innerText='Copied!'; setTimeout(() => this.innerText='Copy Key', 2000);">
                            Copy Key
                        </button>

                        <form method="POST"
                              action="{{ route('patient.privacy-key.regenerate') }}"
                              onsubmit="return confirm('Old privacy key will stop working. Continue?')">
                            @csrf

                            <button type="submit"
                                    class="mhb-btn px-4 py-2.5 rounded-xl bg-gradient-to-r from-red-500 to-rose-500 text-white text-xs font-black shadow-[0_14px_35px_rgba(239,68,68,.24)]">
                                Regenerate
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Find Doctors --}}
            <a href="{{ route('find.doctors') }}"
               class="mhb-card rounded-[24px] bg-gradient-to-br from-blue-600 via-sky-600 to-cyan-500 text-white shadow-[0_22px_70px_rgba(14,165,233,.26)]">
                <div class="relative z-10 h-full flex flex-col">
                    <div class="flex items-start justify-between gap-4">
                        <div class="mhb-icon bg-white/15 border-white/15 shadow-[0_0_35px_rgba(255,255,255,.13)]">
                            👨‍⚕️
                        </div>

                        <span class="px-3 py-1 rounded-full bg-white/12 border border-white/15 text-white/90 text-[10px] font-black uppercase tracking-[.18em]">
                            Search
                        </span>
                    </div>

                    <h2 class="text-xl font-black tracking-tight">Find Doctors</h2>

                    <p class="mt-2 text-blue-50 text-sm leading-5 max-w-xs">
                        Browse approved specialist doctors and book appointments.
                    </p>

                    <div class="mt-auto pt-4 flex items-center justify-between">
                        <span class="text-xs font-black text-white/80">Open Doctor List</span>
                        <span class="w-9 h-9 rounded-full bg-white/15 border border-white/15 flex items-center justify-center">
                            →
                        </span>
                    </div>
                </div>
            </a>

            {{-- My Appointments --}}
            <a href="{{ route('appointments.index') }}"
               class="mhb-card rounded-[24px] bg-gradient-to-br from-purple-600 via-violet-600 to-fuchsia-500 text-white shadow-[0_22px_70px_rgba(168,85,247,.25)]">
                <div class="relative z-10 h-full flex flex-col">
                    <div class="flex items-start justify-between gap-4">
                        <div class="mhb-icon bg-white/15 border-white/15 shadow-[0_0_35px_rgba(255,255,255,.13)]">
                            📋
                        </div>

                        <span class="px-3 py-1 rounded-full bg-white/12 border border-white/15 text-white/90 text-[10px] font-black uppercase tracking-[.18em]">
                            Track
                        </span>
                    </div>

                    <h2 class="text-xl font-black tracking-tight">My Appointments</h2>

                    <p class="mt-2 text-purple-50 text-sm leading-5 max-w-xs">
                        Track appointment requests, approved schedules and history.
                    </p>

                    <div class="mt-auto pt-4 flex items-center justify-between">
                        <span class="text-xs font-black text-white/80">View Appointments</span>
                        <span class="w-9 h-9 rounded-full bg-white/15 border border-white/15 flex items-center justify-center">
                            →
                        </span>
                    </div>
                </div>
            </a>
                        {{-- Medical Documents --}}
            <a href="{{ route('medical-documents.index') }}"
               class="mhb-card rounded-[24px] bg-gradient-to-br from-red-600 via-rose-600 to-pink-500 text-white shadow-[0_22px_70px_rgba(244,63,94,.25)]">
                <div class="relative z-10 h-full flex flex-col">
                    <div class="flex items-start justify-between gap-4">
                        <div class="mhb-icon bg-white/15 border-white/15 shadow-[0_0_35px_rgba(255,255,255,.13)]">
                            📄
                        </div>

                        <span class="px-3 py-1 rounded-full bg-white/12 border border-white/15 text-white/90 text-[10px] font-black uppercase tracking-[.18em]">
                            Vault
                        </span>
                    </div>

                    <h2 class="text-xl font-black tracking-tight">Medical Documents</h2>

                    <p class="mt-2 text-red-50 text-sm leading-5 max-w-xs">
                        Open encrypted medical reports and secure health files.
                    </p>

                    <div class="mt-auto pt-4 flex items-center justify-between">
                        <span class="text-xs font-black text-white/80">Open Vault</span>
                        <span class="w-9 h-9 rounded-full bg-white/15 border border-white/15 flex items-center justify-center">
                            →
                        </span>
                    </div>
                </div>
            </a>

            {{-- Profile --}}
            <a href="{{ route('patient.my-profile') }}"
               class="mhb-card rounded-[24px] bg-gradient-to-br from-orange-500 via-amber-500 to-yellow-500 text-white shadow-[0_22px_70px_rgba(245,158,11,.25)]">
                <div class="relative z-10 h-full flex flex-col">
                    <div class="flex items-start justify-between gap-4">
                        <div class="mhb-icon bg-white/15 border-white/15 shadow-[0_0_35px_rgba(255,255,255,.13)]">
                            👤
                        </div>

                        <span class="px-3 py-1 rounded-full bg-white/12 border border-white/15 text-white/90 text-[10px] font-black uppercase tracking-[.18em]">
                            Edit
                        </span>
                    </div>

                    <h2 class="text-xl font-black tracking-tight">Profile</h2>

                    <p class="mt-2 text-orange-50 text-sm leading-5 max-w-xs">
                        View and edit personal information, contact data and identity.
                    </p>

                    <div class="mt-auto pt-4 flex items-center justify-between">
                        <span class="text-xs font-black text-white/80">Manage Profile</span>
                        <span class="w-9 h-9 rounded-full bg-white/15 border border-white/15 flex items-center justify-center">
                            →
                        </span>
                    </div>
                </div>
            </a>

            {{-- Settings --}}
            <a href="{{ route('patient.settings') }}"
               class="mhb-card rounded-[24px] bg-gradient-to-br from-slate-700 via-slate-800 to-slate-950 text-white shadow-[0_22px_70px_rgba(15,23,42,.35)]">
                <div class="relative z-10 h-full flex flex-col">
                    <div class="flex items-start justify-between gap-4">
                        <div class="mhb-icon bg-white/15 border-white/15 shadow-[0_0_35px_rgba(255,255,255,.13)]">
                            ⚙️
                        </div>

                        <span class="px-3 py-1 rounded-full bg-white/12 border border-white/15 text-white/90 text-[10px] font-black uppercase tracking-[.18em]">
                            Account
                        </span>
                    </div>

                    <h2 class="text-xl font-black tracking-tight">Settings</h2>

                    <p class="mt-2 text-slate-300 text-sm leading-5 max-w-xs">
                        Password, account preferences and secure settings.
                    </p>

                    <div class="mt-auto pt-4 flex items-center justify-between">
                        <span class="text-xs font-black text-white/80">Open Settings</span>
                        <span class="w-9 h-9 rounded-full bg-white/15 border border-white/15 flex items-center justify-center">
                            →
                        </span>
                    </div>
                </div>
            </a>

        </div>

    </div>
</div>

<script>
    function applyPatientTheme() {
        let mode = localStorage.getItem('patient_theme_mode') || 'dark';

        if (mode === 'white') {
            document.body.style.background =
                'linear-gradient(135deg,#f8fafc,#e0f2fe,#ffffff)';
        }

        if (mode === 'dark') {
            document.body.style.background =
                'linear-gradient(135deg,#020617,#0f172a,#111827)';
        }

        if (mode === 'blue') {
            document.body.style.background =
                'linear-gradient(135deg,#0f172a,#1d4ed8,#38bdf8)';
        }
    }

    document.addEventListener('DOMContentLoaded', applyPatientTheme);
</script>

</x-app-layout>