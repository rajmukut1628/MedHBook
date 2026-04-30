<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'rajmukut791@gmail.com'],
            [
                'name' => 'Super Admin',
                'role' => 'super_admin',
                'status' => 'active',
                'password' => Hash::make('41220101628'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'rajmukt1628@gmail.com'],
            [
                'name' => 'General Admin',
                'role' => 'admin',
                'status' => 'active',
                'password' => Hash::make('12345678'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'doctor_medhbook@gmail.com'],
            [
                'name' => 'Demo Doctor',
                'role' => 'doctor',
                'status' => 'active',
                'password' => Hash::make('12345678'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test Patient',
                'role' => 'patient',
                'status' => 'active',
                'password' => Hash::make('12345678'),
            ]
        );
    }
}