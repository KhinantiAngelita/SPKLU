<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Sistem SPKLU</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center;
            background: #F6F8FA; font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; padding: 20px;
        }

        .login-shell {
            width: 100%; max-width: 920px; min-height: 560px; display: grid; grid-template-columns: 1fr 1fr;
            background: #fff; border-radius: 24px; overflow: hidden;
            box-shadow: 0 20px 60px rgba(2,62,138,.15), 0 4px 16px rgba(2,62,138,.08);
        }

        /* ===== Panel kiri — branding ===== */
        .login-brand {
            background: linear-gradient(160deg, #023E8A 0%, #034d9e 45%, #0081AB 100%);
            padding: 48px 44px; display: flex; flex-direction: column; justify-content: space-between;
            color: #fff; position: relative; overflow: hidden;
        }
        .login-brand::after {
            content: ''; position: absolute; inset: 0; pointer-events: none;
            background: radial-gradient(circle at 85% 15%, rgba(255,255,255,.1), transparent 45%),
                        radial-gradient(circle at 10% 90%, rgba(255,198,41,.12), transparent 40%);
        }
        .login-brand-logo { display: flex; align-items: center; gap: 12px; position: relative; z-index: 1; }
        .login-brand-icon {
            width: 44px; height: 44px; border-radius: 13px;
            background: linear-gradient(135deg, #FFC629, #ffab00);
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
            box-shadow: 0 4px 14px rgba(255,198,41,.4);
        }
        .login-brand-icon svg { width: 22px; height: 22px; color: #023E8A; stroke-width: 2.2; }
        .login-brand-text .title { font-weight: 800; font-size: 16px; margin: 0; line-height: 1.2; }
        .login-brand-text .subtitle { font-size: 11.5px; color: rgba(255,255,255,.7); margin: 2px 0 0; }

        .login-brand-hero { position: relative; z-index: 1; margin-top: auto; }
        .login-brand-hero h1 { font-size: 26px; font-weight: 800; line-height: 1.3; margin: 0 0 12px; letter-spacing: -.01em; }
        .login-brand-hero p { font-size: 13.5px; color: rgba(255,255,255,.75); line-height: 1.6; margin: 0; max-width: 320px; }

        .login-brand-features { display: flex; flex-direction: column; gap: 12px; margin-top: 28px; position: relative; z-index: 1; }
        .login-brand-feature { display: flex; align-items: center; gap: 10px; font-size: 12.8px; color: rgba(255,255,255,.85); }
        .login-brand-feature svg { width: 15px; height: 15px; color: #FFC629; stroke-width: 2.5; flex-shrink: 0; }

        /* ===== Panel kanan — form ===== */
        .login-form-panel { padding: 52px 48px; display: flex; flex-direction: column; justify-content: center; }
        .login-form-header { margin-bottom: 28px; }
        .login-form-header h2 { font-size: 22px; font-weight: 800; color: #0f172a; margin: 0 0 6px; letter-spacing: -.01em; }
        .login-form-header p { font-size: 13.5px; color: #64748B; margin: 0; }

        .login-field { margin-bottom: 16px; }
        .login-field label { display: block; font-size: 12.5px; font-weight: 700; color: #475569; margin-bottom: 6px; }
        .login-input-wrap { position: relative; }
        .login-input-wrap svg {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            width: 16px; height: 16px; color: #94a3b8; stroke-width: 2; pointer-events: none;
        }
        .login-input-wrap input {
            width: 100%; padding: 12px 14px 12px 40px; border-radius: 10px; border: 1px solid #e2e8f0;
            font-size: 13.8px; font-family: inherit; transition: all .15s ease; background: #fff;
        }
        .login-input-wrap input:focus { outline: none; border-color: #0081AB; box-shadow: 0 0 0 3px rgba(0,129,171,.12); }

        .login-toggle-password {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer; padding: 4px; color: #94a3b8; display: flex;
        }
        .login-toggle-password svg { width: 16px; height: 16px; stroke-width: 2; }
        .login-toggle-password:hover { color: #64748B; }

        .login-submit-btn {
            width: 100%; padding: 12.5px; border: none; border-radius: 10px; margin-top: 8px;
            background: linear-gradient(135deg, #023E8A, #0081AB); color: #fff;
            font-size: 14px; font-weight: 700; cursor: pointer; transition: all .15s ease;
            box-shadow: 0 3px 12px rgba(2,62,138,.25);
        }
        .login-submit-btn:hover { transform: translateY(-1px); box-shadow: 0 5px 16px rgba(2,62,138,.32); }

        .login-divider { display: flex; align-items: center; gap: 12px; margin: 22px 0; }
        .login-divider::before, .login-divider::after { content: ''; flex: 1; height: 1px; background: #eef1f5; }
        .login-divider span { font-size: 11.5px; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; }

        .login-google-btn {
            width: 100%; padding: 11.5px; border-radius: 10px; border: 1px solid #e2e8f0; background: #fff;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            font-size: 13.5px; font-weight: 600; color: #1E293B; cursor: pointer; text-decoration: none;
            transition: all .15s ease;
        }
        .login-google-btn:hover { background: #f8fafc; border-color: #cbd5e1; }
        .login-google-btn img { width: 17px; height: 17px; }

        .login-alert {
            display: flex; align-items: flex-start; gap: 9px; background: rgba(192,57,43,.08);
            border: 1px solid rgba(192,57,43,.25); color: #C0392B; border-radius: 10px;
            padding: 11px 14px; font-size: 13px; margin-bottom: 18px;
        }
        .login-alert svg { width: 15px; height: 15px; margin-top: 1px; flex-shrink: 0; stroke-width: 2; }

        @media (max-width: 760px) {
            .login-shell { grid-template-columns: 1fr; max-width: 420px; }
            .login-brand { display: none; }
            .login-form-panel { padding: 40px 28px; }
        }
    </style>
</head>
<body>

    <div class="login-shell">

        <div class="login-brand">
            <div class="login-brand-logo">
                <div class="login-brand-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                </div>
                <div class="login-brand-text">
                    <p class="title">Sistem SPKLU</p>
                    <p class="subtitle">PLN UP3 Bogor</p>
                </div>
            </div>

            <div class="login-brand-hero">
                <h1>Kelola & pantau jaringan SPKLU dalam satu sistem terpadu</h1>
                <p>Dari monitoring probabilitas lokasi, studi kelayakan, sampai integrasi ke Master SPKLU — semua tercatat dan terstruktur.</p>

                <div class="login-brand-features">
                    <div class="login-brand-feature">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        Dashboard real-time per role
                    </div>
                    <div class="login-brand-feature">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        Analisis kelayakan lokasi otomatis
                    </div>
                    <div class="login-brand-feature">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        Audit trail & kontrol akses berbasis role
                    </div>
                </div>
            </div>
        </div>

        <div class="login-form-panel">
            <div class="login-form-header">
                <h2>Selamat Datang</h2>
                <p>Masuk ke akun Anda untuk melanjutkan</p>
            </div>

            @if (session('error'))
                <div class="login-alert">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @error('email')
                <div class="login-alert">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>{{ $message }}</span>
                </div>
            @enderror

            <form method="POST" action="{{ route('login.attempt') }}">
                @csrf

                <div class="login-field">
                    <label>Email</label>
                    <div class="login-input-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
                    </div>
                </div>

                <div class="login-field">
                    <label>Password</label>
                    <div class="login-input-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <input type="password" name="password" id="login-password" placeholder="••••••••" required>
                        <button type="button" class="login-toggle-password" onclick="togglePassword()">
                            <svg id="icon-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="login-submit-btn">Masuk</button>
            </form>

            <div class="login-divider"><span>atau</span></div>

            <a href="{{ route('auth.google.redirect') }}" class="login-google-btn">
                <img src="https://www.google.com/favicon.ico" alt="">
                Masuk dengan Google
            </a>
        </div>

    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('login-password');
            const icon = document.getElementById('icon-eye');
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            icon.innerHTML = isHidden
                ? '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>'
                : '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"/><circle cx="12" cy="12" r="3"/>';
        }
    </script>

</body>
</html>