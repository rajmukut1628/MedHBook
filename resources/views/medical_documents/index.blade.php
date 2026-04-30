<x-app-layout>

<style>
.doc-page{min-height:100vh;padding:40px;background:linear-gradient(135deg,#020617,#0f172a,#111827);color:white}
.doc-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:30px;gap:16px}
.doc-header h1{font-size:36px;font-weight:900;margin:0}
.doc-header p{margin-top:8px;color:#cbd5e1}
.upload-btn{background:linear-gradient(135deg,#10b981,#0284c7);color:white;padding:14px 22px;border-radius:16px;text-decoration:none;font-weight:800}
.alert{padding:16px 20px;border-radius:18px;margin-bottom:20px;font-weight:800}
.success{background:rgba(16,185,129,.18);border:1px solid rgba(16,185,129,.35)}
.error{background:rgba(239,68,68,.18);border:1px solid rgba(239,68,68,.35)}
.category-card{background:rgba(255,255,255,.08);border-radius:26px;margin-bottom:26px;overflow:hidden;border:1px solid rgba(255,255,255,.12)}
.category-head{padding:22px 26px;background:linear-gradient(135deg,#0f172a,#064e3b);display:flex;justify-content:space-between;align-items:center;gap:16px;cursor:pointer}
.category-title{display:flex;align-items:center;gap:16px}
.category-icon{width:54px;height:54px;background:rgba(255,255,255,.15);border-radius:18px;display:flex;align-items:center;justify-content:center;font-size:26px}
.category-title h2{margin:0;font-size:22px;font-weight:800}
.category-title p{margin:5px 0 0;color:#cbd5e1;font-size:14px}
.right-tools{display:flex;align-items:center;gap:12px}
.count-badge{background:#10b981;color:white;padding:9px 16px;border-radius:999px;font-size:14px;font-weight:800}
.arrow-btn{width:42px;height:42px;border-radius:14px;border:1px solid rgba(255,255,255,.16);background:rgba(255,255,255,.10);color:white;font-size:20px;font-weight:900;display:flex;align-items:center;justify-content:center;transition:.25s}
.category-card.active .arrow-btn{transform:rotate(180deg);background:#10b981}
.doc-content{display:none}
.category-card.active .doc-content{display:block}
.doc-grid{padding:24px;display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:18px}
.file-card{background:rgba(15,23,42,.85);border:1px solid rgba(255,255,255,.12);border-radius:20px;padding:20px;transition:.2s}
.file-card:hover{transform:translateY(-4px);border-color:rgba(16,185,129,.45)}
.file-top{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px}
.file-icon{width:48px;height:48px;border-radius:16px;background:#d1fae5;color:#047857;display:flex;align-items:center;justify-content:center;font-size:23px}
.file-type{background:#dbeafe;color:#1d4ed8;padding:6px 12px;border-radius:999px;font-size:12px;font-weight:800}
.file-card h3{margin:0;font-size:18px;font-weight:800}
.file-card p{color:#cbd5e1;font-size:14px;margin:8px 0}
.actions{display:flex;gap:8px;flex-wrap:wrap;margin-top:18px}
.action-btn{border:none;text-decoration:none;padding:9px 14px;border-radius:12px;font-size:13px;font-weight:800;color:white;cursor:pointer}
.view{background:#0ea5e9}.open{background:#10b981}.delete{background:#ef4444}
.empty-box{padding:28px;text-align:center;color:#cbd5e1;font-weight:600}
.secure-badge{display:inline-block;background:#14532d;color:#bbf7d0;padding:4px 10px;border-radius:999px;font-size:11px;font-weight:900;margin-top:8px}
@media(max-width:768px){.doc-page{padding:20px}.doc-header{flex-direction:column;align-items:flex-start}.doc-header h1{font-size:30px}.category-head{align-items:flex-start}.right-tools{flex-direction:column;align-items:flex-end}}
</style>

<div class="doc-page">

    <div class="doc-header">
        <div>
            <h1>Medical Documents</h1>
            <p>Encrypted medical reports, scans and prescriptions</p>
        </div>

        <a href="{{ route('medical-documents.create') }}" class="upload-btn">
            + Upload Document
        </a>
    </div>

    @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert error">{{ session('error') }}</div>
    @endif

    @foreach($types as $type)

        @php
            $items = $documents->get($type, collect())->sortByDesc('created_at');

            $icons = [
                'X-Ray' => '🩻',
                'Prescription' => '💊',
                'MRI' => '🧠',
                'CT Scan' => '🫁',
                'Blood Report' => '🩸',
                'Doctor Digital Prescription PDF' => '📋',
                'Other' => '📁',
            ];
        @endphp

        <div class="category-card">

            <div class="category-head" onclick="toggleDocSection(this)">
                <div class="category-title">
                    <div class="category-icon">{{ $icons[$type] ?? '📄' }}</div>

                    <div>
                        <h2>{{ $type }}</h2>
                        <p>Total Upload: {{ $items->count() }}</p>
                    </div>
                </div>

                <div class="right-tools">
                    <div class="count-badge">{{ $items->count() }} Files</div>
                    <div class="arrow-btn">⌄</div>
                </div>
            </div>

            <div class="doc-content">

                @if($items->count() > 0)

                    <div class="doc-grid">

                        @foreach($items as $document)

                            <div class="file-card">

                                <div class="file-top">
                                    <div class="file-icon">📄</div>

                                    <div class="file-type">
                                        {{ strtoupper($document->file_type ?? 'FILE') }}
                                    </div>
                                </div>

                                <h3>{{ $document->title }}</h3>

                                <p>Size: {{ $document->file_size ?? 'N/A' }}</p>

                                @if($document->document_date)
                                    <p>Date: {{ $document->document_date->format('d M Y') }}</p>
                                @endif

                                @if(empty($document->is_prescription_pdf))
                                    <div class="secure-badge">🔒 Encrypted</div>
                                @endif

                                <div class="actions">

                                    @if(!empty($document->is_prescription_pdf))

                                        <a href="{{ route('prescriptions.show', $document->id) }}"
                                           class="action-btn view">
                                            View
                                        </a>

                                        <a href="{{ route('prescriptions.download', $document->id) }}"
                                           class="action-btn open">
                                            Download PDF
                                        </a>

                                    @else

                                        <a href="{{ route('medical-documents.show', $document->id) }}"
                                           class="action-btn view">
                                            View
                                        </a>

                                        <a href="{{ route('medical-documents.download', $document->id) }}"
                                           class="action-btn open">
                                            Secure Download
                                        </a>

                                        <form action="{{ route('medical-documents.destroy', $document->id) }}"
                                              method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button onclick="return confirm('Delete this document?')"
                                                    class="action-btn delete">
                                                Delete
                                            </button>
                                        </form>

                                    @endif

                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="empty-box">
                        No {{ $type }} uploaded yet.
                    </div>

                @endif

            </div>

        </div>

    @endforeach

</div>

<script>
function toggleDocSection(header){
    const card = header.closest('.category-card');
    card.classList.toggle('active');
}
</script>

</x-app-layout>