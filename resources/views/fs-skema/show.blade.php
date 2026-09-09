@extends('layouts.app')

@section('title', $fsSkema->nama_lokasi)

@section('content')
<div class="page-header">
    <h1>FS Skema — {{ $fsSkema->nama_lokasi }}</h1>
    <p class="page-subtitle">{{ $fsSkema->skema === 'skema_2' ? 'Skema 2' : 'Skema 3' }}</p>
</div>

<div class="two-column-layout">
    <div class="card">
        <h3>Ringkasan Lokasi</h3>
        <table class="table-detail">
            <tr><td>Titik Koordinat</td><td>{{ $fsSkema->titik_koordinat ?? '—' }}</td></tr>
            <tr><td>Total RAB Investasi</td><td>Rp {{ number_format($fsSkema->total_rab_investasi, 0, ',', '.') }}</td></tr>
            <tr><td>Mobil/hari</td><td>{{ $fsSkema->mobil_per_hari }}</td></tr>
            <tr><td>Layanan Listrik</td><td>{{ $fsSkema->layanan_listrik ?? '—' }}</td></tr>
            <tr><td>Transaksi kWh/Mobil</td><td>{{ $fsSkema->transaksi_kwh_per_mobil }}</td></tr>
        </table>

        <hr class="section-divider">

        <div class="card surface-highlight">
            <h4>Ringkasan Kelayakan Lokasi</h4>
            <table class="table-default">
                <thead>
                    <tr><th>Komponen</th><th>Poin</th><th>Maksimal</th></tr>
                </thead>
                <tbody>
                    <tr><td>Fasilitas</td><td>{{ $fsSkema->poin_fasilitas }}</td><td>40</td></tr>
                    <tr><td>Kesiapan Jaringan</td><td>{{ $fsSkema->poin_kesiapan_jaringan }}</td><td>20</td></tr>
                    <tr><td>Okupansi</td><td>{{ $fsSkema->poin_okupansi }}</td><td>40</td></tr>
                    <tr class="row-total"><td><strong>TOTAL</strong></td><td><strong>{{ $fsSkema->total_poin }}</strong></td><td><strong>100</strong></td></tr>
                </tbody>
            </table>

            <div class="alert alert-{{ $fsSkema->status_kelayakan === 'Layak' ? 'success' : ($fsSkema->status_kelayakan === 'Menjadi Pertimbangan' ? 'warning' : 'error') }}">
                <i data-lucide="alert-circle"></i> Status Kelayakan: {{ $fsSkema->status_kelayakan }}
            </div>
        </div>

        @if (!$fsSkema->pengajuan && $fsSkema->status_kelayakan !== 'Tidak Layak')
            @can('create', \App\Models\Pengajuan::class)
                <a href="{{ route('monitoring.pengajuan.create', ['fs_skema_id' => $fsSkema->id]) }}" class="btn btn-primary" style="margin-top:12px;">
                    Ajukan ke Monitoring Pengajuan
                </a>
            @endcan
        @elseif ($fsSkema->pengajuan)
            <a href="{{ route('monitoring.pengajuan.show', $fsSkema->pengajuan) }}" class="btn btn-outline" style="margin-top:12px;">
                Lihat Pengajuan Terkait ({{ $fsSkema->pengajuan->id_pengajuan }})
            </a>
        @endif
    </div>

    <div class="side-column">
        <div class="card">
            <h3>Proyeksi ROI 5 Tahun</h3>
            <table class="table-default">
                <thead>
                    <tr><th>Tahun</th><th>Mobil/hari</th><th>Transaksi/Tahun</th><th>Energi (kWh/tahun)</th><th>Pendapatan Mitra</th></tr>
                </thead>
                <tbody>
                    @foreach ($proyeksiRoi as $row)
                        <tr>
                            <td>{{ $row['tahun'] }}</td>
                            <td>{{ $row['mobil_per_hari'] }}</td>
                            <td>{{ number_format($row['transaksi_per_tahun'], 0, ',', '.') }}</td>
                            <td>{{ number_format($row['energi_kwh_per_tahun'], 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($row['pendapatan_mitra'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card alert-panel">
            <h4><i data-lucide="alert-circle"></i> Ringkasan Analisis</h4>
            <p>{{ $fsSkema->narasi_analisis }}</p>
        </div>
    </div>
</div>
@endsection