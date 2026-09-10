@extends('layouts.app')

@section('breadcrumb', 'FS Skema')
@section('page-title', 'FS Skema')

@section('content')
<div class="page-header">
    <h1>FS Skema</h1>
    <p class="page-subtitle">Daftar hasil analisis kelayakan lokasi SPKLU</p>
</div>

<div class="toolbar">
    <form method="GET" class="toolbar-filters">
        <select name="skema" onchange="this.form.submit()">
            <option value="">Semua Skema</option>
            <option value="skema_2" @selected(request('skema') === 'skema_2')>Skema 2</option>
            <option value="skema_3" @selected(request('skema') === 'skema_3')>Skema 3</option>
        </select>
    </form>

    @can('create', \App\Models\FsSkema::class)
        <a href="{{ route('fs-skema.create') }}" class="btn btn-primary">+ Tambah FS Skema</a>
    @endcan
</div>

<div class="card table-scroll">
    <table class="table-default">
        <thead>
            <tr>
                <th>Nama SPKLU</th>
                <th>Skema</th>
                <th>Kandidat Terkait</th>
                <th>Total Poin</th>
                <th>Status Kelayakan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($fsSkemas as $fs)
                <tr>
                    <td><strong>{{ $fs->nama_lokasi }}</strong></td>
                    <td>{{ $fs->skema === 'skema_2' ? 'Skema 2' : 'Skema 3' }}</td>
                    <td>{{ $fs->kandidat->lokasi ?? '—' }}</td>
                    <td>{{ $fs->total_poin }} / 100</td>
                    <td>
                        <span class="badge badge-{{ $fs->status_kelayakan === 'Layak' ? 'hijau' : ($fs->status_kelayakan === 'Menjadi Pertimbangan' ? 'kuning' : 'merah') }}">
                            {{ $fs->status_kelayakan }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('fs-skema.show', $fs) }}" class="btn-icon-edit" title="Lihat Detail">
                            <i data-lucide="eye"></i>
                        </a>
                        @can('update', $fs)
                            <a href="{{ route('fs-skema.edit', $fs) }}" class="btn-icon-edit" title="Edit">
                                <i data-lucide="square-pen"></i>
                            </a>
                        @endcan
                        @can('delete', $fs)
                            <form method="POST" action="{{ route('fs-skema.destroy', $fs) }}" data-confirm="Yakin hapus FS Skema ini?">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon-edit" title="Hapus">
                                    <i data-lucide="trash-2"></i>
                                </button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty-state">Belum ada data FS Skema. Klik "Tambah FS Skema" untuk mulai.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $fsSkemas->links() }}
@endsection