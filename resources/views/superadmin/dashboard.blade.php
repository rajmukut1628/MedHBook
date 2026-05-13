<x-app-layout>
<style>
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(28px) scale(.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    @keyframes floatOrb {
        0%,100% { transform: translateY(0) translateX(0); }
        50% { transform: translateY(-24px) translateX(16px); }
    }

    @keyframes shineMove {
        0% { transform: translateX(-130%); }
        100% { transform: translateX(130%); }
    }

    @keyframes glowPulse {
        0%,100% {
            box-shadow: 0 0 35px rgba(168,85,247,.24), inset 0 0 0 1px rgba(255,255,255,.08);
        }
        50% {
            box-shadow: 0 0 90px rgba(236,72,153,.38), inset 0 0 0 1px rgba(255,255,255,.18);
        }
    }

    @keyframes countPop {
        0%,100% { transform: scale(1); }
        50% { transform: scale(1.06); }
    }

    .sa-page {
        animation: fadeUp .75s ease both;
    }

    .sa-orb {
        animation: floatOrb 7s ease-in-out infinite;
    }

    .sa-glass {
        background: rgba(255,255,255,.09);
        border: 1px solid rgba(255,255,255,.12);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
    }

    .sa-hero {
        position: relative;
        overflow: hidden;
        animation: glowPulse 4s ease-in-out infinite;
    }

    .sa-hero::before,
    .sa-card::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(120deg, transparent, rgba(255,255,255,.16), transparent);
        transform: translateX(-130%);
    }

    .sa-hero:hover::before,
    .sa-card:hover::before {
        animation: shineMove 1.15s ease;
    }

    .sa-stat {
        transition: all .35s ease;
    }

    .sa-stat:hover {
        transform: translateY(-7px) scale(1.015);
        border-color: rgba(216,180,254,.35);
    }

    .sa-count {
        animation: countPop 2.8s ease-in-out infinite;
    }

    .sa-card {
        position: relative;
        overflow: hidden;
        transition: all .35s ease;
    }

    .sa-card:hover {
        transform: translateY(-8px) scale(1.025);
        filter: saturate(1.12);
    }

    .sa-btn {
        transition: all .25s ease;
    }

    .sa-btn:hover {
        transform: translateY(-2px);
    }

    .sa-btn:active {
        transform: scale(.97);
    }
</style>

<div class="relative min-h-screen overflow-hidden bg-slate-950 py-10 px-6">

    {{-- Animated Background --}}
    <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-black to-indigo-950"></div>
    <div class="absolute -top-44 -left-44 w-[560px] h-[560px] rounded-full bg-purple-600/25 blur-3xl sa-orb"></div>
    <div class="absolute top-20 -right-44 w-[600px] h-[600px] rounded-full bg-pink-500/20 blur-3xl sa-orb"></div>
    <div class="absolute bottom-0 left-1/3 w-[650px] h-[650px] rounded-full bg-cyan-500/10 blur-3xl sa-orb"></div>

    <div class="absolute inset-0 opacity-[.07]"
         style="background-image:
         linear-gradient(rgba(255,255,255,.45) 1px, transparent 1px),
         linear-gradient(90deg, rgba(255,255,255,.45) 1px, transparent 1px);
         background-size: 46px 46px;">
    </div>

    <div class="relative z-10 max-w-7xl mx-auto space-y-8 sa-page">

        {{-- HERO --}}
        <div class="sa-hero rounded-[38px] bg-gradient-to-r from-indigo-700 via-purple-700 to-pink-600 p-8 sm:p-10 text-white shadow-2xl">
            <div class="absolute right-0 top-0 h-80 w-80 rounded-full bg-white/10 blur-3xl"></div>
            <div class="absolute -bottom-20 -left-20 h-80 w-80 rounded-full bg-cyan-400/10 blur-3xl"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                <div>
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/15 text-white text-sm font-black mb-4">
                        👑 Super Admin Control
                    </span>

                    <h1 class="text-4xl md:text-6xl font-black tracking-tight">
                        Super Admin Dashboard
                    </h1>

                    <p class="mt-4 text-indigo-100 text-lg max-w-3xl leading-8">
                        Welcome {{ auth()->user()->name }}. Manage admins, patients and doctors with premium
                        system-level control. Medical documents and appointment monitoring are intentionally
                        removed from this dashboard.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <span class="px-4 py-2 rounded-2xl bg-white/10 border border-white/10 text-sm font-bold">
                            Admin Control
                        </span>

                        <span class="px-4 py-2 rounded-2xl bg-white/10 border border-white/10 text-sm font-bold">
                            User Governance
                        </span>

                        <span class="px-4 py-2 rounded-2xl bg-white/10 border border-white/10 text-sm font-bold">
                            Patient Vault Restricted
                        </span>
                    </div>
                </div>

                <div class="min-w-full sm:min-w-[340px] lg:min-w-[380px] rounded-[30px] bg-black/20 border border-white/10 p-6 backdrop-blur-xl">
                    <p class="text-white/70 text-xs font-black uppercase tracking-[.22em]">
                        Access Boundary
                    </p>

                    <h2 class="mt-3 text-3xl font-black">
                        Super Admin Mode
                    </h2>

                    <p class="mt-3 text-indigo-100 text-sm leading-6">
                        This panel excludes patient-only vault and upload features for privacy separation.
                    </p>
                </div>
            </div>
        </div>

        {{-- STATS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

            <div class="sa-stat sa-glass rounded-[30px] p-7 text-white shadow-xl">
                <p class="text-slate-300 font-black text-sm uppercase tracking-wider">Total Users</p>
                <h2 class="sa-count text-5xl font-black mt-4">{{ $users ?? 0 }}</h2>
                <p class="text-slate-400 mt-2 text-sm">All platform accounts</p>
            </div>

            <div class="sa-stat sa-glass rounded-[30px] p-7 text-white shadow-xl">
                <p class="text-slate-300 font-black text-sm uppercase tracking-wider">Total Admins</p>
                <h2 class="sa-count text-5xl font-black mt-4 text-pink-300">{{ $admins ?? 0 }}</h2>
                <p class="text-slate-400 mt-2 text-sm">General admin users</p>
            </div>
                        <div class="sa-stat sa-glass rounded-[30px] p-7 text-white shadow-xl">
                <p class="text-slate-300 font-black text-sm uppercase tracking-wider">Patients</p>
                <h2 class="sa-count text-5xl font-black mt-4 text-cyan-300">{{ $patients ?? 0 }}</h2>
                <p class="text-slate-400 mt-2 text-sm">Patient accounts</p>
            </div>

            <div class="sa-stat sa-glass rounded-[30px] p-7 text-white shadow-xl">
                <p class="text-slate-300 font-black text-sm uppercase tracking-wider">Doctors</p>
                <h2 class="sa-count text-5xl font-black mt-4 text-emerald-300">{{ $doctors ?? 0 }}</h2>
                <p class="text-slate-400 mt-2 text-sm">Doctor accounts</p>
            </div>

        </div>

        {{-- ACTION CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

            <a href="{{ route('superadmin.admins') }}"
               class="sa-card rounded-[34px] p-8 bg-gradient-to-br from-purple-600 via-fuchsia-600 to-pink-600 text-white shadow-2xl">
                <div class="relative z-10">
                    <div class="w-16 h-16 rounded-[24px] bg-white/15 border border-white/10 flex items-center justify-center text-4xl mb-5">
                        🛡️
                    </div>

                    <h2 class="text-2xl font-black">Manage Admins</h2>

                    <p class="mt-3 text-purple-50 leading-6">
                        Create, edit and delete general admin accounts with controlled access.
                    </p>
                </div>
            </a>

            <a href="{{ route('patients.index') }}"
               class="sa-card rounded-[34px] p-8 bg-gradient-to-br from-blue-600 via-sky-600 to-cyan-500 text-white shadow-2xl">
                <div class="relative z-10">
                    <div class="w-16 h-16 rounded-[24px] bg-white/15 border border-white/10 flex items-center justify-center text-4xl mb-5">
                        👥
                    </div>

                    <h2 class="text-2xl font-black">Manage Patients</h2>

                    <p class="mt-3 text-blue-50 leading-6">
                        View, create and manage patient accounts without accessing private vault files.
                    </p>
                </div>
            </a>

            <a href="{{ route('doctors.index') }}"
               class="sa-card rounded-[34px] p-8 bg-gradient-to-br from-emerald-600 via-green-600 to-lime-500 text-white shadow-2xl">
                <div class="relative z-10">
                    <div class="w-16 h-16 rounded-[24px] bg-white/15 border border-white/10 flex items-center justify-center text-4xl mb-5">
                        👨‍⚕️
                    </div>

                    <h2 class="text-2xl font-black">Manage Doctors</h2>

                    <p class="mt-3 text-emerald-50 leading-6">
                        Approve, reject, create and manage doctor accounts securely.
                    </p>
                </div>
            </a>

            <a href="{{ route('profile.edit') }}"
               class="sa-card rounded-[34px] p-8 bg-gradient-to-br from-orange-500 via-rose-500 to-red-500 text-white shadow-2xl">
                <div class="relative z-10">
                    <div class="w-16 h-16 rounded-[24px] bg-white/15 border border-white/10 flex items-center justify-center text-4xl mb-5">
                        👤
                    </div>

                    <h2 class="text-2xl font-black">My Profile</h2>

                    <p class="mt-3 text-orange-50 leading-6">
                        Update profile information, password and personal account settings.
                    </p>
                </div>
            </a>

        </div>

        {{-- ACCESS NOTICE --}}
        <div class="sa-glass rounded-[34px] p-8 shadow-2xl text-white">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <h2 class="text-2xl font-black">
                        Privacy-first Super Admin Boundary
                    </h2>

                    <p class="mt-3 text-slate-300 leading-7 max-w-4xl">
                        Super Admin can manage admins, patients and doctors. Appointment monitoring,
                        medical document vault access and document upload features are intentionally removed
                        from this dashboard. Patient medical documents remain patient-only and protected by
                        private encrypted storage.
                    </p>
                </div>

                <div class="w-20 h-20 rounded-[28px] bg-emerald-500/15 border border-emerald-300/20 flex items-center justify-center text-4xl">
                    🔐
                </div>
            </div>
        </div>

    </div>
</div>
</x-app-layout>