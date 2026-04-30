@extends('layouts.app')

@section('content')
<style>
.wrap{max-width:1300px;margin:auto;padding:30px}
.hero{background:linear-gradient(135deg,#0f172a,#047857);color:white;padding:32px;border-radius:30px;margin-bottom:24px;box-shadow:0 25px 60px rgba(0,0,0,.18)}
.hero h1{font-size:38px;font-weight:900;margin:0}
.hero p{color:#d1fae5;margin-top:8px}
.topbar{display:flex;gap:14px;justify-content:space-between;margin-bottom:22px;flex-wrap:wrap}
.search{flex:1;min-width:260px;padding:15px 18px;border-radius:18px;border:1px solid #cbd5e1}
.btn{border:0;text-decoration:none;padding:14px 20px;border-radius:18px;font-weight:900;cursor:pointer;display:inline-block}
.btn-green{background:linear-gradient(135deg,#10b981,#22c55e);color:white}
.btn-blue{background:#2563eb;color:white}
.btn-yellow{background:#f59e0b;color:white}
.btn-red{background:#dc2626;color:white}
.btn-dark{background:#0f172a;color:white}
.alert{background:#ecfdf5;color:#047857;border:1px solid #86efac;padding:16px;border-radius:18px;margin-bottom:20px;font-weight:800}
.grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}
.card{background:white;border-radius:28px;padding:24px;box-shadow:0 20px 55px rgba(15,23,42,.08);border:1px solid #eef2f7}
.avatar{width:64px;height:64px;border-radius:22px;background:linear-gradient(135deg,#10b981,#22c55e);display:flex;align-items:center;justify-content:center;color:white;font-size:28px;font-weight:900;margin-bottom:16px}
.card h3{font-size:22px;font-weight:900;color:#0f172a;margin:0}
.spec{color:#047857;font-weight:800;margin-top:6px}
.info{margin-top:15px;background:#f8fafc;border-radius:18px;padding:14px;color:#475569;line-height:1.8}
.actions{display:flex;gap:8px;flex-wrap:wrap;margin-top:18px}
.status{display:inline-block;margin-top:12px;padding:7px 12px;border-radius:999px;font-weight:900;font-size:12px}
.approved{background:#dcfce7;color:#047857}
.pending{background:#fef9c3;color:#a16207}
.rejected{background:#fee2e2;color:#b91c1c}
.suspended{background:#fee2e2;color:#b91c1c}
.active{background:#dcfce7;color:#047857}
.inline-input{padding:10px;border-radius:14px;border:1px solid #cbd5e1}
</style>

<div class="wrap">

    <div class="hero">
        <h1>Doctors Directory</h1>
        <p>Manage doctors, verification and account control</p>
    </div>

    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('doctors.index') }}" class="topbar">
        <input class="search" type="text" name="search"
               value="{{ request('search') }}"
               placeholder="Search doctor...">

        <button class="btn btn-green">Search</button>
        <a href="{{ route('doctors.create') }}" class="btn btn-blue">+ Add Doctor</a>
    </form>

    <div class="grid">

        @forelse($doctors as $doctor)

        @php
            $verify = $doctor->verification_status ?? 'pending';
            $user = $doctor->user;
        @endphp

        <div class="card">

            <div class="avatar">
                {{ strtoupper(substr($doctor->name,0,1)) }}
            </div>

            <h3>Dr. {{ $doctor->name }}</h3>

            <div class="spec">
                {{ $doctor->specialist ?? 'Specialist N/A' }}
            </div>

            <span class="status {{ $verify }}">
                {{ ucfirst($verify) }}
            </span>

            <div class="info">
                <strong>Email:</strong> {{ $doctor->email }} <br>
                <strong>Phone:</strong> {{ $doctor->phone ?? 'N/A' }}
            </div>

            <div class="actions">

                <a href="{{ route('doctors.show',$doctor->id) }}" class="btn btn-blue">View</a>

                @if($verify === 'pending')
                    <form method="POST" action="{{ route('doctors.approve',$doctor->id) }}">
                        @csrf
                        <button class="btn btn-green">Approve</button>
                    </form>

                    <form method="POST" action="{{ route('doctors.reject',$doctor->id) }}">
                        @csrf
                        <button class="btn btn-red">Reject</button>
                    </form>
                @endif

                @if($verify === 'approved')
                    <span class="status approved">Approved ✔</span>
                @endif

                @if($verify === 'rejected')
                    <span class="status rejected">Rejected ❌</span>
                @endif

                <form action="{{ route('doctors.destroy',$doctor->id) }}"
                      method="POST">
                    @csrf
                    @method('DELETE')

                    <button class="btn btn-red">Delete</button>
                </form>

            </div>

        </div>

        @empty
        <div class="card">
            <h3>No doctors found</h3>
        </div>
        @endforelse

    </div>

</div>
@endsection