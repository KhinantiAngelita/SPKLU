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
            width: 320px;
            height: 168px;
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
                <svg viewBox="0 0 420 205" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <!-- Screen Card Gradient -->
                        <linearGradient id="phoneCardGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#023E8A"/>
                            <stop offset="100%" stop-color="#0081AB"/>
                        </linearGradient>

                        <!-- Matchmaker Bright Orange Button Gradient -->
                        <linearGradient id="btnGrad" x1="0" y1="0" x2="1" y2="0">
                            <stop offset="0%" stop-color="#FFC629"/>
                            <stop offset="100%" stop-color="#FF7800"/>
                        </linearGradient>

                        <!-- Handshake Teal Gradient -->
                        <linearGradient id="tealGrad" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#0081AB"/>
                            <stop offset="100%" stop-color="#023E8A"/>
                        </linearGradient>

                        <!-- Crisp Drop Shadows with Light Blue / Navy Hue -->
                        <filter id="phoneShadow" x="-15%" y="-15%" width="130%" height="130%">
                            <feDropShadow dx="0" dy="6" stdDeviation="10" flood-color="#023E8A" flood-opacity="0.08"/>
                        </filter>

                        <filter id="floatCardShadow" x="-15%" y="-15%" width="130%" height="130%">
                            <feDropShadow dx="0" dy="8" stdDeviation="12" flood-color="#023E8A" flood-opacity="0.11"/>
                        </filter>

                        <filter id="badgeShadow" x="-20%" y="-20%" width="140%" height="140%">
                            <feDropShadow dx="0" dy="4" stdDeviation="6" flood-color="#023E8A" flood-opacity="0.08"/>
                        </filter>

                        <filter id="btnShadow" x="-10%" y="-15%" width="120%" height="140%">
                            <feDropShadow dx="0" dy="3" stdDeviation="3" flood-color="#FF7800" flood-opacity="0.3"/>
                        </filter>
                    </defs>

                    <!-- Subtle Light-Blue Ground Blueprint Grid -->
                    <line x1="30" y1="168" x2="390" y2="168" stroke="#E0F2FE" stroke-width="1.2"/>
                    <line x1="30" y1="190" x2="390" y2="190" stroke="#E0F2FE" stroke-width="1.2"/>
                    <line x1="68" y1="150" x2="68" y2="198" stroke="#E0F2FE" stroke-width="1.2"/>
                    <line x1="112" y1="150" x2="112" y2="198" stroke="#E0F2FE" stroke-width="1.2"/>
                    <line x1="308" y1="150" x2="308" y2="198" stroke="#E0F2FE" stroke-width="1.2"/>
                    <line x1="352" y1="150" x2="352" y2="198" stroke="#E0F2FE" stroke-width="1.2"/>

                    <!-- Dashed Circuit Connecting Lines (Light Blue) -->
                    <path d="M68 56 L112 56 L112 76 L148 76" stroke="#7DD3FC" stroke-width="1.5" stroke-dasharray="4 4" fill="none"/>
                    <path d="M68 150 L112 150 L112 134 L148 134" stroke="#7DD3FC" stroke-width="1.5" stroke-dasharray="4 4" fill="none"/>
                    <path d="M352 56 L308 56 L308 76 L272 76" stroke="#7DD3FC" stroke-width="1.5" stroke-dasharray="4 4" fill="none"/>
                    <path d="M352 150 L308 150 L308 134 L272 134" stroke="#7DD3FC" stroke-width="1.5" stroke-dasharray="4 4" fill="none"/>

                    <!-- Circuit Glowing Nodes -->
                    <circle cx="112" cy="56" r="5" fill="#BAE6FD" opacity="0.8"/>
                    <circle cx="112" cy="56" r="2.8" fill="#0284C7"/>
                    <circle cx="112" cy="150" r="5" fill="#BAE6FD" opacity="0.8"/>
                    <circle cx="112" cy="150" r="2.8" fill="#0284C7"/>
                    <circle cx="308" cy="56" r="5" fill="#BAE6FD" opacity="0.8"/>
                    <circle cx="308" cy="56" r="2.8" fill="#0284C7"/>
                    <circle cx="308" cy="150" r="5" fill="#BAE6FD" opacity="0.8"/>
                    <circle cx="308" cy="150" r="2.8" fill="#0284C7"/>

                    <!-- Central Smartphone -->
                    <g filter="url(#phoneShadow)">
                        <!-- Outer Chassis: Pure White with Crisp Light-Blue Border -->
                        <rect x="148" y="12" width="124" height="182" rx="20" fill="#FFFFFF" stroke="#BAE6FD" stroke-width="1.8"/>

                        <!-- Speaker / Earpiece -->
                        <rect x="194" y="18" width="32" height="3" rx="1.5" fill="#CBD5E1"/>

                        <!-- Phone Screen with Hint of Light Blue -->
                        <rect x="154" y="25" width="112" height="163" rx="14" fill="#F0F9FF" stroke="#E0F2FE" stroke-width="1"/>

                        <!-- Screen Header: Mini Profile & Verification -->
                        <circle cx="170" cy="38" r="7.5" fill="#0284C7"/>
                        <circle cx="170" cy="36.5" r="2.6" fill="#FFFFFF"/>
                        <path d="M165.5 42.5 C165.5 40.5 167.5 39.5 170 39.5 C172.5 39.5 174.5 40.5 174.5 42.5" fill="#FFFFFF"/>
                        <rect x="183" y="34.5" width="46" height="4" rx="2" fill="#BAE6FD"/>
                        <rect x="183" y="41" width="30" height="3" rx="1.5" fill="#CBD5E1"/>

                        <!-- Deep Blue / Cyan Feature Card on Screen -->
                        <rect x="160" y="52" width="100" height="128" rx="10" fill="url(#phoneCardGrad)"/>

                        <!-- SPKLU Electric Bolt Badge on Screen -->
                        <circle cx="210" cy="88" r="20" fill="#0081AB" opacity="0.45"/>
                        <polygon points="212 76 202 90 210 90 208 104 219 90 211 90" fill="#FFC629"/>
                        <text x="210" y="118" font-family="'Inter', sans-serif" font-size="7.5" font-weight="800" fill="#FFFFFF" text-anchor="middle" letter-spacing="0.6">SPKLU PLN</text>
                        <text x="210" y="128" font-family="'Inter', sans-serif" font-size="5.5" font-weight="600" fill="#BAE6FD" text-anchor="middle" letter-spacing="0.4">AKTIVASI SISTEM</text>
                    </g>

                    <!-- Foreground Floating Activation Card (Overlapping Phone) -->
                    <g filter="url(#floatCardShadow)">
                        <!-- Card White Container with Crisp Light-Blue Border -->
                        <rect x="172" y="72" width="156" height="106" rx="12" fill="#FFFFFF" stroke="#BAE6FD" stroke-width="1.8"/>

                        <!-- Header Row of Floating Card: Avatar & Status -->
                        <circle cx="191" cy="89" r="8.5" fill="#F0FDF4" stroke="#86EFAC" stroke-width="1.2"/>
                        <polyline points="187.5 89 190 91.5 194.5 86.5" fill="none" stroke="#10B981" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        
                        <text x="206" y="87" font-family="'Inter', sans-serif" font-size="7.5" font-weight="700" fill="#1E293B">Aktivasi Pengguna</text>
                        <text x="206" y="96" font-family="'Inter', sans-serif" font-size="6" font-weight="500" fill="#64748B">Verifikasi Unit UP3</text>

                        <!-- Status Pill -->
                        <rect x="278" y="82" width="40" height="13" rx="6.5" fill="#ECFDF5" stroke="#A7F3D0" stroke-width="0.8"/>
                        <circle cx="285" cy="88.5" r="2.2" fill="#10B981"/>
                        <text x="300" y="91.5" font-family="'Inter', sans-serif" font-size="5.8" font-weight="800" fill="#059669" text-anchor="middle">AKTIF</text>

                        <!-- Soft Blue Divider Line -->
                        <line x1="184" y1="104" x2="316" y2="104" stroke="#F1F5F9" stroke-width="1"/>

                        <!-- Activation Bar with Matchmaker Gradient Button -->
                        <rect x="184" y="112" width="132" height="24" rx="7" fill="#F8FAFC" stroke="#BAE6FD" stroke-width="1"/>
                        <circle cx="196" cy="124" r="5.5" fill="#E0F2FE" stroke="#0081AB" stroke-width="1"/>
                        <circle cx="196" cy="123" r="1.8" fill="#0081AB"/>
                        <rect x="210" y="115" width="102" height="18" rx="9" fill="url(#btnGrad)" filter="url(#btnShadow)"/>
                        <text x="261" y="127" font-family="'Inter', sans-serif" font-size="7" font-weight="800" fill="#FFFFFF" text-anchor="middle" letter-spacing="0.4">AKTIFKAN SEKARANG</text>

                        <!-- Toggle Switch Row -->
                        <rect x="184" y="146" width="22" height="12" rx="6" fill="#10B981"/>
                        <circle cx="199" cy="152" r="4.5" fill="#FFFFFF"/>
                        <text x="213" y="153.5" font-family="'Inter', sans-serif" font-size="6.5" font-weight="600" fill="#475569">Sistem Terintegrasi</text>

                        <!-- Checkmark Pill -->
                        <circle cx="310" cy="152" r="6" fill="#E0F2FE"/>
                        <polyline points="307.5 152 309.5 154 312.5 150" fill="none" stroke="#0284C7" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                    </g>

                    <!-- Top-Left Badge: User Account Group Badge -->
                    <g filter="url(#badgeShadow)">
                        <circle cx="68" cy="56" r="23" fill="#FFFFFF" stroke="#BAE6FD" stroke-width="1.8"/>
                        <circle cx="68" cy="56" r="19" fill="#F0FDF4"/>
                        <!-- User Group Icons in Emerald Green -->
                        <circle cx="68" cy="50" r="4.5" fill="#10B981"/>
                        <path d="M60 62 C60 58 63.5 56.5 68 56.5 C72.5 56.5 76 58 76 62" fill="#10B981"/>
                        <circle cx="59" cy="52" r="3.5" fill="#34D399"/>
                        <path d="M53 61 C53 58.5 55.5 57.5 59 57.5" fill="#34D399"/>
                        <circle cx="77" cy="52" r="3.5" fill="#34D399"/>
                        <path d="M83 61 C83 58.5 80.5 57.5 77 57.5" fill="#34D399"/>
                    </g>

                    <!-- Bottom-Left Badge: Setting Gear with Verified Checkmark -->
                    <g filter="url(#badgeShadow)">
                        <circle cx="68" cy="150" r="23" fill="#FFFFFF" stroke="#BAE6FD" stroke-width="1.8"/>
                        <circle cx="68" cy="150" r="19" fill="#F0F9FF"/>
                        <!-- Gear Shape in Blue -->
                        <path d="M68 138 L70 138 L70.5 141 C71.5 141.3 72.5 141.8 73.4 142.5 L76 141 L77.5 142.5 L76 145.1 C76.7 146 77.2 147 77.5 148 L80.5 148.5 L80.5 150.5 L77.5 151 C77.2 152 76.7 153 76 153.9 L77.5 156.5 L76 158 L73.4 156.5 C72.5 157.2 71.5 157.7 70.5 158 L70 161 L68 161 L67.5 158 C66.5 157.7 65.5 157.2 64.6 156.5 L62 158 L60.5 156.5 L62 153.9 C61.3 153 60.8 152 60.5 151 L57.5 150.5 L57.5 148.5 L60.5 148 C60.8 147 61.3 146 62 145.1 L60.5 142.5 L62 141 L64.6 142.5 C65.5 141.8 66.5 141.3 67.5 141 Z" fill="#0284C7"/>
                        <!-- Center Verified Checkmark -->
                        <circle cx="68" cy="149.5" r="8" fill="#023E8A"/>
                        <polyline points="63.5 149.5 66.5 152.5 72.5 146.5" fill="none" stroke="#FFFFFF" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </g>

                    <!-- Top-Right Badge: Security Question & OTP Message Bubbles -->
                    <g filter="url(#badgeShadow)">
                        <!-- Outer Base -->
                        <circle cx="352" cy="56" r="23" fill="#FFFFFF" stroke="#BAE6FD" stroke-width="1.8"/>
                        <!-- Cyan Question Bubble -->
                        <rect x="338" y="43" width="22" height="16" rx="4.5" fill="#38BDF8"/>
                        <polygon points="344 59 344 62 348 59" fill="#38BDF8"/>
                        <text x="349" y="54.5" font-family="'Inter', sans-serif" font-size="9.5" font-weight="900" fill="#FFFFFF" text-anchor="middle">?</text>

                        <!-- Dark Blue OTP Dots Bubble -->
                        <rect x="351" y="52" width="24" height="17" rx="4.5" fill="#023E8A"/>
                        <polygon points="368 69 368 72 364 69" fill="#023E8A"/>
                        <circle cx="358" cy="60.5" r="1.5" fill="#FFFFFF"/>
                        <circle cx="363" cy="60.5" r="1.5" fill="#FFFFFF"/>
                        <circle cx="368" cy="60.5" r="1.5" fill="#FFFFFF"/>
                    </g>

                    <!-- Bottom-Right Badge: SPKLU Partnership / Access Handshake -->
                    <g filter="url(#badgeShadow)">
                        <circle cx="352" cy="150" r="23" fill="url(#tealGrad)" stroke="#BAE6FD" stroke-width="1.8"/>
                        <circle cx="352" cy="150" r="20" fill="none" stroke="rgba(255,255,255,0.25)" stroke-width="1.2"/>
                        <!-- White Handshake Vector -->
                        <path d="M341 149 L346 144 L352 150 L358 144 L363 149 L357 155 L352 152 L347 155 Z" fill="#FFFFFF"/>
                        <!-- Light Blue Cuffs -->
                        <path d="M340 147 L343 144 L346 148 L343 151 Z" fill="#BAE6FD"/>
                        <path d="M364 147 L361 144 L358 148 L361 151 Z" fill="#BAE6FD"/>
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
