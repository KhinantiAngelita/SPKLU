<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        abort_if(! $request->user(), 401);

        abort_unless(
            in_array($request->user()->role, $roles),
            403,
            'Anda tidak punya akses ke halaman ini.'
        );

        return $next($request);
    }
}