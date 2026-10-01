<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktivasi Akun — Sistem SPKLU</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(160deg, #023E8A 0%, #034d9e 45%, #0081AB 100%);
            padding: 32px 20px;
            position: relative;
            overflow-x: hidden;
        }
        body::before {
            content: ''; position: absolute; inset: 0; pointer-events: none;
            background: radial-gradient(circle at 85% 10%, rgba(255,255,255,.12), transparent 45%),
                        radial-gradient(circle at 8% 92%, rgba(255,198,41,.15), transparent 40%);
        }

        .act-card {
            background: #fff;
            border-radius: 24px;
            width: 500px;
            max-width: 100%;
            box-shadow: 0 30px 70px rgba(1,26,64,.35);
            overflow: hidden;
            position: relative;
            z-index: 1;
            border: 1px solid rgba(255,255,255,.3);
        }

        .act-header {
            background: linear-gradient(150deg, rgba(2,62,138,.07), rgba(0,129,171,.12));
            padding: 36px 36px 24px;
            text-align: center;
            border-bottom: 1px solid #f1f5f9;
        }

        .act-logo {
            width: 56px; height: 56px; border-radius: 16px;
            background: linear-gradient(135deg, #023E8A, #0081AB);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
            box-shadow: 0 8px 20px rgba(2,62,138,.3);
        }
        .act-logo svg { width: 28px; height: 28px; color: #FFC629; stroke-width: 2.2; }

        .act-header h1 { font-size: 21px; font-weight: 800; color: #0f172a; margin-bottom: 6px; letter-spacing: -.02em; }
        .act-header p { font-size: 13.5px; color: #64748B; line-height: 1.5; }

        .act-body { padding: 32px 36px 36px; }

        .field-group { margin-bottom: 20px; }
        .field-label { display: block; font-size: 12.5px; font-weight: 700; color: #334155; margin-bottom: 7px; text-transform: uppercase; letter-spacing: .04em; }
        .field-input-wrap { position: relative; }
        .field-input-wrap svg {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            width: 17px; height: 17px; color: #94a3b8; stroke-width: 2; pointer-events: none;
        }
        .field-input {
            width: 100%; padding: 13px 16px 13px 44px;
            border-radius: 12px; border: 1.8px solid #cbd5e1;
            font-size: 14px; color: #0f172a; font-family: inherit;
            transition: all .15s ease; background: #fff;
        }
        .field-input:focus {
            outline: none; border-color: #0081AB;
            box-shadow: 0 0 0 4px rgba(0,129,171,.15);
        }

        .up3-select-arrow {
            position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
            pointer-events: none; color: #94a3b8;
        }

        .act-btn-submit {
            width: 100%; padding: 14px 20px; border-radius: 12px;
            font-size: 14.5px; font-weight: 800; cursor: pointer;
            border: none; background: linear-gradient(135deg, #023E8A, #0081AB);
            color: #fff; box-shadow: 0 5px 18px rgba(2,62,138,.3);
            display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: all .15s ease; margin-top: 10px;
        }
        .act-btn-submit:hover { transform: translateY(-1px); box-shadow: 0 8px 22px rgba(2,62,138,.38); }

        .back-login-box { text-align: center; margin-top: 20px; font-size: 13px; color: #64748B; }
        .back-login-link { color: #0081AB; font-weight: 700; text-decoration: none; }
        .back-login-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="act-card">
        <div class="act-header">
            <div class="act-logo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
            </div>
            <h1>Aktivasi Akun Mandiri</h1>
            <p>Masukkan alamat email terdaftar dan pilih Unit UP3 Anda untuk verifikasi via Kode OTP</p>
        </div>

        <div class="act-body">
            @if ($errors->any())
                <div style="background:rgba(192,57,43,.08); border:1px solid rgba(192,57,43,.25); color:#C0392B; padding:12px 14px; border-radius:10px; font-size:12.5px; margin-bottom:20px;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('activation.request-otp-direct') }}">
                @csrf

                <div class="field-group">
                    <label class="field-label" for="email">Email Terdaftar</label>
                    <div class="field-input-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <input type="email" name="email" id="email" class="field-input" value="{{ old('email') }}" required placeholder="nama@email.com" autofocus>
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="up3">Pilih Unit UP3 Wilayah Kerja</label>
                    <div class="field-input-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <select name="up3" id="up3" class="field-input" required style="appearance:none; cursor:pointer;">
                            <option value="">— Pilih salah satu dari 16 UP3 —</option>
                            @foreach ($daftarUp3 as $u)
                                <option value="{{ $u }}" @selected(old('up3') === $u)>
                                    {{ $u }}
                                </option>
                            @endforeach
                        </select>
                        <span class="up3-select-arrow">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="16" height="16" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                        </span>
                    </div>
                </div>

                <button type="submit" class="act-btn-submit">
                    Kirim Kode OTP Aktivasi &rarr;
                </button>
            </form>

            <div class="back-login-box">
                Sudah memiliki akun aktif?
                <a href="{{ route('login') }}" class="back-login-link">Masuk di sini</a>
            </div>
        </div>
    </div>
</body>
</html>
