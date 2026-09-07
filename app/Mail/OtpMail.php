<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public string $code) {}

    public function build()
    {
        return $this->subject('Kode Aktivasi Akun — Sistem SPKLU')
            ->markdown('emails.otp', ['code' => $this->code]);
    }
}