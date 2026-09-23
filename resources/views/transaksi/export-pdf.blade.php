<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Ringkasan Transaksi SPKLU</title>
    <style>
        @page {
            margin: 28px 30px 42px 30px;
            @bottom-right {
                content: "Halaman " counter(page);
                font-size: 8px;
                color: #64748B;
            }
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 9px;
            color: #0F172A;
            line-height: 1.35;
            background: #ffffff;
            margin: 0;
            padding: 0;
        }

        /* HEADER KOP */
        .kop-table {
            width: 100%;
            border-bottom: 2.5px solid #023E8A;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }
        .kop-table td {
            vertical-align: middle;
            border: none;
            padding: 0;
        }
        .kop-title-sub {
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #0081AB;
            margin: 0 0 2px 0;
        }
        .kop-title-main {
            font-size: 16px;
            font-weight: 800;
            color: #1B2559;
            letter-spacing: -0.2px;
            margin: 0 0 3px 0;
        }
        .kop-title-desc {
            font-size: 8.5px;
            color: #64748B;
            margin: 0;
        }
        .kop-meta {
            text-align: right;
            font-size: 8px;
            color: #475569;
            line-height: 1.45;
        }
        .kop-meta strong {
            color: #1B2559;
        }
        .kop-meta-badge {
            display: inline-block;
            background: #E0F2FE;
            color: #0369A1;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 3px;
            margin-bottom: 3px;
        }

        /* SECTION TITLE */
        .section-header {
            margin: 14px 0 8px 0;
            padding-bottom: 4px;
            border-bottom: 1.5px solid #E2E8F0;
        }
        .section-title {
            font-size: 10.5px;
            font-weight: 800;
            color: #1B2559;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
        }
        .section-subtitle {
            font-size: 8px;
            color: #64748B;
            margin-left: 6px;
        }

        /* KPI SUMMARY CARDS */
        .kpi-table {
            width: 100%;
            margin-bottom: 14px;
            border-collapse: separate;
            border-spacing: 6px 0;
        }
        .kpi-card {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-top: 3px solid #0081AB;
            border-radius: 4px;
            padding: 8px 10px;
            vertical-align: top;
        }
        .kpi-label {
            font-size: 7.5px;
            font-weight: 700;
            color: #64748B;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 3px;
        }
        .kpi-value {
            font-size: 13px;
            font-weight: 800;
            color: #1B2559;
            line-height: 1.15;
            margin-bottom: 2px;
        }
        .kpi-sub {
            font-size: 7.5px;
            color: #64748B;
        }

        /* TABLES */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            font-size: 8.5px;
        }
        .data-table th {
            background: #023E8A;
            color: #ffffff;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 7.5px;
            letter-spacing: 0.3px;
            padding: 6px 7px;
            border: 1px solid #023E8A;
            text-align: left;
        }
        .data-table th.text-right {
            text-align: right;
        }
        .data-table th.text-center {
            text-align: center;
        }
        .data-table td {
            padding: 5px 7px;
            border-bottom: 1px solid #E2E8F0;
            border-right: 1px solid #F1F5F9;
            border-left: 1px solid #F1F5F9;
            color: #1E293B;
        }
        .data-table td.text-right {
            text-align: right;
        }
        .data-table td.text-center {
            text-align: center;
        }
        .data-table tbody tr:nth-child(even) {
            background: #F8FAFC;
        }
        .data-table tfoot td {
            background: #F1F5F9;
            font-weight: 800;
            color: #0F172A;
            border-top: 1.5px solid #CBD5E1;
            border-bottom: 1.5px solid #CBD5E1;
            padding: 6px 7px;
        }

        .badge-unit {
            font-family: monospace;
            background: #F1F5F9;
            border: 1px solid #E2E8F0;
            padding: 1px 4px;
            border-radius: 3px;
            font-size: 7.5px;
            color: #334155;
        }

        /* SIGNATURE SECTION */
        .signature-table {
            width: 100%;
            margin-top: 24px;
            border-collapse: collapse;
            page-break-inside: avoid;
        }
        .signature-table td {
            width: 50%;
            vertical-align: top;
            border: none;
            padding: 0 20px;
        }
        .sig-box {
            font-size: 8.5px;
            color: #334155;
            line-height: 1.4;
        }
        .sig-space {
            height: 52px;
        }
        .sig-name {
            font-weight: 800;
            color: #0F172A;
            text-decoration: underline;
        }
        .sig-title {
            font-size: 8px;
            color: #64748B;
        }

        /* FOOTER NOTE */
        .footer-note {
            margin-top: 18px;
            padding-top: 6px;
            border-top: 1px dashed #CBD5E1;
            font-size: 7.5px;
            color: #94A3B8;
            text-align: center;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

    <!-- KOP LAPORAN -->
    <table class="kop-table">
        <tr>
            <td style="width: 58%;">
                <div class="kop-title-sub">PT PLN (PERSERO) &bull; MONITORING SPKLU</div>
                <div class="kop-title-main">LAPORAN RINGKASAN TRANSAKSI SPKLU</div>
                <div class="kop-title-desc">Sistem Monitoring & Evaluasi Kinerja Pengisian Kendaraan Listrik</div>
            </td>
            <td style="width: 42%;">
                <div class="kop-meta">
                    <span class="kop-meta-badge">{{ $spkluTerpilih ? 'SPKLU TUNGGAL' : 'SEMUA SPKLU ('.$totalSpkluAktif.' UNIT)' }}</span><br>
                    <strong>Periode:</strong> {{ $mulai->translatedFormat('d F Y') }} &ndash; {{ $sampai->translatedFormat('d F Y') }}<br>
                    <strong>Cakupan:</strong> {{ $spkluTerpilih ? $spkluTerpilih->nama : 'Seluruh Unit SPKLU Aktif' }}<br>
                    <strong>Dicetak:</strong> {{ now()->translatedFormat('d F Y, H:i') }} WIB
                </div>
            </td>
        </tr>
    </table>

    <!-- RINGKASAN EKSEKUTIF / KPI -->
    <table class="kpi-table">
        <tr>
            <td class="kpi-card" style="width: 25%;">
                <div class="kpi-label">Total Transaksi</div>
                <div class="kpi-value">{{ number_format($totalTransaksi, 0, ',', '.') }}</div>
                <div class="kpi-sub">Sesi pengisian berhasil</div>
            </td>
            <td class="kpi-card" style="width: 25%;">
                <div class="kpi-label">Energi Terdistribusi</div>
                <div class="kpi-value">{{ number_format($totalEnergi, 1, ',', '.') }} <span style="font-size:9px; font-weight:normal;">kWh</span></div>
                <div class="kpi-sub">Rata-rata {{ number_format($rataKwh, 1, ',', '.') }} kWh/transaksi</div>
            </td>
            <td class="kpi-card" style="width: 25%;">
                <div class="kpi-label">Total Pendapatan</div>
                <div class="kpi-value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                <div class="kpi-sub">Rata-rata Rp {{ number_format($rataRp, 0, ',', '.') }}/trx</div>
            </td>
            <td class="kpi-card" style="width: 25%;">
                <div class="kpi-label">Unit SPKLU Beroperasi</div>
                <div class="kpi-value">{{ $totalSpkluAktif }} <span style="font-size:9px; font-weight:normal;">SPKLU</span></div>
                <div class="kpi-sub">{{ $totalDurasi > 0 ? number_format($totalDurasi, 0, ',', '.').' Menit total' : 'Durasi tercatat' }}</div>
            </td>
        </tr>
    </table>

    <!-- REKAPITULASI BULANAN -->
    <div class="section-header">
        <span class="section-title">1. Ringkasan Kinerja Bulanan</span>
        <span class="section-subtitle">Tren transaksi dan konsumsi energi kumulatif per bulan</span>
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 6%;" class="text-center">No</th>
                <th style="width: 22%;">Bulan Periode</th>
                <th style="width: 18%;" class="text-right">Jumlah Transaksi</th>
                <th style="width: 20%;" class="text-right">Energi (kWh)</th>
                <th style="width: 22%;" class="text-right">Pendapatan (Rp)</th>
                <th style="width: 12%;" class="text-right">Rata kWh/Trx</th>
            </tr>
        </thead>
        <tbody>
            @php
                $subTrx = 0;
                $subKwh = 0;
                $subRp = 0;
            @endphp
            @forelse ($rekapBulanan as $idx => $b)
                @php
                    $subTrx += $b->total_transaksi;
                    $subKwh += $b->total_energi;
                    $subRp += $b->total_pendapatan;
                    $rataBulan = $b->total_transaksi > 0 ? $b->total_energi / $b->total_transaksi : 0;
                    $carbonBulan = \Illuminate\Support\Carbon::createFromFormat('Y-m', $b->bulan);
                @endphp
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td><strong>{{ $carbonBulan ? $carbonBulan->translatedFormat('F Y') : $b->bulan }}</strong></td>
                    <td class="text-right">{{ number_format($b->total_transaksi, 0, ',', '.') }} kali</td>
                    <td class="text-right">{{ number_format($b->total_energi, 1, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($b->total_pendapatan, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($rataBulan, 1, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center" style="padding:10px; color:#94A3B8;">Tidak ada data pada periode ini.</td></tr>
            @endforelse
        </tbody>
        @if ($rekapBulanan->isNotEmpty())
            <tfoot>
                <tr>
                    <td colspan="2" class="text-center">TOTAL KUMULATIF</td>
                    <td class="text-right">{{ number_format($subTrx, 0, ',', '.') }} kali</td>
                    <td class="text-right">{{ number_format($subKwh, 1, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($subRp, 0, ',', '.') }}</td>
                    <td class="text-right">{{ $subTrx > 0 ? number_format($subKwh / $subTrx, 1, ',', '.') : '0' }}</td>
                </tr>
            </tfoot>
        @endif
    </table>

    <!-- REKAPITULASI PER SPKLU -->
    <div class="section-header" style="margin-top:16px;">
        <span class="section-title">2. Rekapitulasi Kinerja per SPKLU</span>
        <span class="section-subtitle">Diurutkan berdasarkan kontribusi transaksi tertinggi</span>
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">No</th>
                <th style="width: 32%;">Nama SPKLU</th>
                <th style="width: 13%;">Kode / Kota</th>
                <th style="width: 14%;" class="text-right">Jumlah Transaksi</th>
                <th style="width: 16%;" class="text-right">Energi (kWh)</th>
                <th style="width: 20%;" class="text-right">Pendapatan (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rekapSpklu as $idx => $s)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td><strong>{{ $s->nama_spklu }}</strong></td>
                    <td>
                        <span class="badge-unit">{{ $s->kode_unit ?? '—' }}</span>
                        <span style="color:#64748B; font-size:7.5px;">{{ $s->nama_ulp ? '('.$s->nama_ulp.')' : '' }}</span>
                    </td>
                    <td class="text-right">{{ number_format($s->total_transaksi, 0, ',', '.') }} kali</td>
                    <td class="text-right">{{ number_format($s->total_energi, 1, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($s->total_pendapatan, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center" style="padding:10px; color:#94A3B8;">Tidak ada data SPKLU.</td></tr>
            @endforelse
        </tbody>
        @if ($rekapSpklu->isNotEmpty())
            <tfoot>
                <tr>
                    <td colspan="3" class="text-center">TOTAL KESELURUHAN</td>
                    <td class="text-right">{{ number_format($totalTransaksi, 0, ',', '.') }} kali</td>
                    <td class="text-right">{{ number_format($totalEnergi, 1, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        @endif
    </table>

    <!-- RINCIAN / SAMPEL TRANSAKSI -->
    @if ($isSpkluKhusus)
        <div class="page-break"></div>
        <div class="section-header">
            <span class="section-title">3. Rincian Harian SPKLU: {{ $spkluTerpilih->nama ?? '' }}</span>
            <span class="section-subtitle">Data riwayat transaksi harian selama periode laporan</span>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 6%;" class="text-center">No</th>
                    <th style="width: 18%;">Tanggal</th>
                    <th style="width: 14%;" class="text-center">Hari</th>
                    <th style="width: 18%;" class="text-right">Transaksi</th>
                    <th style="width: 20%;" class="text-right">Energi (kWh)</th>
                    <th style="width: 24%;" class="text-right">Pendapatan (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rincian as $idx => $r)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td>{{ $r->tanggal ? \Illuminate\Support\Carbon::parse($r->tanggal)->translatedFormat('d M Y') : '—' }}</td>
                        <td class="text-center" style="color:#64748B;">{{ $r->tanggal ? \Illuminate\Support\Carbon::parse($r->tanggal)->translatedFormat('l') : '—' }}</td>
                        <td class="text-right">{{ number_format($r->jumlah_transaksi, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($r->energi_kwh, 1, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($r->pendapatan_rp, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center" style="padding:10px; color:#94A3B8;">Tidak ada rincian harian.</td></tr>
                @endforelse
            </tbody>
        </table>
    @else
        <div class="section-header" style="margin-top:16px;">
            <span class="section-title">3. Sampel 50 Sesi Transaksi Harian Terbesar</span>
            <span class="section-subtitle">Data transaksi dengan pemakaian energi dan sesi tertinggi</span>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%;" class="text-center">No</th>
                    <th style="width: 14%;">Tanggal</th>
                    <th style="width: 35%;">Nama SPKLU</th>
                    <th style="width: 14%;" class="text-right">Transaksi</th>
                    <th style="width: 16%;" class="text-right">Energi (kWh)</th>
                    <th style="width: 16%;" class="text-right">Pendapatan (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rincian as $idx => $r)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td>{{ $r->tanggal ? \Illuminate\Support\Carbon::parse($r->tanggal)->translatedFormat('d M Y') : '—' }}</td>
                        <td><strong>{{ $r->spklu->nama ?? '—' }}</strong></td>
                        <td class="text-right">{{ number_format($r->jumlah_transaksi, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($r->energi_kwh, 1, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($r->pendapatan_rp, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center" style="padding:10px; color:#94A3B8;">Tidak ada transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    @endif

    <!-- TANDA TANGAN / PENGESAHAN -->
    <table class="signature-table">
        <tr>
            <td style="text-align: left;">
                <div class="sig-box">
                    <div>Disiapkan Oleh:</div>
                    <div class="sig-space"></div>
                    <div class="sig-name">Tim Operasional & Analisis SPKLU</div>
                    <div class="sig-title">Divisi Niaga & Pelayanan Pelanggan PLN</div>
                </div>
            </td>
            <td style="text-align: right;">
                <div class="sig-box">
                    <div>Mengetahui & Menyetujui:</div>
                    <div class="sig-space"></div>
                    <div class="sig-name">Manager Efisiensi & Kemitraan SPKLU</div>
                    <div class="sig-title">PT PLN (Persero) UID</div>
                </div>
            </td>
        </tr>
    </table>

    <div class="footer-note">
        Dokumen ini diterbitkan secara resmi melalui Sistem Monitoring & Analisis SPKLU PLN &bull; Waktu generate: {{ now()->format('d/m/Y H:i:s') }} WIB
    </div>

</body>
</html>