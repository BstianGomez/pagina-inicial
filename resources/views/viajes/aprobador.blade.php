@extends('viajes.layouts.dashboard')

@section('title', 'Panel de Aprobador')
@section('subtitle', 'Gestione y revise las solicitudes de viaje pendientes de validación')

@section('header')
<div class="ms-banner">
    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
        <div>
            <h1 class="ms-banner-title">Centro de Aprobaciones</h1>
            <p class="ms-banner-sub">Revise las comisiones de servicio y valide los presupuestos estimados.</p>
        </div>
        <div class="ms-kpi-icon" style="background: rgba(255,255,255,0.2); width: 60px; height: 60px; border-radius: 1rem;">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
    </div>
</div>
@endsection

@section('content')

<!-- ── KPI SUMMARY ────────────────────────────────────── -->
<div class="ms-kpi-grid">
    <div id="kpi-pendientes" class="ms-kpi" onclick="filtrarSeccion('pendientes')" style="cursor: pointer; transition: all 0.3s ease;">
        <div class="ms-kpi-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        </div>
        <div class="ms-kpi-body">
            <span class="ms-kpi-label">Por Aprobar</span>
            <span class="ms-kpi-value">{{ $pendientes->count() }}</span>
            <span class="ms-kpi-desc">Solicitudes en espera</span>
        </div>
    </div>
    <div id="kpi-historial" class="ms-kpi" onclick="filtrarSeccion('historial')" style="cursor: pointer; transition: all 0.3s ease;">
        <div class="ms-kpi-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </div>
        <div class="ms-kpi-body">
            <span class="ms-kpi-label">Historial Reciente</span>
            <span class="ms-kpi-value">{{ $historial->count() }}</span>
            <span class="ms-kpi-desc">Últimas 30 decisiones</span>
        </div>
    </div>
</div>

<!-- ── PENDIENTES ───────────────────────────────────── -->
<div id="section-pendientes" class="ms-table-card" style="margin-bottom: 2rem;">
    <div class="ms-table-header">
        <h3 class="ms-table-title">Solicitudes Pendientes</h3>
        <span class="ms-table-count">{{ $pendientes->count() }} requerimientos</span>
    </div>
    <div class="ms-table-wrapper">
        <table class="ms-table">
            <thead>
                <tr>
                    <th>Solicitante</th>
                    <th>Destino & Presupuesto</th>
                    <th>Fecha Viaje</th>
                    <th>Motivo</th>
                    <th>Tipo</th>
                    <th>CECO</th>
                    <th style="text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendientes as $sol)
                <tr>
                    <td>
                        <div style="font-weight: 800; color: var(--text-main);">{{ $sol->tipo === 'externo' ? $sol->nombre_externo : ($sol->solicitante->name ?? 'N/A') }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $sol->tipo === 'externo' ? $sol->correo_externo : ($sol->solicitante->email ?? '') }}</div>
                    </td>
                    <td>
                        <div style="font-weight: 700; color: var(--brand-primary);">{{ $sol->destino }}</div>
                        @if($sol->monto_estimado)
                        <div style="font-size: 0.8rem; font-weight: 800; color: #10b981; margin-top: 2px;">
                            Est: ${{ number_format($sol->monto_estimado, 0, ',', '.') }}
                        </div>
                        @endif
                    </td>
                    <td>
                        <div style="font-size: 0.85rem; font-weight: 700;">{{ $sol->fecha_viaje->format('d M, Y') }}</div>
                    </td>
                    <td>
                        <div class="truncate-2" style="font-size: 0.8rem; max-width: 200px;" title="{{ $sol->motivo }}">{{ $sol->motivo }}</div>
                    </td>
                    <td>
                        <span class="chip chip-{{ $sol->tipo }}">{{ strtoupper($sol->tipo) }}</span>
                    </td>
                    <td>
                        <span class="chip" style="background: #f1f5f9; color: #475569;">{{ $sol->ceco }}</span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem; justify-content: center;">
                            <button onclick="verDetalles(this)" data-json="{{ json_encode($sol) }}" class="ms-btn-reset" style="padding: 0.5rem; border-radius: 0.75rem;" title="Ver Detalles">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            </button>
                            <button onclick="abrirModalDecision('aprobar', {{ $sol->id }}, '{{ addslashes($sol->tipo === 'externo' ? $sol->nombre_externo : $sol->solicitante->name) }}')" class="ms-btn-reset" style="padding: 0.5rem; border-radius: 0.75rem; background: #ecfdf5; color: #059669; border-color: #a7f3d0;" title="Aprobar">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            </button>
                            <button onclick="abrirModalDecision('rechazar', {{ $sol->id }}, '{{ addslashes($sol->tipo === 'externo' ? $sol->nombre_externo : $sol->solicitante->name) }}')" class="ms-btn-reset" style="padding: 0.5rem; border-radius: 0.75rem; background: #fef2f2; color: #dc2626; border-color: #fecaca;" title="Rechazar">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 4rem; text-align: center; color: var(--text-muted);">
                        <div style="display: flex; flex-direction: column; align-items: center; gap: 1rem; opacity: 0.5;">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            <p style="font-weight: 700;">No hay solicitudes pendientes de aprobación</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ── HISTORIAL ────────────────────────────────────── -->
<div id="section-historial" class="ms-table-card">
    <div class="ms-table-header">
        <h3 class="ms-table-title">Historial de Decisiones</h3>
    </div>
    <div class="ms-table-wrapper">
        <table class="ms-table">
            <thead>
                <tr>
                    <th>Solicitante</th>
                    <th>Destino</th>
                    <th>Decisión</th>
                    <th>Fecha Decisión</th>
                    <th>Comentario</th>
                </tr>
            </thead>
            <tbody>
                @foreach($historial as $sol)
                <tr>
                    <td style="font-weight: 700;">{{ $sol->tipo === 'externo' ? $sol->nombre_externo : ($sol->solicitante->name ?? 'N/A') }}</td>
                    <td style="font-size: 0.85rem;">{{ $sol->destino }}</td>
                    <td>
                        @if($sol->estado === 'aprobado' || $sol->estado === 'gestionado')
                            <span class="chip chip-aprobado">✓ Aprobado</span>
                        @else
                            <span class="chip chip-rechazado">✕ Rechazado</span>
                        @endif
                    </td>
                    <td style="font-size: 0.8rem; color: var(--text-muted);">
                        {{ ($sol->aprobado_en ?? $sol->rechazado_en)?->format('d-m-Y H:i') ?? '—' }}
                    </td>
                    <td>
                        <div class="truncate-2" style="font-size: 0.75rem; font-style: italic; max-width: 250px;">
                            {{ $sol->comentario_aprobador ?? $sol->comentario_rechazo ?? '—' }}
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- ── MODAL DECISIÓN ──────────────────────────────────── -->
<div id="modalDecision" class="ms-modal" onclick="cerrarModalDecision(event)">
    <div class="ms-modal-content" onclick="event.stopPropagation()" style="max-width: 500px;">
        <div class="ms-modal-header">
            <h2 id="modalTitulo" class="ms-table-title" style="font-size: 1.25rem;">Validar Solicitud</h2>
            <button class="ms-btn-reset" style="padding: 0.5rem;" onclick="cerrarModalDecision()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <div class="ms-modal-body">
            <p id="modalSubtitulo" style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1.5rem;"></p>
            
            <form id="formDecision" method="POST">
                @csrf
                <div class="form-field">
                    <label class="form-label">Comentario o Justificación <span id="spanOpcional" style="font-weight: 400; opacity: 0.6;">(opcional)</span></label>
                    <textarea id="textComentario" name="comentario" class="ms-search-input" rows="4" placeholder="Escriba aquí sus observaciones..." style="padding: 1rem; height: auto;"></textarea>
                    <p id="errorComentario" style="display:none; color: #dc2626; font-size: 0.75rem; font-weight: 700; margin-top: 0.5rem;">El comentario es obligatorio para rechazar la solicitud.</p>
                </div>
            </form>
        </div>
        <div class="ms-modal-footer" style="padding: 1.5rem 2.5rem; background: #fcfdfe; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 1rem;">
            <button class="ms-btn-reset" onclick="cerrarModalDecision()">Cancelar</button>
            <button type="submit" form="formDecision" id="btnConfirmar" class="ms-btn-new" style="min-width: 150px; justify-content: center;">Confirmar</button>
        </div>
    </div>
</div>

<!-- ── MODAL DETALLES ──────────────────────────────────── -->
<div id="modalDetalles" class="ms-modal" onclick="cerrarModalDetalles(event)">
    <div class="ms-modal-content" onclick="event.stopPropagation()" style="max-width: 700px;">
        <div class="ms-modal-header" style="background: var(--brand-primary); color: white;">
            <div>
                <h2 class="ms-banner-title" style="color: white; font-size: 1.25rem;">Revisión de Solicitud</h2>
                <span id="det-id" style="font-size: 0.8rem; font-weight: 800; opacity: 0.8;"></span>
            </div>
            <button class="ms-btn-reset" style="padding: 0.5rem; color: white; background: rgba(255,255,255,0.2); border: none;" onclick="cerrarModalDetalles()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <div id="det-body" class="ms-modal-body" style="padding: 2.5rem;">
            <!-- Dinámico -->
        </div>
        <div class="ms-modal-footer">
            <button class="ms-btn-reset" onclick="cerrarModalDetalles()">Cerrar</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
var accionActual = '';
var BASE_ROUTE = "{{ url('viajes/solicitudes') }}";

function abrirModalDecision(accion, id, nombre) {
    accionActual = accion;
    const modal = document.getElementById('modalDecision');
    const form = document.getElementById('formDecision');
    const titulo = document.getElementById('modalTitulo');
    const sub = document.getElementById('modalSubtitulo');
    const btn = document.getElementById('btnConfirmar');
    const opt = document.getElementById('spanOpcional');
    
    document.getElementById('textComentario').value = '';
    document.getElementById('errorComentario').style.display = 'none';

    if (accion === 'aprobar') {
        form.action = `${BASE_ROUTE}/${id}/aprobar`;
        titulo.textContent = 'Aprobar Solicitud';
        titulo.style.color = '#059669';
        sub.textContent = `Está a punto de validar el viaje solicitado por ${nombre}.`;
        btn.style.background = '#059669';
        opt.style.display = 'inline';
    } else {
        form.action = `${BASE_ROUTE}/${id}/rechazar`;
        titulo.textContent = 'Rechazar Solicitud';
        titulo.style.color = '#dc2626';
        sub.textContent = `Indique el motivo por el cual no procede la solicitud de ${nombre}.`;
        btn.style.background = '#dc2626';
        opt.style.display = 'none';
    }

    modal.classList.add('active');
}

function cerrarModalDecision() {
    document.getElementById('modalDecision').classList.remove('active');
}

document.getElementById('formDecision').addEventListener('submit', function(e) {
    if (accionActual === 'rechazar' && document.getElementById('textComentario').value.trim() === '') {
        e.preventDefault();
        document.getElementById('errorComentario').style.display = 'block';
    }
});

function verDetalles(btn) {
    const sol = JSON.parse(btn.getAttribute('data-json'));
    const body = document.getElementById('det-body');
    document.getElementById('det-id').textContent = 'REQ-00' + sol.id;

    let pvs = (sol.pv || []).map(n => `<span class="chip" style="background:#f1f5f9; color:#475569; font-size:10px;">PV #${n}</span>`).join(' ');
    let gastos = (sol.gastos || []).map(g => `<div style="font-size:13px; padding:8px; border-bottom:1px solid #f1f5f9;">• ${g.descripcion}</div>`).join('');

    body.innerHTML = `
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
            <div>
                <h4 style="font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 1rem;">Itinerario</h4>
                <div style="font-size: 1rem; font-weight: 800; color: var(--brand-primary); margin-bottom: 0.5rem;">${sol.destino}</div>
                <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-main); margin-bottom: 1rem;">Salida: ${new Date(sol.fecha_viaje).toLocaleDateString('es-CL')}</div>
                
                <div style="background: #f0fdf4; padding: 1rem; border-radius: 1rem; border: 1px solid #dcfce7; margin-top: 1.5rem;">
                    <div style="font-size: 0.65rem; font-weight: 800; color: #166534; text-transform: uppercase;">Monto Estimado</div>
                    <div style="font-size: 1.5rem; font-weight: 900; color: #15803d;">$${new Intl.NumberFormat('es-CL').format(sol.monto_estimado || 0)}</div>
                </div>
            </div>
            <div>
                <h4 style="font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 1rem;">Requerimientos</h4>
                <div style="display: flex; gap: 0.75rem; margin-bottom: 1rem;">
                    <div style="flex:1; text-align:center; padding:0.75rem; border-radius:0.75rem; background:${sol.alojamiento ? '#f5f3ff' : '#f8fafc'}; border:1px solid ${sol.alojamiento ? '#ddd6fe' : '#e2e8f0'};">
                        <div style="font-size:0.6rem; font-weight:800; color:${sol.alojamiento ? '#7c3aed' : '#94a3b8'};">ALOJAMIENTO</div>
                        <div style="font-weight:700;">${sol.alojamiento ? 'SÍ' : 'NO'}</div>
                    </div>
                    <div style="flex:1; text-align:center; padding:0.75rem; border-radius:0.75rem; background:${sol.traslado ? '#ecfeff' : '#f8fafc'}; border:1px solid ${sol.traslado ? '#cffafe' : '#e2e8f0'};">
                        <div style="font-size:0.6rem; font-weight:800; color:${sol.traslado ? '#0891b2' : '#94a3b8'};">TRASLADO</div>
                        <div style="font-weight:700;">${sol.traslado ? 'SÍ' : 'NO'}</div>
                    </div>
                </div>
                <div>${pvs}</div>
            </div>
        </div>
        <div style="margin-bottom: 2rem;">
            <h4 style="font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.75rem;">Motivo del Viaje</h4>
            <div style="background: #f8fafc; padding: 1rem; border-radius: 1rem; border: 1px solid var(--border-color); font-size: 0.9rem; line-height: 1.6; color: #334155;">${sol.motivo}</div>
        </div>
        <div>
            <h4 style="font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.75rem;">Otros Gastos Proyectados</h4>
            <div style="background: white; border: 1px solid var(--border-color); border-radius: 1rem; overflow: hidden;">
                ${gastos || '<div style="padding:1rem; text-align:center; color:var(--text-muted); font-size:0.85rem; font-style:italic;">No hay gastos adicionales</div>'}
            </div>
        </div>
    `;

    document.getElementById('modalDetalles').classList.add('active');
}

function cerrarModalDetalles() {
    document.getElementById('modalDetalles').classList.remove('active');
}

var filtroActual = 'todos';

function filtrarSeccion(tipo) {
    const secPendientes = document.getElementById('section-pendientes');
    const secHistorial = document.getElementById('section-historial');
    const kpiPendientes = document.getElementById('kpi-pendientes');
    const kpiHistorial = document.getElementById('kpi-historial');

    if (filtroActual === tipo) {
        // Si se hace clic en el mismo filtro activo, mostramos todo
        secPendientes.style.display = 'block';
        secHistorial.style.display = 'block';
        kpiPendientes.classList.remove('active');
        kpiHistorial.classList.remove('active');
        filtroActual = 'todos';
    } else {
        // Aplicamos el filtro seleccionado
        secPendientes.style.display = (tipo === 'pendientes') ? 'block' : 'none';
        secHistorial.style.display = (tipo === 'historial') ? 'block' : 'none';
        
        kpiPendientes.classList.toggle('active', tipo === 'pendientes');
        kpiHistorial.classList.toggle('active', tipo === 'historial');
        filtroActual = tipo;
    }
}
</script>
@endpush

@endsection
