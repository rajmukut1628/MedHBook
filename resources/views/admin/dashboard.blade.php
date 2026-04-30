<x-app-layout>
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-indigo-950 to-slate-950 py-10 px-6">
    <div class="max-w-7xl mx-auto space-y-8">

        <!-- Header -->
        <div class="relative overflow-hidden rounded-[32px] border border-white/10 bg-white/10 backdrop-blur-xl shadow-2xl p-8 text-white">
            <div class="absolute top-0 right-0 w-72 h-72 bg-indigo-500/20 blur-3xl rounded-full"></div>
            <div class="absolute bottom-0 left-0 w-72 h-72 bg-cyan-500/20 blur-3xl rounded-full"></div>

            <div class="relative z-10">
                <span class="inline-flex px-4 py-2 rounded-full bg-indigo-500/20 text-indigo-300 text-sm font-bold mb-4">
                    🛡️ Premium Admin Panel
                </span>

                <h1 class="text-5xl font-black tracking-tight">
                    Admin Dashboard
                </h1>

                <p class="text-slate-300 mt-3 text-lg max-w-2xl">
                    Manage patients, doctors, suspension controls and profile settings with premium control access.
                </p>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="rounded-3xl p-7 bg-white/10 border border-white/10 backdrop-blur-xl shadow-2xl">
                <p class="text-slate-400 font-semibold uppercase tracking-wider">Total Patients</p>
                <h2 class="text-6xl font-black text-cyan-300 mt-3">{{ $patients }}</h2>
            </div>

            <div class="rounded-3xl p-7 bg-white/10 border border-white/10 backdrop-blur-xl shadow-2xl">
                <p class="text-slate-400 font-semibold uppercase tracking-wider">Total Doctors</p>
                <h2 class="text-6xl font-black text-emerald-300 mt-3">{{ $doctors }}</h2>
            </div>

        </div>

        <!-- Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

            <!-- Patients -->
            <a href="{{ route('patients.index') }}"
               class="group relative overflow-hidden rounded-3xl p-8 bg-gradient-to-br from-blue-600 to-cyan-500 text-white shadow-2xl hover:scale-[1.03] transition duration-300">

                <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>

                <div class="relative z-10">
                    <div class="text-6xl mb-4">👥</div>
                    <h2 class="text-3xl font-black">Manage Patients</h2>
                    <p class="mt-3 text-blue-50">
                        Delete, suspend, restore and control patient accounts.
                    </p>
                </div>
            </a>

            <!-- Doctors -->
            <a href="{{ route('doctors.index') }}"
               class="group relative overflow-hidden rounded-3xl p-8 bg-gradient-to-br from-emerald-600 to-green-500 text-white shadow-2xl hover:scale-[1.03] transition duration-300">

                <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>

                <div class="relative z-10">
                    <div class="text-6xl mb-4">👨‍⚕️</div>
                    <h2 class="text-3xl font-black">Manage Doctors</h2>
                    <p class="mt-3 text-emerald-50">
                        Delete, suspend and monitor doctor accounts.
                    </p>
                </div>
            </a>

            <!-- Profile -->
            <a href="{{ route('profile.edit') }}"
               class="group relative overflow-hidden rounded-3xl p-8 bg-gradient-to-br from-orange-500 to-rose-500 text-white shadow-2xl hover:scale-[1.03] transition duration-300">

                <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>

                <div class="relative z-10">
                    <div class="text-6xl mb-4">⚙️</div>
                    <h2 class="text-3xl font-black">My Profile</h2>
                    <p class="mt-3 text-orange-50">
                        Update password, account info and profile settings.
                    </p>
                </div>
            </a>

        </div>

        <!-- Footer -->
        <div class="rounded-3xl border border-white/10 bg-white/10 backdrop-blur-xl p-8 shadow-2xl text-white">
            <h2 class="text-2xl font-black">Premium MedHBook Admin Access</h2>

            <p class="mt-3 text-slate-300 leading-7">
                Admin has limited but powerful access: manage doctors, patients,
                suspend accounts for custom days/months and maintain secure healthcare operations.
            </p>
        </div>

    </div>
</div>
</x-app-layout>