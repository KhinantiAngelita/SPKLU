<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Password — Aktivasi Akun SPKLU</title>
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
            padding: 20px;
            color: #0F172A;
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

        .field-group {
            margin-bottom: 18px;
        }
        .field-label {
            display: block;
            font-size: 12.5px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 7px;
        }
        .field-input-wrap {
            position: relative;
        }
        .field-input-wrap input {
            width: 100%;
            padding: 12px 44px 12px 14px;
            border-radius: 11px;
            border: 1.8px solid #CBD5E1;
            font-size: 14px;
            font-family: inherit;
            color: #0F172A;
            background: #FFFFFF;
            transition: all .15s ease;
        }
        .field-input-wrap input:focus {
            outline: none;
            border-color: #0081AB;
            box-shadow: 0 0 0 4px rgba(0, 129, 171, 0.15);
        }
        .btn-toggle-eye {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #94A3B8;
            padding: 4px;
        }
        .btn-toggle-eye:hover { color: #475569; }

        .pw-strength {
            display: flex;
            gap: 4px;
            margin-top: 8px;
        }
        .pw-strength-bar {
            height: 4px;
            flex: 1;
            border-radius: 999px;
            background: #E2E8F0;
            transition: all .2s ease;
        }

        .pw-checklist {
            list-style: none;
            margin-top: 10px;
            display: flex;
            flex-direction: column;
            gap: 5px;
            font-size: 12px;
            color: #94A3B8;
        }
        .pw-checklist li {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .pw-checklist li.met {
            color: #059669;
            font-weight: 600;
        }
        .pw-checklist li .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #CBD5E1;
        }
        .pw-checklist li.met .dot {
            background: #059669;
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
            margin-top: 24px;
        }
        .act-btn-submit:hover {
            background: #002D66;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(2, 62, 138, 0.32);
        }
        .act-btn-submit:active { transform: translateY(0); }
    </style>
</head>
<body>

    <div class="act-card">
        <div class="act-header">
            <div class="act-logo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </div>
            <h1>Buat Password Baru</h1>
            <p>Langkah terakhir untuk mengaktifkan akun<br><strong>{{ $user->email }}</strong></p>

            <div class="act-stepper">
                <div class="step-item done">
                    <span class="step-num">✓</span>
                    <span>Pilih UP3</span>
                </div>
                <div class="step-divider"></div>
                <div class="step-item done">
                    <span class="step-num">✓</span>
                    <span>Kode OTP</span>
                </div>
                <div class="step-divider"></div>
                <div class="step-item active">
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

            {{-- Info UP3 yang dipilih --}}
            <div class="up3-badge-box">
                <div class="up3-badge-left">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <span>Unit: <strong>{{ $user->up3 ?? 'UP3 Terdaftar' }}</strong></span>
                </div>
                <span style="font-size:11.5px; font-weight:700; color:#059669; background:#DCFCE7; padding:3px 9px; border-radius:6px; border:1px solid #BBF7D0;">OTP Terverifikasi</span>
            </div>

            <form method="POST" action="{{ route('activation.store-password', $token) }}">
                @csrf

                <div class="field-group">
                    <label class="field-label" for="password">Password Baru</label>
                    <div class="field-input-wrap">
                        <input type="password" name="password" id="password" required placeholder="Minimal 8 karakter" autofocus oninput="checkStrength(this.value)">
                        <button type="button" class="btn-toggle-eye" onclick="toggleView('password', 'eye1')">
                            <svg id="eye1" viewBox="0 0 24 24" fill="none" stroke="currentColor" width="18" height="18" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>

                    <div class="pw-strength">
                        <div class="pw-strength-bar" id="str1"></div>
                        <div class="pw-strength-bar" id="str2"></div>
                        <div class="pw-strength-bar" id="str3"></div>
                    </div>

                    <ul class="pw-checklist">
                        <li id="rule-len"><span class="dot"></span> Minimal 8 karakter</li>
                        <li id="rule-mix"><span class="dot"></span> Kombinasi huruf dan angka</li>
                    </ul>
                </div>

                <div class="field-group">
                    <label class="field-label" for="password_confirmation">Konfirmasi Password Baru</label>
                    <div class="field-input-wrap">
                        <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Ulangi password baru">
                        <button type="button" class="btn-toggle-eye" onclick="toggleView('password_confirmation', 'eye2')">
                            <svg id="eye2" viewBox="0 0 24 24" fill="none" stroke="currentColor" width="18" height="18" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="act-btn-submit">
                    Simpan &amp; Selesaikan Aktivasi
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="18" height="18" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                </button>
            </form>
        </div>
    </div>

    <script>
        function toggleView(fieldId, iconId) {
            const input = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            icon.innerHTML = isHidden
                ? '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>'
                : '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"/><circle cx="12" cy="12" r="3"/>';
        }

        function checkStrength(val) {
            const hasLen = val.length >= 8;
            const hasMix = /[A-Za-z]/.test(val) && /[0-9]/.test(val);

            document.getElementById('rule-len').className = hasLen ? 'met' : '';
            document.getElementById('rule-mix').className = hasMix ? 'met' : '';

            const s1 = document.getElementById('str1');
            const s2 = document.getElementById('str2');
            const s3 = document.getElementById('str3');

            s1.style.background = '#E2E8F0';
            s2.style.background = '#E2E8F0';
            s3.style.background = '#E2E8F0';

            if (!val) return;

            if (val.length < 6) {
                s1.style.background = '#EF4444';
            } else if (hasLen && hasMix) {
                s1.style.background = '#22C55E';
                s2.style.background = '#22C55E';
                s3.style.background = '#22C55E';
            } else {
                s1.style.background = '#F59E0B';
                s2.style.background = '#F59E0B';
            }
        }
    </script>
</body>
</html>