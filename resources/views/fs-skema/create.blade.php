@extends('layouts.app')

@section('breadcrumb', 'FS Skema')
@section('page-title', 'Tambah FS Skema')

@section('content')
<div class="page-header">
    <h1>Tambah FS Skema</h1>
    <p class="page-subtitle">Hitung kelayakan lokasi SPKLU baru</p>
</div>

<div class="card">
    <form method="POST" action="{{ route('fs-skema.store') }}">
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

        <div class="tabs-skema">
            <label class="tab-radio">
                <input type="radio" name="skema" value="skema_2" @checked(old('skema', 'skema_2') === 'skema_2')> Skema 2
            </label>
            <label class="tab-radio">
                <input type="radio" name="skema" value="skema_3" @checked(old('skema') === 'skema_3')> Skema 3
            </label>
        </div>

        <div class="form-row">
            <div class="field-group">
                <label>Nama Tempat/Lokasi</label>
                <input type="text" name="nama_lokasi" value="{{ old('nama_lokasi') }}" placeholder="Masukan nama lengkap" required>
            </div>
            <div class="field-group">
                <label>Titik Koordinat</label>
                <input type="text" name="titik_koordinat" value="{{ old('titik_koordinat') }}" placeholder="Masukan titik kordinat">
            </div>
        </div>

        <div class="form-row">
            <div class="field-group">
                <label>Total RAB Investasi (Rp)</label>
                <input type="number" step="0.01" name="total_rab_investasi" value="{{ old('total_rab_investasi') }}" placeholder="Masukan Total RAB" required>
            </div>
            <div class="field-group">
                <label>Mobil/hari</label>
                <input type="number" name="mobil_per_hari" value="{{ old('mobil_per_hari') }}" placeholder="Masukan asumsi mobil per hari" required>
            </div>
        </div>

        <div class="form-row">
            <div class="field-group">
                <label>Layanan Listrik</label>
                <input type="text" name="layanan_listrik" value="{{ old('layanan_listrik') }}" placeholder="Masukan jenis layanan listrik">
            </div>
            <div class="field-group">
                <label>Transaksi kWh/Mobil</label>
                <input type="number" step="0.01" name="transaksi_kwh_per_mobil" value="{{ old('transaksi_kwh_per_mobil') }}" placeholder="Masukan Transaksi kWh/mobil" required>
            </div>
        </div>

        <hr class="section-divider">
        <h3>Penilaian Lokasi</h3>

        <label class="field-label-group">Fasilitas (maks 40 poin)</label>
        <div class="chip-group">
            @foreach (['toilet' => 'Toilet', 'ruang_tunggu' => 'Ruang Tunggu', 'parkir' => 'Parkir', 'kafetaria' => 'Kafetaria'] as $val => $label)
                <label class="chip">
                    <input type="checkbox" name="fasilitas[]" value="{{ $val }}" @checked(in_array($val, old('fasilitas', [])))>
                    {{ $label }}
                </label>
            @endforeach
        </div>

        <label class="field-label-group">Kesiapan Jaringan (maks 20 poin)</label>
        <div class="field-group">
            <input type="text" name="kesiapan_jaringan" value="{{ old('kesiapan_jaringan') }}" placeholder="Masukan alamat email / status jaringan">
        </div>

        <label class="field-label-group">Okupansi (maks 40 poin)</label>
        <div class="chip-group">
            @foreach (['dekat_perumahan' => 'Dekat Perumahan', 'pintu_tol' => 'Pintu Tol', 'pusat_keramaian' => 'Pusat Keramaian', 'ruas_jalan_protokol' => 'Ruas Jalan Protokol'] as $val => $label)
                <label class="chip">
                    <input type="checkbox" name="okupansi[]" value="{{ $val }}" @checked(in_array($val, old('okupansi', [])))>
                    {{ $label }}
                </label>
            @endforeach
        </div>

        <div class="modal-actions">
            <a href="{{ route('fs-skema.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection