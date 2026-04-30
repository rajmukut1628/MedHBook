@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-100 via-white to-emerald-50 py-10">

    <div class="max-w-7xl mx-auto px-6">

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-4xl font-extrabold text-slate-800">
                Patient Profile
            </h1>
            <p class="text-slate-500 mt-2">
                Manage patient information and quickly find the right doctor
            </p>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-6 bg-emerald-100 border border-emerald-300 text-emerald-700 px-5 py-4 rounded-2xl shadow">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Patient Profile Card --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">

                    <div class="bg-gradient-to-r from-emerald-500 to-green-600 p-8 text-center">
                        <div class="w-24 h-24 mx-auto rounded-full bg-white flex items-center justify-center text-4xl font-extrabold text-emerald-600 shadow-lg">
                            {{ strtoupper(substr($patient->name, 0, 1)) }}
                        </div>

                        <h2 class="text-2xl font-bold text-white mt-4">
                            {{ $patient->name }}
                        </h2>

                        <p class="text-emerald-100">
                            Patient ID: #{{ $patient->id }}
                        </p>
                    </div>

                    <div class="p-6 space-y-4">

                        <div class="bg-slate-50 rounded-2xl p-4">
                            <p class="text-sm text-slate-400">Email</p>
                            <p class="font-semibold text-slate-700">
                                {{ $patient->email ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="bg-slate-50 rounded-2xl p-4">
                            <p class="text-sm text-slate-400">Phone</p>
                            <p class="font-semibold text-slate-700">
                                {{ $patient->phone ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="bg-slate-50 rounded-2xl p-4">
                            <p class="text-sm text-slate-400">Age</p>
                            <p class="font-semibold text-slate-700">
                                {{ $patient->age ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="bg-slate-50 rounded-2xl p-4">
                            <p class="text-sm text-slate-400">Gender</p>
                            <p class="font-semibold text-slate-700">
                                {{ $patient->gender ?? 'N/A' }}
                            </p>
                        </div>

                        <a href="{{ route('patients.edit', $patient->id) }}"
                           class="block text-center w-full py-3 rounded-2xl bg-slate-900 text-white font-bold hover:bg-emerald-600 transition">
                            Edit Profile
                        </a>
                    </div>
                </div>
            </div>

            {{-- Find Doctor Feature --}}
            <div class="lg:col-span-2">

                <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8 mb-8">

                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-3xl font-extrabold text-slate-800">
                                Find Doctor
                            </h2>
                            <p class="text-slate-500 mt-1">
                                Search doctor by name, specialty or symptoms
                            </p>
                        </div>

                        <div class="hidden md:block bg-emerald-100 text-emerald-700 px-5 py-3 rounded-2xl font-bold">
                            Premium Search
                        </div>
                    </div>

                    <form action="{{ route('patients.show', $patient->id) }}" method="GET">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                            <div class="md:col-span-3">
                                <input type="text"
                                       name="search"
                                       value="{{ request('search') }}"
                                       placeholder="Search: Cardiology, Neuro, heart, মাথা, হৃদরোগ..."
                                       class="w-full px-5 py-4 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 outline-none shadow-sm">
                            </div>

                            <button type="submit"
                                    class="px-6 py-4 rounded-2xl bg-gradient-to-r from-emerald-500 to-green-600 text-white font-extrabold shadow-lg hover:scale-105 transition">
                                Find Doctor
                            </button>

                        </div>
                    </form>
                </div>

                {{-- Doctor Results --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    @forelse($doctors as $doctor)
                        <div class="bg-white rounded-3xl shadow-lg border border-slate-100 p-6 hover:shadow-2xl hover:-translate-y-1 transition">

                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-r from-emerald-500 to-green-600 flex items-center justify-center text-white text-2xl font-extrabold shadow">
                                    {{ strtoupper(substr($doctor->name, 0, 1)) }}
                                </div>

                                <div>
                                    <h3 class="text-xl font-extrabold text-slate-800">
                                        Dr. {{ $doctor->name }}
                                    </h3>
                                    <p class="text-emerald-600 font-semibold">
                                        {{ $doctor->specialization }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-5 space-y-3 text-sm">
                                <div class="flex justify-between bg-slate-50 rounded-xl px-4 py-3">
                                    <span class="text-slate-500">Email</span>
                                    <span class="font-semibold text-slate-700">
                                        {{ $doctor->email ?? 'N/A' }}
                                    </span>
                                </div>

                                <div class="flex justify-between bg-slate-50 rounded-xl px-4 py-3">
                                    <span class="text-slate-500">Phone</span>
                                    <span class="font-semibold text-slate-700">
                                        {{ $doctor->phone ?? 'N/A' }}
                                    </span>
                                </div>

                                <div class="flex justify-between bg-slate-50 rounded-xl px-4 py-3">
                                    <span class="text-slate-500">Fee</span>
                                    <span class="font-semibold text-slate-700">
                                        ৳{{ $doctor->fee ?? 'N/A' }}
                                    </span>
                                </div>
                            </div>

                            <a href="{{ route('appointments.create', ['doctor_id' => $doctor->id]) }}"
                               class="block text-center mt-6 py-3 rounded-2xl bg-slate-900 text-white font-bold hover:bg-emerald-600 transition">
                                Book Appointment
                            </a>
                        </div>
                    @empty
                        <div class="md:col-span-2 bg-white rounded-3xl shadow p-10 text-center">
                            <h3 class="text-2xl font-bold text-slate-700">
                                No Doctor Found
                            </h3>
                            <p class="text-slate-500 mt-2">
                                Try searching with another specialty or symptom.
                            </p>
                        </div>
                    @endforelse

                </div>

            </div>

        </div>
    </div>
</div>
@endsection