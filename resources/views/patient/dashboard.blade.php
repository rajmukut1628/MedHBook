<x-app-layout>

<style>
body.theme-dark{background:linear-gradient(135deg,#020617,#0f172a,#111827);}
body.theme-white{background:linear-gradient(135deg,#f8fafc,#e0f2fe,#ffffff);}
body.theme-blue{background:linear-gradient(135deg,#0f172a,#1d4ed8,#38bdf8);}
.wrap{max-width:1400px;margin:auto;padding:35px;}
.hero{background:linear-gradient(135deg,#059669,#065f46,#022c22);padding:40px;border-radius:30px;color:white;box-shadow:0 25px 60px rgba(0,0,0,.25);margin-bottom:28px;}
.hero h1{font-size:42px;font-weight:900;margin:0;}
.hero p{margin-top:10px;font-size:16px;opacity:.9;}
.alert{padding:18px 22px;border-radius:22px;margin-bottom:20px;color:white;font-weight:800;}
.success{background:rgba(16,185,129,.18);border:1px solid rgba(16,185,129,.35);}
.error{background:rgba(239,68,68,.18);border:1px solid rgba(239,68,68,.35);}
.grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px;}
.card{background:rgba(255,255,255,.08);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.10);padding:28px;border-radius:28px;text-decoration:none;color:white;transition:.25s;box-shadow:0 15px 40px rgba(0,0,0,.20);}
.card:hover{transform:translateY(-8px);box-shadow:0 25px 60px rgba(0,0,0,.28);border-color:rgba(16,185,129,.45);}
.icon{width:64px;height:64px;border-radius:22px;display:flex;align-items:center;justify-content:center;font-size:30px;margin-bottom:18px;}
.blue{background:linear-gradient(135deg,#2563eb,#38bdf8);}
.green{background:linear-gradient(135deg,#059669,#22c55e);}
.purple{background:linear-gradient(135deg,#7c3aed,#c084fc);}
.orange{background:linear-gradient(135deg,#ea580c,#f59e0b);}
.red{background:linear-gradient(135deg,#dc2626,#fb7185);}
.gold{background:linear-gradient(135deg,#f59e0b,#facc15);}
.slate{background:linear-gradient(135deg,#0f172a,#475569);}
.card h2{font-size:24px;font-weight:900;margin:0;}
.card p{margin-top:8px;opacity:.8;font-size:14px;}
.key{font-size:34px;font-weight:900;letter-spacing:4px;margin-top:12px;color:#fde68a;word-break:break-all;}
.btn-row{display:flex;flex-wrap:wrap;gap:10px;margin-top:18px;}
.copy-btn,.regen-btn{border:0;border-radius:16px;padding:12px 16px;color:white;font-weight:900;cursor:pointer;transition:.25s;}
.copy-btn{background:linear-gradient(135deg,#10b981,#14b8a6);}
.regen-btn{background:linear-gradient(135deg,#dc2626,#fb7185);}
.copy-btn:hover,.regen-btn:hover{transform:translateY(-2px);filter:brightness(1.08);}
@media(max-width:900px){.grid{grid-template-columns:1fr}.wrap{padding:20px}.hero h1{font-size:32px}}
</style>

<div class="wrap">

    <div class="hero">
        <h1>Patient Dashboard</h1>
        <p>Welcome back, {{ auth()->user()->name }} | Role: Patient</p>
    </div>

    @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert error">{{ session('error') }}</div>
    @endif

    <div class="grid">

        <div class="card">
            <div class="icon gold">🔐</div>
            <h2>Privacy Key</h2>
            <p>Share only with trusted doctor. Regenerate anytime if needed.</p>

            <div class="key">
                {{ $privacyKey ?? '------' }}
            </div>

            <div class="btn-row">
                <button type="button"
                        class="copy-btn"
                        onclick="navigator.clipboard.writeText('{{ $privacyKey ?? '' }}'); this.innerText='Copied!'; setTimeout(() => this.innerText='Copy Key', 2000);">
                    Copy Key
                </button>

                <form method="POST"
                      action="{{ route('patient.privacy-key.regenerate') }}"
                      onsubmit="return confirm('Old privacy key will stop working. Continue?')">
                    @csrf

                    <button type="submit" class="regen-btn">
                        Regenerate
                    </button>
                </form>
            </div>
        </div>

        <a href="{{ route('find.doctors') }}" class="card">
            <div class="icon blue">👨‍⚕️</div>
            <h2>Find Doctors</h2>
            <p>Browse specialist doctors list</p>
        </a>

        <a href="{{ route('appointments.create') }}" class="card">
            <div class="icon green">📅</div>
            <h2>Book Appointment</h2>
            <p>Schedule your visit instantly</p>
        </a>

        <a href="{{ route('appointments.index') }}" class="card">
            <div class="icon purple">📋</div>
            <h2>My Appointments</h2>
            <p>Track booking history</p>
        </a>

        <a href="{{ route('medical-documents.index') }}" class="card">
            <div class="icon red">📄</div>
            <h2>Upload Documents</h2>
            <p>Upload reports and prescriptions</p>
        </a>

        <a href="{{ route('patient.my-profile') }}" class="card">
            <div class="icon orange">👤</div>
            <h2>Profile</h2>
            <p>Edit personal information</p>
        </a>

        <a href="{{ route('patient.settings') }}" class="card">
            <div class="icon slate">⚙️</div>
            <h2>Settings</h2>
            <p>Change password, delete account and theme mode</p>
        </a>

    </div>

</div>
<script>
function applyPatientTheme(){
    let mode = localStorage.getItem('patient_theme_mode') || 'dark';

    if(mode === 'white'){
        document.body.style.background = 'linear-gradient(135deg,#f8fafc,#e0f2fe,#ffffff)';
    }

    if(mode === 'dark'){
        document.body.style.background = 'linear-gradient(135deg,#020617,#0f172a,#111827)';
    }

    if(mode === 'blue'){
        document.body.style.background = 'linear-gradient(135deg,#0f172a,#1d4ed8,#38bdf8)';
    }
}

document.addEventListener('DOMContentLoaded', applyPatientTheme);
</script>

</x-app-layout>