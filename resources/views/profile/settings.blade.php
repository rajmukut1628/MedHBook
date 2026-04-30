@include('profile.partials.style')

<div class="page">
    <div class="panel">

        <div class="top-settings">⚙️</div>

        <h1>Settings</h1>
        <p>Customize your MedHBook premium experience.</p>

        <div class="settings-grid">

            <div class="setting-card">
                <h3>🌙 Dark Mode</h3>
                <p>Premium dark healthcare interface enabled.</p>
                <span class="badge">Active</span>
            </div>

            <div class="setting-card">
                <h3>🔔 Notifications</h3>
                <p>Appointment reminders and account alerts enabled.</p>
                <span class="badge">Enabled</span>
            </div>

            <div class="setting-card">
                <h3>🛡 Security</h3>
                <p>Protected login, password update and secure dashboard.</p>
                <span class="badge">Protected</span>
            </div>

            <div class="setting-card">
                <h3>👤 Role</h3>
                <p>Your current account type is {{ ucfirst(auth()->user()->role) }}.</p>
                <span class="badge">{{ ucfirst(auth()->user()->role) }}</span>
            </div>

        </div>

        <a href="{{ route('dashboard') }}" class="back">← Back Dashboard</a>
    </div>
</div>

<style>
.top-settings{
    width:90px;
    height:90px;
    margin:0 auto 18px;
    border-radius:28px;
    background:linear-gradient(135deg,#7c3aed,#22c55e);
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:38px;
    box-shadow:0 20px 40px rgba(124,58,237,.35);
}

.badge{
    display:inline-block;
    margin-top:12px;
    padding:8px 14px;
    border-radius:999px;
    background:rgba(16,185,129,.22);
    color:#6ee7b7;
    font-weight:900;
    font-size:13px;
}
</style>