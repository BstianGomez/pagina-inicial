<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Portal de Viajes') | Modern Portal</title>
    
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
            overflow: hidden;
            background: radial-gradient(circle at 0% 0%, rgba(15, 107, 182, 0.03) 0%, transparent 50%),
                        radial-gradient(circle at 100% 100%, rgba(182, 153, 80, 0.03) 0%, transparent 50%);
        }

        .content {
            flex: 1;
            padding: 1.5rem 2rem;
            overflow-y: auto;
            scrollbar-width: thin;
        }

        /* ── Modern Table override for Viajes ──────────────── */
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

        .ms-table-count {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 600;
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
            background: rgba(248, 250, 252, 0.8);
            padding: 1rem;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            color: var(--text-muted);
            letter-spacing: 0.5px;
            text-align: left;
            border-bottom: 2px solid var(--border-color);
        }

        .ms-table td {
            padding: 1rem;
            font-size: 0.85rem;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
            transition: all 0.2s;
        }

        .ms-table tr:hover td {
            background: rgba(15, 107, 182, 0.02);
        }

        /* ── Chips ────────────────────── */
        .chip {
            padding: 0.35rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.7rem;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            text-transform: uppercase;
        }

        .chip-pendiente { background: #fffbeb; color: #b45309; }
        .chip-info { background: #eff6ff; color: #1e40af; }
        .chip-aprobado { background: #ecfdf5; color: #065f46; }
        .chip-rechazado { background: #fef2f2; color: #991b1b; }
        .chip-gestionado { background: #f5f3ff; color: #7c3aed; }
        .chip-interno { background: #e0f2fe; color: #0369a1; }
        .chip-externo { background: #fef3c7; color: #92400e; }

        /* ── KPI Cards ──────────────── */
        .ms-kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
        }

        .ms-kpi {
            background: var(--bg-glass);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-color);
            border-radius: 1.25rem;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-premium);
            cursor: pointer;
        }

        .ms-kpi:hover { transform: translateY(-5px); border-color: var(--brand-primary); }
        .ms-kpi.active { border-color: var(--brand-primary); box-shadow: 0 0 0 3px rgba(15, 107, 182, 0.1); }

        .ms-kpi-icon {
            width: 44px;
            height: 44px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .ms-kpi-body { display: flex; flex-direction: column; }
        .ms-kpi-label { font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); }
        .ms-kpi-value { font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 800; color: var(--text-main); line-height: 1.2; }
        .ms-kpi-desc { font-size: 0.7rem; color: var(--text-muted); }

        /* ── Banner ────────────────── */
        .ms-banner {
            background: linear-gradient(135deg, var(--brand-primary) 0%, #1e40af 100%);
            border-radius: 1.5rem;
            padding: 2rem 2.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-premium);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .ms-banner::after {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            pointer-events: none;
        }

        .ms-banner-title { font-family: 'Outfit', sans-serif; font-size: 1.75rem; font-weight: 800; margin: 0; }
        .ms-banner-sub { font-size: 0.95rem; opacity: 0.9; margin-top: 0.5rem; }

        /* ── Modals ────────────────── */
        .ms-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            padding: 2rem;
        }

        .ms-modal.active { display: flex; }

        .ms-modal-content {
            background: white;
            width: 100%;
            max-width: 800px;
            max-height: 90vh;
            border-radius: 2rem;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            animation: modalIn 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.95) translateY(20px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        .ms-modal-header {
            padding: 1.5rem 2.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fcfdfe;
        }

        .ms-modal-body { padding: 2.5rem; overflow-y: auto; }

        /* ── Form Styles ───────────── */
        .ms-filters {
            background: var(--bg-glass);
            backdrop-filter: blur(10px);
            padding: 1rem 1.5rem;
            border-radius: 1.25rem;
            margin-bottom: 1.5rem;
            border: 1px solid var(--border-color);
            display: flex;
            gap: 1rem;
            align-items: center;
            box-shadow: var(--shadow-premium);
            flex-wrap: wrap;
        }

        .ms-search-wrap { flex: 1; min-width: 200px; position: relative; }
        .ms-search-icon { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); width: 18px; }
        .ms-search-input { width: 100%; padding: 0.75rem 1rem 0.75rem 2.75rem; border-radius: 0.75rem; border: 1.5px solid var(--border-color); font-size: 0.9rem; transition: all 0.2s; }
        .ms-search-input:focus { border-color: var(--brand-primary); outline: none; box-shadow: 0 0 0 4px rgba(15, 107, 182, 0.1); }

        .ms-select { padding: 0.75rem 2.5rem 0.75rem 1rem; border-radius: 0.75rem; border: 1.5px solid var(--border-color); background: white; font-size: 0.9rem; font-weight: 600; cursor: pointer; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b' stroke-width='2.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19.5 8.25l-7.5 7.5-7.5-7.5' /%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1rem; }

        .ms-btn-excel, .ms-btn-new, .ms-btn-reset {
            padding: 0.75rem 1.5rem; border-radius: 0.75rem; font-weight: 700; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.5rem; cursor: pointer; transition: all 0.2s; border: none; color: white; text-decoration: none;
            position: relative; z-index: 2;
        }
        .ms-btn-excel { background: #10b981; }
        .ms-btn-new { background: #f59e0b; }
        .ms-btn-reset { background: #f1f5f9; color: #475569; border: 1px solid var(--border-color); }
        
        .ms-btn-excel:hover, .ms-btn-new:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }

        /* Toast / Swal custom */
        .swal-modern-confirm { background-color: var(--brand-primary) !important; border-radius: 12px !important; }
        .swal-modern-cancel { background-color: #64748b !important; border-radius: 12px !important; }
    </style>
    @stack('styles')
</head>
<body>
    <div class="page">
        @include('viajes.partials.sidebar')

        <main class="main-content">
            <x-oc.page-header 
                :title="$__env->yieldContent('title', 'Portal de Viajes')" 
                :subtitle="$__env->yieldContent('subtitle', 'Gestión de Solicitudes')"
                :backRoute="null"
                :showLogout="true"
                module="Viajes"
                logoutRoute="viajes.logout"
            />

            <div class="content">
                @yield('header')
                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
