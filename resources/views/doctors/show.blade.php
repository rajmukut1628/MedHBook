@extends('layouts.app')

@section('content')
<style>
    .wrap{max-width:900px;margin:auto;padding:30px}

    .hero{
        background:linear-gradient(135deg,#0f172a,#047857);
        color:white;
        padding:32px;
        border-radius:30px;
        margin-bottom:24px;
        box-shadow:0 25px 60px rgba(0,0,0,.18)
    }

    .hero h1{font-size:34px;font-weight:900;margin:0}
    .hero p{color:#d1fae5;margin-top:8px}

    .card{
        background:white;
        border-radius:28px;
        padding:28px;
        box-shadow:0 20px 55px rgba(15,23,42,.08);
        border:1px solid #eef2f7
    }

    .top{
        display:flex;
        align-items:center;
        gap:18px;
        margin-bottom:25px;
        flex-wrap:wrap;
    }

    .avatar{
        width:78px;
        height:78px;
        border-radius:24px;
        background:linear-gradient(135deg,#10b981,#22c55e);
        display:flex;
        align-items:center;
        justify-content:center;
        color:white;
        font-size:34px;
        font-weight:900;
        overflow:hidden;
        border:4px solid #bbf7d0;
        box-shadow:0 15px 35px rgba(16,185,129,.25);
    }

    .avatar img{
        width:100%;
        height:100%;
        object-fit:cover;
        display:block;
    }

    .name{
        font-size:30px;
        font-weight:900;
        color:#0f172a;
        margin:0;
    }

    .spec{
        color:#047857;
        font-size:16px;
        font-weight:800;
        margin-top:4px;
    }

    .secure-badge{
        display:inline-flex;
        margin-top:8px;
        padding:7px 12px;
        border-radius:999px;
        background:#ecfdf5;
        color:#047857;
        font-size:12px;
        font-weight:900;
    }

    .grid{
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:18px;
        margin-top:20px;
    }

    .box{
        background:#f8fafc;
        border:1px solid #e2e8f0;
        padding:18px;
        border-radius:20px;
    }

    .label{
        font-size:13px;
        color:#64748b;
        font-weight:800;
        margin-bottom:6px;
        text-transform:uppercase;
    }

    .value{
        font-size:18px;
        color:#0f172a;
        font-weight:800;
        word-break:break-word;
    }
        .actions{
        display:flex;
        gap:12px;
        margin-top:28px;
        flex-wrap:wrap;
    }

    .btn{
        border:0;
        text-decoration:none;
        padding:14px 22px;
        border-radius:18px;
        font-weight:900;
        display:inline-block;
        cursor:pointer;
    }

    .btn-gray{background:#e2e8f0;color:#334155}
    .btn-red{background:#dc2626;color:white}
    .btn-blue{background:#2563eb;color:white}
    .btn-green{background:#059669;color:white}

    @media(max-width:700px){
        .wrap{padding:18px}
        .hero h1{font-size:28px}
        .grid{grid-template-columns:1fr}
        .name{font-size:24px}
    }
</style>

<div class="wrap">

    <div class="hero">
        <h1>Doctor Details</h1>
        <p>View complete doctor profile information with secure photo and CV access.</p>
    </div>

    @php
        $name = $doctor->name ?? 'Unknown Doctor';
        $specialist = $doctor->specialist ?? $doctor->specialization ?? 'Specialist N/A';

        $doctorPhoto = $doctor->profile_photo ?? optional($doctor->user)->profile_photo;
        $doctorCv = $doctor->cv ?? null;
    @endphp

    <div class="card">

        <div class="top">
            <div class="avatar">
                @if($doctorPhoto)
                    <img src="{{ route('secure.file.show', [
                        'folder' => 'doctor-photos',
                        'filename' => basename($doctorPhoto)
                    ]) }}" alt="Doctor Photo">
                @else
                    {{ strtoupper(substr($name,0,1)) }}
                @endif
            </div>

            <div>
                <h2 class="name">Dr. {{ $name }}</h2>
                <div class="spec">{{ $specialist }}</div>
                <div class="secure-badge">🔐 Private file access active</div>
            </div>
        </div>

        <div class="grid">

            <div class="box">
                <div class="label">Doctor ID</div>
                <div class="value">{{ $doctor->id }}</div>
            </div>

            <div class="box">
                <div class="label">Phone</div>
                <div class="value">{{ $doctor->phone ?? 'N/A' }}</div>
            </div>

            <div class="box">
                <div class="label">Email</div>
                <div class="value">{{ $doctor->email ?? 'N/A' }}</div>
            </div>

            <div class="box">
                <div class="label">Degree</div>
                <div class="value">{{ $doctor->degree ?? 'N/A' }}</div>
            </div>

            <div class="box">
                <div class="label">Experience</div>
                <div class="value">{{ $doctor->experience ?? 0 }} Years</div>
            </div>

            <div class="box">
                <div class="label">Status</div>
                <div class="value">{{ ucfirst($doctor->verification_status ?? 'pending') }}</div>
            </div>

            <div class="box">
                <div class="label">CV Status</div>
                <div class="value">
                    @if($doctorCv)
                        Encrypted CV Available
                    @else
                        No CV Uploaded
                    @endif
                </div>
            </div>

            <div class="box">
                <div class="label">Storage</div>
                <div class="value">Private Encrypted Storage</div>
            </div>

        </div>

        <div class="actions">
            <a href="{{ route('doctors.index') }}" class="btn btn-gray">
                ← Back
            </a>

            @if($doctorCv)
                <a href="{{ route('secure.file.download', [
                    'folder' => 'doctor-cvs',
                    'filename' => basename($doctorCv)
                ]) }}" class="btn btn-blue">
                    Download CV
                </a>
            @endif

            <form action="{{ route('doctors.destroy', $doctor->id) }}"
                  method="POST"
                  onsubmit="return confirm('Delete this doctor?')">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-red">
                    Delete
                </button>
            </form>
        </div>

    </div>

</div>
@endsection