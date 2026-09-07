@extends('layouts.app')

@section('breadcrumb', 'Master Parameter')
@section('page-title', 'Master Parameter')

@section('content')

<style>
    /* ================= MASTER PARAMETER — STYLES ================= */
    .mp-page {
        --mp-radius: 16px;
        --mp-radius-sm: 10px;
        --mp-border: rgba(0,0,0,0.06);
        --mp-shadow: 0 1px 2px rgba(16,24,40,0.04), 0 4px 16px rgba(16,24,40,0.05);
        --mp-shadow-hover: 0 4px 10px rgba(16,24,40,0.06), 0 12px 28px rgba(16,24,40,0.08);
        --mp-blue: #0081AB;
        --mp-blue-dark: #023E8A;
        --mp-amber: #E8A317;
        --mp-green: #16A34A;
    }

    .mp-page .mp-notice {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        background: linear-gradient(135deg, rgba(0,129,171,0.06), rgba(2,62,138,0.04));
        border: 1px solid rgba(0,129,171,0.15);
        border-radius: var(--mp-radius-sm);
        padding: 14px 16px;
        margin-bottom: 22px;
        color: var(--text-secondary);
        font-size: 13.5px;
        line-height: 1.55;
    }

    .mp-page .mp-notice svg {
        width: 18px;
        height: 18px;
        min-width: 18px;
        margin-top: 1px;
        color: var(--mp-blue-dark);
    }

    /* ---------- Grid & card ---------- */
    .mp-page .mp-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .mp-page .summary-card {
        background: var(--surface, #fff);
        border: 1px solid var(--mp-border);
        border-radius: var(--mp-radius);
        padding: 22px;
        box-shadow: var(--mp-shadow);
        transition: box-shadow .25s ease, border-color .25s ease;
    }

    .mp-page .summary-card:hover {
        box-shadow: var(--mp-shadow-hover);
        border-color: rgba(0,129,171,0.2);
    }

    .mp-page .mp-card-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 15.5px;
        font-weight: 700;
        color: var(--text-primary, #101828);
        margin: 0 0 16px;
    }

    .mp-page .mp-card-title-icon {
        width: 30px;
        height: 30px;
        min-width: 30px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .mp-page .mp-card-title-icon svg {
        width: 16px;
        height: 16px;
        stroke-width: 1.9;
    }

    .mp-page .mp-card-title-icon.blue  { background: rgba(0,129,171,0.12); color: var(--mp-blue-dark); }
    .mp-page .mp-card-title-icon.amber { background: rgba(232,163,23,0.14); color: var(--mp-amber); }
    .mp-page .mp-card-title-icon.green { background: rgba(22,163,74,0.12); color: var(--mp-green); }

    .mp-page .mp-card-full {
        grid-column: span 2;
    }

    /* ---------- Table ---------- */
    .mp-page .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .mp-page .data-table thead th {
        text-align: left;
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: var(--text-secondary);
        font-weight: 700;
        padding: 10px 12px;
        background: #f8fafc;
    }

    .mp-page .data-table thead th:first-child { border-radius: 8px 0 0 8px; }
    .mp-page .data-table thead th:last-child  { border-radius: 0 8px 8px 0; width: 1%; }

    .mp-page .data-table tbody td {
        padding: 10px 12px;
        border-bottom: 1px solid var(--mp-border);
        vertical-align: middle;
    }

    .mp-page .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    .mp-page .data-table tbody tr {
        transition: background .15s ease;
    }

    .mp-page .data-table tbody tr:hover {
        background: #f8fafc;
    }

    .mp-page .mp-kode {
        display: inline-block;
        font-weight: 700;
        font-size: 12.5px;
        color: var(--mp-blue-dark);
        background: rgba(0,129,171,0.1);
        border-radius: 7px;
        padding: 3px 9px;
    }

    /* ---------- Inputs ---------- */
    .mp-page .mp-input {
        width: 100%;
        padding: 8px 10px;
        border-radius: 8px;
        border: 1px solid #dde3ea;
        font-size: 13.5px;
        color: var(--text-primary, #101828);
        background: #fff;
        transition: border-color .2s ease, box-shadow .2s ease;
    }

    .mp-page .mp-input:hover {
        border-color: #c7d1db;
    }

    .mp-page .mp-input:focus {
        outline: none;
        border-color: var(--mp-blue);
        box-shadow: 0 0 0 3px rgba(0,129,171,0.14);
    }

    .mp-page .mp-input-tarif  { width: 130px; }
    .mp-page .mp-input-poin   { width: 84px; text-align: center; }

    /* ---------- Save button (per row) ---------- */
    .mp-page .mp-save-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid rgba(0,129,171,0.3);
        background: rgba(0,129,171,0.06);
        color: var(--mp-blue-dark);
        font-size: 12.5px;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 8px;
        cursor: pointer;
        transition: background .2s ease, color .2s ease, transform .15s ease;
        white-space: nowrap;
    }

    .mp-page .mp-save-btn svg {
        width: 13px;
        height: 13px;
        stroke-width: 2.2;
    }

    .mp-page .mp-save-btn:hover {
        background: var(--mp-blue);
        color: #fff;
        border-color: var(--mp-blue);
        transform: translateY(-1px);
    }

    /* ---------- Target tahunan ---------- */
    .mp-page .mp-target-form {
        display: flex;
        gap: 14px;
        align-items: flex-end;
        flex-wrap: wrap;
        padding-top: 4px;
        border-top: 1px dashed var(--mp-border);
        margin-top: 4px;
        padding-top: 18px;
    }

    .mp-page .mp-field label {
        display: block;
        font-size: 12.5px;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 5px;
    }

    .mp-page .mp-field .mp-input {
        min-width: 160px;
    }

    .mp-page .mp-btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, var(--mp-blue), var(--mp-blue-dark));
        color: #fff;
        border: none;
        font-size: 13.5px;
        font-weight: 600;
        padding: 10px 18px;
        border-radius: 9px;
        cursor: pointer;
        box-shadow: 0 3px 10px rgba(2,62,138,0.25);
        transition: transform .15s ease, box-shadow .15s ease;
    }

    .mp-page .mp-btn-primary svg {
        width: 15px;
        height: 15px;
        stroke-width: 2.2;
    }

    .mp-page .mp-btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 14px rgba(2,62,138,0.32);
    }

    .mp-page .mp-empty-row td {
        text-align: center;
        color: var(--text-secondary);
        padding: 18px 12px;
    }

    @media (max-width: 900px) {
        .mp-page .mp-grid { grid-template-columns: 1fr; }
        .mp-page .mp-card-full { grid-column: span 1; }
    }
</style>

<div class="mp-page">

    <div class="mp-notice">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
        <span>Nilai referensi yang dipakai sistem untuk perhitungan skor. Perubahan di sini hanya berlaku untuk penilaian baru, tidak mengubah data yang sudah dinilai sebelumnya.</span>
    </div>

    <div class="mp-grid">

        {{-- Tarif Listrik --}}
        <div class="summary-card">
            <h3 class="mp-card-title">
                <span class="mp-card-title-icon amber">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                </span>
                Tarif Layanan Listrik
            </h3>
            <table class="data-table">
                <thead><tr><th>Kode</th><th>Tarif per kWh</th><th></th></tr></thead>
                <tbody>
                    @forelse ($tarifListrik as $tarif)
                        <tr>
                            <td><span class="mp-kode">{{ $tarif->kode }}</span></td>
                            <td>
                                <input
                                    type="number" step="0.01" name="tarif_per_kwh"
                                    value="{{ $tarif->tarif_per_kwh }}"
                                    form="tarif-form-{{ $tarif->id }}"
                                    class="mp-input mp-input-tarif">
                            </td>
                            <td>
                                <button type="submit" form="tarif-form-{{ $tarif->id }}" class="mp-save-btn">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    Simpan
                                </button>
                                <form id="tarif-form-{{ $tarif->id }}" method="POST" action="{{ route('master-parameter.tarif.update', $tarif) }}" style="display:none;">
                                    @csrf @method('PATCH')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr class="mp-empty-row"><td colspan="3">Belum ada data tarif.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Poin Kesiapan Jaringan --}}
        <div class="summary-card">
            <h3 class="mp-card-title">
                <span class="mp-card-title-icon blue">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" y1="20" x2="12.01" y2="20"/></svg>
                </span>
                Poin Kesiapan Jaringan
            </h3>
            <table class="data-table">
                <thead><tr><th>Kondisi</th><th>Poin</th><th></th></tr></thead>
                <tbody>
                    @forelse ($poinJaringan as $poin)
                        <tr>
                            <td>{{ $poin->kondisi }}</td>
                            <td>
                                <input
                                    type="number" name="poin"
                                    value="{{ $poin->poin }}" min="0" max="20"
                                    form="poin-form-{{ $poin->id }}"
                                    class="mp-input mp-input-poin">
                            </td>
                            <td>
                                <button type="submit" form="poin-form-{{ $poin->id }}" class="mp-save-btn">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    Simpan
                                </button>
                                <form id="poin-form-{{ $poin->id }}" method="POST" action="{{ route('master-parameter.poin-jaringan.update', $poin) }}" style="display:none;">
                                    @csrf @method('PATCH')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr class="mp-empty-row"><td colspan="3">Belum ada data poin jaringan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Target Tahunan --}}
        <div class="summary-card mp-card-full">
            <h3 class="mp-card-title">
                <span class="mp-card-title-icon green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                </span>
                Target Tahunan
            </h3>

            <table class="data-table" style="margin-bottom:6px;">
                <thead><tr><th>Tahun</th><th>Target Jumlah SPKLU</th></tr></thead>
                <tbody>
                    @forelse ($targetTahunan as $target)
                        <tr>
                            <td style="font-weight:600;">{{ $target->tahun }}</td>
                            <td>{{ number_format($target->target_jumlah_spklu) }} unit</td>
                        </tr>
                    @empty
                        <tr class="mp-empty-row"><td colspan="2">Belum ada target.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <form method="POST" action="{{ route('master-parameter.target.store') }}" class="mp-target-form">
                @csrf
                <div class="mp-field">
                    <label>Tahun</label>
                    <input type="number" name="tahun" min="2020" required class="mp-input" style="width:120px;">
                </div>
                <div class="mp-field">
                    <label>Target Jumlah SPKLU</label>
                    <input type="number" name="target_jumlah_spklu" min="0" required class="mp-input" style="width:180px;">
                </div>
                <button type="submit" class="mp-btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Tambah Target
                </button>
            </form>
        </div>

    </div>

</div>

@endsection