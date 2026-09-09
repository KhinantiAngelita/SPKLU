@extends('layouts.app')

@section('breadcrumb', 'Transaksi')
@section('page-title', 'Upload Data Transaksi')

@section('content')

<style>
    .up-page-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:20px; flex-wrap:wrap; gap:10px; }
    .up-page-subtitle { color:#64748B; margin:4px 0 0; font-size:13.5px; }

    .up-btn { display:inline-flex; align-items:center; gap:7px; border:none; border-radius:9px; font-size:13.3px; font-weight:700; padding:10px 18px; cursor:pointer; transition:all .15s ease; white-space:nowrap; }
    .up-btn svg { width:15px; height:15px; stroke-width:2.1; }
    .up-btn-outline { background:#fff; color:#1E293B; border:1px solid #e2e8f0; }
    .up-btn-outline:hover { background:#f8fafc; border-color:#cbd5e1; }
    .up-btn-primary { background:linear-gradient(135deg,#023E8A,#0081AB); color:#fff; box-shadow:0 2px 10px rgba(2,62,138,.25); }
    .up-btn-primary:hover { transform:translateY(-1px); box-shadow:0 4px 14px rgba(2,62,138,.3); }
    .up-btn-primary:disabled { opacity:.55; cursor:not-allowed; transform:none; box-shadow:none; }

    /* Summary cards */
    .up-summary-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:18px; margin-bottom:22px; }
    .up-summary-card { background:#fff; border-radius:16px; padding:20px 22px; border:1px solid #eef1f5; box-shadow:0 1px 2px rgba(15,23,42,.04), 0 6px 16px rgba(15,23,42,.05); display:flex; align-items:center; gap:16px; transition:transform .18s ease, box-shadow .18s ease; }
    .up-summary-card:hover { transform:translateY(-2px); box-shadow:0 4px 8px rgba(15,23,42,.06), 0 14px 28px rgba(15,23,42,.09); }
    .up-summary-icon { width:46px; height:46px; border-radius:13px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .up-summary-icon svg { width:21px; height:21px; stroke-width:2; }
    .up-ic-blue  { background:linear-gradient(135deg, rgba(2,62,138,.12), rgba(0,129,171,.12)); color:#023E8A; }
    .up-ic-amber { background:linear-gradient(135deg, rgba(232,163,23,.15), rgba(232,163,23,.06)); color:#E8A317; }
    .up-ic-green { background:linear-gradient(135deg, rgba(46,158,91,.14), rgba(46,158,91,.06)); color:#2E9E5B; }
    .up-summary-label { font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:.04em; color:#94a3b8; margin:0 0 4px; }
    .up-summary-value { font-size:24px; font-weight:800; letter-spacing:-.02em; color:#0f172a; margin:0; }

    /* Upload card */
    .up-card { background:#fff; border-radius:16px; padding:26px; border:1px solid #eef1f5; box-shadow:0 1px 2px rgba(15,23,42,.04), 0 6px 16px rgba(15,23,42,.05); margin-bottom:22px; }
    .up-card-body { padding:22px 26px; }
    .up-card-title { margin:0 0 4px; font-size:16.5px; font-weight:700; color:#0f172a; }
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
    .up-table { width:100%; min-width:860px; border-collapse:collapse; }
    .up-table thead th { background:#fafbfc; text-align:left; font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8; padding:13px 20px; border-bottom:1px solid #eef1f5; white-space:nowrap; }
    .up-table td { padding:14px 20px; font-size:13.3px; color:#1E293B; border-bottom:1px solid #f5f7fa; }
    .up-table tbody tr:nth-child(even) { background:#fbfcfd; }
    .up-table tbody tr:hover { background:rgba(0,129,171,.04); }
    .up-table tbody tr:last-child td { border-bottom:none; }
    .up-empty { text-align:center; padding:48px 20px; color:#94a3b8; font-size:13.5px; }
    .up-empty svg { width:32px; height:32px; color:#cbd5e1; margin-bottom:8px; stroke-width:1.5; }

    .up-badge { display:inline-flex; align-items:center; padding:4px 11px; border-radius:999px; font-size:11px; font-weight:700; white-space:nowrap; }
    .up-badge-success { background:rgba(46,158,91,.14); color:#2E9E5B; }
    .up-badge-error { background:rgba(192,57,43,.14); color:#C0392B; }
    .up-badge-warn { background:rgba(232,163,23,.14); color:#92660f; }

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
    .up-del-btn, .up-match-btn { border:none; border-radius:8px; width:32px; height:32px; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; transition:all .15s ease; }
    .up-del-btn { background:rgba(192,57,43,.1); color:#C0392B; }
    .up-del-btn:hover { background:rgba(192,57,43,.18); transform:translateY(-1px); }
    .up-match-btn { background:rgba(232,163,23,.14); color:#92660f; position:relative; }
    .up-match-btn:hover { background:rgba(232,163,23,.22); transform:translateY(-1px); }
    .up-match-btn svg { width:15px; height:15px; }
    .up-match-count {
        position:absolute; top:-5px; right:-5px; background:#C0392B; color:#fff;
        font-size:9px; font-weight:700; min-width:15px; height:15px; border-radius:999px;
        display:flex; align-items:center; justify-content:center; padding:0 3px;
        box-shadow:0 0 0 2px #fff;
    }

    .up-modal-overlay { display:none; position:fixed; inset:0; background:rgba(15,23,42,.5); backdrop-filter:blur(2px); align-items:center; justify-content:center; z-index:50; }
    .up-modal-overlay.show { display:flex; }
    .up-modal { background:#fff; border-radius:18px; padding:0; width:600px; max-width:92vw; box-shadow:0 24px 60px rgba(0,0,0,.25); max-height:90vh; overflow:hidden; display:flex; flex-direction:column; }
    .up-modal-header { background:linear-gradient(135deg, rgba(2,62,138,.06), rgba(0,129,171,.09)); padding:20px 24px; }
    .up-modal-header h3 { margin:0 0 4px; font-size:16.5px; font-weight:800; color:#023E8A; }
    .up-modal-header p { margin:0; font-size:12.5px; color:#64748B; }
    .up-modal-body { padding:20px 24px; overflow-y:auto; }
    .up-modal-footer { padding:16px 24px; border-top:1px solid #f1f5f9; }

    .alert-error { background:rgba(192,57,43,.08); border:1px solid rgba(192,57,43,.25); color:#C0392B; border-radius:10px; padding:12px 16px; font-size:13.5px; margin-bottom:18px; }
</style>

@error('file')
    <div class="alert-error">{{ $message }}</div>
@enderror

<div class="up-page-header">
    <div>
        <p class="up-page-subtitle" style="margin-top:0;">Upload file mentah transaksi dan lihat riwayat file yang sudah pernah diproses</p>
    </div>
    <a href="{{ route('transaksi.index') }}" class="up-btn up-btn-outline">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        Lihat Ringkasan
    </a>
</div>

<div class="up-summary-grid">
    <div class="up-summary-card">
        <div class="up-summary-icon up-ic-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
        <div>
            <p class="up-summary-label">Total Riwayat</p>
            <p class="up-summary-value">{{ number_format($riwayat->total()) }}</p>
        </div>
    </div>
    <div class="up-summary-card">
        <div class="up-summary-icon up-ic-amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></div>
        <div>
            <p class="up-summary-label">Nama Belum Dipetakan</p>
            <p class="up-summary-value">{{ number_format($unmatchedList->count()) }}</p>
        </div>
    </div>
    <div class="up-summary-card">
        <div class="up-summary-icon up-ic-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg></div>
        <div>
            <p class="up-summary-label">Alias Tersimpan</p>
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
        <span class="up-dropzone-hint">Format: .xlsx, .xls, .csv — maks 50MB per file</span>
        <input type="file" id="file-input-transaksi" accept=".xlsx,.xls,.csv" multiple style="display:none;">
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

@if ($unmatchedList->count() > 0)
<div class="surface-card">
    <div class="section-header-bar">
        <div class="section-header-bar-left">
            <div class="section-header-bar-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
            <div>
                <h2>Nama SPKLU Belum Dipetakan ({{ $unmatchedList->count() }})</h2>
                <p>Gabungan dari semua file — pakai ikon "Cocokkan Data" di tabel bawah untuk lihat per file spesifik.</p>
            </div>
        </div>
    </div>
    <div class="up-card-body">
        @foreach ($unmatchedList as $item)
            <form method="POST" action="{{ route('transaksi.alias.store') }}" class="up-alias-row">
                @csrf
                <input type="hidden" name="nama_asli" value="{{ $item->nama_asli }}">
                <div class="up-alias-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                </div>
                <div class="up-alias-name">
                    <strong>{{ $item->nama_asli }}</strong><br>
                    <span style="color:#94a3b8; font-size:11.5px;">{{ number_format($item->jumlah_baris_total) }} baris (akumulasi)</span>
                </div>
                <select name="spklu_id" required>
                    <option value="">Pilih SPKLU yang benar...</option>
                    @foreach ($spkluList as $s)<option value="{{ $s->id }}">{{ $s->nama }}</option>@endforeach
                </select>
                <button type="submit" class="up-btn up-btn-primary" style="padding:7px 12px; font-size:12px;">Simpan</button>
            </form>
        @endforeach
    </div>
</div>
@endif

<div class="surface-card">
    <div class="section-header-bar">
        <div class="section-header-bar-left">
            <div class="section-header-bar-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <div>
                <h2>Riwayat Upload</h2>
                <p>Ikon kuning = masih ada nama belum cocok khusus dari file itu. Hapus riwayat akan ikut menghapus data transaksi terkait.</p>
            </div>
        </div>
    </div>

    <div class="up-table-scroll">
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
                                    <form method="POST" action="{{ route('transaksi.upload.destroy', $r) }}"
                                          data-confirm="Riwayat &quot;{{ $r->nama_file }}&quot; beserta SEMUA data transaksi dari file ini akan terhapus permanen dan tidak bisa dibatalkan."
                                          data-confirm-title="Hapus riwayat ini?"
                                          data-confirm-type="danger">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="up-del-btn" title="Hapus riwayat + data">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
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

@if ($aliasList->count() > 0)
<div class="surface-card">
    <div class="section-header-bar">
        <div class="section-header-bar-left">
            <div class="section-header-bar-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
            </div>
            <div>
                <h2>Pemetaan Alias yang Sudah Selesai</h2>
                <p>Otomatis dipakai untuk upload berikutnya.</p>
            </div>
        </div>
    </div>
    <div class="up-table-scroll">
        <table class="up-table" style="min-width:0;">
            <thead><tr><th>Nama di File Sumber</th><th>Dipetakan ke SPKLU</th></tr></thead>
            <tbody>
                @foreach ($aliasList as $alias)
                    <tr><td>{{ $alias->nama_asli }}</td><td>{{ $alias->spklu->nama ?? '—' }}</td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Modal Cocokkan Data --}}
<div class="up-modal-overlay" id="modal-cocokkan">
    <div class="up-modal">
        <div class="up-modal-header">
            <h3 id="modal-cocokkan-title">Cocokkan Data</h3>
            <p>Nama SPKLU yang belum cocok, khusus dari file ini.</p>
        </div>
        <div class="up-modal-body">
            <div id="modal-cocokkan-list"></div>
        </div>
        <div class="up-modal-footer">
            <button type="button" class="up-btn" style="background:#fff; border:1px solid #e2e8f0; width:100%; justify-content:center;"
                    onclick="document.getElementById('modal-cocokkan').classList.remove('show')">Tutup</button>
        </div>
    </div>
</div>

<script>
let fileQueue = [];

const fileInput = document.getElementById('file-input-transaksi');
const dropzone = document.getElementById('dropzone-transaksi');
const queueList = document.getElementById('file-queue-list');
const btnSubmit = document.getElementById('btn-submit-upload');
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '{{ csrf_token() }}';

function formatBytes(bytes) {
    if (!bytes) return '';
    const units = ['B', 'KB', 'MB', 'GB'];
    let i = 0;
    let n = bytes;
    while (n >= 1024 && i < units.length - 1) { n /= 1024; i++; }
    return n.toFixed(i === 0 ? 0 : 1) + ' ' + units[i];
}

function addFilesToQueue(fileList) {
    const newItems = Array.from(fileList).map(file => ({ file, status: 'menunggu', progress: 0 }));
    fileQueue = fileQueue.concat(newItems);
    renderQueue();
    document.getElementById('file-name-label-transaksi').textContent =
        fileQueue.length === 1 ? fileQueue[0].file.name : `${fileQueue.length} file dipilih`;
    btnSubmit.disabled = fileQueue.length === 0;
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
        title: gagal === 0 ? 'Semua file berhasil diproses!' : 'Sebagian file gagal diproses',
        html: `<b>${sukses}</b> berhasil${gagal > 0 ? `, <b style="color:#C0392B">${gagal}</b> gagal` : ''}. Halaman akan dimuat ulang.`,
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
                item.message = 'Respons server tidak dikenali';
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

function openMatchModal(btn) {
    const items = JSON.parse(btn.dataset.unmatched);
    const filename = btn.dataset.filename;

    document.getElementById('modal-cocokkan-title').textContent = 'Cocokkan Data — ' + filename;

    document.getElementById('modal-cocokkan-list').innerHTML = items.map(item => `
        <form method="POST" action="{{ route('transaksi.alias.store') }}" class="up-alias-row">
            <input type="hidden" name="_token" value="${csrfToken}">
            <input type="hidden" name="nama_asli" value="${item.nama}">
            <div class="up-alias-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/></svg>
            </div>
            <div class="up-alias-name">
                <strong>${item.nama}</strong><br>
                <span style="color:#94a3b8; font-size:11.5px;">${item.jumlah.toLocaleString('id-ID')} baris di file ini</span>
            </div>
            <select name="spklu_id" required>
                <option value="">Pilih SPKLU yang benar...</option>
                @foreach ($spkluList as $s)<option value="{{ $s->id }}">{{ $s->nama }}</option>@endforeach
            </select>
            <button type="submit" class="up-btn up-btn-primary" style="padding:7px 12px; font-size:12px;">Simpan</button>
        </form>
    `).join('');

    document.getElementById('modal-cocokkan').classList.add('show');
}
</script>

@endsection