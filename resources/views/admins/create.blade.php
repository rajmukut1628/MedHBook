@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Admin</h2>

    <form action="{{ route('admins.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button class="btn btn-success">Create Admin</button>
        <a href="{{ route('admins.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection