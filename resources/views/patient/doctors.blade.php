<x-app-layout>
<div class="p-6">

    <h1 class="text-3xl font-bold mb-6">Available Doctors</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        @forelse($doctors as $doctor)

        <div class="bg-white shadow rounded-xl p-6">
            <h2 class="text-xl font-bold">{{ $doctor->name }}</h2>

            <p class="text-gray-500 mt-2">
                {{ $doctor->specialization }}
            </p>

            <p class="text-sm text-gray-400 mt-1">
                {{ $doctor->email }}
            </p>

            <a href="/appointments/create"
               class="inline-block mt-5 bg-emerald-600 text-white px-4 py-2 rounded-lg">
                Book Appointment
            </a>
        </div>

        @empty

        <div class="col-span-3 text-center text-gray-500">
            No doctors available
        </div>

        @endforelse

    </div>

</div>
</x-app-layout>