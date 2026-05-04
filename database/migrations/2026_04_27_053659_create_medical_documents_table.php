<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_documents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('title');
            $table->string('document_type');
            $table->string('doctor_name')->nullable();
            $table->string('hospital_name')->nullable();
            $table->date('document_date')->nullable();
            $table->text('notes')->nullable();

            $table->string('encrypted_file_path');
            $table->string('original_name')->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();

            $table->string('encryption_version')->default('E2EE-V1');
            $table->string('salt');
            $table->string('iv');
            $table->string('auth_tag');
            $table->string('key_hint')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_documents');
    }
};