<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih UP3 — Aktivasi Akun SPKLU</title>
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
            width: 520px;
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

        /* Stepper */
        .act-stepper {
            display: flex; justify-content: center; align-items: center; gap: 8px;
            margin: 20px auto 0; max-width: 380px;
        }
        .step-item {
            display: flex; align-items: center; gap: 6px; font-size: 11.5px; font-weight: 700; color: #94a3b8;
        }
        .step-item.active { color: #023E8A; }
        .step-item.done { color: #059669; }
        .step-num {
            width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 11px; background: #e2e8f0; color: #64748b;
        }
        .step-item.active .step-num { background: #023E8A; color: #fff; box-shadow: 0 0 0 3px rgba(2,62,138,.2); }
        .step-item.done .step-num { background: #059669; color: #fff; }
        .step-divider { width: 20px; height: 2px; background: #e2e8f0; }

        .act-body { padding: 32px 36px 36px; }

        .act-user-box {
            display: flex; align-items: center; gap: 14px;
            background: #f8fafc; border: 1px solid #e2e8f0;
            border-radius: 14px; padding: 14px 18px; margin-bottom: 24px;
        }
        .act-user-avatar {
            width: 44px; height: 44px; border-radius: 50%;
            background: linear-gradient(135deg, #023E8A, #0081AB);
            color: #fff; font-weight: 800; font-size: 16px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .act-user-info { flex: 1; min-width: 0; }
        .act-user-info .name { font-size: 14.5px; font-weight: 800; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .act-user-info .email { font-size: 12.5px; color: #64748B; margin-top: 2px; }
        .act-user-role {
            font-size: 11px; font-weight: 700; text-transform: uppercase;
            padding: 3px 9px; border-radius: 6px;
            background: rgba(0,129,171,.12); color: #0081AB;
        }

        .act-label {
            display: block; font-size: 12.5px; font-weight: 700;
            color: #334155; margin-bottom: 8px; text-transform: uppercase; letter-spacing: .04em;
        }

        .up3-select-wrap { position: relative; margin-bottom: 24px; }
        .up3-select-wrap svg {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            width: 18px; height: 18px; color: #0081AB; stroke-width: 2.2; pointer-events: none;
        }
        .up3-select {
            width: 100%; padding: 13px 16px 13px 44px;
            border-radius: 12px; border: 1.8px solid #cbd5e1;
            font-size: 14px; font-weight: 600; color: #0f172a;
            background: #fff; appearance: none; cursor: pointer;
            transition: all .15s ease;
        }
        .up3-select:focus {
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
            transition: all .15s ease;
        }
        .act-btn-submit:hover { transform: translateY(-1px); box-shadow: 0 8px 22px rgba(2,62,138,.38); }
        .act-btn-submit:active { transform: translateY(0); }

        .act-note {
            display: flex; gap: 10px; align-items: flex-start;
            background: rgba(0,129,171,.06); border: 1px solid rgba(0,129,171,.16);
            border-radius: 12px; padding: 12px 14px; margin-top: 24px;
            font-size: 12px; color: #0369a1; line-height: 1.55;
        }
        .act-note svg { width: 16px; height: 16px; min-width: 16px; margin-top: 1px; color: #0081AB; }

        .up3-chips-title { font-size: 11.5px; font-weight: 700; color: #64748B; margin-bottom: 8px; text-transform: uppercase; letter-spacing: .03em; }
        .up3-grid-hint {
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 6px; margin-bottom: 20px;
        }
        .up3-pill {
            background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 8px;
            padding: 6px 8px; font-size: 11px; font-weight: 600; color: #475569;
            text-align: center; cursor: pointer; transition: all .15s ease;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .up3-pill:hover { background: #e0f2fe; color: #0284c7; border-color: #7dd3fc; }
        .up3-pill.active { background: #023E8A; color: #fff; border-color: #023E8A; }
    </style>
</head>
<body>
    <div class="act-card">
        <div class="act-header">
            <div class="act-logo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
            </div>
            <h1>Aktivasi Akun SPKLU</h1>
            <p>Pilih Unit Pelaksana Pelayanan Pelanggan (UP3) wilayah kerja Anda sebelum melanjutkan aktivasi via OTP</p>

            <div class="act-stepper">
                <div class="step-item active">
                    <span class="step-num">1</span>
                    <span>Pilih UP3</span>
                </div>
                <div class="step-divider"></div>
                <div class="step-item">
                    <span class="step-num">2</span>
                    <span>Kode OTP</span>
                </div>
                <div class="step-divider"></div>
                <div class="step-item">
                    <span class="step-num">3</span>
                    <span>Password</span>
                </div>
                <div class="step-divider"></div>
                <div class="step-item">
                    <span class="step-num">4</span>
                    <span>Riwayat</span>
                </div>
            </div>
        </div>

        <div class="act-body">
            @if ($errors->any())
                <div style="background:rgba(192,57,43,.08); border:1px solid rgba(192,57,43,.25); color:#C0392B; padding:12px 14px; border-radius:10px; font-size:12.5px; margin-bottom:20px;">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Profil Singkat Pengguna --}}
            <div class="act-user-box">
                <div class="act-user-avatar">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="act-user-info">
                    <div class="name">{{ $user->name }}</div>
                    <div class="email">{{ $user->email }}</div>
                </div>
                <div class="act-user-role">
                    {{ str_replace('_', ' ', $user->role) }}
                </div>
            </div>

            <form method="POST" action="{{ route('activation.select-up3', $token) }}" id="formSelectUp3">
                @csrf

                <label class="act-label" for="select-up3-field">Pilih Unit UP3 Wilayah Kerja</label>
                <div class="up3-select-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <select name="up3" id="select-up3-field" class="up3-select" required onchange="syncPill(this.value)">
                        <option value="">— Klik untuk memilih salah satu dari 16 UP3 —</option>
                        @foreach ($daftarUp3 as $up3)
                            <option value="{{ $up3 }}" @selected(old('up3', $user->up3) === $up3)>
                                {{ $up3 }}
                            </option>
                        @endforeach
                    </select>
                    <span class="up3-select-arrow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="16" height="16" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </span>
                </div>

                {{-- Quick Pick Chips --}}
                <div class="up3-chips-title">Atau Pilih Cepat:</div>
                <div class="up3-grid-hint">
                    @foreach ($daftarUp3 as $up3)
                        <div class="up3-pill {{ old('up3', $user->up3) === $up3 ? 'active' : '' }}" onclick="pilihUp3('{{ $up3 }}')">
                            {{ str_replace('UP3 ', '', $up3) }}
                        </div>
                    @endforeach
                </div>

                <button type="submit" class="act-btn-submit">
                    Lanjut ke Verifikasi OTP
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="18" height="18" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </button>
            </form>

            <div class="act-note">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                <span>Aktivasi kini menggunakan <strong>Kode OTP</strong> langsung tanpa perlu tautan email tambahan. Riwayat lengkap aktivasi Anda akan dicatat dan ditampilkan di akhir proses.</span>
            </div>
        </div>
    </div>

    <script>
        function pilihUp3(nama) {
            const select = document.getElementById('select-up3-field');
            select.value = nama;
            syncPill(nama);
        }

        function syncPill(nama) {
            document.querySelectorAll('.up3-pill').forEach(pill => {
                const text = pill.innerText.trim();
                const cleanSelected = nama.replace('UP3 ', '').trim();
                if (text === cleanSelected) {
                    pill.classList.add('active');
                } else {
                    pill.classList.remove('active');
                }
            });
        }
    </script>
</body>
</html>
