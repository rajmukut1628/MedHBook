@extends('layouts.app')

@section('content')

<style>
body{
    background: linear-gradient(135deg,#020617,#0f172a,#064e3b);
}

.wrap{
    max-width: 950px;
    margin: auto;
    padding: 40px 20px;
}

.card-box{
    background: rgba(255,255,255,.08);
    backdrop-filter: blur(14px);
    border: 1px solid rgba(255,255,255,.10);
    border-radius: 28px;
    padding: 35px;
    box-shadow: 0 25px 70px rgba(0,0,0,.30);
}

.title{
    font-size: 38px;
    font-weight: 900;
    color: white;
    margin-bottom: 8px;
}

.sub{
    color: rgba(255,255,255,.75);
    margin-bottom: 28px;
}

.grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:18px;
}

.full{
    grid-column:1/-1;
}

label{
    display:block;
    color:white;
    font-weight:800;
    margin-bottom:8px;
}

input, textarea, select{
    width:100%;
    border-radius:18px;
    border:1px solid rgba(255,255,255,.14);
    padding:14px 16px;
    background:#0f172a;
    color:white;
    outline:none;
}

textarea{
    min-height:120px;
    resize:none;
}

input:focus, textarea:focus{
    border-color:#10b981;
}

.err{
    color:#fecaca;
    font-size:14px;
    margin-top:6px;
    font-weight:700;
}

.btn-row{
    display:flex;
    gap:12px;
    flex-wrap:wrap;
    margin-top:28px;
}

.btn-save{
    border:0;
    padding:14px 24px;
    border-radius:18px;
    font-weight:900;
    color:white;
    background:linear-gradient(135deg,#10b981,#14b8a6);
}

.btn-back{
    text-decoration:none;
    padding:14px 24px;
    border-radius:18px;
    font-weight:900;
    color:white;
    background:rgba(255,255,255,.14);
}

.note{
    margin-top:20px;
    padding:16px;
    border-radius:18px;
    background:rgba(59,130,246,.12);
    color:#dbeafe;
    font-weight:700;
}

@media(max-width:800px){
    .grid{
        grid-template-columns:1fr;
    }
}
</style>

<div class="wrap">

    <div class="card-box">

        <div class="title">➕ Add Patient</div>
        <div class="sub">
            Create patient manually with login email and password.
        </div>

        <form action="{{ route('patients.store') }}" method="POST">
            @csrf

            <div class="grid">

                <div>
                    <label>Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="err">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label>Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="err">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label>Password</label>
                    <input type="text" name="password" value="{{ old('password') }}" required>
                    @error('password')
                        <div class="err">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label>Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}">
                    @error('phone')
                        <div class="err">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label>Age</label>
                    <input type="number" name="age" value="{{ old('age') }}">
                    @error('age')
                        <div class="err">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label>Gender</label>
                    <select name="gender">
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="full">
                    <label>Address</label>
                    <textarea name="address">{{ old('address') }}</textarea>
                </div>

            </div>

            <div class="note">
                Patient can login using this Email + Password after account creation.
            </div>

            <div class="btn-row">
                <button type="submit" class="btn-save">
                    Create Patient Account
                </button>

                <a href="{{ route('patients.index') }}" class="btn-back">
                    Back
                </a>
            </div>

        </form>

    </div>

</div>

@endsection