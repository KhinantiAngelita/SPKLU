<?php

namespace App\Providers;

use App\Models\AktivitasNotifikasi;
use App\Models\Probabilitas;
use App\Policies\ProbabilitasPolicy;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (str_starts_with(config('app.url'), 'https://') || app()->environment('production')) {
            URL::forceScheme('https');
        }

        View::composer('layouts.partials.topbar', function ($view) {
            if (! auth()->check()) {
                $view->with(['notifications' => collect(), 'notifCount' => 0]);

                return;
            }

            if (! Schema::hasTable('aktivitas_notifikasis')) {
                $view->with(['notifications' => collect(), 'notifCount' => 0]);

                return;
            }

            $user = auth()->user();
            $notifications = AktivitasNotifikasi::query()
                ->forRole($user->role)
                ->latest()
                ->take(15)
                ->get();

            $lastRead = $user->last_read_notification_at;
            $notifCount = $lastRead
                ? AktivitasNotifikasi::query()->forRole($user->role)->where('created_at', '>', $lastRead)->count()
                : $notifications->count();

            $view->with([
                'notifications' => $notifications,
                'notifCount' => $notifCount,
            ]);
        });
    }

    protected $policies = [
        Probabilitas::class => ProbabilitasPolicy::class,
    ];
}
