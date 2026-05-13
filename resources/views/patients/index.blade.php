@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-100 via-white to-emerald-50 py-10">
    <div class="max-w-7xl mx-auto px-6">

        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-4xl font-extrabold text-slate-800">
                    Patients
                </h1>
                <p class="text-slate-500 mt-2">
                    Premium patient management panel
                </p>
            </div>

            <a href="{{ route('patients.create') }}"
               class="px-6 py-3 rounded-2xl bg-gradient-to-r from-emerald-500 to-green-600 text-white font-bold shadow-lg hover:scale-105 duration-200">
                + Add Patient
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-emerald-100 border border-emerald-300 text-emerald-700 px-5 py-4 rounded-2xl shadow">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-300 text-red-700 px-5 py-4 rounded-2xl shadow">
                {{ session('error') }}
            </div>
        @endif

        <!-- Table -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl overflow-hidden border border-white">

            <table class="w-full">
                <thead class="bg-slate-900 text-white">
                    <tr>
                        <th class="px-6 py-5 text-left">Patient</th>
                        <th class="px-6 py-5 text-left">Phone</th>
                        <th class="px-6 py-5 text-left">Age</th>
                        <th class="px-6 py-5 text-left">Status</th>
                        <th class="px-6 py-5 text-left">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($patients as $patient)
                    <tr class="border-b hover:bg-slate-50 transition duration-200">

                        <!-- Patient -->
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-r from-sky-500 to-blue-600 text-white flex items-center justify-center font-bold text-lg">
                                    {{ strtoupper(substr($patient->name,0,1)) }}
                                </div>

                                <div>
                                    <div class="font-bold text-slate-800">
                                        {{ $patient->name }}
                                    </div>
                                    <div class="text-sm text-slate-400">
                                        ID #{{ $patient->id }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Phone -->
                        <td class="px-6 py-5 text-slate-700 font-medium">
                            {{ $patient->phone }}
                        </td>

                        <!-- Age -->
                        <td class="px-6 py-5">
                            <span class="px-4 py-2 rounded-full bg-purple-100 text-purple-700 text-sm font-bold">
                                {{ $patient->age }} Years
                            </span>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-5">
                            @if($patient->user && $patient->user->status === 'suspended')
                                <span class="px-4 py-2 rounded-full bg-red-100 text-red-700 text-sm font-bold">
                                    Suspended
                                </span>
                            @else
                                <span class="px-4 py-2 rounded-full bg-emerald-100 text-emerald-700 text-sm font-bold">
                                    Active
                                </span>
                            @endif
                        </td>

                        <!-- Action -->
                        <td class="px-6 py-5">
                            <div class="flex flex-wrap gap-2">

                                <a href="{{ route('patients.show',$patient->id) }}"
                                   class="px-4 py-2 rounded-xl bg-blue-500 text-white text-sm font-bold hover:bg-blue-600">
                                    View
                                </a>
                                <!-- Suspend -->
                                @if(auth()->user()->role === 'admin' && $patient->user)

                                @if($patient->user->status !== 'suspended')

                                <form action="{{ route('admin.users.suspend',$patient->user->id) }}" method="POST" class="flex gap-2">
                                    @csrf
                                    @method('PATCH')

                                    <select name="duration_type" class="rounded-xl border-slate-300 text-sm">
                                        <option value="days">Days</option>
                                        <option value="months">Months</option>
                                    </select>

                                    <input type="number"
                                           name="duration"
                                           min="1"
                                           value="7"
                                           class="w-20 rounded-xl border-slate-300 text-sm">

                                    <button class="px-4 py-2 rounded-xl bg-rose-500 text-white text-sm font-bold hover:bg-rose-600">
                                        Suspend
                                    </button>
                                </form>

                                @else

                                <form action="{{ route('admin.users.unsuspend',$patient->user->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <button class="px-4 py-2 rounded-xl bg-green-600 text-white text-sm font-bold hover:bg-green-700">
                                        Unsuspend
                                    </button>
                                </form>

                                @endif
                                @endif

                                <!-- Delete -->
                                <form action="{{ route('patients.destroy',$patient->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button onclick="return confirm('Delete patient?')"
                                            class="px-4 py-2 rounded-xl bg-red-500 text-white text-sm font-bold hover:bg-red-600">
                                        Delete
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-slate-400">
                            No patient found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection