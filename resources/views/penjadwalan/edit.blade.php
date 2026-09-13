@extends('layouts.app')

@section('breadcrumb', 'Penjadwalan')
@section('page-title', 'Edit Jadwal')

@section('content')

<style>
    .jde-page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; flex-wrap:wrap; gap:10px; }
    .jde-page-header h1 { font-size:20px; font-weight:700; color:#0F172A; margin:0; }
    .jde-page-header p { color:#64748B; margin:4px 0 0; font-size:13.5px; }
    .jde-btn { display:inline-flex; align-items:center; gap:7px; border:none; border-radius:9px; font-size:13.3px; font-weight:700; padding:10px 18px; cursor:pointer; text-decoration:none; }
    .jde-btn svg { width:15px; height:15px; stroke-width:2.1; }
    .jde-btn-outline { background:#fff; color:#1E293B; border:1px solid #E2E8F0; }
    .jde-btn-outline:hover { background:#F8FAFC; border-color:#CBD5E1; }
    .jde-btn-primary { background:#F5B301; color:#78350F; box-shadow:0 2px 10px rgba(245,179,1,.25); border:none; }
    .jde-btn-primary:hover { background:#E5A700; }

    .jde-alert-error { background:rgba(192,57,43,.08); border:1px solid rgba(192,57,43,.25); color:#C0392B; border-radius:10px; padding:12px 16px; font-size:13.5px; margin-bottom:18px; }

    .jde-card { background:#fff; border-radius:14px; box-shadow:0 1px 3px rgba(15,23,42,.08); max-width:640px; }
    .jde-card-head { display:flex; align-items:center; gap:10px; padding:20px 22px; border-bottom:1px solid #F1F5F9; }
    .jde-icon-box { width:34px; height:34px; border-radius:9px; background:#FEF3C7; color:#B45309; display:flex; align-items:center; justify-content:center; }
    .jde-icon-box svg { width:17px; height:17px; }
    .jde-card-head h2 { font-size:15px; margin:0; color:#0F172A; }
    .jde-card-head p { font-size:12.5px; margin:2px 0 0; color:#94A3B8; }

    .jde-form-body { padding:20px 22px 24px; }
    .jde-form-body label { display:block; font-size:12.5px; font-weight:700; color:#475569; margin:16px 0 7px; }
    .jde-form-body label:first-child { margin-top:0; }
    .jde-form-body input[type="text"], .jde-form-body input[type="date"], .jde-form-body input[type="time"], .jde-form-body select {
        width:100%; padding:10px 12px; border-radius:9px; border:1px solid #E2E8F0; font-size:13.5px; font-family:inherit; background:#fff; color:#334155;
    }
    .jde-form-body input:focus, .jde-form-body select:focus { outline:none; border-color:#1D4ED8; box-shadow:0 0 0 3px rgba(29,78,216,.12); }
    .jde-form-row { display:grid; grid-template-columns:1fr 1fr; gap:14px; }

    .jde-mode-group { display:grid; grid-template-columns:1fr 1fr; gap:8px; }
    .jde-mode-pill { text-align:center; padding:10px; border-radius:9px; font-size:13.5px; font-weight:700; color:#94A3B8; background:#F1F5F9; cursor:pointer; border:1px solid transparent; }
    .jde-mode-pill.active-online { background:rgba(46,158,91,.14); color:#2E9E5B; border-color:rgba(46,158,91,.3); }
    .jde-mode-pill.active-offline { background:rgba(29,78,216,.12); color:#1D4ED8; border-color:rgba(29,78,216,.3); }

    .jde-form-actions { display:flex; justify-content:flex-end; gap:8px; margin-top:26px; padding-top:20px; border-top:1px solid #F1F5F9; }
</style>

<div class="jde-page-header">
    <div>
        <h1>Edit Jadwal</h1>
        <p>Perbarui detail jadwal — {{ $jadwal->judul }}</p>
    </div>
    <a href="{{ route('penjadwalan.index') }}" class="jde-btn jde-btn-outline">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Kembali
    </a>
</div>

@if ($errors->any())
    <div class="jde-alert-error">
        <ul style="margin:0; padding-left:18px;">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="jde-card">
    <div class="jde-card-head">
        <div class="jde-icon-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
        </div>
        <div>
            <h2>Edit Jadwal</h2>
            <p>Perbarui waktu, mode, atau status jadwal</p>
        </div>
    </div>

    <form method="POST" action="{{ route('penjadwalan.update', $jadwal) }}" id="form-jadwal-edit" class="jde-form-body">
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
        <div class="jde-form-row">
            <div><label>Tanggal</label><input type="date" id="input-tanggal" value="{{ $tanggalAwal }}" required></div>
            <div><label>Jam</label><input type="time" id="input-jam" value="{{ $jamAwal }}" required></div>
        </div>
        <input type="hidden" name="waktu_mulai" id="input-waktu-mulai" value="{{ $waktuAwal }}">

        <label>Mode</label>
        <div class="jde-mode-group">
            <div class="jde-mode-pill" id="pill-online" onclick="pilihMode('online')">Online</div>
            <div class="jde-mode-pill" id="pill-offline" onclick="pilihMode('offline')">Offline</div>
        </div>
        <input type="hidden" name="mode" id="input-mode" value="{{ old('mode', $jadwal->mode) }}" required>

        <label>Lokasi <span id="label-wajib" style="display:none;color:#C0392B;">*</span></label>
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

        <div class="jde-form-actions">
            <a href="{{ route('penjadwalan.index') }}" class="jde-btn jde-btn-outline">Batal</a>
            <button type="submit" class="jde-btn jde-btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>

<script>
function pilihMode(mode) {
    document.getElementById('input-mode').value = mode;
    document.getElementById('pill-online').classList.toggle('active-online', mode === 'online');
    document.getElementById('pill-offline').classList.toggle('active-offline', mode === 'offline');
    document.getElementById('label-wajib').style.display = mode === 'offline' ? 'inline' : 'none';
}
document.addEventListener('DOMContentLoaded', () => {
    pilihMode(document.getElementById('input-mode').value || 'offline');
});
document.getElementById('form-jadwal-edit').addEventListener('submit', function (e) {
    const tanggal = document.getElementById('input-tanggal').value;
    const jam = document.getElementById('input-jam').value;
    if (!tanggal || !jam) {
        e.preventDefault();
        if (window.Swal) {
            Swal.fire({ icon: 'warning', title: 'Lengkapi dulu', text: 'Tanggal dan Jam wajib diisi.', confirmButtonColor: '#0081AB' });
        } else {
            alert('Tanggal dan Jam wajib diisi.');
        }
        return;
    }
    document.getElementById('input-waktu-mulai').value = `${tanggal} ${jam}:00`;
});
</script>
@endsection