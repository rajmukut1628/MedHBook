<x-app-layout>

@php
$user = auth()->user();

$filtered = collect($appointments);

if(request('status')){
    $filtered = $filtered->where('status', request('status'));
}

if(request('search')){
    $search = strtolower(request('search'));

    $filtered = $filtered->filter(function($item) use ($search){
        return str_contains(strtolower($item->patient->name ?? ''), $search)
            || str_contains(strtolower($item->doctor->name ?? ''), $search);
    });
}

$todayAppointments = $filtered->filter(function($item){
    return $item->appointment_date == date('Y-m-d');
});

$approvedCount = $filtered->where('status','Approved')->count();
$estimatedEarnings = 0;

if($user->role === 'doctor'){
    $fee = optional($filtered->first()?->doctor)->consultation_fee ?? 0;
    $estimatedEarnings = $approvedCount * $fee;
}
@endphp

<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-4 sm:px-6 lg:px-8 py-8">
<div class="max-w-7xl mx-auto space-y-8">

    <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-5">

        <div>
            <span class="inline-flex px-4 py-2 rounded-full bg-emerald-500/20 text-emerald-300 text-sm font-bold mb-3">
                📅 Appointment Manager
            </span>

            <h1 class="text-4xl font-black text-white">
                @if($user->role === 'doctor')
                    Doctor Appointments
                @elseif($user->role === 'patient')
                    My Appointments
                @else
                    All Appointments
                @endif
            </h1>

            <p class="text-slate-300 mt-2">
                Manage schedules, bookings and requests.
            </p>
        </div>

        @if($user->role === 'patient')
            <a href="{{ route('appointments.create') }}"
               class="px-6 py-3 rounded-2xl bg-emerald-600 text-white font-bold">
                + Book Appointment
            </a>
        @endif

    </div>

    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Search patient / doctor"
               class="rounded-2xl bg-white/10 border-white/10 text-white">

        <select name="status"
                class="rounded-2xl bg-slate-800 border-white/10 text-white">

            <option value="">All Status</option>
            <option value="Pending" {{ request('status')=='Pending'?'selected':'' }}>Pending</option>
            <option value="Approved" {{ request('status')=='Approved'?'selected':'' }}>Approved</option>
            <option value="Rejected" {{ request('status')=='Rejected'?'selected':'' }}>Rejected</option>
            <option value="Cancelled" {{ request('status')=='Cancelled'?'selected':'' }}>Cancelled</option>
            <option value="Rescheduled" {{ request('status')=='Rescheduled'?'selected':'' }}>Rescheduled</option>
        </select>

        <button class="rounded-2xl bg-blue-600 text-white font-bold px-6 py-3">
            Filter
        </button>

    </form>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-5">

        <div class="p-6 rounded-3xl bg-white/10 border border-white/10">
            <p class="text-slate-400 text-sm font-bold">Total</p>
            <h2 class="text-3xl font-black text-white mt-2">{{ $filtered->count() }}</h2>
        </div>

        <div class="p-6 rounded-3xl bg-white/10 border border-white/10">
            <p class="text-slate-400 text-sm font-bold">Today</p>
            <h2 class="text-3xl font-black text-cyan-300 mt-2">{{ $todayAppointments->count() }}</h2>
        </div>

        <div class="p-6 rounded-3xl bg-white/10 border border-white/10">
            <p class="text-slate-400 text-sm font-bold">Pending</p>
            <h2 class="text-3xl font-black text-yellow-300 mt-2">{{ $filtered->where('status','Pending')->count() }}</h2>
        </div>

        <div class="p-6 rounded-3xl bg-white/10 border border-white/10">
            <p class="text-slate-400 text-sm font-bold">Approved</p>
            <h2 class="text-3xl font-black text-emerald-300 mt-2">{{ $approvedCount }}</h2>
        </div>

        <div class="p-6 rounded-3xl bg-white/10 border border-white/10">
            <p class="text-slate-400 text-sm font-bold">Earnings</p>
            <h2 class="text-2xl font-black text-white mt-2">৳ {{ $estimatedEarnings }}</h2>
        </div>

    </div>

    @if($user->role === 'doctor')
    <div class="p-6 rounded-3xl bg-white/10 border border-white/10">
        <h2 class="text-2xl font-black text-white mb-4">Today Appointments</h2>

        <div class="space-y-3">
            @forelse($todayAppointments as $appointment)
                <div class="p-4 rounded-2xl bg-white/10 text-white flex justify-between">
                    <div>
                        <h3 class="font-black">{{ $appointment->patient->name ?? '-' }}</h3>
                        <p class="text-slate-300 text-sm">
                            {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                        </p>
                    </div>

                    <span class="text-emerald-300 font-bold">{{ $appointment->status }}</span>
                </div>
            @empty
                <p class="text-slate-300">No appointments today.</p>
            @endforelse
        </div>
    </div>
    @endif

    <div class="space-y-5">

        @forelse($filtered as $appointment)

        <div class="p-6 rounded-3xl bg-white/10 border border-white/10 shadow-xl">

            <div class="flex flex-col xl:flex-row xl:justify-between gap-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 flex-1">

                    <div>
                        <p class="text-slate-400 text-xs font-bold">PATIENT</p>
                        <h3 class="text-white text-xl font-black mt-1">
                            {{ $appointment->patient->name ?? '-' }}
                        </h3>

                        <p class="text-slate-300 text-sm">
                            {{ $appointment->patient->email ?? '' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-slate-400 text-xs font-bold">DOCTOR</p>
                        <h3 class="text-white text-xl font-black mt-1">
                            {{ $appointment->doctor->name ?? '-' }}
                        </h3>

                        <p class="text-emerald-300 text-sm">
                            {{ $appointment->doctor->specialist ?? '' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-slate-400 text-xs font-bold">DATE</p>
                        <h3 class="text-white font-bold mt-1">
                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}
                        </h3>
                    </div>

                    <div>
                        <p class="text-slate-400 text-xs font-bold">TIME</p>
                        <h3 class="text-blue-300 font-bold mt-1">
                            {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                        </h3>
                    </div>

                    <div class="md:col-span-2">
                        <p class="text-slate-400 text-xs font-bold">NOTES</p>
                        <p class="text-slate-200 mt-2">
                            {{ $appointment->problem ?: 'No notes added.' }}
                        </p>
                    </div>

                </div>

                <div class="xl:w-72 space-y-4">

                    <span class="inline-flex px-4 py-2 rounded-full text-sm font-black
                    @if($appointment->status === 'Approved') bg-emerald-500/20 text-emerald-300
                    @elseif($appointment->status === 'Pending') bg-yellow-500/20 text-yellow-300
                    @elseif($appointment->status === 'Rescheduled') bg-blue-500/20 text-blue-300
                    @else bg-red-500/20 text-red-300 @endif">
                        {{ $appointment->status }}
                    </span>

                    @if($user->role === 'doctor')

                        @if($appointment->status === 'Pending')
                        <div class="grid grid-cols-2 gap-3">

                            <form method="POST" action="{{ route('appointments.approve',$appointment->id) }}">
                                @csrf
                                @method('PATCH')
                                <button class="w-full px-4 py-3 rounded-2xl bg-emerald-600 text-white font-bold">
                                    Approve
                                </button>
                            </form>

                            <form method="POST" action="{{ route('appointments.reject',$appointment->id) }}">
                                @csrf
                                @method('PATCH')
                                <button class="w-full px-4 py-3 rounded-2xl bg-red-600 text-white font-bold">
                                    Reject
                                </button>
                            </form>

                        </div>
                        @endif

                    @elseif($user->role === 'patient')

                        @if(in_array($appointment->status,['Pending','Approved']))
                        <form method="POST" action="{{ route('appointments.cancel',$appointment->id) }}">
                            @csrf
                            @method('PATCH')
                            <button class="w-full px-4 py-3 rounded-2xl bg-red-600 text-white font-bold">
                                Cancel
                            </button>
                        </form>
                        @endif

                    @endif

                </div>

            </div>

        </div>

        @empty

        <div class="p-16 rounded-3xl bg-white/10 text-center">
            <h2 class="text-2xl font-black text-white">No appointments found</h2>
        </div>

        @endforelse

    </div>

</div>
</div>

</x-app-layout>