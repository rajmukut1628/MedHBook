<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medical_documents', function (Blueprint $table) {

            if (!Schema::hasColumn('medical_documents', 'file_path')) {
                $table->string('file_path')->nullable()->after('notes');
            }

            if (!Schema::hasColumn('medical_documents', 'is_encrypted')) {
                $table->boolean('is_encrypted')->default(true)->after('file_size');
            }

            if (!Schema::hasColumn('medical_documents', 'tag')) {
                $table->string('tag')->nullable()->after('iv');
            }

            if (!Schema::hasColumn('medical_documents', 'privacy_key_hint')) {
                $table->string('privacy_key_hint')->nullable()->after('key_hint');
            }

        });
    }

    public function down(): void
    {
        Schema::table('medical_documents', function (Blueprint $table) {

            if (Schema::hasColumn('medical_documents', 'file_path')) {
                $table->dropColumn('file_path');
            }

            if (Schema::hasColumn('medical_documents', 'is_encrypted')) {
                $table->dropColumn('is_encrypted');
            }

            if (Schema::hasColumn('medical_documents', 'tag')) {
                $table->dropColumn('tag');
            }

            if (Schema::hasColumn('medical_documents', 'privacy_key_hint')) {
                $table->dropColumn('privacy_key_hint');
            }

        });
    }
};