@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Appointment</h2>

    <form action="{{ route('appointments.update', $appointment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Patient</label>
            <select name="patient_id" class="form-control" required>
                @foreach($patients as $patient)
                    <option value="{{ $patient->id }}" {{ $appointment->patient_id == $patient->id ? 'selected' : '' }}>
                        {{ $patient->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Doctor</label>
            <select name="doctor_id" class="form-control" required>
                @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}" {{ $appointment->doctor_id == $doctor->id ? 'selected' : '' }}>
                        {{ $doctor->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Date</label>
            <input type="date" name="date" class="form-control" value="{{ $appointment->date }}" required>
        </div>

        <div class="mb-3">
            <label>Time</label>
            <input type="time" name="time" class="form-control" value="{{ $appointment->time }}" required>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('appointments.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection