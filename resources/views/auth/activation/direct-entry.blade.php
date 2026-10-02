<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktivasi Akun — Sistem SPKLU</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #F4F6FB;
            padding: 32px 20px;
            color: #0F172A;
        }

        .act-card {
            background: #FFFFFF;
            border-radius: 20px;
            width: 540px;
            max-width: 100%;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.06), 0 1px 3px rgba(15, 23, 42, 0.04);
            border: 1px solid #E2E8F0;
            overflow: hidden;
        }

        .act-header {
            background: #FFFFFF;
            padding: 32px 36px 22px;
            text-align: center;
            border-bottom: 1px solid #F1F5F9;
        }

        .act-illustration {
            width: 160px;
            height: 125px;
            margin: 0 auto 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .act-illustration svg {
            width: 100%;
            height: 100%;
            overflow: visible;
        }

        .act-header h1 {
            font-size: 21px;
            font-weight: 800;
            color: #1B2559;
            margin-bottom: 6px;
            letter-spacing: -.02em;
        }
        .act-header p {
            font-size: 13px;
            color: #64748B;
            line-height: 1.5;
        }

        .act-body {
            padding: 26px 36px 36px;
        }

        .field-group {
            margin-bottom: 20px;
        }
        .field-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 7px;
            text-transform: uppercase;
            letter-spacing: .04em;
        }
        .field-input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }
        .field-input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            color: #94A3B8;
            z-index: 5;
            transition: color .15s ease;
        }
        .field-input-icon svg {
            width: 18px;
            height: 18px;
            stroke-width: 2;
        }
        .field-input,
        input.field-input,
        .field-input-wrap input {
            width: 100% !important;
            padding-left: 46px !important;
            padding-right: 16px !important;
            padding-top: 13px !important;
            padding-bottom: 13px !important;
            border-radius: 11px !important;
            border: 1.8px solid #CBD5E1 !important;
            font-size: 14px !important;
            color: #0F172A !important;
            font-family: inherit !important;
            transition: all .15s ease !important;
            background: #FFFFFF !important;
            box-sizing: border-box !important;
        }
        .field-input:focus,
        input.field-input:focus,
        .field-input-wrap input:focus {
            outline: none !important;
            border-color: #0081AB !important;
            box-shadow: 0 0 0 4px rgba(0, 129, 171, 0.15) !important;
        }
        .field-input-wrap:focus-within .field-input-icon {
            color: #0081AB;
        }

        /* Status banner */
        .up3-selected-banner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #F0F9FF;
            border: 1.5px solid #BAE6FD;
            border-radius: 12px;
            padding: 10px 14px;
            margin-bottom: 14px;
        }
        .up3-selected-banner.empty {
            background: #F8FAFC;
            border-color: #E2E8F0;
        }
        .up3-selected-left {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .up3-selected-left svg {
            width: 16px;
            height: 16px;
            color: #0284C7;
        }
        .up3-selected-banner.empty .up3-selected-left svg {
            color: #94A3B8;
        }
        .up3-selected-name {
            font-size: 13px;
            font-weight: 700;
            color: #1E293B;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .up3-selected-banner.empty .up3-selected-name {
            font-size: 12.5px;
            font-weight: 500;
            color: #64748B;
        }
        .up3-selected-badge {
            font-size: 10.5px;
            font-weight: 700;
            background: #0284C7;
            color: #FFFFFF;
            padding: 2px 8px;
            border-radius: 6px;
        }
        .up3-banner-code {
            display: inline-block;
            background: #023E8A;
            color: #FFFFFF;
            font-weight: 800;
            font-size: 11px;
            padding: 2px 7px;
            border-radius: 5px;
            letter-spacing: .03em;
        }

        /* Quick Grid 18 Units (6x3) */
        .up3-quick-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 7px;
            margin-bottom: 22px;
        }
        @media (max-width: 520px) {
            .up3-quick-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        .up3-chip-btn {
            background: #FFFFFF;
            border: 1.5px solid #E2E8F0;
            border-radius: 9px;
            padding: 9px 4px;
            font-size: 12.5px;
            font-weight: 750;
            color: #1E293B;
            text-align: center;
            cursor: pointer;
            transition: all .15s ease;
            outline: none;
            width: 100%;
            letter-spacing: .02em;
        }
        .up3-chip-btn:hover {
            border-color: #0081AB;
            background: #F0F9FF;
            color: #023E8A;
            transform: translateY(-1px);
        }
        .up3-chip-btn.active {
            background: #023E8A;
            color: #FFFFFF;
            border-color: #023E8A;
            box-shadow: 0 4px 12px rgba(2, 62, 138, 0.22);
            font-weight: 800;
        }

        .act-btn-submit {
            width: 100%;
            padding: 13px 20px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 800;
            cursor: pointer;
            border: none;
            background: #023E8A;
            color: #FFFFFF;
            box-shadow: 0 4px 14px rgba(2, 62, 138, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all .15s ease;
            margin-top: 10px;
        }
        .act-btn-submit:hover {
            background: #002D66;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(2, 62, 138, 0.32);
        }

        .back-login-box {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #64748B;
        }
        .back-login-link {
            color: #0081AB;
            font-weight: 700;
            text-decoration: none;
        }
        .back-login-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <div class="act-card">
        <div class="act-header">
            <div class="act-illustration">
                <svg viewBox="0 0 180 140" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Soft Background Bubble / Glow -->
                    <ellipse cx="90" cy="78" rx="64" ry="46" fill="#F0F9FF"/>
                    <circle cx="134" cy="50" r="28" fill="#E0F2FE" opacity="0.7"/>
                    <circle cx="48" cy="54" r="22" fill="#E0F2FE" opacity="0.6"/>

                    <!-- Decorative Sparks / Stars in PLN Bright Orange and Electric Yellow -->
                    <path d="M152 30 L154 24 L156 30 L162 32 L156 34 L154 40 L152 34 L146 32 Z" fill="#FFC629"/>
                    <path d="M26 38 L27.5 33 L29 38 L34 39.5 L29 41 L27.5 46 L26 41 L21 39.5 Z" fill="#FB7303"/>
                    <circle cx="34" cy="98" r="3" fill="#0081AB" opacity="0.4"/>
                    <circle cx="158" cy="90" r="3.5" fill="#10B981" opacity="0.5"/>

                    <!-- Smartphone / Verification Screen (Left/Center) -->
                    <g filter="drop-shadow(0 6px 14px rgba(2,62,138,0.10))">
                        <rect x="28" y="34" width="56" height="84" rx="10" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="1.8"/>
                        <!-- Phone Speaker Bar -->
                        <rect x="47" y="39" width="18" height="3" rx="1.5" fill="#CBD5E1"/>
                        <!-- Screen Avatar Profile -->
                        <circle cx="56" cy="58" r="11" fill="#E0F2FE"/>
                        <circle cx="56" cy="55" r="5" fill="#0284C7"/>
                        <path d="M48 66 C48 62.5 51.5 61 56 61 C60.5 61 64 62.5 64 66" fill="#0284C7"/>
                        <!-- Screen Form Input Rows -->
                        <rect x="37" y="74" width="38" height="5" rx="2.5" fill="#F1F5F9" stroke="#E2E8F0" stroke-width="0.8"/>
                        <rect x="37" y="83" width="28" height="5" rx="2.5" fill="#F1F5F9" stroke="#E2E8F0" stroke-width="0.8"/>
                        <!-- OTP Mini Dots / Action button -->
                        <rect x="37" y="94" width="38" height="12" rx="4" fill="url(#btnGrad)"/>
                        <circle cx="48" cy="100" r="1.5" fill="#FFFFFF"/>
                        <circle cx="56" cy="100" r="1.5" fill="#FFFFFF"/>
                        <circle cx="64" cy="100" r="1.5" fill="#FFFFFF"/>
                    </g>

                    <!-- Floating Verification Check Shield (Center-Top) -->
                    <g filter="drop-shadow(0 4px 10px rgba(16,185,129,0.25))">
                        <circle cx="84" cy="34" r="15" fill="#FFFFFF"/>
                        <path d="M84 21 L95 25.5 C95 33.5 90.5 41 84 43.5 C77.5 41 73 33.5 73 25.5 Z" fill="#10B981"/>
                        <path d="M79 32 L82.5 35.5 L89 29" stroke="#FFFFFF" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </g>

                    <!-- Person / User Figure (Right) -->
                    <!-- Body / Polo Shirt (PLN Deep Blue) -->
                    <path d="M102 126 C102 108 114 102 128 102 C142 102 154 108 154 126" fill="#023E8A"/>
                    <!-- Shirt Collar & Button Placket -->
                    <path d="M123 102 L128 112 L133 102" fill="#FFFFFF"/>
                    <line x1="128" y1="112" x2="128" y2="124" stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="round"/>
                    <!-- Lanyard & ID Card -->
                    <path d="M124 106 L128 117 L132 106" stroke="#0081AB" stroke-width="1.5" fill="none"/>
                    <rect x="124.5" y="117" width="7" height="9" rx="1.5" fill="#FFFFFF" stroke="#0081AB" stroke-width="1"/>

                    <!-- Neck -->
                    <rect x="124" y="88" width="8" height="15" rx="3" fill="#FED7AA"/>

                    <!-- Head / Face -->
                    <circle cx="128" cy="76" r="15" fill="#FED7AA"/>
                    <!-- Friendly Facial Expression -->
                    <circle cx="123" cy="74" r="1.5" fill="#1E293B"/>
                    <circle cx="131" cy="74" r="1.5" fill="#1E293B"/>
                    <path d="M125 79 Q128 82 131 79" stroke="#1E293B" stroke-width="1.2" stroke-linecap="round" fill="none"/>

                    <!-- Hair (Neat Modern Style) -->
                    <path d="M113 74 C113 61 120 56 129 56 C140 56 144 63 144 73 C140 71 135 70 128 70 C120 70 115 72 113 74 Z" fill="#1E293B"/>
                    <path d="M113 74 C113 78 115 80 115 80" stroke="#1E293B" stroke-width="2.5" stroke-linecap="round"/>

                    <!-- Arm & Hand Reaching Toward Phone/Check Shield -->
                    <path d="M102 114 C92 108 84 98 76 96" stroke="#023E8A" stroke-width="7" stroke-linecap="round"/>
                    <circle cx="74" cy="95" r="4.5" fill="#FED7AA"/>

                    <!-- Gradient Definition for Mini Screen Button -->
                    <defs>
                        <linearGradient id="btnGrad" x1="37" y1="94" x2="75" y2="106" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#FB7303"/>
                            <stop offset="1" stop-color="#EF6109"/>
                        </linearGradient>
                    </defs>
                </svg>
            </div>
            <h1>Aktivasi Akun Mandiri</h1>
            <p>Masukkan alamat email terdaftar dan pilih Unit UP3 Anda untuk verifikasi via Kode OTP</p>
        </div>

        <div class="act-body">
            @if ($errors->any())
                <div style="background:rgba(192,57,43,.08); border:1px solid rgba(192,57,43,.25); color:#C0392B; padding:12px 14px; border-radius:10px; font-size:12.5px; margin-bottom:18px;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('activation.request-otp-direct') }}" onsubmit="return validateDirectForm()">
                @csrf
                <input type="hidden" name="up3" id="selected-up3-direct" value="{{ old('up3') }}">

                <div class="field-group">
                    <label class="field-label" for="email">Email Terdaftar</label>
                    <div class="field-input-wrap">
                        <span class="field-input-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </span>
                        <input type="email" name="email" id="email" class="field-input" value="{{ old('email') }}" required placeholder="nama@email.com" autofocus>
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label">Pilih Unit UP3 Wilayah Kerja</label>
                    @php 
                        $initDirectUp3 = old('up3');
                        $up3List = $up3Map ?? \App\Models\User::UP3_MAP;
                        $initKode = null;
                        if ($initDirectUp3) {
                            foreach ($up3List as $k => $n) {
                                if ($initDirectUp3 === $k || $initDirectUp3 === $n) {
                                    $initKode = $k;
                                    $initDirectUp3 = $n;
                                    break;
                                }
                            }
                        }
                    @endphp
                    <div id="up3DirectBanner" class="up3-selected-banner {{ $initDirectUp3 ? '' : 'empty' }}">
                        <div class="up3-selected-left">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <div class="up3-selected-name" id="up3DirectText">
                                @if ($initDirectUp3)
                                    @if ($initKode)<span class="up3-banner-code">{{ $initKode }}</span>@endif
                                    <span>{{ $initDirectUp3 }} (UID Jawa Barat)</span>
                                @else
                                    Silakan klik salah satu unit UP3 di bawah
                                @endif
                            </div>
                        </div>
                        <span class="up3-selected-badge" id="up3DirectBadge" style="{{ $initDirectUp3 ? '' : 'display:none;' }}">✓ Terpilih</span>
                    </div>

                    <div class="up3-quick-grid">
                        @foreach ($up3List as $kode => $nama)
                            @php
                                $isAct = ($initDirectUp3 === $kode || $initDirectUp3 === $nama);
                            @endphp
                            <button type="button" 
                                    class="up3-chip-btn {{ $isAct ? 'active' : '' }}" 
                                    data-up3="{{ $nama }}"
                                    data-kode="{{ $kode }}"
                                    title="{{ $nama }}"
                                    onclick="pilihDirectUp3('{{ $nama }}', '{{ $kode }}')">
                                {{ $kode }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="act-btn-submit">
                    Kirim Kode OTP Aktivasi
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="18" height="18" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </button>
            </form>

            <div class="back-login-box">
                Sudah memiliki akun aktif?
                <a href="{{ route('login') }}" class="back-login-link">Masuk di sini</a>
            </div>
        </div>
    </div>

    <script>
        function pilihDirectUp3(nama, kode) {
            document.getElementById('selected-up3-direct').value = nama;
            const banner = document.getElementById('up3DirectBanner');
            const text = document.getElementById('up3DirectText');
            const badge = document.getElementById('up3DirectBadge');

            banner.classList.remove('empty');
            text.innerHTML = `<span class="up3-banner-code">${kode}</span> <span>${nama} (UID Jawa Barat)</span>`;
            badge.style.display = 'inline-block';

            document.querySelectorAll('.up3-chip-btn').forEach(btn => {
                if (btn.getAttribute('data-up3') === nama || btn.getAttribute('data-kode') === kode) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }

        function validateDirectForm() {
            const up3 = document.getElementById('selected-up3-direct').value.trim();
            if (!up3) {
                alert('Silakan pilih salah satu UP3 terlebih dahulu.');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
