<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Probabilitas;
use App\Models\Spklu;
use App\Models\Transaksi;
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
        $dariBulan = $request->dari_bulan ?: now()->subMonths(5)->format('Y-m');
        $sampaiBulan = $request->sampai_bulan ?: now()->format('Y-m');

        $mulai = Carbon::createFromFormat('Y-m', $dariBulan)->startOfMonth();
        $sampai = Carbon::createFromFormat('Y-m', $sampaiBulan)->endOfMonth();

        $trenTransaksi = $this->buildTrenTransaksi($mulai, $sampai);

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
                'lokasi' => $p->lokasi,
                'ulp' => $p->ulp,
                'tahap_saat_ini' => $p->tahapSaatIni(),
                'status_kanban' => $p->statusKanban(),
                'diajukan_pada' => $p->created_at,
            ]);

        // ===== Ringkasan Keuangan & Energi bulan berjalan vs bulan lalu =====
        $ringkasanKeuangan = $this->buildRingkasanKeuangan();

        // ===== Ringkasan Rekomendasi Lokasi (bagian "murah", tanpa grid scan) =====
        $zonaSpklu = $this->rekomendasiLokasiService->hitungZonaSpklu();
        $wilayahPotensialTop = $this->rekomendasiLokasiService->hitungRekomendasiWilayah()->first();

        return view('dashboard.index', [
            'totalSpkluTerpasang' => Spklu::aktif()->count(),
            'spkluBaruBulanIni' => Spklu::aktif()->whereMonth('created_at', now()->month)->count(),

            'pengajuanOnProgress' => $pengajuanOnProgress,
            'kandidatAktif' => $kandidatAktif,
            'kandidatButuhTindakLanjut' => $kandidatButuhTindakLanjut,

            'jadwalHariIni' => Jadwal::whereDate('waktu_mulai', today())->orderBy('waktu_mulai')->get(),
            'jadwalBesok' => Jadwal::whereDate('waktu_mulai', today()->addDay())->orderBy('waktu_mulai')->get(),
            'kalenderBulanIni' => $this->buildKalenderData(),

            'topKandidat' => $topKandidat,
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
     * Pendapatan & energi bulan berjalan vs bulan lalu — sebelumnya
     * Dashboard sama sekali gak nampilin angka uang/energi (cuma ada di
     * halaman Transaksi terpisah), padahal itu KPI inti buat manajemen.
     */
    private function buildRingkasanKeuangan(): array
    {
        $bulanIni = now();
        $bulanLalu = $bulanIni->copy()->subMonth();

        $agregatBulanIni = Transaksi::query()
            ->whereMonth('tanggal', $bulanIni->month)
            ->whereYear('tanggal', $bulanIni->year)
            ->selectRaw('SUM(pendapatan_rp) as pendapatan, SUM(energi_kwh) as energi')
            ->first();

        $agregatBulanLalu = Transaksi::query()
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

        return [
            'pendapatan_bulan_ini' => $pendapatanBulanIni,
            'tren_pendapatan_persen' => $hitungTren($pendapatanBulanIni, $pendapatanBulanLalu),
            'energi_bulan_ini' => $energiBulanIni,
            'tren_energi_persen' => $hitungTren($energiBulanIni, $energiBulanLalu),
            'nama_bulan' => $bulanIni->translatedFormat('F Y'),
        ];
    }

    /**
     * Agregat jumlah transaksi per bulan dari tabel transaksis (data hasil upload),
     * plus persentase perubahan dibanding periode sebelumnya dengan panjang yang sama.
     */
    private function buildTrenTransaksi(Carbon $mulai, Carbon $sampai): array
    {
        $driver = DB::connection()->getDriverName();
        $formatSql = $driver === 'sqlite'
            ? "strftime('%Y-%m', tanggal)"
            : "DATE_FORMAT(tanggal, '%Y-%m')";

        $data = Transaksi::query()
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

    private function buildKalenderData(): array
    {
        $bulan = now();
        $jadwalBulanIni = Jadwal::whereMonth('waktu_mulai', $bulan->month)
            ->whereYear('waktu_mulai', $bulan->year)
            ->get()
            ->groupBy(fn ($j) => $j->waktu_mulai->format('j'));

        return [
            'bulan' => $bulan->translatedFormat('F Y'),
            'tanggalBerjadwal' => $jadwalBulanIni->keys()->toArray(),
        ];
    }
}
