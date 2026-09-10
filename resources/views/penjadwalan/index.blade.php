@extends('layouts.app')

@section('breadcrumb', 'Penjadwalan')
@section('page-title', 'Penjadwalan')

@section('content')

<style>
    .jdw-page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; flex-wrap:wrap; gap:10px; }
    .jdw-page-subtitle { color:#64748B; margin:0; font-size:13.5px; }
    .jdw-btn { display:inline-flex; align-items:center; gap:7px; border:none; border-radius:9px; font-size:13.3px; font-weight:700; padding:10px 18px; cursor:pointer; transition:all .15s ease; }
    .jdw-btn svg { width:15px; height:15px; stroke-width:2.1; }
    .jdw-btn-primary { background:linear-gradient(135deg,#023E8A,#0081AB); color:#fff; box-shadow:0 2px 10px rgba(2,62,138,.25); }
    .jdw-btn-primary:hover { transform:translateY(-1px); box-shadow:0 4px 14px rgba(2,62,138,.32); }
    .jdw-filters { display:flex; gap:10px; align-items:center; padding:16px 24px 20px; }
    .jdw-select { padding:9px 30px 9px 14px; border-radius:9px; border:1px solid #e2e8f0; font-size:13px; font-weight:500; background:#fff; color:#1E293B; cursor:pointer; appearance:none; min-width:170px;
        background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6' fill='none'%3E%3Cpath d='M1 1L5 5L9 1' stroke='%2364748B' stroke-width='1.6' stroke-linecap='round'/%3E%3C/svg%3E");
        background-repeat:no-repeat; background-position:right 12px center; }
    .jdw-select:focus { outline:none; border-color:#0081AB; box-shadow:0 0 0 3px rgba(0,129,171,.14); }
    .jdw-table-wrap { overflow-x:auto; -webkit-overflow-scrolling:touch; }
    .jdw-table { width:100%; border-collapse:collapse; min-width:900px; }
    .jdw-table thead th { background:#fafbfc; text-align:left; font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8; padding:13px 20px; border-bottom:1px solid #eef1f5; white-space:nowrap; }
    .jdw-table td { padding:14px 20px; font-size:13.3px; color:#1E293B; border-bottom:1px solid #f5f7fa; }
    .jdw-table tbody tr:nth-child(even) { background:#fbfcfd; }
    .jdw-table tbody tr:hover { background:rgba(0,129,171,.05); }
    .jdw-table tbody tr:last-child td { border-bottom:none; }
    .jdw-empty { text-align:center; padding:48px 20px; color:#94a3b8; font-size:13.5px; }
    .jdw-pill { display:inline-flex; align-items:center; padding:4px 11px; border-radius:999px; font-size:11px; font-weight:700; white-space:nowrap; }
    .jdw-mode-online { background:rgba(46,158,91,.14); color:#2E9E5B; }
    .jdw-mode-offline { background:rgba(2,62,138,.12); color:#023E8A; }
    .jdw-status-terjadwal { background:rgba(0,129,171,.12); color:#0081AB; }
    .jdw-status-berlangsung { background:rgba(232,163,23,.14); color:#92660f; }
    .jdw-status-selesai { background:rgba(46,158,91,.14); color:#2E9E5B; }
    .jdw-status-batal { background:rgba(192,57,43,.12); color:#C0392B; }
    .jdw-action-group { display:flex; gap:6px; }
    .jdw-icon-btn { width:32px; height:32px; border-radius:8px; border:none; background:rgba(0,129,171,.1); color:#023E8A; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; transition:background .15s ease; text-decoration:none; }
    .jdw-icon-btn:hover { background:rgba(0,129,171,.18); }
    .jdw-icon-btn svg { width:15px; height:15px; stroke-width:2; }
    .jdw-icon-btn-danger { background:rgba(192,57,43,.1); color:#C0392B; }
    .jdw-icon-btn-danger:hover { background:rgba(192,57,43,.18); }
</style>

<div class="jdw-page-header">
    <p class="jdw-page-subtitle">Daftar jadwal kunjungan/pertemuan terkait lokasi Probabilitas</p>
    @can('create', \App\Models\Jadwal::class)
        <a href="{{ route('penjadwalan.create') }}" class="jdw-btn jdw-btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Buat Jadwal
        </a>
    @endcan
</div>

<div class="surface-card">
    <div class="section-header-bar">
        <div class="section-header-bar-left">
            <div class="section-header-bar-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <div><h2>Daftar Jadwal</h2><p>{{ $jadwals->total() }} jadwal tercatat</p></div>
        </div>
    </div>

    <form method="GET" class="jdw-filters">
        <select name="status" class="jdw-select" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            @foreach (['terjadwal', 'berlangsung', 'selesai', 'batal'] as $s)
                <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
    </form>

    <div class="jdw-table-wrap">
        <table class="jdw-table">
            <thead>
                <tr><th>Permohonan</th><th>Waktu</th><th>Mode</th><th>Lokasi</th><th>PJ</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse ($jadwals as $j)
                    <tr>
                        <td>
                            <div class="row-icon-cell">
                                <div class="row-icon-box"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/></svg></div>
                                <span style="font-weight:600;">{{ $j->probabilitas?->lokasi ?? '—' }}</span>
                            </div>
                        </td>
                        <td style="white-space:nowrap;">{{ $j->waktu_mulai->translatedFormat('d M Y, H:i') }}</td>
                        <td><span class="jdw-pill {{ $j->mode === 'online' ? 'jdw-mode-online' : 'jdw-mode-offline' }}">{{ ucfirst($j->mode) }}</span></td>
                        <td>{{ $j->lokasi ?? '—' }}</td>
                        <td>{{ $j->penanggungJawab->name ?? '—' }}</td>
                        <td><span class="jdw-pill jdw-status-{{ $j->status }}">{{ ucfirst($j->status) }}</span></td>
                        <td>
                            <div class="jdw-action-group">
                                @can('update', $j)
                                    <a href="{{ route('penjadwalan.edit', $j) }}" class="jdw-icon-btn" title="Edit">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                    </a>
                                @endcan
                                @can('delete', $j)
                                    <form method="POST" action="{{ route('penjadwalan.destroy', $j) }}"
                                          data-confirm="Jadwal &quot;{{ $j->judul }}&quot; akan dihapus permanen."
                                          data-confirm-title="Hapus jadwal ini?"
                                          data-confirm-type="danger">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="jdw-icon-btn jdw-icon-btn-danger" title="Hapus">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="jdw-empty">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" style="display:block; margin:0 auto 8px; width:32px; height:32px; color:#cbd5e1; stroke-width:1.5;"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            Belum ada jadwal.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding:16px 24px;">{{ $jadwals->links() }}</div>
</div>

@endsection