<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Riwayat Aktivasi Akun</title>
</head>
<body style="margin: 0; padding: 0; background-color: #F1F5F9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1E293B;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #F1F5F9; padding: 36px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 580px; background-color: #FFFFFF; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.07); border: 1px solid #E2E8F0;">
                    
                    <tr>
                        <td style="background: linear-gradient(135deg, #023E8A 0%, #0081AB 100%); padding: 32px 28px; text-align: center;">
                            <div style="display: inline-block; width: 48px; height: 48px; background: rgba(255, 255, 255, 0.16); border: 2px solid rgba(255, 255, 255, 0.35); border-radius: 14px; text-align: center; line-height: 48px; margin-bottom: 12px;">
                                <span style="font-size: 24px; color: #FFC629; vertical-align: middle;">✓</span>
                            </div>
                            <h1 style="margin: 0 0 4px; font-size: 21px; font-weight: 800; color: #FFFFFF; letter-spacing: 0.5px;">SISTEM MONITORING SPKLU</h1>
                            <p style="margin: 0; font-size: 12px; font-weight: 700; color: #BAE6FD; letter-spacing: 1.5px; text-transform: uppercase;">PT PLN (PERSERO) — {{ strtoupper($namaUp3) }}</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 36px 32px;">
                            <div style="display: inline-block; padding: 4px 14px; background-color: #DCFCE7; color: #15803D; font-size: 11.5px; font-weight: 800; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px;">
                                ● Akun Berhasil Diaktivasi
                            </div>

                            <h2 style="margin: 0 0 10px; font-size: 20px; font-weight: 800; color: #0F172A;">
                                Halo, {{ $user->name }}
                            </h2>

                            <p style="margin: 0 0 22px; font-size: 14px; line-height: 1.6; color: #475569;">
                                Selamat! Akun Anda telah berhasil diaktivasi pada <strong>Sistem SPKLU</strong>. Berikut adalah rincian riwayat aktivasi Anda:
                            </p>

                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; margin-bottom: 24px;">
                                <tr>
                                    <td style="padding: 18px 20px;">
                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td style="font-size: 12px; color: #64748B; font-weight: 600; padding-bottom: 10px; width: 38%;">Waktu Aktivasi</td>
                                                <td style="font-size: 13px; color: #0F172A; font-weight: 700; padding-bottom: 10px;">{{ $riwayat->diaktivasi_pada->translatedFormat('l, d F Y, H:i') }} WIB</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 12px; color: #64748B; font-weight: 600; padding-bottom: 10px;">Unit UP3</td>
                                                <td style="font-size: 13px; color: #023E8A; font-weight: 800; padding-bottom: 10px;">{{ $riwayat->up3 }}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 12px; color: #64748B; font-weight: 600; padding-bottom: 10px;">Hak Akses (Role)</td>
                                                <td style="padding-bottom: 10px;">
                                                    <span style="display: inline-block; background-color: #0081AB; color: #FFFFFF; font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 6px; text-transform: capitalize;">
                                                        {{ str_replace('_', ' ', $riwayat->role) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 12px; color: #64748B; font-weight: 600; padding-bottom: 10px;">Email Pengguna</td>
                                                <td style="font-size: 13px; color: #0F172A; font-weight: 600; padding-bottom: 10px;">{{ $riwayat->email }}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 12px; color: #64748B; font-weight: 600;">Metode Aktivasi</td>
                                                <td style="font-size: 12.5px; color: #0F172A; font-weight: 600;">{{ $riwayat->metode_aktivasi }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 24px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('dashboard') }}" target="_blank" style="display: inline-block; background: linear-gradient(135deg, #023E8A, #0081AB); color: #FFFFFF; font-size: 14px; font-weight: 700; text-decoration: none; padding: 13px 32px; border-radius: 10px; box-shadow: 0 4px 14px rgba(2, 62, 138, 0.35); text-align: center;">
                                            Buka Dashboard SPKLU &rarr;
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0; font-size: 12px; color: #94A3B8; text-align: center;">
                                Simpan email ini sebagai arsip resmi riwayat aktivasi akun Anda.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
