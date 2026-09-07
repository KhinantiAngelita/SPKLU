@extends('layouts.app')

@section('breadcrumb', 'Transaksi')
@section('page-title', 'Transaksi')

@section('content')

<style>
    .trx-toolbar { display:flex; gap:10px; flex-wrap:wrap; align-items:center; margin-bottom:20px; }
    .trx-field { position:relative; }
    .trx-field .trx-icon { position:absolute; left:12px; top:50%; transform:translateY(-50%); width:15px; height:15px; color:#94a3b8; pointer-events:none; stroke-width:1.9; }
    .trx-select, .trx-date {
        padding:10px 14px 10px 34px; border-radius:9px; border:1px solid #e2e8f0; font-size:13.5px;
        background:#fff; color:#1E293B; cursor:pointer; appearance:none; min-width:150px;
        background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6' fill='none'%3E%3Cpath d='M1 1L5 5L9 1' stroke='%2394a3b8' stroke-width='1.6' stroke-linecap='round'/%3E%3C/svg%3E");
        background-repeat:no-repeat; background-position:right 12px center; padding-right:30px;
        transition:border-color .2s ease, box-shadow .2s ease;
    }
    .trx-date { cursor:auto; background-image:none; padding-right:14px; min-width:140px; }
    .trx-select:hover, .trx-date:hover { border-color:#c7d1db; }
    .trx-select:focus, .trx-date:focus { outline:none; border-color:#0081AB; box-shadow:0 0 0 3px rgba(0,129,171,.12); }

    .trx-daterange { display:flex; align-items:center; gap:8px; background:#fff; border:1px solid #e2e8f0; border-radius:9px; padding:0 4px; }
    .trx-daterange .trx-date { border:none; box-shadow:none !important; min-width:118px; padding:10px 6px; }
    .trx-daterange .trx-sep { color:#94a3b8; width:13px; height:13px; stroke-width:2; }

    .trx-btn { display:inline-flex; align-items:center; gap:7px; border:none; border-radius:9px; font-size:13.5px; font-weight:600; padding:10px 18px; cursor:pointer; transition:all .15s ease; }
    .trx-btn svg { width:15px; height:15px; stroke-width:2.1; }
    .trx-btn-primary { background:linear-gradient(135deg,#023E8A,#0081AB); color:#fff; box-shadow:0 2px 10px rgba(2,62,138,.25); }
    .trx-btn-primary:hover { transform:translateY(-1px); box-shadow:0 4px 14px rgba(2,62,138,.32); }
    .trx-btn-outline { background:#fff; color:#1E293B; border:1px solid #e2e8f0; }
    .trx-btn-outline:hover { background:#f8fafc; border-color:#cbd5e1; }

    .trx-card { background:#fff; border-radius:16px; padding:22px; border:1px solid #eef1f5; box-shadow:0 1px 2px rgba(15,23,42,.04), 0 6px 16px rgba(15,23,42,.05); margin-bottom:20px; transition:box-shadow .2s ease, border-color .2s ease; }
    .trx-card:hover { box-shadow:0 4px 10px rgba(15,23,42,.05), 0 12px 26px rgba(15,23,42,.07); border-color:rgba(0,129,171,.16); }

    .trx-card-title { display:flex; align-items:center; gap:10px; margin:0; font-size:16px; font-weight:700; color:#0f172a; }
    .trx-card-title-icon { width:30px; height:30px; min-width:30px; border-radius:9px; display:flex; align-items:center; justify-content:center; }
    .trx-card-title-icon svg { width:16px; height:16px; stroke-width:1.9; }
    .trx-card-title-icon.blue  { background:rgba(0,129,171,.12); color:#023E8A; }
    .trx-card-title-icon.amber { background:rgba(232,163,23,.14); color:#E8A317; }
    .trx-card-title-icon.green { background:rgba(22,163,74,.12); color:#16A34A; }

    .trx-table { width:100%; border-collapse:collapse; }
    .trx-table thead th { background:#fafbfc; text-align:left; font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8; padding:13px 20px; border-top:1px solid #eef1f5; border-bottom:1px solid #eef1f5; }
    .trx-table td { padding:14px 20px; font-size:13.5px; border-bottom:1px solid #f5f7fa; }
    .trx-table tbody tr:nth-child(even) { background:#fbfcfd; }
    .trx-table tbody tr:hover { background:rgba(0,129,171,.05); }
    .trx-table tbody tr:last-child td { border-bottom:none; }
    .trx-empty { text-align:center; padding:44px 20px; color:#94a3b8; font-size:13.5px; }
    .trx-empty svg { width:30px; height:30px; stroke-width:1.5; color:#cbd5e1; margin-bottom:10px; }

    .trx-modal-overlay { display:none; position:fixed; inset:0; background:rgba(15,23,42,.45); align-items:center; justify-content:center; z-index:50; }
    .trx-modal { background:#fff; border-radius:16px; padding:26px; width:460px; max-width:92vw; box-shadow:0 20px 50px rgba(0,0,0,.2); max-height:90vh; overflow-y:auto; }
    .trx-modal-title { display:flex; align-items:center; gap:10px; margin:0 0 6px; font-size:17px; font-weight:700; color:#0f172a; }
    .trx-modal-title-icon { width:32px; height:32px; min-width:32px; border-radius:10px; display:flex; align-items:center; justify-content:center; }
    .trx-modal-title-icon svg { width:17px; height:17px; stroke-width:2; }
    .trx-modal-title-icon.blue  { background:rgba(0,129,171,.12); color:#023E8A; }
    .trx-modal-title-icon.amber { background:rgba(232,163,23,.16); color:#c9820f; }
    .trx-modal label { display:block; font-size:12.5px; font-weight:600; color:#475569; margin:14px 0 5px; }
    .trx-modal input, .trx-modal select { width:100%; padding:10px 12px; border-radius:8px; border:1px solid #e2e8f0; font-size:13.5px; transition:border-color .2s ease, box-shadow .2s ease; }
    .trx-modal input:focus, .trx-modal select:focus { outline:none; border-color:#0081AB; box-shadow:0 0 0 3px rgba(0,129,171,.12); }

    .trx-dropzone { display:flex; flex-direction:column; align-items:center; gap:8px; border:2px dashed #cbd5e1; border-radius:12px; padding:28px 20px; text-align:center; color:#64748B; cursor:pointer; font-size:13px; transition:border-color .2s ease, background .2s ease; }
    .trx-dropzone svg { width:26px; height:26px; stroke-width:1.6; color:#94a3b8; transition:color .2s ease; }
    .trx-dropzone:hover { border-color:#0081AB; background:rgba(0,129,171,.03); }
    .trx-dropzone:hover svg { color:#0081AB; }
    .trx-dropzone-name { font-weight:600; color:#1E293B; }
    .trx-note { display:flex; gap:9px; align-items:flex-start; font-size:12.5px; color:#64748B; background:#f8fafc; border-radius:9px; padding:10px 12px; margin-top:12px; }
    .trx-note svg { width:14px; height:14px; min-width:14px; margin-top:1px; color:#94a3b8; stroke-width:2; }

    .alert-error, .alert-success { display:flex; gap:10px; align-items:flex-start; border-radius:10px; padding:12px 16px; font-size:13.5px; margin-bottom:18px; }
    .alert-error svg, .alert-success svg { width:17px; height:17px; min-width:17px; margin-top:1px; stroke-width:2; }
    .alert-error { background:rgba(192,57,43,.08); border:1px solid rgba(192,57,43,.25); color:#C0392B; }
    .alert-success { background:rgba(46,158,91,.08); border:1px solid rgba(46,158,91,.25); color:#2E9E5B; }

    .trx-alias-row { display:flex; gap:10px; align-items:center; padding:9px 4px; border-radius:9px; transition:background .15s ease; }
    .trx-alias-row:hover { background:#f8fafc; }
    .trx-alias-row + .trx-alias-row { border-top:1px dashed #eef1f5; }
    .trx-alias-row select { padding:8px 10px; border-radius:7px; border:1px solid #e2e8f0; font-size:12.5px; max-width:220px; }
    .trx-alias-count { color:#94a3b8; font-size:11.5px; }
</style>

@if (session('success'))
    <div class="alert-success">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        <span>{{ session('success') }}</span>
    </div>
@endif
@if (session('error'))
    <div class="alert-error">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <span>{{ session('error') }}</span>
    </div>
@endif

<form method="GET" class="trx-toolbar">
    <div class="trx-field">
        <svg class="trx-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
        <select name="spklu_id" class="trx-select" onchange="this.form.submit()">
            <option value="">Semua SPKLU</option>
            @foreach ($spkluList as $spklu)
                <option value="{{ $spklu->id }}" {{ (string) $spkluTerpilih === (string) $spklu->id ? 'selected' : '' }}>{{ $spklu->nama }}</option>
            @endforeach
        </select>
    </div>

    <div class="trx-field">
        <svg class="trx-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
        <select name="satuan" class="trx-select" onchange="this.form.submit()">
            <option value="kali" {{ $satuan === 'kali' ? 'selected' : '' }}>Kali</option>
            <option value="kwh" {{ $satuan === 'kwh' ? 'selected' : '' }}>kWh</option>
            <option value="rp" {{ $satuan === 'rp' ? 'selected' : '' }}>Rp</option>
        </select>
    </div>

    <div class="trx-field">
        <svg class="trx-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
        <select name="tampilan" class="trx-select" onchange="this.form.submit()">
            <option value="bulanan" {{ $tampilan === 'bulanan' ? 'selected' : '' }}>Per Bulan</option>
            <option value="kumulatif" {{ $tampilan === 'kumulatif' ? 'selected' : '' }}>Kumulatif</option>
        </select>
    </div>

    <div class="trx-daterange">
        <input type="date" name="dari" value="{{ $dari }}" class="trx-date" onchange="this.form.submit()">
        <svg class="trx-sep" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/></svg>
        <input type="date" name="sampai" value="{{ $sampai }}" class="trx-date" onchange="this.form.submit()">
    </div>

    @if (in_array(auth()->user()->role, ['super_admin', 'pengelola']))
        <button type="button" class="trx-btn trx-btn-outline" style="margin-left:auto;" onclick="document.getElementById('modal-upload-transaksi').style.display='flex'">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            Upload Data
        </button>
    @endif
</form>

<div class="trx-card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; flex-wrap:wrap; gap:8px;">
        <h2 class="trx-card-title">
            <span class="trx-card-title-icon blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 17 9 11 13 15 21 7"/><polyline points="14 7 21 7 21 14"/></svg>
            </span>
            Tren Transaksi
        </h2>
        <span style="font-size:12px; color:#94a3b8;">{{ \Carbon\Carbon::parse($dari)->translatedFormat('M Y') }} – {{ \Carbon\Carbon::parse($sampai)->translatedFormat('M Y') }}</span>
    </div>
    <canvas id="chart-transaksi" height="80"></canvas>
</div>

<div class="trx-card" style="padding:0;">
    <table class="trx-table">
        <thead>
            <tr><th>Bulan</th><th>Total ({{ strtoupper($satuan) }})</th></tr>
        </thead>
        <tbody>
            @forelse ($chartData as $row)
                <tr>
                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $row->bulan)->translatedFormat('F Y') }}</td>
                    <td style="font-weight:600;">{{ number_format($row->total, $satuan === 'rp' ? 0 : 1) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="trx-empty">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" style="display:block; margin-left:auto; margin-right:auto;"><path d="M22 12h-6l-2 3h-4l-2-3H2"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
                        Belum ada data transaksi di rentang ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($aliasList->count() > 0 && in_array(auth()->user()->role, ['super_admin', 'pengelola']))
<div class="trx-card">
    <h3 class="trx-card-title" style="margin-bottom:14px;">
        <span class="trx-card-title-icon green">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
        </span>
        Pemetaan Alias SPKLU
    </h3>
    <table class="trx-table">
        <thead><tr><th>Nama di File Sumber</th><th>Dipetakan ke SPKLU</th></tr></thead>
        <tbody>
            @foreach ($aliasList as $alias)
                <tr>
                    <td>{{ $alias->nama_asli }}</td>
                    <td>{{ $alias->spklu->nama ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

{{-- Modal Upload --}}
<div id="modal-upload-transaksi" class="trx-modal-overlay">
    <div class="trx-modal">
        <h3 class="trx-modal-title">
            <span class="trx-modal-title-icon blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            </span>
            Upload Data Transaksi
        </h3>
        <p style="font-size:13px; color:#64748B; margin:0;">
            File Excel/CSV berisi transaksi mentah (kolom: TANGGAL, SPKLU, KWH, RPPAKAI, dst). Data akan otomatis diringkas jadi rekap harian per SPKLU.
        </p>

        <form method="POST" action="{{ route('transaksi.import') }}" enctype="multipart/form-data">
            @csrf
            <label for="file-input-transaksi" class="trx-dropzone" style="margin-top:14px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                <span id="file-name-label-transaksi">Klik untuk pilih file, atau drag &amp; drop di sini</span>
                <input type="file" id="file-input-transaksi" name="file" accept=".xlsx,.xls,.csv" required
                       style="display:none;"
                       onchange="document.getElementById('file-name-label-transaksi').textContent = this.files[0]?.name ?? 'Klik untuk pilih file'">
            </label>

            <div class="trx-note">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                <span>File besar bisa butuh beberapa menit untuk diproses — jangan tutup atau refresh halaman ini selama proses berjalan.</span>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:8px; margin-top:20px;">
                <button type="button" class="trx-btn trx-btn-outline" onclick="document.getElementById('modal-upload-transaksi').style.display='none'">Batal</button>
                <button type="submit" class="trx-btn trx-btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    Upload
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Panel SPKLU tidak cocok, muncul otomatis setelah upload kalau ada yang gagal --}}
@if (session('unmatched_spklu'))
<div class="trx-modal-overlay" style="display:flex;">
    <div class="trx-modal" style="width:580px;">
        <h3 class="trx-modal-title">
            <span class="trx-modal-title-icon amber">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </span>
            Nama SPKLU Tidak Cocok
        </h3>
        <p style="font-size:13.5px; color:#64748B; margin:0;">
            Nama-nama ini ada di file yang diupload tapi tidak ketemu padanannya di Master SPKLU. Petain manual di bawah, lalu upload ulang file yang sama untuk memproses baris yang tadi terlewat.
        </p>
        <div style="max-height:340px; overflow-y:auto; margin:14px 0;">
            @foreach (session('unmatched_spklu') as $nama => $jumlah)
                <form method="POST" action="{{ route('transaksi.alias.store') }}" class="trx-alias-row">
                    @csrf
                    <input type="hidden" name="nama_asli" value="{{ $nama }}">
                    <div style="flex:1; font-size:13px;">
                        <strong>{{ $nama }}</strong><br>
                        <span class="trx-alias-count">{{ number_format($jumlah) }} baris</span>
                    </div>
                    <select name="spklu_id" required>
                        <option value="">Pilih SPKLU yang benar...</option>
                        @foreach ($spkluList as $s)
                            <option value="{{ $s->id }}">{{ $s->nama }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="trx-btn trx-btn-primary" style="padding:7px 12px; font-size:12px;">Simpan</button>
                </form>
            @endforeach
        </div>
        <button type="button" class="trx-btn trx-btn-outline" onclick="this.closest('.trx-modal-overlay').style.display='none'" style="width:100%; justify-content:center;">Tutup</button>
    </div>
</div>
@endif

<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
    new Chart(document.getElementById('chart-transaksi'), {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData->pluck('bulan')) !!},
            datasets: [{
                label: '{{ strtoupper($satuan) }}',
                data: {!! json_encode($chartData->pluck('total')) !!},
                borderColor: '#0081AB',
                backgroundColor: 'rgba(0,129,171,0.1)',
                tension: 0.35,
                fill: true,
                pointRadius: 4,
                pointBackgroundColor: '#023E8A',
                pointHoverRadius: 6,
                borderWidth: 2.5,
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                x: { grid: { display: false } }
            },
            interaction: { intersect: false, mode: 'index' }
        }
    });
</script>

@endsection