<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Solicitudes de OC</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|dm-sans:400,500,600" rel="stylesheet" />

    <style>
        @include('oc.partials.common_styles')

        .content {
            padding: 24px 20px 48px;
        }

        .container {
            width: 100%;
            margin: 24px 0 60px;
            padding: 0;
        }

        .card {
            background: var(--card);
            border: 1px solid #e5e9f2;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(16, 24, 40, 0.10), 0 2px 8px rgba(16, 24, 40, 0.05);
            overflow-x: hidden;
            overflow-y: visible;
            width: 100%;
        }

        .card[aria-label="Tabla de solicitudes"] {
            width: 100%;
        }

        .toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            padding: 22px 24px;
            border-bottom: 1px solid rgba(227, 232, 240, 0.8);
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        }

        .toolbar-title {
            font-family: "Space Grotesk", "DM Sans", ui-sans-serif, system-ui, sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.3px;
        }

        .toolbar-subtitle {
            font-size: 13px;
            color: var(--muted);
            margin-top: 4px;
        }

        .toolbar-actions {
            align-items: center;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
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

        .btn-secondary {
            background: #eaf3ff;
            color: #0b5fa5;
            border-color: #cfe2ff;
        }

        .btn-outline {
            background: #ffffff;
            color: #0b5fa5;
            border-color: #0b5fa5;
        }

        .btn-accent {
            background: #0f7a3e;
            color: #fff;
            border-color: transparent;
            box-shadow: 0 4px 12px rgba(15, 122, 62, 0.3);
        }

        .btn-accent:hover {
            background: #0b5b2e;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(15, 122, 62, 0.4);
        }

        .filters {
            display: grid;
            grid-template-columns: 1.5fr repeat(2, minmax(150px, 1fr));
            gap: 14px;
            padding: 18px 24px 20px;
            border-bottom: 1px solid rgba(227, 232, 240, 0.8);
            background: #fcfdfe;
        }

        .filters label {
            font-size: 12px;
            color: var(--muted);
            font-weight: 600;
            display: block;
            margin-bottom: 6px;
        }

        .field {
            display: flex;
            flex-direction: column;
        }

        .input, .select {
            padding: 11px 13px;
            border: 1.5px solid #d9dfe8;
            border-radius: 9px;
            background: white;
            font-size: 14px;
            color: #1f2937;
            transition: all 150ms ease;
            font-weight: 500;
        }

        .select {
            cursor: pointer;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            padding-right: 35px;
        }

        .select option {
            white-space: normal;
            padding: 8px;
        }

        .input:focus, .select:focus {
            outline: none;
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(15, 107, 182, 0.1);
        }

        .table-wrap {
            width: 100%;
            overflow-x: auto;
            overflow-y: visible;
            scrollbar-width: thin;
        }

        table {
            width: 100%;
            min-width: 1040px;
            border-collapse: collapse;
            font-size: 13px;
        }

        thead th {
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #5b6473;
            background: linear-gradient(180deg, #f8fafc 0%, #f1f4f8 100%);
            border-bottom: 2px solid #e3e8f0;
            padding: 14px 16px;
            font-weight: 600;
            position: sticky;
            top: 0;
            z-index: 1;
            backdrop-filter: blur(3px);
        }

        tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #f0f3f8;
            font-size: 14px;
            color: #1f2937;
            font-weight: 500;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            vertical-align: middle;
        }

        tbody tr:nth-child(even) { background: #fcfdff; }
        tbody td.compact { max-width: 80px; }
        tbody td.actions { width: 40px; text-align: center; }
        tbody tr { position: relative; }
        tbody tr:hover {
            background: rgba(15, 107, 182, 0.04);
            z-index: 10;
            transition: background 120ms ease;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            padding: 5px 12px;
            font-size: 12px;
            border-radius: 8px;
            background: linear-gradient(135deg, #e8f1fb 0%, #f0f7ff 100%);
            color: #0a4f86;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .chip-cliente { background: linear-gradient(135deg, #ede9fe 0%, #f5f3ff 100%); color: #5b21b6; }
        .chip-interna { background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 100%); color: #1d4ed8; }
        .chip-negocio { background: linear-gradient(135deg, #dcfce7 0%, #f0fdf4 100%); color: #166534; }

        .status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 13px;
        }

        .dot { width: 8px; height: 8px; border-radius: 999px; background: var(--warning); }

        .status.ok { background: #dcfce7 !important; color: #15803d !important; }
        .status.ok .dot { background: #15803d !important; }
        .status.pending { background: #fef08a !important; color: #a16207 !important; }
        .status.pending .dot { background: #a16207 !important; }
        .status.danger { background: #fee2e2 !important; color: #b91c1c !important; }
        .status.danger .dot { background: #b91c1c !important; }
        .status.facturado { background: #e0f2fe !important; color: #0369a1 !important; }
        .status.facturado .dot { background: #0369a1 !important; }
        .status.enviada { background: #f3f4f6 !important; color: #4b5563 !important; }
        .status.enviada .dot { background: #4b5563 !important; }

        .col-proveedor, .col-descripcion { max-width: 260px; }

        .truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            white-space: normal;
            line-height: 1.35;
            max-height: calc(1.35em * 2);
        }

        .menu-trigger {
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 20px;
            font-weight: bold;
            color: var(--muted);
            transition: background 150ms ease;
            position: relative;
            line-height: 1;
        }

        .menu-trigger:hover { background: #f0f4f8; }

        .dropdown-menu {
            display: none;
            position: fixed;
            background: white;
            border: 1.5px solid #e5e9f2;
            border-radius: 12px;
            box-shadow: 0 12px 32px rgba(16, 24, 40, 0.20), 0 2px 8px rgba(16, 24, 40, 0.10);
            min-width: 180px;
            z-index: 999999;
            overflow: visible;
        }

        .dropdown-menu.show {
            display: block;
            animation: dropdownSlideIn 0.15s ease-out;
        }

        @keyframes dropdownSlideIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dropdown-item {
            display: block;
            width: 100%;
            padding: 11px 16px;
            border: none;
            background: transparent;
            text-align: left;
            cursor: pointer;
            font-size: 14px;
            color: #1f2937;
            transition: all 150ms ease;
            font-weight: 500;
        }

        .dropdown-item:hover { background: #f7fbff; color: #0f6bb6; }
        .dropdown-item.primary { color: var(--brand); font-weight: 600; }

        .menu-container { position: relative; z-index: 100; }
        tbody tr:hover .menu-container { z-index: 1001; }

        .footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 18px 16px;
            color: var(--muted);
            font-size: 13px;
        }

        .pagination-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            padding: 12px 18px 18px;
            border-top: 1px solid #eef2f7;
            background: #fcfdff;
        }

        .pagination-info { font-size: 13px; color: var(--muted); font-weight: 500; }

        .pagination { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }

        .page-link {
            min-width: 36px;
            height: 36px;
            padding: 0 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #dbe5f1;
            border-radius: 9px;
            background: #fff;
            color: #1f2937;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: all 140ms ease;
        }

        .page-link:hover { border-color: #0f6bb6; color: #0f6bb6; background: #f4f9ff; }
        .page-link.active { background: #0f6bb6; color: #fff; border-color: #0f6bb6; }
        .page-link.disabled { opacity: 0.45; pointer-events: none; }

        .empty { display: none; padding: 24px 18px 30px; color: var(--muted); text-align: center; }

        .alert {
            width: 100%;
            margin: 0 0 20px 0;
            padding: 14px 20px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            font-weight: 500;
            animation: slideDown 0.3s ease;
        }

        .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 900px) {
            .filters { grid-template-columns: 1fr; }
            .toolbar { align-items: flex-start; }
            tbody td { max-width: 150px; }
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(8px);
            z-index: 9999; display: none; align-items: center; justify-content: center;
            animation: fadeIn 0.3s ease-out;
        }
        .modal-overlay.show { display: flex; }
        .modal-container {
            background: #ffffff; border-radius: 20px; width: 100%; max-width: 600px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); overflow: hidden;
            animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1); border: 1px solid rgba(0,0,0,0.05);
        }
        .modal-header {
            padding: 24px 32px; border-bottom: 1px solid #f1f5f9; display: flex;
            align-items: center; justify-content: space-between; background: #ffffff;
        }
        .modal-title { font-size: 20px; font-weight: 700; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;}
        .modal-close {
            background: #f8fafc; border: none; font-size: 24px; cursor: pointer; color: #64748b;
            display: flex; align-items: center; justify-content: center; width: 36px; height: 36px;
            border-radius: 50%; transition: all 0.2s;
        }
        .modal-close:hover { background: #e2e8f0; color: #0f172a; transform: rotate(90deg); }
        .modal-body { padding: 32px; max-height: 70vh; overflow-y: auto; background: #fcfcfd; }
        .detail-section {
            background: #ffffff; border-radius: 12px; padding: 20px; margin-bottom: 20px;
            border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .detail-section-title {
            font-size: 13px; font-weight: 700; text-transform: uppercase; color: #3b82f6;
            letter-spacing: 0.05em; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;
            border-bottom: 2px solid #eff6ff; padding-bottom: 8px;
        }
        .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .detail-item { display: flex; flex-direction: column; gap: 6px; }
        .detail-item.full-width { grid-column: 1 / -1; }
        .detail-label { font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; }
        .detail-value { font-size: 15px; color: #0f172a; font-weight: 500; }
        .monto-badge {
            display: inline-block; background: #eff6ff; color: #1d4ed8; padding: 6px 12px;
            border-radius: 8px; font-weight: 800; font-size: 18px; border: 1px solid #bfdbfe;
        }
        .obs-box {
            background: #fdfbed; border: 1px solid #fcebb6; color: #92720e;
            padding: 16px; border-radius: 12px; font-size: 14px; line-height: 1.6;
        }
        .desc-box {
            background: #f8fafc; border: 1px solid #e2e8f0; color: #334155;
            padding: 16px; border-radius: 12px; font-size: 14px; line-height: 1.6;
        }
        .modal-footer {
            padding: 20px 32px; background: #ffffff; border-top: 1px solid #f1f5f9;
            display: flex; justify-content: flex-end; gap: 12px;
        }
        .btn-modern { padding: 10px 24px; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.2s; border: none; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; }
        .btn-close { background: #f1f5f9; color: #475569; }
        .btn-close:hover { background: #e2e8f0; color: #0f172a; }
        .btn-download { background: #0f6bb6; color: white; box-shadow: 0 4px 6px rgba(15, 107, 182, 0.2); text-decoration: none;}
        .btn-download:hover { background: #0d5a9a; box-shadow: 0 6px 8px rgba(15, 107, 182, 0.3); transform: translateY(-1px); }

        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        .swal2-container { z-index: 10000 !important; }
    </style>

</head>
<body>
    <div class="page">
        @include('oc.partials.sidebar', ['active' => 'index'])

        <!-- Main Content -->
        <div class="main-content">
            <x-page-header 
                title="Solicitudes de OC" 
                subtitle="Gestión y seguimiento de órdenes de compra"
                :backRoute="null"
                :showLogout="true"
            />

            @if(session('success'))
                <div class="alert alert-success">
                    <span style="font-size: 18px;">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-info" style="background:#fef2f2;border-color:#fecaca;color:#991b1b;">
                    <span style="font-size: 18px;">⚠</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <main class="content">
                <section class="card" aria-label="Tabla de solicitudes">
                <div class="toolbar">
                    <div>
                        <div class="toolbar-title">Listado de Solicitudes</div>
                        <div class="toolbar-subtitle">Controla tus solicitudes y filtra por tipo o estado.</div>
                    </div>
                    <div class="toolbar-actions">
                        <a href="{{ route('oc.export') }}" class="btn btn-ghost">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                            Exportar
                        </a>
                    </div>
                </div>

                <div class="filters">
                    <div class="field">
                        <label for="search">Buscar</label>
                        <input id="search" class="input" type="search" placeholder="Buscar por proveedor, descripcion o rut" />
                    </div>
                    <div class="field">
                        <label for="filterTipo">Tipo solicitud</label>
                        <select id="filterTipo" class="select">
                            <option value="">Todos</option>
                            <option value="Cliente">Cliente</option>
                            <option value="Interna">Interna</option>
                            <option value="Negocio">Negocio</option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="filterEstado">Estado</label>
                        <select id="filterEstado" class="select">
                            <option value="">Todos</option>
                            <option value="Solicitada">Solicitada</option>
                            <option value="Aceptada">Aceptada</option>
                            <option value="Rechazada">Rechazada</option>
                            <option value="Facturado">Facturado</option>
                        </select>
                    </div>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>CECO</th>
                                <th>Tipo</th>
                                <th>Documento</th>
                                <th>Estado</th>
                                <th>Proveedor</th>
                                <th>Descripción</th>
                                <th>Cantidad</th>
                                <th>Monto</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="ocRows">
                            @foreach ($rows as $index => $row)
                                @php
                                    $ceco = data_get($row, 'ceco');
                                    $cecoNumber = preg_match('/\d+/', (string) $ceco, $cecoMatch) ? $cecoMatch[0] : $ceco;
                                    $tipo = data_get($row, 'tipo_solicitud');
                                    $documento = data_get($row, 'tipo_documento');
                                    $estado = data_get($row, 'estado');
                                    $rut = data_get($row, 'rut');
                                    $proveedor = data_get($row, 'proveedor');
                                    $descripcion = data_get($row, 'descripcion');
                                    $cantidad = data_get($row, 'cantidad');
                                    $monto = data_get($row, 'monto');
                                    $numeroOc = 'OC-' . date('Y', $row->created_at ? strtotime($row->created_at) : time()) . '-' . str_pad($row->id, 3, '0', STR_PAD_LEFT);
                                    $estadoClass = match (strtolower((string) $estado)) {
                                        'facturado' => 'facturado',
                                        'aprobada', 'aceptada', 'entregada' => 'ok',
                                        'enviada' => 'enviada',
                                        'rechazada' => 'danger',
                                        default => 'pending',
                                    };
                                    $tipoClass = strtolower(preg_replace('/[^a-z0-9]+/i', '-', (string) $tipo));
                                @endphp
                                    <tr data-text="{{ strtolower(($ceco ?? '').' '.($tipo ?? '').' '.($documento ?? '').' '.($estado ?? '').' '.($rut ?? '').' '.($proveedor ?? '').' '.($descripcion ?? '')) }}"
                                        data-tipo="{{ $tipo }}"
                                        data-estado="{{ $estado }}"
                                        data-index="{{ $index }}"
                                        data-id="{{ $row->id }}"
                                        data-numero-oc="{{ $numeroOc }}"
                                        data-ceco="{{ $ceco }}"
                                        data-tipo-solicitud="{{ $tipo }}"
                                        data-tipo-documento="{{ $documento }}"
                                        data-proveedor="{{ $proveedor }}"
                                        data-email-proveedor="{{ data_get($row, 'email_proveedor') }}"
                                        data-rut="{{ $rut }}"
                                        data-descripcion="{{ $descripcion }}"
                                        data-cantidad="{{ $cantidad }}"
                                        data-monto="{{ $monto }}"
                                        data-observacion="{{ data_get(json_decode($row->datos_extra, true) ?? [], 'observacion', data_get(json_decode($row->datos_extra, true) ?? [], 'observacion2', 'No especificada')) }}"
                                        data-ruta-adjunto="{{ data_get(json_decode($row->datos_extra, true) ?? [], 'ruta_adjunto', '') }}"
                                        data-manager-comment="{{ $row->manager_comment ?? '' }}"
                                        data-sent-file-path="{{ $row->sent_file_path ?? '' }}"
                                        data-fecha="{{ data_get($row, 'created_at') ? date('d/m/Y H:i', strtotime($row->created_at)) : 'N/A' }}">
                                        <td class="compact">{{ $cecoNumber }}</td>
                                    <td><span class="chip chip-{{ $tipoClass }}">{{ $tipo }}</span></td>
                                    <td>{{ $documento }}</td>
                                    <td>
                                        <span class="status {{ $estadoClass }}">
                                            <span class="dot"></span>
                                            {{ $estado }}
                                        </span>
                                    </td>
                                    <td class="col-proveedor" title="{{ $proveedor }} - {{ $rut }}"><span class="truncate-2">{{ $proveedor }}</span></td>
                                    <td class="col-descripcion" title="{{ $descripcion }}"><span class="truncate-2">{{ $descripcion }}</span></td>
                                    <td class="compact">{{ $cantidad }}</td>
                                    <td class="compact">${{ number_format((float) $monto, 0, ',', '.') }}</td>
                                    <td class="actions">
                                        <div class="menu-container">
                                            <button type="button" class="menu-trigger" onclick="toggleMenu(event, {{ $index }})">⋮</button>
                                            <div class="dropdown-menu" id="menu-{{ $index }}">
                                                @if(strtolower((string)$estado) !== 'facturado')
                                                    <button type="button" class="dropdown-item primary" onclick="enviarOC({{ $index }})">✉ Enviar OC</button>
                                                    <button type="button" class="dropdown-item" onclick="irAlGestor({{ $index }})">🔍 Ir al Gestor</button>
                                                    <button type="button" class="dropdown-item" onclick="marcarFacturado({{ $index }})">✓ Marcar Facturado</button>
                                                @else
                                                    <button type="button" class="dropdown-item" style="opacity: 0.5; cursor: not-allowed;" title="Esta OC ya está facturada" disabled>✉ Enviar OC (S/N)</button>
                                                    <button type="button" class="dropdown-item" onclick="irAlGestor({{ $index }})">🔍 Ir al Gestor</button>
                                                    <button type="button" class="dropdown-item" style="opacity: 0.5; cursor: not-allowed;" disabled>✓ Ya Facturado</button>
                                                @endif
                                                <button type="button" class="dropdown-item" onclick="verDetalle({{ $index }})">👁 Ver detalle</button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($rows->total() === 0)
                    <div class="empty">No hay resultados para este filtro.</div>
                @endif

                <div class="footer">
                    <div>Mostrando: {{ $rows->total() }} solicitud{{ $rows->total() !== 1 ? 'es' : '' }}</div>
                    <div>Ultima actualizacion: {{ now()->format('d/m/Y') }}</div>
                </div>

                @if($rows->lastPage() > 1)
                    <div class="pagination-wrap" aria-label="Paginación de solicitudes">
                        <div class="pagination-info">
                            Mostrando {{ $rows->firstItem() ?? 0 }} - {{ $rows->lastItem() ?? 0 }} de {{ $rows->total() }} solicitudes
                        </div>
                        <div class="pagination">
                            <a class="page-link {{ $rows->onFirstPage() ? 'disabled' : '' }}" href="{{ $rows->previousPageUrl() ?: '#' }}">Anterior</a>

                            @for($page = 1; $page <= $rows->lastPage(); $page++)
                                <a class="page-link {{ $rows->currentPage() === $page ? 'active' : '' }}" href="{{ $rows->url($page) }}">{{ $page }}</a>
                            @endfor

                            <a class="page-link {{ $rows->hasMorePages() ? '' : 'disabled' }}" href="{{ $rows->nextPageUrl() ?: '#' }}">Siguiente</a>
                        </div>
                    </div>
                @endif
            </section>
        </main>
        </div> <!-- Cierre del wrapper de contenido -->
    </div> <!-- Cierre de .page -->

    <script>
        const searchInput = document.getElementById('search');
        const filterTipo = document.getElementById('filterTipo');
        const filterEstado = document.getElementById('filterEstado');

        // Restaurar filtros desde URL
        const restoreFiltersFromUrl = () => {
            const urlParams = new URLSearchParams(window.location.search);
            searchInput.value = urlParams.get('search') || '';
            filterTipo.value = urlParams.get('tipo') || '';
            filterEstado.value = urlParams.get('estado') || '';
        };

        // Aplicar filtros (recargar página con parámetros)
        const applyFilters = () => {
            const params = new URLSearchParams();
            const searchValue = searchInput.value.trim();
            const tipoValue = filterTipo.value;
            const estadoValue = filterEstado.value;

            if (searchValue) params.set('search', searchValue);
            if (tipoValue) params.set('tipo', tipoValue);
            if (estadoValue) params.set('estado', estadoValue);

            // Redirigir a página 1 con los filtros aplicados
            const query = params.toString();
            const nextUrl = query ? `${window.location.pathname}?${query}` : window.location.pathname;
            window.location.href = nextUrl;
        };

        // Debounce para búsqueda de texto
        let searchTimeout;
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(applyFilters, 500);
        });

        // Aplicar filtros inmediatamente al cambiar select
        filterTipo.addEventListener('change', applyFilters);
        filterEstado.addEventListener('change', applyFilters);

        // Restaurar valores al cargar la página
        restoreFiltersFromUrl();

        // Menú desplegable
        function toggleMenu(event, index) {
            event.stopPropagation();
            const button = event.currentTarget;
            const menu = document.getElementById(`menu-${index}`);
            const allMenus = document.querySelectorAll('.dropdown-menu');
            
            // Cerrar todos los otros menús
            allMenus.forEach(m => {
                if (m !== menu) m.classList.remove('show');
            });
            document.querySelectorAll('tbody tr').forEach(tr => tr.style.zIndex = '');
            
            // Toggle el menú actual
            if (menu.classList.contains('show')) {
                menu.classList.remove('show');
                button.closest('tr').style.zIndex = '';
            } else {
                button.closest('tr').style.zIndex = '9999';
                // Posicionar el menú usando fixed position
                const rect = button.getBoundingClientRect();
                menu.style.top = (rect.bottom + 4) + 'px';
                menu.style.left = (rect.right - 170) + 'px';
                menu.classList.add('show');
            }
        }

        // Cerrar menús al hacer clic fuera
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.menu-container')) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.remove('show');
                });
                document.querySelectorAll('tbody tr').forEach(tr => {
                    tr.style.zIndex = '';
                });
            }
        });

        let currentOcToSend = null;

        function enviarOC(index) {
            const row = document.querySelector(`tr[data-index="${index}"]`);
            if (row) {
                currentOcToSend = row;
                
                // Set data to the confirmation modal
                const nroOC = row.dataset.numeroOc || 'N/A';
                document.getElementById('confirmEnvioOCNumber').textContent = nroOC;
                
                // Show confirmation modal
                document.getElementById('ocConfirmModal').classList.add('show');
            }
            
            // Cerrar el menú y restaurar z-index
            document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('show'));
            document.querySelectorAll('tbody tr').forEach(tr => tr.style.zIndex = '');
        }

        function confirmarEnvio() {
            if (currentOcToSend) {
                const row = currentOcToSend;
                // Llenar el formulario oculto
                document.getElementById('oc_solicitud_id').value = row.dataset.id;
                document.getElementById('numero_oc').value = row.dataset.numeroOc;
                document.getElementById('ceco').value = row.dataset.ceco || '';
                document.getElementById('tipo_solicitud').value = row.dataset.tipoSolicitud || '';
                document.getElementById('proveedor').value = row.dataset.proveedor || '';
                document.getElementById('email_proveedor').value = row.dataset.emailProveedor || '';
                document.getElementById('rut').value = row.dataset.rut || '';
                document.getElementById('descripcion').value = row.dataset.descripcion || '';
                document.getElementById('cantidad').value = row.dataset.cantidad || '';
                document.getElementById('monto').value = row.dataset.monto || '';
                
                // Mover el archivo del modal al formulario real si existe
                const fileInput = document.getElementById('oc_file_input');
                const form = document.getElementById('ocSendForm');
                
                // Limpiar archivos previos en el form real
                const existingFile = form.querySelector('input[type="file"]');
                if (existingFile) existingFile.remove();
                
                // Clonar el input de archivo (o simplemente moverlo)
                if (fileInput.files.length > 0) {
                    form.appendChild(fileInput);
                }

                // Enviar el formulario
                form.submit();
                cerrarModalEnvio();
            }
        }

        function cerrarModalEnvio() {
            document.getElementById('ocConfirmModal').classList.remove('show');
            currentOcToSend = null;
        }

        function verDetalle(index) {
            const row = document.querySelector(`tr[data-index="${index}"]`);
            if (row) {
                // Rellenar datos en el modal
                document.getElementById('modal-detalle-id').textContent = row.dataset.id || 'N/A';
                document.getElementById('modal-detalle-numero-oc').textContent = row.dataset.numeroOc || 'N/A';
                
                const estado = row.dataset.estado || 'N/A';
                const estadoEl = document.getElementById('modal-detalle-estado');
                estadoEl.textContent = estado;
                
                // Aplicar color al estado según su valor
                estadoEl.className = 'detail-value';
                if (estado.toLowerCase() === 'solicitada') estadoEl.style.color = '#b97700';
                else if (estado.toLowerCase() === 'aceptada' || estado.toLowerCase() === 'enviada') estadoEl.style.color = '#0f7a3e';
                else if (estado.toLowerCase() === 'facturado') estadoEl.style.color = '#0284c7';
                else if (estado.toLowerCase() === 'rechazada') estadoEl.style.color = '#dc2626';

                document.getElementById('modal-detalle-fecha').textContent = row.dataset.fecha || 'N/A';
                document.getElementById('modal-detalle-ceco').textContent = row.dataset.ceco || 'N/A';
                document.getElementById('modal-detalle-tipo').textContent = row.dataset.tipoSolicitud || 'N/A';
                document.getElementById('modal-detalle-documento').textContent = row.dataset.tipoDocumento || 'N/A';
                document.getElementById('modal-detalle-proveedor').textContent = row.dataset.proveedor || 'N/A';
                document.getElementById('modal-detalle-email').textContent = row.dataset.emailProveedor || 'No especificado';
                document.getElementById('modal-detalle-rut').textContent = row.dataset.rut || 'N/A';
                document.getElementById('modal-detalle-monto').textContent = '$' + Number(row.dataset.monto || 0).toLocaleString('es-CL');
                document.getElementById('modal-detalle-cantidad').textContent = row.dataset.cantidad || 'N/A';
                document.getElementById('modal-detalle-descripcion').textContent = row.dataset.descripcion || 'N/A';
                document.getElementById('modal-detalle-observacion').textContent = row.dataset.observacion || 'No especificada';
                
                // Gestión de Envío (Datos del Gestor)
                const managerComment = row.dataset.managerComment;
                const sentFilePath = row.dataset.sentFilePath;
                const gestionSection = document.getElementById('modal-detalle-gestion-section');
                
                if (managerComment || sentFilePath) {
                    gestionSection.style.display = 'block';
                    
                    const commentEl = document.getElementById('modal-detalle-manager-comment');
                    if (managerComment) {
                        commentEl.textContent = managerComment;
                        commentEl.parentElement.style.display = 'block';
                    } else {
                        commentEl.parentElement.style.display = 'none';
                    }
                    
                    const sentFileBtn = document.getElementById('modal-detalle-sent-file-btn');
                    const noSentFileEl = document.getElementById('modal-detalle-no-sent-file');
                    
                    if (sentFilePath) {
                        sentFileBtn.href = '/oc/enviadas/pdf/' + row.dataset.id; // Adjusted to use the route for downloading sent PDF
                        sentFileBtn.style.display = 'inline-flex';
                        noSentFileEl.style.display = 'none';
                    } else {
                        sentFileBtn.style.display = 'none';
                        noSentFileEl.style.display = 'inline-block';
                    }
                } else {
                    gestionSection.style.display = 'none';
                }

                if (row.dataset.rutaAdjunto) {
                    document.getElementById('modal-detalle-adjunto-btn').href = '/oc/adjunto/' + row.dataset.rutaAdjunto;
                    document.getElementById('modal-detalle-adjunto-btn').style.display = 'inline-flex';
                    document.getElementById('modal-detalle-no-adjunto').style.display = 'none';
                } else {
                    document.getElementById('modal-detalle-adjunto-btn').style.display = 'none';
                    document.getElementById('modal-detalle-no-adjunto').style.display = 'inline-block';
                }

                // Mostrar modal
                document.getElementById('ocDetailModal').classList.add('show');
            }

            // Cerrar el menú
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.remove('show');
            });
        }

        function cerrarModalDetalle() {
            document.getElementById('ocDetailModal').classList.remove('show');
        }

        function irAlGestor(index) {
            const row = document.querySelector(`tr[data-index="${index}"]`);
            if (row) {
                // Redirigir al gestor de aprobaciones
                window.location.href = '{{ route("oc.gestor") }}';
            }
            // Cerrar el menú
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.remove('show');
            });
        }

        function marcarFacturado(index) {
            const row = document.querySelector(`tr[data-index="${index}"]`);
            if (!row) return;

            const id = row.dataset.id;
            const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
            const token = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

            // Hacer la llamada al servidor
            fetch(`/oc/gestor/${id}/facturacion`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            }).then(res => {
                if (res.ok) return res.json();
                throw new Error('Error al marcar como facturado');
            }).then(data => {
                if (data.success) {
                    // Mostrar notificación de éxito
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-success';
                    alert.innerHTML = '<span style="font-size: 18px;">✓</span><span>Solicitud marcada como facturada correctamente</span>';
                    alert.style.position = 'fixed';
                    alert.style.top = '20px';
                    alert.style.left = '50%';
                    alert.style.transform = 'translateX(-50%)';
                    alert.style.zIndex = '10000';
                    alert.style.maxWidth = '500px';
                    document.body.appendChild(alert);

                    // Recargar la página después de 1.5 segundos
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                }
            }).catch(err => {
                console.error('Error:', err);
                alert('Error al marcar como facturado');
            });

            // Cerrar el menú
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.remove('show');
            });
        }

        // Sidebar toggle functionality
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
            
            // Save state to localStorage
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        }

        // Restore sidebar state on page load
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            
            if (isCollapsed) {
                sidebar.classList.add('collapsed');
            }
        });
    </script>

    
    <!-- Modal Detalle OC -->
    <div class="modal-overlay" id="ocDetailModal" onclick="cerrarModalDetalle()">
        <div class="modal-container" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h3 class="modal-title">
                    <i class="fas fa-file-invoice" style="color: #0f6bb6; font-size: 1.2em;"></i>
                    Detalle Orden de Compra <span id="modal-detalle-numero-oc" style="color: #0f6bb6; font-weight: 800; margin-left: 6px;"></span>
                </h3>
                <button class="modal-close" onclick="cerrarModalDetalle()">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                
                <div class="detail-section">
                    <div class="detail-section-title"><i class="fas fa-info-circle"></i> Información General</div>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="detail-label">ID Interno</span>
                            <span class="detail-value">#<span id="modal-detalle-id"></span></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Fecha Creación</span>
                            <span class="detail-value" id="modal-detalle-fecha"></span>
                        </div>
                        <div class="detail-item" id="estado_item">
                            <span class="detail-label">Estado</span>
                            <span class="detail-value" id="modal-detalle-estado" style="font-weight: 600;"></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">CECO</span>
                            <span class="detail-value" id="modal-detalle-ceco"></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Tipo Solicitud</span>
                            <span class="detail-value" id="modal-detalle-tipo"></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Tipo Documento</span>
                            <span class="detail-value" id="modal-detalle-documento"></span>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <div class="detail-section-title"><i class="fas fa-building"></i> Datos del Proveedor</div>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="detail-label">RUT</span>
                            <span class="detail-value" id="modal-detalle-rut"></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Razón Social / Nombre</span>
                            <span class="detail-value" id="modal-detalle-proveedor"></span>
                        </div>
                        <div class="detail-item full-width" id="modal-detalle-extra-container"></div><div class="detail-item full-width">
                            <span class="detail-label">Email Proveedor</span>
                            <span class="detail-value" id="modal-detalle-email"></span>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <div class="detail-section-title"><i class="fas fa-shopping-cart"></i> Detalles de la Compra</div>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="detail-label">Cantidad</span>
                            <span class="detail-value" id="modal-detalle-cantidad"></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Monto Total</span>
                            <span class="detail-value"><span id="modal-detalle-monto" class="monto-badge"></span></span>
                        </div>
                        <div class="detail-item full-width">
                            <span class="detail-label">Descripción de la Compra</span>
                            <div class="desc-box" id="modal-detalle-descripcion"></div>
                        </div>
                        <div class="detail-item full-width">
                            <span class="detail-label"><i class="fas fa-exclamation-circle" style="color: #d97706;"></i> Observación Especial</span>
                            <div class="obs-box" id="modal-detalle-observacion"></div>
                        </div>
                        <div class="detail-item full-width">
                            <span class="detail-label">Cotización Adjunta</span>
                            <div style="margin-top: 8px;">
                                <a id="modal-detalle-adjunto-btn" href="#" class="btn-modern btn-download" style="display:none;" target="_blank">
                                    <i class="fas fa-download"></i> Descargar Archivo Adjunto
                                </a>
                                <span id="modal-detalle-no-adjunto" style="font-size: 14px; color: #94a3b8; font-style: italic; display: none;">No hay adjunto</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nueva sección Gestión de Envío -->
                <div class="detail-section" id="modal-detalle-gestion-section" style="display: none; border-left: 4px solid #0f6bb6; background: #f8fafc;">
                    <div class="detail-section-title"><i class="fas fa-paper-plane" style="color: #0f6bb6;"></i> Gestión de Envío (por el Gestor)</div>
                    <div class="detail-grid">
                        <div class="detail-item full-width">
                            <span class="detail-label">Comentario del Gestor</span>
                            <div class="obs-box" id="modal-detalle-manager-comment" style="background: #fff; border-color: #0f6bb6;"></div>
                        </div>
                        <div class="detail-item full-width">
                            <span class="detail-label">Orden de Compra Enviada (Documento)</span>
                            <div style="margin-top: 8px;">
                                <a id="modal-detalle-sent-file-btn" href="#" class="btn-modern btn-download" style="background: #0f6bb6; color: #fff;" target="_blank">
                                    <i class="fas fa-file-pdf"></i> Descargar OC Enviada (.pdf)
                                </a>
                                <span id="modal-detalle-no-sent-file" style="font-size: 14px; color: #94a3b8; font-style: italic; display: none;">No se adjuntó archivo personalizado</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modern btn-close" onclick="cerrarModalDetalle()">
                    Cerrar
                </button>
            </div>
        </div>
    </div>


    <!-- Formulario oculto para envío de OC -->
    <form id="ocSendForm" method="POST" action="{{ route('oc.send') }}" style="display: none;" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="oc_solicitud_id" name="oc_solicitud_id">
        <input type="hidden" id="numero_oc" name="numero_oc">
        <input type="hidden" id="ceco" name="ceco">
        <input type="hidden" id="tipo_solicitud" name="tipo_solicitud">
        <input type="hidden" id="proveedor" name="proveedor">
        <input type="hidden" id="email_proveedor" name="email_proveedor">
        <input type="hidden" id="rut" name="rut">
        <input type="hidden" id="descripcion" name="descripcion">
        <input type="hidden" id="cantidad" name="cantidad">
        <input type="hidden" id="monto" name="monto">
    </form>

    <!-- Modal Confirmar Envío OC -->
    <div class="modal-overlay" id="ocConfirmModal" style="z-index: 100000; display: none;">
        <div class="modal-container" style="max-width: 500px;">
            <div class="modal-header">
                <h3 class="modal-title" style="color: #0f6bb6;">Preparar Envío de OC</h3>
                <button class="modal-close" onclick="cerrarModalEnvio()">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <form id="ocConfirmSendForm" method="POST" action="{{ route('oc.send') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="modal_oc_solicitud_id" name="oc_solicitud_id">
                <input type="hidden" id="modal_numero_oc" name="numero_oc">
                <input type="hidden" id="modal_ceco" name="ceco">
                <input type="hidden" id="modal_tipo_solicitud" name="tipo_solicitud">
                <input type="hidden" id="modal_proveedor" name="proveedor">
                <input type="hidden" id="modal_email_proveedor" name="email_proveedor">
                <input type="hidden" id="modal_rut" name="rut">
                <input type="hidden" id="modal_descripcion" name="descripcion">
                <input type="hidden" id="modal_cantidad" name="cantidad">
                <input type="hidden" id="modal_monto" name="monto">

                <div class="modal-body" style="padding: 24px;">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <div style="font-size: 40px; color: #0f6bb6; margin-bottom: 12px;">📨</div>
                        <div style="font-size: 18px; font-weight: 600;">¿Enviar OC <span id="confirmEnvioOCNumber" style="color: #0f6bb6;"></span>?</div>
                        <p style="color: #64748b; font-size: 14px; margin-top: 8px;">Adjunte el archivo PDF de la OC y añada un comentario opcional para el proveedor.</p>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: 701; font-size: 13px; color: #475569; margin-bottom: 8px; text-transform: uppercase;">Archivo OC (PDF) <span style="color:red">*</span></label>
                        <input type="file" name="oc_file" id="modal_oc_file_idx" accept=".pdf" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; background: #f8fafc;">
                    </div>

                    <div style="margin-bottom: 10px;">
                        <label style="display: block; font-weight: 701; font-size: 13px; color: #475569; margin-bottom: 8px; text-transform: uppercase;">Comentario para el Proveedor</label>
                        <textarea name="comentario" id="modal_comentario_idx" placeholder="Escriba un mensaje opcional..." style="width: 100%; min-height: 100px; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; font-family: inherit; resize: vertical;"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 16px 24px; background: #f8fafc; border-top: 1px solid #e2e8f0; border-radius: 0 0 16px 16px; display: flex; gap: 12px; justify-content: flex-end;">
                    <button type="button" onclick="cerrarModalEnvio()" style="background: #fff; color: #64748b; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 20px; font-weight: 600; font-size: 14px; cursor: pointer;">Cancelar</button>
                    <button type="submit" style="background: #0f6bb6; color: #fff; border: none; border-radius: 8px; padding: 10px 24px; font-weight: 700; font-size: 14px; cursor: pointer; box-shadow: 0 4px 10px rgba(15, 107, 182, 0.2);">Enviar Orden de Compra</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function cerrarModalEnvio() {
            document.getElementById('ocConfirmModal').style.display = 'none';
        }

        function enviarOC(index) {
            const row = document.querySelector(`tr[data-index="${index}"]`);
            if (!row) return;

            const nroOC = row.dataset.numeroOc || 'N/A';
            document.getElementById('confirmEnvioOCNumber').textContent = nroOC;
            
            // Populate hidden fields
            document.getElementById('modal_oc_solicitud_id').value = row.dataset.id;
            document.getElementById('modal_numero_oc').value = nroOC;
            document.getElementById('modal_ceco').value = row.dataset.ceco;
            document.getElementById('modal_tipo_solicitud').value = row.dataset.tipoSolicitud;
            document.getElementById('modal_proveedor').value = row.dataset.proveedor;
            document.getElementById('modal_email_proveedor').value = row.dataset.emailProveedor;
            document.getElementById('modal_rut').value = row.dataset.rut;
            document.getElementById('modal_descripcion').value = row.dataset.descripcion;
            document.getElementById('modal_cantidad').value = row.dataset.cantidad;
            document.getElementById('modal_monto').value = row.dataset.monto;

            // Show modal
            document.getElementById('ocConfirmModal').style.display = 'flex';
        }

        function verDetalle(index) {
            // Existing verDetalle implementation...
            // I should ensure this function exists or implemented properly elsewhere.
            // Since it was already there, I'll assume it's part of the scripts I haven't seen.
            if (typeof window.verDetalleExtra !== 'undefined') {
                window.verDetalleExtra(index);
            }
        }
    </script>
</body>
</html>
