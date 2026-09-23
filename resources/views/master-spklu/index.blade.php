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
    .msp-btn-danger { background:#C0392B; color:#fff; font-weight:700; box-shadow:0 2px 8px rgba(192,57,43,.35); }
    .msp-btn-danger:hover { transform:translateY(-1px); background:#a8302a; }

    .msp-card-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:24px; }
    .msp-card {
        background:#fff; border-radius:16px; padding:20px; border:1px solid #e2e8f0;
        box-shadow:0 2px 6px rgba(15,23,42,.03), 0 10px 15px -3px rgba(15,23,42,.02);
        position:relative; overflow:hidden; display:flex; flex-direction:column; justify-content:space-between;
        transition:transform .2s cubic-bezier(0.4, 0, 0.2, 1), box-shadow .2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .msp-card:hover { transform:translateY(-3px); box-shadow:0 12px 24px -4px rgba(15,23,42,.08); }
    .msp-card::before { content:""; position:absolute; top:0; left:0; right:0; height:3px; }
    .msp-card.blue::before  { background:linear-gradient(90deg, #023E8A, #0081AB); }
    .msp-card.green::before { background:linear-gradient(90deg, #059669, #10B981); }
    .msp-card.amber::before { background:linear-gradient(90deg, #D97706, #F59E0B); }
    .msp-card.rose::before  { background:linear-gradient(90deg, #7C3AED, #8B5CF6); }
    .msp-card-top { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:10px; }
    .msp-card-icon { width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .msp-card-icon svg { width:20px; height:20px; stroke-width:2; }
    .msp-card-icon.blue   { background:rgba(0,129,171,.1); color:#0081AB; }
    .msp-card-icon.green  { background:rgba(5,150,105,.1); color:#059669; }
    .msp-card-icon.amber  { background:rgba(217,119,6,.1); color:#D97706; }
    .msp-card-icon.purple { background:rgba(124,58,237,.1); color:#7C3AED; }
    .msp-card-label { font-size:11.5px; font-weight:700; text-transform:uppercase; letter-spacing:.04em; color:#64748B; margin:0 0 6px; }
    .msp-card-value { font-size:28px; font-weight:800; color:#1B2559 !important; margin:0 0 4px; line-height:1; }
    .msp-card-note { font-size:12px; color:#64748B; line-height:1.4; margin:0; }

    .msp-split-row { display:flex; gap:18px; margin:4px 0 4px; }
    .msp-value-split { font-size:22px; font-weight:800; color:#1B2559 !important; display:flex; flex-direction:column; line-height:1.1; }
    .msp-value-split small { font-size:11px; font-weight:600; color:#64748B; margin-top:3px; }

    .msp-banner { background:linear-gradient(135deg, rgba(255,198,41,.12), rgba(232,163,23,.08)); border:1px solid rgba(232,163,23,.35); border-radius:12px; padding:14px 20px; display:flex; align-items:center; justify-content:space-between; margin-bottom:22px; color:#92660f; font-size:13.5px; font-weight:500; gap:12px; flex-wrap:wrap; }
    .msp-banner-danger { background:linear-gradient(135deg, rgba(192,57,43,.10), rgba(192,57,43,.06)); border:1px solid rgba(192,57,43,.3); color:#C0392B; }
    .msp-banner-text { display:flex; align-items:center; gap:8px; }
    .msp-banner-text svg { width:16px; height:16px; stroke-width:2; flex-shrink:0; }

    .msp-table-box { background:#fff; border-radius:18px; border:1px solid #eef1f5; box-shadow:0 1px 2px rgba(15,23,42,.04), 0 6px 20px rgba(15,23,42,.05); overflow:hidden; margin-bottom:24px; }
    
    .msp-filters { display:flex; gap:12px; flex-wrap:wrap; align-items:center; padding:18px 24px 20px; }
    .msp-field { position:relative; display:inline-flex; align-items:center; }
    .msp-field > svg {
        position:absolute;
        left:14px;
        top:50%;
        transform:translateY(-50%);
        width:16px;
        height:16px;
        stroke-width:2;
        color:#94a3b8;
        pointer-events:none;
        flex-shrink:0;
        z-index:2;
    }
    .msp-input, .msp-select {
        height:42px;
        padding:0 16px 0 42px !important;
        border-radius:10px;
        border:1.5px solid #e2e8f0;
        font-size:13.5px;
        background:#fff;
        color:#1E293B;
        font-family:inherit;
        transition:border-color .15s ease, box-shadow .15s ease;
        box-sizing:border-box;
    }
    .msp-input { min-width:260px; }
    .msp-input::placeholder { color:#94a3b8; font-size:13.5px; }
    .msp-select {
        cursor:pointer;
        min-width:165px;
        appearance:none;
        background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6' fill='none'%3E%3Cpath d='M1 1L5 5L9 1' stroke='%2394a3b8' stroke-width='1.8' stroke-linecap='round'/%3E%3C/svg%3E");
        background-repeat:no-repeat;
        background-position:right 14px center;
        padding-right:36px !important;
    }
    .msp-input:focus, .msp-select:focus { outline:none; border-color:#0081AB; box-shadow:0 0 0 3.5px rgba(0,129,171,.12); }
    .msp-input:hover, .msp-select:hover { border-color:#cbd5e1; }
    .msp-reset { display:inline-flex; align-items:center; gap:6px; font-size:13px; font-weight:600; color:#0081AB; background:rgba(0,129,171,.08); border:none; border-radius:9px; padding:10px 14px; cursor:pointer; transition:background .15s ease; height:42px; box-sizing:border-box; }
    .msp-reset:hover { background:rgba(0,129,171,.14); }
    .msp-reset svg { width:14px; height:14px; stroke-width:2.3; }

    .msp-table-wrap { overflow-x:auto; -webkit-overflow-scrolling:touch; }
    .msp-table { width:100%; border-collapse:collapse; min-width:1180px; }
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
    .msp-modal-lg { width:680px; }

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
    .msp-hint { font-size:11.5px; color:#94a3b8; margin:4px 0 0; }
    .msp-hint.msp-hint-active { color:#0081AB; font-weight:600; }

    .msp-import-info { background:#f8fafc; border:1px solid #eef1f5; border-radius:10px; padding:12px 14px; font-size:12.3px; color:#64748B; line-height:1.6; margin-bottom:16px; }
    .msp-import-info code { background:#eef2f7; color:#023E8A; padding:1px 5px; border-radius:5px; font-size:11.5px; }

    .msp-dropzone { border:2px dashed #cbd5e1; border-radius:12px; padding:32px; text-align:center; color:#64748B; cursor:pointer; font-size:13.5px; transition:all .15s ease; display:flex; flex-direction:column; align-items:center; gap:8px; }
    .msp-dropzone:hover { border-color:#0081AB; background:rgba(0,129,171,.03); }
    .msp-dropzone svg { width:26px; height:26px; stroke-width:1.6; color:#94a3b8; }

    /* CSS Tambahan Khusus Tabel Pemetaan Alias */
    .alias-table { width:100%; border-collapse:collapse; min-width:600px; }
    .alias-table th { background:#fafbfc; text-align:left; font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8; padding:12px 20px; border-top:1px solid #eef1f5; border-bottom:1px solid #eef1f5; }
    .alias-table td { padding:12px 20px; font-size:13px; color:#1e293b; border-bottom:1px solid #f5f7fa; }
    .alias-table tr:last-child td { border-bottom:none; }
    .alias-badge { display:inline-flex; align-items:center; gap:5px; background:rgba(0,129,171,.1); color:#023E8A; font-weight:700; padding:3px 10px; border-radius:999px; font-size:11.5px; }
</style>

<div class="msp-header">
    <div>
        <h1 style="font-size:22px; font-weight:800; color:#1B2559; margin:0 0 4px; letter-spacing:-0.015em;">Master SPKLU</h1>
        <p class="msp-subtitle">Daftar SPKLU yang sudah aktif di sistem</p>
    </div>
    @if (in_array(auth()->user()->role, ['super_admin', 'pengelola']))
        <div class="msp-actions">
            @if (($unmatchedTransaksiCount ?? 0) > 0)
                <button class="msp-btn msp-btn-warning" onclick="bukaModalPemetaanBulk()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M16 3h5v5"/><path d="M8 3H3v5"/><path d="M12 22v-8"/><path d="m8 18 4 4 4-4"/></svg>
                    Pemetaan Massal ({{ $unmatchedTransaksiCount }})
                </button>
            @endif
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
    <div class="msp-card blue">
        <div class="msp-card-top">
            <div class="msp-card-label">Total Unit SPKLU</div>
            <div class="msp-card-icon blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/></svg>
            </div>
        </div>
        <div class="msp-card-value">{{ $totalUnit }} <span style="font-size:14px; font-weight:600; color:#64748B;">unit</span></div>
        <div class="msp-card-note">Seluruh SPKLU terdaftar</div>
    </div>

    <div class="msp-card green">
        <div class="msp-card-top">
            <div class="msp-card-label">Berdasarkan Type</div>
            <div class="msp-card-icon green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><path d="m17 5-5-3-5 3"/><path d="m17 19-5 3-5-3"/></svg>
            </div>
        </div>
        <div class="msp-split-row">
            <div class="msp-value-split">
                {{ $totalByType['DC'] ?? 0 }}
                <small>Unit DC</small>
            </div>
            <div class="msp-value-split">
                {{ $totalByType['AC'] ?? 0 }}
                <small>Unit AC</small>
            </div>
        </div>
        <div class="msp-card-note">Fast Charging vs Standard</div>
    </div>

    <div class="msp-card amber">
        <div class="msp-card-top">
            <div class="msp-card-label">Berdasarkan Kepemilikan</div>
            <div class="msp-card-icon amber">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M12 6h.01"/><path d="M12 10h.01"/><path d="M12 14h.01"/><path d="M16 10h.01"/><path d="M16 14h.01"/><path d="M8 10h.01"/><path d="M8 14h.01"/></svg>
            </div>
        </div>
        <div class="msp-split-row">
            <div class="msp-value-split">
                {{ $totalByKepemilikan['PLN'] ?? 0 }}
                <small>Milik PLN</small>
            </div>
            <div class="msp-value-split">
                {{ $totalByKepemilikan['Swasta'] ?? 0 }}
                <small>Mitra Swasta</small>
            </div>
        </div>
        <div class="msp-card-note">Aset internal &amp; kemitraan</div>
    </div>

    <div class="msp-card rose">
        <div class="msp-card-top">
            <div class="msp-card-label">Total Kapasitas</div>
            <div class="msp-card-icon purple">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v4"/><path d="m4.93 4.93 2.83 2.83"/><path d="M2 12h4"/><path d="m4.93 19.07 2.83-2.83"/><path d="M12 22v-4"/><path d="m19.07 19.07-2.83-2.83"/><path d="M22 12h-4"/><path d="m19.07 4.93-2.83 2.83"/></svg>
            </div>
        </div>
        <div class="msp-card-value">{{ number_format($totalKapasitas, 0, ',', '.') }} <span style="font-size:14px; font-weight:600; color:#64748B;">kW</span></div>
        <div class="msp-card-note">*tidak termasuk unit custom</div>
    </div>
</div>

@if (($unmatchedTransaksiCount ?? 0) > 0)
<div class="msp-banner msp-banner-danger">
    <span class="msp-banner-text">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        Ada {{ $unmatchedTransaksiCount }} nama SPKLU dari data transaksi yang belum cocok dengan Master SPKLU. Data transaksi untuk nama-nama itu belum terhitung sampai dipetakan.
    </span>
    <button type="button" class="msp-btn msp-btn-danger" onclick="bukaModalPemetaanBulk()">Lakukan Pemetaan</button>
</div>
@endif

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
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama SPKLU..." class="msp-input" autocomplete="off">
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
                    <th>Tanggal Aktif</th>
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
                            </div>
                        </td>
                        <td>{{ $spklu->nozzle }}</td>
                        <td>
                            <span class="msp-pill {{ $spklu->kepemilikan === 'PLN' ? 'msp-pill-pln' : 'msp-pill-swasta' }}">{{ $spklu->kepemilikan }}</span>
                        </td>
                        <td>{{ $spklu->skema ?? '—' }}</td>
                        <td style="white-space:nowrap;">{{ $spklu->tanggal_aktif?->translatedFormat('d M Y') ?? '—' }}</td>
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
                        <td colspan="12">
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

{{-- CARD PEMETAAN ALIAS SPKLU --}}
<div class="msp-table-box">
    <div class="section-header-bar">
        <div class="section-header-bar-left">
            <div class="section-header-bar-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M16 3h5v5"/><path d="M8 3H3v5"/><path d="M12 22v-8"/><path d="m8 18 4 4 4-4"/></svg>
            </div>
            <div>
                <h2>Pemetaan Alias SPKLU</h2>
                <p>Pasangkan nama SPKLU dari file transaksi ke Master SPKLU agar data transaksi terhitung akurat</p>
            </div>
        </div>
        @if (in_array(auth()->user()->role, ['super_admin', 'pengelola']) && count($unmatchedList ?? []) > 0)
            <button type="button" class="msp-btn msp-btn-warning" onclick="bukaModalPemetaanBulk()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M16 3h5v5"/><path d="M8 3H3v5"/><path d="M12 22v-8"/><path d="m8 18 4 4 4-4"/></svg>
                Pemetaan Massal ({{ count($unmatchedList) }})
            </button>
        @endif
    </div>

    <div class="msp-table-wrap">
        <table class="alias-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Asli di File Transaksi</th>
                    <th>Dipetakan ke Master SPKLU</th>
                    @if (in_array(auth()->user()->role, ['super_admin', 'pengelola']))<th>Aksi</th>@endif
                </tr>
            </thead>
            <tbody>
                @forelse ($aliasList ?? [] as $i => $alias)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td style="font-weight:600; color:#0f172a;">{{ $alias->nama_asli }}</td>
                        <td>
                            <span class="alias-badge">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" style="width:12px; height:12px;"><path d="M20 6 9 17l-5-5"/></svg>
                                {{ $alias->spklu->nama ?? '—' }}
                            </span>
                        </td>
                        @if (in_array(auth()->user()->role, ['super_admin', 'pengelola']))
                            <td>
                                <button type="button" class="msp-del-btn" title="Ubah Pemetaan" onclick='bukaModalEditAlias({{ $alias->id }}, "{{ addslashes($alias->nama_asli) }}", {{ $alias->spklu_id }})'>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                </button>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align:center; padding:36px 20px; color:#94a3b8;">
                            Belum ada pemetaan alias SPKLU tersimpan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
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
                <select name="ulp_mapping_id" id="tambah-ulp" required>
                    <option value="">Pilih ULP...</option>
                    @foreach ($ulpList as $ulp)
                        <option value="{{ $ulp->id }}">{{ $ulp->nama_penuh }}</option>
                    @endforeach
                </select>

                <label>Kode Unit</label>
                <input type="text" name="kode_unit" id="tambah-kode-unit" placeholder="Otomatis terisi saat ULP dipilih">
                <p class="msp-hint" id="tambah-kode-unit-hint">Kode kantor unit PLN — biasanya sama untuk semua SPKLU di 1 ULP yang sama.</p>

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

                <label>Kode Unit</label>
                <input type="text" name="kode_unit" id="edit-kode-unit" placeholder="Otomatis terisi saat ULP diganti (kalau masih kosong)">
                <p class="msp-hint" id="edit-kode-unit-hint">Kode kantor unit PLN — biasanya sama untuk semua SPKLU di 1 ULP yang sama.</p>

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

                <label>Tanggal Aktif</label>
                <input type="date" name="tanggal_aktif" id="edit-tanggal-aktif">

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

{{-- Modal Edit Single Alias --}}
<div id="modal-edit-alias" class="msp-modal-overlay">
    <div class="msp-modal">
        <div class="msp-modal-header-bar">
            <div class="msp-modal-header-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M16 3h5v5"/><path d="M8 3H3v5"/><path d="M12 22v-8"/><path d="m8 18 4 4 4-4"/></svg>
            </div>
            <div>
                <h3>Edit Pemetaan Alias</h3>
                <p>Ubah pasangan Master SPKLU</p>
            </div>
        </div>
        <div class="msp-modal-body">
            <form method="POST" id="form-edit-alias">
                @csrf
                @method('PUT')

                <label>Nama Asli di File Transaksi</label>
                <input type="text" id="alias-nama-asli" readonly style="background:#f8fafc; color:#64748B;">

                <label>Petakan ke Master SPKLU</label>
                <select name="spklu_id" id="alias-spklu-id" required>
                    @foreach ($spklus as $spklu)
                        <option value="{{ $spklu->id }}">{{ $spklu->nama }}</option>
                    @endforeach
                </select>

                <div style="display:flex; justify-content:flex-end; gap:8px; margin-top:22px;">
                    <button type="button" class="msp-btn msp-btn-outline" data-close-modal>Batal</button>
                    <button type="submit" class="msp-btn msp-btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Pemetaan Bulk (Massal) --}}
<div id="modal-pemetaan-bulk" class="msp-modal-overlay">
    <div class="msp-modal msp-modal-lg">
        <div class="msp-modal-header-bar">
            <div class="msp-modal-header-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M16 3h5v5"/><path d="M8 3H3v5"/><path d="M12 22v-8"/><path d="m8 18 4 4 4-4"/></svg>
            </div>
            <div>
                <h3>Pemetaan Alias Massal</h3>
                <p>Hubungkan nama yang belum teridentifikasi ke Master SPKLU</p>
            </div>
        </div>
        <div class="msp-modal-body">
            <form method="POST" action="{{ route('master-spklu.alias.bulk-store') }}">
                @csrf
                <div style="max-height: 380px; overflow-y: auto; padding-right: 5px;">
                    @forelse ($unmatchedList ?? [] as $idx => $u)
                        <div style="background:#f8fafc; border:1px solid #eef1f5; border-radius:10px; padding:12px 14px; margin-bottom:12px;">
                            <div style="font-weight:700; color:#0f172a; font-size:13px; margin-bottom:4px;">
                                {{ $u->nama_asli }}
                                <span style="font-weight:500; color:#94a3b8; font-size:11.5px;">({{ $u->jumlah_baris_total }} transaksi)</span>
                            </div>
                            <input type="hidden" name="mappings[{{ $idx }}][nama_asli]" value="{{ $u->nama_asli }}">
                            <select name="mappings[{{ $idx }}][spklu_id]" required style="margin-top:6px;">
                                <option value="">-- Pilih SPKLU Tujuan --</option>
                                @foreach ($spklus as $spklu)
                                    <option value="{{ $spklu->id }}">{{ $spklu->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    @empty
                        <p style="text-align:center; color:#94a3b8; margin:20px 0;">Tidak ada nama SPKLU gantung yang perlu dipetakan saat ini.</p>
                    @endforelse
                </div>

                @if (count($unmatchedList ?? []) > 0)
                    <div style="display:flex; justify-content:flex-end; gap:8px; margin-top:20px;">
                        <button type="button" class="msp-btn msp-btn-outline" data-close-modal>Batal</button>
                        <button type="submit" class="msp-btn msp-btn-primary">Simpan Semua Pemetaan</button>
                    </div>
                @else
                    <div style="display:flex; justify-content:flex-end; margin-top:20px;">
                        <button type="button" class="msp-btn msp-btn-outline" data-close-modal>Tutup</button>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

<script>
function bukaModalEdit(spklu) {
    document.getElementById('form-edit-spklu').action = '/master-spklu/' + spklu.id;
    document.getElementById('edit-nama').value = spklu.nama ?? '';
    document.getElementById('edit-ulp').value = spklu.ulp_mapping_id ?? '';
    document.getElementById('edit-kode-unit').value = spklu.kode_unit ?? '';
    document.getElementById('edit-type').value = spklu.type ?? '';
    document.getElementById('edit-kw').value = spklu.kw ?? '';
    document.getElementById('edit-kw-detail').value = spklu.kw_detail ?? '';
    document.getElementById('edit-nozzle').value = spklu.nozzle ?? 1;
    document.getElementById('edit-kepemilikan').value = spklu.kepemilikan ?? '';
    document.getElementById('edit-skema').value = spklu.skema ?? '';
    document.getElementById('edit-tanggal-aktif').value = spklu.tanggal_aktif ? spklu.tanggal_aktif.substring(0, 10) : '';
    document.getElementById('edit-latitude').value = spklu.latitude ?? '';
    document.getElementById('edit-longitude').value = spklu.longitude ?? '';
    document.getElementById('modal-edit-spklu').style.display = 'flex';
}

function bukaModalEditAlias(id, namaAsli, spkluId) {
    document.getElementById('form-edit-alias').action = '/master-spklu/alias/' + id;
    document.getElementById('alias-nama-asli').value = namaAsli;
    document.getElementById('alias-spklu-id').value = spkluId;
    document.getElementById('modal-edit-alias').style.display = 'flex';
}

function bukaModalPemetaanBulk() {
    document.getElementById('modal-pemetaan-bulk').style.display = 'flex';
}

document.querySelectorAll('[data-open-modal]').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-open-modal');
        const modal = document.getElementById(id);
        if (modal) modal.style.display = 'flex';
    });
});

document.querySelectorAll('[data-close-modal]').forEach(btn => {
    btn.addEventListener('click', () => {
        btn.closest('.msp-modal-overlay').style.display = 'none';
    });
});

window.addEventListener('click', (e) => {
    if (e.target.classList.contains('msp-modal-overlay')) {
        e.target.style.display = 'none';
    }
});

async function ambilKodeUnitUntukUlp(ulpId) {
    if (!ulpId) return null;
    try {
        const res = await fetch(`/master-spklu/kode-unit-by-ulp/${ulpId}`, {
            headers: { 'Accept': 'application/json' },
        });
        if (!res.ok) return null;
        const data = await res.json();
        return data.kode_unit ?? null;
    } catch (e) {
        console.warn('Gagal ambil kode_unit untuk ULP ini:', e);
        return null;
    }
}

document.getElementById('tambah-ulp')?.addEventListener('change', async function () {
    const kodeUnitInput = document.getElementById('tambah-kode-unit');
    const hint = document.getElementById('tambah-kode-unit-hint');

    const kodeUnit = await ambilKodeUnitUntukUlp(this.value);

    if (kodeUnit) {
        kodeUnitInput.value = kodeUnit;
        hint.textContent = `Otomatis diisi dari SPKLU lain di ULP yang sama (${kodeUnit}) — bisa diubah manual kalau perlu.`;
        hint.classList.add('msp-hint-active');
    } else {
        kodeUnitInput.value = '';
        hint.textContent = 'Belum ada SPKLU lain di ULP ini yang punya Kode Unit — isi manual.';
        hint.classList.remove('msp-hint-active');
    }
});

document.getElementById('edit-ulp')?.addEventListener('change', async function () {
    const kodeUnitInput = document.getElementById('edit-kode-unit');
    const hint = document.getElementById('edit-kode-unit-hint');

    const kodeUnit = await ambilKodeUnitUntukUlp(this.value);

    if (kodeUnit) {
        kodeUnitInput.value = kodeUnit;
        hint.textContent = `Kode Unit otomatis disesuaikan ke ${kodeUnit} sesuai ULP yang dipilih.`;
        hint.classList.add('msp-hint-active');
    }
});
</script>

@endsection