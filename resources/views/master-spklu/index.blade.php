@extends('layouts.app')

@section('breadcrumb', 'Master SPKLU')
@section('page-title', 'Master SPKLU')

@section('content')

<style>
    .msp-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:26px; gap:16px; flex-wrap:wrap; }
    .msp-subtitle { color:#64748B; margin:0; font-size:14px; }
    .msp-actions { display:flex; gap:10px; }

    .msp-btn { border:none; border-radius:9px; font-size:13.5px; font-weight:600; padding:10px 18px; cursor:pointer; transition:all .15s ease; }
    .msp-btn-primary { background:linear-gradient(135deg,#023E8A,#0081AB); color:#fff; box-shadow:0 2px 10px rgba(2,62,138,.25); }
    .msp-btn-primary:hover { transform:translateY(-1px); box-shadow:0 4px 14px rgba(2,62,138,.32); }
    .msp-btn-outline { background:#fff; color:#1E293B; border:1px solid #e2e8f0; }
    .msp-btn-outline:hover { background:#f8fafc; border-color:#cbd5e1; }
    .msp-btn-warning { background:#FFC629; color:#023E8A; font-weight:700; box-shadow:0 2px 8px rgba(255,198,41,.4); }

    .msp-card-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:18px; margin-bottom:24px; }
    .msp-card {
        background:#fff; border-radius:16px; padding:22px; border:1px solid #eef1f5;
        box-shadow:0 1px 2px rgba(15,23,42,.04), 0 6px 16px rgba(15,23,42,.05);
        display:flex; justify-content:space-between; align-items:flex-start;
        transition:transform .18s ease, box-shadow .18s ease;
    }
    .msp-card:hover { transform:translateY(-2px); box-shadow:0 4px 8px rgba(15,23,42,.06), 0 14px 28px rgba(15,23,42,.09); }
    .msp-card-label { font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:.05em; color:#94a3b8; margin:0 0 8px; }
    .msp-card-value { font-size:25px; font-weight:800; letter-spacing:-.02em; color:#0f172a; margin:0; }
    .msp-card-note { font-size:11px; color:#94a3b8; margin:6px 0 0; }
    .msp-card-icon { width:46px; height:46px; border-radius:13px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .msp-card-icon i { width:22px; height:22px; }
    .msp-ic-blue  { background:linear-gradient(135deg, rgba(2,62,138,.12), rgba(0,129,171,.12)); color:#023E8A; }
    .msp-ic-green { background:linear-gradient(135deg, rgba(46,158,91,.14), rgba(46,158,91,.06)); color:#2E9E5B; }
    .msp-ic-amber { background:linear-gradient(135deg, rgba(232,163,23,.15), rgba(232,163,23,.06)); color:#E8A317; }
    .msp-ic-red   { background:linear-gradient(135deg, rgba(192,57,43,.14), rgba(192,57,43,.06)); color:#C0392B; }

    .msp-banner { background:linear-gradient(135deg, rgba(255,198,41,.12), rgba(232,163,23,.08)); border:1px solid rgba(232,163,23,.35); border-radius:12px; padding:14px 20px; display:flex; align-items:center; justify-content:space-between; margin-bottom:22px; color:#92660f; font-size:13.5px; font-weight:500; }

    .msp-table-box { background:#fff; border-radius:18px; border:1px solid #eef1f5; box-shadow:0 1px 2px rgba(15,23,42,.04), 0 6px 20px rgba(15,23,42,.05); overflow:hidden; }
    .msp-table-head { padding:22px 24px 0; display:flex; align-items:center; gap:10px; }
    .msp-table-head-icon { width:32px; height:32px; border-radius:9px; background:linear-gradient(135deg,#023E8A,#0081AB); display:flex; align-items:center; justify-content:center; }
    .msp-table-head-icon i { width:16px; height:16px; color:#fff; }
    .msp-table-head h2 { margin:0; font-size:16.5px; font-weight:700; color:#0f172a; }

    .msp-filters { display:flex; gap:10px; flex-wrap:wrap; align-items:center; padding:16px 24px 20px; }
    .msp-field { position:relative; }
    .msp-field i { position:absolute; left:12px; top:50%; transform:translateY(-50%); width:15px; height:15px; color:#94a3b8; pointer-events:none; }
    .msp-input, .msp-select {
        padding:10px 14px 10px 34px; border-radius:9px; border:1px solid #e2e8f0; font-size:13.5px;
        background:#fff; color:#1E293B;
    }
    .msp-input { min-width:200px; }
    .msp-select { cursor:pointer; min-width:150px; appearance:none;
        background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6' fill='none'%3E%3Cpath d='M1 1L5 5L9 1' stroke='%2394a3b8' stroke-width='1.6' stroke-linecap='round'/%3E%3C/svg%3E");
        background-repeat:no-repeat; background-position:right 12px center; padding-right:30px;
    }
    .msp-input:focus, .msp-select:focus { outline:none; border-color:#0081AB; box-shadow:0 0 0 3px rgba(0,129,171,.12); }
    .msp-reset { font-size:13px; font-weight:600; color:#0081AB; background:none; border:none; cursor:pointer; }

    .msp-table-wrap { overflow-x:auto; }
    .msp-table { width:100%; border-collapse:collapse; }
    .msp-table thead th {
        background:#fafbfc; text-align:left; font-size:10.5px; font-weight:700; text-transform:uppercase;
        letter-spacing:.06em; color:#94a3b8; padding:13px 24px; border-top:1px solid #eef1f5; border-bottom:1px solid #eef1f5; white-space:nowrap;
    }
    .msp-table tbody td { padding:15px 24px; font-size:13.5px; color:#1E293B; border-bottom:1px solid #f5f7fa; }
    .msp-table tbody tr:nth-child(even) { background:#fbfcfd; }
    .msp-table tbody tr:hover { background:rgba(0,129,171,.05); }
    .msp-table tbody tr:last-child td { border-bottom:none; }

    .msp-pill { display:inline-flex; align-items:center; padding:4px 11px; border-radius:999px; font-size:11px; font-weight:700; letter-spacing:.02em; }
    .msp-pill-type { background:#eef2f7; color:#334155; }
    .msp-pill-custom { background:rgba(232,163,23,.15); color:#92660f; margin-left:6px; }

    .msp-del-btn { width:32px; height:32px; border-radius:8px; border:none; background:rgba(255,198,41,.15); cursor:pointer; font-size:14px; transition:background .15s ease; }
    .msp-del-btn:hover { background:rgba(0,129,171,.15); }

    .msp-empty { text-align:center; padding:60px 20px; color:#94a3b8; }
    .msp-empty i { width:38px; height:38px; color:#cbd5e1; margin-bottom:10px; }
    .msp-empty p { margin:0; font-size:13.5px; }

    .msp-pagination { display:flex; align-items:center; justify-content:space-between; padding:16px 24px; border-top:1px solid #f1f5f9; flex-wrap:wrap; gap:12px; }
    .msp-pagination-info { font-size:12.5px; color:#94a3b8; margin:0; }
    .msp-pagination-links { display:flex; align-items:center; gap:4px; }
    .msp-pagination-links a, .msp-pagination-links span {
        display:inline-flex; align-items:center; justify-content:center; min-width:32px; height:32px;
        border-radius:8px; font-size:13px; color:#64748B;
    }
    .msp-pagination-links a:hover { background:#f1f5f9; color:#1E293B; }
    .msp-pagination-links .active { background:linear-gradient(135deg,#023E8A,#0081AB); color:#fff; font-weight:700; }
    .msp-pagination-links .disabled { color:#cbd5e1; }

    .msp-modal-overlay { display:none; position:fixed; inset:0; background:rgba(15,23,42,.45); align-items:center; justify-content:center; z-index:50; }
    .msp-modal { background:#fff; border-radius:16px; padding:26px; width:440px; max-width:92vw; box-shadow:0 20px 50px rgba(0,0,0,.2); max-height:90vh; overflow-y:auto; }
    .msp-modal h3 { margin:0 0 6px; font-size:17px; }
    .msp-modal label { display:block; font-size:12.5px; font-weight:600; color:#475569; margin:14px 0 5px; }
    .msp-modal input, .msp-modal select { width:100%; padding:10px 12px; border-radius:8px; border:1px solid #e2e8f0; font-size:13.5px; }
    .msp-modal input:focus, .msp-modal select:focus { outline:none; border-color:#0081AB; box-shadow:0 0 0 3px rgba(0,129,171,.12); }

    .msp-dropzone { border:2px dashed #cbd5e1; border-radius:12px; padding:32px; text-align:center; color:#64748B; cursor:pointer; font-size:13.5px; transition:all .15s ease; }
    .msp-dropzone:hover { border-color:#0081AB; background:rgba(0,129,171,.03); }

    .alert-error { background:rgba(192,57,43,.08); border:1px solid rgba(192,57,43,.25); color:#C0392B; border-radius:10px; padding:12px 16px; font-size:13.5px; margin-bottom:18px; }
    .alert-success { background:rgba(46,158,91,.08); border:1px solid rgba(46,158,91,.25); color:#2E9E5B; border-radius:10px; padding:12px 16px; font-size:13.5px; margin-bottom:18px; }
</style>

<div class="msp-header">
    <p class="msp-subtitle">Daftar SPKLU yang sudah aktif di sistem</p>
    @if (in_array(auth()->user()->role, ['super_admin', 'pengelola']))
        <div class="msp-actions">
            <button class="msp-btn msp-btn-outline" data-open-modal="modal-import-spklu">↑ Import Excel</button>
            <button class="msp-btn msp-btn-primary" data-open-modal="modal-tambah-spklu">+ Tambah SPKLU</button>
        </div>
    @endif
</div>

<div class="msp-card-grid">
    <div class="msp-card">
        <div>
            <p class="msp-card-label">Total Unit SPKLU</p>
            <p class="msp-card-value">{{ $totalUnit }}</p>
        </div>
        <div class="msp-card-icon msp-ic-blue"><i data-lucide="zap"></i></div>
    </div>
    <div class="msp-card">
        <div>
            <p class="msp-card-label">Berdasarkan Type</p>
            <p class="msp-card-value">{{ $totalByType['DC'] ?? 0 }} DC / {{ $totalByType['AC'] ?? 0 }} AC</p>
        </div>
        <div class="msp-card-icon msp-ic-green"><i data-lucide="plug-zap"></i></div>
    </div>
    <div class="msp-card">
        <div>
            <p class="msp-card-label">Berdasarkan Kepemilikan</p>
            <p class="msp-card-value">{{ $totalByKepemilikan['PLN'] ?? 0 }} PLN / {{ $totalByKepemilikan['Swasta'] ?? 0 }} Swasta</p>
        </div>
        <div class="msp-card-icon msp-ic-amber"><i data-lucide="building-2"></i></div>
    </div>
    <div class="msp-card">
        <div>
            <p class="msp-card-label">Total Kapasitas</p>
            <p class="msp-card-value">{{ number_format($totalKapasitas, 0) }} kW</p>
            <p class="msp-card-note">*tidak termasuk unit custom</p>
        </div>
        <div class="msp-card-icon msp-ic-red"><i data-lucide="battery-charging"></i></div>
    </div>
</div>

@if (session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert-error">{{ session('error') }}</div>
@endif

@if ($menungguValidasiCount > 0 && auth()->user()->role === 'super_admin')
<div class="msp-banner">
    <span>⚠ Ada {{ $menungguValidasiCount }} data baru yang ditambahkan ke Master SPKLU dan menunggu validasi</span>
    <a href="{{ route('master-spklu.validasi') }}" class="msp-btn msp-btn-warning">Validasi</a>
</div>
@endif

<div class="msp-table-box">

    <div class="msp-table-head">
        <div class="msp-table-head-icon"><i data-lucide="list"></i></div>
        <h2>Daftar SPKLU</h2>
    </div>

    <form method="GET" class="msp-filters">
        <div class="msp-field">
            <i data-lucide="search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama SPKLU..." class="msp-input">
        </div>

        <div class="msp-field">
            <i data-lucide="map-pin"></i>
            <select name="ulp_id" class="msp-select" onchange="this.form.submit()">
                <option value="">Semua ULP</option>
                @foreach ($ulpList as $ulp)
                    <option value="{{ $ulp->id }}" {{ request('ulp_id') == $ulp->id ? 'selected' : '' }}>{{ $ulp->nama_penuh }}</option>
                @endforeach
            </select>
        </div>

        <div class="msp-field">
            <i data-lucide="zap"></i>
            <select name="type" class="msp-select" onchange="this.form.submit()">
                <option value="">Semua Type</option>
                <option value="AC" {{ request('type') === 'AC' ? 'selected' : '' }}>AC</option>
                <option value="DC" {{ request('type') === 'DC' ? 'selected' : '' }}>DC</option>
            </select>
        </div>

        <div class="msp-field">
            <i data-lucide="building"></i>
            <select name="kepemilikan" class="msp-select" onchange="this.form.submit()">
                <option value="">Semua Kepemilikan</option>
                <option value="PLN" {{ request('kepemilikan') === 'PLN' ? 'selected' : '' }}>PLN</option>
                <option value="Swasta" {{ request('kepemilikan') === 'Swasta' ? 'selected' : '' }}>Swasta</option>
            </select>
        </div>

        @if (request()->anyFilled(['search', 'ulp_id', 'type', 'kepemilikan']))
            <button type="button" class="msp-reset" onclick="window.location.href='{{ route('master-spklu.index') }}'">Reset filter</button>
        @endif
    </form>

    <div class="msp-table-wrap">
        <table class="msp-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Id SPKLU</th>
                    <th>Kode Unit</th>
                    <th>Nama SPKLU</th>
                    <th>ULP</th>
                    <th>Type</th>
                    <th>KW</th>
                    <th>Nozzle</th>
                    <th>Kepemilikan</th>
                    <th>Skema</th>
                    <th>Koordinat</th>
                    @if (in_array(auth()->user()->role, ['super_admin', 'pengelola']))<th>Aksi</th>@endif
                </tr>
            </thead>
            <tbody>
                @forelse ($spklus as $i => $spklu)
                    <tr>
                        <td>{{ $spklus->firstItem() + $i }}</td>
                        <td>{{ $spklu->id_spklu }}</td>
                        <td>{{ $spklu->kode_unit ?? '—' }}</td>
                        <td style="font-weight:600; white-space:nowrap;">{{ $spklu->nama }}</td>
                        <td>{{ $spklu->ulp->nama_penuh ?? '—' }}</td>
                        <td><span class="msp-pill msp-pill-type">{{ $spklu->type }}</span></td>
                        <td>
                            {{ $spklu->kw_detail ?? $spklu->kw }}
                            @if (is_null($spklu->kw))
                                <span class="msp-pill msp-pill-custom">Custom</span>
                            @endif
                        </td>
                        <td>{{ $spklu->nozzle }}</td>
                        <td>{{ $spklu->kepemilikan }}</td>
                        <td>{{ $spklu->skema ?? '—' }}</td>
                        <td style="font-size:12px; color:#94a3b8; white-space:nowrap;">
                            @if ($spklu->latitude && $spklu->longitude)
                                {{ number_format($spklu->latitude, 5) }}, {{ number_format($spklu->longitude, 5) }}
                            @else
                                —
                            @endif
                        </td>
                        @if (in_array(auth()->user()->role, ['super_admin', 'pengelola']))
                            <td>
                                <button type="button" class="msp-del-btn" title="Edit" onclick='bukaModalEdit(@json($spklu))'>✎</button>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="11">
                            <div class="msp-empty">
                                <i data-lucide="inbox"></i>
                                <p>Belum ada data SPKLU.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($spklus->hasPages())
    <div class="msp-pagination">
        <p class="msp-pagination-info">Menampilkan {{ $spklus->firstItem() }}–{{ $spklus->lastItem() }} dari {{ $spklus->total() }} data</p>
        <div class="msp-pagination-links">
            @if ($spklus->onFirstPage())
                <span class="disabled">‹</span>
            @else
                <a href="{{ $spklus->previousPageUrl() }}">‹</a>
            @endif

            @for ($page = 1; $page <= $spklus->lastPage(); $page++)
                @if ($page == $spklus->currentPage())
                    <span class="active">{{ $page }}</span>
                @else
                    <a href="{{ $spklus->url($page) }}">{{ $page }}</a>
                @endif
            @endfor

            @if ($spklus->hasMorePages())
                <a href="{{ $spklus->nextPageUrl() }}">›</a>
            @else
                <span class="disabled">›</span>
            @endif
        </div>
    </div>
    @endif
</div>

{{-- Modal Tambah SPKLU --}}
<div id="modal-tambah-spklu" class="msp-modal-overlay">
    <div class="msp-modal">
        <h3>Tambah SPKLU</h3>
        <form method="POST" action="{{ route('master-spklu.store') }}">
            @csrf
            <label>Nama SPKLU</label>
            <input type="text" name="nama" required>

            <label>ULP</label>
            <select name="ulp_mapping_id" required>
                @foreach ($ulpList as $ulp)
                    <option value="{{ $ulp->id }}">{{ $ulp->nama_penuh }}</option>
                @endforeach
            </select>

            <label>Type</label>
            <select name="type" required>
                <option value="AC">AC</option>
                <option value="DC">DC</option>
            </select>

            <label>Kapasitas (kW)</label>
            <input type="number" step="0.01" name="kw" required>

            <label>Nozzle</label>
            <input type="number" name="nozzle" value="1" required>

            <label>Kepemilikan</label>
            <select name="kepemilikan" required>
                <option value="PLN">PLN</option>
                <option value="Swasta">Swasta</option>
            </select>

            <label>Koordinat Latitude (opsional)</label>
            <input type="text" name="latitude" placeholder="-6.591828">

            <label>Koordinat Longitude (opsional)</label>
            <input type="text" name="longitude" placeholder="106.794393">

            <div style="display:flex; justify-content:flex-end; gap:8px; margin-top:22px;">
                <button type="button" class="msp-btn msp-btn-outline" data-close-modal>Batal</button>
                <button type="submit" class="msp-btn msp-btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Import Excel --}}
<div id="modal-import-spklu" class="msp-modal-overlay">
    <div class="msp-modal">
        <h3>Import Data SPKLU dari Excel</h3>
        <p style="font-size:13px; color:#64748B;">
            File harus punya kolom header: <code>ID SPKLU, KD UNIT, ULP, Nama SPKLU, Type, KW, NOZZLE, Kepemilikan, SKEMA, PKS, TIKOR, Latitude, Longitude</code>.
            Nama ULP tidak case-sensitive, tapi ejaannya harus sama dengan yang ada di sistem.
            Kolom KW boleh diisi angka bersih (22, 120) atau format custom multi-mesin (2x120, 80, 120 & 200) — yang custom otomatis ditandai dan tidak ikut dijumlah ke total kapasitas.
        </p>

        <form method="POST" action="{{ route('master-spklu.import') }}" enctype="multipart/form-data">
            @csrf
            <label for="file-input-spklu" class="msp-dropzone" style="display:block; margin-top:12px;">
                <span id="file-name-label">📄 Klik untuk pilih file, atau drag & drop di sini</span>
                <input type="file" id="file-input-spklu" name="file" accept=".xlsx,.xls,.csv" required
                       style="display:none;"
                       onchange="document.getElementById('file-name-label').textContent = this.files[0]?.name ?? 'Klik untuk pilih file'">
            </label>

            <div style="display:flex; justify-content:flex-end; gap:8px; margin-top:22px;">
                <button type="button" class="msp-btn msp-btn-outline" data-close-modal>Batal</button>
                <button type="submit" class="msp-btn msp-btn-primary">Import</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit SPKLU --}}
<div id="modal-edit-spklu" class="msp-modal-overlay">
    <div class="msp-modal">
        <h3>Edit SPKLU</h3>
        <form method="POST" id="form-edit-spklu">
            @csrf
            @method('PUT')

            <label>Nama SPKLU</label>
            <input type="text" name="nama" id="edit-nama" required>

            <label>ULP</label>
            <select name="ulp_mapping_id" id="edit-ulp" required>
                @foreach ($ulpList as $ulp)
                    <option value="{{ $ulp->id }}">{{ $ulp->nama_penuh }}</option>
                @endforeach
            </select>

            <label>Type</label>
            <select name="type" id="edit-type" required>
                <option value="AC">AC</option>
                <option value="DC">DC</option>
            </select>

            <label>Kapasitas (kW) — kosongkan kalau custom</label>
            <input type="number" step="0.01" name="kw" id="edit-kw">

            <label>Kapasitas (teks, misal "2x120")</label>
            <input type="text" name="kw_detail" id="edit-kw-detail">

            <label>Nozzle</label>
            <input type="number" name="nozzle" id="edit-nozzle" required>

            <label>Kepemilikan</label>
            <select name="kepemilikan" id="edit-kepemilikan" required>
                <option value="PLN">PLN</option>
                <option value="Swasta">Swasta</option>
            </select>

            <label>Skema</label>
            <input type="number" name="skema" id="edit-skema">

            <label>Latitude</label>
            <input type="text" name="latitude" id="edit-latitude">

            <label>Longitude</label>
            <input type="text" name="longitude" id="edit-longitude">

            <div style="display:flex; justify-content:flex-end; gap:8px; margin-top:22px;">
                <button type="button" class="msp-btn msp-btn-outline" data-close-modal>Batal</button>
                <button type="submit" class="msp-btn msp-btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
function bukaModalEdit(spklu) {
    document.getElementById('form-edit-spklu').action = '/master-spklu/' + spklu.id;
    document.getElementById('edit-nama').value = spklu.nama ?? '';
    document.getElementById('edit-ulp').value = spklu.ulp_mapping_id ?? '';
    document.getElementById('edit-type').value = spklu.type ?? '';
    document.getElementById('edit-kw').value = spklu.kw ?? '';
    document.getElementById('edit-kw-detail').value = spklu.kw_detail ?? '';
    document.getElementById('edit-nozzle').value = spklu.nozzle ?? 1;
    document.getElementById('edit-kepemilikan').value = spklu.kepemilikan ?? '';
    document.getElementById('edit-skema').value = spklu.skema ?? '';
    document.getElementById('edit-latitude').value = spklu.latitude ?? '';
    document.getElementById('edit-longitude').value = spklu.longitude ?? '';
    document.getElementById('modal-edit-spklu').style.display = 'flex';
}
</script>

@endsection