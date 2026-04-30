<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            if (!Schema::hasColumn('doctors', 'degree')) {
                $table->string('degree')->nullable();
            }

            if (!Schema::hasColumn('doctors', 'experience')) {
                $table->integer('experience')->default(0);
            }

            if (!Schema::hasColumn('doctors', 'license_number')) {
                $table->string('license_number')->nullable();
            }

            if (!Schema::hasColumn('doctors', 'chamber_address')) {
                $table->text('chamber_address')->nullable();
            }

            if (!Schema::hasColumn('doctors', 'cv')) {
                $table->string('cv')->nullable();
            }

            if (!Schema::hasColumn('doctors', 'verification_status')) {
                $table->string('verification_status')->default('pending');
            }
        });
    }

    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $columns = [
                'degree',
                'experience',
                'license_number',
                'chamber_address',
                'cv',
                'verification_status',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('doctors', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};