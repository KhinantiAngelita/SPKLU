@extends('layouts.app')

@section('breadcrumb', 'FS Skema')
@section('page-title', 'Tambah FS Skema')

@push('styles')
<style>
.fsf-header{margin-bottom:24px}
.fsf-header h1{font-size:24px;font-weight:700;color:#0F172A;margin:0}
.fsf-header p{color:#64748B;margin:4px 0 0;font-size:14px}
.fsf-card{background:#fff;border-radius:14px;box-shadow:0 1px 3px rgba(15,23,42,.08);padding:28px}
.fsf-alert-error{background:#FEF2F2;border:1px solid #FECACA;color:#B91C1C;padding:12px 16px;border-radius:10px;margin-bottom:20px;font-size:14px}
.fsf-alert-error ul{margin:4px 0 0;padding-left:18px}
.fsf-tabs{display:flex;gap:8px;background:#F1F5F9;padding:4px;border-radius:10px;width:fit-content;margin-bottom:24px}
.fsf-tab{position:relative}
.fsf-tab input{position:absolute;opacity:0;cursor:pointer}
.fsf-tab span{display:block;padding:8px 22px;border-radius:8px;font-size:14px;font-weight:600;color:#64748B;cursor:pointer}
.fsf-tab input:checked + span{background:#fff;color:#0EA5B7;box-shadow:0 1px 2px rgba(15,23,42,.08)}
.fsf-section-title{font-size:15px;font-weight:700;color:#0F172A;margin:28px 0 14px;padding-top:20px;border-top:1px solid #F1F5F9}
.fsf-row{display:grid;grid-template-columns:1fr 1fr;gap:18px;margin-bottom:18px}
.fsf-field label{display:block;font-size:13px;font-weight:600;color:#334155;margin-bottom:6px}
.fsf-field input,.fsf-field select,.fsf-field textarea{width:100%;padding:10px 12px;border:1px solid #E2E8F0;border-radius:8px;font-size:14px;color:#0F172A}
.fsf-field input:focus,.fsf-field select:focus{outline:none;border-color:#0EA5B7;box-shadow:0 0 0 3px rgba(14,165,183,.12)}
.fsf-hint{font-size:12px;color:#94A3B8;margin-top:4px}
.fsf-label-group{font-size:13px;font-weight:600;color:#334155;margin-bottom:10px;display:block}
.fsf-chip-group{display:flex;flex-wrap:wrap;gap:10px;margin-bottom:18px}
.fsf-chip{position:relative}
.fsf-chip input{position:absolute;opacity:0;cursor:pointer}
.fsf-chip span{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border:1px solid #E2E8F0;border-radius:999px;font-size:13px;font-weight:500;color:#475569;cursor:pointer}
.fsf-chip input:checked + span{background:#E0F7FA;border-color:#0EA5B7;color:#0C8A9A}
.fsf-actions{display:flex;justify-content:flex-end;gap:10px;margin-top:28px;padding-top:20px;border-top:1px solid #F1F5F9}
.fsf-btn-outline{padding:10px 20px;border-radius:8px;border:1px solid #E2E8F0;color:#475569;font-weight:600;font-size:14px;text-decoration:none}
.fsf-btn-primary{padding:10px 22px;border-radius:8px;border:none;background:#0EA5B7;color:#fff;font-weight:600;font-size:14px;cursor:pointer}
.fsf-skema3-only{display:none}
</style>
@endpush

@section('content')
<div class="fsf-header">
    <h1>Tambah FS Skema</h1>
    <p>Hitung kelayakan lokasi SPKLU baru</p>
</div>

<div class="fsf-card">
    <form method="POST" action="{{ route('fs-skema.store') }}" id="form-fs-skema">
        @csrf

        @if ($errors->any())
            <div class="fsf-alert-error">
                <strong>Ada isian yang perlu diperbaiki:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="fsf-tabs">
            <label class="fsf-tab">
                <input type="radio" name="skema" value="skema_2" @checked(old('skema', 'skema_2') === 'skema_2') onchange="toggleSkema('skema_2')">
                <span>Skema 2</span>
            </label>
            <label class="fsf-tab">
                <input type="radio" name="skema" value="skema_3" @checked(old('skema') === 'skema_3') onchange="toggleSkema('skema_3')">
                <span>Skema 3</span>
            </label>
        </div>

        <div class="fsf-row">
            <div class="fsf-field">
                <label>Nama Tempat/Lokasi (Nama SPKLU)</label>
                <input type="text" name="nama_lokasi" value="{{ old('nama_lokasi') }}" placeholder="Masukan nama lengkap" required>
            </div>
            <div class="fsf-field">
                <label>Titik Koordinat</label>
                <input type="text" name="titik_koordinat" value="{{ old('titik_koordinat') }}" placeholder="-6.1944, 106.8318">
                <p class="fsf-hint">Format: lat, lng — dipakai buat hitung 3 SPKLU terdekat</p>
            </div>
        </div>

        <div class="fsf-row">
            <div class="fsf-field">
                <label>Kandidat Terkait (opsional)</label>
                <select name="kandidat_id">
                    <option value="">Tidak terhubung ke kandidat</option>
                    @foreach ($kandidatList as $k)
                        <option value="{{ $k->id }}" @selected(old('kandidat_id') == $k->id)>
                            {{ $k->nama_lokasi ?? $k->lokasi }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="fsf-field">
                <label>Layanan Listrik</label>
                <select name="layanan_listrik">
                    <option value="">Pilih jenis layanan...</option>
                    <option value="TM" @selected(old('layanan_listrik') === 'TM')>TM</option>
                    <option value="TR" @selected(old('layanan_listrik') === 'TR')>TR</option>
                    <option value="LTR" @selected(old('layanan_listrik') === 'LTR')>LTR</option>
                </select>
            </div>
        </div>

        {{-- SKEMA 2 --}}
        <div id="blok-skema-2">
            <div class="fsf-row">
                <div class="fsf-field">
                    <label>Total RAB Investasi (Rp)</label>
                    <input type="number" step="0.01" name="total_rab_investasi" value="{{ old('total_rab_investasi') }}" placeholder="Masukan Total RAB">
                </div>
                <div></div>
            </div>
        </div>

        {{-- SKEMA 3 --}}
        <div id="blok-skema-3" class="fsf-skema3-only">
            <div class="fsf-row">
                <div class="fsf-field">
                    <label>RAB Mitra Mesin (Rp)</label>
                    <input type="number" step="0.01" name="rab_mitra_mesin" value="{{ old('rab_mitra_mesin') }}" placeholder="Masukan RAB Mitra Mesin">
                </div>
                <div class="fsf-field">
                    <label>RAB Mitra Lahan (Rp)</label>
                    <input type="number" step="0.01" name="rab_mitra_lahan" value="{{ old('rab_mitra_lahan') }}" placeholder="Masukan RAB Mitra Lahan">
                </div>
            </div>
            <div class="fsf-row">
                <div class="fsf-field">
                    <label>Sharing Profit Mitra Lahan</label>
                    <input type="number" step="0.01" min="0" max="1" name="sharing_provit_mitra_lahan" value="{{ old('sharing_provit_mitra_lahan', 0.10) }}" placeholder="0.10">
                    <p class="fsf-hint">Nilai 0–1 (contoh 0.10 = 10%). Default 10% jika dikosongkan.</p>
                </div>
                <div></div>
            </div>
        </div>

        <div class="fsf-row">
            <div class="fsf-field">
                <label>Mobil/hari</label>
                <input type="number" name="mobil_per_hari" value="{{ old('mobil_per_hari') }}" placeholder="Masukan asumsi mobil per hari" required>
            </div>
            <div class="fsf-field">
                <label>Transaksi kWh/Mobil</label>
                <input type="number" step="0.01" name="transaksi_kwh_per_mobil" value="{{ old('transaksi_kwh_per_mobil') }}" placeholder="Masukan Transaksi kWh/mobil" required>
            </div>
        </div>

        <div class="fsf-section-title">Penilaian Lokasi</div>

        <label class="fsf-label-group">Fasilitas (maks 40 poin)</label>
        <div class="fsf-chip-group">
            @foreach (['toilet' => 'Toilet', 'ruang_tunggu' => 'Ruang Tunggu', 'parkir' => 'Parkir', 'kafetaria' => 'Kafetaria'] as $val => $label)
                <label class="fsf-chip">
                    <input type="checkbox" name="fasilitas[]" value="{{ $val }}" @checked(in_array($val, old('fasilitas', [])))>
                    <span>{{ $label }}</span>
                </label>
            @endforeach
        </div>

        <div class="fsf-field" style="margin-bottom:18px">
            <label>Kesiapan Jaringan (maks 20 poin)</label>
            <input type="text" name="kesiapan_jaringan" value="{{ old('kesiapan_jaringan') }}" placeholder="Masukan status jaringan">
        </div>

        <label class="fsf-label-group">Okupansi (maks 40 poin)</label>
        <div class="fsf-chip-group">
            @foreach (['dekat_perumahan' => 'Dekat Perumahan', 'pintu_tol' => 'Pintu Tol', 'pusat_keramaian' => 'Pusat Keramaian', 'ruas_jalan_protokol' => 'Ruas Jalan Protokol'] as $val => $label)
                <label class="fsf-chip">
                    <input type="checkbox" name="okupansi[]" value="{{ $val }}" @checked(in_array($val, old('okupansi', [])))>
                    <span>{{ $label }}</span>
                </label>
            @endforeach
        </div>

        <div class="fsf-actions">
            <a href="{{ route('fs-skema.index') }}" class="fsf-btn-outline">Batal</a>
            <button type="submit" class="fsf-btn-primary">Simpan</button>
        </div>
    </form>
</div>

<script>
function toggleSkema(val) {
    document.getElementById('blok-skema-2').style.display = val === 'skema_2' ? 'block' : 'none';
    document.getElementById('blok-skema-3').classList.toggle('fsf-skema3-only', val !== 'skema_3');
}
document.addEventListener('DOMContentLoaded', () => {
    const dipilih = document.querySelector('input[name="skema"]:checked')?.value || 'skema_2';
    toggleSkema(dipilih);
});
</script>
@endsection