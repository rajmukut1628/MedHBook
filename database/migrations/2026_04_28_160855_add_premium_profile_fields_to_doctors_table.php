<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            if (!Schema::hasColumn('doctors', 'profile_photo')) {
                $table->string('profile_photo')->nullable()->after('cv');
            }

            if (!Schema::hasColumn('doctors', 'bio')) {
                $table->text('bio')->nullable()->after('profile_photo');
            }

            if (!Schema::hasColumn('doctors', 'qualification')) {
                $table->string('qualification')->nullable()->after('degree');
            }

            if (!Schema::hasColumn('doctors', 'gender')) {
                $table->string('gender')->nullable()->after('phone');
            }

            if (!Schema::hasColumn('doctors', 'blood_group')) {
                $table->string('blood_group')->nullable()->after('gender');
            }
        });
    }

    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn([
                'profile_photo',
                'bio',
                'qualification',
                'gender',
                'blood_group',
            ]);
        });
    }
};