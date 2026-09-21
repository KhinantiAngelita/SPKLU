<?php

namespace App\Services;

use App\Models\Probabilitas;

class ProbabilitasScoreService
{
    /**
     * Total tahap yang jadi pembagi persentase. Diambil dari jumlah key
     * di Probabilitas::TAHAPAN supaya kalau suatu saat jumlah tahap
     * berubah, rumus ini otomatis ikut menyesuaikan — tidak ada angka
     * "11" yang di-hardcode di lebih dari satu tempat.
     */
    public function totalTahap(): int
    {
        return count(Probabilitas::TAHAPAN);
    }

    /**
     * Hitung ulang jumlah tahap selesai, persentase, dan kategori untuk
     * satu lokasi kandidat, lalu simpan hasilnya sebagai cache di kolom
     * probabilitas. Dipanggil setiap kali ada kunjungan baru dicatat
     * atau riwayat kunjungan diubah/dihapus.
     *
     * Aturan "selesai": kunjungan TERAKHIR (by tanggal) untuk tahap itu
     * hasilnya 'berhasil'. Kalau kunjungan terakhir 'perlu_kunjungan_ulang'
     * atau 'gagal', tahap itu belum dihitung selesai meskipun pernah
     * berhasil di kunjungan sebelumnya — status terkini yang menentukan.
     */
    public function recalculate(Probabilitas $probabilitas): Probabilitas
    {
        $probabilitas->loadMissing('riwayatTahapan');

        $terakhirPerTahap = $probabilitas->riwayatTahapan
            ->sortByDesc('tanggal')
            ->unique('tahap');

        $jumlahSelesai = $terakhirPerTahap->where('hasil', 'berhasil')->count();
        $total = $this->totalTahap();
        $persentase = $total > 0 ? round(($jumlahSelesai / $total) * 100, 2) : 0;

        $probabilitas->jumlah_tahap_selesai = $jumlahSelesai;
        $probabilitas->persentase_progres = $persentase;

        // Mitra mesin sudah ada -> otomatis dianggap >50%, terlepas dari
        // persentase progres tahapan aktual.
        $probabilitas->kategori = (! empty($probabilitas->mitra_mesin) || $persentase >= 50)
            ? '>50%'
            : '<50%';

        $probabilitas->save();

        return $probabilitas;
    }
}