<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable();

            $table->string('specialist')->nullable();
            $table->string('specialization')->nullable();

            $table->string('room')->nullable();

            $table->string('degree')->nullable();
            $table->integer('experience')->default(0);
            $table->string('license_number')->nullable();
            $table->text('chamber_address')->nullable();
            $table->string('cv')->nullable();

            $table->string('verification_status')->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};