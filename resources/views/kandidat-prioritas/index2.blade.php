@extends('layouts.app')

@section('title', 'Kandidat Prioritas')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/kandidat.css') }}">
    <style>
        /* FIX: pagination default Laravel nganggep Tailwind ada (SVG panah
           Prev/Next pakai class h-5 w-5 yang gak ngefek tanpa Tailwind,
           jatuh ke ukuran default browser ~300px). Dirapiin di sini biar
           konsisten sama desain sistem, tanpa perlu ganti default view
           Laravel-nya lewat provider. */
        nav[role="navigation"] { display: flex; align-items: center; justify-content: center; gap: 5px; margin-top: 18px; flex-wrap: wrap; font-size: 13.3px; }
        nav[role="navigation"] svg { width: 16px !important; height: 16px !important; display: inline-block; vertical-align: middle; }
        nav[role="navigation"] a, nav[role="navigation"] span {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 34px; height: 34px; padding: 0 10px; border-radius: 9px;
            font-weight: 600; color: #64748B; text-decoration: none; transition: all .15s ease;
        }
        nav[role="navigation"] a:hover { background: #F6F8FA; color: #0081AB; }
        nav[role="navigation"] span[aria-current="page"] { background: linear-gradient(135deg, #023E8A, #0081AB); color: #fff; }
        nav[role="navigation"] .hidden, nav[role="navigation"] .sr-only { display: none !important; }
    </style>
@endpush

@section('content')

<div class="kp-wrap">

    {{-- =========================================================
         HEADER
    ========================================================= --}}
    <div style="margin-bottom: 20px;">
        <h1 style="font-size:22px; font-weight:800; color:#1B2559; margin:0 0 4px; letter-spacing:-0.015em;">Peringkat Kandidat</h1>
        <p class="kp-sub" style="margin:0; color:#64748B; font-size:13.5px;">Ranking Akhir &mdash; 20% Progres + 20% Kapasitas + 20% Demand ULP + 20% Kebutuhan + 20% Okupansi/Fasilitas/Jaringan</p>
    </div>


    {{-- =========================================================
         SUMMARY CARDS
    ========================================================= --}}
    <div class="kp-cards">

        <div class="kp-card blue">
            <div class="kp-card-top">
                <div class="label">Total Kandidat</div>
                <div class="kp-card-icon blue">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
            </div>

            <div class="value">
                {{ $ringkasan['total_kandidat'] }}
            </div>

            <div class="note">
                Kandidat dengan TIKOR terisi &amp; siap diranking
            </div>
        </div>


        <div class="kp-card green">
            <div class="kp-card-top">
                <div class="label">Rata-rata Skor Akhir</div>
                <div class="kp-card-icon green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
            </div>

            <div class="value">
                {{ $ringkasan['rata_rata_skor'] }}
            </div>

            <div class="note">
                dari seluruh kandidat yang sudah diranking
            </div>
        </div>


        <div class="kp-card amber">
            <div class="kp-card-top">
                <div class="label">Prioritas Tinggi</div>
                <div class="kp-card-icon amber">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </div>
            </div>

            <div class="value">
                {{ $ringkasan['prioritas_tinggi'] }}
            </div>

            <div class="note">
                kandidat dengan Skor Akhir &gt; 80
            </div>
        </div>


        <div class="kp-card rose">
            <div class="kp-card-top">
                <div class="label">Komponen Rata-rata</div>
                <div class="kp-card-icon rose">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                </div>
            </div>

            <div class="kp-split-row">

                <div class="value-split">
                    {{ $ringkasan['rata_rata_progres'] }}%

                    <small>
                        Skor Progres
                    </small>
                </div>

                <div class="value-split">
                    {{ $ringkasan['rata_rata_kebutuhan'] }}

                    <small>
                        Skor Kebutuhan
                    </small>
                </div>

            </div>

            <div class="note">
                rata-rata dari seluruh kandidat
            </div>
        </div>

    </div>


    {{-- =========================================================
         FILTER
    ========================================================= --}}
    <div class="kp-strip">

        <span class="kp-strip-title">
            Ranking Kandidat
        </span>

        <form method="GET" class="kp-filter-form">

            <input
                type="text"
                name="search"
                value="{{ $filter['search'] }}"
                placeholder="Search nama lokasi..."
            >

            <select
                name="ulp_mapping_id"
                onchange="this.form.submit()"
            >
                <option value="">
                    Semua ULP
                </option>

                @foreach ($daftarUlp as $ulp)

                    <option
                        value="{{ $ulp->id }}"
                        @selected($filter['ulpId'] == $ulp->id)
                    >
                        {{ $ulp->nama_penuh }}
                    </option>

                @endforeach

            </select>

        </form>

    </div>


    {{-- =========================================================
         TABLE
    ========================================================= --}}
    <div class="kp-table-wrap">

        <div class="kp-table-scroll">

            <table class="kp-table">

                <thead>

                    <tr>

                        <th>Rank</th>

                        <th>
                            Nama Lokasi
                        </th>

                        <th>
                            ULP
                        </th>

                        <th>
                            Mitra Mesin
                        </th>

                        <th>
                            Skor Progres
                        </th>

                        <th>SPKLU Terdekat</th>

                        <th>
                            Skor Demand ULP
                        </th>
                        <th>
                            Skor Kapasitas
                        </th>
                        <th>
                            Skor Kebutuhan
                        </th>
                        <th>Skor Poin Okupansi + <br> Fasilitas + Jaringan</th>


                        <th>
                            Status
                        </th>

                        <th>
                            Skor Akhir
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($kandidatList as $kandidat)

                        @php

                            $badgeClass =
                                $kandidat->kategori_peringkat === 'A'
                                    ? 'kp-badge-a'
                                    : (
                                        $kandidat->kategori_peringkat === 'B'
                                            ? 'kp-badge-b'
                                            : 'kp-badge-c'
                                    );

                            $skorClass =
                                $kandidat->kategori_peringkat === 'A'
                                    ? 'kp-skor-a'
                                    : (
                                        $kandidat->kategori_peringkat === 'B'
                                            ? 'kp-skor-b'
                                            : 'kp-skor-c'
                                    );

                        @endphp


                        <tr class="{{ is_null($kandidat->skor_akhir) ? 'kp-attn' : '' }}">


                            {{-- RANK --}}
                            <td>

                                @if ($kandidat->rank)

                                    <span class="kp-skor {{ $skorClass }}">
                                        #{{ $kandidat->rank }}
                                    </span>

                                @else

                                    -

                                @endif

                            </td>


                            {{-- NAMA LOKASI --}}
                            <td style="min-width:180px; max-width:180px;">

                                <div class="kp-nama">
                                    {{ $kandidat->nama_lokasi }}
                                </div>

                                @if (is_null($kandidat->skor_akhir))

                                    <div class="kp-warn">
                                        ⚠ TIKOR belum diisi
                                    </div>

                                @endif

                            </td>


                            {{-- ULP --}}
                            <td>
                                {{ $kandidat->ulpMapping->nama_penuh ?? '-' }}
                            </td>


                            {{-- MITRA --}}
                            <td>
                                {{ $kandidat->mitra_mesin ?? '-' }}
                            </td>


                            {{-- SKOR PROGRES --}}
                            <td class="kp-poin">

                                <div class="num">
                                    {{ $kandidat->skor_progres_peringkat !== null ? round($kandidat->skor_progres_peringkat * 100) . '%' : '-' }}
                                </div>

                            </td>

                            {{-- SPKLU TERDEKAT --}}
                            <td style="min-width:200px;">

                                @forelse ($kandidat->spklu_terdekat_list as $spklu)

                                    @php

                                        $statusClass =
                                            $spklu['status_jarak'] === 'Bagus'
                                                ? 'kp-status-bagus'
                                                : (
                                                    str_starts_with($spklu['status_jarak'], 'Tidak Bagus')
                                                        ? 'kp-status-tidak'
                                                        : 'kp-status-belum'
                                                );

                                    @endphp

                                    <div class="kp-spklu-item">

                                        <div class="kp-spklu-row">

                                            <span
                                                class="kp-spklu-nama"
                                                title="{{ $spklu['nama'] }}"
                                            >
                                                {{ $spklu['nama'] }}
                                            </span>

                                            <span
                                                class="kp-jarak-badge"
                                                title="{{ $spklu['status_jarak'] }}"
                                            >
                                                {{ $spklu['jarak_km'] !== null ? number_format($spklu['jarak_km'], 2) : '-' }} km
                                            </span>

                                        </div>

                                        <div class="kp-spklu-bar {{ $statusClass }}"></div>

                                    </div>

                                @empty

                                    <span style="font-size:12px; color:#9ca3af;">
                                        Koordinat belum diisi
                                    </span>

                                @endforelse

                            </td>


                            {{-- SKOR DEMAND ULP --}}
                            <td class="kp-poin">

                                <div class="num">
                                    {{ $kandidat->skor_demand_ulp ?? '-' }}
                                </div>

                            </td>

                            {{-- SKOR KAPASITAS --}}
                            <td class="kp-poin">
                                <div class="num">
                                    {{ $kandidat->skor_kapasitas ?? '-' }}
                                </div>
                            </td>


                            {{-- SKOR KEBUTUHAN --}}
                            <td class="kp-poin">

                                <div class="num">
                                    {{ $kandidat->skor_kebutuhan ?? '-' }}
                                </div>

                            </td>

                            {{-- SKOR POIN OKUPANSI --}}
                            <td class="kp-poin">
                                <div class="num">
                                    {{ $kandidat->skor_poin_okupansi ?? '-' }}
                                </div>
                            </td>

                            {{-- STATUS --}}
                            <td>
                                {{ $kandidat->status_tampil ?? '-' }}
                            </td>


                            {{-- SKOR AKHIR --}}
                            <td>

                                <span class="kp-skor {{ $skorClass }}">
                                    {{ $kandidat->skor_akhir ?? '-' }}
                                </span>

                            </td>


                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="9"
                                class="kp-empty"
                            >
                                Belum ada kandidat yang bisa diranking.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- =====================================================
             LEGEND
        ====================================================== --}}
        <div class="kp-legend">

            <strong>
                Keterangan:
            </strong>

            <span>
                <span
                    class="kp-dot"
                    style="background:#22c55e;"
                ></span>

                Skor &gt;80 Prioritas Tinggi
            </span>


            <span>
                <span
                    class="kp-dot"
                    style="background:#f59e0b;"
                ></span>

                Skor 50-79 Prioritas Sedang
            </span>


            <span>
                <span
                    class="kp-dot"
                    style="background:#ef4444;"
                ></span>

                Skor &lt;50 Prioritas Rendah
            </span>


            <span style="color:#f59e0b;">
                ⚠ TIKOR belum diisi (tidak bisa diranking)
            </span>

        </div>

    </div>


    {{-- =========================================================
         PAGINATION
    ========================================================= --}}
    <div style="margin-top:12px;">

        {{ $kandidatList->links() }}

    </div>

</div>

@endsection