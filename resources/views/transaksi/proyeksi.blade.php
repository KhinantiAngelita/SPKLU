@extends('layouts.app')

@section('breadcrumb', 'Transaksi')
@section('page-title', 'Proyeksi Penjualan Energi')

@section('content')
<style>
    /* =========================================================
       Gaya Halaman Proyeksi Energi - Executive Simulator
       ========================================================= */
    .proyeksi-container {
        display: flex;
        flex-direction: column;
        gap: 20px;
        max-width: 1300px;
        margin: 0 auto;
    }

    /* Toolbar Kontrol & Simulator */
    .proyeksi-toolbar {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 10px rgba(15,23,42,0.04);
        padding: 18px 22px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .proyeksi-toolbar-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
        padding-bottom: 14px;
        border-bottom: 1px solid #f1f5f9;
    }

    .proyeksi-toolbar-title h1 {
        margin: 0;
        font-size: 18px;
        font-weight: 800;
        color: var(--brand-dark, #023E8A);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .proyeksi-toolbar-title h1 svg {
        width: 20px;
        height: 20px;
        color: #0081AB;
    }
    .proyeksi-toolbar-title p {
        margin: 4px 0 0;
        font-size: 12.8px;
        color: #64748b;
    }

    .proyeksi-toolbar-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    /* Tombol Aksi */
    .btn-proyeksi {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 14px;
        border-radius: 9px;
        font-size: 12.8px;
        font-weight: 700;
        cursor: pointer;
        border: none;
        transition: all 0.15s ease;
    }
    .btn-proyeksi svg {
        width: 15px;
        height: 15px;
    }
    .btn-proyeksi-reset {
        background: #f1f5f9;
        color: #475569;
    }
    .btn-proyeksi-reset:hover {
        background: #e2e8f0;
        color: #1e293b;
    }
    .btn-proyeksi-csv {
        background: #fff;
        color: #334155;
        border: 1px solid #cbd5e1;
    }
    .btn-proyeksi-csv:hover {
        background: #f8fafc;
        border-color: #94a3b8;
    }
    .btn-proyeksi-download {
        background: linear-gradient(135deg, #023E8A, #0081AB);
        color: #fff;
        box-shadow: 0 3px 10px rgba(2,62,138,0.2);
    }
    .btn-proyeksi-download:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 14px rgba(2,62,138,0.28);
    }

    /* Baris Bawah Toolbar: Kontrol Interaktif */
    .proyeksi-toolbar-bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }

    .proyeksi-controls-left {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
    }

    /* Toggle Satuan Metrik (kWh vs Rp) */
    .metric-toggle-group {
        display: inline-flex;
        background: #f1f5f9;
        padding: 4px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        gap: 3px;
    }
    .btn-metric-toggle {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 7px;
        font-size: 12.5px;
        font-weight: 700;
        border: none;
        background: transparent;
        color: #64748b;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .btn-metric-toggle svg {
        width: 14px;
        height: 14px;
    }
    .btn-metric-toggle.active {
        background: #fff;
        color: #023E8A;
        box-shadow: 0 1px 4px rgba(0,0,0,0.08);
    }

    /* Skenario Pertumbuhan Pills */
    .scenarios-wrapper {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .scenario-label {
        font-size: 12px;
        font-weight: 700;
        color: #475569;
        white-space: nowrap;
    }
    .scenario-pills {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f8fafc;
        padding: 4px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
    }
    .btn-scenario-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 11px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 700;
        border: none;
        background: transparent;
        color: #475569;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .btn-scenario-pill svg {
        width: 13px;
        height: 13px;
    }
    .btn-scenario-pill:hover {
        background: #edf2f7;
        color: #0f172a;
    }
    .btn-scenario-pill.active {
        background: #0081AB;
        color: #fff;
        box-shadow: 0 2px 6px rgba(0, 129, 171, 0.25);
    }
    .btn-scenario-pill.active svg {
        color: #fff;
    }

    /* Input Nilai YoY Custom */
    .yoy-custom-box {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #fff;
        border: 1px solid #cbd5e1;
        padding: 4px 10px;
        border-radius: 8px;
    }
    .yoy-custom-box label {
        font-size: 11.5px;
        font-weight: 700;
        color: #64748b;
        margin: 0;
    }
    .yoy-custom-box input[type="number"] {
        width: 62px;
        padding: 3px 6px;
        border-radius: 5px;
        border: 1px solid #cbd5e1;
        font-size: 12.5px;
        font-weight: 800;
        color: #023E8A;
        text-align: right;
    }
    .yoy-custom-box input[type="number"]:focus {
        outline: none;
        border-color: #0081AB;
    }

    /* Badge Tarif Listrik SPKLU */
    .proyeksi-tarif-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 8px;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
        font-size: 12px;
        font-weight: 700;
    }
    .proyeksi-tarif-badge svg {
        width: 14px;
        height: 14px;
        color: #16a34a;
    }

    /* Frame Slide Presentasi */
    .slide-frame {
        background: #fff;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        padding: 28px 32px;
        box-shadow: 0 8px 30px rgba(15,23,42,0.06);
        position: relative;
        overflow: hidden;
    }

    .slide-main-title {
        font-size: 30px;
        font-weight: 900;
        color: #00609C;
        letter-spacing: -0.03em;
        margin: 0 0 2px;
        text-transform: uppercase;
    }
    .slide-subtitle {
        font-size: 15px;
        font-weight: 700;
        font-style: italic;
        color: #00609C;
        margin: 0 0 22px;
    }

    /* Section Card Grafik */
    .chart-section-card {
        background: #fff;
        border-radius: 14px;
        border: 1.5px solid #e0e7ff;
        box-shadow: 0 4px 20px rgba(0, 96, 156, 0.04);
        padding: 24px 28px;
    }
    .chart-section-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .chart-header-left {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        max-width: 72%;
    }
    .chart-icon-box {
        width: 38px;
        height: 38px;
        min-width: 38px;
        border-radius: 10px;
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0284c7;
    }
    .chart-icon-box svg {
        width: 20px;
        height: 20px;
    }
    .chart-title-area h3 {
        font-size: 18px;
        font-weight: 900;
        color: #0f2942;
        margin: 0 0 4px;
    }
    .chart-title-area p {
        font-size: 12.8px;
        color: #475569;
        margin: 0;
        line-height: 1.4;
    }
    .chart-legend-custom {
        display: flex;
        align-items: center;
        gap: 18px;
        flex-wrap: wrap;
    }
    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12.5px;
        font-weight: 700;
        color: #334155;
    }
    .legend-line {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 12px;
        position: relative;
    }
    .legend-line::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 2.5px;
        border-radius: 2px;
    }
    .legend-line::after {
        content: '';
        position: absolute;
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }
    .legend-line-blue::before { background: #0081AB; }
    .legend-line-blue::after { background: #0081AB; }
    .legend-line-gold::before { background: #FFC629; }
    .legend-line-gold::after { background: #FFC629; }

    /* Canvas Wrapper */
    .canvas-wrapper {
        position: relative;
        height: 340px;
        width: 100%;
        margin-bottom: 8px;
    }

    /* Brackets / Pembatas Timeline Bawah Grafik */
    .timeline-bracket-bar {
        display: flex;
        margin-left: 55px;
        margin-right: 20px;
        margin-bottom: 20px;
        font-size: 11.5px;
        font-weight: 700;
        color: #64748b;
        text-align: center;
    }
    .timeline-bracket-realisasi {
        width: 28.57%;
        border-top: 1.5px solid #cbd5e1;
        padding-top: 5px;
        position: relative;
    }
    .timeline-bracket-realisasi::before,
    .timeline-bracket-realisasi::after {
        content: '';
        position: absolute;
        top: -4px;
        width: 1px;
        height: 7px;
        background: #cbd5e1;
    }
    .timeline-bracket-realisasi::before { left: 0; }
    .timeline-bracket-realisasi::after { right: 0; }

    .timeline-bracket-proyeksi {
        width: 71.43%;
        border-top: 1.5px dashed #f59e0b;
        padding-top: 5px;
        color: #d97706;
        position: relative;
    }
    .timeline-bracket-proyeksi::before,
    .timeline-bracket-proyeksi::after {
        content: '';
        position: absolute;
        top: -4px;
        width: 1px;
        height: 7px;
        background: #f59e0b;
    }
    .timeline-bracket-proyeksi::before { left: 0; }
    .timeline-bracket-proyeksi::after { right: 0; }

    /* Bar Pengatur Mode SPKLU Baru */
    .table-mode-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
        padding: 8px 12px;
        background: #fdfaf3;
        border: 1px solid #fef3c7;
        border-radius: 8px;
    }
    .table-mode-left {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        font-weight: 700;
        color: #92400e;
    }
    .table-mode-left svg {
        width: 14px;
        height: 14px;
        color: #d97706;
    }
    .spklu-mode-pills {
        display: inline-flex;
        gap: 4px;
        background: #fff;
        padding: 2px;
        border-radius: 6px;
        border: 1px solid #fde68a;
    }
    .btn-spklu-mode {
        border: none;
        background: transparent;
        font-size: 11.5px;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 5px;
        cursor: pointer;
        color: #78350f;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .btn-spklu-mode svg {
        width: 12px;
        height: 12px;
    }
    .btn-spklu-mode.active {
        background: #f59e0b;
        color: #fff;
    }
    .btn-spklu-mode.active svg {
        color: #fff;
    }

    /* Tabel Matriks */
    .matrix-table-wrap {
        overflow-x: auto;
        border-radius: 10px;
    }
    .matrix-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 4px 6px;
        font-size: 13px;
        text-align: center;
    }
    .matrix-table th {
        font-size: 11.5px;
        font-weight: 700;
        color: #475569;
        padding: 6px 10px;
        white-space: nowrap;
    }
    .matrix-table th.col-proyeksi {
        color: #b45309;
        background: rgba(254, 243, 199, 0.5);
        border-radius: 6px;
    }
    .matrix-table td {
        padding: 8px 10px;
        font-weight: 700;
        border-radius: 8px;
        transition: all 0.15s ease;
    }

    /* Baris 1: Tambahan SPKLU Baru */
    .badge-label-yellow {
        background: #FFC629;
        color: #023E8A;
        font-weight: 800;
        text-align: left;
        padding: 10px 14px !important;
        border-radius: 8px;
        white-space: nowrap;
    }
    .cell-yellow-data {
        background: #FEF3C7;
        color: #92400E;
        vertical-align: middle;
    }
    .cell-editable-input {
        width: 100%;
        max-width: 110px;
        border: 1px dashed #d97706;
        background: #fff;
        border-radius: 6px;
        padding: 5px 6px;
        font-family: inherit;
        font-weight: 800;
        font-size: 12.8px;
        color: #92400E;
        text-align: center;
        transition: all 0.15s ease;
    }
    .cell-editable-input:focus {
        outline: none;
        border-color: #b45309;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.25);
        background: #fffef0;
    }
    .sub-hint-calc {
        display: block;
        font-size: 10.5px;
        font-weight: 600;
        color: #b45309;
        margin-top: 3px;
    }

    /* Baris 2: Eksisting */
    .badge-label-blue {
        background: #0081AB;
        color: #fff;
        font-weight: 800;
        text-align: left;
        padding: 10px 14px !important;
        border-radius: 8px;
        white-space: nowrap;
    }
    .cell-blue-data {
        background: #E0F2FE;
        color: #0369A1;
    }

    /* Baris 3: Total */
    .badge-label-navy {
        background: #023E8A;
        color: #fff;
        font-weight: 800;
        text-align: left;
        padding: 10px 14px !important;
        border-radius: 8px;
        white-space: nowrap;
    }
    .cell-navy-data {
        background: #DBEAFE;
        color: #1E40AF;
        font-size: 13.5px;
    }

    /* Catatan Bawah */
    .proyeksi-notes-card {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 18px;
        border-radius: 10px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        font-size: 12.5px;
        color: #64748b;
    }
    .proyeksi-notes-card svg {
        width: 18px;
        height: 18px;
        color: #0081AB;
        flex-shrink: 0;
    }
</style>

<div class="proyeksi-container">

    {{-- Toolbar Aksi dan Kontrol Simulator --}}
    <div class="proyeksi-toolbar">
        <div class="proyeksi-toolbar-top">
            <div class="proyeksi-toolbar-title">
                <h1>
                    <i data-lucide="line-chart"></i>
                    Simulasi Proyeksi Penjualan Energi & Pendapatan
                </h1>
                <p>Simulasikan skenario pertumbuhan eksisting serta kontribusi SPKLU baru secara interaktif real-time.</p>
            </div>

            <div class="proyeksi-toolbar-actions">
                <button type="button" class="btn-proyeksi btn-proyeksi-reset" id="btn-reset-data">
                    <i data-lucide="rotate-ccw"></i>
                    Reset Benchmark
                </button>
                <button type="button" class="btn-proyeksi btn-proyeksi-csv" id="btn-export-csv">
                    <i data-lucide="file-spreadsheet"></i>
                    Export CSV
                </button>
                <button type="button" class="btn-proyeksi btn-proyeksi-download" id="btn-download-slide">
                    <i data-lucide="image"></i>
                    Unduh Slide (PNG)
                </button>
            </div>
        </div>

        <div class="proyeksi-toolbar-bottom">
            <div class="proyeksi-controls-left">
                {{-- Toggle Satuan (kWh vs Rp) --}}
                <div class="metric-toggle-group">
                    <button type="button" class="btn-metric-toggle active" data-metric="kwh" id="btn-metric-kwh">
                        <i data-lucide="zap"></i>
                        <span>Energi (kWh)</span>
                    </button>
                    <button type="button" class="btn-metric-toggle" data-metric="rp" id="btn-metric-rp">
                        <i data-lucide="coins"></i>
                        <span>Pendapatan (Rp)</span>
                    </button>
                </div>

                {{-- Skenario Pertumbuhan Preset --}}
                <div class="scenarios-wrapper">
                    <span class="scenario-label">Skenario YoY:</span>
                    <div class="scenario-pills">
                        <button type="button" class="btn-scenario-pill" data-yoy="150" data-key="konservatif" title="Adopsi EV landai">
                            <i data-lucide="shield"></i>
                            <span>Konservatif (+150%)</span>
                        </button>
                        <button type="button" class="btn-scenario-pill active" data-yoy="315" data-key="moderat" title="Tren benchmark resmi">
                            <i data-lucide="scale"></i>
                            <span>Moderat (+315%)</span>
                        </button>
                        <button type="button" class="btn-scenario-pill" data-yoy="450" data-key="agresif" title="Akselerasi adopsi cepat">
                            <i data-lucide="rocket"></i>
                            <span>Agresif (+450%)</span>
                        </button>
                    </div>

                    {{-- Custom Rate Input --}}
                    <div class="yoy-custom-box">
                        <label for="input-yoy-rate">Custom:</label>
                        <input type="number" id="input-yoy-rate" value="{{ $growthYoyPersen }}" step="5" min="0" max="1000">
                        <span style="font-size: 11px; font-weight: 700; color: #64748b;">%</span>
                    </div>
                </div>
            </div>

            {{-- Tarif Reference Badge --}}
            <div class="proyeksi-tarif-badge" title="Tarif listrik SPKLU PLN (kode TR)">
                <i data-lucide="tag"></i>
                <span>Tarif: Rp {{ number_format($tarifPerKwh, 0, ',', '.') }} / kWh</span>
            </div>
        </div>
    </div>

    {{-- Frame Slide Presentasi Utama --}}
    <div class="slide-frame" id="slide-capture-area">

        {{-- Kartu Visual Grafik & Tabel --}}
        <div class="chart-section-card">

            {{-- Header Kartu Grafik & Legend --}}
            <div class="chart-section-header">
                <div class="chart-header-left">
                    <div class="chart-icon-box">
                        <i data-lucide="trending-up" id="chart-main-icon"></i>
                    </div>
                    <div class="chart-title-area">
                        <h3 id="label-chart-title">Proyeksi Penjualan Energi: Eksisting + SPKLU Baru</h3>
                        <p id="label-asumsi-teks">{{ $asumsiTeks }}</p>
                    </div>
                </div>

                <div class="chart-legend-custom">
                    <div class="legend-item">
                        <span class="legend-line legend-line-blue"></span>
                        <span id="legend-eksisting-text">Eksisting (proyeksi)</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-line legend-line-gold"></span>
                        <span id="legend-total-text">Total (Eksisting + SPKLU Baru)</span>
                    </div>
                </div>
            </div>

            {{-- Canvas Chart.js --}}
            <div class="canvas-wrapper">
                <canvas id="chartProyeksiEnergi"></canvas>
            </div>

            {{-- Timeline Brackets --}}
            <div class="timeline-bracket-bar">
                <div class="timeline-bracket-realisasi">
                    &larr; Realisasi 2026 &rarr;
                </div>
                <div class="timeline-bracket-proyeksi">
                    &larr; Proyeksi (termasuk tambahan SPKLU, mulai M+1) &rarr;
                </div>
            </div>

            {{-- Bar Pengatur Mode SPKLU Baru --}}
            <div class="table-mode-bar">
                <div class="table-mode-left">
                    <i data-lucide="sliders"></i>
                    <span>Mode Input Tambahan SPKLU Baru:</span>
                </div>
                <div class="spklu-mode-pills">
                    <button type="button" class="btn-spklu-mode active" data-mode="kwh" id="btn-spklu-mode-kwh">
                        <i data-lucide="zap"></i>
                        <span>Input Angka (kWh)</span>
                    </button>
                    <button type="button" class="btn-spklu-mode" data-mode="unit" id="btn-spklu-mode-unit">
                        <i data-lucide="layers"></i>
                        <span>Estimasi per Unit (+Unit Mesin)</span>
                    </button>
                </div>
            </div>

            {{-- Tabel Matriks --}}
            <div class="matrix-table-wrap">
                <table class="matrix-table" id="proyeksi-matrix-table">
                    <thead>
                        <tr>
                            <th style="width: 250px; text-align: left;"></th>
                            @foreach ($periods as $p)
                                <th class="{{ $p['tipe'] === 'proyeksi' ? 'col-proyeksi' : '' }}">
                                    {{ $p['bulan_label'] }}<br>
                                    <span style="font-size: 10px; font-weight: 600;">({{ $p['tipe'] }})</span>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Baris 1: Tambahan SPKLU Baru --}}
                        <tr>
                            <td class="badge-label-yellow" id="badge-label-baru">
                                Tambahan SPKLU Baru<br>
                                <span style="font-size:10.5px; font-weight:500;" id="unit-label-baru">(kWh/bulan)</span>
                            </td>
                            @foreach ($periods as $idx => $p)
                                <td class="cell-yellow-data">
                                    @if ($p['spklu_baru_kwh'] === null)
                                        <span class="val-empty">-</span>
                                    @else
                                        <input
                                            type="text"
                                            class="cell-editable-input input-spklu-baru"
                                            data-index="{{ $idx }}"
                                            value="{{ number_format($p['spklu_baru_kwh'], 0, ',', '.') }}"
                                        >
                                        <span class="sub-hint-calc" data-index="{{ $idx }}">
                                            ~{{ $p['spklu_baru_unit'] ?? 0 }} unit
                                        </span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>

                        {{-- Baris 2: Eksisting (proyeksi) --}}
                        <tr>
                            <td class="badge-label-blue" id="badge-label-eksisting">
                                Eksisting (proyeksi)<br>
                                <span style="font-size:10.5px; font-weight:500;" id="unit-label-eksisting">(kWh/bulan)</span>
                            </td>
                            @foreach ($periods as $idx => $p)
                                <td class="cell-blue-data cell-eksisting-val" data-index="{{ $idx }}">
                                    {{ number_format($p['eksisting_kwh'], 0, ',', '.') }}
                                </td>
                            @endforeach
                        </tr>

                        {{-- Baris 3: Total --}}
                        <tr>
                            <td class="badge-label-navy" id="badge-label-total">
                                Total<br>
                                <span style="font-size:10.5px; font-weight:500;" id="unit-label-total">(kWh/bulan)</span>
                            </td>
                            @foreach ($periods as $idx => $p)
                                <td class="cell-navy-data cell-total-val" data-index="{{ $idx }}">
                                    {{ number_format($p['total_kwh'], 0, ',', '.') }}
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

    </div>

    {{-- Keterangan / Catatan Kaki --}}
    <div class="proyeksi-notes-card">
        <i data-lucide="info"></i>
        <div>
            <strong>Catatan Simulator:</strong> Gunakan toggle di atas untuk beralih antara metrik <em>Energi (kWh)</em> dan <em>Pendapatan (Rupiah)</em> secara instan berbasis tarif PLN SPKLU (Rp {{ number_format($tarifPerKwh, 0, ',', '.') }}/kWh). Anda juga dapat memilih skenario pertumbuhan YoY secara cepat atau mengubah mode input SPKLU Baru per unit mesin.
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Re-render Lucide icons
    if (window.lucide) {
        window.lucide.createIcons();
    }

    // 1. Data Mentah Awal dari Controller
    const defaultData = @json($periods);
    const tarifPerKwh = {{ $tarifPerKwh }};
    const kwhPerUnit = {{ $kwhPerUnit }};
    const defaultGrowthYoY = {{ $growthYoyPersen }};

    let periodsData = JSON.parse(JSON.stringify(defaultData));
    const baselineEksisting = periodsData.map(p => p.eksisting_kwh);

    // State Aplikasi
    let currentMetric = 'kwh'; // 'kwh' | 'rp'
    let spkluInputMode = 'kwh'; // 'kwh' | 'unit'

    // Format Helpers
    function parseAngka(str) {
        if (!str) return 0;
        const clean = str.toString().replace(/\./g, '').replace(/,/g, '').trim();
        const num = parseFloat(clean);
        return isNaN(num) ? 0 : num;
    }

    function formatNumberId(num) {
        return new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(Math.round(num));
    }

    function formatCompactRupiah(val) {
        const absVal = Math.abs(val);
        if (absVal >= 1000000000) {
            const m = val / 1000000000;
            return 'Rp ' + m.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' M';
        }
        if (absVal >= 1000000) {
            const jt = val / 1000000;
            return 'Rp ' + jt.toLocaleString('id-ID', { minimumFractionDigits: 1, maximumFractionDigits: 1 }) + ' Jt';
        }
        return 'Rp ' + formatNumberId(val);
    }

    // 2. Setup Chart.js
    const ctx = document.getElementById('chartProyeksiEnergi').getContext('2d');

    const labels = periodsData.map(p => p.bulan_label);
    const eksistingSeries = periodsData.map(p => p.eksisting_kwh);
    const totalSeries = periodsData.map(p => p.total_kwh);

    // Custom Plugin untuk Shading Area Proyeksi (kuning lembut) & Garis Vertikal Dotted
    const projectionAreaPlugin = {
        id: 'projectionArea',
        beforeDraw: (chartInstance) => {
            const { ctx: c, chartArea, scales } = chartInstance;
            if (!chartArea || !scales || !scales.x) return;

            const { top, bottom, height } = chartArea;
            const x = scales.x;

            const xSep = x.getPixelForValue(1);
            const xOkt = x.getPixelForValue(2);
            const dividerX = (xSep + xOkt) / 2;
            const rightEdge = chartArea.right;

            c.save();

            // Background kuning transparan
            c.fillStyle = 'rgba(254, 243, 199, 0.35)';
            c.fillRect(dividerX, top, rightEdge - dividerX, height);

            // Garis putus-putus
            c.beginPath();
            c.setLineDash([4, 4]);
            c.strokeStyle = '#f59e0b';
            c.lineWidth = 1.5;
            c.moveTo(dividerX, top);
            c.lineTo(dividerX, bottom);
            c.stroke();

            // Label 'Area Proyeksi'
            c.setLineDash([]);
            c.font = 'bold 11px sans-serif';
            c.fillStyle = '#b45309';
            c.textAlign = 'left';
            c.fillText('Area Proyeksi', dividerX + 8, top + 16);

            c.restore();
        }
    };

    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Total (Eksisting + SPKLU Baru)',
                    data: totalSeries,
                    borderColor: '#FFC629',
                    backgroundColor: '#FFC629',
                    borderWidth: 3.5,
                    pointBackgroundColor: '#FFC629',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2.5,
                    pointRadius: 6.5,
                    pointHoverRadius: 9,
                    tension: 0.28,
                    order: 1,
                    datalabels: {
                        align: 'top',
                        offset: 7,
                        color: '#92400e',
                        backgroundColor: '#fef3c7',
                        borderColor: '#f59e0b',
                        borderWidth: 1,
                        borderRadius: 5,
                        padding: { top: 3, bottom: 3, left: 6, right: 6 },
                        font: { weight: 'bold', size: 10.5 },
                        formatter: function(value) {
                            return currentMetric === 'rp' ? formatCompactRupiah(value) : formatNumberId(value);
                        }
                    }
                },
                {
                    label: 'Eksisting (proyeksi)',
                    data: eksistingSeries,
                    borderColor: '#0081AB',
                    backgroundColor: '#0081AB',
                    borderWidth: 3,
                    pointBackgroundColor: '#0081AB',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2.5,
                    pointRadius: 6,
                    pointHoverRadius: 8.5,
                    tension: 0.28,
                    order: 2,
                    datalabels: {
                        align: 'bottom',
                        offset: 7,
                        color: '#0369a1',
                        backgroundColor: '#e0f2fe',
                        borderColor: '#0284c7',
                        borderWidth: 1,
                        borderRadius: 5,
                        padding: { top: 3, bottom: 3, left: 6, right: 6 },
                        font: { weight: 'bold', size: 10.5 },
                        formatter: function(value) {
                            return currentMetric === 'rp' ? formatCompactRupiah(value) : formatNumberId(value);
                        }
                    }
                }
            ]
        },
        plugins: [ChartDataLabels, projectionAreaPlugin],
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: { top: 30, right: 25, bottom: 5, left: 10 }
            },
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.94)',
                    titleFont: { size: 13, weight: 'bold' },
                    bodyFont: { size: 12.5 },
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(ctxTooltip) {
                            const val = ctxTooltip.parsed.y;
                            if (currentMetric === 'rp') {
                                return ctxTooltip.dataset.label + ': Rp ' + formatNumberId(val);
                            }
                            return ctxTooltip.dataset.label + ': ' + formatNumberId(val) + ' kWh';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 2200000,
                    title: {
                        display: true,
                        text: 'kWh/bulan',
                        font: { size: 12, weight: 'bold' },
                        color: '#64748b'
                    },
                    ticks: {
                        stepSize: 500000,
                        callback: function(val) {
                            if (currentMetric === 'rp') {
                                return formatCompactRupiah(val);
                            }
                            return formatNumberId(val);
                        },
                        font: { size: 11, weight: '600' },
                        color: '#64748b'
                    },
                    grid: {
                        color: '#f1f5f9',
                        drawBorder: false
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: {
                        font: { size: 12.5, weight: '700' },
                        color: '#1e293b'
                    }
                }
            }
        }
    });

    // 3. Logika Perhitungan Dinamis Real-Time
    function updateKalkulasi() {
        const yoyRate = parseFloat(document.getElementById('input-yoy-rate').value) || defaultGrowthYoY;
        const scaleFactor = yoyRate / defaultGrowthYoY;

        const newTotalSeries = [];
        const newEksistingSeries = [];

        periodsData.forEach((p, idx) => {
            // Hitung eksisting
            let eksistingVal = baselineEksisting[idx];
            if (p.tipe === 'proyeksi') {
                eksistingVal = Math.round(baselineEksisting[idx] * (1 + (scaleFactor - 1) * 0.15));
            }

            // Ambil input SPKLU baru
            let spkluBaruKwh = 0;
            const inputEl = document.querySelector(`.input-spklu-baru[data-index="${idx}"]`);
            if (inputEl) {
                const rawInput = parseAngka(inputEl.value);
                if (spkluInputMode === 'unit') {
                    // Raw input adalah jumlah unit
                    p.spklu_baru_unit = rawInput;
                    spkluBaruKwh = rawInput * kwhPerUnit;
                    p.spklu_baru_kwh = spkluBaruKwh;
                } else {
                    // Raw input adalah kWh
                    spkluBaruKwh = rawInput;
                    p.spklu_baru_kwh = spkluBaruKwh;
                    p.spklu_baru_unit = Math.round(spkluBaruKwh / kwhPerUnit);
                }
            }

            // Simpan nilai terhitung
            p.eksisting_kwh = eksistingVal;
            p.total_kwh = eksistingVal + spkluBaruKwh;

            p.eksisting_rp = Math.round(p.eksisting_kwh * tarifPerKwh);
            p.spklu_baru_rp = Math.round(spkluBaruKwh * tarifPerKwh);
            p.total_rp = Math.round(p.total_kwh * tarifPerKwh);

            // Tentukan data series yang masuk grafik sesuai metric aktif
            const chartEksisting = currentMetric === 'rp' ? p.eksisting_rp : p.eksisting_kwh;
            const chartTotal = currentMetric === 'rp' ? p.total_rp : p.total_kwh;

            newEksistingSeries.push(chartEksisting);
            newTotalSeries.push(chartTotal);

            // Update sel di DOM tabel
            const cellEksisting = document.querySelector(`.cell-eksisting-val[data-index="${idx}"]`);
            if (cellEksisting) {
                cellEksisting.textContent = currentMetric === 'rp'
                    ? 'Rp ' + formatNumberId(p.eksisting_rp)
                    : formatNumberId(p.eksisting_kwh);
            }

            const cellTotal = document.querySelector(`.cell-total-val[data-index="${idx}"]`);
            if (cellTotal) {
                cellTotal.textContent = currentMetric === 'rp'
                    ? 'Rp ' + formatNumberId(p.total_rp)
                    : formatNumberId(p.total_kwh);
            }

            // Update sub-hint
            const hintEl = document.querySelector(`.sub-hint-calc[data-index="${idx}"]`);
            if (hintEl) {
                if (spkluInputMode === 'unit') {
                    hintEl.textContent = currentMetric === 'rp'
                        ? formatCompactRupiah(p.spklu_baru_rp)
                        : formatNumberId(p.spklu_baru_kwh) + ' kWh';
                } else {
                    hintEl.textContent = '~' + (p.spklu_baru_unit || 0) + ' unit';
                }
            }
        });

        // Update Chart Datasets
        chart.data.datasets[0].data = newTotalSeries;
        chart.data.datasets[1].data = newEksistingSeries;

        // Auto Scale Sumbu Y
        const maxVal = Math.max(...newTotalSeries);
        if (currentMetric === 'rp') {
            chart.options.scales.y.title.text = 'Pendapatan (Rupiah) / bulan';
            chart.options.scales.y.ticks.stepSize = 1000000000;
            if (maxVal > 5000000000) {
                chart.options.scales.y.max = Math.ceil((maxVal * 1.15) / 1000000000) * 1000000000;
            } else {
                chart.options.scales.y.max = 5500000000;
            }
        } else {
            chart.options.scales.y.title.text = 'kWh/bulan';
            chart.options.scales.y.ticks.stepSize = 500000;
            if (maxVal > 2000000) {
                chart.options.scales.y.max = Math.ceil((maxVal * 1.15) / 500000) * 500000;
            } else {
                chart.options.scales.y.max = 2200000;
            }
        }

        chart.update();

        // Update Header & Deskripsi
        const metricName = currentMetric === 'rp' ? 'Pendapatan Penjualan Energi' : 'Penjualan Energi';
        document.getElementById('label-chart-title').textContent = `Proyeksi ${metricName}: Eksisting + SPKLU Baru`;
        document.getElementById('label-asumsi-teks').textContent =
            `Penjualan energi eksisting diproyeksikan naik sesuai pertumbuhan realisasi (+${yoyRate}% YoY), ditambah kontribusi SPKLU baru (dengan asumsi mulai M+1).`;
    }

    // 4. Toggle Satuan Metrik (kWh vs Rp)
    function switchMetric(metric) {
        if (currentMetric === metric) return;
        currentMetric = metric;

        document.querySelectorAll('.btn-metric-toggle').forEach(b => {
            b.classList.toggle('active', b.dataset.metric === metric);
        });

        // Update Tabel Header Satuan
        const unitSuffix = metric === 'rp' ? '(Rupiah/bulan)' : '(kWh/bulan)';
        document.getElementById('unit-label-baru').textContent = unitSuffix;
        document.getElementById('unit-label-eksisting').textContent = unitSuffix;
        document.getElementById('unit-label-total').textContent = unitSuffix;

        updateKalkulasi();
    }

    document.getElementById('btn-metric-kwh').addEventListener('click', () => switchMetric('kwh'));
    document.getElementById('btn-metric-rp').addEventListener('click', () => switchMetric('rp'));

    // 5. Toggle Mode Input SPKLU Baru (Angka kWh vs Jumlah Unit)
    function switchSpkluMode(mode) {
        if (spkluInputMode === mode) return;
        spkluInputMode = mode;

        document.querySelectorAll('.btn-spklu-mode').forEach(b => {
            b.classList.toggle('active', b.dataset.mode === mode);
        });

        // Update tampilan input kotak di tabel
        document.querySelectorAll('.input-spklu-baru').forEach(input => {
            const idx = parseInt(input.dataset.index, 10);
            const p = periodsData[idx];

            if (mode === 'unit') {
                input.value = (p.spklu_baru_unit && p.spklu_baru_unit > 0) ? formatNumberId(p.spklu_baru_unit) : '';
                input.placeholder = '+Unit';
            } else {
                input.value = (p.spklu_baru_kwh && p.spklu_baru_kwh > 0) ? formatNumberId(p.spklu_baru_kwh) : '';
                input.placeholder = 'kWh';
            }
        });

        updateKalkulasi();
    }

    document.getElementById('btn-spklu-mode-kwh').addEventListener('click', () => switchSpkluMode('kwh'));
    document.getElementById('btn-spklu-mode-unit').addEventListener('click', () => switchSpkluMode('unit'));

    // 6. Preset Skenario Pertumbuhan
    document.querySelectorAll('.btn-scenario-pill').forEach(btn => {
        btn.addEventListener('click', () => {
            const yoy = btn.dataset.yoy;
            document.getElementById('input-yoy-rate').value = yoy;

            document.querySelectorAll('.btn-scenario-pill').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            updateKalkulasi();
        });
    });

    // Custom YoY input listener
    const inputYoy = document.getElementById('input-yoy-rate');
    inputYoy.addEventListener('input', () => {
        const val = parseFloat(inputYoy.value);
        document.querySelectorAll('.btn-scenario-pill').forEach(b => {
            b.classList.toggle('active', parseFloat(b.dataset.yoy) === val);
        });
        updateKalkulasi();
    });

    // Event Listener pada input cell tabel SPKLU Baru
    document.querySelectorAll('.input-spklu-baru').forEach(input => {
        input.addEventListener('input', (e) => {
            const raw = parseAngka(e.target.value);
            e.target.value = raw > 0 ? formatNumberId(raw) : (e.target.value === '' ? '' : '0');
            updateKalkulasi();
        });
    });

    // Reset ke Benchmark
    document.getElementById('btn-reset-data').addEventListener('click', () => {
        document.getElementById('input-yoy-rate').value = defaultGrowthYoY;

        // Reset skenario pill ke Moderat
        document.querySelectorAll('.btn-scenario-pill').forEach(b => {
            b.classList.toggle('active', b.dataset.key === 'moderat');
        });

        // Reset mode ke kWh
        switchSpkluMode('kwh');
        switchMetric('kwh');

        periodsData = JSON.parse(JSON.stringify(defaultData));

        document.querySelectorAll('.input-spklu-baru').forEach(input => {
            const idx = parseInt(input.dataset.index, 10);
            const val = defaultData[idx].spklu_baru_kwh;
            input.value = val !== null ? formatNumberId(val) : '';
        });

        updateKalkulasi();

        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'info',
            title: 'Data proyeksi dikembalikan ke benchmark resmi.',
            showConfirmButton: false,
            timer: 2500
        });
    });

    // Unduh Slide Presentasi sebagai Gambar PNG (html2canvas)
    document.getElementById('btn-download-slide').addEventListener('click', () => {
        const slideArea = document.getElementById('slide-capture-area');

        Swal.fire({
            title: 'Mempersiapkan Gambar...',
            text: 'Sedang merender slide presentasi resolusi tinggi.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        html2canvas(slideArea, {
            scale: 2.2,
            useCORS: true,
            backgroundColor: '#ffffff'
        }).then(canvas => {
            Swal.close();
            const link = document.createElement('a');
            const suffix = currentMetric === 'rp' ? 'Pendapatan' : 'Energi';
            link.download = `Proyeksi-${suffix}-SPKLU-` + new Date().toISOString().slice(0, 10) + '.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        }).catch(err => {
            Swal.close();
            Swal.fire('Gagal', 'Tidak dapat merender gambar slide.', 'error');
            console.error(err);
        });
    });

    // Ekspor Data ke CSV (Mencakup data kWh dan Rp)
    document.getElementById('btn-export-csv').addEventListener('click', () => {
        let csvContent = 'data:text/csv;charset=utf-8,';
        csvContent += 'Indikator,' + periodsData.map(p => `"${p.bulan_label} (${p.tipe})"`).join(',') + '\n';

        const rowBaruKwh = ['"Tambahan SPKLU Baru (kWh)"'];
        const rowBaruRp = ['"Tambahan SPKLU Baru (Rp)"'];
        const rowEksKwh = ['"Eksisting (kWh)"'];
        const rowEksRp = ['"Eksisting (Rp)"'];
        const rowTotKwh = ['"Total Penjualan (kWh)"'];
        const rowTotRp = ['"Total Pendapatan (Rp)"'];

        periodsData.forEach((p, idx) => {
            rowBaruKwh.push(p.spklu_baru_kwh || 0);
            rowBaruRp.push(p.spklu_baru_rp || 0);
            rowEksKwh.push(p.eksisting_kwh || 0);
            rowEksRp.push(p.eksisting_rp || 0);
            rowTotKwh.push(p.total_kwh || 0);
            rowTotRp.push(p.total_rp || 0);
        });

        csvContent += rowBaruKwh.join(',') + '\n';
        csvContent += rowBaruRp.join(',') + '\n';
        csvContent += rowEksKwh.join(',') + '\n';
        csvContent += rowEksRp.join(',') + '\n';
        csvContent += rowTotKwh.join(',') + '\n';
        csvContent += rowTotRp.join(',') + '\n';

        const encodedUri = encodeURI(csvContent);
        const link = document.createElement('a');
        link.setAttribute('href', encodedUri);
        link.setAttribute('download', `Proyeksi_SPKLU_${currentMetric.toUpperCase()}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });
});
</script>
@endpush
@endsection
