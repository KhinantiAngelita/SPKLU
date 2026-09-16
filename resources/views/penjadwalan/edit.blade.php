@extends('layouts.app')

@section('breadcrumb', 'Penjadwalan')
@section('page-title', 'Edit Jadwal')

@section('content')

<style>
    .jdf-btn { display:inline-flex; align-items:center; gap:7px; border:none; border-radius:10px; font-size:13.3px; font-weight:700; padding:10px 18px; cursor:pointer; text-decoration:none; transition:all .15s ease; }
    .jdf-btn svg { width:15px; height:15px; stroke-width:2.1; }
    .jdf-btn-outline { background:#fff; color:#1E293B; border:1px solid #E2E8F0; }
    .jdf-btn-outline:hover { background:#F8FAFC; border-color:#CBD5E1; }
    .jdf-btn-primary { background:linear-gradient(135deg, #023E8A, #0081AB); color:#fff; box-shadow:0 6px 16px rgba(2,62,138,.25); border:none; }
    .jdf-btn-primary:hover { transform:translateY(-1px); box-shadow:0 8px 20px rgba(2,62,138,.32); }

    .jdf-alert-error { background:rgba(192,57,43,.08); border:1px solid rgba(192,57,43,.25); color:#C0392B; border-radius:10px; padding:12px 16px; font-size:13.5px; margin:0 auto 18px; max-width:520px; }

    .jdf-card { background:#fff; border-radius:16px; box-shadow:0 1px 3px rgba(15,23,42,.06), 0 8px 24px rgba(15,23,42,.06); max-width:520px; margin:0 auto; overflow:hidden; }

    .jdf-form-head { display:flex; align-items:center; gap:14px; padding:22px 24px; background:linear-gradient(150deg, rgba(2,62,138,.06), rgba(0,129,171,.10)); border-bottom:1px solid #F1F5F9; }
    .jdf-icon-box { width:44px; height:44px; border-radius:12px; background:linear-gradient(135deg, #023E8A, #0081AB); color:#fff; display:flex; align-items:center; justify-content:center; flex-shrink:0; box-shadow:0 4px 12px rgba(2,62,138,.25); }
    .jdf-icon-box svg { width:20px; height:20px; stroke-width:2.3; }
    .jdf-form-head strong { font-size:17px; font-weight:800; color:#023E8A; display:block; letter-spacing:-.01em; }
    .jdf-form-head span { font-size:12.5px; color:#64748B; }

    .jdf-section-title { font-size:11.5px; font-weight:700; letter-spacing:.05em; text-transform:uppercase; color:#0081AB; margin:0 0 14px; }

    .jdf-form-body { padding:20px 24px 26px; }
    .jdf-form-body label { display:block; font-size:12.5px; font-weight:700; color:#475569; margin:16px 0 7px; }
    .jdf-form-body > label:first-child { margin-top:0; }
    .jdf-form-body input[type="text"], .jdf-form-body input[type="date"], .jdf-form-body input[type="time"], .jdf-form-body select {
        width:100%; padding:10px 12px; border-radius:10px; border:1px solid #E2E8F0; font-size:13.5px; font-family:inherit; background:#F8FAFC; color:#334155; transition:border-color .15s ease, box-shadow .15s ease, background .15s ease;
    }
    .jdf-form-body input:focus, .jdf-form-body select:focus { outline:none; border-color:#0081AB; box-shadow:0 0 0 3px rgba(0,129,171,.12); background:#fff; }
    .jdf-form-row { display:grid; grid-template-columns:1fr 1fr; gap:14px; }

    .jdf-mode-group { display:grid; grid-template-columns:1fr 1fr; gap:8px; }
    .jdf-mode-pill { text-align:center; padding:11px; border-radius:10px; font-size:13.5px; font-weight:700; color:#94A3B8; background:#F1F5F9; cursor:pointer; border:1.5px solid transparent; transition:all .15s ease; }
    .jdf-mode-pill.active-online { background:rgba(46,158,91,.12); color:#2E9E5B; border-color:rgba(46,158,91,.3); }
    .jdf-mode-pill.active-offline { background:rgba(2,62,138,.08); color:#023E8A; border-color:rgba(2,62,138,.25); }

    .jdf-form-actions { display:flex; justify-content:flex-end; gap:8px; margin-top:26px; padding-top:20px; border-top:1px solid #F1F5F9; }
</style>

@if ($errors->any())
    <div class="jdf-alert-error">
        <ul style="margin:0; padding-left:18px;">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="jdf-card">
    <div class="jdf-form-head">
        <div class="jdf-icon-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
        </div>
        <div>
            <strong>Edit Jadwal</strong>
            <span>Perbarui detail jadwal — {{ $jadwal->judul }}</span>
        </div>
    </div>

    <form method="POST" action="{{ route('penjadwalan.update', $jadwal) }}" id="form-jadwal-edit" class="jdf-form-body">
        @csrf
        @method('PUT')

        <div class="jdf-section-title">Detail Jadwal</div>

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
        <div class="jdf-form-row">
            <div><label>Tanggal</label><input type="date" id="input-tanggal" value="{{ $tanggalAwal }}" required></div>
            <div><label>Jam</label><input type="time" id="input-jam" value="{{ $jamAwal }}" required></div>
        </div>
        <input type="hidden" name="waktu_mulai" id="input-waktu-mulai" value="{{ $waktuAwal }}">

        <label>Mode Pertemuan</label>
        <div class="jdf-mode-group">
            <div class="jdf-mode-pill" id="pill-online" onclick="pilihMode('online')">Online</div>
            <div class="jdf-mode-pill" id="pill-offline" onclick="pilihMode('offline')">Offline</div>
        </div>
        <input type="hidden" name="mode" id="input-mode" value="{{ old('mode', $jadwal->mode) }}" required>

        <div id="blok-lokasi-offline">
            <label>Lokasi <span id="label-wajib-lokasi" style="display:none;color:#C0392B;">*</span></label>
            <input type="text" name="lokasi" value="{{ old('lokasi', $jadwal->lokasi) }}" placeholder="Alamat lokasi kunjungan">
        </div>

        <div id="blok-online" style="display:none">
            <label>Platform</label>
            <select name="platform">
                <option value="">Pilih platform...</option>
                <option value="Zoom" @selected(old('platform', $jadwal->platform) === 'Zoom')>Zoom</option>
                <option value="Google Meet" @selected(old('platform', $jadwal->platform) === 'Google Meet')>Google Meet</option>
                <option value="Lainnya" @selected(old('platform', $jadwal->platform) === 'Lainnya')>Lainnya</option>
            </select>

            <label>Link Pertemuan</label>
            <input type="text" name="link_pertemuan" value="{{ old('link_pertemuan', $jadwal->link_pertemuan) }}" placeholder="https://zoom.us/j/xxxxxxxxxx">
        </div>

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

        <div class="jdf-form-actions">
            <a href="{{ route('penjadwalan.index') }}" class="jdf-btn jdf-btn-outline">Batal</a>
            <button type="submit" class="jdf-btn jdf-btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>

<script>
function pilihMode(mode) {
    document.getElementById('input-mode').value = mode;
    document.getElementById('pill-online').classList.toggle('active-online', mode === 'online');
    document.getElementById('pill-offline').classList.toggle('active-offline', mode === 'offline');

    document.getElementById('blok-lokasi-offline').style.display = mode === 'offline' ? 'block' : 'none';
    document.getElementById('blok-online').style.display = mode === 'online' ? 'block' : 'none';
    document.getElementById('label-wajib-lokasi').style.display = mode === 'offline' ? 'inline' : 'none';
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
            Swal.fire({ icon: 'warning', title: 'Lengkapi dulu', text: 'Tanggal dan Jam wajib diisi.', confirmButtonColor: '#023E8A' });
        } else {
            alert('Tanggal dan Jam wajib diisi.');
        }
        return;
    }
    document.getElementById('input-waktu-mulai').value = `${tanggal} ${jam}:00`;
});
</script>
@endsection