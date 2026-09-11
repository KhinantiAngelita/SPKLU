<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Kedaluwarsa — Sistem SPKLU</title>
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
            padding: 40px 32px;
            text-align: center;
        }

        .act-icon {
            width: 68px; height: 68px; border-radius: 20px;
            background: rgba(232,163,23,.14);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 22px;
        }
        .act-icon svg { width: 34px; height: 34px; color: #E8A317; }

        h1 { font-size: 18px; font-weight: 800; color: #023E8A; margin-bottom: 10px; }
        p { font-size: 13.5px; color: #64748B; line-height: 1.6; margin-bottom: 6px; }
        .act-email {
            display: inline-block; margin-top: 10px; padding: 8px 16px;
            background: #F8FAFC; border: 1px solid #eef1f5; border-radius: 8px;
            font-size: 13px; font-weight: 700; color: #023E8A;
        }

        .act-btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            margin-top: 26px; padding: 12px 28px; border-radius: 10px;
            font-size: 13.5px; font-weight: 700; text-decoration: none;
            background: linear-gradient(135deg, #023E8A, #0081AB);
            color: #fff;
            box-shadow: 0 4px 14px rgba(2,62,138,.25);
            transition: all .15s ease;
        }
        .act-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(2,62,138,.32); }
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