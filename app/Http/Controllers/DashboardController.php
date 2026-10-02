<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Probabilitas;
use App\Models\Spklu;
use App\Models\TargetTahunan;
use App\Models\Transaksi;
use App\Models\User;
use App\Services\KandidatPeringkatService;
use App\Services\RekomendasiLokasiService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct(
        private KandidatPeringkatService $peringkatService,
        private RekomendasiLokasiService $rekomendasiLokasiService,
    ) {}

    public function index(Request $request)
    {
        $user = $request->user();
        $userUp3 = $user?->up3;
        $isSuperAdmin = $user?->role === 'super_admin';

        $selectedUp3 = $request->get('up3');
        if (! $isSuperAdmin && $userUp3) {
            $selectedUp3 = $userUp3;
        }

        $minTanggal = Transaksi::when($selectedUp3, fn ($q) => $q->whereHas('spklu', fn ($s) => $s->where('up3', $selectedUp3)))->min('tanggal');
        $maxTanggal = Transaksi::when($selectedUp3, fn ($q) => $q->whereHas('spklu', fn ($s) => $s->where('up3', $selectedUp3)))->max('tanggal');

        $defaultDari = $minTanggal ? Carbon::parse($minTanggal)->format('Y-m') : now()->startOfYear()->format('Y-m');
        $defaultSampai = $maxTanggal ? Carbon::parse($maxTanggal)->format('Y-m') : now()->format('Y-m');

        $dariBulan = $request->dari_bulan ?: $defaultDari;
        $sampaiBulan = $request->sampai_bulan ?: $defaultSampai;

        $mulai = Carbon::createFromFormat('Y-m', $dariBulan)->startOfMonth();
        $sampai = Carbon::createFromFormat('Y-m', $sampaiBulan)->endOfMonth();

        $trenTransaksi = $this->buildTrenTransaksi($mulai, $sampai, $selectedUp3);

        // ===== Kandidat & Pengajuan — tersambung ke Probabilitas =====
        $probabilitasAktif = Probabilitas::whereNull('spklu_id')
            ->with('riwayatTahapan')
            ->get();

        $kandidatAktif = $probabilitasAktif->count();

        $kandidatButuhTindakLanjut = $probabilitasAktif->filter(function ($p) {
            return collect($p->badgePerTahap())->contains(fn ($b) => $b['warna'] === 'kuning');
        })->count();

        $pengajuanOnProgress = $probabilitasAktif->filter(
            fn ($p) => $p->statusKanban() === 'on_progress'
        )->count();

        $topKandidat = $this->peringkatService->top(5)->map(fn ($k) => (object) [
            'nama' => $k->nama_lokasi,
            'wilayah' => $k->ulpMapping->nama_penuh ?? '-',
            'skor' => $k->skor_akhir,
        ]);

        $pengajuanTerbaru = Probabilitas::with('riwayatTahapan')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($p) => (object) [
                'id' => $p->id,
                'lokasi' => $p->lokasi,
                'ulp' => $p->ulp,
                'tahap_saat_ini' => $p->tahapSaatIni(),
                'status_kanban' => $p->statusKanban(),
                'diajukan_pada' => $p->created_at,
            ]);

        // ===== Ringkasan Keuangan & Energi bulan berjalan vs bulan lalu =====
        $ringkasanKeuangan = $this->buildRingkasanKeuangan($selectedUp3);

        // ===== Top 5 SPKLU Berkinerja Tertinggi (Existing Assets) =====
        $topSpkluPerforma = Transaksi::query()
            ->whereNotNull('spklu_id')
            ->whereHas('spklu')
            ->when($selectedUp3, fn ($q) => $q->whereHas('spklu', fn ($s) => $s->where('up3', $selectedUp3)))
            ->whereBetween('tanggal', [$mulai, $sampai])
            ->groupBy('spklu_id')
            ->selectRaw('spklu_id, SUM(jumlah_transaksi) as total_transaksi, SUM(energi_kwh) as total_energi, SUM(pendapatan_rp) as total_pendapatan')
            ->orderByDesc('total_energi')
            ->take(5)
            ->with(['spklu.ulp'])
            ->get();

        // ===== Ringkasan Rekomendasi Lokasi (bagian "murah", tanpa grid scan) =====
        $zonaSpklu = $this->rekomendasiLokasiService->hitungZonaSpklu();
        $wilayahPotensialTop = $this->rekomendasiLokasiService->hitungRekomendasiWilayah()->first();

        $spkluQuery = Spklu::aktif()->when($selectedUp3, fn ($q) => $q->where('up3', $selectedUp3));
        $totalSpkluTerpasang = (clone $spkluQuery)->count();
        $spkluBaruBulanIni = (clone $spkluQuery)->whereMonth('created_at', now()->month)->count();

        // ===== Target Tahunan SPKLU dari Master Parameter =====
        $targetTahunan = TargetTahunan::where('tahun', now()->year)->value('target_jumlah_spklu');

        return view('dashboard.index', [
            'totalSpkluTerpasang' => $totalSpkluTerpasang,
            'spkluBaruBulanIni' => $spkluBaruBulanIni,
            'targetTahunan' => $targetTahunan,

            'selectedUp3' => $selectedUp3,
            'userUp3' => $userUp3,
            'isSuperAdmin' => $isSuperAdmin,
            'daftarUp3' => User::DAFTAR_UP3,

            'pengajuanOnProgress' => $pengajuanOnProgress,
            'kandidatAktif' => $kandidatAktif,
            'kandidatButuhTindakLanjut' => $kandidatButuhTindakLanjut,

            'jadwalHariIni' => Jadwal::whereDate('waktu_mulai', today())
                ->when($selectedUp3, fn ($q) => $q->whereHas('probabilitas', fn ($p) => $p->where('up3', $selectedUp3)))
                ->orderBy('waktu_mulai')
                ->get(),
            'jadwalBesok' => Jadwal::whereDate('waktu_mulai', today()->addDay())
                ->when($selectedUp3, fn ($q) => $q->whereHas('probabilitas', fn ($p) => $p->where('up3', $selectedUp3)))
                ->orderBy('waktu_mulai')
                ->get(),
            'kalenderBulanIni' => $this->buildKalenderData($selectedUp3),

            'topKandidat' => $topKandidat,
            'topSpkluPerforma' => $topSpkluPerforma,
            'pengajuanTerbaru' => $pengajuanTerbaru,

            'ringkasanKeuangan' => $ringkasanKeuangan,
            'ringkasanZona' => $zonaSpklu['ringkasan'],
            'wilayahPotensialTop' => $wilayahPotensialTop,

            'dariBulan' => $dariBulan,
            'sampaiBulan' => $sampaiBulan,
            'trenTransaksiPersen' => $trenTransaksi['persen'],
            'trenTransaksiLabels' => $trenTransaksi['labels'],
            'trenTransaksiData' => $trenTransaksi['data'],
        ]);
    }

    /**
     * Pendapatan & energi bulan berjalan vs bulan lalu.
     * Menggunakan bulan data transaksi terkini bila bulan kalender saat ini belum memiliki transaksi upload.
     */
    private function buildRingkasanKeuangan(?string $selectedUp3 = null): array
    {
        $latestDate = Transaksi::when($selectedUp3, fn ($q) => $q->whereHas('spklu', fn ($s) => $s->where('up3', $selectedUp3)))->max('tanggal');
        $bulanAcuan = ($latestDate && Transaksi::when($selectedUp3, fn ($q) => $q->whereHas('spklu', fn ($s) => $s->where('up3', $selectedUp3)))->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->doesntExist())
            ? Carbon::parse($latestDate)
            : now();

        $bulanIni = $bulanAcuan;
        $bulanLalu = $bulanIni->copy()->subMonth();

        $agregatBulanIni = Transaksi::query()
            ->when($selectedUp3, fn ($q) => $q->whereHas('spklu', fn ($s) => $s->where('up3', $selectedUp3)))
            ->whereMonth('tanggal', $bulanIni->month)
            ->whereYear('tanggal', $bulanIni->year)
            ->selectRaw('SUM(pendapatan_rp) as pendapatan, SUM(energi_kwh) as energi')
            ->first();

        $agregatBulanLalu = Transaksi::query()
            ->when($selectedUp3, fn ($q) => $q->whereHas('spklu', fn ($s) => $s->where('up3', $selectedUp3)))
            ->whereMonth('tanggal', $bulanLalu->month)
            ->whereYear('tanggal', $bulanLalu->year)
            ->selectRaw('SUM(pendapatan_rp) as pendapatan, SUM(energi_kwh) as energi')
            ->first();

        $pendapatanBulanIni = (float) ($agregatBulanIni->pendapatan ?? 0);
        $pendapatanBulanLalu = (float) ($agregatBulanLalu->pendapatan ?? 0);
        $energiBulanIni = (float) ($agregatBulanIni->energi ?? 0);
        $energiBulanLalu = (float) ($agregatBulanLalu->energi ?? 0);

        $hitungTren = function ($sekarang, $dulu) {
            if (! $dulu || $dulu == 0) {
                return $sekarang > 0 ? 100.0 : 0.0;
            }

            return round((($sekarang - $dulu) / $dulu) * 100, 1);
        };

        // Reduksi Emisi: faktor emisi EV terhindar ~0.85 kg CO2/kWh; Ekuivalen BBM ~0.35 Liter/kWh
        $reduksiCo2Kg = round($energiBulanIni * 0.85, 1);
        $bensinSavedLiter = round($energiBulanIni * 0.35, 1);

        return [
            'pendapatan_bulan_ini' => $pendapatanBulanIni,
            'tren_pendapatan_persen' => $hitungTren($pendapatanBulanIni, $pendapatanBulanLalu),
            'energi_bulan_ini' => $energiBulanIni,
            'tren_energi_persen' => $hitungTren($energiBulanIni, $energiBulanLalu),
            'nama_bulan' => $bulanIni->translatedFormat('F Y'),
            'reduksi_co2_kg' => $reduksiCo2Kg,
            'bensin_saved_liter' => $bensinSavedLiter,
        ];
    }

    /**
     * Agregat jumlah transaksi per bulan dari tabel transaksis (data hasil upload),
     * plus persentase perubahan dibanding periode sebelumnya dengan panjang yang sama.
     */
    private function buildTrenTransaksi(Carbon $mulai, Carbon $sampai, ?string $selectedUp3 = null): array
    {
        $driver = DB::connection()->getDriverName();
        $formatSql = $driver === 'sqlite'
            ? "strftime('%Y-%m', tanggal)"
            : "DATE_FORMAT(tanggal, '%Y-%m')";

        $data = Transaksi::query()
            ->when($selectedUp3, fn ($q) => $q->whereHas('spklu', fn ($s) => $s->where('up3', $selectedUp3)))
            ->whereBetween('tanggal', [$mulai, $sampai])
            ->selectRaw("{$formatSql} as bulan, SUM(jumlah_transaksi) as total")
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $totalSekarang = (int) $data->sum('total');

        $durasiHari = $mulai->diffInDays($sampai) + 1;
        $mulaiSebelumnya = $mulai->copy()->subDays($durasiHari);
        $sampaiSebelumnya = $mulai->copy()->subDay();

        $totalSebelumnya = (int) Transaksi::query()
            ->when($selectedUp3, fn ($q) => $q->whereHas('spklu', fn ($s) => $s->where('up3', $selectedUp3)))
            ->whereBetween('tanggal', [$mulaiSebelumnya, $sampaiSebelumnya])
            ->sum('jumlah_transaksi');

        $persen = ! $totalSebelumnya
            ? ($totalSekarang > 0 ? 100 : 0)
            : round((($totalSekarang - $totalSebelumnya) / $totalSebelumnya) * 100, 1);

        return [
            'labels' => $data->pluck('bulan')
                ->map(fn ($b) => Carbon::createFromFormat('Y-m', $b)->translatedFormat('M Y'))
                ->toArray(),
            'data' => $data->pluck('total')->toArray(),
            'persen' => $persen,
        ];
    }

    private function buildKalenderData(?string $selectedUp3 = null): array
    {
        $bulan = now();
        $jadwalBulanIni = Jadwal::whereMonth('waktu_mulai', $bulan->month)
            ->whereYear('waktu_mulai', $bulan->year)
            ->when($selectedUp3, fn ($q) => $q->whereHas('probabilitas', fn ($p) => $p->where('up3', $selectedUp3)))
            ->get()
            ->groupBy(fn ($j) => $j->waktu_mulai->format('j'));

        return [
            'bulan' => $bulan->translatedFormat('F Y'),
            'tanggalBerjadwal' => $jadwalBulanIni->keys()->toArray(),
        ];
    }
}
