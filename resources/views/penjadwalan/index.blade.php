@extends('layouts.app')

@section('title', 'Penjadwalan')

@section('content')
<div class="page-header">
    <h1>Penjadwalan</h1>
    <p class="page-subtitle">Ringkasan Sistem SPKLU</p>
</div>

<div class="toolbar">
    <form method="GET" class="toolbar-filters">
        <select name="status" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            @foreach (['terjadwal', 'berlangsung', 'selesai', 'batal'] as $s)
                <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
    </form>

    @can('create', \App\Models\Jadwal::class)
        <a href="{{ route('penjadwalan.create') }}" class="btn btn-primary">+ Buat Jadwal</a>
    @endcan
</div>

<div class="card table-scroll">
    <table class="table-default">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Pengajuan Terkait</th>
                <th>Waktu</th>
                <th>Mode</th>
                <th>PJ</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($jadwals as $j)
                <tr>
                    <td><strong>{{ $j->judul }}</strong></td>
                    <td>{{ $j->pengajuan->fsSkema->nama_lokasi ?? '—' }}</td>
                    <td>{{ $j->waktu_mulai->format('d M Y H:i') }}</td>
                    <td>{{ ucfirst($j->mode) }}</td>
                    <td>{{ $j->penanggungJawab->name ?? '—' }}</td>
                    <td><span class="badge badge-status-{{ $j->status }}">{{ ucfirst($j->status) }}</span></td>
                    <td>
                        @can('update', $j)
                            <a href="{{ route('penjadwalan.edit', $j) }}" class="btn-icon-edit" title="Edit">
                                <i data-lucide="square-pen"></i>
                            </a>
                        @endcan
                        @can('delete', $j)
                            <form method="POST" action="{{ route('penjadwalan.destroy', $j) }}" data-confirm="Yakin hapus jadwal ini?">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon-edit" title="Hapus">
                                    <i data-lucide="trash-2"></i>
                                </button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="empty-state">Belum ada jadwal.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $jadwals->links() }}
@endsection