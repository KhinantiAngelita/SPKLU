@extends('layouts.app')

@section('breadcrumb', 'FS Skema')
@section('page-title', $fsSkema->nama_lokasi)

@push('styles')
<style>
.fsf-wrap{display:grid;grid-template-columns:1.15fr 1fr;gap:20px;align-items:start}
@media (max-width:1100px){.fsf-wrap{grid-template-columns:1fr}}
.fsf-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:24px;flex-wrap:wrap;gap:12px}
.fsf-header h1{font-size:24px;font-weight:700;color:#1B2559;margin:0}
.fsf-header p{color:#64748B;margin:4px 0 0;font-size:14px}
.fsf-header-actions{display:flex;gap:8px;flex-shrink:0}
.fsf-card{background:#fff;border-radius:14px;box-shadow:0 1px 3px rgba(15,23,42,.08);padding:28px}
.fsf-tabs{display:flex;gap:8px;background:#F1F5F9;padding:4px;border-radius:10px;width:fit-content;margin-bottom:24px}
.fsf-tab-static{padding:8px 22px;border-radius:8px;font-size:14px;font-weight:600;color:#94A3B8}
.fsf-tab-static.active{background:#fff;color:#0081AB;box-shadow:0 1px 2px rgba(15,23,42,.08)}
.fsf-section-title{font-size:15px;font-weight:700;margin:28px 0 14px;padding-top:20px;border-top:1px solid #F1F5F9;letter-spacing:.03em;text-transform:uppercase;color:#0081AB}
.fsf-row{display:grid;grid-template-columns:1fr 1fr;gap:18px;margin-bottom:18px}
.fsf-field label{display:block;font-size:13px;font-weight:600;color:#334155;margin-bottom:6px}
.fsf-field input,.fsf-field select{width:100%;padding:10px 12px;border:1px solid #E2E8F0;border-radius:8px;font-size:14px;color:#0F172A;background:#F8FAFC;cursor:not-allowed}
.fsf-hint{font-size:12px;color:#94A3B8;margin-top:4px}
.fsf-label-group{font-size:13px;font-weight:600;color:#334155;margin-bottom:10px;display:block}
.fsf-chip-group{display:flex;flex-wrap:wrap;gap:10px;margin-bottom:18px}
.fsf-chip-static{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border:1px solid #E2E8F0;border-radius:999px;font-size:13px;font-weight:500;color:#CBD5E1}
.fsf-chip-static.active{background:rgba(0,129,171,.12);border-color:#0081AB;color:#023E8A;font-weight:600}
.fsf-actions{display:flex;justify-content:flex-end;gap:10px;margin-top:28px;padding-top:20px;border-top:1px solid #F1F5F9}
.fsf-btn-outline{padding:10px 20px;border-radius:8px;border:1px solid #E2E8F0;color:#475569;font-weight:600;font-size:14px;text-decoration:none;display:inline-flex;align-items:center;gap:6px}
.fsf-btn-primary{padding:10px 22px;border-radius:8px;border:none;background:#0081AB;color:#fff;font-weight:600;font-size:14px;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:6px}
.fsf-btn-primary:hover{background:#023E8A}
.fsf-btn-danger{padding:10px 20px;border-radius:8px;border:1px solid #FECACA;color:#B91C1C;background:#fff;font-weight:600;font-size:14px;cursor:pointer}

.fsf-mini-card{background:#F8FAFC;border:1px solid #E2E8F0;border-radius:12px;overflow:hidden;margin-top:8px}
.fsf-mini-card-header{background:#FFFFFF;padding:14px 20px;font-size:15px;font-weight:700;color:#1B2559;display:flex;align-items:center;gap:10px;border-bottom:1px solid #E2E8F0}
.fsf-mini-card-header svg{width:17px;height:17px;flex-shrink:0;stroke-width:2;color:#0081AB}
.fsf-mini-card-body{padding:18px 20px}

.fsp-card{background:#fff;border-radius:14px;box-shadow:0 1px 3px rgba(15,23,42,.08);overflow:hidden;margin-bottom:20px}
.fsp-card-header{background:#FFFFFF;padding:14px 20px;font-size:15px;font-weight:700;color:#1B2559;display:flex;align-items:center;gap:10px;border-bottom:1px solid #F1F5F9}
.fsp-card-header svg{width:17px;height:17px;flex-shrink:0;stroke-width:2;color:#0081AB}
.fsp-card-header .fsp-loading{color:#0081AB;font-size:12px;margin-left:auto}
.fsp-card-body{padding:18px 20px}
.fsp-table{width:100%;border-collapse:collapse;font-size:13px}
.fsp-table th{text-align:left;color:#94A3B8;font-weight:600;padding:8px 4px;border-bottom:1px solid #F1F5F9;white-space:nowrap}
.fsp-table td{padding:9px 4px;border-bottom:1px solid #F1F5F9;color:#334155;white-space:nowrap}
.fsp-jarak-bagus{color:#15803D;font-weight:600}
.fsp-jarak-risiko{color:#B91C1C;font-weight:600}
.fsp-jarak-belum{color:#94A3B8;font-style:italic}
.fsp-empty{color:#94A3B8;font-size:13px;padding:6px 0}
.fsp-poin-row{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #F1F5F9;font-size:13.5px}
.fsp-poin-row:last-child{border-bottom:none;font-weight:700;color:#0F172A}
.fsp-status-box{margin-top:14px;padding:10px 14px;border-radius:10px;font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px}
.fsp-status-hijau{background:#DCFCE7;color:#15803D}
.fsp-status-kuning{background:#FEF3C7;color:#B45309}
.fsp-status-merah{background:#FEE2E2;color:#B91C1C}
.fsp-narasi{font-size:13.5px;color:#475569;line-height:1.6;margin:0}
.fsp-estimasi-roi{margin-top:14px;padding:12px 14px;background:#F8FAFC;border-radius:8px;font-size:13px;color:#334155;line-height:1.7}
.fsp-estimasi-roi strong{color:#0F172A}
.fsp-bep-tag{font-size:11px;background:#0081AB;color:#fff;padding:2px 8px;border-radius:999px;font-weight:600;margin-left:6px}

/* Riwayat Analisis */
.fsr-item{position:relative;padding:0 0 20px 22px;border-left:2px solid #E2E8F0}
.fsr-item:last-child{border-left-color:transparent;padding-bottom:0}
.fsr-item::before{content:'';position:absolute;left:-6px;top:2px;width:10px;height:10px;border-radius:999px;background:#0081AB}
.fsr-tanggal{font-size:12px;color:#94A3B8;margin-bottom:4px}
.fsr-head{display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:6px}
.fsr-badge{font-size:11px;font-weight:700;padding:2px 9px;border-radius:999px}
.fsr-poin{font-size:12.5px;color:#64748B}
.fsr-narasi{font-size:13px;color:#475569;line-height:1.5;margin:0}
.fsr-empty{font-size:13px;color:#94A3B8;font-style:italic}
</style>
@endpush

@section('content')
<div class="fsf-header">
    <div>
        <h1>{{ $fsSkema->nama_lokasi }}</h1>
        <p>Detail hasil analisis kelayakan lokasi — hanya tampilan (read-only)</p>
    </div>
    <div class="fsf-header-actions">
        <a href="{{ route('fs-skema.index') }}" class="fsf-btn-outline">&larr; Kembali</a>
        @can('update', $fsSkema)
            <a href="{{ route('fs-skema.edit', $fsSkema) }}" class="fsf-btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
                Edit
            </a>
        @endcan
    </div>
</div>

<div class="fsf-wrap">
    {{-- KOLOM KIRI: DATA (read-only) — strukturnya PERSIS sama seperti form
         Tambah/Edit FS Skema, cuma semua field ditampilkan disabled tanpa
         form submit, dan gak ada live-preview AJAX (datanya udah pasti,
         diambil langsung dari yang tersimpan). --}}
    <div class="fsf-card">
        <div class="fsf-tabs">
            <span class="fsf-tab-static {{ $fsSkema->skema === 'skema_2' ? 'active' : '' }}">Skema 2</span>
            <span class="fsf-tab-static {{ $fsSkema->skema === 'skema_3' ? 'active' : '' }}">Skema 3</span>
        </div>

        <div class="fsf-row">
            <div class="fsf-field">
                <label>Nama Tempat/Lokasi (Nama SPKLU)</label>
                <input type="text" value="{{ $fsSkema->nama_lokasi }}" disabled>
            </div>
            <div class="fsf-field">
                <label>Titik Kordinat</label>
                <input type="text" value="{{ $fsSkema->titik_koordinat ?? '—' }}" disabled>
            </div>
        </div>

        <div class="fsf-row">
            <div class="fsf-field">
                <label>Kandidat Terkait</label>
                <input type="text" value="{{ $fsSkema->kandidat->nama_lokasi ?? $fsSkema->kandidat->lokasi ?? 'Tidak terhubung ke kandidat' }}" disabled>
            </div>
            <div class="fsf-field">
                <label>Layanan Listrik</label>
                <input type="text" value="{{ $fsSkema->layanan_listrik ?? '—' }}" disabled>
            </div>
        </div>

        @if ($fsSkema->skema === 'skema_2')
            <div class="fsf-row">
                <div class="fsf-field">
                    <label>Total RAB Investasi (Rp)</label>
                    <input type="text" value="Rp {{ number_format($fsSkema->total_rab_investasi ?? 0, 0, ',', '.') }}" disabled>
                </div>
                <div class="fsf-field">
                    <label>Mobil/hari</label>
                    <input type="text" value="{{ $fsSkema->mobil_per_hari }}" disabled>
                </div>
            </div>
        @else
            <div class="fsf-row">
                <div class="fsf-field">
                    <label>RAB Mitra Mesin (Rp)</label>
                    <input type="text" value="Rp {{ number_format($fsSkema->rab_mitra_mesin ?? 0, 0, ',', '.') }}" disabled>
                </div>
                <div class="fsf-field">
                    <label>RAB Mitra Lahan (Rp)</label>
                    <input type="text" value="Rp {{ number_format($fsSkema->rab_mitra_lahan ?? 0, 0, ',', '.') }}" disabled>
                </div>
            </div>
            <div class="fsf-row">
                <div class="fsf-field">
                    <label>Mobil/hari</label>
                    <input type="text" value="{{ $fsSkema->mobil_per_hari }}" disabled>
                </div>
                <div class="fsf-field">
                    <label>Sharing Profit Mitra Lahan</label>
                    <input type="text" value="{{ ($fsSkema->sharing_provit_mitra_lahan ?? 0) * 100 }}%" disabled>
                </div>
            </div>
        @endif

        <div class="fsf-row">
            <div class="fsf-field">
                <label>Transaksi kWh/Mobil</label>
                <input type="text" value="{{ $fsSkema->transaksi_kwh_per_mobil }}" disabled>
            </div>
            <div class="fsf-field">
                <label>Masa Kontrak (Tahun)</label>
                <input type="text" value="{{ $fsSkema->masa_kontrak_tahun ?? 5 }} Tahun" disabled>
            </div>
        </div>

        <div class="fsf-section-title">Penilaian Lokasi</div>

        <label class="fsf-label-group">Fasilitas (maks 40 poin)</label>
        <div class="fsf-chip-group">
            @foreach (\App\Services\FsSkemaCalculatorService::LABEL_FASILITAS as $val => $label)
                <span class="fsf-chip-static {{ in_array($val, $fsSkema->fasilitas ?? []) ? 'active' : '' }}">{{ $label }}</span>
            @endforeach
        </div>

        <div class="fsf-field-half" style="max-width: calc(50% - 9px); margin-bottom:18px;">
            <label>Kesiapan Jaringan (maks 20 poin)</label>
            <input type="text" value="{{ $fsSkema->kesiapan_jaringan ?? '—' }}" disabled>
        </div>

        <label class="fsf-label-group">Okupansi (maks 40 poin)</label>
        <div class="fsf-chip-group">
            @foreach (\App\Services\FsSkemaCalculatorService::LABEL_OKUPANSI as $val => $label)
                <span class="fsf-chip-static {{ in_array($val, $fsSkema->okupansi ?? []) ? 'active' : '' }}">{{ $label }}</span>
            @endforeach
        </div>

        <div class="fsf-mini-card">
            <div class="fsf-mini-card-header">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Ringkasan Kelayakan Lokasi
            </div>
            <div class="fsf-mini-card-body">
                <div class="fsp-poin-row"><span>Fasilitas</span><span>{{ $fsSkema->poin_fasilitas }} / 40</span></div>
                <div class="fsp-poin-row"><span>Kesiapan Jaringan</span><span>{{ $fsSkema->poin_kesiapan_jaringan }} / 20</span></div>
                <div class="fsp-poin-row"><span>Okupansi</span><span>{{ $fsSkema->poin_okupansi }} / 40</span></div>
                <div class="fsp-poin-row"><span>TOTAL</span><span>{{ $fsSkema->total_poin }} / 100</span></div>
                <div class="fsp-status-box fsp-status-{{ $fsSkema->status_kelayakan === 'Layak' ? 'hijau' : ($fsSkema->status_kelayakan === 'Menjadi Pertimbangan' ? 'kuning' : 'merah') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    Status Kelayakan: {{ $fsSkema->status_kelayakan }}
                </div>
            </div>
        </div>

        {{-- Riwayat Analisis --}}
        <div class="fsf-mini-card">
            <div class="fsf-mini-card-header">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Riwayat Analisis
            </div>
            <div class="fsf-mini-card-body">
                @if ($riwayatAnalisis->isEmpty())
                    <p class="fsr-empty">Belum ada riwayat perhitungan untuk FS Skema ini.</p>
                @else
                    @foreach ($riwayatAnalisis as $riwayat)
                        <div class="fsr-item">
                            <div class="fsr-tanggal">{{ $riwayat->created_at->translatedFormat('d M Y, H:i') }} WIB
                                @if ($riwayat->pencatat) &middot; oleh {{ $riwayat->pencatat->name }} @endif
                            </div>
                            <div class="fsr-head">
                                <span class="fsr-badge fsp-status-{{ $riwayat->status_kelayakan === 'Layak' ? 'hijau' : ($riwayat->status_kelayakan === 'Menjadi Pertimbangan' ? 'kuning' : 'merah') }}">
                                    {{ $riwayat->status_kelayakan }}
                                </span>
                                <span class="fsr-poin">Total Poin: {{ $riwayat->total_poin }}/100 (Fasilitas {{ $riwayat->poin_fasilitas }}, Jaringan {{ $riwayat->poin_kesiapan_jaringan }}, Okupansi {{ $riwayat->poin_okupansi }})</span>
                            </div>
                            @if ($riwayat->narasi_analisis)
                                <p class="fsr-narasi">{{ $riwayat->narasi_analisis }}</p>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="fsf-actions">
            @can('delete', $fsSkema)
                <form method="POST" action="{{ route('fs-skema.destroy', $fsSkema) }}" data-confirm="Yakin hapus FS Skema ini?" style="display:inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="fsf-btn-danger">Hapus</button>
                </form>
            @endcan
            @can('update', $fsSkema)
                <a href="{{ route('fs-skema.edit', $fsSkema) }}" class="fsf-btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
                    Edit
                </a>
            @endcan
        </div>
    </div>

    {{-- KOLOM KANAN: sama persis seperti form Tambah/Edit, cuma datanya
         sudah pasti (bukan live-preview) — diambil dari controller show(). --}}
    <div>
        <div class="fsp-card">
            <div class="fsp-card-header">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                3 SPKLU Terdekat
            </div>
            <div class="fsp-card-body">
                @if (empty($spkluTerdekat))
                    <div class="fsp-empty">Titik koordinat belum diisi, atau belum ada SPKLU aktif dengan koordinat lengkap.</div>
                @else
                    <table class="fsp-table">
                        <thead><tr><th>Nama SPKLU</th><th>Jarak</th><th>Status Jarak</th></tr></thead>
                        <tbody>
                            @foreach ($spkluTerdekat as $s)
                                <tr>
                                    <td>{{ $s['nama'] }}</td>
                                    <td>{{ number_format($s['jarak_km'], 2) }} km</td>
                                    <td>
                                        @if ($s['status_jarak'] === 'Bagus')
                                            <span class="fsp-jarak-bagus">Bagus</span>
                                        @elseif (str_contains($s['status_jarak'], 'kanibalisasi'))
                                            <span class="fsp-jarak-risiko">Berisiko Kanibalisasi</span>
                                        @else
                                            <span class="fsp-jarak-belum">{{ $s['status_jarak'] }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        <div class="fsp-card">
            <div class="fsp-card-header">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
                Proyeksi ROI {{ $proyeksiRoi['masa_kontrak_tahun'] }} Tahun
            </div>
            <div class="fsp-card-body">
                @if ($proyeksiRoi['tipe'] === 'skema_2')
                    <table class="fsp-table">
                        <thead><tr><th>Tahun</th><th>Mobil/hari</th><th>Transaksi/tahun</th><th>Energi (kWh)</th><th>Pendapatan</th><th>Kumulatif</th></tr></thead>
                        <tbody>
                            @foreach ($proyeksiRoi['tahunan'] as $row)
                                <tr>
                                    <td>{{ $row['tahun'] }} @if($row['sudah_bep'])<span class="fsp-bep-tag">BEP</span>@endif</td>
                                    <td>{{ number_format($row['mobil_per_hari'], 1) }}</td>
                                    <td>{{ number_format($row['transaksi_per_tahun'], 0, ',', '.') }}</td>
                                    <td>{{ number_format($row['energi_kwh_per_tahun'], 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($row['pendapatan_mitra'], 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($row['kumulatif'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="fsp-estimasi-roi">
                        <strong>Estimasi ROI:</strong> {{ $proyeksiRoi['estimasi_roi_teks'] }}
                    </div>
                @else
                    <table class="fsp-table">
                        <thead><tr><th>Tahun</th><th>Mobil/hari</th><th>Transaksi/tahun</th><th>Energi (kWh)</th><th>Pendpt. Mesin</th><th>Pendpt. Lahan</th></tr></thead>
                        <tbody>
                            @foreach ($proyeksiRoi['tahunan'] as $row)
                                <tr>
                                    <td>{{ $row['tahun'] }}</td>
                                    <td>{{ number_format($row['mobil_per_hari'], 1) }}</td>
                                    <td>{{ number_format($row['transaksi_per_tahun'], 0, ',', '.') }}</td>
                                    <td>{{ number_format($row['energi_kwh_per_tahun'], 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($row['pendapatan_mesin'], 0, ',', '.') }} @if($row['sudah_bep_mesin'])<span class="fsp-bep-tag">BEP</span>@endif</td>
                                    <td>Rp {{ number_format($row['pendapatan_lahan'], 0, ',', '.') }} @if($row['sudah_bep_lahan'])<span class="fsp-bep-tag">BEP</span>@endif</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="fsp-estimasi-roi">
                        <strong>Estimasi ROI Mitra Mesin:</strong> {{ $proyeksiRoi['estimasi_roi_mesin_teks'] }}<br>
                        <strong>Estimasi ROI Mitra Lahan:</strong> {{ $proyeksiRoi['estimasi_roi_lahan_teks'] }}
                    </div>
                @endif
            </div>
        </div>

        <div class="fsp-card">
            <div class="fsp-card-header">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/><line x1="2" y1="20" x2="22" y2="20"/></svg>
                Grafik Proyeksi ROI
            </div>
            <div class="fsp-card-body">
                <canvas id="fsp-roi-chart" height="220"></canvas>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
{{--
    Chart.js di-host LOKAL (public/vendor/chartjs/chart.umd.min.js), bukan
    dari CDN — sama seperti form Tambah/Edit, supaya gak bergantung akses
    internet ke CDN eksternal.
--}}
<script src="{{ asset('vendor/chartjs/chart.umd.min.js') }}" onerror="window.__chartJsGagalDimuat = true"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof Chart === 'undefined' || window.__chartJsGagalDimuat) {
        const el = document.getElementById('fsp-roi-chart');
        if (el) el.replaceWith(document.createTextNode('Grafik tidak bisa ditampilkan: library Chart.js gagal dimuat. Pastikan file public/vendor/chartjs/chart.umd.min.js ada.'));
        return;
    }

    const roi = @json($proyeksiRoi);
    const canvas = document.getElementById('fsp-roi-chart');
    if (!canvas || !roi) return;

    const labelsTahun = roi.tahunan.map(r => 'Tahun ' + r.tahun);

    const datasets = roi.tipe === 'skema_2'
        ? [{
            label: 'Progres BEP (%)',
            data: roi.tahunan.map(r => r.persen_progres),
            borderColor: '#0081AB',
            backgroundColor: 'rgba(0,129,171,0.15)',
            tension: 0.3,
            fill: true,
        }]
        : [
            {
                label: 'Progres BEP Mitra Mesin (%)',
                data: roi.tahunan.map(r => r.persen_progres_mesin),
                borderColor: '#0081AB',
                backgroundColor: 'rgba(0,129,171,0.15)',
                tension: 0.3,
                fill: true,
            },
            {
                label: 'Progres BEP Mitra Lahan (%)',
                data: roi.tahunan.map(r => r.persen_progres_lahan),
                borderColor: '#F59E0B',
                backgroundColor: 'rgba(245,158,11,0.12)',
                tension: 0.3,
                fill: true,
            },
        ];

    new Chart(canvas, {
        type: 'line',
        data: { labels: labelsTahun, datasets },
        options: {
            responsive: true,
            scales: {
                y: {
                    ticks: { callback: v => v + '%' },
                    title: { display: true, text: 'Progres menuju BEP (%)' },
                },
            },
            plugins: { legend: { display: true, position: 'bottom' } },
        },
    });
});
</script>
@endpush