<?php

namespace App\Models;

use App\Enums\UserStatus;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'status',
        'google_id', 'avatar', 'invited_by',
        'force_password_change', 'created_directly_by',
        'requires_otp_first_login', 'first_login_verified_at',
    ];

    protected $hidden = ['password', 'otp_code', 'remember_token'];

    protected $casts = [
        'status' => UserStatus::class,
        'force_password_change' => 'boolean',
        'requires_otp_first_login' => 'boolean',
        'email_verified_at' => 'datetime',
        'invitation_expires_at' => 'datetime',
        'otp_expires_at' => 'datetime',
        'last_login_at' => 'datetime',
        'first_login_verified_at' => 'datetime',
    ];

    // ---------- Relasi ----------

    public function invitedBy()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function createdDirectlyBy()
    {
        return $this->belongsTo(User::class, 'created_directly_by');
    }

    // ---------- Helper Undangan ----------

    public static function generateInvitationToken(): string
    {
        return Str::random(64);
    }

    public function invitationIsExpired(): bool
    {
        return $this->invitation_expires_at && $this->invitation_expires_at->isPast();
    }

    // ---------- Helper OTP ----------

    public function generateOtp(): string
    {
        $code = (string) random_int(100000, 999999);

        $this->update([
            'otp_code' => Hash::make($code),
            'otp_expires_at' => now()->addMinutes(10),
            'otp_attempts' => 0,
        ]);

        return $code; // dikirim via email, TIDAK disimpan plain di DB
    }

    public function verifyOtp(string $inputCode): bool
    {
        if (! $this->otp_code || ! $this->otp_expires_at || $this->otp_expires_at->isPast()) {
            return false;
        }

        if ($this->otp_attempts >= 5) {
            return false;
        }

        if (! Hash::check($inputCode, $this->otp_code)) {
            $this->increment('otp_attempts');
            return false;
        }

        return true;
    }

    public function clearOtp(): void
    {
        $this->update([
            'otp_code' => null,
            'otp_expires_at' => null,
            'otp_attempts' => 0,
        ]);
    }

    public function needsFirstLoginOtp(): bool
    {
        return $this->requires_otp_first_login && ! $this->first_login_verified_at;
    }

    public function activate(): void
    {
        $this->update([
            'status' => UserStatus::Active,
            'invitation_token' => null,
            'invitation_expires_at' => null,
            'email_verified_at' => now(),
        ]);
        $this->clearOtp();
    }

    public function isActive(): bool
    {
        return $this->status === UserStatus::Active;
    }
}