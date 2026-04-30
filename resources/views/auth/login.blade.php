<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="mx-auto mb-4 h-14 w-14 rounded-2xl bg-emerald-100 flex items-center justify-center text-3xl shadow">
            🩺
        </div>

        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">
            Welcome Back
        </h2>

        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            Login to your MedHBook account
        </p>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-700 dark:border-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 rounded-xl border border-red-300 bg-red-50 px-4 py-3 text-sm font-bold text-red-700 dark:border-red-700 dark:bg-red-900/30 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Firebase Google Login -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <button type="button"
                onclick="firebaseGoogleLogin('patient')"
                class="w-full flex items-center justify-center gap-3 px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white font-bold hover:bg-gray-100 dark:hover:bg-gray-700 transition shadow-sm">
            🌐 Login as Patient
        </button>

        <button type="button"
                onclick="firebaseGoogleLogin('doctor')"
                class="w-full flex items-center justify-center gap-3 px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white font-bold hover:bg-gray-100 dark:hover:bg-gray-700 transition shadow-sm">
            👨‍⚕️ Login as Doctor
        </button>
    </div>

    <div id="googleLoginMessage" class="mt-4 hidden rounded-xl px-4 py-3 text-sm font-bold"></div>

    <div class="my-6 flex items-center gap-3">
        <div class="h-px flex-1 bg-gray-200 dark:bg-gray-700"></div>
        <span class="text-xs font-bold text-gray-400 uppercase">or login with email</span>
        <div class="h-px flex-1 bg-gray-200 dark:bg-gray-700"></div>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email Address')" />

            <x-text-input id="email"
                          class="block mt-1 w-full"
                          type="email"
                          name="email"
                          :value="old('email')"
                          required
                          autofocus
                          autocomplete="username" />

            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password"
                          class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required
                          autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me"
                       type="checkbox"
                       class="rounded border-gray-300 dark:border-gray-700 text-emerald-600 shadow-sm focus:ring-emerald-500"
                       name="remember">

                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">
                    Remember me
                </span>
            </label>
        </div>

        <button type="submit"
                class="mt-6 w-full py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold hover:from-emerald-700 hover:to-teal-700 shadow-lg transition">
            Login
        </button>

        <div class="mt-5 flex items-center justify-between">
            <a href="{{ route('register') }}"
               class="text-sm font-semibold text-emerald-600 hover:underline">
                Create account
            </a>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="text-sm text-gray-500 hover:text-emerald-600 hover:underline">
                    Forgot password?
                </a>
            @endif
        </div>
    </form>

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
        import { getAuth, GoogleAuthProvider, signInWithPopup } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-auth.js";

        const firebaseConfig = {
            apiKey: "AIzaSyCFdefv4kZhlC7cWwRewhr4miPnr-FRVJ0",
            authDomain: "medhbook-5de7d.firebaseapp.com",
            projectId: "medhbook-5de7d",
            storageBucket: "medhbook-5de7d.firebasestorage.app",
            messagingSenderId: "550695795362",
            appId: "1:550695795362:web:968395fffa56b1031775fb"
        };

        const app = initializeApp(firebaseConfig);
        const auth = getAuth(app);
        const provider = new GoogleAuthProvider();

        window.firebaseGoogleLogin = async function(role) {
            const messageBox = document.getElementById('googleLoginMessage');

            try {
                messageBox.className = "mt-4 rounded-xl px-4 py-3 text-sm font-bold bg-blue-50 text-blue-700 border border-blue-300";
                messageBox.innerText = "Opening Google login...";
                messageBox.classList.remove('hidden');

                const result = await signInWithPopup(auth, provider);
                const idToken = await result.user.getIdToken();

                messageBox.innerText = "Verifying account...";

                const response = await fetch("{{ route('firebase.google.login') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        idToken: idToken,
                        role: role
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || "Google login failed.");
                }

                messageBox.className = "mt-4 rounded-xl px-4 py-3 text-sm font-bold bg-emerald-50 text-emerald-700 border border-emerald-300";
                messageBox.innerText = "Login successful. Redirecting...";

                window.location.href = data.redirect;

            } catch (error) {
                messageBox.className = "mt-4 rounded-xl px-4 py-3 text-sm font-bold bg-red-50 text-red-700 border border-red-300";
                messageBox.innerText = error.message || "Something went wrong.";
                messageBox.classList.remove('hidden');
            }
        };
    </script>
</x-guest-layout>