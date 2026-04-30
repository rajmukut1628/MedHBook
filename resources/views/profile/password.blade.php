@include('profile.partials.style')

<div class="page">
    <div class="panel">

        <div class="top-lock">🔒</div>

        <h1>Change Password</h1>
        <p>Update your account security and keep your access protected.</p>

        @include('profile.partials.alert')

        @if ($errors->any())
            <div class="alert error-box">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="form">
            @csrf
            @method('PUT')

            <label>Current Password</label>
            <input
                type="password"
                name="current_password"
                required
                placeholder="Enter current password"
            >

            <label>New Password</label>
            <input
                type="password"
                name="password"
                required
                placeholder="Enter new password"
            >

            <label>Confirm Password</label>
            <input
                type="password"
                name="password_confirmation"
                required
                placeholder="Confirm new password"
            >

            <button type="submit">Change Password</button>
        </form>

        <a href="{{ route('dashboard') }}" class="back">← Back Dashboard</a>

    </div>
</div>

<style>
.top-lock{
    width:90px;
    height:90px;
    margin:0 auto 18px;
    border-radius:28px;
    background:linear-gradient(135deg,#0f172a,#334155);
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:38px;
    box-shadow:0 20px 40px rgba(15,23,42,.35);
}

.error-box{
    background:rgba(220,38,38,.18)!important;
    border:1px solid #ef4444!important;
    color:#fecaca!important;
}
</style>