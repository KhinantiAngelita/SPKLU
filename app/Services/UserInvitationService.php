<?php

namespace App\Services;

use App\Enums\UserStatus;
use App\Mail\UserInvitationMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserInvitationService
{
    public function invite(string $name, string $email, string $role, User $invitedBy): User
    {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'status' => UserStatus::Pending,
            'password' => null,
            'invitation_token' => User::generateInvitationToken(),
            'invitation_expires_at' => now()->addDays(7),
            'invited_by' => $invitedBy->id,
        ]);

        Mail::to($user->email)->send(new UserInvitationMail($user));

        return $user;
    }

    public function resend(User $user): void
    {
        $user->update([
            'invitation_token' => User::generateInvitationToken(),
            'invitation_expires_at' => now()->addDays(7),
        ]);

        Mail::to($user->email)->send(new UserInvitationMail($user));
    }

    /**
     * Super Admin buat akun langsung, tanpa proses undangan/aktivasi.
     * Return password plain text SEKALI SAJA (untuk ditampilkan ke Super Admin, tidak pernah disimpan).
     */
    public function createDirectly(string $name, string $email, string $role, ?string $password, User $createdBy): array
    {
        $plainPassword = $password ?: Str::password(12);

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'status' => UserStatus::Active,
            'password' => bcrypt($plainPassword),
            'force_password_change' => true,
            'requires_otp_first_login' => true,
            'created_directly_by' => $createdBy->id,
            'email_verified_at' => now(),
        ]);

        return [
            'user' => $user,
            'plain_password' => $plainPassword,
        ];
    }
}