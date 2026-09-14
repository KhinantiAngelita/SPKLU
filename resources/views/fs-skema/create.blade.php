@extends('layouts.app')

@section('breadcrumb', 'FS Skema')
@section('page-title', 'Tambah FS Skema')

@push('styles')
<style>
.fsf-wrap{display:grid;grid-template-columns:1.15fr 1fr;gap:20px;align-items:start}
@media (max-width:1100px){.fsf-wrap{grid-template-columns:1fr}}
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
.fsf-section-title{font-size:15px;font-weight:700;color:#0F172A;margin:28px 0 14px;padding-top:20px;border-top:1px solid #F1F5F9;letter-spacing:.03em;text-transform:uppercase;color:#0EA5B7}
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

/* Combobox pencarian lokasi (sumber: Probabilitas) */
.fsf-combobox { position:relative; }
.fsf-combobox-trigger {
    display:flex; align-items:center; justify-content:space-between; gap:8px;
    width:100%; padding:10px 12px; border-radius:8px; border:1px solid #E2E8F0;
    background:#fff; font-size:14px; font-family:inherit; color:#94A3B8; cursor:pointer; text-align:left;
    transition:border-color .15s ease, box-shadow .15s ease;
}
.fsf-combobox-trigger:hover { border-color:#cbd5e1; }
.fsf-combobox.open .fsf-combobox-trigger,
.fsf-combobox-trigger:focus-visible { outline:none; border-color:#0EA5B7; box-shadow:0 0 0 3px rgba(14,165,183,.12); }
.fsf-combobox-label { overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
.fsf-combobox-label.has-value { color:#0F172A; font-weight:600; }
.fsf-combobox-chevron { width:13px; height:13px; color:#94A3B8; flex-shrink:0; transition:transform .15s ease; }
.fsf-combobox.open .fsf-combobox-chevron { transform:rotate(180deg); }

.fsf-combobox-panel {
    display:none;
    position:absolute; top:calc(100% + 4px); left:0; right:0; z-index:20;
    background:#fff; border:1px solid #E2E8F0; border-radius:10px;
    box-shadow:0 8px 24px rgba(15,23,42,.14);
    overflow:hidden;
}
.fsf-combobox-panel.open { display:block; }
.fsf-combobox-search {
    width:100%; box-sizing:border-box; padding:10px 12px; border:none; border-bottom:1px solid #F1F5F9;
    font-size:13.5px; font-family:inherit;
}
.fsf-combobox-search:focus { outline:none; background:#fbfcfd; }
.fsf-combobox-options { max-height:230px; overflow-y:auto; }
.fsf-combobox-option { padding:10px 12px; font-size:13.5px; color:#0F172A; cursor:pointer; }
.fsf-combobox-option:hover { background:rgba(14,165,183,.08); }
.fsf-combobox-option.selected { background:rgba(14,165,183,.12); color:#0C8A9A; font-weight:600; }
.fsf-combobox-empty { display:none; padding:16px 12px; font-size:13px; color:#94A3B8; text-align:center; }

/* Field lebar-setengah berdiri sendiri (tanpa pasangan kosong di sebelahnya) */
.fsf-field-half { max-width: calc(50% - 9px); margin-bottom:18px; }
@media (max-width:640px){ .fsf-field-half{ max-width:100%; } }

/* Panel kanan (live preview) */
.fsp-card{background:#fff;border-radius:14px;box-shadow:0 1px 3px rgba(15,23,42,.08);overflow:hidden;margin-bottom:20px}
.fsp-card-header{background:#ECF4F8;padding:14px 20px;font-size:15px;font-weight:700;color:#0F172A;display:flex;align-items:center;justify-content:space-between;gap:8px}
.fsp-card-body{padding:18px 20px}
.fsp-table{width:100%;border-collapse:collapse;font-size:13px}
.fsp-table th{text-align:left;color:#94A3B8;font-weight:600;padding:8px 4px;border-bottom:1px solid #F1F5F9;white-space:nowrap}
.fsp-table td{padding:9px 4px;border-bottom:1px solid #F1F5F9;color:#334155;white-space:nowrap}
.fsp-jarak-bagus{color:#15803D;font-weight:600}
.fsp-jarak-risiko{color:#B91C1C;font-weight:600}
.fsp-jarak-belum{color:#94A3B8;font-style:italic}
.fsp-empty{color:#94A3B8;font-size:13px;padding:6px 0}
.fsp-poin-row{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #F1F5F9;font-size:13.5px}
.fsp-poin-row:last-child{border-bottom:none;font-weight:700;color:#0F172A}
.fsp-status-box{margin-top:14px;padding:10px 14px;border-radius:10px;font-size:13px;font-weight:600}
.fsp-status-hijau{background:#DCFCE7;color:#15803D}
.fsp-status-kuning{background:#FEF3C7;color:#B45309}
.fsp-status-merah{background:#FEE2E2;color:#B91C1C}
.fsp-status-netral{background:#F1F5F9;color:#64748B}
.fsp-narasi{font-size:13.5px;color:#475569;line-height:1.6;margin:0}
.fsp-narasi-placeholder{color:#94A3B8;font-style:italic;font-size:13px}
.fsp-loading{font-size:12px;color:#0EA5B7;font-weight:600}
.fsp-chart-wrap{position:relative;height:220px}
</style>
@endpush

@section('content')
<div class="fsf-header">
    <h1>Tambah FS Skema</h1>
    <p>Hitung kelayakan lokasi SPKLU baru</p>
</div>

<div class="fsf-wrap">
    {{-- KOLOM KIRI: FORM --}}
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
                    <input type="radio" name="skema" value="skema_2" @checked(old('skema', 'skema_2') === 'skema_2') data-preview-trigger>
                    <span>Skema 2</span>
                </label>
                <label class="fsf-tab">
                    <input type="radio" name="skema" value="skema_3" @checked(old('skema') === 'skema_3') data-preview-trigger>
                    <span>Skema 3</span>
                </label>
            </div>

            <div class="fsf-row">
                <div class="fsf-field">
                    <label>Nama Tempat/Lokasi (Nama SPKLU)</label>
                    <div class="fsf-combobox" id="combobox-lokasi">
                        <input type="hidden" name="nama_lokasi" id="input-nama-lokasi" value="{{ old('nama_lokasi') }}" data-preview-trigger>
                        <input type="hidden" name="kandidat_id" id="input-kandidat-id" value="{{ old('kandidat_id') }}">
                        <button type="button" class="fsf-combobox-trigger">
                            <span class="fsf-combobox-label {{ old('nama_lokasi') ? 'has-value' : '' }}" id="label-nama-lokasi">{{ old('nama_lokasi') ?: 'Pilih lokasi dari data Probabilitas...' }}</span>
                            <svg class="fsf-combobox-chevron" viewBox="0 0 10 6" fill="none"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                        </button>
                        <div class="fsf-combobox-panel">
                            <input type="text" class="fsf-combobox-search" placeholder="Cari lokasi...">
                            <div class="fsf-combobox-options">
                                @foreach ($probabilitasList as $p)
                                    <div class="fsf-combobox-option"
                                         data-label="{{ $p->lokasi }}"
                                         data-lat="{{ $p->tikor_lat }}"
                                         data-lng="{{ $p->tikor_lng }}"
                                         data-kandidat-id="{{ $p->kandidatPrioritas->id ?? '' }}">
                                        {{ $p->lokasi }}
                                    </div>
                                @endforeach
                            </div>
                            <div class="fsf-combobox-empty">Tidak ada lokasi yang cocok.</div>
                        </div>
                    </div>
                    <p class="fsf-hint">Ditarik dari data Probabilitas — koordinat &amp; kandidat terkait terisi otomatis saat dipilih.</p>
                </div>
                <div class="fsf-field">
                    <label>Titik Kordinat</label>
                    <input type="text" name="titik_koordinat" id="input-titik-koordinat" value="{{ old('titik_koordinat') }}" placeholder="-6.1944, 106.8318" data-preview-trigger>
                    <p class="fsf-hint">Terisi otomatis saat pilih lokasi, tapi tetap bisa diedit manual.</p>
                </div>
            </div>

            <div class="fsf-row">
                <div class="fsf-field">
                    <label>Layanan Listrik</label>
                    <select name="layanan_listrik" data-preview-trigger>
                        <option value="">Pilih jenis layanan...</option>
                        <option value="TM" @selected(old('layanan_listrik') === 'TM')>TM</option>
                        <option value="TR" @selected(old('layanan_listrik') === 'TR')>TR</option>
                        <option value="LTR" @selected(old('layanan_listrik') === 'LTR')>LTR</option>
                    </select>
                </div>
                <div class="fsf-field">
                    <label>Masa Kontrak (Tahun)</label>
                    <input type="number" name="masa_kontrak_tahun" min="1" max="20" value="{{ old('masa_kontrak_tahun', 5) }}" placeholder="5" required data-preview-trigger>
                    <p class="fsf-hint">Menentukan panjang proyeksi ROI di panel kanan.</p>
                </div>
            </div>

            {{-- SKEMA 2 --}}
            <div id="blok-skema-2">
                <div class="fsf-row">
                    <div class="fsf-field">
                        <label>Total RAB Investasi (Rp)</label>
                        <input type="text" inputmode="numeric" class="fsf-rupiah-display" data-target="total_rab_investasi" placeholder="Contoh: 500.000.000">
                        <input type="hidden" name="total_rab_investasi" id="hidden-total_rab_investasi" value="{{ old('total_rab_investasi') }}" data-preview-trigger>
                    </div>
                    <div class="fsf-field">
                        <label>Mobil/hari</label>
                        <input type="number" name="mobil_per_hari" value="{{ old('mobil_per_hari') }}" placeholder="Masukan asumsi mobil per hari" required data-preview-trigger>
                    </div>
                </div>
            </div>

            {{-- SKEMA 3 --}}
            <div id="blok-skema-3" class="fsf-skema3-only">
                <div class="fsf-row">
                    <div class="fsf-field">
                        <label>RAB Mitra Mesin (Rp)</label>
                        <input type="text" inputmode="numeric" class="fsf-rupiah-display" data-target="rab_mitra_mesin" placeholder="Contoh: 500.000.000">
                        <input type="hidden" name="rab_mitra_mesin" id="hidden-rab_mitra_mesin" value="{{ old('rab_mitra_mesin') }}" data-preview-trigger>
                    </div>
                    <div class="fsf-field">
                        <label>RAB Mitra Lahan (Rp)</label>
                        <input type="text" inputmode="numeric" class="fsf-rupiah-display" data-target="rab_mitra_lahan" placeholder="Contoh: 200.000.000">
                        <input type="hidden" name="rab_mitra_lahan" id="hidden-rab_mitra_lahan" value="{{ old('rab_mitra_lahan') }}" data-preview-trigger>
                    </div>
                </div>
                <div class="fsf-row">
                    <div class="fsf-field">
                        <label>Sharing Profit Mitra Lahan</label>
                        <input type="number" step="0.01" min="0" max="1" name="sharing_provit_mitra_lahan" value="{{ old('sharing_provit_mitra_lahan', 0.10) }}" placeholder="0.10" data-preview-trigger>
                        <p class="fsf-hint">Nilai 0–1 (contoh 0.10 = 10%). Default 10% jika dikosongkan.</p>
                    </div>
                    <div class="fsf-field">
                        <label>Mobil/hari</label>
                        <input type="number" name="mobil_per_hari_skema3" value="{{ old('mobil_per_hari') }}" placeholder="Masukan asumsi mobil per hari" data-preview-trigger>
                    </div>
                </div>
            </div>

            <div class="fsf-field-half">
                <label>Transaksi kWh/Mobil</label>
                <input type="number" step="0.01" name="transaksi_kwh_per_mobil" value="{{ old('transaksi_kwh_per_mobil') }}" placeholder="Masukan Transaksi kWh/mobil" required data-preview-trigger>
            </div>

            <div class="fsf-section-title">Penilaian Lokasi</div>

            <label class="fsf-label-group">Fasilitas (maks 40 poin)</label>
            <div class="fsf-chip-group">
                @foreach (['toilet' => 'Toilet', 'ruang_tunggu' => 'Ruang Tunggu', 'parkir' => 'Parkir', 'kafetaria' => 'Kafetaria'] as $val => $label)
                    <label class="fsf-chip">
                        <input type="checkbox" name="fasilitas[]" value="{{ $val }}" @checked(in_array($val, old('fasilitas', []))) data-preview-trigger>
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
            </div>

            <div class="fsf-field-half">
                <label>Kesiapan Jaringan (maks 20 poin)</label>
                <select name="kesiapan_jaringan" data-preview-trigger>
                    <option value="">Pilih status jaringan...</option>
                    <option value="Siap sambung" @selected(old('kesiapan_jaringan') === 'Siap sambung')>Siap sambung — 20 poin</option>
                    <option value="Perluasan SUTM (mudah)" @selected(old('kesiapan_jaringan') === 'Perluasan SUTM (mudah)')>Perluasan SUTM (mudah) — 15 poin</option>
                    <option value="Perluasan SKTM (gardu tembok)" @selected(old('kesiapan_jaringan') === 'Perluasan SKTM (gardu tembok)')>Perluasan SKTM (gardu tembok) — 10 poin</option>
                    <option value="Perluasan rumit" @selected(old('kesiapan_jaringan') === 'Perluasan rumit')>Perluasan rumit — 5 poin</option>
                </select>
            </div>

            <label class="fsf-label-group">Okupansi (maks 40 poin)</label>
            <div class="fsf-chip-group">
                @foreach (['dekat_perumahan' => 'Dekat Perumahan', 'pintu_tol' => 'Pintu Tol', 'pusat_keramaian' => 'Pusat Keramaian', 'ruas_jalan_protokol' => 'Ruas Jalan Protokol'] as $val => $label)
                    <label class="fsf-chip">
                        <input type="checkbox" name="okupansi[]" value="{{ $val }}" @checked(in_array($val, old('okupansi', []))) data-preview-trigger>
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

    {{-- KOLOM KANAN: LIVE PREVIEW --}}
    <div>
        <div class="fsp-card">
            <div class="fsp-card-header">3 SPKLU Terdekat <span id="fsp-loading-spklu" class="fsp-loading" style="display:none">memuat…</span></div>
            <div class="fsp-card-body">
                <div id="fsp-spklu-empty" class="fsp-empty">Isi Titik Kordinat untuk melihat SPKLU terdekat.</div>
                <table class="fsp-table" id="fsp-spklu-table" style="display:none">
                    <thead>
                        <tr><th>Nama SPKLU</th><th>Jarak</th><th>Status Jarak</th></tr>
                    </thead>
                    <tbody id="fsp-spklu-body"></tbody>
                </table>
            </div>
        </div>

        <div class="fsp-card">
            <div class="fsp-card-header">Ringkasan Kelayakan Lokasi</div>
            <div class="fsp-card-body">
                <div class="fsp-poin-row"><span>Fasilitas</span><span id="fsp-poin-fasilitas">0 / 40</span></div>
                <div class="fsp-poin-row"><span>Kesiapan Jaringan</span><span id="fsp-poin-jaringan">0 / 20</span></div>
                <div class="fsp-poin-row"><span>Okupansi</span><span id="fsp-poin-okupansi">0 / 40</span></div>
                <div class="fsp-poin-row"><span>TOTAL</span><span id="fsp-poin-total">0 / 100</span></div>
                <div class="fsp-status-box fsp-status-netral" id="fsp-status-box">Status Kelayakan: —</div>
            </div>
        </div>

        <div class="fsp-card">
            <div class="fsp-card-header"><span id="fsp-roi-title">Proyeksi ROI</span> <span id="fsp-loading-roi" class="fsp-loading" style="display:none">memuat…</span></div>
            <div class="fsp-card-body">
                <div id="fsp-roi-empty" class="fsp-empty">Isi Mobil/hari &amp; Transaksi kWh/Mobil untuk melihat proyeksi.</div>
                <table class="fsp-table" id="fsp-roi-table" style="display:none">
                    <thead id="fsp-roi-head"></thead>
                    <tbody id="fsp-roi-body"></tbody>
                </table>
            </div>
        </div>

        <div class="fsp-card">
            <div class="fsp-card-header">Ringkasan Analisis</div>
            <div class="fsp-card-body">
                <p class="fsp-narasi-placeholder" id="fsp-narasi">Lengkapi form untuk melihat ringkasan analisis.</p>
            </div>
        </div>
    </div>
</div>

<script>
// Tandai kedua field Mobil/hari SEKALI pakai data-role, supaya pencarian
// elemen tidak bergantung pada attribute `name` yang berubah-ubah tiap
// kali tab diganti.
function tandaiFieldMobil() {
    document.querySelectorAll('input[name="mobil_per_hari"], input[name="mobil_per_hari_disabled"]')
        .forEach(el => { if (!el.dataset.role) el.dataset.role = 'mobil-skema2'; });
    document.querySelectorAll('input[name="mobil_per_hari_skema3"]')
        .forEach(el => { if (!el.dataset.role) el.dataset.role = 'mobil-skema3'; });
}

function toggleSkema(val) {
    document.getElementById('blok-skema-2').style.display = val === 'skema_2' ? 'block' : 'none';
    document.getElementById('blok-skema-3').classList.toggle('fsf-skema3-only', val !== 'skema_3');

    tandaiFieldMobil();

    const mobilSkema2 = document.querySelector('[data-role="mobil-skema2"]');
    const mobilSkema3 = document.querySelector('[data-role="mobil-skema3"]');

    if (val === 'skema_3') {
        mobilSkema3.name = 'mobil_per_hari';
        mobilSkema2.name = 'mobil_per_hari_disabled';
    } else {
        mobilSkema2.name = 'mobil_per_hari';
        mobilSkema3.name = 'mobil_per_hari_disabled';
    }
}

document.querySelectorAll('input[name="skema"]').forEach(el => {
    el.addEventListener('change', () => { toggleSkema(el.value); jadwalkanPreview(); });
});

document.addEventListener('DOMContentLoaded', () => {
    const dipilih = document.querySelector('input[name="skema"]:checked')?.value || 'skema_2';
    toggleSkema(dipilih);
    initComboboxLokasi();
    initRupiahFormatter();
    jalankanPreview();
});

/* =====================================================================
   Format Rupiah live (titik ribuan) — input teks terpisah dari hidden
   input yang benar-benar dikirim (angka murni tanpa titik).
   ===================================================================== */
function formatRibuan(raw) {
    if (!raw) return '';
    return Number(raw).toLocaleString('id-ID');
}

function initRupiahFormatter() {
    document.querySelectorAll('.fsf-rupiah-display').forEach(displayEl => {
        const hiddenEl = document.getElementById('hidden-' + displayEl.dataset.target);
        if (!hiddenEl) return;

        if (hiddenEl.value) {
            displayEl.value = formatRibuan(hiddenEl.value);
        }

        displayEl.addEventListener('input', function () {
            const raw = this.value.replace(/\D/g, '');
            hiddenEl.value = raw;
            this.value = formatRibuan(raw);
            hiddenEl.dispatchEvent(new Event('input', { bubbles: true }));
        });
    });
}

/* =====================================================================
   Combobox pencarian "Nama Tempat/Lokasi" — sumber data: Probabilitas
   ===================================================================== */
function initComboboxLokasi() {
    const root = document.getElementById('combobox-lokasi');
    const trigger = root.querySelector('.fsf-combobox-trigger');
    const label = document.getElementById('label-nama-lokasi');
    const hiddenInput = document.getElementById('input-nama-lokasi');
    const hiddenKandidat = document.getElementById('input-kandidat-id');
    const panel = root.querySelector('.fsf-combobox-panel');
    const search = root.querySelector('.fsf-combobox-search');
    const emptyState = root.querySelector('.fsf-combobox-empty');
    const options = Array.from(root.querySelectorAll('.fsf-combobox-option'));
    const koordinatInput = document.getElementById('input-titik-koordinat');

    function closePanel() {
        panel.classList.remove('open');
        root.classList.remove('open');
    }
    function openPanel() {
        panel.classList.add('open');
        root.classList.add('open');
        search.value = '';
        filterOptions('');
        setTimeout(() => search.focus(), 0);
    }

    trigger.addEventListener('click', function (e) {
        e.stopPropagation();
        panel.classList.contains('open') ? closePanel() : openPanel();
    });

    function filterOptions(query) {
        const q = query.trim().toLowerCase();
        let anyVisible = false;
        options.forEach(opt => {
            const match = opt.dataset.label.toLowerCase().includes(q);
            opt.style.display = match ? '' : 'none';
            if (match) anyVisible = true;
        });
        emptyState.style.display = anyVisible ? 'none' : 'block';
    }

    search.addEventListener('input', () => filterOptions(search.value));
    search.addEventListener('click', e => e.stopPropagation());

    options.forEach(opt => {
        opt.addEventListener('click', function () {
            hiddenInput.value = this.dataset.label;
            label.textContent = this.dataset.label;
            label.classList.add('has-value');
            options.forEach(o => o.classList.remove('selected'));
            this.classList.add('selected');

            if (this.dataset.lat && this.dataset.lng) {
                koordinatInput.value = `${this.dataset.lat}, ${this.dataset.lng}`;
            }
            hiddenKandidat.value = this.dataset.kandidatId || '';

            closePanel();
            jadwalkanPreview();
        });
    });

    document.addEventListener('click', function (e) {
        if (!root.contains(e.target)) closePanel();
    });
}

document.getElementById('form-fs-skema').addEventListener('submit', function (e) {
    const namaLokasi = document.getElementById('input-nama-lokasi').value.trim();
    if (!namaLokasi) {
        e.preventDefault();
        document.getElementById('combobox-lokasi').querySelector('.fsf-combobox-trigger').focus();
        alert('Pilih lokasi terlebih dahulu dari daftar Probabilitas.');
    }
});

// ===== LIVE PREVIEW (AJAX) =====
const PREVIEW_URL = '{{ route('fs-skema.preview') }}';
let timerPreview = null;

function jadwalkanPreview() {
    clearTimeout(timerPreview);
    timerPreview = setTimeout(jalankanPreview, 500);
}

document.getElementById('form-fs-skema').addEventListener('input', jadwalkanPreview);
document.getElementById('form-fs-skema').addEventListener('change', jadwalkanPreview);

function formatRupiah(angka) {
    return 'Rp ' + Number(angka || 0).toLocaleString('id-ID');
}

async function jalankanPreview() {
    const form = document.getElementById('form-fs-skema');
    const formData = new FormData(form);

    document.getElementById('fsp-loading-spklu').style.display = 'inline';
    document.getElementById('fsp-loading-roi').style.display = 'inline';

    try {
        const res = await fetch(PREVIEW_URL, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value,
                'Accept': 'application/json',
            },
            body: formData,
        });

        if (!res.ok) return;
        const data = await res.json();
        renderPoin(data.poin);
        renderSpklu(data.spklu_terdekat);
        renderRoi(data.proyeksi_roi);
        renderNarasi(data.narasi_analisis);
    } catch (e) {
        // koneksi gagal — biarkan panel tetap menampilkan state terakhir
    } finally {
        document.getElementById('fsp-loading-spklu').style.display = 'none';
        document.getElementById('fsp-loading-roi').style.display = 'none';
    }
}

function renderPoin(poin) {
    if (!poin) return;
    document.getElementById('fsp-poin-fasilitas').textContent = poin.fasilitas + ' / 40';
    document.getElementById('fsp-poin-jaringan').textContent = poin.jaringan + ' / 20';
    document.getElementById('fsp-poin-okupansi').textContent = poin.okupansi + ' / 40';
    document.getElementById('fsp-poin-total').textContent = poin.total + ' / 100';

    const box = document.getElementById('fsp-status-box');
    box.textContent = 'Status Kelayakan: ' + poin.status;
    box.className = 'fsp-status-box ' + (
        poin.status === 'Layak' ? 'fsp-status-hijau' :
        poin.status === 'Menjadi Pertimbangan' ? 'fsp-status-kuning' : 'fsp-status-merah'
    );
}

function renderSpklu(list) {
    const empty = document.getElementById('fsp-spklu-empty');
    const table = document.getElementById('fsp-spklu-table');
    const body = document.getElementById('fsp-spklu-body');

    if (!list || list.length === 0) {
        empty.style.display = 'block';
        table.style.display = 'none';
        return;
    }

    empty.style.display = 'none';
    table.style.display = 'table';
    body.innerHTML = list.map(s => {
        let kelasStatus = 'fsp-jarak-belum';
        if (s.status_jarak === 'Bagus') kelasStatus = 'fsp-jarak-bagus';
        else if (s.status_jarak && s.status_jarak.includes('kanibalisasi')) kelasStatus = 'fsp-jarak-risiko';

        return `<tr>
            <td>${s.nama}</td>
            <td>${Number(s.jarak_km).toFixed(2)} km</td>
            <td class="${kelasStatus}">${s.status_jarak}</td>
        </tr>`;
    }).join('');
}

let roiChart = null;

function renderRoiChart(roi) {
    const wrap = document.getElementById('fsp-roi-chart-wrap');
    const empty = document.getElementById('fsp-roi-chart-empty');
    const canvas = document.getElementById('fsp-roi-chart');

    if (!roi || !roi.tahunan || roi.tahunan.length === 0) {
        if (roiChart) { roiChart.destroy(); roiChart = null; }
        wrap.style.display = 'none';
        empty.style.display = 'block';
        return;
    }

    wrap.style.display = 'block';
    empty.style.display = 'none';

    const labels = roi.tahunan.map(r => 'Tahun ' + r.tahun);
    let datasets;

    if (roi.tipe === 'skema_2') {
        datasets = [{
            label: 'Kumulatif Pendapatan',
            data: roi.tahunan.map(r => r.kumulatif),
            borderColor: '#0EA5B7',
            backgroundColor: 'rgba(14,165,183,0.12)',
            fill: true,
            tension: 0.3,
        }];
    } else {
        datasets = [
            {
                label: 'Pendapatan Mitra Mesin',
                data: roi.tahunan.map(r => r.pendapatan_mesin),
                borderColor: '#0EA5B7',
                backgroundColor: 'rgba(14,165,183,0.12)',
                fill: false,
                tension: 0.3,
            },
            {
                label: 'Pendapatan Mitra Lahan',
                data: roi.tahunan.map(r => r.pendapatan_lahan),
                borderColor: '#F59E0B',
                backgroundColor: 'rgba(245,158,11,0.12)',
                fill: false,
                tension: 0.3,
            },
        ];
    }

    if (roiChart) roiChart.destroy();
    roiChart = new Chart(canvas.getContext('2d'), {
        type: 'line',
        data: { labels, datasets },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: roi.tipe !== 'skema_2', labels: { font: { size: 11 } } },
                tooltip: { callbacks: { label: ctx => ctx.dataset.label + ': ' + formatRupiah(ctx.parsed.y) } },
            },
            scales: {
                y: { ticks: { callback: v => formatRupiah(v) } },
            },
        },
    });
}

function renderRoi(roi) {
    const empty = document.getElementById('fsp-roi-empty');
    const table = document.getElementById('fsp-roi-table');
    const head = document.getElementById('fsp-roi-head');
    const body = document.getElementById('fsp-roi-body');
    const title = document.getElementById('fsp-roi-title');

    if (!roi) {
        empty.style.display = 'block';
        table.style.display = 'none';
        title.textContent = 'Proyeksi ROI';
        renderRoiChart(null);
        return;
    }

    empty.style.display = 'none';
    table.style.display = 'table';
    title.textContent = `Proyeksi ROI ${roi.tahunan.length} Tahun`;
    renderRoiChart(roi);

    if (roi.tipe === 'skema_2') {
        head.innerHTML = '<tr><th>Tahun</th><th>Mobil/hari</th><th>Energi (kWh)</th><th>Pendapatan</th><th>Kumulatif</th></tr>';
        body.innerHTML = roi.tahunan.map(r => `<tr>
            <td>${r.tahun}${r.sudah_bep ? ' ✓BEP' : ''}</td>
            <td>${r.mobil_per_hari}</td>
            <td>${Number(r.energi_kwh_per_tahun).toLocaleString('id-ID')}</td>
            <td>${formatRupiah(r.pendapatan_mitra)}</td>
            <td>${formatRupiah(r.kumulatif)}</td>
        </tr>`).join('');
    } else {
        head.innerHTML = '<tr><th>Tahun</th><th>Energi (kWh)</th><th>Pendpt. Mesin</th><th>Pendpt. Lahan</th></tr>';
        body.innerHTML = roi.tahunan.map(r => `<tr>
            <td>${r.tahun}</td>
            <td>${Number(r.energi_kwh_per_tahun).toLocaleString('id-ID')}</td>
            <td>${formatRupiah(r.pendapatan_mesin)}${r.sudah_bep_mesin ? ' ✓BEP' : ''}</td>
            <td>${formatRupiah(r.pendapatan_lahan)}${r.sudah_bep_lahan ? ' ✓BEP' : ''}</td>
        </tr>`).join('');
    }
}

function renderNarasi(narasi) {
    const el = document.getElementById('fsp-narasi');
    if (narasi) {
        el.textContent = narasi;
        el.classList.remove('fsp-narasi-placeholder');
        el.classList.add('fsp-narasi');
    } else {
        el.textContent = 'Lengkapi form untuk melihat ringkasan analisis.';
        el.classList.add('fsp-narasi-placeholder');
        el.classList.remove('fsp-narasi');
    }
}
</script>
@endsection