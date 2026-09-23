@extends('layouts.app')

@section('breadcrumb', 'Transaksi')
@section('page-title', 'Ringkasan Transaksi')

@section('content')

<style>
    .trx-page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; flex-wrap:wrap; gap:10px; }
    .trx-page-subtitle { color:#64748B; margin:0; font-size:13.5px; }

    .trx-btn { display:inline-flex; align-items:center; gap:7px; border:none; border-radius:9px; font-size:13.3px; font-weight:700; padding:10px 18px; cursor:pointer; transition:all .15s ease; }
    .trx-btn svg { width:15px; height:15px; stroke-width:2.1; }
    .trx-btn-outline { background:#fff; color:#1E293B; border:1px solid #e2e8f0; }
    .trx-btn-outline:hover { background:#f8fafc; border-color:#cbd5e1; }
    .trx-btn-export { background:linear-gradient(135deg,#dc2626,#b91c1c); color:#fff; box-shadow:0 2px 10px rgba(220,38,38,.28); }
    .trx-btn-export:hover { transform:translateY(-1px); box-shadow:0 4px 14px rgba(220,38,38,.34); }

    .trx-filter-form { display:flex; align-items:center; gap:14px; flex-wrap:wrap; }

    .trx-select {
        padding:10px 34px 10px 14px; border-radius:9px; border:1px solid #e2e8f0; font-size:13.3px; font-weight:500;
        background:#fff; color:#1E293B; cursor:pointer; appearance:none; width:220px; flex:0 0 auto;
        background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6' fill='none'%3E%3Cpath d='M1 1L5 5L9 1' stroke='%2364748B' stroke-width='1.6' stroke-linecap='round'/%3E%3C/svg%3E");
        background-repeat:no-repeat; background-position:right 14px center; transition:all .15s ease;
    }
    .trx-select:focus { outline:none; border-color:#0081AB; box-shadow:0 0 0 3px rgba(0,129,171,.14); }
    .trx-select-sm { width:118px; padding:8px 28px 8px 12px; font-size:12.4px; background-position:right 10px center; }

    .trx-pill-group { display:inline-flex; background:#fff; border:1px solid #e2e8f0; border-radius:9px; padding:3px; gap:2px; flex-shrink:0; }
    .trx-pill { border:none; background:none; padding:7px 13px; border-radius:7px; font-size:12.8px; font-weight:700; color:#64748B; cursor:pointer; transition:all .15s ease; white-space:nowrap; }
    .trx-pill:hover { color:#1E293B; }
    .trx-pill.active { background:linear-gradient(135deg,#FFC629,#ffab00); color:#023E8A; box-shadow:0 2px 6px rgba(255,198,41,.4); }

    .trx-daterange { display:flex; align-items:center; gap:8px; background:#fff; border:1px solid #e2e8f0; border-radius:9px; padding:8px 12px; flex-shrink:0; transition:border-color .15s ease; }
    .trx-daterange:focus-within { border-color:#0081AB; box-shadow:0 0 0 3px rgba(0,129,171,.14); }
    .trx-daterange input { border:none; padding:0; font-size:12.8px; color:#1E293B; width:106px; font-family:inherit; }
    .trx-daterange input:focus { outline:none; }
    .trx-daterange input::-webkit-calendar-picker-indicator {
        filter: invert(28%) sepia(97%) saturate(1226%) hue-rotate(175deg) brightness(94%) contrast(101%);
        cursor: pointer;
        opacity: .8;
        padding: 2px;
        border-radius: 5px;
        transition: background-color .15s ease;
    }
    .trx-daterange input::-webkit-calendar-picker-indicator:hover { background-color: rgba(0,129,171,.1); opacity: 1; }
    .trx-daterange-sep { color:#cbd5e1; font-size:12px; flex-shrink:0; }

    .trx-card-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:18px; margin-bottom:22px; }
    .trx-summary-card { background:#fff; border-radius:16px; padding:22px; border:1px solid #eef1f5; box-shadow:0 1px 2px rgba(15,23,42,.04), 0 6px 16px rgba(15,23,42,.05); transition:transform .18s ease, box-shadow .18s ease; }
    .trx-summary-card:hover { transform:translateY(-2px); box-shadow:0 4px 8px rgba(15,23,42,.06), 0 14px 28px rgba(15,23,42,.09); }
    .trx-summary-top { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:14px; }
    .trx-summary-icon { width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .trx-summary-icon svg { width:20px; height:20px; stroke-width:2; }
    .ic-blue  { background:linear-gradient(135deg, rgba(2,62,138,.12), rgba(0,129,171,.12)); color:#023E8A; }
    .ic-green { background:linear-gradient(135deg, rgba(46,158,91,.14), rgba(46,158,91,.06)); color:#2E9E5B; }
    .ic-amber { background:linear-gradient(135deg, rgba(232,163,23,.15), rgba(232,163,23,.06)); color:#E8A317; }
    .ic-purple{ background:linear-gradient(135deg, rgba(147,51,234,.14), rgba(147,51,234,.06)); color:#9333ea; }
    .trx-summary-label { font-size:12.5px; font-weight:600; text-transform:uppercase; letter-spacing:.04em; color:#94a3b8; margin:0 0 6px; }
    .trx-summary-value { font-size:26px; font-weight:800; letter-spacing:-.02em; color:#0f172a; margin:0; }
    .trx-trend { display:inline-flex; align-items:center; gap:4px; font-size:12px; font-weight:700; margin-top:10px; }
    .trx-trend svg { width:12px; height:12px; stroke-width:3; }
    .trx-trend.up { color:#2E9E5B; }
    .trx-trend.down { color:#C0392B; }
    .trx-trend-note { color:#94a3b8; font-weight:500; }

    .trx-table { width:100%; border-collapse:collapse; }
    .trx-table thead th { background:#fafbfc; text-align:left; font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8; padding:13px 20px; border-bottom:1px solid #eef1f5; }
    .trx-table td { padding:14px 20px; font-size:13.5px; border-bottom:1px solid #f5f7fa; }
    .trx-table tbody tr:nth-child(even) { background:#fbfcfd; }
    .trx-table tbody tr:hover { background:rgba(0,129,171,.05); }
    .trx-table tbody tr:last-child td { border-bottom:none; }
    .trx-empty { text-align:center; padding:48px 20px; color:#94a3b8; font-size:13.5px; }

    .trx-year-filter { display:flex; gap:12px; flex-wrap:wrap; align-items:center; padding:14px 20px; border-bottom:1px solid #f1f5f9; }
    .trx-year-checkbox { display:inline-flex; align-items:center; gap:6px; font-size:12.8px; font-weight:600; color:#334155; cursor:pointer; }
    .trx-year-checkbox input { accent-color:#0081AB; cursor:pointer; }

    .trx-data-table-wrap { overflow-x:auto; border-top:1px solid #f1f5f9; }
    .trx-data-table { width:100%; border-collapse:collapse; min-width:800px; }
    .trx-data-table th { text-align:right; font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.04em; color:#94a3b8; padding:11px 14px; border-bottom:1.5px solid #eef1f5; white-space:nowrap; }
    .trx-data-table th:first-child { text-align:left; }
    .trx-data-table td { text-align:right; font-size:12.5px; color:#334155; padding:10px 14px; border-bottom:1px solid #f5f7fa; white-space:nowrap; font-variant-numeric:tabular-nums; }
    .trx-data-table td:first-child { text-align:left; padding:8px 14px; }
    .trx-data-table tbody tr:nth-child(even) { background:#fbfcfd; }
    .trx-data-table tbody tr:hover { background:rgba(0,129,171,.05); }
    .trx-data-table td.total-col, .trx-data-table th.total-col { font-weight:800; color:#023E8A; background:#F0F7FA; }
    .trx-data-table tbody tr:nth-child(even) td.total-col { background:#EAF3F7; }
    .trx-data-table tr:last-child td { border-bottom:none; }

    .trx-tahun-chip {
        display:inline-flex; align-items:center; gap:7px; padding:5px 12px 5px 9px; border-radius:999px;
        font-weight:800; font-size:12.5px; background:#F8FAFC; border:1px solid #EEF1F5;
    }
    .trx-line-swatch {
        display:inline-block; width:20px; height:8px; position:relative; flex-shrink:0;
    }
    .trx-line-swatch::before {
        content:''; position:absolute; top:50%; left:0; right:0; height:2.5px;
        background:currentColor; border-radius:2px; transform:translateY(-50%);
    }
    .trx-line-swatch::after {
        content:''; position:absolute; top:50%; left:50%; width:6px; height:6px;
        border-radius:50%; background:currentColor; transform:translate(-50%,-50%);
        box-shadow:0 0 0 2px #fff;
    }
    .trx-tren-legend {
        display:flex; justify-content:center; align-items:center; gap:10px;
        flex-wrap:wrap; padding:14px 20px 18px; border-top:1px solid #f1f5f9;
    }
    .trx-tren-legend .trx-tahun-chip { color:#334155; }
</style>

<div class="trx-page-header">
    <p class="trx-page-subtitle">Ringkasan transaksi seluruh SPKLU</p>
    <div style="display:flex; gap:10px;">
        @if (in_array(auth()->user()->role, ['super_admin', 'pengelola']))
            <a href="{{ route('transaksi.upload') }}" class="trx-btn trx-btn-outline">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                Kelola Upload
            </a>
        @endif
        <a href="{{ route('transaksi.export', request()->query()) }}" class="trx-btn trx-btn-export">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            Export PDF
        </a>
    </div>
</div>

<form method="GET" id="form-filter" class="filter-shell">
    <div class="trx-filter-form">
        <div class="filter-label-inline">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
            Filter
        </div>

        <select name="spklu_id" class="trx-select" onchange="document.getElementById('form-filter').submit()">
            <option value="">Semua SPKLU</option>
            @foreach ($spkluList as $spklu)
                <option value="{{ $spklu->id }}" {{ (string) $spkluTerpilih === (string) $spklu->id ? 'selected' : '' }}>{{ $spklu->nama }}</option>
            @endforeach
        </select>

        <div class="filter-divider"></div>

        <input type="hidden" name="satuan" id="input-satuan" value="{{ $satuan }}">
        <div class="trx-pill-group">
            <button type="button" class="trx-pill {{ $satuan === 'kali' ? 'active' : '' }}" onclick="setSatuan('kali')">Kali</button>
            <button type="button" class="trx-pill {{ $satuan === 'kwh' ? 'active' : '' }}" onclick="setSatuan('kwh')">kWh</button>
            <button type="button" class="trx-pill {{ $satuan === 'rp' ? 'active' : '' }}" onclick="setSatuan('rp')">Rp</button>
        </div>

        <div class="filter-divider"></div>

        <div class="trx-daterange">
            <input type="date" name="dari" value="{{ $dari }}" onchange="document.getElementById('form-filter').submit()">
            <span class="trx-daterange-sep">—</span>
            <input type="date" name="sampai" value="{{ $sampai }}" onchange="document.getElementById('form-filter').submit()">
        </div>
    </div>
</form>

<div class="trx-card-grid">
    <div class="trx-summary-card">
        <div class="trx-summary-top">
            <div>
                <p class="trx-summary-label">Total Transaksi</p>
                <p class="trx-summary-value">{{ number_format($totalTransaksi) }}</p>
            </div>
            <div class="trx-summary-icon ic-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg></div>
        </div>
        <span class="trx-trend {{ $trend['transaksi'] >= 0 ? 'up' : 'down' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                @if ($trend['transaksi'] >= 0)<polyline points="18 15 12 9 6 15"/>@else<polyline points="6 9 12 15 18 9"/>@endif
            </svg>
            {{ abs($trend['transaksi']) }}% <span class="trx-trend-note">vs periode lalu</span>
        </span>
    </div>

    <div class="trx-summary-card">
        <div class="trx-summary-top">
            <div>
                <p class="trx-summary-label">Energi Tersalur</p>
                <p class="trx-summary-value">{{ number_format($totalEnergi / 1000, 1) }}k kWh</p>
            </div>
            <div class="trx-summary-icon ic-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"/></svg></div>
        </div>
        <span class="trx-trend {{ $trend['energi'] >= 0 ? 'up' : 'down' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                @if ($trend['energi'] >= 0)<polyline points="18 15 12 9 6 15"/>@else<polyline points="6 9 12 15 18 9"/>@endif
            </svg>
            {{ abs($trend['energi']) }}% <span class="trx-trend-note">vs periode lalu</span>
        </span>
    </div>

    <div class="trx-summary-card">
        <div class="trx-summary-top">
            <div>
                <p class="trx-summary-label">Total Pendapatan</p>
                <p class="trx-summary-value">Rp {{ number_format($totalPendapatan / 1000000, 2) }}M</p>
            </div>
            <div class="trx-summary-icon ic-amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
        </div>
        <span class="trx-trend {{ $trend['pendapatan'] >= 0 ? 'up' : 'down' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                @if ($trend['pendapatan'] >= 0)<polyline points="18 15 12 9 6 15"/>@else<polyline points="6 9 12 15 18 9"/>@endif
            </svg>
            {{ abs($trend['pendapatan']) }}% <span class="trx-trend-note">vs periode lalu</span>
        </span>
    </div>

    <div class="trx-summary-card">
        <div class="trx-summary-top">
            <div>
                <p class="trx-summary-label">Rata-Rata/Transaksi</p>
                <p class="trx-summary-value">{{ number_format($rataRataKwhPerTransaksi, 1) }} kWh</p>
            </div>
            <div class="trx-summary-icon ic-purple"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
        </div>
        <span class="trx-trend up" style="color:#94a3b8;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
            <span class="trx-trend-note">energi rata-rata per sesi</span>
        </span>
    </div>
</div>

@php
    $paletWarnaTren = ['#023E8A', '#E8A317', '#2E9E5B', '#C0392B', '#6D5DD3', '#0EA5B7'];
    $namaBulanSingkat = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nop','Des'];
    $namaBulanPenuh = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $bulanSingkatTerpilih = array_slice($namaBulanSingkat, $bulanAwal - 1, $bulanAkhir - $bulanAwal + 1);
@endphp

<div class="surface-card" style="margin-bottom: 20px;">
    <div class="section-header-bar">
        <div class="section-header-bar-left">
            <div class="section-header-bar-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
            <div>
                <h2>Visualisasi Tren Transaksi</h2>
                <p>Perbandingan antar tahun, per bulan{{ $spkluTerpilih ? ' — ' . ($spkluList->firstWhere('id', $spkluTerpilih)->nama ?? '') : '' }} · Satuan: {{ strtoupper($satuan) }}</p>
            </div>
        </div>
    </div>

    <form method="GET" id="form-filter-tren" class="trx-year-filter">
        <input type="hidden" name="spklu_id" value="{{ $spkluTerpilih }}">
        <input type="hidden" name="satuan" value="{{ $satuan }}">
        <input type="hidden" name="dari" value="{{ $dari }}">
        <input type="hidden" name="sampai" value="{{ $sampai }}">

        @foreach ($tahunTersedia as $tahun)
            <label class="trx-year-checkbox">
                <input type="checkbox" name="tahun[]" value="{{ $tahun }}"
                    {{ in_array($tahun, $tahunDipilih) ? 'checked' : '' }}
                    onchange="document.getElementById('form-filter-tren').submit()">
                {{ $tahun }}
            </label>
        @endforeach

        <span style="display:inline-flex; align-items:center; gap:8px; margin-left:auto;">
            <select name="bulan_awal" class="trx-select trx-select-sm" onchange="document.getElementById('form-filter-tren').submit()">
                @foreach ($namaBulanPenuh as $i => $nama)
                    <option value="{{ $i + 1 }}" {{ $bulanAwal == $i + 1 ? 'selected' : '' }}>{{ $nama }}</option>
                @endforeach
            </select>
            <span class="trx-daterange-sep">—</span>
            <select name="bulan_akhir" class="trx-select trx-select-sm" onchange="document.getElementById('form-filter-tren').submit()">
                @foreach ($namaBulanPenuh as $i => $nama)
                    <option value="{{ $i + 1 }}" {{ $bulanAkhir == $i + 1 ? 'selected' : '' }}>{{ $nama }}</option>
                @endforeach
            </select>
        </span>
    </form>

    <div style="padding:22px 24px;">
        <canvas id="chart-tren-per-tahun" height="95"></canvas>
    </div>

    <div class="trx-data-table-wrap">
        <table class="trx-data-table">
            <thead>
                <tr>
                    <th>Tahun</th>
                    @foreach ($bulanSingkatTerpilih as $bulan)
                        <th>{{ $bulan }}</th>
                    @endforeach
                    <th class="total-col">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($trenPerTahun as $tahun => $dataBulanan)
                    @php $warnaBaris = $paletWarnaTren[$loop->index % count($paletWarnaTren)]; @endphp
                    <tr>
                        <td>
                            <span class="trx-tahun-chip" style="color: {{ $warnaBaris }};">
                                <span class="trx-line-swatch"></span>{{ $tahun }}
                            </span>
                        </td>
                        @foreach ($dataBulanan as $nilai)
                            <td>{{ $nilai != 0 ? number_format($nilai, 0, ',', '.') : '—' }}</td>
                        @endforeach
                        <td class="total-col">{{ number_format(array_sum($dataBulanan), 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="{{ count($bulanSingkatTerpilih) + 2 }}" style="text-align:center; color:#94a3b8;">Belum ada data transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if (count($trenPerTahun) > 0)
    <div class="trx-tren-legend">
        @foreach ($trenPerTahun as $tahun => $dataBulanan)
            <span class="trx-tahun-chip" style="color: {{ $paletWarnaTren[$loop->index % count($paletWarnaTren)] }};">
                <span class="trx-line-swatch"></span>{{ $tahun }}
            </span>
        @endforeach
    </div>
    @endif
</div>

<div class="surface-card" style="margin-bottom: 20px;">
    <div class="section-header-bar">
        <div class="section-header-bar-left">
            <div class="section-header-bar-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="3" y1="15" x2="21" y2="15"/><line x1="9" y1="3" x2="9" y2="21"/></svg></div>
            <div>
                <h2>Kali Transaksi per SPKLU</h2>
                <p>Jumlah transaksi bulanan, {{ $periodeMatriks->count() }} bulan terakhir</p>
            </div>
        </div>
    </div>

    <div class="trx-data-table-wrap">
        <table class="trx-data-table" style="min-width:1200px;">
            <thead>
                <tr>
                    <th style="text-align:left;">No</th>
                    <th style="text-align:left;">Unit UP</th>
                    <th style="text-align:left;">SPKLU</th>
                    @foreach ($periodeMatriks as $p)
                        {{-- Format 'Y-m' atau 'Ym' diubah menjadi Nama Bulan Bahasa Indonesia --}}
                        <th>{{ \Illuminate\Support\Carbon::parse(str_contains($p, '-') ? $p : substr($p,0,4).'-'.substr($p,4,2))->translatedFormat('F Y') }}</th>
                    @endforeach
                    <th class="total-col">Rata-Rata</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($matriksKaliTransaksi as $row)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $row['kode_unit'] ?? '—' }}</td>
                        <td style="text-align:left; font-weight:600;">{{ $row['nama'] }}</td>
                        @foreach ($row['per_bulan'] as $nilai)
                            <td>{{ $nilai !== null ? number_format($nilai, 0, ',', '.') : '—' }}</td>
                        @endforeach
                        <td class="total-col">{{ $row['rata_rata'] !== null ? number_format($row['rata_rata'], 0, ',', '.') : '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="{{ $periodeMatriks->count() + 4 }}" style="text-align:center; color:#94a3b8;">Belum ada data transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="surface-card">
    <div class="section-header-bar">
        <div class="section-header-bar-left">
            <div class="section-header-bar-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg></div>
            <div>
                <h2>Rincian Transaksi</h2>
                <p>Rekap harian per SPKLU</p>
            </div>
        </div>
    </div>
    <table class="trx-table">
        <thead>
            <tr><th>Tanggal</th><th>SPKLU</th><th>Jumlah Transaksi</th><th>Energi (kWh)</th><th>Pendapatan (Rp)</th></tr>
        </thead>
        <tbody>
            @forelse ($rincian as $row)
                <tr>
                    <td>{{ $row->tanggal->translatedFormat('d F Y') }}</td>
                    <td>
                        <div class="row-icon-cell">
                            <div class="row-icon-box"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/></svg></div>
                            <span style="font-weight:600;">{{ $row->spklu->nama ?? '—' }}</span>
                        </div>
                    </td>
                    <td>{{ number_format($row->jumlah_transaksi) }}</td>
                    <td>{{ number_format($row->energi_kwh, 1) }}</td>
                    <td>{{ number_format($row->pendapatan_rp, 0) }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="trx-empty">Belum ada data transaksi di rentang ini.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<script>
    Chart.register(ChartDataLabels);

    function setSatuan(v) { document.getElementById('input-satuan').value = v; document.getElementById('form-filter').submit(); }

    const satuanAktif = '{{ $satuan }}';
    const trenPerTahun = @json($trenPerTahun ?? []);
    const bulanLabelTren = @json($bulanSingkatTerpilih);
    const paletWarnaTren = ['#023E8A', '#E8A317', '#2E9E5B', '#C0392B', '#6D5DD3', '#0EA5B7'];

    function formatSatuan(v) {
        if (satuanAktif === 'rp') return 'Rp' + new Intl.NumberFormat('id-ID').format(v);
        if (satuanAktif === 'kwh') return new Intl.NumberFormat('id-ID').format(v) + ' kWh';
        return new Intl.NumberFormat('id-ID').format(v) + 'x';
    }

    const datasetsTren = Object.keys(trenPerTahun).map((tahun, i) => ({
        label: tahun,
        data: trenPerTahun[tahun],
        borderColor: paletWarnaTren[i % paletWarnaTren.length],
        backgroundColor: paletWarnaTren[i % paletWarnaTren.length],
        tension: 0.3,
        fill: false,
        pointRadius: 4,
        borderWidth: 2.5,
    }));

    new Chart(document.getElementById('chart-tren-per-tahun'), {
        type: 'line',
        data: { labels: bulanLabelTren, datasets: datasetsTren },
        options: {
            plugins: {
                legend: { display: false },
                datalabels: {
                    display: function(context) {
                        return context.dataset.data[context.dataIndex] > 0;
                    },
                    align: 'top',
                    anchor: 'end',
                    font: {
                        size: 10,
                        weight: 'bold',
                        family: 'inherit'
                    },
                    color: '#334155',
                    formatter: function(value) {
                        if (satuanAktif === 'rp') {
                            if (value >= 1000000) return (value / 1000000).toFixed(1) + 'M';
                            if (value >= 1000) return (value / 1000).toFixed(0) + 'k';
                            return value;
                        }
                        return new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(value);
                    }
                },
                tooltip: {
                    callbacks: {
                        label: ctx => ctx.dataset.label + ': ' + formatSatuan(ctx.parsed.y),
                    },
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: v => new Intl.NumberFormat('id-ID').format(v) },
                },
            },
        }
    });
</script>

@endsection