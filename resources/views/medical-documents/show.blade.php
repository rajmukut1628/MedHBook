<x-app-layout>

<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 py-10 px-6">
    <div class="max-w-5xl mx-auto">

        <div class="bg-white/10 backdrop-blur-2xl rounded-[32px] shadow-2xl p-8 border border-white/10 text-white">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5 mb-8">
                <div>
                    <p class="inline-flex px-4 py-2 rounded-full bg-emerald-400/10 border border-emerald-300/20 text-emerald-200 text-sm font-black">
                        🔐 Private Medical Document
                    </p>

                    <h1 class="mt-4 text-3xl md:text-4xl font-black">
                        {{ $medicalDocument->title }}
                    </h1>

                    <p class="text-slate-300 mt-2">
                        {{ $medicalDocument->document_type }}
                    </p>
                </div>

                <a href="{{ route('medical-documents.index') }}"
                   class="px-5 py-3 bg-white/10 hover:bg-white/20 border border-white/10 text-white rounded-xl font-bold">
                    ← Back
                </a>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div class="p-5 rounded-2xl bg-white/10 border border-white/10">
                    <p class="text-sm text-slate-400">Original File</p>
                    <h3 class="text-lg font-bold text-white mt-1 break-words">
                        {{ $medicalDocument->original_name ?? 'Private File' }}
                    </h3>
                </div>

                <div class="p-5 rounded-2xl bg-white/10 border border-white/10">
                    <p class="text-sm text-slate-400">Storage Status</p>
                    <h3 class="text-lg font-bold text-emerald-300 mt-1">
                        Private Storage
                    </h3>
                </div>

                <div class="p-5 rounded-2xl bg-white/10 border border-white/10">
                    <p class="text-sm text-slate-400">File Type</p>
                    <h3 class="text-lg font-bold text-white mt-1">
                        {{ $medicalDocument->file_type ?? 'Unknown' }}
                    </h3>
                </div>

                <div class="p-5 rounded-2xl bg-white/10 border border-white/10">
                    <p class="text-sm text-slate-400">File Size</p>
                    <h3 class="text-lg font-bold text-white mt-1">
                        {{ $medicalDocument->pretty_size ?? ($medicalDocument->file_size ?? 'N/A') }}
                    </h3>
                </div>

                <div class="p-5 rounded-2xl bg-white/10 border border-white/10">
                    <p class="text-sm text-slate-400">Doctor</p>
                    <h3 class="text-lg font-bold text-white mt-1">
                        {{ $medicalDocument->doctor_name ?? 'N/A' }}
                    </h3>
                </div>

                <div class="p-5 rounded-2xl bg-white/10 border border-white/10">
                    <p class="text-sm text-slate-400">Hospital</p>
                    <h3 class="text-lg font-bold text-white mt-1">
                        {{ $medicalDocument->hospital_name ?? 'N/A' }}
                    </h3>
                </div>

                <div class="p-5 rounded-2xl bg-white/10 border border-white/10">
                    <p class="text-sm text-slate-400">Document Date</p>
                    <h3 class="text-lg font-bold text-white mt-1">
                        {{ $medicalDocument->document_date ? $medicalDocument->document_date->format('d M Y') : 'N/A' }}
                    </h3>
                </div>

                <div class="p-5 rounded-2xl bg-white/10 border border-white/10">
                    <p class="text-sm text-slate-400">Uploaded At</p>
                    <h3 class="text-lg font-bold text-white mt-1">
                        {{ $medicalDocument->created_at ? $medicalDocument->created_at->format('d M Y, h:i A') : 'N/A' }}
                    </h3>
                </div>

                <div class="md:col-span-2 p-5 rounded-2xl bg-white/10 border border-white/10">
                    <p class="text-sm text-slate-400">Notes</p>
                    <p class="text-slate-200 mt-2">
                        {{ $medicalDocument->notes ?? 'No notes added.' }}
                    </p>
                </div>
            </div>

            <div class="mt-8 grid md:grid-cols-2 gap-4">
                <a href="{{ route('medical-documents.download', $medicalDocument->id) }}"
                   class="text-center px-6 py-4 bg-gradient-to-r from-blue-500 to-cyan-500 hover:scale-[1.02] transition text-white rounded-2xl font-black">
                    Download Main File
                </a>

                <form method="POST"
                      action="{{ route('medical-documents.destroy', $medicalDocument->id) }}"
                      onsubmit="return confirm('Delete this document?')">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="w-full px-6 py-4 bg-red-500 hover:bg-red-600 text-white rounded-2xl font-black">
                        Delete Document
                    </button>
                </form>
            </div>

            <div class="mt-6 rounded-2xl bg-emerald-400/10 border border-emerald-300/20 p-5">
                <h3 class="font-black text-emerald-200">
                    🔐 Security Notice
                </h3>

                <p class="mt-2 text-slate-300 text-sm">
                    This file is stored in private storage. It cannot be opened from public URL,
                    public/storage, or direct browser link. Only the logged-in owner can download it.
                </p>
            </div>

        </div>

    </div>
</div>

</x-app-layout>