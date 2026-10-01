<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktivasi Berhasil — Riwayat Aktivasi Akun</title>
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

        .act-badge-success {
            width: 60px; height: 60px; border-radius: 20px;
            background: linear-gradient(135deg, #059669, #10b981);
            color: #fff; display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
            box-shadow: 0 8px 24px rgba(5,150,105,.35);
        }
        .act-badge-success svg { width: 32px; height: 32px; stroke-width: 2.5; }

        .act-header h1 { font-size: 22px; font-weight: 800; color: #0f172a; margin-bottom: 6px; letter-spacing: -.02em; }
        .act-header p { font-size: 13.5px; color: #64748B; }

        /* Stepper */
        .act-stepper {
            display: flex; justify-content: center; align-items: center; gap: 8px;
            margin: 20px auto 0; max-width: 380px;
        }
        .step-item {
            display: flex; align-items: center; gap: 6px; font-size: 11.5px; font-weight: 700; color: #059669;
        }
        .step-num {
            width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 11px; background: #059669; color: #fff;
        }
        .step-divider { width: 20px; height: 2px; background: #059669; }

        .act-body { padding: 32px 36px 36px; }

        /* Receipt Box */
        .receipt-card {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 24px;
        }
        .receipt-header {
            background: #fff;
            padding: 14px 20px;
            border-bottom: 1px solid #eef1f5;
            display: flex; justify-content: space-between; align-items: center;
        }
        .receipt-title { font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; color: #023E8A; display: flex; align-items: center; gap: 6px; }
        .receipt-title svg { width: 15px; height: 15px; }
        .receipt-status-pill {
            background: #dcfce7; color: #15803d; font-size: 11px; font-weight: 800;
            padding: 3px 10px; border-radius: 999px; text-transform: uppercase;
        }

        .receipt-table { width: 100%; border-collapse: collapse; }
        .receipt-table tr { border-bottom: 1px solid #f1f5f9; }
        .receipt-table tr:last-child { border-bottom: none; }
        .receipt-table td { padding: 12px 20px; font-size: 13px; vertical-align: middle; }
        .receipt-label { color: #64748B; font-weight: 600; width: 42%; }
        .receipt-value { color: #0f172a; font-weight: 700; text-align: right; }

        .receipt-up3-badge {
            background: #023E8A; color: #fff; font-size: 12px; font-weight: 800;
            padding: 4px 10px; border-radius: 6px; display: inline-block;
        }

        .receipt-role-badge {
            background: rgba(0,129,171,.12); color: #0081AB; font-size: 11.5px; font-weight: 700;
            padding: 3px 9px; border-radius: 6px; text-transform: uppercase; display: inline-block;
        }

        .act-actions { display: flex; flex-direction: column; gap: 10px; }

        .act-btn-dashboard {
            width: 100%; padding: 14px 20px; border-radius: 12px;
            font-size: 14.5px; font-weight: 800; cursor: pointer; text-decoration: none;
            background: linear-gradient(135deg, #023E8A, #0081AB);
            color: #fff; box-shadow: 0 5px 18px rgba(2,62,138,.3);
            display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: all .15s ease;
        }
        .act-btn-dashboard:hover { transform: translateY(-1px); box-shadow: 0 8px 22px rgba(2,62,138,.38); }

        .act-btn-print {
            width: 100%; padding: 12px 20px; border-radius: 12px;
            font-size: 13.5px; font-weight: 700; cursor: pointer;
            background: #fff; border: 1.5px solid #cbd5e1; color: #334155;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: all .15s ease;
        }
        .act-btn-print:hover { background: #f8fafc; border-color: #94a3b8; }

        @media print {
            body { background: #fff !important; padding: 0 !important; }
            body::before { display: none !important; }
            .act-card { box-shadow: none !important; border: 1px solid #ccc !important; width: 100% !important; margin: 0 auto; }
            .act-actions, .act-stepper { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="act-card">
        <div class="act-header">
            <div class="act-badge-success">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <h1>Akun Berhasil Diaktivasi!</h1>
            <p>Selamat, akun Anda telah aktif dan siap digunakan untuk masuk ke Sistem SPKLU.</p>

            <div class="act-stepper">
                <div class="step-item">
                    <span class="step-num">✓</span>
                    <span>Pilih UP3</span>
                </div>
                <div class="step-divider"></div>
                <div class="step-item">
                    <span class="step-num">✓</span>
                    <span>Kode OTP</span>
                </div>
                <div class="step-divider"></div>
                <div class="step-item">
                    <span class="step-num">✓</span>
                    <span>Password</span>
                </div>
                <div class="step-divider"></div>
                <div class="step-item">
                    <span class="step-num">✓</span>
                    <span>Selesai</span>
                </div>
            </div>
        </div>

        <div class="act-body">
            {{-- Kotak Bukti Riwayat Aktivasi --}}
            <div class="receipt-card">
                <div class="receipt-header">
                    <div class="receipt-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                        Bukti Riwayat Aktivasi
                    </div>
                    <span class="receipt-status-pill">● Aktif</span>
                </div>

                <table class="receipt-table">
                    <tr>
                        <td class="receipt-label">Waktu Aktivasi</td>
                        <td class="receipt-value">
                            {{ $riwayat ? $riwayat->diaktivasi_pada->translatedFormat('d M Y, H:i') . ' WIB' : now()->translatedFormat('d M Y, H:i') . ' WIB' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="receipt-label">Nama Pengguna</td>
                        <td class="receipt-value">{{ $user->name ?? $riwayat->nama ?? 'Pengguna' }}</td>
                    </tr>
                    <tr>
                        <td class="receipt-label">Alamat Email</td>
                        <td class="receipt-value" style="font-family:monospace; font-size:12.5px;">{{ $user->email ?? $riwayat->email ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="receipt-label">Unit UP3 Terpilih</td>
                        <td class="receipt-value">
                            <span class="receipt-up3-badge">
                                {{ $riwayat->up3 ?? $user->up3 ?? 'UP3 Terdaftar' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="receipt-label">Hak Akses (Role)</td>
                        <td class="receipt-value">
                            <span class="receipt-role-badge">
                                {{ str_replace('_', ' ', $riwayat->role ?? $user->role ?? 'Pengelola') }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="receipt-label">Metode Aktivasi</td>
                        <td class="receipt-value" style="color:#059669;">
                            {{ $riwayat->metode_aktivasi ?? 'Verifikasi Kode OTP' }}
                        </td>
                    </tr>
                </table>
            </div>

            <div class="act-actions">
                <a href="{{ route('dashboard') }}" class="act-btn-dashboard">
                    Masuk ke Dashboard SPKLU
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="18" height="18" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>

                <button type="button" class="act-btn-print" onclick="window.print()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" width="16" height="16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    Cetak / Simpan Bukti Aktivasi
                </button>
            </div>
        </div>
    </div>
</body>
</html>
