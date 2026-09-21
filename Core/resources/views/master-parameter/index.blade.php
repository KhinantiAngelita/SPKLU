@extends('layouts.app')

@section('breadcrumb', 'Master Parameter')
@section('page-title', 'Master Parameter')

@section('content')

<style>
    .mp-subtitle { color:#64748B; margin:-6px 0 20px; font-size:13.5px; }
    .mp-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px; }
    .mp-grid-full { grid-column: span 2; }

    .mp-table { width:100%; border-collapse:collapse; }
    .mp-table thead th { background:#fafbfc; text-align:left; font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8; padding:12px 20px; border-bottom:1px solid #eef1f5; }
    .mp-table td { padding:12px 20px; font-size:13.3px; border-bottom:1px solid #f5f7fa; }
    .mp-table tbody tr:nth-child(even) { background:#fbfcfd; }
    .mp-table tbody tr:hover { background:rgba(0,129,171,.04); }
    .mp-table tbody tr:last-child td { border-bottom:none; }
    .mp-table input {
        width:110px; padding:7px 10px; border-radius:7px; border:1px solid #e2e8f0; font-size:13px;
        transition:all .15s ease;
    }
    .mp-table input:focus { outline:none; border-color:#0081AB; box-shadow:0 0 0 3px rgba(0,129,171,.12); }

    .mp-save-link { background:none; border:none; color:#0081AB; font-size:12.5px; font-weight:700; cursor:pointer; padding:4px 8px; border-radius:6px; transition:background .15s ease; }
    .mp-save-link:hover { background:rgba(0,129,171,.1); }

    .mp-note { display:flex; gap:9px; align-items:flex-start; font-size:12px; color:#92660f; background:rgba(232,163,23,.08); border:1px solid rgba(232,163,23,.25); border-radius:9px; padding:11px 14px; margin:16px 24px 20px; }
    .mp-note svg { width:14px; height:14px; min-width:14px; margin-top:1px; stroke-width:2; }

    .mp-empty { text-align:center; padding:32px 20px; color:#94a3b8; font-size:13px; }

    .mp-target-form { display:flex; gap:12px; align-items:flex-end; padding:0 24px 22px; flex-wrap:wrap; }
    .mp-field label { display:block; font-size:11.5px; font-weight:700; color:#64748B; text-transform:uppercase; letter-spacing:.04em; margin-bottom:6px; }
    .mp-field input { padding:9px 12px; border-radius:8px; border:1px solid #e2e8f0; font-size:13.5px; width:170px; }
    .mp-field input:focus { outline:none; border-color:#0081AB; box-shadow:0 0 0 3px rgba(0,129,171,.12); }

    .mp-btn { border:none; border-radius:9px; font-size:13.3px; font-weight:700; padding:10px 20px; cursor:pointer; transition:all .15s ease; background:linear-gradient(135deg,#023E8A,#0081AB); color:#fff; box-shadow:0 2px 10px rgba(2,62,138,.25); }
    .mp-btn:hover { transform:translateY(-1px); }
</style>

<p class="mp-subtitle">Nilai referensi yang dipakai sistem untuk perhitungan skor. Perubahan hanya berlaku untuk penilaian baru — data yang sudah dinilai sebelumnya tidak dihitung ulang otomatis.</p>

<div class="mp-grid">

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
            <thead><tr><th>Kode</th><th>Tarif per kWh</th><th></th></tr></thead>
            <tbody>
                @forelse ($tarifListrik as $tarif)
                <tr>
                    <form method="POST" action="{{ route('master-parameter.tarif.update', $tarif) }}">
                        @csrf @method('PATCH')
                        <td style="font-weight:700; color:#023E8A;">{{ $tarif->kode }}</td>
                        <td><input type="number" step="0.01" name="tarif_per_kwh" value="{{ $tarif->tarif_per_kwh }}"></td>
                        <td><button type="submit" class="mp-save-link">Simpan</button></td>
                    </form>
                </tr>
                @empty
                    <tr><td colspan="3" class="mp-empty">Belum ada data tarif.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="surface-card">
        <div class="section-header-bar">
            <div class="section-header-bar-left">
                <div class="section-header-bar-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" y1="20" x2="12.01" y2="20"/></svg></div>
                <div>
                    <h2>Poin Kesiapan Jaringan</h2>
                    <p>Skor berdasarkan kondisi jaringan listrik</p>
                </div>
            </div>
        </div>
        <table class="mp-table">
            <thead><tr><th>Kondisi</th><th>Poin</th><th></th></tr></thead>
            <tbody>
                @forelse ($poinJaringan as $poin)
                <tr>
                    <form method="POST" action="{{ route('master-parameter.poin-jaringan.update', $poin) }}">
                        @csrf @method('PATCH')
                        <td>{{ $poin->kondisi }}</td>
                        <td><input type="number" name="poin" value="{{ $poin->poin }}" min="0" max="20" style="width:70px;"></td>
                        <td><button type="submit" class="mp-save-link">Simpan</button></td>
                    </form>
                </tr>
                @empty
                    <tr><td colspan="3" class="mp-empty">Belum ada data poin jaringan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="surface-card mp-grid-full">
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
            <thead><tr><th>Tahun</th><th>Target Jumlah SPKLU</th></tr></thead>
            <tbody>
                @forelse ($targetTahunan as $target)
                    <tr><td style="font-weight:700;">{{ $target->tahun }}</td><td>{{ number_format($target->target_jumlah_spklu) }} unit</td></tr>
                @empty
                    <tr><td colspan="2" class="mp-empty">Belum ada target yang ditentukan.</td></tr>
                @endforelse
            </tbody>
        </table>

        <form method="POST" action="{{ route('master-parameter.target.store') }}" class="mp-target-form">
            @csrf
            <div class="mp-field">
                <label>Tahun</label>
                <input type="number" name="tahun" min="2020" required placeholder="2027">
            </div>
            <div class="mp-field">
                <label>Target Jumlah SPKLU</label>
                <input type="number" name="target_jumlah_spklu" min="0" required placeholder="50">
            </div>
            <button type="submit" class="mp-btn">+ Tambah Target</button>
        </form>
    </div>

</div>

<div class="mp-note">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    <span><strong>Perhatian:</strong> mengubah nilai di atas akan memengaruhi hasil perhitungan skor untuk penilaian BARU sejak saat ini disimpan. Data lokasi yang sudah dinilai sebelumnya tidak akan dihitung ulang otomatis.</span>
</div>

@endsection