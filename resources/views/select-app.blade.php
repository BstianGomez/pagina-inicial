<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal Unificado</title>
    <style>
        :root {
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color-scheme: light;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            background: #f8fafc;
            color: #0f172a;
        }

        .container {
            width: min(100%, 1180px);
            margin: 0 auto;
            padding: 2rem 1.25rem 3rem;
        }

        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.75rem;
        }

        .header-card {
            flex: 1 1 0;
            display: none;
        }

        .logout-wrap {
            display: flex;
            justify-content: flex-end;
            width: 100%;
        }

        .logout-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.65rem;
            padding: 0.95rem 1.25rem;
            border-radius: 999px;
            border: 1px solid rgba(15, 23, 42, 0.08);
            background: #ffffff;
            color: #0f172a;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform 200ms ease, box-shadow 200ms ease, border-color 200ms ease;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        }

        .logout-button:hover {
            transform: translateY(-1px);
            border-color: rgba(15, 23, 42, 0.12);
        }

        .panel {
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.06);
            border-radius: 2rem;
            box-shadow: 0 24px 80px rgba(15, 23, 42, 0.08);
            padding: 2.5rem;
        }

        .panel-header {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .panel-header h2 {
            margin: 0;
            font-size: 1.85rem;
            font-weight: 800;
        }

        .panel-header p {
            margin: 0;
            color: #64748b;
            font-size: 1rem;
            line-height: 1.8;
            max-width: 42rem;
            margin-left: auto;
            margin-right: auto;
        }

        .cards-row {
            display: flex;
            justify-content: center;
            align-items: stretch;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .app-card {
            position: relative;
            width: min(100%, 330px);
            min-height: 350px;
            border-radius: 30px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            text-decoration: none;
            color: inherit;
            padding: 2rem;
            transition: transform 300ms ease, box-shadow 300ms ease;
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: 0 18px 50px rgba(15, 23, 42, 0.08);
        }

        .app-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 90px rgba(15, 23, 42, 0.14);
        }

        .card-badge {
            width: 72px;
            height: 72px;
            display: grid;
            place-items: center;
            border-radius: 22px;
            margin-bottom: 1.5rem;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
        }

        .card-title {
            margin: 0 0 0.75rem;
            font-size: 1.35rem;
            font-weight: 800;
            color: #0f172a;
        }

        .card-desc {
            margin: 0;
            color: #64748b;
            font-size: 1rem;
            line-height: 1.75;
            min-height: 80px;
        }

        .card-cta {
            margin-top: 1.75rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            padding: 0.95rem 1.3rem;
            border-radius: 16px;
            background: #f8fafc;
            color: #0f172a;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            border: 1px solid rgba(15, 23, 42, 0.08);
            transition: transform 200ms ease, background 200ms ease;
        }

        .app-card:hover .card-cta {
            transform: translateY(-1px);
        }

        .card-oc .card-badge { background: linear-gradient(135deg, #dbeafe, #eff6ff); color: #1d4ed8; }
        .card-viajes .card-badge { background: linear-gradient(135deg, #ede9ff, #f5f3ff); color: #6d28d9; }
        .card-rendicion .card-badge { background: linear-gradient(135deg, #d9f7ec, #e6fffa); color: #047857; }

        .footer-note {
            text-align: center;
            margin-top: 1.5rem;
            color: #64748b;
            font-size: 0.95rem;
        }

        @media (max-width: 900px) {
            .header-row { flex-direction: column; align-items: stretch; }
            .logout-wrap { justify-content: flex-start; }
            .cards-row { gap: 1rem; }
        }

        @media (max-width: 660px) {
            .container { padding: 1.5rem 1rem 2rem; }
            .app-card { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-row">
            <div class="logout-wrap">
                <form method="POST" action="{{ route('force-logout') }}">
                    @csrf
                    <button type="submit" class="logout-button">Salir</button>
                </form>
            </div>
        </div>

        <div class="panel">
            <div class="panel-header">
                <h2>Selecciona tu módulo</h2>
                <p>Si tienes más de una aplicación asignada, elige la herramienta donde quieras continuar.</p>
            </div>

            <div class="cards-row">
                @if(in_array('oc', $apps))
                <a href="{{ url('/oc/oc') }}" class="app-card card-oc">
                    <div class="card-badge">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 5h18" />
                            <path d="M7 5v14" />
                            <path d="M17 5v14" />
                            <path d="M3 19h18" />
                            <path d="M10 8h4" />
                            <path d="M10 12h4" />
                            <path d="M10 16h4" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="card-title">Módulo OC</h3>
                        <p class="card-desc">Administra órdenes de compra, revisa solicitudes y gestiona aprobaciones desde un panel claro y moderno.</p>
                    </div>
                    <div class="card-cta">Ingresar</div>
                </a>
                @endif

                @if(in_array('viajes', $apps))
                <a href="{{ url('/viajes/mis-solicitudes') }}" class="app-card card-viajes">
                    <div class="card-badge">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 12h18" />
                            <path d="M12 3v18" />
                            <path d="M7 7l3.5 3.5L7 14" />
                            <path d="M14 7l3.5 3.5L14 14" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="card-title">Gestión Viajes</h3>
                        <p class="card-desc">Revisa solicitudes, gestiona itinerarios y controla viajes desde una interfaz ordenada.</p>
                    </div>
                    <div class="card-cta">Ingresar</div>
                </a>
                @endif

                @if(in_array('rendicion', $apps))
                <a href="{{ url('/rendicion/informes') }}" class="app-card card-rendicion">
                    <div class="card-badge">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 6v12" />
                            <path d="M6 12h12" />
                            <path d="M7 6h10" />
                            <path d="M7 18h10" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="card-title">Rendición Gastos</h3>
                        <p class="card-desc">Controla tus informes y administra los gastos con seguimiento intuitivo y transparencia.</p>
                    </div>
                    <div class="card-cta">Ingresar</div>
                </a>
                @endif

                @if($is_super_admin)
                <a href="{{ route('admin.users.index') }}" class="app-card" style="background: linear-gradient(135deg, #f8fafc, #f1f5f9); border: 2px dashed #cbd5e1;">
                    <div class="card-badge" style="background: #0f172a; color: #fff;">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="card-title text-indigo-600">Panel Admin</h3>
                        <p class="card-desc">Gestión global de usuarios, permisos y configuración del sistema para todos los módulos.</p>
                    </div>
                    <div class="card-cta" style="background: #0f172a; color: #fff;">Gestionar</div>
                </a>
                @endif
            </div>

            <div class="footer-note">Si no encuentras tu aplicación, comunícate con soporte para actualizar tus permisos.</div>
        </div>
    </div>
</body>
</html>
