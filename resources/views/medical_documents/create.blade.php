<x-app-layout>

<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 py-10">
    <div class="max-w-4xl mx-auto px-6">

        <div class="bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/10 text-white">

            <h1 class="text-3xl font-extrabold">Upload Medical Document</h1>
            <p class="text-slate-300 mt-2 mb-8">
                Upload X-Ray, Prescription, MRI, CT Scan, Blood Report or other files.
            </p>

            @if ($errors->any())
                <div class="mb-6 rounded-2xl bg-red-500/20 border border-red-400 p-4">
                    <ul class="space-y-1 text-sm text-red-100">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('medical-documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label class="block mb-2 font-bold">Document Type</label>
                    <select name="document_type" class="w-full rounded-xl bg-white/10 border-white/20 text-white" required>
                        <option value="" class="text-black">Select Type</option>

                        @foreach($types as $type)
                            <option value="{{ $type }}" class="text-black"
                                {{ old('document_type') == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block mb-2 font-bold">Title</label>
                    <input
                        type="text"
                        name="title"
                        value="{{ old('title') }}"
                        class="w-full rounded-xl bg-white/10 border-white/20 text-white placeholder-slate-300"
                        placeholder="Example: Chest X-Ray Report"
                        required
                    >
                </div>

                <div>
                    <label class="block mb-2 font-bold">Document Date</label>
                    <input
                        type="date"
                        name="document_date"
                        value="{{ old('document_date') }}"
                        class="w-full rounded-xl bg-white/10 border-white/20 text-white"
                    >
                </div>

                <div>
                    <label class="block mb-2 font-bold">Upload File</label>
                    <input
                        type="file"
                        name="file"
                        class="w-full rounded-xl border border-white/20 p-4 bg-white/10 text-white"
                        required
                    >

                    <p class="text-sm text-slate-300 mt-2">
                        Supports PDF, JPG, JPEG, PNG, WEBP, DOC, DOCX, XLS, XLSX, TXT, ZIP (Max 10MB)
                    </p>
                </div>

                <div>
                    <label class="block mb-2 font-bold">Notes</label>
                    <textarea
                        name="notes"
                        rows="4"
                        class="w-full rounded-xl bg-white/10 border-white/20 text-white placeholder-slate-300"
                        placeholder="Optional notes..."
                    >{{ old('notes') }}</textarea>
                </div>

                <div class="flex flex-wrap gap-3 pt-4">

                    <button type="submit"
                        class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold">
                        Upload Document
                    </button>

                    <a href="{{ route('medical-documents.index') }}"
                        class="px-6 py-3 bg-slate-300 hover:bg-slate-200 text-slate-900 rounded-xl font-bold">
                        Back
                    </a>

                </div>

            </form>
        </div>

    </div>
</div>

</x-app-layout>