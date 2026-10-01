<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP — Aktivasi Akun SPKLU</title>
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
            width: 500px;
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

        .up3-badge-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            padding: 10px 16px;
            margin-bottom: 22px;
            font-size: 13px;
        }
        .up3-badge-left {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
            color: #023E8A;
        }
        .up3-badge-left svg {
            width: 16px;
            height: 16px;
            color: #0081AB;
        }
        .up3-change-link {
            font-size: 12px;
            font-weight: 600;
            color: #0284C7;
            text-decoration: none;
        }
        .up3-change-link:hover { text-decoration: underline; }

        /* Banner Kode OTP (Bypass / Kemudahan Testing Tanpa Email) */
        .otp-demo-banner {
            background: #FFFBEB;
            border: 1.5px dashed #F59E0B;
            border-radius: 14px;
            padding: 13px 16px;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .otp-demo-text {
            font-size: 12px;
            color: #92400E;
            font-weight: 600;
        }
        .otp-demo-code {
            font-size: 19px;
            font-weight: 800;
            color: #B45309;
            letter-spacing: 2px;
            font-family: ui-monospace, monospace;
            background: #FFFFFF;
            padding: 3px 8px;
            border-radius: 6px;
            border: 1px solid #FDE68A;
            display: inline-block;
            margin-top: 4px;
        }
        .btn-use-otp {
            background: #F59E0B;
            color: #FFFFFF;
            border: none;
            padding: 7px 13px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all .15s ease;
            white-space: nowrap;
        }
        .btn-use-otp:hover {
            background: #D97706;
            transform: translateY(-1px);
        }

        .otp-boxes {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin: 18px 0 8px;
        }
        .otp-box {
            width: 50px;
            height: 58px;
            border-radius: 12px;
            border: 1.8px solid #CBD5E1;
            font-size: 22px;
            font-weight: 800;
            text-align: center;
            color: #023E8A;
            font-family: inherit;
            transition: all .15s ease;
            background: #FFFFFF;
        }
        .otp-box:focus {
            outline: none;
            border-color: #0081AB;
            box-shadow: 0 0 0 4px rgba(0, 129, 171, 0.15);
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
            margin-top: 24px;
        }
        .act-btn-submit:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(2, 62, 138, 0.32);
        }
        .act-btn-submit:disabled {
            opacity: .5;
            cursor: not-allowed;
            transform: none !important;
        }

        .resend-box {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #64748B;
        }
        .resend-btn {
            background: none;
            border: none;
            color: #0081AB;
            font-weight: 700;
            cursor: pointer;
            font-size: 13px;
            padding: 0;
            text-decoration: underline;
        }
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
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </div>
            <h1>Verifikasi Kode OTP</h1>
            <p>Masukkan 6 digit kode OTP aktivasi akun untuk<br><strong>{{ $user->email }}</strong></p>

            <div class="act-stepper">
                <div class="step-item done">
                    <span class="step-num">✓</span>
                    <span>Pilih UP3</span>
                </div>
                <div class="step-divider"></div>
                <div class="step-item active">
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
            @if (session('success'))
                <div style="background:rgba(46,158,91,.08); border:1px solid rgba(46,158,91,.2); color:#2E9E5B; padding:11px 14px; border-radius:10px; font-size:12.5px; margin-bottom:18px;">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div style="background:rgba(192,57,43,.08); border:1px solid rgba(192,57,43,.25); color:#C0392B; padding:12px 14px; border-radius:10px; font-size:12.5px; margin-bottom:18px;">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Info UP3 yang dipilih --}}
            <div class="up3-badge-box">
                <div class="up3-badge-left">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <span>{{ $user->up3 ?? 'Belum Ditentukan' }}</span>
                </div>
                <a href="{{ route('activation.show', $token) }}" class="up3-change-link">Ubah UP3</a>
            </div>

            {{-- Banner Kode OTP Otomatis / Praktis Tanpa Email --}}
            @if (session('demo_otp'))
                <div class="otp-demo-banner">
                    <div>
                        <div class="otp-demo-text">Kode OTP Aktivasi Anda:</div>
                        <span class="otp-demo-code" id="demoOtpCode">{{ session('demo_otp') }}</span>
                    </div>
                    <button type="button" class="btn-use-otp" onclick="isiOtomatis('{{ session('demo_otp') }}')">
                        Gunakan Kode
                    </button>
                </div>
            @endif

            <form method="POST" action="{{ route('activation.verify-otp', $token) }}" id="otpForm">
                @csrf
                <div style="text-align:center; font-size:12px; font-weight:700; color:#64748B; text-transform:uppercase; letter-spacing:.05em;">
                    Ketik 6 Digit Kode
                </div>

                <div class="otp-boxes" id="otpBoxes">
                    <input type="text" class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]*" autofocus>
                    <input type="text" class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]*">
                    <input type="text" class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]*">
                    <input type="text" class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]*">
                    <input type="text" class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]*">
                    <input type="text" class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]*">
                </div>
                <input type="hidden" name="otp" id="otpFull">

                <button type="submit" class="act-btn-submit" id="btnSubmitOtp" disabled>
                    Verifikasi OTP &amp; Lanjut
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="18" height="18" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </button>
            </form>

            <div class="resend-box">
                Tidak menerima kode?
                <form method="POST" action="{{ route('activation.resend-otp', $token) }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="resend-btn">Kirim Ulang Kode OTP</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const boxes = document.querySelectorAll('.otp-box');
        const hiddenInput = document.getElementById('otpFull');
        const submitBtn = document.getElementById('btnSubmitOtp');

        function updateFullOtp() {
            let full = '';
            boxes.forEach(b => full += b.value);
            hiddenInput.value = full;
            submitBtn.disabled = full.length !== 6;
            if (full.length === 6) {
                submitBtn.focus();
            }
        }

        boxes.forEach((box, i) => {
            box.addEventListener('input', (e) => {
                const val = e.target.value.replace(/[^0-9]/g, '');
                box.value = val ? val[0] : '';
                if (box.value && i < boxes.length - 1) {
                    boxes[i + 1].focus();
                }
                updateFullOtp();
            });

            box.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !box.value && i > 0) {
                    boxes[i - 1].focus();
                }
            });

            box.addEventListener('paste', (e) => {
                e.preventDefault();
                const paste = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');
                if (paste) {
                    for (let j = 0; j < boxes.length && j < paste.length; j++) {
                        boxes[j].value = paste[j];
                    }
                    const next = Math.min(paste.length, boxes.length - 1);
                    boxes[next].focus();
                    updateFullOtp();
                }
            });
        });

        function isiOtomatis(code) {
            const digits = code.toString().trim();
            for (let j = 0; j < boxes.length && j < digits.length; j++) {
                boxes[j].value = digits[j];
            }
            updateFullOtp();
        }
    </script>
</body>
</html>