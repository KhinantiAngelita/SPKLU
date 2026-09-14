<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Login — Sistem SPKLU</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(160deg, #023E8A 0%, #034d9e 45%, #0081AB 100%);
            padding: 24px;
            position: relative;
            overflow: hidden;
        }
        body::before {
            content: ''; position: absolute; inset: 0; pointer-events: none;
            background: radial-gradient(circle at 85% 10%, rgba(255,255,255,.10), transparent 45%),
                        radial-gradient(circle at 8% 92%, rgba(255,198,41,.14), transparent 40%);
        }

        .act-card {
            background: #fff;
            border-radius: 22px;
            width: 420px;
            max-width: 100%;
            box-shadow: 0 30px 70px rgba(1,26,64,.35);
            overflow: hidden;
            position: relative;
            z-index: 1;
        }

        .act-header {
            background: linear-gradient(150deg, rgba(2,62,138,.06), rgba(0,129,171,.10));
            padding: 40px 36px 26px;
            text-align: center;
            border-bottom: 1px solid #f1f5f9;
        }

        .act-logo {
            width: 56px; height: 56px; border-radius: 16px;
            background: linear-gradient(135deg, #023E8A, #0081AB);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 18px;
            box-shadow: 0 8px 20px rgba(2,62,138,.3);
        }
        .act-logo svg { width: 26px; height: 26px; color: #FFC629; stroke-width: 2.2; }

        .act-header h1 { font-size: 19px; font-weight: 800; color: #0f172a; margin-bottom: 6px; letter-spacing: -.01em; }
        .act-header p { font-size: 13px; color: #64748B; line-height: 1.6; }
        .act-header p strong { color: #023E8A; }

        .act-body { padding: 30px 36px 36px; }

        .act-alert {
            padding: 12px 14px; border-radius: 10px;
            font-size: 12.5px; margin-bottom: 20px;
            display: flex; align-items: flex-start; gap: 9px;
        }
        .act-alert-error { background: rgba(192,57,43,.08); color: #C0392B; border: 1px solid rgba(192,57,43,.2); }
        .act-alert-success { background: rgba(46,158,91,.08); color: #2E9E5B; border: 1px solid rgba(46,158,91,.2); }
        .act-alert svg { width: 16px; height: 16px; flex-shrink: 0; margin-top: 1px; }

        label {
            display: block; font-size: 12px; font-weight: 700;
            color: #94a3b8; margin-bottom: 14px; text-align: center;
            text-transform: uppercase; letter-spacing: .06em;
        }

        .otp-boxes { display: flex; gap: 9px; justify-content: center; margin-bottom: 8px; }
        .otp-box {
            width: 46px; height: 54px;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            font-size: 22px;
            font-weight: 800;
            text-align: center;
            color: #023E8A;
            font-family: inherit;
            transition: all .15s ease;
        }
        .otp-box:focus {
            outline: none; border-color: #0081AB;
            box-shadow: 0 0 0 4px rgba(0,129,171,.14);
        }

        .act-error-text {
            color: #C0392B; font-size: 12px; font-weight: 600;
            text-align: center; margin: 14px 0 4px;
        }

        .act-btn {
            display: flex; align-items: center; justify-content: center; gap: 10px;
            width: 100%; padding: 14px 20px; border-radius: 12px;
            font-size: 13.8px; font-weight: 700; cursor: pointer;
            border: none; transition: all .15s ease;
        }
        .act-btn-primary {
            background: linear-gradient(135deg, #023E8A, #0081AB);
            color: #fff;
            box-shadow: 0 5px 16px rgba(2,62,138,.28);
            margin-top: 22px;
        }
        .act-btn-primary:hover { transform: translateY(-1px); box-shadow: 0 7px 20px rgba(2,62,138,.35); }
        .act-btn-primary:disabled { opacity: .55; cursor: not-allowed; transform: none; box-shadow: none; }

        .act-resend {
            text-align: center; margin-top: 22px; font-size: 12.5px; color: #94a3b8;
        }
        .act-resend button {
            background: none; border: none; color: #0081AB;
            font-size: 12.5px; font-weight: 700; cursor: pointer; padding: 0;
        }
        .act-resend button:hover { text-decoration: underline; }

        .act-info-box {
            display: flex; gap: 9px; align-items: flex-start;
            background: rgba(0,129,171,.06); border: 1px solid rgba(0,129,171,.16);
            border-radius: 10px; padding: 11px 13px; margin-bottom: 22px;
            font-size: 12px; color: #0369a1; line-height: 1.55;
        }
        .act-info-box svg { width: 15px; height: 15px; min-width: 15px; margin-top: 1px; color: #0081AB; }
    </style>
</head>
<body>
    <div class="act-card">
        <div class="act-header">
            <div class="act-logo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <h1>Verifikasi Login</h1>
            <p>Ini pertama kali Anda login — demi keamanan, kode verifikasi<br>sudah dikirim ke <strong>{{ auth()->user()->email ?? $user->email ?? '' }}</strong></p>
        </div>

        <div class="act-body">
            @if (session('success'))
                <div class="act-alert act-alert-success">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="act-info-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                <span>Langkah ini cuma sekali di login pertama Anda. Login berikutnya cukup email &amp; password seperti biasa.</span>
            </div>

            <form method="POST" action="{{ route('login.first-otp.verify') }}" id="otpForm">
                @csrf
                <label>Kode Verifikasi</label>
                <div class="otp-boxes" id="otpBoxes">
                    <input type="text" class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]*" autofocus>
                    <input type="text" class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]*">
                    <input type="text" class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]*">
                    <input type="text" class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]*">
                    <input type="text" class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]*">
                    <input type="text" class="otp-box" maxlength="1" inputmode="numeric" pattern="[0-9]*">
                </div>
                <input type="hidden" name="otp" id="otpValue">

                @error('otp')
                    <p class="act-error-text">{{ $message }}</p>
                @enderror

                <button type="submit" class="act-btn act-btn-primary" id="otpSubmit" disabled>Verifikasi &amp; Lanjutkan</button>
            </form>

            <div class="act-resend">
                Tidak menerima kode?
                <form method="POST" action="{{ route('login.first-otp.resend') }}" style="display:inline;">
                    @csrf
                    <button type="submit">Kirim Ulang</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const boxes = Array.from(document.querySelectorAll('.otp-box'));
        const hidden = document.getElementById('otpValue');
        const submitBtn = document.getElementById('otpSubmit');

        function syncValue() {
            const val = boxes.map(b => b.value).join('');
            hidden.value = val;
            submitBtn.disabled = val.length !== 6;
        }

        boxes.forEach((box, i) => {
            box.addEventListener('input', () => {
                box.value = box.value.replace(/[^0-9]/g, '');
                if (box.value && i < boxes.length - 1) boxes[i + 1].focus();
                syncValue();
            });
            box.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !box.value && i > 0) boxes[i - 1].focus();
            });
            box.addEventListener('paste', (e) => {
                e.preventDefault();
                const digits = (e.clipboardData.getData('text').match(/[0-9]/g) || []).slice(0, 6);
                digits.forEach((d, idx) => { if (boxes[idx]) boxes[idx].value = d; });
                if (digits.length) boxes[Math.min(digits.length, boxes.length) - 1].focus();
                syncValue();
            });
        });
    </script>
</body>
</html>