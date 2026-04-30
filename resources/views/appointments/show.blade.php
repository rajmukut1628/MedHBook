@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Appointment Details</h2>

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <td>{{ $appointment->id }}</td>
        </tr>
        <tr>
            <th>Patient</th>
            <td>{{ $appointment->patient->name }}</td>
        </tr>
        <tr>
            <th>Doctor</th>
            <td>{{ $appointment->doctor->name }}</td>
        </tr>
        <tr>
            <th>Date</th>
            <td>{{ $appointment->date }}</td>
        </tr>
        <tr>
            <th>Time</th>
            <td>{{ $appointment->time }}</td>
        </tr>
    </table>

    <a href="{{ route('appointments.index') }}" class="btn btn-secondary">Back</a>
    <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-warning">Edit</a>
</div>
@endsection