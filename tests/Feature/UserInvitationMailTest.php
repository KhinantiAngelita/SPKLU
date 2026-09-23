<?php

namespace Tests\Feature;

use App\Mail\OtpMail;
use App\Mail\UserInvitationMail;
use App\Models\User;
use Tests\TestCase;

class UserInvitationMailTest extends TestCase
{
    public function test_user_invitation_mail_renders_with_spklu_theme_and_no_laravel_branding(): void
    {
        $user = new User([
            'name' => 'Khinanti Angelita',
            'email' => 'khinanti@example.com',
            'role' => 'super_admin',
            'invitation_token' => 'test-invitation-token-12345',
        ]);

        $mail = new UserInvitationMail($user);
        $rendered = $mail->render();

        $this->assertStringContainsString('SISTEM MANAJEMEN SPKLU', $rendered);
        $this->assertStringContainsString('PT PLN (PERSERO) UP3 BOGOR', $rendered);
        $this->assertStringContainsString('Khinanti Angelita', $rendered);
        $this->assertStringContainsString('Aktivasi Akun Saya', $rendered);
        $this->assertStringNotContainsStringIgnoringCase('laravel', $rendered);
    }

    public function test_otp_mail_renders_with_spklu_theme_and_no_laravel_branding(): void
    {
        $user = new User([
            'name' => 'Khinanti Angelita',
            'email' => 'khinanti@example.com',
        ]);

        $mail = new OtpMail($user, '789123');
        $rendered = $mail->render();

        $this->assertStringContainsString('SISTEM MANAJEMEN SPKLU', $rendered);
        $this->assertStringContainsString('789123', $rendered);
        $this->assertStringNotContainsStringIgnoringCase('laravel', $rendered);
    }
}
