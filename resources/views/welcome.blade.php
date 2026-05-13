<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MedHBook</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#030607] text-white overflow-x-hidden selection:bg-emerald-400 selection:text-black">

<!-- Premium Animated Background -->
<div class="fixed inset-0 -z-10 overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(16,185,129,0.20),transparent_34%),radial-gradient(circle_at_bottom_right,rgba(239,68,68,0.18),transparent_34%),linear-gradient(135deg,#020403,#050505,#020617)]"></div>

    <div class="absolute top-20 left-10 w-72 h-72 bg-emerald-500/20 rounded-full blur-[110px] animate-pulse"></div>
    <div class="absolute bottom-20 right-10 w-96 h-96 bg-red-600/20 rounded-full blur-[130px] animate-pulse"></div>
    <div class="absolute top-1/2 left-1/2 w-80 h-80 bg-cyan-500/10 rounded-full blur-[120px] animate-bounce"></div>

    <div class="absolute inset-0 opacity-[0.08] bg-[linear-gradient(rgba(255,255,255,.12)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,.12)_1px,transparent_1px)] bg-[size:48px_48px]"></div>
</div>

<!-- Navbar -->
<nav class="sticky top-0 z-50 w-full border-b border-white/10 bg-black/55 backdrop-blur-2xl shadow-[0_20px_80px_rgba(16,185,129,0.10)]">
<div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

<a href="/" class="group flex items-center gap-3">
    <div class="relative w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-400 via-emerald-500 to-teal-700 flex items-center justify-center shadow-[0_0_35px_rgba(16,185,129,0.55)] transition-all duration-500 group-hover:scale-110">
        <span class="relative z-10 text-xl">❤</span>
        <div class="absolute inset-0 rounded-2xl bg-emerald-400/40 blur-xl animate-pulse"></div>
    </div>

    <div>
        <span class="block text-2xl font-black tracking-tight">
            MedHBook
        </span>
        <span class="block text-[11px] text-emerald-300/80 font-semibold tracking-[0.25em] uppercase">
            Secure Health
        </span>
    </div>
</a>

<div class="hidden md:flex items-center gap-3">
    <span class="px-4 py-2 rounded-full border border-emerald-400/20 bg-emerald-400/10 text-emerald-200 text-sm font-bold shadow-[0_0_30px_rgba(16,185,129,0.12)]">
        ● Live Protected
    </span>
</div>

</div>
</nav>
<!-- Hero Section -->
<section class="relative max-w-7xl mx-auto px-6 py-14 lg:py-20">

<div class="relative grid grid-cols-1 lg:grid-cols-2 min-h-[590px] rounded-[34px] overflow-hidden border border-white/10 bg-white/[0.045] backdrop-blur-2xl shadow-[0_0_80px_rgba(16,185,129,0.16)]">

    <!-- Live Moving Border Glow -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-24 left-0 w-[420px] h-[420px] bg-emerald-400/20 blur-[120px] animate-pulse"></div>
        <div class="absolute -bottom-24 right-0 w-[420px] h-[420px] bg-red-500/20 blur-[120px] animate-pulse"></div>
    </div>

    <!-- Left Content -->
    <div class="relative z-10 flex items-center px-8 sm:px-10 lg:px-16 py-16">

        <div class="max-w-xl">

            <div class="inline-flex items-center gap-3 px-5 py-2 rounded-full border border-emerald-400/25 bg-emerald-400/10 text-emerald-200 text-sm font-extrabold shadow-[0_0_35px_rgba(16,185,129,0.18)]">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-400"></span>
                </span>
                Smart Digital Healthcare Platform
            </div>

            <h1 class="mt-8 text-4xl sm:text-5xl lg:text-6xl font-black leading-[1.05] tracking-tight">
                One Platform for
                <span class="block mt-2 text-transparent bg-clip-text bg-gradient-to-r from-emerald-300 via-emerald-400 to-teal-300 drop-shadow-[0_0_22px_rgba(16,185,129,0.38)]">
                    Prescriptions,
                </span>
                Appointments & Reports
            </h1>

            <p class="mt-7 text-base sm:text-lg text-slate-300 leading-relaxed max-w-lg">
                Manage patients, doctors, appointments and reports with a secure,
                premium and modern healthcare experience.
            </p>
                        <div class="mt-10 flex flex-wrap gap-4">

                <a href="{{ route('login') }}"
                class="group relative px-8 py-4 rounded-2xl bg-white text-black font-black overflow-hidden shadow-[0_18px_45px_rgba(255,255,255,0.18)] transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_25px_70px_rgba(255,255,255,0.28)]">
                    <span class="relative z-10">Login</span>
                    <div class="absolute inset-0 bg-gradient-to-r from-white via-emerald-100 to-white opacity-0 group-hover:opacity-100 transition duration-500"></div>
                </a>

                <a href="{{ route('register', ['role' => 'patient']) }}"
                class="group relative px-8 py-4 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-black overflow-hidden shadow-[0_18px_55px_rgba(16,185,129,0.40)] transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_25px_90px_rgba(16,185,129,0.55)]">
                    <span class="relative z-10">Patient Register</span>
                    <div class="absolute inset-0 bg-gradient-to-r from-teal-400 via-emerald-400 to-green-500 opacity-0 group-hover:opacity-100 transition duration-500"></div>
                </a>

                <a href="{{ route('register', ['role' => 'doctor']) }}"
                class="group relative px-8 py-4 rounded-2xl border border-white/15 bg-white/5 text-white font-black overflow-hidden shadow-[0_18px_55px_rgba(255,255,255,0.08)] transition-all duration-500 hover:-translate-y-1 hover:border-emerald-300/50 hover:shadow-[0_25px_80px_rgba(16,185,129,0.22)]">
                    <span class="relative z-10">Doctor Register</span>
                    <div class="absolute inset-0 bg-emerald-400/10 opacity-0 group-hover:opacity-100 transition duration-500"></div>
                </a>

            </div>

            <div class="mt-12 grid grid-cols-3 gap-4 max-w-lg">
                <div class="rounded-2xl border border-white/10 bg-white/[0.06] p-4 shadow-[0_0_35px_rgba(16,185,129,0.08)] backdrop-blur-xl">
                    <h3 class="text-2xl font-black text-emerald-300">24/7</h3>
                    <p class="mt-1 text-xs text-slate-400 font-semibold">Access</p>
                </div>

                <div class="rounded-2xl border border-white/10 bg-white/[0.06] p-4 shadow-[0_0_35px_rgba(16,185,129,0.08)] backdrop-blur-xl">
                    <h3 class="text-2xl font-black text-red-300">100%</h3>
                    <p class="mt-1 text-xs text-slate-400 font-semibold">Private</p>
                </div>

                <div class="rounded-2xl border border-white/10 bg-white/[0.06] p-4 shadow-[0_0_35px_rgba(16,185,129,0.08)] backdrop-blur-xl">
                    <h3 class="text-2xl font-black text-cyan-300">Smart</h3>
                    <p class="mt-1 text-xs text-slate-400 font-semibold">System</p>
                </div>
            </div>

        </div>
    </div>
        <!-- Right Visual Panel -->
    <div class="relative bg-gradient-to-br from-[#140202] via-[#220303] to-[#060606] flex items-center justify-center overflow-hidden">

        <!-- Background Glow Effects -->
        <div class="absolute top-10 right-10 w-72 h-72 bg-red-500/20 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-10 left-10 w-72 h-72 bg-emerald-500/20 rounded-full blur-[120px] animate-pulse"></div>

        <!-- Rotating Ring -->
        <div class="absolute w-[430px] h-[430px] rounded-full border border-red-500/20 animate-spin"
             style="animation-duration: 25s;"></div>

        <div class="absolute w-[330px] h-[330px] rounded-full border border-emerald-400/20 animate-spin"
             style="animation-duration: 18s; animation-direction: reverse;"></div>

        <!-- Main Center Content -->
        <div class="relative z-10 text-center">

            <div class="relative inline-flex items-center justify-center w-64 h-64 rounded-full
                        bg-gradient-to-br from-red-600/20 via-red-500/10 to-emerald-500/10
                        border border-white/10 backdrop-blur-2xl
                        shadow-[0_0_120px_rgba(239,68,68,0.30)]">

                <div class="absolute inset-6 rounded-full border border-white/10"></div>

                <div class="text-center">
                    <div class="text-8xl font-black text-red-500 drop-shadow-[0_0_40px_rgba(239,68,68,0.8)]">
                        Care
                    </div>
                    <p class="mt-2 text-sm tracking-[0.35em] uppercase text-red-200/80 font-bold">
                        HEALTH
                    </p>
                </div>
            </div>

            <!-- Floating Cards -->
            <div class="absolute -top-6 -left-20 px-5 py-3 rounded-2xl border border-white/10 bg-white/10 backdrop-blur-xl shadow-[0_0_50px_rgba(16,185,129,0.18)] animate-bounce">
                <p class="text-xs text-emerald-300 font-black">🔒 Encrypted Files</p>
            </div>

            <div class="absolute top-8 -right-24 px-5 py-3 rounded-2xl border border-white/10 bg-white/10 backdrop-blur-xl shadow-[0_0_50px_rgba(239,68,68,0.18)] animate-bounce"
                 style="animation-delay: 1s;">
                <p class="text-xs text-red-300 font-black">🩺 Smart Doctors</p>
            </div>

            <div class="absolute -bottom-8 left-1/2 -translate-x-1/2 px-5 py-3 rounded-2xl border border-white/10 bg-white/10 backdrop-blur-xl shadow-[0_0_50px_rgba(59,130,246,0.18)] animate-bounce"
                 style="animation-delay: 2s;">
                <p class="text-xs text-cyan-300 font-black">📄 Secure Reports</p>
            </div>
        </div>
    </div>
    </div>
</section>

<!-- Bottom Premium Feature Strip -->
<section class="max-w-7xl mx-auto px-6 pb-16">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

        <div class="group rounded-3xl border border-white/10 bg-white/[0.045] p-6 backdrop-blur-2xl shadow-[0_0_50px_rgba(16,185,129,0.08)] hover:shadow-[0_0_80px_rgba(16,185,129,0.20)] transition-all duration-500 hover:-translate-y-1">
            <div class="w-12 h-12 rounded-2xl bg-emerald-500/15 flex items-center justify-center text-2xl mb-5 shadow-[0_0_30px_rgba(16,185,129,0.25)]">
                🔐
            </div>
            <h3 class="text-xl font-black">Secure Documents</h3>
            <p class="mt-3 text-sm text-slate-400 leading-relaxed">
                Patient reports and medical files stay protected with private access control.
            </p>
        </div>

        <div class="group rounded-3xl border border-white/10 bg-white/[0.045] p-6 backdrop-blur-2xl shadow-[0_0_50px_rgba(239,68,68,0.08)] hover:shadow-[0_0_80px_rgba(239,68,68,0.20)] transition-all duration-500 hover:-translate-y-1">
            <div class="w-12 h-12 rounded-2xl bg-red-500/15 flex items-center justify-center text-2xl mb-5 shadow-[0_0_30px_rgba(239,68,68,0.25)]">
                🩺
            </div>
            <h3 class="text-xl font-black">Doctor Workflow</h3>
            <p class="mt-3 text-sm text-slate-400 leading-relaxed">
                Doctors can manage patients, appointments and digital prescriptions smoothly.
            </p>
        </div>

        <div class="group rounded-3xl border border-white/10 bg-white/[0.045] p-6 backdrop-blur-2xl shadow-[0_0_50px_rgba(59,130,246,0.08)] hover:shadow-[0_0_80px_rgba(59,130,246,0.20)] transition-all duration-500 hover:-translate-y-1">
            <div class="w-12 h-12 rounded-2xl bg-cyan-500/15 flex items-center justify-center text-2xl mb-5 shadow-[0_0_30px_rgba(59,130,246,0.25)]">
                📅
            </div>
            <h3 class="text-xl font-black">Appointments</h3>
            <p class="mt-3 text-sm text-slate-400 leading-relaxed">
                Patients can find doctors and continue appointment flow without changing process.
            </p>
        </div>

    </div>
</section>

</body>
</html>