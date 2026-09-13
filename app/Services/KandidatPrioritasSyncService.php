<?php

namespace App\Services;

use App\Models\KandidatPrioritas;
use App\Models\Probabilitas;
use App\Models\UlpMapping;

class KandidatPrioritasSyncService
{
    /**
     * Buat atau update baris KandidatPrioritas yang terhubung ke satu
     * Probabilitas. Dipanggil setelah Probabilitas dibuat (store) atau
     * field poin/identitasnya diubah (update) — supaya data di halaman
     * Kandidat Prioritas selalu ikut data terbaru dari Probabilitas.
     */
    public function sync(Probabilitas $probabilitas): KandidatPrioritas
    {
        $ulpMappingId = $probabilitas->ulp
            ? UlpMapping::where('nama_penuh', $probabilitas->ulp)->value('id')
            : null;

        $kandidat = KandidatPrioritas::updateOrCreate(
        ['probabilitas_id' => $probabilitas->id],
        [
            'nama_lokasi' => $probabilitas->lokasi,
            'ulp_mapping_id' => $ulpMappingId,
            'koordinat' => "{$probabilitas->tikor_lat}, {$probabilitas->tikor_lng}",
            'mitra_mesin' => $probabilitas->mitra_mesin,

            // Poin — ambil dari accessor yang sudah kita buat di Probabilitas.
            // Fallback ke 0 karena data poin belum tentu ada saat kandidat
            // baru dibuat lewat form minimal (Kandidat), diisi belakangan.
            'poin_fasilitas' => $probabilitas->poin_fasilitas,
            'poin_jaringan' => $probabilitas->poin_jaringan,
            'poin_okupasi' => $probabilitas->poin_okupasi,
        ]
    );

        // skor_prioritas itu kolom asli, bukan accessor — harus dihitung
        // & disimpan eksplisit tiap kali poin komponennya berubah,
        // sesuai pola hitungSkorPrioritas() yang sudah ada di model.
        $kandidat->skor_prioritas = $kandidat->hitungSkorPrioritas();
        $kandidat->save();

        return $kandidat;
    }
}