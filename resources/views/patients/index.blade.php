@extends('layouts.app')

@section('content')
<style>
    @keyframes mhbFloat {
        0%,100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }

    @keyframes mhbGlow {
        0%,100% {
            box-shadow: 0 20px 80px rgba(15,23,42,.20);
        }
        50% {
            box-shadow: 0 25px 110px rgba(16,185,129,.22);
        }
    }

    @keyframes mhbFadeUp {
        from {
            opacity: 0;
            transform: translateY(16px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .mhb-page {
        min-height: 100vh;
        background:
            radial-gradient(circle at top left, rgba(16,185,129,.22), transparent 35%),
            radial-gradient(circle at bottom right, rgba(59,130,246,.16), transparent 35%),
            linear-gradient(135deg, #020617, #0f172a 55%, #064e3b);
        padding: 32px 20px;
    }

    .mhb-wrap {
        max-width: 1400px;
        margin: auto;
    }

    .mhb-hero,
    .mhb-panel,
    .mhb-row {
        animation: mhbFadeUp .55s ease both;
    }

    .mhb-hero {
        position: relative;
        overflow: hidden;
        border-radius: 34px;
        background: rgba(255,255,255,.08);
        border: 1px solid rgba(255,255,255,.12);
        backdrop-filter: blur(24px);
        padding: 28px;
        color: white;
        animation: mhbGlow 5s ease-in-out infinite;
    }

    .mhb-hero::after {
        content: "";
        position: absolute;
        width: 380px;
        height: 380px;
        border-radius: 999px;
        background: rgba(16,185,129,.18);
        filter: blur(45px);
        top: -160px;
        right: -120px;
        pointer-events: none;
    }

    .mhb-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 14px;
        border-radius: 999px;
        background: rgba(16,185,129,.12);
        border: 1px solid rgba(52,211,153,.25);
        color: #a7f3d0;
        font-size: 12px;
        font-weight: 900;
        letter-spacing: .16em;
        text-transform: uppercase;
    }

    .mhb-hero h1 {
        margin: 14px 0 0;
        font-size: clamp(30px, 4vw, 52px);
        font-weight: 950;
        letter-spacing: -1px;
    }

    .mhb-hero p {
        margin-top: 10px;
        color: #cbd5e1;
        max-width: 760px;
        line-height: 1.7;
    }

    .mhb-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-top: 24px;
    }

    .mhb-stat {
        border-radius: 26px;
        background: rgba(15,23,42,.58);
        border: 1px solid rgba(255,255,255,.10);
        padding: 18px;
    }

    .mhb-stat span {
        color: #94a3b8;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: .12em;
    }

    .mhb-stat strong {
        display: block;
        color: white;
        font-size: 30px;
        font-weight: 950;
        margin-top: 6px;
    }

    .mhb-panel {
        margin-top: 22px;
        border-radius: 30px;
        background: rgba(255,255,255,.08);
        border: 1px solid rgba(255,255,255,.12);
        backdrop-filter: blur(22px);
        padding: 20px;
        box-shadow: 0 20px 80px rgba(0,0,0,.30);
    }

    .mhb-filter-grid {
        display: grid;
        grid-template-columns: 1.8fr 1fr 1fr auto auto;
        gap: 12px;
        align-items: center;
    }

    .mhb-input,
    .mhb-select {
        width: 100%;
        border-radius: 18px;
        border: 1px solid rgba(255,255,255,.14);
        background: rgba(15,23,42,.82);
        color: white;
        padding: 14px 16px;
        outline: none;
        font-weight: 700;
    }

    .mhb-input::placeholder {
        color: #64748b;
    }

    .mhb-input:focus,
    .mhb-select:focus {
        border-color: rgba(52,211,153,.75);
        box-shadow: 0 0 0 4px rgba(16,185,129,.13);
    }

    .mhb-btn {
        border: 0;
        text-decoration: none;
        padding: 14px 18px;
        border-radius: 18px;
        font-weight: 950;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        white-space: nowrap;
        transition: .25s ease;
    }

    .mhb-btn:hover {
        transform: translateY(-2px);
    }

    .mhb-btn-green {
        background: linear-gradient(135deg, #10b981, #06b6d4);
        color: white;
        box-shadow: 0 18px 45px rgba(16,185,129,.22);
    }

    .mhb-btn-blue {
        background: linear-gradient(135deg, #2563eb, #06b6d4);
        color: white;
        box-shadow: 0 18px 45px rgba(37,99,235,.18);
    }

    .mhb-btn-soft {
        background: rgba(255,255,255,.08);
        border: 1px solid rgba(255,255,255,.12);
        color: white;
    }

    .mhb-alert-success {
        margin-top: 18px;
        border-radius: 24px;
        background: rgba(16,185,129,.12);
        border: 1px solid rgba(52,211,153,.28);
        color: #d1fae5;
        padding: 16px 18px;
        font-weight: 900;
    }

    .mhb-alert-error {
        margin-top: 18px;
        border-radius: 24px;
        background: rgba(239,68,68,.12);
        border: 1px solid rgba(248,113,113,.28);
        color: #fecaca;
        padding: 16px 18px;
        font-weight: 900;
    }
        .mhb-list {
        margin-top: 22px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .mhb-row {
        border-radius: 24px;
        background: rgba(255,255,255,.08);
        border: 1px solid rgba(255,255,255,.10);
        backdrop-filter: blur(20px);
        padding: 14px 16px;
        display: grid;
        grid-template-columns: 54px 1.4fr .8fr .8fr .8fr auto;
        gap: 14px;
        align-items: center;
        box-shadow: 0 16px 45px rgba(0,0,0,.18);
        transition: .25s ease;
    }

    .mhb-row:hover {
        transform: translateY(-2px);
        border-color: rgba(52,211,153,.35);
        box-shadow: 0 24px 70px rgba(16,185,129,.12);
    }

    .mhb-avatar {
        height: 52px;
        width: 52px;
        border-radius: 18px;
        background: linear-gradient(135deg, #10b981, #06b6d4);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 22px;
        font-weight: 950;
        box-shadow: 0 16px 38px rgba(16,185,129,.22);
    }

    .mhb-name {
        color: white;
        font-size: 16px;
        font-weight: 950;
        margin: 0;
    }

    .mhb-mini {
        color: #94a3b8;
        font-size: 12px;
        font-weight: 700;
        margin-top: 3px;
    }

    .mhb-pill {
        display: inline-flex;
        padding: 8px 12px;
        border-radius: 999px;
        background: rgba(6,182,212,.12);
        border: 1px solid rgba(103,232,249,.22);
        color: #a5f3fc;
        font-size: 12px;
        font-weight: 900;
    }

    .mhb-status {
        display: inline-flex;
        padding: 8px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 950;
        text-transform: capitalize;
    }

    .mhb-active {
        background: rgba(34,197,94,.14);
        color: #bbf7d0;
        border: 1px solid rgba(74,222,128,.25);
    }

    .mhb-suspended {
        background: rgba(234,179,8,.14);
        color: #fef08a;
        border: 1px solid rgba(250,204,21,.25);
    }

    .mhb-blocked {
        background: rgba(239,68,68,.14);
        color: #fecaca;
        border: 1px solid rgba(248,113,113,.25);
    }

    .mhb-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 8px;
    }

    .mhb-action {
        border: 0;
        text-decoration: none;
        padding: 9px 12px;
        border-radius: 14px;
        font-size: 12px;
        font-weight: 950;
        cursor: pointer;
        color: white;
        transition: .2s ease;
    }

    .mhb-action:hover {
        transform: translateY(-1px);
    }

    .view { background: #2563eb; }
    .suspend { background: #f59e0b; }
    .unsuspend { background: #059669; }
    .delete { background: #991b1b; }

    .mhb-inline-form {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        align-items: center;
    }

    .mhb-small-input,
    .mhb-small-select {
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,.14);
        background: rgba(15,23,42,.82);
        color: white;
        padding: 8px 9px;
        font-size: 12px;
        font-weight: 800;
        outline: none;
    }

    .mhb-small-input {
        width: 70px;
    }

    .mhb-pagination {
        margin-top: 22px;
        border-radius: 26px;
        background: rgba(255,255,255,.08);
        border: 1px solid rgba(255,255,255,.10);
        padding: 16px;
        color: white;
    }

    @media(max-width: 1200px) {
        .mhb-filter-grid {
            grid-template-columns: 1fr 1fr;
        }

        .mhb-row {
            grid-template-columns: 52px 1fr;
        }

        .mhb-actions {
            grid-column: 1 / -1;
            justify-content: flex-start;
        }
    }

    @media(max-width: 760px) {
        .mhb-stats {
            grid-template-columns: repeat(2, 1fr);
        }

        .mhb-filter-grid {
            grid-template-columns: 1fr;
        }

        .mhb-row {
            grid-template-columns: 1fr;
        }

        .mhb-avatar {
            display: none;
        }
    }
</style>

<div class="mhb-page">
    <div class="mhb-wrap">

        <div class="mhb-hero">
            <span class="mhb-badge">
                🫀 Admin Patient Management
            </span>

            <h1>Patients Directory</h1>

            <p>
                Manage patient accounts, status, privacy records and core medical identity.
                Compact premium list view with 25 patients per page.
            </p>

            <div class="mhb-stats">
                <div class="mhb-stat">
                    <span>Total Patients</span>
                    <strong>{{ $totalPatients ?? 0 }}</strong>
                </div>

                <div class="mhb-stat">
                    <span>Active</span>
                    <strong>{{ $activePatients ?? 0 }}</strong>
                </div>

                <div class="mhb-stat">
                    <span>Suspended</span>
                    <strong>{{ $suspendedPatients ?? 0 }}</strong>
                </div>

                <div class="mhb-stat">
                    <span>Blocked</span>
                    <strong>{{ $blockedPatients ?? 0 }}</strong>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mhb-alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mhb-alert-error">
                {{ session('error') }}
            </div>
        @endif

        <div class="mhb-panel">
            <form method="GET" action="{{ route('patients.index') }}" class="mhb-filter-grid">
                <input class="mhb-input"
                       type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search by name, email, phone, patient ID, blood group or privacy key...">

                <select name="gender" class="mhb-select">
                    <option value="">All Gender</option>
                    <option value="Male" {{ request('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ request('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Other" {{ request('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>

                <select name="status" class="mhb-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>

                <button class="mhb-btn mhb-btn-green" type="submit">
                    🔍 Filter
                </button>

                <a href="{{ route('patients.create') }}" class="mhb-btn mhb-btn-blue">
                    + Add Patient
                </a>
            </form>

            @if(request('search') || request('gender') || request('status'))
                <div style="margin-top:14px;">
                    <a href="{{ route('patients.index') }}" class="mhb-btn mhb-btn-soft">
                        Clear Filters
                    </a>
                </div>
            @endif

            <div class="mhb-list">
                @forelse($patients as $patient)
                    @php
                        $userStatus = strtolower(optional($patient->user)->status ?? 'active');

                        $statusClass = match($userStatus) {
                            'suspended' => 'mhb-suspended',
                            'blocked', 'inactive' => 'mhb-blocked',
                            default => 'mhb-active',
                        };
                    @endphp

                    <div class="mhb-row">
                        <div class="mhb-avatar">
                            {{ strtoupper(substr($patient->name ?? 'P', 0, 1)) }}
                        </div>

                        <div>
                            <h3 class="mhb-name">
                                {{ $patient->name ?? optional($patient->user)->name ?? 'N/A' }}
                            </h3>
                            <div class="mhb-mini">
                                P{{ $patient->id }} • {{ $patient->email ?? optional($patient->user)->email ?? 'No email' }}
                            </div>
                        </div>

                        <div>
                            <span class="mhb-pill">
                                {{ $patient->phone ?? 'No phone' }}
                            </span>
                        </div>

                        <div>
                            <span class="mhb-pill">
                                {{ $patient->blood_group ?? 'Blood N/A' }}
                            </span>
                        </div>

                        <div>
                            <span class="mhb-status {{ $statusClass }}">
                                {{ ucfirst($userStatus) }}
                            </span>
                        </div>

                        <div class="mhb-actions">
                            <a href="{{ route('patients.show', $patient->id) }}" class="mhb-action view">
                                View
                            </a>

                            @if(auth()->user()->role === 'admin' && $patient->user)
                                @if($userStatus !== 'suspended')
                                    <form action="{{ route('admin.users.suspend', $patient->user->id) }}"
                                          method="POST"
                                          class="mhb-inline-form">
                                        @csrf
                                        @method('PATCH')

                                        <select name="duration_type" class="mhb-small-select">
                                            <option value="days">Days</option>
                                            <option value="months">Months</option>
                                        </select>

                                        <input type="number"
                                               name="duration"
                                               min="1"
                                               value="7"
                                               class="mhb-small-input">

                                        <button class="mhb-action suspend" type="submit">
                                            Suspend
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.users.unsuspend', $patient->user->id) }}"
                                          method="POST">
                                        @csrf
                                        @method('PATCH')

                                        <button class="mhb-action unsuspend" type="submit">
                                            Unsuspend
                                        </button>
                                    </form>
                                @endif
                            @endif

                            <form action="{{ route('patients.destroy', $patient->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this patient?');">
                                @csrf
                                @method('DELETE')

                                <button class="mhb-action delete" type="submit">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="mhb-row">
                        <div>
                            <h3 class="mhb-name">No patients found</h3>
                            <div class="mhb-mini">Try changing search, gender or status filter.</div>
                        </div>
                    </div>
                @endforelse
            </div>

            @if(method_exists($patients, 'links'))
                <div class="mhb-pagination">
                    {{ $patients->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection