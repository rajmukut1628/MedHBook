<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medical_documents', function (Blueprint $table) {
            if (!Schema::hasColumn('medical_documents', 'encrypted_file')) {
                $table->longText('encrypted_file')->nullable()->after('notes');
            }

            if (!Schema::hasColumn('medical_documents', 'doctor_name')) {
                $table->string('doctor_name')->nullable()->after('document_type');
            }

            if (!Schema::hasColumn('medical_documents', 'hospital_name')) {
                $table->string('hospital_name')->nullable()->after('doctor_name');
            }

            if (!Schema::hasColumn('medical_documents', 'original_filename')) {
                $table->string('original_filename')->nullable()->after('encrypted_file');
            }

            if (!Schema::hasColumn('medical_documents', 'file_size')) {
                $table->unsignedBigInteger('file_size')->nullable()->after('original_filename');
            }

            if (!Schema::hasColumn('medical_documents', 'file_type')) {
                $table->string('file_type')->nullable()->after('file_size');
            }

            if (!Schema::hasColumn('medical_documents', 'encryption_mode')) {
                $table->string('encryption_mode')->default('browser_side')->after('file_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('medical_documents', function (Blueprint $table) {
            $columns = [
                'encrypted_file',
                'doctor_name',
                'hospital_name',
                'original_filename',
                'file_size',
                'file_type',
                'encryption_mode',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('medical_documents', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};