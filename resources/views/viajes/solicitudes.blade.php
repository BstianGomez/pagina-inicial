@extends('viajes.layouts.dashboard')

@section('title', 'Solicitar Viaje')
@section('subtitle', 'Crea una nueva solicitud de comisión de servicio')

@section('header')
<div class="ms-banner">
    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
        <div>
            <h1 class="ms-banner-title">Nueva Solicitud</h1>
            <p class="ms-banner-sub">Complete el formulario para iniciar el proceso de aprobación y estimación.</p>
        </div>
        <a href="{{ route('viajes.mis-solicitudes') }}" class="ms-btn-reset" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: white;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Volver al Listado
        </a>
    </div>
</div>
@endsection

@section('content')

<div class="card" style="border-radius: 1.5rem; overflow: visible;">
    <form id="solicitudForm" method="POST" action="{{ route('viajes.solicitudes.store') }}" style="padding: 2.5rem;">
        @csrf
        
        <!-- ── Tipo de Usuario ────────────────────────────── -->
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

        <!-- ── Campos Externos ────────────────────────────── -->
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
        <div class="form-grid">
            <div class="form-field">
                <label class="form-label">Centro de Costo (CECO)</label>
                <select name="ceco" class="ms-select" required style="width: 100%;">
                    <option value="">Seleccione CECO...</option>
                    <optgroup label="── Interno ──">
                        <option value="20001">20001 - Finanzas General</option>
                        <option value="20002">20002 - Finanzas y Operaciones</option>
                        <option value="20003">20003 - Desarrollo Organizacional</option>
                        <option value="20004">20004 - Operaciones</option>
                        <option value="20005">20005 - Servicios Generales</option>
                        <option value="20131">20131 - TI</option>
                        <option value="20132">20132 - Cursos Internos Fundación</option>
                        <option value="20133">20133 - Sofofa Servicios</option>
                        <option value="20134">20134 - Cursos Téc y Bec Fun</option>
                        <option value="20136">20136 - Gestión de Clientes</option>
                        <option value="20139">20139 - Innovación</option>
                        <option value="20143">20143 - Gerencias</option>
                        <option value="20147">20147 - Comunicaciones</option>
                        <option value="20148">20148 - Control de Gestión</option>
                    </optgroup>
                    <optgroup label="── Externo ──">
                        <option value="20137">20137 - Sucursal Concepción</option>
                        <option value="20141">20141 - Metro</option>
                        <option value="20144">20144 - Academia Latam</option>
                        <option value="20146">20146 - Academia Leonera</option>
                        <option value="20150a">20150 - Gastos Inversión Academia</option>
                        <option value="20150b">20150 - Academia Forma</option>
                        <option value="20151">20151 - Gestión Interna</option>
                        <option value="20152">20152 - Academia Carozzi</option>
                        <option value="20135">20135 - Academia SQM</option>
                        <option value="20153">20153 - Inversiones SQM</option>
                    </optgroup>
                    <optgroup label="── Unidad de Negocio ──">
                        <option value="20006">20006 - Formación</option>
                        <option value="20138">20138 - Plataforma</option>
                        <option value="20140">20140 - Gestión del Conocimiento</option>
                        <option value="20149">20149 - Diseño</option>
                    </optgroup>
                </select>
            </div>
            <div class="form-field">
                <label class="form-label">Destino</label>
                <input type="text" name="destino" class="ms-search-input" placeholder="Ciudad, País o Localidad" required style="padding-left: 1rem;">
            </div>
        </div>

        <!-- PV Section -->
        <div id="pvSection" style="display:none; margin: 1.5rem 0; background: #f0f9ff; padding: 1.5rem; border-radius: 1rem; border: 1px solid #bae6fd;">
            <label class="form-label" style="color: #0369a1;">Proyectos PV Asociados</label>
            <div style="display: flex; gap: 1.5rem; align-items: flex-start;">
                <div style="width: 150px;">
                    <input type="number" min="1" max="20" id="cantidadPV" class="ms-search-input" placeholder="Cant. PV" style="padding-left: 1rem;">
                </div>
                <div id="pvBlocks" style="flex: 1; display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 1rem;"></div>
            </div>
        </div>

        <div class="form-grid" style="margin-top: 1.5rem;">
            <div class="form-field">
                <label class="form-label">Fecha de Salida</label>
                <input type="date" name="fecha_viaje" class="ms-search-input" required style="padding-left: 1rem;">
            </div>
            <div class="form-field">
                <label class="form-label">Fecha de Retorno</label>
                <input type="date" name="fecha_retorno" class="ms-search-input" style="padding-left: 1rem;">
            </div>
        </div>

        <div class="form-field" style="margin-top: 1.5rem;">
            <label class="form-label">Motivo y Justificación del Viaje</label>
            <textarea name="motivo" class="ms-search-input" rows="3" placeholder="Explique brevemente el objetivo de la comisión..." required style="padding: 1rem; height: auto;"></textarea>
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

        <!-- ── GASTOS EXTRAS ─────────────────────────────── -->
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
                <div class="gasto-item" style="display: flex; gap: 1rem; align-items: center; animation: fadeIn 0.2s ease;">
                    <div style="flex: 1;">
                        <input type="text" name="gastos[0][descripcion]" class="ms-search-input" placeholder="Ej: Arriendo de automóvil por 3 días" style="padding-left: 1rem;">
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" style="width: 42px; height: 42px; border-radius: 0.75rem; border: none; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
            </div>

            <div class="form-field" style="margin-top: 1.5rem;">
                <label class="form-label">Observaciones Generales de Gastos</label>
                <textarea name="observaciones_gastos" class="ms-search-input" rows="2" placeholder="Notas adicionales sobre la rendición o estimación de estos gastos..." style="padding: 1rem; height: auto;"></textarea>
            </div>
        </div>

        <div style="margin-top: 3rem; display: flex; justify-content: flex-end; gap: 1rem;">
            <a href="{{ route('viajes.mis-solicitudes') }}" class="ms-btn-reset">Cancelar</a>
            <button type="submit" class="ms-btn-new" style="min-width: 220px; justify-content: center; font-size: 1rem;">Enviar Solicitud</button>
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

document.addEventListener('DOMContentLoaded', function() {
    const cecoSelect = document.querySelector('select[name="ceco"]');
    const pvSection = document.getElementById('pvSection');
    const cantidadPV = document.getElementById('cantidadPV');
    const pvBlocks = document.getElementById('pvBlocks');

    function handleCecoChange() {
        if (cecoSelect.value === '20136') {
            pvSection.style.display = 'block';
        } else {
            pvSection.style.display = 'none';
            pvBlocks.innerHTML = '';
            cantidadPV.value = '';
        }
    }
    
    cecoSelect.addEventListener('change', handleCecoChange);
    handleCecoChange();

    cantidadPV.addEventListener('input', function() {
        let n = Math.min(parseInt(cantidadPV.value) || 0, 20);
        pvBlocks.innerHTML = '';
        for (let i = 0; i < n; i++) {
            let block = document.createElement('div');
            block.innerHTML = `
                <label style="font-size: 0.65rem; font-weight: 800; color: #0369a1; text-transform: uppercase; margin-bottom: 4px; display: block;">PV #${i+1}</label>
                <input type="number" name="pv[${i}]" class="ms-search-input" placeholder="N°" style="padding-left: 0.75rem; font-size: 0.8rem;" required>
            `;
            pvBlocks.appendChild(block);
        }
    });

    document.getElementById('solicitudForm').addEventListener('submit', function(e) {
        // Simple validation or animation could go here
    });
});
</script>
<style>
@keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem; }
.form-grid.three-col { grid-template-columns: 1fr 1fr 1fr; }
@media (max-width: 768px) { .form-grid, .form-grid.three-col { grid-template-columns: 1fr; } }
</style>
@endpush

@endsection
