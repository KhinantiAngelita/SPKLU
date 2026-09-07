<?php

use App\Http\Controllers\Auth\ActivationController;
use App\Http\Controllers\Auth\ForcePasswordChangeController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManajemenUserController;
use App\Http\Controllers\MasterParameterController;
use App\Http\Controllers\MasterSpkluController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

// ============ ROOT ============

Route::get('/', fn () => redirect()->route('login'));

// ============ LOGIN (tanpa auth) ============

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.attempt');

Route::prefix('login/first-otp')->name('login.first-otp.')->group(function () {
    Route::get('/', [LoginController::class, 'showFirstOtpForm'])->name('form');
    Route::post('/', [LoginController::class, 'verifyFirstOtp'])->name('verify');
    Route::post('resend', [LoginController::class, 'resendFirstOtp'])->name('resend');
});

// ============ AKTIVASI UNDANGAN (tanpa auth) ============

Route::prefix('activation')->name('activation.')->group(function () {
    Route::get('{token}', [ActivationController::class, 'show'])->name('show');
    Route::post('{token}/send-otp', [ActivationController::class, 'sendOtp'])->name('send-otp');
    Route::get('{token}/otp', [ActivationController::class, 'showOtpForm'])->name('otp-form');
    Route::post('{token}/otp', [ActivationController::class, 'verifyOtp'])->name('verify-otp');
    Route::get('{token}/set-password', [ActivationController::class, 'showSetPassword'])->name('set-password');
    Route::post('{token}/set-password', [ActivationController::class, 'setPassword'])->name('store-password');
});

// ============ GOOGLE OAUTH (tanpa auth, karena bisa dipanggil dari halaman aktivasi) ============

Route::prefix('auth/google')->name('auth.google.')->group(function () {
    Route::get('redirect/{token?}', [GoogleAuthController::class, 'redirect'])->name('redirect');
    Route::get('callback', [GoogleAuthController::class, 'callback'])->name('callback');
});

// ============ HALAMAN UTAMA (butuh login) ============

Route::middleware(['auth'])->group(function () {

    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('force-password-change', [ForcePasswordChangeController::class, 'show'])->name('password.force-change.show');
    Route::post('force-password-change', [ForcePasswordChangeController::class, 'update'])->name('password.force-change.update');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('auth/google/link', [GoogleAuthController::class, 'link'])->name('auth.google.link');

    Route::prefix('master-spklu')->name('master-spklu.')->group(function () {
        Route::get('/', [MasterSpkluController::class, 'index'])->name('index');
        Route::post('/', [MasterSpkluController::class, 'store'])->middleware('role:super_admin,pengelola')->name('store');
        Route::put('{spklu}', [MasterSpkluController::class, 'update'])->middleware('role:super_admin,pengelola')->name('update'); // GANTI dari destroy
        Route::post('import', [MasterSpkluController::class, 'importExcel'])->middleware('role:super_admin,pengelola')->name('import');

        Route::middleware('role:super_admin')->group(function () {
            Route::get('validasi', [MasterSpkluController::class, 'validasiIndex'])->name('validasi');
            Route::post('validasi/{spklu}/approve', [MasterSpkluController::class, 'validasiApprove'])->name('validasi.approve');
            Route::post('validasi/{spklu}/reject', [MasterSpkluController::class, 'validasiReject'])->name('validasi.reject');
        });
    });

    Route::prefix('transaksi')->name('transaksi.')->group(function () {
        Route::get('/', [TransaksiController::class, 'index'])->name('index');
        Route::post('import', [TransaksiController::class, 'import'])->middleware('role:super_admin,pengelola')->name('import');
        Route::post('alias', [TransaksiController::class, 'storeAlias'])->middleware('role:super_admin,pengelola')->name('alias.store'); // BARU
    });

    Route::middleware('role:super_admin')->prefix('manajemen-user')->name('manajemen-user.')->group(function () {
        Route::get('/', [ManajemenUserController::class, 'index'])->name('index');
        Route::post('/', [ManajemenUserController::class, 'store'])->name('store');
        Route::patch('{user}', [ManajemenUserController::class, 'update'])->name('update');
        Route::post('{user}/resend-invitation', [ManajemenUserController::class, 'resendInvitation'])->name('resend-invitation');
        Route::post('{user}/toggle-status', [ManajemenUserController::class, 'toggleStatus'])->name('toggle-status');
    });

    Route::middleware('role:super_admin')->prefix('master-parameter')->name('master-parameter.')->group(function () {
        Route::get('/', [MasterParameterController::class, 'index'])->name('index');
        Route::patch('tarif/{tarif}', [MasterParameterController::class, 'updateTarif'])->name('tarif.update');
        Route::patch('poin-jaringan/{poin}', [MasterParameterController::class, 'updatePoinJaringan'])->name('poin-jaringan.update');
        Route::post('target', [MasterParameterController::class, 'storeTarget'])->name('target.store');
    });

});