@extends('layouts.app')

@section('breadcrumb', 'Rekomendasi Lokasi')
@section('page-title', 'Rekomendasi Lokasi')

@section('content')

<style>
    .rl-page-header { margin-bottom:18px; }
    .rl-page-subtitle { color:#64748B; margin:0; font-size:13.5px; }

    .rl-card-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:18px; margin-bottom:20px; }
    .rl-card { background:#fff; border-radius:16px; padding:20px 22px; border:1px solid #eef1f5; box-shadow:0 1px 2px rgba(15,23,42,.04), 0 6px 16px rgba(15,23,42,.05); display:flex; align-items:center; gap:14px; cursor:pointer; transition:box-shadow .15s ease, border-color .15s ease; }
    .rl-card:hover { box-shadow:0 4px 10px rgba(15,23,42,.08), 0 10px 24px rgba(15,23,42,.08); }
    .rl-card.rl-card-active { border-color:currentColor; }
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

    .rl-filter-group { display:flex; align-items:center; gap:8px; }

    .rl-peta-wrapper { margin-bottom:20px; }

    .rl-bottom-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; align-items:stretch; }
    @media (max-width:1000px) { .rl-bottom-grid { grid-template-columns:1fr; } }

    .rl-bottom-grid .surface-card { display:flex; flex-direction:column; }
    .rl-card-scroll { overflow-y:auto; height:420px; }
    .rl-card-scroll::-webkit-scrollbar { width:6px; }
    .rl-card-scroll::-webkit-scrollbar-thumb { background:#e2e8f0; border-radius:6px; }

    #peta-rekomendasi { height:560px; width:100%; border-radius:0 0 16px 16px; }

    .rl-legend { display:flex; gap:16px; flex-wrap:wrap; padding:14px 20px; border-bottom:1px solid #f1f5f9; font-size:12.5px; }
    .rl-legend-item { display:flex; align-items:center; gap:7px; color:#334155; cursor:pointer; padding:4px 8px; border-radius:6px; transition:background .15s ease; }
    .rl-legend-item:hover { background:#f8fafc; }
    .rl-legend-item.rl-legend-item-off { opacity:.35; }
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

    .rl-tindak-badge { display:inline-flex; align-items:center; gap:5px; font-size:11px; font-weight:700; padding:4px 10px; border-radius:20px; white-space:nowrap; }
    .rl-tindak-badge.ganti-mesin { background:rgba(192,57,43,.1); color:#C0392B; }
    .rl-tindak-badge.tambah-unit { background:rgba(0,129,171,.1); color:#0081AB; }

    .rl-tindak-item { display:flex; align-items:center; gap:14px; padding:14px 20px; border-bottom:1px solid #f5f7fa; }
    .rl-tindak-item:last-child { border-bottom:none; }
    .rl-tindak-rank { width:24px; height:24px; border-radius:50%; background:#FEF2F2; color:#C0392B; font-size:11.5px; font-weight:800; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .rl-tindak-nama { font-weight:700; font-size:13px; color:#1E293B; }
    .rl-tindak-meta { font-size:11.5px; color:#94a3b8; margin-top:2px; }
    .rl-tindak-skor { text-align:center; flex-shrink:0; }
    .rl-tindak-skor-nilai { font-size:15px; font-weight:800; color:#C0392B; }
    .rl-tindak-skor-label { font-size:9.5px; color:#94a3b8; text-transform:uppercase; }

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

{{-- Klik kartu buat filter zona di peta --}}
<div class="rl-card-grid">
    <div class="rl-card" data-kategori="hijau" onclick="toggleFilterKartu('hijau')">
        <span class="rl-card-dot" style="background:#2E9E5B;"></span>
        <div>
            <p class="rl-card-value">{{ $ringkasan['hijau'] }}</p>
            <p class="rl-card-label">Zona Aman (Hijau)</p>
        </div>
    </div>
    <div class="rl-card" data-kategori="kuning" onclick="toggleFilterKartu('kuning')">
        <span class="rl-card-dot" style="background:#E8A317;"></span>
        <div>
            <p class="rl-card-value">{{ $ringkasan['kuning'] }}</p>
            <p class="rl-card-label">Zona Waspada (Kuning)</p>
        </div>
    </div>
    <div class="rl-card" data-kategori="merah" onclick="toggleFilterKartu('merah')">
        <span class="rl-card-dot" style="background:#C0392B;"></span>
        <div>
            <p class="rl-card-value">{{ $ringkasan['merah'] }}</p>
            <p class="rl-card-label">Zona Padat (Merah)</p>
        </div>
    </div>
    <div class="rl-card" data-kategori="_rekomendasi" onclick="toggleFilterKartu('_rekomendasi')">
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

        <div class="rl-filter-group">
            {{-- Filter kategori zona — client-side, gak reload halaman --}}
            <select id="rl-filter-kategori" class="rl-select" onchange="filterKategoriPeta(this.value)">
                <option value="">Semua Kategori</option>
                <option value="hijau">🟢 Hijau — Aman</option>
                <option value="kuning">🟡 Kuning — Waspada</option>
                <option value="merah">🔴 Merah — Padat</option>
                <option value="belum_ada_data">⚪ Belum Ada Data</option>
            </select>

            <form method="GET">
                <select name="ulp_mapping_id" class="rl-select" onchange="this.form.submit()">
                    <option value="">Semua ULP</option>
                    @foreach ($daftarUlp as $ulp)
                        <option value="{{ $ulp->id }}" {{ (string) $ulpTerpilih === (string) $ulp->id ? 'selected' : '' }}>{{ $ulp->nama_penuh }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <div class="rl-legend">
        <span class="rl-legend-item" data-kategori="hijau" onclick="toggleFilterLegenda('hijau')"><span class="rl-legend-dot" style="background:#2E9E5B;"></span> Hijau — aman, skor gabungan rendah</span>
        <span class="rl-legend-item" data-kategori="kuning" onclick="toggleFilterLegenda('kuning')"><span class="rl-legend-dot" style="background:#E8A317;"></span> Kuning — waspada, cek dulu</span>
        <span class="rl-legend-item" data-kategori="merah" onclick="toggleFilterLegenda('merah')"><span class="rl-legend-dot" style="background:#C0392B;"></span> Merah — padat, hindari terlalu dekat</span>
        <span class="rl-legend-item" data-kategori="belum_ada_data" onclick="toggleFilterLegenda('belum_ada_data')"><span class="rl-legend-dot" style="background:#94A3B8;"></span> Abu — belum ada data transaksi</span>
        <span class="rl-legend-item" data-kategori="_rekomendasi" onclick="toggleFilterLegenda('_rekomendasi')"><span class="rl-legend-dot rl-legend-plus" style="background:#2563EB;"></span> Biru (+) — titik rekomendasi otomatis</span>
    </div>

    <div id="peta-rekomendasi"></div>
</div>

{{-- SPKLU EXISTING yang statusnya merah (padat) & butuh tindak lanjut --}}
@if ($spkluPerluTindakLanjut->isNotEmpty())
<div class="surface-card" style="margin-bottom:20px;">
    <div class="section-header-bar">
        <div class="section-header-bar-left">
            <div class="section-header-bar-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></div>
            <div>
                <h2>SPKLU Perlu Tindak Lanjut</h2>
                <p>Lokasi existing berstatus padat (merah) — bukan lahan kosong, tapi unit yang udah ada kewalahan</p>
            </div>
        </div>
    </div>

    <div class="rl-card-scroll" style="height:340px;">
        @foreach ($spkluPerluTindakLanjut as $s)
            <div class="rl-tindak-item">
                <span class="rl-tindak-rank">{{ $loop->iteration }}</span>
                <div style="flex:1; min-width:0;">
                    <p class="rl-tindak-nama">{{ $s['nama'] }}</p>
                    <p class="rl-tindak-meta">
                        {{ $s['ulp'] ?? '-' }} &middot;
                        {{ $s['kapasitas_kw'] }} kW &middot;
                        {{ $s['rata_rata_transaksi_bulan'] ?? '-' }} transaksi/bulan &middot;
                        {{ $s['rata_rata_durasi_menit_bulan'] !== null ? number_format($s['rata_rata_durasi_menit_bulan'] / 60, 1) . ' jam' : '-' }} durasi/bulan
                    </p>
                </div>
                <div class="rl-tindak-skor">
                    <div class="rl-tindak-skor-nilai">{{ $s['skor_gabungan'] }}</div>
                    <div class="rl-tindak-skor-label">Skor</div>
                </div>
                @if ($s['rekomendasi_tindak_lanjut'] === 'ganti_mesin')
                    <span class="rl-tindak-badge ganti-mesin">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                        Ganti Mesin
                    </span>
                @else
                    <span class="rl-tindak-badge tambah-unit">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Tambah Unit
                    </span>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endif

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

    // Nyimpen referensi tiap layer zona (circle + circleMarker) beserta kategorinya,
    // supaya bisa di-toggle tampil/sembunyi tanpa reload/refetch data.
    const zonaLayers = [];

    titikPeta.forEach(t => {
        const warna = warnaStatus[t.status] || '#94A3B8';

        const circle = L.circle([t.latitude, t.longitude], {
            radius: t.radius_km * 1000,
            color: warna,
            fillColor: warna,
            fillOpacity: 0.15,
            weight: 1.5,
        }).addTo(peta);

        const marker = L.circleMarker([t.latitude, t.longitude], {
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

        zonaLayers.push({ status: t.status, circle, marker });
        batasSemuaTitik.push([t.latitude, t.longitude]);
    });

    const iconRekomendasi = L.divIcon({
        className: '',
        html: '<div class="rl-marker-rekomendasi-inner">+</div>',
        iconSize: [24, 24],
        iconAnchor: [12, 12],
    });

    // Titik rekomendasi (biru +) dianggap kategori khusus "_rekomendasi" biar bisa di-toggle juga
    const rekomendasiLayers = [];

    titikRekomendasi.forEach((t, i) => {
        const marker = L.marker([t.latitude, t.longitude], { icon: iconRekomendasi })
            .addTo(peta)
            .bindPopup(`
                <strong>Rekomendasi #${i + 1}</strong><br>
                Koordinat: ${t.latitude}, ${t.longitude}<br>
                Skor potensi: <strong>${t.skor_potensi}</strong><br>
                Jarak ke SPKLU terdekat: ${t.jarak_terdekat_km} km (${t.spklu_terdekat})<br>
                ULP terdekat: ${t.ulp_terdekat ?? '-'}<br>
                <a href="/monitoring/kandidat/create?tikor=${t.latitude},${t.longitude}" style="color:#2563EB; font-weight:700;">Jadikan Kandidat Baru →</a>
            `);

        rekomendasiLayers.push(marker);
        batasSemuaTitik.push([t.latitude, t.longitude]);
    });

    if (batasSemuaTitik.length > 1) {
        peta.fitBounds(batasSemuaTitik, { padding: [30, 30] });
    }

    /* =========================================================
       FILTER KATEGORI (dropdown, kartu ringkasan, & legenda)
       Semua saling sinkron ke satu sumber: kategoriAktif
    ========================================================= */

    let kategoriAktif = '';

    function terapkanFilter() {
        // Toggle zona (hijau/kuning/merah/belum_ada_data)
        zonaLayers.forEach(({ status, circle, marker }) => {
            const tampil = !kategoriAktif || kategoriAktif === status;

            if (tampil) {
                if (!peta.hasLayer(circle)) circle.addTo(peta);
                if (!peta.hasLayer(marker)) marker.addTo(peta);
            } else {
                if (peta.hasLayer(circle)) peta.removeLayer(circle);
                if (peta.hasLayer(marker)) peta.removeLayer(marker);
            }
        });

        // Toggle titik rekomendasi (kategori khusus "_rekomendasi")
        const tampilRekomendasi = !kategoriAktif || kategoriAktif === '_rekomendasi';
        rekomendasiLayers.forEach(marker => {
            if (tampilRekomendasi) {
                if (!peta.hasLayer(marker)) marker.addTo(peta);
            } else {
                if (peta.hasLayer(marker)) peta.removeLayer(marker);
            }
        });

        // Sinkronisasi tampilan dropdown
        document.getElementById('rl-filter-kategori').value = kategoriAktif;

        // Sinkronisasi highlight kartu ringkasan
        document.querySelectorAll('.rl-card[data-kategori]').forEach(card => {
            card.classList.toggle('rl-card-active', kategoriAktif && card.dataset.kategori === kategoriAktif);
        });

        // Sinkronisasi highlight legenda
        document.querySelectorAll('.rl-legend-item[data-kategori]').forEach(item => {
            item.classList.toggle('rl-legend-item-off', kategoriAktif && item.dataset.kategori !== kategoriAktif);
        });
    }

    function filterKategoriPeta(kategori) {
        kategoriAktif = kategori;
        terapkanFilter();
    }

    function toggleFilterKartu(kategori) {
        kategoriAktif = (kategoriAktif === kategori) ? '' : kategori;
        terapkanFilter();
    }

    function toggleFilterLegenda(kategori) {
        kategoriAktif = (kategoriAktif === kategori) ? '' : kategori;
        terapkanFilter();
    }
</script>

@endsection