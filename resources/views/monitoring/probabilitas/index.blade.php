@extends('layouts.app')

@section('title', 'Monitoring Probabilitas SPKLU')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/probabilitas.css') }}">
@endpush

@section('content')
<div class="page-header">
    <h1>Monitoring Probabilitas SPKLU</h1>
    <p class="page-subtitle">Ringkasan Sistem SPKLU</p>
</div>

<div class="toolbar">
    <form method="GET" class="toolbar-filters">
        <input type="text" name="search" placeholder="Cari lokasi..." value="{{ request('search') }}">
        <select name="kategori" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            <option value=">50%" @selected(request('kategori') === '>50%')>&gt;50%</option>
            <option value="<50%" @selected(request('kategori') === '<50%')>&lt;50%</option>
        </select>
    </form>

    @can('create', \App\Models\Probabilitas::class)
        <button type="button" class="btn btn-primary" onclick="document.getElementById('modal-tambah').showModal()">
            + Tambah Lokasi
        </button>
    @endcan
</div>

<div class="card table-scroll">
    <table class="table-probabilitas">
        <thead>
            <tr>
                <th rowspan="2" class="col-sticky col-no">No</th>
                <th rowspan="2" class="col-sticky col-lokasi group-start">Lokasi</th>
                <th colspan="3" class="group-header">Identitas</th>
                <th colspan="6" class="group-header group-alt">Kebutuhan Mesin (unit)</th>
                <th rowspan="2" class="group-start">Mitra Mesin</th>
                <th rowspan="2" class="group-end">Poin Perluasan</th>
                <th colspan="4" class="group-header group-alt">Poin Fasilitas</th>
                <th colspan="4" class="group-header">Poin Okupansi</th>
                <th colspan="{{ count(\App\Models\Probabilitas::TAHAPAN) }}" class="group-header group-alt">Status Tahapan</th>
                <th rowspan="2" class="group-start">Keterangan</th>
                <th rowspan="2" class="group-end sticky-right">Edit</th>
            </tr>
            <tr>
                <th>TIKOR</th><th>ULP</th><th class="group-end">Skema</th>
                <th class="group-alt">22kW</th><th class="group-alt">30kW</th><th class="group-alt">50kW</th>
                <th class="group-alt">60kW</th><th class="group-alt">120kW</th><th class="group-alt group-end">180kW</th>
                <th class="group-alt">Ruang Tunggu</th><th class="group-alt">Parkir</th>
                <th class="group-alt">Toilet</th><th class="group-alt group-end">Kafe</th>
                <th>Perumahan</th><th>Pintu Tol</th><th>Pusat Keramaian</th><th class="group-end">Ruas Jalan</th>
                @foreach (\App\Models\Probabilitas::TAHAPAN as $i => $label)
                    <th class="group-alt {{ $loop->last ? 'group-end' : '' }}">{{ $label }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse ($daftarProbabilitas as $i => $p)
                @php $badges = $p->badgePerTahap(); @endphp
                <tr>
                    <td class="col-sticky col-no">{{ $daftarProbabilitas->firstItem() + $i }}</td>
                    <td class="col-sticky col-lokasi group-start"><strong>{{ $p->lokasi }}</strong></td>
                    <td>{{ $p->tikor_lat }}, {{ $p->tikor_lng }}</td>
                    <td>{{ $p->ulp }}</td>
                    <td class="group-end">{{ $p->skema ?? '—' }}</td>
                    <td class="group-alt">{{ $p->kebutuhan_22kw }}</td>
                    <td class="group-alt">{{ $p->kebutuhan_30kw }}</td>
                    <td class="group-alt">{{ $p->kebutuhan_50kw }}</td>
                    <td class="group-alt">{{ $p->kebutuhan_60kw }}</td>
                    <td class="group-alt">{{ $p->kebutuhan_120kw }}</td>
                    <td class="group-alt group-end">{{ $p->kebutuhan_180kw }}</td>
                    <td class="group-start">{{ $p->mitra_mesin ?? '—' }}</td>
                    <td class="group-end">{{ $p->poin_perluasan_jaringan ?? '—' }}</td>
                    <td class="dot group-alt {{ $p->fasilitas_ruang_tunggu ? 'dot-on' : '' }}"></td>
                    <td class="dot group-alt {{ $p->fasilitas_parkir ? 'dot-on' : '' }}"></td>
                    <td class="dot group-alt {{ $p->fasilitas_toilet ? 'dot-on' : '' }}"></td>
                    <td class="dot group-alt group-end {{ $p->fasilitas_kafe ? 'dot-on' : '' }}"></td>
                    <td class="dot {{ $p->okupansi_perumahan ? 'dot-on' : '' }}"></td>
                    <td class="dot {{ $p->okupansi_pintu_tol ? 'dot-on' : '' }}"></td>
                    <td class="dot {{ $p->okupansi_pusat_keramaian ? 'dot-on' : '' }}"></td>
                    <td class="dot group-end {{ $p->okupansi_ruas_jalan ? 'dot-on' : '' }}"></td>

                    @foreach (\App\Models\Probabilitas::TAHAPAN as $key => $label)
                        <td class="text-center group-alt {{ $loop->last ? 'group-end' : '' }}">
                            <button type="button"
                                    class="icon-tahap icon-tahap-{{ $badges[$key]['warna'] }}"
                                    title="{{ $badges[$key]['label'] }}"
                                    onclick="bukaRiwayat({{ $p->id }}, '{{ $key }}', '{{ $label }}')">
                                <i data-lucide="{{ $badges[$key]['warna'] === 'hijau' ? 'check' : ($badges[$key]['warna'] === 'kuning' ? 'refresh-cw' : 'circle') }}"></i>
                            </button>
                        </td>
                    @endforeach

                    <td class="group-start">{{ $p->keterangan ?? '—' }}</td>
                    <td class="group-end sticky-right">
                        @can('update', $p)
                            <button type="button" class="btn-icon-edit" onclick="bukaEdit({{ $p->id }})">
                                <i data-lucide="square-pen"></i>
                            </button>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="{{ 23 + count(\App\Models\Probabilitas::TAHAPAN) }}" class="empty-state">Belum ada lokasi kandidat. Klik "Tambah Lokasi" untuk mulai.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $daftarProbabilitas->links() }}

{{-- Modal Tambah Lokasi --}}
<dialog id="modal-tambah" class="dialog-clean">
    <form method="POST" action="{{ route('monitoring.probabilitas.store') }}" id="form-tambah">
        @csrf

        <div class="modal-header-gradient">
            <h2>Tambah Lokasi Kandidat</h2>
            <button type="button" class="modal-close-btn" onclick="document.getElementById('modal-tambah').close()">✕</button>
        </div>

        <div class="modal-body-clean">
            <p class="modal-hint">Isi identitas dasar dulu — poin fasilitas, okupansi, dan tahapan bisa dilengkapi belakangan lewat Edit.</p>

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
                <label>Lokasi</label>
                <input type="text" name="lokasi" value="{{ old('lokasi') }}" placeholder="cth. SPKLU UP3 Bogor" required>
            </div>

            <div class="field-group">
                <label>TIKOR (Titik Koordinat)</label>
                <input type="text" id="tikor-gabung" placeholder="-6.1944, 106.8318"
                       value="{{ old('tikor_lat') ? old('tikor_lat').', '.old('tikor_lng') : '' }}" required>
                <p class="field-hint">Paste langsung dari Google Maps, format: lat, lng — akan otomatis terpisah saat disimpan.</p>
            </div>

            {{-- hidden fields ini yang beneran dikirim ke server --}}
            <input type="hidden" name="tikor_lat" id="f-tikor-lat">
            <input type="hidden" name="tikor_lng" id="f-tikor-lng">

            <div class="form-row">
                <div class="field-group">
                    <label>ULP</label>
                    <select name="ulp" required>
                        <option value="">Pilih ULP...</option>
                        @foreach ($daftarUlp as $ulp)
                            <option value="{{ $ulp->nama_penuh }}" @selected(old('ulp') === $ulp->nama_penuh)>
                                {{ $ulp->nama_penuh }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="field-group">
                    <label>Skema</label>
                    <select name="skema">
                        <option value="">Belum ditentukan</option>
                        <option value="Skema 2" @selected(old('skema') === 'Skema 2')>Skema 2 (Curah TR)</option>
                        <option value="Skema 3" @selected(old('skema') === 'Skema 3')>Skema 3 (Mitra Mesin & Lahan)</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="modal-actions">
            <button type="button" class="btn btn-outline" onclick="document.getElementById('modal-tambah').close()">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</dialog>

<script>

function bukaEdit(id) {
    fetch(`/monitoring/probabilitas/${id}/edit-data`)
        .then(r => r.json())
        .then(data => window.isiModalEdit(data)); // lihat _modal_edit.blade.php
}

function bukaRiwayat(probabilitasId, tahapKey, tahapLabel) {
    fetch(`/monitoring/probabilitas/${probabilitasId}/tahapan/${tahapKey}`)
        .then(r => r.json())
        .then(data => window.isiModalRiwayat(probabilitasId, tahapKey, tahapLabel, data));
}

document.getElementById('form-tambah').addEventListener('submit', function (e) {
    const gabung = document.getElementById('tikor-gabung').value;
    const parts = gabung.split(',').map(s => s.trim());

    if (parts.length !== 2 || isNaN(parts[0]) || isNaN(parts[1])) {
        e.preventDefault();
        alert('Format TIKOR salah. Contoh yang benar: -6.1944, 106.8318');
        return;
    }

    document.getElementById('f-tikor-lat').value = parts[0];
    document.getElementById('f-tikor-lng').value = parts[1];
});

function aturTinggiHeaderTabel() {
    const theadRow1 = document.querySelector('.table-probabilitas thead tr:first-child');
    if (!theadRow1) return;
    const tinggi = theadRow1.getBoundingClientRect().height;
    document.querySelector('.table-probabilitas').style.setProperty('--thead-row1-h', `${tinggi}px`);
}

document.addEventListener('DOMContentLoaded', () => {
    aturTinggiHeaderTabel();
});
window.addEventListener('resize', aturTinggiHeaderTabel);

// Kalau ada error validasi dari server, buka lagi modalnya otomatis
@if ($errors->any())
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('modal-tambah').showModal();
    });
@endif
</script>

{{-- Modal Edit (Image 2) dan Modal Riwayat dimuat lewat include terpisah,
     di-render kosong lalu diisi via fetch saat tombol diklik, supaya
     tidak perlu render N modal untuk tiap baris grid. --}}
@include('monitoring.probabilitas._modal_edit')
@include('monitoring.probabilitas._modal_riwayat')

@endsection