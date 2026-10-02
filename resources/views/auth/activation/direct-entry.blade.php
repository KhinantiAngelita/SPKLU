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
            background-color: #E2EFF9;
            background: linear-gradient(145deg, #EBF5FC 0%, #D8ECF8 100%);
            padding: 12px 16px;
            color: #0F172A;
        }

        .act-card {
            background: #FFFFFF;
            border-radius: 18px;
            width: 520px;
            max-width: 100%;
            box-shadow: 0 8px 30px rgba(2, 62, 138, 0.08), 0 2px 6px rgba(2, 62, 138, 0.04);
            border: 1px solid rgba(2, 62, 138, 0.08);
            overflow: hidden;
        }

        .act-header {
            background: #FFFFFF;
            padding: 16px 28px 10px;
            text-align: center;
            border-bottom: 1px solid #F1F5F9;
        }

        .act-illustration {
            width: 140px;
            height: 84px;
            margin: 0 auto 5px;
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
            font-size: 19px;
            font-weight: 800;
            color: #1B2559;
            margin-bottom: 3px;
            letter-spacing: -.02em;
        }
        .act-header p {
            font-size: 12px;
            color: #64748B;
            line-height: 1.4;
        }

        .act-body {
            padding: 14px 28px 18px;
        }

        .field-group {
            margin-bottom: 11px;
        }
        .field-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 5px;
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
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            color: #94A3B8;
            z-index: 5;
            transition: color .15s ease;
        }
        .field-input-icon svg {
            width: 16px;
            height: 16px;
            stroke-width: 2;
        }
        .field-input,
        input.field-input,
        .field-input-wrap input {
            width: 100% !important;
            padding-left: 42px !important;
            padding-right: 14px !important;
            padding-top: 9.5px !important;
            padding-bottom: 9.5px !important;
            border-radius: 9px !important;
            border: 1.6px solid #CBD5E1 !important;
            font-size: 13.5px !important;
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
            box-shadow: 0 0 0 3px rgba(0, 129, 171, 0.15) !important;
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
            border-radius: 9px;
            padding: 7px 12px;
            margin-bottom: 8px;
        }
        .up3-selected-banner.empty {
            background: #F8FAFC;
            border-color: #E2E8F0;
        }
        .up3-selected-left {
            display: flex;
            align-items: center;
            gap: 7px;
        }
        .up3-selected-left svg {
            width: 15px;
            height: 15px;
            color: #0284C7;
        }
        .up3-selected-banner.empty .up3-selected-left svg {
            color: #94A3B8;
        }
        .up3-selected-name {
            font-size: 12px;
            font-weight: 700;
            color: #1E293B;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .up3-selected-banner.empty .up3-selected-name {
            font-size: 11.5px;
            font-weight: 500;
            color: #64748B;
        }
        .up3-selected-badge {
            font-size: 10px;
            font-weight: 700;
            background: #0284C7;
            color: #FFFFFF;
            padding: 2px 7px;
            border-radius: 5px;
        }
        .up3-banner-code {
            display: inline-block;
            background: #023E8A;
            color: #FFFFFF;
            font-weight: 800;
            font-size: 10.5px;
            padding: 2px 6px;
            border-radius: 4px;
            letter-spacing: .03em;
        }

        /* Quick Grid 18 Units (6x3) */
        .up3-quick-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 5px;
            margin-bottom: 12px;
        }
        @media (max-width: 520px) {
            .up3-quick-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        .up3-chip-btn {
            background: #FFFFFF;
            border: 1.5px solid #E2E8F0;
            border-radius: 7px;
            padding: 6.5px 2px;
            font-size: 11.5px;
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
            box-shadow: 0 3px 10px rgba(2, 62, 138, 0.22);
            font-weight: 800;
        }

        .act-btn-submit {
            width: 100%;
            padding: 10.5px 18px;
            border-radius: 10px;
            font-size: 13.5px;
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
            margin-top: 3px;
        }
        .act-btn-submit:hover {
            background: #002D66;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(2, 62, 138, 0.32);
        }

        .back-login-box {
            text-align: center;
            margin-top: 11px;
            font-size: 12px;
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
                <svg viewBox="0 0 220 130" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="avatarGrad" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#0284C7"/>
                            <stop offset="100%" stop-color="#023E8A"/>
                        </linearGradient>
                        <linearGradient id="btnGrad" x1="0" y1="0" x2="1" y2="0">
                            <stop offset="0%" stop-color="#FFC629"/>
                            <stop offset="100%" stop-color="#FF7800"/>
                        </linearGradient>
                        <linearGradient id="shieldGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#34D399"/>
                            <stop offset="100%" stop-color="#059669"/>
                        </linearGradient>
                        <linearGradient id="bodyGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#034FA8"/>
                            <stop offset="100%" stop-color="#023E8A"/>
                        </linearGradient>
                    </defs>

                    <!-- Soft Ambient Background Circles -->
                    <ellipse cx="110" cy="75" rx="85" ry="48" fill="#E0F2FE" opacity="0.6"/>
                    <circle cx="165" cy="46" r="30" fill="#BAE6FD" opacity="0.45"/>
                    <circle cx="50" cy="52" r="26" fill="#BAE6FD" opacity="0.4"/>

                    <!-- Sparkles & Accent Dots -->
                    <path d="M196 28 L198 22 L200 28 L206 30 L200 32 L198 38 L196 32 L190 30 Z" fill="#FFC629"/>
                    <path d="M22 36 L23.5 31 L25 36 L30 37.5 L25 39 L23.5 44 L22 39 L17 37.5 Z" fill="#FF7800"/>
                    <circle cx="28" cy="95" r="3" fill="#0081AB" opacity="0.4"/>
                    <circle cx="202" cy="85" r="3" fill="#10B981" opacity="0.5"/>
                    <circle cx="110" cy="18" r="2.5" fill="#FFC629"/>

                    <!-- Smartphone (Left side) -->
                    <g filter="drop-shadow(0 4px 10px rgba(2,62,138,0.12))">
                        <!-- Outer Phone Chassis -->
                        <rect x="34" y="14" width="70" height="106" rx="13" fill="#FFFFFF" stroke="#0F172A" stroke-width="2.2"/>
                        
                        <!-- Dynamic Island / Speaker Notch -->
                        <rect x="58" y="19" width="22" height="3.5" rx="1.75" fill="#1E293B"/>

                        <!-- Screen Area Inner Background -->
                        <rect x="37" y="25" width="64" height="91" rx="9" fill="#F8FAFC"/>

                        <!-- Profile Badge on Screen -->
                        <rect x="42" y="30" width="54" height="34" rx="7" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="0.8"/>
                        <!-- Avatar Circle -->
                        <circle cx="69" cy="44" r="9" fill="url(#avatarGrad)"/>
                        <circle cx="69" cy="42" r="3" fill="#FFFFFF"/>
                        <path d="M64 49 C64 46.8 66 45.8 69 45.8 C72 45.8 74 46.8 74 49" fill="#FFFFFF"/>
                        <!-- Tiny Verified Checkmark on Avatar -->
                        <circle cx="76" cy="49" r="3" fill="#10B981"/>
                        <polyline points="74.8 49 75.8 50 77.2 48" fill="none" stroke="#FFFFFF" stroke-width="0.9" stroke-linecap="round" stroke-linejoin="round"/>

                        <!-- Screen UI Lines -->
                        <rect x="49" y="56" width="40" height="3" rx="1.5" fill="#CBD5E1"/>

                        <!-- Passcode Dots / PIN Indicator -->
                        <rect x="44" y="68" width="50" height="12" rx="4" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="0.8"/>
                        <circle cx="53" cy="74" r="2" fill="#023E8A"/>
                        <circle cx="61" cy="74" r="2" fill="#023E8A"/>
                        <circle cx="69" cy="74" r="2" fill="#023E8A"/>
                        <circle cx="77" cy="74" r="2" fill="#023E8A"/>
                        <polyline points="83 72.5 84.5 74 87 71.5" fill="none" stroke="#10B981" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>

                        <!-- Button on Screen -->
                        <rect x="44" y="85" width="50" height="15" rx="5" fill="url(#btnGrad)"/>
                        <text x="69" y="95.5" font-family="'Inter', sans-serif" font-size="6.5" font-weight="800" fill="#FFFFFF" text-anchor="middle" letter-spacing="0.5">AKTIFKAN</text>
                        <circle cx="88" cy="92.5" r="1.5" fill="#FFFFFF" opacity="0.8"/>
                    </g>

                    <!-- Floating Verified Shield Badge -->
                    <g filter="drop-shadow(0 3px 8px rgba(16,185,129,0.3))">
                        <circle cx="108" cy="34" r="16" fill="#FFFFFF"/>
                        <path d="M108 21 L120 26 C120 35 115 43 108 46 C101 43 96 35 96 26 Z" fill="url(#shieldGrad)"/>
                        <polyline points="102.5 33.5 106.5 37.5 113.5 30" fill="none" stroke="#FFFFFF" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>
                    </g>

                    <!-- Modern Friendly Character (Right side) -->
                    <g>
                        <!-- Body / Corporate Polo -->
                        <path d="M130 126 C130 106 142 100 157 100 C172 100 184 106 184 126 Z" fill="url(#bodyGrad)"/>
                        
                        <!-- Polo Collar & Details -->
                        <path d="M151 100 L157 111 L163 100" fill="#FFFFFF"/>
                        <path d="M152 100 L157 109 L162 100" fill="#E2E8F0"/>
                        <line x1="157" y1="111" x2="157" y2="124" stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="round"/>
                        <circle cx="157" cy="115" r="1" fill="#023E8A"/>
                        <circle cx="157" cy="120" r="1" fill="#023E8A"/>

                        <!-- Lanyard & ID Card -->
                        <path d="M153 104 L157 117 L161 104" stroke="#0081AB" stroke-width="1.8" fill="none"/>
                        <rect x="153" y="117" width="8" height="9" rx="1.5" fill="#FFFFFF" stroke="#0081AB" stroke-width="1"/>
                        <rect x="154.5" y="119" width="5" height="1.5" rx="0.75" fill="#023E8A"/>

                        <!-- Neck -->
                        <rect x="153" y="87" width="8" height="14" rx="3" fill="#FED7AA"/>
                        <!-- Neck shadow under chin -->
                        <path d="M153 91 C155 93 159 93 161 91 L161 87 L153 87 Z" fill="#FDBA74" opacity="0.6"/>

                        <!-- Head / Face -->
                        <circle cx="157" cy="75" r="15" fill="#FED7AA"/>

                        <!-- Ears -->
                        <circle cx="142.5" cy="75" r="3" fill="#FED7AA"/>
                        <circle cx="171.5" cy="75" r="3" fill="#FED7AA"/>

                        <!-- Hair (Modern side-sweep fringe with volume) -->
                        <path d="M142 73 C142 59 149 53 158 53 C169 53 174 61 174 72 C170 70 164 69 157 69 C149 69 144 71 142 73 Z" fill="#0F172A"/>
                        <!-- Sideburns & fringe lock -->
                        <path d="M142.5 73 L142.5 78 L145 74 Z" fill="#0F172A"/>
                        <path d="M171.5 73 L171.5 78 L169 74 Z" fill="#0F172A"/>
                        <!-- Hair highlight shine -->
                        <path d="M150 56 C154 55 160 55 165 57" stroke="#334155" stroke-width="1.5" stroke-linecap="round" fill="none"/>

                        <!-- Expressive Friendly Face -->
                        <!-- Eyebrows -->
                        <path d="M149 69 C151 68 153 68.5 154 69" stroke="#1E293B" stroke-width="1" stroke-linecap="round" fill="none"/>
                        <path d="M160 69 C161 68.5 163 68 165 69" stroke="#1E293B" stroke-width="1" stroke-linecap="round" fill="none"/>
                        <!-- Eyes -->
                        <circle cx="151.5" cy="73.5" r="1.6" fill="#0F172A"/>
                        <circle cx="162.5" cy="73.5" r="1.6" fill="#0F172A"/>
                        <circle cx="152" cy="73" r="0.6" fill="#FFFFFF"/>
                        <circle cx="163" cy="73" r="0.6" fill="#FFFFFF"/>
                        <!-- Cheeks blush -->
                        <circle cx="147.5" cy="77" r="2.2" fill="#F87171" opacity="0.4"/>
                        <circle cx="166.5" cy="77" r="2.2" fill="#F87171" opacity="0.4"/>
                        <!-- Gentle Smile -->
                        <path d="M154.5 78 Q157 81 159.5 78" stroke="#0F172A" stroke-width="1.4" stroke-linecap="round" fill="none"/>

                        <!-- Arm & Hand Pointing/Tapping directly at phone button -->
                        <path d="M135 110 C122 106 108 98 96 93" stroke="url(#bodyGrad)" stroke-width="8" stroke-linecap="round"/>
                        <!-- Sleeve Cuff -->
                        <ellipse cx="98" cy="94" rx="2.5" ry="4.5" fill="#0081AB" transform="rotate(-25 98 94)"/>
                        <!-- Hand / Fingers tapping button -->
                        <circle cx="92" cy="92" r="3.8" fill="#FED7AA"/>
                        <!-- Extended Index Finger touching screen button -->
                        <path d="M92 92 L85 91" stroke="#FED7AA" stroke-width="3" stroke-linecap="round"/>
                        <circle cx="84" cy="91" r="1.5" fill="#FED7AA"/>
                        <!-- Tap sparkle -->
                        <path d="M79 87 L80 84 L81 87 L84 88 L81 89 L80 92 L79 89 L76 88 Z" fill="#FFC629"/>
                    </g>
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
