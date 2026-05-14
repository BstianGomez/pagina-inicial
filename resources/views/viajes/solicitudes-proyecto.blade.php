@extends('viajes.layouts.dashboard')

@section('title', 'Viaje con Proyecto')
@section('subtitle', 'Crea una solicitud de viaje asociada a un OT, OC o OP')

@section('header')
<div class="ms-banner">
    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
        <div>
            <h1 class="ms-banner-title">Viaje con Proyecto</h1>
            <p class="ms-banner-sub">Asocia esta solicitud a un número de proyecto (OT / OC / OP) para un control preciso de gastos.</p>
        </div>
        <a href="{{ route('viajes.mis-solicitudes') }}" class="ms-btn-reset" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: white;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Volver al Listado
        </a>
    </div>
</div>
@endsection

@section('content')

@if ($errors->any())
<div style="background: #fef2f2; border: 1.5px solid #fca5a5; border-radius: 1rem; padding: 1rem 1.5rem; margin-bottom: 1.5rem; color: #dc2626; font-size: 0.875rem; font-weight: 600;">
    <ul style="margin: 0; padding-left: 1.25rem;">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card" style="border-radius: 1.5rem; overflow: visible;">
    <form id="viajeProyectoForm" method="POST" action="{{ route('viajes.solicitudes-proyecto.store') }}" style="padding: 2.5rem;">
        @csrf

        {{-- ── Identificación del Proyecto ──────────────────── --}}
        <div style="background: #eff6ff; border: 1.5px solid #93c5fd; border-radius: 1rem; padding: 1.5rem; margin-bottom: 2rem;">
            <label style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: #1d4ed8; display: block; margin-bottom: 1rem;">
                Identificación del Proyecto
            </label>
            <div style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
                <div>
                    <label style="font-size: 0.75rem; font-weight: 700; color: #475569; display: block; margin-bottom: 0.4rem;">Tipo <span style="color:#ef4444;">*</span></label>
                    <select name="project_prefix" required
                        style="width: 70px; padding: 0.6rem 0.5rem; border-radius: 0.6rem; border: 1.5px solid #bfdbfe; font-size: 0.85rem; font-weight: 800; color: #1e40af; text-align: center; background: white; cursor: pointer;">
                        <option value="OT" {{ old('project_prefix') === 'OT' ? 'selected' : '' }}>OT</option>
                        <option value="OC" {{ old('project_prefix') === 'OC' ? 'selected' : '' }}>OC</option>
                        <option value="OP" {{ old('project_prefix') === 'OP' ? 'selected' : '' }}>OP</option>
                    </select>
                </div>
                <div>
                    <label style="font-size: 0.75rem; font-weight: 700; color: #475569; display: block; margin-bottom: 0.4rem;">Número <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="project_number" value="{{ old('project_number') }}" placeholder="Ej: 12345" required
                        oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                        style="width: 150px; padding: 0.6rem 1rem; border-radius: 0.6rem; border: 1.5px solid #bfdbfe; font-size: 0.9rem; font-weight: 700; color: #1e293b; background: white;">
                </div>
            </div>
            <p style="font-size: 0.75rem; color: #3b82f6; font-weight: 500; margin-top: 0.75rem; margin-bottom: 0;">
                Esta solicitud quedará asociada al proyecto seleccionado para facilitar el control de gastos y rendiciones.
            </p>
        </div>

        {{-- ── Tipo de Usuario ─────────────────────────────── --}}
        <div style="background: #f8fafc; padding: 1.5rem; border-radius: 1rem; border: 1px solid var(--border-color); margin-bottom: 2rem;">
            <label class="form-label" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--brand-primary); margin-bottom: 1rem; display: block;">Tipo de Solicitante</label>
            <div style="display: flex; gap: 2rem;">
                <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; font-weight: 700; color: var(--text-main);">
                    <input type="radio" name="tipoSolicitud" id="solicitudInterno" value="interno" checked onchange="toggleExterno()" style="width: 1.25rem; height: 1.25rem; accent-color: var(--brand-primary);">
                    Usuario Interno
                </label>
                <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; font-weight: 700; color: var(--text-main);">
                    <input type="radio" name="tipoSolicitud" id="solicitudExterno" value="externo" onchange="toggleExterno()" style="width: 1.25rem; height: 1.25rem; accent-color: var(--brand-primary);">
                    Usuario Externo
                </label>
            </div>
        </div>

        {{-- ── Campos Externos ─────────────────────────────── --}}
        <div id="externoFields" style="display:none; animation: fadeIn 0.3s ease; margin-bottom: 2rem; padding: 1.5rem; border: 1.5px dashed var(--brand-secondary); border-radius: 1rem; background: rgba(182, 153, 80, 0.03);">
            <h4 style="font-size: 0.85rem; font-weight: 800; color: var(--brand-secondary); text-transform: uppercase; margin-bottom: 1.5rem;">Datos del Viajero Externo</h4>
            <div class="form-grid">
                <div class="form-field">
                    <label class="form-label">Nombre Completo</label>
                    <input type="text" name="nombre_externo" class="ms-search-input" placeholder="Ej: Juan Pérez" style="padding-left: 1rem;">
                </div>
                <div class="form-field">
                    <label class="form-label">Correo Electrónico</label>
                    <input type="email" name="correo_externo" class="ms-search-input" placeholder="juan@ejemplo.com" style="padding-left: 1rem;">
                </div>
            </div>
            <div class="form-grid three-col" style="margin-top: 1rem;">
                <div class="form-field">
                    <label class="form-label">RUT</label>
                    <input type="text" name="rut" class="ms-search-input" placeholder="12.345.678-9" style="padding-left: 1rem;">
                </div>
                <div class="form-field">
                    <label class="form-label">Fecha Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" class="ms-search-input" style="padding-left: 1rem;">
                </div>
                <div class="form-field">
                    <label class="form-label">Cargo / Función</label>
                    <input type="text" name="cargo" class="ms-search-input" placeholder="Consultor, Director, etc." style="padding-left: 1rem;">
                </div>
            </div>
        </div>

        <!-- ── Datos Generales ────────────────────────────── -->
        <div class="form-field">
            <label class="form-label">Centro de Costo (CECO)</label>
                <select name="ceco" class="ms-select" required style="width: 100%;">
                    <option value="">Seleccione CECO...</option>
                    <optgroup label="── Interno ──">
                        <option value="20001" {{ old('ceco') === '20001' ? 'selected' : '' }}>20001 - Finanzas General</option>
                        <option value="20002" {{ old('ceco') === '20002' ? 'selected' : '' }}>20002 - Finanzas y Operaciones</option>
                        <option value="20003" {{ old('ceco') === '20003' ? 'selected' : '' }}>20003 - Desarrollo Organizacional</option>
                        <option value="20004" {{ old('ceco') === '20004' ? 'selected' : '' }}>20004 - Operaciones</option>
                        <option value="20005" {{ old('ceco') === '20005' ? 'selected' : '' }}>20005 - Servicios Generales</option>
                        <option value="20131" {{ old('ceco') === '20131' ? 'selected' : '' }}>20131 - TI</option>
                        <option value="20132" {{ old('ceco') === '20132' ? 'selected' : '' }}>20132 - Cursos Internos Fundación</option>
                        <option value="20133" {{ old('ceco') === '20133' ? 'selected' : '' }}>20133 - Sofofa Servicios</option>
                        <option value="20134" {{ old('ceco') === '20134' ? 'selected' : '' }}>20134 - Cursos Téc y Bec Fun</option>
                        <option value="20136" {{ old('ceco') === '20136' ? 'selected' : '' }}>20136 - Gestión de Clientes</option>
                        <option value="20139" {{ old('ceco') === '20139' ? 'selected' : '' }}>20139 - Innovación</option>
                        <option value="20143" {{ old('ceco') === '20143' ? 'selected' : '' }}>20143 - Gerencias</option>
                        <option value="20147" {{ old('ceco') === '20147' ? 'selected' : '' }}>20147 - Comunicaciones</option>
                        <option value="20148" {{ old('ceco') === '20148' ? 'selected' : '' }}>20148 - Control de Gestión</option>
                    </optgroup>
                    <optgroup label="── Externo ──">
                        <option value="20137" {{ old('ceco') === '20137' ? 'selected' : '' }}>20137 - Sucursal Concepción</option>
                        <option value="20141" {{ old('ceco') === '20141' ? 'selected' : '' }}>20141 - Metro</option>
                        <option value="20144" {{ old('ceco') === '20144' ? 'selected' : '' }}>20144 - Academia Latam</option>
                        <option value="20146" {{ old('ceco') === '20146' ? 'selected' : '' }}>20146 - Academia Leonera</option>
                    </optgroup>
                </select>
                </select>
        </div>

        <div class="form-grid" style="margin-top: 1.5rem;">
            <div class="form-field">
                <label class="form-label">Origen</label>
                <input type="text" name="origen" class="ms-search-input" placeholder="Ciudad, País o Localidad" required style="padding-left: 1rem;" value="{{ old('origen') }}">
            </div>
            <div class="form-field">
                <label class="form-label">Destino</label>
                <input type="text" name="destino" class="ms-search-input" placeholder="Ciudad, País o Localidad" required style="padding-left: 1rem;" value="{{ old('destino') }}">
            </div>
        </div>

        <div class="form-grid" style="margin-top: 1.5rem;">
            <div class="form-field">
                <label class="form-label">Fecha de Salida</label>
                <input type="date" name="fecha_viaje" class="ms-search-input" required style="padding-left: 1rem;" value="{{ old('fecha_viaje') }}">
            </div>
            <div class="form-field">
                <label class="form-label">Fecha de Retorno</label>
                <input type="date" name="fecha_retorno" class="ms-search-input" style="padding-left: 1rem;" value="{{ old('fecha_retorno') }}">
            </div>
        </div>

        <div class="form-field" style="margin-top: 1.5rem;">
            <label class="form-label">Motivo y Justificación del Viaje</label>
            <textarea name="motivo" class="ms-search-input" rows="3" placeholder="Explique brevemente el objetivo de la comisión de servicio..." required style="padding: 1rem; height: auto;">{{ old('motivo') }}</textarea>
        </div>

        <div class="form-grid" style="margin-top: 1.5rem;">
            <div class="form-field">
                <label class="form-label">¿Requiere Alojamiento?</label>
                <select name="alojamiento" class="ms-select">
                    <option value="no">No</option>
                    <option value="si">Sí, gestionar reserva</option>
                </select>
            </div>
            <div class="form-field">
                <label class="form-label">¿Requiere Traslado?</label>
                <select name="traslado" class="ms-select">
                    <option value="no">No</option>
                    <option value="si">Sí, gestionar pasajes/transporte</option>
                </select>
            </div>
        </div>

        {{-- ── Gastos Adicionales ──────────────────────────── --}}
        <div style="margin-top: 3rem; border-top: 2px solid var(--border-color); padding-top: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <div>
                    <h4 style="font-family: 'Outfit', sans-serif; font-size: 1.1rem; font-weight: 800; color: var(--text-main); margin: 0;">Gastos Adicionales</h4>
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.25rem;">Indique otros requerimientos como viáticos, peajes, arriendos, etc.</p>
                </div>
                <button type="button" onclick="agregarGasto()" class="ms-btn-new" style="font-size: 0.8rem; padding: 0.5rem 1rem;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Añadir Gasto
                </button>
            </div>

            <div id="gastosContainer" style="display: flex; flex-direction: column; gap: 1rem;">
                <div class="gasto-item" style="display: flex; gap: 1rem; align-items: center;">
                    <div style="flex: 1;">
                        <input type="text" name="gastos[0][descripcion]" class="ms-search-input" placeholder="Ej: Arriendo de automóvil por 3 días" style="padding-left: 1rem;">
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" style="width: 42px; height: 42px; border-radius: 0.75rem; border: none; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
            </div>
        </div>

        <div style="margin-top: 3rem; display: flex; justify-content: flex-end; gap: 1rem;">
            <a href="{{ route('viajes.mis-solicitudes') }}" class="ms-btn-reset">Cancelar</a>
            <button type="submit" class="ms-btn-new" style="min-width: 220px; justify-content: center; font-size: 1rem;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2L11 13"/><path d="M22 2L15 22 11 13 2 9l20-7z"/></svg>
                Enviar Solicitud
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function toggleExterno() {
    var externo = document.getElementById('solicitudExterno').checked;
    document.getElementById('externoFields').style.display = externo ? 'block' : 'none';
}

var gastoIndex = 1;
function agregarGasto() {
    var container = document.getElementById('gastosContainer');
    var div = document.createElement('div');
    div.className = 'gasto-item';
    div.style.cssText = 'display: flex; gap: 1rem; align-items: center; animation: fadeIn 0.2s ease;';
    div.innerHTML = `
        <div style="flex: 1;">
            <input type="text" name="gastos[${gastoIndex}][descripcion]" class="ms-search-input" placeholder="Descripción del gasto..." style="padding-left: 1rem;">
        </div>
        <button type="button" onclick="this.parentElement.remove()" style="width: 42px; height: 42px; border-radius: 0.75rem; border: none; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; cursor: pointer;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
    `;
    container.appendChild(div);
    gastoIndex++;
}
</script>
<style>
@keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem; }
.form-grid.three-col { grid-template-columns: 1fr 1fr 1fr; }
.form-field { display: flex; flex-direction: column; gap: 0.4rem; }
.form-label { font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.025em; }
@media (max-width: 768px) { .form-grid, .form-grid.three-col { grid-template-columns: 1fr; } }
</style>
@endpush

@endsection
