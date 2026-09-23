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
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@spklu.local',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'status' => UserStatus::Active,
                'email_verified_at' => now(),
                'force_password_change' => false,
                'requires_otp_first_login' => false,
                'first_login_verified_at' => now(),
            ],
            [
                'name' => 'Pengelola',
                'email' => 'pengelola@spklu.local',
                'password' => Hash::make('password'),
                'role' => 'pengelola',
                'status' => UserStatus::Active,
                'email_verified_at' => now(),
                'force_password_change' => false,
                'requires_otp_first_login' => false,
                'first_login_verified_at' => now(),
            ],
            [
                'name' => 'Pemasaran',
                'email' => 'pemasaran@spklu.local',
                'password' => Hash::make('password'),
                'role' => 'pemasaran',
                'status' => UserStatus::Active,
                'email_verified_at' => now(),
                'force_password_change' => false,
                'requires_otp_first_login' => false,
                'first_login_verified_at' => now(),
            ],
            [
                'name' => 'Manajemen',
                'email' => 'manajemen@spklu.local',
                'password' => Hash::make('password'),
                'role' => 'manajemen',
                'status' => UserStatus::Active,
                'email_verified_at' => now(),
                'force_password_change' => false,
                'requires_otp_first_login' => false,
                'first_login_verified_at' => now(),
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                $data
            );
        }
    }
}
