<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Spklu;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $dariBulan = $request->dari_bulan ?: now()->subMonths(5)->format('Y-m');
        $sampaiBulan = $request->sampai_bulan ?: now()->format('Y-m');

        $mulai = Carbon::createFromFormat('Y-m', $dariBulan)->startOfMonth();
        $sampai = Carbon::createFromFormat('Y-m', $sampaiBulan)->endOfMonth();

        $trenTransaksi = $this->buildTrenTransaksi($mulai, $sampai);

        return view('dashboard.index', [
            'totalSpkluTerpasang' => Spklu::aktif()->count(),
            'spkluBaruBulanIni' => Spklu::aktif()->whereMonth('created_at', now()->month)->count(),

            'pengajuanOnProgress' => 0,   // ganti: app(PengajuanService::class)->countOnProgress()
            'kandidatAktif' => 0,         // ganti: app(SkorKandidatService::class)->countAktif()
            'kandidatButuhTindakLanjut' => 0,

            'jadwalHariIni' => Jadwal::whereDate('waktu_mulai', today())->orderBy('waktu_mulai')->get(),
            'jadwalBesok' => Jadwal::whereDate('waktu_mulai', today()->addDay())->orderBy('waktu_mulai')->get(),
            'kalenderBulanIni' => $this->buildKalenderData(),

            'topKandidat' => [], // nanti diisi array of object {nama, wilayah, skor} dari Service Person B

            'dariBulan' => $dariBulan,
            'sampaiBulan' => $sampaiBulan,
            'trenTransaksiPersen' => $trenTransaksi['persen'],
            'trenTransaksiLabels' => $trenTransaksi['labels'],
            'trenTransaksiData' => $trenTransaksi['data'],
        ]);
    }

    /**
     * Agregat jumlah transaksi per bulan dari tabel transaksis (data hasil upload),
     * plus persentase perubahan dibanding periode sebelumnya dengan panjang yang sama.
     */
    private function buildTrenTransaksi(Carbon $mulai, Carbon $sampai): array
    {
        $data = Transaksi::query()
            ->whereBetween('tanggal', [$mulai, $sampai])
            ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan, SUM(jumlah_transaksi) as total")
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