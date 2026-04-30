@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-100 via-white to-emerald-50 py-10">
    <div class="max-w-5xl mx-auto px-6">

        <div class="bg-white rounded-3xl shadow-2xl p-8 border border-slate-100">

            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-extrabold text-slate-800">
                        {{ $medicalDocument->title }}
                    </h1>

                    <p class="text-slate-500 mt-2">
                        {{ $medicalDocument->document_type }}
                    </p>
                </div>

                <a href="{{ route('medical-documents.index') }}"
                   class="px-5 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl font-bold">
                    Back
                </a>
            </div>

            <div class="grid md:grid-cols-2 gap-8">

                <div class="space-y-4">
                    <div class="p-5 rounded-2xl bg-slate-50">
                        <p class="text-sm text-slate-500">File Type</p>
                        <h3 class="text-lg font-bold text-slate-800">
                            {{ strtoupper($medicalDocument->file_type ?? 'FILE') }}
                        </h3>
                    </div>

                    <div class="p-5 rounded-2xl bg-slate-50">
                        <p class="text-sm text-slate-500">File Size</p>
                        <h3 class="text-lg font-bold text-slate-800">
                            {{ $medicalDocument->file_size ?? 'N/A' }}
                        </h3>
                    </div>

                    <div class="p-5 rounded-2xl bg-slate-50">
                        <p class="text-sm text-slate-500">Document Date</p>
                        <h3 class="text-lg font-bold text-slate-800">
                            {{ $medicalDocument->document_date ? $medicalDocument->document_date->format('d M Y') : 'N/A' }}
                        </h3>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="p-5 rounded-2xl bg-slate-50">
                        <p class="text-sm text-slate-500">Notes</p>
                        <p class="text-slate-700 mt-2">
                            {{ $medicalDocument->notes ?? 'No notes added.' }}
                        </p>
                    </div>

                    <a href="{{ $medicalDocument->file_url }}" target="_blank"
                       class="block text-center px-6 py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-bold">
                        Open / Download File
                    </a>
                </div>

            </div>

        </div>

    </div>
</div>
@endsection