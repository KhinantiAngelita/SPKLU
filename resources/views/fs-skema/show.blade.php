@extends('layouts.app')

@section('breadcrumb', 'FS Skema')
@section('page-title', $fsSkema->nama_lokasi)

@push('styles')
<style>
.fsd-header{margin-bottom:24px}
.fsd-header h1{font-size:22px;font-weight:700;color:#0F172A;margin:0}
.fsd-header p{color:#64748B;margin:4px 0 0;font-size:14px}
.fsd-grid{display:grid;grid-template-columns:1.1fr 1fr;gap:20px}
@media (max-width:900px){.fsd-grid{grid-template-columns:1fr}}
.fsd-card{background:#fff;border-radius:14px;box-shadow:0 1px 3px rgba(15,23,42,.08);padding:22px;margin-bottom:20px}
.fsd-card h3{font-size:15px;font-weight:700;color:#0F172A;margin:0 0 14px}
.fsd-detail-row{display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid #F1F5F9;font-size:14px}
.fsd-detail-row:last-child{border-bottom:none}
.fsd-detail-row span:first-child{color:#94A3B8}
.fsd-detail-row span:last-child{color:#0F172A;font-weight:600;text-align:right}
.fsd-poin-table{width:100%;border-collapse:collapse;font-size:13px;margin-top:6px}
.fsd-poin-table th{text-align:left;color:#94A3B8;font-weight:600;padding:8px 0;border-bottom:1px solid #F1F5F9}
.fsd-poin-table td{padding:9px 0;border-bottom:1px solid #F1F5F9;color:#334155}
.fsd-poin-table tr.total td{font-weight:700;color:#0F172A;border-top:2px solid #E2E8F0;border-bottom:none}
.fsd-status-box{margin-top:16px;padding:12px 16px;border-radius:10px;font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px}
.fsd-status-hijau{background:#DCFCE7;color:#15803D}
.fsd-status-kuning{background:#FEF3C7;color:#B45309}
.fsd-status-merah{background:#FEE2E2;color:#B91C1C}
.fsd-mini-table{width:100%;border-collapse:collapse;font-size:12.5px}
.fsd-mini-table th{text-align:left;color:#94A3B8;font-weight:600;padding:8px 6px;border-bottom:1px solid #F1F5F9;white-space:nowrap}
.fsd-mini-table td{padding:9px 6px;border-bottom:1px solid #F1F5F9;color:#334155;white-space:nowrap}
.fsd-jarak-bagus{color:#15803D;font-weight:600}
.fsd-jarak-risiko{color:#B91C1C;font-weight:600}
.fsd-jarak-belum{color:#94A3B8;font-style:italic}
.fsd-narasi{font-size:13.5px;color:#475569;line-height:1.6;background:#FFFBEB;border-left:3px solid #F59E0B;padding:14px 16px;border-radius:8px;margin:0}
.fsd-narasi-title{display:flex;align-items:center;gap:6px;font-weight:700;color:#B45309;margin-bottom:8px;font-size:14px}
.fsd-bep-tag{font-size:11px;background:#0EA5B7;color:#fff;padding:2px 8px;border-radius:999px;font-weight:600;margin-left:6px}
.fsd-back{display:inline-flex;align-items:center;gap:4px;color:#64748B;font-size:13px;text-decoration:none;margin-bottom:14px}
.fsd-bep-caption{font-size:12.5px;color:#64748B;margin-top:10px}
.fsd-bep-caption strong{color:#0EA5B7}
</style>
@endpush

@section('content')
<a href="{{ route('fs-skema.index') }}" class="fsd-back">&larr; Kembali ke daftar</a>

<div class="fsd-header">
    <h1>FS Skema — {{ $fsSkema->nama_lokasi }}</h1>
    <p>
        {{ $fsSkema->skema === 'skema_2' ? 'Skema 2' : 'Skema 3' }}
        @if($fsSkema->kandidat) &middot; Kandidat: {{ $fsSkema->kandidat->nama_lokasi ?? $fsSkema->kandidat->lokasi }} @endif
    </p>
</div>

<div class="fsd-grid">
    {{-- KOLOM KIRI --}}
    <div>
        <div class="fsd-card">
            <h3>Ringkasan Lokasi</h3>
            <div class="fsd-detail-row"><span>Titik Koordinat</span><span>{{ $fsSkema->titik_koordinat ?? '—' }}</span></div>
            <div class="fsd-detail-row"><span>Layanan Listrik</span><span>{{ $fsSkema->layanan_listrik ?? '—' }}</span></div>

            @if ($fsSkema->skema === 'skema_2')
                <div class="fsd-detail-row"><span>Total RAB Investasi</span><span>Rp {{ number_format($fsSkema->total_rab_investasi ?? 0, 0, ',', '.') }}</span></div>
            @else
                <div class="fsd-detail-row"><span>RAB Mitra Mesin</span><span>Rp {{ number_format($fsSkema->rab_mitra_mesin ?? 0, 0, ',', '.') }}</span></div>
                <div class="fsd-detail-row"><span>RAB Mitra Lahan</span><span>Rp {{ number_format($fsSkema->rab_mitra_lahan ?? 0, 0, ',', '.') }}</span></div>
                <div class="fsd-detail-row"><span>Sharing Profit Mitra Lahan</span><span>{{ ($fsSkema->sharing_provit_mitra_lahan ?? 0) * 100 }}%</span></div>
            @endif

            <div class="fsd-detail-row"><span>Mobil/hari</span><span>{{ $fsSkema->mobil_per_hari }}</span></div>
            <div class="fsd-detail-row"><span>Transaksi kWh/Mobil</span><span>{{ $fsSkema->transaksi_kwh_per_mobil }}</span></div>
        </div>

        <div class="fsd-card">
            <h3>Ringkasan Kelayakan Lokasi</h3>
            <table class="fsd-poin-table">
                <thead><tr><th>Komponen</th><th>Poin</th><th>Maksimal</th></tr></thead>
                <tbody>
                    <tr><td>Fasilitas</td><td>{{ $fsSkema->poin_fasilitas }}</td><td>40</td></tr>
                    <tr><td>Kesiapan Jaringan</td><td>{{ $fsSkema->poin_kesiapan_jaringan }}</td><td>20</td></tr>
                    <tr><td>Okupansi</td><td>{{ $fsSkema->poin_okupansi }}</td><td>40</td></tr>
                    <tr class="total"><td>TOTAL</td><td>{{ $fsSkema->total_poin }}</td><td>100</td></tr>
                </tbody>
            </table>

            <div class="fsd-status-box fsd-status-{{ $fsSkema->status_kelayakan === 'Layak' ? 'hijau' : ($fsSkema->status_kelayakan === 'Menjadi Pertimbangan' ? 'kuning' : 'merah') }}">
                <i data-lucide="alert-circle"></i> Status Kelayakan: {{ $fsSkema->status_kelayakan }}
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN --}}
    <div>
        <div class="fsd-card">
            <h3>3 SPKLU Terdekat</h3>
            @if (empty($spkluTerdekat))
                <p style="font-size:13px;color:#94A3B8">Titik koordinat belum diisi, atau belum ada SPKLU aktif dengan koordinat lengkap.</p>
            @else
                <table class="fsd-mini-table">
                    <thead>
                        <tr><th>Nama SPKLU</th><th>Kapasitas</th><th>Jarak</th><th>Status Jarak</th><th>Rata² Transaksi kWh/bulan</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($spkluTerdekat as $s)
                            <tr>
                                <td>{{ $s['nama'] }}</td>
                                <td>{{ $s['kapasitas_kw'] ?? '—' }} kW</td>
                                <td>{{ number_format($s['jarak_km'], 2) }} km</td>
                                <td>
                                    @if ($s['status_jarak'] === 'Bagus')
                                        <span class="fsd-jarak-bagus">Bagus</span>
                                    @elseif ($s['status_jarak'] === 'Tidak Bagus (berisiko kanibalisasi)')
                                        <span class="fsd-jarak-risiko">Berisiko Kanibalisasi</span>
                                    @else
                                        <span class="fsd-jarak-belum">Jarak ideal ULP belum diatur</span>
                                    @endif
                                </td>
                                <td>{{ number_format($s['rata_rata_transaksi_kwh_bulan'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="fsd-card">
            <h3>Proyeksi ROI 5 Tahun</h3>

            @if ($proyeksiRoi['tipe'] === 'skema_2')
                <table class="fsd-mini-table">
                    <thead><tr><th>Tahun</th><th>Mobil/hari</th><th>Energi (kWh)</th><th>Pendapatan</th><th>Kumulatif</th></tr></thead>
                    <tbody>
                        @foreach ($proyeksiRoi['tahunan'] as $row)
                            <tr>
                                <td>{{ $row['tahun'] }} @if($row['sudah_bep'])<span class="fsd-bep-tag">BEP</span>@endif</td>
                                <td>{{ number_format($row['mobil_per_hari'], 1) }}</td>
                                <td>{{ number_format($row['energi_kwh_per_tahun'], 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($row['pendapatan_mitra'], 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($row['kumulatif'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <p class="fsd-bep-caption">
                    Estimasi BEP:
                    @if ($proyeksiRoi['estimasi_bep'])
                        <strong>bulan ke-{{ $proyeksiRoi['estimasi_bep'] }}</strong>
                    @else
                        <span style="color:#94A3B8">belum tercapai dalam proyeksi 5 tahun</span>
                    @endif
                </p>
            @else
                <table class="fsd-mini-table">
                    <thead><tr><th>Tahun</th><th>Energi (kWh)</th><th>Pendpt. Mesin</th><th>Kumulatif Mesin</th><th>Pendpt. Lahan</th><th>Kumulatif Lahan</th></tr></thead>
                    <tbody>
                        @foreach ($proyeksiRoi['tahunan'] as $row)
                            <tr>
                                <td>{{ $row['tahun'] }}</td>
                                <td>{{ number_format($row['energi_kwh_per_tahun'], 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($row['pendapatan_mesin'], 0, ',', '.') }} @if($row['sudah_bep_mesin'])<span class="fsd-bep-tag">BEP</span>@endif</td>
                                <td>Rp {{ number_format($row['kumulatif_mesin'], 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($row['pendapatan_lahan'], 0, ',', '.') }} @if($row['sudah_bep_lahan'])<span class="fsd-bep-tag">BEP</span>@endif</td>
                                <td>Rp {{ number_format($row['kumulatif_lahan'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <p class="fsd-bep-caption">
                    Estimasi BEP Mitra Mesin:
                    <strong>{{ $proyeksiRoi['estimasi_bep_mesin'] ? 'bulan ke-'.$proyeksiRoi['estimasi_bep_mesin'] : 'belum tercapai' }}</strong>
                    &nbsp;|&nbsp; Mitra Lahan:
                    <strong>{{ $proyeksiRoi['estimasi_bep_lahan'] ? 'bulan ke-'.$proyeksiRoi['estimasi_bep_lahan'] : 'belum tercapai' }}</strong>
                </p>
            @endif
        </div>

        <div class="fsd-card" style="margin-bottom:0">
            <div class="fsd-narasi-title"><i data-lucide="sparkles"></i> Ringkasan Analisis</div>
            <p class="fsd-narasi">{{ $fsSkema->narasi_analisis }}</p>
        </div>
    </div>
</div>
@endsection