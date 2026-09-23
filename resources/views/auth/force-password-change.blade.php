<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password — Sistem SPKLU</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-revolution-circle.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
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
            width: 440px;
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
        .act-header p { font-size: 13px; color: #64748B; }
        .act-header p strong { color: #023E8A; }

        .act-body { padding: 30px 36px 36px; }

        .act-alert {
            padding: 12px 14px; border-radius: 10px;
            font-size: 12.5px; margin-bottom: 20px;
            display: flex; align-items: flex-start; gap: 9px;
        }
        .act-alert-error { background: rgba(192,57,43,.08); color: #C0392B; border: 1px solid rgba(192,57,43,.2); }
        .act-alert svg { width: 16px; height: 16px; flex-shrink: 0; margin-top: 1px; }

        .act-info-box {
            display: flex; gap: 9px; align-items: flex-start;
            background: rgba(232,163,23,.08); border: 1px solid rgba(232,163,23,.22);
            border-radius: 10px; padding: 11px 13px; margin-bottom: 22px;
            font-size: 12px; color: #92660f; line-height: 1.55;
        }
        .act-info-box svg { width: 15px; height: 15px; min-width: 15px; margin-top: 1px; color: #E8A317; }

        label {
            display: block; font-size: 12.5px; font-weight: 700;
            color: #475569; margin: 18px 0 7px;
        }
        label:first-of-type { margin-top: 0; }

        .field-wrap { position: relative; }

        input[type="password"], input[type="text"] {
            width: 100%;
            padding: 13px 42px 13px 14px;
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            font-size: 14px;
            font-family: inherit;
            color: #0f172a;
            transition: all .15s ease;
        }
        input[type="password"]:focus, input[type="text"]:focus {
            outline: none; border-color: #0081AB;
            box-shadow: 0 0 0 4px rgba(0,129,171,.12);
        }

        .toggle-eye {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer; color: #94a3b8;
            display: flex; align-items: center; padding: 4px;
        }
        .toggle-eye:hover { color: #64748B; }
        .toggle-eye svg { width: 18px; height: 18px; }

        .act-error-text {
            color: #C0392B; font-size: 11.5px; font-weight: 600;
            margin-top: 7px;
        }

        .pw-strength { display: flex; gap: 4px; margin-top: 10px; }
        .pw-strength-bar {
            height: 4px; flex: 1; border-radius: 999px; background: #eef1f5;
            transition: background .2s ease;
        }

        .pw-checklist {
            list-style: none; margin-top: 12px; display: flex; flex-direction: column; gap: 6px;
        }
        .pw-checklist li {
            display: flex; align-items: center; gap: 7px;
            font-size: 12px; color: #94a3b8; transition: color .15s ease;
        }
        .pw-checklist li .dot {
            width: 15px; height: 15px; border-radius: 50%; background: #eef1f5;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
            transition: all .15s ease;
        }
        .pw-checklist li .dot svg { width: 9px; height: 9px; color: #fff; opacity: 0; transition: opacity .15s ease; }
        .pw-checklist li.met { color: #2E9E5B; }
        .pw-checklist li.met .dot { background: #2E9E5B; }
        .pw-checklist li.met .dot svg { opacity: 1; }

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
            margin-top: 26px;
        }
        .act-btn-primary:hover { transform: translateY(-1px); box-shadow: 0 7px 20px rgba(2,62,138,.35); }
    </style>
</head>
<body>
    <div class="act-card">
        <div class="act-header">
            <div class="act-logo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </div>
            <h1>Wajib Ganti Password</h1>
            <p>Untuk keamanan akun <strong>{{ auth()->user()->email }}</strong></p>
        </div>

        <div class="act-body">
            @if ($errors->any() && !$errors->has('password'))
                <div class="act-alert act-alert-error">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <div class="act-info-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                <span>Password saat ini adalah password sementara. Buat password baru sebelum melanjutkan ke dashboard.</span>
            </div>

            <form method="POST" action="{{ route('password.force-change.update') }}" id="setPasswordForm">
                @csrf

                <label for="current_password">Password Saat Ini</label>
                <div class="field-wrap">
                    <input type="password" name="current_password" id="current_password" required>
                    <button type="button" class="toggle-eye" onclick="togglePw('current_password', this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
                @error('current_password')
                    <p class="act-error-text">{{ $message }}</p>
                @enderror

                <label for="password">Password Baru</label>
                <div class="field-wrap">
                    <input type="password" name="password" id="password" minlength="8" required>
                    <button type="button" class="toggle-eye" onclick="togglePw('password', this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
                @error('password')
                    <p class="act-error-text">{{ $message }}</p>
                @enderror

                <div class="pw-strength" id="pwStrength">
                    <div class="pw-strength-bar"></div>
                    <div class="pw-strength-bar"></div>
                    <div class="pw-strength-bar"></div>
                    <div class="pw-strength-bar"></div>
                </div>

                <ul class="pw-checklist" id="pwChecklist">
                    <li data-rule="length"><span class="dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span>Minimal 8 karakter</li>
                    <li data-rule="case"><span class="dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span>Huruf besar & kecil</li>
                    <li data-rule="number"><span class="dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span>Mengandung angka</li>
                    <li data-rule="symbol"><span class="dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span>Mengandung simbol</li>
                </ul>

                <label for="password_confirmation">Konfirmasi Password Baru</label>
                <div class="field-wrap">
                    <input type="password" name="password_confirmation" id="password_confirmation" minlength="8" required>
                    <button type="button" class="toggle-eye" onclick="togglePw('password_confirmation', this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>

                <button type="submit" class="act-btn act-btn-primary">Ganti Password &amp; Lanjutkan</button>
            </form>
        </div>
    </div>

    <script>
        function togglePw(id, btn) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }

        const pwInput = document.getElementById('password');
        const bars = document.querySelectorAll('.pw-strength-bar');
        const checklistItems = document.querySelectorAll('#pwChecklist li');
        const colors = ['#C0392B', '#E8A317', '#0081AB', '#2E9E5B'];

        pwInput.addEventListener('input', function () {
            const val = this.value;
            const rules = {
                length: val.length >= 8,
                case: /[A-Z]/.test(val) && /[a-z]/.test(val),
                number: /[0-9]/.test(val),
                symbol: /[^A-Za-z0-9]/.test(val)
            };

            let score = 0;
            checklistItems.forEach(item => {
                const met = rules[item.dataset.rule];
                item.classList.toggle('met', met);
                if (met) score++;
            });

            bars.forEach((bar, i) => {
                bar.style.background = i < score ? colors[score - 1] : '#eef1f5';
            });
        });
    </script>
</body>
</html>