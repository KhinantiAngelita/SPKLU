@extends('layouts.app')

@section('breadcrumb', 'Penjadwalan')
@section('page-title', 'Buat Jadwal')

@section('content')

<style>
    .jdf-section-title { font-size:11.5px; font-weight:700; letter-spacing:.05em; text-transform:uppercase; color:#0081AB; margin:0 0 14px; }

    .jdf-alert-error { background:rgba(192,57,43,.08); border:1px solid rgba(192,57,43,.25); color:#C0392B; border-radius:10px; padding:12px 16px; font-size:13px; margin:0 auto 18px; max-width:460px; }

    .jdf-card { background:#fff; border-radius:16px; box-shadow:0 1px 3px rgba(15,23,42,.06), 0 8px 24px rgba(15,23,42,.06); max-width:460px; margin:0 auto 20px; overflow:hidden; }

    .jdf-form-head { display:flex; align-items:center; gap:14px; padding:22px 24px; background:linear-gradient(150deg, rgba(2,62,138,.06), rgba(0,129,171,.10)); border-bottom:1px solid #F1F5F9; }
    .jdf-icon-box { width:44px; height:44px; border-radius:12px; background:linear-gradient(135deg, #023E8A, #0081AB); color:#fff; display:flex; align-items:center; justify-content:center; flex-shrink:0; box-shadow:0 4px 12px rgba(2,62,138,.25); }
    .jdf-icon-box svg { width:20px; height:20px; stroke-width:2.3; }
    .jdf-form-head strong { font-size:17px; font-weight:800; color:#023E8A; display:block; letter-spacing:-.01em; }
    .jdf-form-head span { font-size:12.5px; color:#64748B; }

    .jdf-form-body { padding:20px 24px 26px; }
    .jdf-form-body label { display:block; font-size:12.5px; font-weight:700; color:#475569; margin:16px 0 7px; }
    .jdf-form-body > label:first-child { margin-top:0; }
    .jdf-form-body select, .jdf-form-body input[type="text"], .jdf-form-body input[type="date"], .jdf-form-body input[type="time"] {
        width:100%; padding:10px 12px; border-radius:10px; border:1px solid #e2e8f0; font-size:13.5px; font-family:inherit; background:#F8FAFC; color:#334155; transition:border-color .15s ease, box-shadow .15s ease, background .15s ease;
    }
    .jdf-form-body select:focus, .jdf-form-body input:focus { outline:none; border-color:#0081AB; box-shadow:0 0 0 3px rgba(0,129,171,.12); background:#fff; }
    .jdf-hint { font-size:11px; color:#94a3b8; margin-top:5px; }

    .jdf-mini-cal { background:#F8FAFC; border:1px solid #EEF1F5; border-radius:12px; padding:12px; }
    .jdf-mini-cal-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:10px; }
    .jdf-mini-cal-head strong { font-size:13px; color:#0F172A; margin:0; }
    .jdf-mini-cal-nav button { width:24px; height:24px; border-radius:7px; border:1px solid #e2e8f0; background:#fff; color:#64748B; cursor:pointer; display:flex; align-items:center; justify-content:center; }
    .jdf-mini-cal-nav button:hover { background:#F1F5F9; }
    .jdf-mini-grid { display:grid; grid-template-columns:repeat(7,1fr); gap:3px; text-align:center; }
    .jdf-mini-grid .dow { font-size:10px; font-weight:700; color:#94A3B8; padding-bottom:4px; }
    .jdf-mini-day { aspect-ratio:1; display:flex; align-items:center; justify-content:center; font-size:12px; color:#334155; border-radius:7px; cursor:pointer; background:transparent; border:none; transition:background .12s ease; }
    .jdf-mini-day:hover { background:#EEF2FF; }
    .jdf-mini-day.selected { background:#023E8A; color:#fff; font-weight:700; }
    .jdf-mini-day.blank { visibility:hidden; cursor:default; }

    .jdf-mode-group { display:grid; grid-template-columns:1fr 1fr; gap:8px; }
    .jdf-mode-pill { text-align:center; padding:11px; border-radius:10px; font-size:13.5px; font-weight:700; color:#94A3B8; background:#F1F5F9; cursor:pointer; border:1.5px solid transparent; transition:all .15s ease; }
    .jdf-mode-pill.active-online { background:rgba(46,158,91,.12); color:#2E9E5B; border-color:rgba(46,158,91,.3); }
    .jdf-mode-pill.active-offline { background:rgba(2,62,138,.08); color:#023E8A; border-color:rgba(2,62,138,.25); }

    .jdf-btn { display:inline-flex; align-items:center; justify-content:center; gap:7px; border:none; border-radius:11px; font-size:13.5px; font-weight:700; padding:13px 20px; cursor:pointer; text-decoration:none; transition:all .15s ease; }
    .jdf-btn-outline { background:#fff; color:#475569; border:1px solid #E2E8F0; }
    .jdf-btn-outline:hover { background:#F8FAFC; border-color:#CBD5E1; }
    .jdf-btn-primary { flex:1; background:linear-gradient(135deg, #023E8A, #0081AB); color:#fff; box-shadow:0 6px 16px rgba(2,62,138,.25); border:none; }
    .jdf-btn-primary:hover { transform:translateY(-1px); box-shadow:0 8px 20px rgba(2,62,138,.32); }
    .jdf-form-actions { display:flex; gap:10px; margin-top:24px; }
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
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        </div>
        <div>
            <strong>Buat Jadwal Baru</strong>
            <span>Jadwalkan kunjungan atau pertemuan baru</span>
        </div>
    </div>

    <form method="POST" action="{{ route('penjadwalan.store') }}" id="form-jadwal" class="jdf-form-body">
        @csrf

        <div class="jdf-section-title">Detail Jadwal</div>

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
        <div class="jdf-mini-cal">
            <div class="jdf-mini-cal-head">
                <div class="jdf-mini-cal-nav"><button type="button" onclick="ubahBulanForm(-1)">&lsaquo;</button></div>
                <strong id="mini-cal-label"></strong>
                <div class="jdf-mini-cal-nav"><button type="button" onclick="ubahBulanForm(1)">&rsaquo;</button></div>
            </div>
            <div class="jdf-mini-grid" id="mini-cal-grid"></div>
        </div>
        <input type="hidden" name="tanggal_pilihan" id="input-tanggal" value="{{ old('tanggal_pilihan') }}">
        <p class="jdf-hint" id="mini-cal-hint">Klik salah satu tanggal di atas.</p>

        <label>Pilih Jam</label>
        <select id="input-jam" required>
            <option value="">Pilih Jam Kunjungan</option>
            @foreach (['08:00','09:00','10:00','11:00','13:00','14:00','15:00','16:00'] as $jam)
                <option value="{{ $jam }}" @selected(old('jam') === $jam)>{{ $jam }}</option>
            @endforeach
        </select>
        <input type="hidden" name="waktu_mulai" id="input-waktu-mulai" value="{{ old('waktu_mulai') }}">

        <label>Mode Pertemuan</label>
        <div class="jdf-mode-group">
            <div class="jdf-mode-pill" id="pill-online" onclick="pilihMode('online')">Online</div>
            <div class="jdf-mode-pill" id="pill-offline" onclick="pilihMode('offline')">Offline</div>
        </div>
        <input type="hidden" name="mode" id="input-mode" value="{{ old('mode', 'offline') }}" required>

        {{-- OFFLINE: Lokasi kunjungan --}}
        <div id="blok-lokasi-offline">
            <label>Lokasi <span id="label-wajib-lokasi" style="display:none;color:#C0392B;">*</span></label>
            <input type="text" name="lokasi" value="{{ old('lokasi') }}" placeholder="Masukan alamat lokasi kunjungan">
        </div>

        {{-- ONLINE: Platform + Link Pertemuan --}}
        <div id="blok-online" style="display:none">
            <label>Platform</label>
            <select name="platform">
                <option value="">Pilih platform...</option>
                <option value="Zoom" @selected(old('platform') === 'Zoom')>Zoom</option>
                <option value="Google Meet" @selected(old('platform') === 'Google Meet')>Google Meet</option>
                <option value="Lainnya" @selected(old('platform') === 'Lainnya')>Lainnya</option>
            </select>

            <label>Link Pertemuan</label>
            <input type="text" name="link_pertemuan" value="{{ old('link_pertemuan') }}" placeholder="https://zoom.us/j/xxxxxxxxxx">
        </div>

        <label>Penanggung Jawab</label>
        <select name="penanggung_jawab">
            <option value="">Belum ditentukan</option>
            @foreach ($users as $u)
                <option value="{{ $u->id }}" @selected(old('penanggung_jawab') == $u->id)>{{ $u->name }}</option>
            @endforeach
        </select>

        <div class="jdf-form-actions">
            <a href="{{ route('penjadwalan.index') }}" class="jdf-btn jdf-btn-outline">Batal</a>
            <button type="submit" class="jdf-btn jdf-btn-primary">Simpan Jadwal</button>
        </div>
    </form>
</div>

<script>
// Kalau datang dari klik "+ Tambah Jadwal" di modal kalender (index), tanggal
// bisa ke-prefill lewat ?tanggal=YYYY-MM-DD di URL.
const tanggalDariQuery = new URLSearchParams(window.location.search).get('tanggal');

let miniCalCursor = new Date();
let tanggalTerpilih = "{{ old('tanggal_pilihan') }}" || tanggalDariQuery || new Date().toISOString().slice(0, 10);

const namaBulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

function jumlahHari(y, m) { return new Date(y, m + 1, 0).getDate(); }

function renderMiniCal() {
    const y = miniCalCursor.getFullYear(), m = miniCalCursor.getMonth();
    document.getElementById('mini-cal-label').textContent = `${namaBulan[m]} ${y}`;
    const grid = document.getElementById('mini-cal-grid');
    grid.innerHTML = '';
    ['MIN','SEN','SEL','RAB','KAM','JUM','SAB'].forEach(d => grid.innerHTML += `<div class="dow">${d}</div>`);

    const offsetMinggu = new Date(y, m, 1).getDay();
    for (let i = 0; i < offsetMinggu; i++) grid.innerHTML += `<div class="jdf-mini-day blank"></div>`;

    const totalHari = jumlahHari(y, m);
    for (let tgl = 1; tgl <= totalHari; tgl++) {
        const iso = `${y}-${String(m+1).padStart(2,'0')}-${String(tgl).padStart(2,'0')}`;
        const aktif = iso === tanggalTerpilih ? 'selected' : '';
        grid.innerHTML += `<button type="button" class="jdf-mini-day ${aktif}" onclick="pilihTanggal('${iso}')">${tgl}</button>`;
    }
}

function pilihTanggal(iso) {
    tanggalTerpilih = iso;
    document.getElementById('input-tanggal').value = iso;
    document.getElementById('mini-cal-hint').textContent = `Tanggal dipilih: ${iso}`;
    renderMiniCal();
    gabungkanWaktu();
}

function ubahBulanForm(delta) { miniCalCursor.setMonth(miniCalCursor.getMonth() + delta); renderMiniCal(); }

function pilihMode(mode) {
    document.getElementById('input-mode').value = mode;
    document.getElementById('pill-online').classList.toggle('active-online', mode === 'online');
    document.getElementById('pill-offline').classList.toggle('active-offline', mode === 'offline');

    document.getElementById('blok-lokasi-offline').style.display = mode === 'offline' ? 'block' : 'none';
    document.getElementById('blok-online').style.display = mode === 'online' ? 'block' : 'none';
    document.getElementById('label-wajib-lokasi').style.display = mode === 'offline' ? 'inline' : 'none';
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
            Swal.fire({ icon: 'warning', title: 'Lengkapi dulu', text: 'Tanggal dan Jam wajib diisi.', confirmButtonColor: '#023E8A' });
        } else {
            alert('Tanggal dan Jam wajib diisi.');
        }
    }
});

document.addEventListener('DOMContentLoaded', () => {
    pilihMode(document.getElementById('input-mode').value || 'offline');
    miniCalCursor = new Date(tanggalTerpilih);
    pilihTanggal(tanggalTerpilih);
});
</script>
@endsection