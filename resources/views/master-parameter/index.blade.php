@extends('layouts.app')

@section('breadcrumb', 'Master Parameter')
@section('page-title', 'Master Parameter')

@section('content')

<style>
    .mp-subtitle { color:#64748B; margin:-6px 0 24px; font-size:13.5px; }

    /* Section divider & header */
    .mp-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin: 36px 0 16px;
        padding-top: 24px;
        border-top: 1px solid #E2E8F0;
        flex-wrap: wrap;
    }
    .mp-section-header:first-of-type {
        margin-top: 18px;
        padding-top: 0;
        border-top: none;
    }
    .mp-section-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .mp-section-title {
        font-size: 16px;
        font-weight: 800;
        color: #1B2559;
        margin: 0 0 3px 0;
        display: flex;
        align-items: center;
        gap: 8px;
        letter-spacing: -0.01em;
    }
    .mp-section-title svg {
        width: 18px;
        height: 18px;
        stroke-width: 2.2;
        color: #0081AB;
    }
    .mp-section-desc {
        font-size: 12.5px;
        color: #64748B;
        margin: 0;
    }

    .mp-grid-3 { display:grid; grid-template-columns:repeat(3, 1fr); gap:20px; margin-bottom:24px; }
    .mp-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:24px; }
    .mp-grid-full { grid-column: 1 / -1; }

    @media (max-width: 1024px) {
        .mp-grid-3 { grid-template-columns: 1fr; }
        .mp-grid-2 { grid-template-columns: 1fr; }
    }

    .mp-table { width:100%; border-collapse:collapse; }
    .mp-table thead th { background:#fafbfc; text-align:left; font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8; padding:12px 16px; border-bottom:1px solid #eef1f5; }
    .mp-table td { padding:10px 16px; font-size:13px; border-bottom:1px solid #f5f7fa; vertical-align:middle; }
    .mp-table tbody tr:nth-child(even) { background:#fbfcfd; }
    .mp-table tbody tr:hover { background:rgba(0,129,171,.04); }
    .mp-table tbody tr:last-child td { border-bottom:none; }
    
    .mp-table input[type="number"], .mp-table input[type="text"] {
        padding:6px 9px; border-radius:7px; border:1px solid #e2e8f0; font-size:12.5px;
        transition:all .15s ease;
    }
    .mp-table input:focus { outline:none; border-color:#0081AB; box-shadow:0 0 0 3px rgba(0,129,171,.12); }

    .mp-save-link { background:none; border:none; color:#0081AB; font-size:12px; font-weight:700; cursor:pointer; padding:4px 7px; border-radius:6px; transition:background .15s ease; }
    .mp-save-link:hover { background:rgba(0,129,171,.1); }

    .mp-del-btn, .mp-edit-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid transparent;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all .15s ease;
        padding: 0;
        vertical-align: middle;
        box-sizing: border-box;
    }
    .mp-edit-btn {
        background: rgba(245, 158, 11, 0.12);
        color: #D97706;
        margin-right: 4px;
    }
    .mp-edit-btn:hover {
        background: rgba(245, 158, 11, 0.22);
        color: #B45309;
        border-color: rgba(245, 158, 11, 0.35);
    }
    .mp-del-btn {
        background: rgba(192,57,43,.08);
        color: #C0392B;
    }
    .mp-del-btn:hover {
        background: rgba(192,57,43,.18);
        color: #962D22;
    }
    .mp-del-btn svg, .mp-edit-btn svg { width: 15px; height: 15px; stroke-width: 2.2; }

    .mp-badge-aktif { display:inline-flex; align-items:center; padding:2px 8px; border-radius:999px; font-size:11px; font-weight:700; background:#dcfce7; color:#15803d; }
    .mp-badge-nonaktif { display:inline-flex; align-items:center; padding:2px 8px; border-radius:999px; font-size:11px; font-weight:700; background:#f1f5f9; color:#64748b; }

    .mp-note { display:flex; gap:9px; align-items:flex-start; font-size:12px; color:#92660f; background:rgba(232,163,23,.08); border:1px solid rgba(232,163,23,.25); border-radius:9px; padding:11px 14px; margin-top:20px; }
    .mp-note svg { width:14px; height:14px; min-width:14px; margin-top:1px; stroke-width:2; }

    .mp-empty { text-align:center; padding:24px 16px; color:#94a3b8; font-size:12.5px; }

    .mp-card-form { display:flex; gap:10px; align-items:flex-end; padding:14px 16px 16px; background:#fafbfc; border-top:1px solid #edf2f7; flex-wrap:wrap; border-radius:0 0 12px 12px; }
    .mp-field label { display:block; font-size:11px; font-weight:700; color:#64748B; text-transform:uppercase; letter-spacing:.04em; margin-bottom:5px; }
    .mp-field input, .mp-field select { padding:8px 11px; border-radius:7px; border:1px solid #e2e8f0; font-size:13px; background:#fff; }
    .mp-field input:focus, .mp-field select:focus { outline:none; border-color:#0081AB; box-shadow:0 0 0 3px rgba(0,129,171,.12); }

    .mp-btn { border:none; border-radius:8px; font-size:12.5px; font-weight:700; padding:8px 16px; cursor:pointer; transition:all .15s ease; background:#023E8A; color:#fff; box-shadow:0 2px 6px rgba(2,62,138,.2); height:37px; display:inline-flex; align-items:center; gap:6px; }
    .mp-btn:hover { background:#002D66; transform:translateY(-1px); box-shadow:0 4px 10px rgba(2,62,138,.25); }

    /* Modal Styling */
    .mp-modal-backdrop { display:none; position:fixed; inset:0; background:rgba(15,23,42,.5); backdrop-filter:blur(3px); z-index:9999; align-items:center; justify-content:center; }
    .mp-modal-box { background:#fff; border-radius:14px; width:92%; max-width:480px; box-shadow:0 20px 40px rgba(0,0,0,.2); overflow:hidden; animation:modalPop .15s ease-out; }
    @keyframes modalPop { from { transform:scale(.95); opacity:0; } to { transform:scale(1); opacity:1; } }
    .mp-modal-header { padding:16px 20px; background:#023E8A; color:#fff; font-weight:700; font-size:15px; display:flex; justify-content:space-between; align-items:center; }
    .mp-modal-close { background:none; border:none; color:#fff; font-size:20px; cursor:pointer; line-height:1; opacity:.8; }
    .mp-modal-close:hover { opacity:1; }
    .mp-modal-body { padding:20px; }
</style>

<div style="margin-bottom: 8px;">
    <h1 style="font-size:22px; font-weight:800; color:#1B2559; margin:0 0 4px; letter-spacing:-0.015em;">Master Parameter</h1>
    <p class="mp-subtitle">Nilai referensi yang dipakai sistem untuk perhitungan skor probabilitas, kelayakan FS, dan daftar mitra mesin.</p>
</div>

{{-- SECTION 1: PARAMETER PENILAIAN LOKASI (3 CARDS) --}}
<div class="mp-section-header">
    <div class="mp-section-header-left">
        <div>
            <h2 class="mp-section-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                Parameter Penilaian Lokasi &amp; Bobot Poin
            </h2>
            <p class="mp-section-desc">Konfigurasi bobot penilaian teknis: kesiapan jaringan listrik, fasilitas penunjang, dan okupansi pasar</p>
        </div>
    </div>
</div>

<div class="mp-grid-3">

    {{-- CARD 1: POIN KESIAPAN JARINGAN --}}
    <div class="surface-card" style="display:flex; flex-direction:column; justify-content:space-between;">
        <div>
            <div class="section-header-bar">
                <div class="section-header-bar-left">
                    <div class="section-header-bar-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" y1="20" x2="12.01" y2="20"/></svg>
                    </div>
                    <div>
                        <h2>Poin Kesiapan Jaringan</h2>
                        <p>Skor kondisi jaringan (maks 20 poin)</p>
                    </div>
                </div>
            </div>
            <table class="mp-table">
                <thead><tr><th>Kondisi</th><th style="width:70px;">Poin</th><th style="width:90px; text-align:right;">Aksi</th></tr></thead>
                <tbody>
                    @forelse ($poinJaringan as $pj)
                    <tr>
                        <form method="POST" action="{{ route('master-parameter.poin-jaringan.update', $pj) }}">
                            @csrf @method('PATCH')
                            <td><input type="text" name="kondisi" value="{{ $pj->kondisi }}" style="width:100%;"></td>
                            <td><input type="number" name="poin" value="{{ $pj->poin }}" min="0" max="20" style="width:55px;"></td>
                            <td style="text-align:right; white-space:nowrap;">
                                <button type="submit" class="mp-save-link">Simpan</button>
                        </form>
                                <form method="POST" action="{{ route('master-parameter.poin-jaringan.destroy', $pj) }}" style="display:inline;"
                                      data-confirm="Hapus parameter kesiapan jaringan &quot;{{ $pj->kondisi }}&quot;?" data-confirm-type="danger">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="mp-del-btn" title="Hapus">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </button>
                                </form>
                            </td>
                    </tr>
                    @empty
                        <tr><td colspan="3" class="mp-empty">Belum ada data parameter jaringan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <form method="POST" action="{{ route('master-parameter.poin-jaringan.store') }}" class="mp-card-form">
            @csrf
            <div class="mp-field" style="flex:1; min-width:140px;">
                <label>Kondisi Baru</label>
                <input type="text" name="kondisi" required placeholder="Contoh: Sambungan Baru" style="width:100%;">
            </div>
            <div class="mp-field" style="width:70px;">
                <label>Poin</label>
                <input type="number" name="poin" min="0" max="20" required placeholder="10" style="width:100%;">
            </div>
            <button type="submit" class="mp-btn">+ Tambah</button>
        </form>
    </div>

    {{-- CARD 2: POIN FASILITAS --}}
    <div class="surface-card" style="display:flex; flex-direction:column; justify-content:space-between;">
        <div>
            <div class="section-header-bar">
                <div class="section-header-bar-left">
                    <div class="section-header-bar-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    </div>
                    <div>
                        <h2>Poin Fasilitas</h2>
                        <p>Fasilitas lokasi (maks 40 poin)</p>
                    </div>
                </div>
            </div>
            <table class="mp-table">
                <thead><tr><th>Nama Fasilitas</th><th style="width:65px;">Poin</th><th style="width:90px; text-align:right;">Aksi</th></tr></thead>
                <tbody>
                    @forelse ($poinFasilitas as $pf)
                    <tr>
                        <form method="POST" action="{{ route('master-parameter.poin-fasilitas.update', $pf) }}">
                            @csrf @method('PATCH')
                            <td><input type="text" name="nama" value="{{ $pf->nama }}" style="width:100%;"></td>
                            <td><input type="number" name="poin" value="{{ $pf->poin }}" min="0" max="40" style="width:55px;"></td>
                            <td style="text-align:right; white-space:nowrap;">
                                <button type="submit" class="mp-save-link">Simpan</button>
                        </form>
                                <form method="POST" action="{{ route('master-parameter.poin-fasilitas.destroy', $pf) }}" style="display:inline;"
                                      data-confirm="Hapus parameter fasilitas &quot;{{ $pf->nama }}&quot;?" data-confirm-type="danger">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="mp-del-btn" title="Hapus">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </button>
                                </form>
                            </td>
                    </tr>
                    @empty
                        <tr><td colspan="3" class="mp-empty">Belum ada data parameter fasilitas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <form method="POST" action="{{ route('master-parameter.poin-fasilitas.store') }}" class="mp-card-form">
            @csrf
            <div class="mp-field" style="flex:1; min-width:140px;">
                <label>Nama Fasilitas</label>
                <input type="text" name="nama" required placeholder="Contoh: Musholla" style="width:100%;">
            </div>
            <div class="mp-field" style="width:65px;">
                <label>Poin</label>
                <input type="number" name="poin" min="0" max="40" value="10" required style="width:100%;">
            </div>
            <button type="submit" class="mp-btn">+ Tambah</button>
        </form>
    </div>

    {{-- CARD 3: POIN OKUPANSI --}}
    <div class="surface-card" style="display:flex; flex-direction:column; justify-content:space-between;">
        <div>
            <div class="section-header-bar">
                <div class="section-header-bar-left">
                    <div class="section-header-bar-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <div>
                        <h2>Poin Okupansi</h2>
                        <p>Kondisi okupansi (maks 40 poin)</p>
                    </div>
                </div>
            </div>
            <table class="mp-table">
                <thead><tr><th>Kondisi Kawasan</th><th style="width:65px;">Poin</th><th style="width:90px; text-align:right;">Aksi</th></tr></thead>
                <tbody>
                    @forelse ($poinOkupansi as $po)
                    <tr>
                        <form method="POST" action="{{ route('master-parameter.poin-okupansi.update', $po) }}">
                            @csrf @method('PATCH')
                            <td><input type="text" name="nama" value="{{ $po->nama }}" style="width:100%;"></td>
                            <td><input type="number" name="poin" value="{{ $po->poin }}" min="0" max="40" style="width:55px;"></td>
                            <td style="text-align:right; white-space:nowrap;">
                                <button type="submit" class="mp-save-link">Simpan</button>
                        </form>
                                <form method="POST" action="{{ route('master-parameter.poin-okupansi.destroy', $po) }}" style="display:inline;"
                                      data-confirm="Hapus parameter okupansi &quot;{{ $po->nama }}&quot;?" data-confirm-type="danger">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="mp-del-btn" title="Hapus">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </button>
                                </form>
                            </td>
                    </tr>
                    @empty
                        <tr><td colspan="3" class="mp-empty">Belum ada data parameter okupansi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <form method="POST" action="{{ route('master-parameter.poin-okupansi.store') }}" class="mp-card-form">
            @csrf
            <div class="mp-field" style="flex:1; min-width:140px;">
                <label>Kondisi Baru</label>
                <input type="text" name="nama" required placeholder="Contoh: Kawasan Industri" style="width:100%;">
            </div>
            <div class="mp-field" style="width:65px;">
                <label>Poin</label>
                <input type="number" name="poin" min="0" max="40" value="10" required style="width:100%;">
            </div>
            <button type="submit" class="mp-btn">+ Tambah</button>
        </form>
    </div>

</div>

{{-- SECTION 2: MITRA MESIN (FULL CARD CRUD) --}}
<div class="mp-section-header">
    <div class="mp-section-header-left">
        <div>
            <h2 class="mp-section-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                Master Mitra Mesin SPKLU
            </h2>
            <p class="mp-section-desc">Daftar vendor penyedia unit charging station terdaftar untuk seleksi kandidat &amp; pemodelan kemitraan</p>
        </div>
    </div>
</div>

<div class="surface-card" style="margin-bottom:24px;">
    <div class="section-header-bar">
        <div class="section-header-bar-left">
            <div class="section-header-bar-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
            </div>
            <div>
                <h2>Daftar Mitra Mesin</h2>
                <p>Penyedia mesin SPKLU yang muncul pada pilihan monitoring probabilitas &amp; rekomendasi kandidat</p>
            </div>
        </div>
    </div>

    <table class="mp-table">
        <thead>
            <tr>
                <th style="width:50px;">No</th>
                <th>Nama Mitra Mesin</th>
                <th>Keterangan</th>
                <th style="width:110px;">Status</th>
                <th style="width:130px; text-align:right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($mitraMesin as $idx => $m)
            <tr>
                <td style="color:#94a3b8; font-weight:700;">{{ $idx + 1 }}</td>
                <td style="font-weight:700; color:#1B2559;">{{ $m->nama }}</td>
                <td style="color:#64748B;">{{ $m->keterangan ?? '—' }}</td>
                <td>
                    @if($m->is_aktif)
                        <span class="mp-badge-aktif">● Aktif</span>
                    @else
                        <span class="mp-badge-nonaktif">○ Nonaktif</span>
                    @endif
                </td>
                <td style="text-align:right; white-space:nowrap;">
                    <button type="button" class="mp-edit-btn" onclick="bukaModalEditMitra({{ $m->id }}, '{{ addslashes($m->nama) }}', '{{ addslashes($m->keterangan ?? '') }}', {{ $m->is_aktif ? 'true' : 'false' }})" title="Ubah Mitra Mesin">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4Z"/></svg>
                    </button>
                    <form method="POST" action="{{ route('master-parameter.mitra-mesin.destroy', $m) }}" style="display:inline;"
                          data-confirm="Hapus Mitra Mesin &quot;{{ $m->nama }}&quot;?" data-confirm-type="danger">
                        @csrf @method('DELETE')
                        <button type="submit" class="mp-del-btn" title="Hapus Mitra Mesin">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
                <tr><td colspan="5" class="mp-empty">Belum ada data mitra mesin.</td></tr>
            @endforelse
        </tbody>
    </table>

    <form method="POST" action="{{ route('master-parameter.mitra-mesin.store') }}" class="mp-card-form">
        @csrf
        <div class="mp-field" style="flex:1; min-width:200px;">
            <label>Nama Mitra Mesin Baru</label>
            <input type="text" name="nama" required placeholder="Contoh: PT Sumber Energi" style="width:100%;">
        </div>
        <div class="mp-field" style="flex:1.5; min-width:240px;">
            <label>Keterangan / Kontak (Opsional)</label>
            <input type="text" name="keterangan" placeholder="Contoh: Penyedia fast charging 60-120kW" style="width:100%;">
        </div>
        <div class="mp-field" style="width:120px;">
            <label>Status</label>
            <select name="is_aktif" style="width:100%;">
                <option value="1">Aktif</option>
                <option value="0">Nonaktif</option>
            </select>
        </div>
        <button type="submit" class="mp-btn">+ Tambah Mitra</button>
    </form>
</div>

{{-- SECTION 3: TARIF LISTRIK & TARGET TAHUNAN --}}
<div class="mp-section-header">
    <div class="mp-section-header-left">
        <div>
            <h2 class="mp-section-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                Tarif &amp; Target Operasional
            </h2>
            <p class="mp-section-desc">Parameter tarif dasar listrik layanan SPKLU dan sasaran jumlah unit terpasang tahunan</p>
        </div>
    </div>
</div>

<div class="mp-grid-2">

    {{-- CARD: TARIF LISTRIK --}}
    <div class="surface-card">
        <div class="section-header-bar">
            <div class="section-header-bar-left">
                <div class="section-header-bar-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
                <div>
                    <h2>Tarif Layanan Listrik</h2>
                    <p>Rp per kWh, sesuai jenis sambungan</p>
                </div>
            </div>
        </div>
        <table class="mp-table">
            <thead><tr><th>Kode</th><th>Tarif per kWh</th><th style="width:80px; text-align:right;"></th></tr></thead>
            <tbody>
                @forelse ($tarifListrik as $tarif)
                <tr>
                    <form method="POST" action="{{ route('master-parameter.tarif.update', $tarif) }}">
                        @csrf @method('PATCH')
                        <td style="font-weight:700; color:#023E8A;">{{ $tarif->kode }}</td>
                        <td><input type="number" step="0.01" name="tarif_per_kwh" value="{{ $tarif->tarif_per_kwh }}" style="width:120px;"></td>
                        <td style="text-align:right;"><button type="submit" class="mp-save-link">Simpan</button></td>
                    </form>
                </tr>
                @empty
                    <tr><td colspan="3" class="mp-empty">Belum ada data tarif.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- CARD: TARGET TAHUNAN --}}
    <div class="surface-card" style="display:flex; flex-direction:column; justify-content:space-between;">
        <div>
            <div class="section-header-bar">
                <div class="section-header-bar-left">
                    <div class="section-header-bar-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg></div>
                    <div>
                        <h2>Target Tahunan</h2>
                        <p>Target jumlah SPKLU terpasang per tahun</p>
                    </div>
                </div>
            </div>
            <table class="mp-table">
                <thead><tr><th>Tahun</th><th>Target Jumlah SPKLU</th><th style="width:60px; text-align:right;"></th></tr></thead>
                <tbody>
                    @forelse ($targetTahunan as $target)
                        <tr>
                            <td style="font-weight:700; color:#1B2559;">{{ $target->tahun }}</td>
                            <td>{{ number_format($target->target_jumlah_spklu) }} unit</td>
                            <td style="text-align:right;">
                                <form method="POST" action="{{ route('master-parameter.target.destroy', $target) }}" style="display:inline;"
                                      data-confirm="Hapus target tahun {{ $target->tahun }}?" data-confirm-type="danger">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="mp-del-btn" title="Hapus">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="mp-empty">Belum ada target yang ditentukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <form method="POST" action="{{ route('master-parameter.target.store') }}" class="mp-card-form">
            @csrf
            <div class="mp-field" style="width:110px;">
                <label>Tahun</label>
                <input type="number" name="tahun" min="2020" required placeholder="2027" style="width:100%;">
            </div>
            <div class="mp-field" style="flex:1; min-width:140px;">
                <label>Target SPKLU (Unit)</label>
                <input type="number" name="target_jumlah_spklu" min="0" required placeholder="50" style="width:100%;">
            </div>
            <button type="submit" class="mp-btn">+ Tambah</button>
        </form>
    </div>

</div>

<div class="mp-note">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    <span><strong>Perhatian:</strong> Mengubah nilai parameter atau mitra di atas akan langsung memengaruhi penilaian &amp; pilihan baru. Data lokasi yang sudah dinilai sebelumnya tidak diubah otomatis.</span>
</div>

{{-- MODAL EDIT MITRA MESIN --}}
<div class="mp-modal-backdrop" id="modal-edit-mitra">
    <div class="mp-modal-box">
        <div class="mp-modal-header">
            <span>Edit Mitra Mesin</span>
            <button type="button" class="mp-modal-close" onclick="tutupModalEditMitra()">&times;</button>
        </div>
        <form method="POST" id="form-edit-mitra" class="mp-modal-body">
            @csrf
            @method('PATCH')
            <div style="margin-bottom:14px;">
                <label style="display:block; font-size:12px; font-weight:700; color:#475569; margin-bottom:6px;">Nama Mitra Mesin</label>
                <input type="text" name="nama" id="edit-mitra-nama" required style="width:100%; padding:9px 12px; border:1px solid #cbd5e1; border-radius:8px; font-size:13.5px;">
            </div>
            <div style="margin-bottom:14px;">
                <label style="display:block; font-size:12px; font-weight:700; color:#475569; margin-bottom:6px;">Keterangan / Kontak</label>
                <input type="text" name="keterangan" id="edit-mitra-keterangan" style="width:100%; padding:9px 12px; border:1px solid #cbd5e1; border-radius:8px; font-size:13.5px;">
            </div>
            <div style="margin-bottom:20px;">
                <label style="display:flex; align-items:center; gap:8px; font-size:13px; font-weight:600; color:#334155; cursor:pointer;">
                    <input type="checkbox" name="is_aktif" id="edit-mitra-aktif" value="1" style="width:16px; height:16px;">
                    <span>Mitra Aktif (muncul dalam pilihan sistem)</span>
                </label>
            </div>
            <div style="display:flex; justify-content:flex-end; gap:8px;">
                <button type="button" class="btn btn-secondary" onclick="tutupModalEditMitra()" style="padding:8px 16px; border-radius:8px; font-size:13px; font-weight:600;">Batal</button>
                <button type="submit" class="mp-btn" style="height:auto; padding:8px 18px;">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function bukaModalEditMitra(id, nama, keterangan, isAktif) {
        const form = document.getElementById('form-edit-mitra');
        form.action = `/master-parameter/mitra-mesin/${id}`;
        document.getElementById('edit-mitra-nama').value = nama;
        document.getElementById('edit-mitra-keterangan').value = keterangan;
        document.getElementById('edit-mitra-aktif').checked = Boolean(isAktif);
        document.getElementById('modal-edit-mitra').style.display = 'flex';
    }

    function tutupModalEditMitra() {
        document.getElementById('modal-edit-mitra').style.display = 'none';
    }

    window.addEventListener('click', function(e) {
        const modal = document.getElementById('modal-edit-mitra');
        if (e.target === modal) {
            tutupModalEditMitra();
        }
    });
</script>

@endsection