<x-app-layout>

<style>
    @keyframes dashFadeUp {
        from { opacity: 0; transform: translateY(22px) scale(.985); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    @keyframes orbFloat {
        0%,100% { transform: translateY(0px) translateX(0px); }
        50% { transform: translateY(-16px) translateX(10px); }
    }

    @keyframes shineMove {
        0% { transform: translateX(-130%); }
        100% { transform: translateX(130%); }
    }

    @keyframes glowPulse {
        0%,100% { box-shadow: 0 0 22px rgba(16,185,129,.18); }
        50% { box-shadow: 0 0 55px rgba(34,211,238,.28); }
    }

    .patient-page {
        animation: dashFadeUp .65s ease both;
    }

    .patient-orb {
        animation: orbFloat 7s ease-in-out infinite;
    }

    .patient-glass {
        background:
            linear-gradient(135deg, rgba(15,23,42,.82), rgba(15,23,42,.55)),
            radial-gradient(circle at top left, rgba(16,185,129,.15), transparent 35%),
            radial-gradient(circle at bottom right, rgba(34,211,238,.11), transparent 38%);
        border: 1px solid rgba(255,255,255,.12);
        backdrop-filter: blur(22px);
        -webkit-backdrop-filter: blur(22px);
    }

    .patient-hero,
    .patient-card {
        position: relative;
        overflow: hidden;
    }

    .patient-hero {
        animation: glowPulse 4s ease-in-out infinite;
    }

    .patient-hero::before,
    .patient-card::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(120deg, transparent, rgba(255,255,255,.12), transparent);
        transform: translateX(-130%);
    }

    .patient-hero:hover::before,
    .patient-card:hover::before {
        animation: shineMove 1.05s ease;
    }

    .patient-card {
        min-height: 82px;
        padding: 12px 14px !important;
        transition: all .3s ease;
    }

    .patient-card:hover {
        transform: translateY(-4px) scale(1.012);
        border-color: rgba(45,212,191,.35);
        filter: saturate(1.1);
    }

    .patient-icon {
        width: 38px;
        height: 38px;
        border-radius: 13px;
        font-size: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 8px;
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

    .privacy-key-box {
        padding: 9px 12px !important;
    }

    .privacy-key-text {
        font-size: 18px;
        letter-spacing: .13em;
        line-height: 1.2;
    }

    @media(max-width: 768px) {
        .patient-card {
            min-height: 78px;
            padding: 12px !important;
        }
    }
</style>

<div class="relative min-h-screen overflow-hidden bg-slate-950 py-7 px-4 sm:px-6">

    {{-- Animated Background --}}
    <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-emerald-950 to-slate-950"></div>
    <div class="absolute -top-44 -left-44 w-[520px] h-[520px] rounded-full bg-emerald-500/20 blur-3xl patient-orb"></div>
    <div class="absolute top-20 -right-44 w-[520px] h-[520px] rounded-full bg-cyan-500/18 blur-3xl patient-orb"></div>
    <div class="absolute bottom-0 left-1/3 w-[600px] h-[600px] rounded-full bg-blue-500/10 blur-3xl patient-orb"></div>

    <div class="absolute inset-0 opacity-[.06]"
         style="background-image:
         linear-gradient(rgba(255,255,255,.45) 1px, transparent 1px),
         linear-gradient(90deg, rgba(255,255,255,.45) 1px, transparent 1px);
         background-size: 42px 42px;">
    </div>

    <div class="relative z-10 max-w-7xl mx-auto space-y-5 patient-page">

        {{-- HERO --}}
        <div class="patient-hero patient-glass rounded-[26px] p-5 sm:p-6 text-white shadow-2xl">
            <div class="relative z-10">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-500/15 border border-emerald-300/20 text-emerald-200 text-xs font-black mb-3">
                    🩺 Patient Control Center
                </span>

                <h1 class="text-3xl md:text-4xl font-black tracking-tight">
                    Patient Dashboard
                </h1>

                <p class="mt-2 text-slate-300 text-sm md:text-base max-w-3xl leading-6">
                    Welcome back, <span class="text-emerald-300 font-black">{{ auth()->user()->name }}</span>.
                    Manage doctors, appointments, encrypted documents, profile and privacy key.
                </p>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-[20px] bg-emerald-500/15 border border-emerald-300/30 text-emerald-100 p-4 font-black shadow-xl">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-[20px] bg-red-500/15 border border-red-300/30 text-red-100 p-4 font-black shadow-xl">
                ⚠️ {{ session('error') }}
            </div>
        @endif
                {{-- DASHBOARD CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">

            {{-- Privacy Key --}}
            <div class="patient-card patient-glass rounded-[22px] text-white shadow-2xl">
                <div class="relative z-10">
                    <div class="patient-icon bg-gradient-to-br from-amber-400 to-yellow-400 shadow-xl">
                        🔐
                    </div>

                    <h2 class="text-xl font-black">Privacy Key</h2>

                    <p class="mt-1 text-slate-300 text-sm leading-5">
                        Share only with trusted doctors.
                    </p>

                    <div class="privacy-key-box mt-2 rounded-xl bg-black/25 border border-amber-300/20">
                        <p class="text-amber-200 text-[10px] font-black uppercase tracking-[.22em]">
                            Your Key
                        </p>

                        <div class="privacy-key-text mt-1 font-black text-amber-200 break-all">
                            {{ $privacyKey ?? '------' }}
                        </div>
                    </div>

                    <div class="mt-2 flex flex-wrap gap-2">
                        <button type="button"
                                class="premium-btn px-3 py-2 rounded-lg bg-gradient-to-r from-emerald-500 to-teal-500 text-white text-xs font-black shadow-xl"
                                onclick="navigator.clipboard.writeText('{{ $privacyKey ?? '' }}'); this.innerText='Copied!'; setTimeout(() => this.innerText='Copy Key', 2000);">
                            Copy Key
                        </button>

                        <form method="POST"
                              action="{{ route('patient.privacy-key.regenerate') }}"
                              onsubmit="return confirm('Old privacy key will stop working. Continue?')">
                            @csrf

                            <button type="submit"
                                    class="premium-btn px-3 py-2 rounded-lg bg-gradient-to-r from-red-500 to-rose-500 text-white text-xs font-black shadow-xl">
                                Regenerate
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Find Doctors --}}
            <a href="{{ route('find.doctors') }}"
               class="patient-card rounded-[22px] bg-gradient-to-br from-blue-600 via-sky-600 to-cyan-500 text-white shadow-2xl">
                <div class="relative z-10">
                    <div class="patient-icon bg-white/15 border border-white/10">
                        👨‍⚕️
                    </div>

                    <h2 class="text-xl font-black">Find Doctors</h2>

                    <p class="mt-1 text-blue-50 text-sm leading-5">
                        Browse approved specialist doctors.
                    </p>
                </div>
            </a>

            {{-- My Appointments --}}
            <a href="{{ route('appointments.index') }}"
               class="patient-card rounded-[22px] bg-gradient-to-br from-purple-600 via-violet-600 to-fuchsia-500 text-white shadow-2xl">
                <div class="relative z-10">
                    <div class="patient-icon bg-white/15 border border-white/10">
                        📋
                    </div>

                    <h2 class="text-xl font-black">My Appointments</h2>

                    <p class="mt-1 text-purple-50 text-sm leading-5">
                        Track appointment requests and history.
                    </p>
                </div>
            </a>

            {{-- Medical Documents --}}
            <a href="{{ route('medical-documents.index') }}"
               class="patient-card rounded-[22px] bg-gradient-to-br from-red-600 via-rose-600 to-pink-500 text-white shadow-2xl">
                <div class="relative z-10">
                    <div class="patient-icon bg-white/15 border border-white/10">
                        📄
                    </div>

                    <h2 class="text-xl font-black">Medical Documents</h2>

                    <p class="mt-1 text-red-50 text-sm leading-5">
                        Open encrypted medical vault.
                    </p>
                </div>
            </a>

            {{-- Profile --}}
            <a href="{{ route('patient.my-profile') }}"
               class="patient-card rounded-[22px] bg-gradient-to-br from-orange-500 via-amber-500 to-yellow-500 text-white shadow-2xl">
                <div class="relative z-10">
                    <div class="patient-icon bg-white/15 border border-white/10">
                        👤
                    </div>

                    <h2 class="text-xl font-black">Profile</h2>

                    <p class="mt-1 text-orange-50 text-sm leading-5">
                        View and edit personal information.
                    </p>
                </div>
            </a>

            {{-- Settings --}}
            <a href="{{ route('patient.settings') }}"
               class="patient-card rounded-[22px] bg-gradient-to-br from-slate-700 via-slate-800 to-slate-950 text-white shadow-2xl">
                <div class="relative z-10">
                    <div class="patient-icon bg-white/15 border border-white/10">
                        ⚙️
                    </div>

                    <h2 class="text-xl font-black">Settings</h2>

                    <p class="mt-1 text-slate-300 text-sm leading-5">
                        Password and account settings.
                    </p>
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