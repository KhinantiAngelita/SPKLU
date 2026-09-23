<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Aktivasi Tidak Valid — Sistem SPKLU</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-revolution.png') }}">
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
            padding: 44px 36px 40px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .act-icon {
            width: 72px; height: 72px; border-radius: 20px;
            background: rgba(2,62,138,.08);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 22px;
        }
        .act-icon svg { width: 36px; height: 36px; color: #0081AB; }

        h1 { font-size: 20px; font-weight: 800; color: #0f172a; margin-bottom: 12px; letter-spacing: -.01em; }
        p { font-size: 13.5px; color: #64748B; line-height: 1.65; margin-bottom: 16px; }

        .act-reasons {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            padding: 16px;
            text-align: left;
            margin-bottom: 24px;
        }
        .act-reasons-title {
            font-size: 12.5px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .act-reasons ul {
            padding-left: 18px;
            font-size: 12.5px;
            color: #475569;
            line-height: 1.6;
        }
        .act-reasons li {
            margin-bottom: 4px;
        }

        .act-btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 9px;
            padding: 13px 28px; border-radius: 12px;
            font-size: 14px; font-weight: 700; text-decoration: none;
            background: linear-gradient(135deg, #023E8A, #0081AB);
            color: #fff;
            box-shadow: 0 5px 16px rgba(2,62,138,.28);
            transition: all .15s ease;
            width: 100%;
        }
        .act-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 22px rgba(2,62,138,.38);
        }
        .act-btn svg { width: 16px; height: 16px; }
    </style>
</head>
<body>
    <div class="act-card">
        <div class="act-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
        </div>

        <h1>Link Tidak Ditemukan</h1>
        <p>Tautan aktivasi akun ini sudah tidak berlaku atau tidak terdaftar di sistem.</p>

        <div class="act-reasons">
            <div class="act-reasons-title">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                Kemungkinan Penyebab:
            </div>
            <ul>
                <li><strong>Akun sudah aktif:</strong> Anda sebelumnya sudah menyelesaikan proses aktivasi. Silakan langsung login.</li>
                <li><strong>Email lama:</strong> Jika undangan pernah dikirim ulang, periksa email terbaru di kotak masuk Anda (pesan paling bawah di thread Gmail).</li>
                <li><strong>Link kedaluwarsa:</strong> Masa berlaku tautan undangan sudah habis.</li>
            </ul>
        </div>

        <a href="{{ route('login') }}" class="act-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                <polyline points="10 17 15 12 10 7"/>
                <line x1="15" y1="12" x2="3" y2="12"/>
            </svg>
            Menuju Halaman Login
        </a>
    </div>
</body>
</html>
