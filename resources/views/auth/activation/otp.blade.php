@component('mail::message')
@component('mail::header', ['url' => config('app.url')])
⚡ Sistem SPKLU
@endcomponent

# Kode Aktivasi Anda

Gunakan kode berikut untuk mengaktivasi akun Anda. Kode berlaku selama **10 menit**.

<div style="background:#F5F7FA; border-radius:14px; padding:24px; text-align:center; margin:24px 0;">
    <div style="font-size:36px; font-weight:800; letter-spacing:10px; color:#023E8A; font-family:'Inter',sans-serif;">
        {{ $code }}
    </div>
</div>

Kalau Anda tidak meminta kode ini, abaikan email ini.

Terima kasih,<br>
**Sistem SPKLU · PLN UP3 Bogor**
@endcomponent