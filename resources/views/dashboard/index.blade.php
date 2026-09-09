@extends('layouts.app')

@section('breadcrumb', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

<style>
    .dsh-subtitle { color:#64748B; margin:-6px 0 20px; font-size:13.5px; }

    .dsh-card-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:18px; margin-bottom:22px; }
    .dsh-card { background:#fff; border-radius:16px; padding:22px; border:1px solid #eef1f5; box-shadow:0 1px 2px rgba(15,23,42,.04), 0 6px 16px rgba(15,23,42,.05); transition:transform .18s ease, box-shadow .18s ease; }
    .dsh-card:hover { transform:translateY(-2px); box-shadow:0 4px 8px rgba(15,23,42,.06), 0 14px 28px rgba(15,23,42,.09); }
    .dsh-card-top { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:14px; }
    .dsh-card-icon { width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .dsh-card-icon svg { width:20px; height:20px; stroke-width:2; }
    .dsh-card-label { font-size:12.5px; font-weight:600; text-transform:uppercase; letter-spacing:.04em; color:#94a3b8; margin:0 0 6px; }
    .dsh-card-value { font-size:26px; font-weight:800; letter-spacing:-.02em; color:#0f172a; margin:0; }
    .dsh-trend { display:inline-flex; align-items:center; padding:4px 11px; border-radius:999px; font-size:11.5px; font-weight:700; margin-top:10px; }
    .dsh-trend-up { background:rgba(46,158,91,.12); color:#2E9E5B; }
    .dsh-trend-down { background:rgba(192,57,43,.12); color:#C0392B; }
    .dsh-trend-neutral { background:#eef2f7; color:#64748B; }
    .dsh-trend-amber { background:rgba(232,163,23,.14); color:#92660f; }

    /* Kolom kiri (Kalender) & kanan (Jadwal Terdekat) — tinggi TETAP, bukan ngikutin isi */
    .dsh-grid-2col { display:grid; grid-template-columns:1.3fr 1fr; gap:20px; margin-bottom:20px; align-items:stretch; }
    .dsh-grid-2col > .surface-card { display:flex; flex-direction:column; height:390px; }

    .calendar-widget { padding:16px 20px 18px; flex:1; display:flex; flex-direction:column; justify-content:center; min-height:0; }
    .calendar-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; }
    .calendar-nav-btn { background:#f1f5f9; border:1px solid #e2e8f0; border-radius:8px; width:28px; height:28px; cursor:pointer; color:#64748B; transition:background .15s ease; }
    .calendar-nav-btn:hover { background:#e2e8f0; }
    .calendar-today-btn { background:#f1f5f9; border:1px solid #e2e8f0; border-radius:8px; padding:6px 12px; font-size:12px; font-weight:600; cursor:pointer; color:#1E293B; transition:background .15s ease; }
    .calendar-today-btn:hover { background:#e2e8f0; }
    .calendar-grid { display:grid; grid-template-columns:repeat(7,1fr); gap:4px; }
    .calendar-day-label { text-align:center; font-size:10.5px; font-weight:700; color:#94a3b8; padding:4px 0 8px; text-transform:uppercase; }
    .calendar-cell { height:34px; display:flex; align-items:center; justify-content:center; border-radius:8px; font-size:12.5px; position:relative; color:#1E293B; transition:background .15s ease; }
    .calendar-cell:not(.today):not(:empty):hover { background:#f1f5f9; cursor:default; }
    .calendar-cell.today { background:linear-gradient(135deg,#023E8A,#0081AB); color:#fff; font-weight:800; box-shadow:0 3px 10px rgba(2,62,138,.28); }
    .calendar-cell.has-event::after { content:''; position:absolute; bottom:4px; width:4px; height:4px; border-radius:50%; background:#0081AB; }
    .calendar-cell.today.has-event::after { background:#FFC629; }

    .jadwal-widget { padding:16px 18px; flex:1; min-height:0; overflow-y:auto; }
    .jadwal-widget::-webkit-scrollbar { width:5px; }
    .jadwal-widget::-webkit-scrollbar-thumb { background:#e2e8f0; border-radius:4px; }
    .jadwal-section-label { font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#94a3b8; margin:0 0 8px; display:flex; align-items:center; gap:6px; }
    .jadwal-section-label:not(:first-child) { margin-top:16px; }
    .jadwal-section-label::after { content:''; flex:1; height:1px; background:#f1f5f9; }

    .jadwal-item { display:flex; align-items:center; gap:10px; padding:9px 11px; border-radius:10px; background:#f8fafc; border-left:3px solid #2E9E5B; margin-bottom:6px; transition:transform .15s ease; }
    .jadwal-item:hover { transform:translateX(2px); }
    .jadwal-item.besok { border-left-color:#E8A317; }
    .jadwal-time { font-weight:800; color:#0081AB; font-size:12.5px; width:42px; flex-shrink:0; }
    .jadwal-info { flex:1; min-width:0; }
    .jadwal-info p { margin:0; }
    .jadwal-title { font-weight:700; font-size:12.8px; color:#1E293B; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .jadwal-desc { font-size:11px; color:#94a3b8; margin-top:1px !important; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .jadwal-badge { display:inline-flex; padding:3px 9px; border-radius:999px; font-size:10px; font-weight:700; flex-shrink:0; }
    .jadwal-badge-online { background:rgba(46,158,91,.14); color:#2E9E5B; }
    .jadwal-badge-offline { background:#eef2f7; color:#64748B; }
    .dsh-empty-inline { text-align:center; padding:16px 10px; color:#94a3b8; font-size:12.5px; }

    /* Filter bulanan untuk Tren Transaksi — cuma range bulan, tanpa spklu/satuan/tampilan */
    .dsh-month-range { display:flex; align-items:center; gap:6px; background:#fff; border:1px solid #e2e8f0; border-radius:9px; padding:2px 10px; flex-shrink:0; }
    .dsh-month-range input { border:none; padding:9px 2px; font-size:12.8px; color:#1E293B; width:126px; font-family:inherit; }
    .dsh-month-range input:focus { outline:none; }
    .dsh-month-range-sep { color:#cbd5e1; font-size:12px; }

    .dsh-table { width:100%; border-collapse:collapse; }
    .dsh-table thead th { background:#fafbfc; text-align:left; font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8; padding:13px 20px; border-bottom:1px solid #eef1f5; }
    .dsh-table td { padding:14px 20px; font-size:13.3px; border-bottom:1px solid #f5f7fa; }
    .dsh-table tbody tr:nth-child(even) { background:#fbfcfd; }
    .dsh-table tbody tr:hover { background:rgba(0,129,171,.05); }
    .dsh-table tbody tr:last-child td { border-bottom:none; }
    .dsh-empty { text-align:center; padding:44px 20px; color:#94a3b8; font-size:13.5px; }

    .badge-potensi-sangat-tinggi { background:rgba(46,158,91,.14); color:#2E9E5B; padding:4px 11px; border-radius:999px; font-size:11px; font-weight:700; }
    .badge-potensi-tinggi { background:rgba(232,163,23,.14); color:#92660f; padding:4px 11px; border-radius:999px; font-size:11px; font-weight:700; }
    .badge-potensi-sedang { background:#eef2f7; color:#64748B; padding:4px 11px; border-radius:999px; font-size:11px; font-weight:700; }

    .progress-bar-wrap { display:flex; align-items:center; gap:8px; }
    .progress-bar-track { flex:1; height:6px; background:#eef1f5; border-radius:999px; overflow:hidden; min-width:60px; }
    .progress-bar-fill { height:100%; border-radius:999px; background:#2E9E5B; }
    .progress-bar-fill.amber { background:#E8A317; }
    .progress-bar-fill.red { background:#C0392B; }
    .progress-bar-value { font-weight:800; font-size:13px; width:30px; text-align:right; }
</style>

<p class="dsh-subtitle">Ringkasan Sistem SPKLU</p>

<div class="dsh-card-grid">
    <div class="dsh-card">
        <div class="dsh-card-top">
            <div>
                <p class="dsh-card-label">Total SPKLU Terpasang</p>
                <p class="dsh-card-value">{{ number_format($totalSpkluTerpasang) }} <span style="font-size:13px; font-weight:500; color:#94a3b8;">unit</span></p>
            </div>
            <div class="dsh-card-icon" style="background:linear-gradient(135deg, rgba(2,62,138,.12), rgba(0,129,171,.12)); color:#023E8A;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg></div>
        </div>
        <span class="dsh-trend dsh-trend-up">+{{ $spkluBaruBulanIni ?? 0 }} bulan ini</span>
    </div>

    <div class="dsh-card">
        <div class="dsh-card-top">
            <div>
                <p class="dsh-card-label">Pengajuan On-Progress</p>
                <p class="dsh-card-value">{{ number_format($pengajuanOnProgress) }} <span style="font-size:13px; font-weight:500; color:#94a3b8;">sesi</span></p>
            </div>
            <div class="dsh-card-icon" style="background:linear-gradient(135deg, rgba(232,163,23,.15), rgba(232,163,23,.06)); color:#E8A317;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg></div>
        </div>
        <span class="dsh-trend dsh-trend-neutral">vs bulan lalu</span>
    </div>

    <div class="dsh-card">
        <div class="dsh-card-top">
            <div>
                <p class="dsh-card-label">Kandidat Aktif</p>
                <p class="dsh-card-value">{{ number_format($kandidatAktif) }} <span style="font-size:13px; font-weight:500; color:#94a3b8;">lokasi</span></p>
            </div>
            <div class="dsh-card-icon" style="background:linear-gradient(135deg, rgba(46,158,91,.14), rgba(46,158,91,.06)); color:#2E9E5B;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0Z"/><circle cx="12" cy="10" r="3"/></svg></div>
        </div>
        <span class="dsh-trend dsh-trend-amber">{{ $kandidatButuhTindakLanjut }} menunggu tindak lanjut</span>
    </div>

    <div class="dsh-card">
        <div class="dsh-card-top">
            <div>
                <p class="dsh-card-label">Jadwal Mendatang</p>
                <p class="dsh-card-value">{{ ($jadwalHariIni->count() + $jadwalBesok->count()) }}</p>
            </div>
            <div class="dsh-card-icon" style="background:linear-gradient(135deg, rgba(147,51,234,.14), rgba(147,51,234,.06)); color:#9333ea;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
        </div>
        <span class="dsh-trend dsh-trend-neutral">minggu ini</span>
    </div>
</div>

<div class="dsh-grid-2col">
    <div class="surface-card">
        <div class="section-header-bar">
            <div class="section-header-bar-left">
                <div class="section-header-bar-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
                <div><h2>{{ now()->translatedFormat('F Y') }}</h2></div>
            </div>
            <div style="display:flex; gap:6px;">
                <button class="calendar-nav-btn">‹</button>
                <button class="calendar-today-btn">Hari Ini</button>
                <button class="calendar-nav-btn">›</button>
            </div>
        </div>

        <div class="calendar-widget">
            @php
                $bulanIni = now();
                $awalBulan = $bulanIni->copy()->startOfMonth();
                $akhirBulan = $bulanIni->copy()->endOfMonth();
                $offsetAwal = $awalBulan->dayOfWeekIso - 1;
            @endphp

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
                    <div class="calendar-cell {{ $isToday ? 'today' : '' }} {{ $hasEvent ? 'has-event' : '' }}">{{ $tgl }}</div>
                @endfor
            </div>
        </div>
    </div>

    <div class="surface-card">
        <div class="section-header-bar">
            <div class="section-header-bar-left">
                <div class="section-header-bar-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
                <div><h2>Jadwal Terdekat</h2></div>
            </div>
        </div>

        <div class="jadwal-widget">
            <p class="jadwal-section-label">Hari Ini</p>
            @forelse ($jadwalHariIni as $jadwal)
                <div class="jadwal-item">
                    <div class="jadwal-time">{{ $jadwal->waktu_mulai->format('H:i') }}</div>
                    <div class="jadwal-info">
                        <p class="jadwal-title">{{ $jadwal->judul }}</p>
                        <p class="jadwal-desc">{{ \Illuminate\Support\Str::limit($jadwal->deskripsi, 40) }}</p>
                    </div>
                    <span class="jadwal-badge {{ $jadwal->mode === 'online' ? 'jadwal-badge-online' : 'jadwal-badge-offline' }}">{{ ucfirst($jadwal->mode) }}</span>
                </div>
            @empty
                <p class="dsh-empty-inline">Tidak ada jadwal hari ini.</p>
            @endforelse

            <p class="jadwal-section-label">Besok</p>
            @forelse ($jadwalBesok as $jadwal)
                <div class="jadwal-item besok">
                    <div class="jadwal-time">{{ $jadwal->waktu_mulai->format('H:i') }}</div>
                    <div class="jadwal-info">
                        <p class="jadwal-title">{{ $jadwal->judul }}</p>
                        <p class="jadwal-desc">{{ \Illuminate\Support\Str::limit($jadwal->deskripsi, 40) }}</p>
                    </div>
                    <span class="jadwal-badge {{ $jadwal->mode === 'online' ? 'jadwal-badge-online' : 'jadwal-badge-offline' }}">{{ ucfirst($jadwal->mode) }}</span>
                </div>
            @empty
                <p class="dsh-empty-inline">Tidak ada jadwal besok.</p>
            @endforelse
        </div>
    </div>
</div>

<form method="GET" id="form-filter-dashboard" class="filter-shell" style="flex-direction:row; flex-wrap:wrap; align-items:center;">
    <div class="filter-label-inline">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
        Filter Bulan
    </div>

    <div class="dsh-month-range">
        <input type="month" name="dari_bulan" value="{{ $dariBulan }}" onchange="document.getElementById('form-filter-dashboard').submit()">
        <span class="dsh-month-range-sep">—</span>
        <input type="month" name="sampai_bulan" value="{{ $sampaiBulan }}" onchange="document.getElementById('form-filter-dashboard').submit()">
    </div>
</form>

<div class="surface-card">
    <div class="section-header-bar">
        <div class="section-header-bar-left">
            <div class="section-header-bar-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
            <div>
                <h2>Tren Transaksi</h2>
                <p>{{ \Carbon\Carbon::createFromFormat('Y-m', $dariBulan)->translatedFormat('M Y') }} – {{ \Carbon\Carbon::createFromFormat('Y-m', $sampaiBulan)->translatedFormat('M Y') }}</p>
            </div>
        </div>
        <span class="dsh-trend {{ $trenTransaksiPersen >= 0 ? 'dsh-trend-up' : 'dsh-trend-down' }}">
            {{ $trenTransaksiPersen >= 0 ? '↑' : '↓' }} {{ abs($trenTransaksiPersen) }}% vs periode lalu
        </span>
    </div>
    <div style="padding:22px 24px;">
        <canvas id="chart-tren-dashboard" height="70"></canvas>
    </div>
</div>

<div class="surface-card">
    <div class="section-header-bar">
        <div class="section-header-bar-left">
            <div class="section-header-bar-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/></svg></div>
            <div>
                <h2>Top 5 Kandidat Prioritas</h2>
                <p>Berdasarkan skor potensi pemasangan SPKLU</p>
            </div>
        </div>
    </div>

    <table class="dsh-table">
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
                    <td><span class="{{ $potensiClass }}">{{ $potensiLabel }}</span></td>
                    <td>
                        <div class="progress-bar-wrap">
                            <div class="progress-bar-track"><div class="progress-bar-fill {{ $barClass }}" style="width:{{ $kandidat->skor }}%;"></div></div>
                            <span class="progress-bar-value">{{ $kandidat->skor }}</span>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="dsh-empty">Belum ada data kandidat.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<script>
    Chart.register(ChartDataLabels);

    new Chart(document.getElementById('chart-tren-dashboard'), {
        type: 'line',
        data: {
            labels: {!! json_encode($trenTransaksiLabels ?? []) !!},
            datasets: [{
                data: {!! json_encode($trenTransaksiData ?? []) !!},
                borderColor: '#0081AB', backgroundColor: 'rgba(0,129,171,0.1)',
                tension: 0.35, fill: true, pointRadius: 4, pointBackgroundColor: '#023E8A', borderWidth: 2.5,
            }]
        },
        options: {
            plugins: {
                legend: { display: false },
                datalabels: {
                    align: 'top',
                    anchor: 'end',
                    color: '#023E8A',
                    font: { weight: '700', size: 11 },
                    formatter: (value) => new Intl.NumberFormat('id-ID').format(value),
                }
            },
            scales: {
                y: { beginAtZero: true, grace: '15%' }
            },
            layout: { padding: { top: 20 } }
        }
    });
</script>

@endsection