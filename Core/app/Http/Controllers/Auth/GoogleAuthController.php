<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect(?string $token = null)
    {
        if ($token) {
            session(['activation_token' => $token]);
        }

        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();
        $token = session('activation_token');

        if ($token) {
            $invitedUser = User::where('invitation_token', $token)->first();

            if (! $invitedUser) {
                return redirect()->route('login')->with('error', 'Link undangan tidak valid.');
            }

            if (strtolower($googleUser->getEmail()) !== strtolower($invitedUser->email)) {
                return redirect()
                    ->route('activation.show', $token)
                    ->with('error', "Akun Google ({$googleUser->getEmail()}) tidak sesuai dengan email undangan ({$invitedUser->email}). Silakan login Google dengan akun yang benar.");
            }

            $invitedUser->update([
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
            ]);
            $invitedUser->activate();

            session()->forget('activation_token');
            Auth::login($invitedUser);

            return redirect()->route('dashboard')->with('success', 'Akun berhasil diaktivasi lewat Google!');
        }

        $existingUser = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if (! $existingUser || ! $existingUser->isActive()) {
            return redirect()->route('login')->with('error', 'Akun tidak ditemukan atau belum aktif. Hubungi Super Admin.');
        }

        if (! $existingUser->google_id) {
            $existingUser->update(['google_id' => $googleUser->getId(), 'avatar' => $googleUser->getAvatar()]);
        }

        $existingUser->update(['last_login_at' => now()]);
        Auth::login($existingUser);

        return redirect()->route('dashboard');
    }

    public function link()
    {
        return Socialite::driver('google')->redirect();
    }
}