<?php

namespace Tests\Feature;

use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GlobalUp3FilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_set_global_up3_and_it_persists_in_session(): void
    {
        $admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'status' => UserStatus::Active,
            'force_password_change' => false,
            'up3' => null,
            'password_changed_at' => now(),
        ]);

        // Request with ?up3=UP3 Bogor
        $response = $this->actingAs($admin)->get('/dashboard?up3=UP3+Bogor');
        $response->assertStatus(200);
        $response->assertSessionHas('active_up3', 'UP3 Bogor');

        // Subsequent request to /master-spklu without ?up3 query param retains UP3 Bogor in session and view
        $response2 = $this->actingAs($admin)->get('/master-spklu');
        $response2->assertStatus(200);
        $this->assertEquals('UP3 Bogor', session('active_up3'));
        $response2->assertViewHas('activeUp3', 'UP3 Bogor');
        $response2->assertViewHas('selectedUp3', 'UP3 Bogor');
    }

    public function test_super_admin_can_reset_global_up3_to_all(): void
    {
        $admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'status' => UserStatus::Active,
            'force_password_change' => false,
            'up3' => null,
            'password_changed_at' => now(),
        ]);

        // Set to UP3 Bogor first
        $this->actingAs($admin)->get('/dashboard?up3=UP3+Bogor');
        $this->assertEquals('UP3 Bogor', session('active_up3'));

        // Reset to all via ?up3=
        $response = $this->actingAs($admin)->get('/dashboard?up3=');
        $response->assertStatus(200);
        $this->assertNull(session('active_up3'));
        $response->assertViewHas('activeUp3', null);
    }

    public function test_non_super_admin_is_always_scoped_to_own_up3(): void
    {
        $user = User::create([
            'name' => 'Staff Pemasaran',
            'email' => 'staff@example.com',
            'password' => Hash::make('password123'),
            'role' => 'pemasaran',
            'status' => UserStatus::Active,
            'force_password_change' => false,
            'up3' => 'UP3 Bandung',
            'password_changed_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $this->assertEquals('UP3 Bandung', session('active_up3'));
        $response->assertViewHas('activeUp3', 'UP3 Bandung');
        $response->assertViewHas('selectedUp3', 'UP3 Bandung');
    }
}
