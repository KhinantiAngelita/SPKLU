<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\AuditLogHelper;
use App\Helpers\NotifikasiHelper;
use App\Http\Controllers\Controller;
use App\Mail\ActivationSuccessMail;
use App\Models\RiwayatAktivasi;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ActivationController extends Controller
{
    public function __construct(protected OtpService $otpService) {}

    /**
     * Halaman aktivasi mandiri (akses langsung dari login / url /activation)
     */
    public function showDirectActivation()
    {
        return view('auth.activation.direct-entry', [
            'daftarUp3' => User::DAFTAR_UP3,
        ]);
    }

    /**
     * Proses aktivasi mandiri: input email & pilih UP3 -> kirim OTP
     */
    public function requestOtpDirect(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'up3' => ['required', Rule::in(User::DAFTAR_UP3)],
        ], [
            'email.required' => 'Email wajib diisi.',
            'up3.required' => 'Pilih unit UP3 Anda terlebih dahulu.',
            'up3.in' => 'Pilihan UP3 tidak valid.',
        ]);

        $email = strtolower(trim((string) $request->email));
        $user = User::where('email', $email)->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => 'Email tidak terdaftar dalam sistem. Silakan hubungi administrator untuk mendapatkan akses.',
            ]);
        }

        if ($user->status->value === 'active') {
            return redirect()->route('login')->with('info', 'Akun Anda sudah berstatus aktif. Silakan langsung masuk.');
        }

        // Simpan UP3 yang dipilih
        $user->update([
            'up3' => $request->up3,
            'invitation_token' => $user->invitation_token ?: User::generateInvitationToken(),
            'invitation_expires_at' => now()->addDays(7),
        ]);

        $this->otpService->sendTo($user);

        return redirect()
            ->route('activation.otp-form', $user->invitation_token)
            ->with('success', 'Kode OTP aktivasi telah dikirim untuk akun '.$user->email.' ('.$user->up3.')');
    }

    /**
     * Langkah 1: Akses via Token Undangan — Wajib Pilih UP3 terlebih dahulu
     */
    public function show(string $token)
    {
        $user = User::where('invitation_token', $token)->first();

        if (! $user) {
            return view('auth.activation.invalid');
        }

        if ($user->invitationIsExpired()) {
            return view('auth.activation.expired', compact('user'));
        }

        return view('auth.activation.select-up3', [
            'user' => $user,
            'token' => $token,
            'daftarUp3' => User::DAFTAR_UP3,
        ]);
    }

    /**
     * Simpan pilihan UP3 lalu kirimkan kode OTP ke pengguna
     */
    public function selectUp3(Request $request, string $token)
    {
        $user = User::where('invitation_token', $token)->first();
        if (! $user) {
            return view('auth.activation.invalid');
        }
        abort_if($user->invitationIsExpired(), 410, 'Link undangan sudah kedaluwarsa.');

        $request->validate([
            'up3' => ['required', Rule::in(User::DAFTAR_UP3)],
        ], [
            'up3.required' => 'Pilih unit UP3 wilayah Anda terlebih dahulu sebelum aktivasi.',
            'up3.in' => 'Pilihan UP3 tidak valid.',
        ]);

        $user->update(['up3' => $request->up3]);

        $this->otpService->sendTo($user);

        return redirect()
            ->route('activation.otp-form', $token)
            ->with('success', 'Unit UP3 tersimpan. Kode OTP aktivasi telah dibuat.');
    }

    /**
     * Langkah 2: Form Input Kode OTP 6 Digit
     */
    public function showOtpForm(string $token)
    {
        $user = User::where('invitation_token', $token)->first();
        if (! $user) {
            return view('auth.activation.invalid');
        }

        return view('auth.activation.otp', compact('user', 'token'));
    }

    /**
     * Verifikasi Kode OTP
     */
    public function verifyOtp(Request $request, string $token)
    {
        $user = User::where('invitation_token', $token)->first();
        if (! $user) {
            return view('auth.activation.invalid');
        }

        $request->validate(['otp' => 'required|digits:6'], [
            'otp.required' => 'Masukkan 6 digit kode OTP aktivasi.',
            'otp.digits' => 'Kode OTP harus berupa 6 angka.',
        ]);

        if (! $this->otpService->verify($user, $request->otp)) {
            throw ValidationException::withMessages(['otp' => 'Kode OTP salah atau sudah kedaluwarsa. Silakan coba lagi.']);
        }

        session(["activation_otp_verified_{$token}" => true]);

        return redirect()->route('activation.set-password', $token);
    }

    /**
     * Kirim Ulang OTP
     */
    public function resendOtp(string $token)
    {
        $user = User::where('invitation_token', $token)->first();
        if (! $user) {
            return view('auth.activation.invalid');
        }
        abort_if($user->invitationIsExpired(), 410, 'Link undangan sudah kedaluwarsa.');

        $this->otpService->sendTo($user);

        return back()->with('success', 'Kode OTP baru berhasil dibuat.');
    }

    /**
     * Langkah 3: Form Pembuatan Password Baru
     */
    public function showSetPassword(string $token)
    {
        $user = User::where('invitation_token', $token)->first();
        if (! $user) {
            return view('auth.activation.invalid');
        }

        return view('auth.activation.set-password', compact('user', 'token'));
    }

    /**
     * Simpan Password Baru, Aktifkan Akun, dan Catat Riwayat Aktivasi
     */
    public function setPassword(Request $request, string $token)
    {
        $user = User::where('invitation_token', $token)->first();
        if (! $user) {
            return view('auth.activation.invalid');
        }

        $request->validate([
            'password' => 'required|min:8|confirmed',
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal terdiri dari 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user->update(['password' => Hash::make($request->password)]);
        $user->activate();

        // Catat ke tabel riwayat_aktivasi
        $riwayat = RiwayatAktivasi::create([
            'user_id' => $user->id,
            'nama' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'up3' => $user->up3 ?: 'UP3 Bogor',
            'metode_aktivasi' => 'Verifikasi OTP',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'diaktivasi_pada' => now(),
        ]);

        // Catat Audit Log
        AuditLogHelper::record($user, 'activated', $user, [], [
            'up3' => $user->up3,
            'role' => $user->role,
            'diaktivasi_pada' => now()->toIso8601String(),
        ]);

        // Kirim Notifikasi Internal Sistem
        try {
            NotifikasiHelper::kirim(
                'user',
                "Pengguna {$user->name} ({$user->email}) berhasil mengaktivasi akun [Role: {$user->role} | {$user->up3}].",
                'user-check',
                route('manajemen-user.index'),
                ['super_admin'],
                'Aktivasi Akun Berhasil'
            );
        } catch (\Throwable $e) {
            Log::warning('Gagal kirim NotifikasiHelper saat aktivasi: '.$e->getMessage());
        }

        // Kirim email tanda terima aktivasi (jika mailer aktif)
        try {
            Mail::to($user->email)->send(new ActivationSuccessMail($user, $riwayat));
        } catch (\Throwable $e) {
            Log::warning("Gagal kirim email ActivationSuccessMail ke {$user->email}: ".$e->getMessage());
        }

        Auth::login($user);
        session(['last_riwayat_aktivasi_id' => $riwayat->id]);

        return redirect()->route('activation.success', $token);
    }

    /**
     * Langkah 4: Tampilkan Riwayat & Bukti Aktivasi Lengkap
     */
    public function showSuccess(Request $request, ?string $token = null)
    {
        $user = Auth::user();
        if (! $user && $token) {
            $user = User::where('invitation_token', $token)->first();
        }

        $riwayatId = session('last_riwayat_aktivasi_id');
        $riwayat = null;
        if ($riwayatId) {
            $riwayat = RiwayatAktivasi::find($riwayatId);
        }

        if (! $riwayat && $user) {
            $riwayat = RiwayatAktivasi::where('user_id', $user->id)->latest('diaktivasi_pada')->first();
        }

        return view('auth.activation.success', [
            'user' => $user,
            'riwayat' => $riwayat,
        ]);
    }
}
