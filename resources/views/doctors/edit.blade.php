@extends('layouts.app')

@section('content')
<style>
    .wrap{max-width:1050px;margin:auto;padding:30px}
    .hero{
        background:linear-gradient(135deg,#020617,#064e3b,#0891b2);
        color:white;padding:36px;border-radius:32px;margin-bottom:24px;
        box-shadow:0 25px 70px rgba(0,0,0,.28)
    }
    .hero h1{font-size:36px;font-weight:900;margin:0}
    .hero p{color:#ccfbf1;margin-top:8px}
    .card{
        background:linear-gradient(180deg,#ffffff,#f8fafc);
        border-radius:30px;padding:28px;
        box-shadow:0 25px 65px rgba(15,23,42,.12);
        border:1px solid #e2e8f0
    }
    .topbar{display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap;margin-bottom:22px}
    .badge{background:#dcfce7;color:#047857;padding:9px 15px;border-radius:999px;font-weight:900;font-size:13px}
    .chamber-box{
        background:white;border:1px solid #e2e8f0;border-radius:26px;
        padding:24px;margin-bottom:20px;box-shadow:0 14px 35px rgba(15,23,42,.07)
    }
    .chamber-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;gap:12px}
    .chamber-head h3{font-size:20px;font-weight:900;color:#0f172a;margin:0}
    .remove-btn{background:#fee2e2;color:#dc2626;border:0;padding:10px 15px;border-radius:14px;font-weight:900;cursor:pointer}
    .grid{display:grid;grid-template-columns:1fr 1fr;gap:18px}
    .days-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px}
    .form-group{margin-bottom:18px}
    .form-group label{display:block;font-weight:900;color:#0f172a;margin-bottom:8px}
    .input{
        width:100%;padding:15px 18px;border-radius:18px;border:1px solid #cbd5e1;
        outline:none;background:#f8fafc;color:#0f172a;font-weight:700
    }
    .input:focus{border-color:#10b981;box-shadow:0 0 0 4px rgba(16,185,129,.14);background:white}
    .day-label{
        display:flex!important;align-items:center;gap:10px;font-weight:900!important;
        background:#f1f5f9;border:1px solid #e2e8f0;border-radius:16px;
        padding:13px 14px;cursor:pointer
    }
    .add-btn{
        background:linear-gradient(135deg,#10b981,#0891b2);
        color:white;border:0;padding:15px 22px;border-radius:18px;
        font-weight:900;cursor:pointer;box-shadow:0 14px 30px rgba(16,185,129,.28)
    }
    .actions{display:flex;gap:12px;justify-content:space-between;flex-wrap:wrap;margin-top:28px}
    .btn{border:0;text-decoration:none;padding:14px 22px;border-radius:18px;font-weight:900;cursor:pointer;display:inline-block}
    .btn-save{background:#2563eb;color:white}
    .btn-gray{background:#e2e8f0;color:#334155}
    .hint{font-size:13px;color:#64748b;margin-top:10px;font-weight:700}
    .error{color:#dc2626;font-size:14px;font-weight:800;margin-top:6px}
    @media(max-width:800px){
        .wrap{padding:18px}.hero h1{font-size:28px}.grid,.days-grid{grid-template-columns:1fr}
    }
</style>

<div class="wrap">
    <div class="hero">
        <h1>Manage Chambers & Schedule</h1>
        <p>Add multiple chambers with separate working days, available time and consultation fee.</p>
    </div>

    <div class="card">
        <div class="topbar">
            <div>
                <span class="badge">🏥 Multi Chamber System</span>
                <p class="hint">
                    Doctor can manage more than one chamber from here.
                </p>
            </div>

            <a href="{{ route('profile.edit') }}" class="btn btn-gray">
                ← Back to My Profile
            </a>
        </div>

        @if($errors->any())
            <div style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca;padding:16px;border-radius:18px;margin-bottom:20px;font-weight:800">
                <ul style="margin:0;padding-left:18px">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('doctors.update', $doctor->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Hidden basic values so controller validation/update does not break --}}
            <input type="hidden" name="name" value="{{ old('name', $doctor->name ?? '') }}">
            <input type="hidden" name="phone" value="{{ old('phone', $doctor->phone ?? 'N/A') }}">
            <input type="hidden" name="specialist" value="{{ old('specialist', $doctor->specialist ?? $doctor->specialization ?? 'Doctor') }}">
            <input type="hidden" name="degree" value="{{ old('degree', $doctor->degree ?? '') }}">
            <input type="hidden" name="qualification" value="{{ old('qualification', $doctor->qualification ?? '') }}">
            <input type="hidden" name="experience" value="{{ old('experience', $doctor->experience ?? 0) }}">

            @php
                $oldChambers = old('chambers');

                if ($oldChambers) {
                    $chambers = $oldChambers;
                } elseif (!empty($doctor->chambers) && is_array($doctor->chambers)) {
                    $chambers = $doctor->chambers;
                } else {
                    $addresses = $doctor->chamber_addresses ?? $doctor->chamber_address ?? '';
                    $chambers = [
                        [
                            'address' => is_array($addresses) ? implode("\n", $addresses) : $addresses,
                            'working_days' => $doctor->working_days ?? [],
                            'start_time' => $doctor->start_time ?? '',
                            'end_time' => $doctor->end_time ?? '',
                            'fee' => $doctor->consultation_fee ?? 0,
                        ]
                    ];
                }

                $days = ['Saturday','Sunday','Monday','Tuesday','Wednesday','Thursday','Friday'];
            @endphp

            <div id="chambers-container">
                                @foreach($chambers as $index => $chamber)
                    <div class="chamber-box">
                        <div class="chamber-head">
                            <h3>Chamber #{{ $index + 1 }}</h3>

                            @if($index > 0)
                                <button type="button" class="remove-btn" onclick="this.closest('.chamber-box').remove()">
                                    Remove
                                </button>
                            @endif
                        </div>

                        <div class="form-group">
                            <label>Chamber Address</label>
                            <textarea name="chambers[{{ $index }}][address]"
                                      rows="3"
                                      class="input"
                                      placeholder="Example: Popular Diagnostic Center, Dhaka">{{ $chamber['address'] ?? '' }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Working Days</label>

                            <div class="days-grid">
                                @foreach($days as $day)
                                    <label class="day-label">
                                        <input type="checkbox"
                                               name="chambers[{{ $index }}][working_days][]"
                                               value="{{ $day }}"
                                               {{ in_array($day, $chamber['working_days'] ?? []) ? 'checked' : '' }}>
                                        {{ $day }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="grid">
                            <div class="form-group">
                                <label>Start Time</label>
                                <input type="time"
                                       name="chambers[{{ $index }}][start_time]"
                                       class="input"
                                       value="{{ $chamber['start_time'] ?? '' }}">
                            </div>

                            <div class="form-group">
                                <label>End Time</label>
                                <input type="time"
                                       name="chambers[{{ $index }}][end_time]"
                                       class="input"
                                       value="{{ $chamber['end_time'] ?? '' }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Consultation Fee</label>
                            <input type="number"
                                   name="chambers[{{ $index }}][fee]"
                                   class="input"
                                   value="{{ $chamber['fee'] ?? '' }}"
                                   placeholder="Example: 800"
                                   min="0">
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" class="add-btn" onclick="addChamber()">
                + Add Another Chamber
            </button>

            <p class="hint">
                প্রতিটা chamber এর আলাদা address, working days, time এবং fee save হবে।
            </p>

            <div class="actions">
                <a href="{{ route('profile.edit') }}" class="btn btn-gray">← Back</a>

                <button type="submit" class="btn btn-save">
                    Save Chambers & Schedule
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let chamberIndex = {{ count($chambers) }};

function addChamber() {
    const container = document.getElementById('chambers-container');

    const html = `
        <div class="chamber-box">
            <div class="chamber-head">
                <h3>Chamber #${chamberIndex + 1}</h3>

                <button type="button" class="remove-btn" onclick="this.closest('.chamber-box').remove()">
                    Remove
                </button>
            </div>

            <div class="form-group">
                <label>Chamber Address</label>
                <textarea name="chambers[${chamberIndex}][address]"
                          rows="3"
                          class="input"
                          placeholder="Example: Popular Diagnostic Center, Dhaka"></textarea>
            </div>

            <div class="form-group">
                <label>Working Days</label>

                <div class="days-grid">
                    ${['Saturday','Sunday','Monday','Tuesday','Wednesday','Thursday','Friday'].map(day => `
                        <label class="day-label">
                            <input type="checkbox"
                                   name="chambers[${chamberIndex}][working_days][]"
                                   value="${day}">
                            ${day}
                        </label>
                    `).join('')}
                </div>
            </div>

            <div class="grid">
                <div class="form-group">
                    <label>Start Time</label>
                    <input type="time"
                           name="chambers[${chamberIndex}][start_time]"
                           class="input">
                </div>

                <div class="form-group">
                    <label>End Time</label>
                    <input type="time"
                           name="chambers[${chamberIndex}][end_time]"
                           class="input">
                </div>
            </div>

            <div class="form-group">
                <label>Consultation Fee</label>
                <input type="number"
                       name="chambers[${chamberIndex}][fee]"
                       class="input"
                       placeholder="Example: 800"
                       min="0">
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', html);
    chamberIndex++;
}
</script>
@endsection