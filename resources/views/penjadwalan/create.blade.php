@extends('layouts.app')

@section('title', 'Buat Jadwal')

@section('content')
<div class="page-header">
    <h1>Buat Jadwal Kunjungan</h1>
    <p class="page-subtitle">Ringkasan Sistem SPKLU</p>
</div>

<div class="card">
    <form method="POST" action="{{ route('penjadwalan.store') }}">
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
            <label>Pengajuan Terkait</label>
            <select name="pengajuan_id" required>
                <option value="">Pilih pengajuan...</option>
                @foreach ($pengajuans as $p)
                    <option value="{{ $p->id }}" @selected(request('pengajuan_id') == $p->id || old('pengajuan_id') == $p->id)>
                        {{ $p->id_pengajuan }} — {{ $p->fsSkema->nama_lokasi }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="field-group">
            <label>Judul</label>
            <input type="text" name="judul" value="{{ old('judul') }}" placeholder="cth. Survei Lapangan Lokasi X" required>
        </div>

        <div class="field-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi" placeholder="Keterangan kegiatan (opsional)">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="form-row">
            <div class="field-group">
                <label>Waktu Mulai</label>
                <input type="datetime-local" name="waktu_mulai" value="{{ old('waktu_mulai') }}" required>
            </div>
            <div class="field-group">
                <label>Mode Kunjungan</label>
                <select name="mode" id="mode-select" required onchange="toggleLokasi(this.value)">
                    <option value="">Pilih mode...</option>
                    <option value="online" @selected(old('mode') === 'online')>Online</option>
                    <option value="offline" @selected(old('mode') === 'offline')>Offline</option>
                </select>
            </div>
        </div>

        <div class="field-group" id="field-lokasi">
            <label>Lokasi <span id="label-wajib" style="display:none;color:red;">*</span></label>
            <input type="text" name="lokasi" value="{{ old('lokasi') }}" placeholder="Alamat lokasi kunjungan">
        </div>

        <div class="field-group">
            <label>Penanggung Jawab</label>
            <select name="penanggung_jawab">
                <option value="">Belum ditentukan</option>
                @foreach ($users as $u)
                    <option value="{{ $u->id }}" @selected(old('penanggung_jawab') == $u->id)>{{ $u->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="modal-actions">
            <a href="{{ route('penjadwalan.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>

<script>
function toggleLokasi(mode) {
    document.getElementById('label-wajib').style.display = mode === 'offline' ? 'inline' : 'none';
}
document.addEventListener('DOMContentLoaded', () => toggleLokasi(document.getElementById('mode-select').value));
</script>
@endsection