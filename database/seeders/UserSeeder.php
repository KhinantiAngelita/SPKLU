<?php
// database/seeders/UserSeeder.php

namespace Database\Seeders;

use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@spklu.local',
            'password' => Hash::make('password'), // GANTI setelah seeding di production
            'role' => 'super_admin',
            'status' => UserStatus::Active,
            'email_verified_at' => now(),
        ]);
    }
}