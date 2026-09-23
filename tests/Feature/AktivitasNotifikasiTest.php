<?php

namespace Tests\Feature;

use App\Enums\UserStatus;
use App\Helpers\NotifikasiHelper;
use App\Models\AktivitasNotifikasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AktivitasNotifikasiTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;

    protected User $pemasaran;

    protected User $manajemen;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superAdmin = User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@spklu.test',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'status' => UserStatus::Active,
            'force_password_change' => false,
        ]);

        $this->pemasaran = User::create([
            'name' => 'Pemasaran SPKLU',
            'email' => 'pemasaran@spklu.test',
            'password' => Hash::make('password123'),
            'role' => 'pemasaran',
            'status' => UserStatus::Active,
            'force_password_change' => false,
        ]);

        $this->manajemen = User::create([
            'name' => 'Manajemen SPKLU',
            'email' => 'manajemen@spklu.test',
            'password' => Hash::make('password123'),
            'role' => 'manajemen',
            'status' => UserStatus::Active,
            'force_password_change' => false,
        ]);
    }

    public function test_notifikasi_helper_creates_notification_record(): void
    {
        $this->actingAs($this->superAdmin);

        $notif = NotifikasiHelper::kirim(
            'spklu',
            'SPKLU baru ditambahkan',
            'zap',
            '/master-spklu',
            null,
            'Tambah SPKLU'
        );

        $this->assertDatabaseHas('aktivitas_notifikasis', [
            'id' => $notif->id,
            'kategori' => 'spklu',
            'judul' => 'Tambah SPKLU',
            'pesan' => 'SPKLU baru ditambahkan',
            'icon' => 'zap',
        ]);
    }

    public function test_role_scoping_filters_notifications_correctly(): void
    {
        // Publik untuk semua role
        NotifikasiHelper::kirim(
            'spklu',
            'SPKLU Terbuka Untuk Semua',
            'zap',
            null,
            null
        );

        // Khusus pemasaran dan super admin
        NotifikasiHelper::kirim(
            'fs_skema',
            'Simulasi FS Skema Khusus Pemasaran',
            'calculator',
            null,
            ['super_admin', 'pemasaran']
        );

        // Super admin melihat 2 notifikasi
        $notifsSuperAdmin = AktivitasNotifikasi::forRole('super_admin')->get();
        $this->assertCount(2, $notifsSuperAdmin);

        // Pemasaran melihat 2 notifikasi
        $notifsPemasaran = AktivitasNotifikasi::forRole('pemasaran')->get();
        $this->assertCount(2, $notifsPemasaran);

        // Manajemen hanya melihat 1 (yang publik)
        $notifsManajemen = AktivitasNotifikasi::forRole('manajemen')->get();
        $this->assertCount(1, $notifsManajemen);
        $this->assertEquals('SPKLU Terbuka Untuk Semua', $notifsManajemen->first()->pesan);
    }

    public function test_mark_all_notifications_as_read_endpoint(): void
    {
        $this->actingAs($this->superAdmin);

        $this->assertNull($this->superAdmin->fresh()->last_read_notification_at);

        $response = $this->postJson(route('notifikasi.baca-semua'));
        $response->assertOk();
        $response->assertJson(['status' => 'ok']);

        $this->assertNotNull($this->superAdmin->fresh()->last_read_notification_at);
    }

    public function test_topbar_renders_notifications_and_avatars(): void
    {
        NotifikasiHelper::kirim(
            'spklu',
            'Uji Notifikasi Topbar',
            'zap',
            '/master-spklu',
            null,
            'Uji Topbar'
        );

        $response = $this->actingAs($this->superAdmin)->get(route('dashboard'));
        $response->assertOk();
        $response->assertSee('Uji Notifikasi Topbar');
        $response->assertSee('topbar-avatar');
    }
}
