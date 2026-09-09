@extends('layouts.app')

@section('title', 'Ajukan Pengajuan')

@section('content')
<div class="page-header">
    <h1>Ajukan Pengajuan Baru</h1>
    <p class="page-subtitle">Ringkasan Sistem SPKLU</p>
</div>

<div class="card">
    <form method="POST" action="{{ route('monitoring.pengajuan.store') }}">
        @csrf

        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="field-group">
            <label>Pilih FS Skema</label>
            <select name="fs_skema_id" required>
                <option value="">Pilih FS Skema yang layak diajukan...</option>
                @foreach ($fsSkemas as $fs)
                    <option value="{{ $fs->id }}" @selected(request('fs_skema_id') == $fs->id)>
                        {{ $fs->nama_lokasi }} ({{ $fs->status_kelayakan }}, {{ $fs->total_poin }} poin)
                    </option>
                @endforeach
            </select>
            <p class="field-hint">Cuma FS Skema yang belum punya pengajuan dan bukan "Tidak Layak" yang muncul di sini.</p>
        </div>

        <div class="modal-actions">
            <a href="{{ route('monitoring.pengajuan.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">Ajukan</button>
        </div>
    </form>
</div>
@endsection