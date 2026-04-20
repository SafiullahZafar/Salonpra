<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — {{ config('app.name', 'The Crimpers') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{min-height:100vh;display:flex;font-family:'Outfit',sans-serif;background:#f1f5f9}

        /* Left panel */
        .left{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:40px;background:#fff;min-height:100vh}
        .login-box{width:100%;max-width:400px}

        /* Logo */
        .logo{display:flex;align-items:center;gap:10px;margin-bottom:40px}
        .logo-mark{width:42px;height:42px;background:#111827;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .logo-mark svg{width:22px;height:22px;color:#F7DF79}
        .logo-name{font-size:20px;font-weight:900;color:#111827}
        .logo-sub{font-size:11px;color:#9ca3af;font-weight:500;margin-top:1px}

        /* Heading */
        .login-title{font-size:26px;font-weight:900;color:#111827;margin-bottom:6px}
        .login-sub{font-size:14px;color:#9ca3af;font-weight:500;margin-bottom:32px}

        /* Fields */
        .field{margin-bottom:16px}
        .field label{display:block;font-size:11px;font-weight:800;color:#374151;text-transform:uppercase;letter-spacing:.07em;margin-bottom:6px}
        .field input{width:100%;height:46px;border:1.5px solid #e5e7eb;border-radius:12px;padding:0 14px;font-size:14px;font-family:'Outfit',sans-serif;outline:none;color:#111827;background:#f9fafb;transition:all .2s}
        .field input:focus{border-color:#F7DF79;background:#fff;box-shadow:0 0 0 3px rgba(247,223,121,.2)}

        /* Remember row */
        .remember-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px}
        .remember-label{display:flex;align-items:center;gap:8px;font-size:13px;color:#6b7280;font-weight:500;cursor:pointer}
        .remember-label input[type=checkbox]{width:16px;height:16px;accent-color:#F7DF79;cursor:pointer}

        /* Button */
        .btn-login{width:100%;height:48px;border:none;border-radius:12px;background:#F7DF79;color:#111827;font-size:14px;font-weight:900;letter-spacing:.06em;cursor:pointer;transition:all .2s;font-family:'Outfit',sans-serif;box-shadow:0 4px 14px rgba(247,223,121,.4)}
        .btn-login:hover{background:#fde047;transform:translateY(-1px);box-shadow:0 6px 20px rgba(247,223,121,.5)}
        .btn-login:active{transform:scale(.98)}

        /* Error & Success */
        .err-box{background:#fef2f2;border:1.5px solid #fecaca;border-radius:10px;padding:10px 14px;margin-bottom:20px;font-size:13px;color:#dc2626;font-weight:600}
        .succ-box{background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:10px;padding:10px 14px;margin-bottom:20px;font-size:13px;color:#16a34a;font-weight:600}

        /* Right panel */
        .right{width:420px;flex-shrink:0;background:#111827;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:48px;min-height:100vh}
        .right-content{text-align:center}
        .right-icon{width:80px;height:80px;background:rgba(247,223,121,.15);border-radius:24px;display:flex;align-items:center;justify-content:center;margin:0 auto 28px}
        .right-icon svg{width:40px;height:40px;color:#F7DF79}
        .right-title{font-size:24px;font-weight:900;color:#fff;margin-bottom:12px;line-height:1.3}
        .right-sub{font-size:14px;color:rgba(255,255,255,.4);font-weight:500;line-height:1.6;margin-bottom:36px}
        .feature-list{display:flex;flex-direction:column;gap:14px;text-align:left}
        .feature-item{display:flex;align-items:center;gap:12px}
        .feature-dot{width:32px;height:32px;background:rgba(247,223,121,.1);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .feature-dot svg{width:16px;height:16px;color:#F7DF79}
        .feature-text{font-size:13px;color:rgba(255,255,255,.6);font-weight:500}

        @media(max-width:768px){.right{display:none}.left{padding:24px}}
    </style>
</head>
<body>
    <div class="left">
        <div class="login-box">
            <!-- Logo -->
            <div class="logo">
                <div class="logo-mark">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/></svg>
                </div>
                <div>
                    <div class="logo-name">The Crimpers</div>
                    <div class="logo-sub">Salon Management</div>
                </div>
            </div>

            <h1 class="login-title">Welcome back</h1>
            <p class="login-sub">Sign in to your account to continue</p>

            @if(session('status'))
            <div class="succ-box">{!! session('status') !!}</div>
            @endif

            @if($errors->any())
            <div class="err-box">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}">
                @csrf
                <div class="field">
                    <label>Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
                </div>
                <div class="field">
                    <label>Password</label>
                    <input type="password" name="password" id="login-password" placeholder="••••••••" required>
                </div>
                <div class="remember-row">
                    <label class="remember-label">
                        <input type="checkbox" onchange="document.getElementById('login-password').type = this.checked ? 'text' : 'password'">
                        Show password
                    </label>
                    <a href="{{ route('password.request') }}" style="font-size:13px; color:#111827; font-weight:700; text-decoration:none; transition:opacity 0.2s;" onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">Forgot Password?</a>
                </div>
                <button type="submit" class="btn-login">SIGN IN</button>
            </form>
        </div>
    </div>

    <div class="right">
        <div class="right-content">
            <div class="right-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="m6 3 6 6 6-6"/><path d="M20 21H4"/><path d="m6 21 6-6 6 6"/></svg>
            </div>
            <h2 class="right-title">Manage your salon<br>with confidence</h2>
            <p class="right-sub">Everything you need to run a professional salon — billing, staff, inventory and more.</p>
            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-dot">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                    </div>
                    <span class="feature-text">Fast POS terminal for quick billing</span>
                </div>
                <div class="feature-item">
                    <div class="feature-dot">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" x2="18" y1="20" y2="10"/><line x1="12" x2="12" y1="20" y2="4"/><line x1="6" x2="6" y1="20" y2="14"/></svg>
                    </div>
                    <span class="feature-text">Real-time sales analytics & reports</span>
                </div>
                <div class="feature-item">
                    <div class="feature-dot">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <span class="feature-text">Staff & customer management</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
