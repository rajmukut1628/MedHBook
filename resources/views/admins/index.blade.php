@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Admin List</h2>

    <a href="{{ route('admins.create') }}" class="btn btn-primary mb-3">
        Add Admin
    </a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Action</th>
        </tr>

        @foreach($admins as $admin)
        <tr>
            <td>{{ $admin->id }}</td>
            <td>{{ $admin->name }}</td>
            <td>{{ $admin->email }}</td>
            <td>
                <form action="{{ route('admins.destroy', $admin->id) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button onclick="return confirm('Delete admin?')" class="btn btn-danger btn-sm">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
        @endforeach

    </table>
</div>
@endsection