@extends('layouts.app')

@section('title', 'Monitoring Pengajuan')

@section('content')
<div class="page-header">
    <h1>Monitoring Pengajuan</h1>
    <p class="page-subtitle">Ringkasan Sistem SPKLU</p>
</div>

<div class="toolbar">
    <form method="GET" class="toolbar-filters">
        <select name="status" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            @foreach (['diajukan', 'diverifikasi', 'disetujui', 'ditolak', 'tervalidasi'] as $s)
                <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
    </form>

    @can('create', \App\Models\Pengajuan::class)
        <a href="{{ route('monitoring.pengajuan.create') }}" class="btn btn-primary">+ Ajukan Baru</a>
    @endcan
</div>

<div class="card table-scroll">
    <table class="table-default">
        <thead>
            <tr>
                <th>ID Pengajuan</th>
                <th>Nama SPKLU</th>
                <th>Diajukan Oleh</th>
                <th>Tanggal Diajukan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pengajuans as $p)
                <tr>
                    <td><strong>{{ $p->id_pengajuan }}</strong></td>
                    <td>{{ $p->fsSkema->nama_lokasi }}</td>
                    <td>{{ $p->pengaju->name ?? '—' }}</td>
                    <td>{{ $p->tanggal_diajukan?->format('d M Y') ?? '—' }}</td>
                    <td><span class="badge badge-status-{{ $p->status }}">{{ ucfirst($p->status) }}</span></td>
                    <td>
                        <a href="{{ route('monitoring.pengajuan.show', $p) }}" class="btn-icon-edit" title="Lihat Detail">
                            <i data-lucide="eye"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty-state">Belum ada pengajuan.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $pengajuans->links() }}
@endsection