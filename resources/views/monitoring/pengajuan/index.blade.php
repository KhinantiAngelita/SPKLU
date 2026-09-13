@extends('layouts.app')

@section('breadcrumb', 'Monitoring SPKLU')
@section('page-title', 'Pengajuan Integrasi SPKLU')

@section('content')

<style>
    /* Header halaman */
    .pgj-page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; flex-wrap:wrap; gap:10px; }
    .pgj-page-subtitle { color:#64748B; margin:0; font-size:13.5px; }

    /* Tombol */
    .pgj-btn { display:inline-flex; align-items:center; gap:7px; border:none; border-radius:9px; font-size:13.3px; font-weight:700; padding:10px 18px; cursor:pointer; transition:all .15s ease; text-decoration:none; }
    .pgj-btn svg { width:15px; height:15px; stroke-width:2.1; }
    .pgj-btn-primary { background:linear-gradient(135deg,#023E8A,#0081AB); color:#fff; box-shadow:0 2px 10px rgba(2,62,138,.25); }
    .pgj-btn-primary:hover { transform:translateY(-1px); box-shadow:0 4px 14px rgba(2,62,138,.32); }
    .pgj-btn-disabled { background:#eef1f5; color:#94a3b8; cursor:not-allowed; }
    .pgj-btn-disabled:hover { transform:none; }

    /* Board & kolom kanban */
    .pgj-board { display:grid; grid-template-columns:repeat(3, 1fr); gap:18px; align-items:start; }
    .pgj-col { background:#f6f8fa; border-radius:16px; overflow:hidden; border:1px solid #eef1f5; }
    .pgj-col-head { padding:14px 18px; display:flex; align-items:center; justify-content:space-between; color:#fff; font-weight:700; font-size:14px; }
    .pgj-col-head-belum { background:linear-gradient(135deg,#64748B,#475569); }
    .pgj-col-head-progress { background:linear-gradient(135deg,#023E8A,#0081AB); }
    .pgj-col-head-selesai { background:linear-gradient(135deg,#2E9E5B,#238a4c); }
    .pgj-col-count { background:rgba(255,255,255,.25); border-radius:999px; min-width:24px; height:24px; padding:0 7px; display:inline-flex; align-items:center; justify-content:center; font-size:12.5px; font-weight:800; }
    .pgj-col-body { padding:14px; display:flex; flex-direction:column; gap:10px; max-height:640px; overflow-y:auto; }
    .pgj-col-body::-webkit-scrollbar { width:5px; }
    .pgj-col-body::-webkit-scrollbar-thumb { background:#dbe1e8; border-radius:4px; }

    /* Kartu kandidat */
    .pgj-card { background:#fff; border-radius:12px; padding:14px 16px; border:1px solid #eef1f5; box-shadow:0 1px 2px rgba(15,23,42,.04); position:relative; }
    .pgj-card-title { font-size:13.8px; font-weight:700; color:#0f172a; margin:0 0 2px; padding-right:70px; }
    .pgj-card-ulp { font-size:11.5px; color:#94a3b8; margin:0 0 10px; }
    .pgj-card-tahap { display:flex; align-items:center; gap:6px; font-size:12px; color:#1E293B; margin-bottom:6px; }
    .pgj-card-tahap-dot { width:6px; height:6px; border-radius:999px; background:#0081AB; flex-shrink:0; }
    .pgj-card-update { font-size:11px; color:#94a3b8; }
    .pgj-card-validasi { position:absolute; top:12px; right:12px; background:#FFC629; color:#023E8A; font-size:10.5px; font-weight:800; padding:5px 12px; border-radius:999px; border:none; cursor:pointer; transition:all .15s ease; }
    .pgj-card-validasi:hover { background:#ffb700; transform:translateY(-1px); }
    .pgj-card-validasi-done { background:rgba(46,158,91,.14); color:#2E9E5B; cursor:default; }
    .pgj-card-validasi-done:hover { transform:none; }
    .pgj-empty { padding:30px 14px; text-align:center; color:#94a3b8; font-size:12.5px; }

    /* Modal Validasi */
    .val-modal-overlay { display:none; position:fixed; inset:0; background:rgba(15,23,42,.5); backdrop-filter:blur(2px); align-items:center; justify-content:center; z-index:60; }
    .val-modal-overlay.show { display:flex; }
    .val-modal { background:#fff; border-radius:16px; width:460px; max-width:92vw; box-shadow:0 24px 60px rgba(0,0,0,.25); overflow:hidden; }
    .val-modal-header { background:linear-gradient(135deg, rgba(46,158,91,.08), rgba(46,158,91,.14)); padding:20px 24px; }
    .val-modal-header h3 { margin:0 0 4px; font-size:16.5px; font-weight:800; color:#238a4c; }
    .val-modal-header p { margin:0; font-size:12.5px; color:#64748B; }
    .val-modal-body { padding:20px 24px; }
    .val-modal-body label { display:block; font-size:12.5px; font-weight:700; color:#475569; margin:14px 0 5px; }
    .val-modal-body label:first-child { margin-top:0; }
    .val-modal-body input, .val-modal-body select { width:100%; padding:10px 12px; border-radius:8px; border:1px solid #e2e8f0; font-size:13.5px; font-family:inherit; }
    .val-modal-body input:focus, .val-modal-body select:focus { outline:none; border-color:#2E9E5B; box-shadow:0 0 0 3px rgba(46,158,91,.14); }
    .val-modal-body .form-row-2 { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
    .val-modal-actions { display:flex; justify-content:flex-end; gap:8px; margin-top:22px; }
    .val-btn { border:none; border-radius:9px; font-size:13.3px; font-weight:700; padding:10px 18px; cursor:pointer; }
    .val-btn-outline { background:#fff; color:#1E293B; border:1px solid #e2e8f0; }
    .val-btn-primary { background:linear-gradient(135deg,#2E9E5B,#238a4c); color:#fff; }
</style>

<div class="pgj-page-header">
    <div>
        <h1 style="margin:0 0 4px; font-size:20px; font-weight:800; color:#0f172a;">Pengajuan Integrasi SPKLU</h1>
        <p class="pgj-page-subtitle">Ringkasan progres kandidat lokasi berdasarkan tahapan Probabilitas</p>
    </div>

    @if (Route::has('monitoring.kandidat-baru.create'))
        <a href="{{ route('monitoring.kandidat-baru.create') }}" class="pgj-btn pgj-btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"/>
                <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Tambah Kandidat Baru
        </a>
    @else
        <button type="button" class="pgj-btn pgj-btn-disabled" disabled title="Menunggu fitur Kandidat Baru selesai dibuat">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"/>
                <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Tambah Kandidat Baru
        </button>
    @endif
</div>

<div class="pgj-board">

    <div class="pgj-col">
        <div class="pgj-col-head pgj-col-head-belum">
            Belum Mulai
            <span class="pgj-col-count">{{ $belumMulai->count() }}</span>
        </div>
        <div class="pgj-col-body">
            @forelse ($belumMulai as $p)
                @include('monitoring.pengajuan._card', ['p' => $p, 'showValidasi' => false])
            @empty
                <p class="pgj-empty">Belum ada lokasi di tahap ini.</p>
            @endforelse
        </div>
    </div>

    <div class="pgj-col">
        <div class="pgj-col-head pgj-col-head-progress">
            On-Progress
            <span class="pgj-col-count">{{ $onProgress->count() }}</span>
        </div>
        <div class="pgj-col-body">
            @forelse ($onProgress as $p)
                @include('monitoring.pengajuan._card', ['p' => $p, 'showValidasi' => false])
            @empty
                <p class="pgj-empty">Belum ada lokasi di tahap ini.</p>
            @endforelse
        </div>
    </div>

    <div class="pgj-col">
        <div class="pgj-col-head pgj-col-head-selesai">
            Selesai Integrasi
            <span class="pgj-col-count">{{ $selesaiIntegrasi->count() }}</span>
        </div>
        <div class="pgj-col-body">
            @forelse ($selesaiIntegrasi as $p)
                @include('monitoring.pengajuan._card', ['p' => $p, 'showValidasi' => true])
            @empty
                <p class="pgj-empty">Belum ada lokasi di tahap ini.</p>
            @endforelse
        </div>
    </div>

</div>

{{-- Modal Validasi Integrasi --}}
<div class="val-modal-overlay" id="modal-validasi">
    <div class="val-modal">
        <div class="val-modal-header">
            <h3>Validasi Integrasi</h3>
            <p id="val-subjudul">Lengkapi data teknis sebelum masuk Master SPKLU</p>
        </div>

        <form method="POST" id="form-validasi">
            @csrf

            <div class="val-modal-body">
                <label>Nama Lokasi</label>
                <input type="text" id="val-nama-display" disabled style="background:#f8fafc; color:#64748B;">

                <label>ULP</label>
                <select name="ulp_mapping_id" id="val-ulp" required>
                    <option value="">Pilih ULP...</option>
                    @foreach ($ulpList as $ulp)
                        <option value="{{ $ulp->id }}" data-nama="{{ strtolower($ulp->nama_penuh) }}">{{ $ulp->nama_penuh }}</option>
                    @endforeach
                </select>

                <div class="form-row-2">
                    <div>
                        <label>Type</label>
                        <select name="type" required>
                            <option value="">Pilih...</option>
                            <option value="AC">AC</option>
                            <option value="DC">DC</option>
                        </select>
                    </div>
                    <div>
                        <label>Kepemilikan</label>
                        <select name="kepemilikan" required>
                            <option value="">Pilih...</option>
                            <option value="PLN">PLN</option>
                            <option value="Swasta">Swasta</option>
                        </select>
                    </div>
                </div>

                <div class="form-row-2">
                    <div>
                        <label>Kapasitas (kW)</label>
                        <input type="number" step="0.01" name="kw" id="val-kw" required>
                    </div>
                    <div>
                        <label>Nozzle</label>
                        <input type="number" name="nozzle" id="val-nozzle" required>
                    </div>
                </div>

                <div class="val-modal-actions">
                    <button type="button" class="val-btn val-btn-outline" onclick="document.getElementById('modal-validasi').classList.remove('show')">Batal</button>
                    <button type="submit" class="val-btn val-btn-primary">Validasi & Masukkan ke Master SPKLU</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function bukaModalValidasi(id, lokasi, ulpAsli, estimasiKw, estimasiNozzle) {
        document.getElementById('form-validasi').action = `/monitoring/pengajuan/${id}/validasi`;
        document.getElementById('val-subjudul').textContent = `Lengkapi data teknis "${lokasi}" sebelum masuk Master SPKLU`;
        document.getElementById('val-nama-display').value = lokasi;
        document.getElementById('val-kw').value = estimasiKw;
        document.getElementById('val-nozzle').value = estimasiNozzle > 0 ? estimasiNozzle : 1;

        // Coba cocokkan ULP otomatis (case-insensitive, partial match)
        const ulpSelect = document.getElementById('val-ulp');
        ulpSelect.value = '';

        const target = ulpAsli.toLowerCase();
        for (const opt of ulpSelect.options) {
            const namaOpt = opt.dataset.nama || '';
            if (namaOpt && (namaOpt.includes(target) || target.includes(namaOpt))) {
                ulpSelect.value = opt.value;
                break;
            }
        }

        document.getElementById('modal-validasi').classList.add('show');
    }
</script>

@endsection