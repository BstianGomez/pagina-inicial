<x-layouts.app
    page_title=""
    page_subtitle="">

    <style>
        .is-duplicate-row {
            background-color: #fff1f2 !important;
            border-left: 2px solid #e11d48 !important;
        }

        .is-duplicate-row:hover {
            background-color: #ffe4e6 !important;
        }

        .badge-duplicate {
            display: inline-flex;
            align-items: center;
            padding: 0.15rem 0.4rem;
            border-radius: 0.25rem;
            font-size: 0.55rem;
            font-weight: 900;
            background: #e11d48;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 0.3rem;
            max-width: fit-content;
        }

        .content-header {
            padding: 16px 22px;
        }

        .content-header h1 {
            font-size: 18px;
            margin: 0 0 3px;
        }

        .content-header p {
            font-size: 12px;
            margin: 0;
        }

        .dash-hero {
            background: linear-gradient(135deg, #0f6bb6 0%, #1b7dc8 100%);
            border-radius: 18px;
            color: #fff;
            padding: 20px 24px;
            box-shadow: 0 12px 24px rgba(15, 107, 182, 0.18);
            position: relative;
            overflow: hidden;
            margin-bottom: 18px;
        }

        .dash-hero-inner {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 18px;
        }

        .dash-fixed-fund {
            margin-bottom: 18px;
            border: 1px solid #d9e8fb;
            border-radius: 16px;
            background: linear-gradient(140deg, #f5f9ff 0%, #e9f3ff 100%);
            box-shadow: 0 10px 24px rgba(15, 107, 182, 0.12);
            padding: 16px 18px;
            display: grid;
            gap: 12px;
        }

        .dash-fixed-fund-title {
            margin: 0;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #1e4f8d;
        }

        .dash-fixed-fund-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .dash-fixed-fund-item {
            border-radius: 12px;
            background: #ffffff;
            border: 1px solid #dbe8f7;
            padding: 12px;
        }

        .dash-fixed-fund-label {
            margin: 0;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #617a98;
        }

        .dash-fixed-fund-value {
            margin: 4px 0 0;
            font-size: 27px;
            line-height: 1.1;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: #0f3f76;
        }

        .dash-hero-copy {
            min-width: 0;
            flex: 1;
        }

        .dash-hero::before {
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

        .dash-hero h2 {
            margin: 0;
            font-size: clamp(19px, 2.2vw, 26px);
            line-height: 1.08;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .dash-hero p {
            margin: 6px 0 0;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.92);
            max-width: 62ch;
        }

        .dash-draft-alert {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 16px;
            padding: 16px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            animation: slideDown 0.4s ease-out;
        }

        .dash-draft-alert-icon {
            background: #fef3c7;
            color: #d97706;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .dash-draft-alert-icon svg {
            width: 24px;
            height: 24px;
        }

        .dash-draft-alert-copy {
            flex: 1;
        }

        .dash-draft-alert-copy strong {
            display: block;
            font-size: 14px;
            color: #92400e;
        }

        .dash-draft-alert-copy p {
            margin: 2px 0 0;
            font-size: 13px;
            color: #b45309;
        }

        .dash-draft-alert-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .dash-draft-alert-link {
            font-size: 13px;
            font-weight: 700;
            color: #92400e;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 10px;
            transition: all 0.2s;
        }

        .dash-draft-alert-link:hover {
            background: #fef3c7;
            text-decoration: underline;
        }

        .dash-draft-alert-btn {
            background: #f59e0b;
            color: #fff;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
        }

        .dash-draft-alert-btn:hover {
            background: #d97706;
            transform: translateY(-1px);
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dash-quick-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 18px;
        }

        .dash-quick-card {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            text-decoration: none;
            background: #fff;
            border: 1px solid #e5e9f2;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 6px 14px rgba(16, 24, 40, 0.07);
            transition: transform 170ms ease, box-shadow 170ms ease, border-color 170ms ease;
        }

        .dash-quick-card:hover {
            transform: translateY(-3px);
            border-color: #bdd6ec;
            box-shadow: 0 12px 24px rgba(15, 107, 182, 0.14);
        }

        .dash-quick-icon {
            height: 34px;
            width: 34px;
            min-width: 34px;
            border-radius: 10px;
            background: #eaf3ff;
            color: #0f6bb6;
            display: grid;
            place-items: center;
        }

        .dash-quick-icon svg {
            width: 17px;
            height: 17px;
        }

        .dash-quick-title {
            margin: 0;
            color: #0f172a;
            font-size: 14px;
            font-weight: 800;
            letter-spacing: -0.01em;
        }

        .dash-quick-copy {
            margin: 2px 0 0;
            color: #5b6473;
            font-size: 12px;
            line-height: 1.35;
        }

        .dash-stats {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 10px;
            margin-bottom: 18px;
        }

        .dash-stat {
            --stat-accent: #94a3b8;
            appearance: none;
            width: 100%;
            text-align: left;
            font: inherit;
            text-decoration: none;
            color: inherit;
            background: #fff;
            border: 1px solid #e5e9f2;
            border-left: 4px solid #f8fafc;
            border-radius: 12px;
            padding: 14px 16px;
            box-shadow: 0 5px 12px rgba(16, 24, 40, 0.06);
            cursor: pointer;
            transition: border-color 140ms ease, box-shadow 140ms ease, transform 140ms ease;
            position: relative;
        }

        .dash-stat::after {
            content: '';
            position: absolute;
            top: 10px;
            right: 12px;
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: transparent;
            border: 1px solid transparent;
            transition: all 140ms ease;
        }

        .dash-stat:hover {
            transform: translateY(-1px);
        }

        .dash-stat.is-active {
            border-left-width: 6px;
            box-shadow: 0 9px 18px rgba(16, 24, 40, 0.09);
        }

        .dash-stat.is-active::after {
            background: var(--stat-accent);
            border-color: rgba(255, 255, 255, 0.75);
        }

        .dash-stat--requested {
            --stat-accent: #60a5fa;
            border-left-color: #60a5fa;
            background: #fff !important;
            border-color: #e5e9f2 !important;
        }

        .dash-stat--requested.is-active {
            background: #fff !important;
            border-color: #e5e9f2 !important;
            border-left-color: #60a5fa !important;
        }

        .dash-stat--in-process {
            --stat-accent: #eab308;
            border-left-color: #eab308;
        }

        .dash-stat--accepted {
            --stat-accent: #2563eb;
            border-left-color: #2563eb;
        }

        .dash-stat--rejected {
            --stat-accent: #dc2626;
            border-left-color: #dc2626;
        }

        .dash-stat--reimbursed {
            --stat-accent: #15803d;
            border-left-color: #15803d;
        }

        .dash-stat-label {
            margin: 0;
            text-transform: uppercase;
            color: #8ea1bc;
            letter-spacing: 0.11em;
            font-size: 10px;
            font-weight: 800;
        }

        .dash-stat-value {
            margin: 6px 0 0;
            color: #0f172a;
            font-size: 26px;
            line-height: 1;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .dash-table-shell {
            background: #fff;
            border: 1px solid #e5e9f2;
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(16, 24, 40, 0.08), 0 2px 6px rgba(16, 24, 40, 0.04);
            overflow: hidden;
        }

        .dash-table-head {
            padding: 16px 20px;
            border-bottom: 1px solid rgba(227, 232, 240, 0.8);
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .dash-table-head h3 {
            margin: 0;
            color: #0f172a;
            font-size: 18px;
            letter-spacing: -0.03em;
            font-weight: 800;
        }

        .dash-table-head p {
            margin: 3px 0 0;
            color: #5b6473;
            font-size: 12px;
            font-weight: 500;
        }

        .dash-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 10px;
            border: 1px solid #cfe2ff;
            color: #0b5fa5;
            background: #fff;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 800;
            text-decoration: none;
        }

        .dash-btn:hover {
            background: #eff6ff;
            border-color: #3b82f6;
        }

        .dash-table-shell .table-sofofa th {
            padding-top: 13px;
            padding-bottom: 13px;
            font-size: 8px;
        }

        .dash-table-shell .table-sofofa td {
            padding-top: 15px;
            padding-bottom: 15px;
        }

        @media (max-width: 1024px) {
            .dash-quick-grid { grid-template-columns: 1fr; }
            .dash-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 768px) {
            .dash-hero-inner {
                flex-direction: column;
                align-items: flex-start;
            }

            .dash-fixed-fund-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="dash-hero">
        <div class="dash-hero-inner">
            <div class="dash-hero-copy">
                <h2>Bienvenido, {{ auth()->user()->name }}</h2>
                <p>Revisa tu actividad reciente y accede rapido a las acciones mas usadas.</p>
            </div>
        </div>
    </div>

    @if($draftExpensesCount > 0)
        <div class="dash-draft-alert">
            <div class="dash-draft-alert-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div class="dash-draft-alert-copy">
                <strong>Tienes {{ $draftExpensesCount }} gasto(s) en borrador sin rendir.</strong>
                <p>Completa tu rendición para que puedan ser procesados.</p>
            </div>
            <div class="dash-draft-alert-actions">
                <a href="{{ route('expenses.drafts') }}" class="dash-draft-alert-link">Gestionar Borradores</a>
                <a href="{{ route('expenses.create') }}" class="dash-draft-alert-btn">Crear Rendición</a>
            </div>
        </div>
    @endif

    @if(!empty($fixedFundSummary))
        <section class="dash-fixed-fund">
            <h3 class="dash-fixed-fund-title">Resumen Fondo Fijo</h3>
            <div class="dash-fixed-fund-grid">
                <div class="dash-fixed-fund-item">
                    <p class="dash-fixed-fund-label">Te queda</p>
                    <p class="dash-fixed-fund-value">${{ number_format($fixedFundSummary['remaining'], 0, ',', '.') }}</p>
                </div>
                <div class="dash-fixed-fund-item">
                    <p class="dash-fixed-fund-label">Has usado</p>
                    <p class="dash-fixed-fund-value">${{ number_format($fixedFundSummary['used'], 0, ',', '.') }}</p>
                </div>
            </div>
        </section>
    @endif

    <div class="dash-quick-grid">
        <a href="{{ route('expenses.index') }}" class="dash-quick-card">
            <span class="dash-quick-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            </span>
            <span>
                <h3 class="dash-quick-title">Ingresar nuevo gasto</h3>
                <p class="dash-quick-copy">Agrega varios gastos y envialos en una sola rendicion.</p>
            </span>
        </a>

        <a href="{{ route('reports.index') }}" class="dash-quick-card">
            <span class="dash-quick-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </span>
            <span>
                <h3 class="dash-quick-title">Ver Rendiciones</h3>
                <p class="dash-quick-copy">Consulta estado, filtros y detalle de tus solicitudes.</p>
            </span>
        </a>

        @if(auth()->user()->hasAnyRole(['Gestor', 'Aprobador', 'Admin', 'Superadmin']))
            <a href="{{ route('reports.inbox') }}" class="dash-quick-card">
                <span class="dash-quick-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0l-8 5-8-5"/></svg>
                </span>
                <span>
                    <h3 class="dash-quick-title">Bandeja de Revision</h3>
                    <p class="dash-quick-copy">Gestiona rendiciones pendientes de accion.</p>
                </span>
            </a>
        @else
            <a href="{{ route('reports.index') }}" class="dash-quick-card">
                <span class="dash-quick-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7h8M8 11h8m-8 4h5m5 4H6a2 2 0 01-2-2V7a2 2 0 012-2h7.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V17a2 2 0 01-2 2z"/></svg>
                </span>
                <span>
                    <h3 class="dash-quick-title">Mis Rendiciones</h3>
                    <p class="dash-quick-copy">Consulta historial, estados y montos en un solo lugar.</p>
                </span>
            </a>
        @endif

        <a href="{{ route('analytics.index') }}" class="dash-quick-card">
            <span class="dash-quick-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </span>
            <span>
                <h3 class="dash-quick-title">Analíticas / Gráficos</h3>
                <p class="dash-quick-copy">Revisa resúmenes estadísticos visuales.</p>
            </span>
        </a>
    </div>

    @php
        $toggleInProcessUrl = $statusGroup === 'in_process'
            ? route('dashboard')
            : route('dashboard', ['status_group' => 'in_process']);
        $toggleAcceptedUrl = $statusGroup === 'accepted'
            ? route('dashboard')
            : route('dashboard', ['status_group' => 'accepted']);
        $toggleRejectedUrl = $statusGroup === 'rejected'
            ? route('dashboard')
            : route('dashboard', ['status_group' => 'rejected']);
        $toggleReimbursedUrl = $statusGroup === 'reimbursed'
            ? route('dashboard')
            : route('dashboard', ['status_group' => 'reimbursed']);
    @endphp

    <div class="dash-stats">
        <a href="{{ route('dashboard') }}" class="dash-stat dash-stat--requested {{ empty($statusGroup) ? 'is-active' : '' }}">
            <p class="dash-stat-label">Solicitadas</p>
            <h3 class="dash-stat-value">{{ $stats['total_count'] }}</h3>
        </a>
        <a href="{{ $toggleInProcessUrl }}" class="dash-stat dash-stat--in-process {{ $statusGroup === 'in_process' ? 'is-active' : '' }}">
            <p class="dash-stat-label">En Proceso</p>
            <h3 class="dash-stat-value">{{ $stats['in_process'] }}</h3>
        </a>
        <a href="{{ $toggleAcceptedUrl }}" class="dash-stat dash-stat--accepted {{ $statusGroup === 'accepted' ? 'is-active' : '' }}">
            <p class="dash-stat-label">Aceptadas</p>
            <h3 class="dash-stat-value">{{ $stats['accepted'] }}</h3>
        </a>
        <a href="{{ $toggleRejectedUrl }}" class="dash-stat dash-stat--rejected {{ $statusGroup === 'rejected' ? 'is-active' : '' }}">
            <p class="dash-stat-label">Rechazadas</p>
            <h3 class="dash-stat-value">{{ $stats['rejected'] }}</h3>
        </a>
        <a href="{{ $toggleReimbursedUrl }}" class="dash-stat dash-stat--reimbursed {{ $statusGroup === 'reimbursed' ? 'is-active' : '' }}">
            <p class="dash-stat-label">Reembolsadas</p>
            <h3 class="dash-stat-value">{{ $stats['reimbursed'] }}</h3>
        </a>
    </div>

    <div class="dash-table-shell">
        <div class="dash-table-head">
            <div>
                <h3>{{ $statusGroup ? 'Rendiciones Filtradas' : 'Rendiciones' }}</h3>
                <p>{{ $statusGroup ? 'Mostrando resultados completos del estado seleccionado.' : 'Navega por todas tus rendiciones con paginacion.' }}</p>
            </div>
            <a href="{{ route('reports.index') }}" class="dash-btn">Ver todo</a>
        </div>

        <div class="overflow-x-auto">
            <table class="table-sofofa">
                <thead>
                    <tr>
                        <th class="text-left px-3 min-w-[50px] whitespace-nowrap uppercase tracking-widest text-[9px] font-bold text-slate-500 border-b border-slate-100">ID</th>
                        <th class="text-left px-3 min-w-[50px] whitespace-nowrap uppercase tracking-widest text-[9px] font-bold text-slate-500 border-b border-slate-100">CECO</th>
                        <th class="text-left px-3 min-w-[90px] whitespace-nowrap uppercase tracking-widest text-[9px] font-bold text-slate-500 border-b border-slate-100">CATEGORÍA</th>
                        <th class="text-left px-3 min-w-[120px] whitespace-nowrap uppercase tracking-widest text-[9px] font-bold text-slate-500 border-b border-slate-100">TÍTULO</th>
                        <th class="text-left px-3 min-w-[130px] whitespace-nowrap uppercase tracking-widest text-[9px] font-bold text-slate-500 border-b border-slate-100">ESTADO</th>
                        <th class="text-left px-3 min-w-[100px] whitespace-nowrap uppercase tracking-widest text-[9px] font-bold text-slate-500 border-b border-slate-100">SOLICITANTE</th>
                        <th class="text-right px-3 min-w-[100px] whitespace-nowrap uppercase tracking-widest text-[9px] font-bold text-slate-500 border-b border-slate-100">MONTO</th>
                        <th class="w-10 px-3 text-center border-b border-slate-100"></th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($reports as $report)
                        @php
                            $isDraft = $report->status === 'Borrador';
                            $route = $isDraft ? route('expenses.createStep1', $report->id) : route('reports.show', $report->id);
                            $category = $report->expenses->first()->category ?? null;
                            $categoryName = $category->name ?? 'Gasto';

                            $baseCategories = ['insumos computacionales', 'alimentacion', 'movilizacion', 'hospedaje', 'papeleria', 'otros'];
                            $normalizedCategory = \Illuminate\Support\Str::of($categoryName)->ascii()->lower()->trim()->value();
                            $isCustomCategory = (bool) ($category?->is_custom ?? false) || !in_array($normalizedCategory, $baseCategories, true);

                            if ($isCustomCategory) {
                                $categoryColor = [
                                    'bg' => '#fff7ed',
                                    'text' => '#9a3412',
                                    'border' => '#fdba74',
                                ];
                            } else {
                                $categoryColorMap = [
                                    'alimentacion' => ['bg' => '#fef3c7', 'text' => '#92400e', 'border' => '#fcd34d'],
                                    'pasajes aereos' => ['bg' => '#e0f2fe', 'text' => '#0c4a6e', 'border' => '#7dd3fc'],
                                    'insumos computacionales' => ['bg' => '#ede9fe', 'text' => '#5b21b6', 'border' => '#c4b5fd'],
                                    'movilizacion' => ['bg' => '#dcfce7', 'text' => '#166534', 'border' => '#86efac'],
                                    'hospedaje' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'border' => '#fca5a5'],
                                    'papeleria' => ['bg' => '#ffedd5', 'text' => '#9a3412', 'border' => '#fdba74'],
                                    'otros' => ['bg' => '#e0e7ff', 'text' => '#3730a3', 'border' => '#a5b4fc'],
                                ];

                                if (isset($categoryColorMap[$normalizedCategory])) {
                                    $categoryColor = $categoryColorMap[$normalizedCategory];
                                } else {
                                    $categoryPalette = [
                                        ['bg' => '#e0eeff', 'text' => '#0b4f86', 'border' => '#8fc0f0'],
                                        ['bg' => '#e9f7ef', 'text' => '#166534', 'border' => '#86efac'],
                                        ['bg' => '#fef3c7', 'text' => '#92400e', 'border' => '#fcd34d'],
                                        ['bg' => '#ede9fe', 'text' => '#5b21b6', 'border' => '#c4b5fd'],
                                        ['bg' => '#fee2e2', 'text' => '#991b1b', 'border' => '#fca5a5'],
                                        ['bg' => '#d1fae5', 'text' => '#065f46', 'border' => '#6ee7b7'],
                                    ];
                                    $paletteIndex = ((int) sprintf('%u', crc32($normalizedCategory))) % count($categoryPalette);
                                    $categoryColor = $categoryPalette[$paletteIndex];
                                }
                            }

                            $statusMap = [
                                'Borrador' => ['dot' => '#eab308', 'bg' => '#fffbeb', 'text' => '#92400e', 'border' => '#fde68a'],
                                'Pendiente aprobación jefatura' => ['dot' => '#2563eb', 'bg' => '#eff6ff', 'text' => '#1e40af', 'border' => '#bfdbfe'],
                                'Subsanada por solicitante (jefatura)' => ['dot' => '#2563eb', 'bg' => '#eff6ff', 'text' => '#1e40af', 'border' => '#bfdbfe'],
                                'Aprobada por jefatura' => ['dot' => '#1d4ed8', 'bg' => '#dbeafe', 'text' => '#1e3a8a', 'border' => '#93c5fd'],
                                'En revisión' => ['dot' => '#4f46e5', 'bg' => '#eef2ff', 'text' => '#3730a3', 'border' => '#c7d2fe'],
                                'Subsanada por solicitante (Gestor)' => ['dot' => '#4f46e5', 'bg' => '#eef2ff', 'text' => '#3730a3', 'border' => '#c7d2fe'],
                                'Aprobada por gestor' => ['dot' => '#0f766e', 'bg' => '#ccfbf1', 'text' => '#115e59', 'border' => '#99f6e4'],
                                'Pendiente pago' => ['dot' => '#d97706', 'bg' => '#fff7ed', 'text' => '#9a3412', 'border' => '#fed7aa'],
                                'Reembolsada' => ['dot' => '#15803d', 'bg' => '#dcfce7', 'text' => '#166534', 'border' => '#86efac'],
                                'Rechazada por jefatura' => ['dot' => '#dc2626', 'bg' => '#fef2f2', 'text' => '#b91c1c', 'border' => '#fecaca'],
                                'Rechazada por gestor' => ['dot' => '#dc2626', 'bg' => '#fef2f2', 'text' => '#b91c1c', 'border' => '#fecaca'],
                                'Observada por jefatura' => ['dot' => '#ea580c', 'bg' => '#fff7ed', 'text' => '#c2410c', 'border' => '#fed7aa'],
                                'Observada por gestor' => ['dot' => '#ea580c', 'bg' => '#fff7ed', 'text' => '#c2410c', 'border' => '#fed7aa'],
                            ];
                            $cfg = $statusMap[$report->status] ?? $statusMap['Borrador'];

                            // Detectar si es posiblemente un duplicado iterando sus gastos y validando con boletas de la DB
                            $isDuplicate = $report->has_duplicate_expenses;
                            
                            $rowClasses = $isDuplicate ? 'is-duplicate-row bg-red-50/20 hover:bg-red-50/80 transition-colors duration-150 group cursor-pointer' : 'hover:bg-slate-50/80 transition-colors duration-150 group cursor-pointer';
                        @endphp
                        <tr class="{{ $rowClasses }}" onclick="window.location='{{ $route }}'">
                            <td class="font-bold text-slate-400 text-[10px] px-3 py-6">#{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="font-bold text-slate-700 text-[10px] px-3 py-6">{{ $report->expenses->first()->ceco->code ?? 'N/A' }}</td>
                            <td class="px-3 py-6">
                                <span style="display:inline-flex;align-items:center;padding:0.3rem 0.6rem;border-radius:0.6rem;font-size:0.58rem;font-weight:900;background:{{ $categoryColor['bg'] }};color:{{ $categoryColor['text'] }};border:1px solid {{ $categoryColor['border'] }};text-transform:uppercase;letter-spacing:0.06em;">
                                    {{ $categoryName }}
                                </span>
                            </td>
                            <td class="font-medium text-slate-800 text-[12px] px-3 py-6">
                                <span class="truncate max-w-[200px] block" style="{{ $isDuplicate ? 'color: #be123c;' : '' }}">{{ $report->title }}</span>
                                @if($isDuplicate)
                                    @php
                                        $dupes = $report->duplicate_reports;
                                        $dupeText = $dupes->count() > 0 ? 'Repetida con rep. #' . $dupes->pluck('id')->implode(', #') : 'Repetida';
                                        $titleText = $dupes->count() > 0 ? 'Coincide con las rendiciones: #' . $dupes->pluck('id')->implode(', #') : 'Posible solicitud duplicada por coincidir mismo RUT y monto.';
                                    @endphp
                                    <span class="badge-duplicate mt-1" title="{{ $titleText }}">
                                        <svg style="width:10px;height:10px;margin-right:3px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        {{ $dupeText }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 py-6">
                                <span style="display:inline-flex;align-items:center;padding:0.3rem 0.55rem;border-radius:0.6rem;font-size:0.58rem;font-weight:800;background:{{ $cfg['bg'] }};color:{{ $cfg['text'] }};border:1px solid {{ $cfg['border'] }};white-space:nowrap;">
                                    <span style="height:0.38rem;width:0.38rem;border-radius:999px;background:{{ $cfg['dot'] }};margin-right:0.36rem;"></span>
                                    {{ $report->status }}
                                </span>
                            </td>
                            <td class="text-slate-600 text-[10px] font-medium px-3 py-6">{{ $report->user->name }}</td>
                            <td class="text-right font-bold text-slate-900 text-[12px] px-3 py-6">${{ number_format($report->total_amount, 0, ',', '.') }}</td>
                            <td class="text-center py-6">
                                <a href="{{ $route }}" class="text-slate-300 group-hover:text-sofofa-blue transition-all">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-slate-400 font-medium">No hay rendiciones para mostrar</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($reports, 'hasPages') && $reports->hasPages())
            <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/40">
                {{ $reports->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
