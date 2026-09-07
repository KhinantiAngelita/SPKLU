<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Sistem SPKLU</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body style="display:flex; align-items:center; justify-content:center; height:100vh; background: linear-gradient(135deg, var(--brand-dark) 0%, var(--brand-mid) 100%);">

    <div class="modal-box" style="position:static; width:380px;">

        <div style="text-align:center; margin-bottom:24px;">
            <div class="sidebar-logo-icon" style="margin:0 auto 12px;">
                <span style="color:var(--brand-dark); font-weight:700;">⚡</span>
            </div>
            <h2 style="margin:0;">Sistem SPKLU</h2>
            <p style="color:var(--text-secondary); font-size:14px; margin:4px 0 0;">Masuk ke akun Anda</p>
        </div>

        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @error('email')
            <div class="alert alert-error">{{ $message }}</div>
        @enderror

        <form method="POST" action="{{ route('login.attempt') }}">
            @csrf

            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit" class="btn-primary" style="width:100%; margin-top:20px;">
                Masuk
            </button>
        </form>

        <div style="text-align:center; margin-top:16px;">
            <a href="{{ route('auth.google.redirect') }}" class="btn-outline" style="display:flex; align-items:center; justify-content:center; gap:8px;">
                <img src="https://www.google.com/favicon.ico" width="16" height="16" alt="">
                Masuk dengan Google
            </a>
        </div>

    </div>

</body>
</html>