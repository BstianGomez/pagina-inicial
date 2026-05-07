<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel Portal') }}</title>

        <!-- Dark mode: must run BEFORE any CSS to prevent flash -->
        <script>
            (function() {
                if (localStorage.getItem('darkMode') === 'true') {
                    document.documentElement.classList.add('dark');
                }
            })();
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <style>
            /* ===== LIGHT MODE (default) ===== */
            :root {
                --primary: #4f46e5;
                --primary-dark: #3730a3;
                --bg-main: #f8fafc;
                --bg-nav: rgba(255,255,255,0.85);
                --border-nav: rgba(226,232,240,0.8);
                --text-primary: #1e293b;
                --text-secondary: #64748b;
                --text-nav-link: #64748b;
                --user-pill-bg: #ffffff;
                --user-pill-border: #e2e8f0;
                --logout-bg: #fee2e2;
                --logout-color: #ef4444;
                --nav-bg-grad1: rgba(79,70,229,0.05);
                --nav-bg-grad2: rgba(99,102,241,0.05);
                --toggle-bg: #e2e8f0;
                --toggle-icon-color: #64748b;
            }

            /* ===== DARK MODE ===== */
            html.dark {
                --primary: #818cf8;
                --primary-dark: #6366f1;
                --bg-main: #0f172a;
                --bg-nav: rgba(15,23,42,0.9);
                --border-nav: rgba(51,65,85,0.8);
                --text-primary: #f1f5f9;
                --text-secondary: #94a3b8;
                --text-nav-link: #94a3b8;
                --user-pill-bg: #1e293b;
                --user-pill-border: #334155;
                --logout-bg: #3f1515;
                --logout-color: #f87171;
                --nav-bg-grad1: rgba(99,102,241,0.08);
                --nav-bg-grad2: rgba(129,140,248,0.08);
                --toggle-bg: #334155;
                --toggle-icon-color: #f59e0b;
            }

            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
                background-color: var(--bg-main);
                color: var(--text-primary);
                background-image: 
                    radial-gradient(at 0% 0%, var(--nav-bg-grad1) 0px, transparent 50%),
                    radial-gradient(at 100% 100%, var(--nav-bg-grad2) 0px, transparent 50%);
                background-attachment: fixed;
                transition: background-color 0.3s, color 0.3s;
            }

            .glass-nav {
                background: var(--bg-nav);
                backdrop-filter: blur(12px);
                border-bottom: 1px solid var(--border-nav);
                position: sticky;
                top: 0;
                z-index: 40;
                transition: background 0.3s, border-color 0.3s;
            }

            .nav-container {
                max-width: 1400px;
                margin: 0 auto;
                height: 64px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0 24px;
            }

            .logo-text {
                font-family: 'Space Grotesk', sans-serif;
                font-weight: 700;
                font-size: 1.25rem;
                letter-spacing: -0.02em;
                color: var(--text-primary);
                display: flex;
                align-items: center;
                gap: 12px;
                text-decoration: none;
                transition: all 0.2s;
            }

            .logo-text:hover { transform: translateY(-1px); }

            .nav-link-custom {
                font-weight: 600;
                font-size: 0.875rem;
                color: var(--text-nav-link);
                transition: color 0.2s;
                text-decoration: none;
            }

            .nav-link-custom:hover, .nav-link-custom.active { color: var(--primary); }

            .user-pill {
                background: var(--user-pill-bg);
                border: 1px solid var(--user-pill-border);
                padding: 6px 16px;
                border-radius: 99px;
                display: flex;
                align-items: center;
                gap: 10px;
                box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
                transition: background 0.3s, border-color 0.3s;
            }

            .user-pill span { color: var(--text-primary); font-size: 0.875rem; font-weight: 700; }

            .btn-logout-direct {
                background: var(--logout-bg);
                color: var(--logout-color);
                width: 36px; height: 36px;
                border-radius: 10px;
                display: flex; align-items: center; justify-content: center;
                transition: all 0.2s;
                border: none; cursor: pointer;
            }

            .btn-logout-direct:hover {
                background: #ef4444;
                color: white;
                transform: scale(1.05);
            }

            /* Dark Mode Toggle Button */
            .dark-toggle {
                width: 36px; height: 36px;
                border-radius: 10px;
                background: var(--toggle-bg);
                border: none; cursor: pointer;
                display: flex; align-items: center; justify-content: center;
                font-size: 1rem;
                color: var(--toggle-icon-color);
                transition: all 0.3s;
            }

            .dark-toggle:hover { transform: scale(1.1); opacity: 0.8; }

            .main-content {
                padding-top: 24px;
                min-height: calc(100vh - 64px);
            }

            /* Dark mode overrides for common white cards */
            html.dark .bg-white { background-color: #1e293b !important; }
            html.dark .border-slate-100 { border-color: #334155 !important; }
            html.dark .border-slate-200 { border-color: #475569 !important; }
            html.dark .text-slate-900 { color: #f1f5f9 !important; }
            html.dark .text-slate-800 { color: #e2e8f0 !important; }
            html.dark .text-slate-700 { color: #cbd5e1 !important; }
            html.dark .text-slate-600 { color: #94a3b8 !important; }
            html.dark .text-slate-500 { color: #64748b !important; }
            html.dark .text-slate-400 { color: #475569 !important; }
            html.dark .bg-slate-50 { background-color: #0f172a !important; }
            html.dark .bg-slate-100 { background-color: #1e293b !important; }
            html.dark .shadow-xl { box-shadow: 0 20px 25px -5px rgba(0,0,0,0.5) !important; }
            html.dark .shadow-sm { box-shadow: 0 1px 2px 0 rgba(0,0,0,0.4) !important; }

            /* Keep dark mode logo readable */
            html.dark .logo-img { filter: brightness(1); }
            html.dark .bg-slate-50\/30 { background-color: rgba(15,23,42,0.3) !important; }
            html.dark .divide-slate-50 { border-color: #1e293b !important; }
            html.dark .hover\:bg-slate-50\/50:hover { background-color: rgba(30,41,59,0.5) !important; }

            header.bg-white.shadow {
                background: transparent !important;
                box-shadow: none !important;
            }
        </style>
    </head>
    <body class="antialiased">
        <!-- Modern Navigation -->
        <nav class="glass-nav">
            <div class="nav-container">
                <div class="flex items-center gap-8">
                    <a href="{{ route('app-redirect') }}" class="logo-text">
                        <img src="{{ asset('img/logo.png') }}" alt="Fundación SOFOFA Capital Humano" class="logo-img" style="height: 36px; width: auto; object-fit: contain;">
                    </a>

                    <div class="hidden sm:flex gap-6">
                        <a href="{{ route('app-redirect') }}" class="nav-link-custom {{ request()->routeIs('app-redirect') ? 'active' : '' }}">Inicio</a>
                        @if(Auth::user()->isSuperAdmin())
                            <a href="{{ route('admin.users.index') }}" class="nav-link-custom {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Usuarios</a>
                            <a href="{{ route('admin.reports.oc') }}" class="nav-link-custom {{ request()->routeIs('admin.reports.oc') ? 'active' : '' }}">Datos OC</a>
                            <a href="{{ route('admin.reports.viajes') }}" class="nav-link-custom {{ request()->routeIs('admin.reports.viajes') ? 'active' : '' }}">Datos Viajes</a>
                            <a href="{{ route('admin.reports.rendicion') }}" class="nav-link-custom {{ request()->routeIs('admin.reports.rendicion') ? 'active' : '' }}">Datos Rendiciones</a>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Dark Mode Toggle -->
                    <button id="dark-toggle-btn" class="dark-toggle" title="Cambiar tema" onclick="toggleDarkMode()">
                        <i id="dark-toggle-icon" class="fas fa-moon"></i>
                    </button>

                    <div class="user-pill hidden md:flex">
                        <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <span>{{ Auth::user()->name }}</span>
                    </div>

                    <form method="POST" action="{{ route('force-logout') }}">
                        @csrf
                        <button type="submit" class="btn-logout-direct" title="Cerrar Sesión Completamente">
                            <i class="fas fa-power-off"></i>
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        @isset($header)
            <header class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </header>
        @endisset

        <!-- Page Content -->
        <main class="main-content">
            {{ $slot }}
        </main>

        <script>
            // Set correct icon on load
            document.addEventListener('DOMContentLoaded', function() {
                const icon = document.getElementById('dark-toggle-icon');
                if (icon && document.documentElement.classList.contains('dark')) {
                    icon.className = 'fas fa-sun';
                }
            });

            function toggleDarkMode() {
                const html = document.documentElement;
                const icon = document.getElementById('dark-toggle-icon');
                const isDark = html.classList.toggle('dark');
                localStorage.setItem('darkMode', isDark);
                if (icon) icon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
            }
        </script>
    </body>
</html>
