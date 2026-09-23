@extends('layouts.app')

@section('breadcrumb', 'Rekomendasi Lokasi')
@section('page-title', 'Rekomendasi Lokasi')

@section('content')

<style>
    .rl-page-header { margin-bottom:18px; }
    .rl-page-subtitle { color:#64748B; margin:0; font-size:13.5px; }

    .rl-card-grid { display:grid; grid-template-columns:repeat(5, 1fr); gap:14px; margin-bottom:20px; }
    @media (max-width:1100px) { .rl-card-grid { grid-template-columns:repeat(auto-fit, minmax(170px, 1fr)); } }
    .rl-card {
        background:#fff; border-radius:16px; padding:18px 20px; border:1px solid #e2e8f0;
        box-shadow:0 2px 6px rgba(15,23,42,.03), 0 10px 15px -3px rgba(15,23,42,.02); position:relative; overflow:hidden;
        cursor:pointer; transition:transform .2s cubic-bezier(0.4, 0, 0.2, 1), box-shadow .2s cubic-bezier(0.4, 0, 0.2, 1), border-color .18s ease;
        display:flex; flex-direction:column; justify-content:space-between;
    }
    .rl-card::before { content:""; position:absolute; top:0; left:0; right:0; height:3px; }
    .rl-card.dark-blue::before { background:linear-gradient(90deg, #023E8A, #0081AB); }
    .rl-card.light-blue::before { background:linear-gradient(90deg, #0081AB, #38BDF8); }
    .rl-card.amber::before  { background:linear-gradient(90deg, #D97706, #F59E0B); }
    .rl-card.purple::before { background:linear-gradient(90deg, #7C3AED, #8B5CF6); }
    .rl-card.blue::before   { background:linear-gradient(90deg, #2563EB, #60A5FA); }
    .rl-card:hover {
        box-shadow:0 12px 24px -4px rgba(15,23,42,.08);
        transform:translateY(-3px);
    }
    .rl-card.rl-card-active {
        box-shadow:0 0 0 2px #0081AB, 0 12px 24px -4px rgba(0,129,171,.15);
        border-color:#0081AB;
        transform:translateY(-3px);
    }
    .rl-card.dark-blue.rl-card-active  { box-shadow:0 0 0 2px #023E8A, 0 12px 24px -4px rgba(2,62,138,.18); border-color:#023E8A; }
    .rl-card.light-blue.rl-card-active { box-shadow:0 0 0 2px #0081AB, 0 12px 24px -4px rgba(0,129,171,.18); border-color:#0081AB; }
    .rl-card.amber.rl-card-active      { box-shadow:0 0 0 2px #E8A317, 0 12px 24px -4px rgba(232,163,23,.18); border-color:#E8A317; }
    .rl-card.purple.rl-card-active     { box-shadow:0 0 0 2px #7C3AED, 0 12px 24px -4px rgba(124,58,237,.18); border-color:#7C3AED; }
    .rl-card.blue.rl-card-active       { box-shadow:0 0 0 2px #2563EB, 0 12px 24px -4px rgba(37,99,235,.18); border-color:#2563EB; }
    .rl-card-top { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:10px; }
    .rl-card-icon { width:40px; height:40px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .rl-card-icon svg { width:19px; height:19px; stroke-width:2; }
    .rl-card-icon.dark-blue  { background:rgba(2,62,138,.1); color:#023E8A; }
    .rl-card-icon.light-blue { background:rgba(0,129,171,.1); color:#0081AB; }
    .rl-card-icon.amber      { background:rgba(217,119,6,.1); color:#D97706; }
    .rl-card-icon.purple     { background:rgba(124,58,237,.1); color:#7C3AED; }
    .rl-card-icon.blue       { background:rgba(37,99,235,.1); color:#2563EB; }
    .rl-card-label { font-size:11.5px; font-weight:700; text-transform:uppercase; letter-spacing:.04em; color:#64748B; margin:0; }
    .rl-card-value { font-size:28px; font-weight:800; color:#1B2559 !important; margin:0 0 4px; line-height:1; }
    .rl-card-note { font-size:12px; color:#64748B; line-height:1.4; margin:0; }

    .rl-select {
        padding:9px 32px 9px 14px; border-radius:9px; border:1px solid #e2e8f0; font-size:13px; font-weight:500;
        background:#fff; color:#1E293B; cursor:pointer; appearance:none;
        background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6' fill='none'%3E%3Cpath d='M1 1L5 5L9 1' stroke='%2364748B' stroke-width='1.6' stroke-linecap='round'/%3E%3C/svg%3E");
        background-repeat:no-repeat; background-position:right 12px center;
    }
    .rl-select:focus { outline:none; border-color:#0081AB; box-shadow:0 0 0 3px rgba(0,129,171,.14); }

    .rl-filter-group { display:flex; align-items:center; gap:8px; }

    .rl-filter-pills { display:inline-flex; gap:3px; background:#F1F5F9; padding:3px; border-radius:9px; margin-left:14px; }
    .rl-pill {
        border:none; background:transparent; padding:5px 11px; border-radius:6px;
        font-size:12px; font-weight:600; color:#64748B; cursor:pointer;
        transition:all .15s ease; white-space:nowrap; display:inline-flex; align-items:center; gap:4px;
    }
    .rl-pill:hover { color:#0F172A; background:rgba(255,255,255,0.7); }
    .rl-pill.active { background:#fff; color:#0081AB; font-weight:700; box-shadow:0 1px 3px rgba(15,23,42,.08); }
    @media (max-width:1050px) { .rl-filter-pills { display:none; } }

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
    .rl-legend-dot { width:16px; height:16px; border-radius:50%; flex-shrink:0; display:inline-flex; align-items:center; justify-content:center; }
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
        width:26px; height:26px; border-radius:50%; background:#2563EB; border:2px solid #fff;
        box-shadow:0 2px 7px rgba(37,99,235,.55); display:flex; align-items:center; justify-content:center;
        color:#fff; font-weight:800; font-size:16px; line-height:1; cursor:pointer;
    }
</style>

<div class="rl-page-header">
    <h1 style="font-size:22px; font-weight:800; color:#1B2559; margin:0 0 4px; letter-spacing:-0.015em;">Rekomendasi Lokasi</h1>
    <p class="rl-page-subtitle">Peta sebaran SPKLU eksisting (DC/AC), kandidat pipeline berdasarkan kepemilikan mitra mesin, dan titik rekomendasi otomatis.</p>
</div>

<div class="rl-info-box">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
    <span>Distribusi SPKLU: <strong>Biru Tua</strong> untuk Existing DC, <strong>Biru Muda</strong> untuk Existing AC, <strong>Kuning</strong> untuk SPKLU Baru/Kandidat yang sudah memiliki pasangan (mitra mesin), dan <strong>Ungu</strong> untuk kandidat yang belum ada pasangan. Titik <strong>Biru (+)</strong> di peta adalah rekomendasi otomatis koordinat potensial baru.</span>
</div>

{{-- Klik kartu buat filter zona di peta --}}
<div class="rl-card-grid">
    <div class="rl-card dark-blue" data-kategori="dc" onclick="toggleFilterKartu('dc')">
        <div class="rl-card-top">
            <div class="rl-card-label">SPKLU Existing DC</div>
            <div class="rl-card-icon dark-blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/></svg>
            </div>
        </div>
        <div class="rl-card-value">{{ $ringkasan['dc'] ?? 0 }}</div>
        <div class="rl-card-note">Fast / Ultra Fast DC</div>
    </div>
    <div class="rl-card light-blue" data-kategori="ac" onclick="toggleFilterKartu('ac')">
        <div class="rl-card-top">
            <div class="rl-card-label">SPKLU Existing AC</div>
            <div class="rl-card-icon light-blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><path d="m17 5-5-3-5 3"/><path d="m17 19-5 3-5-3"/></svg>
            </div>
        </div>
        <div class="rl-card-value">{{ $ringkasan['ac'] ?? 0 }}</div>
        <div class="rl-card-note">Standard AC Charging</div>
    </div>
    <div class="rl-card amber" data-kategori="kuning" onclick="toggleFilterKartu('kuning')">
        <div class="rl-card-top">
            <div class="rl-card-label">Ada Pasangan (Mitra)</div>
            <div class="rl-card-icon amber">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
        </div>
        <div class="rl-card-value">{{ $ringkasan['kandidat_ada_pasangan'] ?? 0 }}</div>
        <div class="rl-card-note">Kandidat ber-mitra mesin</div>
    </div>
    <div class="rl-card purple" data-kategori="ungu" onclick="toggleFilterKartu('ungu')">
        <div class="rl-card-top">
            <div class="rl-card-label">Belum Ada Pasangan</div>
            <div class="rl-card-icon purple">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
        </div>
        <div class="rl-card-value">{{ $ringkasan['kandidat_belum_pasangan'] ?? 0 }}</div>
        <div class="rl-card-note">Kandidat tanpa mitra mesin</div>
    </div>
    <div class="rl-card blue" data-kategori="_rekomendasi" onclick="toggleFilterKartu('_rekomendasi')">
        <div class="rl-card-top">
            <div class="rl-card-label">Titik Rekomendasi</div>
            <div class="rl-card-icon blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
            </div>
        </div>
        <div class="rl-card-value">{{ $titikRekomendasi->count() }}</div>
        <div class="rl-card-note">Ekspansi otomatis (+)</div>
    </div>
</div>

<div class="rl-peta-wrapper surface-card" style="overflow:hidden;">
    <div class="section-header-bar">
        <div class="section-header-bar-left" style="display:flex; align-items:center; flex-wrap:wrap; gap:12px;">
            <div style="display:flex; align-items:center; gap:10px;">
                <div class="section-header-bar-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0Z"/><circle cx="12" cy="10" r="3"/></svg></div>
                <div><h2>Peta Zona SPKLU</h2></div>
            </div>
            <div class="rl-filter-pills">
                <button type="button" class="rl-pill active" data-pill="" onclick="filterKategoriPeta('')">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
                    Semua Titik
                </button>
                <button type="button" class="rl-pill" data-pill="_semua_spklu" onclick="filterKategoriPeta('_semua_spklu')">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                    Keduanya (Master + Baru)
                </button>
                <button type="button" class="rl-pill" data-pill="_master_spklu" onclick="filterKategoriPeta('_master_spklu')">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/></svg>
                    Master SPKLU
                </button>
                <button type="button" class="rl-pill" data-pill="_semua_kandidat" onclick="filterKategoriPeta('_semua_kandidat')">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/></svg>
                    SPKLU Baru
                </button>
            </div>
        </div>

        <div class="rl-filter-group">
            {{-- Filter kategori zona & jenis titik — client-side, gak reload halaman --}}
            <select id="rl-filter-kategori" class="rl-select" onchange="filterKategoriPeta(this.value)">
                <option value="">Semua Titik (SPKLU &amp; Rekomendasi)</option>
                <optgroup label="── KELOMPOK UTAMA ──">
                    <option value="_semua_spklu">Tampilkan Keduanya (Master SPKLU + SPKLU Baru)</option>
                    <option value="_master_spklu">Hanya SPKLU Terintegrasi (Master SPKLU)</option>
                    <option value="_semua_kandidat">Hanya SPKLU Baru (Pipeline Probing)</option>
                </optgroup>
                <optgroup label="── KATEGORI DETAIL ──">
                    <option value="dc">Biru Tua — SPKLU Existing (DC)</option>
                    <option value="ac">Biru Muda — SPKLU Existing (AC)</option>
                    <option value="kuning">Kuning — Kandidat (Ada Pasangan)</option>
                    <option value="ungu">Ungu — Kandidat (Belum Ada Pasangan)</option>
                    <option value="_rekomendasi">Biru (+) — Titik Rekomendasi</option>
                </optgroup>
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
        <span class="rl-legend-item" data-kategori="_semua_spklu" onclick="toggleFilterLegenda('_semua_spklu')">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#0284C7" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
            <strong style="color:#0284C7;">Keduanya</strong>
        </span>
        <span class="rl-legend-item" data-kategori="_master_spklu" onclick="toggleFilterLegenda('_master_spklu')">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#023E8A" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/></svg>
            <strong style="color:#023E8A;">Master SPKLU</strong>
        </span>
        <span class="rl-legend-item" data-kategori="dc" onclick="toggleFilterLegenda('dc')">
            <span class="rl-legend-dot" style="background:#023E8A;">
                <svg viewBox="0 0 24 24" width="9" height="9" fill="none" stroke="#ffffff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="11" height="18" rx="2"/><path d="M8.5 7.5L7 11h3l-1.5 3.5"/><path d="M14 9h2a2 2 0 0 1 2 2v6.5a1.5 1.5 0 0 0 3 0V11.5"/></svg>
            </span>
            DC
        </span>
        <span class="rl-legend-item" data-kategori="ac" onclick="toggleFilterLegenda('ac')">
            <span class="rl-legend-dot" style="background:#0081AB;">
                <svg viewBox="0 0 24 24" width="9" height="9" fill="none" stroke="#ffffff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="11" height="18" rx="2"/><path d="M8.5 7.5L7 11h3l-1.5 3.5"/><path d="M14 9h2a2 2 0 0 1 2 2v6.5a1.5 1.5 0 0 0 3 0V11.5"/></svg>
            </span>
            AC
        </span>
        <span class="rl-legend-item" data-kategori="_semua_kandidat" onclick="toggleFilterLegenda('_semua_kandidat')">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#7C3AED" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/></svg>
            <strong style="color:#7C3AED;">SPKLU Baru</strong>
        </span>
        <span class="rl-legend-item" data-kategori="kuning" onclick="toggleFilterLegenda('kuning')">
            <span class="rl-legend-dot" style="background:#E8A317;">
                <svg viewBox="0 0 24 24" width="9" height="9" fill="none" stroke="#ffffff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="11" height="18" rx="2"/><path d="M8.5 7.5L7 11h3l-1.5 3.5"/><path d="M14 9h2a2 2 0 0 1 2 2v6.5a1.5 1.5 0 0 0 3 0V11.5"/></svg>
            </span>
            Ada Pasangan
        </span>
        <span class="rl-legend-item" data-kategori="ungu" onclick="toggleFilterLegenda('ungu')">
            <span class="rl-legend-dot" style="background:#7C3AED;">
                <svg viewBox="0 0 24 24" width="9" height="9" fill="none" stroke="#ffffff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="11" height="18" rx="2"/><path d="M8.5 7.5L7 11h3l-1.5 3.5"/><path d="M14 9h2a2 2 0 0 1 2 2v6.5a1.5 1.5 0 0 0 3 0V11.5"/></svg>
            </span>
            Belum Pasangan
        </span>
        <span class="rl-legend-item" data-kategori="_rekomendasi" onclick="toggleFilterLegenda('_rekomendasi')">
            <span class="rl-legend-dot rl-legend-plus" style="background:#2563EB; color:#fff; font-weight:800; font-size:11px; line-height:1;">+</span>
            Titik Rekomendasi
        </span>
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
                <h2>SPKLU Eksisting Perlu Tindak Lanjut (Kewalahan / Zona Merah)</h2>
                <p>Daftar SPKLU aktif yang mengalami kepadatan transaksi &amp; durasi pemakaian tinggi. Ini bukan membuka lokasi baru, melainkan intervensi teknis pada unit yang sudah ada.</p>
            </div>
        </div>
    </div>

    {{-- Penjelasan Kriteria Tindak Lanjut --}}
    <div style="background:#FFFBEB; border-bottom:1px solid #FEF3C7; padding:12px 20px; font-size:12.5px; color:#92400E; display:flex; flex-direction:column; gap:6px;">
        <div style="display:flex; align-items:center; gap:8px;">
            <span class="rl-tindak-badge ganti-mesin">Ganti Mesin</span>
            <span>&rarr; Untuk SPKLU <strong>&lt; 60 kW</strong>: Disarankan upgrade mesin ke Fast/Ultra Fast Charging agar durasi pengisian per kendaraan lebih singkat dan antrean cepat terurai.</span>
        </div>
        <div style="display:flex; align-items:center; gap:8px;">
            <span class="rl-tindak-badge tambah-unit">Tambah Unit</span>
            <span>&rarr; Untuk SPKLU <strong>&ge; 60 kW</strong>: Mesin sudah berdaya tinggi, namun volume pengguna sangat banyak. Solusinya adalah menambah titik/nozzle baru di lokasi yang sama.</span>
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
                        {{ $s['kw_display'] ?? ($s['kapasitas_kw'] ? $s['kapasitas_kw'] : '-') }} kW &middot;
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
    const kandidatBaru = @json($kandidatBaru);

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

    // Nyimpen referensi tiap layer zona (circle + circleMarker) beserta tipenya (dc / ac),
    // supaya bisa di-toggle tampil/sembunyi tanpa reload/refetch data.
    const zonaLayers = [];

    function buatIconSpkluMarker(bgColor, shadowColor) {
        return L.divIcon({
            className: '',
            html: `<div style="width:26px; height:26px; border-radius:50%; background:${bgColor}; border:2px solid #ffffff; box-shadow:0 2px 7px ${shadowColor}; display:flex; align-items:center; justify-content:center; cursor:pointer;">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="#ffffff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="11" height="18" rx="2"/>
                    <path d="M8.5 7.5L7 11h3l-1.5 3.5"/>
                    <path d="M14 9h2a2 2 0 0 1 2 2v6.5a1.5 1.5 0 0 0 3 0V11.5"/>
                    <path d="M21 9.5V8"/>
                </svg>
            </div>`,
            iconSize: [26, 26],
            iconAnchor: [13, 13],
            popupAnchor: [0, -13],
        });
    }

    titikPeta.forEach(t => {
        const isDc = (t.type || '').toUpperCase() === 'DC';
        const warna = isDc ? '#023E8A' : '#0081AB'; // Biru tua DC, Biru muda AC
        const shadow = isDc ? 'rgba(2,62,138,0.55)' : 'rgba(0,129,171,0.55)';
        const badgeBg = isDc ? 'rgba(2,62,138,0.1)' : 'rgba(0,129,171,0.1)';

        const circle = L.circle([t.latitude, t.longitude], {
            radius: t.radius_km * 1000,
            color: warna,
            fillColor: warna,
            fillOpacity: 0.15,
            weight: 1.5,
        }).addTo(peta);

        const marker = L.marker([t.latitude, t.longitude], {
            icon: buatIconSpkluMarker(warna, shadow)
        }).addTo(peta).bindPopup(`
            <div style="min-width:190px;">
                <span style="display:inline-block; font-size:10px; font-weight:800; text-transform:uppercase; color:${warna}; background:${badgeBg}; padding:2px 6px; border-radius:4px; margin-bottom:5px;">
                    SPKLU Existing (${isDc ? 'DC' : 'AC'})
                </span><br>
                <strong style="font-size:13px; color:#0F172A;">${t.nama}</strong><br>
                <span style="font-size:12px; color:#64748B;">ULP: ${t.ulp ?? '-'}</span><br>
                <span style="font-size:12px; color:#64748B;">Tipe: <strong>${isDc ? 'DC (Fast / Ultra Fast)' : 'AC (Standard)'}</strong></span><br>
                <span style="font-size:12px; color:#64748B;">Status Zona: <strong style="color:${warnaStatus[t.status] || '#64748B'};">${labelStatus[t.status] || '-'}</strong></span><br>
                <span style="font-size:12px; color:#64748B;">Rata-rata transaksi: ${t.rata_rata_transaksi_bulan !== null ? t.rata_rata_transaksi_bulan + '/bln' : '-'}</span><br>
                <span style="font-size:12px; color:#64748B;">Tren 3 bulan: ${formatTren(t)}</span><br>
                <span style="font-size:12px; color:#64748B;">Skor gabungan: ${t.skor_gabungan !== null ? t.skor_gabungan : '-'}</span><br>
                <span style="font-size:12px; color:#64748B;">Radius jangkauan: ${t.radius_km} km</span>
            </div>
        `);

        zonaLayers.push({ type: isDc ? 'dc' : 'ac', circle, marker });
        batasSemuaTitik.push([t.latitude, t.longitude]);
    });

    const iconRekomendasi = L.divIcon({
        className: '',
        html: '<div class="rl-marker-rekomendasi-inner">+</div>',
        iconSize: [26, 26],
        iconAnchor: [13, 13],
        popupAnchor: [0, -13],
    });

    // Titik rekomendasi (biru +) kategori khusus "_rekomendasi"
    const rekomendasiLayers = [];

    titikRekomendasi.forEach((t, i) => {
        const marker = L.marker([t.latitude, t.longitude], { icon: iconRekomendasi })
            .addTo(peta)
            .bindPopup(`
                <div style="min-width:180px;">
                    <span style="display:inline-block; font-size:10px; font-weight:800; text-transform:uppercase; color:#2563EB; background:#EFF6FF; padding:2px 6px; border-radius:4px; margin-bottom:4px;">
                        Rekomendasi #${i + 1}
                    </span><br>
                    <strong style="font-size:13px; color:#0F172A;">Titik Ekspansi Baru</strong><br>
                    <span style="font-size:12px; color:#64748B;">Koordinat: ${t.latitude}, ${t.longitude}</span><br>
                    <span style="font-size:12px; color:#64748B;">Skor potensi: <strong>${t.skor_potensi}</strong></span><br>
                    <span style="font-size:12px; color:#64748B;">SPKLU terdekat: ${t.jarak_terdekat_km} km (${t.spklu_terdekat})</span><br>
                    <span style="font-size:12px; color:#64748B;">ULP terdekat: ${t.ulp_terdekat ?? '-'}</span><br>
                    <div style="margin-top:8px; padding-top:6px; border-top:1px solid #f1f5f9;">
                        <a href="/monitoring/kandidat/create?tikor=${t.latitude},${t.longitude}" style="color:#2563EB; font-weight:700; font-size:12px;">Jadikan Kandidat Baru →</a>
                    </div>
                </div>
            `);

        rekomendasiLayers.push(marker);
        batasSemuaTitik.push([t.latitude, t.longitude]);
    });

    // SPKLU Baru / Kandidat Pipeline:
    // Kuning: yang udah ada pasangan (mitra_mesin)
    // Ungu: yang belum ada pasangan
    const kandidatLayers = [];

    kandidatBaru.forEach(k => {
        const punyaPasangan = Boolean(k.mitra_mesin && k.mitra_mesin.trim() !== '');
        const warnaKandidat = punyaPasangan ? '#E8A317' : '#7C3AED'; // Kuning ada pasangan, Ungu belum ada pasangan
        const shadowKandidat = punyaPasangan ? 'rgba(232,163,23,0.55)' : 'rgba(124,58,237,0.55)';
        const badgeBg = punyaPasangan ? 'rgba(232,163,23,0.12)' : 'rgba(124,58,237,0.1)';

        const marker = L.marker([k.latitude, k.longitude], {
            icon: buatIconSpkluMarker(warnaKandidat, shadowKandidat)
        })
            .addTo(peta)
            .bindPopup(`
                <div style="min-width:195px;">
                    <span style="display:inline-block; font-size:10px; font-weight:800; text-transform:uppercase; color:${warnaKandidat}; background:${badgeBg}; padding:2px 6px; border-radius:4px; margin-bottom:4px;">
                        ${punyaPasangan ? 'Kandidat — Ada Pasangan' : 'Kandidat — Belum Ada Pasangan'}
                    </span><br>
                    <strong style="font-size:13px; color:#0F172A;">${k.nama}</strong><br>
                    <span style="font-size:12px; color:#64748B;">ULP: ${k.ulp ?? '-'}</span><br>
                    <span style="font-size:12px; color:#64748B;">Tahap: <strong>${k.tahap}</strong></span><br>
                    <span style="font-size:12px; color:#64748B;">Mitra Mesin: <strong>${k.mitra_mesin ? k.mitra_mesin : '<span style="color:#7C3AED; font-style:italic;">Belum Ada</span>'}</strong></span><br>
                    <span style="font-size:12px; color:#64748B;">Status: <strong style="text-transform:capitalize;">${(k.status_kanban || '').replace('_', ' ')}</strong></span><br>
                    <div style="margin-top:8px; padding-top:6px; border-top:1px solid #f1f5f9;">
                        <a href="/monitoring/probabilitas" style="color:${warnaKandidat}; font-weight:700; font-size:11.5px;">Lihat di Pipeline Probing &rarr;</a>
                    </div>
                </div>
            `);

        kandidatLayers.push({ kategori: punyaPasangan ? 'kuning' : 'ungu', marker });
        batasSemuaTitik.push([k.latitude, k.longitude]);
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
        // Toggle SPKLU existing (Master SPKLU: dc / ac)
        zonaLayers.forEach(({ type, circle, marker }) => {
            let tampil = false;
            if (!kategoriAktif || kategoriAktif === '_semua_spklu' || kategoriAktif === '_master_spklu') {
                tampil = true;
            } else if (kategoriAktif === type) {
                tampil = true;
            }

            if (tampil) {
                if (!peta.hasLayer(circle)) circle.addTo(peta);
                if (!peta.hasLayer(marker)) marker.addTo(peta);
            } else {
                if (peta.hasLayer(circle)) peta.removeLayer(circle);
                if (peta.hasLayer(marker)) peta.removeLayer(marker);
            }
        });

        // Toggle SPKLU Baru / kandidat pipeline (kuning / ungu)
        kandidatLayers.forEach(({ kategori, marker }) => {
            let tampil = false;
            if (!kategoriAktif || kategoriAktif === '_semua_spklu' || kategoriAktif === '_semua_kandidat') {
                tampil = true;
            } else if (kategoriAktif === kategori) {
                tampil = true;
            }

            if (tampil) {
                if (!peta.hasLayer(marker)) marker.addTo(peta);
            } else {
                if (peta.hasLayer(marker)) peta.removeLayer(marker);
            }
        });

        // Toggle titik rekomendasi (kategori khusus "_rekomendasi")
        // Catatan: Jika memilih grup SPKLU (_semua_spklu, _master_spklu, _semua_kandidat),
        // titik rekomendasi di-hide agar peta fokus menampilkan unit SPKLU
        const tampilRekomendasi = (!kategoriAktif && kategoriAktif !== '_semua_spklu' && kategoriAktif !== '_master_spklu' && kategoriAktif !== '_semua_kandidat') || kategoriAktif === '_rekomendasi';
        rekomendasiLayers.forEach(marker => {
            if (tampilRekomendasi) {
                if (!peta.hasLayer(marker)) marker.addTo(peta);
            } else {
                if (peta.hasLayer(marker)) peta.removeLayer(marker);
            }
        });

        // Sinkronisasi tampilan dropdown
        document.getElementById('rl-filter-kategori').value = kategoriAktif;

        // Sinkronisasi highlight pills
        document.querySelectorAll('.rl-pill[data-pill]').forEach(pill => {
            pill.classList.toggle('active', pill.dataset.pill === kategoriAktif);
        });

        // Sinkronisasi highlight kartu ringkasan
        document.querySelectorAll('.rl-card[data-kategori]').forEach(card => {
            let isActive = false;
            if (kategoriAktif === card.dataset.kategori) {
                isActive = true;
            } else if (kategoriAktif === '_master_spklu' && (card.dataset.kategori === 'dc' || card.dataset.kategori === 'ac')) {
                isActive = true;
            } else if (kategoriAktif === '_semua_kandidat' && (card.dataset.kategori === 'kuning' || card.dataset.kategori === 'ungu')) {
                isActive = true;
            } else if (kategoriAktif === '_semua_spklu' && card.dataset.kategori !== '_rekomendasi') {
                isActive = true;
            }
            card.classList.toggle('rl-card-active', isActive);
        });

        // Sinkronisasi highlight legenda
        document.querySelectorAll('.rl-legend-item[data-kategori]').forEach(item => {
            let isOff = false;
            if (!kategoriAktif) {
                isOff = false;
            } else if (item.dataset.kategori === kategoriAktif) {
                isOff = false;
            } else if (kategoriAktif === '_master_spklu' && (item.dataset.kategori === 'dc' || item.dataset.kategori === 'ac' || item.dataset.kategori === '_master_spklu')) {
                isOff = false;
            } else if (kategoriAktif === '_semua_kandidat' && (item.dataset.kategori === 'kuning' || item.dataset.kategori === 'ungu' || item.dataset.kategori === '_semua_kandidat')) {
                isOff = false;
            } else if (kategoriAktif === '_semua_spklu' && item.dataset.kategori !== '_rekomendasi') {
                isOff = false;
            } else {
                isOff = true;
            }
            item.classList.toggle('rl-legend-item-off', isOff);
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