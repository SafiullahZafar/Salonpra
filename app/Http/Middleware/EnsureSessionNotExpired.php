<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSessionNotExpired
{
    public function handle(Request $request, Closure $next): Response
    {
        $expiresAt = $request->session()->get('auth_expires_at');

        if (!$expiresAt || now()->timestamp > (int) $expiresAt) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('session.expired');
        }

        return $next($request);
    }
}
