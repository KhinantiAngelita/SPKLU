<?php

namespace App\Http\Controllers;

use App\Helpers\AuditLogHelper;
use App\Models\MitraMesin;
use App\Models\PoinFasilitas;
use App\Models\PoinKesiapanJaringan;
use App\Models\PoinOkupansi;
use App\Models\TargetTahunan;
use App\Models\TarifListrik;
use App\Services\FsSkemaCalculatorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class MasterParameterController extends Controller
{
    public function index()
    {
        // 1. Poin Kesiapan Jaringan (Auto-seed jika kosong)
        if (Schema::hasTable('poin_kesiapan_jaringan') && PoinKesiapanJaringan::count() === 0) {
            foreach (FsSkemaCalculatorService::OPSI_KESIAPAN_JARINGAN as $kondisi => $poin) {
                PoinKesiapanJaringan::create([
                    'kondisi' => $kondisi,
                    'poin' => $poin,
                ]);
            }
        }
        $poinJaringan = Schema::hasTable('poin_kesiapan_jaringan')
            ? PoinKesiapanJaringan::orderBy('urutan')->orderBy('id')->get()
            : collect();

        // 2. Poin Fasilitas (Auto-seed jika tabel ada tapi kosong)
        if (Schema::hasTable('poin_fasilitas') && PoinFasilitas::count() === 0) {
            $defaultFasilitas = [
                ['nama' => 'Toilet', 'kode' => 'toilet', 'poin' => 10, 'urutan' => 1],
                ['nama' => 'Ruang Tunggu', 'kode' => 'ruang_tunggu', 'poin' => 10, 'urutan' => 2],
                ['nama' => 'Parkir', 'kode' => 'parkir', 'poin' => 10, 'urutan' => 3],
                ['nama' => 'Kafetaria', 'kode' => 'kafetaria', 'poin' => 10, 'urutan' => 4],
            ];
            foreach ($defaultFasilitas as $f) {
                PoinFasilitas::create([
                    'nama' => $f['nama'],
                    'kode' => $f['kode'],
                    'poin' => $f['poin'],
                    'urutan' => $f['urutan'],
                    'is_aktif' => true,
                ]);
            }
        }
        $poinFasilitas = Schema::hasTable('poin_fasilitas')
            ? PoinFasilitas::orderBy('urutan')->orderBy('id')->get()
            : collect();

        // 3. Poin Okupansi (Auto-seed jika tabel ada tapi kosong)
        if (Schema::hasTable('poin_okupansi') && PoinOkupansi::count() === 0) {
            $defaultOkupansi = [
                ['nama' => 'Dekat Perumahan', 'kode' => 'dekat_perumahan', 'poin' => 10, 'urutan' => 1],
                ['nama' => 'Dekat Pintu Tol', 'kode' => 'pintu_tol', 'poin' => 10, 'urutan' => 2],
                ['nama' => 'Pusat Keramaian', 'kode' => 'pusat_keramaian', 'poin' => 10, 'urutan' => 3],
                ['nama' => 'Ruas Jalan Protokol', 'kode' => 'ruas_jalan_protokol', 'poin' => 10, 'urutan' => 4],
            ];
            foreach ($defaultOkupansi as $o) {
                PoinOkupansi::create([
                    'nama' => $o['nama'],
                    'kode' => $o['kode'],
                    'poin' => $o['poin'],
                    'urutan' => $o['urutan'],
                    'is_aktif' => true,
                ]);
            }
        }
        $poinOkupansi = Schema::hasTable('poin_okupansi')
            ? PoinOkupansi::orderBy('urutan')->orderBy('id')->get()
            : collect();

        // 4. Mitra Mesin (Auto-seed jika tabel ada tapi kosong)
        if (Schema::hasTable('mitra_mesin') && MitraMesin::count() === 0) {
            $defaultMitra = [
                'UCI Beny', 'Voltron', 'EAD', 'LAD', 'Niscala',
                'Prastiwahyu', 'TEB', 'Arista', 'PLN ES',
            ];
            foreach ($defaultMitra as $idx => $nama) {
                MitraMesin::create([
                    'nama' => $nama,
                    'keterangan' => 'Mitra penyedia mesin SPKLU',
                    'is_aktif' => true,
                    'urutan' => $idx + 1,
                ]);
            }
        }
        $mitraMesin = Schema::hasTable('mitra_mesin')
            ? MitraMesin::orderBy('urutan')->orderBy('nama')->get()
            : collect();

        return view('master-parameter.index', [
            'tarifListrik' => Schema::hasTable('tarif_listrik') ? TarifListrik::all() : collect(),
            'poinJaringan' => $poinJaringan,
            'poinFasilitas' => $poinFasilitas,
            'poinOkupansi' => $poinOkupansi,
            'mitraMesin' => $mitraMesin,
            'targetTahunan' => Schema::hasTable('target_tahunan') ? TargetTahunan::orderByDesc('tahun')->get() : collect(),
        ]);
    }

    // ==========================================
    // TARIF LISTRIK
    // ==========================================
    public function updateTarif(Request $request, TarifListrik $tarif)
    {
        $request->validate(['tarif_per_kwh' => 'required|numeric|min:0']);

        $nilaiLama = $tarif->only(['tarif_per_kwh']);

        $tarif->update([
            'tarif_per_kwh' => $request->tarif_per_kwh,
            'updated_by' => $request->user()->id,
        ]);

        AuditLogHelper::record($tarif, 'updated', $request->user(), $nilaiLama, $tarif->only(['tarif_per_kwh']));

        return back()->with('success', "Tarif {$tarif->kode} berhasil diperbarui.");
    }

    // ==========================================
    // POIN KESIAPAN JARINGAN
    // ==========================================
    public function storePoinJaringan(Request $request)
    {
        $request->validate([
            'kondisi' => 'required|string|max:255',
            'poin' => 'required|integer|min:0|max:20',
        ]);

        $poin = PoinKesiapanJaringan::create([
            'kondisi' => trim($request->kondisi),
            'poin' => $request->poin,
            'urutan' => PoinKesiapanJaringan::max('urutan') + 1,
            'updated_by' => $request->user()->id,
        ]);

        AuditLogHelper::record($poin, 'created', $request->user(), [], $poin->only(['kondisi', 'poin']));

        return back()->with('success', 'Parameter kesiapan jaringan berhasil ditambahkan.');
    }

    public function updatePoinJaringan(Request $request, PoinKesiapanJaringan $poin)
    {
        $request->validate([
            'kondisi' => 'sometimes|required|string|max:255',
            'poin' => 'required|integer|min:0|max:20',
        ]);

        $nilaiLama = $poin->only(['kondisi', 'poin']);

        $poin->update([
            'kondisi' => $request->input('kondisi', $poin->kondisi),
            'poin' => $request->poin,
            'updated_by' => $request->user()->id,
        ]);

        AuditLogHelper::record($poin, 'updated', $request->user(), $nilaiLama, $poin->only(['kondisi', 'poin']));

        return back()->with('success', 'Poin kesiapan jaringan berhasil diperbarui.');
    }

    public function destroyPoinJaringan(Request $request, PoinKesiapanJaringan $poin)
    {
        $nilaiLama = $poin->only(['kondisi', 'poin']);
        $poin->delete();

        AuditLogHelper::record($poin, 'deleted', $request->user(), $nilaiLama, []);

        return back()->with('success', 'Parameter kesiapan jaringan berhasil dihapus.');
    }

    // ==========================================
    // POIN FASILITAS
    // ==========================================
    public function storePoinFasilitas(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'poin' => 'required|integer|min:0|max:40',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $kode = Str::slug($request->nama, '_');
        $kodeAwal = $kode;
        $counter = 1;
        while (PoinFasilitas::where('kode', $kode)->exists()) {
            $kode = $kodeAwal.'_'.$counter++;
        }

        $fasilitas = PoinFasilitas::create([
            'nama' => trim($request->nama),
            'kode' => $kode,
            'poin' => $request->poin,
            'keterangan' => $request->keterangan,
            'is_aktif' => true,
            'urutan' => PoinFasilitas::max('urutan') + 1,
            'updated_by' => $request->user()->id,
        ]);

        AuditLogHelper::record($fasilitas, 'created', $request->user(), [], $fasilitas->only(['nama', 'kode', 'poin']));

        return back()->with('success', "Parameter fasilitas '{$fasilitas->nama}' berhasil ditambahkan.");
    }

    public function updatePoinFasilitas(Request $request, PoinFasilitas $poin)
    {
        $request->validate([
            'nama' => 'sometimes|required|string|max:255',
            'poin' => 'required|integer|min:0|max:40',
            'keterangan' => 'nullable|string|max:255',
            'is_aktif' => 'nullable|boolean',
        ]);

        $nilaiLama = $poin->only(['nama', 'poin', 'keterangan', 'is_aktif']);

        $poin->update([
            'nama' => $request->input('nama', $poin->nama),
            'poin' => $request->poin,
            'keterangan' => $request->input('keterangan', $poin->keterangan),
            'is_aktif' => $request->has('is_aktif') ? $request->boolean('is_aktif') : $poin->is_aktif,
            'updated_by' => $request->user()->id,
        ]);

        AuditLogHelper::record($poin, 'updated', $request->user(), $nilaiLama, $poin->only(['nama', 'poin', 'keterangan', 'is_aktif']));

        return back()->with('success', "Parameter fasilitas '{$poin->nama}' berhasil diperbarui.");
    }

    public function destroyPoinFasilitas(Request $request, PoinFasilitas $poin)
    {
        $nilaiLama = $poin->only(['nama', 'kode', 'poin']);
        $nama = $poin->nama;
        $poin->delete();

        AuditLogHelper::record($poin, 'deleted', $request->user(), $nilaiLama, []);

        return back()->with('success', "Parameter fasilitas '{$nama}' berhasil dihapus.");
    }

    // ==========================================
    // POIN OKUPANSI
    // ==========================================
    public function storePoinOkupansi(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'poin' => 'required|integer|min:0|max:40',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $kode = Str::slug($request->nama, '_');
        $kodeAwal = $kode;
        $counter = 1;
        while (PoinOkupansi::where('kode', $kode)->exists()) {
            $kode = $kodeAwal.'_'.$counter++;
        }

        $okupansi = PoinOkupansi::create([
            'nama' => trim($request->nama),
            'kode' => $kode,
            'poin' => $request->poin,
            'keterangan' => $request->keterangan,
            'is_aktif' => true,
            'urutan' => PoinOkupansi::max('urutan') + 1,
            'updated_by' => $request->user()->id,
        ]);

        AuditLogHelper::record($okupansi, 'created', $request->user(), [], $okupansi->only(['nama', 'kode', 'poin']));

        return back()->with('success', "Parameter okupansi '{$okupansi->nama}' berhasil ditambahkan.");
    }

    public function updatePoinOkupansi(Request $request, PoinOkupansi $poin)
    {
        $request->validate([
            'nama' => 'sometimes|required|string|max:255',
            'poin' => 'required|integer|min:0|max:40',
            'keterangan' => 'nullable|string|max:255',
            'is_aktif' => 'nullable|boolean',
        ]);

        $nilaiLama = $poin->only(['nama', 'poin', 'keterangan', 'is_aktif']);

        $poin->update([
            'nama' => $request->input('nama', $poin->nama),
            'poin' => $request->poin,
            'keterangan' => $request->input('keterangan', $poin->keterangan),
            'is_aktif' => $request->has('is_aktif') ? $request->boolean('is_aktif') : $poin->is_aktif,
            'updated_by' => $request->user()->id,
        ]);

        AuditLogHelper::record($poin, 'updated', $request->user(), $nilaiLama, $poin->only(['nama', 'poin', 'keterangan', 'is_aktif']));

        return back()->with('success', "Parameter okupansi '{$poin->nama}' berhasil diperbarui.");
    }

    public function destroyPoinOkupansi(Request $request, PoinOkupansi $poin)
    {
        $nilaiLama = $poin->only(['nama', 'kode', 'poin']);
        $nama = $poin->nama;
        $poin->delete();

        AuditLogHelper::record($poin, 'deleted', $request->user(), $nilaiLama, []);

        return back()->with('success', "Parameter okupansi '{$nama}' berhasil dihapus.");
    }

    // ==========================================
    // MITRA MESIN (CRUD)
    // ==========================================
    public function storeMitraMesin(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:mitra_mesin,nama',
            'keterangan' => 'nullable|string|max:255',
            'is_aktif' => 'nullable|boolean',
        ]);

        $mitra = MitraMesin::create([
            'nama' => trim($request->nama),
            'keterangan' => $request->keterangan,
            'is_aktif' => $request->boolean('is_aktif', true),
            'urutan' => MitraMesin::max('urutan') + 1,
            'updated_by' => $request->user()->id,
        ]);

        AuditLogHelper::record($mitra, 'created', $request->user(), [], $mitra->only(['nama', 'keterangan', 'is_aktif']));

        return back()->with('success', "Mitra Mesin '{$mitra->nama}' berhasil ditambahkan.");
    }

    public function updateMitraMesin(Request $request, MitraMesin $mitra)
    {
        $request->validate([
            'nama' => "required|string|max:255|unique:mitra_mesin,nama,{$mitra->id}",
            'keterangan' => 'nullable|string|max:255',
            'is_aktif' => 'nullable|boolean',
        ]);

        $nilaiLama = $mitra->only(['nama', 'keterangan', 'is_aktif']);

        $mitra->update([
            'nama' => trim($request->nama),
            'keterangan' => $request->keterangan,
            'is_aktif' => $request->has('is_aktif') ? $request->boolean('is_aktif') : $mitra->is_aktif,
            'updated_by' => $request->user()->id,
        ]);

        AuditLogHelper::record($mitra, 'updated', $request->user(), $nilaiLama, $mitra->only(['nama', 'keterangan', 'is_aktif']));

        return back()->with('success', "Data Mitra Mesin '{$mitra->nama}' berhasil diperbarui.");
    }

    public function destroyMitraMesin(Request $request, MitraMesin $mitra)
    {
        $nilaiLama = $mitra->only(['nama', 'keterangan', 'is_aktif']);
        $nama = $mitra->nama;
        $mitra->delete();

        AuditLogHelper::record($mitra, 'deleted', $request->user(), $nilaiLama, []);

        return back()->with('success', "Mitra Mesin '{$nama}' berhasil dihapus.");
    }

    // ==========================================
    // TARGET TAHUNAN
    // ==========================================
    public function storeTarget(Request $request)
    {
        $request->validate([
            'tahun' => 'required|integer|unique:target_tahunan,tahun',
            'target_jumlah_spklu' => 'required|integer|min:0',
        ]);

        $target = TargetTahunan::create([
            ...$request->only('tahun', 'target_jumlah_spklu'),
            'updated_by' => $request->user()->id,
        ]);

        AuditLogHelper::record($target, 'created', $request->user(), [], $target->only(['tahun', 'target_jumlah_spklu']));

        return back()->with('success', 'Target tahunan berhasil ditambahkan.');
    }

    public function destroyTarget(Request $request, TargetTahunan $target)
    {
        $nilaiLama = $target->only(['tahun', 'target_jumlah_spklu']);
        $tahun = $target->tahun;
        $target->delete();

        AuditLogHelper::record($target, 'deleted', $request->user(), $nilaiLama, []);

        return back()->with('success', "Target tahun {$tahun} berhasil dihapus.");
    }
}
