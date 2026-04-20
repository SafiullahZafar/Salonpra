<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'SalonPri') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f1f5f9;
        }
        .sidebar-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 9999px; /* Pill shape */
            background-color: #e2e8f0; /* Default light gray pill as in image */
            color: #64748b;
            margin-bottom: 0.5rem;
        }
        .sidebar-item:hover {
            background-color: #cbd5e1;
            color: #1e293b;
            transform: translateX(4px);
        }
        .sidebar-active {
            background-color: #fde047 !important; /* Soft yellow as in image */
            color: #1e293b !important;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }
        .sidebar-active i {
            color: #1e293b !important;
        }
        
        /* ── Salon Form System ── */
        .salon-card { background: #fff; border-radius: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid #f1f1f1; padding: 32px; }
        .salon-label { display: block; font-size: 13px; font-weight: 700; color: #374151; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.05em; }
        .salon-input { width: 100%; background: #f9fafb; border: 1.5px solid #e5e7eb; border-radius: 12px; padding: 12px 16px; font-size: 14px; font-family: 'Outfit', sans-serif; transition: all 0.2s; outline: none; }
        .salon-input:focus { border-color: #F7DF79; background: #fff; box-shadow: 0 0 0 4px rgba(247,223,121,0.15); }
        .salon-btn-gold { background: #F7DF79; color: #111827; font-weight: 700; padding: 12px 24px; border-radius: 12px; transition: all 0.2s; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; }
        .salon-btn-gold:hover { background: #fde047; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(247,223,121,0.3); }

        /* Global App Toasts */
        .app-toast-wrap { position: fixed; top: 18px; right: 18px; z-index: 99999; display: flex; flex-direction: column; gap: 10px; }
        .app-toast { min-width: 260px; max-width: 420px; background: #fff; border: 1px solid #e5e7eb; border-left: 4px solid #e5e7eb; border-radius: 12px; box-shadow: 0 14px 28px rgba(15,23,42,.12); padding: 12px 14px; display: flex; align-items: flex-start; gap: 10px; transform: translateX(120%); opacity: 0; transition: all .25s ease; }
        .app-toast.show { transform: translateX(0); opacity: 1; }
        .app-toast i { width: 18px; height: 18px; margin-top: 1px; }
        .app-toast-title { font-size: 12px; font-weight: 900; color: #111827; margin-bottom: 1px; }
        .app-toast-msg { font-size: 12px; color: #475569; line-height: 1.35; }
        .app-toast.success { border-left-color: #16a34a; }
        .app-toast.success i { color: #16a34a; }
        .app-toast.error { border-left-color: #ef4444; }
        .app-toast.error i { color: #ef4444; }
        .app-toast.info { border-left-color: #0284c7; }
        .app-toast.info i { color: #0284c7; }
    </style>
</head>
<body class="bg-dashboard-bg overflow-x-hidden">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-y-auto min-w-0">
            <!-- Page Content -->
            <main class="flex-1 px-8 pt-8 pb-8">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Global message helper for consistent UI feedback
        window.showAppMessage = function (message, type = 'info') {
            const iconMap = { success: 'check-circle-2', error: 'circle-alert', info: 'info' };
            const titleMap = { success: 'Success', error: 'Error', info: 'Notice' };
            let wrap = document.getElementById('app-toast-wrap');
            if (!wrap) {
                wrap = document.createElement('div');
                wrap.id = 'app-toast-wrap';
                wrap.className = 'app-toast-wrap';
                document.body.appendChild(wrap);
            }

            const toast = document.createElement('div');
            toast.className = `app-toast ${type}`;
            toast.innerHTML = `
                <i data-lucide="${iconMap[type] || iconMap.info}"></i>
                <div>
                    <div class="app-toast-title">${titleMap[type] || titleMap.info}</div>
                    <div class="app-toast-msg">${message}</div>
                </div>
            `;
            wrap.appendChild(toast);
            lucide.createIcons();
            setTimeout(() => toast.classList.add('show'), 10);
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 240);
            }, 3200);
        };
    </script>
</body>
</html>
