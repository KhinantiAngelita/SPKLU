<?php

namespace App\Mail;

use App\Models\RiwayatAktivasi;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActivationSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public RiwayatAktivasi $riwayat) {}

    public function build()
    {
        $namaUp3 = $this->riwayat->up3 ?: ($this->user->up3 ?: 'PLN UID Jawa Barat');

        return $this->subject("Bukti Riwayat Aktivasi Akun — {$namaUp3}")
            ->view('emails.activation-success', [
                'user' => $this->user,
                'riwayat' => $this->riwayat,
                'namaUp3' => $namaUp3,
            ]);
    }
}
