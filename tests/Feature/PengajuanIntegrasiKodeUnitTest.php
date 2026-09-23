<?php

namespace Tests\Feature;

use App\Enums\UserStatus;
use App\Models\Probabilitas;
use App\Models\Spklu;
use App\Models\TahapanProbing;
use App\Models\UlpMapping;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PengajuanIntegrasiKodeUnitTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private UlpMapping $ulp;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Admin Pengajuan',
            'email' => 'admin.pengajuan@example.com',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'status' => UserStatus::Active,
            'force_password_change' => false,
        ]);

        $this->ulp = UlpMapping::create([
            'nama_singkat' => 'Kota',
            'nama_penuh' => 'Bogor Kota',
            'jarak_ideal_km' => 1.5,
            'kategori_area' => 'Kota Padat',
        ]);

        // Buat satu SPKLU existing agar kode_unit ULP ini terdeteksi sebagai 53831
        Spklu::create([
            'id_spklu' => 'SPKLU-001',
            'nama' => 'SPKLU Existing Bogor Kota',
            'ulp_mapping_id' => $this->ulp->id,
            'kode_unit' => '53831',
            'type' => 'DC',
            'kw' => 60,
            'nozzle' => 2,
            'kepemilikan' => 'PLN',
            'status' => 'aktif',
        ]);
    }

    public function test_kandidat_create_form_renders_with_ulp_options(): void
    {
        $response = $this->actingAs($this->user)->get(route('monitoring.kandidat.create'));

        $response->assertStatus(200);
        $response->assertSee('Tambah Kandidat Baru');
        $response->assertSee('Bogor Kota');
    }

    public function test_store_kandidat_saves_ulp_properly(): void
    {
        $response = $this->actingAs($this->user)->post(route('monitoring.probabilitas.store'), [
            'lokasi' => 'Mall Botani Square',
            'alamat' => 'Jl. Pajajaran No. 1',
            'nomor_telepon' => '081234567890',
            'pic' => 'Budi',
            'ulp' => 'Bogor Kota',
            'tikor' => '-6.597147, 106.806039',
        ]);

        $response->assertRedirect(route('monitoring.probabilitas.index'));
        $this->assertDatabaseHas('probabilitas', [
            'lokasi' => 'Mall Botani Square',
            'ulp' => 'Bogor Kota',
        ]);
    }

    public function test_pengajuan_index_displays_active_tambah_kandidat_button(): void
    {
        $response = $this->actingAs($this->user)->get(route('monitoring.pengajuan.index'));

        $response->assertStatus(200);
        $response->assertSee('Tambah Kandidat Baru');
        $response->assertSee(route('monitoring.kandidat.create'));
        $response->assertDontSee('disabled title="Menunggu fitur Kandidat Baru selesai dibuat"');
    }

    public function test_validasi_integrasi_automatically_populates_ulp_and_kode_unit(): void
    {
        $kandidat = Probabilitas::create([
            'lokasi' => 'RS Siloam Bogor',
            'tikor_lat' => -6.597147,
            'tikor_lng' => 106.806039,
            'ulp' => 'Bogor Kota',
        ]);

        // Simulasikan semua 11 tahapan selesai
        foreach (array_keys(Probabilitas::TAHAPAN) as $tahapKey) {
            TahapanProbing::create([
                'probabilitas_id' => $kandidat->id,
                'tahap' => $tahapKey,
                'tanggal' => now(),
                'hasil' => 'berhasil',
            ]);
        }

        $kandidat->refresh();
        $this->assertEquals('selesai_integrasi', $kandidat->statusKanban());

        // Lakukan validasi integrasi masuk ke Master SPKLU
        $response = $this->actingAs($this->user)->post(route('monitoring.pengajuan.validasi', $kandidat), [
            'ulp_mapping_id' => $this->ulp->id,
            // kode_unit sengaja dikosongkan untuk menguji auto-populate dari ULP
            'type' => 'DC',
            'kw' => 60,
            'nozzle' => 2,
            'kepemilikan' => 'PLN',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Pastikan record SPKLU baru tercipta dengan ULP dan kode_unit 53831
        $this->assertDatabaseHas('spklus', [
            'nama' => 'RS Siloam Bogor',
            'ulp_mapping_id' => $this->ulp->id,
            'kode_unit' => '53831',
            'type' => 'DC',
            'status' => 'aktif',
        ]);
    }

    public function test_existing_spklu_with_null_kode_unit_falls_back_to_ulp_kode_unit(): void
    {
        $ulpBarat = UlpMapping::create([
            'nama_singkat' => 'Barat',
            'nama_penuh' => 'Bogor Barat',
            'jarak_ideal_km' => 3,
            'kategori_area' => 'Dalam Kota',
        ]);

        $spklu = Spklu::create([
            'id_spklu' => 'SPKLU-082',
            'nama' => 'SPKLU UCI BENY KLUB BOGOR RAYA',
            'ulp_mapping_id' => $ulpBarat->id,
            'kode_unit' => null,
            'type' => 'DC',
            'kw' => 120,
            'nozzle' => 2,
            'kepemilikan' => 'Swasta',
            'status' => 'aktif',
        ]);

        $this->assertEquals('53841', $spklu->kode_unit);

        $response = $this->actingAs($this->user)->get(route('master-spklu.index'));
        $response->assertStatus(200);
        $response->assertSee('53841');
        $response->assertSee('SPKLU UCI BENY KLUB BOGOR RAYA');
    }

    public function test_editing_spklu_ulp_automatically_updates_kode_unit(): void
    {
        $ulpTimur = UlpMapping::create([
            'nama_singkat' => 'Timur',
            'nama_penuh' => 'Bogor Timur',
            'jarak_ideal_km' => 3,
            'kategori_area' => 'Dalam Kota',
        ]);

        $spklu = Spklu::create([
            'id_spklu' => 'SPKLU-099',
            'nama' => 'SPKLU Test Edit ULP',
            'ulp_mapping_id' => $this->ulp->id, // Bogor Kota (53831)
            'kode_unit' => '53831',
            'type' => 'DC',
            'kw' => 60,
            'nozzle' => 2,
            'kepemilikan' => 'PLN',
            'status' => 'aktif',
        ]);

        // Edit SPKLU ganti ULP ke Bogor Timur (53821)
        $response = $this->actingAs($this->user)->put(route('master-spklu.update', $spklu), [
            'nama' => 'SPKLU Test Edit ULP',
            'ulp_mapping_id' => $ulpTimur->id,
            'type' => 'DC',
            'kw' => 60,
            'nozzle' => 2,
            'kepemilikan' => 'PLN',
        ]);

        $response->assertRedirect();
        $spklu->refresh();

        $this->assertEquals($ulpTimur->id, $spklu->ulp_mapping_id);
        $this->assertEquals('53821', $spklu->kode_unit);
    }

    public function test_kode_unit_by_ulp_endpoint_returns_official_code(): void
    {
        $ulpJasinga = UlpMapping::create([
            'nama_singkat' => 'Jasinga',
            'nama_penuh' => 'ULP Jasinga',
            'jarak_ideal_km' => 5,
            'kategori_area' => 'Luar Kota',
        ]);

        $response = $this->actingAs($this->user)->getJson(route('master-spklu.kode-unit-by-ulp', $ulpJasinga));

        $response->assertStatus(200);
        $response->assertJson(['kode_unit' => '53853']);
    }
}
