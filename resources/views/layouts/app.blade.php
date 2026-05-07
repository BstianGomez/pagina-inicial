<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel Portal') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <style>
            :root {
                --primary: #4f46e5;
                --primary-dark: #3730a3;
                --bg-main: #f8fafc;
            }

            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
                background-color: var(--bg-main);
                color: #1e293b;
                background-image: 
                    radial-gradient(at 0% 0%, rgba(79, 70, 229, 0.05) 0px, transparent 50%),
                    radial-gradient(at 100% 100%, rgba(99, 102, 241, 0.05) 0px, transparent 50%);
                background-attachment: fixed;
            }

            .glass-nav {
                background: rgba(255, 255, 255, 0.8);
                backdrop-filter: blur(12px);
                border-bottom: 1px solid rgba(226, 232, 240, 0.8);
                position: sticky;
                top: 0;
                z-index: 40;
            }

            .nav-container {
                max-width: 1400px;
                margin: 0 auto;
                height: 72px;
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
                color: #0f172a;
                display: flex;
                align-items: center;
                gap: 12px;
                text-decoration: none;
                transition: all 0.2s;
            }

            .logo-text:hover {
                transform: translateY(-1px);
            }

            .logo-icon-box {
                width: 40px;
                height: 40px;
                background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                box-shadow: 0 8px 16px -4px rgba(79, 70, 229, 0.4);
                font-size: 1.1rem;
            }

            .nav-links {
                display: flex;
                gap: 32px;
                align-items: center;
            }

            .nav-link-custom {
                font-weight: 600;
                font-size: 0.875rem;
                color: #64748b;
                transition: all 0.2s;
                text-decoration: none;
            }

            .nav-link-custom:hover, .nav-link-custom.active {
                color: var(--primary);
            }

            .user-pill {
                background: #ffffff;
                border: 1px solid #e2e8f0;
                padding: 6px 16px;
                border-radius: 99px;
                display: flex;
                align-items: center;
                gap: 10px;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            }

            .btn-logout-direct {
                background: #fee2e2;
                color: #ef4444;
                width: 36px;
                height: 36px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.2s;
                border: none;
                cursor: pointer;
            }

            .btn-logout-direct:hover {
                background: #ef4444;
                color: white;
                transform: scale(1.05);
            }

            .main-content {
                padding-top: 24px;
                min-height: calc(100vh - 72px);
            }

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
                        <div class="logo-icon-box">
                            <i class="fas fa-cubes"></i>
                        </div>
                        <span style="background: linear-gradient(to right, #0f172a, #334155); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">PORTAL INSTITUCIONAL</span>
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

                <div class="flex items-center gap-4">
                    <div class="user-pill hidden md:flex">
                        <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <span class="text-sm font-bold text-slate-700">{{ Auth::user()->name }}</span>
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
    </body>
</html>
