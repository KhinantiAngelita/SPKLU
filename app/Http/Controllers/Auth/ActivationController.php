<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ActivationController extends Controller
{
    public function __construct(protected OtpService $otpService) {}

    public function show(string $token)
    {
        $user = User::where('invitation_token', $token)->firstOrFail();

        if ($user->invitationIsExpired()) {
            return view('auth.activation.expired', compact('user'));
        }

        return view('auth.activation.choose-method', compact('user', 'token'));
    }

    public function sendOtp(string $token)
    {
        $user = User::where('invitation_token', $token)->firstOrFail();
        abort_if($user->invitationIsExpired(), 410, 'Link undangan sudah kedaluwarsa.');

        $this->otpService->sendTo($user);

        return redirect()
            ->route('activation.otp-form', $token)
            ->with('success', 'Kode OTP sudah dikirim ke ' . $user->email);
    }

    public function showOtpForm(string $token)
    {
        $user = User::where('invitation_token', $token)->firstOrFail();
        return view('auth.activation.otp', compact('user', 'token'));
    }

    public function verifyOtp(Request $request, string $token)
    {
        $user = User::where('invitation_token', $token)->firstOrFail();

        $request->validate(['otp' => 'required|digits:6']);

        if (! $this->otpService->verify($user, $request->otp)) {
            throw ValidationException::withMessages(['otp' => 'Kode OTP salah atau sudah kedaluwarsa.']);
        }

        return redirect()->route('activation.set-password', $token);
    }

    public function showSetPassword(string $token)
    {
        $user = User::where('invitation_token', $token)->firstOrFail();
        return view('auth.activation.set-password', compact('user', 'token'));
    }

    public function setPassword(Request $request, string $token)
    {
        $user = User::where('invitation_token', $token)->firstOrFail();

        $request->validate(['password' => 'required|min:8|confirmed']);

        $user->update(['password' => Hash::make($request->password)]);
        $user->activate();

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Akun berhasil diaktivasi!');
    }
}