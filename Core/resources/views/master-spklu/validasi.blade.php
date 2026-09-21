@extends('layouts.app')

@section('breadcrumb', 'Master SPKLU')
@section('page-title', 'Validasi Data Master SPKLU')

@section('content')

<p style="color:var(--text-secondary); margin-bottom:20px;">
    Data berikut otomatis masuk dari Pengajuan yang sudah divalidasi integrasinya. Cek sekali lagi sebelum resmi tayang di Master SPKLU.
</p>

@forelse ($pending as $spklu)
<div class="summary-card" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
    <div>
        <p style="font-weight:600; margin:0;">{{ $spklu->nama }}</p>
        <p style="color:var(--text-secondary); font-size:13px; margin:4px 0 0;">
            {{ $spklu->ulp->nama_penuh ?? '—' }} · {{ $spklu->type }} · {{ $spklu->kw }} kW
            @if ($spklu->pengajuan_id) · dari Pengajuan #{{ $spklu->pengajuan_id }} @endif
        </p>
    </div>
    <div style="display:flex; gap:8px;">
        <form method="POST" action="{{ route('master-spklu.validasi.reject', $spklu) }}" onsubmit="return confirm('Tolak data ini?')">
            @csrf
            <input type="hidden" name="alasan" value="Ditolak dari halaman validasi">
            <button type="submit" class="btn-outline">Tolak</button>
        </form>
        <form method="POST" action="{{ route('master-spklu.validasi.approve', $spklu) }}">
            @csrf
            <button type="submit" class="btn-primary">Validasi & Aktifkan</button>
        </form>
    </div>
</div>
@empty
<div class="summary-card" style="text-align:center; color:var(--text-secondary);">
    Tidak ada data yang menunggu validasi.
</div>
@endforelse

@endsection