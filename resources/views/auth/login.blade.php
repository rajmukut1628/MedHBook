<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MedHBook Login</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html, body {
            margin: 0;
            width: 100%;
            min-height: 100%;
            overflow-x: hidden;
            background: #020617;
        }

        @keyframes float {
            0%,100% { transform: translateY(0); }
            50% { transform: translateY(-18px); }
        }

        @keyframes glow {
            0%,100% { box-shadow: 0 0 30px rgba(45,212,191,.25); }
            50% { box-shadow: 0 0 70px rgba(16,185,129,.55); }
        }

        @keyframes shine {
            0% { left: -80%; }
            100% { left: 130%; }
        }

        .glass {
            background: rgba(15, 23, 42, .78);
            border: 1px solid rgba(255,255,255,.12);
            backdrop-filter: blur(24px);
        }

        .float { animation: float 5s ease-in-out infinite; }
        .glow { animation: glow 3.5s ease-in-out infinite; }

        .shine {
            position: relative;
            overflow: hidden;
        }

        .shine::before {
            content: "";
            position: absolute;
            top: 0;
            left: -80%;
            width: 45%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.45), transparent);
            animation: shine 3s infinite;
        }
    </style>
</head>

<body>
<div class="relative min-h-screen w-full flex items-center justify-center px-6 py-10 overflow-hidden">

    <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950"></div>
    <div class="absolute -top-40 -left-40 w-[520px] h-[520px] rounded-full bg-emerald-500/25 blur-3xl float"></div>
    <div class="absolute top-10 right-10 w-[520px] h-[520px] rounded-full bg-cyan-500/20 blur-3xl float"></div>
    <div class="absolute bottom-0 left-1/2 w-[600px] h-[600px] rounded-full bg-blue-600/15 blur-3xl float"></div>

    <div class="absolute inset-0 opacity-[0.07]"
         style="background-image:linear-gradient(rgba(255,255,255,.5) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.5) 1px,transparent 1px);background-size:45px 45px;">
    </div>

    <div class="relative z-10 w-full max-w-6xl grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">

        <div class="hidden lg:block text-white">
            <div class="inline-flex items-center gap-3 px-5 py-3 rounded-full glass">
                <span class="w-2 h-2 bg-emerald-300 rounded-full animate-pulse"></span>
                <span class="text-sm font-semibold text-emerald-100">Secure Healthcare Platform</span>
            </div>

            <h1 class="mt-8 text-6xl xl:text-7xl font-black leading-tight">
                Welcome to
                <span class="block text-transparent bg-clip-text bg-gradient-to-r from-emerald-300 via-cyan-300 to-blue-400">
                    MedHBook
                </span>
            </h1>

            <p class="mt-6 max-w-xl text-lg leading-8 text-slate-300">
                One premium platform for appointments, prescriptions,
                medical reports, encrypted documents and secure patient care.
            </p>

            <div class="grid grid-cols-3 gap-5 mt-10 max-w-xl">
                <div class="glass rounded-3xl p-6 text-center hover:scale-105 transition">
                    <div class="text-4xl">🩺</div>
                    <p class="mt-3 font-bold">Doctors</p>
                </div>

                <div class="glass rounded-3xl p-6 text-center hover:scale-105 transition">
                    <div class="text-4xl">📄</div>
                    <p class="mt-3 font-bold">Documents</p>
                </div>

                <div class="glass rounded-3xl p-6 text-center hover:scale-105 transition">
                    <div class="text-4xl">🔐</div>
                    <p class="mt-3 font-bold">Security</p>
                </div>
            </div>
        </div>

        <div class="w-full flex justify-center">
            <div class="glass glow w-full max-w-[480px] rounded-[36px] p-8 sm:p-10 shadow-2xl">

                <div class="text-center">
                    <div class="mx-auto w-20 h-20 rounded-3xl bg-gradient-to-br from-emerald-300 to-cyan-300 flex items-center justify-center text-4xl float shadow-xl">
                        🫀
                    </div>

                    <h2 class="mt-7 text-4xl font-black text-white">Welcome Back</h2>
                    <p class="mt-2 text-sm text-slate-400">Login to your MedHBook account</p>
                </div>

                @if(session('success'))
                    <div class="mt-7 rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-5 py-4 text-emerald-100 font-bold shadow-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mt-7 rounded-2xl border border-red-400/30 bg-red-500/10 px-5 py-4 text-red-100 font-bold shadow-lg">
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('status'))
                    <div class="mt-7 rounded-2xl border border-cyan-400/30 bg-cyan-500/10 px-5 py-4 text-cyan-100 font-bold shadow-lg">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-8">
                    <a href="{{ route('google.redirect', ['role' => 'patient']) }}"
                       class="w-full flex items-center justify-center gap-3 rounded-2xl border border-white/10 bg-white/10 hover:bg-emerald-500/20 text-white py-4 text-sm font-extrabold transition shadow-lg">
                        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg"
                             class="w-5 h-5" alt="Google">
                        Patient Google
                    </a>

                    <a href="{{ route('google.redirect', ['role' => 'doctor']) }}"
                       class="w-full flex items-center justify-center gap-3 rounded-2xl border border-white/10 bg-white/10 hover:bg-cyan-500/20 text-white py-4 text-sm font-extrabold transition shadow-lg">
                        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg"
                             class="w-5 h-5" alt="Google">
                        Doctor Google
                    </a>
                </div>

                <div class="flex items-center gap-3 my-7">
                    <div class="h-px flex-1 bg-white/10"></div>
                    <span class="text-[11px] font-black tracking-widest text-slate-400">OR LOGIN WITH EMAIL</span>
                    <div class="h-px flex-1 bg-white/10"></div>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-bold text-slate-200 mb-2">Email Address</label>
                        <input id="email"
                               type="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               autofocus
                               class="w-full rounded-2xl bg-white/95 px-5 py-4 text-slate-900 font-semibold outline-none focus:ring-4 focus:ring-emerald-400/60">

                        @error('email')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-5">
                        <label for="password" class="block text-sm font-bold text-slate-200 mb-2">Password</label>
                        <input id="password"
                               type="password"
                               name="password"
                               required
                               class="w-full rounded-2xl bg-white/95 px-5 py-4 text-slate-900 font-semibold outline-none focus:ring-4 focus:ring-cyan-400/60">

                        @error('password')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-5 flex items-center justify-between gap-4">
                        <label class="inline-flex items-center gap-2 text-sm text-slate-300">
                            <input type="checkbox"
                                   name="remember"
                                   class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            Remember me
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               class="text-sm text-emerald-300 hover:text-cyan-300 whitespace-nowrap">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <button type="submit"
                            class="shine mt-8 w-full rounded-2xl bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 py-4 text-white font-black shadow-xl hover:scale-[1.02] active:scale-[.98] transition">
                        Login
                    </button>

                    <div class="mt-7 flex items-center justify-between">
                        <a href="{{ route('register') }}"
                           class="text-sm font-bold text-emerald-400 hover:text-emerald-300">
                            Create account
                        </a>

                        <p class="text-xs text-slate-500">Secure Login</p>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>
</body>
</html>