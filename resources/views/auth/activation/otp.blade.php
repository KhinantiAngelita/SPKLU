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
            align-items: center;
            justify-content: center;
            background: linear-gradient(160deg, #023E8A 0%, #034d9e 45%, #0081AB 100%);
            padding: 32px 20px;
            position: relative;
            overflow-x: hidden;
        }
        body::before {
            content: ''; position: absolute; inset: 0; pointer-events: none;
            background: radial-gradient(circle at 85% 10%, rgba(255,255,255,.12), transparent 45%),
                        radial-gradient(circle at 8% 92%, rgba(255,198,41,.15), transparent 40%);
        }

        .act-card {
            background: #fff;
            border-radius: 24px;
            width: 480px;
            max-width: 100%;
            box-shadow: 0 30px 70px rgba(1,26,64,.35);
            overflow: hidden;
            position: relative;
            z-index: 1;
            border: 1px solid rgba(255,255,255,.3);
        }

        .act-header {
            background: linear-gradient(150deg, rgba(2,62,138,.07), rgba(0,129,171,.12));
            padding: 36px 36px 24px;
            text-align: center;
            border-bottom: 1px solid #f1f5f9;
        }

        .act-logo {
            width: 56px; height: 56px; border-radius: 16px;
            background: linear-gradient(135deg, #023E8A, #0081AB);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
            box-shadow: 0 8px 20px rgba(2,62,138,.3);
        }
        .act-logo svg { width: 28px; height: 28px; color: #FFC629; stroke-width: 2.2; }

        .act-header h1 { font-size: 21px; font-weight: 800; color: #0f172a; margin-bottom: 6px; letter-spacing: -.02em; }
        .act-header p { font-size: 13.5px; color: #64748B; line-height: 1.5; }

        /* Stepper */
        .act-stepper {
            display: flex; justify-content: center; align-items: center; gap: 8px;
            margin: 20px auto 0; max-width: 380px;
        }
        .step-item {
            display: flex; align-items: center; gap: 6px; font-size: 11.5px; font-weight: 700; color: #94a3b8;
        }
        .step-item.active { color: #023E8A; }
        .step-item.done { color: #059669; }
        .step-num {
            width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 11px; background: #e2e8f0; color: #64748b;
        }
        .step-item.active .step-num { background: #023E8A; color: #fff; box-shadow: 0 0 0 3px rgba(2,62,138,.2); }
        .step-item.done .step-num { background: #059669; color: #fff; }
        .step-divider { width: 20px; height: 2px; background: #e2e8f0; }

        .act-body { padding: 32px 36px 36px; }

        .up3-badge-box {
            display: flex; align-items: center; justify-content: space-between;
            background: #f8fafc; border: 1px solid #e2e8f0;
            border-radius: 12px; padding: 10px 16px; margin-bottom: 22px;
            font-size: 13px;
        }
        .up3-badge-left { display: flex; align-items: center; gap: 8px; font-weight: 700; color: #023E8A; }
        .up3-badge-left svg { width: 16px; height: 16px; color: #0081AB; }
        .up3-change-link { font-size: 12px; font-weight: 600; color: #0284c7; text-decoration: none; }
        .up3-change-link:hover { text-decoration: underline; }

        /* Banner Kode OTP (Bypass / Kemudahan Testing Tanpa Email) */
        .otp-demo-banner {
            background: linear-gradient(135deg, rgba(255, 198, 41, 0.15), rgba(245, 158, 11, 0.12));
            border: 1.5px dashed #f59e0b;
            border-radius: 14px; padding: 14px 18px; margin-bottom: 22px;
            display: flex; align-items: center; justify-content: space-between; gap: 12px;
        }
        .otp-demo-text { font-size: 12.5px; color: #92400e; }
        .otp-demo-code {
            font-size: 20px; font-weight: 800; color: #b45309; letter-spacing: 2px;
            font-family: ui-monospace, monospace; background: #fff; padding: 3px 8px; border-radius: 6px;
        }
        .btn-use-otp {
            background: #f59e0b; color: #fff; border: none; padding: 6px 12px; border-radius: 8px;
            font-size: 12px; font-weight: 700; cursor: pointer; transition: all .15s ease;
        }
        .btn-use-otp:hover { background: #d97706; transform: translateY(-1px); }

        .otp-boxes { display: flex; gap: 10px; justify-content: center; margin: 18px 0 8px; }
        .otp-box {
            width: 52px; height: 60px;
            border-radius: 14px;
            border: 1.8px solid #cbd5e1;
            font-size: 24px;
            font-weight: 800;
            text-align: center;
            color: #023E8A;
            font-family: inherit;
            transition: all .15s ease;
            background: #fff;
        }
        .otp-box:focus {
            outline: none; border-color: #0081AB;
            box-shadow: 0 0 0 4px rgba(0,129,171,.15);
        }

        .act-btn-submit {
            width: 100%; padding: 14px 20px; border-radius: 12px;
            font-size: 14.5px; font-weight: 800; cursor: pointer;
            border: none; background: linear-gradient(135deg, #023E8A, #0081AB);
            color: #fff; box-shadow: 0 5px 18px rgba(2,62,138,.3);
            display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: all .15s ease; margin-top: 24px;
        }
        .act-btn-submit:hover { transform: translateY(-1px); box-shadow: 0 8px 22px rgba(2,62,138,.38); }

        .resend-box {
            text-align: center; margin-top: 20px; font-size: 13px; color: #64748B;
        }
        .resend-btn {
            background: none; border: none; color: #0081AB; font-weight: 700;
            cursor: pointer; font-size: 13px; padding: 0; text-decoration: underline;
        }
    </style>
</head>
<body>
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