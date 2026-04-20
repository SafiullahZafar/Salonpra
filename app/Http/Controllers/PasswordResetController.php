<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use App\Models\User;

class PasswordResetController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['We could not find a user with that email address.'],
            ]);
        }

        try {
            // Attempt standard mail send
            $status = Password::broker()->sendResetLink($request->only('email'));
            if ($status === Password::RESET_LINK_SENT) {
                return back()->with('status', __($status));
            }
        } catch (\Exception $e) {
            // Fallback for local dev without SMTP or broken credentials.
            // Re-create token AFTER exception because the broker's failing attempt overwrote the DB hash.
            $token = Password::broker()->createToken($user);
            $resetUrl = route('password.reset', ['token' => $token, 'email' => $request->email]);

            $errMsg = 'Gmail SMTP Authentication failed. You must generate a Google App Password and put it in your .env file (`MAIL_PASSWORD`). In the meantime, use this: <a href="'.$resetUrl.'" style="color:#000;text-decoration:underline;background:#F7DF79;padding:4px 8px;border-radius:4px;display:inline-block;margin-top:6px;">Developer Reset Link</a>';
            return back()->with('status', \Illuminate\Support\HtmlString::fromString($errMsg));
        }

        throw ValidationException::withMessages([
            'email' => [__($status ?? Password::INVALID_USER)],
        ]);
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', __($status));
        }

        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }
}
