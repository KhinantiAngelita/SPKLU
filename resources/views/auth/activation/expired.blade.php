<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Kedaluwarsa — Sistem SPKLU</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}?v=2">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-revolution-circle.png') }}?v=2">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}?v=2">
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
            padding: 44px 36px 40px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .act-icon {
            width: 72px; height: 72px; border-radius: 20px;
            background: rgba(232,163,23,.12);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 24px;
        }
        .act-icon svg { width: 36px; height: 36px; color: #E8A317; }

        h1 { font-size: 19px; font-weight: 800; color: #0f172a; margin-bottom: 12px; letter-spacing: -.01em; }
        p { font-size: 13.5px; color: #64748B; line-height: 1.65; margin-bottom: 6px; }
        .act-email {
            display: inline-block; margin-top: 12px; padding: 9px 18px;
            background: #F8FAFC; border: 1px solid #eef1f5; border-radius: 10px;
            font-size: 13px; font-weight: 700; color: #023E8A;
        }

        .act-btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 9px;
            margin-top: 28px; padding: 13px 30px; border-radius: 12px;
            font-size: 13.5px; font-weight: 700; text-decoration: none;
            background: linear-gradient(135deg, #023E8A, #0081AB);
            color: #fff;
            box-shadow: 0 5px 16px rgba(2,62,138,.28);
            transition: all .15s ease;
        }
        .act-btn:hover { transform: translateY(-1px); box-shadow: 0 7px 20px rgba(2,62,138,.35); }
    </style>
</head>
<body>
    <div class="act-card">
        <div class="act-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>

        <h1>Link Undangan Sudah Kedaluwarsa</h1>
        <p>Link aktivasi untuk akun berikut sudah tidak berlaku lagi (kedaluwarsa setelah 7 hari):</p>
        <span class="act-email">{{ $user->email }}</span>
        <p style="margin-top:18px;">Silakan hubungi Super Admin untuk mengirim ulang undangan aktivasi ke email Anda.</p>

        <a href="{{ route('login') }}" class="act-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><polyline points="12 19 5 12 12 5"/></svg>
            Kembali ke Halaman Login
        </a>
    </div>
</body>
</html>