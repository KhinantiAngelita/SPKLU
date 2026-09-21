<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function __construct(protected OtpService $otpService) {}

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate(['email' => 'required|email', 'password' => 'required']);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! $user->password || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages(['email' => 'Email atau password salah.']);
        }

        if ($user->status->value !== 'active') {
            throw ValidationException::withMessages(['email' => 'Akun ini belum aktif atau sudah dinonaktifkan.']);
        }

        if ($user->needsFirstLoginOtp()) {
            $this->otpService->sendTo($user);
            session(['pending_first_login_user_id' => $user->id]);

            return redirect()->route('login.first-otp.form');
        }

        Auth::login($user);
        $user->update(['last_login_at' => now()]);

        return redirect()->intended(route('dashboard'));
    }

    public function showFirstOtpForm()
    {
        $userId = session('pending_first_login_user_id');
        abort_unless($userId, 403, 'Sesi login tidak valid, silakan login ulang.');

        $user = User::findOrFail($userId);
        return view('auth.first-login-otp', compact('user'));
    }

    public function verifyFirstOtp(Request $request)
    {
        $userId = session('pending_first_login_user_id');
        abort_unless($userId, 403);

        $user = User::findOrFail($userId);
        $request->validate(['otp' => 'required|digits:6']);

        if (! $this->otpService->verify($user, $request->otp)) {
            throw ValidationException::withMessages(['otp' => 'Kode OTP salah atau sudah kedaluwarsa.']);
        }

        $user->update(['first_login_verified_at' => now()]);
        session()->forget('pending_first_login_user_id');

        Auth::login($user);
        $user->update(['last_login_at' => now()]);

        return redirect()->route('dashboard');
    }

    public function resendFirstOtp()
    {
        $userId = session('pending_first_login_user_id');
        abort_unless($userId, 403);

        $this->otpService->sendTo(User::findOrFail($userId));

        return back()->with('success', 'Kode OTP dikirim ulang.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}