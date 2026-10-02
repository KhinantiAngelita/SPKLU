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
            width: 220px;
            height: 126px;
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
            padding: 24px 36px 30px;
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
                <svg viewBox="0 0 280 160" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="avatarGrad" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#0081AB"/>
                            <stop offset="100%" stop-color="#023E8A"/>
                        </linearGradient>
                        <linearGradient id="shieldGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#10B981"/>
                            <stop offset="100%" stop-color="#047857"/>
                        </linearGradient>
                        <linearGradient id="btnGrad" x1="0" y1="0" x2="1" y2="0">
                            <stop offset="0%" stop-color="#FFC629"/>
                            <stop offset="100%" stop-color="#FF7800"/>
                        </linearGradient>
                        <linearGradient id="poloGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#034FA8"/>
                            <stop offset="100%" stop-color="#023E8A"/>
                        </linearGradient>
                        <linearGradient id="phoneBody" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#FFFFFF"/>
                            <stop offset="100%" stop-color="#F1F5F9"/>
                        </linearGradient>
                        <filter id="cardShadow" x="-20%" y="-20%" width="140%" height="140%">
                            <feDropShadow dx="0" dy="6" stdDeviation="8" flood-color="#023E8A" flood-opacity="0.10"/>
                        </filter>
                        <filter id="badgeGlow" x="-30%" y="-30%" width="160%" height="160%">
                            <feDropShadow dx="0" dy="4" stdDeviation="6" flood-color="#10B981" flood-opacity="0.28"/>
                        </filter>
                    </defs>

                    <!-- Ambient Backdrop Clouds / Glow -->
                    <ellipse cx="140" cy="85" rx="105" ry="60" fill="#E0F2FE" opacity="0.6"/>
                    <circle cx="210" cy="55" r="40" fill="#BAE6FD" opacity="0.45"/>
                    <circle cx="65" cy="65" r="35" fill="#BAE6FD" opacity="0.4"/>

                    <!-- Ground / Pedestal Shadow -->
                    <ellipse cx="140" cy="150" rx="105" ry="8" fill="#CBD5E1" opacity="0.45"/>
                    <ellipse cx="140" cy="150" rx="65" ry="4" fill="#94A3B8" opacity="0.3"/>

                    <!-- Floating Sparkle Stars -->
                    <path d="M245 32 L247.5 24 L250 32 L258 34.5 L250 37 L247.5 45 L245 37 L237 34.5 Z" fill="#FFC629"/>
                    <path d="M24 42 L25.5 36 L27 42 L33 43.5 L27 45 L25.5 51 L24 45 L18 43.5 Z" fill="#FF7800"/>
                    <circle cx="35" cy="115" r="3" fill="#0081AB" opacity="0.4"/>
                    <circle cx="255" cy="105" r="3.5" fill="#10B981" opacity="0.5"/>
                    <circle cx="135" cy="18" r="2.5" fill="#FFC629"/>

                    <!-- Smartphone (Left side) -->
                    <g filter="url(#cardShadow)">
                        <!-- Outer Phone Body -->
                        <rect x="42" y="18" width="82" height="124" rx="15" fill="url(#phoneBody)" stroke="#1E293B" stroke-width="2.2"/>
                        <!-- Notch Speaker -->
                        <rect x="71" y="24" width="24" height="3.5" rx="1.75" fill="#334155"/>

                        <!-- Screen Container -->
                        <rect x="46" y="31" width="74" height="106" rx="10" fill="#FFFFFF"/>

                        <!-- Screen Header Badge -->
                        <rect x="52" y="36" width="62" height="38" rx="8" fill="#F8FAFC" stroke="#E2E8F0" stroke-width="0.8"/>
                        
                        <!-- User Avatar on Screen -->
                        <circle cx="83" cy="51" r="11" fill="url(#avatarGrad)"/>
                        <path d="M83 48 A3.5 3.5 0 1 0 83 55 A3.5 3.5 0 1 0 83 48" fill="#FFFFFF"/>
                        <path d="M77 60 C77 56.5 80 55.5 83 55.5 C86 55.5 89 56.5 89 60" fill="#FFFFFF"/>

                        <!-- Verified check pill on avatar -->
                        <circle cx="91" cy="58" r="3.8" fill="#10B981"/>
                        <polyline points="89.5 58 90.8 59.3 92.5 57" fill="none" stroke="#FFFFFF" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round"/>

                        <!-- Screen text line -->
                        <rect x="62" y="66" width="42" height="3.5" rx="1.75" fill="#CBD5E1"/>

                        <!-- PIN Code Input Row -->
                        <rect x="52" y="80" width="62" height="14" rx="5" fill="#F1F5F9" stroke="#E2E8F0" stroke-width="0.8"/>
                        <circle cx="63" cy="87" r="2.5" fill="#023E8A"/>
                        <circle cx="73" cy="87" r="2.5" fill="#023E8A"/>
                        <circle cx="83" cy="87" r="2.5" fill="#023E8A"/>
                        <circle cx="93" cy="87" r="2.5" fill="#023E8A"/>
                        <polyline points="101 85 103 87 106 84" fill="none" stroke="#10B981" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>

                        <!-- Action Button on Screen -->
                        <rect x="52" y="101" width="62" height="18" rx="6" fill="url(#btnGrad)"/>
                        <text x="83" y="113" font-family="'Inter', sans-serif" font-size="7.5" font-weight="800" fill="#FFFFFF" text-anchor="middle" letter-spacing="0.6">AKTIVASI</text>
                        <circle cx="106" cy="110" r="1.5" fill="#FFFFFF" opacity="0.8"/>
                    </g>

                    <!-- Floating Verified Shield Badge (Center-Top) -->
                    <g filter="url(#badgeGlow)">
                        <circle cx="130" cy="38" r="19" fill="#FFFFFF"/>
                        <path d="M130 23 L144 29 C144 39.5 138 49 130 52 C122 49 116 39.5 116 29 Z" fill="url(#shieldGrad)"/>
                        <polyline points="123.5 37.5 128.5 42.5 137 33.5" fill="none" stroke="#FFFFFF" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    </g>

                    <!-- Modern Stylized Character (Right side, Undraw/Tech Corporate style) -->
                    <g>
                        <!-- Lower Body / Trousers (Grounding) -->
                        <path d="M168 135 L164 150 L180 150 L184 135 Z" fill="#1E293B"/>
                        <path d="M188 135 L192 150 L208 150 L204 135 Z" fill="#0F172A"/>

                        <!-- Torso / Polo Shirt (PLN Deep Blue) -->
                        <path d="M158 136 C158 112 172 105 188 105 C204 105 218 112 218 136 Z" fill="url(#poloGrad)"/>

                        <!-- Polo Collar & Trim -->
                        <path d="M181 105 L188 118 L195 105" fill="#FFFFFF"/>
                        <path d="M182 105 L188 115 L194 105" fill="#E2E8F0"/>
                        <line x1="188" y1="118" x2="188" y2="132" stroke="#FFFFFF" stroke-width="1.6" stroke-linecap="round"/>
                        <circle cx="188" cy="122" r="1.2" fill="#023E8A"/>
                        <circle cx="188" cy="128" r="1.2" fill="#023E8A"/>

                        <!-- ID Card Lanyard -->
                        <path d="M183 110 L188 124 L193 110" stroke="#0081AB" stroke-width="2" fill="none"/>
                        <rect x="183.5" y="124" width="9" height="10" rx="1.8" fill="#FFFFFF" stroke="#0081AB" stroke-width="1.2"/>
                        <rect x="185" y="126.5" width="6" height="2" rx="1" fill="#023E8A"/>

                        <!-- Neck -->
                        <rect x="183" y="90" width="10" height="17" rx="3.5" fill="#FCD34D"/>
                        <path d="M183 94 C185 97 191 97 193 94 L193 90 L183 90 Z" fill="#F59E0B" opacity="0.3"/>

                        <!-- Head (Modern stylized silhouette) -->
                        <circle cx="188" cy="76" r="17" fill="#FCD34D"/>
                        <!-- Ears -->
                        <circle cx="171" cy="76" r="3.5" fill="#FCD34D"/>
                        <circle cx="205" cy="76" r="3.5" fill="#FCD34D"/>

                        <!-- Hair (Sleek modern side-part haircut) -->
                        <path d="M170 74 C170 57 178 50 189 50 C202 50 207 60 207 72 C202 70 195 68 187 68 C178 68 172 71 170 74 Z" fill="#0F172A"/>
                        <path d="M170 74 L170 80 L174 75 Z" fill="#0F172A"/>
                        <path d="M205 74 L205 80 L202 75 Z" fill="#0F172A"/>
                        <!-- Hair volume highlight -->
                        <path d="M180 54 C184 53 192 53 198 55" stroke="#334155" stroke-width="1.8" stroke-linecap="round" fill="none"/>

                        <!-- Minimalist Sleek Profile (Undraw Style) -->
                        <circle cx="182" cy="74" r="1.8" fill="#0F172A"/>
                        <circle cx="194" cy="74" r="1.8" fill="#0F172A"/>
                        <path d="M185 79 Q188 82 191 79" stroke="#0F172A" stroke-width="1.4" stroke-linecap="round" fill="none"/>

                        <!-- Left Arm & Hand: Reaching naturally forward to touch screen button -->
                        <path d="M165 118 C150 114 135 106 120 102" stroke="url(#poloGrad)" stroke-width="9.5" stroke-linecap="round"/>
                        <ellipse cx="123" cy="103" rx="3" ry="5.5" fill="#0081AB" transform="rotate(-20 123 103)"/>
                        <circle cx="116" cy="101" r="4.5" fill="#FCD34D"/>
                        <!-- Index Finger touching activation button -->
                        <path d="M116 101 L108 100" stroke="#FCD34D" stroke-width="3.6" stroke-linecap="round"/>
                        <circle cx="107" cy="100" r="1.8" fill="#FCD34D"/>

                        <!-- Tapping Interaction Sparkle on Button -->
                        <path d="M100 95 L101.5 91 L103 95 L107 96.5 L103 98 L101.5 102 L100 98 L96 96.5 Z" fill="#FFC629"/>

                        <!-- Right Arm: Natural relaxed posture -->
                        <path d="M211 118 C220 125 224 134 220 144" stroke="url(#poloGrad)" stroke-width="9" stroke-linecap="round"/>
                        <circle cx="218" cy="144" r="4.5" fill="#FCD34D"/>
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
