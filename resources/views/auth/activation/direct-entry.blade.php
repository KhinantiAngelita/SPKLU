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
                <svg viewBox="0 0 420 220" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <!-- Tech Blue Gradient Background Canvas -->
                        <linearGradient id="techBgGrad" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#2563EB"/>
                            <stop offset="45%" stop-color="#1D4ED8"/>
                            <stop offset="100%" stop-color="#023E8A"/>
                        </linearGradient>

                        <!-- Screen Card Gradient -->
                        <linearGradient id="phoneCardGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#1E40AF"/>
                            <stop offset="100%" stop-color="#172554"/>
                        </linearGradient>

                        <!-- Button Gradient -->
                        <linearGradient id="btnGrad" x1="0" y1="0" x2="1" y2="0">
                            <stop offset="0%" stop-color="#FFC629"/>
                            <stop offset="100%" stop-color="#FF7800"/>
                        </linearGradient>

                        <!-- Handshake Teal Gradient -->
                        <linearGradient id="tealGrad" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#0081AB"/>
                            <stop offset="100%" stop-color="#023E8A"/>
                        </linearGradient>

                        <!-- Badge Shadow -->
                        <filter id="badgeShadow" x="-20%" y="-20%" width="140%" height="140%">
                            <feDropShadow dx="0" dy="4" stdDeviation="5" flood-color="#021D4A" flood-opacity="0.30"/>
                        </filter>

                        <!-- Floating Card Shadow -->
                        <filter id="floatCardShadow" x="-15%" y="-15%" width="130%" height="130%">
                            <feDropShadow dx="0" dy="8" stdDeviation="10" flood-color="#00173D" flood-opacity="0.35"/>
                        </filter>
                    </defs>

                    <!-- Rounded Tech Blueprint Canvas -->
                    <rect x="6" y="6" width="408" height="208" rx="16" fill="url(#techBgGrad)"/>

                    <!-- Subtle Blueprint Grid Lines (Bottom Half) -->
                    <line x1="6" y1="165" x2="414" y2="165" stroke="rgba(255,255,255,0.12)" stroke-width="1"/>
                    <line x1="6" y1="190" x2="414" y2="190" stroke="rgba(255,255,255,0.12)" stroke-width="1"/>
                    <line x1="60" y1="145" x2="60" y2="214" stroke="rgba(255,255,255,0.08)" stroke-width="1"/>
                    <line x1="120" y1="145" x2="120" y2="214" stroke="rgba(255,255,255,0.08)" stroke-width="1"/>
                    <line x1="300" y1="145" x2="300" y2="214" stroke="rgba(255,255,255,0.08)" stroke-width="1"/>
                    <line x1="360" y1="145" x2="360" y2="214" stroke="rgba(255,255,255,0.08)" stroke-width="1"/>

                    <!-- Circuit Dashed Connecting Lines -->
                    <path d="M72 58 L115 58 L115 80 L145 80" stroke="rgba(255,255,255,0.45)" stroke-width="1.5" stroke-dasharray="4 4" fill="none"/>
                    <path d="M72 165 L115 165 L115 145 L145 145" stroke="rgba(255,255,255,0.45)" stroke-width="1.5" stroke-dasharray="4 4" fill="none"/>
                    <path d="M350 58 L305 58 L305 80 L275 80" stroke="rgba(255,255,255,0.45)" stroke-width="1.5" stroke-dasharray="4 4" fill="none"/>
                    <path d="M350 165 L305 165 L305 145 L275 145" stroke="rgba(255,255,255,0.45)" stroke-width="1.5" stroke-dasharray="4 4" fill="none"/>

                    <!-- Glowing Circuit Connection Nodes -->
                    <circle cx="115" cy="58" r="5" fill="#FFFFFF" opacity="0.3"/>
                    <circle cx="115" cy="58" r="3" fill="#FFFFFF"/>
                    <circle cx="115" cy="165" r="5" fill="#FFFFFF" opacity="0.3"/>
                    <circle cx="115" cy="165" r="3" fill="#FFFFFF"/>
                    <circle cx="305" cy="58" r="5" fill="#FFFFFF" opacity="0.3"/>
                    <circle cx="305" cy="58" r="3" fill="#FFFFFF"/>
                    <circle cx="305" cy="165" r="5" fill="#FFFFFF" opacity="0.3"/>
                    <circle cx="305" cy="165" r="3" fill="#FFFFFF"/>

                    <!-- Phone Long Diagonal Drop Shadow -->
                    <polygon points="145 18 265 18 365 214 115 214" fill="rgba(2, 28, 70, 0.35)"/>

                    <!-- Central Smartphone -->
                    <g>
                        <!-- Phone Outer White Frame -->
                        <rect x="145" y="16" width="122" height="188" rx="20" fill="#FFFFFF"/>
                        <!-- Top Notch Speaker Bar -->
                        <rect x="186" y="22" width="40" height="3.5" rx="1.75" fill="#CBD5E1"/>

                        <!-- Screen Content -->
                        <!-- Profile Header Row on Screen -->
                        <circle cx="168" cy="38" r="9" fill="#0284C7"/>
                        <circle cx="168" cy="36" r="3" fill="#FFFFFF"/>
                        <path d="M163 43 C163 40.5 165 39.5 168 39.5 C171 39.5 173 40.5 173 43" fill="#FFFFFF"/>
                        <rect x="184" y="34" width="68" height="8" rx="4" fill="#0284C7" opacity="0.8"/>

                        <!-- Screen Skeleton Content Lines -->
                        <rect x="158" y="54" width="96" height="4" rx="2" fill="#E2E8F0"/>
                        <rect x="158" y="63" width="72" height="4" rx="2" fill="#E2E8F0"/>

                        <!-- Deep Blue App Feature Card on Screen -->
                        <rect x="158" y="76" width="96" height="114" rx="10" fill="url(#phoneCardGrad)"/>
                        
                        <!-- SPKLU Electric Bolt & Power Glow on Screen -->
                        <circle cx="206" cy="122" r="26" fill="#3B82F6" opacity="0.35"/>
                        <polygon points="208 106 197 122 206 122 204 138 215 122 206 122" fill="#FFC629"/>
                        <text x="206" y="154" font-family="'Inter', sans-serif" font-size="7.5" font-weight="800" fill="#FFFFFF" text-anchor="middle" letter-spacing="0.5">SPKLU PLN</text>
                    </g>

                    <!-- Foreground Floating Card (Overlapping Phone) -->
                    <g filter="url(#floatCardShadow)">
                        <!-- Card White Container -->
                        <rect x="176" y="86" width="150" height="96" rx="11" fill="#FFFFFF"/>

                        <!-- Header Row of Floating Card: Avatar & Status -->
                        <circle cx="194" cy="102" r="7.5" fill="#60A5FA"/>
                        <circle cx="194" cy="100.5" r="2.5" fill="#FFFFFF"/>
                        <path d="M190 106 C190 104 191.5 103 194 103 C196.5 103 198 104 198 106" fill="#FFFFFF"/>
                        <rect x="206" y="99" width="38" height="3" rx="1.5" fill="#94A3B8"/>
                        <rect x="206" y="105" width="24" height="2.5" rx="1.25" fill="#CBD5E1"/>

                        <!-- Divider Line -->
                        <rect x="187" y="115" width="128" height="2" rx="1" fill="#93C5FD"/>

                        <!-- OTP / Activation Input Pill Row -->
                        <!-- Key / Link Icon -->
                        <circle cx="194" cy="129" r="4.5" fill="#EFF6FF" stroke="#3B82F6" stroke-width="1"/>
                        <line x1="193" y1="129" x2="197" y2="129" stroke="#3B82F6" stroke-width="1.2"/>
                        <!-- Activation Pill Bar with Matchmaker Gradient Button -->
                        <rect x="204" y="123" width="102" height="13" rx="6.5" fill="#F0F9FF" stroke="#BFDBFE" stroke-width="0.8"/>
                        <rect x="204" y="123" width="62" height="13" rx="6.5" fill="url(#btnGrad)"/>
                        <text x="235" y="132" font-family="'Inter', sans-serif" font-size="6.2" font-weight="800" fill="#FFFFFF" text-anchor="middle">AKTIFKAN AKUN</text>

                        <!-- Toggle Switch Row -->
                        <rect x="187" y="143" width="18" height="9" rx="4.5" fill="#10B981"/>
                        <circle cx="199" cy="147.5" r="3.2" fill="#FFFFFF"/>
                        <rect x="210" y="145" width="48" height="2.5" rx="1.25" fill="#CBD5E1"/>
                        <rect x="264" y="145" width="36" height="2.5" rx="1.25" fill="#CBD5E1"/>

                        <!-- Bottom Feedback Icons Row -->
                        <line x1="187" y1="159" x2="315" y2="159" stroke="#F1F5F9" stroke-width="1"/>
                        <!-- Thumbs up -->
                        <path d="M198 168 C198 165.5 200 165.5 200 164 L200 163 C200 162.5 199.5 162 199 162 L196 164 L196 170 L202 170 C202.5 170 203 169.5 203 169 L203.5 167 C203.8 166.5 203.5 166 203 166 Z" stroke="#94A3B8" stroke-width="0.9" fill="none"/>
                        <!-- Heart -->
                        <path d="M228 164 C226 162 224 163.5 224 165 C224 167 228 170 228 170 C228 170 232 167 232 165 C232 163.5 230 162 228 164 Z" stroke="#94A3B8" stroke-width="0.9" fill="none"/>
                        <!-- Verified Check -->
                        <polyline points="258 166 260.5 168.5 265 164" fill="none" stroke="#10B981" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                        <!-- Send Arrow -->
                        <path d="M292 163 L298 166 L292 169 L293.5 166 Z" stroke="#94A3B8" stroke-width="0.9" fill="none"/>
                    </g>

                    <!-- Top-Left Badge: User Account Group Badge -->
                    <g filter="url(#badgeShadow)">
                        <circle cx="72" cy="58" r="23" fill="#FFFFFF"/>
                        <circle cx="72" cy="58" r="19" fill="#ECFDF5"/>
                        <!-- User Group Icons in Emerald Green -->
                        <circle cx="72" cy="52" r="4.5" fill="#10B981"/>
                        <path d="M64 64 C64 60 67.5 58.5 72 58.5 C76.5 58.5 80 60 80 64" fill="#10B981"/>
                        <circle cx="63" cy="54" r="3.5" fill="#34D399"/>
                        <path d="M57 63 C57 60.5 59.5 59.5 63 59.5" fill="#34D399"/>
                        <circle cx="81" cy="54" r="3.5" fill="#34D399"/>
                        <path d="M87 63 C87 60.5 84.5 59.5 81 59.5" fill="#34D399"/>
                    </g>

                    <!-- Bottom-Left Badge: Setting Gear with Verified Checkmark -->
                    <g filter="url(#badgeShadow)">
                        <circle cx="72" cy="165" r="23" fill="#FFFFFF"/>
                        <circle cx="72" cy="165" r="19" fill="#F0F9FF"/>
                        <!-- Gear Shape in Blue -->
                        <path d="M72 153 L74 153 L74.5 156 C75.5 156.3 76.5 156.8 77.4 157.5 L80 156 L81.5 157.5 L80 160.1 C80.7 161 81.2 162 81.5 163 L84.5 163.5 L84.5 165.5 L81.5 166 C81.2 167 80.7 168 80 168.9 L81.5 171.5 L80 173 L77.4 171.5 C76.5 172.2 75.5 172.7 74.5 173 L74 176 L72 176 L71.5 173 C70.5 172.7 69.5 172.2 68.6 171.5 L66 173 L64.5 171.5 L66 168.9 C65.3 168 64.8 167 64.5 166 L61.5 165.5 L61.5 163.5 L64.5 163 C64.8 162 65.3 161 66 160.1 L64.5 157.5 L66 156 L68.6 157.5 C69.5 156.8 70.5 156.3 71.5 156 Z" fill="#0284C7"/>
                        <!-- Center Verified Checkmark -->
                        <circle cx="72" cy="164.5" r="8" fill="#023E8A"/>
                        <polyline points="67.5 164.5 70.5 167.5 76.5 161.5" fill="none" stroke="#FFFFFF" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </g>

                    <!-- Top-Right Badge: Security Question & OTP Message Bubbles -->
                    <g filter="url(#badgeShadow)">
                        <!-- Cyan Question Bubble -->
                        <rect x="332" y="44" width="24" height="18" rx="5" fill="#38BDF8"/>
                        <polygon points="338 62 338 66 343 62" fill="#38BDF8"/>
                        <text x="344" y="57" font-family="'Inter', sans-serif" font-size="11" font-weight="900" fill="#FFFFFF" text-anchor="middle">?</text>

                        <!-- Dark Blue OTP Dots Bubble -->
                        <rect x="348" y="55" width="28" height="20" rx="5" fill="#023E8A"/>
                        <polygon points="368 75 368 79 363 75" fill="#023E8A"/>
                        <circle cx="356" cy="65" r="1.8" fill="#FFFFFF"/>
                        <circle cx="362" cy="65" r="1.8" fill="#FFFFFF"/>
                        <circle cx="368" cy="65" r="1.8" fill="#FFFFFF"/>
                    </g>

                    <!-- Bottom-Right Badge: SPKLU Partnership / Access Handshake -->
                    <g filter="url(#badgeShadow)">
                        <circle cx="360" cy="165" r="23" fill="url(#tealGrad)"/>
                        <circle cx="360" cy="165" r="21" fill="none" stroke="rgba(255,255,255,0.25)" stroke-width="1.5"/>
                        <!-- White Handshake Vector -->
                        <path d="M349 164 L354 159 L360 165 L366 159 L371 164 L365 170 L360 167 L355 170 Z" fill="#FFFFFF"/>
                        <!-- Cuffs -->
                        <path d="M348 162 L351 159 L354 163 L351 166 Z" fill="#BAE6FD"/>
                        <path d="M372 162 L369 159 L366 163 L369 166 Z" fill="#BAE6FD"/>
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
