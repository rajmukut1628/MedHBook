<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'patient_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('patient_id')->nullable()->unique()->after('role');
            });
        }

        if (!Schema::hasColumn('users', 'doctor_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('doctor_id')->nullable()->unique()->after('patient_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'doctor_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropUnique(['doctor_id']);
                $table->dropColumn('doctor_id');
            });
        }

        if (Schema::hasColumn('users', 'patient_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropUnique(['patient_id']);
                $table->dropColumn('patient_id');
            });
        }
    }
};