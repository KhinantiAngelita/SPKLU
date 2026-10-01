<?php

namespace App\Services;

use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    public function sendTo(User $user): string
    {
        $code = $user->generateOtp();

        try {
            Mail::to($user->email)->send(new OtpMail($user, $code));
        } catch (\Throwable $e) {
            Log::warning("Gagal mengirim email OTP ke {$user->email}: ".$e->getMessage());
        }

        // Simpan ke session flash agar user bisa melihat dan menggunakan langsung jika email tidak sampai/tanpa email
        session()->flash('demo_otp', $code);

        return $code;
    }

    public function verify(User $user, string $code): bool
    {
        $valid = $user->verifyOtp($code);

        if ($valid) {
            $user->clearOtp();
        }

        return $valid;
    }
}
