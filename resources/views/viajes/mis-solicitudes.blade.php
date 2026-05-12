@extends('viajes.layouts.dashboard')

@section('title', 'Mis Solicitudes')
@section('subtitle', 'Historial de solicitudes de viaje enviadas')

@section('header')
<div class="ms-banner">
    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
        <div>
            @php 
                $rol = Auth::user()->rol ?? 'usuario';
                $esPrivilegiado = in_array($rol, ['admin', 'super_admin', 'aprobador', 'gestor']);
            @endphp
            <h1 class="ms-banner-title">{{ $esPrivilegiado ? 'Panel Global de Viajes' : 'Mis Solicitudes' }}</h1>
            <p class="ms-banner-sub">{{ $esPrivilegiado ? 'Administración centralizada de todas las comisiones de servicio.' : 'Gestione y consulte el estado de sus requerimientos de viaje.' }}</p>
        </div>
        <div style="display: flex; gap: 1rem;">
            <button onclick="exportExcel()" class="ms-btn-excel" title="Exportar a Excel">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                Exportar
            </button>
            <a href="{{ url('viajes/solicitudes') }}" class="ms-btn-new">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Nueva Solicitud
            </a>
        </div>
    </div>
</div>
@endsection

@section('content')

<!-- ── KPI CARDS ──────────────────────────────────────── -->
<div class="ms-kpi-grid">
    <div class="ms-kpi active" id="card-total" onclick="filtrarTabla('todos')">
        <div class="ms-kpi-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
        </div>
        <div class="ms-kpi-body">
            <span class="ms-kpi-label">Total Solicitudes</span>
            <span class="ms-kpi-value">{{ $total }}</span>
            <span class="ms-kpi-desc">Registros históricos</span>
        </div>
    </div>

    <div class="ms-kpi" id="card-pendiente" onclick="filtrarTabla('pendiente')">
        <div class="ms-kpi-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        </div>
        <div class="ms-kpi-body">
            <span class="ms-kpi-label">En Proceso</span>
            <span class="ms-kpi-value">{{ $pendientes }}</span>
            <span class="ms-kpi-desc">Pendientes de acción</span>
        </div>
    </div>

    <div class="ms-kpi" id="card-aprobado" onclick="filtrarTabla('aprobado')">
        <div class="ms-kpi-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </div>
        <div class="ms-kpi-body">
            <span class="ms-kpi-label">Aprobadas</span>
            <span class="ms-kpi-value">{{ $aprobadas }}</span>
            <span class="ms-kpi-desc">Listas para gestión</span>
        </div>
    </div>

    <div class="ms-kpi" id="card-rechazado" onclick="filtrarTabla('rechazado')">
        <div class="ms-kpi-icon" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
        </div>
        <div class="ms-kpi-body">
            <span class="ms-kpi-label">Rechazadas</span>
            <span class="ms-kpi-value">{{ $rechazadas }}</span>
            <span class="ms-kpi-desc">No procedentes</span>
        </div>
    </div>
</div>

<!-- ── FILTROS ─────────────────────────────────────────── -->
<div class="ms-filters">
    <div class="ms-search-wrap">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="ms-search-icon"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        <input id="busqueda" type="text" placeholder="Buscar por destino, motivo, solicitante o CECO..." class="ms-search-input" oninput="aplicarFiltros()">
    </div>
    
    <select id="filtroEstado" class="ms-select" onchange="aplicarFiltros()">
        <option value="todos">Todos los Estados</option>
        <option value="pendiente">En Proceso (Estimación/Aprobación)</option>
        <option value="aprobado">Aprobadas / Completadas</option>
        <option value="rechazado">Rechazadas</option>
    </select>

    <select id="filtroTipo" class="ms-select" onchange="aplicarFiltros()">
        <option value="todos">Todos los Tipos</option>
        <option value="interno">Viajero Interno</option>
        <option value="externo">Viajero Externo</option>
    </select>

    <button onclick="resetFiltros()" class="ms-btn-reset">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M23 4v6h-6"></path><path d="M1 20v-6h6"></path><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
        Limpiar
    </button>
</div>

<!-- ── TABLA ───────────────────────────────────────────── -->
<div class="ms-table-card">
    <div class="ms-table-header">
        <h3 class="ms-table-title">Historial de Comisiones</h3>
        <span id="contadorResultados" class="ms-table-count">Cargando...</span>
    </div>
    <div class="ms-table-wrapper">
        <table class="ms-table" id="tablaSolicitudes">
            <thead>
                <tr>
                    <th>ID</th>
                    @if($esPrivilegiado) <th>Solicitante</th> @endif
                    <th>Destino</th>
                    <th>Fechas</th>
                    <th>Motivo / Justificación</th>
                    <th>CECO</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tbodySolicitudes">
                @forelse($solicitudes as $sol)
                <tr data-estado="{{ $sol->estado }}"
                    data-tipo="{{ $sol->tipo }}"
                    data-texto="{{ strtolower($sol->id . ' ' . $sol->destino . ' ' . $sol->motivo . ' ' . $sol->ceco . ' ' . ($sol->solicitante->name ?? '') . ' ' . ($sol->nombre_externo ?? '')) }}">
                    
                    <td style="font-weight: 800; color: var(--brand-primary);">#{{ $sol->id }}</td>
                    
                    @if($esPrivilegiado)
                    <td style="font-weight: 700;">
                        {{ $sol->tipo === 'externo' ? $sol->nombre_externo : ($sol->solicitante->name ?? 'N/A') }}
                    </td>
                    @endif
                    
                    <td style="font-weight: 600;">{{ $sol->destino }}</td>
                    
                    <td style="white-space: nowrap;">
                        <div style="font-size: 0.8rem; font-weight: 700;">{{ $sol->fecha_viaje->format('d/m/Y') }}</div>
                        <div style="font-size: 0.7rem; color: var(--text-muted);">hasta {{ $sol->fecha_retorno?->format('d/m/Y') ?? '—' }}</div>
                    </td>
                    
                    <td style="max-width: 250px;">
                        <div class="truncate-2" title="{{ $sol->motivo }}">{{ $sol->motivo }}</div>
                    </td>
                    
                    <td><span class="chip" style="background: #f1f5f9; color: #475569; border-radius: 0.5rem;">{{ $sol->ceco }}</span></td>
                    
                    <td>
                        <span class="chip chip-{{ $sol->tipo }}">{{ $sol->tipo }}</span>
                    </td>
                    
                    <td>
                        <span class="chip chip-{{ $sol->estado }}">
                            @if($sol->estado === 'pendiente') ⚖️ Estimación
                            @elseif($sol->estado === 'en_aprobacion') ⏳ Aprobación
                            @elseif($sol->estado === 'aprobado') ✓ Aprobado
                            @elseif($sol->estado === 'rechazado') ✕ Rechazado
                            @elseif($sol->estado === 'gestionado') ★ Completado
                            @else {{ $sol->estado }} @endif
                        </span>
                    </td>
                    
                    <td style="text-align: right;">
                        <button class="ms-btn-reset" style="padding: 0.5rem; border-radius: 0.5rem;" onclick="abrirModal({{ $sol->toJson() }})">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </button>
                    </td>
                </tr>
                @empty
                <tr id="no-rows-row">
                    <td colspan="10" style="padding: 4rem 0; text-align: center;">
                        <div style="display: flex; flex-direction: column; align-items: center; gap: 1rem;">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#e2e8f0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                            <p style="color: var(--text-muted); font-weight: 600;">No se encontraron registros</p>
                        </div>
                    </td>
                </tr>
                @endforelse
                <tr id="sinResultados" style="display:none;">
                    <td colspan="10" style="padding: 4rem 0; text-align: center;">
                        <div style="display: flex; flex-direction: column; align-items: center; gap: 1rem;">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#e2e8f0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            <p style="color: var(--text-muted); font-weight: 600;">Ninguna solicitud coincide con la búsqueda</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- ── MODAL DETALLES ──────────────────────────────────── -->
<div id="modalDetalles" class="ms-modal" onclick="cerrarModal(event)">
    <div class="ms-modal-content" onclick="event.stopPropagation()">
        <div class="ms-modal-header">
            <div>
                <h2 class="ms-banner-title" style="color: var(--text-main); font-size: 1.5rem;" id="m-titulo">Detalles del Viaje</h2>
                <span id="m-id" style="font-family: 'Outfit', sans-serif; font-weight: 800; color: var(--brand-primary);">#000</span>
            </div>
            <button class="ms-btn-reset" style="padding: 0.5rem; border-radius: 0.5rem;" onclick="cerrarModal()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <div class="ms-modal-body">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <!-- Col Izquierda -->
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <div style="background: #f8fafc; padding: 1.25rem; border-radius: 1rem; border: 1px solid var(--border-color);">
                        <h4 style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: var(--brand-primary); margin-bottom: 1rem;">Información del Viajero</h4>
                        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                            <div style="display: flex; justify-content: space-between;"><span style="color: var(--text-muted); font-weight: 600;">Nombre:</span><span id="m-nombre" style="font-weight: 700;"></span></div>
                            <div id="m-externo-info" style="display:none; flex-direction: column; gap: 0.5rem;">
                                <div style="display: flex; justify-content: space-between;"><span style="color: var(--text-muted); font-weight: 600;">RUT:</span><span id="m-rut" style="font-weight: 700;"></span></div>
                                <div style="display: flex; justify-content: space-between;"><span style="color: var(--text-muted); font-weight: 600;">Email:</span><span id="m-email-ext" style="font-weight: 700;"></span></div>
                            </div>
                            <div style="display: flex; justify-content: space-between;"><span style="color: var(--text-muted); font-weight: 600;">Tipo:</span><span id="m-tipo"></span></div>
                        </div>
                    </div>

                    <div style="background: #f8fafc; padding: 1.25rem; border-radius: 1rem; border: 1px solid var(--border-color);">
                        <h4 style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: var(--brand-primary); margin-bottom: 1rem;">Detalles del Destino</h4>
                        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                            <div style="display: flex; justify-content: space-between;"><span style="color: var(--text-muted); font-weight: 600;">Destino:</span><span id="m-destino" style="font-weight: 700;"></span></div>
                            <div style="display: flex; justify-content: space-between;"><span style="color: var(--text-muted); font-weight: 600;">CECO:</span><span id="m-ceco" style="font-weight: 700;"></span></div>
                            <div style="display: flex; justify-content: space-between;"><span style="color: var(--text-muted); font-weight: 600;">Ida:</span><span id="m-fecha-v" style="font-weight: 700;"></span></div>
                            <div style="display: flex; justify-content: space-between;"><span style="color: var(--text-muted); font-weight: 600;">Regreso:</span><span id="m-fecha-r" style="font-weight: 700;"></span></div>
                        </div>
                    </div>
                </div>

                <!-- Col Derecha -->
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <div style="background: #f8fafc; padding: 1.25rem; border-radius: 1rem; border: 1px solid var(--border-color);">
                        <h4 style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: var(--brand-primary); margin-bottom: 1rem;">Logística y Motivo</h4>
                        <div style="background: white; padding: 0.75rem; border-radius: 0.75rem; border: 1px solid var(--border-color); font-size: 0.85rem; line-height: 1.5; margin-bottom: 1rem;" id="m-motivo"></div>
                        <div style="display: flex; gap: 1rem;">
                            <div style="flex: 1; text-align: center; background: white; padding: 0.5rem; border-radius: 0.75rem; border: 1px solid var(--border-color);"><div style="font-size: 0.65rem; font-weight: 800; color: var(--text-muted);">ALOJAMIENTO</div><div id="m-alojamiento" style="font-weight: 700; color: var(--brand-primary);"></div></div>
                            <div style="flex: 1; text-align: center; background: white; padding: 0.5rem; border-radius: 0.75rem; border: 1px solid var(--border-color);"><div style="font-size: 0.65rem; font-weight: 800; color: var(--text-muted);">TRASLADO</div><div id="m-traslado" style="font-weight: 700; color: var(--brand-primary);"></div></div>
                        </div>
                    </div>

                    <div id="m-status-section" style="background: #eff6ff; padding: 1.25rem; border-radius: 1rem; border: 1px solid #dbeafe;">
                        <h4 style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: #1e40af; margin-bottom: 1rem;">Estado y Resolución</h4>
                        <div style="margin-bottom: 0.75rem;"><span id="m-estado"></span></div>
                        <div id="m-aprobado-info" style="display:none; font-size: 0.85rem;">
                            <div style="margin-bottom: 0.25rem;"><b style="color: #1e40af;">Aprobado por:</b> <span id="m-aprobador"></span></div>
                            <div style="font-style: italic; color: #475569;" id="m-com-aprob"></div>
                        </div>
                        <div id="m-rechazado-info" style="display:none; font-size: 0.85rem;">
                            <div style="margin-bottom: 0.25rem;"><b style="color: #dc2626;">Rechazado por:</b> <span id="m-rechazador"></span></div>
                            <div style="color: #dc2626; font-weight: 600;" id="m-com-rech"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="padding: 1.5rem 2.5rem; background: #fcfdfe; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end;">
            <button class="ms-btn-reset" onclick="cerrarModal()">Cerrar Detalle</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
var filtroActivo = 'todos';

function filtrarTabla(estado) {
    if (filtroActivo === estado && estado !== 'todos') {
        estado = 'todos';
    }
    
    filtroActivo = estado;
    document.getElementById('filtroEstado').value = estado;
    
    document.querySelectorAll('.ms-kpi').forEach(el => el.classList.remove('active'));
    var mapa = { todos:'card-total', pendiente:'card-pendiente', aprobado:'card-aprobado', rechazado:'card-rechazado', gestionado:'card-aprobado' };
    if (mapa[estado]) document.getElementById(mapa[estado])?.classList.add('active');
    
    aplicarFiltros();
}

function aplicarFiltros() {
    var busqueda = document.getElementById('busqueda').value.toLowerCase();
    var estado   = document.getElementById('filtroEstado').value;
    var tipo     = document.getElementById('filtroTipo').value;
    var filas    = document.querySelectorAll('#tbodySolicitudes tr:not(#sinResultados):not(#no-rows-row)');
    var visibles = 0;
    
    filas.forEach(function(fila) {
        var rowEstado = fila.getAttribute('data-estado');
        var okTexto  = busqueda === '' || (fila.getAttribute('data-texto') || '').toLowerCase().includes(busqueda);
        
        var okEstado = false;
        if (estado === 'todos') {
            okEstado = true;
        } else if (estado === 'pendiente') {
            // "En Proceso" incluye tanto estimación como aprobación
            okEstado = (rowEstado === 'pendiente' || rowEstado === 'en_aprobacion');
        } else if (estado === 'aprobado') {
            // "Aprobadas" incluye tanto aprobadas como ya gestionadas
            okEstado = (rowEstado === 'aprobado' || rowEstado === 'gestionado');
        } else {
            okEstado = (rowEstado === estado);
        }

        var okTipo   = tipo === 'todos' || fila.getAttribute('data-tipo') === tipo;
        
        if (okTexto && okEstado && okTipo) {
            fila.style.display = '';
            visibles++;
        } else {
            fila.style.display = 'none';
        }
    });

    const sinRes = document.getElementById('sinResultados');
    if (sinRes) sinRes.style.display = (visibles === 0 && filas.length > 0) ? '' : 'none';
    
    const countEl = document.getElementById('contadorResultados');
    if (countEl) countEl.textContent = visibles + ' resultado' + (visibles !== 1 ? 's' : '');
}

function resetFiltros() {
    document.getElementById('busqueda').value = '';
    document.getElementById('filtroEstado').value = 'todos';
    document.getElementById('filtroTipo').value = 'todos';
    document.querySelectorAll('.ms-kpi').forEach(el => el.classList.remove('active'));
    document.getElementById('card-total').classList.add('active');
    aplicarFiltros();
}

document.addEventListener('DOMContentLoaded', aplicarFiltros);

function abrirModal(sol) {
    document.getElementById('m-id').textContent = '#' + String(sol.id).padStart(3, '0');
    document.getElementById('m-nombre').textContent = sol.tipo === 'externo' ? sol.nombre_externo : (sol.solicitante ? sol.solicitante.name : 'N/A');
    
    const extInfo = document.getElementById('m-externo-info');
    if (sol.tipo === 'externo') {
        extInfo.style.display = 'flex';
        document.getElementById('m-rut').textContent = sol.rut || '—';
        document.getElementById('m-email-ext').textContent = sol.correo_externo || '—';
    } else {
        extInfo.style.display = 'none';
    }

    document.getElementById('m-tipo').innerHTML = `<span class="chip chip-${sol.tipo}">${sol.tipo.toUpperCase()}</span>`;
    document.getElementById('m-destino').textContent = sol.destino;
    document.getElementById('m-ceco').textContent = sol.ceco;
    document.getElementById('m-fecha-v').textContent = formatDate(sol.fecha_viaje);
    document.getElementById('m-fecha-r').textContent = sol.fecha_retorno ? formatDate(sol.fecha_retorno) : '—';
    document.getElementById('m-motivo').textContent = sol.motivo;
    document.getElementById('m-alojamiento').textContent = sol.alojamiento ? 'SÍ' : 'NO';
    document.getElementById('m-traslado').textContent = sol.traslado ? 'SÍ' : 'NO';

    const mEst = document.getElementById('m-estado');
    mEst.innerHTML = `<span class="chip chip-${sol.estado}">${sol.estado.toUpperCase()}</span>`;

    document.getElementById('m-aprobado-info').style.display = 'none';
    document.getElementById('m-rechazado-info').style.display = 'none';

    if (sol.estado === 'aprobado' || sol.estado === 'gestionado') {
        document.getElementById('m-aprobado-info').style.display = 'block';
        document.getElementById('m-aprobador').textContent = sol.aprobador ? sol.aprobador.name : 'Sistema';
        document.getElementById('m-com-aprob').textContent = sol.comentario_aprobador || 'Sin comentarios adicionales';
    } else if (sol.estado === 'rechazado') {
        document.getElementById('m-rechazado-info').style.display = 'block';
        document.getElementById('m-rechazador').textContent = sol.rechazador ? sol.rechazador.name : 'Sistema';
        document.getElementById('m-com-rech').textContent = sol.comentario_rechazo || 'No se indicó motivo';
    }

    document.getElementById('modalDetalles').classList.add('active');
}

function cerrarModal(e) {
    document.getElementById('modalDetalles').classList.remove('active');
}

function formatDate(dateStr) {
    if (!dateStr) return '—';
    const d = new Date(dateStr);
    return d.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
}

function exportExcel() {
    const table = document.getElementById('tablaSolicitudes');
    const rows = Array.from(table.querySelectorAll('tr:not(#sinResultados):not(#no-rows-row)'));
    const visibleRows = rows.filter(row => row.style.display !== 'none');
    
    let csvContent = "\uFEFF"; 
    visibleRows.forEach(row => {
        const cols = Array.from(row.querySelectorAll('th, td:not(:last-child)'));
        const rowData = cols.map(col => `"${col.innerText.replace(/"/g, '""').trim()}"`).join(';'); 
        csvContent += rowData + "\r\n";
    });

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement("a");
    const url = URL.createObjectURL(blob);
    link.setAttribute("href", url);
    link.setAttribute("download", "viajes_" + new Date().toISOString().split('T')[0] + ".csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    Swal.fire({ title: 'Exportación Exitosa', text: 'Se ha generado el archivo CSV correctamente', icon: 'success', customClass: { confirmButton: 'swal-modern-confirm' } });
}
</script>
@endpush

@endsection
