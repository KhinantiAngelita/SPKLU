<?php

namespace Tests\Feature;

use App\Enums\UserStatus;
use App\Models\User;
use Tests\TestCase;

class ProyeksiEnergiTest extends TestCase
{
    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get(route('transaksi.proyeksi'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_proyeksi_energi(): void
    {
        $user = new User;
        $user->id = 1;
        $user->name = 'Pengelola SPKLU';
        $user->email = 'pengelola@spklu.local';
        $user->role = 'pengelola';
        $user->status = UserStatus::Active;
        $user->force_password_change = false;

        $response = $this->actingAs($user)->get(route('transaksi.proyeksi'));

        $response->assertStatus(200);
        $response->assertSee('Proyeksi Penjualan Energi: Eksisting + SPKLU Baru');
        $response->assertSee('Tambahan SPKLU Baru');
        $response->assertSee('Eksisting (proyeksi)');
        $response->assertSee('Total');
        $response->assertSee('Energi (kWh)');
        $response->assertSee('Pendapatan (Rp)');
        $response->assertSee('Konservatif (+150%)');
        $response->assertSee('Moderat (+315%)');
        $response->assertSee('Agresif (+450%)');
        $response->assertSee('Mode Input Tambahan SPKLU Baru');
        $response->assertSee('Tarif: Rp');
    }
}
