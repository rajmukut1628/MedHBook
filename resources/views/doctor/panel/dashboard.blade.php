<x-app-layout>
<style>
    body{
        background: linear-gradient(135deg,#eff6ff,#ecfdf5);
    }

    .wrap{
        padding:30px;
        max-width:1350px;
        margin:auto;
    }

    .hero{
        background: linear-gradient(135deg,#0f172a,#1d4ed8);
        color:white;
        padding:36px;
        border-radius:32px;
        box-shadow:0 30px 70px rgba(0,0,0,.18);
        margin-bottom:28px;
    }

    .hero h1{
        font-size:38px;
        font-weight:900;
        margin:0;
    }

    .hero p{
        margin-top:8px;
        opacity:.9;
    }

    .stats{
        display:grid;
        grid-template-columns:repeat(4,1fr);
        gap:18px;
        margin-bottom:28px;
    }

    .stat{
        background:white;
        border-radius:24px;
        padding:22px;
        box-shadow:0 18px 45px rgba(15,23,42,.08);
    }

    .stat .num{
        font-size:28px;
        font-weight:900;
        color:#2563eb;
    }

    .stat .txt{
        color:#64748b;
        margin-top:6px;
    }

    .grid{
        display:grid;
        grid-template-columns:repeat(4,1fr);
        gap:22px;
    }

    .card{
        text-decoration:none;
        color:#0f172a;
        background:white;
        border-radius:28px;
        padding:28px;
        box-shadow:0 20px 55px rgba(15,23,42,.08);
        transition:.25s;
        border:1px solid #eef2f7;
    }

    .card:hover{
        transform:translateY(-8px);
        box-shadow:0 28px 65px rgba(15,23,42,.12);
    }

    .icon{
        width:62px;
        height:62px;
        border-radius:20px;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:30px;
        margin-bottom:18px;
        color:white;
    }

    .blue{background:linear-gradient(135deg,#2563eb,#38bdf8);}
    .green{background:linear-gradient(135deg,#059669,#22c55e);}
    .purple{background:linear-gradient(135deg,#7c3aed,#c084fc);}
    .yellow{background:linear-gradient(135deg,#ca8a04,#facc15);}
    .red{background:linear-gradient(135deg,#dc2626,#fb7185);}
    .dark{background:linear-gradient(135deg,#0f172a,#334155);}
    .teal{background:linear-gradient(135deg,#0f766e,#2dd4bf);}
    .orange{background:linear-gradient(135deg,#ea580c,#fb923c);}

    .card h2{
        font-size:22px;
        font-weight:900;
        margin:0;
    }

    .card p{
        color:#64748b;
        margin-top:8px;
        line-height:1.5;
    }

    .bottom{
        margin-top:28px;
        background:white;
        border-radius:28px;
        padding:28px;
        box-shadow:0 20px 55px rgba(15,23,42,.08);
    }

    .bottom h3{
        margin:0;
        font-size:26px;
        font-weight:900;
        color:#0f172a;
    }

    .bottom p{
        color:#64748b;
        margin-top:8px;
    }

    @media(max-width:1200px){
        .grid{grid-template-columns:1fr 1fr;}
        .stats{grid-template-columns:1fr 1fr;}
    }

    @media(max-width:768px){
        .grid{grid-template-columns:1fr;}
        .stats{grid-template-columns:1fr;}
        .hero h1{font-size:30px;}
        .wrap{padding:18px;}
    }
</style>

<div class="wrap">

    <div class="hero">
        <h1>Doctor Dashboard</h1>
        <p>Welcome Dr. {{ auth()->user()->name }} — manage profile, schedule, appointments and practice growth.</p>
    </div>

    <div class="stats">
        <div class="stat">
            <div class="num">12</div>
            <div class="txt">Today's Appointments</div>
        </div>

        <div class="stat">
            <div class="num">4.9★</div>
            <div class="txt">Patient Rating</div>
        </div>

        <div class="stat">
            <div class="num">128</div>
            <div class="txt">Total Patients</div>
        </div>

        <div class="stat">
            <div class="num">৳ 24K</div>
            <div class="txt">Monthly Earnings</div>
        </div>
    </div>

    <div class="grid">

        <a href="{{ route('doctor.profile') }}" class="card">
            <div class="icon blue">👨‍⚕️</div>
            <h2>Doctor Profile</h2>
            <p>Manage specialization, chamber and personal details.</p>
        </a>

        <a href="{{ route('doctor.schedule') }}" class="card">
            <div class="icon green">🕒</div>
            <h2>Smart Schedule</h2>
            <p>Create slots, weekly timing and availability setup.</p>
        </a>

        <a href="{{ route('doctor.appointments') }}" class="card">
            <div class="icon purple">📅</div>
            <h2>Appointments</h2>
            <p>Approve, reject or manage booking requests.</p>
        </a>

        <a href="{{ route('doctor.dashboard') }}" class="card">
            <div class="icon yellow">🏠</div>
            <h2>Main Dashboard</h2>
            <p>Quick access to overview and analytics.</p>
        </a>

        <a href="{{ route('profile.edit') }}" class="card">
            <div class="icon red">👤</div>
            <h2>My Account</h2>
            <p>Update login account and contact information.</p>
        </a>

        <a href="{{ route('password.edit') }}" class="card">
            <div class="icon dark">🔒</div>
            <h2>Security</h2>
            <p>Change password and secure your doctor panel.</p>
        </a>

        <a href="{{ route('settings') }}" class="card">
            <div class="icon teal">⚙️</div>
            <h2>Settings</h2>
            <p>Notifications, preferences and panel controls.</p>
        </a>

        <a href="{{ route('doctor.appointments') }}" class="card">
            <div class="icon orange">📈</div>
            <h2>Practice Growth</h2>
            <p>Track visits, demand and performance trends.</p>
        </a>

    </div>

    <div class="bottom">
        <h3>Premium Doctor Workspace</h3>
        <p>Efficient scheduling, verified patients, secure data and faster practice management.</p>
    </div>

</div>
</x-app-layout>