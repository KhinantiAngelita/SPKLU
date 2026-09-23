@extends('layouts.app')

@section('title', 'Monitoring Probabilitas SPKLU')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/probabilitas.css') }}">

    <style>
        /* FIX: pagination default Laravel nganggep Tailwind ada (SVG panah
           Prev/Next pakai class h-5 w-5 yang gak ngefek tanpa Tailwind,
           jatuh ke ukuran default browser ~300px). Dirapiin di sini biar
           konsisten sama desain sistem, tanpa perlu ganti default view
           Laravel-nya lewat provider. */
        nav[role="navigation"] { display: flex; align-items: center; justify-content: center; gap: 5px; margin-top: 18px; flex-wrap: wrap; font-size: 13.3px; }
        nav[role="navigation"] svg { width: 16px !important; height: 16px !important; display: inline-block; vertical-align: middle; }
        nav[role="navigation"] a, nav[role="navigation"] span {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 34px; height: 34px; padding: 0 10px; border-radius: 9px;
            font-weight: 600; color: #64748B; text-decoration: none; transition: all .15s ease;
        }
        nav[role="navigation"] a:hover { background: #F6F8FA; color: #0081AB; }
        nav[role="navigation"] span[aria-current="page"] { background: linear-gradient(135deg, #023E8A, #0081AB); color: #fff; }
        nav[role="navigation"] .hidden, nav[role="navigation"] .sr-only { display: none !important; }
    </style>
@endpush

@section('content')

    <div class="page-header">
        <h1>Monitoring Probabilitas SPKLU</h1>
        <p class="page-subtitle">Pantau probabilitas kandidat proyek SPKLU</p>
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
                    <th>TIKOR</th>
                    <th>ULP</th>
                    <th class="group-end">Skema</th>

                    <th class="group-alt">22kW</th>
                    <th class="group-alt">30kW</th>
                    <th class="group-alt">50kW</th>
                    <th class="group-alt">60kW</th>
                    <th class="group-alt">120kW</th>
                    <th class="group-alt group-end">180kW</th>

                    <th class="group-alt">Ruang Tunggu</th>
                    <th class="group-alt">Parkir</th>
                    <th class="group-alt">Toilet</th>
                    <th class="group-alt group-end">Kafe</th>

                    <th>Perumahan</th>
                    <th>Pintu Tol</th>
                    <th>Pusat Keramaian</th>
                    <th class="group-end">Ruas Jalan</th>

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
                    <tr>
                        <td colspan="{{ 23 + count(\App\Models\Probabilitas::TAHAPAN) }}" class="empty-state">
                            Belum ada lokasi kandidat. Klik "Tambah Lokasi" untuk mulai.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $daftarProbabilitas->links() }}

    {{-- Modal Edit (Image 2) dan Modal Riwayat dimuat lewat include terpisah,
         di-render kosong lalu diisi via fetch saat tombol diklik, supaya
         tidak perlu render N modal untuk tiap baris grid. --}}

    {{-- Modal Detail Kandidat (read-only, isi form Tambah Kandidat) --}}
    <dialog id="modal-detail" class="dialog-clean detail-dialog">
        <form id="form-detail-edit">
            <div class="modal-header-gradient">
                <h2 id="detail-lokasi">-</h2>
                <button type="button" class="modal-close-btn" onclick="document.getElementById('modal-detail').close()">✕</button>
            </div>

            <div class="modal-body-clean detail-body">
                <div id="detail-error" style="display:none; margin-bottom:12px; padding:10px 14px; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; color:#b91c1c; font-size:13px;"></div>

                <div class="detail-item detail-full">
                    <span class="detail-label">Alamat</span>
                    <input type="text" name="alamat" id="detail-alamat" class="detail-input">
                </div>

                <div class="detail-divider"></div>

                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label">Nomor Telephone</span>
                        <input type="text" name="nomor_telepon" id="detail-telepon" class="detail-input">
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">PIC</span>
                        <input type="text" name="pic" id="detail-pic" class="detail-input">
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
                        <select name="ulp" id="detail-ulp" class="detail-input">
                            <option value="">— Pilih ULP —</option>
                            @foreach ($daftarUlp as $ulp)
                                <option value="{{ $ulp->nama_penuh }}">{{ $ulp->nama_penuh }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Skema</span>
                        <select name="skema" id="detail-skema" class="detail-input">
                            <option value="">— Pilih Skema —</option>
                            <option value="Skema 1">Skema 1</option>
                            <option value="Skema 2">Skema 2</option>
                            <option value="Skema 3">Skema 3</option>
                            <option value="Skema 4">Skema 4</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('modal-detail').close()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </dialog>

    @include('monitoring.probabilitas._modal_edit')
    @include('monitoring.probabilitas._modal_riwayat')

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

        let DETAIL_PROBABILITAS_ID = null;

        function bukaDetail(id) {
            fetch(`/monitoring/probabilitas/${id}/edit-data`)
                .then(r => r.json())
                .then(data => {
                    const p = data.probabilitas;
                    DETAIL_PROBABILITAS_ID = p.id;

                    document.getElementById('detail-error').style.display = 'none';
                    document.getElementById('detail-lokasi').textContent = p.lokasi;
                    document.getElementById('detail-alamat').value = p.alamat ?? '';
                    document.getElementById('detail-telepon').value = p.nomor_telepon ?? '';
                    document.getElementById('detail-pic').value = p.pic ?? '';
                    document.getElementById('detail-tikor').textContent = `${p.tikor_lat}, ${p.tikor_lng}`;
                    document.getElementById('detail-ulp').value = p.ulp ?? '';
                    document.getElementById('detail-skema').value = p.skema ?? '';
                    document.getElementById('modal-detail').showModal();
                });
        }

        document.getElementById('form-detail-edit').addEventListener('submit', function (e) {
            e.preventDefault();

            const errorBox = document.getElementById('detail-error');
            const submitBtn = e.target.querySelector('button[type="submit"]');
            errorBox.style.display = 'none';
            submitBtn.disabled = true;

            const csrf = document.querySelector('meta[name=csrf-token]')?.content ?? '';
            const payload = {
                alamat: document.getElementById('detail-alamat').value,
                nomor_telepon: document.getElementById('detail-telepon').value,
                pic: document.getElementById('detail-pic').value,
                ulp: document.getElementById('detail-ulp').value,
                skema: document.getElementById('detail-skema').value,
            };

            fetch(`/monitoring/probabilitas/${DETAIL_PROBABILITAS_ID}`, {
                method: 'PUT',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                body: JSON.stringify(payload),
            })
                .then(async (res) => {
                    const data = await res.json().catch(() => ({}));
                    if (!res.ok) {
                        const pesan = data.errors
                            ? Object.values(data.errors).flat().join(' ')
                            : (data.message || 'Gagal menyimpan perubahan.');
                        errorBox.textContent = pesan;
                        errorBox.style.display = 'block';
                        return;
                    }
                    window.location.reload();
                })
                .catch(() => {
                    errorBox.textContent = 'Gagal menghubungi server. Coba lagi.';
                    errorBox.style.display = 'block';
                })
                .finally(() => {
                    submitBtn.disabled = false;
                });
        });

        function aturTinggiHeaderTabel() {
            const theadRow1 = document.querySelector('.table-probabilitas thead tr:first-child');
            if (!theadRow1) return;

            const tinggi = theadRow1.getBoundingClientRect().height;
            document.querySelector('.table-probabilitas').style.setProperty('--thead-row1-h', `${tinggi}px`);
        }

        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
            aturTinggiHeaderTabel();

            // kalau tadi keluar dari modal riwayat lewat "Lihat Semua Riwayat",
            // buka ulang modal riwayat yang sama begitu balik ke halaman ini
            const pending = sessionStorage.getItem('reopenRiwayat');
            if (pending) {
                sessionStorage.removeItem('reopenRiwayat');
                const { probabilitasId, tahapKey, tahapLabel } = JSON.parse(pending);
                bukaRiwayat(probabilitasId, tahapKey, tahapLabel);
            }
        });

        window.addEventListener('resize', aturTinggiHeaderTabel);
    </script>

@endsection