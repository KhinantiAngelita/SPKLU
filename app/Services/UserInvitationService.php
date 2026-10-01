<?php

namespace App\Services;

use App\Enums\UserStatus;
use App\Mail\UserInvitationMail;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserInvitationService
{
    public function invite(string $name, string $email, string $role, User $invitedBy, ?string $up3 = null): User
    {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'up3' => $up3,
            'status' => UserStatus::Pending,
            'password' => null,
            'invitation_token' => User::generateInvitationToken(),
            'invitation_expires_at' => now()->addDays(7),
            'invited_by' => $invitedBy->id,
        ]);

        try {
            Mail::to($user->email)->send(new UserInvitationMail($user));
        } catch (\Throwable $e) {
            Log::warning('Gagal mengirim email undangan ke '.$user->email.': '.$e->getMessage());
        }

        return $user;
    }

    public function resend(User $user): void
    {
        $user->update([
            'invitation_token' => User::generateInvitationToken(),
            'invitation_expires_at' => now()->addDays(7),
        ]);

        try {
            Mail::to($user->email)->send(new UserInvitationMail($user));
        } catch (\Throwable $e) {
            Log::warning('Gagal mengirim ulang email undangan ke '.$user->email.': '.$e->getMessage());
        }
    }

    public function createDirectly(string $name, string $email, string $role, ?string $password, User $createdBy, ?string $up3 = null): array
    {
        $plainPassword = $password ?: Str::password(12);

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'up3' => $up3,
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
