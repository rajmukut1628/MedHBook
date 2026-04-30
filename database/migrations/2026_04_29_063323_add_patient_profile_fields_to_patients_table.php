<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {

            if (!Schema::hasColumn('patients', 'profile_photo')) {
                $table->string('profile_photo')->nullable()->after('privacy_key');
            }

            if (!Schema::hasColumn('patients', 'blood_group')) {
                $table->string('blood_group')->nullable()->after('gender');
            }

            if (!Schema::hasColumn('patients', 'has_allergy')) {
                $table->boolean('has_allergy')->default(false)->after('blood_group');
            }

            if (!Schema::hasColumn('patients', 'has_diabetes')) {
                $table->boolean('has_diabetes')->default(false)->after('has_allergy');
            }

            if (!Schema::hasColumn('patients', 'has_blood_pressure')) {
                $table->boolean('has_blood_pressure')->default(false)->after('has_diabetes');
            }

            if (!Schema::hasColumn('patients', 'emergency_contact')) {
                $table->string('emergency_contact')->nullable()->after('has_blood_pressure');
            }
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {

            $columns = [
                'profile_photo',
                'blood_group',
                'has_allergy',
                'has_diabetes',
                'has_blood_pressure',
                'emergency_contact',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('patients', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};