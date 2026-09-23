<?php

namespace Tests\Feature;

use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ManajemenUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_view_manajemen_user_page(): void
    {
        $admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'status' => UserStatus::Active,
            'force_password_change' => false,
        ]);

        $response = $this->actingAs($admin)->get(route('manajemen-user.index'));

        $response->assertStatus(200);
        $response->assertSee('Manajemen User');
        $response->assertSee('Admin Utama');
        $response->assertSee('Total Pengguna');
    }

    public function test_non_super_admin_cannot_access_manajemen_user(): void
    {
        $pengelola = User::create([
            'name' => 'Staf Pengelola',
            'email' => 'pengelola@example.com',
            'password' => Hash::make('password123'),
            'role' => 'pengelola',
            'status' => UserStatus::Active,
            'force_password_change' => false,
        ]);

        $response = $this->actingAs($pengelola)->get(route('manajemen-user.index'));

        $response->assertStatus(403);
    }

    public function test_can_filter_users_by_search_and_role(): void
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'status' => UserStatus::Active,
            'force_password_change' => false,
        ]);

        $user1 = User::create([
            'name' => 'Budi Pemasaran',
            'email' => 'budi.pem@example.com',
            'password' => Hash::make('password123'),
            'role' => 'pemasaran',
            'status' => UserStatus::Active,
            'force_password_change' => false,
        ]);

        $user2 = User::create([
            'name' => 'Siti Pengelola',
            'email' => 'siti.kel@example.com',
            'password' => Hash::make('password123'),
            'role' => 'pengelola',
            'status' => UserStatus::Active,
            'force_password_change' => false,
        ]);

        $response = $this->actingAs($admin)->get(route('manajemen-user.index', ['search' => 'Budi']));
        $response->assertStatus(200);
        $response->assertSee('Budi Pemasaran');
        $response->assertDontSee('Siti Pengelola');

        $responseRole = $this->actingAs($admin)->get(route('manajemen-user.index', ['role' => 'pengelola']));
        $responseRole->assertStatus(200);
        $responseRole->assertSee('Siti Pengelola');
        $responseRole->assertDontSee('Budi Pemasaran');
    }

    public function test_super_admin_can_update_user_name_and_role(): void
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'status' => UserStatus::Active,
            'force_password_change' => false,
        ]);

        $target = User::create([
            'name' => 'Target User',
            'email' => 'target@example.com',
            'password' => Hash::make('password123'),
            'role' => 'pemasaran',
            'status' => UserStatus::Active,
            'force_password_change' => false,
        ]);

        $response = $this->actingAs($admin)->patch(route('manajemen-user.update', $target), [
            'name' => 'Target User Updated',
            'role' => 'pengelola',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $target->id,
            'name' => 'Target User Updated',
            'role' => 'pengelola',
        ]);
    }
}
