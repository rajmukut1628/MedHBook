<nav x-data="{ open: false, profileOpen: false }"
     class="sticky top-0 z-50 border-b border-white/10 bg-slate-950/80 backdrop-blur-2xl shadow-[0_20px_80px_rgba(0,0,0,0.45)]">

    @php
        $user = Auth::user();

        $displayId =
            $user->patient_id ??
            $user->doctor_id ??
            $user->admin_id ??
            $user->unique_id ??
            $user->user_code ??
            $user->privacy_key ??
            ('USER-' . str_pad($user->id, 5, '0', STR_PAD_LEFT));

        $roleLabel = strtoupper(str_replace('_', ' ', $user->role));
        $roleText = str_replace('_', ' ', $user->role);
    @endphp

    <style>
        @keyframes navGlow {
            0%, 100% {
                box-shadow:
                    0 0 20px rgba(16, 185, 129, 0.08),
                    0 0 50px rgba(59, 130, 246, 0.05);
            }
            50% {
                box-shadow:
                    0 0 40px rgba(16, 185, 129, 0.20),
                    0 0 85px rgba(34, 211, 238, 0.12);
            }
        }

        @keyframes pulseDot {
            0%, 100% {
                opacity: 1;
                transform: scale(1);
            }
            50% {
                opacity: .55;
                transform: scale(1.28);
            }
        }

        @keyframes floatLogo {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-3px);
            }
        }

        @keyframes idFloat {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-4px);
            }
        }

        @keyframes idBorder {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        .mhb-nav-shell {
            animation: navGlow 6s ease-in-out infinite;
        }

        .mhb-live-dot {
            animation: pulseDot 2s infinite;
        }

        .mhb-logo {
            animation: floatLogo 4s ease-in-out infinite;
        }

        .mhb-id-card {
            animation: idFloat 4.5s ease-in-out infinite;
        }

        .mhb-id-card::before {
            content: "";
            position: absolute;
            inset: -1px;
            border-radius: 1.25rem;
            padding: 1px;
            background: linear-gradient(
                120deg,
                rgba(16,185,129,.75),
                rgba(34,211,238,.45),
                rgba(59,130,246,.35),
                rgba(16,185,129,.75)
            );
            background-size: 250% 250%;
            animation: idBorder 5s ease infinite;
            -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }

        .mhb-shine {
            position: relative;
            overflow: hidden;
        }

        .mhb-shine::after {
            content: "";
            position: absolute;
            top: -20%;
            left: -120%;
            width: 80%;
            height: 140%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255,255,255,0.13),
                transparent
            );
            transform: skewX(-20deg);
            transition: 0.8s;
        }

        .mhb-shine:hover::after {
            left: 150%;
        }
    </style>

    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-20 right-10 w-72 h-72 bg-emerald-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -top-16 left-20 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mhb-nav-shell mt-3 mb-3 rounded-3xl border border-white/10 bg-white/[0.06] backdrop-blur-2xl px-4 sm:px-6">
            <div class="flex items-center justify-between h-20 gap-4">

                <!-- Left Logo Section -->
                <div class="flex items-center min-w-0">

                    <a href="{{ route('dashboard') }}"
                       class="mhb-shine flex items-center gap-4 group">

                        <div class="mhb-logo relative shrink-0">
                            <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-emerald-500/20 to-cyan-500/20 blur-xl"></div>

                            <div class="relative h-14 w-14 rounded-2xl bg-gradient-to-br from-slate-900 to-slate-800 border border-white/10 shadow-[0_20px_40px_rgba(0,0,0,0.35)] flex items-center justify-center group-hover:scale-105 transition duration-300">
                                <x-application-logo class="h-8 w-8 text-white fill-current" />
                            </div>
                        </div>

                        <div class="hidden sm:block min-w-0">
                            <h1 class="text-white text-xl font-black tracking-tight">
                                MedHBook
                            </h1>
                            <p class="text-slate-400 text-xs font-semibold tracking-[0.2em] uppercase">
                                Secure Healthcare Platform
                            </p>
                        </div>
                    </a>
                </div>
                                <!-- Middle Premium User ID Card -->
                <div class="hidden lg:flex flex-1 justify-center px-4">
                    <div class="mhb-id-card mhb-shine relative px-6 py-3 rounded-[20px]
                                bg-gradient-to-r from-emerald-500/10 via-cyan-500/10 to-blue-500/10
                                border border-white/10
                                shadow-[0_18px_55px_rgba(16,185,129,0.14)]
                                hover:shadow-[0_22px_70px_rgba(34,211,238,0.22)]
                                transition-all duration-300">

                        <div class="relative flex items-center gap-4">

                            <div class="flex items-center gap-2">
                                <span class="mhb-live-dot h-2.5 w-2.5 rounded-full bg-emerald-400 shadow-[0_0_18px_rgba(52,211,153,1)]"></span>
                                <span class="text-[10px] font-black tracking-[0.25em] uppercase text-emerald-300">
                                    {{ $roleLabel }}
                                </span>
                            </div>

                            <div class="w-px h-8 bg-white/10"></div>

                            <div class="text-left">
                                <p class="text-[10px] text-slate-400 font-black uppercase tracking-[0.25em]">
                                    Unique ID
                                </p>
                                <p class="text-white text-sm font-black tracking-[0.12em]">
                                    {{ $displayId }}
                                </p>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Right Profile Section -->
                <div class="hidden sm:flex items-center gap-4 shrink-0">

                    <div class="relative" x-data="{ dropdownOpen: false }">
                        <button @click="dropdownOpen = !dropdownOpen"
                                @click.outside="dropdownOpen = false"
                                class="mhb-shine flex items-center gap-3 px-4 py-3 rounded-2xl bg-white/[0.05] border border-white/10 hover:bg-white/[0.09] transition shadow-[0_15px_45px_rgba(0,0,0,0.25)]">

                            <div class="h-11 w-11 rounded-2xl bg-gradient-to-br from-emerald-500 to-cyan-500 flex items-center justify-center text-white font-black shadow-[0_15px_35px_rgba(16,185,129,.25)]">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>

                            <div class="hidden md:block text-left">
                                <p class="text-white text-sm font-black leading-tight max-w-[150px] truncate">
                                    {{ $user->name }}
                                </p>
                                <p class="text-slate-400 text-xs font-bold capitalize">
                                    {{ $roleText }}
                                </p>
                            </div>

                            <svg class="h-4 w-4 text-slate-400 transition"
                                 :class="{ 'rotate-180': dropdownOpen }"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="dropdownOpen"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                             class="absolute right-0 mt-4 w-80 rounded-3xl bg-slate-950/95 backdrop-blur-2xl border border-white/10 shadow-[0_30px_100px_rgba(0,0,0,0.55)] overflow-hidden z-50"
                             style="display: none;">

                            <div class="p-5 bg-gradient-to-br from-emerald-500/10 to-cyan-500/10 border-b border-white/10">
                                <div class="flex items-center gap-3">
                                    <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-cyan-500 flex items-center justify-center text-white font-black">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>

                                    <div class="min-w-0">
                                        <p class="text-white font-black truncate">
                                            {{ $user->name }}
                                        </p>
                                        <p class="text-slate-400 text-xs font-semibold truncate">
                                            {{ $user->email }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-4 rounded-2xl border border-emerald-300/20 bg-black/25 p-4">
                                    <p class="text-[10px] text-emerald-300 font-black uppercase tracking-[0.25em]">
                                        {{ $roleLabel }} ID
                                    </p>
                                    <p class="mt-1 text-white text-base font-black tracking-[0.12em] break-all">
                                        {{ $displayId }}
                                    </p>
                                </div>
                            </div>
                                                        <div class="p-3 space-y-2">
                                <a href="{{ route('profile.edit') }}"
                                   class="flex items-center gap-3 px-4 py-3 rounded-2xl text-slate-300 hover:text-white hover:bg-white/10 transition font-bold">
                                    <span>👤</span>
                                    <span>Profile Settings</span>
                                </a>

                                <a href="{{ route('dashboard') }}"
                                   class="flex items-center gap-3 px-4 py-3 rounded-2xl text-slate-300 hover:text-white hover:bg-white/10 transition font-bold">
                                    <span>🏠</span>
                                    <span>Go to Dashboard</span>
                                </a>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <button type="submit"
                                            class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-red-300 hover:text-red-100 hover:bg-red-500/10 transition font-black">
                                        <span>🚪</span>
                                        <span>Log Out</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile Hamburger -->
                <div class="flex items-center sm:hidden">
                    <button @click="open = !open"
                            class="inline-flex items-center justify-center h-12 w-12 rounded-2xl bg-white/[0.06] border border-white/10 text-white hover:bg-white/[0.12] transition">

                        <svg class="h-6 w-6"
                             stroke="currentColor"
                             fill="none"
                             viewBox="0 0 24 24">

                            <path :class="{ 'hidden': open, 'inline-flex': !open }"
                                  class="inline-flex"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"/>

                            <path :class="{ 'hidden': !open, 'inline-flex': open }"
                                  class="hidden"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

            </div>
        </div>

        <!-- Mobile Menu -->
        <div :class="{ 'block': open, 'hidden': !open }"
             class="hidden sm:hidden pb-4">

            <div class="rounded-3xl border border-white/10 bg-slate-950/95 backdrop-blur-2xl shadow-[0_30px_100px_rgba(0,0,0,0.45)] p-4 space-y-3">

                <div class="px-4 py-4 rounded-2xl bg-gradient-to-r from-emerald-500/10 via-cyan-500/10 to-blue-500/10 border border-emerald-300/20">
                    <div class="flex items-center gap-2">
                        <span class="mhb-live-dot h-2.5 w-2.5 rounded-full bg-emerald-400 shadow-[0_0_18px_rgba(52,211,153,1)]"></span>
                        <span class="text-[10px] font-black tracking-[0.25em] uppercase text-emerald-300">
                            {{ $roleLabel }}
                        </span>
                    </div>

                    <p class="mt-3 text-[10px] text-slate-400 font-black uppercase tracking-[0.25em]">
                        Unique ID
                    </p>

                    <p class="mt-1 text-white text-base font-black tracking-[0.12em] break-all">
                        {{ $displayId }}
                    </p>
                </div>

                <div class="border-t border-white/10 pt-4 mt-4">
                    <div class="px-4 py-3 rounded-2xl bg-white/[0.05] border border-white/10">
                        <div class="text-white font-black">
                            {{ $user->name }}
                        </div>

                        <div class="text-slate-400 text-sm mt-1 break-all">
                            {{ $user->email }}
                        </div>

                        <div class="inline-flex mt-3 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-400/20 text-emerald-200 text-xs font-black capitalize">
                            {{ $roleText }}
                        </div>
                    </div>

                    <a href="{{ route('profile.edit') }}"
                       class="mt-3 flex items-center gap-3 px-4 py-3 rounded-2xl text-slate-200 hover:text-white hover:bg-white/10 transition font-bold">
                        <span>👤</span>
                        <span>Profile Settings</span>
                    </a>

                    <a href="{{ route('dashboard') }}"
                       class="mt-2 flex items-center gap-3 px-4 py-3 rounded-2xl text-slate-200 hover:text-white hover:bg-white/10 transition font-bold">
                        <span>🏠</span>
                        <span>Go to Dashboard</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf

                        <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-red-300 hover:text-red-100 hover:bg-red-500/10 transition font-black">
                            <span>🚪</span>
                            <span>Log Out</span>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</nav>