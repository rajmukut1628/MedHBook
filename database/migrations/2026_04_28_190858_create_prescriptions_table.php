<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('prescriptions', function (Blueprint $table) {
        $table->id();

        $table->foreignId('doctor_id')->nullable()->constrained('doctors')->nullOnDelete();
        $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();

        $table->date('prescription_date');
        $table->text('diagnosis');
        $table->text('medicines');
        $table->text('advice')->nullable();
        $table->date('next_visit_date')->nullable();

        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};