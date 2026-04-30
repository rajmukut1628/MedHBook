<x-app-layout>
<div class="p-6 max-w-5xl mx-auto">

    <h1 class="text-3xl font-bold mb-6">Smart Schedule System</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST"
          action="{{ route('doctor.schedule.store') }}"
          class="bg-white shadow rounded-xl p-6 space-y-4 mb-8">

        @csrf

        <div>
            <label class="font-semibold">Schedule Date</label>
            <input type="date" name="schedule_date"
                   class="w-full border rounded-lg p-2 mt-1">
        </div>

        <div>
            <label class="font-semibold">Day of Week</label>
            <select name="day_of_week"
                    class="w-full border rounded-lg p-2 mt-1">
                <option value="">Select Day</option>
                <option>Saturday</option>
                <option>Sunday</option>
                <option>Monday</option>
                <option>Tuesday</option>
                <option>Wednesday</option>
                <option>Thursday</option>
                <option>Friday</option>
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="font-semibold">Start Time</label>
                <input type="time" name="start_time"
                       class="w-full border rounded-lg p-2 mt-1" required>
            </div>

            <div>
                <label class="font-semibold">End Time</label>
                <input type="time" name="end_time"
                       class="w-full border rounded-lg p-2 mt-1" required>
            </div>
        </div>

        <div class="space-y-2">
            <label class="block">
                <input type="checkbox" name="is_recurring" value="1">
                Recurring Schedule
            </label>

            <label class="block">
                <input type="checkbox" name="is_emergency" value="1">
                Emergency Slot
            </label>
        </div>

        <button class="bg-green-600 text-white px-6 py-3 rounded-lg">
            Create Slot
        </button>
    </form>

    <div class="bg-white shadow rounded-xl p-6">
        <h2 class="text-xl font-bold mb-4">Availability Calendar</h2>

        <table class="w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2">Date</th>
                    <th class="border p-2">Day</th>
                    <th class="border p-2">Start</th>
                    <th class="border p-2">End</th>
                    <th class="border p-2">Type</th>
                </tr>
            </thead>

            <tbody>
                @forelse($schedules as $slot)
                    <tr>
                        <td class="border p-2">{{ $slot->schedule_date }}</td>
                        <td class="border p-2">{{ $slot->day_of_week }}</td>
                        <td class="border p-2">{{ $slot->start_time }}</td>
                        <td class="border p-2">{{ $slot->end_time }}</td>
                        <td class="border p-2">
                            @if($slot->is_emergency)
                                Emergency
                            @elseif($slot->is_recurring)
                                Recurring
                            @else
                                Normal
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center p-4">
                            No schedules found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
</x-app-layout>