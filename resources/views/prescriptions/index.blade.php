<x-app-layout>
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 py-10 px-6">
    <div class="max-w-7xl mx-auto">

        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-extrabold text-white">Prescriptions</h1>
                <p class="text-slate-300 mt-2">Manage patient prescriptions</p>
            </div>

            <a href="{{ route('prescriptions.create') }}"
               class="px-6 py-3 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-extrabold shadow-lg">
                + Create Prescription
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-2xl bg-emerald-500/10 border border-emerald-400/30 text-emerald-200 px-5 py-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl shadow-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-white/10 text-slate-200">
                        <tr>
                            <th class="px-6 py-4">#</th>
                            <th class="px-6 py-4">Patient</th>
                            <th class="px-6 py-4">Doctor</th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Diagnosis</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-white/10">
                        @forelse($prescriptions as $prescription)
                            <tr class="text-slate-200 hover:bg-white/5">
                                <td class="px-6 py-4 font-bold">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $prescription->patient->name ?? $prescription->patient->user->name ?? 'Patient' }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $prescription->doctor->name ?? $prescription->doctor->user->name ?? 'Doctor' }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ optional($prescription->prescription_date)->format('d M, Y') }}
                                </td>

                                <td class="px-6 py-4 max-w-xs truncate">
                                    {{ $prescription->diagnosis }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex justify-end gap-3">

                                        <a href="{{ route('prescriptions.show', $prescription->id) }}"
                                           class="px-4 py-2 rounded-xl bg-blue-500/20 text-blue-200 border border-blue-400/30">
                                            View
                                        </a>

                                        <a href="{{ route('prescriptions.download', $prescription->id) }}"
                                           class="px-4 py-2 rounded-xl bg-emerald-500/20 text-emerald-200 border border-emerald-400/30">
                                            PDF
                                        </a>

                                        @if(auth()->user()->role !== 'patient')
                                            <form method="POST"
                                                  action="{{ route('prescriptions.destroy', $prescription->id) }}"
                                                  onsubmit="return confirm('Delete this prescription?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="px-4 py-2 rounded-xl bg-red-500/20 text-red-200 border border-red-400/30">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-300">
                                    No prescription found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
</x-app-layout>