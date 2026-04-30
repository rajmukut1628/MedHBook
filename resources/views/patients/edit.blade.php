@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Patient</h2>

    <form action="{{ route('patients.update', $patient->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ $patient->name }}" required>
        </div>

        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ $patient->phone }}" required>
        </div>

        <div class="mb-3">
            <label>Age</label>
            <input type="number" name="age" class="form-control" value="{{ $patient->age }}" required>
        </div>

        <div class="mb-3">
            <label>Address</label>
            <textarea name="address" class="form-control">{{ $patient->address }}</textarea>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('patients.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection