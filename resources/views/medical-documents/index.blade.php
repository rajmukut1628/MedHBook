<x-app-layout>

<style>
    @keyframes vaultFloat {
        0%,100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-18px) rotate(2deg); }
    }

    @keyframes softReveal {
        from { opacity: 0; transform: translateY(28px) scale(.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    @keyframes borderGlow {
        0%,100% {
            box-shadow:
                0 0 35px rgba(16,185,129,.18),
                inset 0 0 0 1px rgba(255,255,255,.08);
        }
        50% {
            box-shadow:
                0 0 80px rgba(34,211,238,.30),
                inset 0 0 0 1px rgba(45,212,191,.25);
        }
    }

    @keyframes shimmerLine {
        0% { transform: translateX(-120%); }
        100% { transform: translateX(120%); }
    }

    @keyframes countPulse {
        0%,100% { transform: scale(1); }
        50% { transform: scale(1.08); }
    }

    .vault-glass {
        background:
            linear-gradient(135deg, rgba(15,23,42,.88), rgba(15,23,42,.58)),
            radial-gradient(circle at top left, rgba(16,185,129,.16), transparent 35%),
            radial-gradient(circle at bottom right, rgba(34,211,238,.12), transparent 38%);
        border: 1px solid rgba(255,255,255,.12);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
    }

    .vault-card {
        animation: softReveal .7s ease both;
        position: relative;
        overflow: hidden;
    }

    .vault-card::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(120deg, transparent, rgba(255,255,255,.08), transparent);
        transform: translateX(-120%);
        transition: .7s;
    }

    .vault-card:hover::before {
        animation: shimmerLine 1.1s ease;
    }

    .vault-orb {
        animation: vaultFloat 7s ease-in-out infinite;
    }

    .vault-glow {
        animation: borderGlow 4s ease-in-out infinite;
    }

    .category-panel {
        transition: all .4s ease;
    }

    .category-panel:hover {
        transform: translateY(-7px);
        border-color: rgba(45,212,191,.45);
    }

    .doc-section {
        max-height: 0;
        overflow: hidden;
        opacity: 0;
        transform: translateY(-12px);
        transition:
            max-height .65s ease,
            opacity .45s ease,
            transform .45s ease,
            margin-top .45s ease;
    }

    .doc-section.open {
        max-height: 4000px;
        opacity: 1;
        transform: translateY(0);
        margin-top: 26px;
    }

    .arrow-icon {
        transition: transform .35s ease;
    }

    .arrow-icon.rotate {
        transform: rotate(180deg);
    }

    .doc-mini-card {
        transition: all .35s ease;
    }

    .doc-mini-card:hover {
        transform: translateY(-6px) scale(1.015);
        border-color: rgba(52,211,153,.45);
        box-shadow: 0 24px 80px rgba(0,0,0,.25);
    }

    .premium-btn {
        transition: all .25s ease;
    }

    .premium-btn:hover {
        transform: translateY(-2px);
    }

    .premium-btn:active {
        transform: scale(.97);
    }

    .counter-badge {
        animation: countPulse 2.8s ease-in-out infinite;
    }

    .secure-ribbon {
        position: relative;
        overflow: hidden;
    }

    .secure-ribbon::after {
        content: "";
        position: absolute;
        top: 0;
        left: -120%;
        width: 80%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,.13), transparent);
        transform: skewX(-18deg);
    }

    .secure-ribbon:hover::after {
        animation: shimmerLine 1.1s ease;
    }
</style>

<div class="relative min-h-screen overflow-hidden bg-slate-950 px-4 sm:px-6 py-8">

    {{-- Premium Animated Background --}}
    <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950"></div>

    <div class="absolute -top-44 -left-44 w-[560px] h-[560px] rounded-full bg-emerald-500/20 blur-3xl vault-orb"></div>
    <div class="absolute top-24 -right-32 w-[520px] h-[520px] rounded-full bg-cyan-500/20 blur-3xl vault-orb"></div>
    <div class="absolute bottom-0 left-1/3 w-[650px] h-[650px] rounded-full bg-blue-600/14 blur-3xl vault-orb"></div>
    <div class="absolute bottom-32 right-1/4 w-[360px] h-[360px] rounded-full bg-purple-500/10 blur-3xl vault-orb"></div>

    <div class="absolute inset-0 opacity-[.075]"
         style="background-image:
         linear-gradient(rgba(255,255,255,.5) 1px, transparent 1px),
         linear-gradient(90deg, rgba(255,255,255,.5) 1px, transparent 1px);
         background-size: 44px 44px;">
    </div>

    <div class="relative z-10 max-w-7xl mx-auto">

        @php
            $types = [
                'Prescription' => [
                    'icon' => '💊',
                    'gradient' => 'from-emerald-400 via-teal-400 to-cyan-400',
                    'text' => 'Doctor prescription records and medication notes.'
                ],
                'MRI' => [
                    'icon' => '🧠',
                    'gradient' => 'from-purple-400 via-indigo-400 to-blue-400',
                    'text' => 'MRI scan reports and neurological imaging files.'
                ],
                'CT Scan' => [
                    'icon' => '🧬',
                    'gradient' => 'from-cyan-400 via-sky-400 to-blue-500',
                    'text' => 'CT scan documents and diagnostic imaging records.'
                ],
                'X-Ray' => [
                    'icon' => '🩻',
                    'gradient' => 'from-sky-400 via-cyan-400 to-emerald-400',
                    'text' => 'X-Ray films, reports, and related medical documents.'
                ],
                'Blood Report' => [
                    'icon' => '🩸',
                    'gradient' => 'from-red-400 via-rose-400 to-pink-400',
                    'text' => 'Blood test reports and laboratory investigation files.'
                ],
                'Doctor Digital Prescription PDF' => [
                    'icon' => '📄',
                    'gradient' => 'from-amber-400 via-orange-400 to-red-400',
                    'text' => 'Online doctor-generated digital prescription PDFs.'
                ],
                'Other' => [
                    'icon' => '🔐',
                    'gradient' => 'from-slate-300 via-slate-400 to-slate-500',
                    'text' => 'Other private medical files stored in the vault.'
                ],
            ];

            $totalDocs = $documents->count() ?? 0;
        @endphp
                {{-- Header --}}
        <div class="vault-glass vault-glow rounded-[36px] p-6 sm:p-8 lg:p-10 mb-8 text-white vault-card">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">

                <div class="max-w-3xl">
                    <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-emerald-400/10 border border-emerald-300/20 text-emerald-200 text-sm font-black">
                        <span class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-300 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-300"></span>
                        </span>
                        Private Medical Vault
                    </div>

                    <h1 class="mt-5 text-4xl sm:text-5xl lg:text-6xl font-black leading-tight tracking-tight">
                        Medical
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-300 via-cyan-300 to-blue-400">
                            Documents
                        </span>
                    </h1>

                    <p class="mt-4 text-slate-300 text-base sm:text-lg leading-relaxed">
                        Your sensitive medical files are stored in private server storage.
                        They cannot be accessed using direct public URLs. Only logged-in patients can view,
                        download, or delete their own documents.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <span class="px-4 py-2 rounded-2xl bg-white/10 border border-white/10 text-sm font-bold text-slate-200">
                            🔐 Private Storage
                        </span>

                        <span class="px-4 py-2 rounded-2xl bg-white/10 border border-white/10 text-sm font-bold text-slate-200">
                            👤 Owner Only Access
                        </span>

                        <span class="px-4 py-2 rounded-2xl bg-white/10 border border-white/10 text-sm font-bold text-slate-200">
                            🚫 No Public URL
                        </span>
                    </div>

                    {{-- Upload Button --}}
                    <div class="mt-7">
                        <a href="{{ route('medical-documents.create') }}"
                           class="premium-btn secure-ribbon inline-flex items-center justify-center gap-3 px-8 py-4 rounded-2xl bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 text-white font-black shadow-2xl shadow-emerald-500/20 hover:scale-[1.03]">
                            <span class="text-xl">⬆️</span>
                            Upload Document
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 min-w-full sm:min-w-[360px] lg:min-w-[390px]">
                    <div class="rounded-[28px] bg-white/10 border border-white/10 p-5">
                        <p class="text-slate-400 text-xs font-black uppercase tracking-[.2em]">
                            Total Files
                        </p>

                        <h2 class="mt-3 text-5xl font-black text-white counter-badge">
                            {{ $totalDocs }}
                        </h2>

                        <p class="mt-2 text-slate-400 text-sm">
                            Private documents
                        </p>
                    </div>

                    <div class="rounded-[28px] bg-white/10 border border-white/10 p-5">
                        <p class="text-slate-400 text-xs font-black uppercase tracking-[.2em]">
                            Categories
                        </p>

                        <h2 class="mt-3 text-5xl font-black text-cyan-300">
                            {{ count($types) }}
                        </h2>

                        <p class="mt-2 text-slate-400 text-sm">
                            Fixed sections
                        </p>
                    </div>

                    <div class="col-span-2 rounded-[28px] bg-gradient-to-r from-emerald-500/20 via-cyan-500/15 to-blue-500/20 border border-emerald-300/20 p-5">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-emerald-200 text-xs font-black uppercase tracking-[.2em]">
                                    Security Status
                                </p>

                                <h3 class="mt-2 text-2xl font-black text-white">
                                    Protected Vault Active
                                </h3>

                                <p class="mt-1 text-sm text-slate-400">
                                    Files are served through Laravel controller after login verification.
                                </p>
                            </div>

                            <div class="w-16 h-16 rounded-3xl bg-emerald-400/20 border border-emerald-300/20 flex items-center justify-center text-3xl">
                                🛡️
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-8 rounded-[28px] bg-emerald-500/15 border border-emerald-300/30 text-emerald-100 p-5 font-black vault-card">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- Error Message --}}
        @if(session('error'))
            <div class="mb-8 rounded-[28px] bg-red-500/15 border border-red-300/30 text-red-100 p-5 font-black vault-card">
                ⚠️ {{ session('error') }}
            </div>
        @endif

        {{-- Empty Global Message --}}
        @if($totalDocs == 0)
            <div class="vault-glass rounded-[34px] p-8 mb-8 text-center text-white vault-card">
                <div class="mx-auto w-20 h-20 rounded-[28px] bg-white/10 border border-white/10 flex items-center justify-center text-4xl">
                    📂
                </div>

                <h2 class="mt-5 text-3xl font-black">
                    No medical documents found
                </h2>

                <p class="mt-3 text-slate-400 max-w-xl mx-auto">
                    This vault is ready. Uploaded documents will appear here by category.
                    This page only contains View, Download, and Delete actions.
                </p>

                <div class="mt-6">
                    <a href="{{ route('medical-documents.create') }}"
                       class="premium-btn inline-flex items-center justify-center gap-3 px-7 py-3 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-black">
                        Upload Your First Document
                    </a>
                </div>
            </div>
        @endif
                {{-- Category Dashboard --}}
        <div class="space-y-7">

            @foreach($types as $type => $meta)
                @php
                    $docs = $groupedDocuments[$type] ?? collect();
                    $sectionId = 'section-' . \Illuminate\Support\Str::slug($type);
                    $arrowId = 'arrow-' . \Illuminate\Support\Str::slug($type);
                    $delay = $loop->index * 90;
                @endphp

                <div class="vault-glass category-panel rounded-[34px] p-5 sm:p-6 lg:p-7 text-white vault-card"
                     style="animation-delay: {{ $delay }}ms;">

                    {{-- Category Header --}}
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">

                        <div class="flex items-center gap-4 sm:gap-5">
                            <div class="relative">
                                <div class="absolute inset-0 rounded-[28px] bg-gradient-to-br {{ $meta['gradient'] }} blur-xl opacity-40"></div>

                                <div class="relative w-16 h-16 sm:w-20 sm:h-20 rounded-[28px] bg-gradient-to-br {{ $meta['gradient'] }} flex items-center justify-center text-3xl sm:text-4xl shadow-2xl">
                                    {{ $meta['icon'] }}
                                </div>
                            </div>

                            <div>
                                <h2 class="text-2xl sm:text-3xl font-black leading-tight">
                                    {{ $type }}
                                </h2>

                                <p class="text-slate-400 text-sm sm:text-base mt-1 max-w-2xl">
                                    {{ $meta['text'] }}
                                </p>

                                <div class="mt-3 flex flex-wrap gap-2">
                                    <span class="px-3 py-1 rounded-full bg-white/10 border border-white/10 text-xs font-black text-slate-300">
                                        Private Category
                                    </span>

                                    <span class="px-3 py-1 rounded-full bg-emerald-400/10 border border-emerald-300/20 text-xs font-black text-emerald-200">
                                        {{ $docs->count() }} Files
                                    </span>

                                    <span class="px-3 py-1 rounded-full bg-cyan-400/10 border border-cyan-300/20 text-xs font-black text-cyan-200">
                                        Login Required
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between md:justify-end gap-4">
                            <div class="text-right">
                                <p class="text-slate-400 text-xs font-black uppercase tracking-[.18em]">
                                    Total Files
                                </p>

                                <h3 class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-emerald-300 to-cyan-300">
                                    {{ $docs->count() }}
                                </h3>
                            </div>

                            <button type="button"
                                    onclick="toggleDocs('{{ $sectionId }}', '{{ $arrowId }}')"
                                    class="premium-btn group w-14 h-14 rounded-[22px] bg-white/10 border border-white/10 hover:bg-emerald-500/25 hover:border-emerald-300/30 flex items-center justify-center">
                                <span id="{{ $arrowId }}"
                                      class="arrow-icon text-3xl font-black text-white group-hover:text-emerald-200">
                                    ⌄
                                </span>
                            </button>
                        </div>

                    </div>

                    {{-- Documents Expand Section --}}
                    <div id="{{ $sectionId }}" class="doc-section">

                        @if($docs->count())
                            <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-5 lg:gap-6 pt-2">

                                @foreach($docs as $doc)
                                    <div class="doc-mini-card rounded-[30px] bg-slate-950/60 border border-white/10 p-5 sm:p-6">

                                        <div class="flex items-start justify-between gap-4">
                                            <div class="relative">
                                                <div class="absolute inset-0 rounded-3xl bg-gradient-to-br {{ $meta['gradient'] }} blur-lg opacity-40"></div>

                                                <div class="relative w-14 h-14 rounded-3xl bg-gradient-to-br {{ $meta['gradient'] }} flex items-center justify-center text-2xl shadow-xl">
                                                    {{ $meta['icon'] }}
                                                </div>
                                            </div>

                                            <div class="px-3 py-1 rounded-full bg-emerald-400/10 border border-emerald-300/20 text-emerald-200 text-[11px] font-black uppercase tracking-widest">
                                                Private
                                            </div>
                                        </div>

                                        <h3 class="mt-5 text-xl font-black leading-snug text-white line-clamp-2">
                                            {{ $doc->title }}
                                        </h3>

                                        <p class="mt-2 text-sm text-slate-400 break-words">
                                            {{ $doc->original_name ?? 'Private medical document' }}
                                        </p>

                                        <div class="mt-5 grid grid-cols-2 gap-3 text-sm">
                                            <div class="rounded-2xl bg-white/5 border border-white/10 p-3">
                                                <p class="text-slate-500 text-xs font-bold">Doctor</p>
                                                <p class="mt-1 text-slate-200 font-bold truncate">
                                                    {{ $doc->doctor_name ?? 'N/A' }}
                                                </p>
                                            </div>

                                            <div class="rounded-2xl bg-white/5 border border-white/10 p-3">
                                                <p class="text-slate-500 text-xs font-bold">Hospital</p>
                                                <p class="mt-1 text-slate-200 font-bold truncate">
                                                    {{ $doc->hospital_name ?? 'N/A' }}
                                                </p>
                                            </div>
                                        </div>
                                                                                <div class="mt-4 space-y-2 text-sm text-slate-300">
                                            <div class="flex items-center justify-between gap-3 rounded-2xl bg-white/5 border border-white/10 px-4 py-3">
                                                <span class="text-slate-400">📅 Date</span>
                                                <span class="font-bold text-white">
                                                    {{ optional($doc->document_date)->format('d M Y') ?? 'N/A' }}
                                                </span>
                                            </div>

                                            <div class="flex items-center justify-between gap-3 rounded-2xl bg-white/5 border border-white/10 px-4 py-3">
                                                <span class="text-slate-400">📦 Size</span>
                                                <span class="font-bold text-white">
                                                    {{ $doc->pretty_size ?? 'N/A' }}
                                                </span>
                                            </div>

                                            <div class="flex items-center justify-between gap-3 rounded-2xl bg-white/5 border border-white/10 px-4 py-3">
                                                <span class="text-slate-400">🔐 Storage</span>
                                                <span class="font-bold text-emerald-200">
                                                    Private
                                                </span>
                                            </div>
                                        </div>

                                        @if($doc->notes)
                                            <div class="mt-4 rounded-2xl bg-cyan-400/10 border border-cyan-300/20 p-4">
                                                <p class="text-xs font-black uppercase tracking-[.18em] text-cyan-200">
                                                    Notes
                                                </p>

                                                <p class="mt-2 text-sm text-slate-300 leading-relaxed">
                                                    {{ $doc->notes }}
                                                </p>
                                            </div>
                                        @endif

                                        {{-- Only View, Download, Delete --}}
                                        <div class="mt-6 grid grid-cols-3 gap-3">

                                            <a href="{{ route('medical-documents.show', $doc->id) }}"
                                               class="premium-btn text-center rounded-2xl bg-white/10 hover:bg-cyan-500/25 border border-white/10 hover:border-cyan-300/30 px-3 py-3 text-xs sm:text-sm font-black text-cyan-100">
                                                View
                                            </a>

                                            <a href="{{ route('medical-documents.download', $doc->id) }}"
                                               class="premium-btn text-center rounded-2xl bg-blue-500/90 hover:bg-blue-500 px-3 py-3 text-xs sm:text-sm font-black text-white shadow-lg shadow-blue-500/10">
                                                Download
                                            </a>

                                            <form method="POST"
                                                  action="{{ route('medical-documents.destroy', $doc->id) }}"
                                                  onsubmit="return confirm('Are you sure you want to delete this document?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="premium-btn w-full rounded-2xl bg-red-500/90 hover:bg-red-500 px-3 py-3 text-xs sm:text-sm font-black text-white shadow-lg shadow-red-500/10">
                                                    Delete
                                                </button>
                                            </form>

                                        </div>

                                    </div>
                                @endforeach

                            </div>
                        @else
                            <div class="pt-4">
                                <div class="rounded-[30px] bg-slate-950/45 border border-dashed border-white/20 p-8 text-center">
                                    <div class="mx-auto w-16 h-16 rounded-[24px] bg-white/10 border border-white/10 flex items-center justify-center text-4xl">
                                        {{ $meta['icon'] }}
                                    </div>

                                    <h3 class="mt-5 text-2xl font-black text-white">
                                        No {{ $type }} files
                                    </h3>

                                    <p class="mt-2 text-slate-400">
                                        No document found in this section.
                                    </p>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            @endforeach

        </div>
    </div>
</div>

<script>
    function toggleDocs(sectionId, arrowId) {
        const section = document.getElementById(sectionId);
        const arrow = document.getElementById(arrowId);

        if (!section || !arrow) return;

        const isOpen = section.classList.contains('open');

        document.querySelectorAll('.doc-section').forEach(item => {
            item.classList.remove('open');
        });

        document.querySelectorAll('.arrow-icon').forEach(item => {
            item.classList.remove('rotate');
        });

        if (!isOpen) {
            section.classList.add('open');
            arrow.classList.add('rotate');

            setTimeout(() => {
                section.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            }, 220);
        }
    }
</script>

</x-app-layout>