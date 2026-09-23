<?php

use App\Http\Controllers\Auth\ActivationController;
use App\Http\Controllers\Auth\ForcePasswordChangeController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FsSkemaController;
use App\Http\Controllers\KandidatPeringkatController;
use App\Http\Controllers\KandidatPrioritasController;
use App\Http\Controllers\KandidatSpkluTerdekatController;
use App\Http\Controllers\ManajemenUserController;
use App\Http\Controllers\MasterParameterController;
use App\Http\Controllers\MasterSpkluController;
use App\Http\Controllers\Monitoring\PengajuanController;
use App\Http\Controllers\Monitoring\ProbabilitasController;
use App\Http\Controllers\PenjadwalanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProyeksiEnergiController;
use App\Http\Controllers\RekomendasiLokasiController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

// ============ ROOT ============

Route::get('/', fn () => redirect()->route('login'));

// ============ LOGIN (tanpa auth) ============

Route::get('login', [LoginController::class, 'showLoginForm'])
    ->name('login');

Route::post('login', [LoginController::class, 'login'])
    ->name('login.attempt');

Route::prefix('login/first-otp')->name('login.first-otp.')->group(function () {

    Route::get('/', [LoginController::class, 'showFirstOtpForm'])
        ->name('form');

    Route::post('/', [LoginController::class, 'verifyFirstOtp'])
        ->name('verify');

    Route::post('resend', [LoginController::class, 'resendFirstOtp'])
        ->name('resend');
});

// ============ AKTIVASI UNDANGAN (tanpa auth) ============

Route::prefix('activation')->name('activation.')->group(function () {

    Route::get('{token}', [ActivationController::class, 'show'])
        ->name('show');

    Route::post('{token}/send-otp', [ActivationController::class, 'sendOtp'])
        ->name('send-otp');

    Route::get('{token}/otp', [ActivationController::class, 'showOtpForm'])
        ->name('otp-form');

    Route::post('{token}/otp', [ActivationController::class, 'verifyOtp'])
        ->name('verify-otp');

    Route::get('{token}/set-password', [ActivationController::class, 'showSetPassword'])
        ->name('set-password');

    Route::post('{token}/set-password', [ActivationController::class, 'setPassword'])
        ->name('store-password');
});

// ============ GOOGLE OAUTH (tanpa auth) ============

Route::prefix('auth/google')->name('auth.google.')->group(function () {

    Route::get('redirect/{token?}', [GoogleAuthController::class, 'redirect'])
        ->name('redirect');

    Route::get('callback', [GoogleAuthController::class, 'callback'])
        ->name('callback');
});

// ============ HALAMAN UTAMA (butuh login) ============

Route::middleware(['auth'])->group(function () {

    // ============ AUTH ============

    Route::post('logout', [LoginController::class, 'logout'])
        ->name('logout');

    Route::get('force-password-change', [ForcePasswordChangeController::class, 'show'])
        ->name('password.force-change.show');

    Route::post('force-password-change', [ForcePasswordChangeController::class, 'update'])
        ->name('password.force-change.update');

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('auth/google/link', [GoogleAuthController::class, 'link'])
        ->name('auth.google.link');

    // ============ PROFILE ============

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
    });

    // ============ MASTER SPKLU ============

    Route::prefix('master-spklu')->name('master-spklu.')->group(function () {

        Route::get('/', [MasterSpkluController::class, 'index'])
            ->name('index');

        Route::post('/', [MasterSpkluController::class, 'store'])
            ->middleware('role:super_admin,pengelola')
            ->name('store');

        Route::put('{spklu}', [MasterSpkluController::class, 'update'])
            ->middleware('role:super_admin,pengelola')
            ->name('update');

        Route::post('import', [MasterSpkluController::class, 'importExcel'])
            ->middleware('role:super_admin,pengelola')
            ->name('import');

        Route::get('kode-unit-by-ulp/{ulpMapping}', [MasterSpkluController::class, 'kodeUnitByUlp'])
            ->name('kode-unit-by-ulp');

        // ---- PEMETAAN ALIAS (dipindah dari Transaksi, controller & route sudah konsisten) ----
        Route::post('alias', [MasterSpkluController::class, 'storeAlias'])
            ->middleware('role:super_admin,pengelola')
            ->name('alias.store');

        Route::put('alias/{spkluAlias}', [MasterSpkluController::class, 'updateAlias'])
            ->middleware('role:super_admin,pengelola')
            ->name('alias.update');

        Route::post('alias/bulk', [MasterSpkluController::class, 'storeAliasBulk'])
            ->middleware('role:super_admin,pengelola')
            ->name('alias.bulk-store');
        // ---------------------------------------------------------------------------------------

        Route::middleware('role:super_admin')->group(function () {

            Route::get('validasi', [MasterSpkluController::class, 'validasiIndex'])
                ->name('validasi');

            Route::post('validasi/{spklu}/approve', [MasterSpkluController::class, 'validasiApprove'])
                ->name('validasi.approve');

            Route::post('validasi/{spklu}/reject', [MasterSpkluController::class, 'validasiReject'])
                ->name('validasi.reject');
        });
    });

    // ============ TRANSAKSI ============
    // Catatan: TIDAK ADA lagi route alias.* di sini — sudah full pindah ke grup master-spklu di atas.

    Route::prefix('transaksi')->name('transaksi.')->group(function () {

        Route::get('/', [TransaksiController::class, 'index'])
            ->name('index');

        Route::get('upload', [TransaksiController::class, 'uploadPage'])
            ->middleware('role:super_admin,pengelola')
            ->name('upload');

        Route::delete('upload/{transaksiUpload}', [TransaksiController::class, 'destroyUpload'])
            ->middleware('role:super_admin')
            ->name('upload.destroy');

        Route::post('upload/{transaksiUpload}/reupload', [TransaksiController::class, 'reupload'])
            ->middleware('role:super_admin,pengelola')
            ->name('upload.reupload');

        Route::post('upload/{transaksiUpload}/reprocess', [TransaksiController::class, 'reprocess'])
            ->middleware('role:super_admin,pengelola')
            ->name('upload.reprocess');

        Route::get('upload/{transaksiUpload}/status', [TransaksiController::class, 'uploadStatus'])
            ->name('upload.status');

        Route::get('export', [TransaksiController::class, 'export'])
            ->name('export');

        Route::post('import', [TransaksiController::class, 'import'])
            ->middleware('role:super_admin,pengelola')
            ->name('import');

        Route::get('proyeksi', [ProyeksiEnergiController::class, 'index'])
            ->name('proyeksi');
    });

    // ============ MANAJEMEN USER ============

    Route::middleware('role:super_admin')
        ->prefix('manajemen-user')
        ->name('manajemen-user.')
        ->group(function () {

            Route::get('/', [ManajemenUserController::class, 'index'])
                ->name('index');

            Route::post('/', [ManajemenUserController::class, 'store'])
                ->name('store');

            Route::patch('{user}', [ManajemenUserController::class, 'update'])
                ->name('update');

            Route::post('{user}/resend-invitation', [ManajemenUserController::class, 'resendInvitation'])
                ->name('resend-invitation');

            Route::post('{user}/toggle-status', [ManajemenUserController::class, 'toggleStatus'])
                ->name('toggle-status');

            Route::delete('{user}', [ManajemenUserController::class, 'destroy'])
                ->name('destroy');
        });

    // ============ MASTER PARAMETER ============

    Route::middleware('role:super_admin')
        ->prefix('master-parameter')
        ->name('master-parameter.')
        ->group(function () {

            Route::get('/', [MasterParameterController::class, 'index'])
                ->name('index');

            Route::patch('tarif/{tarif}', [MasterParameterController::class, 'updateTarif'])
                ->name('tarif.update');

            Route::patch('poin-jaringan/{poin}', [MasterParameterController::class, 'updatePoinJaringan'])
                ->name('poin-jaringan.update');

            Route::post('target', [MasterParameterController::class, 'storeTarget'])
                ->name('target.store');
        });

    // ============ MONITORING PROBABILITAS ============

    Route::prefix('monitoring/probabilitas')
        ->name('monitoring.probabilitas.')
        ->group(function () {

            Route::get('/', [ProbabilitasController::class, 'index'])
                ->name('index');

            Route::post('/', [ProbabilitasController::class, 'store'])
                ->middleware('role:super_admin,pengelola')
                ->name('store');

            Route::get('/{probabilitas}/edit-data', [ProbabilitasController::class, 'editData'])
                ->middleware('role:super_admin,pengelola')
                ->name('edit-data');

            Route::put('/{probabilitas}', [ProbabilitasController::class, 'update'])
                ->middleware('role:super_admin,pengelola')
                ->name('update');

            Route::post('/{probabilitas}/tahapan', [ProbabilitasController::class, 'storeTahapan'])
                ->middleware('role:super_admin,pengelola')
                ->name('tahapan.store');

            Route::get('/{probabilitas}/tahapan/{tahap}/riwayat', [ProbabilitasController::class, 'riwayatLengkap'])
                ->name('riwayat-lengkap');

            Route::get('/{probabilitas}/tahapan/{tahap}', [ProbabilitasController::class, 'riwayatTahap'])
                ->name('tahapan.riwayat');

            Route::delete('/{probabilitas}/tahapan/{tahapanProbing}', [ProbabilitasController::class, 'destroyTahapan'])
                ->middleware('role:super_admin,pengelola')
                ->name('tahapan.destroy');
        });

    // ============ MONITORING KANDIDAT ============

    Route::prefix('monitoring/kandidat')
        ->name('monitoring.kandidat.')
        ->group(function () {

            Route::get('/create', [ProbabilitasController::class, 'create'])
                ->middleware('role:super_admin,pengelola')
                ->name('create');
        });

    Route::get('monitoring/kandidat-baru/create', [ProbabilitasController::class, 'create'])
        ->middleware('role:super_admin,pengelola')
        ->name('monitoring.kandidat-baru.create');

    // ============ KANDIDAT PRIORITAS ============

    Route::get('/kandidat-prioritas', [KandidatPrioritasController::class, 'index'])
        ->name('kandidat-prioritas.index');

    Route::post('kandidat-prioritas/{kandidat}/spklu-terdekat', [KandidatSpkluTerdekatController::class, 'store'])
        ->middleware('role:super_admin,pengelola')
        ->name('kandidat-prioritas.spklu-terdekat.store');

    Route::post('kandidat-prioritas/{kandidat}/spklu-terdekat/otomatis', [KandidatSpkluTerdekatController::class, 'ambilOtomatis'])
        ->middleware('role:super_admin,pengelola')
        ->name('kandidat-prioritas.spklu-terdekat.otomatis');

    Route::get('/kandidat-peringkat', [KandidatPeringkatController::class, 'index2'])
        ->name('kandidat-peringkat.index');

    // ============ REKOMENDASI LOKASI ============

    Route::get('/rekomendasi-lokasi', [RekomendasiLokasiController::class, 'index'])
        ->name('rekomendasi-lokasi.index');

    // ============ MONITORING PENGAJUAN ============

    Route::prefix('monitoring/pengajuan')
        ->name('monitoring.pengajuan.')
        ->group(function () {

            Route::get('/', [PengajuanController::class, 'index'])
                ->name('index');

            Route::post('/{probabilitas}/validasi', [PengajuanController::class, 'validasi'])
                ->middleware('role:super_admin,pengelola')
                ->name('validasi');
        });

    // ============ FS SKEMA ============

    Route::prefix('fs-skema')
        ->name('fs-skema.')
        ->group(function () {

            Route::get('/', [FsSkemaController::class, 'index'])
                ->name('index');

            Route::get('/create', [FsSkemaController::class, 'create'])
                ->middleware('role:super_admin,pemasaran,pengelola')
                ->name('create');

            Route::post('/', [FsSkemaController::class, 'store'])
                ->middleware('role:super_admin,pemasaran,pengelola')
                ->name('store');

            Route::match(['post', 'put'], '/preview', [FsSkemaController::class, 'preview'])
                ->middleware('role:super_admin,pemasaran,pengelola')
                ->name('preview');

            Route::get('/{fsSkema}', [FsSkemaController::class, 'show'])
                ->name('show');

            Route::get('/{fsSkema}/edit', [FsSkemaController::class, 'edit'])
                ->middleware('role:super_admin,pengelola')
                ->name('edit');

            Route::put('/{fsSkema}', [FsSkemaController::class, 'update'])
                ->middleware('role:super_admin,pengelola')
                ->name('update');

            Route::delete('/{fsSkema}', [FsSkemaController::class, 'destroy'])
                ->middleware('role:super_admin,pengelola')
                ->name('destroy');
        });

    // ============ PENJADWALAN ============

    Route::prefix('penjadwalan')
        ->name('penjadwalan.')
        ->group(function () {

            Route::get('/', [PenjadwalanController::class, 'index'])
                ->name('index');

            Route::get('/create', [PenjadwalanController::class, 'create'])
                ->middleware('role:super_admin,pemasaran,pengelola')
                ->name('create');

            Route::post('/', [PenjadwalanController::class, 'store'])
                ->middleware('role:super_admin,pemasaran,pengelola')
                ->name('store');

            Route::get('/{jadwal}/edit', [PenjadwalanController::class, 'edit'])
                ->middleware('role:super_admin,pemasaran,pengelola')
                ->name('edit');

            Route::put('/{jadwal}', [PenjadwalanController::class, 'update'])
                ->middleware('role:super_admin,pemasaran,pengelola')
                ->name('update');

            Route::delete('/{jadwal}', [PenjadwalanController::class, 'destroy'])
                ->middleware('role:super_admin')
                ->name('destroy');
        });

    // ============ NOTIFIKASI ============

    Route::post('/notifikasi/baca-semua', function () {
        if (auth()->check()) {
            auth()->user()->update(['last_read_notification_at' => now()]);
        }

        return response()->json(['status' => 'ok']);
    })->name('notifikasi.baca-semua');

});
