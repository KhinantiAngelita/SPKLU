@extends('layouts.app')

@section('breadcrumb', 'Transaksi')
@section('page-title', 'Upload Data Transaksi')

@section('content')

@php
    $upAdaSedangDiproses = $riwayat->contains(fn($r) => $r->status === 'diproses');
@endphp

<link rel="stylesheet" href="{{ asset('vendor/choices/choices.min.css') }}">

<style>
    .up-page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; padding-bottom:16px; border-bottom:1px solid #E2E8F0; flex-wrap:wrap; gap:16px; }
    .up-page-subtitle { color:#64748B; margin:4px 0 0; font-size:13.5px; }

    .up-btn { display:inline-flex; align-items:center; gap:7px; border:none; border-radius:9px; font-size:13.3px; font-weight:700; padding:10px 18px; cursor:pointer; transition:all .15s ease; white-space:nowrap; }
    .up-btn svg { width:15px; height:15px; stroke-width:2.1; }
    .up-btn-outline { background:#fff; color:#1E293B; border:1px solid #e2e8f0; }
    .up-btn-outline:hover { background:#f8fafc; border-color:#cbd5e1; }
    .up-btn-primary { background:#023E8A; color:#fff; box-shadow:0 2px 10px rgba(2,62,138,.25); }
    .up-btn-primary:hover { background:#002D66; transform:translateY(-1px); box-shadow:0 4px 14px rgba(2,62,138,.3); }
    .up-btn-primary:disabled { opacity:.55; cursor:not-allowed; transform:none; box-shadow:none; }

    /* Summary cards */
    .up-summary-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:24px; }
    .up-summary-card {
        background:#fff; border-radius:16px; padding:20px; border:1px solid #e2e8f0;
        box-shadow:0 2px 6px rgba(15,23,42,.03), 0 10px 15px -3px rgba(15,23,42,.02);
        display:flex; align-items:center; gap:16px; position:relative; overflow:hidden;
        transition:transform .2s cubic-bezier(0.4, 0, 0.2, 1), box-shadow .2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .up-summary-card:hover { transform:translateY(-3px); box-shadow:0 12px 24px -4px rgba(15,23,42,.08); }
    .up-summary-card::before { content:""; position:absolute; top:0; left:0; right:0; height:3px; }
    .up-summary-card.blue::before   { background:linear-gradient(90deg, #023E8A, #0081AB); }
    .up-summary-card.amber::before  { background:linear-gradient(90deg, #D97706, #F59E0B); }
    .up-summary-card.green::before  { background:linear-gradient(90deg, #059669, #10B981); }
    .up-summary-icon { width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .up-summary-icon svg { width:20px; height:20px; stroke-width:2; }
    .up-ic-blue  { background:rgba(0,129,171,.1); color:#0081AB; }
    .up-ic-amber { background:rgba(217,119,6,.1); color:#D97706; }
    .up-ic-green { background:rgba(5,150,105,.1); color:#059669; }
    .up-summary-label { font-size:11.5px; font-weight:700; text-transform:uppercase; letter-spacing:.04em; color:#64748B; margin:0 0 4px; }
    .up-summary-value { font-size:28px; font-weight:800; letter-spacing:-.02em; color:#1B2559 !important; margin:0; line-height:1; }

    /* Upload card */
    .up-card { background:#fff; border-radius:16px; padding:26px; border:1px solid #eef1f5; box-shadow:0 1px 2px rgba(15,23,42,.04), 0 6px 16px rgba(15,23,42,.05); margin-bottom:22px; }
    .up-card-body { padding:22px 26px; }
    .up-card-title { margin:0 0 4px; font-size:16.5px; font-weight:800; color:#1B2559 !important; }
    .up-card-desc { color:#64748B; font-size:13.5px; margin:0 0 18px; }

    .up-dropzone { position:relative; display:flex; flex-direction:column; align-items:center; gap:10px; border:2px dashed #cbd5e1; border-radius:14px; padding:40px 20px; text-align:center; color:#64748B; cursor:pointer; transition:all .2s ease; }
    .up-dropzone svg { width:34px; height:34px; stroke-width:1.5; color:#94a3b8; transition:color .2s ease; }
    .up-dropzone:hover, .up-dropzone.dragging { border-color:#0081AB; background:rgba(0,129,171,.04); }
    .up-dropzone:hover svg, .up-dropzone.dragging svg { color:#0081AB; }
    .up-dropzone.dragging { border-style:solid; box-shadow:0 0 0 4px rgba(0,129,171,.1); }
    .up-dropzone-name { font-weight:600; color:#1E293B; font-size:14px; }
    .up-dropzone-hint { font-size:12px; color:#94a3b8; }

    .up-note { display:flex; gap:9px; align-items:flex-start; font-size:12.5px; color:#0369a1; background:rgba(0,129,171,.07); border:1px solid rgba(0,129,171,.15); border-radius:9px; padding:12px 14px; margin-top:16px; }
    .up-note svg { width:14px; height:14px; min-width:14px; margin-top:1px; color:#0081AB; stroke-width:2; }

    /* Queue list */
    .uq-item { display:flex; align-items:center; gap:12px; padding:12px 14px; border:1px solid #eef1f5; border-radius:10px; margin-bottom:8px; background:#fbfcfd; }
    .uq-icon { width:36px; height:36px; border-radius:9px; background:#fff; border:1px solid #eef1f5; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .uq-icon svg { width:16px; height:16px; color:#64748B; }
    .uq-info { flex:1; min-width:0; }
    .uq-name-row { display:flex; align-items:baseline; gap:8px; }
    .uq-name { font-size:13.3px; font-weight:600; color:#1E293B; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .uq-size { font-size:11px; color:#94a3b8; flex-shrink:0; }
    .uq-status { font-size:11.5px; color:#94a3b8; margin-top:2px; }
    .uq-bar-track { width:100%; height:5px; background:#eef1f5; border-radius:999px; overflow:hidden; margin-top:6px; }
    .uq-bar-fill { height:100%; width:0%; background:linear-gradient(90deg,#023E8A,#0081AB); border-radius:999px; transition:width .15s ease; }
    .uq-bar-fill.processing { background:#E8A317; animation:uqPulse 1.2s ease-in-out infinite; }
    .uq-bar-fill.success { background:#2E9E5B; width:100% !important; }
    .uq-bar-fill.error { background:#C0392B; width:100% !important; }
    @keyframes uqPulse { 0%,100% { opacity:1; } 50% { opacity:.5; } }
    .uq-pct { font-size:12px; font-weight:700; color:#64748B; min-width:38px; text-align:right; }
    .uq-remove { background:none; border:none; color:#cbd5e1; cursor:pointer; font-size:16px; padding:4px; line-height:1; transition:color .15s ease; }
    .uq-remove:hover { color:#C0392B; }

    /* Tables */
    .up-table-scroll { overflow-x:auto; -webkit-overflow-scrolling:touch; }
    .up-table-scroll-riwayat {
        max-height: 410px;
        overflow-y: auto;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .up-table-scroll-riwayat thead th {
        position: sticky;
        top: 0;
        z-index: 10;
        background: #fafbfc;
        box-shadow: 0 1px 0 #eef1f5;
    }

    /* Tombol filter urutan (Terbaru / Terlama) */
    .up-sort-btn {
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #64748B;
        background: transparent;
        transition: all .15s ease;
    }
    .up-sort-btn:hover { color: #1E293B; background: rgba(255,255,255,.7); }
    .up-sort-btn.active {
        background: #023E8A;
        color: #fff !important;
        box-shadow: 0 2px 6px rgba(2,62,138,.25);
    }
    .up-sort-btn svg { width: 13px; height: 13px; stroke-width: 2.2; }

    .up-table { width:100%; min-width:860px; border-collapse:collapse; }
    .up-table thead th { background:#fafbfc; text-align:left; font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8; padding:13px 20px; border-bottom:1px solid #eef1f5; white-space:nowrap; }
    .up-table td { padding:14px 20px; font-size:13.3px; color:#1E293B; border-bottom:1px solid #f5f7fa; }
    .up-table tbody tr:nth-child(even) { background:#fbfcfd; }
    .up-table tbody tr:hover { background:rgba(0,129,171,.04); }
    .up-table tbody tr:last-child td { border-bottom:none; }
    .up-empty { text-align:center; padding:48px 20px; color:#94a3b8; font-size:13.5px; }
    .up-empty > svg { width:32px; height:32px; color:#cbd5e1; margin-bottom:8px; stroke-width:1.5; }

    .up-empty-icon-success {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #DCFCE7;
        border: 2px solid #86EFAC;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
        box-shadow: 0 4px 14px rgba(22, 163, 74, 0.14);
    }
    .up-empty-icon-success svg {
        width: 26px !important;
        height: 26px !important;
        color: #16A34A !important;
        stroke: #16A34A !important;
        stroke-width: 2.8 !important;
        margin-bottom: 0 !important;
    }

    .up-badge { display:inline-flex; align-items:center; padding:4px 11px; border-radius:999px; font-size:11px; font-weight:700; white-space:nowrap; }
    .up-badge-success { background:rgba(46,158,91,.14); color:#2E9E5B; }
    .up-badge-error { background:rgba(192,57,43,.14); color:#C0392B; }
    .up-badge-warn { background:rgba(232,163,23,.14); color:#92660f; }
    .up-badge-processing { background:rgba(232,163,23,.14); color:#92660f; display:inline-flex; align-items:center; gap:6px; }
    .up-badge-processing .up-badge-dot { width:6px; height:6px; border-radius:50%; background:#E8A317; animation:uqPulse 1.2s ease-in-out infinite; }

    /* Badge tanggal - satu baris, background abu terang, senada dengan badge "Tidak Cocok" */
    .up-date-badge { display:inline-flex; align-items:center; background:#f1f5f9; color:#475569; padding:5px 10px; border-radius:7px; font-size:12.3px; font-weight:600; white-space:nowrap; }

    /* Icon petir untuk baris nama SPKLU (alias/unmatched) */
    .up-alias-row { display:flex; gap:12px; align-items:center; padding:12px 4px; }
    .up-alias-row + .up-alias-row { border-top:1px dashed #eef1f5; }
    .up-alias-row select { padding:8px 10px; border-radius:7px; border:1px solid #e2e8f0; font-size:12.5px; max-width:220px; font-family:inherit; }
    .up-alias-icon { width:34px; height:34px; border-radius:9px; background:rgba(0,129,171,.12); color:#0081AB; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .up-alias-icon svg { width:16px; height:16px; stroke-width:2; fill:none; }
    .up-alias-name { flex:1; min-width:0; font-size:13px; }

    .up-action-group { display:flex; gap:6px; }
    .up-del-btn, .up-match-btn, .up-edit-btn { border:none; border-radius:8px; width:32px; height:32px; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; transition:all .15s ease; }
    .up-del-btn { background:rgba(192,57,43,.08); color:#C0392B; }
    .up-del-btn:hover { background:rgba(192,57,43,.18); transform:translateY(-1px); }
    .up-match-btn { background:rgba(2,62,138,.08); color:#023E8A; position:relative; }
    .up-match-btn:hover { background:rgba(2,62,138,.18); transform:translateY(-1px); }
    .up-edit-btn { background:rgba(245,158,11,.12); color:#D97706; }
    .up-edit-btn:hover { background:rgba(245,158,11,.22); color:#B45309; transform:translateY(-1px); }
    .up-del-btn svg, .up-match-btn svg, .up-edit-btn svg { width:15px; height:15px; stroke-width:2.2; }
    .up-match-count {
        position:absolute; top:-5px; right:-5px; background:#C0392B; color:#fff;
        font-size:9px; font-weight:700; min-width:15px; height:15px; border-radius:999px;
        display:flex; align-items:center; justify-content:center; padding:0 3px;
        box-shadow:0 0 0 2px #fff;
    }

    .up-modal-overlay { display:none; position:fixed; inset:0; background:rgba(15,23,42,.5); backdrop-filter:blur(2px); align-items:center; justify-content:center; z-index:50; }
    .up-modal-overlay.show { display:flex; }
    /* overflow:hidden DIHAPUS dari sini — itu yang bikin dropdown Choices.js kepotong/gak muncul.
       Rounded corner dipindah ke header/footer supaya sudut modal tetap rapi tanpa overflow:hidden. */
    .up-modal { background:#fff; border-radius:18px; padding:0; width:600px; max-width:92vw; box-shadow:0 24px 60px rgba(0,0,0,.25); max-height:90vh; display:flex; flex-direction:column; }
    .up-modal-header { background:#FFFFFF; border-bottom:1px solid #F1F5F9; padding:20px 24px; border-radius:18px 18px 0 0; }
    .up-modal-header h3 { margin:0 0 4px; font-size:16.5px; font-weight:800; color:#1B2559; }
    .up-modal-header p { margin:0; font-size:12.5px; color:#64748B; }
    .up-modal-body { padding:20px 24px; overflow-y:auto; }
    .up-modal-footer { padding:16px 24px; border-top:1px solid #f1f5f9; border-radius:0 0 18px 18px; }

    .alert-error { background:rgba(192,57,43,.08); border:1px solid rgba(192,57,43,.25); color:#C0392B; border-radius:10px; padding:12px 16px; font-size:13.5px; margin-bottom:18px; }

    /* Choices.js — samain tinggi & radius dengan dropdown lain di halaman ini */
    .choices { margin-bottom:0; font-size:12.8px; }
    .choices__inner { min-height:auto; padding:7px 10px; border-radius:7px; border:1px solid #e2e8f0; background:#fff; }
    .up-alias-row .choices { flex:1; max-width:220px; }
    .choices__list--dropdown { border-radius:8px; overflow:hidden; z-index:60; }
    .choices__input { background:#fff; }

    /* FIX: .surface-card (didefinisikan di layout utama) kemungkinan overflow:hidden
       buat jaga rounded corner — ini motong render dropdown Choices.js yang posisinya
       absolute, bikin dropdown "SPKLU Belum Dipetakan" keliatan gak aktif/gak bisa
       diklik walau Choices.js-nya sendiri sudah ke-attach dengan benar. Bug yang sama
       yang udah pernah kejadian & difix di modal "Cocokkan Data" (lihat komentar
       overflow:hidden DIHAPUS di atas), sekarang muncul lagi di card ini karena card
       ini pakai wrapper beda (.surface-card, bukan .up-modal). */
    .surface-card:has(#alias-unmatched-table),
    .surface-card:has(#alias-bulk-rows),
    .surface-card:has(#modal-cocokkan-list) {
        overflow: visible;
    }
    .up-table-scroll-alias {
        overflow: visible;
    }
    .up-table-scroll-alias table td {
        overflow: visible;
    }
</style>

@error('file')
    <div class="alert-error">{{ $message }}</div>
@enderror

<div class="up-page-header">
    <div>
        <h1 style="font-size:22px; font-weight:800; color:#1B2559; margin:0 0 4px; letter-spacing:-0.015em;">Upload Data Transaksi</h1>
        <p class="up-page-subtitle" style="margin-top:0;">Upload file mentah transaksi dan lihat riwayat file yang sudah pernah diproses</p>
    </div>
    <a href="{{ route('transaksi.index') }}" class="up-btn up-btn-outline">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        Lihat Ringkasan
    </a>
</div>

<div class="up-summary-grid">
    <div class="up-summary-card blue">
        <div class="up-summary-icon up-ic-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
        <div>
            <p class="up-summary-label">Total Riwayat</p>
            <p class="up-summary-value">{{ number_format($riwayat->total()) }}</p>
        </div>
    </div>
    <div class="up-summary-card amber">
        <div class="up-summary-icon up-ic-amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></div>
        <div>
            <p class="up-summary-label">Nama Belum Dipetakan</p>
            <p class="up-summary-value">{{ number_format($unmatchedList->count()) }}</p>
        </div>
    </div>
    <div class="up-summary-card green" style="cursor:pointer;" onclick="window.location='{{ route('master-spklu.index') }}'">
        <div class="up-summary-icon up-ic-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg></div>
        <div>
            <p class="up-summary-label">Alias di Master SPKLU</p>
            <p class="up-summary-value">{{ number_format($aliasList->count()) }}</p>
        </div>
    </div>
</div>

<div class="up-card">
    <h2 class="up-card-title">Upload File Baru</h2>
    <p class="up-card-desc">Bisa pilih banyak file sekaligus — diproses satu per satu berurutan, jangan tutup halaman selama proses berjalan.</p>

    <label for="file-input-transaksi" class="up-dropzone" id="dropzone-transaksi">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
        <span class="up-dropzone-name" id="file-name-label-transaksi">Klik untuk pilih file (bisa lebih dari satu), atau drag &amp; drop di sini</span>
        <span class="up-dropzone-hint">Format: .csv — maks 50MB per file</span>
        <input type="file" id="file-input-transaksi" accept=".csv" multiple style="display:none;">
    </label>

    <div class="up-note">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
        <span>File besar (100 ribu+ baris) bisa butuh beberapa menit per file — jangan tutup atau refresh halaman ini selama proses berjalan.</span>
    </div>

    <div id="file-queue-list" style="margin-top:18px; display:none;"></div>

    <div style="margin-top:20px;">
        <button type="button" class="up-btn up-btn-primary" id="btn-submit-upload" disabled>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            Upload &amp; Proses Semua
        </button>
    </div>
</div>

<div class="surface-card">
    <div class="section-header-bar">
        <div class="section-header-bar-left">
            <div class="section-header-bar-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <div>
                <h2>Riwayat Upload</h2>
                <p>Ikon kuning = masih ada nama belum cocok khusus dari file itu. Ikon panah = upload ulang file ini (mengganti data lama dari riwayat ini). Hapus riwayat akan ikut menghapus data transaksi terkait.</p>
            </div>
        </div>

        <div style="display:flex; align-items:center; gap:8px;">
            <span style="font-size:11.5px; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#64748B;">Urutkan:</span>
            <div style="background:#f1f5f9; padding:3px; border-radius:10px; display:inline-flex; gap:3px;">
                <a href="{{ route('transaksi.upload', array_merge(request()->query(), ['urutan' => 'terbaru'])) }}"
                   class="up-sort-btn {{ ($urutan ?? 'terbaru') === 'terbaru' ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><polyline points="19 12 12 19 5 12"/></svg>
                    Terbaru
                </a>
                <a href="{{ route('transaksi.upload', array_merge(request()->query(), ['urutan' => 'terlama'])) }}"
                   class="up-sort-btn {{ ($urutan ?? 'terbaru') === 'terlama' ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
                    Terlama
                </a>
            </div>
        </div>
    </div>

    <div class="up-table-scroll up-table-scroll-riwayat">
        <table class="up-table">
            <thead>
                <tr>
                    <th>Nama File</th>
                    <th>Diupload Oleh</th>
                    <th>Tanggal</th>
                    <th>Baris Diproses</th>
                    <th>Rekap Tersimpan</th>
                    <th>Tidak Cocok</th>
                    <th>Status</th>
                    @if (auth()->user()->role === 'super_admin')<th>Aksi</th>@endif
                </tr>
            </thead>
            <tbody>
                @forelse ($riwayat as $r)
                    <tr>
                        <td>
                            <div class="row-icon-cell">
                                <div class="row-icon-box {{ $r->status === 'gagal' ? '' : 'green' }}">
                                    @if ($r->status === 'gagal')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><line x1="9" y1="14" x2="15" y2="18"/><line x1="15" y1="14" x2="9" y2="18"/></svg>
                                    @else
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                    @endif
                                </div>
                                <span style="font-weight:600;">{{ $r->nama_file }}</span>
                            </div>
                        </td>
                        <td>{{ $r->diuploadOleh->name ?? '—' }}</td>
                        <td><span class="up-date-badge">{{ $r->created_at->translatedFormat('d M Y, H:i') }}</span></td>
                        <td>{{ number_format($r->total_baris_diproses) }}</td>
                        <td>{{ number_format($r->total_rekap_tersimpan) }}</td>
                        <td>
                            @if ($r->jumlah_nama_tidak_cocok > 0)
                                <span class="up-badge up-badge-warn">{{ $r->jumlah_nama_tidak_cocok }} nama</span>
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            @if ($r->status === 'berhasil')
                                <span class="up-badge up-badge-success">Berhasil</span>
                            @elseif ($r->status === 'diproses')
                                <span class="up-badge up-badge-processing"><span class="up-badge-dot"></span>Sedang Diproses</span>
                            @else
                                <span class="up-badge up-badge-error" title="{{ $r->pesan_error }}">Gagal</span>
                            @endif
                        </td>
                        @if (auth()->user()->role === 'super_admin')
                            <td>
                                <div class="up-action-group">
                                    @if ($r->unresolvedUnmatched->count() > 0)
                                        <button type="button" class="up-match-btn" title="Cocokkan Data"
                                            data-unmatched='@json($r->unresolvedUnmatched->map(fn($u) => ["nama" => $u->nama_asli, "jumlah" => $u->jumlah_baris]))'
                                            data-filename="{{ $r->nama_file }}"
                                            onclick="openMatchModal(this)">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                                            <span class="up-match-count">{{ $r->unresolvedUnmatched->count() }}</span>
                                        </button>
                                    @endif

                                    @if ($r->path_file)
                                        <button type="button" class="up-match-btn" title="Proses Ulang (pakai file yang sama, gak perlu pilih file lagi)" onclick="doReprocess({{ $r->id }})">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-3-6.7"/><polyline points="21 3 21 9 15 9"/></svg>
                                        </button>
                                        <button type="button" class="up-match-btn" title="Ganti File (upload file lain untuk menggantikan)" onclick="triggerReupload({{ $r->id }})">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                        </button>
                                    @else
                                        <button type="button" class="up-match-btn" title="Upload Ulang File Ini (file asli sudah tidak tersimpan di server)" onclick="triggerReupload({{ $r->id }})">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-3-6.7"/><polyline points="21 3 21 9 15 9"/></svg>
                                        </button>
                                    @endif
                                    <input type="file" id="reupload-input-{{ $r->id }}" accept=".csv" style="display:none;" onchange="doReupload({{ $r->id }}, this)">

                                    <form method="POST" action="{{ route('transaksi.upload.destroy', $r) }}"
                                          data-confirm="Riwayat &quot;{{ $r->nama_file }}&quot; beserta SEMUA data transaksi dari file ini akan terhapus permanen dan tidak bisa dibatalkan."
                                          data-confirm-title="Hapus riwayat ini?"
                                          data-confirm-type="danger">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="up-del-btn" title="Hapus riwayat + data">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="up-empty">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" style="display:block; margin:0 auto 8px;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            Belum ada riwayat upload.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding:16px 24px;">{{ $riwayat->links() }}</div>
</div>

{{-- Card: Pemetaan Alias yang Belum Dipetakan --}}
<div class="surface-card">
    <div class="section-header-bar">
        <div class="section-header-bar-left">
            <div class="section-header-bar-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
            <div>
                <h2>Pemetaan Alias yang Belum Dipetakan @if($unmatchedList->count() > 0)<span style="margin-left:6px; font-size:12px; font-weight:700; background:rgba(232,163,23,0.15); color:#92660F; padding:2px 10px; border-radius:999px;">{{ $unmatchedList->count() }} nama</span>@endif</h2>
                <p>Daftar nama SPKLU di file transaksi yang belum cocok otomatis. Jika sudah dipetakan, data akan otomatis masuk ke <strong>Pemetaan Alias SPKLU</strong> di menu <strong>Master SPKLU</strong>.</p>
            </div>
        </div>
    </div>

    @if ($unmatchedList->count() > 0)
        <div class="up-table-scroll up-table-scroll-alias">
            <table class="up-table" id="alias-unmatched-table" style="min-width:0;">
                <thead>
                    <tr>
                        <th style="width:36%;">Nama di File Sumber</th>
                        <th style="width:16%;">Jumlah Baris</th>
                        <th style="width:36%;">Dipetakan ke Master SPKLU</th>
                        <th style="width:12%; text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($unmatchedList as $item)
                        <tr class="up-alias-row" data-nama="{{ $item->nama_asli }}">
                            <td>
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <div class="up-alias-icon" style="width:30px; height:30px; border-radius:8px; background:rgba(232,163,23,0.12); color:#D97706;">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" style="width:15px; height:15px;"><path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                                    </div>
                                    <div>
                                        <strong style="color:#0F172A; font-size:13.5px;">{{ $item->nama_asli }}</strong>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="up-badge up-badge-warn">
                                    {{ number_format($item->jumlah_baris_total) }} baris
                                </span>
                            </td>
                            <td style="overflow:visible;">
                                <select class="alias-searchable" style="width:100%;">
                                    <option value="">Pilih Master SPKLU tujuan...</option>
                                    @foreach ($spkluList as $s)
                                        <option value="{{ $s->id }}">{{ $s->nama }} ({{ $s->up3 }})</option>
                                    @endforeach
                                </select>
                            </td>
                            <td style="text-align:center;">
                                <button type="button" class="up-btn up-btn-primary" style="padding:6px 14px; font-size:12px; width:100%; justify-content:center;" onclick="simpanSingleAlias(this)" title="Petakan nama ini ke Master SPKLU">
                                    Petakan
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:16px 24px; background:#FAFBFD; border-top:1px solid #EEF2F6; display:flex; justify-content:space-between; align-items:center; border-radius:0 0 18px 18px; flex-wrap:wrap; gap:12px;">
            <span style="font-size:12.5px; color:#64748B;">
                <svg style="width:14px; height:14px; display:inline; vertical-align:-2px; color:#0081AB; margin-right:4px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                Pilih SPKLU untuk beberapa baris sekaligus, lalu klik tombol di samping untuk menyimpan bersamaan.
            </span>
            <button type="button" class="up-btn up-btn-primary" id="btn-simpan-alias-bulk">
                Simpan Semua yang Dipilih
            </button>
        </div>
    @else
        <div class="up-empty" style="padding:48px 24px;">
            <div class="up-empty-icon-success">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
            </div>
            <p style="font-weight:800; color:#1E293B; font-size:16px; margin:0 0 6px;">Semua Nama SPKLU Telah Dipetakan</p>
            <p style="color:#64748B; font-size:13px; margin:0 0 20px; max-width:480px; margin-inline:auto; line-height:1.5;">Tidak ada alias transaksi yang tertunda. Seluruh alias yang tersimpan aktif dapat dilihat dan dikelola pada menu <strong>Master SPKLU</strong>.</p>
            <a href="{{ route('master-spklu.index') }}" class="up-btn up-btn-outline" style="display:inline-flex;">
                Lihat Pemetaan Alias di Master SPKLU
                <svg style="width:14px; height:14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        </div>
    @endif
</div>

{{-- Modal Cocokkan Data --}}
<div class="up-modal-overlay" id="modal-cocokkan">
    <div class="up-modal">
        <div class="up-modal-header">
            <h3 id="modal-cocokkan-title">Cocokkan Data</h3>
            <p>Nama SPKLU yang belum cocok, khusus dari file ini. Pilih beberapa sekaligus lalu simpan.</p>
        </div>
        <div class="up-modal-body">
            <div id="modal-cocokkan-list"></div>
        </div>
        <div class="up-modal-footer" style="display:flex; gap:8px;">
            <button type="button" class="up-btn" style="background:#fff; border:1px solid #e2e8f0; flex:1; justify-content:center;"
                    onclick="document.getElementById('modal-cocokkan').classList.remove('show')">Tutup</button>
            <button type="button" class="up-btn up-btn-primary" style="flex:1; justify-content:center;" id="btn-simpan-modal-cocokkan">Simpan Semua yang Dipilih</button>
        </div>
    </div>
</div>


<script src="{{ asset('vendor/choices/choices.min.js') }}"></script>
<script>
let fileQueue = [];

const fileInput = document.getElementById('file-input-transaksi');
const dropzone = document.getElementById('dropzone-transaksi');
const queueList = document.getElementById('file-queue-list');
const btnSubmit = document.getElementById('btn-submit-upload');
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '{{ csrf_token() }}';

// ===== Dropdown SPKLU searchable (Choices.js) — dipakai di card "Belum Dipetakan" & modal "Cocokkan Data" =====
function initAliasChoices(scope) {
    scope.querySelectorAll('select.alias-searchable').forEach(el => {
        if (el.dataset.choicesInit) return;
        el.dataset.choicesInit = '1';
        new Choices(el, {
            searchEnabled: true,
            shouldSort: false,
            itemSelectText: '',
            placeholder: true,
            searchPlaceholderValue: 'Cari SPKLU...',
            noResultsText: 'SPKLU tidak ditemukan',
        });
    });
}

function formatBytes(bytes) {
    if (!bytes) return '';
    const units = ['B', 'KB', 'MB', 'GB'];
    let i = 0;
    let n = bytes;
    while (n >= 1024 && i < units.length - 1) { n /= 1024; i++; }
    return n.toFixed(i === 0 ? 0 : 1) + ' ' + units[i];
}

function addFilesToQueue(fileList) {
    const invalidFormatFiles = [];
    const tooLargeFiles = [];
    const validItems = [];

    Array.from(fileList).forEach(file => {
        const ext = file.name.split('.').pop().toLowerCase();
        if (ext !== 'csv') {
            invalidFormatFiles.push(file.name);
            return;
        }
        if (file.size > 50 * 1024 * 1024) {
            tooLargeFiles.push({ name: file.name, size: formatBytes(file.size) });
            return;
        }
        validItems.push({ file, status: 'menunggu', progress: 0 });
    });

    if (invalidFormatFiles.length > 0) {
        Swal.fire({
            icon: 'error',
            title: 'Format File Tidak Sesuai',
            html: `Sistem saat ini <b>hanya menerima format .csv</b>.<br><br>File berikut tidak dapat diunggah:<br><span style="color:#C0392B; font-weight:600;">${invalidFormatFiles.join('<br>')}</span><br><br><small style="color:#64748B;">Silakan simpan file sebagai <b>CSV (Comma delimited) (*.csv)</b> di Excel terlebih dahulu.</small>`,
            confirmButtonColor: '#0081AB',
        });
    }

    if (tooLargeFiles.length > 0) {
        const daftar = tooLargeFiles.map(f => `${f.name} (${f.size})`).join('<br>');
        Swal.fire({
            icon: 'warning',
            title: 'Ukuran File Terlalu Besar',
            html: `File melebihi batas maksimal <b>50 MB</b> per file:<br><br><span style="color:#C0392B; font-weight:600;">${daftar}</span><br><br><small style="color:#64748B;">Silakan perkecil atau bagi data transaksi di file tersebut sebelum diupload.</small>`,
            confirmButtonColor: '#0081AB',
        });
    }

    if (validItems.length > 0) {
        fileQueue = fileQueue.concat(validItems);
        renderQueue();
        document.getElementById('file-name-label-transaksi').textContent =
            fileQueue.length === 1 ? fileQueue[0].file.name : `${fileQueue.length} file dipilih`;
        btnSubmit.disabled = fileQueue.length === 0;
    }
}

fileInput.addEventListener('change', function () {
    addFilesToQueue(this.files);
    this.value = '';
});

// Drag & drop
['dragenter', 'dragover'].forEach(evt => {
    dropzone.addEventListener(evt, function (e) {
        e.preventDefault();
        e.stopPropagation();
        dropzone.classList.add('dragging');
    });
});

['dragleave', 'drop'].forEach(evt => {
    dropzone.addEventListener(evt, function (e) {
        e.preventDefault();
        e.stopPropagation();
        dropzone.classList.remove('dragging');
    });
});

dropzone.addEventListener('drop', function (e) {
    const files = e.dataTransfer?.files;
    if (files && files.length > 0) {
        addFilesToQueue(files);
    }
});

function renderQueue() {
    if (fileQueue.length === 0) {
        queueList.style.display = 'none';
        return;
    }
    queueList.style.display = 'block';
    queueList.innerHTML = fileQueue.map((item, i) => {
        let barClass = '';
        let statusText = 'Menunggu giliran';
        let pctText = '';

        if (item.status === 'uploading') {
            statusText = 'Mengupload...';
            pctText = item.progress + '%';
        } else if (item.status === 'processing') {
            barClass = 'processing';
            statusText = 'Memproses di server (bisa beberapa menit)...';
        } else if (item.status === 'success') {
            barClass = 'success';
            statusText = item.message ?? 'Berhasil';
        } else if (item.status === 'error') {
            barClass = 'error';
            statusText = item.message ?? 'Gagal';
        }

        return `
            <div class="uq-item">
                <div class="uq-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                </div>
                <div class="uq-info">
                    <div class="uq-name-row">
                        <div class="uq-name">${item.file.name}</div>
                        <div class="uq-size">${formatBytes(item.file.size)}</div>
                    </div>
                    <div class="uq-status">${statusText}</div>
                    <div class="uq-bar-track"><div class="uq-bar-fill ${barClass}" style="width:${item.status === 'uploading' ? item.progress : 0}%"></div></div>
                </div>
                <div class="uq-pct">${pctText}</div>
                ${item.status === 'menunggu' ? `<button type="button" class="uq-remove" onclick="removeFromQueue(${i})">✕</button>` : ''}
            </div>
        `;
    }).join('');
}

function removeFromQueue(index) {
    fileQueue.splice(index, 1);
    renderQueue();
    document.getElementById('file-name-label-transaksi').textContent =
        fileQueue.length === 0
            ? 'Klik untuk pilih file (bisa lebih dari satu), atau drag & drop di sini'
            : (fileQueue.length === 1 ? fileQueue[0].file.name : `${fileQueue.length} file dipilih`);
    btnSubmit.disabled = fileQueue.length === 0;
}

btnSubmit.addEventListener('click', async function () {
    btnSubmit.disabled = true;
    btnSubmit.textContent = 'Sedang memproses...';

    let sukses = 0, gagal = 0;

    for (let i = 0; i < fileQueue.length; i++) {
        if (fileQueue[i].status === 'success') { sukses++; continue; }
        const ok = await uploadOneFile(fileQueue[i]);
        ok ? sukses++ : gagal++;
        renderQueue();
    }

    btnSubmit.textContent = 'Selesai';

    await Swal.fire({
        icon: gagal === 0 ? 'success' : 'warning',
        title: gagal === 0 ? 'Semua file berhasil diupload!' : 'Sebagian file gagal diupload',
        html: `<b>${sukses}</b> berhasil diupload${gagal > 0 ? `, <b style="color:#C0392B">${gagal}</b> gagal` : ''}. File yang berhasil diupload masih diproses di background — cek status terbaru di Riwayat Upload. Halaman akan dimuat ulang.`,
        confirmButtonText: 'OK',
        confirmButtonColor: '#0081AB',
    });

    location.reload();
});

function uploadOneFile(item) {
    return new Promise((resolve) => {
        item.status = 'uploading';
        item.progress = 0;
        renderQueue();

        const formData = new FormData();
        formData.append('file', item.file);
        formData.append('_token', csrfToken);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '{{ route('transaksi.import') }}');
        xhr.setRequestHeader('Accept', 'application/json');

        xhr.upload.addEventListener('progress', function (e) {
            if (e.lengthComputable) {
                item.progress = Math.round((e.loaded / e.total) * 100);
                if (item.progress >= 100) item.status = 'processing';
                renderQueue();
            }
        });

        xhr.onload = function () {
            if (xhr.status === 413) {
                item.status = 'error';
                item.message = 'Ukuran file terlalu besar (melebihi batas 50 MB)';
                resolve(false);
                return;
            }
            try {
                const res = JSON.parse(xhr.responseText);
                if (xhr.status >= 200 && xhr.status < 300 && res.success) {
                    item.status = 'success';
                    item.message = res.message;
                    resolve(true);
                } else {
                    item.status = 'error';
                    item.message = res.message ?? (res.errors?.file?.[0] ?? 'Gagal diproses');
                    resolve(false);
                }
            } catch (e) {
                item.status = 'error';
                item.message = xhr.status === 413 ? 'Ukuran file terlalu besar (melebihi batas 50 MB)' : 'Respons server tidak dikenali';
                resolve(false);
            }
        };

        xhr.onerror = function () {
            item.status = 'error';
            item.message = 'Koneksi terputus saat upload';
            resolve(false);
        };

        xhr.send(formData);
    });
}

// ===== Simpan alias sekaligus (bulk) — dipakai card "Belum Dipetakan" & modal "Cocokkan Data" =====
async function submitAliasBulk(containerSelector, btn) {
    const rows = document.querySelectorAll(`${containerSelector} .up-alias-row`);
    const mappings = [];
    rows.forEach(row => {
        const select = row.querySelector('select.alias-searchable');
        if (select && select.value) {
            mappings.push({ nama_asli: row.dataset.nama, spklu_id: select.value });
        }
    });

    if (mappings.length === 0) {
        Swal.fire({ icon: 'info', title: 'Belum ada yang dipilih', text: 'Pilih SPKLU untuk minimal satu nama dulu.' });
        return;
    }

    btn.disabled = true;
    const teksAsli = btn.textContent;
    btn.textContent = 'Menyimpan...';

    try {
        const res = await fetch('{{ route('master-spklu.alias.bulk-store') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ mappings }),
        });
        const data = await res.json();
        if (res.ok && data.success) {
            await Swal.fire({
                icon: 'success',
                title: `${data.jumlah} Pemetaan Berhasil Disimpan`,
                text: 'Data pemetaan otomatis masuk ke Pemetaan Alias SPKLU di menu Master SPKLU. Halaman akan dimuat ulang.',
                confirmButtonColor: '#0081AB',
            });
            location.reload();
        } else {
            throw new Error(data.message || 'Gagal menyimpan');
        }
    } catch (e) {
        Swal.fire({ icon: 'error', title: 'Gagal menyimpan', text: e.message });
        btn.disabled = false;
        btn.textContent = teksAsli;
    }
}

// ===== Simpan alias per baris (single) =====
async function simpanSingleAlias(btn) {
    const row = btn.closest('tr.up-alias-row');
    const namaAsli = row.dataset.nama;
    const select = row.querySelector('select.alias-searchable');
    const spkluId = select ? select.value : '';

    if (!spkluId) {
        Swal.fire({
            icon: 'info',
            title: 'Pilih SPKLU Terlebih Dahulu',
            text: 'Silakan pilih Master SPKLU yang sesuai untuk "' + namaAsli + '" sebelum memetakan.',
            confirmButtonColor: '#0081AB',
        });
        return;
    }

    btn.disabled = true;
    const originalText = btn.innerHTML;
    btn.innerHTML = 'Menyimpan...';

    try {
        const res = await fetch('{{ route('master-spklu.alias.bulk-store') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ mappings: [{ nama_asli: namaAsli, spklu_id: spkluId }] }),
        });
        const data = await res.json();
        if (res.ok && data.success) {
            await Swal.fire({
                icon: 'success',
                title: 'Pemetaan Berhasil Disimpan',
                text: `"${namaAsli}" kini otomatis terhubung ke Master SPKLU dan tersimpan di menu Master SPKLU.`,
                confirmButtonColor: '#0081AB',
            });
            location.reload();
        } else {
            throw new Error(data.message || 'Gagal menyimpan pemetaan');
        }
    } catch (e) {
        Swal.fire({ icon: 'error', title: 'Gagal Menyimpan', text: e.message });
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
}

document.getElementById('btn-simpan-alias-bulk')?.addEventListener('click', function () {
    submitAliasBulk('#alias-unmatched-table', this);
});

document.getElementById('btn-simpan-modal-cocokkan')?.addEventListener('click', function () {
    submitAliasBulk('#modal-cocokkan-list', this);
});

// FIX: modal ditampilkan (classList.add('show')) DULU, baru Choices.js diinit
// lewat requestAnimationFrame di frame berikutnya. Kalau Choices diinit saat
// elemen masih display:none, ukurannya kehitung 0 dan dropdown-nya jadi
// "rusak" (gak bisa diklik / opsi gak nongol) walau modal sudah kelihatan.
function openMatchModal(btn) {
    const items = JSON.parse(btn.dataset.unmatched);
    const filename = btn.dataset.filename;

    document.getElementById('modal-cocokkan-title').textContent = 'Cocokkan Data — ' + filename;

    document.getElementById('modal-cocokkan-list').innerHTML = items.map(item => `
        <div class="up-alias-row" data-nama="${item.nama}">
            <div class="up-alias-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/></svg>
            </div>
            <div class="up-alias-name">
                <strong>${item.nama}</strong><br>
                <span style="color:#94a3b8; font-size:11.5px;">${item.jumlah.toLocaleString('id-ID')} baris di file ini</span>
            </div>
            <select class="alias-searchable">
                <option value="">Pilih SPKLU yang benar...</option>
                @foreach ($spkluList as $s)<option value="{{ $s->id }}">{{ $s->nama }}</option>@endforeach
            </select>
        </div>
    `).join('');

    document.getElementById('modal-cocokkan').classList.add('show');

    requestAnimationFrame(() => {
        initAliasChoices(document.getElementById('modal-cocokkan-list'));
    });
}




// ===== Proses ulang 1-klik: pakai file mentah yang sudah tersimpan di server =====
// gak perlu pilih file lagi — cukup konfirmasi, server yang reprocess ulang
// dari salinan file yang sama persis seperti waktu upload pertama.
async function doReprocess(id) {
    const konfirmasi = await Swal.fire({
        icon: 'warning',
        title: 'Proses ulang file ini?',
        html: 'Data transaksi &amp; nama tidak cocok dari riwayat ini akan <b>dihapus dan diproses ulang</b> dari file yang sama persis seperti sebelumnya — cocok dipakai kalau ada alias baru yang perlu ikut diproses.',
        showCancelButton: true,
        confirmButtonText: 'Ya, proses ulang',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#0081AB',
    });

    if (!konfirmasi.isConfirmed) return;

    Swal.fire({
        title: 'Memproses ulang...',
        html: 'Jangan tutup atau refresh halaman ini selama proses berjalan.',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading(),
    });

    try {
        const res = await fetch(`/transaksi/upload/${id}/reprocess`, {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        });
        const data = await res.json();
        if (res.ok && data.success) {
            await Swal.fire({ icon: 'success', title: 'Diproses ulang di background', text: data.message });
            location.reload();
        } else {
            throw new Error(data.message || 'Gagal memproses ulang');
        }
    } catch (e) {
        Swal.fire({ icon: 'error', title: 'Gagal', text: e.message });
    }
}

// ===== Upload ulang file di riwayat (ganti dengan file lain) =====
function triggerReupload(id) {
    document.getElementById('reupload-input-' + id).click();
}

async function doReupload(id, inputEl) {
    const file = inputEl.files[0];
    if (!file) return;

    const ext = file.name.split('.').pop().toLowerCase();
    if (ext !== 'csv') {
        Swal.fire({
            icon: 'error',
            title: 'Format File Tidak Sesuai',
            html: `Sistem saat ini <b>hanya menerima format .csv</b>.<br><br>File terpilih: <b>${file.name}</b><br><br><small style="color:#64748B;">Silakan simpan file sebagai <b>CSV (Comma delimited) (*.csv)</b> di Excel terlebih dahulu.</small>`,
            confirmButtonColor: '#0081AB',
        });
        inputEl.value = '';
        return;
    }

    if (file.size > 50 * 1024 * 1024) {
        Swal.fire({
            icon: 'warning',
            title: 'Ukuran File Terlalu Besar',
            html: `File <b>${file.name}</b> (${formatBytes(file.size)}) melebihi batas maksimal <b>50 MB</b>.<br><br><small style="color:#64748B;">Silakan perkecil atau bagi data transaksi di file tersebut sebelum diupload.</small>`,
            confirmButtonColor: '#0081AB',
        });
        inputEl.value = '';
        return;
    }

    const konfirmasi = await Swal.fire({
        icon: 'warning',
        title: 'Upload ulang file ini?',
        html: 'Data transaksi &amp; nama tidak cocok yang berasal dari riwayat ini akan <b>dihapus dan diganti</b> dengan hasil dari file baru.',
        showCancelButton: true,
        confirmButtonText: 'Ya, upload ulang',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#0081AB',
    });

    if (!konfirmasi.isConfirmed) { inputEl.value = ''; return; }

    Swal.fire({
        title: 'Mengupload...',
        html: 'Jangan tutup atau refresh halaman ini selama upload berjalan.',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading(),
    });

    const formData = new FormData();
    formData.append('file', file);
    formData.append('_token', csrfToken);

    try {
        const res = await fetch(`/transaksi/upload/${id}/reupload`, {
            method: 'POST',
            headers: { 'Accept': 'application/json' },
            body: formData,
        });
        const data = await res.json();
        if (res.ok && data.success) {
            await Swal.fire({ icon: 'success', title: 'Diupload, sedang diproses di background', text: data.message });
            location.reload();
        } else {
            throw new Error(data.message || 'Gagal memproses ulang');
        }
    } catch (e) {
        Swal.fire({ icon: 'error', title: 'Gagal', text: e.message });
    } finally {
        inputEl.value = '';
    }
}

// Inisialisasi dropdown searchable yang udah ada di halaman saat load pertama
// (card "Belum Dipetakan" — kalau ada). Modal "Cocokkan Data" diinit sendiri
// saat dibuka (select-nya baru dibuat tiap kali lewat innerHTML). Modal
// "Edit Pemetaan Alias" pakai select native, gak butuh Choices sama sekali.
document.addEventListener('DOMContentLoaded', function () {
    initAliasChoices(document);
});

// ===== Auto-refresh selagi masih ada riwayat berstatus "diproses" =====
// Biar status "Sedang Diproses" otomatis kecek ulang tanpa perlu refresh
// manual. Berhenti dengan sendirinya begitu tidak ada lagi baris 'diproses'
// (karena $upAdaSedangDiproses jadi false setelah reload berikutnya).
@if($upAdaSedangDiproses)
setTimeout(() => location.reload(), 10000);
@endif
</script>

@endsection