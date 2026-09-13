<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktivasi Akun — Sistem SPKLU</title>
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
            width: 440px;
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

        .act-body { padding: 28px 32px 32px; }

        .act-user-box {
            background: #F8FAFC;
            border: 1px solid #eef1f5;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .act-avatar {
            width: 42px; height: 42px; border-radius: 50%;
            background: linear-gradient(135deg, #FFC629, #ffab00);
            color: #023E8A;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 800; flex-shrink: 0;
        }
        .act-user-info h3 { font-size: 14px; font-weight: 700; color: #0f172a; }
        .act-user-info p { font-size: 12.5px; color: #64748B; margin-top: 2px; }
        .act-role-badge {
            display: inline-block; margin-top: 6px;
            padding: 3px 10px; border-radius: 999px;
            font-size: 10.5px; font-weight: 700;
            background: rgba(2,62,138,.12); color: #023E8A;
        }

        .act-alert {
            padding: 12px 14px; border-radius: 10px;
            font-size: 12.5px; margin-bottom: 18px;
            display: flex; align-items: flex-start; gap: 8px;
        }
        .act-alert-error { background: rgba(192,57,43,.08); color: #C0392B; border: 1px solid rgba(192,57,43,.2); }
        .act-alert-success { background: rgba(46,158,91,.08); color: #2E9E5B; border: 1px solid rgba(46,158,91,.2); }
        .act-alert svg { width: 16px; height: 16px; flex-shrink: 0; margin-top: 1px; }

        .act-option-title {
            font-size: 12px; font-weight: 700; color: #94a3b8;
            text-transform: uppercase; letter-spacing: .05em;
            margin-bottom: 12px;
        }

        .act-btn {
            display: flex; align-items: center; justify-content: center; gap: 10px;
            width: 100%; padding: 13px 20px; border-radius: 10px;
            font-size: 13.8px; font-weight: 700; cursor: pointer;
            border: none; text-decoration: none; transition: all .15s ease;
            margin-bottom: 12px;
        }
        .act-btn svg { width: 18px; height: 18px; flex-shrink: 0; }

        .act-btn-google {
            background: #fff; color: #1E293B; border: 1.5px solid #e2e8f0;
        }
        .act-btn-google:hover { border-color: #cbd5e1; background: #f8fafc; }

        .act-btn-otp {
            background: linear-gradient(135deg, #023E8A, #0081AB);
            color: #fff;
            box-shadow: 0 4px 14px rgba(2,62,138,.25);
        }
        .act-btn-otp:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(2,62,138,.32); }

        .act-divider {
            display: flex; align-items: center; gap: 12px;
            margin: 20px 0; color: #cbd5e1; font-size: 11.5px; font-weight: 600;
        }
        .act-divider::before, .act-divider::after {
            content: ''; flex: 1; height: 1px; background: #eef1f5;
        }

        .act-footer {
            text-align: center; margin-top: 24px;
            font-size: 11.5px; color: #94a3b8;
        }
        .act-footer strong { color: #64748B; }
    </style>
</head>
<body>
    <div class="act-card">
        <div class="act-header">
            <div class="act-logo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/></svg>
            </div>
            <h1>Aktivasi Akun</h1>
            <p>Sistem Manajemen SPKLU</p>
        </div>

        <div class="act-body">
            @if (session('error'))
                <div class="act-alert act-alert-error">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if (session('success'))
                <div class="act-alert act-alert-success">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="act-user-box">
                <div class="act-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                <div class="act-user-info">
                    <h3>{{ $user->name }}</h3>
                    <p>{{ $user->email }}</p>
                    <span class="act-role-badge">{{ ucwords(str_replace('_', ' ', $user->role)) }}</span>
                </div>
            </div>

            <p class="act-option-title">Pilih Metode Aktivasi</p>

            <a href="{{ route('auth.google.redirect', $token) }}" class="act-btn act-btn-google">
                <svg viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                Lanjutkan dengan Google
            </a>

            <div class="act-divider">ATAU</div>

            <form method="POST" action="{{ route('activation.send-otp', $token) }}">
                @csrf
                <button type="submit" class="act-btn act-btn-otp">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><polyline points="3 7 12 13 21 7"/></svg>
                    Verifikasi via Kode Email (OTP)
                </button>
            </form>

            <p class="act-footer">
                Link ini berlaku hingga
                <strong>{{ $user->invitation_expires_at?->translatedFormat('d F Y, H:i') }}</strong>.
                Kalau merasa tidak diundang, abaikan halaman ini.
            </p>
        </div>
    </div>
</body>
</html>