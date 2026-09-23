<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user) {}

    public function build()
    {
        $activationUrl = route('activation.show', $this->user->invitation_token);

        return $this->subject('Undangan Bergabung — Sistem SPKLU PLN UP3 Bogor')
            ->view('emails.user-invitation', [
                'user' => $this->user,
                'activationUrl' => $activationUrl,
            ]);
    }
}
