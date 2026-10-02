<?php

namespace Tests\Feature;

use App\Models\Spklu;
use App\Models\User;
use Database\Seeders\MasterParameterSeeder;
use Database\Seeders\UlpMappingSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterSpkluExportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            UserSeeder::class,
            MasterParameterSeeder::class,
            UlpMappingSeeder::class,
        ]);
    }

    public function test_user_can_export_master_spklu(): void
    {
        $user = User::where('email', 'admin@spklu.local')->first();

        Spklu::create([
            'id_spklu' => 'SPKLU-001',
            'kode_unit' => '53831',
            'nama' => 'SPKLU Kantor PLN UP3 Bogor',
            'ulp_mapping_id' => 1,
            'type' => 'DC',
            'kw' => 50,
            'nozzle' => 2,
            'kepemilikan' => 'PLN',
            'skema' => 'Io2',
            'status' => 'aktif',
            'up3' => 'UP3 Bogor',
        ]);

        $response = $this->actingAs($user)->get(route('master-spklu.export'));

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');

        $content = $response->streamedContent();
        $this->assertStringContainsString('ID SPKLU', $content);
        $this->assertStringContainsString('SPKLU Kantor PLN UP3 Bogor', $content);
        $this->assertStringContainsString('UP3 Bogor', $content);
        $this->assertStringContainsString('Aktif', $content);
    }
}
