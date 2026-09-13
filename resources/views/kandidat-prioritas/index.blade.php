@extends('layouts.app')

@section('title', 'Kandidat Prioritas')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/kandidat.css') }}">
    <style>
        /* ============ Design tokens ============ */
        .kp-wrap{ --kp-blue:#2563eb; --kp-blue-dark:#1d4ed8; --kp-blue-soft:#eff6ff;
                   --kp-green:#16a34a; --kp-green-soft:#f0fdf4;
                   --kp-amber:#d97706; --kp-amber-soft:#fffbeb;
                   --kp-rose:#e11d48; --kp-rose-soft:#fff1f2;
                   --kp-ink:#0f172a; --kp-sub:#64748b; --kp-border:#e2e8f0;
                   --kp-radius:12px; }

        .kp-wrap h1{ font-size:22px; font-weight:800; color:var(--kp-ink); margin-bottom:2px; }
        .kp-sub{ color:var(--kp-sub); font-size:13.5px; margin-bottom:18px; }

        /* ============ Summary cards ============ */
        .kp-cards{ display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:22px; }
        .kp-card{ background:#fff; border-radius:var(--kp-radius); padding:16px 18px;
                   border:1px solid var(--kp-border); box-shadow:0 1px 2px rgba(15,23,42,.04);
                   transition:box-shadow .18s ease, transform .18s ease; position:relative; overflow:hidden; }
        .kp-card::before{ content:""; position:absolute; top:0; left:0; width:4px; height:100%; }
        .kp-card.blue::before{ background:var(--kp-blue); }
        .kp-card.green::before{ background:var(--kp-green); }
        .kp-card.amber::before{ background:var(--kp-amber); }
        .kp-card.rose::before{ background:var(--kp-rose); }
        .kp-card:hover{ box-shadow:0 8px 20px rgba(15,23,42,.08); transform:translateY(-2px); }
        .kp-card .label{ font-size:12.5px; font-weight:600; color:var(--kp-sub); text-transform:uppercase; letter-spacing:.03em; }
        .kp-card .value{ font-size:26px; font-weight:800; color:var(--kp-ink); margin:6px 0 4px; }
        .kp-card .note{ font-size:12px; color:var(--kp-sub); line-height:1.4; }
        .kp-card.amber .note{ color:var(--kp-amber); font-weight:600; }

        .kp-split-row{ display:flex; gap:18px; margin:6px 0 4px; }
        .value-split{ font-size:22px; font-weight:800; color:var(--kp-ink); display:flex; flex-direction:column; }
        .value-split small{ font-size:11px; font-weight:600; color:var(--kp-sub); text-transform:uppercase; letter-spacing:.02em; }

        /* ============ Strip: title + filters ============ */
        .kp-strip{ display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;
                    background:#fff; border:1px solid var(--kp-border); border-radius:var(--kp-radius) var(--kp-radius) 0 0;
                    padding:14px 18px; }
        .kp-strip-title{ font-size:15px; font-weight:700; color:var(--kp-ink); }
        .kp-filter-form{ display:flex; gap:8px; }
        .kp-filter-form input[type="text"]{
            padding:8px 12px; border:1px solid var(--kp-border); border-radius:8px; font-size:13px;
            min-width:220px; outline:none; transition:border-color .15s ease, box-shadow .15s ease; }
        .kp-filter-form input[type="text"]:focus{ border-color:var(--kp-blue); box-shadow:0 0 0 3px rgba(37,99,235,.12); }
        .kp-filter-form select{
            padding:8px 12px; border:1px solid var(--kp-border); border-radius:8px; font-size:13px;
            background:#fff; color:var(--kp-ink); outline:none; cursor:pointer; }
        .kp-filter-form select:focus{ border-color:var(--kp-blue); }

        /* ============ Table ============ */
        .kp-table-wrap{ background:#fff; border:1px solid var(--kp-border); border-top:none;
                         border-radius:0 0 var(--kp-radius) var(--kp-radius); overflow:hidden; }
        .kp-table-scroll{ overflow-x:auto; -webkit-overflow-scrolling:touch; }
        .kp-table{ width:100%; min-width:1180px; border-collapse:collapse; }
        .kp-table thead th{
            position:sticky; top:0; background:#eff6ff; color:var(--kp-sub); font-size:11.5px;
            font-weight:700; text-transform:uppercase; letter-spacing:.03em; text-align:left;
            padding:10px 12px; border-bottom:1px solid var(--kp-border); white-space:nowrap; }
        .kp-table tbody td{ padding:12px; font-size:13px; color:var(--kp-ink); border-bottom:1px solid #f1f5f9; vertical-align:top; }
        .kp-table tbody tr:hover{ background:#f8fafc; }
        .kp-table tbody tr.kp-attn{ background:var(--kp-amber-soft); }
        .kp-table tbody tr.kp-attn:hover{ background:#fef3c7; }
        .kp-table tbody tr:last-child td{ border-bottom:none; }

        /* Freeze kolom No & Nama Lokasi saat tabel digeser ke samping */
        .kp-table th:nth-child(1), .kp-table td:nth-child(1){ position:sticky; left:0; width:48px; }
        .kp-table th:nth-child(2), .kp-table td:nth-child(2){ position:sticky; left:48px; width:180px; box-shadow:2px 0 6px rgba(15,23,42,.06); }
        .kp-table thead th:nth-child(1), .kp-table thead th:nth-child(2){ z-index:3; }
        .kp-table tbody td:nth-child(1), .kp-table tbody td:nth-child(2){ z-index:1; background:#fff; }
        .kp-table tbody tr:hover td:nth-child(1), .kp-table tbody tr:hover td:nth-child(2){ background:#f8fafc; }
        .kp-table tbody tr.kp-attn td:nth-child(1), .kp-table tbody tr.kp-attn td:nth-child(2){ background:var(--kp-amber-soft); }
        .kp-table tbody tr.kp-attn:hover td:nth-child(1), .kp-table tbody tr.kp-attn:hover td:nth-child(2){ background:#fef3c7; }

        .kp-nama{ font-weight:700; color:var(--kp-ink); font-size:13.5px; }
        .kp-warn{ color:var(--kp-amber); font-size:11.5px; font-weight:600; margin-top:2px;
                   display:flex; align-items:center; gap:4px; }

        /* Kategori badge */
        .kp-badge-kategori{ display:inline-flex; align-items:center; justify-content:center; min-width:26px;
                              height:22px; padding:0 8px; border-radius:999px; font-size:12px; font-weight:800; }
        .kp-badge-a{ background:var(--kp-green-soft); color:var(--kp-green); border:1px solid #bbf7d0; }
        .kp-badge-b{ background:var(--kp-amber-soft); color:var(--kp-amber); border:1px solid #fde68a; }
        .kp-badge-c{ background:var(--kp-rose-soft); color:var(--kp-rose); border:1px solid #fecdd3; }

        /* Skor prioritas akhir */
        .kp-skor{ display:inline-flex; align-items:center; justify-content:center; min-width:40px;
                    padding:5px 10px; border-radius:8px; font-size:13px; font-weight:800; }
        .kp-skor-a{ background:var(--kp-green); color:#fff; }
        .kp-skor-b{ background:var(--kp-amber); color:#fff; }
        .kp-skor-c{ background:var(--kp-rose); color:#fff; }

        .kp-poin .num{ font-weight:700; font-size:13.5px; color:var(--kp-ink); }

        /* SPKLU terdekat list */
        .kp-spklu-item{ margin-bottom:8px; }
        .kp-spklu-row{ display:flex; align-items:center; justify-content:space-between; gap:8px; margin-bottom:3px; }
        .kp-spklu-nama{ font-size:12px; color:var(--kp-blue); font-weight:600; max-width:120px;
                          overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .kp-jarak-badge{ font-size:11px; font-weight:700; color:var(--kp-ink); background:#f1f5f9;
                           padding:2px 7px; border-radius:6px; flex-shrink:0; }
        .kp-spklu-bar{ height:5px; border-radius:999px; background:#e2e8f0; overflow:hidden; position:relative; }
        .kp-spklu-bar::after{ content:""; position:absolute; inset:0; border-radius:999px; }
        .kp-status-bagus::after{ background:var(--kp-green); width:100%; }
        .kp-status-tidak::after{ background:var(--kp-rose); width:100%; }
        .kp-status-belum::after{ background:var(--kp-amber); width:60%; }

        /* Legend */
        .kp-legend{ display:flex; align-items:center; flex-wrap:wrap; gap:16px; padding:14px 18px;
                     background:#f8fafc; border-top:1px solid var(--kp-border); font-size:12px; color:var(--kp-sub); }
        .kp-legend strong{ color:var(--kp-ink); font-size:12.5px; }
        .kp-legend span{ display:inline-flex; align-items:center; gap:6px; }
        .kp-dot{ width:9px; height:9px; border-radius:50%; display:inline-block; }

        .kp-empty{ text-align:center; padding:40px 20px !important; color:var(--kp-sub); font-size:14px; }

        /* Input Jarak REAL button */
        .kp-btn-input-jarak{ margin-top:8px; width:100%; display:flex; align-items:center; justify-content:center;
            gap:7px; font-size:12.5px; font-weight:700; padding:9px 10px; border:none; border-radius:8px;
            background:linear-gradient(135deg, #3b82f6, #2563eb); color:#fff; cursor:pointer; letter-spacing:.2px;
            box-shadow:0 2px 6px rgba(37,99,235,.35); transition:all .18s ease; }
        .kp-btn-input-jarak:hover{ background:linear-gradient(135deg, #2563eb, #1d4ed8);
            box-shadow:0 4px 10px rgba(37,99,235,.45); transform:translateY(-1px); }

        @media (max-width: 1100px){
            .kp-cards{ grid-template-columns:repeat(2,1fr); }
        }
        @media (max-width: 640px){
            .kp-cards{ grid-template-columns:1fr; }
            .kp-strip{ flex-direction:column; align-items:stretch; }
            .kp-filter-form{ flex-direction:column; }
            .kp-filter-form input[type="text"]{ min-width:0; }
        }
    </style>
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
            <div class="note">Seluruh kandidat SPKLU terdaftar</div>
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

    {{-- ===== Strip: judul + search + filter, satu baris ===== --}}
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
        @php $spkluDataJs = []; @endphp

        <div class="kp-table-scroll">
        <table class="kp-table">
            <thead>
                <tr>
                    <th>No</th>
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
                    @php
                        $spkluDataJs[$kandidat->id] = collect($kandidat->spklu_terdekat)->map(function ($s) {
                            return [
                                'nama' => is_array($s) ? ($s['nama'] ?? '') : ($s->nama_spklu ?? $s->nama ?? ''),
                                'jarak_km' => is_array($s) ? ($s['jarak_km'] ?? '') : ($s->jarak_km ?? ''),
                            ];
                        })->values();
                    @endphp
                    <tr class="{{ $kandidat->jarak_real_diisi ? '' : 'kp-attn' }}">
                        <td>{{ $kandidatList->firstItem() + $index }}</td>

                        <td style="min-width:180px; max-width:180px;">
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
                                onclick="bukaModalJarak({{ $kandidat->id }})">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                Input Jarak REAL
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
                        <td colspan="10" class="kp-empty">Belum ada data kandidat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>

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
    <div id="modalJarak" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.5); backdrop-filter:blur(2px); z-index:50; align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:12px; padding:0; width:460px; max-width:92%; box-shadow:0 20px 50px rgba(0,0,0,0.25); overflow:hidden;">

            <div style="padding:18px 24px; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between;">
                <div>
                    <h3 style="margin:0; font-size:16px; font-weight:700; color:#0f172a;">Input Jarak REAL</h3>
                    <p style="margin:2px 0 0; font-size:12.5px; color:#64748b;">3 SPKLU terdekat dari lokasi ini</p>
                </div>
                <button type="button" onclick="tutupModalJarak()" style="background:none; border:none; font-size:20px; line-height:1; color:#94a3b8; cursor:pointer; padding:4px;">&times;</button>
            </div>

            <form id="formJarak" method="POST">
                @csrf
                <div style="padding:20px 24px; display:flex; flex-direction:column; gap:12px;">
                    @for ($i = 0; $i < 3; $i++)
                        <div style="display:flex; gap:8px; align-items:center;">
                            <span style="flex-shrink:0; width:22px; height:22px; border-radius:50%; background:#eff6ff; color:#2563eb; font-size:12px; font-weight:700; display:flex; align-items:center; justify-content:center;">{{ $i + 1 }}</span>
                            <input type="text" name="items[{{ $i }}][nama_spklu]" data-role="nama"
                                placeholder="Nama SPKLU #{{ $i + 1 }}" required
                                style="flex:2; padding:8px 10px; border:1px solid #cbd5e1; border-radius:7px; font-size:13px; outline:none;"
                                onfocus="this.style.borderColor='#2563eb'" onblur="this.style.borderColor='#cbd5e1'">
                            <input type="number" step="0.01" min="0" name="items[{{ $i }}][jarak_km]" data-role="jarak"
                                placeholder="Jarak (km)" required
                                style="flex:1; padding:8px 10px; border:1px solid #cbd5e1; border-radius:7px; font-size:13px; outline:none;"
                                onfocus="this.style.borderColor='#2563eb'" onblur="this.style.borderColor='#cbd5e1'">
                        </div>
                    @endfor
                </div>

                <div style="display:flex; justify-content:flex-end; gap:8px; padding:16px 24px; background:#f8fafc; border-top:1px solid #e2e8f0;">
                    <button type="button" onclick="tutupModalJarak()"
                        style="padding:8px 16px; border:1px solid #cbd5e1; border-radius:7px; background:#fff; color:#334155; font-size:13px; font-weight:600; cursor:pointer;">
                        Batal
                    </button>
                    <button type="submit"
                        style="padding:8px 18px; background:#2563eb; color:#fff; border:none; border-radius:7px; font-size:13px; font-weight:600; cursor:pointer;">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const SPKLU_DATA = @json($spkluDataJs ?? []);

        function bukaModalJarak(kandidatId) {
            document.getElementById('formJarak').action = `/kandidat-prioritas/${kandidatId}/spklu-terdekat`;

            const data = SPKLU_DATA[kandidatId] || [];

            const namaInputs = document.querySelectorAll('#formJarak input[data-role="nama"]');
            const jarakInputs = document.querySelectorAll('#formJarak input[data-role="jarak"]');

            namaInputs.forEach((input, i) => {
                input.value = data[i]?.nama ?? '';
            });
            jarakInputs.forEach((input, i) => {
                input.value = data[i]?.jarak_km ?? '';
            });

            document.getElementById('modalJarak').style.display = 'flex';
        }

        function tutupModalJarak() {
            document.getElementById('modalJarak').style.display = 'none';
        }
    </script>
</div>


@endsection