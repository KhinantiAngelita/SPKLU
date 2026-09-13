@extends('layouts.app')

@section('breadcrumb', 'Penjadwalan')
@section('page-title', 'Daftar Jadwal')

@section('content')

<style>
    .jdi-page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; flex-wrap:wrap; gap:10px; }
    .jdi-page-header h1 { font-size:20px; font-weight:700; color:#0F172A; margin:0; }
    .jdi-page-header p { color:#64748B; margin:4px 0 0; font-size:13.5px; }
    .jdi-btn { display:inline-flex; align-items:center; gap:7px; border:none; border-radius:9px; font-size:13.3px; font-weight:700; padding:10px 18px; cursor:pointer; text-decoration:none; }
    .jdi-btn svg { width:15px; height:15px; stroke-width:2.1; }
    .jdi-btn-primary { background:#F5B301; color:#78350F; box-shadow:0 2px 10px rgba(245,179,1,.25); }
    .jdi-btn-primary:hover { background:#E5A700; transform:translateY(-1px); }

    .jdi-card { background:#fff; border-radius:14px; box-shadow:0 1px 3px rgba(15,23,42,.08); }
    .jdi-card-head { display:flex; align-items:center; gap:10px; padding:20px 22px; border-bottom:1px solid #F1F5F9; }
    .jdi-icon-box { width:34px; height:34px; border-radius:9px; background:#DBEAFE; color:#1D4ED8; display:flex; align-items:center; justify-content:center; }
    .jdi-icon-box svg { width:17px; height:17px; }
    .jdi-card-head h2 { font-size:15px; margin:0; color:#0F172A; }
    .jdi-card-head p { font-size:12.5px; margin:2px 0 0; color:#94A3B8; }

    .jdi-filters { display:flex; gap:10px; align-items:center; padding:16px 22px; }
    .jdi-select { padding:9px 30px 9px 14px; border-radius:9px; border:1px solid #e2e8f0; font-size:13px; font-weight:500; background:#fff; color:#1E293B; cursor:pointer; appearance:none; min-width:170px;
        background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6' fill='none'%3E%3Cpath d='M1 1L5 5L9 1' stroke='%2364748B' stroke-width='1.6' stroke-linecap='round'/%3E%3C/svg%3E");
        background-repeat:no-repeat; background-position:right 12px center; }
    .jdi-select:focus { outline:none; border-color:#1D4ED8; box-shadow:0 0 0 3px rgba(29,78,216,.14); }

    .jdi-table-wrap { overflow-x:auto; }
    .jdi-table { width:100%; border-collapse:collapse; min-width:900px; }
    .jdi-table thead th { background:#FAFBFC; text-align:left; font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#94A3B8; padding:13px 22px; border-bottom:1px solid #EEF1F5; white-space:nowrap; }
    .jdi-table td { padding:14px 22px; font-size:13.3px; color:#1E293B; border-bottom:1px solid #F5F7FA; }
    .jdi-table tbody tr:nth-child(even) { background:#FBFCFD; }
    .jdi-table tbody tr:hover { background:rgba(29,78,216,.04); }
    .jdi-table tbody tr:last-child td { border-bottom:none; }
    .jdi-empty { text-align:center; padding:48px 20px; color:#94A3B8; font-size:13.5px; }
    .jdi-empty svg { display:block; margin:0 auto 8px; width:32px; height:32px; color:#CBD5E1; stroke-width:1.5; }

    .jdi-pill { display:inline-flex; align-items:center; padding:4px 11px; border-radius:999px; font-size:11px; font-weight:700; white-space:nowrap; }
    .jdi-mode-online { background:rgba(46,158,91,.14); color:#2E9E5B; }
    .jdi-mode-offline { background:rgba(29,78,216,.12); color:#1D4ED8; }
    .jdi-status-terjadwal { background:rgba(29,78,216,.12); color:#1D4ED8; }
    .jdi-status-berlangsung { background:rgba(232,163,23,.14); color:#92660F; }
    .jdi-status-selesai { background:rgba(46,158,91,.14); color:#2E9E5B; }
    .jdi-status-batal { background:rgba(192,57,43,.12); color:#C0392B; }

    .jdi-lokasi-cell { display:flex; align-items:center; gap:10px; }
    .jdi-lokasi-icon { width:30px; height:30px; border-radius:8px; background:#F1F5F9; color:#64748B; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .jdi-lokasi-icon svg { width:14px; height:14px; }

    .jdi-action-group { display:flex; gap:6px; justify-content:flex-end; }
    .jdi-icon-btn { width:32px; height:32px; border-radius:8px; border:none; background:rgba(29,78,216,.1); color:#1D4ED8; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; text-decoration:none; }
    .jdi-icon-btn:hover { background:rgba(29,78,216,.18); }
    .jdi-icon-btn svg { width:15px; height:15px; stroke-width:2; }
    .jdi-icon-btn-danger { background:rgba(192,57,43,.1); color:#C0392B; }
    .jdi-icon-btn-danger:hover { background:rgba(192,57,43,.18); }
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
                        <td>{{ $j->lokasi ?? '—' }}</td>
                        <td>{{ $j->penanggungJawab->name ?? '—' }}</td>
                        <td><span class="jdi-pill jdi-status-{{ $j->status }}">{{ ucfirst($j->status) }}</span></td>
                        <td style="text-align:right">
                            <div class="jdi-action-group">
                                @can('update', $j)
                                    <a href="{{ route('penjadwalan.edit', $j) }}" class="jdi-icon-btn" title="Edit">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                    </a>
                                @endcan
                                @can('delete', $j)
                                    <form method="POST" action="{{ route('penjadwalan.destroy', $j) }}"
                                          data-confirm="Jadwal &quot;{{ $j->judul }}&quot; akan dihapus permanen."
                                          data-confirm-title="Hapus jadwal ini?"
                                          data-confirm-type="danger">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="jdi-icon-btn jdi-icon-btn-danger" title="Hapus">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
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
@endsection