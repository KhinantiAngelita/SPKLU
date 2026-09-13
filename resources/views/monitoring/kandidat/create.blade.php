@extends('layouts.app')

@section('content')
<div class="kandidat-form-page">
    <div class="kandidat-form-card">

        <div class="kandidat-form-header">
            <div class="kandidat-form-icon">+</div>
            <div>
                <h1>Tambah Kandidat Baru</h1>
                <p>Ringkasan Sistem SPKLU</p>
            </div>
        </div>

        <form method="POST" action="{{ route('monitoring.probabilitas.store') }}">
            @csrf

            <h3 class="kandidat-section-title">INFORMASI LOKASI</h3>

            <div class="form-group">
                <label>Nama Lokasi</label>
                <input type="text" name="lokasi" placeholder="Masukan nama lengkap" value="{{ old('lokasi') }}" required>
                @error('lokasi') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <input type="text" name="alamat" placeholder="Masukan alamat lokasi" value="{{ old('alamat') }}">
                @error('alamat') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Nomor Telephone</label>
                <input type="text" name="nomor_telepon" placeholder="Masukan nomor telephone" value="{{ old('nomor_telepon') }}">
                @error('nomor_telepon') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>PIC</label>
                <input type="text" name="pic" placeholder="Masukan nama PIC" value="{{ old('pic') }}">
                @error('pic') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Titik Kordinat</label>
                <input type="text" name="tikor" placeholder="Contoh: -6.597147, 106.806039"
                    value="{{ old('tikor') }}" required>
                <small class="form-hint">Format: latitude, longitude (dipisah koma) — bisa langsung paste dari Google Maps</small>
                @error('tikor') <span class="form-error">{{ $message }}</span> @enderror
            </div>
            

            <div class="kandidat-form-actions">
                <a href="{{ route('monitoring.probabilitas.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Tambah Kandidat</button>
            </div>
        </form>

    </div>
</div>
@endsection