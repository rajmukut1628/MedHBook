<x-app-layout>

<style>
    @keyframes floaty {
        0%,100% { transform: translateY(0); }
        50% { transform: translateY(-14px); }
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(28px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes pulseGlow {
        0%,100% { box-shadow: 0 0 28px rgba(16,185,129,.25); }
        50% { box-shadow: 0 0 70px rgba(34,211,238,.45); }
    }

    .mhb-glass {
        background: rgba(15, 23, 42, .74);
        border: 1px solid rgba(255,255,255,.12);
        backdrop-filter: blur(24px);
    }

    .mhb-float {
        animation: floaty 5s ease-in-out infinite;
    }

    .mhb-fade {
        animation: fadeUp .7s ease both;
    }

    .mhb-glow {
        animation: pulseGlow 4s ease-in-out infinite;
    }

    .mhb-input {
        width: 100%;
        border-radius: 18px;
        background: rgba(255,255,255,.92);
        color: #0f172a;
        padding: 15px 18px;
        font-weight: 700;
        outline: none;
        border: 1px solid rgba(255,255,255,.35);
    }

    .mhb-input:focus {
        border-color: #22d3ee;
        box-shadow: 0 0 0 4px rgba(34,211,238,.18);
    }

    .type-card {
        cursor: pointer;
        transition: all .3s ease;
    }

    .type-card:hover {
        transform: translateY(-5px) scale(1.02);
    }

    .type-radio:checked + .type-card {
        border-color: #34d399;
        background: rgba(16,185,129,.18);
        box-shadow: 0 0 35px rgba(16,185,129,.25);
    }
</style>

<div class="relative min-h-screen overflow-hidden bg-slate-950 px-5 py-10">

    <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950"></div>
    <div class="absolute -top-40 -left-40 w-[520px] h-[520px] bg-emerald-500/25 rounded-full blur-3xl mhb-float"></div>
    <div class="absolute bottom-0 right-0 w-[520px] h-[520px] bg-cyan-500/20 rounded-full blur-3xl mhb-float"></div>

    <div class="relative z-10 max-w-6xl mx-auto">

        {{-- Header --}}
        <div class="mhb-glass mhb-glow rounded-[36px] p-8 mb-8 text-white mhb-fade">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-400/10 border border-emerald-300/20 text-emerald-200 text-sm font-bold">
                        <span class="w-2 h-2 rounded-full bg-emerald-300 animate-pulse"></span>
                        Private Medical Vault
                    </div>

                    <h1 class="mt-5 text-4xl md:text-5xl font-black">
                        Multi-file
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-300 via-cyan-300 to-blue-400">
                            Upload
                        </span>
                    </h1>

                    <p class="mt-4 text-slate-300 max-w-2xl">
                        Upload one or multiple medical documents together. Files are stored in 
                        <b>private storage</b> and cannot be accessed without login.
                    </p>
                </div>

                <a href="{{ route('medical-documents.index') }}"
                   class="inline-flex justify-center px-6 py-3 rounded-2xl bg-white/10 border border-white/10 text-white font-black hover:bg-white/20 transition">
                    ← Back to Vault
                </a>
            </div>
        </div>

        @if($errors->any())
            <div class="mb-6 rounded-2xl bg-red-500/20 border border-red-400/30 text-red-100 p-5 font-bold">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            $types = [
                'Prescription' => '💊',
                'MRI' => '🧠',
                'CT Scan' => '🧬',
                'X-Ray' => '🩻',
                'Blood Report' => '🩸',
                'Other' => '🔐',
            ];
        @endphp

        <form method="POST"
              action="{{ route('medical-documents.store') }}"
              enctype="multipart/form-data">
            @csrf

            {{-- Category --}}
            <div class="mhb-glass rounded-[34px] p-7 text-white mhb-fade mb-8">
                <h2 class="text-2xl font-black mb-2">1. Select Document Type</h2>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($types as $type => $icon)
                        <label>
                            <input type="radio"
                                   name="document_type"
                                   value="{{ $type }}"
                                   class="hidden type-radio"
                                   required>

                            <div class="type-card rounded-3xl p-5 border border-white/10 bg-white/5 text-center">
                                <div class="text-4xl mb-3">{{ $icon }}</div>
                                <div class="font-black">{{ $type }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
                        {{-- Details --}}
            <div class="mhb-glass rounded-[34px] p-7 text-white mhb-fade mb-8">
                <h2 class="text-2xl font-black mb-2">2. Document Details</h2>
                <p class="text-slate-400 mb-6">These details will be saved with every selected file.</p>

                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold mb-2">Document Title</label>
                        <input name="title"
                               required
                               value="{{ old('title') }}"
                               placeholder="Example: Blood Report - May 2026"
                               class="mhb-input">
                    </div>

                    <div>
                        <label class="block text-sm font-bold mb-2">Document Date</label>
                        <input type="date"
                               name="document_date"
                               value="{{ old('document_date') }}"
                               class="mhb-input">
                    </div>

                    <div>
                        <label class="block text-sm font-bold mb-2">Doctor Name</label>
                        <input name="doctor_name"
                               value="{{ old('doctor_name') }}"
                               placeholder="Example: Dr. Rahman"
                               class="mhb-input">
                    </div>

                    <div>
                        <label class="block text-sm font-bold mb-2">Hospital / Diagnostic Center</label>
                        <input name="hospital_name"
                               value="{{ old('hospital_name') }}"
                               placeholder="Example: Popular Diagnostic Center"
                               class="mhb-input">
                    </div>
                </div>

                <div class="mt-5">
                    <label class="block text-sm font-bold mb-2">Notes</label>
                    <textarea name="notes"
                              rows="4"
                              placeholder="Write short note about these documents..."
                              class="mhb-input">{{ old('notes') }}</textarea>
                </div>
            </div>

            {{-- Multi File Upload --}}
            <div class="mhb-glass rounded-[34px] p-7 text-white mhb-fade mb-8">
                <h2 class="text-2xl font-black mb-2">3. Upload Files</h2>

                <p class="text-slate-400 mb-6">
                    You can select multiple files together. Supported: PDF, JPG, PNG, WEBP, DOC, DOCX, XLS, XLSX, TXT, ZIP.
                </p>

                <label for="medical_files"
                       class="block rounded-[30px] border-2 border-dashed border-emerald-300/30 bg-white/5 p-10 text-center cursor-pointer hover:bg-emerald-500/10 hover:border-emerald-300/60 transition">
                    <div class="text-6xl mb-4">📁</div>

                    <h3 class="text-2xl font-black">Choose Medical Files</h3>

                    <p class="text-slate-400 mt-2">
                        Click here to select one or multiple documents
                    </p>

                    <p id="fileName" class="mt-4 text-emerald-300 font-black">
                        No files selected
                    </p>
                </label>

                <input type="file"
                       name="medical_files[]"
                       id="medical_files"
                       multiple
                       required
                       accept=".pdf,.jpg,.jpeg,.png,.webp,.doc,.docx,.xls,.xlsx,.txt,.zip"
                       class="hidden">

                <div id="selectedFilesBox"
                     class="hidden mt-6 rounded-2xl bg-slate-950/50 border border-white/10 p-5">
                    <h4 class="font-black text-emerald-300 mb-3">Selected Files</h4>
                    <ul id="selectedFilesList" class="space-y-2 text-sm text-slate-300"></ul>
                </div>

                <div class="mt-6 rounded-2xl bg-slate-950/50 border border-white/10 p-5">
                    <h4 class="font-black text-emerald-300">🔐 Security Note</h4>

                    <p class="text-slate-400 mt-2 text-sm">
                        Files will be saved in Laravel private storage:
                        <span class="text-emerald-300 font-bold">storage/app/private/medical-documents</span>.
                        They will not be available from public URL.
                    </p>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex flex-col md:flex-row gap-4 justify-between items-center mhb-fade">
                <a href="{{ route('medical-documents.index') }}"
                   class="w-full md:w-auto text-center px-7 py-4 rounded-2xl bg-white/10 border border-white/10 text-white font-black hover:bg-white/20 transition">
                    Cancel
                </a>

                <button type="submit"
                        id="submitBtn"
                        class="w-full md:w-auto px-10 py-4 rounded-2xl bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 text-white font-black shadow-xl hover:scale-[1.03] transition">
                    Save Uploads
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    const medicalFiles = document.getElementById('medical_files');
    const fileName = document.getElementById('fileName');
    const selectedFilesBox = document.getElementById('selectedFilesBox');
    const selectedFilesList = document.getElementById('selectedFilesList');
    const submitBtn = document.getElementById('submitBtn');

    function formatSize(bytes) {
        if (bytes >= 1024 * 1024) {
            return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
        }

        if (bytes >= 1024) {
            return (bytes / 1024).toFixed(2) + ' KB';
        }

        return bytes + ' B';
    }

    medicalFiles?.addEventListener('change', function () {
        const files = Array.from(this.files || []);

        selectedFilesList.innerHTML = '';

        if (!files.length) {
            fileName.innerText = 'No files selected';
            selectedFilesBox.classList.add('hidden');
            return;
        }

        fileName.innerText = files.length === 1
            ? files[0].name
            : files.length + ' files selected';

        selectedFilesBox.classList.remove('hidden');

        files.forEach((file, index) => {
            const li = document.createElement('li');
            li.className = 'flex items-center justify-between gap-4 rounded-xl bg-white/5 border border-white/10 px-4 py-3';

            li.innerHTML = `
                <span class="font-bold break-all">${index + 1}. ${file.name}</span>
                <span class="text-emerald-300 font-black whitespace-nowrap">${formatSize(file.size)}</span>
            `;

            selectedFilesList.appendChild(li);
        });
    });

    submitBtn?.addEventListener('click', function () {
        setTimeout(() => {
            this.innerText = 'Uploading...';
            this.disabled = true;
            this.classList.add('opacity-70', 'cursor-not-allowed');
        }, 50);
    });
</script>

</x-app-layout>