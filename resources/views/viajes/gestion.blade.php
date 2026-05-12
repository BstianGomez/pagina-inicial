@extends('viajes.layouts.dashboard')

@section('title', 'Gestión Operativa')
@section('subtitle', 'Estimación de costos y cierre administrativo de viajes')

@section('header')
<div class="ms-banner" style="background: linear-gradient(135deg, #0369a1 0%, #075985 100%);">
    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
        <div>
            <h1 class="ms-banner-title">Panel de Gestión Logística</h1>
            <p class="ms-banner-sub">Monitoreo, estimación de presupuesto y cierre de servicios de viaje.</p>
        </div>
        <div class="ms-kpi-icon" style="background: rgba(255,255,255,0.2); width: 60px; height: 60px; border-radius: 1rem;">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
        </div>
    </div>
</div>
@endsection

@section('content')

<!-- ── KPI SUMMARY (FILTERS) ────────────────────────── -->
<div class="ms-kpi-grid">
    <div id="kpi-estimacion" class="ms-kpi active" onclick="filtrarGestion('estimacion')" style="cursor: pointer; transition: all 0.3s ease; border-top: 4px solid #f59e0b;">
        <div class="ms-kpi-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </div>
        <div class="ms-kpi-body">
            <span class="ms-kpi-label">Fase Estimación</span>
            <span class="ms-kpi-value">{{ $pendientesEstimacion->count() }}</span>
            <span class="ms-kpi-desc">Pendientes de presupuesto</span>
        </div>
    </div>

    <div id="kpi-finalizacion" class="ms-kpi" onclick="filtrarGestion('finalizacion')" style="cursor: pointer; transition: all 0.3s ease; border-top: 4px solid #0ea5e9;">
        <div class="ms-kpi-icon" style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
        </div>
        <div class="ms-kpi-body">
            <span class="ms-kpi-label">Fase Finalización</span>
            <span class="ms-kpi-value">{{ $pendientesFinalizacion->count() }}</span>
            <span class="ms-kpi-desc">Esperando compra/cierre</span>
        </div>
    </div>

    <div id="kpi-historial" class="ms-kpi" onclick="filtrarGestion('historial')" style="cursor: pointer; transition: all 0.3s ease; border-top: 4px solid #10b981;">
        <div class="ms-kpi-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </div>
        <div class="ms-kpi-body">
            <span class="ms-kpi-label">Historial</span>
            <span class="ms-kpi-value">{{ $historial->count() }}</span>
            <span class="ms-kpi-desc">Comisiones cerradas</span>
        </div>
    </div>
</div>

<!-- ── FASE 1: ESTIMACIÓN ────────────────────────── -->
<div id="section-estimacion" class="ms-table-card">
    <div class="ms-table-header" style="background: rgba(245, 158, 11, 0.05); padding: 1.5rem 2rem;">
        <div>
            <h3 class="ms-table-title" style="color: #92400e; font-size: 1.25rem;">1. Fase de Estimación de Presupuesto</h3>
            <p style="font-size: 0.85rem; color: #b45309; margin: 0;">Ingrese el monto estimado para que el aprobador valide el gasto.</p>
        </div>
    </div>
    <div class="ms-table-wrapper">
        @forelse($pendientesEstimacion as $sol)
            <div style="border-bottom: 1px solid var(--border-color); transition: all 0.2s;">
                <div style="padding: 1.25rem 2rem; display: flex; align-items: center; justify-content: space-between; cursor: pointer; background: white;" onclick="toggleGestionForm('est-{{ $sol->id }}')">
                    <div style="display: flex; align-items: center; gap: 1.5rem;">
                        <div style="width: 48px; height: 48px; border-radius: 1rem; background: #fffbeb; display: flex; align-items: center; justify-content: center; border: 1px solid #fef3c7;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        </div>
                        <div>
                            <div style="font-weight: 800; color: var(--text-main); font-size: 1rem;">{{ $sol->tipo === 'externo' ? $sol->nombre_externo : ($sol->solicitante->name ?? 'N/A') }}</div>
                            <div style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600;">Destino: {{ $sol->destino }} · Fecha: {{ $sol->fecha_viaje->format('d/m/Y') }}</div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <span class="chip" style="background: #fef3c7; color: #92400e; font-weight: 800;">CECO: {{ $sol->ceco }}</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--text-muted);"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </div>
                </div>
                
                <div id="form-est-{{ $sol->id }}" style="display:none; padding: 2rem; background: #fdfaf3; border-top: 1px solid #fef3c7; animation: slideDown 0.3s ease;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                        <!-- Detalles -->
                        <div style="background: white; padding: 1.5rem; border-radius: 1.25rem; border: 1px solid #fef3c7; display: flex; flex-direction: column; gap: 1.25rem;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                <div>
                                    <span style="font-size: 0.7rem; font-weight: 800; color: #b45309; text-transform: uppercase; display: block;">PROYECTO PV</span>
                                    <span style="font-weight: 700; color: #92400e;">{{ $sol->pv ? implode(', ', $sol->pv) : 'No especificado' }}</span>
                                </div>
                                <div>
                                    <span style="font-size: 0.7rem; font-weight: 800; color: #b45309; text-transform: uppercase; display: block;">REQUERIMIENTOS</span>
                                    <span style="font-weight: 700; color: #92400e;">{{ $sol->alojamiento ? '🏨 Alojamiento' : '' }} {{ $sol->traslado ? '✈️ Traslado' : '' }}</span>
                                </div>
                            </div>
                            <div>
                                <span style="font-size: 0.7rem; font-weight: 800; color: #b45309; text-transform: uppercase; display: block;">JUSTIFICACIÓN / MOTIVO</span>
                                <p style="font-size: 0.9rem; color: #92400e; margin: 4px 0 0; line-height: 1.5;">{{ $sol->motivo }}</p>
                            </div>
                            @if(!empty($sol->gastos))
                            <div>
                                <span style="font-size: 0.7rem; font-weight: 800; color: #b45309; text-transform: uppercase; display: block;">OTROS GASTOS DECLARADOS</span>
                                <div style="font-size: 0.85rem; color: #92400e; margin-top: 6px;">
                                    @foreach($sol->gastos as $g)
                                        <div style="padding: 6px 0; border-bottom: 1px dashed #fef3c7;">• {{ $g['descripcion'] }}</div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Formulario -->
                        <div style="background: white; padding: 1.5rem; border-radius: 1.25rem; border: 1px solid #fef3c7;">
                            <h4 style="font-size: 0.85rem; font-weight: 800; color: #92400e; margin-bottom: 1.5rem; text-transform: uppercase;">Definir Presupuesto Estimado</h4>
                            <form method="POST" action="{{ route('viajes.gestion.estimar', $sol) }}">
                                @csrf
                                <div style="margin-bottom: 1.5rem;">
                                    <label class="form-label" style="color: #92400e; font-weight: 800;">Monto Total Proyectado (CLP)</label>
                                    <div style="position: relative;">
                                        <span style="position: absolute; left: 1.25rem; top: 50%; transform: translateY(-50%); font-weight: 800; color: #d97706; font-size: 1.1rem;">$</span>
                                        <input type="number" name="monto_estimado" class="ms-search-input" style="padding-left: 2.5rem; border-color: #fcd34d; font-weight: 800; background: #fffcf5; font-size: 1.1rem;" placeholder="Ej: 450000" required>
                                    </div>
                                    <p style="font-size: 0.75rem; color: #b45309; margin-top: 0.5rem;">* Este monto incluye pasajes, hotel y gastos varios.</p>
                                </div>
                                <button type="submit" class="ms-btn-new" style="width: 100%; justify-content: center; background: #d97706; padding: 1rem; font-size: 1rem;">Enviar para Aprobación Jefatura</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div style="padding: 6rem; text-align: center; background: white;">
                <div style="display: flex; flex-direction: column; align-items: center; gap: 1rem; opacity: 0.3;">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    <p style="font-weight: 700; font-size: 1.25rem;">No hay solicitudes en fase de estimación</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- ── FASE 2: FINALIZACIÓN ──────────────────────── -->
<div id="section-finalizacion" class="ms-table-card" style="display: none;">
    <div class="ms-table-header" style="background: rgba(14, 165, 233, 0.05); padding: 1.5rem 2rem;">
        <div>
            <h3 class="ms-table-title" style="color: #0369a1; font-size: 1.25rem;">2. Fase de Cierre y Finalización</h3>
            <p style="font-size: 0.85rem; color: #0284c7; margin: 0;">Adjunte vouchers y datos de reserva de las solicitudes aprobadas.</p>
        </div>
    </div>
    <div class="ms-table-wrapper">
        @forelse($pendientesFinalizacion as $sol)
            <div style="border-bottom: 1px solid var(--border-color);">
                <div style="padding: 1.25rem 2rem; display: flex; align-items: center; justify-content: space-between; cursor: pointer; background: white;" onclick="toggleGestionForm('fin-{{ $sol->id }}')">
                    <div style="display: flex; align-items: center; gap: 1.5rem;">
                        <div style="width: 48px; height: 48px; border-radius: 1rem; background: #f0f9ff; display: flex; align-items: center; justify-content: center; border: 1px solid #e0f2fe;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                        </div>
                        <div>
                            <div style="font-weight: 800; color: var(--text-main); font-size: 1rem;">{{ $sol->tipo === 'externo' ? $sol->nombre_externo : ($sol->solicitante->name ?? 'N/A') }}</div>
                            <div style="font-size: 0.85rem; color: #0369a1; font-weight: 800;">Presupuesto Aprobado: ${{ number_format($sol->monto_estimado, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <span class="chip" style="background: #e0f2fe; color: #0369a1; font-weight: 800;">ID #{{ $sol->id }}</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--text-muted);"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </div>
                </div>

                <div id="form-fin-{{ $sol->id }}" style="display:none; padding: 2rem; background: #f0f9ff; border-top: 1px solid #bae6fd; animation: slideDown 0.3s ease;">
                    <div style="display: grid; grid-template-columns: 1fr 1.2fr; gap: 2rem;">
                        <!-- Detalles -->
                        <div style="background: white; padding: 1.5rem; border-radius: 1.25rem; border: 1px solid #bae6fd; display: flex; flex-direction: column; gap: 1.25rem;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                <div>
                                    <span style="font-size: 0.7rem; font-weight: 800; color: #0369a1; text-transform: uppercase; display: block;">PROYECTO PV</span>
                                    <span style="font-weight: 700; color: #0c4a6e;">{{ $sol->pv ? implode(', ', $sol->pv) : 'No especificado' }}</span>
                                </div>
                                <div>
                                    <span style="font-size: 0.7rem; font-weight: 800; color: #0369a1; text-transform: uppercase; display: block;">APROBADO POR</span>
                                    <span style="font-weight: 700; color: #0c4a6e;">{{ $sol->aprobador->name ?? 'Sistema' }}</span>
                                </div>
                            </div>
                            <div>
                                <span style="font-size: 0.7rem; font-weight: 800; color: #0369a1; text-transform: uppercase; display: block;">MOTIVO DEL VIAJE</span>
                                <p style="font-size: 0.9rem; color: #0c4a6e; margin: 4px 0 0; line-height: 1.5;">{{ $sol->motivo }}</p>
                            </div>
                        </div>

                        <!-- Formulario de Cierre -->
                        <div style="background: white; padding: 1.5rem; border-radius: 1.25rem; border: 1px solid #bae6fd;">
                            <h4 style="font-size: 0.85rem; font-weight: 800; color: #0369a1; margin-bottom: 1.5rem; text-transform: uppercase;">Datos Finales de Compra</h4>
                            <form method="POST" action="{{ route('viajes.gestion.store', $sol) }}" enctype="multipart/form-data">
                                @csrf
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                                    <div class="form-field">
                                        <label class="form-label" style="font-size: 0.75rem; font-weight: 800; color: #0369a1;">N° Reserva / Localizador</label>
                                        <input type="text" name="nro_reserva" class="ms-search-input" placeholder="Ej: LAT-992" required style="padding-left: 1rem; border-color: #bae6fd;">
                                    </div>
                                    <div class="form-field">
                                        <label class="form-label" style="font-size: 0.75rem; font-weight: 800; color: #0369a1;">Proveedor / Línea Aérea</label>
                                        <input type="text" name="linea_aerea" class="ms-search-input" placeholder="LATAM, Sky..." required style="padding-left: 1rem; border-color: #bae6fd;">
                                    </div>
                                </div>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                                    <div class="form-field">
                                        <label class="form-label" style="font-size: 0.75rem; font-weight: 800; color: #0369a1;">Costo Pasaje Final (CLP)</label>
                                        <input type="number" name="monto_pasaje" class="ms-search-input" required style="padding-left: 1rem; border-color: #bae6fd;" placeholder="0">
                                    </div>
                                    <div class="form-field">
                                        <label class="form-label" style="font-size: 0.75rem; font-weight: 800; color: #0369a1;">Costo Hotel Final (CLP)</label>
                                        <input type="number" name="monto_hotel" class="ms-search-input" style="padding-left: 1rem; border-color: #bae6fd;" placeholder="Opcional">
                                    </div>
                                </div>
                                <div style="margin-bottom: 1.5rem;">
                                    <label class="form-label" style="font-size: 0.75rem; font-weight: 800; color: #0369a1;">Adjuntar Comprobantes / Vouchers</label>
                                    <div style="border: 2px dashed #bae6fd; border-radius: 0.75rem; padding: 1rem; text-align: center; background: #f0f9ff; position: relative;">
                                        <input type="file" name="archivos[]" multiple class="ms-search-input" required style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="2" style="margin: 0 auto 0.5rem;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
                                        <span style="font-size: 0.8rem; font-weight: 700; color: #0369a1;">Seleccionar archivos PDF o Imagen</span>
                                    </div>
                                </div>
                                <button type="submit" class="ms-btn-new" style="width: 100%; justify-content: center; background: #0284c7; padding: 1rem; font-size: 1rem;">Finalizar Gestión y Notificar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div style="padding: 6rem; text-align: center; background: white;">
                <div style="display: flex; flex-direction: column; align-items: center; gap: 1rem; opacity: 0.3;">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 6L9 17l-5-5"/></svg>
                    <p style="font-weight: 700; font-size: 1.25rem;">No hay solicitudes aprobadas esperando cierre</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- ── HISTORIAL DE GESTIÓN ──────────────────────────── -->
<div id="section-historial" class="ms-table-card" style="display: none;">
    <div class="ms-table-header" style="background: rgba(16, 185, 129, 0.05); padding: 1.5rem 2rem;">
        <div>
            <h3 class="ms-table-title" style="color: #065f46; font-size: 1.25rem;">Historial de Comisiones Gestionadas</h3>
            <p style="font-size: 0.85rem; color: #059669; margin: 0;">Registro histórico de todos los viajes cerrados administrativamente.</p>
        </div>
    </div>
    <div class="ms-table-wrapper">
        <table class="ms-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Solicitante</th>
                    <th>Destino</th>
                    <th>Reserva / Proveedor</th>
                    <th>Costo Final</th>
                    <th>Fecha Cierre</th>
                    <th>Archivos</th>
                </tr>
            </thead>
            <tbody>
                @forelse($historial as $sol)
                <tr>
                    <td style="font-weight: 800; color: var(--brand-primary);">#{{ $sol->id }}</td>
                    <td style="font-weight: 700;">{{ $sol->tipo === 'externo' ? $sol->nombre_externo : ($sol->solicitante->name ?? 'N/A') }}</td>
                    <td style="font-weight: 600;">{{ $sol->destino }}</td>
                    <td>
                        <div style="font-weight: 700; color: var(--text-main);">{{ $sol->gestion?->nro_reserva ?? '—' }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $sol->gestion?->linea_aerea ?? '—' }}</div>
                    </td>
                    <td style="font-weight: 900; color: #10b981;">
                        ${{ number_format(($sol->gestion?->monto_pasaje ?? 0) + ($sol->gestion?->monto_hotel ?? 0) + ($sol->gestion?->monto_traslado ?? 0), 0, ',', '.') }}
                    </td>
                    <td style="font-size: 0.8rem; color: var(--text-muted);">{{ $sol->gestionado_en?->format('d/m/Y H:i') ?? '—' }}</td>
                    <td>
                        @if($sol->archivos->count() > 0)
                            <div style="display: flex; gap: 0.5rem;">
                                @foreach($sol->archivos as $archivo)
                                    <a href="{{ route('viajes.archivos.descargar', $archivo) }}" class="ms-btn-reset" style="padding: 0.4rem; border-radius: 0.5rem;" title="{{ $archivo->nombre_original }}">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            —
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 4rem; text-align: center; color: var(--text-muted); opacity: 0.5;">No hay registros históricos</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
var filtroActual = 'estimacion';

function filtrarGestion(tipo) {
    const sections = ['estimacion', 'finalizacion', 'historial'];
    const kpis = {
        'estimacion': 'kpi-estimacion',
        'finalizacion': 'kpi-finalizacion',
        'historial': 'kpi-historial'
    };

    sections.forEach(sec => {
        document.getElementById('section-' + sec).style.display = (sec === tipo) ? 'block' : 'none';
        document.getElementById(kpis[sec]).classList.toggle('active', sec === tipo);
    });

    filtroActual = tipo;
}

function toggleGestionForm(id) {
    const form = document.getElementById('form-' + id);
    if (!form) return;
    
    const isVisible = form.style.display === 'block';
    
    // Cerrar otros del MISMO tipo
    const prefix = id.split('-')[0];
    document.querySelectorAll('[id^="form-' + prefix + '"]').forEach(el => {
        if (el.id !== 'form-' + id) el.style.display = 'none';
    });

    form.style.display = isVisible ? 'none' : 'block';
}
</script>
<style>
@keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
.ms-kpi.active { transform: translateY(-5px); box-shadow: 0 12px 20px -10px rgba(0,0,0,0.1); }
</style>
@endpush

@endsection
