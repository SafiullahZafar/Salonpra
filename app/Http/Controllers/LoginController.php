<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\TotpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('pos.index');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', strtolower(trim($request->email)))->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        if (empty($user->two_factor_secret)) {
            $user->two_factor_secret = TotpService::generateSecret();
            $user->save();
        }

        $request->session()->put('two_factor_user_id', $user->id);
        $request->session()->forget(['two_factor_passed', 'auth_expires_at']);

        return redirect()->route('auth.2fa.form');
    }

    public function showTwoFactorForm(Request $request)
    {
        $userId = $request->session()->get('two_factor_user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);
        if (!$user) {
            $request->session()->forget('two_factor_user_id');
            return redirect()->route('login');
        }

        $appName = config('app.name', 'Salon POS');
        $issuer = rawurlencode($appName);
        $account = rawurlencode($user->email);
        $secret = $user->two_factor_secret;
        $otpAuthUri = "otpauth://totp/{$issuer}:{$account}?secret={$secret}&issuer={$issuer}&period=30&digits=6";
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . rawurlencode($otpAuthUri);

        return view('auth.two-factor', [
            'user' => $user,
            'secret' => $secret,
            'qrUrl' => $qrUrl,
        ]);
    }

    public function verifyTwoFactor(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        $userId = $request->session()->get('two_factor_user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);
        if (!$user || empty($user->two_factor_secret)) {
            return redirect()->route('login');
        }

        if (!TotpService::verifyCode($user->two_factor_secret, $request->code)) {
            return back()->withErrors([
                'code' => 'Invalid authenticator code. Please try again.',
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->put('two_factor_passed', true);
        $request->session()->put('auth_expires_at', now()->addDays(29)->timestamp);
        $request->session()->forget('two_factor_user_id');

        $user->last_login_at = now();
        if (!$user->two_factor_enabled_at) {
            $user->two_factor_enabled_at = now();
        }
        $user->save();

        return redirect()->route('pos.index');
    }

    public function sessionExpired()
    {
        return view('auth.session-expired');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}