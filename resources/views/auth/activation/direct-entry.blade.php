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
                <svg viewBox="0 0 430 205" fill="none" xmlns="http://www.w3.org/2000/svg" style="user-select: none; -webkit-user-select: none;">
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

                        <!-- Crisp Drop Shadows with Light Blue / Navy Hue -->
                        <filter id="phoneShadow" x="-15%" y="-15%" width="130%" height="130%">
                            <feDropShadow dx="0" dy="6" stdDeviation="10" flood-color="#023E8A" flood-opacity="0.08"/>
                        </filter>

                        <filter id="floatCardShadow" x="-15%" y="-15%" width="130%" height="130%">
                            <feDropShadow dx="0" dy="8" stdDeviation="12" flood-color="#023E8A" flood-opacity="0.10"/>
                        </filter>

                        <filter id="badgeShadow" x="-20%" y="-20%" width="140%" height="140%">
                            <feDropShadow dx="0" dy="4" stdDeviation="6" flood-color="#023E8A" flood-opacity="0.08"/>
                        </filter>

                        <filter id="btnShadow" x="-10%" y="-15%" width="120%" height="140%">
                            <feDropShadow dx="0" dy="3" stdDeviation="3" flood-color="#FF7800" flood-opacity="0.3"/>
                        </filter>
                    </defs>

                    <!-- Clean Subtle Tech Floor Lines -->
                    <line x1="40" y1="182" x2="390" y2="182" stroke="#E2E8F0" stroke-width="1" stroke-dasharray="4 4"/>
                    <line x1="20" y1="196" x2="410" y2="196" stroke="#E0F2FE" stroke-width="1.2"/>

                    <!-- Dashed Circuit Connecting Lines (Symmetric Light Blue) -->
                    <path d="M54 54 L104 54 L104 74 L146 74" stroke="#7DD3FC" stroke-width="1.5" stroke-dasharray="4 4" fill="none"/>
                    <path d="M54 150 L104 150 L104 130 L146 130" stroke="#7DD3FC" stroke-width="1.5" stroke-dasharray="4 4" fill="none"/>
                    <path d="M376 54 L326 54 L326 74 L264 74" stroke="#7DD3FC" stroke-width="1.5" stroke-dasharray="4 4" fill="none"/>
                    <path d="M376 150 L336 150 L336 150 L320 150" stroke="#7DD3FC" stroke-width="1.5" stroke-dasharray="4 4" fill="none"/>

                    <!-- Circuit Glowing Nodes -->
                    <circle cx="104" cy="54" r="4.5" fill="#BAE6FD" opacity="0.8"/>
                    <circle cx="104" cy="54" r="2.5" fill="#0284C7"/>
                    <circle cx="104" cy="150" r="4.5" fill="#BAE6FD" opacity="0.8"/>
                    <circle cx="104" cy="150" r="2.5" fill="#0284C7"/>
                    <circle cx="326" cy="54" r="4.5" fill="#BAE6FD" opacity="0.8"/>
                    <circle cx="326" cy="54" r="2.5" fill="#0284C7"/>
                    <circle cx="336" cy="150" r="4.5" fill="#BAE6FD" opacity="0.8"/>
                    <circle cx="336" cy="150" r="2.5" fill="#0284C7"/>

                    <!-- Central Smartphone -->
                    <g filter="url(#phoneShadow)">
                        <!-- Outer Chassis: Pure White with Crisp Light-Blue Border -->
                        <rect x="146" y="12" width="118" height="182" rx="20" fill="#FFFFFF" stroke="#BAE6FD" stroke-width="1.8"/>

                        <!-- Speaker / Earpiece -->
                        <rect x="190" y="18" width="30" height="3" rx="1.5" fill="#CBD5E1"/>

                        <!-- Phone Screen with Hint of Light Blue -->
                        <rect x="152" y="25" width="106" height="163" rx="14" fill="#F0F9FF" stroke="#E0F2FE" stroke-width="1"/>

                        <!-- Screen Header: Mini Profile & Verification -->
                        <circle cx="168" cy="38" r="7.5" fill="#0284C7"/>
                        <circle cx="168" cy="36.5" r="2.6" fill="#FFFFFF"/>
                        <path d="M163.5 42.5 C163.5 40.5 165.5 39.5 168 39.5 C170.5 39.5 172.5 40.5 172.5 42.5" fill="#FFFFFF"/>
                        <rect x="180" y="34.5" width="44" height="4" rx="2" fill="#BAE6FD"/>
                        <rect x="180" y="41" width="28" height="3" rx="1.5" fill="#CBD5E1"/>

                        <!-- Deep Blue / Cyan Feature Card on Screen -->
                        <rect x="158" y="52" width="94" height="128" rx="10" fill="url(#phoneCardGrad)"/>

                        <!-- SPKLU Electric Bolt Badge on Screen -->
                        <circle cx="205" cy="88" r="20" fill="#0081AB" opacity="0.45"/>
                        <polygon points="207 76 197 90 205 90 203 104 214 90 206 90" fill="#FFC629"/>
                        <text x="205" y="118" font-family="'Inter', sans-serif" font-size="7.5" font-weight="800" fill="#FFFFFF" text-anchor="middle" letter-spacing="0.6">SPKLU PLN</text>
                        <text x="205" y="128" font-family="'Inter', sans-serif" font-size="5.5" font-weight="600" fill="#BAE6FD" text-anchor="middle" letter-spacing="0.4">AKTIVASI SISTEM</text>
                    </g>

                    <!-- Foreground Floating Activation Card (Overlapping Phone) -->
                    <g filter="url(#floatCardShadow)">
                        <!-- Card White Container with Crisp Light-Blue Border -->
                        <rect x="176" y="72" width="144" height="102" rx="12" fill="#FFFFFF" stroke="#BAE6FD" stroke-width="1.8"/>

                        <!-- Header Row of Floating Card: Avatar & Status -->
                        <circle cx="193" cy="87" r="8.5" fill="#F0FDF4" stroke="#86EFAC" stroke-width="1.2"/>
                        <polyline points="189.5 87 192 89.5 196.5 84.5" fill="none" stroke="#10B981" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        
                        <text x="206" y="85" font-family="'Inter', sans-serif" font-size="7.5" font-weight="700" fill="#1E293B">Aktivasi Akun</text>
                        <text x="206" y="94" font-family="'Inter', sans-serif" font-size="6" font-weight="500" fill="#64748B">Verifikasi Unit UP3</text>

                        <!-- Status Pill -->
                        <rect x="272" y="80.5" width="38" height="13" rx="6.5" fill="#ECFDF5" stroke="#A7F3D0" stroke-width="0.8"/>
                        <circle cx="278" cy="87" r="2.2" fill="#10B981"/>
                        <text x="293" y="87" font-family="'Inter', sans-serif" font-size="5.8" font-weight="700" fill="#059669" text-anchor="middle" dominant-baseline="central">Aktif</text>

                        <!-- Soft Blue Divider Line -->
                        <line x1="186" y1="101" x2="310" y2="101" stroke="#F1F5F9" stroke-width="1"/>

                        <!-- Activation Bar with Matchmaker Gradient Button -->
                        <rect x="186" y="106" width="124" height="27" rx="7.5" fill="#F8FAFC" stroke="#BAE6FD" stroke-width="1"/>
                        <!-- Key Icon -->
                        <circle cx="196" cy="119.5" r="5" fill="#E0F2FE" stroke="#0081AB" stroke-width="1"/>
                        <circle cx="196" cy="118.5" r="1.6" fill="#0081AB"/>
                        <line x1="196" y1="120.5" x2="196" y2="122.5" stroke="#0081AB" stroke-width="1.2"/>
                        <!-- Matchmaker Orange Button with Comfortable Padding -->
                        <rect x="205" y="109" width="100" height="21" rx="10.5" fill="url(#btnGrad)" filter="url(#btnShadow)"/>
                        <text x="255" y="119.5" font-family="'Inter', -apple-system, sans-serif" font-size="6.3" font-weight="700" fill="#FFFFFF" text-anchor="middle" dominant-baseline="central" letter-spacing="0.2">Aktifkan Sekarang</text>

                        <!-- Bottom Verification Banner (Email & Unit Terdaftar) -->
                        <rect x="186" y="139" width="124" height="23" rx="6.5" fill="#F0FDF4" stroke="#BBF7D0" stroke-width="0.8"/>
                        <!-- Crisp Solid Green Checkmark Circle -->
                        <circle cx="198" cy="150.5" r="5.5" fill="#10B981"/>
                        <polyline points="195.5 150.5 197.3 152.3 200.8 148.5" fill="none" stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <!-- Text -->
                        <text x="208" y="150.5" font-family="'Inter', -apple-system, sans-serif" font-size="6.2" font-weight="700" fill="#15803D" dominant-baseline="central">Email &amp; Unit Terdaftar</text>
                        <!-- Valid Status Tag -->
                        <rect x="277" y="144" width="28" height="13" rx="6.5" fill="#DCFCE7"/>
                        <circle cx="282.5" cy="150.5" r="1.8" fill="#10B981"/>
                        <text x="294" y="150.5" font-family="'Inter', sans-serif" font-size="5.5" font-weight="800" fill="#15803D" text-anchor="middle" dominant-baseline="central">VALID</text>
                    </g>

                    <!-- Top-Left Badge: Email Terverifikasi (Mail Envelope with Checkmark) -->
                    <g filter="url(#badgeShadow)">
                        <circle cx="54" cy="54" r="23" fill="#FFFFFF" stroke="#BAE6FD" stroke-width="1.8"/>
                        <circle cx="54" cy="54" r="19" fill="#F0FDF4"/>
                        <!-- Mail Envelope -->
                        <rect x="43" y="47" width="22" height="14" rx="2.5" fill="#0284C7"/>
                        <path d="M43 49 L54 56.5 L65 49" fill="none" stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="round"/>
                        <!-- Green Checkmark Badge -->
                        <circle cx="62" cy="47" r="4.5" fill="#10B981" stroke="#FFFFFF" stroke-width="1"/>
                        <polyline points="60 47 61.5 48.5 64 45.8" fill="none" stroke="#FFFFFF" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </g>

                    <!-- Bottom-Left Badge: Unit UP3 Wilayah Kerja (Location Pin with PLN Bolt) -->
                    <g filter="url(#badgeShadow)">
                        <circle cx="54" cy="150" r="23" fill="#FFFFFF" stroke="#BAE6FD" stroke-width="1.8"/>
                        <circle cx="54" cy="150" r="19" fill="#F0FDF4"/>
                        <!-- Location Pin -->
                        <path d="M54 139 C49.5 139 46 142.5 46 147 C46 152.5 54 161 54 161 C54 161 62 152.5 62 147 C62 142.5 58.5 139 54 139 Z" fill="#023E8A"/>
                        <!-- Inner PLN Lightning Bolt -->
                        <polygon points="55 142 50.5 147 54 147 53 152 57.5 147 54 147" fill="#FFC629"/>
                    </g>

                    <!-- Top-Right Badge: Keamanan Kode OTP (Security Shield with Keyhole & OTP Dots) -->
                    <g filter="url(#badgeShadow)">
                        <circle cx="376" cy="54" r="23" fill="#FFFFFF" stroke="#BAE6FD" stroke-width="1.8"/>
                        <circle cx="376" cy="54" r="19" fill="#EFF6FF"/>
                        <!-- Security Shield -->
                        <path d="M376 43 L386 46.5 C386 53 381.5 58.5 376 61.5 C370.5 58.5 366 53 366 46.5 Z" fill="#0081AB"/>
                        <!-- Keyhole inside Shield -->
                        <circle cx="376" cy="50" r="2.2" fill="#FFFFFF"/>
                        <polygon points="375 51 377 51 377.5 55 374.5 55" fill="#FFFFFF"/>
                        <!-- Mini OTP 3 Dots -->
                        <circle cx="372" cy="57" r="1" fill="#BAE6FD"/>
                        <circle cx="376" cy="57" r="1" fill="#FFFFFF"/>
                        <circle cx="379.8" cy="57" r="1" fill="#BAE6FD"/>
                    </g>

                    <!-- Bottom-Right Badge: Akun Pegawai Aktif (Verified User Profile with Green Checkmark) -->
                    <g filter="url(#badgeShadow)">
                        <circle cx="376" cy="150" r="23" fill="#FFFFFF" stroke="#BAE6FD" stroke-width="1.8"/>
                        <circle cx="376" cy="150" r="19" fill="#F0FDF4"/>
                        <!-- User Silhouette -->
                        <circle cx="376" cy="144" r="4.8" fill="#023E8A"/>
                        <path d="M367.5 156.5 C367.5 152.5 371.2 151 376 151 C380.8 151 384.5 152.5 384.5 156.5" fill="#023E8A"/>
                        <!-- Green Verified Shield Badge -->
                        <circle cx="383" cy="144" r="4.5" fill="#10B981" stroke="#FFFFFF" stroke-width="1.1"/>
                        <polyline points="381.2 144 382.5 145.3 384.8 142.8" fill="none" stroke="#FFFFFF" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
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
