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
            padding: 24px 20px;
            color: #0F172A;
        }

        .act-card {
            background: #FFFFFF;
            border-radius: 22px;
            width: 550px;
            max-width: 100%;
            box-shadow: 0 16px 40px rgba(2, 62, 138, 0.09), 0 3px 10px rgba(2, 62, 138, 0.04);
            border: 1px solid rgba(2, 62, 138, 0.08);
            overflow: hidden;
        }

        .act-header {
            background: #FFFFFF;
            padding: 26px 36px 16px;
            text-align: center;
            border-bottom: 1px solid #F1F5F9;
        }

        .act-illustration {
            width: 290px;
            height: 158px;
            margin: 0 auto 10px;
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
            margin-bottom: 5px;
            letter-spacing: -.02em;
        }
        .act-header p {
            font-size: 13px;
            color: #64748B;
            line-height: 1.5;
        }

        .act-body {
            padding: 22px 36px 28px;
        }

        .field-group {
            margin-bottom: 18px;
        }
        .field-label {
            display: block;
            font-size: 11.5px;
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
            width: 19px;
            height: 19px;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            color: #94A3B8;
            z-index: 5;
            transition: color .15s ease;
        }
        .field-input-icon svg {
            width: 17px;
            height: 17px;
            stroke-width: 2;
        }
        .field-input,
        input.field-input,
        .field-input-wrap input {
            width: 100% !important;
            padding-left: 44px !important;
            padding-right: 16px !important;
            padding-top: 12px !important;
            padding-bottom: 12px !important;
            border-radius: 11px !important;
            border: 1.6px solid #CBD5E1 !important;
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
            border-radius: 10px;
            padding: 9px 14px;
            margin-bottom: 11px;
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
            font-size: 12.5px;
            font-weight: 700;
            color: #1E293B;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .up3-selected-banner.empty .up3-selected-name {
            font-size: 12px;
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
            margin-bottom: 20px;
        }
        @media (max-width: 520px) {
            .up3-quick-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        .up3-chip-btn {
            background: #FFFFFF;
            border: 1.5px solid #E2E8F0;
            border-radius: 8px;
            padding: 8.5px 3px;
            font-size: 12px;
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
            padding: 12.5px 20px;
            border-radius: 11px;
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
            margin-top: 4px;
        }
        .act-btn-submit:hover {
            background: #002D66;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(2, 62, 138, 0.32);
        }

        .back-login-box {
            text-align: center;
            margin-top: 18px;
            font-size: 12.5px;
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
                <svg viewBox="0 0 420 230" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="padlockGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#FFD026"/>
                            <stop offset="100%" stop-color="#EAA605"/>
                        </linearGradient>
                        <linearGradient id="shieldGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#34D399"/>
                            <stop offset="100%" stop-color="#059669"/>
                        </linearGradient>
                        <linearGradient id="btnGrad" x1="0" y1="0" x2="1" y2="0">
                            <stop offset="0%" stop-color="#FFC629"/>
                            <stop offset="100%" stop-color="#FF7800"/>
                        </linearGradient>
                        <filter id="winShadow" x="-10%" y="-10%" width="120%" height="125%">
                            <feDropShadow dx="0" dy="6" stdDeviation="8" flood-color="#1E293B" flood-opacity="0.12"/>
                        </filter>
                    </defs>

                    <!-- Base Ground Shadow -->
                    <ellipse cx="210" cy="216" rx="175" ry="6" fill="#D2E7F9" opacity="0.85"/>

                    <!-- Soft Pastel Blue Backdrop Circle -->
                    <ellipse cx="205" cy="115" rx="115" ry="90" fill="#E8F4FD"/>

                    <!-- Left Foliage (Layer 1 - Darker Olive Green) -->
                    <path d="M110 212 C100 170 95 120 120 65 C135 95 130 145 124 212 Z" fill="#689F38"/>
                    <path d="M92 212 C80 180 82 145 98 105 C112 130 108 170 102 212 Z" fill="#558B2F"/>

                    <!-- Left Foliage (Layer 2 - Vibrant Leafy Fronds) -->
                    <path d="M125 212 C115 155 118 100 142 50 C158 85 152 145 140 212 Z" fill="#8BC34A"/>
                    <path d="M102 212 C90 170 92 135 116 85 C130 115 124 165 115 212 Z" fill="#9CCC65"/>
                    <path d="M85 212 C72 185 78 155 96 125 C108 150 102 185 94 212 Z" fill="#8BC34A"/>
                    <!-- Leaf Veins -->
                    <path d="M128 160 Q135 120 140 65" stroke="#7CB342" stroke-width="1.8" stroke-linecap="round" fill="none"/>
                    <path d="M133 130 Q142 125 147 122" stroke="#7CB342" stroke-width="1.4" stroke-linecap="round" fill="none"/>
                    <path d="M131 105 Q140 98 144 94" stroke="#7CB342" stroke-width="1.4" stroke-linecap="round" fill="none"/>

                    <!-- Bottom Left Soft Blue Cloud -->
                    <path d="M72 208 C70 198 78 190 88 190 C90 190 92 191 94 192 C97 185 105 180 113 180 C123 180 131 187 132 197 C136 198 139 203 138 208 Z" fill="#B8DAF8"/>

                    <!-- Giant Smartphone (Center) -->
                    <g>
                        <!-- Phone Chassis -->
                        <rect x="156" y="28" width="112" height="182" rx="22" fill="#2D2E3E"/>
                        <rect x="160" y="32" width="104" height="174" rx="19" fill="#36384C"/>
                        
                        <!-- Top Speaker Notch -->
                        <rect x="198" y="36" width="28" height="4" rx="2" fill="#20212D"/>
                        <circle cx="230" cy="38" r="1.5" fill="#20212D"/>

                        <!-- Screen Area -->
                        <rect x="164" y="44" width="96" height="152" rx="14" fill="#FFFFFF"/>
                        
                        <!-- Bottom Chin / Home Pill -->
                        <rect x="200" y="202" width="24" height="3" rx="1.5" fill="#4A4C63"/>
                    </g>

                    <!-- Floating Modal / Card Window (Overlapping Phone) -->
                    <g filter="url(#winShadow)">
                        <!-- Card Body -->
                        <rect x="135" y="78" width="154" height="98" rx="10" fill="#FFFFFF"/>
                        
                        <!-- Card Header Bar (Soft Sky Blue) -->
                        <path d="M135 88 A10 10 0 0 1 145 78 L279 78 A10 10 0 0 1 289 88 L289 100 L135 100 Z" fill="#D0E5F9"/>

                        <!-- Window Control Buttons (Red, Yellow, Green) -->
                        <circle cx="261" cy="89" r="3.2" fill="#EF4444"/>
                        <circle cx="270" cy="89" r="3.2" fill="#F59E0B"/>
                        <circle cx="279" cy="89" r="3.2" fill="#10B981"/>

                        <!-- Card Interior - Account / Activation Elements -->
                        <!-- Subtitle Badge: SPKLU ID & Aktivasi -->
                        <rect x="146" y="107" width="56" height="13" rx="6.5" fill="#F0F9FF" stroke="#BAE6FD" stroke-width="0.8"/>
                        <circle cx="153" cy="113.5" r="3.5" fill="#0284C7"/>
                        <text x="161" y="116" font-family="'Inter', sans-serif" font-size="6" font-weight="700" fill="#023E8A">SPKLU ID</text>

                        <rect x="240" y="107" width="38" height="13" rx="6.5" fill="#ECFDF5" stroke="#A7F3D0" stroke-width="0.8"/>
                        <text x="259" y="116" font-family="'Inter', sans-serif" font-size="6.2" font-weight="800" fill="#059669" text-anchor="middle">AKTIF ✓</text>

                        <!-- Password / OTP Input Bar (Soft Blue Box with Blue Asterisks) -->
                        <rect x="146" y="126" width="132" height="28" rx="7" fill="#B8DAF8"/>
                        <!-- 8 Bold Password Asterisks matching reference image -->
                        <text x="212" y="146" font-family="monospace, sans-serif" font-size="21" font-weight="900" fill="#4B96E6" text-anchor="middle" letter-spacing="4">✱✱✱✱✱✱✱✱</text>

                        <!-- Bottom Action Row: Progress / Ready indicator -->
                        <rect x="146" y="160" width="132" height="10" rx="5" fill="#F1F5F9"/>
                        <rect x="146" y="160" width="98" height="10" rx="5" fill="url(#btnGrad)"/>
                        <text x="195" y="167.5" font-family="'Inter', sans-serif" font-size="5.5" font-weight="800" fill="#FFFFFF" text-anchor="middle" letter-spacing="0.5">VERIFIKASI AKTIVASI</text>
                    </g>

                    <!-- Golden Security Padlock (Mounted on Top Center of Window) -->
                    <g>
                        <!-- Lock Shackle -->
                        <path d="M201 78 L201 60 A11 11 0 0 1 223 60 L223 78" stroke="#EAA605" stroke-width="6.5" stroke-linecap="round" fill="none"/>
                        <path d="M201 78 L201 60 A11 11 0 0 1 223 60 L223 78" stroke="#FFD026" stroke-width="4.5" stroke-linecap="round" fill="none"/>

                        <!-- Lock Body (Yellow Gold with Rounded Corners) -->
                        <rect x="190" y="72" width="44" height="38" rx="8" fill="url(#padlockGrad)"/>
                        <!-- 3D Shadow on Lock Body -->
                        <path d="M190 102 A8 8 0 0 0 198 110 L226 110 A8 8 0 0 0 234 102 L234 98 L190 98 Z" fill="#D69404" opacity="0.6"/>

                        <!-- Keyhole -->
                        <circle cx="212" cy="87" r="4.2" fill="#B57B00"/>
                        <path d="M209.5 87 L214.5 87 L216 98 L208 98 Z" fill="#B57B00"/>

                        <!-- Verification Checkmark Shield (Badge on lock indicating "Aktivasi") -->
                        <circle cx="228" cy="98" r="8.5" fill="#FFFFFF"/>
                        <circle cx="228" cy="98" r="7.2" fill="url(#shieldGrad)"/>
                        <polyline points="224.5 98 227 100.5 231.5 95.5" fill="none" stroke="#FFFFFF" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </g>

                    <!-- Right Side: Potted Plant (Beside Character) -->
                    <g>
                        <!-- Yellow Flower Pot -->
                        <path d="M348 180 L350 212 A2 2 0 0 0 352 214 L368 214 A2 2 0 0 0 370 212 L372 180 Z" fill="#F5BA13"/>
                        <rect x="346" y="177" width="28" height="5" rx="2" fill="#E0A307"/>
                        <ellipse cx="360" cy="179" rx="12" ry="2" fill="#8D5B04"/>

                        <!-- Foliage / Leaves Sprouting from Pot -->
                        <!-- Tall Stem & Leaves -->
                        <path d="M360 178 C360 150 365 125 372 102 C378 122 376 150 368 178 Z" fill="#7CB342"/>
                        <path d="M358 178 C354 155 350 135 344 118 C354 130 358 152 360 178 Z" fill="#689F38"/>
                        <path d="M362 178 C366 160 375 145 385 132 C382 148 374 165 364 178 Z" fill="#8BC34A"/>
                        <!-- Stems with leaf pairs -->
                        <path d="M370 140 C378 136 384 140 385 146 C379 148 373 145 370 140 Z" fill="#8BC34A"/>
                        <path d="M366 120 C374 116 380 120 381 126 C375 128 369 125 366 120 Z" fill="#9CCC65"/>
                        <path d="M352 148 C344 145 340 150 340 156 C346 157 351 154 352 148 Z" fill="#689F38"/>
                    </g>

                    <!-- Right Side: Friendly Character Presenting the Modal -->
                    <g>
                        <!-- Shoes (Bright Yellow with subtle shadow) -->
                        <ellipse cx="312" cy="214" rx="7.5" ry="3.8" fill="#F5BA13"/>
                        <ellipse cx="310" cy="213" rx="4.5" ry="2" fill="#FFD026"/>
                        <ellipse cx="334" cy="214" rx="7.5" ry="3.8" fill="#F5BA13"/>
                        <ellipse cx="332" cy="213" rx="4.5" ry="2" fill="#FFD026"/>

                        <!-- Trousers (Sky Blue with natural folds) -->
                        <!-- Left Leg -->
                        <path d="M309 146 L308 211 L316 211 L319 146 Z" fill="#4BA3F5"/>
                        <!-- Right Leg -->
                        <path d="M325 146 L329 211 L338 211 L334 146 Z" fill="#3B8CE0"/>
                        <!-- Crotch & Waist connector -->
                        <path d="M312 146 L331 146 L326 165 L318 165 Z" fill="#3B8CE0"/>
                        <!-- Belt / Waist detail -->
                        <rect x="310" y="142" width="23" height="4" rx="1.5" fill="#2563EB"/>

                        <!-- Shirt (Vibrant Red-Orange matching reference image) -->
                        <path d="M307 142 L306 112 C306 102 314 98 322 98 C331 98 338 102 338 112 L337 142 Z" fill="#FA5C38"/>
                        <!-- White Vertical Button Placket -->
                        <rect x="320" y="104" width="3" height="38" rx="1" fill="#FFFFFF"/>
                        <circle cx="321.5" cy="112" r="0.9" fill="#FA5C38"/>
                        <circle cx="321.5" cy="120" r="0.9" fill="#FA5C38"/>
                        <circle cx="321.5" cy="128" r="0.9" fill="#FA5C38"/>
                        <circle cx="321.5" cy="136" r="0.9" fill="#FA5C38"/>

                        <!-- White Collar -->
                        <path d="M316 98 L321.5 106 L327 98" fill="#FFFFFF"/>
                        
                        <!-- Right Arm (Resting on side) -->
                        <path d="M336 105 C343 112 344 125 342 136" stroke="#FA5C38" stroke-width="7" stroke-linecap="round" fill="none"/>
                        <circle cx="341" cy="138" r="3.2" fill="#FED7AA"/>

                        <!-- Left Arm (Extended forward, presenting the card) -->
                        <!-- Sleeve -->
                        <path d="M309 105 C300 110 294 116 288 120" stroke="#FA5C38" stroke-width="7" stroke-linecap="round" fill="none"/>
                        <!-- Forearm & Hand pointing/presenting toward card -->
                        <path d="M288 120 L277 122" stroke="#FED7AA" stroke-width="5" stroke-linecap="round"/>
                        <!-- Open Hand fingers gesturing at the card -->
                        <path d="M277 122 L271 121" stroke="#FED7AA" stroke-width="2.5" stroke-linecap="round"/>
                        <path d="M277 124 L272 124" stroke="#FED7AA" stroke-width="2" stroke-linecap="round"/>
                        <path d="M278 120 L274 118" stroke="#FED7AA" stroke-width="2" stroke-linecap="round"/>

                        <!-- Neck -->
                        <rect x="319" y="88" width="6.5" height="12" rx="2.5" fill="#FED7AA"/>

                        <!-- Head & Face -->
                        <circle cx="322" cy="78" r="12" fill="#FED7AA"/>
                        <!-- Cheerful Profile Face looking left towards window -->
                        <circle cx="317.5" cy="76" r="1.4" fill="#1E293B"/>
                        <!-- Happy smiling mouth -->
                        <path d="M316 82 Q318.5 85 321 82" stroke="#1E293B" stroke-width="1.2" stroke-linecap="round" fill="none"/>
                        <!-- Rosy Cheek -->
                        <circle cx="316" cy="79" r="1.8" fill="#FB7185" opacity="0.45"/>
                        <!-- Ear -->
                        <circle cx="328" cy="78" r="2.6" fill="#FED7AA"/>

                        <!-- Hair (Dark Brown / Black stylish cut matching reference) -->
                        <path d="M313 74 C313 64 319 59 328 59 C336 59 339 67 338 75 C334 73 328 72 322 72 C317 72 314 73 313 74 Z" fill="#292524"/>
                        <path d="M313 74 L313 78 L316 75 Z" fill="#292524"/>
                        <path d="M336 74 L336 79 L333 75 Z" fill="#292524"/>
                        <path d="M322 62 Q327 60 332 64" stroke="#44403C" stroke-width="1.4" stroke-linecap="round" fill="none"/>
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
