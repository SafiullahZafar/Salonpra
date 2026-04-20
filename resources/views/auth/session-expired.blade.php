<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Expired — {{ config('app.name', 'The Crimpers') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            font-family:'Outfit',sans-serif;
            background:#111827;
            color:#fff;
            padding:20px;
        }

        .container {
            width: 100%;
            max-width: 480px;
            text-align: center;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.05);
            backdrop-filter: blur(10px);
            padding: 60px 40px;
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
        }

        .icon-box {
            width: 80px;
            height: 80px;
            background: rgba(247,223,121,0.1);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 32px;
            color: #F7DF79;
        }

        .icon-box svg {
            width: 40px;
            height: 40px;
        }

        h1 {
            font-size: 32px;
            font-weight: 900;
            margin-bottom: 16px;
            letter-spacing: -0.02em;
        }

        p {
            font-size: 16px;
            color: rgba(255,255,255,0.5);
            line-height: 1.6;
            margin-bottom: 40px;
        }

        .timer-bar {
            width: 100%;
            height: 4px;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 24px;
        }

        .progress {
            height: 100%;
            background: #F7DF79;
            width: 100%;
            animation: countdown 5s linear forwards;
        }

        @keyframes countdown {
            from { width: 100%; }
            to { width: 0%; }
        }

        .btn-redirect {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #F7DF79;
            color: #111827;
            padding: 14px 28px;
            border-radius: 14px;
            font-weight: 800;
            text-decoration: none;
            font-size: 15px;
            transition: all 0.2s;
            box-shadow: 0 4px 14px rgba(247,223,121,0.2);
        }

        .btn-redirect:hover {
            background: #fde047;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(247,223,121,0.3);
        }

        .redirect-info {
            font-size: 13px;
            color: rgba(255,255,255,0.3);
            font-weight: 500;
            margin-top: 24px;
        }

        #seconds {
            color: #F7DF79;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon-box">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>

        <h1>Session Expired</h1>
        <p>For your security, you have been automatically logged out after 29 days of activity. Please sign in again to continue.</p>

        <div class="timer-bar">
            <div class="progress"></div>
        </div>

        <a href="{{ route('login') }}" class="btn-redirect">
            SIGN IN NOW
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </a>

        <div class="redirect-info">
            Redirecting in <span id="seconds">5</span> seconds...
        </div>
    </div>

    <script>
        let timeLeft = 5;
        const secondsEl = document.getElementById('seconds');
        
        const timer = setInterval(() => {
            timeLeft--;
            if (secondsEl) secondsEl.textContent = timeLeft;
            
            if (timeLeft <= 0) {
                clearInterval(timer);
                window.location.href = "{{ route('login') }}";
            }
        }, 1000);
    </script>
</body>
</html>
