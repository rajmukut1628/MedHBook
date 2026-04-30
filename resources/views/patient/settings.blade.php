<x-app-layout>

<style>
body{background:linear-gradient(135deg,#020617,#0f172a,#111827);}
.wrap{max-width:1100px;margin:auto;padding:35px;}
.hero{background:linear-gradient(135deg,#2563eb,#1e40af,#0f172a);padding:38px;border-radius:30px;color:white;box-shadow:0 25px 60px rgba(0,0,0,.25);margin-bottom:28px;}
.hero h1{font-size:40px;font-weight:900;margin:0;}
.hero p{margin-top:10px;opacity:.88;}
.grid{display:grid;grid-template-columns:1fr 1fr;gap:22px;}
.card{background:rgba(255,255,255,.08);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.12);padding:28px;border-radius:28px;color:white;box-shadow:0 15px 40px rgba(0,0,0,.20);}
.card h2{font-size:25px;font-weight:900;margin:0 0 8px;}
.card p{opacity:.75;margin-bottom:20px;}
.label{display:block;font-weight:800;margin:15px 0 8px;}
.input{width:100%;border:1px solid rgba(255,255,255,.18);border-radius:18px;padding:14px;background:rgba(15,23,42,.75);color:white;}
.btn{border:0;border-radius:18px;padding:14px 20px;color:white;font-weight:900;cursor:pointer;margin-top:18px;}
.green{background:linear-gradient(135deg,#10b981,#14b8a6);}
.red{background:linear-gradient(135deg,#dc2626,#fb7185);}
.blue{background:linear-gradient(135deg,#2563eb,#38bdf8);}
.white-btn{background:white;color:#0f172a;}
.dark-btn{background:#020617;color:white;border:1px solid rgba(255,255,255,.25);}
.theme-row{display:flex;gap:12px;flex-wrap:wrap;}
.full{grid-column:1/-1;}
.back{display:inline-block;margin-top:18px;text-decoration:none;color:white;font-weight:900;background:rgba(255,255,255,.14);padding:12px 18px;border-radius:16px;}
.error-text{color:#fecaca;font-weight:700;margin-top:7px;font-size:14px;}
.status{padding:15px 18px;background:rgba(16,185,129,.18);border:1px solid rgba(16,185,129,.35);color:white;border-radius:18px;margin-bottom:18px;font-weight:800;}
@media(max-width:850px){.grid{grid-template-columns:1fr}.wrap{padding:20px}.hero h1{font-size:32px}}
</style>

<div class="wrap">

    <div class="hero">
        <h1>Patient Settings</h1>
        <p>Change password, delete account and customize your theme mode.</p>

        <a href="{{ route('patient.dashboard') }}" class="back">← Back to Dashboard</a>
    </div>

    @if(session('status'))
        <div class="status">{{ session('status') }}</div>
    @endif

    <div class="grid">

    

        <div class="card">
            <h2>🔐 Change Password</h2>
            <p>Update your account password securely.</p>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')

                <label class="label">Current Password</label>
                <input type="password" name="current_password" class="input" required>
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="error-text" />

                <label class="label">New Password</label>
                <input type="password" name="password" class="input" required>
                <x-input-error :messages="$errors->updatePassword->get('password')" class="error-text" />

                <label class="label">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="input" required>

                <button type="submit" class="btn green">Update Password</button>
            </form>
        </div>

        <div class="card">
            <h2>🗑 Delete Account</h2>
            <p>This action is permanent. Please enter your password to continue.</p>

            <form method="POST"
                  action="{{ route('profile.destroy') }}"
                  onsubmit="return confirm('Are you sure you want to delete your account?');">
                @csrf
                @method('DELETE')

                <label class="label">Password</label>
                <input type="password" name="password" class="input" required>
                <x-input-error :messages="$errors->userDeletion->get('password')" class="error-text" />

                <button type="submit" class="btn red">Delete My Account</button>
            </form>
        </div>

    </div>
</div>
</x-app-layout>