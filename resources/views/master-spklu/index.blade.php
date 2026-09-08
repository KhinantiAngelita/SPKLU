@extends('layouts.app')

@section('breadcrumb', 'Master SPKLU')
@section('page-title', 'Master SPKLU')

@section('content')

<style>
    .msp-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:26px; gap:16px; flex-wrap:wrap; }
    .msp-subtitle { color:#64748B; margin:0; font-size:14px; }
    .msp-actions { display:flex; gap:10px; }

    .msp-btn { border:none; border-radius:9px; font-size:13.5px; font-weight:600; padding:10px 18px; cursor:pointer; transition:all .15s ease; display:inline-flex; align-items:center; gap:7px; }
    .msp-btn svg { width:15px; height:15px; stroke-width:2; flex-shrink:0; }
    .msp-btn-primary { background:linear-gradient(135deg,#023E8A,#0081AB); color:#fff; box-shadow:0 2px 10px rgba(2,62,138,.25); }
    .msp-btn-primary:hover { transform:translateY(-1px); box-shadow:0 4px 14px rgba(2,62,138,.32); }
    .msp-btn-outline { background:#fff; color:#1E293B; border:1px solid #e2e8f0; }
    .msp-btn-outline:hover { background:#f8fafc; border-color:#cbd5e1; }
    .msp-btn-warning { background:#FFC629; color:#023E8A; font-weight:700; box-shadow:0 2px 8px rgba(255,198,41,.4); }

    .msp-card-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:18px; margin-bottom:24px; }
    .msp-card {
        background:#fff; border-radius:16px; padding:22px; border:1px solid #eef1f5;
        box-shadow:0 1px 2px rgba(15,23,42,.04), 0 6px 16px rgba(15,23,42,.05);
        display:flex; justify-content:space-between; align-items:center; min-height:96px;
        transition:transform .18s ease, box-shadow .18s ease, border-color .18s ease;
    }
    .msp-card:hover { transform:translateY(-2px); box-shadow:0 4px 10px rgba(15,23,42,.06), 0 12px 28px rgba(15,23,42,.08); border-color:rgba(0,129,171,.22); }
    .msp-card-label { font-size:12.5px; font-weight:600; text-transform:uppercase; letter-spacing:.03em; color:#64748B; margin:0 0 8px; }
    .msp-card-value { font-size:26px; font-weight:700; letter-spacing:-.01em; color:#101828; margin:0; line-height:1.2; }
    .msp-card-note { font-size:11px; color:#94a3b8; margin:6px 0 0; }

    /* Dual-stat: dua angka dalam satu card, masing-masing baris sendiri + dot warna, biar tinggi card konsisten */
    .msp-dual-stat { display:flex; flex-direction:column; gap:4px; }
    .msp-dual-stat-row { display:flex; align-items:center; gap:7px; font-size:16.5px; font-weight:700; color:#101828; }
    .msp-dot { width:8px; height:8px; border-radius:999px; flex-shrink:0; }
    .msp-dot-blue { background:#023E8A; }
    .msp-dot-slate { background:#94a3b8; }
    .msp-dot-amber { background:#E8A317; }

    /* Icon boxes — overlay rgba, konsisten dengan halaman lain di sistem */
    .msp-card-icon { width:46px; height:46px; min-width:46px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .msp-card-icon svg { width:21px; height:21px; stroke-width:1.8; }
    .msp-ic-blue  { background:linear-gradient(135deg, rgba(2,62,138,.12), rgba(0,129,171,.12)); color:#023E8A; }
    .msp-ic-green { background:linear-gradient(135deg, rgba(46,158,91,.14), rgba(46,158,91,.06)); color:#2E9E5B; }
    .msp-ic-amber { background:linear-gradient(135deg, rgba(232,163,23,.15), rgba(232,163,23,.06)); color:#E8A317; }
    .msp-ic-red   { background:linear-gradient(135deg, rgba(192,57,43,.14), rgba(192,57,43,.06)); color:#C0392B; }

    .msp-banner { background:linear-gradient(135deg, rgba(255,198,41,.12), rgba(232,163,23,.08)); border:1px solid rgba(232,163,23,.35); border-radius:12px; padding:14px 20px; display:flex; align-items:center; justify-content:space-between; margin-bottom:22px; color:#92660f; font-size:13.5px; font-weight:500; gap:12px; flex-wrap:wrap; }
    .msp-banner-text { display:flex; align-items:center; gap:8px; }
    .msp-banner-text svg { width:16px; height:16px; stroke-width:2; flex-shrink:0; }

    .msp-table-box { background:#fff; border-radius:18px; border:1px solid #eef1f5; box-shadow:0 1px 2px rgba(15,23,42,.04), 0 6px 20px rgba(15,23,42,.05); overflow:hidden; }
    .msp-table-head { padding:22px 24px 0; display:flex; align-items:center; gap:10px; }

    .msp-table-head-icon { width:30px; height:30px; min-width:30px; border-radius:9px; background:rgba(0,129,171,.12); color:#023E8A; display:flex; align-items:center; justify-content:center; }
    .msp-table-head-icon svg { width:16px; height:16px; stroke-width:1.9; }
    .msp-table-head h2 { margin:0; font-size:16.5px; font-weight:700; color:#0f172a; }

    .msp-filters { display:flex; gap:10px; flex-wrap:wrap; align-items:center; padding:16px 24px 20px; }
    .msp-field { position:relative; }
    .msp-field svg { position:absolute; left:12px; top:50%; transform:translateY(-50%); width:15px; height:15px; stroke-width:2; color:#94a3b8; pointer-events:none; }
    .msp-input, .msp-select {
        padding:10px 14px 10px 34px; border-radius:9px; border:1px solid #e2e8f0; font-size:13.5px;
        background:#fff; color:#1E293B; font-family:inherit;
    }
    .msp-input { min-width:200px; }
    .msp-select { cursor:pointer; min-width:150px; appearance:none;
        background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6' fill='none'%3E%3Cpath d='M1 1L5 5L9 1' stroke='%2394a3b8' stroke-width='1.6' stroke-linecap='round'/%3E%3C/svg%3E");
        background-repeat:no-repeat; background-position:right 12px center; padding-right:30px;
    }
    .msp-input:focus, .msp-select:focus { outline:none; border-color:#0081AB; box-shadow:0 0 0 3px rgba(0,129,171,.12); }
    .msp-reset { display:inline-flex; align-items:center; gap:5px; font-size:12.8px; font-weight:600; color:#0081AB; background:rgba(0,129,171,.08); border:none; border-radius:8px; padding:9px 12px; cursor:pointer; transition:background .15s ease; }
    .msp-reset:hover { background:rgba(0,129,171,.14); }
    .msp-reset svg { width:13px; height:13px; stroke-width:2.3; }

    .msp-table-wrap { overflow-x:auto; -webkit-overflow-scrolling:touch; }
    .msp-table { width:100%; border-collapse:collapse; min-width:1100px; }
    .msp-table thead th {
        background:#fafbfc; text-align:left; font-size:10.5px; font-weight:700; text-transform:uppercase;
        letter-spacing:.06em; color:#94a3b8; padding:13px 24px; border-top:1px solid #eef1f5; border-bottom:1px solid #eef1f5; white-space:nowrap;
    }
    .msp-table tbody td { padding:15px 24px; font-size:13.5px; color:#1E293B; border-bottom:1px solid #f5f7fa; }
    .msp-table tbody tr:nth-child(even) { background:#fbfcfd; }
    .msp-table tbody tr:hover { background:rgba(0,129,171,.05); }
    .msp-table tbody tr:last-child td { border-bottom:none; }

    .msp-kw-cell { display:flex; align-items:center; gap:6px; flex-wrap:wrap; }

    .msp-pill { display:inline-flex; align-items:center; padding:4px 11px; border-radius:999px; font-size:11px; font-weight:700; letter-spacing:.02em; white-space:nowrap; }
    .msp-pill-ac { background:#eef2f7; color:#334155; }
    .msp-pill-dc { background:rgba(0,129,171,.14); color:#023E8A; }
    .msp-pill-pln { background:rgba(2,62,138,.12); color:#023E8A; }
    .msp-pill-swasta { background:rgba(232,163,23,.15); color:#92660f; }
    /* .msp-pill-custom { background:rgba(232,163,23,.15); color:#92660f; } */

    .msp-del-btn { width:32px; height:32px; border-radius:8px; border:none; background:rgba(0,129,171,.1); color:#023E8A; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; transition:background .15s ease; }
    .msp-del-btn svg { width:15px; height:15px; stroke-width:2; }
    .msp-del-btn:hover { background:rgba(0,129,171,.18); }

    .msp-empty { text-align:center; padding:60px 20px; color:#94a3b8; }
    .msp-empty svg { width:36px; height:36px; stroke-width:1.7; color:#cbd5e1; margin-bottom:10px; fill:none; }
    .msp-empty p { margin:0; font-size:13.5px; }

    .msp-pagination { display:flex; align-items:center; justify-content:space-between; padding:16px 24px; border-top:1px solid #f1f5f9; flex-wrap:wrap; gap:12px; }
    .msp-pagination-info { font-size:12.5px; color:#94a3b8; margin:0; }
    .msp-pagination-links { display:flex; align-items:center; gap:4px; }
    .msp-pagination-links a, .msp-pagination-links span {
        display:inline-flex; align-items:center; justify-content:center; min-width:32px; height:32px;
        border-radius:8px; font-size:13px; color:#64748B; transition:background .15s ease;
    }
    .msp-pagination-links a:hover { background:#f1f5f9; color:#1E293B; }
    .msp-pagination-links .active { background:linear-gradient(135deg,#023E8A,#0081AB); color:#fff; font-weight:700; }
    .msp-pagination-links .disabled { color:#cbd5e1; }

    .msp-modal-overlay { display:none; position:fixed; inset:0; background:rgba(15,23,42,.45); align-items:center; justify-content:center; z-index:50; }
    .msp-modal { background:#fff; border-radius:16px; padding:0; width:440px; max-width:92vw; box-shadow:0 20px 50px rgba(0,0,0,.2); max-height:90vh; overflow:hidden; display:flex; flex-direction:column; }

    .msp-modal-header-bar { background:linear-gradient(135deg, rgba(2,62,138,.06), rgba(0,129,171,.09)); padding:20px 26px; display:flex; align-items:center; gap:12px; flex-shrink:0; }
    .msp-modal-header-icon { width:38px; height:38px; min-width:38px; border-radius:10px; background:rgba(2,62,138,.12); color:#023E8A; display:flex; align-items:center; justify-content:center; }
    .msp-modal-header-icon svg { width:18px; height:18px; stroke-width:2; }
    .msp-modal-header-bar h3 { margin:0 0 2px; font-size:16.5px; font-weight:800; color:#023E8A; }
    .msp-modal-header-bar p { margin:0; font-size:12px; color:#64748B; }

    .msp-modal-body { padding:22px 26px; overflow-y:auto; }
    .msp-modal-body label { display:block; font-size:12.5px; font-weight:600; color:#475569; margin:14px 0 5px; }
    .msp-modal-body label:first-child { margin-top:0; }
    .msp-modal-body input, .msp-modal-body select { width:100%; padding:10px 12px; border-radius:8px; border:1px solid #e2e8f0; font-size:13.5px; font-family:inherit; }
    .msp-modal-body input:focus, .msp-modal-body select:focus { outline:none; border-color:#0081AB; box-shadow:0 0 0 3px rgba(0,129,171,.12); }

    .msp-import-info { background:#f8fafc; border:1px solid #eef1f5; border-radius:10px; padding:12px 14px; font-size:12.3px; color:#64748B; line-height:1.6; margin-bottom:16px; }
    .msp-import-info code { background:#eef2f7; color:#023E8A; padding:1px 5px; border-radius:5px; font-size:11.5px; }

    .msp-dropzone { border:2px dashed #cbd5e1; border-radius:12px; padding:32px; text-align:center; color:#64748B; cursor:pointer; font-size:13.5px; transition:all .15s ease; display:flex; flex-direction:column; align-items:center; gap:8px; }
    .msp-dropzone:hover { border-color:#0081AB; background:rgba(0,129,171,.03); }
    .msp-dropzone svg { width:26px; height:26px; stroke-width:1.6; color:#94a3b8; }

    .alert-error { background:rgba(192,57,43,.08); border:1px solid rgba(192,57,43,.25); color:#C0392B; border-radius:10px; padding:12px 16px; font-size:13.5px; margin-bottom:18px; }
    .alert-success { background:rgba(46,158,91,.08); border:1px solid rgba(46,158,91,.25); color:#2E9E5B; border-radius:10px; padding:12px 16px; font-size:13.5px; margin-bottom:18px; }
</style>

<div class="msp-header">
    <p class="msp-subtitle">Daftar SPKLU yang sudah aktif di sistem</p>
    @if (in_array(auth()->user()->role, ['super_admin', 'pengelola']))
        <div class="msp-actions">
            <button class="msp-btn msp-btn-outline" data-open-modal="modal-import-spklu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                Import Excel
            </button>
            <button class="msp-btn msp-btn-primary" data-open-modal="modal-tambah-spklu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah SPKLU
            </button>
        </div>
    @endif
</div>

<div class="msp-card-grid">
    <div class="msp-card">
        <div>
            <p class="msp-card-label">Total Unit SPKLU</p>
            <p class="msp-card-value">{{ $totalUnit }}</p>
        </div>
        <div class="msp-card-icon msp-ic-blue">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
        </div>
    </div>

    <div class="msp-card">
        <div>
            <p class="msp-card-label">Berdasarkan Type</p>
            <div class="msp-dual-stat">
                <div class="msp-dual-stat-row"><span class="msp-dot msp-dot-blue"></span>{{ $totalByType['DC'] ?? 0 }} DC</div>
                <div class="msp-dual-stat-row"><span class="msp-dot msp-dot-slate"></span>{{ $totalByType['AC'] ?? 0 }} AC</div>
            </div>
        </div>
        <div class="msp-card-icon msp-ic-green">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22v-5"/><path d="M9 8V2"/><path d="M15 8V2"/><path d="M18 8v5a6 6 0 0 1-6 6 6 6 0 0 1-6-6V8Z"/></svg>
        </div>
    </div>

    <div class="msp-card">
        <div>
            <p class="msp-card-label">Berdasarkan Kepemilikan</p>
            <div class="msp-dual-stat">
                <div class="msp-dual-stat-row"><span class="msp-dot msp-dot-blue"></span>{{ $totalByKepemilikan['PLN'] ?? 0 }} PLN</div>
                <div class="msp-dual-stat-row"><span class="msp-dot msp-dot-amber"></span>{{ $totalByKepemilikan['Swasta'] ?? 0 }} Swasta</div>
            </div>
        </div>
        <div class="msp-card-icon msp-ic-amber">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="22" x2="21" y2="22"/><line x1="6" y1="18" x2="6" y2="11"/><line x1="10" y1="18" x2="10" y2="11"/><line x1="14" y1="18" x2="14" y2="11"/><line x1="18" y1="18" x2="18" y2="11"/><polygon points="12 2 20 7 4 7"/></svg>
        </div>
    </div>

    <div class="msp-card">
        <div>
            <p class="msp-card-label">Total Kapasitas</p>
            <p class="msp-card-value">{{ number_format($totalKapasitas, 0) }} kW</p>
            <p class="msp-card-note">*tidak termasuk unit custom</p>
        </div>
        <div class="msp-card-icon msp-ic-red">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m12 14 4-4"/><path d="M3.34 19a10 10 0 1 1 17.32 0"/></svg>
        </div>
    </div>
</div>

@if ($menungguValidasiCount > 0 && auth()->user()->role === 'super_admin')
<div class="msp-banner">
    <span class="msp-banner-text">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        Ada {{ $menungguValidasiCount }} data baru yang ditambahkan ke Master SPKLU dan menunggu validasi
    </span>
    <a href="{{ route('master-spklu.validasi') }}" class="msp-btn msp-btn-warning">Validasi</a>
</div>
@endif

<div class="msp-table-box">

    <div class="section-header-bar">
        <div class="section-header-bar-left">
            <div class="section-header-bar-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
            </div>
            <div>
                <h2>Daftar SPKLU</h2>
                <p>{{ $spklus->total() }} lokasi terdaftar di sistem</p>
            </div>
        </div>
    </div>

    <form method="GET" class="msp-filters">
        <div class="msp-field">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama SPKLU..." class="msp-input">
        </div>

        <div class="msp-field">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 12-9 12s-9-5-9-12a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            <select name="ulp_id" class="msp-select" onchange="this.form.submit()">
                <option value="">Semua ULP</option>
                @foreach ($ulpList as $ulp)
                    <option value="{{ $ulp->id }}" {{ request('ulp_id') == $ulp->id ? 'selected' : '' }}>{{ $ulp->nama_penuh }}</option>
                @endforeach
            </select>
        </div>

        <div class="msp-field">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
            <select name="type" class="msp-select" onchange="this.form.submit()">
                <option value="">Semua Type</option>
                <option value="AC" {{ request('type') === 'AC' ? 'selected' : '' }}>AC</option>
                <option value="DC" {{ request('type') === 'DC' ? 'selected' : '' }}>DC</option>
            </select>
        </div>

        <div class="msp-field">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="3" width="16" height="18" rx="1"/><line x1="9" y1="8" x2="9.01" y2="8"/><line x1="15" y1="8" x2="15.01" y2="8"/><line x1="9" y1="12" x2="9.01" y2="12"/><line x1="15" y1="12" x2="15.01" y2="12"/><line x1="9" y1="16" x2="15" y2="16"/></svg>
            <select name="kepemilikan" class="msp-select" onchange="this.form.submit()">
                <option value="">Semua Kepemilikan</option>
                <option value="PLN" {{ request('kepemilikan') === 'PLN' ? 'selected' : '' }}>PLN</option>
                <option value="Swasta" {{ request('kepemilikan') === 'Swasta' ? 'selected' : '' }}>Swasta</option>
            </select>
        </div>

        @if (request()->anyFilled(['search', 'ulp_id', 'type', 'kepemilikan']))
            <button type="button" class="msp-reset" onclick="window.location.href='{{ route('master-spklu.index') }}'">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                Reset filter
            </button>
        @endif
    </form>

    <div class="msp-table-wrap">
        <table class="msp-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Id SPKLU</th>
                    <th>Kode Unit</th>
                    <th>Nama SPKLU</th>
                    <th>ULP</th>
                    <th>Type</th>
                    <th>KW</th>
                    <th>Nozzle</th>
                    <th>Kepemilikan</th>
                    <th>Skema</th>
                    <th>Koordinat</th>
                    @if (in_array(auth()->user()->role, ['super_admin', 'pengelola']))<th>Aksi</th>@endif
                </tr>
            </thead>
            <tbody>
                @forelse ($spklus as $i => $spklu)
                    <tr>
                        <td>{{ $spklus->firstItem() + $i }}</td>
                        <td>{{ $spklu->id_spklu }}</td>
                        <td>{{ $spklu->kode_unit ?? '—' }}</td>
                        <td style="font-weight:600; white-space:nowrap;">{{ $spklu->nama }}</td>
                        <td>{{ $spklu->ulp->nama_penuh ?? '—' }}</td>
                        <td>
                            <span class="msp-pill {{ $spklu->type === 'DC' ? 'msp-pill-dc' : 'msp-pill-ac' }}">{{ $spklu->type }}</span>
                        </td>
                        <td>
                            <div class="msp-kw-cell">
                                <span>{{ $spklu->kw_detail ?? $spklu->kw }}</span>
                                <!-- @if (is_null($spklu->kw))
                                    <span class="msp-pill msp-pill-custom">Custom</span>
                                @endif -->
                            </div>
                        </td>
                        <td>{{ $spklu->nozzle }}</td>
                        <td>
                            <span class="msp-pill {{ $spklu->kepemilikan === 'PLN' ? 'msp-pill-pln' : 'msp-pill-swasta' }}">{{ $spklu->kepemilikan }}</span>
                        </td>
                        <td>{{ $spklu->skema ?? '—' }}</td>
                        <td style="font-size:12px; color:#94a3b8; white-space:nowrap;">
                            @if ($spklu->latitude && $spklu->longitude)
                                {{ number_format($spklu->latitude, 5) }}, {{ number_format($spklu->longitude, 5) }}
                            @else
                                —
                            @endif
                        </td>
                        @if (in_array(auth()->user()->role, ['super_admin', 'pengelola']))
                            <td>
                                <button type="button" class="msp-del-btn" title="Edit" onclick='bukaModalEdit(@json($spklu))'>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                </button>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="11">
                            <div class="msp-empty">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                                <p>Belum ada data SPKLU.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($spklus->hasPages())
    <div class="msp-pagination">
        <p class="msp-pagination-info">Menampilkan {{ $spklus->firstItem() }}–{{ $spklus->lastItem() }} dari {{ $spklus->total() }} data</p>
        <div class="msp-pagination-links">
            @if ($spklus->onFirstPage())
                <span class="disabled">‹</span>
            @else
                <a href="{{ $spklus->previousPageUrl() }}">‹</a>
            @endif

            @for ($page = 1; $page <= $spklus->lastPage(); $page++)
                @if ($page == $spklus->currentPage())
                    <span class="active">{{ $page }}</span>
                @else
                    <a href="{{ $spklus->url($page) }}">{{ $page }}</a>
                @endif
            @endfor

            @if ($spklus->hasMorePages())
                <a href="{{ $spklus->nextPageUrl() }}">›</a>
            @else
                <span class="disabled">›</span>
            @endif
        </div>
    </div>
    @endif
</div>

{{-- Modal Tambah SPKLU --}}
<div id="modal-tambah-spklu" class="msp-modal-overlay">
    <div class="msp-modal">
        <div class="msp-modal-header-bar">
            <div class="msp-modal-header-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            </div>
            <div>
                <h3>Tambah SPKLU</h3>
                <p>Isi data lokasi SPKLU baru</p>
            </div>
        </div>
        <div class="msp-modal-body">
            <form method="POST" action="{{ route('master-spklu.store') }}">
                @csrf
                <label>Nama SPKLU</label>
                <input type="text" name="nama" required>

                <label>ULP</label>
                <select name="ulp_mapping_id" required>
                    @foreach ($ulpList as $ulp)
                        <option value="{{ $ulp->id }}">{{ $ulp->nama_penuh }}</option>
                    @endforeach
                </select>

                <label>Type</label>
                <select name="type" required>
                    <option value="AC">AC</option>
                    <option value="DC">DC</option>
                </select>

                <label>Kapasitas (kW)</label>
                <input type="number" step="0.01" name="kw" required>

                <label>Nozzle</label>
                <input type="number" name="nozzle" value="1" required>

                <label>Kepemilikan</label>
                <select name="kepemilikan" required>
                    <option value="PLN">PLN</option>
                    <option value="Swasta">Swasta</option>
                </select>

                <label>Koordinat Latitude (opsional)</label>
                <input type="text" name="latitude" placeholder="-6.591828">

                <label>Koordinat Longitude (opsional)</label>
                <input type="text" name="longitude" placeholder="106.794393">

                <div style="display:flex; justify-content:flex-end; gap:8px; margin-top:22px;">
                    <button type="button" class="msp-btn msp-btn-outline" data-close-modal>Batal</button>
                    <button type="submit" class="msp-btn msp-btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Import Excel --}}
<div id="modal-import-spklu" class="msp-modal-overlay">
    <div class="msp-modal">
        <div class="msp-modal-header-bar">
            <div class="msp-modal-header-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            </div>
            <div>
                <h3>Import Data dari Excel</h3>
                <p>Upload file untuk tambah/update data massal</p>
            </div>
        </div>
        <div class="msp-modal-body">
            <div class="msp-import-info">
                File harus punya kolom header: <code>ID SPKLU, KD UNIT, ULP, Nama SPKLU, Type, KW, NOZZLE, Kepemilikan, SKEMA, PKS, TIKOR, Latitude, Longitude</code>.
                Nama ULP tidak case-sensitive, tapi ejaannya harus sama dengan yang ada di sistem.
                Kolom KW boleh diisi angka bersih (22, 120) atau format custom multi-mesin (2x120, 80, 120 & 200) — yang custom otomatis ditandai dan tidak ikut dijumlah ke total kapasitas.
            </div>

            <form method="POST" action="{{ route('master-spklu.import') }}" enctype="multipart/form-data">
                @csrf
                <label for="file-input-spklu" class="msp-dropzone" style="display:flex;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    <span id="file-name-label">Klik untuk pilih file, atau drag & drop di sini</span>
                    <input type="file" id="file-input-spklu" name="file" accept=".xlsx,.xls,.csv" required
                           style="display:none;"
                           onchange="document.getElementById('file-name-label').textContent = this.files[0]?.name ?? 'Klik untuk pilih file'">
                </label>

                <div style="display:flex; justify-content:flex-end; gap:8px; margin-top:22px;">
                    <button type="button" class="msp-btn msp-btn-outline" data-close-modal>Batal</button>
                    <button type="submit" class="msp-btn msp-btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit SPKLU --}}
<div id="modal-edit-spklu" class="msp-modal-overlay">
    <div class="msp-modal">
        <div class="msp-modal-header-bar">
            <div class="msp-modal-header-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
            </div>
            <div>
                <h3>Edit SPKLU</h3>
                <p>Perbarui data lokasi SPKLU</p>
            </div>
        </div>
        <div class="msp-modal-body">
            <form method="POST" id="form-edit-spklu">
                @csrf
                @method('PUT')

                <label>Nama SPKLU</label>
                <input type="text" name="nama" id="edit-nama" required>

                <label>ULP</label>
                <select name="ulp_mapping_id" id="edit-ulp" required>
                    @foreach ($ulpList as $ulp)
                        <option value="{{ $ulp->id }}">{{ $ulp->nama_penuh }}</option>
                    @endforeach
                </select>

                <label>Type</label>
                <select name="type" id="edit-type" required>
                    <option value="AC">AC</option>
                    <option value="DC">DC</option>
                </select>

                <label>Kapasitas (kW) — kosongkan kalau custom</label>
                <input type="number" step="0.01" name="kw" id="edit-kw">

                <label>Kapasitas (teks, misal "2x120")</label>
                <input type="text" name="kw_detail" id="edit-kw-detail">

                <label>Nozzle</label>
                <input type="number" name="nozzle" id="edit-nozzle" required>

                <label>Kepemilikan</label>
                <select name="kepemilikan" id="edit-kepemilikan" required>
                    <option value="PLN">PLN</option>
                    <option value="Swasta">Swasta</option>
                </select>

                <label>Skema</label>
                <input type="number" name="skema" id="edit-skema">

                <label>Latitude</label>
                <input type="text" name="latitude" id="edit-latitude">

                <label>Longitude</label>
                <input type="text" name="longitude" id="edit-longitude">

                <div style="display:flex; justify-content:flex-end; gap:8px; margin-top:22px;">
                    <button type="button" class="msp-btn msp-btn-outline" data-close-modal>Batal</button>
                    <button type="submit" class="msp-btn msp-btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function bukaModalEdit(spklu) {
    document.getElementById('form-edit-spklu').action = '/master-spklu/' + spklu.id;
    document.getElementById('edit-nama').value = spklu.nama ?? '';
    document.getElementById('edit-ulp').value = spklu.ulp_mapping_id ?? '';
    document.getElementById('edit-type').value = spklu.type ?? '';
    document.getElementById('edit-kw').value = spklu.kw ?? '';
    document.getElementById('edit-kw-detail').value = spklu.kw_detail ?? '';
    document.getElementById('edit-nozzle').value = spklu.nozzle ?? 1;
    document.getElementById('edit-kepemilikan').value = spklu.kepemilikan ?? '';
    document.getElementById('edit-skema').value = spklu.skema ?? '';
    document.getElementById('edit-latitude').value = spklu.latitude ?? '';
    document.getElementById('edit-longitude').value = spklu.longitude ?? '';
    document.getElementById('modal-edit-spklu').style.display = 'flex';
}
</script>

@endsection