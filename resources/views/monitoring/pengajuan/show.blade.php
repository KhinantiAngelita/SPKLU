@extends('layouts.app')

@section('title', $pengajuan->id_pengajuan)

@section('content')
<div class="page-header">
    <h1>Pengajuan {{ $pengajuan->id_pengajuan }}</h1>
    <p class="page-subtitle">{{ $pengajuan->fsSkema->nama_lokasi }}</p>
</div>

<div class="card">
    <table class="table-detail">
        <tr><td>Status</td><td><span class="badge badge-status-{{ $pengajuan->status }}">{{ ucfirst($pengajuan->status) }}</span></td></tr>
        <tr><td>Diajukan Oleh</td><td>{{ $pengajuan->pengaju->name ?? '—' }}</td></tr>
        <tr><td>Tanggal Diajukan</td><td>{{ $pengajuan->tanggal_diajukan?->format('d M Y H:i') ?? '—' }}</td></tr>
        <tr><td>Diverifikasi Oleh</td><td>{{ $pengajuan->verifikator->name ?? '—' }}</td></tr>
        <tr><td>Tanggal Diverifikasi</td><td>{{ $pengajuan->tanggal_diverifikasi?->format('d M Y H:i') ?? '—' }}</td></tr>
        <tr><td>Catatan Verifikasi</td><td>{{ $pengajuan->catatan_verifikasi ?? '—' }}</td></tr>
    </table>

    @can('approve', $pengajuan)
        <hr class="section-divider">
        <h3>Ubah Status</h3>
        <div class="action-buttons">
            @if ($pengajuan->status === 'diajukan')
                <form method="POST" action="{{ route('monitoring.pengajuan.ubah-status', $pengajuan) }}" data-confirm="Verifikasi pengajuan ini?">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="diverifikasi">
                    <button type="submit" class="btn btn-outline">Verifikasi</button>
                </form>
            @endif

            @if ($pengajuan->status === 'diverifikasi')
                <form method="POST" action="{{ route('monitoring.pengajuan.ubah-status', $pengajuan) }}" data-confirm="Setujui pengajuan ini?">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="disetujui">
                    <button type="submit" class="btn btn-primary">Setujui</button>
                </form>
                <form method="POST" action="{{ route('monitoring.pengajuan.ubah-status', $pengajuan) }}" data-confirm="Tolak pengajuan ini?">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="ditolak">
                    <button type="submit" class="btn btn-danger">Tolak</button>
                </form>
            @endif

            @if ($pengajuan->status === 'disetujui')
                <form method="POST" action="{{ route('monitoring.pengajuan.ubah-status', $pengajuan) }}" data-confirm="Tandai pengajuan ini tervalidasi?">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="tervalidasi">
                    <button type="submit" class="btn btn-primary">Tandai Tervalidasi</button>
                </form>
            @endif
        </div>
    @endcan

    @if ($pengajuan->status === 'tervalidasi' || $pengajuan->status === 'disetujui')
        @can('create', \App\Models\Jadwal::class)
            <a href="{{ route('penjadwalan.create', ['pengajuan_id' => $pengajuan->id]) }}" class="btn btn-outline" style="margin-top:12px;">
                + Buat Jadwal Kunjungan
            </a>
        @endcan
    @endif

    @if ($pengajuan->jadwal->isNotEmpty())
        <hr class="section-divider">
        <h3>Jadwal Terkait</h3>
        <table class="table-default">
            <thead><tr><th>Judul</th><th>Waktu</th><th>Mode</th><th>Status</th></tr></thead>
            <tbody>
                @foreach ($pengajuan->jadwal as $j)
                    <tr>
                        <td>{{ $j->judul }}</td>
                        <td>{{ $j->waktu_mulai->format('d M Y H:i') }}</td>
                        <td>{{ ucfirst($j->mode) }}</td>
                        <td>{{ ucfirst($j->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection