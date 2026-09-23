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
         FLASH MESSAGE
    ========================================================= --}}
    @if (session('success'))
        <div style="background:#ecfdf5; border:1px solid #a7f3d0; color:#065f46; padding:10px 16px; border-radius:8px; margin-bottom:14px; font-size:13px;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div style="background:#fef2f2; border:1px solid #fecaca; color:#991b1b; padding:10px 16px; border-radius:8px; margin-bottom:14px; font-size:13px;">
            {{ session('error') }}
        </div>
    @endif


    {{-- =========================================================
         HEADER
    ========================================================= --}}
    <div style="margin-bottom: 20px;">
        <h1 style="font-size:22px; font-weight:800; color:#0f172a; margin:0 0 4px; letter-spacing:-0.015em;">Kandidat Prioritas</h1>
        <p class="kp-sub" style="margin:0; color:#64748B; font-size:13.5px;">Perhitungan jarak dan pembobotan poin kesiapan lokasi kandidat SPKLU</p>
    </div>


    {{-- =========================================================
         SUMMARY CARDS
    ========================================================= --}}
    <div class="kp-cards">

        <div class="kp-card blue">
            <div class="label">Total Kandidat</div>

            <div class="value">
                {{ $ringkasan['total_kandidat'] }}
            </div>

            <div class="note">
                Seluruh kandidat SPKLU terdaftar
            </div>
        </div>


        <div class="kp-card green">
            <div class="label">Rata-rata Skor Progres</div>

            <div class="value">
                {{ $ringkasan['rata_rata_skor'] }}%
            </div>

            <div class="note">
                dari total poin Fasilitas,
                <br>
                Jaringan &amp; Okupasi
            </div>
        </div>


        <div class="kp-card amber">
            <div class="label">Butuh Perhatian</div>

            <div class="value">
                {{ $ringkasan['butuh_perhatian'] }}
            </div>

            <div class="note">
                ▲ {{ $ringkasan['butuh_perhatian'] }}
                kandidat jarak REAL belum diisi
            </div>
        </div>


        <div class="kp-card rose">
            <div class="label">Komponen Skor Akhir</div>

            <div class="kp-split-row">

                <div class="value-split">
                    {{ $ringkasan['demand_ulp'] }}

                    <small>
                        Demand ULP
                    </small>
                </div>

                <div class="value-split">
                    {{ $ringkasan['kebutuhan_ulp'] }}

                    <small>
                        Kebutuhan
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
            Daftar SPKLU
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

        @php
            $spkluDataJs = [];
        @endphp

        <div class="kp-table-scroll">

            <table class="kp-table">

                <thead>

                    <tr>

                        <th>No</th>

                        <th>
                            Nama Lokasi
                        </th>

                        <th>
                            ULP
                        </th>

                        <th>
                            Kordinat
                        </th>

                        <th>
                            Mitra Mesin
                        </th>

                        <th>
                            3 SPKLU Terdekat
                        </th>

                        <th>
                            Skor Jarak
                        </th>

                        <th>
                            Skor Poin Kapasitas
                        </th>

                        <th>
                            Skor Poin Okupansi +
                            <br>
                            Fasilitas + Jaringan
                        </th>

                        <th>
                            Skor Prioritas Akhir
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($kandidatList as $index => $kandidat)

                        @php

                            $badgeClass =
                                $kandidat->kategori === 'A'
                                    ? 'kp-badge-a'
                                    : (
                                        $kandidat->kategori === 'B'
                                            ? 'kp-badge-b'
                                            : 'kp-badge-c'
                                    );

                            $skorClass =
                                $kandidat->kategori === 'A'
                                    ? 'kp-skor-a'
                                    : (
                                        $kandidat->kategori === 'B'
                                            ? 'kp-skor-b'
                                            : 'kp-skor-c'
                                    );

                        @endphp


                        @php

                            $spkluDataJs[$kandidat->id] =
                                collect($kandidat->spklu_terdekat)
                                ->map(function ($s) {

                                    return [

                                        'nama' =>
                                            is_array($s)
                                                ? ($s['nama'] ?? '')
                                                : ($s->nama_spklu ?? $s->nama ?? ''),

                                        'jarak_km' =>
                                            is_array($s)
                                                ? ($s['jarak_km'] ?? '')
                                                : ($s->jarak_km ?? ''),

                                    ];

                                })
                                ->values();

                        @endphp


                        <tr class="{{ $kandidat->jarak_real_diisi ? '' : 'kp-attn' }}">


                            {{-- NO --}}
                            <td>
                                {{ $kandidatList->firstItem() + $index }}
                            </td>


                            {{-- NAMA LOKASI --}}
                            <td style="min-width:180px; max-width:180px;">

                                <div class="kp-nama">
                                    {{ $kandidat->nama_lokasi }}
                                </div>

                                @unless ($kandidat->jarak_real_diisi)

                                    <div class="kp-warn">
                                        ⚠ Jarak REAL belum diisi
                                    </div>

                                @endunless

                            </td>


                            {{-- ULP --}}
                            <td>
                                {{ $kandidat->ulpMapping->nama_penuh ?? '-' }}
                            </td>


                            {{-- KOORDINAT --}}
                            <td style="white-space:nowrap;">
                                {{ $kandidat->koordinat ?? '-' }}
                            </td>


                            {{-- MITRA --}}
                            <td>
                                {{ $kandidat->mitra_mesin ?? '-' }}
                            </td>


                            {{-- 3 SPKLU TERDEKAT --}}
                            <td style="min-width:200px;">

                                @forelse ($kandidat->spklu_terdekat as $spklu)

                                    @php

                                        $statusClass =
                                            $spklu['status_jarak'] === 'Bagus'
                                                ? 'kp-status-bagus'
                                                : (
                                                    str_starts_with(
                                                        $spklu['status_jarak'],
                                                        'Tidak Bagus'
                                                    )
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
                                                {{ $spklu['jarak_km'] }} km
                                            </span>

                                        </div>

                                        <div
                                            class="kp-spklu-bar {{ $statusClass }}"
                                        ></div>

                                    </div>

                                @empty

                                    <span
                                        style="font-size:12px; color:#9ca3af;"
                                    >
                                        Koordinat belum diisi
                                    </span>

                                @endforelse


                                @if (in_array(auth()->user()->role, ['super_admin', 'pengelola']))
                                {{-- TOMBOL AKSI: INPUT MANUAL & AMBIL OTOMATIS --}}
                                <div style="display:flex; gap:5px; flex-wrap:nowrap; margin-top:6px;">

                                    <button
                                        type="button"
                                        onclick="bukaModalJarak({{ $kandidat->id }})"
                                        style="
                                            flex:1;
                                            display:flex;
                                            align-items:center;
                                            justify-content:center;
                                            gap:4px;
                                            padding:6px 8px;
                                            border-radius:7px;
                                            border:1px solid #e2e8f0;
                                            background:#f8fafc;
                                            color:#475569;
                                            font-size:11px;
                                            font-weight:600;
                                            white-space:nowrap;
                                            cursor:pointer;
                                            transition:background .15s ease;
                                        "
                                        onmouseover="this.style.background='#f1f5f9'"
                                        onmouseout="this.style.background='#f8fafc'"
                                    >

                                        <svg
                                            width="12"
                                            height="12"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2.2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            style="flex-shrink:0;"
                                        >
                                            <path d="M12 20h9"/>
                                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                                        </svg>

                                        Manual

                                    </button>

                                    <form method="POST"
                                          action="{{ route('kandidat-prioritas.spklu-terdekat.otomatis', $kandidat->id) }}"
                                          class="form-ambil-otomatis"
                                          style="flex:1; margin:0;">
                                        @csrf
                                        <button
                                            type="submit"
                                            style="
                                                width:100%;
                                                display:flex;
                                                align-items:center;
                                                justify-content:center;
                                                gap:4px;
                                                padding:6px 8px;
                                                border-radius:7px;
                                                border:1px solid rgba(2,62,138,.18);
                                                background:rgba(2,62,138,.07);
                                                color:#023E8A;
                                                font-size:11px;
                                                font-weight:600;
                                                white-space:nowrap;
                                                cursor:pointer;
                                                transition:background .15s ease;
                                            "
                                            onmouseover="this.style.background='rgba(2,62,138,.13)'"
                                            onmouseout="this.style.background='rgba(2,62,138,.07)'"
                                        >
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                                                <circle cx="12" cy="12" r="10"/>
                                                <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>
                                            </svg>
                                            Otomatis
                                        </button>
                                    </form>

                                </div>
                                @endif

                            </td>


                            {{-- SKOR JARAK --}}
                            <td class="kp-poin">

                                <div class="num">
                                    {{ $kandidat->skor_jarak ?? '-' }}
                                </div>

                            </td>


                            {{-- SKOR KAPASITAS --}}
                            <td class="kp-poin">

                                <div class="num">
                                    {{ $kandidat->skor_poin_kapasitas ?? '-' }}
                                </div>

                                @if (is_null($kandidat->skor_poin_kapasitas))

                                    <div
                                        class="kp-warn"
                                        style="font-size:11px;"
                                    >
                                        Belum ada data unit mesin
                                    </div>

                                @endif

                            </td>


                            {{-- SKOR OKUPANSI --}}
                            <td class="kp-poin">

                                <div class="num">
                                    {{ $kandidat->skor_poin_okupansi ?? '-' }}
                                </div>

                            </td>


                            {{-- SKOR AKHIR --}}
                            <td>

                                <span
                                    class="kp-skor {{ $skorClass }}"
                                >
                                    {{ $kandidat->skor_prioritas_akhir ?? '-' }}
                                </span>

                            </td>


                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="10"
                                class="kp-empty"
                            >
                                Belum ada data kandidat.
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
                ⚠ Jarak REAL belum diisi
            </span>

        </div>

    </div>


    {{-- =========================================================
         PAGINATION
    ========================================================= --}}
    <div style="margin-top:12px;">

        {{ $kandidatList->links() }}

    </div>


    {{-- =========================================================
         MODAL INPUT JARAK REAL
    ========================================================= --}}
    <div
        id="modalJarak"
        style="
            display:none;
            position:fixed;
            inset:0;
            background:rgba(15,23,42,0.5);
            backdrop-filter:blur(2px);
            z-index:50;
            align-items:center;
            justify-content:center;
        "
    >

        <div
            style="
                background:#fff;
                border-radius:12px;
                padding:0;
                width:460px;
                max-width:92%;
                box-shadow:0 20px 50px rgba(0,0,0,0.25);
                overflow:hidden;
            "
        >

            {{-- HEADER MODAL --}}
            <div
                style="
                    padding:18px 24px;
                    border-bottom:1px solid #e2e8f0;
                    display:flex;
                    align-items:center;
                    justify-content:space-between;
                "
            >

                <div>

                    <h3
                        style="
                            margin:0;
                            font-size:16px;
                            font-weight:700;
                            color:#0f172a;
                        "
                    >
                        Input Jarak REAL
                    </h3>

                    <p
                        style="
                            margin:2px 0 0;
                            font-size:12.5px;
                            color:#64748b;
                        "
                    >
                        3 SPKLU terdekat dari lokasi ini
                    </p>

                </div>


                <button
                    type="button"
                    onclick="tutupModalJarak()"
                    style="
                        background:none;
                        border:none;
                        font-size:20px;
                        line-height:1;
                        color:#94a3b8;
                        cursor:pointer;
                        padding:4px;
                    "
                >
                    &times;
                </button>

            </div>


            {{-- FORM --}}
            <form
                id="formJarak"
                method="POST"
            >

                @csrf


                <div
                    style="
                        padding:20px 24px;
                        display:flex;
                        flex-direction:column;
                        gap:12px;
                    "
                >

                    @for ($i = 0; $i < 3; $i++)

                        <div
                            style="
                                display:flex;
                                gap:8px;
                                align-items:center;
                            "
                        >

                            <span
                                style="
                                    flex-shrink:0;
                                    width:22px;
                                    height:22px;
                                    border-radius:50%;
                                    background:#f1f5f9;
                                    color:#475569;
                                    font-size:12px;
                                    font-weight:700;
                                    display:flex;
                                    align-items:center;
                                    justify-content:center;
                                "
                            >
                                {{ $i + 1 }}
                            </span>


                            <input
                                type="text"
                                name="items[{{ $i }}][nama_spklu]"
                                data-role="nama"
                                placeholder="Nama SPKLU #{{ $i + 1 }}"
                                required
                                style="
                                    flex:2;
                                    padding:8px 10px;
                                    border:1px solid #cbd5e1;
                                    border-radius:7px;
                                    font-size:13px;
                                    outline:none;
                                "
                                onfocus="this.style.borderColor='#94a3b8'"
                                onblur="this.style.borderColor='#cbd5e1'"
                            >


                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="items[{{ $i }}][jarak_km]"
                                data-role="jarak"
                                placeholder="Jarak (km)"
                                required
                                style="
                                    flex:1;
                                    padding:8px 10px;
                                    border:1px solid #cbd5e1;
                                    border-radius:7px;
                                    font-size:13px;
                                    outline:none;
                                "
                                onfocus="this.style.borderColor='#94a3b8'"
                                onblur="this.style.borderColor='#cbd5e1'"
                            >

                        </div>

                    @endfor

                </div>


                {{-- FOOTER MODAL --}}
                <div
                    style="
                        display:flex;
                        justify-content:flex-end;
                        gap:8px;
                        padding:16px 24px;
                        background:#f8fafc;
                        border-top:1px solid #e2e8f0;
                    "
                >

                    <button
                        type="button"
                        onclick="tutupModalJarak()"
                        style="
                            padding:8px 16px;
                            border:1px solid #cbd5e1;
                            border-radius:7px;
                            background:#fff;
                            color:#334155;
                            font-size:13px;
                            font-weight:600;
                            cursor:pointer;
                        "
                    >
                        Batal
                    </button>


                    <button
                        type="submit"
                        style="
                            padding:8px 18px;
                            background:#334155;
                            color:#fff;
                            border:none;
                            border-radius:7px;
                            font-size:13px;
                            font-weight:600;
                            cursor:pointer;
                        "
                    >
                        Simpan
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
         JAVASCRIPT
    ========================================================= --}}
    <script>

        const SPKLU_DATA = @json($spkluDataJs ?? []);

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: @json(session('success')),
                confirmButtonColor: '#023E8A',
                timer: 3500,
                timerProgressBar: true,
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: @json(session('error')),
                confirmButtonColor: '#023E8A',
            });
        @endif


        function bukaModalJarak(kandidatId)
        {
            document.getElementById('formJarak').action =
                `/kandidat-prioritas/${kandidatId}/spklu-terdekat`;


            const data =
                SPKLU_DATA[kandidatId] || [];


            const namaInputs =
                document.querySelectorAll(
                    '#formJarak input[data-role="nama"]'
                );


            const jarakInputs =
                document.querySelectorAll(
                    '#formJarak input[data-role="jarak"]'
                );


            namaInputs.forEach((input, i) => {

                input.value =
                    data[i]?.nama ?? '';

            });


            jarakInputs.forEach((input, i) => {

                input.value =
                    data[i]?.jarak_km ?? '';

            });


            document.getElementById('modalJarak').style.display =
                'flex';
        }


        function tutupModalJarak()
        {
            document.getElementById('modalJarak').style.display =
                'none';
        }


        /* Klik area luar modal untuk menutup */
        document.getElementById('modalJarak')
            .addEventListener('click', function(e) {

                if (e.target === this) {
                    tutupModalJarak();
                }

            });


        /* Konfirmasi "Ambil Otomatis" pakai SweetAlert2, ganti native confirm() */
        document.querySelectorAll('.form-ambil-otomatis').forEach(function (form) {

            form.addEventListener('submit', function (e) {

                e.preventDefault();

                Swal.fire({
                    title: 'Ambil Jarak Otomatis?',
                    html: 'Jarak akan dihitung ulang lewat <strong>Google Routes API</strong> dan menimpa data jarak yang ada sekarang.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Ambil Sekarang',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#023E8A',
                    cancelButtonColor: '#94a3b8',
                    reverseButtons: true,
                    borderRadius: '14px',
                }).then(function (result) {

                    if (result.isConfirmed) {

                        Swal.fire({
                            title: 'Mengambil jarak dari Google...',
                            html: 'Mohon tunggu sebentar',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        form.submit();
                    }

                });

            });

        });

    </script>

</div>

@endsection