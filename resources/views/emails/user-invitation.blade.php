<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Undangan Bergabung — Sistem SPKLU</title>
</head>
<body style="margin: 0; padding: 0; background-color: #F1F5F9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; color: #1E293B;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #F1F5F9; padding: 36px 12px;">
        <tr>
            <td align="center">
                <!-- Main Container Card -->
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 580px; background-color: #FFFFFF; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.07); border: 1px solid #E2E8F0;">
                    
                    <!-- Header with SPKLU Brand -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #0A2540 0%, #0081AB 100%); padding: 32px 28px; text-align: center;">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center">
                                        <!-- SPKLU Machine Icon Badge -->
                                        <div style="display: inline-block; width: 48px; height: 48px; background: rgba(255, 255, 255, 0.16); border: 2px solid rgba(255, 255, 255, 0.35); border-radius: 14px; text-align: center; line-height: 48px; margin-bottom: 12px;">
                                            <span style="font-size: 22px; color: #FFFFFF; vertical-align: middle;">⚡</span>
                                        </div>
                                        <h1 style="margin: 0 0 4px; font-size: 21px; font-weight: 800; color: #FFFFFF; letter-spacing: 0.5px;">SISTEM MANAJEMEN SPKLU</h1>
                                        <p style="margin: 0; font-size: 11.5px; font-weight: 700; color: #BAE6FD; letter-spacing: 1.5px; text-transform: uppercase;">PT PLN (PERSERO) UP3 BOGOR</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 36px 32px;">
                            <!-- Invitation Badge -->
                            <div style="display: inline-block; padding: 4px 14px; background-color: #E0F2FE; color: #0284C7; font-size: 11.5px; font-weight: 800; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px;">
                                Undangan Akses Pengguna
                            </div>

                            <h2 style="margin: 0 0 12px; font-size: 20px; font-weight: 800; color: #0F172A;">
                                Halo, {{ $user->name }}
                            </h2>

                            <p style="margin: 0 0 22px; font-size: 14px; line-height: 1.6; color: #475569;">
                                Anda telah didaftarkan dan diundang untuk bergabung ke dalam <strong>Sistem Monitoring &amp; Perencanaan SPKLU PLN UP3 Bogor</strong>.
                            </p>

                            <!-- Account Details Box -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; margin-bottom: 26px;">
                                <tr>
                                    <td style="padding: 18px 20px;">
                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td style="font-size: 12px; color: #64748B; font-weight: 600; padding-bottom: 8px; width: 38%;">Email Terdaftar</td>
                                                <td style="font-size: 13px; color: #0F172A; font-weight: 700; padding-bottom: 8px;">{{ $user->email }}</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 12px; color: #64748B; font-weight: 600; padding-bottom: 8px;">Hak Akses (Role)</td>
                                                <td style="padding-bottom: 8px;">
                                                    <span style="display: inline-block; background-color: #0081AB; color: #FFFFFF; font-size: 11.5px; font-weight: 700; padding: 3px 10px; border-radius: 6px; text-transform: capitalize;">
                                                        {{ str_replace('_', ' ', $user->role) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 12px; color: #64748B; font-weight: 600;">Masa Berlaku</td>
                                                <td style="font-size: 12.5px; color: #0F172A; font-weight: 700;">7 Hari (hingga {{ now()->addDays(7)->translatedFormat('d F Y') }})</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 26px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $activationUrl }}" target="_blank" style="display: inline-block; background-color: #0081AB; color: #FFFFFF; font-size: 14.5px; font-weight: 700; text-decoration: none; padding: 14px 34px; border-radius: 10px; box-shadow: 0 4px 14px rgba(0, 129, 171, 0.35); text-align: center;">
                                            Aktivasi Akun Saya &rarr;
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Activation Methods Note -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 10px; margin-bottom: 22px;">
                                <tr>
                                    <td style="padding: 14px 16px; font-size: 12.5px; color: #1E40AF; line-height: 1.5;">
                                        <strong>💡 Metode Aktivasi Mudah:</strong><br>
                                        Setelah mengklik tombol di atas, Anda dapat memilih login instan menggunakan akun <strong>Google</strong> (jika menggunakan email yang sama) atau verifikasi via <strong>Kode OTP</strong> yang dikirim ke email.
                                    </td>
                                </tr>
                            </table>

                            <!-- Direct Link Fallback -->
                            <p style="margin: 0 0 6px; font-size: 12px; color: #64748B;">
                                Jika tombol di atas tidak dapat diklik, salin dan tempel tautan berikut ke peramban (browser) Anda:
                            </p>
                            <div style="background-color: #F1F5F9; border: 1px solid #CBD5E1; border-radius: 8px; padding: 10px 14px; font-size: 11px; color: #334155; word-break: break-all; font-family: Consolas, Monaco, monospace; line-height: 1.4;">
                                {{ $activationUrl }}
                            </div>

                            <p style="margin: 22px 0 0; font-size: 11.5px; color: #94A3B8; line-height: 1.5;">
                                Bila Anda merasa tidak pernah meminta pendaftaran akun atau tidak mengenal undangan ini, silakan abaikan pesan email ini dengan aman.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #F8FAFC; border-top: 1px solid #E2E8F0; padding: 22px 28px; text-align: center;">
                            <p style="margin: 0 0 4px; font-size: 12px; font-weight: 700; color: #334155;">
                                PT PLN (Persero) UP3 Bogor
                            </p>
                            <p style="margin: 0 0 8px; font-size: 11px; color: #94A3B8;">
                                Sistem Monitoring &amp; Perencanaan Infrastruktur SPKLU &bull; Jl. Pajajaran No. 23, Kota Bogor
                            </p>
                            <p style="margin: 0; font-size: 10.5px; color: #CBD5E1;">
                                Email ini dikirim secara otomatis oleh sistem. Harap tidak membalas pesan email ini.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>