@if(session('success'))
    <div class="alert success-alert">
        <span class="alert-icon">✔</span>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="alert error-alert">
        <span class="alert-icon">✖</span>
        <span>{{ session('error') }}</span>
    </div>
@endif

<style>
.success-alert{
    display:flex;
    align-items:center;
    gap:12px;
    background:rgba(16,185,129,.22)!important;
    border:1px solid #10b981!important;
    color:#ecfdf5!important;
}

.error-alert{
    display:flex;
    align-items:center;
    gap:12px;
    background:rgba(220,38,38,.18)!important;
    border:1px solid #ef4444!important;
    color:#fecaca!important;
}

.alert-icon{
    width:32px;
    height:32px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:900;
    background:rgba(255,255,255,.12);
}
</style>