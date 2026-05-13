<x-app-layout>

<style>
body{
    background:linear-gradient(135deg,#020617,#0f172a,#111827);
}
.wrap{
    max-width:1400px;
    margin:auto;
    padding:35px;
}
.top{
    background:linear-gradient(135deg,#2563eb,#0f766e,#064e3b);
    padding:38px;
    border-radius:30px;
    color:white;
    box-shadow:0 25px 60px rgba(0,0,0,.25);
    margin-bottom:28px;
}
.top h1{
    font-size:42px;
    font-weight:900;
    margin:0;
}
.top p{
    margin-top:10px;
    opacity:.9;
}
.search-box{
    margin-top:24px;
    display:flex;
    gap:12px;
    flex-wrap:wrap;
}
.search-input{
    flex:1;
    min-width:260px;
    padding:15px 18px;
    border:none;
    outline:none;
    border-radius:16px;
    background:rgba(255,255,255,.12);
    color:white;
    font-size:15px;
}
.search-input::placeholder{
    color:#cbd5e1;
}
.search-btn{
    padding:15px 22px;
    border:none;
    border-radius:16px;
    background:#10b981;
    color:white;
    font-weight:800;
    cursor:pointer;
}
.reset-btn{
    padding:15px 22px;
    border-radius:16px;
    background:rgba(255,255,255,.12);
    color:white;
    text-decoration:none;
    font-weight:800;
}
.tip{
    margin-top:12px;
    font-size:13px;
    color:#d1fae5;
}
.suggestion-head{
    margin-bottom:20px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:16px;
    flex-wrap:wrap;
}
.suggestion-head h2{
    color:white;
    font-size:26px;
    font-weight:900;
    margin:0;
}
.suggestion-head p{
    color:#cbd5e1;
    margin-top:6px;
    font-size:14px;
}
.showing-count{
    padding:10px 16px;
    border-radius:999px;
    background:rgba(255,255,255,.10);
    border:1px solid rgba(255,255,255,.12);
    color:white;
    font-weight:800;
    font-size:14px;
}
.grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:22px;
}
.card-link{
    text-decoration:none;
}
.doctor-card{
    background:rgba(255,255,255,.08);
    border:1px solid rgba(255,255,255,.12);
    border-radius:28px;
    padding:26px;
    color:white;
    box-shadow:0 15px 40px rgba(0,0,0,.22);
    transition:.25s;
    height:100%;
}
.doctor-card:hover{
    transform:translateY(-6px);
    border-color:rgba(59,130,246,.55);
}
.avatar-img{
    width:70px;
    height:70px;
    border-radius:24px;
    object-fit:cover;
    margin-bottom:18px;
    border:3px solid rgba(255,255,255,.4);
}
.avatar{
    width:70px;
    height:70px;
    border-radius:24px;
    background:linear-gradient(135deg,#2563eb,#38bdf8);
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:34px;
    margin-bottom:18px;
}
.doctor-card h2{
    font-size:23px;
    font-weight:900;
    margin:0;
}
.badge{
    display:inline-block;
    margin-top:10px;
    padding:7px 13px;
    border-radius:999px;
    background:rgba(34,197,94,.15);
    color:#86efac;
    font-size:13px;
    font-weight:700;
}
.info{
        margin-top:16px;
    color:#cbd5e1;
    font-size:14px;
    line-height:1.8;
}
.actions{
    display:flex;
    gap:10px;
    margin-top:22px;
    flex-wrap:wrap;
}
.btn{
    padding:11px 15px;
    border-radius:14px;
    text-decoration:none;
    font-weight:800;
    font-size:14px;
}
.book{
    background:#059669;
    color:white;
}
.view{
    background:#2563eb;
    color:white;
}
.empty{
    grid-column:1/-1;
    text-align:center;
    color:#cbd5e1;
    padding:70px;
    background:rgba(255,255,255,.06);
    border-radius:28px;
}
@media(max-width:900px){
    .grid{
        grid-template-columns:1fr;
    }
    .wrap{
        padding:20px;
    }
    .top h1{
        font-size:32px;
    }
}
</style>

@php
    /*
        Blade side safety:
        Controller theke jodi beshi doctor ase,
        ei page maximum 10 jon doctor show korbe.
    */
    $visibleDoctors = collect($doctors)->take(10);
@endphp

<div class="wrap">

    <div class="top">
        <h1>Find Doctors</h1>
        <p>Search doctor by name, specialist, symptom, Bangla symptom or chamber</p>

        <form method="GET" action="{{ route('find.doctors') }}" class="search-box">

            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   class="search-input"
                   placeholder="Search: বুক ব্যথা / fever / card / eye / skin / Rahim">

            <button type="submit" class="search-btn">
                Search
            </button>

            <a href="{{ route('find.doctors') }}" class="reset-btn">
                Reset
            </a>

        </form>

        <div class="tip">
            Example: বুক ব্যথা, জ্বর, মাথা ব্যথা, heart, cardio, child, eye, skin
        </div>
    </div>

    <div class="suggestion-head">
        <div>
            <h2>
                @if(request('search'))
                    Search Results
                @else
                    Suggested Doctors
                @endif
            </h2>

            <p>
                @if(request('search'))
                    Showing top 10 relevant doctors based on your search.
                @else
                    Showing maximum 10 doctors as suggestions.
                @endif
            </p>
        </div>

        <div class="showing-count">
            Showing: {{ $visibleDoctors->count() }} / 10
        </div>
    </div>

    <div class="grid">

        @forelse($visibleDoctors as $doctor)

            <a href="{{ route('doctor.public.profile', $doctor->id) }}" class="card-link">

                <div class="doctor-card">

                    @if($doctor->profile_photo)
                        <img src="{{ asset('storage/' . $doctor->profile_photo) }}"
                             class="avatar-img"
                             alt="{{ $doctor->name }}">
                    @else
                        <div class="avatar">👨‍⚕️</div>
                    @endif

                    <h2>{{ $doctor->name }}</h2>

                    <span class="badge">
                        {{ $doctor->specialist ?? $doctor->specialization ?? 'Specialist' }}
                    </span>

                    <div class="info">
                        <div>
                            <strong>Phone:</strong>
                            {{ $doctor->phone ?? 'N/A' }}
                        </div>

                        @php
                            $chambers = $doctor->display_chambers ?? [];
                            $firstChamber = $chambers[0] ?? null;
                        @endphp

                        @if($firstChamber)
                            <div>
                                <strong>Chamber:</strong>
                                {{ \Illuminate\Support\Str::limit($firstChamber['address'] ?? 'N/A', 40) }}
                            </div>

                            <div>
                                <strong>Days:</strong>
                                {{ !empty($firstChamber['working_days']) ? implode(', ', $firstChamber['working_days']) : 'Not Added' }}
                            </div>

                            <div>
                                <strong>Time:</strong>
                                {{ $firstChamber['start_time'] ?? '--' }} - {{ $firstChamber['end_time'] ?? '--' }}
                            </div>

                            <div>
                                <strong>Fee:</strong>
                                ৳ {{ $firstChamber['fee'] ?? 0 }}
                            </div>
                        @else
                            <div><strong>Chamber:</strong> N/A</div>
                            <div><strong>Days:</strong> Not Added</div>
                            <div><strong>Time:</strong> -- - --</div>
                            <div><strong>Fee:</strong> ৳ 0</div>
                        @endif

                        <div>
                            <strong>Experience:</strong>
                            {{ $doctor->experience ?? 0 }} Years
                        </div>
                    </div>

                    <div class="actions">

                        <span class="btn view">
                            View Profile
                        </span>

                        <span class="btn book">
                            Book Now
                        </span>

                    </div>

                </div>

            </a>

        @empty

            <div class="empty">
                <h2>No doctors found</h2>
                <p>Try another keyword like fever / eye / বুক ব্যথা / skin</p>
            </div>

        @endforelse

    </div>

</div>

</x-app-layout>