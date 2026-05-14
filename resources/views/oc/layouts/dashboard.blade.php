<!DOCTYPE html>
<html lang="es" style="background-color: #f5f7fb;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Orden de Compra') | Modern Portal</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        @include('oc.partials.common_styles')
    </style>

    <style>
        :root {
            --brand-primary: #0f6bb6;
            --brand-secondary: #b69950;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --bg-glass: rgba(255, 255, 255, 0.9);
            --border-color: rgba(226, 232, 240, 0.8);
            --shadow-premium: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f8fafc;
            color: var(--text-main);
            overflow: hidden;
            margin: 0;
            padding: 0;
        }

        .page {
            display: flex;
            height: 100vh;
            width: 100vw;
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto; /* Scrollbar at the screen edge */
            background: radial-gradient(circle at 0% 0%, rgba(15, 107, 182, 0.03) 0%, transparent 50%),
                        radial-gradient(circle at 100% 100%, rgba(182, 153, 80, 0.03) 0%, transparent 50%);
        }

        .main-content > *:first-child {
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .content {
            flex: 1;
            padding: 1.5rem 2rem;
        }

        /* ── Modern Table Card ────────────────────────── */
        .ms-table-card {
            background: var(--bg-glass);
            backdrop-filter: blur(12px);
            border-radius: 1.5rem;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-premium);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .ms-table-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            background: rgba(248, 250, 252, 0.5);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .ms-table-title {
            font-size: 1rem;
            font-weight: 800;
            color: var(--text-main);
            margin: 0;
        }

        .ms-table-wrapper {
            overflow-x: auto;
        }

        .ms-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .ms-table th {
            background: #fcfdfe;
            padding: 1rem 1.25rem;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border-color);
            text-align: left;
            white-space: nowrap;
        }

        .ms-table td {
            padding: 1.25rem;
            border-bottom: 1px solid rgba(226, 232, 240, 0.4);
            font-size: 0.85rem;
            vertical-align: middle;
            transition: background 0.2s;
        }

        .ms-table tr:hover td {
            background: rgba(15, 107, 182, 0.02);
        }

        /* ── Modern Banner ────────────────────────────── */
        .ms-banner {
            background: linear-gradient(135deg, #0f6bb6 0%, #1a7dc8 100%);
            padding: 2.5rem;
            border-radius: 1.5rem;
            margin-bottom: 2rem;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(15, 107, 182, 0.2);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .ms-banner::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            pointer-events: none;
        }

        .ms-banner-title {
            font-family: 'Outfit', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            margin: 0 0 0.5rem;
            letter-spacing: -0.02em;
        }

        .ms-banner-sub {
            font-size: 1rem;
            opacity: 0.9;
            font-weight: 500;
            margin: 0;
        }

        /* ── KPI Cards ────────────────────────────────── */
        .ms-kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .ms-kpi {
            background: var(--bg-glass);
            backdrop-filter: blur(12px);
            padding: 1.5rem;
            border-radius: 1.25rem;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-premium);
            display: flex;
            align-items: center;
            gap: 1.25rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .ms-kpi:hover {
            transform: translateY(-5px);
            border-color: var(--brand-primary);
        }

        .ms-kpi-icon {
            width: 52px;
            height: 52px;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .ms-kpi-body {
            display: flex;
            flex-direction: column;
        }

        .ms-kpi-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-main);
            line-height: 1;
            font-family: 'Outfit', sans-serif;
        }

        .ms-kpi-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.25rem;
        }

        /* ── Filters & Search ─────────────────────────── */
        .ms-filters {
            background: var(--bg-glass);
            backdrop-filter: blur(12px);
            padding: 1.25rem;
            border-radius: 1.25rem;
            border: 1px solid var(--border-color);
            margin-bottom: 1.5rem;
            display: flex;
            gap: 1rem;
            align-items: center;
            box-shadow: var(--shadow-premium);
            flex-wrap: wrap;
        }

        .ms-search-wrap {
            position: relative;
            flex: 1;
            min-width: 200px;
        }

        .ms-search-icon {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            pointer-events: none;
        }

        .ms-search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 3rem;
            border-radius: 1rem;
            border: 1.5px solid var(--border-color);
            background: rgba(248, 250, 252, 0.8);
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s;
            outline: none;
        }

        .ms-search-input:focus {
            border-color: var(--brand-primary);
            background: white;
            box-shadow: 0 0 0 4px rgba(15, 107, 182, 0.1);
        }

        .ms-select {
            padding: 0.75rem 2.5rem 0.75rem 1.25rem;
            border-radius: 1rem;
            border: 1.5px solid var(--border-color);
            background: rgba(248, 250, 252, 0.8);
            font-size: 0.9rem;
            font-weight: 600;
            outline: none;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b' stroke-width='2.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1rem;
        }

        /* ── Buttons ──────────────────────────────────── */
        .ms-btn-reset {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            border-radius: 1rem;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            border: 1.5px solid var(--border-color);
            background: white;
            color: var(--text-main);
        }

        .ms-btn-reset:hover {
            transform: translateY(-2px);
            background: #f8fafc;
            border-color: var(--brand-primary);
            color: var(--brand-primary);
        }

        .ms-btn-new {
            background: var(--brand-primary);
            background: white;
            color: var(--brand-primary);
            font-weight: 800;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            position: relative;
            z-index: 2;
        }

        .ms-btn-new:hover {
            background: #0d5ea1;
            box-shadow: 0 12px 25px rgba(15, 107, 182, 0.3);
            color: white;
        }

        .ms-btn-excel {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            z-index: 2;
        }
        .ms-btn-excel:hover {
            background: #059669;
            box-shadow: 0 12px 25px rgba(16, 185, 129, 0.3);
            color: white;
        }

        /* ── Chips ────────────────────────────────────── */
        .chip {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 0.85rem;
            border-radius: 2rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        /* ── Modals ───────────────────────────────────── */
        .ms-modal {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(10px);
            z-index: 1000;
            display: none;
            padding: 2rem 1rem;
            opacity: 0;
            transition: opacity 0.3s ease;
            overflow-y: auto; /* Scroll at the screen edge */
        }

        .swal2-container {
            z-index: 3000 !important;
        }

        .ms-modal.active {
            display: block; /* Change from flex to block to allow natural scroll */
            opacity: 1;
        }

        .ms-modal-content {
            background: white;
            width: 100%;
            margin: auto;
            border-radius: 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transform: scale(0.95);
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
        }

        .ms-modal.active .ms-modal-content {
            transform: scale(1);
        }

        .ms-modal-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(248, 250, 252, 0.5);
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; border: 2px solid transparent; background-clip: padding-box; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        @media (max-width: 1024px) {
            .page { flex-direction: column; }
            .content { padding: 1rem; }
        }
    </style>
    @stack('styles')
</head>
<body style="background-color: #f5f7fb;">

    <div class="page">
        @include('oc.partials.sidebar', ['active' => $active ?? ''])

        <main class="main-content">
            <x-oc.page-header title="" subtitle="" :backRoute="null" :showLogout="true" />

            <div class="content">
                @yield('header')
                
                @if(session('success'))
                    <div style="background: #dcfce7; border: 1px solid #bbf7d0; color: #166534; padding: 1.25rem; border-radius: 1rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem; font-weight: 600;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 1.25rem; border-radius: 1rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem; font-weight: 600;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @include('oc.partials.common_scripts')
    @stack('scripts')
</body>
</html>
