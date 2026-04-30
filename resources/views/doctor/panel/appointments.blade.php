<x-app-layout>
<div class="p-6">

    <h1 class="text-3xl font-bold mb-6">Appointment Management</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-xl p-6 overflow-x-auto">

        <table class="w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-3">Patient</th>
                    <th class="border p-3">Doctor</th>
                    <th class="border p-3">Date</th>
                    <th class="border p-3">Time</th>
                    <th class="border p-3">Status</th>
                    <th class="border p-3">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse(\App\Models\Appointment::with(['patient','doctor'])->latest()->get() as $appointment)

                <tr>
                    <td class="border p-3">{{ $appointment->patient->name ?? '-' }}</td>
                    <td class="border p-3">{{ $appointment->doctor->name ?? '-' }}</td>
                    <td class="border p-3">{{ $appointment->appointment_date }}</td>
                    <td class="border p-3">{{ $appointment->appointment_time }}</td>
                    <td class="border p-3">{{ $appointment->status }}</td>

                    <td class="border p-3 space-y-2 min-w-[220px]">

                        <form method="POST" action="{{ route('appointments.approve',$appointment) }}">
                            @csrf
                            @method('PATCH')
                            <button class="bg-green-600 text-white px-3 py-1 rounded w-full">
                                Approve
                            </button>
                        </form>

                        <form method="POST" action="{{ route('appointments.reject',$appointment) }}">
                            @csrf
                            @method('PATCH')
                            <button class="bg-red-600 text-white px-3 py-1 rounded w-full">
                                Reject
                            </button>
                        </form>

                        <form method="POST" action="{{ route('appointments.cancel',$appointment) }}">
                            @csrf
                            @method('PATCH')
                            <button class="bg-yellow-500 text-white px-3 py-1 rounded w-full">
                                Cancel
                            </button>
                        </form>

                        <form method="POST" action="{{ route('appointments.reschedule',$appointment) }}" class="space-y-1">
                            @csrf
                            @method('PATCH')

                            <input type="date"
                                   name="appointment_date"
                                   class="border rounded p-1 w-full"
                                   required>

                            <input type="time"
                                   name="appointment_time"
                                   class="border rounded p-1 w-full"
                                   required>

                            <button class="bg-purple-600 text-white px-3 py-1 rounded w-full">
                                Reschedule
                            </button>
                        </form>

                        <a href="https://meet.google.com"
                           target="_blank"
                           class="bg-blue-600 text-white px-3 py-1 rounded block text-center">
                            Join Call
                        </a>

                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="6" class="text-center p-4">
                        No appointments found
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>

    </div>

</div>
</x-app-layout>