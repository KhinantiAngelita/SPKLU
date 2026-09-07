<?php

namespace App\Services;

use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    public function sendTo(User $user): void
    {
        $code = $user->generateOtp();
        Mail::to($user->email)->send(new OtpMail($user, $code));
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