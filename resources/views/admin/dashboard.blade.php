<x-app-layout>
<style>
    @keyframes adminFadeUp {
        from { opacity: 0; transform: translateY(28px) scale(.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    @keyframes adminFloat {
        0%,100% { transform: translateY(0) translateX(0); }
        50% { transform: translateY(-22px) translateX(14px); }
    }

    @keyframes adminShine {
        0% { transform: translateX(-130%); }
        100% { transform: translateX(130%); }
    }

    @keyframes adminGlow {
        0%,100% {
            box-shadow: 0 0 35px rgba(99,102,241,.22), inset 0 0 0 1px rgba(255,255,255,.08);
        }
        50% {
            box-shadow: 0 0 85px rgba(34,211,238,.35), inset 0 0 0 1px rgba(255,255,255,.16);
        }
    }

    .admin-page { animation: adminFadeUp .75s ease both; }
    .admin-orb { animation: adminFloat 7s ease-in-out infinite; }

    .admin-glass {
        background: rgba(255,255,255,.09);
        border: 1px solid rgba(255,255,255,.12);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
    }

    .admin-hero,
    .admin-card {
        position: relative;
        overflow: hidden;
    }

    .admin-hero { animation: adminGlow 4s ease-in-out infinite; }

    .admin-hero::before,
    .admin-card::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(120deg, transparent, rgba(255,255,255,.15), transparent);
        transform: translateX(-130%);
    }

    .admin-hero:hover::before,
    .admin-card:hover::before {
        animation: adminShine 1.1s ease;
    }

    .admin-card {
        transition: all .35s ease;
    }

    .admin-card:hover {
        transform: translateY(-8px) scale(1.025);
        filter: saturate(1.12);
    }

    .admin-stat {
        transition: all .35s ease;
    }

    .admin-stat:hover {
        transform: translateY(-7px) scale(1.015);
        border-color: rgba(125,211,252,.35);
    }
</style>

<div class="relative min-h-screen overflow-hidden bg-slate-950 py-10 px-6">

    <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-indigo-950 to-slate-950"></div>
    <div class="absolute -top-44 -left-44 w-[560px] h-[560px] rounded-full bg-indigo-500/25 blur-3xl admin-orb"></div>
    <div class="absolute top-20 -right-44 w-[560px] h-[560px] rounded-full bg-cyan-500/20 blur-3xl admin-orb"></div>
    <div class="absolute bottom-0 left-1/3 w-[620px] h-[620px] rounded-full bg-blue-500/10 blur-3xl admin-orb"></div>

    <div class="relative z-10 max-w-7xl mx-auto space-y-8 admin-page">

        {{-- Header --}}
        <div class="admin-hero rounded-[36px] border border-white/10 bg-white/10 backdrop-blur-xl shadow-2xl p-8 sm:p-10 text-white">
            <div class="absolute top-0 right-0 w-80 h-80 bg-indigo-500/20 blur-3xl rounded-full"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-cyan-500/20 blur-3xl rounded-full"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                <div>
                    <span class="inline-flex px-4 py-2 rounded-full bg-indigo-500/20 text-indigo-200 text-sm font-black mb-4">
                        🛡️ Admin Panel
                    </span>

                    <h1 class="text-4xl md:text-6xl font-black tracking-tight">
                        Admin Dashboard
                    </h1>

                    <p class="text-slate-300 mt-4 text-lg max-w-3xl leading-8">
                        Manage patients, doctors, account suspension controls and profile settings.
                        Patient medical document vault and appointment monitoring are intentionally removed
                        from this admin dashboard.
                    </p>
                </div>

                <div class="rounded-[30px] bg-black/20 border border-white/10 p-6 min-w-full sm:min-w-[340px] lg:min-w-[380px]">
                    <p class="text-white/70 text-xs font-black uppercase tracking-[.22em]">
                        Admin Boundary
                    </p>

                    <h2 class="mt-3 text-3xl font-black">
                        Restricted Operations
                    </h2>

                    <p class="mt-3 text-slate-300 text-sm leading-6">
                        Admin can manage accounts, but cannot access patient document upload/vault features.
                    </p>
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="admin-stat rounded-[30px] p-7 admin-glass text-white shadow-2xl">
                <p class="text-slate-400 font-black uppercase tracking-wider">Total Patients</p>
                <h2 class="text-6xl font-black text-cyan-300 mt-3">{{ $patients }}</h2>
                <p class="mt-2 text-slate-400">Registered patient accounts</p>
            </div>

            <div class="admin-stat rounded-[30px] p-7 admin-glass text-white shadow-2xl">
                <p class="text-slate-400 font-black uppercase tracking-wider">Total Doctors</p>
                <h2 class="text-6xl font-black text-emerald-300 mt-3">{{ $doctors }}</h2>
                <p class="mt-2 text-slate-400">Registered doctor accounts</p>
            </div>
        </div>

        {{-- Admin Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

            <a href="{{ route('patients.index') }}"
               class="admin-card rounded-[34px] p-8 bg-gradient-to-br from-blue-600 via-sky-600 to-cyan-500 text-white shadow-2xl">
                <div class="relative z-10">
                    <div class="w-16 h-16 rounded-[24px] bg-white/15 border border-white/10 flex items-center justify-center text-4xl mb-5">
                        👥
                    </div>

                    <h2 class="text-3xl font-black">Manage Patients</h2>
                    <p class="mt-3 text-blue-50 leading-6">
                        View, suspend, restore and control patient accounts without accessing private vault files.
                    </p>
                </div>
            </a>

            <a href="{{ route('doctors.index') }}"
               class="admin-card rounded-[34px] p-8 bg-gradient-to-br from-emerald-600 via-green-600 to-lime-500 text-white shadow-2xl">
                <div class="relative z-10">
                    <div class="w-16 h-16 rounded-[24px] bg-white/15 border border-white/10 flex items-center justify-center text-4xl mb-5">
                        👨‍⚕️
                    </div>

                    <h2 class="text-3xl font-black">Manage Doctors</h2>
                    <p class="mt-3 text-emerald-50 leading-6">
                        Approve, reject, suspend and monitor doctor accounts.
                    </p>
                </div>
            </a>

            <a href="{{ route('profile.edit') }}"
               class="admin-card rounded-[34px] p-8 bg-gradient-to-br from-orange-500 via-rose-500 to-red-500 text-white shadow-2xl">
                <div class="relative z-10">
                    <div class="w-16 h-16 rounded-[24px] bg-white/15 border border-white/10 flex items-center justify-center text-4xl mb-5">
                        ⚙️
                    </div>

                    <h2 class="text-3xl font-black">My Profile</h2>
                    <p class="mt-3 text-orange-50 leading-6">
                        Update password, account information and admin profile settings.
                    </p>
                </div>
            </a>

        </div>

        {{-- Footer --}}
        <div class="admin-glass rounded-[34px] p-8 shadow-2xl text-white">
            <h2 class="text-2xl font-black">MedHBook Admin Access</h2>

            <p class="mt-3 text-slate-300 leading-7">
                Admin has limited but powerful access: manage doctors and patients, suspend accounts
                for custom durations, and maintain secure healthcare operations. Appointment records,
                patient document uploads and medical vault access are intentionally excluded from this panel.
            </p>
        </div>

    </div>
</div>
</x-app-layout>