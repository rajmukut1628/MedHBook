<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes mhbBackFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-3px); }
        }

        @keyframes mhbBackGlow {
            0%, 100% {
                box-shadow: 0 0 14px rgba(16,185,129,.22);
            }
            50% {
                box-shadow: 0 0 24px rgba(34,211,238,.32);
            }
        }

        @keyframes mhbPageFade {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .mhb-page-enter {
            animation: mhbPageFade .45s ease both;
        }

        .mhb-back-btn {
            animation: mhbBackFloat 3s ease-in-out infinite, mhbBackGlow 3.8s ease-in-out infinite;
        }

        .mhb-back-btn:hover {
            animation-play-state: paused;
        }
    </style>
</head>

<body class="font-sans antialiased bg-slate-950 text-slate-100">
    <div class="relative min-h-screen overflow-x-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-black">

        <div class="pointer-events-none fixed inset-0 -z-10">
            <div class="absolute -top-32 -left-32 w-96 h-96 bg-emerald-500/15 rounded-full blur-3xl"></div>
            <div class="absolute top-40 -right-32 w-96 h-96 bg-cyan-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-1/3 w-[520px] h-[520px] bg-blue-500/10 rounded-full blur-3xl"></div>
        </div>

        @include('layouts.navigation')

        @isset($header)
            <header class="relative z-20 border-b border-white/10 bg-slate-950/70 backdrop-blur-2xl shadow-[0_18px_60px_rgba(0,0,0,.35)]">
                <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8 text-white">
                    {{ $header }}
                </div>
            </header>
        @endisset

        @if (!request()->is('/'))
            <div class="fixed left-3 bottom-4 z-[9999]">
                <button
                    type="button"
                    onclick="mhbGoBackSmart()"
                    class="mhb-back-btn group flex items-center gap-2 px-2 py-2 rounded-xl
                           bg-slate-950/80 border border-white/15 text-white backdrop-blur-xl
                           shadow-[0_0_18px_rgba(16,185,129,0.20)]
                           hover:border-emerald-300/50 hover:bg-slate-900/90
                           hover:-translate-y-0.5 transition-all duration-300">

                    <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-400 via-teal-400 to-cyan-500
                                 flex items-center justify-center text-base font-black
                                 shadow-[0_0_16px_rgba(16,185,129,0.35)]
                                 group-hover:scale-105 transition duration-300">
                        ←
                    </span>

                    <span class="hidden sm:block text-left pr-1">
                        <span class="block text-[12px] font-black leading-none">
                            Go Back
                        </span>

                        <span class="block text-[9px] text-emerald-300 font-bold mt-0.5">
                            Previous Page
                        </span>
                    </span>
                </button>
            </div>

            <script>
                function mhbGoBackSmart() {
                    if (window.history.length > 1) {
                        window.history.back();
                    } else {
                        window.location.href = "{{ url('/') }}";
                    }
                }
            </script>
        @endif

        <main class="relative z-10 mhb-page-enter">
            @isset($slot)
                {{ $slot }}
            @else
                @yield('content')
            @endisset
        </main>
    </div>
</body>
</html>