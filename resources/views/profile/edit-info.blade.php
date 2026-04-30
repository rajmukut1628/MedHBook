<x-app-layout>
    <div class="min-h-screen bg-slate-950 py-10 px-6">
        <div class="max-w-2xl mx-auto bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl p-8 text-white">

            <h2 class="text-2xl font-black mb-6">Update Account Info</h2>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="mb-4">
                    <label class="block mb-2 font-bold">Name</label>
                    <input type="text" name="name"
                        value="{{ old('name', $user->name) }}"
                        class="w-full rounded-xl bg-white/10 border-white/20 text-white p-3">
                </div>

                <div class="mb-4">
                    <label class="block mb-2 font-bold">Email</label>
                    <input type="email" name="email"
                        value="{{ old('email', $user->email) }}"
                        class="w-full rounded-xl bg-white/10 border-white/20 text-white p-3">
                </div>

                <button class="px-6 py-3 bg-emerald-600 rounded-xl font-bold">
                    Save Changes
                </button>
            </form>

            <a href="{{ route('profile.edit') }}" class="block mt-5 text-slate-300 underline">
                ← Back
            </a>

        </div>
    </div>
</x-app-layout>