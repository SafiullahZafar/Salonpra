<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->get('two_factor_passed')) {
            return redirect()->route('auth.2fa.form');
        }

        return $next($request);
    }
}
