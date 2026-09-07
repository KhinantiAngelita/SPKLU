@extends('layouts.app')

@section('breadcrumb', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

<style>
    /* ================= SPKLU DASHBOARD — ENHANCED STYLES ================= */
    .spklu-dashboard {
        --sd-radius: 16px;
        --sd-radius-sm: 10px;
        --sd-border: rgba(0,0,0,0.06);
        --sd-shadow: 0 1px 2px rgba(16,24,40,0.04), 0 4px 16px rgba(16,24,40,0.05);
        --sd-shadow-hover: 0 4px 10px rgba(16,24,40,0.06), 0 12px 28px rgba(16,24,40,0.08);
        --sd-blue: #0081AB;
        --sd-blue-dark: #023E8A;
        --sd-amber: #E8A317;
        --sd-green: #16A34A;
        --sd-red: #E4572E;
    }

    .spklu-dashboard .sd-subtitle {
        color: var(--text-secondary);
        margin: -12px 0 22px;
        font-size: 14.5px;
    }

    /* ---------- Summary cards ---------- */
    .spklu-dashboard .card-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 22px;
    }

    .spklu-dashboard .summary-card {
        background: var(--surface, #fff);
        border: 1px solid var(--sd-border);
        border-radius: var(--sd-radius);
        padding: 20px 22px;
        box-shadow: var(--sd-shadow);
        transition: box-shadow .25s ease, transform .25s ease, border-color .25s ease;
    }

    .spklu-dashboard .summary-card:hover {
        box-shadow: var(--sd-shadow-hover);
        transform: translateY(-2px);
        border-color: rgba(0,129,171,0.22);
    }

    /* ---------- Reusable card-title with plain line icon ---------- */
    .spklu-dashboard .sd-card-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 15.5px;
        font-weight: 700;
        color: var(--text-primary, #101828);
    }

    .spklu-dashboard .sd-card-title-icon {
        width: 30px;
        height: 30px;
        min-width: 30px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .spklu-dashboard .sd-card-title-icon svg {
        width: 16px;
        height: 16px;
        stroke-width: 1.9;
    }

    .spklu-dashboard .sd-card-title-icon.blue   { background: rgba(0,129,171,0.12); color: var(--sd-blue-dark); }
    .spklu-dashboard .sd-card-title-icon.amber  { background: rgba(232,163,23,0.14); color: var(--sd-amber); }
    .spklu-dashboard .sd-card-title-icon.green  { background: rgba(22,163,74,0.12); color: var(--sd-green); }
    .spklu-dashboard .sd-card-title-icon.red    { background: rgba(228,87,46,0.12); color: var(--sd-red); }

    .spklu-dashboard .sd-card-subtitle {
        color: var(--text-secondary);
        font-size: 13px;
        margin: 3px 0 0 40px;
    }

    .spklu-dashboard .summary-card-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
    }

    .spklu-dashboard .summary-card-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-secondary);
        margin: 0 0 8px;
        text-transform: uppercase;
        letter-spacing: .03em;
    }

    .spklu-dashboard .summary-card-value {
        font-size: 28px;
        font-weight: 700;
        margin: 0;
        color: var(--text-primary, #101828);
        line-height: 1.2;
    }

    .spklu-dashboard .summary-card-icon {
        width: 46px;
        height: 46px;
        min-width: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .spklu-dashboard .summary-card-icon svg {
        width: 21px;
        height: 21px;
        stroke-width: 1.8;
    }

    .spklu-dashboard .icon-blue   { background: linear-gradient(135deg,#e0f4fa,#c7ecf7); color: var(--sd-blue-dark); }
    .spklu-dashboard .icon-amber  { background: linear-gradient(135deg,#fdf1d8,#fbe6b8); color: var(--sd-amber); }
    .spklu-dashboard .icon-green  { background: linear-gradient(135deg,#dcfbe7,#c2f5d3); color: var(--sd-green); }
    .spklu-dashboard .icon-red    { background: linear-gradient(135deg,#fde3da,#fbcdbd); color: var(--sd-red); }

    .spklu-dashboard .summary-card-trend {
        display: inline-block;
        margin-top: 14px;
        font-size: 12.5px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 999px;
    }

    .spklu-dashboard .trend-up      { background: rgba(22,163,74,0.12); color: var(--sd-green); }
    .spklu-dashboard .trend-neutral { background: rgba(100,116,139,0.12); color: #64748b; }
    .spklu-dashboard .trend-amber   { background: rgba(232,163,23,0.12); color: var(--sd-amber); }

    /* ---------- Two-column layout: calendar + jadwal ---------- */
    .spklu-dashboard .sd-grid-2col {
        display: grid;
        grid-template-columns: 1.3fr 1fr;
        gap: 20px;
        margin-bottom: 22px;
        align-items: start;
    }

    /* ---------- Calendar widget ---------- */
    .spklu-dashboard .calendar-widget {
        padding: 18px 20px;
        background: linear-gradient(180deg, #fbfdff 0%, #ffffff 55%);
        position: relative;
        overflow: hidden;
    }

    .spklu-dashboard .calendar-widget::before {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--sd-blue), var(--sd-blue-dark), var(--sd-amber));
    }

    .spklu-dashboard .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .spklu-dashboard .calendar-nav-btn,
    .spklu-dashboard .calendar-today-btn {
        border: 1px solid var(--sd-border);
        background: #fff;
        border-radius: 8px;
        padding: 3px 9px;
        font-size: 12.5px;
        cursor: pointer;
        color: var(--text-secondary);
        transition: background .2s ease, color .2s ease, transform .15s ease;
    }

    .spklu-dashboard .calendar-nav-btn:hover,
    .spklu-dashboard .calendar-today-btn:hover {
        background: var(--sd-blue);
        color: #fff;
        border-color: var(--sd-blue);
        transform: translateY(-1px);
    }

    .spklu-dashboard .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 4px;
        text-align: center;
    }

    .spklu-dashboard .calendar-day-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--text-secondary);
        padding-bottom: 4px;
        letter-spacing: .02em;
    }

    .spklu-dashboard .calendar-day-label:nth-child(6),
    .spklu-dashboard .calendar-day-label:nth-child(7) {
        color: var(--sd-red);
        opacity: .75;
    }

    .spklu-dashboard .calendar-grid > div:not(.calendar-day-label) {
        aspect-ratio: 1 / 1;
        max-height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-size: 12.5px;
        color: var(--text-primary, #101828);
        position: relative;
        transition: background .2s ease, transform .15s ease;
    }

    .spklu-dashboard .calendar-cell:hover {
        background: rgba(0,129,171,0.1);
        transform: scale(1.08);
        cursor: default;
        font-weight: 600;
    }

    .spklu-dashboard .calendar-cell.has-event {
        background: rgba(232,163,23,0.1);
        font-weight: 600;
        color: #92650c;
    }

    .spklu-dashboard .calendar-cell.today {
        background: linear-gradient(135deg, var(--sd-blue), var(--sd-blue-dark));
        color: #fff;
        font-weight: 700;
        box-shadow: 0 3px 8px rgba(2,62,138,0.35);
    }

    .spklu-dashboard .calendar-cell.has-event::after {
        content: "";
        position: absolute;
        bottom: 3px;
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: var(--sd-amber);
    }

    .spklu-dashboard .calendar-cell.today.has-event::after {
        background: #fff;
    }

    .spklu-dashboard .calendar-legend {
        display: flex;
        gap: 16px;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px dashed var(--sd-border);
        font-size: 11.5px;
        color: var(--text-secondary);
    }

    .spklu-dashboard .calendar-legend span {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .spklu-dashboard .calendar-legend i {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    .spklu-dashboard .calendar-legend i.dot-today { background: var(--sd-blue-dark); }
    .spklu-dashboard .calendar-legend i.dot-event { background: var(--sd-amber); }

    /* ---------- Jadwal list ---------- */
    .spklu-dashboard .jadwal-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 8px;
        border-radius: var(--sd-radius-sm);
        transition: background .2s ease;
    }

    .spklu-dashboard .jadwal-item:hover {
        background: #f8fafc;
        transform: translateX(2px);
    }

    .spklu-dashboard .jadwal-item + .jadwal-item {
        border-top: 1px dashed var(--sd-border);
        margin-top: 2px;
    }

    .spklu-dashboard .jadwal-time {
        min-width: 54px;
        font-weight: 700;
        font-size: 13px;
        color: var(--sd-blue-dark);
        background: rgba(0,129,171,0.08);
        border: 1px solid rgba(0,129,171,0.14);
        border-radius: 9px;
        text-align: center;
        padding: 7px 4px;
    }

    .spklu-dashboard .jadwal-title {
        margin: 0;
        font-weight: 600;
        font-size: 14px;
        color: var(--text-primary, #101828);
    }

    .spklu-dashboard .jadwal-desc {
        margin: 2px 0 0;
        font-size: 12.5px;
        color: var(--text-secondary);
    }

    /* ---------- Badges ---------- */
    .spklu-dashboard .badge {
        display: inline-block;
        padding: 4px 11px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
        border: 1px solid rgba(0,0,0,0.04);
    }

    .spklu-dashboard .badge-green { background: rgba(22,163,74,0.12); color: var(--sd-green); }
    .spklu-dashboard .badge-gray  { background: rgba(100,116,139,0.12); color: #64748b; }

    .spklu-dashboard .badge-potensi-sangat-tinggi { background: rgba(2,62,138,0.1); color: var(--sd-blue-dark); }
    .spklu-dashboard .badge-potensi-tinggi         { background: rgba(0,129,171,0.1); color: var(--sd-blue); }
    .spklu-dashboard .badge-potensi-sedang         { background: rgba(232,163,23,0.12); color: var(--sd-amber); }

    /* ---------- Trend chart card ---------- */
    .spklu-dashboard .sd-chart-card {
        margin-bottom: 22px;
    }

    .spklu-dashboard .sd-chart-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
        flex-wrap: wrap;
        gap: 10px;
    }

    /* ---------- Table ---------- */
    .spklu-dashboard .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .spklu-dashboard .data-table thead th {
        text-align: left;
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: var(--text-secondary);
        font-weight: 700;
        padding: 11px 14px;
        background: #f8fafc;
    }

    .spklu-dashboard .data-table thead th:first-child { border-radius: 8px 0 0 8px; }
    .spklu-dashboard .data-table thead th:last-child  { border-radius: 0 8px 8px 0; }

    .spklu-dashboard .data-table tbody td {
        padding: 13px 14px;
        border-bottom: 1px solid var(--sd-border);
        vertical-align: middle;
    }

    .spklu-dashboard .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    .spklu-dashboard .data-table tbody tr {
        transition: background .15s ease;
    }

    .spklu-dashboard .data-table tbody tr:hover {
        background: #f8fafc;
    }

    .spklu-dashboard .progress-bar-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .spklu-dashboard .progress-bar-track {
        flex: 1;
        height: 8px;
        border-radius: 999px;
        background: #eef1f4;
        overflow: hidden;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.04);
    }

    .spklu-dashboard .progress-bar-fill {
        height: 100%;
        border-radius: 999px;
        background: linear-gradient(90deg, var(--sd-blue), var(--sd-blue-dark));
    }

    .spklu-dashboard .progress-bar-fill.amber {
        background: linear-gradient(90deg, #f6c453, var(--sd-amber));
    }

    .spklu-dashboard .progress-bar-fill.red {
        background: linear-gradient(90deg, #f28b6f, var(--sd-red));
    }

    .spklu-dashboard .progress-bar-value {
        font-weight: 700;
        font-size: 13px;
        min-width: 26px;
        text-align: right;
    }

    /* ---------- Responsive ---------- */
    @media (max-width: 1100px) {
        .spklu-dashboard .card-grid { grid-template-columns: repeat(2, 1fr); }
        .spklu-dashboard .sd-grid-2col { grid-template-columns: 1fr; }
    }

    @media (max-width: 560px) {
        .spklu-dashboard .card-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="spklu-dashboard">

    <p class="sd-subtitle">Ringkasan Sistem SPKLU</p>

    <div class="card-grid">
        <div class="summary-card">
            <div class="summary-card-header">
                <div>
                    <p class="summary-card-label">Total SPKLU Terpasang</p>
                    <p class="summary-card-value">{{ number_format($totalSpkluTerpasang) }} <span style="font-size:14px; font-weight:400; color:var(--text-secondary);">unit</span></p>
                </div>
                <div class="summary-card-icon icon-blue">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                </div>
            </div>
            <span class="summary-card-trend trend-up">+{{ $spkluBaruBulanIni ?? 0 }} bulan ini</span>
        </div>

        <div class="summary-card">
            <div class="summary-card-header">
                <div>
                    <p class="summary-card-label">Pengajuan On-Progress</p>
                    <p class="summary-card-value">{{ number_format($pengajuanOnProgress) }} <span style="font-size:14px; font-weight:400; color:var(--text-secondary);">sesi</span></p>
                </div>
                <div class="summary-card-icon icon-amber">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="4" width="12" height="16" rx="2"/><path d="M9 4V3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1"/><line x1="9" y1="10" x2="15" y2="10"/><line x1="9" y1="14" x2="15" y2="14"/></svg>
                </div>
            </div>
            <span class="summary-card-trend trend-neutral">vs bulan lalu</span>
        </div>

        <div class="summary-card">
            <div class="summary-card-header">
                <div>
                    <p class="summary-card-label">Kandidat Aktif</p>
                    <p class="summary-card-value">{{ number_format($kandidatAktif) }} <span style="font-size:14px; font-weight:400; color:var(--text-secondary);">lokasi</span></p>
                </div>
                <div class="summary-card-icon icon-green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 12-9 12s-9-5-9-12a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                </div>
            </div>
            <span class="summary-card-trend trend-amber">{{ $kandidatButuhTindakLanjut }} menunggu tindak lanjut</span>
        </div>

        <div class="summary-card">
            <div class="summary-card-header">
                <div>
                    <p class="summary-card-label">Jadwal Mendatang</p>
                    <p class="summary-card-value">{{ ($jadwalHariIni->count() + $jadwalBesok->count()) }}</p>
                </div>
                <div class="summary-card-icon icon-red">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
            </div>
            <span class="summary-card-trend trend-neutral">minggu ini</span>
        </div>
    </div>

    <div class="sd-grid-2col">

        {{-- Kalender --}}
        <div class="summary-card calendar-widget">
            @php
                $bulanIni = now();
                $awalBulan = $bulanIni->copy()->startOfMonth();
                $akhirBulan = $bulanIni->copy()->endOfMonth();
                $offsetAwal = $awalBulan->dayOfWeekIso - 1; // Senin = 0
            @endphp

            <div class="calendar-header">
                <h3 class="sd-card-title">
                    <span class="sd-card-title-icon blue">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </span>
                    {{ $bulanIni->translatedFormat('d F Y') }}
                </h3>
                <div style="display:flex; gap:6px;">
                    <button class="calendar-nav-btn">‹</button>
                    <button class="calendar-today-btn">Hari Ini</button>
                    <button class="calendar-nav-btn">›</button>
                </div>
            </div>

            <div class="calendar-grid">
                @foreach (['Sen','Sel','Rab','Kam','Jum','Sab','Min'] as $hari)
                    <div class="calendar-day-label">{{ $hari }}</div>
                @endforeach

                @for ($i = 0; $i < $offsetAwal; $i++)
                    <div></div>
                @endfor

                @for ($tgl = 1; $tgl <= $akhirBulan->day; $tgl++)
                    @php
                        $isToday = $tgl === $bulanIni->day;
                        $hasEvent = in_array((string) $tgl, $kalenderBulanIni['tanggalBerjadwal'] ?? []);
                    @endphp
                    <div class="calendar-cell {{ $isToday ? 'today' : '' }} {{ $hasEvent ? 'has-event' : '' }}">
                        {{ $tgl }}
                    </div>
                @endfor
            </div>

            <div class="calendar-legend">
                <span><i class="dot-today"></i> Hari ini</span>
                <span><i class="dot-event"></i> Ada jadwal</span>
            </div>
        </div>

        {{-- Jadwal --}}
        <div class="summary-card">
            <h3 class="sd-card-title" style="margin:0 0 12px;">
                <span class="sd-card-title-icon amber">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><polyline points="12 7 12 12 15 15"/></svg>
                </span>
                Jadwal Hari Ini
            </h3>
            @forelse ($jadwalHariIni as $jadwal)
                <div class="jadwal-item">
                    <div class="jadwal-time">{{ $jadwal->waktu_mulai->format('H:i') }}</div>
                    <div class="jadwal-info" style="flex:1;">
                        <p class="jadwal-title">{{ $jadwal->judul }}</p>
                        <p class="jadwal-desc">{{ Str::limit($jadwal->deskripsi, 40) }}</p>
                    </div>
                    <span class="badge {{ $jadwal->mode === 'online' ? 'badge-green' : 'badge-gray' }}">{{ ucfirst($jadwal->mode) }}</span>
                </div>
            @empty
                <p style="color:var(--text-secondary); font-size:14px;">Tidak ada jadwal hari ini.</p>
            @endforelse

            <h3 class="sd-card-title" style="margin:20px 0 12px;">
                <span class="sd-card-title-icon blue">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><polyline points="12 7 12 12 15 15"/></svg>
                </span>
                Jadwal Besok
            </h3>
            @forelse ($jadwalBesok as $jadwal)
                <div class="jadwal-item besok">
                    <div class="jadwal-time">{{ $jadwal->waktu_mulai->format('H:i') }}</div>
                    <div class="jadwal-info" style="flex:1;">
                        <p class="jadwal-title">{{ $jadwal->judul }}</p>
                        <p class="jadwal-desc">{{ Str::limit($jadwal->deskripsi, 40) }}</p>
                    </div>
                    <span class="badge {{ $jadwal->mode === 'online' ? 'badge-green' : 'badge-gray' }}">{{ ucfirst($jadwal->mode) }}</span>
                </div>
            @empty
                <p style="color:var(--text-secondary); font-size:14px;">Tidak ada jadwal besok.</p>
            @endforelse
        </div>
    </div>

    <div class="summary-card sd-chart-card">
        <div class="sd-chart-head">
            <div>
                <h3 class="sd-card-title" style="margin:0;">
                    <span class="sd-card-title-icon green">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 17 9 11 13 15 21 7"/><polyline points="14 7 21 7 21 14"/></svg>
                    </span>
                    Tren Transaksi
                </h3>
                <p class="sd-card-subtitle">{{ now()->subMonths(6)->translatedFormat('M Y') }} - {{ now()->translatedFormat('M Y') }}</p>
            </div>
            <span class="summary-card-trend trend-up">↑ {{ $trenTransaksiPersen ?? 0 }}% total</span>
        </div>
        <canvas id="chart-tren-dashboard" height="70"></canvas>
    </div>

    <div class="summary-card">
        <h3 class="sd-card-title" style="margin:0 0 4px;">
            <span class="sd-card-title-icon amber">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15 8.5 22 9.5 17 14.5 18.5 21.5 12 18 5.5 21.5 7 14.5 2 9.5 9 8.5 12 2"/></svg>
            </span>
            Top 5 Kandidat Prioritas
        </h3>
        <p class="sd-card-subtitle" style="margin-bottom:16px;">Berdasarkan skor potensi pemasangan SPKLU</p>

        <table class="data-table">
            <thead>
                <tr><th>#</th><th>Nama Lokasi</th><th>Kota/Wilayah</th><th>Potensi</th><th>Skor</th></tr>
            </thead>
            <tbody>
                @forelse ($topKandidat as $i => $kandidat)
                    @php
                        $potensiClass = match(true) {
                            $kandidat->skor >= 85 => 'badge-potensi-sangat-tinggi',
                            $kandidat->skor >= 65 => 'badge-potensi-tinggi',
                            default => 'badge-potensi-sedang',
                        };
                        $potensiLabel = match(true) {
                            $kandidat->skor >= 85 => 'Sangat Tinggi',
                            $kandidat->skor >= 65 => 'Tinggi',
                            default => 'Sedang',
                        };
                        $barClass = $kandidat->skor >= 75 ? '' : ($kandidat->skor >= 50 ? 'amber' : 'red');
                    @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td style="font-weight:600;">{{ $kandidat->nama }}</td>
                        <td>{{ $kandidat->wilayah }}</td>
                        <td><span class="badge {{ $potensiClass }}">{{ $potensiLabel }}</span></td>
                        <td>
                            <div class="progress-bar-wrap">
                                <div class="progress-bar-track"><div class="progress-bar-fill {{ $barClass }}" style="width:{{ $kandidat->skor }}%;"></div></div>
                                <span class="progress-bar-value">{{ $kandidat->skor }}</span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center; color:var(--text-secondary);">Belum ada data kandidat.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
    new Chart(document.getElementById('chart-tren-dashboard'), {
        type: 'line',
        data: {
            labels: {!! json_encode($trenTransaksiLabels ?? []) !!},
            datasets: [{
                data: {!! json_encode($trenTransaksiData ?? []) !!},
                borderColor: '#0081AB',
                backgroundColor: 'rgba(0,129,171,0.1)',
                tension: 0.35,
                fill: true,
                pointRadius: 4,
                pointBackgroundColor: '#023E8A',
                pointHoverRadius: 6,
                borderWidth: 2.5,
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                x: { grid: { display: false } }
            },
            interaction: { intersect: false, mode: 'index' }
        }
    });
</script>

@endsection