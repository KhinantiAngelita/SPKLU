<?php

namespace App\Helpers;

use App\Models\AktivitasNotifikasi;

class NotifikasiHelper
{
    /**
     * Kirim notifikasi aktivitas operasional ke sistem.
     * Perubahan yang bersifat privat (pengelolaan user, perubahan hak akses/status oleh Super Admin)
     * tidak boleh dikirimkan ke publik.
     *
     * @param  string  $kategori  spklu, transaksi, kandidat, pengajuan, fs_skema, jadwal
     * @param  string  $pesan  Pesan ringkas aktivitas
     * @param  string  $icon  Ikon Lucide (mis. zap, arrow-left-right, activity, calculator, calendar-check)
     * @param  string|null  $url  Tautan halaman terkait jika notifikasi diklik
     * @param  array|null  $targetRoles  Array role tujuan, atau null untuk semua role
     * @param  string|null  $judul  Judul notifikasi opsional
     */
    public static function kirim(
        string $kategori,
        string $pesan,
        string $icon = 'bell',
        ?string $url = null,
        ?array $targetRoles = null,
        ?string $judul = null
    ): AktivitasNotifikasi {
        $actor = auth()->user();

        return AktivitasNotifikasi::create([
            'user_id' => $actor?->id,
            'kategori' => $kategori,
            'judul' => $judul,
            'pesan' => $pesan,
            'icon' => $icon,
            'url' => $url,
            'target_roles' => $targetRoles,
        ]);
    }
}
