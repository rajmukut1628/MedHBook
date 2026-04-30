<x-app-layout>
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-black to-indigo-950 py-10 px-6">
    <div class="max-w-7xl mx-auto space-y-8">

        <div class="flex items-center justify-between">
            <div>
                <span class="inline-flex px-4 py-2 rounded-full bg-purple-500/20 text-purple-300 text-sm font-bold mb-3">
                    🛡️ Super Admin Only
                </span>

                <h1 class="text-4xl font-black text-white">Manage Admins</h1>

                <p class="text-gray-400 mt-2">
                    Create, edit and delete general admin accounts.
                </p>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('superadmin.admin.create') }}"
                   class="px-5 py-3 rounded-2xl bg-gradient-to-r from-purple-600 to-pink-600 text-white font-black shadow-lg">
                    + Create Admin
                </a>

                <a href="{{ route('superadmin.dashboard') }}"
                   class="px-5 py-3 rounded-2xl bg-white/10 text-white border border-white/10 font-bold">
                    Back
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-2xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 px-5 py-4 font-bold">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="rounded-3xl bg-white/10 border border-white/10 p-6 text-white shadow-2xl">
                <p class="text-slate-400 font-bold">Total General Admins</p>
                <h2 class="text-5xl font-black mt-3">{{ $admins->count() }}</h2>
            </div>
        </div>

        <div class="bg-white/10 border border-white/10 rounded-3xl overflow-hidden shadow-2xl">
            <table class="w-full text-left">
                <thead class="bg-white/10 text-white">
                    <tr>
                        <th class="p-5">Admin</th>
                        <th class="p-5">Email</th>
                        <th class="p-5">Role</th>
                        <th class="p-5">Status</th>
                        <th class="p-5 text-right">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($admins as $admin)
                        <tr class="border-t border-white/10 text-gray-200 hover:bg-white/5 transition">
                            <td class="p-5">
                                <div class="flex items-center gap-3">
                                    <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-purple-600 to-pink-600 flex items-center justify-center text-white font-black">
                                        {{ strtoupper(substr($admin->name, 0, 1)) }}
                                    </div>

                                    <div>
                                        <div class="font-black text-white">{{ $admin->name }}</div>
                                        <div class="text-xs text-gray-400">ID #{{ $admin->id }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="p-5">{{ $admin->email }}</td>

                            <td class="p-5">
                                <span class="px-3 py-1 rounded-full bg-indigo-500 text-white text-xs font-bold">
                                    {{ $admin->role }}
                                </span>
                            </td>

                            <td class="p-5">
                                <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-bold">
                                    {{ $admin->status ?? 'active' }}
                                </span>
                            </td>

                            <td class="p-5 text-right">
                                <a href="{{ route('superadmin.admin.edit', $admin->id) }}"
                                   class="px-4 py-2 rounded-xl bg-emerald-600 text-white font-bold">
                                    Edit
                                </a>

                                <form action="{{ route('superadmin.admin.delete', $admin->id) }}"
                                      method="POST"
                                      class="inline"
                                      onsubmit="return confirm('Delete this admin?')">
                                    @csrf
                                    @method('DELETE')

                                    <button class="px-4 py-2 rounded-xl bg-red-600 text-white font-bold ml-2">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-300">
                                No general admin found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
</x-app-layout>