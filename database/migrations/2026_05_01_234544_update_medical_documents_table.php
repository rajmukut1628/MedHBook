<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('medical_documents', 'file_path')) {
            Schema::table('medical_documents', function (Blueprint $table) {
                $table->string('file_path')->nullable()->after('notes');
            });
        }

        if (!Schema::hasColumn('medical_documents', 'is_encrypted')) {
            Schema::table('medical_documents', function (Blueprint $table) {
                $table->boolean('is_encrypted')->default(true)->after('file_size');
            });
        }

        if (!Schema::hasColumn('medical_documents', 'tag')) {
            Schema::table('medical_documents', function (Blueprint $table) {
                if (Schema::hasColumn('medical_documents', 'iv')) {
                    $table->string('tag')->nullable()->after('iv');
                } else {
                    $table->string('tag')->nullable()->after('is_encrypted');
                }
            });
        }

        if (!Schema::hasColumn('medical_documents', 'privacy_key_hint')) {
            Schema::table('medical_documents', function (Blueprint $table) {
                if (Schema::hasColumn('medical_documents', 'key_hint')) {
                    $table->string('privacy_key_hint')->nullable()->after('key_hint');
                } else {
                    $table->string('privacy_key_hint')->nullable()->after('tag');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('medical_documents', 'privacy_key_hint')) {
            Schema::table('medical_documents', function (Blueprint $table) {
                $table->dropColumn('privacy_key_hint');
            });
        }

        if (Schema::hasColumn('medical_documents', 'tag')) {
            Schema::table('medical_documents', function (Blueprint $table) {
                $table->dropColumn('tag');
            });
        }

        if (Schema::hasColumn('medical_documents', 'is_encrypted')) {
            Schema::table('medical_documents', function (Blueprint $table) {
                $table->dropColumn('is_encrypted');
            });
        }

        if (Schema::hasColumn('medical_documents', 'file_path')) {
            Schema::table('medical_documents', function (Blueprint $table) {
                $table->dropColumn('file_path');
            });
        }
    }
};