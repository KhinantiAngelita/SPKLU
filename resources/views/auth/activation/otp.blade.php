<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP — Sistem SPKLU</title>
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
        .act-alert-success { background: rgba(46,158,91,.08); color: #2E9E5B; border: 1px solid rgba(46,158,91,.2); }
        .act-alert svg { width: 16px; height: 16px; flex-shrink: 0; margin-top: 1px; }

        label {
            display: block; font-size: 12.5px; font-weight: 700;
            color: #475569; margin-bottom: 8px; text-align: center;
        }

        .otp-input {
            width: 100%;
            padding: 16px;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            font-size: 28px;
            font-weight: 800;
            text-align: center;
            letter-spacing: 12px;
            color: #023E8A;
            margin-bottom: 8px;
        }
        .otp-input:focus {
            outline: none; border-color: #0081AB;
            box-shadow: 0 0 0 4px rgba(0,129,171,.12);
        }

        .act-error-text {
            color: #C0392B; font-size: 12px; font-weight: 600;
            text-align: center; margin-bottom: 16px;
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
            margin-top: 8px;
        }
        .act-btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(2,62,138,.32); }

        .act-resend {
            text-align: center; margin-top: 20px; font-size: 12.5px; color: #94a3b8;
        }
        .act-resend button {
            background: none; border: none; color: #0081AB;
            font-size: 12.5px; font-weight: 700; cursor: pointer; padding: 0;
        }
        .act-resend button:hover { text-decoration: underline; }

        .act-back {
            display: block; text-align: center; margin-top: 16px;
            font-size: 12px; color: #94a3b8; text-decoration: none;
        }
        .act-back:hover { color: #64748B; }
    </style>
</head>
<body>
    <div class="act-card">
        <div class="act-header">
            <div class="act-logo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><polyline points="3 7 12 13 21 7"/></svg>
            </div>
            <h1>Masukkan Kode OTP</h1>
            <p>Kode 6 digit sudah dikirim ke<br><strong>{{ $user->email }}</strong></p>
        </div>

        <div class="act-body">
            @if (session('success'))
                <div class="act-alert act-alert-success">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('activation.verify-otp', $token) }}">
                @csrf
                <label for="otp">Kode Verifikasi</label>
                <input
                    type="text"
                    name="otp"
                    id="otp"
                    class="otp-input"
                    maxlength="6"
                    inputmode="numeric"
                    pattern="[0-9]*"
                    autocomplete="one-time-code"
                    placeholder="------"
                    autofocus
                    required
                >
                @error('otp')
                    <p class="act-error-text">{{ $message }}</p>
                @enderror

                <button type="submit" class="act-btn act-btn-primary">Verifikasi Kode</button>
            </form>

            <div class="act-resend">
                Tidak menerima kode?
                <form method="POST" action="{{ route('activation.send-otp', $token) }}" style="display:inline;">
                    @csrf
                    <button type="submit">Kirim Ulang</button>
                </form>
            </div>

            <a href="{{ route('activation.show', $token) }}" class="act-back">&larr; Kembali ke pilihan metode aktivasi</a>
        </div>
    </div>
</body>
</html>