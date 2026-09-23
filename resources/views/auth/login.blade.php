<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Sistem SPKLU</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-revolution-circle.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        * { box-sizing: border-box; }
        html, body {
            margin: 0; height: 100%;
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
        }

        .login-shell {
            width: 100%; min-height: 100vh; display: grid; grid-template-columns: 1.1fr 1fr;
            background: #fff;
        }

        /* ===== Panel kiri — branding, full height ===== */
        .login-brand {
            background: linear-gradient(160deg, #023E8A 0%, #034d9e 45%, #0081AB 100%);
            padding: 64px 72px; display: flex; flex-direction: column; justify-content: space-between;
            color: #fff; position: relative; overflow: hidden;
        }
        .login-brand::after {
            content: ''; position: absolute; inset: 0; pointer-events: none;
            background: radial-gradient(circle at 85% 15%, rgba(255,255,255,.12), transparent 45%),
                        radial-gradient(circle at 10% 90%, rgba(255,198,41,.14), transparent 40%);
        }
        .login-brand::before {
            content: ''; position: absolute; right: -120px; top: -120px; width: 420px; height: 420px;
            border-radius: 50%; border: 1px solid rgba(255,255,255,.08); pointer-events: none;
        }
        .login-brand-logo { display: flex; align-items: center; gap: 14px; position: relative; z-index: 1; }

        .login-brand-icon {
            width: 68px; height: 68px; border-radius: 50%;
            background: #fff;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
            box-shadow: 0 4px 14px rgba(0,0,0,.18);
            padding: 3px;
        }
        .login-brand-icon img { width: 100%; height: 100%; object-fit: contain; }
        .login-brand-text .title { font-weight: 800; font-size: 18px; margin: 0; line-height: 1.2; }
        .login-brand-text .subtitle { font-size: 12.5px; color: rgba(255,255,255,.7); margin: 3px 0 0; }

        .login-brand-hero { position: relative; z-index: 1; margin-top: auto; margin-bottom: 44px; max-width: 480px; }
        .login-brand-hero h1 { font-size: 54px; font-weight: 800; line-height: 1.1; margin: 0 0 18px; letter-spacing: -.02em; }
        .login-brand-hero h1 .accent { color: #FFC629; }
        .login-brand-hero p { font-size: 15px; color: rgba(255,255,255,.78); line-height: 1.7; margin: 0; max-width: 400px; }

        .login-brand-features { display: flex; flex-direction: column; gap: 15px; margin-top: 36px; position: relative; z-index: 1; }
        .login-brand-feature { display: flex; align-items: center; gap: 12px; font-size: 14px; color: rgba(255,255,255,.88); }
        .login-brand-feature-icon {
            width: 26px; height: 26px; border-radius: 8px; background: rgba(255,255,255,.12);
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .login-brand-feature-icon svg { width: 14px; height: 14px; color: #FFC629; stroke-width: 2.5; }

        .login-brand-footer { position: relative; z-index: 1; font-size: 12px; color: rgba(255,255,255,.55); margin-top: 56px; }

        /* ===== Panel kanan — form, full height ===== */
        .login-form-panel {
            padding: 64px 88px; display: flex; flex-direction: column; justify-content: center;
        }
        .login-form-inner { width: 100%; max-width: 380px; margin: 0 auto; }

        .login-form-header { margin-bottom: 36px; }
        .login-form-header h2 { font-size: 28px; font-weight: 800; color: #0f172a; margin: 0 0 8px; letter-spacing: -.015em; }
        .login-form-header p { font-size: 14px; color: #64748B; margin: 0; }

        .login-field { margin-bottom: 20px; }
        .login-field label { display: block; font-size: 12.5px; font-weight: 700; color: #334155; margin-bottom: 8px; letter-spacing: .01em; }
        .login-input-wrap { position: relative; }
        .login-input-wrap > svg {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            width: 16px; height: 16px; color: #94a3b8; stroke-width: 2; pointer-events: none;
            transition: color .15s ease;
        }
        .login-input-wrap input {
            width: 100%; padding: 14px 44px 14px 42px; border-radius: 11px; border: 1.5px solid #e2e8f0;
            font-size: 14px; font-family: inherit; transition: all .15s ease; background: #fff;
        }
        .login-input-wrap input:hover { border-color: #cbd5e1; }
        .login-input-wrap input:focus { outline: none; border-color: #F59E0B; box-shadow: inset 0 0 0 2px rgba(245,158,11,.25); }
        .login-input-wrap input:focus + .login-toggle-password,
        .login-input-wrap:has(input:focus) svg:first-child { color: #F59E0B; }

        .login-toggle-password {
            position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer; padding: 5px; color: #94a3b8;
            display: flex; align-items: center; justify-content: center;
            border-radius: 50%; transition: all .15s ease;
        }
        .login-toggle-password svg { width: 16px; height: 16px; stroke-width: 2; }
        .login-toggle-password:hover { background: rgba(245,158,11,.1); color: #F59E0B; }
        .login-toggle-password:active { background: rgba(245,158,11,.18); }

        .login-row { display: flex; align-items: center; justify-content: space-between; margin: 6px 0 26px; font-size: 13px; }
        .login-remember { display: flex; align-items: center; gap: 8px; color: #64748B; cursor: pointer; }
        .login-remember input { accent-color: #F59E0B; width: 15px; height: 15px; cursor: pointer; }
        .login-forgot { color: #F59E0B; text-decoration: none; font-weight: 700; }
        .login-forgot:hover { text-decoration: underline; }

        .login-submit-btn {
            width: 100%; padding: 14.5px; border: none; border-radius: 11px;
            background: linear-gradient(135deg, #F59E0B, #FFC629); color: #fff;
            font-size: 14.5px; font-weight: 700; letter-spacing: .01em; cursor: pointer; transition: all .18s ease;
            box-shadow: 0 4px 14px rgba(245,158,11,.32);
        }
        .login-submit-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(245,158,11,.4); }
        .login-submit-btn:active { transform: translateY(0); box-shadow: 0 3px 10px rgba(245,158,11,.3); }

        .login-divider { display: flex; align-items: center; gap: 12px; margin: 28px 0; }
        .login-divider::before, .login-divider::after { content: ''; flex: 1; height: 1px; background: #eef1f5; }
        .login-divider span { font-size: 11px; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; }

        .login-google-btn {
            width: 100%; padding: 13px; border-radius: 11px; border: 1.5px solid #e2e8f0; background: #fff;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            font-size: 13.8px; font-weight: 600; color: #1E293B; cursor: pointer; text-decoration: none;
            transition: all .15s ease;
        }
        .login-google-btn:hover { background: #f8fafc; border-color: #cbd5e1; transform: translateY(-1px); }
        .login-google-btn img { width: 17px; height: 17px; }

        .login-alert {
            display: flex; align-items: flex-start; gap: 9px; background: rgba(192,57,43,.08);
            border: 1px solid rgba(192,57,43,.25); color: #C0392B; border-radius: 10px;
            padding: 11px 14px; font-size: 13px; margin-bottom: 20px;
        }
        .login-alert svg { width: 15px; height: 15px; margin-top: 1px; flex-shrink: 0; stroke-width: 2; }

        @media (max-width: 900px) {
            .login-shell { grid-template-columns: 1fr; }
            .login-brand { display: none; }
            .login-form-panel { padding: 48px 28px; }
        }
    </style>
</head>
<body>

    <div class="login-shell">

        <div class="login-brand">
            <div class="login-brand-logo">
                <div class="login-brand-icon">
                    <img src="{{ asset('images/logo-revolution.png') }}" alt="Logo rEVolution">
                </div>
                <div class="login-brand-text">
                    <p class="title">Dashboard SPKLU</p>
                    <p class="subtitle">PLN UP3 Bogor</p>
                </div>
            </div>

            <div class="login-brand-hero">
                <h1>The SPKLU<br><span class="accent">Matchmaker</span></h1>
                <p>Metode pemasaran yang menawarkan konsep matchmaker antara pemilik lahan dengan pemilik mesin, dengan tujuan peningkatan penjualan kWh melalui kemitraan SPKLU.</p>

                <div class="login-brand-features">
                    <div class="login-brand-feature">
                        <div class="login-brand-feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        </div>
                        Dashboard real-time per role
                    </div>
                    <div class="login-brand-feature">
                        <div class="login-brand-feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        </div>
                        Analisis kelayakan lokasi otomatis
                    </div>
                    <div class="login-brand-feature">
                        <div class="login-brand-feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        </div>
                        Audit trail & kontrol akses berbasis role
                    </div>
                </div>
            </div>

            <div class="login-brand-footer">© {{ date('Y') }} PT PLN (Persero) UP3 Bogor</div>
        </div>

        <div class="login-form-panel">
            <div class="login-form-inner">
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

                    <div class="login-row">
                        <label class="login-remember">
                            <input type="checkbox" name="remember">
                            Ingat saya
                        </label>
                        <a href="#" class="login-forgot">Lupa password?</a>
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