<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ManageActiveUp3
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            $isSuperAdmin = $user->isSuperAdmin();

            if (! $isSuperAdmin) {
                // Pengguna non-Super Admin selalu terikat pada unit UP3 akunnya
                $activeUp3 = $user->up3;
                session(['active_up3' => $activeUp3]);
                $request->merge(['up3' => $activeUp3]);
            } else {
                // Super Admin: cek apakah ada pergantian UP3 di query param ?up3=
                if ($request->has('up3')) {
                    $requestedUp3 = $request->query('up3');
                    if (empty($requestedUp3) || $requestedUp3 === 'all' || $requestedUp3 === 'semua') {
                        session()->forget('active_up3');
                        $activeUp3 = null;
                    } else {
                        session(['active_up3' => $requestedUp3]);
                        $activeUp3 = $requestedUp3;
                    }
                } else {
                    $activeUp3 = session('active_up3');
                    if ($activeUp3) {
                        $request->merge(['up3' => $activeUp3]);
                    }
                }
            }

            View::share('activeUp3', $activeUp3);
            View::share('selectedUp3', $activeUp3);
            View::share('globalDaftarUp3', User::DAFTAR_UP3);
        }

        return $next($request);
    }
}
