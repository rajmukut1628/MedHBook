<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('appointments', 'chamber_address')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->string('chamber_address')->nullable()->after('doctor_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('appointments', 'chamber_address')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->dropColumn('chamber_address');
            });
        }
    }
};