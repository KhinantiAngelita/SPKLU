<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Password — Sistem SPKLU</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #023E8A 0%, #0081AB 100%);
            padding: 20px;
        }

        .act-card {
            background: #fff;
            border-radius: 20px;
            width: 420px;
            max-width: 100%;
            box-shadow: 0 24px 60px rgba(0,0,0,.25);
            overflow: hidden;
        }

        .act-header {
            background: linear-gradient(135deg, rgba(2,62,138,.08), rgba(0,129,171,.12));
            padding: 32px 32px 24px;
            text-align: center;
        }

        .act-logo {
            width: 56px; height: 56px; border-radius: 16px;
            background: linear-gradient(135deg, #023E8A, #0081AB);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
            box-shadow: 0 8px 20px rgba(2,62,138,.3);
        }
        .act-logo svg { width: 28px; height: 28px; color: #FFC629; }

        .act-header h1 { font-size: 18px; font-weight: 800; color: #023E8A; margin-bottom: 4px; }
        .act-header p { font-size: 13px; color: #64748B; }
        .act-header p strong { color: #023E8A; }

        .act-body { padding: 28px 32px 32px; }

        .act-alert {
            padding: 12px 14px; border-radius: 10px;
            font-size: 12.5px; margin-bottom: 18px;
            display: flex; align-items: flex-start; gap: 8px;
        }
        .act-alert-error { background: rgba(192,57,43,.08); color: #C0392B; border: 1px solid rgba(192,57,43,.2); }
        .act-alert svg { width: 16px; height: 16px; flex-shrink: 0; margin-top: 1px; }

        label {
            display: block; font-size: 12.5px; font-weight: 700;
            color: #475569; margin: 16px 0 6px;
        }
        label:first-of-type { margin-top: 0; }

        .field-wrap { position: relative; }

        input[type="password"], input[type="text"] {
            width: 100%;
            padding: 12px 42px 12px 14px;
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            font-size: 14px;
            color: #0f172a;
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
            margin-top: 6px;
        }

        .act-hint {
            font-size: 11.5px; color: #94a3b8; margin-top: 6px;
        }

        .pw-strength {
            display: flex; gap: 4px; margin-top: 8px;
        }
        .pw-strength-bar {
            height: 4px; flex: 1; border-radius: 999px; background: #eef1f5;
            transition: background .2s ease;
        }

        .act-btn {
            display: flex; align-items: center; justify-content: center; gap: 10px;
            width: 100%; padding: 13px 20px; border-radius: 10px;
            font-size: 13.8px; font-weight: 700; cursor: pointer;
            border: none; transition: all .15s ease;
        }
        .act-btn-primary {
            background: linear-gradient(135deg, #023E8A, #0081AB);
            color: #fff;
            box-shadow: 0 4px 14px rgba(2,62,138,.25);
            margin-top: 22px;
        }
        .act-btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(2,62,138,.32); }
    </style>
</head>
<body>
    <div class="act-card">
        <div class="act-header">
            <div class="act-logo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </div>
            <h1>Buat Password Baru</h1>
            <p>Langkah terakhir untuk <strong>{{ $user->email }}</strong></p>
        </div>

        <div class="act-body">
            @if ($errors->any() && !$errors->has('password'))
                <div class="act-alert act-alert-error">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('activation.store-password', $token) }}" id="setPasswordForm">
                @csrf

                <label for="password">Password Baru</label>
                <div class="field-wrap">
                    <input type="password" name="password" id="password" minlength="8" required autofocus>
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
                <p class="act-hint">Minimal 8 karakter. Kombinasikan huruf besar, angka, dan simbol untuk keamanan lebih baik.</p>

                <label for="password_confirmation">Konfirmasi Password</label>
                <div class="field-wrap">
                    <input type="password" name="password_confirmation" id="password_confirmation" minlength="8" required>
                    <button type="button" class="toggle-eye" onclick="togglePw('password_confirmation', this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>

                <button type="submit" class="act-btn act-btn-primary">Aktifkan Akun</button>
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
        const colors = ['#C0392B', '#E8A317', '#0081AB', '#2E9E5B'];

        pwInput.addEventListener('input', function () {
            const val = this.value;
            let score = 0;
            if (val.length >= 8) score++;
            if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            bars.forEach((bar, i) => {
                bar.style.background = i < score ? colors[score - 1] : '#eef1f5';
            });
        });
    </script>
</body>
</html> 