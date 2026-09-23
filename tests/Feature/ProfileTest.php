<?php

namespace Tests\Feature;

use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_redirected_from_profile(): void
    {
        $response = $this->get(route('profile.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_profile(): void
    {
        $user = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('password123'),
            'role' => 'pengelola',
            'status' => UserStatus::Active,
            'force_password_change' => false,
        ]);

        $response = $this->actingAs($user)->get(route('profile.index'));

        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertSee('budi@example.com');
        $response->assertSee('Profile Saya');
    }

    public function test_user_can_update_their_name(): void
    {
        $user = User::create([
            'name' => 'Nama Lama',
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
            'role' => 'pemasaran',
            'status' => UserStatus::Active,
            'force_password_change' => false,
        ]);

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => 'Nama Baru',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nama Baru',
        ]);
    }

    public function test_user_can_update_password(): void
    {
        $user = User::create([
            'name' => 'User Password',
            'email' => 'pass@example.com',
            'password' => Hash::make('oldpassword123'),
            'role' => 'super_admin',
            'status' => UserStatus::Active,
            'force_password_change' => false,
        ]);

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => 'User Password',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect();
        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }
}
