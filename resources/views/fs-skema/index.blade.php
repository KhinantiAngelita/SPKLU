@extends('layouts.app')

@section('breadcrumb', 'FS Skema')
@section('page-title', 'FS Skema')

@push('styles')
<style>
.fss-header{display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:24px;flex-wrap:wrap;gap:12px}
.fss-header h1{font-size:24px;font-weight:700;color:#0F172A;margin:0}
.fss-header p{color:#64748B;margin:4px 0 0;font-size:14px}
.fss-toolbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;flex-wrap:wrap;gap:12px}
.fss-select{padding:9px 14px;border:1px solid #E2E8F0;border-radius:8px;font-size:14px;background:#fff;color:#334155}
.fss-btn-primary{background:#0EA5B7;color:#fff;padding:10px 18px;border-radius:8px;font-weight:600;font-size:14px;border:none;text-decoration:none;display:inline-flex;align-items:center;gap:6px}
.fss-btn-primary:hover{background:#0C8A9A}
.fss-card{background:#fff;border-radius:14px;box-shadow:0 1px 3px rgba(15,23,42,.08);overflow:hidden}
.fss-table{width:100%;border-collapse:collapse}
.fss-table th{text-align:left;font-size:12px;text-transform:uppercase;letter-spacing:.04em;color:#94A3B8;padding:14px 18px;border-bottom:1px solid #F1F5F9;background:#F8FAFC}
.fss-table td{padding:14px 18px;font-size:14px;color:#334155;border-bottom:1px solid #F1F5F9}
.fss-table tr:last-child td{border-bottom:none}
.fss-table tr:hover td{background:#FAFBFC}
.fss-badge{padding:4px 10px;border-radius:999px;font-size:12px;font-weight:600;display:inline-block}
.fss-badge-hijau{background:#DCFCE7;color:#15803D}
.fss-badge-kuning{background:#FEF3C7;color:#B45309}
.fss-badge-merah{background:#FEE2E2;color:#B91C1C}
.fss-skema-tag{font-size:12px;font-weight:600;color:#0EA5B7;background:#E0F7FA;padding:3px 9px;border-radius:6px}
.fss-icon-btn{width:32px;height:32px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;color:#64748B;border:1px solid #E2E8F0;background:#fff;margin-left:4px;cursor:pointer}
.fss-icon-btn:hover{background:#F1F5F9;color:#0F172A}
.fss-empty{text-align:center;padding:48px 20px;color:#94A3B8;font-size:14px}
</style>
@endpush

@section('content')
<div class="fss-header">
    <div>
        <h1>FS Skema</h1>
        <p>Daftar hasil analisis kelayakan lokasi SPKLU</p>
    </div>
</div>

<div class="fss-toolbar">
    <form method="GET">
        <select name="skema" class="fss-select" onchange="this.form.submit()">
            <option value="">Semua Skema</option>
            <option value="skema_2" @selected(request('skema') === 'skema_2')>Skema 2</option>
            <option value="skema_3" @selected(request('skema') === 'skema_3')>Skema 3</option>
        </select>
    </form>

    @can('create', \App\Models\FsSkema::class)
        <a href="{{ route('fs-skema.create') }}" class="fss-btn-primary">
            <i data-lucide="plus"></i> Tambah FS Skema
        </a>
    @endcan
</div>

<div class="fss-card">
    <table class="fss-table">
        <thead>
            <tr>
                <th>Nama SPKLU</th>
                <th>Skema</th>
                <th>Kandidat Terkait</th>
                <th>Total Poin</th>
                <th>Status Kelayakan</th>
                <th style="text-align:right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($fsSkemas as $fs)
                <tr>
                    <td><strong>{{ $fs->nama_lokasi }}</strong></td>
                    <td><span class="fss-skema-tag">{{ $fs->skema === 'skema_2' ? 'Skema 2' : 'Skema 3' }}</span></td>
                    <td>{{ $fs->kandidat->nama_lokasi ?? $fs->kandidat->lokasi ?? '—' }}</td>
                    <td>{{ $fs->total_poin }} / 100</td>
                    <td>
                        <span class="fss-badge fss-badge-{{ $fs->status_kelayakan === 'Layak' ? 'hijau' : ($fs->status_kelayakan === 'Menjadi Pertimbangan' ? 'kuning' : 'merah') }}">
                            {{ $fs->status_kelayakan }}
                        </span>
                    </td>
                    <td style="text-align:right">
                        <a href="{{ route('fs-skema.show', $fs) }}" class="fss-icon-btn" title="Lihat Detail"><i data-lucide="eye"></i></a>
                        @can('update', $fs)
                            <a href="{{ route('fs-skema.edit', $fs) }}" class="fss-icon-btn" title="Edit"><i data-lucide="square-pen"></i></a>
                        @endcan
                        @can('delete', $fs)
                            <form method="POST" action="{{ route('fs-skema.destroy', $fs) }}" data-confirm="Yakin hapus FS Skema ini?" style="display:inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="fss-icon-btn" title="Hapus"><i data-lucide="trash-2"></i></button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="fss-empty">Belum ada data FS Skema. Klik "Tambah FS Skema" untuk mulai.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:16px">{{ $fsSkemas->links() }}</div>
@endsection