<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih UP3 — Aktivasi Akun SPKLU</title>
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
            padding: 16px 20px;
            color: #0F172A;
        }

        .act-card {
            background: #FFFFFF;
            border-radius: 20px;
            width: 560px;
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

        .act-logo {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: linear-gradient(135deg, #023E8A, #0081AB);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 14px;
        }
        .act-logo svg {
            width: 26px;
            height: 26px;
            color: #FFC629;
            stroke-width: 2.2;
        }

        .act-header h1 {
            font-size: 20px;
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

        /* Stepper */
        .act-stepper {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin: 18px auto 0;
            max-width: 380px;
        }
        .step-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11.5px;
            font-weight: 700;
            color: #94A3B8;
        }
        .step-item.active { color: #023E8A; }
        .step-item.done { color: #059669; }
        .step-num {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            background: #E2E8F0;
            color: #64748B;
        }
        .step-item.active .step-num {
            background: #023E8A;
            color: #FFFFFF;
            box-shadow: 0 0 0 3px rgba(2, 62, 138, 0.18);
        }
        .step-item.done .step-num {
            background: #059669;
            color: #FFFFFF;
        }
        .step-divider {
            width: 20px;
            height: 2px;
            background: #E2E8F0;
        }

        .act-body {
            padding: 28px 36px 36px;
        }

        .act-user-box {
            display: flex;
            align-items: center;
            gap: 14px;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 14px;
            padding: 13px 16px;
            margin-bottom: 22px;
        }
        .act-user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, #023E8A, #0081AB);
            color: #FFFFFF;
            font-weight: 800;
            font-size: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .act-user-info { flex: 1; min-width: 0; }
        .act-user-info .name {
            font-size: 14px;
            font-weight: 800;
            color: #0F172A;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .act-user-info .email {
            font-size: 12px;
            color: #64748B;
            margin-top: 2px;
        }
        .act-user-role {
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 3px 9px;
            border-radius: 6px;
            background: rgba(0, 129, 171, 0.1);
            color: #0081AB;
            border: 1px solid rgba(0, 129, 171, 0.18);
        }

        .act-section-label {
            font-size: 12px;
            font-weight: 700;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: .04em;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Banner info UP3 terpilih */
        .up3-selected-banner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #F0F9FF;
            border: 1.5px solid #BAE6FD;
            border-radius: 12px;
            padding: 11px 16px;
            margin-bottom: 16px;
            transition: all .2s ease;
        }
        .up3-selected-banner.empty {
            background: #F8FAFC;
            border-color: #E2E8F0;
        }
        .up3-selected-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .up3-selected-left svg {
            width: 18px;
            height: 18px;
            color: #0284C7;
            flex-shrink: 0;
        }
        .up3-selected-banner.empty .up3-selected-left svg {
            color: #94A3B8;
        }
        .up3-selected-name {
            font-size: 13.5px;
            font-weight: 800;
            color: #023E8A;
        }
        .up3-selected-banner.empty .up3-selected-name {
            font-size: 13px;
            font-weight: 500;
            color: #64748B;
        }
        .up3-selected-badge {
            font-size: 11px;
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

        /* Grid 18 UP3 (6x3) */
        .up3-quick-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 7px;
            margin-bottom: 24px;
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
        }
        .act-btn-submit:hover {
            background: #002D66;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(2, 62, 138, 0.32);
        }
        .act-btn-submit:active { transform: translateY(0); }

        .act-note {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            padding: 12px 14px;
            margin-top: 22px;
            font-size: 12px;
            color: #64748B;
            line-height: 1.55;
        }
        .act-note svg {
            width: 16px;
            height: 16px;
            min-width: 16px;
            margin-top: 2px;
            color: #0081AB;
        }
    </style>
</head>
<body>

    <div class="act-card">
        <div class="act-header">
            <div class="act-logo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="16 11 18 13 22 9"/></svg>
            </div>
            <h1>Aktivasi Akun SPKLU</h1>
            <p>Pilih Unit Pelaksana Pelayanan Pelanggan (UP3) wilayah kerja Anda sebelum verifikasi OTP</p>

            <div class="act-stepper">
                <div class="step-item active">
                    <span class="step-num">1</span>
                    <span>Pilih UP3</span>
                </div>
                <div class="step-divider"></div>
                <div class="step-item">
                    <span class="step-num">2</span>
                    <span>Kode OTP</span>
                </div>
                <div class="step-divider"></div>
                <div class="step-item">
                    <span class="step-num">3</span>
                    <span>Password</span>
                </div>
                <div class="step-divider"></div>
                <div class="step-item">
                    <span class="step-num">4</span>
                    <span>Riwayat</span>
                </div>
            </div>
        </div>

        <div class="act-body">
            @if ($errors->any())
                <div style="background:rgba(192,57,43,.08); border:1px solid rgba(192,57,43,.25); color:#C0392B; padding:12px 14px; border-radius:10px; font-size:12.5px; margin-bottom:18px;">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Profil Singkat Pengguna --}}
            <div class="act-user-box">
                <div class="act-user-avatar">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="act-user-info">
                    <div class="name">{{ $user->name }}</div>
                    <div class="email">{{ $user->email }}</div>
                </div>
                <div class="act-user-role">
                    {{ str_replace('_', ' ', $user->role) }}
                </div>
            </div>

            <form method="POST" action="{{ route('activation.select-up3', $token) }}" id="formSelectUp3" onsubmit="return validateForm()">
                @csrf
                <input type="hidden" name="up3" id="selected-up3-input" value="{{ old('up3', $user->up3) }}">

                <div class="act-section-label">
                    <span>Pilih Unit UP3 Wilayah Kerja (18 Unit)</span>
                </div>

                {{-- Status Banner Terpilih --}}
                @php
                    $initialUp3 = old('up3', $user->up3);
                    $up3List = $up3Map ?? \App\Models\User::UP3_MAP;
                    $initialKode = null;
                    if ($initialUp3) {
                        foreach ($up3List as $k => $n) {
                            if ($initialUp3 === $k || $initialUp3 === $n) {
                                $initialKode = $k;
                                $initialUp3 = $n;
                                break;
                            }
                        }
                    }
                @endphp
                <div id="up3Banner" class="up3-selected-banner {{ $initialUp3 ? '' : 'empty' }}">
                    <div class="up3-selected-left">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <div class="up3-selected-name" id="up3BannerText">
                            @if ($initialUp3)
                                @if ($initialKode)<span class="up3-banner-code">{{ $initialKode }}</span>@endif
                                <span>{{ $initialUp3 }} (UID Jawa Barat)</span>
                            @else
                                Silakan klik salah satu unit UP3 di bawah ini
                            @endif
                        </div>
                    </div>
                    <span class="up3-selected-badge" id="up3BannerBadge" style="{{ $initialUp3 ? '' : 'display:none;' }}">✓ Terpilih</span>
                </div>

                {{-- Singkatan 18 UP3 --}}
                <div class="up3-quick-grid">
                    @foreach ($up3List as $kode => $nama)
                        @php
                            $isSelected = ($initialUp3 === $kode || $initialUp3 === $nama);
                        @endphp
                        <button type="button" 
                                class="up3-chip-btn {{ $isSelected ? 'active' : '' }}" 
                                data-up3="{{ $nama }}"
                                data-kode="{{ $kode }}"
                                title="{{ $nama }}"
                                onclick="pilihUp3('{{ $nama }}', '{{ $kode }}')">
                            {{ $kode }}
                        </button>
                    @endforeach
                </div>

                <button type="submit" class="act-btn-submit" id="submitBtn">
                    Lanjut ke Verifikasi OTP
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="18" height="18" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </button>
            </form>

            <div class="act-note">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                <span>Aktivasi kini langsung diverifikasi melalui <strong>Kode OTP</strong> tanpa perlu tautan email tambahan. Riwayat lengkap aktivasi Anda akan dicatat dan ditampilkan di akhir proses.</span>
            </div>
        </div>
    </div>

    <script>
        function pilihUp3(nama, kode) {
            document.getElementById('selected-up3-input').value = nama;

            // Update status banner
            const banner = document.getElementById('up3Banner');
            const text = document.getElementById('up3BannerText');
            const badge = document.getElementById('up3BannerBadge');

            banner.classList.remove('empty');
            text.innerHTML = `<span class="up3-banner-code">${kode}</span> <span>${nama} (UID Jawa Barat)</span>`;
            badge.style.display = 'inline-block';

            // Update active states on buttons
            document.querySelectorAll('.up3-chip-btn').forEach(btn => {
                if (btn.getAttribute('data-up3') === nama || btn.getAttribute('data-kode') === kode) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }

        function validateForm() {
            const val = document.getElementById('selected-up3-input').value.trim();
            if (!val) {
                alert('Silakan pilih salah satu Unit Pelaksana Pelayanan Pelanggan (UP3) terlebih dahulu.');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
