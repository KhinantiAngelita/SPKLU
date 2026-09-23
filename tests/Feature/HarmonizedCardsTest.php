<?php

namespace Tests\Feature;

use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class HarmonizedCardsTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'status' => UserStatus::Active,
            'force_password_change' => false,
        ]);
    }

    public function test_dashboard_renders_cards_properly(): void
    {
        $response = $this->actingAs($this->user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Total SPKLU Terpasang');
        $response->assertSee('Pengajuan On-Progress');
        $response->assertSee('Kandidat Aktif');
        $response->assertSee('Jadwal Mendatang');
        $response->assertSee('dsh-card blue', false);
    }

    public function test_master_spklu_renders_cards_properly(): void
    {
        $response = $this->actingAs($this->user)->get(route('master-spklu.index'));

        $response->assertStatus(200);
        $response->assertSee('Total Unit SPKLU');
        $response->assertSee('Berdasarkan Type');
        $response->assertSee('Berdasarkan Kepemilikan');
        $response->assertSee('Total Kapasitas');
        $response->assertSee('msp-card blue', false);
    }

    public function test_rekomendasi_lokasi_renders_cards_properly(): void
    {
        $response = $this->actingAs($this->user)->get(route('rekomendasi-lokasi.index'));

        $response->assertStatus(200);
        $response->assertSee('SPKLU Existing DC');
        $response->assertSee('SPKLU Existing AC');
        $response->assertSee('Ada Pasangan (Mitra)');
        $response->assertSee('Belum Ada Pasangan');
        $response->assertSee('Titik Rekomendasi');
        $response->assertSee('rl-card dark-blue', false);
    }
}
