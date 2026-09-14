@extends('layouts.app')

@section('breadcrumb', 'Rekomendasi Lokasi')
@section('page-title', 'Rekomendasi Lokasi')

@section('content')

<style>
    .rl-page-header { margin-bottom:18px; }
    .rl-page-subtitle { color:#64748B; margin:0; font-size:13.5px; }

    .rl-card-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:18px; margin-bottom:20px; }
    .rl-card { background:#fff; border-radius:16px; padding:20px 22px; border:1px solid #eef1f5; box-shadow:0 1px 2px rgba(15,23,42,.04), 0 6px 16px rgba(15,23,42,.05); display:flex; align-items:center; gap:14px; }
    .rl-card-dot { width:14px; height:14px; border-radius:50%; flex-shrink:0; }
    .rl-card-value { font-size:24px; font-weight:800; color:#0f172a; margin:0; }
    .rl-card-label { font-size:12px; font-weight:600; color:#94a3b8; margin:2px 0 0; }

    .rl-select {
        padding:9px 32px 9px 14px; border-radius:9px; border:1px solid #e2e8f0; font-size:13px; font-weight:500;
        background:#fff; color:#1E293B; cursor:pointer; appearance:none;
        background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6' fill='none'%3E%3Cpath d='M1 1L5 5L9 1' stroke='%2364748B' stroke-width='1.6' stroke-linecap='round'/%3E%3C/svg%3E");
        background-repeat:no-repeat; background-position:right 12px center;
    }
    .rl-select:focus { outline:none; border-color:#0081AB; box-shadow:0 0 0 3px rgba(0,129,171,.14); }

    .rl-peta-wrapper { margin-bottom:20px; }

    .rl-bottom-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; align-items:stretch; }
    @media (max-width:1000px) { .rl-bottom-grid { grid-template-columns:1fr; } }

    .rl-bottom-grid .surface-card { display:flex; flex-direction:column; }
    .rl-card-scroll { overflow-y:auto; height:420px; }
    .rl-card-scroll::-webkit-scrollbar { width:6px; }
    .rl-card-scroll::-webkit-scrollbar-thumb { background:#e2e8f0; border-radius:6px; }

    #peta-rekomendasi { height:560px; width:100%; border-radius:0 0 16px 16px; }

    .rl-legend { display:flex; gap:16px; flex-wrap:wrap; padding:14px 20px; border-bottom:1px solid #f1f5f9; font-size:12.5px; }
    .rl-legend-item { display:flex; align-items:center; gap:7px; color:#334155; }
    .rl-legend-dot { width:11px; height:11px; border-radius:50%; flex-shrink:0; }
    .rl-legend-dot.rl-legend-plus { background:#2563EB; position:relative; }

    .rl-wilayah-item { display:flex; align-items:center; justify-content:space-between; padding:12px 20px; border-bottom:1px solid #f5f7fa; }
    .rl-wilayah-item:last-child { border-bottom:none; }
    .rl-wilayah-nama { font-weight:700; font-size:13px; color:#1E293B; }
    .rl-wilayah-meta { font-size:11.5px; color:#94a3b8; margin-top:2px; }
    .rl-wilayah-persen { font-size:16px; font-weight:800; color:#2E9E5B; }

    .rl-rekomendasi-item { padding:12px 20px; border-bottom:1px solid #f5f7fa; }
    .rl-rekomendasi-item:last-child { border-bottom:none; }
    .rl-rekomendasi-top { display:flex; align-items:flex-start; gap:12px; }
    .rl-rekomendasi-rank { width:22px; height:22px; border-radius:50%; background:#EFF6FF; color:#2563EB; font-size:11.5px; font-weight:800; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:1px; }
    .rl-rekomendasi-nama { font-weight:700; font-size:12.5px; color:#1E293B; }
    .rl-rekomendasi-meta { font-size:11px; color:#94a3b8; margin-top:2px; line-height:1.5; }
    .rl-rekomendasi-skor { margin-left:auto; text-align:right; flex-shrink:0; }
    .rl-rekomendasi-skor-nilai { font-size:14px; font-weight:800; color:#2563EB; }
    .rl-rekomendasi-skor-label { font-size:10px; color:#94a3b8; }
    .rl-rekomendasi-aksi { margin-top:8px; padding-left:34px; }
    .rl-btn-jadikan-kandidat {
        display:inline-flex; align-items:center; gap:6px; font-size:11.5px; font-weight:700;
        color:#2563EB; background:#EFF6FF; border:1px solid rgba(37,99,235,.25); border-radius:7px;
        padding:6px 12px; text-decoration:none; transition:background .15s ease;
    }
    .rl-btn-jadikan-kandidat:hover { background:#DBEAFE; }
    .rl-btn-jadikan-kandidat svg { width:13px; height:13px; }

    .rl-empty { text-align:center; padding:32px 20px; color:#94a3b8; font-size:13px; }

    .rl-info-box { display:flex; gap:10px; align-items:flex-start; font-size:12.5px; color:#0369a1; background:rgba(0,129,171,.07); border:1px solid rgba(0,129,171,.15); border-radius:9px; padding:12px 14px; margin-bottom:16px; }
    .rl-info-box svg { width:15px; height:15px; min-width:15px; margin-top:1px; color:#0081AB; }

    .rl-marker-rekomendasi-inner {
        width:24px; height:24px; border-radius:50%; background:#2563EB; border:2px solid #fff;
        box-shadow:0 2px 6px rgba(37,99,235,.5); display:flex; align-items:center; justify-content:center;
        color:#fff; font-weight:800; font-size:14px; line-height:1;
    }
</style>

<div class="rl-page-header">
    <p class="rl-page-subtitle">Peta zona risiko kanibalisasi & titik rekomendasi otomatis berdasarkan skor gabungan (kepadatan + tren) SPKLU existing.</p>
</div>

<div class="rl-info-box">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
    <span>Warna zona dari <strong>skor gabungan</strong>: rata-rata transaksi bulanan (12 bulan) yang disesuaikan jarak ideal ULP (biar area urban vs jarang dibandingkan adil), dipadukan tren 3 bulan terakhir. Titik biru (+) di peta adalah <strong>rekomendasi otomatis</strong> — koordinat yang paling potensial nampung demand tanpa masuk zona padat. Klik "Jadikan Kandidat Baru" buat langsung masukin ke pipeline Probabilitas.</span>
</div>

<div class="rl-card-grid">
    <div class="rl-card">
        <span class="rl-card-dot" style="background:#2E9E5B;"></span>
        <div>
            <p class="rl-card-value">{{ $ringkasan['hijau'] }}</p>
            <p class="rl-card-label">Zona Aman (Hijau)</p>
        </div>
    </div>
    <div class="rl-card">
        <span class="rl-card-dot" style="background:#E8A317;"></span>
        <div>
            <p class="rl-card-value">{{ $ringkasan['kuning'] }}</p>
            <p class="rl-card-label">Zona Waspada (Kuning)</p>
        </div>
    </div>
    <div class="rl-card">
        <span class="rl-card-dot" style="background:#C0392B;"></span>
        <div>
            <p class="rl-card-value">{{ $ringkasan['merah'] }}</p>
            <p class="rl-card-label">Zona Padat (Merah)</p>
        </div>
    </div>
    <div class="rl-card">
        <span class="rl-card-dot" style="background:#2563EB;"></span>
        <div>
            <p class="rl-card-value">{{ $titikRekomendasi->count() }}</p>
            <p class="rl-card-label">Titik Rekomendasi</p>
        </div>
    </div>
</div>

<div class="rl-peta-wrapper surface-card" style="overflow:hidden;">
    <div class="section-header-bar">
        <div class="section-header-bar-left">
            <div class="section-header-bar-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0Z"/><circle cx="12" cy="10" r="3"/></svg></div>
            <div><h2>Peta Zona SPKLU</h2></div>
        </div>
        <form method="GET">
            <select name="ulp_mapping_id" class="rl-select" onchange="this.form.submit()">
                <option value="">Semua ULP</option>
                @foreach ($daftarUlp as $ulp)
                    <option value="{{ $ulp->id }}" {{ (string) $ulpTerpilih === (string) $ulp->id ? 'selected' : '' }}>{{ $ulp->nama_penuh }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="rl-legend">
        <span class="rl-legend-item"><span class="rl-legend-dot" style="background:#2E9E5B;"></span> Hijau — aman, skor gabungan rendah</span>
        <span class="rl-legend-item"><span class="rl-legend-dot" style="background:#E8A317;"></span> Kuning — waspada, cek dulu</span>
        <span class="rl-legend-item"><span class="rl-legend-dot" style="background:#C0392B;"></span> Merah — padat, hindari terlalu dekat</span>
        <span class="rl-legend-item"><span class="rl-legend-dot" style="background:#94A3B8;"></span> Abu — belum ada data transaksi</span>
        <span class="rl-legend-item"><span class="rl-legend-dot rl-legend-plus" style="background:#2563EB;"></span> Biru (+) — titik rekomendasi otomatis</span>
    </div>

    <div id="peta-rekomendasi"></div>
</div>

<div class="rl-bottom-grid">
    <div class="surface-card">
        <div class="section-header-bar">
            <div class="section-header-bar-left">
                <div class="section-header-bar-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/><circle cx="12" cy="12" r="4"/></svg></div>
                <div>
                    <h2>Titik Rekomendasi Otomatis</h2>
                    <p>Ranking skor potensi, urut tertinggi</p>
                </div>
            </div>
        </div>

        <div class="rl-card-scroll">
            @forelse ($titikRekomendasi as $i => $t)
                <div class="rl-rekomendasi-item">
                    <div class="rl-rekomendasi-top">
                        <span class="rl-rekomendasi-rank">{{ $i + 1 }}</span>
                        <div>
                            <p class="rl-rekomendasi-nama">{{ number_format($t['latitude'], 5) }}, {{ number_format($t['longitude'], 5) }}</p>
                            <p class="rl-rekomendasi-meta">
                                {{ $t['jarak_terdekat_km'] }} km dari {{ $t['spklu_terdekat'] }}<br>
                                ULP terdekat: {{ $t['ulp_terdekat'] ?? '-' }}
                            </p>
                        </div>
                        <div class="rl-rekomendasi-skor">
                            <div class="rl-rekomendasi-skor-nilai">{{ $t['skor_potensi'] }}</div>
                            <div class="rl-rekomendasi-skor-label">skor potensi</div>
                        </div>
                    </div>
                    <div class="rl-rekomendasi-aksi">
                        <a href="{{ route('monitoring.kandidat.create', ['tikor' => $t['latitude'] . ', ' . $t['longitude']]) }}" class="rl-btn-jadikan-kandidat">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Jadikan Kandidat Baru
                        </a>
                    </div>
                </div>
            @empty
                <div class="rl-empty">Belum cukup data SPKLU/transaksi buat nyusun titik rekomendasi.</div>
            @endforelse
        </div>
    </div>

    <div class="surface-card">
        <div class="section-header-bar">
            <div class="section-header-bar-left">
                <div class="section-header-bar-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/></svg></div>
                <div>
                    <h2>Wilayah Paling Potensial</h2>
                    <p>ULP dengan proporsi zona hijau terbanyak</p>
                </div>
            </div>
        </div>

        <div class="rl-card-scroll">
            @forelse ($rekomendasiWilayah as $w)
                <div class="rl-wilayah-item">
                    <div>
                        <p class="rl-wilayah-nama">{{ $w['ulp'] }}</p>
                        <p class="rl-wilayah-meta">{{ $w['jumlah_hijau'] }} dari {{ $w['total_spklu'] }} SPKLU berzona hijau</p>
                    </div>
                    <span class="rl-wilayah-persen">{{ $w['persen_hijau'] }}%</span>
                </div>
            @empty
                <div class="rl-empty">Belum cukup data untuk menyusun rekomendasi wilayah.</div>
            @endforelse
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const titikPeta = @json($titikPeta);
    const titikRekomendasi = @json($titikRekomendasi);

    const warnaStatus = {
        hijau: '#2E9E5B',
        kuning: '#E8A317',
        merah: '#C0392B',
        belum_ada_data: '#94A3B8',
    };

    const labelStatus = {
        hijau: 'Aman',
        kuning: 'Waspada',
        merah: 'Padat',
        belum_ada_data: 'Belum ada data',
    };

    function formatTren(t) {
        if (!t.tren_label) return '-';
        const panah = t.tren_label === 'naik' ? '▲' : (t.tren_label === 'turun' ? '▼' : '→');
        const label = t.tren_label.charAt(0).toUpperCase() + t.tren_label.slice(1);
        return `${panah} ${label} (${t.tren_persen}%)`;
    }

    let pusatLat = -6.5971, pusatLng = 106.8060, zoomAwal = 11;
    if (titikPeta.length > 0) {
        pusatLat = titikPeta.reduce((a, t) => a + t.latitude, 0) / titikPeta.length;
        pusatLng = titikPeta.reduce((a, t) => a + t.longitude, 0) / titikPeta.length;
    }

    const peta = L.map('peta-rekomendasi').setView([pusatLat, pusatLng], zoomAwal);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19,
    }).addTo(peta);

    const batasSemuaTitik = [];

    titikPeta.forEach(t => {
        const warna = warnaStatus[t.status] || '#94A3B8';

        L.circle([t.latitude, t.longitude], {
            radius: t.radius_km * 1000,
            color: warna,
            fillColor: warna,
            fillOpacity: 0.15,
            weight: 1.5,
        }).addTo(peta);

        L.circleMarker([t.latitude, t.longitude], {
            radius: 6,
            color: '#fff',
            weight: 2,
            fillColor: warna,
            fillOpacity: 1,
        }).addTo(peta).bindPopup(`
            <strong>${t.nama}</strong><br>
            ULP: ${t.ulp ?? '-'}<br>
            Status: <span style="color:${warna}; font-weight:700;">${labelStatus[t.status]}</span><br>
            Rata-rata transaksi/bulan: ${t.rata_rata_transaksi_bulan !== null ? t.rata_rata_transaksi_bulan : '-'}<br>
            Tren 3 bulan: ${formatTren(t)}<br>
            Skor gabungan: ${t.skor_gabungan !== null ? t.skor_gabungan : '-'}<br>
            Radius zona: ${t.radius_km} km
        `);

        batasSemuaTitik.push([t.latitude, t.longitude]);
    });

    const iconRekomendasi = L.divIcon({
        className: '',
        html: '<div class="rl-marker-rekomendasi-inner">+</div>',
        iconSize: [24, 24],
        iconAnchor: [12, 12],
    });

    titikRekomendasi.forEach((t, i) => {
        L.marker([t.latitude, t.longitude], { icon: iconRekomendasi })
            .addTo(peta)
            .bindPopup(`
                <strong>Rekomendasi #${i + 1}</strong><br>
                Koordinat: ${t.latitude}, ${t.longitude}<br>
                Skor potensi: <strong>${t.skor_potensi}</strong><br>
                Jarak ke SPKLU terdekat: ${t.jarak_terdekat_km} km (${t.spklu_terdekat})<br>
                ULP terdekat: ${t.ulp_terdekat ?? '-'}<br>
                <a href="/monitoring/kandidat/create?tikor=${t.latitude},${t.longitude}" style="color:#2563EB; font-weight:700;">Jadikan Kandidat Baru →</a>
            `);

        batasSemuaTitik.push([t.latitude, t.longitude]);
    });

    if (batasSemuaTitik.length > 1) {
        peta.fitBounds(batasSemuaTitik, { padding: [30, 30] });
    }
</script>

@endsection