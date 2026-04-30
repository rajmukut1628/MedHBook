<x-guest-layout>
    <div class="text-center">

        <div class="mx-auto mb-6 h-20 w-20 rounded-3xl bg-gradient-to-br from-emerald-400 to-teal-600 flex items-center justify-center shadow-2xl">
            <span class="text-4xl">📧</span>
        </div>

        <h1 class="text-3xl font-extrabold text-white">
            Check Your Email
        </h1>

        <p class="mt-3 text-sm text-slate-300 leading-6">
            আপনার account তৈরি হয়েছে। এখন আপনার email inbox এ গিয়ে
            <span class="font-bold text-emerald-300">Verify Email Address</span>
            button এ click করুন।
        </p>

        <div class="mt-6 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 px-5 py-4 text-left">
            <p class="text-sm text-emerald-300 font-bold">
                ✅ Verification link sent successfully.
            </p>
            <p class="text-xs text-slate-400 mt-1">
                Email না পেলে spam/junk folder check করুন।
            </p>
        </div>

        @if (session('success'))
            <div class="mt-5 rounded-2xl bg-green-500/10 border border-green-500/30 px-5 py-4 text-green-300 text-sm font-bold">
                {{ session('success') }}
            </div>
        @endif

        @if (session('status') == 'verification-link-sent')
            <div class="mt-5 rounded-2xl bg-blue-500/10 border border-blue-500/30 px-5 py-4 text-blue-300 text-sm font-bold">
                A new verification link has been sent to your email.
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}" class="mt-6">
            @csrf

            <button type="submit"
                class="w-full py-4 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-black shadow-lg hover:scale-[1.02] transition">
                Resend Verification Email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf

            <button type="submit"
                class="text-sm text-slate-400 hover:text-white underline">
                Logout & Use Another Email
            </button>
        </form>

        <div class="mt-7 text-xs text-slate-500 leading-5">
            After verification, you can login and continue to your dashboard.
        </div>

    </div>
</x-guest-layout>