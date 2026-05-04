<x-app-layout>
<style>
    @keyframes pageFadeUp {
        from { opacity: 0; transform: translateY(35px) scale(.97); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    @keyframes orbMove {
        0%,100% { transform: translateY(0) translateX(0) scale(1); }
        50% { transform: translateY(-25px) translateX(18px) scale(1.08); }
    }

    @keyframes premiumGlow {
        0%,100% {
            box-shadow: 0 0 35px rgba(16,185,129,.22), inset 0 0 0 1px rgba(255,255,255,.08);
        }
        50% {
            box-shadow: 0 0 90px rgba(34,211,238,.34), inset 0 0 0 1px rgba(45,212,191,.22);
        }
    }

    @keyframes shineMove {
        0% { transform: translateX(-140%) skewX(-15deg); }
        100% { transform: translateX(140%) skewX(-15deg); }
    }

    @keyframes cardFloat {
        0%,100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }

    @keyframes iconPulse {
        0%,100% { transform: scale(1); }
        50% { transform: scale(1.08); }
    }

    .dashboard-page {
        animation: pageFadeUp .8s ease both;
    }

    .orb {
        animation: orbMove 7s ease-in-out infinite;
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
        animation: shineMove 1.1s ease;
    }

    .hero-glow {
        animation: premiumGlow 4.5s ease-in-out infinite;
    }

    .premium-card {
        position: relative;
        overflow: hidden;
        transition: .28s ease;
    }

    .premium-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 24px 70px rgba(0,0,0,.35);
    }

    .premium-card:hover .card-icon {
        animation: iconPulse .8s ease infinite;
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
</style>

<div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 px-4 sm:px-6 py-10">

    {{-- Animated Background --}}
    <div class="absolute -top-44 -left-44 w-[540px] h-[540px] rounded-full bg-emerald-500/20 blur-3xl orb"></div>
    <div class="absolute top-36 -right-44 w-[540px] h-[540px] rounded-full bg-cyan-500/20 blur-3xl orb"></div>
    <div class="absolute bottom-0 left-1/3 w-[580px] h-[580px] rounded-full bg-blue-500/10 blur-3xl orb"></div>

    <div class="pointer-events-none absolute inset-0 opacity-[.08]"
         style="background-image: linear-gradient(rgba(255,255,255,.7) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.7) 1px, transparent 1px); background-size: 46px 46px;">
    </div>

    <div class="relative z-10 max-w-6xl mx-auto dashboard-page">
                {{-- HERO HEADER --}}
        <div class="premium-panel hero-glow rounded-[36px] p-7 sm:p-10 shadow-2xl text-white mb-10">

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

                <div>
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-500/20 border border-emerald-300/20 text-emerald-200 text-sm font-black mb-4">
                        🏥 Patient Dashboard
                    </div>

                    <h1 class="text-4xl sm:text-5xl font-black tracking-tight">
                        Welcome back,
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-cyan-400">
                            {{ auth()->user()->name }}
                        </span>
                    </h1>

                    <p class="text-slate-300 mt-3 max-w-xl font-semibold">
                        Manage appointments, connect with doctors and monitor your health — all in one secure place.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="/appointments/create"
                           class="premium-btn px-6 py-3 rounded-2xl bg-gradient-to-r from-emerald-400 to-cyan-400 text-slate-950 font-black shadow-xl">
                            🚀 Book Appointment
                        </a>

                        <a href="/appointments"
                           class="premium-btn px-6 py-3 rounded-2xl bg-white/10 border border-white/10 text-white font-black hover:bg-white/20">
                            📅 My Appointments
                        </a>
                    </div>
                </div>

                {{-- Right side mini stats --}}
                <div class="grid grid-cols-2 gap-4 w-full max-w-sm">

                    <div class="premium-card rounded-2xl bg-white/10 border border-white/10 p-5 text-center">
                        <p class="text-slate-400 text-sm font-bold">Appointments</p>
                        <h3 class="text-3xl font-black mt-2">12</h3>
                    </div>

                    <div class="premium-card rounded-2xl bg-white/10 border border-white/10 p-5 text-center">
                        <p class="text-slate-400 text-sm font-bold">Doctors</p>
                        <h3 class="text-3xl font-black mt-2">8</h3>
                    </div>

                    <div class="premium-card rounded-2xl bg-white/10 border border-white/10 p-5 text-center">
                        <p class="text-slate-400 text-sm font-bold">Reports</p>
                        <h3 class="text-3xl font-black mt-2">5</h3>
                    </div>

                    <div class="premium-card rounded-2xl bg-white/10 border border-white/10 p-5 text-center">
                        <p class="text-slate-400 text-sm font-bold">Status</p>
                        <h3 class="text-lg font-black mt-2 text-emerald-400">Active</h3>
                    </div>

                </div>

            </div>
        </div>
                {{-- DASHBOARD ACTION CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-10">

            <a href="/appointments/create"
               class="premium-card rounded-[30px] bg-white/10 border border-emerald-300/20 p-7 shadow-2xl text-white backdrop-blur-xl">
                <div class="card-icon h-14 w-14 rounded-2xl bg-gradient-to-br from-emerald-400 to-cyan-400 flex items-center justify-center text-2xl shadow-xl mb-5">
                    📅
                </div>
                <h3 class="text-2xl font-black">Book Appointment</h3>
                <p class="text-slate-300 mt-2 text-sm font-semibold">
                    Schedule your doctor visit instantly.
                </p>
            </a>

            <a href="/appointments"
               class="premium-card rounded-[30px] bg-white/10 border border-white/10 p-7 shadow-2xl text-white backdrop-blur-xl">
                <div class="card-icon h-14 w-14 rounded-2xl bg-gradient-to-br from-purple-400 to-indigo-500 flex items-center justify-center text-2xl shadow-xl mb-5">
                    🧾
                </div>
                <h3 class="text-2xl font-black">My Appointments</h3>
                <p class="text-slate-300 mt-2 text-sm font-semibold">
                    Track your booking history and schedules.
                </p>
            </a>

            <a href="/patient/my-profile"
               class="premium-card rounded-[30px] bg-white/10 border border-white/10 p-7 shadow-2xl text-white backdrop-blur-xl">
                <div class="card-icon h-14 w-14 rounded-2xl bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center text-2xl shadow-xl mb-5">
                    👤
                </div>
                <h3 class="text-2xl font-black">My Profile</h3>
                <p class="text-slate-300 mt-2 text-sm font-semibold">
                    View and update personal health information.
                </p>
            </a>

            <a href="/patient/documents"
               class="premium-card rounded-[30px] bg-white/10 border border-white/10 p-7 shadow-2xl text-white backdrop-blur-xl">
                <div class="card-icon h-14 w-14 rounded-2xl bg-gradient-to-br from-rose-400 to-pink-500 flex items-center justify-center text-2xl shadow-xl mb-5">
                    📄
                </div>
                <h3 class="text-2xl font-black">Upload Documents</h3>
                <p class="text-slate-300 mt-2 text-sm font-semibold">
                    Upload reports, prescriptions and medical files.
                </p>
            </a>

            <a href="/doctors"
               class="premium-card rounded-[30px] bg-white/10 border border-white/10 p-7 shadow-2xl text-white backdrop-blur-xl">
                <div class="card-icon h-14 w-14 rounded-2xl bg-gradient-to-br from-blue-400 to-cyan-500 flex items-center justify-center text-2xl shadow-xl mb-5">
                    👨‍⚕️
                </div>
                <h3 class="text-2xl font-black">Find Doctors</h3>
                <p class="text-slate-300 mt-2 text-sm font-semibold">
                    Browse specialist doctors and healthcare providers.
                </p>
            </a>

            <a href="/settings"
               class="premium-card rounded-[30px] bg-white/10 border border-white/10 p-7 shadow-2xl text-white backdrop-blur-xl">
                <div class="card-icon h-14 w-14 rounded-2xl bg-gradient-to-br from-slate-400 to-slate-600 flex items-center justify-center text-2xl shadow-xl mb-5">
                    ⚙️
                </div>
                <h3 class="text-2xl font-black">Settings</h3>
                <p class="text-slate-300 mt-2 text-sm font-semibold">
                    Manage password, account and theme preferences.
                </p>
            </a>

        </div>
                {{-- PRIVACY KEY + HEALTH SUMMARY --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">

            <div class="premium-panel rounded-[32px] p-7 shadow-2xl text-white lg:col-span-1">
                <div class="relative z-10">
                    <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-yellow-300 to-orange-500 flex items-center justify-center text-2xl shadow-xl mb-5">
                        🔐
                    </div>

                    <h3 class="text-2xl font-black">Privacy Key</h3>
                    <p class="text-slate-300 mt-2 text-sm font-semibold">
                        Share only with trusted doctors.
                    </p>

                    <div class="mt-5 rounded-2xl bg-black/25 border border-white/10 p-4">
                        <p id="privacyKeyText" class="text-2xl font-black text-yellow-300 tracking-widest">
                            PH-{{ strtoupper(substr(md5(auth()->user()->email), 0, 6)) }}
                        </p>
                    </div>

                    <div class="mt-5 flex flex-wrap gap-3">
                        <button type="button"
                                onclick="copyPrivacyKey()"
                                class="premium-btn px-5 py-3 rounded-2xl bg-emerald-500 text-white font-black">
                            Copy Key
                        </button>

                        <button type="button"
                                onclick="regeneratePrivacyKey()"
                                class="premium-btn px-5 py-3 rounded-2xl bg-red-500 text-white font-black">
                            Regenerate
                        </button>
                    </div>
                </div>
            </div>

            <div class="premium-panel rounded-[32px] p-7 shadow-2xl text-white lg:col-span-2">
                <div class="relative z-10">
                    <div class="flex items-center justify-between gap-4 flex-wrap mb-6">
                        <div>
                            <h3 class="text-2xl font-black">Health Snapshot</h3>
                            <p class="text-slate-300 mt-1 text-sm font-semibold">
                                Quick overview of your medical profile.
                            </p>
                        </div>

                        <span class="px-4 py-2 rounded-full bg-emerald-500/20 border border-emerald-300/20 text-emerald-200 text-xs font-black">
                            Live Profile
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="info-card text-white">
                            <p class="text-slate-400 text-sm font-black">Blood Group</p>
                            <h4 class="mt-2 text-3xl font-black">
                                {{ $patient->blood_group ?? 'N/A' }}
                            </h4>
                        </div>

                        <div class="info-card text-white">
                            <p class="text-slate-400 text-sm font-black">Age</p>
                            <h4 class="mt-2 text-3xl font-black">
                                {{ $patient->age ?? 'N/A' }}
                            </h4>
                        </div>

                        <div class="info-card text-white">
                            <p class="text-slate-400 text-sm font-black">Emergency</p>
                            <h4 class="mt-2 text-xl font-black break-words">
                                {{ $patient->emergency_contact ?? 'N/A' }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- QUICK STATUS BAR --}}
        <div class="premium-panel rounded-[32px] p-6 shadow-2xl text-white mb-10">
            <div class="relative z-10 grid grid-cols-1 md:grid-cols-4 gap-4">

                <div class="rounded-2xl bg-white/10 border border-white/10 p-4">
                    <p class="text-slate-400 text-xs font-black">Account</p>
                    <h4 class="text-emerald-300 text-xl font-black mt-1">Verified</h4>
                </div>

                <div class="rounded-2xl bg-white/10 border border-white/10 p-4">
                    <p class="text-slate-400 text-xs font-black">Security</p>
                    <h4 class="text-cyan-300 text-xl font-black mt-1">Encrypted</h4>
                </div>

                <div class="rounded-2xl bg-white/10 border border-white/10 p-4">
                    <p class="text-slate-400 text-xs font-black">Role</p>
                    <h4 class="text-purple-300 text-xl font-black mt-1">Patient</h4>
                </div>

                <div class="rounded-2xl bg-white/10 border border-white/10 p-4">
                    <p class="text-slate-400 text-xs font-black">System</p>
                    <h4 class="text-yellow-300 text-xl font-black mt-1">Online</h4>
                </div>

            </div>
        </div>
                {{-- PREMIUM FOOTER CTA --}}
        <div class="premium-panel rounded-[32px] p-7 shadow-2xl text-white">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-5">
                <div>
                    <h3 class="text-2xl font-black">Need medical support?</h3>
                    <p class="text-slate-300 mt-1 font-semibold">
                        Book an appointment with a trusted doctor anytime.
                    </p>
                </div>

                <a href="/appointments/create"
                   class="premium-btn inline-flex justify-center px-7 py-4 rounded-2xl bg-gradient-to-r from-emerald-400 to-cyan-400 text-slate-950 font-black shadow-xl">
                    Book Appointment Now
                </a>
            </div>
        </div>

    </div>
</div>

<script>
    window.copyPrivacyKey = function () {
        const key = document.getElementById('privacyKeyText')?.innerText || '';

        if (!key) return;

        navigator.clipboard.writeText(key).then(function () {
            alert('Privacy key copied!');
        });
    };

    window.regeneratePrivacyKey = function () {
        const keyText = document.getElementById('privacyKeyText');
        if (!keyText) return;

        const random = Math.random().toString(36).substring(2, 8).toUpperCase();
        keyText.innerText = 'PH-' + random;

        alert('New privacy key generated!');
    };
</script>
</x-app-layout>