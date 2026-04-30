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

            // Owner patient/user
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('document_type');
            $table->string('title');

            // Secure encrypted storage info
            $table->string('encrypted_name')->nullable();
            $table->string('original_name')->nullable();
            $table->string('storage_disk')->default('local');
            $table->string('storage_path');

            // File metadata only
            $table->string('file_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();

            // Encryption metadata
            $table->string('encryption_mode')->default('server_side');

            $table->text('notes')->nullable();
            $table->date('document_date')->nullable();

            $table->timestamps();

            $table->index('user_id');
            $table->index('document_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_documents');
    }
};