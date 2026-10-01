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

        .act-brand-top {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 22px;
            background: #FFFFFF;
            padding: 8px 18px;
            border-radius: 999px;
            border: 1px solid #E2E8F0;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.05);
        }
        .act-brand-top img {
            width: 24px;
            height: 24px;
            object-fit: contain;
        }
        .act-brand-top span {
            font-size: 12.5px;
            font-weight: 800;
            color: #023E8A;
            letter-spacing: .03em;
            text-transform: uppercase;
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

        .act-logo {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: linear-gradient(135deg, #023E8A, #0081AB);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 14px;
            box-shadow: 0 4px 14px rgba(2, 62, 138, 0.25);
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

        .act-body {
            padding: 28px 36px 36px;
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
        }
        .field-input-wrap svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 17px;
            height: 17px;
            color: #94A3B8;
            stroke-width: 2;
            pointer-events: none;
        }
        .field-input {
            width: 100%;
            padding: 12px 16px 12px 44px;
            border-radius: 11px;
            border: 1.8px solid #CBD5E1;
            font-size: 14px;
            color: #0F172A;
            font-family: inherit;
            transition: all .15s ease;
            background: #FFFFFF;
        }
        .field-input:focus {
            outline: none;
            border-color: #0081AB;
            box-shadow: 0 0 0 4px rgba(0, 129, 171, 0.15);
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
            font-weight: 800;
            color: #023E8A;
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

        /* Quick Grid */
        .up3-quick-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 7px;
            margin-bottom: 22px;
        }
        @media (max-width: 580px) {
            .up3-quick-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        .up3-chip-btn {
            background: #FFFFFF;
            border: 1.5px solid #E2E8F0;
            border-radius: 10px;
            padding: 10px 6px;
            font-size: 12px;
            font-weight: 600;
            color: #334155;
            text-align: center;
            cursor: pointer;
            transition: all .15s ease;
            outline: none;
            width: 100%;
        }
        .up3-chip-btn:hover {
            border-color: #0081AB;
            background: #F0F9FF;
            color: #023E8A;
            transform: translateY(-1px);
        }
        .up3-chip-btn.active {
            background: linear-gradient(135deg, #023E8A, #0081AB);
            color: #FFFFFF;
            border-color: #023E8A;
            box-shadow: 0 4px 12px rgba(2, 62, 138, 0.25);
            font-weight: 700;
        }

        .act-btn-submit {
            width: 100%;
            padding: 13px 20px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 800;
            cursor: pointer;
            border: none;
            background: linear-gradient(135deg, #023E8A, #0081AB);
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

    {{-- Brand Top Bar --}}
    <div class="act-brand-top">
        <img src="{{ asset('images/logo-revolution-circle.png') }}" alt="PLN Logo">
        <span>Sistem SPKLU &bull; PLN UID Jawa Barat</span>
    </div>

    <div class="act-card">
        <div class="act-header">
            <div class="act-logo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
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
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <input type="email" name="email" id="email" class="field-input" value="{{ old('email') }}" required placeholder="nama@email.com" autofocus>
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label">Pilih Unit UP3 Wilayah Kerja (Pilih Cepat)</label>
                    @php $initDirectUp3 = old('up3'); @endphp
                    <div id="up3DirectBanner" class="up3-selected-banner {{ $initDirectUp3 ? '' : 'empty' }}">
                        <div class="up3-selected-left">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <div class="up3-selected-name" id="up3DirectText">
                                {{ $initDirectUp3 ? $initDirectUp3 : 'Silakan klik salah satu UP3 di bawah' }}
                            </div>
                        </div>
                        <span class="up3-selected-badge" id="up3DirectBadge" style="{{ $initDirectUp3 ? '' : 'display:none;' }}">✓ Terpilih</span>
                    </div>

                    <div class="up3-quick-grid">
                        @foreach ($daftarUp3 as $u)
                            @php
                                $sName = str_replace('UP3 ', '', $u);
                                $isAct = $initDirectUp3 === $u;
                            @endphp
                            <button type="button" 
                                    class="up3-chip-btn {{ $isAct ? 'active' : '' }}" 
                                    data-up3="{{ $u }}"
                                    onclick="pilihDirectUp3('{{ $u }}')">
                                {{ $sName }}
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
        function pilihDirectUp3(nama) {
            document.getElementById('selected-up3-direct').value = nama;
            const banner = document.getElementById('up3DirectBanner');
            const text = document.getElementById('up3DirectText');
            const badge = document.getElementById('up3DirectBadge');

            banner.classList.remove('empty');
            text.textContent = nama + ' (UID Jawa Barat)';
            badge.style.display = 'inline-block';

            document.querySelectorAll('.up3-chip-btn').forEach(btn => {
                if (btn.getAttribute('data-up3') === nama) {
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
