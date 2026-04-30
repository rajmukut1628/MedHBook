<x-app-layout>
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-black to-indigo-950 py-10 px-6">
    <div class="max-w-7xl mx-auto space-y-8">

        <div class="relative overflow-hidden rounded-[32px] bg-gradient-to-r from-indigo-700 via-purple-700 to-pink-600 p-9 text-white shadow-2xl">
            <div class="absolute right-0 top-0 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>

            <div class="relative z-10">
                <span class="inline-flex px-4 py-2 rounded-full bg-white/15 text-white text-sm font-bold mb-4">
                    👑 Super Admin Control
                </span>

                <h1 class="text-4xl md:text-5xl font-black">Super Admin Dashboard</h1>

                <p class="mt-3 text-indigo-100 text-lg">
                    Welcome {{ auth()->user()->name }} | Full system control
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

            <div class="rounded-3xl bg-white/10 border border-white/10 p-7 text-white shadow-xl">
                <p class="text-slate-300 font-bold">Total Users</p>
                <h2 class="text-5xl font-black mt-3">{{ $users ?? 0 }}</h2>
            </div>

            <div class="rounded-3xl bg-white/10 border border-white/10 p-7 text-white shadow-xl">
                <p class="text-slate-300 font-bold">Total Admins</p>
                <h2 class="text-5xl font-black mt-3">{{ $admins ?? 0 }}</h2>
            </div>

            <div class="rounded-3xl bg-white/10 border border-white/10 p-7 text-white shadow-xl">
                <p class="text-slate-300 font-bold">Patients</p>
                <h2 class="text-5xl font-black mt-3">{{ $patients ?? 0 }}</h2>
            </div>

            <div class="rounded-3xl bg-white/10 border border-white/10 p-7 text-white shadow-xl">
                <p class="text-slate-300 font-bold">Doctors</p>
                <h2 class="text-5xl font-black mt-3">{{ $doctors ?? 0 }}</h2>
            </div>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

            <a href="{{ route('superadmin.admins') }}"
               class="rounded-3xl p-8 bg-gradient-to-br from-purple-600 to-pink-600 text-white shadow-2xl hover:scale-[1.03] transition">
                <div class="text-6xl mb-4">🛡️</div>
                <h2 class="text-3xl font-black">Manage Admins</h2>
                <p class="mt-3 text-purple-50">Create, edit and delete general admins.</p>
            </a>

            <a href="{{ route('patients.index') }}"
               class="rounded-3xl p-8 bg-gradient-to-br from-blue-600 to-cyan-500 text-white shadow-2xl hover:scale-[1.03] transition">
                <div class="text-6xl mb-4">👥</div>
                <h2 class="text-3xl font-black">Manage Patients</h2>
                <p class="mt-3 text-blue-50">View, create and manage patient accounts.</p>
            </a>

            <a href="{{ route('doctors.index') }}"
               class="rounded-3xl p-8 bg-gradient-to-br from-emerald-600 to-green-500 text-white shadow-2xl hover:scale-[1.03] transition">
                <div class="text-6xl mb-4">👨‍⚕️</div>
                <h2 class="text-3xl font-black">Manage Doctors</h2>
                <p class="mt-3 text-emerald-50">Approve, create and manage doctors.</p>
            </a>

            <a href="{{ route('appointments.index') }}"
               class="rounded-3xl p-8 bg-gradient-to-br from-indigo-600 to-blue-600 text-white shadow-2xl hover:scale-[1.03] transition">
                <div class="text-6xl mb-4">📅</div>
                <h2 class="text-3xl font-black">Appointments</h2>
                <p class="mt-3 text-indigo-50">Monitor all booking records.</p>
            </a>

            <a href="{{ route('medical-documents.index') }}"
               class="rounded-3xl p-8 bg-gradient-to-br from-teal-600 to-emerald-500 text-white shadow-2xl hover:scale-[1.03] transition">
                <div class="text-6xl mb-4">📄</div>
                <h2 class="text-3xl font-black">Medical Documents</h2>
                <p class="mt-3 text-teal-50">Review uploaded medical reports.</p>
            </a>

            <a href="{{ route('profile.edit') }}"
               class="rounded-3xl p-8 bg-gradient-to-br from-orange-500 to-rose-500 text-white shadow-2xl hover:scale-[1.03] transition">
                <div class="text-6xl mb-4">👤</div>
                <h2 class="text-3xl font-black">My Profile</h2>
                <p class="mt-3 text-orange-50">Update profile and password.</p>
            </a>

        </div>

    </div>
</div>
</x-app-layout>