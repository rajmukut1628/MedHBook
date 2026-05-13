@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-100 via-white to-emerald-50 py-10">
    <div class="max-w-5xl mx-auto px-6">

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-4xl font-extrabold text-slate-800">
                Patient Profile
            </h1>
            <p class="text-slate-500 mt-2">
                View complete patient information (Read Only)
            </p>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-6 bg-emerald-100 border border-emerald-300 text-emerald-700 px-5 py-4 rounded-2xl shadow">
                {{ session('success') }}
            </div>
        @endif

        {{-- Patient Profile Card --}}
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden">

            {{-- Top Banner --}}
            <div class="bg-gradient-to-r from-emerald-500 to-green-600 p-10 text-center">
                <div class="w-28 h-28 mx-auto rounded-full bg-white flex items-center justify-center
                            text-5xl font-extrabold text-emerald-600 shadow-xl">
                    {{ strtoupper(substr($patient->name, 0, 1)) }}
                </div>

                <h2 class="text-3xl font-extrabold text-white mt-5">
                    {{ $patient->name }}
                </h2>

                <p class="text-emerald-100 mt-2 text-lg">
                    Patient ID: #{{ $patient->id }}
                </p>
            </div>

            {{-- Information --}}
            <div class="p-8">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div class="bg-slate-50 rounded-2xl p-5">
                        <p class="text-sm text-slate-400">Email</p>
                        <p class="font-bold text-slate-800 mt-1">
                            {{ $patient->email ?? 'N/A' }}
                        </p>
                    </div>

                    <div class="bg-slate-50 rounded-2xl p-5">
                        <p class="text-sm text-slate-400">Phone</p>
                        <p class="font-bold text-slate-800 mt-1">
                            {{ $patient->phone ?? 'N/A' }}
                        </p>
                    </div>

                    <div class="bg-slate-50 rounded-2xl p-5">
                        <p class="text-sm text-slate-400">Age</p>
                        <p class="font-bold text-slate-800 mt-1">
                            {{ $patient->age ?? 'N/A' }}
                        </p>
                    </div>

                    <div class="bg-slate-50 rounded-2xl p-5">
                        <p class="text-sm text-slate-400">Gender</p>
                        <p class="font-bold text-slate-800 mt-1">
                            {{ $patient->gender ?? 'N/A' }}
                        </p>
                    </div>

                    <div class="bg-slate-50 rounded-2xl p-5">
                        <p class="text-sm text-slate-400">Blood Group</p>
                        <p class="font-bold text-slate-800 mt-1">
                            {{ $patient->blood_group ?? 'N/A' }}
                        </p>
                    </div>

                    <div class="bg-slate-50 rounded-2xl p-5">
                        <p class="text-sm text-slate-400">Emergency Contact</p>
                        <p class="font-bold text-slate-800 mt-1">
                            {{ $patient->emergency_contact ?? 'N/A' }}
                        </p>
                    </div>

                </div>

                {{-- Address --}}
                <div class="mt-5 bg-slate-50 rounded-2xl p-5">
                    <p class="text-sm text-slate-400">Address</p>
                    <p class="font-bold text-slate-800 mt-1">
                        {{ $patient->address ?? 'N/A' }}
                    </p>
                </div>

                {{-- Health Conditions --}}
                <div class="mt-5 bg-slate-50 rounded-2xl p-5">
                    <p class="text-sm text-slate-400">Health Conditions</p>

                    <div class="mt-3 flex flex-wrap gap-2">
                        @php
                            $conditions = [];

                            if($patient->allergy) $conditions[] = 'Allergy';
                            if($patient->diabetes) $conditions[] = 'Diabetes';
                            if($patient->blood_pressure) $conditions[] = 'Blood Pressure';
                        @endphp

                        @forelse($conditions as $condition)
                            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-sm font-bold">
                                {{ $condition }}
                            </span>
                        @empty
                            <span class="text-slate-500 font-medium">
                                No health conditions listed.
                            </span>
                        @endforelse
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-8 flex flex-wrap gap-3">

                    <a href="{{ route('patients.index') }}"
                       class="px-6 py-3 rounded-2xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition">
                        Back to Patients
                    </a>

                    <form action="{{ route('patients.destroy', $patient->id) }}"
                          method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this patient?')">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="px-6 py-3 rounded-2xl bg-red-600 text-white font-bold hover:bg-red-700 transition">
                            Delete Patient
                        </button>
                    </form>

                </div>

            </div>
        </div>

    </div>
</div>
@endsection