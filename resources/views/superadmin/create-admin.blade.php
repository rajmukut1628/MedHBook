<x-app-layout>
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-black to-indigo-950 py-10 px-6">
    <div class="max-w-3xl mx-auto">

        <div class="mb-8 flex items-center justify-between">
            <div>
                <span class="inline-flex px-4 py-2 rounded-full bg-purple-500/20 text-purple-300 text-sm font-bold mb-3">
                    🛡️ Super Admin Only
                </span>

                <h1 class="text-4xl font-black text-white">Create Admin</h1>
                <p class="text-gray-400 mt-2">Add a new general admin account.</p>
            </div>

            <a href="{{ route('superadmin.admins') }}"
               class="px-5 py-3 rounded-2xl bg-white/10 text-white border border-white/10 font-bold">
                Back
            </a>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-2xl bg-red-500/15 border border-red-500/30 text-red-300 px-5 py-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li class="font-bold">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST"
              action="{{ route('superadmin.admin.store') }}"
              class="rounded-3xl bg-white/10 border border-white/10 p-8 shadow-2xl space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-bold text-gray-300 mb-2">Admin Name</label>
                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       requiredbg-black/40 border border-white/10 text-white
                       class="w-full rounded-2xl  px-5 py-4 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-300 mb-2">Email Address</label>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       class="w-full rounded-2xl class="w-full rounded-2xl bg-slate-900/80 border border-slate-700 text-white placeholder-gray-400 px-5 py-4 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 shadow-inner" px-5 py-4 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-300 mb-2">Status</label>
                <select name="status"
                        required
                        class="w-full rounded-2xl bg-black/40 border border-white/10 text-white px-5 py-4 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="blocked" {{ old('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-300 mb-2">Password</label>
                <input type="password"
                       name="password"
                       required
                       class="w-full rounded-2xl bg-black/40 border border-white/10 text-white px-5 py-4 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-300 mb-2">Confirm Password</label>
                <input type="password"
                       name="password_confirmation"
                       required
                       class="w-full rounded-2xl bg-black/40 border border-white/10 text-white px-5 py-4 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
            </div>

            <button type="submit"
                    class="w-full py-4 rounded-2xl bg-gradient-to-r from-purple-600 to-pink-600 text-white font-black shadow-lg hover:scale-[1.01] transition">
                Create Admin
            </button>
        </form>

    </div>
</div>
</x-app-layout>