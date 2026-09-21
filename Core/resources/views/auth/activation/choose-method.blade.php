<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Metode Aktivasi — Sistem SPKLU</title>
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
        .act-header p { font-size: 13px; color: #64748B; line-height: 1.6; }
        .act-header p strong { color: #023E8A; }

        .act-body { padding: 30px 36px 36px; }

        .act-alert {
            padding: 12px 14px; border-radius: 10px;
            font-size: 12.5px; margin-bottom: 20px;
            display: flex; align-items: flex-start; gap: 9px;
        }
        .act-alert-error { background: rgba(192,57,43,.08); color: #C0392B; border: 1px solid rgba(192,57,43,.2); }
        .act-alert svg { width: 16px; height: 16px; flex-shrink: 0; margin-top: 1px; }

        .method-option {
            display: flex; align-items: center; gap: 14px;
            width: 100%; padding: 16px 18px; border-radius: 14px;
            border: 1.5px solid #e2e8f0; background: #fff;
            text-decoration: none; cursor: pointer;
            transition: all .15s ease; margin-bottom: 14px;
            font-family: inherit; font-size: inherit; color: inherit;
        }
        .method-option:last-child { margin-bottom: 0; }
        .method-option:hover { border-color: #0081AB; box-shadow: 0 4px 14px rgba(0,129,171,.12); transform: translateY(-1px); }

        /* Form pembungkus tombol OTP tidak boleh nambah spacing ekstra */
        form.method-option-wrap { margin: 0 0 14px; }
        form.method-option-wrap:last-child { margin-bottom: 0; }
        form.method-option-wrap .method-option { margin-bottom: 0; }

        .method-icon {
            width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
        }
        .method-icon.google { background: #F8FAFC; border: 1px solid #eef1f5; }
        .method-icon.google img { width: 22px; height: 22px; }
        .method-icon.otp { background: linear-gradient(135deg, #023E8A, #0081AB); }
        .method-icon.otp svg { width: 21px; height: 21px; color: #fff; stroke-width: 2.2; }

        .method-text { flex: 1; text-align: left; }
        .method-text .title { font-size: 14.5px; font-weight: 700; color: #0f172a; margin-bottom: 2px; }
        .method-text .desc { font-size: 12px; color: #94a3b8; line-height: 1.4; }

        .method-arrow { color: #cbd5e1; flex-shrink: 0; }
        .method-arrow svg { width: 18px; height: 18px; stroke-width: 2.2; }

        .act-divider { display: flex; align-items: center; gap: 12px; margin: 22px 0; }
        .act-divider::before, .act-divider::after { content: ''; flex: 1; height: 1px; background: #eef1f5; }
        .act-divider span { font-size: 11px; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; }

        .act-note {
            display: flex; gap: 9px; align-items: flex-start;
            background: rgba(0,129,171,.06); border: 1px solid rgba(0,129,171,.16);
            border-radius: 10px; padding: 11px 13px; margin-top: 22px;
            font-size: 12px; color: #0369a1; line-height: 1.55;
        }
        .act-note svg { width: 15px; height: 15px; min-width: 15px; margin-top: 1px; color: #0081AB; }
    </style>
</head>
<body>
    <div class="act-card">
        <div class="act-header">
            <div class="act-logo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
            </div>
            <h1>Pilih Metode Aktivasi</h1>
            <p>Halo <strong>{{ $user->name }}</strong>, pilih cara Anda ingin mengaktivasi akun<br><strong>{{ $user->email }}</strong></p>
        </div>

        <div class="act-body">
            @if (session('error'))
                <div class="act-alert act-alert-error">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Tombol Google — tetap <a>, karena route-nya GET dan ini murni
                 redirect ke halaman OAuth Google, bukan submit ke server sendiri. --}}
            <a href="{{ route('auth.google.redirect', ['token' => $token]) }}" class="method-option">
                <div class="method-icon google">
                    <img src="https://www.google.com/favicon.ico" alt="">
                </div>
                <div class="method-text">
                    <div class="title">Aktivasi dengan Google</div>
                    <div class="desc">Masuk otomatis pakai akun Google Anda</div>
                </div>
                <div class="method-arrow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                </div>
            </a>

            <div class="act-divider"><span>atau</span></div>

            {{-- Tombol OTP — WAJIB form POST, karena route 'activation.send-otp'
                 didefinisikan Route::post(), bukan Route::get(). --}}
            <form method="POST" action="{{ route('activation.send-otp', $token) }}" class="method-option-wrap">
                @csrf
                <button type="submit" class="method-option">
                    <div class="method-icon otp">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </div>
                    <div class="method-text">
                        <div class="title">Aktivasi dengan Kode OTP</div>
                        <div class="desc">Kami kirim kode verifikasi 6 digit ke email Anda</div>
                    </div>
                    <div class="method-arrow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                    </div>
                </button>
            </form>

            <div class="act-note">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                <span>Link undangan ini berlaku selama 7 hari sejak dikirim.</span>
            </div>
        </div>
    </div>
</body>
</html>