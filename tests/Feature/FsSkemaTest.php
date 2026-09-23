<?php

namespace Tests\Feature;

use App\Enums\UserStatus;
use App\Models\FsSkema;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class FsSkemaTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Pengelola SPKLU',
            'email' => 'pengelola@example.com',
            'password' => Hash::make('password123'),
            'role' => 'pengelola',
            'status' => UserStatus::Active,
            'force_password_change' => false,
        ]);
    }

    public function test_user_can_access_edit_page_with_rendered_preview_data(): void
    {
        $fsSkema = FsSkema::create([
            'skema' => 'skema_2',
            'nama_lokasi' => 'SPKLU Bandung Dago',
            'titik_koordinat' => '-6.8900, 107.6100',
            'total_rab_investasi' => 500000000,
            'mobil_per_hari' => 10,
            'transaksi_kwh_per_mobil' => 25,
            'masa_kontrak_tahun' => 5,
            'layanan_listrik' => 'TR',
            'poin_fasilitas' => 30,
            'poin_kesiapan_jaringan' => 20,
            'poin_okupansi' => 30,
            'total_poin' => 80,
            'status_kelayakan' => 'Layak',
            'narasi_analisis' => 'Lokasi sangat layak untuk pengembangan SPKLU.',
            'created_by' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('fs-skema.edit', $fsSkema));

        $response->assertStatus(200);
        $response->assertSee('3 SPKLU Terdekat');
        $response->assertSee('Proyeksi ROI');
        $response->assertSee('Grafik Proyeksi ROI');
        $response->assertSee('Ringkasan Analisis');
        $response->assertSee('Lokasi sangat layak untuk pengembangan SPKLU.');
    }

    public function test_preview_endpoint_supports_both_post_and_put(): void
    {
        $payload = [
            'skema' => 'skema_2',
            'nama_lokasi' => 'SPKLU Test',
            'titik_koordinat' => '-6.8900, 107.6100',
            'total_rab_investasi' => 500000000,
            'mobil_per_hari' => 10,
            'transaksi_kwh_per_mobil' => 25,
            'masa_kontrak_tahun' => 5,
            'layanan_listrik' => 'TR',
        ];

        // Test normal POST
        $responsePost = $this->actingAs($this->user)
            ->postJson(route('fs-skema.preview'), $payload);

        $responsePost->assertStatus(200);
        $responsePost->assertJsonStructure([
            'poin',
            'spklu_terdekat',
            'proyeksi_roi',
            'narasi_analisis',
        ]);

        // Test POST with _method = PUT (as spoofed by form edit)
        $responsePut = $this->actingAs($this->user)
            ->post(route('fs-skema.preview'), array_merge($payload, ['_method' => 'PUT']), [
                'Accept' => 'application/json',
            ]);

        $responsePut->assertStatus(200);
        $responsePut->assertJsonStructure([
            'poin',
            'spklu_terdekat',
            'proyeksi_roi',
            'narasi_analisis',
        ]);
    }
}
