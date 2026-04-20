<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Two-Factor Auth</title>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
*{box-sizing:border-box;margin:0;padding:0}

body{
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    font-family:'Outfit',sans-serif;
    background:linear-gradient(135deg,#f8fafc,#eef2ff);
}

.card{
    width:100%;
    max-width:420px;
    background:#fff;
    border-radius:20px;
    padding:28px;
    box-shadow:0 15px 40px rgba(0,0,0,0.08);
}

.title{
    font-size:22px;
    font-weight:800;
    margin-bottom:6px;
}

.subtitle{
    font-size:13px;
    color:#6b7280;
    margin-bottom:20px;
}

.qr{
    background:#f9fafb;
    border:1px solid #e5e7eb;
    border-radius:14px;
    padding:16px;
    text-align:center;
    margin-bottom:18px;
}

.qr img{
    width:160px;
    height:160px;
}

.secret{
    background:#f3f4f6;
    padding:10px;
    border-radius:10px;
    font-size:12px;
    margin-bottom:18px;
    text-align:center;
    word-break:break-all;
}

.field{
    margin-bottom:16px;
}

.field label{
    font-size:12px;
    font-weight:600;
    display:block;
    margin-bottom:6px;
}

.input{
    width:100%;
    height:46px;
    border-radius:10px;
    border:1px solid #e5e7eb;
    padding:0 12px;
    font-size:16px;
    text-align:center;
    letter-spacing:4px;
}

.input::placeholder{
    font-size:16px;
    letter-spacing:4px;
    color:#9ca3af;
}

.input:focus{
    outline:none;
    border-color:#6366f1;
    box-shadow:0 0 0 3px rgb(253, 224, 71);
}

.btn{
    width:100%;
    height:44px;
    border:none;
    border-radius:10px;
    background:#fde047;
    color:black;
    font-weight:700;
    cursor:pointer;
    transition:.2s;
}

.btn:hover{
    background:#ffe568;
}

.back{
    display:block;
    text-align:center;
    margin-top:12px;
    font-size:13px;
    color:#6b7280;
    text-decoration:none;
}

.back:hover{
    color:#111827;
}

</style>
</head>

<body>

<div class="card">

<div class="title">Two-Factor Verification</div>
<div class="subtitle">Scan QR and enter the 6-digit code</div>

<div class="qr">
    <img src="{{ $qrUrl }}">
</div>

<div class="secret">{{ $secret }}</div>

<form method="POST" action="{{ route('auth.2fa.verify') }}">
@csrf

<div class="field">
<label>Authentication Code</label>
<input type="text" name="code" class="input" placeholder="000000" maxlength="6">
</div>

<button class="btn">Verify</button>

</form>

<a href="{{ route('login') }}" class="back">Back to login</a>

</div>

</body>
</html>
