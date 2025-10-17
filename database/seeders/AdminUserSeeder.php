<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Créer ou mettre à jour un admin
        User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Créer ou mettre à jour un client
        User::updateOrCreate(
            ['email' => 'client@test.com'],
            [
                'name' => 'Client Test',
                'password' => Hash::make('password'),
                'role' => 'client',
                'email_verified_at' => now(),
            ]
        );

        // Créer ou mettre à jour un coach
        User::updateOrCreate(
            ['email' => 'coach@test.com'],
            [
                'name' => 'Coach Test',
                'password' => Hash::make('password'),
                'role' => 'coach',
                'email_verified_at' => now(),
            ]
        );
    }
}