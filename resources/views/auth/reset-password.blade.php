<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — {{ config('app.name', 'The Crimpers') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{min-height:100vh;display:flex;align-items:center;justify-content:center;font-family:'Outfit',sans-serif;background:#f1f5f9;padding:20px}
        .card{width:100%;max-width:420px;background:#fff;border-radius:24px;border:1px solid #e5e7eb;box-shadow:0 20px 50px rgba(0,0,0,.08);overflow:hidden}
        .card-accent{height:4px;background:linear-gradient(90deg,#F7DF79,#fde047)}
        .card-body{padding:36px 32px}
        .logo{display:flex;align-items:center;gap:10px;margin-bottom:32px}
        .logo-mark{width:38px;height:38px;background:#111827;border-radius:10px;display:flex;align-items:center;justify-content:center}
        .logo-mark svg{width:20px;height:20px;color:#F7DF79}
        .logo-name{font-size:17px;font-weight:900;color:#111827}
        .card-title{font-size:22px;font-weight:900;color:#111827;margin-bottom:6px}
        .card-sub{font-size:13px;color:#9ca3af;font-weight:500;margin-bottom:28px;line-height:1.5}
        .field{margin-bottom:16px}
        .field label{display:block;font-size:11px;font-weight:800;color:#374151;text-transform:uppercase;letter-spacing:.07em;margin-bottom:6px}
        .field input{width:100%;height:46px;border:1.5px solid #e5e7eb;border-radius:12px;padding:0 14px;font-size:14px;font-family:'Outfit',sans-serif;outline:none;color:#111827;background:#f9fafb;transition:all .2s}
        .field input:focus{border-color:#F7DF79;background:#fff;box-shadow:0 0 0 3px rgba(247,223,121,.2)}
        .btn{width:100%;height:48px;border:none;border-radius:12px;background:#F7DF79;color:#111827;font-size:14px;font-weight:900;letter-spacing:.06em;cursor:pointer;transition:all .2s;font-family:'Outfit',sans-serif;box-shadow:0 4px 14px rgba(247,223,121,.4);margin-top:10px}
        .btn:hover{background:#fde047;transform:translateY(-1px)}
        .err-box{background:#fef2f2;border:1.5px solid #fecaca;border-radius:10px;padding:10px 14px;margin-bottom:20px;font-size:13px;color:#dc2626;font-weight:600}
    </style>
</head>
<body>
    <div class="card">
        <div class="card-accent"></div>
        <div class="card-body">
            <div class="logo">
                <div class="logo-mark">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/></svg>
                </div>
                <span class="logo-name">The Crimpers</span>
            </div>

            <h1 class="card-title">Reset Password</h1>
            <p class="card-sub">Create a strong, new password for your account.</p>

            @if($errors->any())
            <div class="err-box">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="field">
                    <label>Email Address</label>
                    <input type="email" name="email" value="{{ $email ?? old('email') }}" required readonly style="background:#e5e7eb; color:#6b7280;">
                </div>

                <div class="field">
                    <label>New Password</label>
                    <input type="password" name="password" required autofocus>
                </div>

                <div class="field">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" required>
                </div>

                <button type="submit" class="btn">RESET PASSWORD</button>
            </form>
        </div>
    </div>
</body>
</html>
