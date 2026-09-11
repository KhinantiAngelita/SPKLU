@extends('layouts.app')

@section('title', 'Kandidat Prioritas')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/kandidat.css') }}">
@endpush

@section('content')

<div class="kp-wrap">

    <div>
        <h1>Kandidat Prioritas</h1>
        <p class="kp-sub">Ringkasan Sistem SPKLU</p>
    </div>

    {{-- ===== Summary cards ===== --}}
    <div class="kp-cards">
        <div class="kp-card blue">
            <div class="label">Total Kandidat</div>
            <div class="value">{{ $ringkasan['total_kandidat'] }}</div>
            <div class="note">
                {{ $kandidatList->where('kategori', 'A')->count() }} kategori A ·
                {{ $kandidatList->where('kategori', 'B')->count() }} kategori B
            </div>
        </div>

        <div class="kp-card green">
            <div class="label">Rata-rata Skor Progres</div>
            <div class="value">{{ $ringkasan['rata_rata_skor'] }}%</div>
            <div class="note">dari total poin Fasilitas, <br>Jaringan &amp; Okupasi</div>
        </div>

        <div class="kp-card amber">
            <div class="label">Butuh Perhatian</div>
            <div class="value">{{ $ringkasan['butuh_perhatian'] }}</div>
            <div class="note">▲ {{ $ringkasan['butuh_perhatian'] }} kandidat jarak REAL belum diisi</div>
        </div>

        <div class="kp-card rose">
            <div class="label">Komponen Skor Akhir</div>
            <div class="kp-split-row">
                <div class="value-split">{{ $ringkasan['demand_ulp'] }}<small>Demand ULP</small></div>
                <div class="value-split">{{ $ringkasan['kebutuhan_ulp'] }}<small>Kebutuhan</small></div>
            </div>
            <div class="note">rata-rata dari seluruh kandidat</div>
        </div>
    </div>

    {{-- ===== Strip: judul + search + filter + tombol Skema, satu baris ===== --}}
    <div class="kp-strip">
        <span class="kp-strip-title">Daftar SPKLU</span>

        <form method="GET" class="kp-filter-form">
            <input type="text" name="search" value="{{ $filter['search'] }}" placeholder="Search nama lokasi...">

            <select name="ulp_mapping_id" onchange="this.form.submit()">
                <option value="">Semua ULP</option>
                @foreach ($daftarUlp as $ulp)
                    <option value="{{ $ulp->id }}" @selected($filter['ulpId'] == $ulp->id)>
                        {{ $ulp->nama_penuh }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    {{-- ===== Table ===== --}}
    <div class="kp-table-wrap">
        <table class="kp-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th>Nama Lokasi</th>
                    <th>ULP</th>
                    <th>Kordinat</th>
                    <th>Mitra Mesin</th>
                    <th>3 SPKLU Terdekat</th>
                    <th>Skor Jarak</th>
                    <th>Skor Poin Kapasitas</th>
                    <th>Skor Poin Okupansi +<br>Fasilitas + Jaringan</th>
                    <th>Skor Prioritas Akhir</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kandidatList as $index => $kandidat)
                    @php
                        $badgeClass = $kandidat->kategori === 'A' ? 'kp-badge-a' : ($kandidat->kategori === 'B' ? 'kp-badge-b' : 'kp-badge-c');
                        $skorClass = $kandidat->kategori === 'A' ? 'kp-skor-a' : ($kandidat->kategori === 'B' ? 'kp-skor-b' : 'kp-skor-c');
                        $lineColor = fn ($v) => $v > 80 ? '#22c55e' : ($v >= 50 ? '#f59e0b' : '#ef4444');
                    @endphp
                    <tr class="{{ $kandidat->jarak_real_diisi ? '' : 'kp-attn' }}">
                        <td>{{ $kandidatList->firstItem() + $index }}</td>

                        <td><span class="kp-badge-kategori {{ $badgeClass }}">{{ $kandidat->kategori }}</span></td>

                        <td style="min-width:180px;">
                            <div class="kp-nama">{{ $kandidat->nama_lokasi }}</div>
                            @unless ($kandidat->jarak_real_diisi)
                                <div class="kp-warn">⚠ Jarak REAL belum diisi</div>
                            @endunless
                        </td>

                        <td>{{ $kandidat->ulpMapping->nama_penuh ?? '-' }}</td>
                        <td style="white-space:nowrap;">{{ $kandidat->koordinat ?? '-' }}</td>
                        <td>{{ $kandidat->mitra_mesin ?? '-' }}</td>

                        {{-- 3 SPKLU Terdekat — dihitung on-the-fly dari Master SPKLU --}}
                        <td style="min-width:200px;">
                            @forelse ($kandidat->spklu_terdekat as $spklu)
                                @php
                                    $statusClass = $spklu['status_jarak'] === 'Bagus' ? 'kp-status-bagus'
                                        : (str_starts_with($spklu['status_jarak'], 'Tidak Bagus') ? 'kp-status-tidak' : 'kp-status-belum');
                                @endphp
                                <div class="kp-spklu-item">
                                    <div class="kp-spklu-row">
                                        <span class="kp-spklu-nama" title="{{ $spklu['nama'] }}">{{ $spklu['nama'] }}</span>
                                        <span class="kp-jarak-badge" title="{{ $spklu['status_jarak'] }}">{{ $spklu['jarak_km'] }} km</span>
                                    </div>
                                    <div class="kp-spklu-bar {{ $statusClass }}"></div>
                                </div>
                            @empty
                                <span style="font-size:12px; color:#9ca3af;">Koordinat belum diisi</span>
                            @endforelse

                            <button type="button"
                                class="kp-btn-input-jarak"
                                onclick="bukaModalJarak({{ $kandidat->id }})"
                                style="margin-top:6px; font-size:11px; padding:2px 8px; border:1px solid #cbd5e1; border-radius:4px; background:#fff; cursor:pointer;">
                                ✎ Input Jarak REAL
                            </button>
                        </td>

                        <td class="kp-poin">
                            <div class="num">{{ $kandidat->skor_jarak ?? '-' }}</div>
                        </td>

                        <td class="kp-poin">
                            <div class="num">{{ $kandidat->skor_poin_kapasitas ?? '-' }}</div>
                            @if (is_null($kandidat->skor_poin_kapasitas))
                                <div class="kp-warn" style="font-size:11px;">Belum ada data unit mesin</div>
                            @endif
                        </td>

                        <td class="kp-poin">
                            <div class="num">{{ $kandidat->skor_poin_okupansi ?? '-' }}</div>
                        </td>

                        <td><span class="kp-skor {{ $skorClass }}">{{ $kandidat->skor_prioritas_akhir ?? '-' }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="kp-empty">Belum ada data kandidat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="kp-legend">
            <strong>Keterangan:</strong>
            <span><span class="kp-dot" style="background:#22c55e;"></span> Skor &gt;80 Prioritas Tinggi</span>
            <span><span class="kp-dot" style="background:#f59e0b;"></span> Skor 50-79 Prioritas Sedang</span>
            <span><span class="kp-dot" style="background:#ef4444;"></span> Skor &lt;50 Prioritas Rendah</span>
            <span style="color:#f59e0b;">⚠ Jarak REAL belum diisi</span>
        </div>
    </div>

    <div style="margin-top:12px;">
        {{ $kandidatList->links() }}
    </div>
    
    {{-- ===== Modal input jarak REAL 3 SPKLU terdekat ===== --}}
    <div id="modalJarak" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:50; align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:8px; padding:20px; width:420px; max-width:90%;">
            <h3 style="margin:0 0 12px;">Input Jarak REAL — 3 SPKLU Terdekat</h3>

            <form id="formJarak" method="POST">
                @csrf
                @for ($i = 0; $i < 3; $i++)
                    <div style="display:flex; gap:8px; margin-bottom:8px;">
                        <input type="text" name="items[{{ $i }}][nama_spklu]" placeholder="Nama SPKLU #{{ $i + 1 }}" required style="flex:2; padding:6px;">
                        <input type="number" step="0.01" min="0" name="items[{{ $i }}][jarak_km]" placeholder="Jarak (km)" required style="flex:1; padding:6px;">
                    </div>
                @endfor

                <div style="display:flex; justify-content:flex-end; gap:8px; margin-top:12px;">
                    <button type="button" onclick="tutupModalJarak()" style="padding:6px 14px;">Batal</button>
                    <button type="submit" style="padding:6px 14px; background:#2563eb; color:#fff; border:none; border-radius:4px;">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function bukaModalJarak(kandidatId) {
            document.getElementById('formJarak').action = `/kandidat-prioritas/${kandidatId}/spklu-terdekat`;
            document.getElementById('modalJarak').style.display = 'flex';
        }

        function tutupModalJarak() {
            document.getElementById('modalJarak').style.display = 'none';
        }
    </script>
</div>


@endsection