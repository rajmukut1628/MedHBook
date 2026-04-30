<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="mx-auto mb-4 h-14 w-14 rounded-2xl bg-emerald-100 flex items-center justify-center text-3xl shadow">
            🩺
        </div>

        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">
            Create MedHBook Account
        </h2>

        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            Register as Patient or Doctor
        </p>
    </div>

    {{-- Google Role Register Buttons --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6">

        <a href="{{ route('google.redirect', ['role' => 'patient']) }}"
           class="w-full flex items-center justify-center gap-3 px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white font-bold hover:bg-gray-100 dark:hover:bg-gray-700 transition shadow-sm">
            <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg"
                 class="w-5 h-5" alt="Google">
            Patient Google
        </a>

        <a href="{{ route('google.redirect', ['role' => 'doctor']) }}"
           class="w-full flex items-center justify-center gap-3 px-4 py-3 rounded-xl border border-emerald-300 dark:border-emerald-700 bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 font-bold hover:bg-emerald-100 dark:hover:bg-emerald-900 transition shadow-sm">
            <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg"
                 class="w-5 h-5" alt="Google">
            Doctor Google
        </a>

    </div>

    <div class="my-6 flex items-center gap-3">
        <div class="h-px flex-1 bg-gray-200 dark:bg-gray-700"></div>
        <span class="text-xs font-bold text-gray-400 uppercase">or register with email</span>
        <div class="h-px flex-1 bg-gray-200 dark:bg-gray-700"></div>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name"
                          class="block mt-1 w-full"
                          type="text"
                          name="name"
                          :value="old('name')"
                          required
                          autofocus
                          autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email"
                          class="block mt-1 w-full"
                          type="email"
                          name="email"
                          :value="old('email')"
                          required
                          autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="role" :value="__('Account Type')" />

            <select id="role"
                    name="role"
                    required
                    class="block mt-1 w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-emerald-500 focus:ring-emerald-500">

                <option value="patient"
                    {{ old('role', request('role', 'patient')) === 'patient' ? 'selected' : '' }}>
                    Patient
                </option>

                <option value="doctor"
                    {{ old('role', request('role')) === 'doctor' ? 'selected' : '' }}>
                    Doctor
                </option>

            </select>

            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password"
                          class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required
                          autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation"
                          class="block mt-1 w-full"
                          type="password"
                          name="password_confirmation"
                          required
                          autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit"
                class="mt-6 w-full py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold hover:from-emerald-700 hover:to-teal-700 shadow-lg transition">
            Create Account
        </button>

        <div class="mt-5 text-center">
            <a href="{{ route('login') }}"
               class="text-sm font-semibold text-emerald-600 hover:underline">
                Already registered? Login
            </a>
        </div>
    </form>
</x-guest-layout>