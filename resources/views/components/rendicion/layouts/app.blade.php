@props([
    'page_title' => '',
    'page_subtitle' => '',
    'backRoute' => null,
    'backLabel' => '↩ Volver al listado',
    'showLogout' => true,
    'additionalButtons' => null,
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trim((string) $page_title) ? $page_title . ' - ' : '' }}{{ config('app.name', 'Fundacion SOFOFA') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
        }

        :root {
            --bg: #f5f7fb;
            --bg-accent: #eef2ff;
            --ink: #101828;
            --muted: #5b6473;
            --brand: #0f6bb6;
            --brand-2: #0a4f86;
            --line: #e3e8f0;
            --card: #ffffff;
            --chip: #e8f1fb;
            --success: #0f7a3e;
            --warning: #b97700;
            --container-max-width: 1440px;
        }

        .sidebar, .sidebar *, .nav-label, .brand-text {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar.is-initializing, .sidebar.is-initializing * {
            transition: none !important;
        }

        .sidebar {
            background: linear-gradient(180deg, #0b5fa5 0%, #0f6bb6 50%, #1b7dc8 100%);
            color: #fff;
            width: 200px;
            display: flex;
            flex-direction: column;
            box-shadow: 5px 0 30px rgba(15, 107, 182, 0.2);
            transition: width 300ms cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 100;
            min-height: 100vh;
            flex-shrink: 0;
        }

        .sidebar.collapsed {
            width: 64px;
        }

        body.sidebar-pref-collapsed .sidebar {
            width: 64px;
        }

        .sidebar-header {
            padding: 8px 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: flex-start;
            min-height: 70px;
            gap: 8px;
        }

        .sidebar.collapsed .sidebar-header {
            padding: 10px 6px;
            justify-content: center;
        }

        body.sidebar-pref-collapsed .sidebar-header {
            padding: 10px 6px;
            justify-content: center;
        }

        .sidebar .brand-badge {
            width: 60px;
            height: 44px;
            min-width: 60px;
            min-height: 44px;
            max-width: 60px;
            max-height: 44px;
            background: transparent;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border-radius: 10px;
            box-shadow: none;
            position: relative;
            top: 5px;
        }

        .sidebar .brand-badge img {
            width: 100%;
            height: 100%;
            max-height: 36px;
            object-fit: contain;
            filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.04));
        }

        .brand-text {
            display: block;
            font-weight: 700;
            font-size: 12px;
            line-height: 1.2;
            color: rgba(255, 255, 255, 0.95);
        }

        .brand-subtitle {
            display: block;
            font-size: 10px;
            color: rgba(255, 255, 255, 0.75);
            margin-top: 2px;
        }

        .sidebar.collapsed .brand-text {
            opacity: 0;
            visibility: hidden;
            position: absolute;
        }

        body.sidebar-pref-collapsed .brand-text,
        body.sidebar-pref-collapsed .brand-subtitle {
            opacity: 0;
            visibility: hidden;
            position: absolute;
        }

        .sidebar-nav {
            flex: 1;
            padding: 12px 0 72px;
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            justify-content: flex-start;
            width: calc(100% - 14px);
            height: 40px;
            padding: 0 10px;
            margin: 2px 0;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            position: relative;
            border: none;
            background: transparent;
            cursor: pointer;
            transition: all 220ms cubic-bezier(0.4, 0, 0.2, 1);
            top: 2px;
        }

        .nav-item::before { display: none; }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.16);
            color: #fff;
        }

        .nav-item.active {
            background: rgba(255, 255, 255, 0.22);
            color: #fff;
            font-weight: 600;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.16);
        }

        .sidebar.collapsed .nav-label {
            opacity: 0;
            visibility: hidden;
            position: absolute;
        }

        body.sidebar-pref-collapsed .nav-label {
            opacity: 0;
            visibility: hidden;
            position: absolute;
        }

        .sidebar.collapsed .nav-item {
            padding: 0;
            margin: 3px 0;
            justify-content: center;
            width: 52px;
        }

        body.sidebar-pref-collapsed .nav-item {
            padding: 0;
            margin: 3px 0;
            justify-content: center;
            width: 52px;
        }

        .sidebar.collapsed .nav-item::before {
            display: none;
        }

        .sidebar.collapsed .nav-item:hover::after {
            content: attr(title);
            position: absolute;
            left: 100%;
            margin-left: 12px;
            padding: 8px 12px;
            background: rgba(16, 24, 40, 0.95);
            color: #fff;
            font-size: 13px;
            font-weight: 500;
            border-radius: 8px;
            white-space: nowrap;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            pointer-events: none;
        }

        .nav-icon {
            width: 20px;
            height: 20px;
            stroke-width: 2.1;
            flex-shrink: 0;
        }

        .nav-label {
            display: inline-block;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .toggle-btn {
            position: fixed;
            bottom: 18px;
            left: 18px;
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.2);
            border: 1.5px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            display: grid;
            place-items: center;
            cursor: pointer;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 220ms cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 220;
        }

        .sidebar.collapsed .toggle-btn {
            transform: none;
        }

        body.sidebar-pref-collapsed .toggle-btn {
            transform: none;
        }

        .toggle-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            transform: scale(1.05);
        }

        .sidebar.collapsed .toggle-btn:hover {
            transform: scale(1.05);
        }

        .toggle-btn:active {
            transform: scale(0.95);
        }

        .sidebar.collapsed .toggle-btn:active {
            transform: scale(0.95);
        }

        .toggle-icon {
            width: 18px;
            height: 18px;
            stroke: #fff;
            display: block;
            position: static;
        }

        .sidebar.collapsed .toggle-icon {
            transform: rotate(180deg);
        }

        body.sidebar-pref-collapsed .toggle-icon {
            transform: rotate(180deg);
        }

        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow-x: hidden;
            position: relative;
        }

        body {
            background-color: var(--bg);
            font-family: "Space Grotesk", "DM Sans", ui-sans-serif, system-ui, sans-serif;
            color: var(--ink);
        }

        .page {
            min-height: 100vh;
            display: grid;
            grid-template-columns: auto 1fr;
            width: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            background: var(--bg);
        }

        .main-content {
            flex: 1 1 auto;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            min-width: 0;
            overflow-x: hidden;
            position: relative;
            background: #f8fafc;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 18px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 200ms cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .btn-ghost {
            background: #fff;
            color: #0b5fa5;
            border-color: #bfdbfe;
        }

        .btn-ghost:hover {
            background: #eff6ff;
            border-color: #3b82f6;
            transform: translateY(-1px);
        }

        .btn-logout {
            background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25);
        }

        .btn-logout:hover {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(220, 38, 38, 0.35);
        }

        .topbar {
            background: linear-gradient(90deg, #0b5fa5 0%, #0f6bb6 50%, #0b5fa5 100%);
            color: white;
            padding: 0 24px;
            box-shadow: 0 4px 20px rgba(15, 107, 182, 0.15);
            position: sticky;
            top: 0;
            z-index: 100;
            min-height: 64px;
            display: flex;
            align-items: center;
            width: 100%;
            backdrop-filter: blur(10px);
        }

        .topbar-inner {
            width: 100%;
            max-width: var(--container-max-width);
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-shrink: 0;
            position: relative;
            top: 2px;
        }

        .topbar-center {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            pointer-events: none;
            display: flex;
            align-items: center;
        }

        .topbar-badge {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255,255,255,0.08);
            padding: 8px 24px;
            border-radius: 50px;
            border: 1px solid rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
        }

        .topbar-badge span {
            font-weight: 600;
            font-size: 14px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: white;
            opacity: 0.9;
        }

        .user-info-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 16px;
            background: rgba(255,255,255,0.12);
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.2s ease;
        }

        .user-info-badge:hover {
            background: rgba(255,255,255,0.18);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--brand-2);
            border: 1px solid rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            color: white;
        }

        .toolbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-logout {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 8px 16px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            text-transform: none;
            text-decoration: none;
        }

        .btn-logout:hover {
            background: #ef4444;
            border-color: #ef4444;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
            transform: translateY(-2px);
        }

        .mobile-menu-toggle {
            display: none;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: white;
            padding: 8px;
            border-radius: 8px;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
        }

        @media (max-width: 1024px) {
            .mobile-menu-toggle {
                display: flex;
            }
            .topbar-center {
                display: none;
            }
        }

        .content-shell {
            flex: 1;
            padding: 24px 28px 32px;
            width: 100%;
            max-width: 100%;
        }

        .content-card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 18px;
            box-shadow: 0 14px 30px rgba(16, 24, 40, 0.08);
            overflow: hidden;
            width: 100%;
            min-height: calc(100vh - 56px);
        }

        

        

        

        .content-body {
            padding: 24px;
        }

        .content-body,
        .content-body * {
            box-sizing: border-box;
        }

        .content-body {
            color: #0f172a;
        }

        .content-body h1,
        .content-body h2,
        .content-body h3,
        .content-body h4,
        .content-body h5 {
            letter-spacing: -0.015em;
        }

        .content-body p,
        .content-body span,
        .content-body label,
        .content-body td,
        .content-body th,
        .content-body li,
        .content-body input,
        .content-body select,
        .content-body textarea,
        .content-body button {
            font-family: "DM Sans", "Space Grotesk", ui-sans-serif, system-ui, sans-serif;
        }

        .content-body input:not([type="checkbox"]):not([type="radio"]):not([type="file"]),
        .content-body select,
        .content-body textarea,
        .content-body .input-pill,
        .content-body .input-premium {
            width: 100%;
            border: 1.5px solid #d9dfe8;
            border-radius: 10px;
            background: #fff;
            color: #1f2937;
            padding: 11px 13px;
            font-size: 14px;
            font-weight: 600;
            transition: all 150ms ease;
            outline: none;
        }

        .content-body input:not([type="checkbox"]):not([type="radio"]):not([type="file"]):focus,
        .content-body select:focus,
        .content-body textarea:focus,
        .content-body .input-pill:focus,
        .content-body .input-premium:focus {
            border-color: #0f6bb6;
            box-shadow: 0 0 0 3px rgba(15, 107, 182, 0.12);
        }

        .content-body .card-main,
        .content-body .app-main-card,
        .content-body .card-premium {
            background: #fff;
            border: 1px solid #e5e9f2;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(16, 24, 40, 0.10), 0 2px 8px rgba(16, 24, 40, 0.05);
            overflow: hidden;
        }

        .content-body .btn-premium,
        .content-body .btn-sofofa {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 10px;
            border: 1px solid transparent;
            color: #fff;
            background: linear-gradient(135deg, #0b5fa5 0%, #0f6bb6 100%);
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            text-decoration: none;
            transition: all 180ms ease;
            box-shadow: 0 4px 12px rgba(15, 107, 182, 0.25);
        }

        .content-body .btn-premium:hover,
        .content-body .btn-sofofa:hover {
            background: linear-gradient(135deg, #0a4f86 0%, #0b5fa5 100%);
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(15, 107, 182, 0.35);
        }

        .content-body .table-sofofa {
            width: 100%;
            border-collapse: collapse;
        }

        .content-body .table-sofofa thead th {
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #5b6473;
            background: linear-gradient(180deg, #f8fafc 0%, #f1f4f8 100%);
            border-bottom: 2px solid #e3e8f0;
            padding: 14px 16px;
            font-weight: 700;
        }

        .content-body .table-sofofa tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #f0f3f8;
            font-size: 14px;
            color: #1f2937;
            font-weight: 500;
            vertical-align: middle;
        }

        .content-body .table-sofofa tbody tr:hover {
            background: rgba(15, 107, 182, 0.04);
            transition: background 120ms ease;
        }

        .content-body [class*="bg-slate-50"] {
            background-color: #f8fafc;
        }

        .content-body .inline-flex.rounded-lg,
        .content-body .inline-flex.rounded-xl,
        .content-body .inline-flex.rounded-2xl {
            white-space: nowrap;
        }

        @media (max-width: 1366px) {
            .brand {
                margin-left: 92px;
            }

            .header-titles {
                margin-left: 20px;
            }

            .content-shell {
                padding: 20px 22px 26px;
            }
        }

        @media (max-width: 1100px) {
            .brand {
                margin-left: 44px;
            }

            .topbar-logo-card {
                height: 44px;
                padding: 5px 18px;
            }

            .topbar-logo {
                height: 28px;
            }

            .header-titles {
                margin-left: 14px;
                padding-left: 12px;
            }

            .content-shell {
                padding: 16px 14px 20px;
            }

            

            .content-body {
                padding: 18px;
            }
        }

        @media (max-width: 900px) {
            .topbar-inner {
                gap: 12px;
            }

            .toolbar-actions {
                gap: 8px;
            }

            .btn {
                padding: 9px 14px;
                font-size: 13px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            .topbar {
                padding: 12px 14px;
            }

            .topbar-inner {
                flex-wrap: wrap;
                gap: 10px;
                justify-content: space-between;
            }

            .brand {
                margin-left: 4px;
                top: 1px;
            }

            .topbar-logo-card {
                width: auto;
                height: 38px;
                padding: 5px 10px;
            }

            .topbar-logo {
                height: 24px;
            }

            .content-shell {
                padding: 16px;
            }

            .content-card {
                min-height: auto;
            }
        }
    
        /* New Hero Header for Pages */
        .page-hero {
            background: linear-gradient(135deg, #0f6bb6 0%, #1b7dc8 100%);
            border-radius: 18px;
            color: #fff;
            padding: 14px 20px;
            box-shadow: 0 8px 20px rgba(15, 107, 182, 0.12);
            position: relative;
            overflow: hidden;
            margin: 14px 18px 10px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .page-hero::before {
            content: '';
            position: absolute;
            right: -60px;
            top: -80px;
            width: 220px;
            height: 220px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.13);
            pointer-events: none;
        }
        .page-hero-inner {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 18px;
        }
        .page-hero-copy {
            min-width: 0;
            flex: 1;
        }
        .page-hero h1 {
            margin: 0;
            font-size: clamp(19px, 2.2vw, 26px);
            line-height: 1.08;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: #fff;
        }
        .page-hero p {
            margin: 6px 0 0;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.92);
            max-width: 62ch;
        }
        .page-hero-btn {
            position: relative;
            z-index: 2;
        }
        @media (max-width: 768px) {
            .page-hero {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
        }

    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <script>
        (function() {
            try {
                if (localStorage.getItem('sidebarCollapsed') === 'true') {
                    document.body.classList.add('sidebar-pref-collapsed');
                }
            } catch (e) {
                // Ignore storage read issues.
            }
        })();
    </script>
    <div class="page">
        <aside class="sidebar is-initializing" id="sidebar">
            <div class="sidebar-header" style="justify-content: center; border-bottom: 1px solid rgba(255,255,255,0.1);">
                <div class="brand-icon" style="width: 100%; height: 50px; display: flex; align-items: center; justify-content: center; padding: 0.5rem;">
                    <img src="{{ asset('rendicion_legacy/images/logo-sofofa-white.png') }}" alt="SOFOFA Logo" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                </div>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('rendicion.dashboard') }}" class="nav-item {{ request()->routeIs('rendicion.dashboard') ? 'active' : '' }}" title="Inicio">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="nav-label">Inicio</span>
                </a>

                <a href="{{ route('rendicion.expenses.index') }}" class="nav-item {{ request()->routeIs('rendicion.expenses.index') ? 'active' : '' }}" title="Gastos">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="nav-label">Gastos</span>
                </a>

                <a href="{{ route('rendicion.reports.index') }}" class="nav-item {{ request()->routeIs('rendicion.reports.index') || request()->routeIs('rendicion.reports.show') ? 'active' : '' }}" title="Rendiciones">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="nav-label">Rendiciones</span>
                </a>

                <a href="{{ route('rendicion.expenses.project') }}" class="nav-item {{ request()->routeIs('rendicion.expenses.project') ? 'active' : '' }}" title="Gastos Proyecto">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <span class="nav-label">Gastos Proyecto</span>
                </a>

                <a href="{{ route('rendicion.reports.project') }}" class="nav-item {{ request()->routeIs('rendicion.reports.project') ? 'active' : '' }}" title="Rendición Proyecto">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    <span class="nav-label">Rendición Proyecto</span>
                </a>

                <a href="{{ route('rendicion.expenses.drafts') }}" class="nav-item {{ request()->routeIs('rendicion.expenses.drafts') ? 'active' : '' }}" title="Borradores de Gastos">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span class="nav-label">Borradores de Gastos</span>
                </a>

                <a href="{{ route('rendicion.analytics.index') }}" class="nav-item {{ request()->routeIs('rendicion.analytics.*') ? 'active' : '' }}" title="Estadísticas">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span class="nav-label">Estadísticas</span>
                </a>



                @if(auth()->user()->isGestor() || auth()->user()->isAprobador())
                    <a href="{{ route('rendicion.reports.inbox') }}" class="nav-item {{ request()->routeIs('rendicion.reports.inbox') ? 'active' : '' }}" title="Bandeja">
                        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0l-8 5-8-5"/>
                        </svg>
                        <span class="nav-label">Bandeja</span>
                    </a>
                @endif

                @if(auth()->user()->isAdmin())
                    <a href="{{ route('rendicion.users.index') }}" class="nav-item {{ request()->routeIs('rendicion.users.*') ? 'active' : '' }}" title="Usuarios">
                        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1m-4 6h-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2h5m10-10a4 4 0 10-8 0 4 4 0 008 0z"/>
                        </svg>
                        <span class="nav-label">Usuarios</span>
                    </a>


                    <a href="{{ route('rendicion.config.index') }}" class="nav-item {{ request()->routeIs('rendicion.config.*') ? 'active' : '' }}" title="Configuración">
                        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.527-.94 3.31.843 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.427 1.756 2.925 0 3.352a1.724 1.724 0 00-1.066 2.572c.94 1.527-.842 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.427 1.756-2.925 1.756-3.352 0a1.724 1.724 0 00-2.572-1.066c-1.527.94-3.31-.842-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.427-1.756-2.925 0-3.352a1.724 1.724 0 001.066-2.572c-.94-1.527.842-3.31 2.37-2.37.996.614 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="nav-label">Configuracion</span>
                    </a>
                @endif

            </nav>

            <button class="toggle-btn" onclick="toggleSidebar()" title="Contraer/Expandir menú">
                <svg class="toggle-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
        </aside>

        <div class="main-content">
            <header class="topbar">
                <div class="topbar-inner">
                    <button class="mobile-menu-toggle" onclick="toggleSidebar()" aria-label="Abrir menú">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" x2="21" y1="6" y2="6"></line>
                            <line x1="3" x2="21" y1="18" y2="18"></line>
                        </svg>
                    </button>
                    <div class="brand">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <div style="line-height: 1.25;">
                                <h1 style="font-size: 16px; font-weight: 800; color: white; margin: 0; letter-spacing: -0.01em;">Rendición Gastos</h1>
                                <p style="font-size: 11px; color: rgba(255, 255, 255, 0.8); margin: 0; text-transform: uppercase; font-weight: 700; letter-spacing: 0.02em;">Fundación SOFOFA</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="topbar-center">
                        <div class="topbar-badge">
                            <span>Portal de Gestión</span>
                        </div>
                    </div>

                    <div class="toolbar-actions">
                        @if($additionalButtons)
                            <div style="display: flex; gap: 8px; padding-right: 12px; border-right: 1px solid rgba(255,255,255,0.15);">
                                {{ $additionalButtons }}
                            </div>
                        @endif
                        
                        @if($backRoute)
                            <a href="{{ is_string($backRoute) ? $backRoute : 'javascript:history.back();' }}" class="btn-logout">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                {{ $backLabel }}
                            </a>
                        @endif

                        @auth
                            <div class="user-info-badge">
                                <div class="user-avatar">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div style="line-height: 1.2; display: block;">
                                    <div style="font-size: 12px; font-weight: 700; color: white; white-space: nowrap;">{{ Auth::user()->name }}</div>
                                    <div style="font-size: 10px; color: rgba(255, 255, 255, 0.6); text-transform: uppercase; font-weight: 600; letter-spacing: 0.02em;">
                                        @php
                                            $roles = Auth::user()->getRoleNames();
                                            $mainRole = $roles->first() ?? 'Usuario';
                                        @endphp
                                        {{ str_replace('_', ' ', $mainRole) }}
                                    </div>
                                </div>
                            </div>

                            @if($showLogout)
                                <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                                    @csrf
                                    <button type="submit" class="btn-logout">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        <span>Volver</span>
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>
            </header>

            <div class="content-shell">
                <div class="content-card">
                    @if (trim((string) $page_title))
                        <div class="page-hero">
                            <div class="page-hero-inner">
                                <div class="page-hero-copy">
                                    <h1>{{ $page_title }}</h1>
                                    @if (trim((string) $page_subtitle))
                                        <p>{{ $page_subtitle }}</p>
                                    @endif
                                </div>
                            </div>
                            @if(request()->route() && !request()->routeIs('dashboard'))
                                <div class="page-hero-btn">
                                    <a href="javascript:history.back();" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-[13px] font-bold text-sofofa-blue bg-white border border-white/20 rounded-xl hover:bg-slate-50 transition-all shadow-sm" style="color: #0f6bb6;">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
                                        Volver
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if(session('error'))
                        <div style="margin: 0 22px 14px; border: 1px solid #fecaca; background: #fef2f2; color: #991b1b; border-radius: 12px; padding: 11px 13px; font-size: 13px; font-weight: 700;">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div style="margin: 0 22px 14px; border: 1px solid #86efac; background: #f0fdf4; color: #166534; border-radius: 12px; padding: 11px 13px; font-size: 13px; font-weight: 700;">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="content-body ui-system">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            if (!sidebar) return;

            sidebar.classList.toggle('collapsed');
            const isCollapsed = sidebar.classList.contains('collapsed');
            document.body.classList.toggle('sidebar-pref-collapsed', isCollapsed);
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        }

        (function() {
            const sidebar = document.getElementById('sidebar');
            if (!sidebar) return;

            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
                sidebar.classList.add('collapsed');
                document.body.classList.add('sidebar-pref-collapsed');
            } else {
                sidebar.classList.remove('collapsed');
                document.body.classList.remove('sidebar-pref-collapsed');
            }

            requestAnimationFrame(() => {
                sidebar.classList.remove('is-initializing');
            });
        })();

        function formatCurrency(input) {
            let value = input.value.replace(/\D/g, '');
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            input.value = value;
        }

        document.addEventListener('submit', function(e) {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                if (form.contains(e.target)) {
                    const currencyInputs = form.querySelectorAll('input[data-type="currency"]');
                    currencyInputs.forEach(input => {
                        input.value = input.value.replace(/\./g, '');
                    });
                }
            });
        });
    </script>
</body>
</html>
