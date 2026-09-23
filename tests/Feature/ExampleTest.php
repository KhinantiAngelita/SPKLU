<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('login'));
    }

    public function test_seeded_users_can_login_directly_without_otp(): void
    {
        $this->seed(UserSeeder::class);

        $roles = [
            'admin@spklu.local',
            'pengelola@spklu.local',
            'pemasaran@spklu.local',
            'manajemen@spklu.local',
        ];

        foreach ($roles as $email) {
            $response = $this->post(route('login'), [
                'email' => $email,
                'password' => 'password',
            ]);

            $response->assertRedirect(route('dashboard'));
            $this->assertAuthenticated();
            $this->post(route('logout'));
        }
    }
}
