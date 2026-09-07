@extends('layouts.app')

@section('breadcrumb', 'Transaksi')
@section('page-title', 'Transaksi')

@section('content')

<style>
    .trx-toolbar { display:flex; gap:10px; flex-wrap:wrap; align-items:center; margin-bottom:20px; }
    .trx-field { position:relative; }
    .trx-field i { position:absolute; left:12px; top:50%; transform:translateY(-50%); width:15px; height:15px; color:#94a3b8; pointer-events:none; }
    .trx-select, .trx-date {
        padding:10px 14px 10px 34px; border-radius:9px; border:1px solid #e2e8f0; font-size:13.5px;
        background:#fff; color:#1E293B; cursor:pointer; appearance:none; min-width:150px;
        background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6' fill='none'%3E%3Cpath d='M1 1L5 5L9 1' stroke='%2394a3b8' stroke-width='1.6' stroke-linecap='round'/%3E%3C/svg%3E");
        background-repeat:no-repeat; background-position:right 12px center; padding-right:30px;
    }
    .trx-date { cursor:auto; background-image:none; padding-right:14px; min-width:140px; }
    .trx-select:focus, .trx-date:focus { outline:none; border-color:#0081AB; box-shadow:0 0 0 3px rgba(0,129,171,.12); }

    .trx-btn { border:none; border-radius:9px; font-size:13.5px; font-weight:600; padding:10px 18px; cursor:pointer; transition:all .15s ease; }
    .trx-btn-primary { background:linear-gradient(135deg,#023E8A,#0081AB); color:#fff; box-shadow:0 2px 10px rgba(2,62,138,.25); }
    .trx-btn-primary:hover { transform:translateY(-1px); box-shadow:0 4px 14px rgba(2,62,138,.32); }
    .trx-btn-outline { background:#fff; color:#1E293B; border:1px solid #e2e8f0; }
    .trx-btn-outline:hover { background:#f8fafc; border-color:#cbd5e1; }

    .trx-card { background:#fff; border-radius:16px; padding:22px; border:1px solid #eef1f5; box-shadow:0 1px 2px rgba(15,23,42,.04), 0 6px 16px rgba(15,23,42,.05); margin-bottom:20px; }
    .trx-card h2, .trx-card h3 { margin:0; font-size:16px; font-weight:700; color:#0f172a; }

    .trx-table { width:100%; border-collapse:collapse; }
    .trx-table thead th { background:#fafbfc; text-align:left; font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8; padding:13px 20px; border-top:1px solid #eef1f5; border-bottom:1px solid #eef1f5; }
    .trx-table td { padding:14px 20px; font-size:13.5px; border-bottom:1px solid #f5f7fa; }
    .trx-table tbody tr:nth-child(even) { background:#fbfcfd; }
    .trx-table tbody tr:hover { background:rgba(0,129,171,.05); }
    .trx-table tbody tr:last-child td { border-bottom:none; }
    .trx-empty { text-align:center; padding:48px 20px; color:#94a3b8; font-size:13.5px; }

    .trx-modal-overlay { display:none; position:fixed; inset:0; background:rgba(15,23,42,.45); align-items:center; justify-content:center; z-index:50; }
    .trx-modal { background:#fff; border-radius:16px; padding:26px; width:460px; max-width:92vw; box-shadow:0 20px 50px rgba(0,0,0,.2); max-height:90vh; overflow-y:auto; }
    .trx-modal h3 { margin:0 0 6px; }
    .trx-modal label { display:block; font-size:12.5px; font-weight:600; color:#475569; margin:14px 0 5px; }
    .trx-modal input, .trx-modal select { width:100%; padding:10px 12px; border-radius:8px; border:1px solid #e2e8f0; font-size:13.5px; }

    .trx-dropzone { border:2px dashed #cbd5e1; border-radius:12px; padding:32px; text-align:center; color:#64748B; cursor:pointer; font-size:13.5px; }
    .trx-dropzone:hover { border-color:#0081AB; background:rgba(0,129,171,.03); }

    .alert-error { background:rgba(192,57,43,.08); border:1px solid rgba(192,57,43,.25); color:#C0392B; border-radius:10px; padding:12px 16px; font-size:13.5px; margin-bottom:18px; }
    .alert-success { background:rgba(46,158,91,.08); border:1px solid rgba(46,158,91,.25); color:#2E9E5B; border-radius:10px; padding:12px 16px; font-size:13.5px; margin-bottom:18px; }

    .trx-alias-row { display:flex; gap:8px; align-items:center; margin-bottom:10px; }
    .trx-alias-row select { padding:7px; border-radius:6px; border:1px solid #e2e8f0; font-size:13px; max-width:220px; }
</style>

@if (session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert-error">{{ session('error') }}</div>
@endif

<form method="GET" class="trx-toolbar">
    <div class="trx-field">
        <i data-lucide="zap"></i>
        <select name="spklu_id" class="trx-select" onchange="this.form.submit()">
            <option value="">Semua SPKLU</option>
            @foreach ($spkluList as $spklu)
                <option value="{{ $spklu->id }}" {{ (string) $spkluTerpilih === (string) $spklu->id ? 'selected' : '' }}>{{ $spklu->nama }}</option>
            @endforeach
        </select>
    </div>

    <div class="trx-field">
        <i data-lucide="bar-chart-2"></i>
        <select name="satuan" class="trx-select" onchange="this.form.submit()">
            <option value="kali" {{ $satuan === 'kali' ? 'selected' : '' }}>Kali</option>
            <option value="kwh" {{ $satuan === 'kwh' ? 'selected' : '' }}>kWh</option>
            <option value="rp" {{ $satuan === 'rp' ? 'selected' : '' }}>Rp</option>
        </select>
    </div>

    <div class="trx-field">
        <i data-lucide="layout-grid"></i>
        <select name="tampilan" class="trx-select" onchange="this.form.submit()">
            <option value="bulanan" {{ $tampilan === 'bulanan' ? 'selected' : '' }}>Per Bulan</option>
            <option value="kumulatif" {{ $tampilan === 'kumulatif' ? 'selected' : '' }}>Kumulatif</option>
        </select>
    </div>

    <input type="date" name="dari" value="{{ $dari }}" class="trx-date" onchange="this.form.submit()">
    <span style="color:#94a3b8;">—</span>
    <input type="date" name="sampai" value="{{ $sampai }}" class="trx-date" onchange="this.form.submit()">

    @if (in_array(auth()->user()->role, ['super_admin', 'pengelola']))
        <button type="button" class="trx-btn trx-btn-outline" style="margin-left:auto;" onclick="document.getElementById('modal-upload-transaksi').style.display='flex'">↑ Upload Data</button>
    @endif
</form>

<div class="trx-card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <h2>Tren Transaksi</h2>
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
                <tr><td colspan="2" class="trx-empty">Belum ada data transaksi di rentang ini.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($aliasList->count() > 0 && in_array(auth()->user()->role, ['super_admin', 'pengelola']))
<div class="trx-card">
    <h3 style="margin-bottom:14px;">Pemetaan Alias SPKLU</h3>
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
        <h3>Upload Data Transaksi</h3>
        <p style="font-size:13px; color:#64748B;">
            File Excel/CSV berisi transaksi mentah (kolom: TANGGAL, SPKLU, KWH, RPPAKAI, dst). Data akan otomatis diringkas jadi rekap harian per SPKLU. File besar bisa butuh beberapa menit — jangan tutup halaman.
        </p>
        <form method="POST" action="{{ route('transaksi.import') }}" enctype="multipart/form-data">
            @csrf
            <label for="file-input-transaksi" class="trx-dropzone" style="display:block; margin-top:12px;">
                <span id="file-name-label-transaksi">📄 Klik untuk pilih file, atau drag & drop di sini</span>
                <input type="file" id="file-input-transaksi" name="file" accept=".xlsx,.xls,.csv" required
                       style="display:none;"
                       onchange="document.getElementById('file-name-label-transaksi').textContent = this.files[0]?.name ?? 'Klik untuk pilih file'">
            </label>

            <div style="display:flex; justify-content:flex-end; gap:8px; margin-top:22px;">
                <button type="button" class="trx-btn trx-btn-outline" onclick="document.getElementById('modal-upload-transaksi').style.display='none'">Batal</button>
                <button type="submit" class="trx-btn trx-btn-primary">Upload</button>
            </div>
        </form>
    </div>
</div>

{{-- Panel SPKLU tidak cocok, muncul otomatis setelah upload kalau ada yang gagal --}}
@if (session('unmatched_spklu'))
<div class="trx-modal-overlay" style="display:flex;">
    <div class="trx-modal" style="width:580px;">
        <h3>⚠ Nama SPKLU Tidak Cocok</h3>
        <p style="font-size:13.5px; color:#64748B;">
            Nama-nama ini ada di file yang diupload tapi tidak ketemu padanannya di Master SPKLU. Petain manual di bawah, lalu upload ulang file yang sama untuk memproses baris yang tadi terlewat.
        </p>
        <div style="max-height:340px; overflow-y:auto; margin:16px 0;">
            @foreach (session('unmatched_spklu') as $nama => $jumlah)
                <form method="POST" action="{{ route('transaksi.alias.store') }}" class="trx-alias-row">
                    @csrf
                    <input type="hidden" name="nama_asli" value="{{ $nama }}">
                    <div style="flex:1; font-size:13px;">
                        <strong>{{ $nama }}</strong><br>
                        <span style="color:#94a3b8; font-size:11.5px;">{{ number_format($jumlah) }} baris</span>
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
        <button type="button" class="trx-btn trx-btn-outline" onclick="this.closest('.trx-modal-overlay').style.display='none'" style="width:100%;">Tutup</button>
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
            }]
        },
        options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });
</script>

@endsection