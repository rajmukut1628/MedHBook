<x-app-layout>
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-black to-indigo-950 py-10">
    <div class="max-w-7xl mx-auto px-6">

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-4xl font-black text-white">Manage Admins</h1>
                <p class="text-gray-400 mt-2">Super Admin can edit or delete general admins</p>
            </div>

            <a href="{{ route('superadmin.dashboard') }}"
               class="px-5 py-3 rounded-xl bg-white/10 text-white border border-white/10">
                Back
            </a>
        </div>

        <div class="bg-white/10 border border-white/10 rounded-3xl overflow-hidden shadow-2xl">
            <table class="w-full text-left">
                <thead class="bg-white/10 text-white">
                    <tr>
                        <th class="p-5">Name</th>
                        <th class="p-5">Email</th>
                        <th class="p-5">Role</th>
                        <th class="p-5">Status</th>
                        <th class="p-5 text-right">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($admins as $admin)
                        <tr class="border-t border-white/10 text-gray-200">
                            <td class="p-5 font-bold">{{ $admin->name }}</td>
                            <td class="p-5">{{ $admin->email }}</td>
                            <td class="p-5">
                                <span class="px-3 py-1 rounded-full bg-indigo-500 text-white text-xs font-bold">
                                    {{ str_replace('_', ' ', $admin->role) }}
                                </span>
                            </td>
                            <td class="p-5">
                                <span class="px-3 py-1 rounded-full {{ $admin->status == 'active' ? 'bg-emerald-600' : 'bg-red-600' }} text-white text-xs font-bold">
                                    {{ $admin->status ?? 'active' }}
                                </span>
                            </td>
                            <td class="p-5 text-right">
                                <a href="{{ route('superadmin.admin.edit', $admin->id) }}"
                                   class="px-4 py-2 rounded-lg bg-emerald-600 text-white font-bold">
                                    Edit
                                </a>

                                <form action="{{ route('superadmin.admin.delete', $admin->id) }}"
                                      method="POST"
                                      class="inline-block"
                                      onsubmit="return confirm('Are you sure you want to delete this admin?');">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="px-4 py-2 rounded-lg bg-red-600 text-white font-bold ml-2">
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