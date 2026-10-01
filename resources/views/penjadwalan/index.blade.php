@extends('layouts.app')

@section('breadcrumb', 'Penjadwalan')
@section('page-title', 'Daftar Jadwal')

@section('content')

<style>
    .jdi-page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; padding-bottom:16px; border-bottom:1px solid #E2E8F0; flex-wrap:wrap; gap:16px; }
    .jdi-page-header h1 { font-size:22px; font-weight:800; color:#1B2559; margin:0 0 4px; letter-spacing:-0.015em; }
    .jdi-page-header p { color:#64748B; margin:0; font-size:13.5px; }
    .jdi-btn { display:inline-flex; align-items:center; gap:7px; border:none; border-radius:10px; font-size:13.3px; font-weight:700; padding:10px 18px; cursor:pointer; text-decoration:none; transition:all .15s ease; }
    .jdi-btn svg { width:15px; height:15px; stroke-width:2.1; }
    .jdi-btn-primary { background:#023E8A; color:#fff; box-shadow:0 6px 16px rgba(2,62,138,.25); }
    .jdi-btn-primary:hover { background:#002D66; transform:translateY(-1px); box-shadow:0 8px 20px rgba(2,62,138,.32); }

    .jdi-card { background:#fff; border-radius:16px; box-shadow:0 1px 3px rgba(15,23,42,.06), 0 8px 24px rgba(15,23,42,.06); overflow:hidden; }
    .jdi-card-head { display:flex; align-items:center; gap:10px; padding:20px 22px; border-bottom:1px solid #F1F5F9; background:#FFFFFF; }
    .jdi-icon-box { width:34px; height:34px; min-width:34px; border-radius:10px; background:linear-gradient(135deg, #023E8A, #0081AB); color:#fff; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .jdi-icon-box svg { width:17px; height:17px; stroke-width:2; }
    .jdi-card-head h2 { font-size:15px; margin:0; color:#1B2559; font-weight:800; }
    .jdi-card-head p { font-size:12.5px; margin:2px 0 0; color:#94A3B8; }

    .jdi-filters { display:flex; gap:10px; align-items:center; padding:16px 22px; }
    .jdi-select { padding:9px 30px 9px 14px; border-radius:9px; border:1px solid #e2e8f0; font-size:13px; font-weight:500; background:#fff; color:#1E293B; cursor:pointer; appearance:none; min-width:170px;
        background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6' fill='none'%3E%3Cpath d='M1 1L5 5L9 1' stroke='%2364748B' stroke-width='1.6' stroke-linecap='round'/%3E%3C/svg%3E");
        background-repeat:no-repeat; background-position:right 12px center; }
    .jdi-select:focus { outline:none; border-color:#0081AB; box-shadow:0 0 0 3px rgba(0,129,171,.14); }

    .jdi-table-wrap { overflow-x:auto; }
    .jdi-table { width:100%; border-collapse:collapse; min-width:900px; }
    .jdi-table thead th { background:#FAFBFC; text-align:left; font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#94A3B8; padding:13px 22px; border-bottom:1px solid #EEF1F5; white-space:nowrap; }
    .jdi-table td { padding:14px 22px; font-size:13.3px; color:#1E293B; border-bottom:1px solid #F5F7FA; }
    .jdi-table tbody tr:nth-child(even) { background:#FBFCFD; }
    .jdi-table tbody tr:hover { background:rgba(0,129,171,.04); }
    .jdi-table tbody tr:last-child td { border-bottom:none; }
    .jdi-empty { text-align:center; padding:48px 20px; color:#94A3B8; font-size:13.5px; }
    .jdi-empty svg { display:block; margin:0 auto 8px; width:32px; height:32px; color:#CBD5E1; stroke-width:1.5; }

    .jdi-pill { display:inline-flex; align-items:center; padding:4px 11px; border-radius:999px; font-size:11px; font-weight:700; white-space:nowrap; }
    .jdi-mode-online { background:rgba(46,158,91,.14); color:#2E9E5B; }
    .jdi-mode-offline { background:rgba(2,62,138,.1); color:#023E8A; }
    .jdi-status-terjadwal { background:rgba(2,62,138,.1); color:#023E8A; }
    .jdi-status-berlangsung { background:rgba(232,163,23,.14); color:#92660F; }
    .jdi-status-selesai { background:rgba(46,158,91,.14); color:#2E9E5B; }
    .jdi-status-batal { background:rgba(192,57,43,.12); color:#C0392B; }

    .jdi-lokasi-cell { display:flex; align-items:center; gap:10px; }
    .jdi-lokasi-icon { width:30px; height:30px; border-radius:8px; background:#F1F5F9; color:#64748B; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .jdi-lokasi-icon svg { width:14px; height:14px; }

    .jdi-action-group { display:flex; gap:6px; justify-content:flex-end; }
    .jdi-icon-btn { width:32px; height:32px; border-radius:8px; border:none; background:rgba(245,158,11,.12); color:#D97706; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; text-decoration:none; transition:all .15s ease; }
    .jdi-icon-btn:hover { background:rgba(245,158,11,.22); color:#B45309; }
    .jdi-icon-btn svg { width:15px; height:15px; stroke-width:2.2; }
    .jdi-icon-btn-danger { background:rgba(192,57,43,.08); color:#C0392B; }
    .jdi-icon-btn-danger:hover { background:rgba(192,57,43,.18); color:#962D22; }

    /* ===== Kalender besar ===== */
    .jdi-cal-card {
        margin-bottom: 24px;
        background: #fff;
        border-radius: 16px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 1px 3px rgba(15,23,42,.04), 0 6px 18px rgba(15,23,42,.04);
        overflow: hidden;
    }
    .jdi-cal-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 22px;
        background: #FFFFFF;
        border-bottom: 1px solid #E2E8F0;
        flex-wrap: wrap;
        gap: 12px;
    }
    .jdi-cal-head-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .jdi-cal-head-left strong {
        font-size: 16.5px;
        color: #1B2559;
        font-weight: 800;
        display: block;
        line-height: 1.25;
    }
    .jdi-cal-head-left span {
        font-size: 12px;
        color: #64748B;
        margin-top: 2px;
        display: block;
    }
    .jdi-cal-nav-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .jdi-cal-nav-btn {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        border: 1px solid #E2E8F0;
        background: #FFFFFF;
        color: #475569;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .jdi-cal-nav-btn:hover {
        background: #F8FAFC;
        color: #023E8A;
        border-color: #CBD5E1;
    }
    .jdi-cal-nav-btn svg {
        width: 16px;
        height: 16px;
    }
    .jdi-cal-nav-today {
        padding: 0 14px;
        height: 34px;
        border-radius: 9px;
        border: 1px solid #BAE6FD;
        background: #EFF6FB;
        color: #023E8A;
        font-size: 12.5px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.15s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .jdi-cal-nav-today:hover {
        background: #023E8A;
        color: #FFFFFF;
        border-color: #023E8A;
    }

    .jdi-cal-dow-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        background: #E2E8F0;
        gap: 1px;
        border-bottom: 1px solid #E2E8F0;
    }
    .jdi-cal-dow {
        background: #F8FAFC;
        text-align: center;
        font-size: 11.5px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 12px 6px;
    }
    .jdi-cal-dow.weekend {
        color: #94A3B8;
    }

    .jdi-cal-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        background: #E2E8F0;
        gap: 1px;
    }
    .jdi-cal-cell {
        min-height: 104px;
        background: #FFFFFF;
        padding: 9px 10px;
        cursor: pointer;
        transition: background 0.12s ease;
        display: flex;
        flex-direction: column;
        user-select: none;
    }
    .jdi-cal-cell:hover {
        background: #F0F7FF;
    }
    .jdi-cal-cell.weekend {
        background: #FAFBFC;
    }
    .jdi-cal-cell.weekend:hover {
        background: #F0F7FF;
    }
    .jdi-cal-cell.other-month {
        background: #F8FAFC;
        cursor: pointer;
    }
    .jdi-cal-cell.other-month:hover {
        background: #F1F5F9;
    }
    .jdi-cal-cell.other-month .num {
        color: #CBD5E1;
        font-weight: 500;
    }
    .jdi-cal-cell.selected {
        background: #EFF6FB;
        box-shadow: inset 0 0 0 2px #0081AB;
    }
    .num-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 5px;
    }
    .jdi-cal-cell .num {
        font-size: 13px;
        font-weight: 700;
        color: #1E293B;
        width: 26px;
        height: 26px;
        border-radius: 7px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .jdi-cal-cell.selected:not(.hari-ini) .num {
        background: #023E8A;
        color: #FFFFFF;
    }
    .jdi-cal-cell.hari-ini .num {
        background: linear-gradient(135deg, #023E8A, #0081AB);
        color: #FFFFFF;
        font-weight: 800;
    }
    .today-tag {
        font-size: 9.5px;
        font-weight: 700;
        color: #023E8A;
        background: #EFF6FB;
        border: 1px solid #BAE6FD;
        padding: 1px 6px;
        border-radius: 999px;
        letter-spacing: -0.01em;
    }
    .jdi-cal-events {
        display: flex;
        flex-direction: column;
        gap: 3px;
        flex: 1;
    }
    .jdi-cal-time-pill {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 10.5px;
        font-weight: 700;
        padding: 3px 6px;
        border-radius: 6px;
        line-height: 1.25;
        background: rgba(2, 62, 138, 0.08);
        color: #023E8A;
        border: 1px solid rgba(2, 62, 138, 0.16);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        transition: transform 0.1s ease;
    }
    .jdi-cal-time-pill:hover {
        transform: translateY(-1px);
    }
    .jdi-cal-time-pill.offline {
        background: rgba(46, 158, 91, 0.1);
        color: #15803D;
        border-color: rgba(46, 158, 91, 0.22);
    }
    .jdi-pill-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #0081AB;
        flex-shrink: 0;
    }
    .jdi-cal-time-pill.offline .jdi-pill-dot {
        background: #2E9E5B;
    }
    .jdi-cal-more {
        font-size: 10px;
        font-weight: 700;
        color: #64748B;
        background: #EEF2F6;
        border-radius: 5px;
        padding: 2px 6px;
        margin-top: 2px;
        display: inline-block;
        align-self: flex-start;
    }

    .jdi-hariini-title { display:flex; align-items:center; justify-content:space-between; padding:18px 22px 10px; }
    .jdi-hariini-title strong { font-size:15px; font-weight:700; color:#1B2559; }
    .jdi-hariini-title span { font-size:12px; color:#94A3B8; }
    .jdi-hariini-list { padding:0 22px 22px; display:flex; flex-direction:column; gap:12px; }
    .jdi-hariini-item {
        display:flex; align-items:center; gap:16px;
        border:1px solid #E2E8F0; border-left:4px solid #023E8A; border-radius:12px;
        padding:14px 18px; background:#fff;
    }
    .jdi-hariini-item.offline { border-left-color:#2E9E5B; }
    .jdi-hariini-time {
        flex-shrink:0; width:56px; text-align:center;
        background:rgba(2,62,138,.08); color:#023E8A; font-weight:700; font-size:13px;
        padding:8px 6px; border-radius:999px; white-space:nowrap;
    }
    .jdi-hariini-item.offline .jdi-hariini-time { background:#EAFAF1; color:#2E9E5B; }
    .jdi-hariini-body { flex:1; min-width:0; }
    .jdi-hariini-body strong { font-size:14px; color:#1B2559; display:block; }
    .jdi-hariini-desc { font-size:12px; color:#94A3B8; display:block; margin-top:2px; }
    .jdi-hariini-badge {
        flex-shrink:0; font-size:11.5px; font-weight:700; padding:5px 14px; border-radius:999px;
        background:rgba(2,62,138,.1); color:#023E8A; white-space:nowrap;
    }
    .jdi-hariini-item.offline .jdi-hariini-badge { background:#DCFCE7; color:#15803D; }
    .jdi-hariini-empty { text-align:center; color:#94A3B8; font-size:13px; padding:24px; }

    /* ===== Modal detail tanggal ===== */
    .jdi-modal-overlay {
        display:none; position:fixed; inset:0; background:rgba(15,23,42,.5); backdrop-filter:blur(2px);
        z-index:60; align-items:center; justify-content:center; padding:20px;
    }
    .jdi-modal-overlay.open { display:flex; }
    .jdi-modal-box { background:#fff; border-radius:16px; width:480px; max-width:100%; max-height:82vh; display:flex; flex-direction:column; box-shadow:0 30px 70px rgba(1,26,64,.3); overflow:hidden; }
    .jdi-modal-head { display:flex; align-items:center; justify-content:space-between; padding:18px 22px; border-bottom:1px solid #F1F5F9; background:#FFFFFF; }
    .jdi-modal-head strong { font-size:15px; color:#1B2559; display:block; }
    .jdi-modal-head span { font-size:12px; color:#94A3B8; }
    .jdi-modal-close { background:none; border:none; font-size:20px; line-height:1; color:#94A3B8; cursor:pointer; padding:4px; }
    .jdi-modal-close:hover { color:#475569; }
    .jdi-modal-body { padding:16px 22px 20px; overflow-y:auto; display:flex; flex-direction:column; gap:12px; }
    .jdi-modal-empty { text-align:center; color:#94A3B8; font-size:13px; padding:28px 10px; }
    .jdi-modal-footer { padding:14px 22px; border-top:1px solid #F1F5F9; }
    .jdi-modal-footer a {
        display:flex; align-items:center; justify-content:center; gap:7px; width:100%;
        padding:11px; border-radius:10px; background:#023E8A; color:#fff;
        font-weight:700; font-size:13.5px; text-decoration:none; transition: background .15s ease;
    }
    .jdi-modal-footer a:hover { background:#002D66; }
    .jdi-modal-footer svg { width:15px; height:15px; stroke-width:2.2; }
</style>

<div class="jdi-page-header">
    <div>
        <h1>Penjadwalan</h1>
        <p>Kelola jadwal kunjungan Anda</p>
    </div>
    @can('create', \App\Models\Jadwal::class)
        <a href="{{ route('penjadwalan.create') }}" class="jdi-btn jdi-btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Buat Jadwal
        </a>
    @endcan
</div>

{{-- ===== KALENDER BESAR + JADWAL HARI INI ===== --}}
<div class="jdi-card jdi-cal-card">
    <div class="jdi-cal-head">
        <div class="jdi-cal-head-left">
            <div class="jdi-icon-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <div>
                <strong id="cal-besar-label"></strong>
                <span>Pilih tanggal untuk melihat detail atau membuat agenda kunjungan</span>
            </div>
        </div>
        <div class="jdi-cal-nav-group">
            <button type="button" class="jdi-cal-nav-btn" onclick="ubahBulanBesar(-1)" title="Bulan Sebelumnya">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            </button>
            <button type="button" class="jdi-cal-nav-today" onclick="pilihHariIni()">Hari Ini</button>
            <button type="button" class="jdi-cal-nav-btn" onclick="ubahBulanBesar(1)" title="Bulan Berikutnya">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
        </div>
    </div>

    <div class="jdi-cal-dow-grid">
        @foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $idx => $d)
            <div class="jdi-cal-dow {{ $idx >= 5 ? 'weekend' : '' }}">{{ $d }}</div>
        @endforeach
    </div>
    <div class="jdi-cal-grid" id="cal-besar-grid"></div>
</div>

{{-- ===== MODAL DETAIL TANGGAL (muncul saat klik cell kalender) ===== --}}
<div class="jdi-modal-overlay" id="modal-detail-tanggal">
    <div class="jdi-modal-box">
        <div class="jdi-modal-head">
            <div>
                <strong id="modal-tanggal-judul"></strong>
                <span id="modal-tanggal-jumlah"></span>
            </div>
            <button type="button" class="jdi-modal-close" onclick="tutupModalDetail()">&times;</button>
        </div>
        <div class="jdi-modal-body" id="modal-tanggal-list"></div>
        <div class="jdi-modal-footer">
            <a href="#" id="modal-tambah-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Jadwal di Tanggal Ini
            </a>
        </div>
    </div>
</div>

<div class="jdi-card">
    <div class="jdi-card-head">
        <div class="jdi-icon-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        </div>
        <div>
            <h2>Daftar Jadwal</h2>
            <p>{{ $jadwals->total() }} jadwal tercatat</p>
        </div>
    </div>

    <form method="GET" class="jdi-filters">
        <select name="status" class="jdi-select" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            @foreach (['terjadwal', 'berlangsung', 'selesai', 'batal'] as $s)
                <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
    </form>

    <div class="jdi-table-wrap">
        <table class="jdi-table">
            <thead>
                <tr><th>Permohonan</th><th>Waktu</th><th>Mode</th><th>Lokasi</th><th>PJ</th><th>Status</th><th style="text-align:right">Aksi</th></tr>
            </thead>
            <tbody>
                @forelse ($jadwals as $j)
                    <tr>
                        <td>
                            <div class="jdi-lokasi-cell">
                                <div class="jdi-lokasi-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                                </div>
                                <span style="font-weight:600;">{{ $j->probabilitas?->lokasi ?? '—' }}</span>
                            </div>
                        </td>
                        <td style="white-space:nowrap;">{{ $j->waktu_mulai->translatedFormat('d M Y, H:i') }}</td>
                        <td><span class="jdi-pill {{ $j->mode === 'online' ? 'jdi-mode-online' : 'jdi-mode-offline' }}">{{ ucfirst($j->mode) }}</span></td>
                        <td>
                            @if ($j->mode === 'online')
                                {{ $j->platform ?? '—' }}
                                @if ($j->link_pertemuan)
                                    <a href="{{ $j->link_pertemuan }}" target="_blank" rel="noopener" style="margin-left:6px;color:#023E8A;font-size:12px;">Buka link</a>
                                @endif
                            @else
                                {{ $j->lokasi ?? '—' }}
                            @endif
                        </td>
                        <td>{{ $j->penanggungJawab->name ?? '—' }}</td>
                        <td><span class="jdi-pill jdi-status-{{ $j->status }}">{{ ucfirst($j->status) }}</span></td>
                        <td style="text-align:right">
                            <div class="jdi-action-group">
                                @can('update', $j)
                                    <a href="{{ route('penjadwalan.edit', $j) }}" class="jdi-icon-btn" title="Ubah Jadwal">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4Z"/></svg>
                                    </a>
                                @endcan
                                @can('delete', $j)
                                    <form method="POST" action="{{ route('penjadwalan.destroy', $j) }}"
                                          data-confirm="Jadwal &quot;{{ $j->judul }}&quot; akan dihapus permanen."
                                          data-confirm-title="Hapus jadwal ini?"
                                          data-confirm-type="danger">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="jdi-icon-btn jdi-icon-btn-danger" title="Hapus Jadwal">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="jdi-empty">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            Belum ada jadwal.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding:16px 22px;">{{ $jadwals->links() }}</div>
</div>

<script>
const jadwalSebulan = @json($jadwalSebulan);
const bulanAwal = "{{ $bulanTampil->format('Y-m-01') }}";
const URL_BUAT_JADWAL = "{{ route('penjadwalan.create') }}";
const URL_EDIT_JADWAL_BASE = "{{ url('penjadwalan') }}"; // + /{id}/edit

let besarCalCursor = new Date(bulanAwal);
let tanggalTerpilih = null;
const tanggalHariIni = new Date().toISOString().slice(0, 10); // FIXED, tidak berubah walau kalender dinavigasi

const namaBulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
const namaHari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
const MAKS_PIL_PER_SEL = 2;

function jumlahHari(y, m) { return new Date(y, m + 1, 0).getDate(); }
function hariPertama(y, m) { const d = new Date(y, m, 1).getDay(); return d === 0 ? 6 : d - 1; }

function pilihTanggalOtherMonth(delta, tgl) {
    besarCalCursor.setMonth(besarCalCursor.getMonth() + delta);
    const y = besarCalCursor.getFullYear(), m = besarCalCursor.getMonth();
    const iso = `${y}-${String(m+1).padStart(2,'0')}-${String(tgl).padStart(2,'0')}`;
    tanggalTerpilih = iso;
    renderCalBesar();
    bukaModalDetail(iso);
}

function renderCalBesar() {
    const y = besarCalCursor.getFullYear(), m = besarCalCursor.getMonth();
    document.getElementById('cal-besar-label').textContent = `${namaBulan[m]} ${y}`;
    const grid = document.getElementById('cal-besar-grid');
    grid.innerHTML = '';

    const offset = hariPertama(y, m);
    const prevYear = m === 0 ? y - 1 : y;
    const prevMonth = m === 0 ? 11 : m - 1;
    const prevMonthTotalDays = jumlahHari(prevYear, prevMonth);

    // Leading days from previous month
    for (let i = offset - 1; i >= 0; i--) {
        const tglPrev = prevMonthTotalDays - i;
        const colIdx = (offset - 1 - i) % 7;
        const isWeekend = colIdx === 5 || colIdx === 6;
        grid.innerHTML += `
            <div class="jdi-cal-cell other-month ${isWeekend ? 'weekend' : ''}" onclick="pilihTanggalOtherMonth(-1, ${tglPrev})">
                <div class="num-wrapper"><span class="num">${tglPrev}</span></div>
            </div>`;
    }

    const totalHari = jumlahHari(y, m);
    for (let tgl = 1; tgl <= totalHari; tgl++) {
        const iso = `${y}-${String(m+1).padStart(2,'0')}-${String(tgl).padStart(2,'0')}`;
        const dayOfWeek = (offset + tgl - 1) % 7;
        const isWeekend = dayOfWeek === 5 || dayOfWeek === 6;
        const itemHariItu = jadwalSebulan.filter(j => j.tanggal === iso).sort((a,b) => a.jam.localeCompare(b.jam));
        const kelasSelected = iso === tanggalTerpilih ? 'selected' : '';
        const kelasHariIni = iso === tanggalHariIni ? 'hari-ini' : '';
        const kelasWeekend = isWeekend ? 'weekend' : '';

        let pilHtml = itemHariItu.slice(0, MAKS_PIL_PER_SEL).map(j =>
            `<span class="jdi-cal-time-pill ${j.mode}" title="${j.jam} · ${j.lokasi_nama} (${j.mode === 'online' ? 'Online' : 'Offline'})">
                <span class="jdi-pill-dot"></span>
                <span>${j.jam}</span>
                <span style="opacity:0.85; font-size:10px;">${j.mode === 'online' ? 'On' : 'Off'}</span>
            </span>`
        ).join('');

        if (itemHariItu.length > MAKS_PIL_PER_SEL) {
            pilHtml += `<div class="jdi-cal-more">+${itemHariItu.length - MAKS_PIL_PER_SEL} lagi</div>`;
        }

        grid.innerHTML += `
            <div class="jdi-cal-cell ${kelasSelected} ${kelasHariIni} ${kelasWeekend}" onclick="pilihTanggal('${iso}')">
                <div class="num-wrapper">
                    <span class="num">${tgl}</span>
                    ${iso === tanggalHariIni ? '<span class="today-tag">Hari ini</span>' : ''}
                </div>
                <div class="jdi-cal-events">${pilHtml}</div>
            </div>`;
    }

    // Trailing days for next month to complete the row
    const totalRendered = offset + totalHari;
    const remaining = (7 - (totalRendered % 7)) % 7;
    for (let i = 1; i <= remaining; i++) {
        const colIdx = (totalRendered + i - 1) % 7;
        const isWeekend = colIdx === 5 || colIdx === 6;
        grid.innerHTML += `
            <div class="jdi-cal-cell other-month ${isWeekend ? 'weekend' : ''}" onclick="pilihTanggalOtherMonth(1, ${i})">
                <div class="num-wrapper"><span class="num">${i}</span></div>
            </div>`;
    }
}

// ===== Klik tanggal kalender: highlight + buka modal detail =====
function pilihTanggal(iso) {
    tanggalTerpilih = iso;
    renderCalBesar();
    bukaModalDetail(iso);
}

function bukaModalDetail(iso) {
    const d = new Date(iso);
    const items = jadwalSebulan.filter(j => j.tanggal === iso).sort((a,b) => a.jam.localeCompare(b.jam));

    document.getElementById('modal-tanggal-judul').textContent =
        `${namaHari[d.getDay()]}, ${d.getDate()} ${namaBulan[d.getMonth()]} ${d.getFullYear()}`;
    document.getElementById('modal-tanggal-jumlah').textContent =
        items.length === 0 ? 'Tidak ada jadwal' : `${items.length} jadwal tercatat`;

    const list = document.getElementById('modal-tanggal-list');
    if (items.length === 0) {
        list.innerHTML = `<div class="jdi-modal-empty">Belum ada jadwal untuk tanggal ini.</div>`;
    } else {
        list.innerHTML = items.map(j => `
            <a href="${URL_EDIT_JADWAL_BASE}/${j.id}/edit" class="jdi-hariini-item ${j.mode}" style="text-decoration:none; cursor:pointer;">
                <div class="jdi-hariini-time">${j.jam}</div>
                <div class="jdi-hariini-body">
                    <strong>${j.lokasi_nama}</strong>
                    <span class="jdi-hariini-desc">${j.deskripsi ? j.deskripsi : 'Klik untuk edit jadwal ini'}</span>
                </div>
                <span class="jdi-hariini-badge">${j.mode === 'online' ? 'Online' : 'Offline'}</span>
            </a>
        `).join('');
    }

    document.getElementById('modal-tambah-link').href = `${URL_BUAT_JADWAL}?tanggal=${iso}`;
    document.getElementById('modal-detail-tanggal').classList.add('open');
}

function tutupModalDetail() {
    document.getElementById('modal-detail-tanggal').classList.remove('open');
}

document.getElementById('modal-detail-tanggal').addEventListener('click', function (e) {
    if (e.target === this) tutupModalDetail();
});

function ubahBulanBesar(delta) { besarCalCursor.setMonth(besarCalCursor.getMonth() + delta); renderCalBesar(); }

function pilihHariIni() {
    besarCalCursor = new Date();
    tanggalTerpilih = tanggalHariIni;
    renderCalBesar();
}

document.addEventListener('DOMContentLoaded', () => {
    renderCalBesar();
});
</script>
@endsection