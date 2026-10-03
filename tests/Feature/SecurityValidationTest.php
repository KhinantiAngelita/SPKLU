<?php

namespace Tests\Feature;

use App\Models\Jadwal;
use App\Models\Probabilitas;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }

    public function test_dashboard_handles_invalid_and_inverted_dates_gracefully(): void
    {
        $user = User::where('role', 'super_admin')->first();

        $response = $this->actingAs($user)->get(route('dashboard', [
            'dari_bulan' => 'invalid-date',
            'sampai_bulan' => 'another-invalid',
        ]));

        $this->assertTrue(in_array($response->status(), [200, 302]));

        $responseInverted = $this->actingAs($user)->get(route('dashboard', [
            'dari_bulan' => '2026-12',
            'sampai_bulan' => '2026-01',
        ]));

        $responseInverted->assertStatus(200);
    }

    public function test_transaksi_handles_invalid_dates_and_bad_satuan_gracefully(): void
    {
        $user = User::where('role', 'super_admin')->first();

        $response = $this->actingAs($user)->get(route('transaksi.index', [
            'dari' => 'not-a-date',
            'sampai' => 'also-not-a-date',
            'satuan' => 'malicious_unit',
        ]));

        // Harusnya diarahkan dengan validasi atau fallback aman tanpa 500
        $this->assertTrue(in_array($response->status(), [200, 302]));
    }

    public function test_penjadwalan_rejects_invalid_status_on_update(): void
    {
        $user = User::where('role', 'super_admin')->first();

        $prob = Probabilitas::create([
            'lokasi' => 'Test Lokasi Penjadwalan',
            'tikor_lat' => -6.59,
            'tikor_lng' => 106.80,
            'created_by' => $user->id,
        ]);

        $jadwal = Jadwal::create([
            'probabilitas_id' => $prob->id,
            'judul' => 'Agenda Test',
            'waktu_mulai' => now()->addDay(),
            'mode' => 'offline',
            'lokasi' => 'Kantor PLN',
            'status' => 'terjadwal',
            'dibuat_oleh' => $user->id,
        ]);

        $response = $this->actingAs($user)->put(route('penjadwalan.update', $jadwal), [
            'probabilitas_id' => $prob->id,
            'waktu_mulai' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'mode' => 'offline',
            'lokasi' => 'Kantor PLN',
            'status' => 'status_ilegal_hacked',
        ]);

        $response->assertSessionHasErrors('status');
        $this->assertEquals('terjadwal', $jadwal->fresh()->status);
    }

    public function test_store_tahapan_probabilitas_saves_and_notifies_without_error(): void
    {
        $user = User::where('role', 'super_admin')->first();

        $prob = Probabilitas::create([
            'lokasi' => 'Test Lokasi Probing',
            'tikor_lat' => -6.59,
            'tikor_lng' => 106.80,
            'created_by' => $user->id,
        ]);

        $response = $this->actingAs($user)->postJson(
            route('monitoring.probabilitas.tahapan.store', $prob),
            [
                'tahap' => 'probing',
                'tanggal' => now()->format('Y-m-d'),
                'petugas_pic' => 'Budi Santoso',
                'hasil' => 'berhasil',
                'catatan' => 'Kunjungan pertama lancar',
            ]
        );

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('tahapan_probings', [
            'probabilitas_id' => $prob->id,
            'tahap' => 'probing',
            'hasil' => 'berhasil',
        ]);
    }
}
