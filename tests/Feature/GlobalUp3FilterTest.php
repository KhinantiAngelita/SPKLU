<?php

namespace Tests\Feature;

use App\Enums\SpkluStatus;
use App\Enums\UserStatus;
use App\Models\KandidatPrioritas;
use App\Models\Probabilitas;
use App\Models\Spklu;
use App\Models\SpkluAlias;
use App\Models\TransaksiUpload;
use App\Models\UlpMapping;
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

    public function test_dashboard_renders_top_spklu_performa_and_esg_metrics(): void
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

        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertViewHas('topSpkluPerforma');
        $response->assertViewHas('ringkasanKeuangan');
        $ringkasan = $response->viewData('ringkasanKeuangan');
        $this->assertArrayHasKey('reduksi_co2_kg', $ringkasan);
        $this->assertArrayHasKey('bensin_saved_liter', $ringkasan);
        $response->assertSee('Top 5 SPKLU Berkinerja Tertinggi');
        $response->assertSee('Reduksi Emisi Karbon');
        $response->assertSee('Ekuivalen Penghematan BBM');
    }

    public function test_data_is_scoped_by_active_up3_across_all_modules(): void
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

        $ulpBogor = UlpMapping::create([
            'up3' => 'UP3 Bogor',
            'nama_singkat' => 'Kota',
            'nama_penuh' => 'Bogor Kota',
            'jarak_ideal_km' => 1.5,
            'kategori_area' => 'Kota Padat',
        ]);

        $ulpCianjur = UlpMapping::create([
            'up3' => 'UP3 Cianjur',
            'nama_singkat' => 'Cjr',
            'nama_penuh' => 'Cianjur Kota',
            'jarak_ideal_km' => 2.5,
            'kategori_area' => 'Dalam Kota',
        ]);

        $spkluBogor = Spklu::create([
            'id_spklu' => 'SPKLU-BGR-001',
            'nama' => 'SPKLU Alun-Alun Bogor',
            'up3' => 'UP3 Bogor',
            'ulp_mapping_id' => $ulpBogor->id,
            'type' => 'DC',
            'kw' => 50,
            'kepemilikan' => 'PLN',
            'latitude' => -6.59,
            'longitude' => 106.79,
            'status' => SpkluStatus::Aktif,
        ]);

        $aliasBogor = SpkluAlias::create([
            'spklu_id' => $spkluBogor->id,
            'nama_asli' => 'SPKLU BOGOR RAW ALIAS',
        ]);

        $uploadBogor = TransaksiUpload::create([
            'nama_file' => 'transaksi_bogor_januari.csv',
            'status' => 'berhasil',
            'diupload_oleh' => $admin->id,
            'up3' => 'UP3 Bogor',
        ]);

        $probBogor = Probabilitas::create([
            'lokasi' => 'Kandidat Botani Bogor',
            'up3' => 'UP3 Bogor',
            'ulp' => 'Bogor Kota',
            'tikor_lat' => -6.60,
            'tikor_lng' => 106.80,
        ]);

        $probCianjur = Probabilitas::create([
            'lokasi' => 'Kandidat Alun-Alun Cianjur',
            'up3' => 'UP3 Cianjur',
            'ulp' => 'Cianjur Kota',
            'tikor_lat' => -6.82,
            'tikor_lng' => 107.14,
        ]);

        $kandidatBogor = KandidatPrioritas::create([
            'probabilitas_id' => $probBogor->id,
            'ulp_mapping_id' => $ulpBogor->id,
            'nama_lokasi' => 'Kandidat Botani Bogor',
            'koordinat' => '-6.60, 106.80',
        ]);

        $kandidatCianjur = KandidatPrioritas::create([
            'probabilitas_id' => $probCianjur->id,
            'ulp_mapping_id' => $ulpCianjur->id,
            'nama_lokasi' => 'Kandidat Alun-Alun Cianjur',
            'koordinat' => '-6.82, 107.14',
        ]);

        // When UP3 Cianjur is active
        $dashRes = $this->actingAs($admin)->get('/dashboard?up3=UP3+Cianjur');
        $dashRes->assertStatus(200);
        $pengajuanTerbaru = $dashRes->viewData('pengajuanTerbaru');
        $this->assertTrue($pengajuanTerbaru->contains('lokasi', 'Kandidat Alun-Alun Cianjur'));
        $this->assertFalse($pengajuanTerbaru->contains('lokasi', 'Kandidat Botani Bogor'));

        // Master SPKLU
        $mspRes = $this->actingAs($admin)->get('/master-spklu');
        $mspRes->assertStatus(200);
        $aliasList = $mspRes->viewData('aliasList');
        $this->assertFalse($aliasList->contains('nama_asli', 'SPKLU BOGOR RAW ALIAS'));

        // Transaksi Upload
        $upRes = $this->actingAs($admin)->get('/transaksi/upload');
        $upRes->assertStatus(200);
        $riwayat = $upRes->viewData('riwayat');
        $this->assertFalse(collect($riwayat->items())->contains('nama_file', 'transaksi_bogor_januari.csv'));

        // Monitoring Probabilitas
        $monProbRes = $this->actingAs($admin)->get('/monitoring/probabilitas');
        $monProbRes->assertStatus(200);
        $daftarProb = $monProbRes->viewData('daftarProbabilitas');
        $this->assertTrue(collect($daftarProb->items())->contains('lokasi', 'Kandidat Alun-Alun Cianjur'));
        $this->assertFalse(collect($daftarProb->items())->contains('lokasi', 'Kandidat Botani Bogor'));

        // Monitoring Pengajuan
        $monPengRes = $this->actingAs($admin)->get('/monitoring/pengajuan');
        $monPengRes->assertStatus(200);
        $belumMulai = $monPengRes->viewData('belumMulai');
        $onProgress = $monPengRes->viewData('onProgress');
        $selesai = $monPengRes->viewData('selesaiIntegrasi');
        $allPengajuan = $belumMulai->concat($onProgress)->concat($selesai);
        $this->assertTrue($allPengajuan->contains('lokasi', 'Kandidat Alun-Alun Cianjur'));
        $this->assertFalse($allPengajuan->contains('lokasi', 'Kandidat Botani Bogor'));

        // Kandidat Prioritas
        $kpRes = $this->actingAs($admin)->get('/kandidat-prioritas');
        $kpRes->assertStatus(200);
        $kandidatList = $kpRes->viewData('kandidatList');
        $this->assertTrue(collect($kandidatList->items())->contains('nama_lokasi', 'Kandidat Alun-Alun Cianjur'));
        $this->assertFalse(collect($kandidatList->items())->contains('nama_lokasi', 'Kandidat Botani Bogor'));

        // Kandidat Peringkat
        $kperRes = $this->actingAs($admin)->get('/kandidat-peringkat');
        $kperRes->assertStatus(200);
        $kperList = $kperRes->viewData('kandidatList');
        $this->assertTrue(collect($kperList->items())->contains('nama_lokasi', 'Kandidat Alun-Alun Cianjur'));
        $this->assertFalse(collect($kperList->items())->contains('nama_lokasi', 'Kandidat Botani Bogor'));
    }
}
