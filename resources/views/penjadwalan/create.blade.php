@extends('layouts.app')

@section('breadcrumb', 'Penjadwalan')
@section('page-title', 'Penjadwalan')

@section('content')

<style>
    .jdc-page-header { margin-bottom:18px; }
    .jdc-page-header h1 { font-size:20px; font-weight:700; color:#0F172A; margin:0; }
    .jdc-page-header p { color:#64748B; margin:4px 0 0; font-size:13.5px; }

    .jdc-layout { display:grid; grid-template-columns:400px 1fr; gap:20px; align-items:start; }
    @media (max-width:1000px){ .jdc-layout{ grid-template-columns:1fr; } }

    .jdc-card { background:#fff; border-radius:14px; box-shadow:0 1px 3px rgba(15,23,42,.08); }
    .jdc-alert-error { background:rgba(192,57,43,.08); border:1px solid rgba(192,57,43,.25); color:#C0392B; border-radius:10px; padding:12px 16px; font-size:13px; margin-bottom:18px; }

    .jdc-form-head { display:flex; align-items:center; gap:10px; padding:20px 22px 4px; }
    .jdc-form-head .jdc-icon-box { width:30px; height:30px; border-radius:8px; background:#DBEAFE; color:#1D4ED8; display:flex; align-items:center; justify-content:center; }
    .jdc-form-head .jdc-icon-box svg { width:16px; height:16px; }
    .jdc-form-head strong { font-size:15px; color:#0F172A; }
    .jdc-form-body { padding:16px 22px 24px; }
    .jdc-form-body label { display:block; font-size:12.5px; font-weight:700; color:#475569; margin:16px 0 7px; }
    .jdc-form-body label:first-child { margin-top:0; }
    .jdc-form-body select, .jdc-form-body input[type="text"] {
        width:100%; padding:10px 12px; border-radius:9px; border:1px solid #e2e8f0; font-size:13.5px; background:#F8FAFC; color:#334155;
    }
    .jdc-form-body select:focus, .jdc-form-body input:focus { outline:none; border-color:#0081AB; box-shadow:0 0 0 3px rgba(0,129,171,.12); background:#fff; }
    .jdc-hint { font-size:11px; color:#94a3b8; margin-top:4px; }

    .jdc-mini-cal-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:10px; }
    .jdc-mini-cal-head strong { font-size:13.5px; color:#0F172A; }
    .jdc-mini-cal-nav button { width:26px; height:26px; border-radius:7px; border:1px solid #e2e8f0; background:#fff; color:#64748B; cursor:pointer; }
    .jdc-mini-cal-nav button:hover { background:#F1F5F9; }
    .jdc-mini-grid { display:grid; grid-template-columns:repeat(7,1fr); gap:4px; text-align:center; }
    .jdc-mini-grid .dow { font-size:10.5px; font-weight:700; color:#94A3B8; padding-bottom:6px; }
    .jdc-mini-day { aspect-ratio:1; display:flex; align-items:center; justify-content:center; font-size:12.5px; color:#334155; border-radius:8px; cursor:pointer; background:transparent; border:none; }
    .jdc-mini-day:hover { background:#F1F5F9; }
    .jdc-mini-day.selected { background:#1D4ED8; color:#fff; font-weight:700; }
    .jdc-mini-day.blank { visibility:hidden; cursor:default; }

    .jdc-mode-group { display:grid; grid-template-columns:1fr 1fr; gap:8px; }
    .jdc-mode-pill { text-align:center; padding:10px; border-radius:9px; font-size:13.5px; font-weight:700; color:#94A3B8; background:#F1F5F9; cursor:pointer; border:1px solid transparent; }
    .jdc-mode-pill.active { background:#fff; color:#0F172A; border-color:#e2e8f0; box-shadow:0 1px 3px rgba(15,23,42,.08); }

    .jdc-btn-submit { width:100%; margin-top:22px; padding:13px; border:none; border-radius:10px; background:#F5B301; color:#78350F; font-weight:700; font-size:14px; cursor:pointer; }
    .jdc-btn-submit:hover { background:#E5A700; }

    .jdc-cal-head { display:flex; align-items:center; justify-content:space-between; padding:20px 22px; }
    .jdc-cal-head strong { font-size:16px; color:#0F172A; }
    .jdc-cal-nav-group { display:flex; gap:6px; }
    .jdc-cal-nav-group button { padding:7px 12px; border-radius:8px; border:1px solid #e2e8f0; background:#fff; color:#475569; font-size:12.5px; font-weight:600; cursor:pointer; }
    .jdc-cal-nav-group button:hover { background:#F8FAFC; }

    .jdc-cal-grid { display:grid; grid-template-columns:repeat(7,1fr); border-top:1px solid #F1F5F9; }
    .jdc-cal-dow { text-align:center; font-size:11px; font-weight:700; color:#94A3B8; padding:10px 0; border-bottom:1px solid #F1F5F9; }
    .jdc-cal-cell { min-height:82px; border-right:1px solid #F8FAFC; border-bottom:1px solid #F8FAFC; padding:8px; cursor:pointer; }
    .jdc-cal-cell:hover { background:#FAFBFC; }
    .jdc-cal-cell.selected { background:#EFF6FF; }
    .jdc-cal-cell .num { font-size:12.5px; color:#334155; }
    .jdc-cal-cell.selected .num { background:#1D4ED8; color:#fff; width:22px; height:22px; border-radius:50%; display:flex; align-items:center; justify-content:center; }

    .jdc-cal-time-pill { display:block; font-size:9.5px; font-weight:700; padding:1.5px 5px; border-radius:5px; margin-top:3px; background:#DBEAFE; color:#1D4ED8; white-space:nowrap; }
    .jdc-cal-time-pill.offline { background:#DCFCE7; color:#15803D; }
    .jdc-cal-more { font-size:9px; color:#94A3B8; margin-top:2px; }

    /* ===== Jadwal Hari Ini (section TETAP, selalu hari ini, tidak ikut klik kalender) ===== */
    .jdc-hariini-title { display:flex; align-items:center; justify-content:space-between; padding:18px 22px 10px; }
    .jdc-hariini-title strong { font-size:15px; font-weight:700; color:#0F172A; }
    .jdc-hariini-title span { font-size:12px; color:#94A3B8; }
    .jdc-hariini-list { padding:0 22px 22px; display:flex; flex-direction:column; gap:12px; }
    .jdc-hariini-item {
        display:flex; align-items:center; gap:16px;
        border:1px solid #E2E8F0; border-left:4px solid #1D4ED8; border-radius:12px;
        padding:14px 18px; background:#fff;
    }
    .jdc-hariini-item.offline { border-left-color:#2E9E5B; }
    .jdc-hariini-time {
        flex-shrink:0; width:56px; text-align:center;
        background:#EFF6FF; color:#1D4ED8; font-weight:700; font-size:13px;
        padding:8px 6px; border-radius:9px; white-space:nowrap;
    }
    .jdc-hariini-item.offline .jdc-hariini-time { background:#EAFAF1; color:#2E9E5B; }
    .jdc-hariini-body { flex:1; min-width:0; }
    .jdc-hariini-body strong { font-size:14px; color:#0F172A; display:block; }
    .jdc-hariini-desc { font-size:12px; color:#94A3B8; display:block; margin-top:2px; }
    .jdc-hariini-badge {
        flex-shrink:0; font-size:11.5px; font-weight:700; padding:5px 14px; border-radius:999px;
        background:#DBEAFE; color:#1D4ED8; white-space:nowrap;
    }
    .jdc-hariini-item.offline .jdc-hariini-badge { background:#DCFCE7; color:#15803D; }
    .jdc-hariini-empty { text-align:center; color:#94A3B8; font-size:13px; padding:24px; }
</style>

<div class="jdc-page-header">
    <h1>Penjadwalan</h1>
    <p>Kelola Jadwal Kunjungan Anda</p>
</div>

@if ($errors->any())
    <div class="jdc-alert-error">
        <ul style="margin:0; padding-left:18px;">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="jdc-layout">
    <div class="jdc-card">
        <div class="jdc-form-head">
            <div class="jdc-icon-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            </div>
            <strong>Buat Jadwal Baru</strong>
        </div>

        <form method="POST" action="{{ route('penjadwalan.store') }}" id="form-jadwal" class="jdc-form-body">
            @csrf

            <label>Pilih Permohonan</label>
            <select name="probabilitas_id" required>
                <option value="">Pilih Permohonan</option>
                @foreach ($probabilitasList as $p)
                    <option value="{{ $p->id }}" @selected(old('probabilitas_id') == $p->id)>
                        {{ $p->lokasi }} — ULP {{ $p->ulp }}
                    </option>
                @endforeach
            </select>

            <label>Pilih Tanggal</label>
            <div class="jdc-mini-cal-head">
                <div class="jdc-mini-cal-nav"><button type="button" onclick="ubahBulanForm(-1)">&lsaquo;</button></div>
                <strong id="mini-cal-label"></strong>
                <div class="jdc-mini-cal-nav"><button type="button" onclick="ubahBulanForm(1)">&rsaquo;</button></div>
            </div>
            <div class="jdc-mini-grid" id="mini-cal-grid"></div>
            <input type="hidden" name="tanggal_pilihan" id="input-tanggal" value="{{ old('tanggal_pilihan') }}">
            <p class="jdc-hint" id="mini-cal-hint">Klik salah satu tanggal di atas.</p>

            <label>Pilih Jam</label>
            <select id="input-jam" required>
                <option value="">Pilih Jam Kunjungan</option>
                @foreach (['08:00','09:00','10:00','11:00','13:00','14:00','15:00','16:00'] as $jam)
                    <option value="{{ $jam }}" @selected(old('jam') === $jam)>{{ $jam }}</option>
                @endforeach
            </select>
            <input type="hidden" name="waktu_mulai" id="input-waktu-mulai" value="{{ old('waktu_mulai') }}">

            <label>Mode Pertemuan</label>
            <div class="jdc-mode-group">
                <div class="jdc-mode-pill" id="pill-online" onclick="pilihMode('online')">Online</div>
                <div class="jdc-mode-pill" id="pill-offline" onclick="pilihMode('offline')">Offline</div>
            </div>
            <input type="hidden" name="mode" id="input-mode" value="{{ old('mode', 'offline') }}" required>

            <label>Lokasi/Platform <span id="label-wajib" style="display:none;color:#C0392B;">*</span></label>
            <input type="text" name="lokasi" value="{{ old('lokasi') }}" placeholder="Masukan Lokasi atau pilih platform">

            <label>Penanggung Jawab</label>
            <select name="penanggung_jawab">
                <option value="">Belum ditentukan</option>
                @foreach ($users as $u)
                    <option value="{{ $u->id }}" @selected(old('penanggung_jawab') == $u->id)>{{ $u->name }}</option>
                @endforeach
            </select>

            <button type="submit" class="jdc-btn-submit">Simpan Jadwal</button>
        </form>
    </div>

    <div class="jdc-card">
        <div class="jdc-cal-head">
            <strong id="cal-besar-label"></strong>
            <div class="jdc-cal-nav-group">
                <button type="button" onclick="ubahBulanBesar(-1)">&lsaquo;</button>
                <button type="button" onclick="pilihHariIni()">Hari Ini</button>
                <button type="button" onclick="ubahBulanBesar(1)">&rsaquo;</button>
            </div>
        </div>

        <div class="jdc-cal-grid">
            @foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $d)
                <div class="jdc-cal-dow">{{ $d }}</div>
            @endforeach
        </div>
        <div class="jdc-cal-grid" id="cal-besar-grid"></div>

        {{-- ===== JADWAL HARI INI — section tetap, selalu tanggal hari ini ===== --}}
        <div class="jdc-hariini-title">
            <strong>Jadwal Hari Ini</strong>
            <span id="hariini-tanggal-label"></span>
        </div>
        <div class="jdc-hariini-list" id="list-jadwal-hariini"></div>
    </div>
</div>

<script>
const jadwalSebulan = @json($jadwalSebulan);
const bulanAwal = "{{ $bulanTampil->format('Y-m-01') }}";

let miniCalCursor = new Date(bulanAwal);
let besarCalCursor = new Date(bulanAwal);
let tanggalTerpilih = "{{ old('tanggal_pilihan', now()->format('Y-m-d')) }}";
const tanggalHariIni = new Date().toISOString().slice(0, 10); // FIXED, tidak berubah walau kalender dinavigasi

const namaBulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
const namaHari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
const MAKS_PIL_PER_SEL = 2;

function jumlahHari(y, m) { return new Date(y, m + 1, 0).getDate(); }
function hariPertama(y, m) { const d = new Date(y, m, 1).getDay(); return d === 0 ? 6 : d - 1; }

function renderMiniCal() {
    const y = miniCalCursor.getFullYear(), m = miniCalCursor.getMonth();
    document.getElementById('mini-cal-label').textContent = `${namaBulan[m]} ${y}`;
    const grid = document.getElementById('mini-cal-grid');
    grid.innerHTML = '';
    ['MIN','SEN','SEL','RAB','KAM','JUM','SAB'].forEach(d => grid.innerHTML += `<div class="dow">${d}</div>`);

    const offsetMinggu = new Date(y, m, 1).getDay();
    for (let i = 0; i < offsetMinggu; i++) grid.innerHTML += `<div class="jdc-mini-day blank"></div>`;

    const totalHari = jumlahHari(y, m);
    for (let tgl = 1; tgl <= totalHari; tgl++) {
        const iso = `${y}-${String(m+1).padStart(2,'0')}-${String(tgl).padStart(2,'0')}`;
        const aktif = iso === tanggalTerpilih ? 'selected' : '';
        grid.innerHTML += `<button type="button" class="jdc-mini-day ${aktif}" onclick="pilihTanggal('${iso}')">${tgl}</button>`;
    }
}

function renderCalBesar() {
    const y = besarCalCursor.getFullYear(), m = besarCalCursor.getMonth();
    document.getElementById('cal-besar-label').textContent = `${namaBulan[m]} ${y}`;
    const grid = document.getElementById('cal-besar-grid');
    grid.innerHTML = '';

    const offset = hariPertama(y, m);
    for (let i = 0; i < offset; i++) grid.innerHTML += `<div class="jdc-cal-cell" style="visibility:hidden"></div>`;

    const totalHari = jumlahHari(y, m);
    for (let tgl = 1; tgl <= totalHari; tgl++) {
        const iso = `${y}-${String(m+1).padStart(2,'0')}-${String(tgl).padStart(2,'0')}`;
        const itemHariItu = jadwalSebulan.filter(j => j.tanggal === iso).sort((a,b) => a.jam.localeCompare(b.jam));
        const aktif = iso === tanggalTerpilih ? 'selected' : '';

        let pilHtml = itemHariItu.slice(0, MAKS_PIL_PER_SEL).map(j =>
            `<span class="jdc-cal-time-pill ${j.mode}">${j.jam} · ${j.mode === 'online' ? 'On' : 'Off'}</span>`
        ).join('');

        if (itemHariItu.length > MAKS_PIL_PER_SEL) {
            pilHtml += `<div class="jdc-cal-more">+${itemHariItu.length - MAKS_PIL_PER_SEL} lagi</div>`;
        }

        grid.innerHTML += `
            <div class="jdc-cal-cell ${aktif}" onclick="pilihTanggal('${iso}')">
                <div class="num">${tgl}</div>
                ${pilHtml}
            </div>`;
    }
}

// Section "Jadwal Hari Ini" — SELALU pakai tanggalHariIni, tidak dipengaruhi navigasi kalender/klik tanggal
function renderJadwalHariIni() {
    const d = new Date(tanggalHariIni);
    document.getElementById('hariini-tanggal-label').textContent =
        `${namaHari[d.getDay()]}, ${d.getDate()} ${namaBulan[d.getMonth()]} ${d.getFullYear()}`;

    const items = jadwalSebulan.filter(j => j.tanggal === tanggalHariIni).sort((a,b) => a.jam.localeCompare(b.jam));
    const wrap = document.getElementById('list-jadwal-hariini');

    if (items.length === 0) {
        wrap.innerHTML = `<div class="jdc-hariini-empty">Belum ada jadwal untuk hari ini.</div>`;
        return;
    }

    wrap.innerHTML = items.map(j => `
        <div class="jdc-hariini-item ${j.mode}">
            <div class="jdc-hariini-time">${j.jam}</div>
            <div class="jdc-hariini-body">
                <strong>${j.lokasi_nama}</strong>
                <span class="jdc-hariini-desc">${j.deskripsi ? j.deskripsi : 'Kunjungan ke lokasi ini'}</span>
            </div>
            <span class="jdc-hariini-badge">${j.mode === 'online' ? 'Online' : 'Offline'}</span>
        </div>
    `).join('');
}

function pilihTanggal(iso) {
    tanggalTerpilih = iso;
    document.getElementById('input-tanggal').value = iso;
    document.getElementById('mini-cal-hint').textContent = `Tanggal dipilih: ${iso}`;
    renderMiniCal();
    renderCalBesar();
    gabungkanWaktu();
    // renderJadwalHariIni() SENGAJA TIDAK dipanggil di sini — section ini fixed hari ini
}

function ubahBulanForm(delta) { miniCalCursor.setMonth(miniCalCursor.getMonth() + delta); renderMiniCal(); }
function ubahBulanBesar(delta) { besarCalCursor.setMonth(besarCalCursor.getMonth() + delta); renderCalBesar(); }

function pilihHariIni() {
    const hariIni = new Date().toISOString().slice(0, 10);
    besarCalCursor = new Date();
    miniCalCursor = new Date();
    pilihTanggal(hariIni);
}

function pilihMode(mode) {
    document.getElementById('input-mode').value = mode;
    document.getElementById('pill-online').classList.toggle('active', mode === 'online');
    document.getElementById('pill-offline').classList.toggle('active', mode === 'offline');
    document.getElementById('label-wajib').style.display = mode === 'offline' ? 'inline' : 'none';
}

function gabungkanWaktu() {
    const tgl = document.getElementById('input-tanggal').value;
    const jam = document.getElementById('input-jam').value;
    if (tgl && jam) document.getElementById('input-waktu-mulai').value = `${tgl} ${jam}:00`;
}

    document.getElementById('input-jam').addEventListener('change', gabungkanWaktu);

    document.getElementById('form-jadwal').addEventListener('submit', function (e) {
        if (!document.getElementById('input-tanggal').value || !document.getElementById('input-jam').value) {
            e.preventDefault();
            if (window.Swal) {
                Swal.fire({ icon: 'warning', title: 'Lengkapi dulu', text: 'Tanggal dan Jam wajib diisi.', confirmButtonColor: '#0081AB' });
            } else {
                alert('Tanggal dan Jam wajib diisi.');
            }
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        pilihMode(document.getElementById('input-mode').value || 'offline');
        if (!tanggalTerpilih) tanggalTerpilih = new Date().toISOString().slice(0, 10);
        pilihTanggal(tanggalTerpilih);
        renderJadwalHariIni();
    });
</script>
@endsection    