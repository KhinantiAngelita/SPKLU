@extends('layouts.app')

@section('breadcrumb', 'Penjadwalan')
@section('page-title', 'Edit Jadwal')

@section('content')

<style>
    .jdw-page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; flex-wrap:wrap; gap:10px; }
    .jdw-page-subtitle { color:#64748B; margin:0; font-size:13.5px; }
    .jdw-btn { display:inline-flex; align-items:center; gap:7px; border:none; border-radius:9px; font-size:13.3px; font-weight:700; padding:10px 18px; cursor:pointer; transition:all .15s ease; }
    .jdw-btn svg { width:15px; height:15px; stroke-width:2.1; }
    .jdw-btn-outline { background:#fff; color:#1E293B; border:1px solid #e2e8f0; }
    .jdw-btn-outline:hover { background:#f8fafc; border-color:#cbd5e1; }
    .jdw-btn-primary { background:linear-gradient(135deg,#023E8A,#0081AB); color:#fff; box-shadow:0 2px 10px rgba(2,62,138,.25); }
    .jdw-btn-primary:hover { transform:translateY(-1px); box-shadow:0 4px 14px rgba(2,62,138,.32); }
    .jdw-form-box { max-width:640px; }
    .jdw-form-body { padding:24px 26px; }
    .jdw-form-body label { display:block; font-size:12.5px; font-weight:700; color:#475569; margin:16px 0 6px; }
    .jdw-form-body label:first-child { margin-top:0; }
    .jdw-form-body input[type="text"], .jdw-form-body input[type="date"], .jdw-form-body input[type="time"], .jdw-form-body select {
        width:100%; padding:10px 12px; border-radius:9px; border:1px solid #e2e8f0; font-size:13.5px; font-family:inherit; background:#fff;
    }
    .jdw-form-body input:focus, .jdw-form-body select:focus { outline:none; border-color:#0081AB; box-shadow:0 0 0 3px rgba(0,129,171,.12); }
    .jdw-form-row { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
    .jdw-mode-group { display:inline-flex; background:#f8fafc; border:1px solid #e2e8f0; border-radius:9px; padding:3px; gap:2px; }
    .jdw-mode-pill { border:none; background:none; padding:9px 20px; border-radius:7px; font-size:13px; font-weight:700; color:#64748B; cursor:pointer; transition:all .15s ease; display:inline-flex; align-items:center; gap:7px; }
    .jdw-mode-pill svg { width:15px; height:15px; stroke-width:2.2; }
    .jdw-mode-pill.active-online { background:linear-gradient(135deg,#2E9E5B,#238a4c); color:#fff; box-shadow:0 2px 8px rgba(46,158,91,.35); }
    .jdw-mode-pill.active-offline { background:linear-gradient(135deg,#023E8A,#0081AB); color:#fff; box-shadow:0 2px 8px rgba(2,62,138,.3); }
    .jdw-form-actions { display:flex; justify-content:flex-end; gap:8px; margin-top:26px; padding-top:20px; border-top:1px solid #f1f5f9; }
    .alert-error { background:rgba(192,57,43,.08); border:1px solid rgba(192,57,43,.25); color:#C0392B; border-radius:10px; padding:12px 16px; font-size:13.5px; margin-bottom:18px; }
</style>

<div class="jdw-page-header">
    <p class="jdw-page-subtitle">Perbarui detail jadwal — {{ $jadwal->judul }}</p>
    <a href="{{ route('penjadwalan.index') }}" class="jdw-btn jdw-btn-outline">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Kembali
    </a>
</div>

@if ($errors->any())
    <div class="alert-error">
        <ul style="margin:0; padding-left:18px;">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="surface-card jdw-form-box">
    <div class="section-header-bar">
        <div class="section-header-bar-left">
            <div class="section-header-bar-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
            </div>
            <div><h2>Edit Jadwal</h2><p>Perbarui waktu, mode, atau status jadwal</p></div>
        </div>
    </div>

    <form method="POST" action="{{ route('penjadwalan.update', $jadwal) }}" id="form-jadwal" class="jdw-form-body">
        @csrf
        @method('PUT')

        <label>Pilih Permohonan</label>
        <select name="probabilitas_id" required>
            <option value="">Pilih lokasi...</option>
            @foreach ($probabilitasList as $p)
                <option value="{{ $p->id }}" @selected(old('probabilitas_id', $jadwal->probabilitas_id) == $p->id)>
                    {{ $p->lokasi }} — ULP {{ $p->ulp }}
                </option>
            @endforeach
        </select>

        @php
            $waktuAwal = old('waktu_mulai') ?: $jadwal->waktu_mulai;
            $tanggalAwal = $waktuAwal ? \Illuminate\Support\Carbon::parse($waktuAwal)->format('Y-m-d') : '';
            $jamAwal = $waktuAwal ? \Illuminate\Support\Carbon::parse($waktuAwal)->format('H:i') : '';
        @endphp
        <div class="jdw-form-row">
            <div><label>Tanggal</label><input type="date" id="input-tanggal" value="{{ $tanggalAwal }}" required></div>
            <div><label>Jam</label><input type="time" id="input-jam" value="{{ $jamAwal }}" required></div>
        </div>
        <input type="hidden" name="waktu_mulai" id="input-waktu-mulai" value="{{ $waktuAwal }}">

        <label>Mode</label>
        <div class="jdw-mode-group">
            <label class="jdw-mode-pill" id="pill-online">
                <input type="radio" name="mode" value="online" id="mode-online" style="display:none;" @checked(old('mode', $jadwal->mode) === 'online') required onchange="pilihMode('online')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M15 10l5-5v14l-5-5"/><rect x="1" y="6" width="14" height="12" rx="2"/></svg>
                Online
            </label>
            <label class="jdw-mode-pill" id="pill-offline">
                <input type="radio" name="mode" value="offline" id="mode-offline" style="display:none;" @checked(old('mode', $jadwal->mode) === 'offline') required onchange="pilihMode('offline')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 12-9 12s-9-5-9-12a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                Offline
            </label>
        </div>

        <label>Lokasi <span id="label-wajib" style="display:none; color:#C0392B;">*</span></label>
        <input type="text" name="lokasi" value="{{ old('lokasi', $jadwal->lokasi) }}" placeholder="Alamat lokasi kunjungan">

        <label>Penanggung Jawab</label>
        <select name="penanggung_jawab">
            <option value="">Belum ditentukan</option>
            @foreach ($users as $u)
                <option value="{{ $u->id }}" @selected(old('penanggung_jawab', $jadwal->penanggung_jawab) == $u->id)>{{ $u->name }}</option>
            @endforeach
        </select>

        <label>Status</label>
        <select name="status">
            @foreach (['terjadwal', 'berlangsung', 'selesai', 'batal'] as $s)
                <option value="{{ $s }}" @selected(old('status', $jadwal->status) === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>

        <div class="jdw-form-actions">
            <a href="{{ route('penjadwalan.index') }}" class="jdw-btn jdw-btn-outline">Batal</a>
            <button type="submit" class="jdw-btn jdw-btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>

<script>
function pilihMode(mode) {
    document.getElementById('pill-online').classList.toggle('active-online', mode === 'online');
    document.getElementById('pill-offline').classList.toggle('active-offline', mode === 'offline');
    document.getElementById('label-wajib').style.display = mode === 'offline' ? 'inline' : 'none';
}
document.addEventListener('DOMContentLoaded', () => {
    const modeTerpilih = document.querySelector('input[name="mode"]:checked');
    pilihMode(modeTerpilih ? modeTerpilih.value : 'offline');
});
document.getElementById('form-jadwal').addEventListener('submit', function (e) {
    const tanggal = document.getElementById('input-tanggal').value;
    const jam = document.getElementById('input-jam').value;
    if (!tanggal || !jam) {
        e.preventDefault();
        Swal.fire({ icon: 'warning', title: 'Lengkapi dulu', text: 'Tanggal dan Jam wajib diisi.', confirmButtonColor: '#0081AB' });
        return;
    }
    document.getElementById('input-waktu-mulai').value = `${tanggal} ${jam}:00`;
});
</script>
@endsection