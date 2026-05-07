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
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('oc.partials.common_scripts')
    <style>
        /* Especificos de esta vista que no estan en common_styles */
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

        .chip-cliente { background: linear-gradient(135deg, #ede9fe 0%, #f5f3ff 100%); color: #5b21b6; }
        .chip-interna { background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 100%); color: #1d4ed8; }
        .chip-negocio { background: linear-gradient(135deg, #dcfce7 0%, #f0fdf4 100%); color: #166534; }

        .menu-trigger {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            cursor: pointer;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            color: #64748b;
            margin: 0 auto;
            outline: none !important;
        }

        .menu-trigger:hover {
            background: #f8fafc;
            border-color: var(--brand);
            color: var(--brand);
            transform: translateY(-1px);
        }

        .dropdown-menu {
            display: none;
            position: fixed;
            background: white;
            border: 1.5px solid #e5e9f2;
            border-radius: 12px;
            box-shadow: 0 12px 32px rgba(16, 24, 40, 0.20), 0 2px 8px rgba(16, 24, 40, 0.10);
            min-width: 180px;
            z-index: 999999;
        }

        .dropdown-menu.show { display: block; animation: dropdownSlideIn 0.15s ease-out; }
        @keyframes dropdownSlideIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }

        .dropdown-item {
            display: block; width: 100%; padding: 11px 16px; border: none; background: transparent;
            text-align: left; cursor: pointer; font-size: 14px; color: #1f2937; transition: all 150ms ease; font-weight: 500;
        }
        .dropdown-item:hover { background: #f7fbff; color: #0f6bb6; }
        .dropdown-item.primary { color: var(--brand); font-weight: 600; }

        /* Modal Details */
        #ocDetailModal .modal-container { max-width: 1040px; border-radius: 24px; }
        #ocDetailModal .modal-body { max-height: calc(100vh - 200px); padding: 24px; background: #f8fafc; }
        
        .rendicion-layout { display: grid; grid-template-columns: 1.6fr 1fr; gap: 24px; }
        .rendicion-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        
        .voucher-item { border: 1px solid #e2e8f0; border-radius: 12px; margin-bottom: 16px; overflow: hidden; }
        .voucher-head { padding: 16px; display: flex; justify-content: space-between; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
        .voucher-amount .money { font-size: 24px; font-weight: 700; color: #0f172a; }

        @media (max-width: 1024px) {
            .rendicion-layout { grid-template-columns: 1fr; }
        }
    </style>

</head>
<body>
    <div class="page">
        @include('oc.partials.sidebar', ['active' => 'index'])

        <!-- Main Content -->
        <div class="main-content">
            <x-oc.page-header 
                title="" 
                subtitle=""
                :backRoute="null"
                :showLogout="true"
            />

            @if(session('success'))
                <script>showAlert('success', "{{ session('success') }}");</script>
            @endif
            @if(session('error'))
                <script>showAlert('error', "{{ session('error') }}");</script>
            @endif

            <main class="content">
                <section class="card" aria-label="Tabla de solicitudes">
                    <div class="card-header">
                        <div>
                            <h1 class="card-title">Solicitudes de OC</h1>
                            <p class="card-subtitle">Gestión y seguimiento de órdenes de compra en tiempo real</p>
                        </div>
                        <div class="toolbar-actions">
                            <a href="{{ route('oc.export') }}" class="btn btn-ghost" style="padding: 10px 20px; font-weight: 700;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                                Exportar Datos
                            </a>
                        </div>
                    </div>

                <div class="filters" style="background: #fcfdfe; border-bottom: 1px solid #edf2f7; padding: 16px 20px; display: flex; align-items: flex-end; gap: 14px; flex-wrap: wrap;">
                    <div class="field" style="flex: 2; min-width: 300px;">
                        <label for="search" style="font-weight: 700; color: #475569; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 8px; display: block;">Buscar Solicitud</label>
                        <div style="position: relative;">
                            <input id="search" class="input" type="search" placeholder="Buscar por proveedor, descripción o RUT..." style="padding-left: 44px !important; border-radius: 12px; border-color: #e2e8f0; height: 46px; width: 100%;" />
                            <svg style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8;" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                    </div>
                    <div class="field" style="flex: 1; min-width: 180px;">
                        <label for="filterTipo" style="font-weight: 700; color: #475569; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 8px; display: block;">Tipo de Orden</label>
                        <select id="filterTipo" class="select" style="border-radius: 12px; border-color: #e2e8f0; height: 46px; width: 100%;">
                            <option value="">Todos los tipos</option>
                            <option value="Cliente">Cliente</option>
                            <option value="Interna">Interna</option>
                            <option value="Negocio">Negocio</option>
                        </select>
                    </div>
                    <div class="field" style="flex: 1; min-width: 180px;">
                        <label for="filterEstado" style="font-weight: 700; color: #475569; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 8px; display: block;">Estado Actual</label>
                        <select id="filterEstado" class="select" style="border-radius: 12px; border-color: #e2e8f0; height: 46px; width: 100%;">
                            <option value="">Todos los estados</option>
                            <option value="Solicitada">Solicitada</option>
                            <option value="Aceptada">Aceptada</option>
                            <option value="Rechazada">Rechazada</option>
                            <option value="Facturado">Facturado</option>
                        </select>
                    </div>
                    <div style="display: flex; align-items: flex-end; padding-bottom: 0;">
                         <button class="btn btn-primary" id="btnFilter" style="height: 46px; padding: 0 28px; border-radius: 12px; font-weight: 700; letter-spacing: 0.5px;">
                            Filtrar
                         </button>
                    </div>
                </div>

                <div class="table-wrap">
                    <table class="responsive-table">
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
                                <th><span style="visibility: hidden;">Acciones</span></th>
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
                                        'edicion', 'ajuste' => 'pending',
                                        default => 'pending',
                                    };
                                    $tipoClass = strtolower(preg_replace('/[^a-z0-9]+/i', '-', (string) $tipo));
                                @endphp
                                    <tr data-text="{{ strtolower(($ceco ?? '').' '.($tipo ?? '').' '.($documento ?? '').' '.($estado ?? '').' '.($rut ?? '').' '.($proveedor ?? '').' '.($descripcion ?? '')) }}"
                                        data-tipo="{{ $tipo }}"
                                        data-estado="{{ $estado }}"
                                        data-index="{{ $index }}"
                                        @php
                                            $datosExtraIdx = json_decode($row->datos_extra, true) ?? [];
                                            $emailProvIdx = data_get($row, 'email_proveedor') ?: data_get($datosExtraIdx, 'email', '');
                                            $numeroOcIdx = 'OC-' . date('Y', $row->created_at ? strtotime($row->created_at) : time()) . '-' . str_pad($row->id, 3, '0', STR_PAD_LEFT);
                                        @endphp
                                        data-id="{{ $row->id }}"
                                        data-numero-oc="{{ $numeroOcIdx }}"
                                        data-ceco="{{ $ceco }}"
                                        data-tipo-solicitud="{{ $tipo }}"
                                        data-tipo-documento="{{ $documento }}"
                                        data-proveedor="{{ $proveedor }}"
                                        data-email-proveedor="{{ $emailProvIdx }}"
                                        data-rut="{{ $rut }}"
                                        data-descripcion="{{ $descripcion }}"
                                        data-cantidad="{{ $cantidad }}"
                                        data-monto="{{ $monto }}"
                                        data-observacion="{{ data_get($datosExtraIdx, 'observacion', data_get($datosExtraIdx, 'observacion2', 'No especificada')) }}"
                                        data-ruta-adjunto="{{ data_get($datosExtraIdx, 'ruta_adjunto', '') }}"
                                        data-manager-comment="{{ $row->manager_comment ?? '' }}"
                                        data-sent-file-path="{{ $row->sent_file_path ?? '' }}"
                                        data-fecha="{{ data_get($row, 'created_at') ? date('d/m/Y H:i', strtotime($row->created_at)) : 'N/A' }}">
                                        <td class="compact" data-label="CECO">{{ $cecoNumber }}</td>
                                    <td data-label="Tipo"><span class="chip chip-{{ $tipoClass }}">{{ $tipo }}</span></td>
                                    <td data-label="Documento">{{ $documento }}</td>
                                    <td data-label="Estado"><span class="status {{ $estadoClass }}">
                                            <span class="dot"></span>
                                            {{ $estado }}
                                        </span>
                                    </td>
                                    <td class="col-proveedor" title="{{ $proveedor }} - {{ $rut }}" data-label="Proveedor"><span class="truncate-2">{{ $proveedor }}</span></td>
                                    <td class="col-descripcion" title="{{ $descripcion }}" data-label="Descripción"><span class="truncate-2">{{ $descripcion }}</span></td>
                                    <td class="compact" data-label="Cantidad">{{ $cantidad }}</td>
                                    <td class="compact" data-label="Monto">${{ number_format((float) $monto, 0, ',', '.') }}</td>
                                    <td class="actions" style="vertical-align: middle !important; text-align: center; width: 60px;">
                                        <div class="menu-container">
                                            <button type="button" class="menu-trigger" onclick="toggleMenu(event, {{ $index }})">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="12" cy="12" r="1.5"></circle>
                                                    <circle cx="12" cy="5" r="1.5"></circle>
                                                    <circle cx="12" cy="19" r="1.5"></circle>
                                                </svg>
                                            </button>
                                            <div class="dropdown-menu" id="menu-{{ $index }}">
                                                @if(strtolower((string)$estado) !== 'facturado')
                                                    <button type="button" class="dropdown-item primary" onclick="enviarOC({{ $index }})">✉ Enviar OC</button>
                                                    <button type="button" class="dropdown-item" onclick="irAlGestor({{ $index }})">🔍 Ir al Gestor</button>
                                                @else
                                                    <button type="button" class="dropdown-item" style="opacity: 0.5; cursor: not-allowed;" title="Esta OC ya está facturada" disabled>✉ Enviar OC (S/N)</button>
                                                    <button type="button" class="dropdown-item" onclick="irAlGestor({{ $index }})">🔍 Ir al Gestor</button>
                                                @endif
                                                <button type="button" class="dropdown-item" onclick="verDetalle({{ $index }})">👁 Ver detalle</button>
                                                @if(in_array(strtolower((string)$estado), ['edicion', 'ajuste']))
                                                    <div class="dropdown-divider"></div>
                                                    <a href="{{ route('oc.solicitudes.editar', $row->id) }}" class="dropdown-item" style="color: #f59e0b; font-weight: 700; text-decoration: none; display: block; width: 100%; text-align: left;">
                                                        ✏️ Editar Solicitud
                                                    </a>
                                                @endif
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
                
                // Set data to the confirmation modal form
                document.getElementById('modal_oc_solicitud_id').value = row.dataset.id;
                document.getElementById('modal_numero_oc').value = row.dataset.numeroOc;
                document.getElementById('modal_ceco').value = row.dataset.ceco || '';
                document.getElementById('modal_tipo_solicitud').value = row.dataset.tipoSolicitud || '';
                document.getElementById('modal_proveedor').value = row.dataset.proveedor || '';
                document.getElementById('modal_email_proveedor').value = row.dataset.emailProveedor || '';
                document.getElementById('modal_rut').value = row.dataset.rut || '';
                document.getElementById('modal_descripcion').value = row.dataset.descripcion || '';
                document.getElementById('modal_cantidad').value = row.dataset.cantidad || '';
                document.getElementById('modal_monto').value = row.dataset.monto || '';
                
                document.getElementById('confirmEnvioOCNumber').textContent = row.dataset.numeroOc || 'N/A';
                
                // Reset file and comment
                document.getElementById('modal_oc_file_idx').value = '';
                document.getElementById('modal_comentario_idx').value = '';
                
                // Show confirmation modal
                document.getElementById('ocConfirmModal').classList.add('show');
                document.getElementById('ocConfirmModal').style.display = 'flex';
            }
            
            // Cerrar el menú y restaurar z-index
            document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('show'));
            document.querySelectorAll('tbody tr').forEach(tr => tr.style.zIndex = '');
        }

        function confirmarEnvio() {
            // Reemplazado por el envío directo desde el modal ocConfirmSendForm
        }
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
            document.getElementById('ocConfirmModal').style.display = 'none';
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
                estadoEl.className = 'status-chip';
                estadoEl.style.background = '#f9edd1';
                estadoEl.style.color = '#856503';
                estadoEl.style.borderColor = '#edd49f';

                if (estado.toLowerCase() === 'aceptada' || estado.toLowerCase() === 'enviada') {
                    estadoEl.style.background = '#e8f6ee';
                    estadoEl.style.color = '#0f7a3e';
                    estadoEl.style.borderColor = '#bfe5cf';
                } else if (estado.toLowerCase() === 'facturado') {
                    estadoEl.style.background = '#e8f4fb';
                    estadoEl.style.color = '#0284c7';
                    estadoEl.style.borderColor = '#b9ddf2';
                } else if (estado.toLowerCase() === 'rechazada') {
                    estadoEl.style.background = '#fbeaea';
                    estadoEl.style.color = '#dc2626';
                    estadoEl.style.borderColor = '#f3c8c8';
                }

                document.getElementById('modal-detalle-fecha').textContent = row.dataset.fecha || 'N/A';
                document.getElementById('modal-detalle-ceco').textContent = row.dataset.ceco || 'N/A';
                document.getElementById('modal-detalle-tipo').textContent = row.dataset.tipoSolicitud || 'N/A';
                document.getElementById('modal-detalle-documento').textContent = row.dataset.tipoDocumento || 'N/A';
                document.getElementById('modal-detalle-proveedor').textContent = row.dataset.proveedor || 'N/A';
                document.getElementById('voucher-proveedor').textContent = row.dataset.proveedor || 'N/A';
                document.getElementById('transfer-nombre').textContent = row.dataset.proveedor || 'N/A';
                document.getElementById('modal-detalle-email').textContent = row.dataset.emailProveedor || 'No especificado';
                document.getElementById('modal-detalle-rut').textContent = row.dataset.rut || 'N/A';
                document.getElementById('transfer-rut').textContent = row.dataset.rut || 'N/A';
                document.getElementById('transfer-cuenta').textContent = row.dataset.emailProveedor || 'No especificada';
                document.getElementById('modal-detalle-monto').textContent = '$' + Number(row.dataset.monto || 0).toLocaleString('es-CL');
                document.getElementById('modal-detalle-cantidad').textContent = row.dataset.cantidad || 'N/A';
                document.getElementById('modal-detalle-descripcion').textContent = row.dataset.descripcion || 'N/A';
                document.getElementById('modal-detalle-observacion').textContent = row.dataset.observacion || 'No especificada';
                document.getElementById('voucher-fecha').textContent = row.dataset.fecha || 'N/A';

                const avatar = document.getElementById('owner-avatar');
                const proveedorName = (row.dataset.proveedor || 'OC').trim();
                avatar.textContent = proveedorName ? proveedorName.charAt(0).toUpperCase() : 'OC';
                
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
                        noSentFileEl.style.display = 'inline-flex';
                    }
                }

                // Mostrar datos extra
                const extraContainer = document.getElementById('modal-detalle-extra-container');
                if (extraContainer) {
                    extraContainer.innerHTML = '';
                    try {
                        const extraData = JSON.parse(row.dataset.datosExtra || '{}');
                        const excludeKeys = ['_token', 'proveedor', 'rut', 'email_proveedor', 'descripcion', 'monto_mensual', 'ceco'];
                        const extraKeys = Object.keys(extraData).filter(k => !excludeKeys.includes(k));

                        if (extraKeys.length > 0) {
                            extraContainer.innerHTML = `
                                <div class="detail-item full-width" style="margin-top: 8px; padding-top: 16px; border-top: 1px dashed #e2e8f0;">
                                    <span class="detail-label" style="color: #0f6bb6;">Datos Adicionales</span>
                                </div>
                            `;
                            
                            extraKeys.forEach(key => {
                                const label = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                                let value = extraData[key];
                                if(typeof value === 'object' && value !== null) {
                                    value = JSON.stringify(value, null, 2);
                                }
                                if(value === null || value === '') value = 'N/A';
                                
                                extraContainer.innerHTML += `
                                    <div class="detail-item">
                                        <span class="detail-label">${label}</span>
                                        <span class="detail-value">${value}</span>
                                    </div>
                                `;
                            });
                        }
                    } catch (e) {
                        console.error('Error parsing extra data:', e);
                    }
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
            // Lógica de búsqueda
            const searchInput = document.getElementById('search');
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        const url = new URL(window.location.href);
                        if (this.value) url.searchParams.set('search', this.value);
                        else url.searchParams.delete('search');
                        url.searchParams.set('page', 1);
                        window.location.href = url.toString();
                    }
                });
            }
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
                <div class="rendicion-header">
                    <div class="rendicion-header-top">Expediente de gasto</div>
                    <h3 class="rendicion-title" id="modal-detalle-numero-oc">N/A</h3>
                </div>
                <button class="modal-close" onclick="cerrarModalDetalle()">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <div class="rendicion-layout">
                    <section class="rendicion-main">
                        <article class="rendicion-card rendicion-owner">
                            <div class="owner-info">
                                <div class="owner-avatar" id="owner-avatar">OC</div>
                                <div>
                                    <div class="owner-name" id="modal-detalle-proveedor">N/A</div>
                                    <div class="owner-meta">
                                        <span id="modal-detalle-fecha">N/A</span>
                                        <span class="status-chip" id="modal-detalle-estado">N/A</span>
                                    </div>
                                </div>
                            </div>
                            <button class="action-outline" type="button">Anular informe</button>
                        </article>

                        <article class="rendicion-card">
                            <h4 class="section-title">Listado de comprobantes</h4>
                            <div class="voucher-item">
                                <div class="voucher-head">
                                    <div class="voucher-main">
                                        <span class="voucher-icon"><i class="fas fa-file-invoice"></i></span>
                                        <div>
                                            <div class="voucher-name" id="voucher-proveedor">N/A</div>
                                            <div class="voucher-sub">
                                                <span>RUT: <span id="modal-detalle-rut">N/A</span></span>
                                                <span class="voucher-tag" id="modal-detalle-documento">Documento</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="voucher-amount">
                                        <span class="date" id="voucher-fecha">N/A</span>
                                        <span class="money" id="modal-detalle-monto">$0</span>
                                    </div>
                                </div>

                                <div class="voucher-preview">
                                    <div>
                                        <div class="desc-box" id="modal-detalle-descripcion"></div>
                                        <div class="obs-box" id="modal-detalle-observacion"></div>
                                    </div>

                                    <div>
                                        <a id="modal-detalle-adjunto-btn" href="#" class="btn-modern btn-download detail-cta" style="display:none;" target="_blank">
                                            <i class="fas fa-paperclip"></i> Ver Boleta
                                        </a>
                                        <button type="button" class="btn-modern detail-cta muted" id="modal-detalle-no-adjunto" style="display:none;">Sin adjunto</button>
                                    </div>
                                </div>
                            </div>

                            <div class="total-box">
                                <div class="total-label">Monto total rendición</div>
                                <div class="total-sub">Calculado automáticamente sobre <span id="modal-detalle-cantidad">0</span> items</div>
                            </div>
                        </article>
                        
                        <!-- Contenedor para datos extra dinámicos -->
                        <article class="rendicion-card" id="modal-detalle-extra-section">
                            <div class="meta-grid" id="modal-detalle-extra-container" style="gap: 16px;">
                                <!-- Se llena dinámicamente vía JS -->
                            </div>
                        </article>
                    </section>

                    <aside class="rendicion-aside">
                        <article class="rendicion-card">
                            <h4 class="side-card-title">Gestión</h4>
                            <button type="button" class="ghost-action primary">Habilitar edición al solicitante</button>
                            <button type="button" class="ghost-action">Solicitar ajuste</button>
                        </article>

                        <article class="rendicion-card">
                            <h4 class="side-card-title">Información de transferencia</h4>
                            <div class="transfer-name" id="transfer-nombre">N/A</div>
                            <div class="transfer-list">
                                <div class="transfer-item"><strong>RUT</strong><span id="transfer-rut">N/A</span></div>
                                <div class="transfer-item"><strong>Banco</strong><span>No especificado</span></div>
                                <div class="transfer-item"><strong>Cuenta</strong><span id="transfer-cuenta">No especificada</span></div>
                            </div>
                        </article>

                        <article class="rendicion-card" id="estado_item">
                            <h4 class="side-card-title">Resumen</h4>
                            <div class="meta-grid">
                                <div class="meta-pill">
                                    <span class="k">ID Interno</span>
                                    <span class="v">#<span id="modal-detalle-id"></span></span>
                                </div>
                                <div class="meta-pill">
                                    <span class="k">CECO</span>
                                    <span class="v" id="modal-detalle-ceco"></span>
                                </div>
                                <div class="meta-pill">
                                    <span class="k">Tipo Solicitud</span>
                                    <span class="v" id="modal-detalle-tipo"></span>
                                </div>
                                <div class="meta-pill">
                                    <span class="k">Email Proveedor</span>
                                    <span class="v" id="modal-detalle-email"></span>
                                </div>
                            </div>
                        </article>

                        <article class="rendicion-card" id="modal-detalle-gestion-section" style="display: none;">
                            <h4 class="side-card-title">Gestión de envío</h4>
                            <div class="obs-box" id="modal-detalle-manager-comment" style="margin-top: 0;"></div>
                            <div style="margin-top: 10px;">
                                <a id="modal-detalle-sent-file-btn" href="#" class="btn-modern btn-download detail-cta" target="_blank">
                                    <i class="fas fa-file-pdf"></i> Descargar OC enviada
                                </a>
                                <button type="button" class="btn-modern detail-cta muted" id="modal-detalle-no-sent-file" style="display:none;">No hay archivo enviado</button>
                            </div>
                        </article>
                    </aside>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modern btn-close" onclick="cerrarModalDetalle()">
                    Cerrar
                </button>
            </div>
        </div>
    </div>


    <!-- El envío de OC se realiza desde el modal ocConfirmModal definido abajo -->

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
            <form id="ocConfirmSendForm" method="POST" action="{{ route('oc.send') }}" enctype="multipart/form-data" style="display: flex; flex-direction: column; flex: 1; overflow: hidden; margin: 0;">
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

                <div class="modal-body">
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-ghost" onclick="cerrarModalEnvio()" style="margin: 0;">Cancelar</button>
                    <button type="submit" class="btn btn-primary" style="margin: 0;">Enviar Orden de Compra</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('ocConfirmSendForm').addEventListener('submit', function(e) {
            const btn = this.querySelector('button[type="submit"]');
            // Usar setTimeout para evitar que algunos navegadores cancelen el envío al deshabilitar el botón instantáneamente
            setTimeout(() => {
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
            }, 10);
        });

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

        function verDetalleLegacy(index) {
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
