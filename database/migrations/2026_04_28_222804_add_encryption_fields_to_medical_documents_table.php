<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medical_documents', function (Blueprint $table) {
            if (!Schema::hasColumn('medical_documents', 'original_name')) {
                $table->string('original_name')->nullable();
            }

            if (!Schema::hasColumn('medical_documents', 'mime_type')) {
                $table->string('mime_type')->nullable();
            }

            if (!Schema::hasColumn('medical_documents', 'file_size')) {
                $table->unsignedBigInteger('file_size')->nullable();
            }

            if (!Schema::hasColumn('medical_documents', 'is_encrypted')) {
                $table->boolean('is_encrypted')->default(true);
            }
        });
    }

    public function down(): void
    {
        Schema::table('medical_documents', function (Blueprint $table) {
            $table->dropColumn([
                'original_name',
                'mime_type',
                'file_size',
                'is_encrypted',
            ]);
        });
    }
};