<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inicio Usuario - Aplicación OC</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|dm-sans:400,500,600" rel="stylesheet" />

    <style>
        @include('oc.partials.common_styles')

        .main-content {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content {
            padding: 28px 24px 42px;
        }

        .hero {
            background: linear-gradient(135deg, #0f6bb6 0%, #1b7dc8 100%);
            color: #fff;
            border-radius: 20px;
            padding: 28px;
            box-shadow: 0 14px 36px rgba(15, 107, 182, 0.25);
            margin-bottom: 22px;
        }

        .hero h1 {
            margin: 0 0 6px;
            font-size: 26px;
            font-family: "Space Grotesk", "DM Sans", ui-sans-serif, system-ui, sans-serif;
        }

        .hero p {
            margin: 0;
            font-size: 15px;
            opacity: 0.95;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .quick-card {
            background: #fff;
            border: 1px solid #e3e8f0;
            border-radius: 14px;
            padding: 16px;
            text-decoration: none;
            color: #101828;
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.08);
            transition: transform 160ms ease, box-shadow 160ms ease;
        }

        .quick-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.12);
        }

        .quick-title {
            margin: 0 0 6px;
            font-size: 16px;
            font-weight: 700;
        }

        .quick-subtitle {
            margin: 0;
            color: #5b6473;
            font-size: 13px;
            line-height: 1.4;
        }

        .panel {
            background: #fff;
            border: 1px solid #e3e8f0;
            border-radius: 14px;
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.08);
            overflow: hidden;
        }

        .panel-header {
            padding: 16px 18px;
            border-bottom: 1px solid #edf2f7;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .panel-title {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
        }

        .panel-body {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 720px;
        }

        th, td {
            padding: 12px 14px;
            text-align: left;
            border-bottom: 1px solid #edf2f7;
            font-size: 13px;
        }

        th {
            background: #f8fafc;
            color: #475467;
            font-size: 12px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .empty {
            padding: 22px;
            color: #667085;
            text-align: center;
            font-size: 14px;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 12px;
            font-weight: 700;
            background: #eaf3ff;
            color: #0b5fa5;
        }

        @media (max-width: 1024px) {
            .quick-actions {
                grid-template-columns: 1fr;
            }

            .content {
                padding: 16px;
            }
        }
    </style>
    @include('oc.partials.common_scripts')
</head>
<body>
    <div class="page">
        @include('oc.partials.sidebar')

        <main class="main-content">
            <header class="topbar">
                <div>
                    <h1 style="margin: 0;">Inicio de Usuario</h1>
                    <p style="margin: 6px 0 0; color: #dbeafe;">Panel inicial exclusivo para el rol usuario.</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Cerrar sesión</button>
                </form>
            </header>

            <section class="content">
                <div class="hero">
                    <h1>Bienvenido, {{ auth()->user()->name }}</h1>
                    <p>Este es tu inicio personalizado. Desde aquí puedes crear solicitudes y revisar tus últimas órdenes sin entrar al listado general.</p>
                </div>

                <div class="quick-actions">
                    <a class="quick-card" href="{{ route('oc.cliente') }}">
                        <h3 class="quick-title">Nueva OC Cliente</h3>
                        <p class="quick-subtitle">Crear solicitud para servicios de cliente.</p>
                    </a>
                    <a class="quick-card" href="{{ route('oc.interna') }}">
                        <h3 class="quick-title">Nueva OC Interna</h3>
                        <p class="quick-subtitle">Generar solicitud interna del equipo.</p>
                    </a>
                    <a class="quick-card" href="{{ route('oc.negocio') }}">
                        <h3 class="quick-title">Nueva OC Negocio</h3>
                        <p class="quick-subtitle">Solicitar órdenes vinculadas a unidades de negocio.</p>
                    </a>
                </div>

                <div class="panel">
                    <div class="panel-header">
                        <h2 class="panel-title">Tus últimas solicitudes</h2>
                        <a href="{{ route('oc.enviadas') }}" style="font-size: 13px; color: #0b5fa5; text-decoration: none; font-weight: 700;">Ver enviadas</a>
                    </div>
                    <div class="panel-body">
                        @if($recentRequests->count() > 0)
                            <table>
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Proveedor</th>
                                        <th>Descripción</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentRequests as $request)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($request->created_at)->format('d/m/Y') }}</td>
                                            <td>{{ $request->tipo_solicitud ?? 'N/A' }}</td>
                                            <td>{{ $request->proveedor ?? 'N/A' }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($request->descripcion ?? 'Sin descripción', 60) }}</td>
                                            <td><span class="chip">{{ $request->estado ?? 'Solicitada' }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="empty">Aún no tienes solicitudes registradas.</div>
                        @endif
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
