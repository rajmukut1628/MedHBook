<x-app-layout>

@php
    $days = [];

    if (isset($selectedDoctor) && $selectedDoctor) {
        $days = is_array($selectedDoctor->working_days)
            ? $selectedDoctor->working_days
            : [];
    }

    $startTime = $selectedDoctor->start_time ?? '';
    $endTime = $selectedDoctor->end_time ?? '';
@endphp

<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">

        <div class="mb-8">
            <span class="inline-flex px-4 py-2 rounded-full bg-emerald-500/20 text-emerald-300 text-sm font-bold mb-3">
                📅 New Appointment
            </span>

            <h1 class="text-4xl font-black text-white">Book Appointment</h1>

            <p class="text-slate-300 mt-2">
                Select doctor, date, and available 5-minute time slot.
            </p>
        </div>

        @if(session('success'))
            <div class="mb-5 p-4 rounded-2xl bg-emerald-500/20 text-emerald-200 border border-emerald-400/30 font-bold">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-5 p-4 rounded-2xl bg-red-500/20 text-red-200 border border-red-400/30 font-bold">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-5 p-4 rounded-2xl bg-red-500/20 text-red-200 border border-red-400/30">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/10 overflow-hidden">

            <div class="px-6 py-5 border-b border-white/10">
                <h2 class="text-xl font-black text-white">
                    Appointment Information
                </h2>
            </div>

            <form action="{{ route('appointments.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-white mb-2">
                        Doctor
                    </label>

                    <select name="doctor_id"
                            id="doctor_id"
                            required
                            onchange="reloadSlots()"
                            class="w-full rounded-2xl bg-slate-800 border-white/20 text-white px-4 py-3">

                        <option value="">Select Doctor</option>

                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}"
                                {{ old('doctor_id', $selectedDoctor->id ?? request('doctor_id')) == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->name }} - {{ $doctor->specialist ?? $doctor->specialization }}
                            </option>
                        @endforeach

                    </select>
                </div>

                @if(isset($selectedDoctor) && $selectedDoctor)
                    <div class="rounded-2xl bg-emerald-500/10 border border-emerald-400/20 p-5">
                        <h3 class="text-lg font-bold text-white mb-4">
                            Doctor Schedule
                        </h3>

                        <div class="space-y-2 text-slate-200 text-sm">
                            <p><strong>Name:</strong> {{ $selectedDoctor->name }}</p>
                            <p><strong>Specialist:</strong> {{ $selectedDoctor->specialist ?? $selectedDoctor->specialization }}</p>

                            <p>
                                <strong>Chamber:</strong><br>
                                {!! nl2br(e($selectedDoctor->chamber_addresses ?? $selectedDoctor->chamber_address ?? 'Not Added')) !!}
                            </p>

                            <p>
                                <strong>Working Days:</strong>
                                {{ count($days) ? implode(', ', $days) : 'Not Added' }}
                            </p>

                            <p>
                                <strong>Available Time:</strong>
                                {{ $startTime ?: 'Not Set' }} - {{ $endTime ?: 'Not Set' }}
                            </p>

                            <p>
                                <strong>Fee:</strong>
                                ৳ {{ $selectedDoctor->consultation_fee ?? 0 }}
                            </p>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    <div>
                        <label class="block text-sm font-bold text-white mb-2">
                            Appointment Date
                        </label>

                        <input type="date"
                               id="appointment_date"
                               name="appointment_date"
                               min="{{ date('Y-m-d') }}"
                               value="{{ old('appointment_date', request('appointment_date')) }}"
                               onchange="reloadSlots()"
                               required
                               class="w-full rounded-2xl bg-white/10 border-white/20 text-white px-4 py-3">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-white mb-2">
                            Select 5-Minute Slot
                        </label>

                        <select name="appointment_time"
                                id="appointment_time"
                                required
                                class="w-full rounded-2xl bg-slate-800 border-white/20 text-white px-4 py-3">

                            <option value="">Select Slot</option>

                            @if(isset($availableSlots) && count($availableSlots))
                                @foreach($availableSlots as $slot)
                                    @php
                                        $isBooked = in_array($slot, $bookedSlots ?? []);
                                    @endphp

                                    <option value="{{ $slot }}"
                                            {{ old('appointment_time') == $slot ? 'selected' : '' }}
                                            {{ $isBooked ? 'disabled' : '' }}>
                                        {{ $slot }} {{ $isBooked ? '(Booked)' : '' }}
                                    </option>
                                @endforeach
                            @else
                                <option value="" disabled>
                                    Select doctor and date first
                                </option>
                            @endif
                        </select>
                    </div>

                </div>

                <div id="dayWarning" class="hidden rounded-2xl bg-red-500/20 border border-red-400 text-red-200 p-4 font-bold">
                    Doctor is unavailable on selected day. Please choose another day.
                </div>

                <div>
                    <label class="block text-sm font-bold text-white mb-2">
                        Problem / Notes
                    </label>

                    <textarea name="problem"
                              rows="4"
                              class="w-full rounded-2xl bg-white/10 border-white/20 text-white px-4 py-3"
                              placeholder="Write symptoms...">{{ old('problem') }}</textarea>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-4">

                    <button type="submit"
                            id="submitBtn"
                            class="inline-flex justify-center px-6 py-3 rounded-2xl bg-emerald-600 text-white font-black hover:bg-emerald-700 transition">
                        Confirm Appointment
                    </button>

                    <a href="{{ route('find.doctors') }}"
                       class="inline-flex justify-center px-6 py-3 rounded-2xl bg-white/10 text-white font-black hover:bg-white/20 transition">
                        Back
                    </a>

                </div>

            </form>

        </div>

    </div>
</div>

<script>
    const allowedDays = @json($days);
    const dateInput = document.getElementById('appointment_date');
    const dayWarning = document.getElementById('dayWarning');
    const submitBtn = document.getElementById('submitBtn');

    function reloadSlots() {
        const doctorId = document.getElementById('doctor_id').value;
        const date = document.getElementById('appointment_date').value;

        if (doctorId && date) {
            window.location.href = "{{ route('appointments.create') }}" + "?doctor_id=" + doctorId + "&appointment_date=" + date;
        } else if (doctorId) {
            window.location.href = "{{ route('appointments.create') }}" + "?doctor_id=" + doctorId;
        }
    }

    dateInput?.addEventListener('change', function () {
        if (!this.value) return;

        const selected = new Date(this.value);
        const dayName = selected.toLocaleDateString('en-US', { weekday: 'long' });

        if (allowedDays.length > 0 && !allowedDays.includes(dayName)) {
            dayWarning.classList.remove('hidden');
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            dayWarning.classList.add('hidden');
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    });
</script>

</x-app-layout>