<x-app-layout>

<style>
    :root{
        --bg1:#020617;
        --bg2:#08111f;
        --glass:rgba(255,255,255,.075);
        --glass2:rgba(255,255,255,.11);
        --line:rgba(255,255,255,.13);
        --text:#f8fafc;
        --muted:#cbd5e1;
        --cyan:#22d3ee;
        --blue:#3b82f6;
        --emerald:#10b981;
        --violet:#8b5cf6;
        --amber:#fbbf24;
    }

    body{
        background:
            radial-gradient(circle at top left, rgba(34,211,238,.18), transparent 34%),
            radial-gradient(circle at 85% 10%, rgba(139,92,246,.18), transparent 30%),
            radial-gradient(circle at bottom right, rgba(16,185,129,.12), transparent 32%),
            linear-gradient(135deg,var(--bg1),var(--bg2),#020617);
        min-height:100vh;
    }

    .mhb-ai-page{
        max-width:1440px;
        margin:0 auto;
        padding:34px;
        color:var(--text);
        position:relative;
        overflow:hidden;
    }

    .mhb-ai-page::before{
        content:"";
        position:absolute;
        inset:0;
        background-image:
            linear-gradient(rgba(255,255,255,.035) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.035) 1px, transparent 1px);
        background-size:54px 54px;
        mask-image:linear-gradient(to bottom, black, transparent 80%);
        pointer-events:none;
    }

    .hero{
        position:relative;
        overflow:hidden;
        border-radius:38px;
        padding:38px;
        border:1px solid var(--line);
        background:
            linear-gradient(135deg, rgba(59,130,246,.23), rgba(16,185,129,.16), rgba(139,92,246,.15)),
            rgba(255,255,255,.075);
        box-shadow:
            0 35px 95px rgba(0,0,0,.42),
            inset 0 1px 0 rgba(255,255,255,.16);
        backdrop-filter:blur(22px);
    }

    .hero::after{
        content:"";
        position:absolute;
        width:420px;
        height:420px;
        right:-150px;
        top:-150px;
        border-radius:50%;
        background:radial-gradient(circle, rgba(34,211,238,.35), transparent 65%);
        filter:blur(4px);
        animation:floatGlow 7s ease-in-out infinite alternate;
    }

    @keyframes floatGlow{
        from{transform:translate(-10px,10px) scale(1);}
        to{transform:translate(20px,-16px) scale(1.08);}
    }

    .hero-inner{
        position:relative;
        z-index:2;
        display:grid;
        grid-template-columns:1.25fr .75fr;
        gap:28px;
        align-items:center;
    }

    .eyebrow{
        display:inline-flex;
        align-items:center;
        gap:10px;
        padding:9px 14px;
        border-radius:999px;
        background:rgba(34,211,238,.12);
        border:1px solid rgba(34,211,238,.24);
        color:#a5f3fc;
        font-weight:900;
        font-size:13px;
        letter-spacing:.3px;
        margin-bottom:18px;
    }

    .pulse-dot{
        width:9px;
        height:9px;
        border-radius:999px;
        background:#22c55e;
        box-shadow:0 0 0 0 rgba(34,197,94,.7);
        animation:pulse 1.8s infinite;
    }

    @keyframes pulse{
        0%{box-shadow:0 0 0 0 rgba(34,197,94,.7);}
        70%{box-shadow:0 0 0 12px rgba(34,197,94,0);}
        100%{box-shadow:0 0 0 0 rgba(34,197,94,0);}
    }

    .hero h1{
        margin:0;
        font-size:clamp(34px,5vw,62px);
        line-height:1;
        font-weight:1000;
        letter-spacing:-1.8px;
    }

    .gradient-text{
        background:linear-gradient(90deg,#67e8f9,#93c5fd,#86efac);
        -webkit-background-clip:text;
        background-clip:text;
        color:transparent;
    }

    .hero p{
        max-width:760px;
        margin:16px 0 0;
        color:#dbeafe;
        font-size:16px;
        line-height:1.75;
    }

    .hero-stats{
        display:grid;
        grid-template-columns:repeat(3,1fr);
        gap:12px;
        margin-top:24px;
    }

    .stat-card{
        padding:15px;
        border-radius:22px;
        border:1px solid rgba(255,255,255,.13);
        background:rgba(255,255,255,.08);
        box-shadow:inset 0 1px 0 rgba(255,255,255,.09);
    }

    .stat-card strong{
        display:block;
        font-size:22px;
        font-weight:1000;
    }

    .stat-card span{
        display:block;
        color:#cbd5e1;
        font-size:12px;
        margin-top:4px;
        font-weight:700;
    }

    .hero-panel{
        border-radius:32px;
        padding:24px;
        border:1px solid rgba(255,255,255,.13);
        background:rgba(2,6,23,.32);
        box-shadow:inset 0 1px 0 rgba(255,255,255,.12);
    }

    .hero-panel-title{
        display:flex;
        align-items:center;
        gap:12px;
        font-weight:1000;
        font-size:18px;
        margin-bottom:16px;
    }

    .ai-orb{
        width:52px;
        height:52px;
        border-radius:20px;
        display:grid;
        place-items:center;
        font-size:26px;
        background:linear-gradient(135deg,rgba(34,211,238,.28),rgba(139,92,246,.22));
        border:1px solid rgba(255,255,255,.16);
        box-shadow:0 18px 42px rgba(34,211,238,.12);
    }

    .quick-tags{
        display:flex;
        flex-wrap:wrap;
        gap:10px;
    }

    .quick-tags span{
        padding:9px 12px;
        border-radius:999px;
        background:rgba(255,255,255,.09);
        border:1px solid rgba(255,255,255,.12);
        color:#e0f2fe;
        font-size:12px;
        font-weight:800;
    }

    .search-shell{
        position:relative;
        z-index:4;
        margin-top:-18px;
        padding:22px;
        border-radius:32px;
        border:1px solid rgba(255,255,255,.14);
        background:rgba(15,23,42,.72);
        backdrop-filter:blur(20px);
        box-shadow:0 24px 70px rgba(0,0,0,.35);
    }

    .search-form{
        display:grid;
        grid-template-columns:1fr auto auto;
        gap:12px;
        align-items:center;
    }

    .input-wrap{
        position:relative;
    }

    .input-icon{
        position:absolute;
        left:18px;
        top:50%;
        transform:translateY(-50%);
        font-size:18px;
        opacity:.85;
    }

    .search-input{
        width:100%;
        padding:17px 18px 17px 52px;
        border-radius:22px;
        border:1px solid rgba(255,255,255,.13);
        background:rgba(255,255,255,.08);
        color:white;
        outline:none;
        font-size:15px;
        font-weight:700;
        transition:.25s;
    }

    .search-input:focus{
        border-color:rgba(34,211,238,.55);
        box-shadow:0 0 0 5px rgba(34,211,238,.10);
        background:rgba(255,255,255,.11);
    }

    .search-input::placeholder{
        color:#94a3b8;
        font-weight:600;
    }

    .search-btn,
    .reset-btn{
        border:none;
        border-radius:22px;
        padding:17px 22px;
        font-weight:1000;
        cursor:pointer;
        text-decoration:none;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        gap:9px;
        min-height:56px;
        transition:.22s;
        white-space:nowrap;
    }

    .search-btn{
        color:white;
        background:linear-gradient(135deg,#06b6d4,#2563eb,#7c3aed);
        box-shadow:0 18px 40px rgba(37,99,235,.24);
    }

    .search-btn:hover{
        transform:translateY(-2px);
        box-shadow:0 24px 55px rgba(37,99,235,.32);
    }

    .reset-btn{
        color:#e2e8f0;
        background:rgba(255,255,255,.08);
        border:1px solid rgba(255,255,255,.13);
    }

    .reset-btn:hover{
        background:rgba(255,255,255,.13);
    }

    .tip-row{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:14px;
        flex-wrap:wrap;
        margin-top:14px;
        color:#cbd5e1;
        font-size:13px;
    }

    .tip-row strong{
        color:#a7f3d0;
    }

    .sample-chips{
        display:flex;
        flex-wrap:wrap;
        gap:8px;
    }

    .sample-chips button{
        border:none;
        cursor:pointer;
        padding:8px 11px;
        border-radius:999px;
        background:rgba(34,211,238,.10);
        color:#cffafe;
        border:1px solid rgba(34,211,238,.18);
        font-weight:800;
        font-size:12px;
    }

    .ai-box{
        margin-top:22px;
        border-radius:30px;
        overflow:hidden;
        border:1px solid rgba(34,211,238,.23);
        background:
            linear-gradient(135deg,rgba(34,211,238,.14),rgba(59,130,246,.10),rgba(16,185,129,.08)),
            rgba(255,255,255,.06);
        box-shadow:0 22px 60px rgba(0,0,0,.28);
    }

    .ai-box-inner{
        padding:22px;
        display:grid;
        grid-template-columns:auto 1fr auto;
        gap:18px;
        align-items:start;
    }

    .ai-icon{
        width:58px;
        height:58px;
        border-radius:22px;
        display:grid;
        place-items:center;
        font-size:29px;
        background:rgba(34,211,238,.14);
        border:1px solid rgba(34,211,238,.24);
        box-shadow:0 16px 35px rgba(34,211,238,.10);
    }

    .ai-box h3{
        margin:0;
        font-size:20px;
        font-weight:1000;
    }

    .ai-specialty{
        color:#67e8f9;
    }

    .ai-box p{
        margin:7px 0 0;
        color:#cbd5e1;
        line-height:1.6;
        font-size:14px;
    }

    .confidence{
        text-align:right;
        min-width:120px;
    }

    .confidence strong{
        font-size:28px;
        font-weight:1000;
        display:block;
        color:#86efac;
    }

    .confidence span{
        color:#a7f3d0;
        font-size:12px;
        font-weight:900;
    }

    .warning-note{
        border-top:1px solid rgba(255,255,255,.10);
        padding:13px 22px;
        color:#fde68a;
        font-size:12px;
        font-weight:700;
        background:rgba(251,191,36,.06);
    }
        .section-head{
        position:relative;
        z-index:2;
        margin-top:28px;
        margin-bottom:18px;
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:18px;
        flex-wrap:wrap;
    }

    .section-head h2{
        margin:0;
        font-size:28px;
        font-weight:1000;
        letter-spacing:-.5px;
    }

    .section-head p{
        margin:7px 0 0;
        color:#cbd5e1;
        font-size:14px;
    }

    .showing-count{
        padding:11px 16px;
        border-radius:999px;
        background:rgba(255,255,255,.08);
        border:1px solid rgba(255,255,255,.13);
        color:white;
        font-weight:1000;
        font-size:13px;
    }

    .doctor-grid{
        position:relative;
        z-index:2;
        display:grid;
        grid-template-columns:repeat(3,1fr);
        gap:22px;
    }

    .doctor-card{
        position:relative;
        overflow:hidden;
        min-height:100%;
        border-radius:32px;
        padding:24px;
        color:white;
        text-decoration:none;
        border:1px solid rgba(255,255,255,.13);
        background:
            radial-gradient(circle at top right, rgba(34,211,238,.13), transparent 32%),
            rgba(255,255,255,.075);
        box-shadow:0 20px 60px rgba(0,0,0,.32);
        backdrop-filter:blur(18px);
        transition:.25s ease;
        display:block;
    }

    .doctor-card:hover{
        transform:translateY(-7px);
        border-color:rgba(34,211,238,.45);
        box-shadow:0 30px 80px rgba(0,0,0,.42);
    }

    .doctor-top{
        display:flex;
        gap:16px;
        align-items:center;
    }

    .avatar-img,
    .avatar{
        width:76px;
        height:76px;
        border-radius:25px;
        flex:0 0 auto;
    }

    .avatar-img{
        object-fit:cover;
        border:3px solid rgba(255,255,255,.28);
    }

    .avatar{
        display:grid;
        place-items:center;
        font-size:34px;
        background:linear-gradient(135deg,#2563eb,#22d3ee,#10b981);
        box-shadow:0 18px 40px rgba(34,211,238,.18);
    }

    .doctor-name{
        margin:0;
        font-size:21px;
        font-weight:1000;
        line-height:1.15;
    }

    .specialty-badge{
        display:inline-flex;
        margin-top:9px;
        padding:7px 11px;
        border-radius:999px;
        background:rgba(16,185,129,.13);
        color:#86efac;
        border:1px solid rgba(16,185,129,.22);
        font-size:12px;
        font-weight:1000;
    }

    .doctor-info{
        margin-top:18px;
        display:grid;
        gap:10px;
        color:#dbeafe;
        font-size:13px;
    }

    .info-line{
        display:flex;
        gap:10px;
        align-items:flex-start;
        padding:10px 12px;
        border-radius:17px;
        background:rgba(255,255,255,.055);
        border:1px solid rgba(255,255,255,.08);
    }

    .info-line strong{
        color:white;
        font-weight:1000;
    }

    .doctor-actions{
        margin-top:20px;
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:10px;
    }

    .mini-btn{
        border-radius:17px;
        padding:12px;
        text-align:center;
        font-size:13px;
        font-weight:1000;
    }

    .view-btn{
        background:rgba(59,130,246,.18);
        color:#bfdbfe;
        border:1px solid rgba(59,130,246,.24);
    }

    .book-btn{
        background:linear-gradient(135deg,#059669,#10b981);
        color:white;
        box-shadow:0 15px 35px rgba(16,185,129,.18);
    }

    .empty{
        position:relative;
        z-index:2;
        grid-column:1/-1;
        text-align:center;
        padding:70px 22px;
        border-radius:34px;
        background:rgba(255,255,255,.065);
        border:1px solid rgba(255,255,255,.13);
        box-shadow:0 20px 60px rgba(0,0,0,.28);
    }

    .empty-icon{
        width:82px;
        height:82px;
        margin:0 auto 18px;
        border-radius:28px;
        display:grid;
        place-items:center;
        font-size:42px;
        background:rgba(34,211,238,.11);
        border:1px solid rgba(34,211,238,.20);
    }

    .empty h2{
        margin:0;
        font-size:26px;
        font-weight:1000;
    }

    .empty p{
        color:#cbd5e1;
        margin-top:10px;
    }

    @media(max-width:1100px){
        .hero-inner{
            grid-template-columns:1fr;
        }

        .doctor-grid{
            grid-template-columns:repeat(2,1fr);
        }
    }

    @media(max-width:760px){
        .mhb-ai-page{
            padding:18px;
        }

        .hero{
            padding:25px;
            border-radius:30px;
        }

        .hero-stats{
            grid-template-columns:1fr;
        }

        .search-form{
            grid-template-columns:1fr;
        }

        .ai-box-inner{
            grid-template-columns:1fr;
        }

        .confidence{
            text-align:left;
        }

        .doctor-grid{
            grid-template-columns:1fr;
        }

        .doctor-actions{
            grid-template-columns:1fr;
        }
    }
</style>

@php
    $visibleDoctors = collect($doctors ?? [])->take(10);
    $totalVisible = $visibleDoctors->count();
@endphp

<div class="mhb-ai-page">

    <div class="hero">
        <div class="hero-inner">
            <div>
                <div class="eyebrow">
                    <span class="pulse-dot"></span>
                    Real AI Powered Doctor Matching
                </div>

                <h1>
                    Find the Right
                    <span class="gradient-text">Doctor Faster</span>
                </h1>

                <p>
                    Search by symptoms, Bangla words, Banglish, specialist name, doctor name,
                    or chamber location. MedHBook AI will suggest a suitable specialty and show
                    relevant approved doctors.
                </p>

                <div class="hero-stats">
                    <div class="stat-card">
                        <strong>{{ $totalVisible }}</strong>
                        <span>Doctors Showing</span>
                    </div>

                    <div class="stat-card">
                        <strong>AI</strong>
                        <span>Specialty Match</span>
                    </div>

                    <div class="stat-card">
                        <strong>10</strong>
                        <span>Max Suggestions</span>
                    </div>
                </div>
            </div>

            <div class="hero-panel">
                <div class="hero-panel-title">
                    <div class="ai-orb">🤖</div>
                    <div>
                        Smart Search Examples
                    </div>
                </div>

                <div class="quick-tags">
                    <span>বুক ব্যথা</span>
                    <span>মাথা ব্যথা</span>
                    <span>skin allergy</span>
                    <span>baby fever</span>
                    <span>heart pain</span>
                    <span>eye problem</span>
                    <span>gastric</span>
                    <span>tooth pain</span>
                </div>
            </div>
        </div>
    </div>

    <div class="search-shell">
        <form method="GET" action="{{ route('find.doctors') }}" class="search-form">
            <div class="input-wrap">
                <span class="input-icon">🔎</span>

                <input
                    id="doctorSearchInput"
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="search-input"
                    placeholder="Search: বুক ব্যথা / fever / cardiology / eye / skin / Rahim"
                >
            </div>

            <button type="submit" class="search-btn">
                🤖 AI Search
            </button>

            <a href="{{ route('find.doctors') }}" class="reset-btn">
                Reset
            </a>
        </form>

        <div class="tip-row">
            <div>
                <strong>Tip:</strong>
                Write symptoms naturally. Example: “amar buk betha”, “মাথা ঘুরে”, “baby fever”.
            </div>

            <div class="sample-chips">
                <button type="button" onclick="setDoctorSearch('বুক ব্যথা')">বুক ব্যথা</button>
                <button type="button" onclick="setDoctorSearch('matha betha')">matha betha</button>
                <button type="button" onclick="setDoctorSearch('skin allergy')">skin allergy</button>
                <button type="button" onclick="setDoctorSearch('baby fever')">baby fever</button>
            </div>
        </div>

        @if(!empty($aiSuggestion))
            <div class="ai-box">
                <div class="ai-box-inner">
                    <div class="ai-icon">🧠</div>

                    <div>
                        <h3>
                            AI Suggested Specialty:
                            <span class="ai-specialty">
                                {{ $aiSuggestion['specialty'] ?? 'Medicine' }}
                            </span>
                        </h3>

                        <p>
                            {{ $aiSuggestion['reason'] ?? 'AI matched your search with a suitable specialty.' }}
                        </p>

                        <p>
                            Source:
                            <strong>
                                {{ ($aiSuggestion['source'] ?? 'ai') === 'ai' ? 'Gemini AI' : 'Fallback Keyword Match' }}
                            </strong>
                        </p>
                    </div>

                    <div class="confidence">
                        <strong>{{ $aiSuggestion['confidence'] ?? 70 }}%</strong>
                        <span>Confidence</span>
                    </div>
                </div>

                <div class="warning-note">
                    ⚠️ This is not a medical diagnosis. For emergency symptoms, contact the nearest hospital immediately.
                </div>
            </div>
        @endif
    </div>

    <div class="section-head">
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
                    Showing top relevant approved doctors based on your AI search.
                @else
                    Showing maximum 10 approved doctors as suggestions.
                @endif
            </p>
        </div>

        <div class="showing-count">
            Showing: {{ $totalVisible }} / 10
        </div>
    </div>

    <div class="doctor-grid">
        @forelse($visibleDoctors as $doctor)
            <a href="{{ route('doctor.public.profile', $doctor->id) }}" class="doctor-card">

                <div class="doctor-top">
                    @if($doctor->profile_photo)
                        <img
                            src="{{ route('private.file.show', ['path' => $doctor->profile_photo]) }}"
                            class="avatar-img"
                            alt="{{ $doctor->name }}"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='grid';"
                        >

                        <div class="avatar" style="display:none;">👨‍⚕️</div>
                    @else
                        <div class="avatar">👨‍⚕️</div>
                    @endif

                    <div>
                        <h2 class="doctor-name">
                            {{ $doctor->name }}
                        </h2>

                        <span class="specialty-badge">
                            {{ $doctor->specialist ?? $doctor->specialization ?? 'Specialist' }}
                        </span>
                    </div>
                </div>

                <div class="doctor-info">
                    <div class="info-line">
                        <span>📞</span>
                        <div>
                            <strong>Phone:</strong>
                            {{ $doctor->phone ?? 'N/A' }}
                        </div>
                    </div>

                    @php
                        $chambers = $doctor->display_chambers ?? [];
                        $firstChamber = $chambers[0] ?? null;
                    @endphp

                    @if($firstChamber)
                        <div class="info-line">
                            <span>🏥</span>
                            <div>
                                <strong>Chamber:</strong>
                                {{ \Illuminate\Support\Str::limit($firstChamber['address'] ?? 'N/A', 48) }}
                            </div>
                        </div>

                        <div class="info-line">
                            <span>📅</span>
                            <div>
                                <strong>Days:</strong>
                                {{ !empty($firstChamber['working_days']) ? implode(', ', $firstChamber['working_days']) : 'Not Added' }}
                            </div>
                        </div>

                        <div class="info-line">
                            <span>⏰</span>
                            <div>
                                <strong>Time:</strong>
                                {{ $firstChamber['start_time'] ?? '--' }} - {{ $firstChamber['end_time'] ?? '--' }}
                            </div>
                        </div>

                        <div class="info-line">
                            <span>💳</span>
                            <div>
                                <strong>Fee:</strong>
                                ৳ {{ $firstChamber['fee'] ?? 0 }}
                            </div>
                        </div>
                    @else
                        <div class="info-line">
                            <span>🏥</span>
                            <div><strong>Chamber:</strong> N/A</div>
                        </div>

                        <div class="info-line">
                            <span>📅</span>
                            <div><strong>Days:</strong> Not Added</div>
                        </div>

                        <div class="info-line">
                            <span>⏰</span>
                            <div><strong>Time:</strong> -- - --</div>
                        </div>

                        <div class="info-line">
                            <span>💳</span>
                            <div><strong>Fee:</strong> ৳ 0</div>
                        </div>
                    @endif

                    <div class="info-line">
                        <span>⭐</span>
                        <div>
                            <strong>Experience:</strong>
                            {{ $doctor->experience ?? 0 }} Years
                        </div>
                    </div>
                </div>

                <div class="doctor-actions">
                    <span class="mini-btn view-btn">
                        View Profile
                    </span>

                    <span class="mini-btn book-btn">
                        Book Now
                    </span>
                </div>
            </a>
        @empty
            <div class="empty">
                <div class="empty-icon">🔍</div>
                <h2>No doctors found</h2>
                <p>
                    Try another keyword like fever, eye, বুক ব্যথা, skin, baby fever, or cardiology.
                </p>
            </div>
        @endforelse
    </div>

</div>

<script>
    function setDoctorSearch(value){
        const input = document.getElementById('doctorSearchInput');

        if(input){
            input.value = value;
            input.focus();
        }
    }
</script>

</x-app-layout>