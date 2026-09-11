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
    <form method="GET" class="toolbar-filters" id="filter-form">
        <input type="text" name="search" placeholder="Cari lokasi..." value="{{ request('search') }}">
        <input type="hidden" name="kategori" id="kategori-hidden" value="{{ request('kategori') }}">

        <div class="segmented-filter">
            <button type="button"
                    class="segmented-btn {{ ! request('kategori') ? 'active' : '' }}"
                    onclick="pilihKategori('')">
                Semua
            </button>
            <button type="button"
                    class="segmented-btn {{ request('kategori') === '>50%' ? 'active' : '' }}"
                    onclick="pilihKategori('>50%')">
                &gt;50%
            </button>
            <button type="button"
                    class="segmented-btn {{ request('kategori') === '<50%' ? 'active' : '' }}"
                    onclick="pilihKategori('<50%')">
                &lt;50%
            </button>
        </div>
    </form>

    @can('create', \App\Models\Probabilitas::class)
        <a href="{{ route('monitoring.kandidat.create') }}" class="btn btn-primary">
            + Tambah Kandidat Baru
        </a>
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
                <th rowspan="2" class="group-end">Poin Perluasan <br> Jaringan</th>
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
                    <td class="col-sticky col-lokasi group-start">
                        <strong class="lokasi-clickable" onclick="bukaDetail({{ $p->id }})" style="cursor: pointer;">
                            {{ $p->lokasi }}
                        </strong>
                    </td>
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

function pilihKategori(value) {
    document.getElementById('kategori-hidden').value = value;
    document.getElementById('filter-form').submit();
}

function bukaDetail(id) {
    fetch(`/monitoring/probabilitas/${id}/edit-data`)
        .then(r => r.json())
        .then(data => {
            const p = data.probabilitas;
            document.getElementById('detail-lokasi').textContent = p.lokasi;
            document.getElementById('detail-alamat').textContent = p.alamat || '—';
            document.getElementById('detail-telepon').textContent = p.nomor_telepon || '—';
            document.getElementById('detail-pic').textContent = p.pic || '—';
            document.getElementById('detail-tikor').textContent = `${p.tikor_lat}, ${p.tikor_lng}`;
            document.getElementById('detail-ulp').textContent = p.ulp || '—';
            document.getElementById('detail-skema').textContent = p.skema || '—';
            document.getElementById('modal-detail').showModal();
        });
}

document.addEventListener('DOMContentLoaded', () => lucide.createIcons());  

function aturTinggiHeaderTabel() {
    const theadRow1 = document.querySelector('.table-probabilitas thead tr:first-child');
    if (!theadRow1) return;
    const tinggi = theadRow1.getBoundingClientRect().height;
    document.querySelector('.table-probabilitas').style.setProperty('--thead-row1-h', `${tinggi}px`);
}

document.addEventListener('DOMContentLoaded', () => {
    aturTinggiHeaderTabel();
    lucide.createIcons();
});
window.addEventListener('resize', aturTinggiHeaderTabel);

</script>

{{-- Modal Edit (Image 2) dan Modal Riwayat dimuat lewat include terpisah,
     di-render kosong lalu diisi via fetch saat tombol diklik, supaya
     tidak perlu render N modal untuk tiap baris grid. --}}

{{-- Modal Detail Kandidat (read-only, isi form Tambah Kandidat) --}}
<dialog id="modal-detail" class="dialog-clean detail-dialog">
    <div class="modal-header-gradient">
        <h2 id="detail-lokasi">-</h2>
        <button type="button" class="modal-close-btn" onclick="document.getElementById('modal-detail').close()">✕</button>
    </div>

    <div class="modal-body-clean detail-body">
        <div class="detail-item detail-full">
            <span class="detail-label">Alamat</span>
            <span class="detail-value" id="detail-alamat">-</span>
        </div>

        <div class="detail-divider"></div>

        <div class="detail-row">
            <div class="detail-item">
                <span class="detail-label">Nomor Telephone</span>
                <span class="detail-value" id="detail-telepon">-</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">PIC</span>
                <span class="detail-value" id="detail-pic">-</span>
            </div>
        </div>

        <div class="detail-divider"></div>

        <div class="detail-item detail-full">
            <span class="detail-label">Titik Koordinat</span>
            <span class="detail-value detail-mono" id="detail-tikor">-</span>
        </div>

        <div class="detail-divider"></div>

        <div class="detail-row">
            <div class="detail-item">
                <span class="detail-label">ULP</span>
                <span class="detail-value" id="detail-ulp">-</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Skema</span>
                <span class="detail-value" id="detail-skema">-</span>
            </div>
        </div>
    </div>

    <div class="modal-actions">
        <button type="button" class="btn btn-outline" onclick="document.getElementById('modal-detail').close()">Tutup</button>
    </div>
</dialog>     
@include('monitoring.probabilitas._modal_edit')
@include('monitoring.probabilitas._modal_riwayat')

@endsection