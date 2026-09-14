@component('mail::message')
@component('mail::header', ['url' => config('app.url')])
⚡ Sistem SPKLU
@endcomponent

# Halo, {{ $user->name }}

Anda diundang untuk bergabung ke **Sistem Manajemen SPKLU** sebagai **{{ ucfirst(str_replace('_', ' ', $user->role)) }}**.

@component('mail::button', ['url' => $activationUrl, 'color' => 'primary'])
Aktivasi Akun Saya
@endcomponent

Link ini berlaku selama 7 hari. Setelah klik, Anda bisa pilih mengaktivasi akun lewat Google atau lewat kode verifikasi yang dikirim ke email ini.

Kalau Anda tidak merasa diundang, abaikan email ini.

Terima kasih,<br>
**Sistem SPKLU · PLN UP3 Bogor**
@endcomponent