@extends('viajes.layouts.dashboard')

@section('title', 'Solicitar Viaje')
@section('subtitle', 'Crea una nueva solicitud de viaje')

@section('header')
<div class="banner">
    <h1>Solicitar Viaje</h1>
    <p>Selecciona si la solicitud es para usuario interno o externo.</p>
</div>
@endsection

@section('content')

@if(session('success'))
<div style="background:#dcfce7;border:1px solid #86efac;color:#15803d;padding:14px 20px;border-radius:12px;margin-bottom:24px;display:flex;align-items:center;gap:10px;">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('success') }}
</div>
@endif

<div class="card">
    <form id="solicitudForm" method="POST" action="{{ route('viajes.solicitudes.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">¿Para quién es la solicitud?</label>
            <div style="display: flex; gap: 24px; align-items: center; padding: 4px 0;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <input type="radio" name="tipoSolicitud" id="solicitudInterno" value="interno" checked onchange="toggleExterno()">
                    <label for="solicitudInterno">Usuario interno</label>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <input type="radio" name="tipoSolicitud" id="solicitudExterno" value="externo" onchange="toggleExterno()">
                    <label for="solicitudExterno">Usuario externo</label>
                </div>
            </div>
        </div>

        <div id="externoFields" style="display:none">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-field">
                    <label class="form-label">Nombre externo</label>
                    <input type="text" name="nombre_externo" class="form-control" placeholder="Nombre completo">
                </div>
                <div class="form-field">
                    <label class="form-label">Correo externo</label>
                    <input type="email" name="correo_externo" class="form-control" placeholder="correo@ejemplo.com">
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-field">
                    <label class="form-label">RUT</label>
                    <input type="text" name="rut" class="form-control" placeholder="12.345.678-9">
                </div>
                <div class="form-field">
                    <label class="form-label">Fecha de nacimiento</label>
                    <input type="date" name="fecha_nacimiento" class="form-control">
                </div>
                <div class="form-field">
                    <label class="form-label">Cargo</label>
                    <input type="text" name="cargo" class="form-control" placeholder="Ej: Consultor">
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">CECO</label>
            <select name="ceco" class="form-select">
                <option value="">Selecciona un Centro de Costo (CECO)</option>

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
        <!-- PV Section: solo visible si CECO 20136 -->
        <div id="pvSection" class="mb-3" style="display:none;">
            <label class="form-label">Cantidad de PV</label>
                <div style="display: flex; align-items: flex-start; gap: 20px;">
                    <input type="number" min="1" max="20" id="cantidadPV" class="form-control" placeholder="¿Cuántos PV desea agregar?" style="max-width:200px;">
                    <div id="pvBlocks" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-start; width: 100%;"></div>
                </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Destino</label>
            <input type="text" name="destino" class="form-control" placeholder="Destino del viaje" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label class="form-label">Fecha de viaje</label>
                <input type="date" name="fecha_viaje" class="form-control" required>
            </div>
            <div>
                <label class="form-label">Fecha de retorno</label>
                <input type="date" name="fecha_retorno" class="form-control">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Motivo</label>
            <textarea name="motivo" class="form-control" rows="3" placeholder="Describe el motivo del viaje..." required></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 28px;">
            <div>
                <label class="form-label">¿Necesita alojamiento?</label>
                <select name="alojamiento" class="form-select">
                    <option value="no">No</option>
                    <option value="si">Sí</option>
                </select>
            </div>
            <div>
                <label class="form-label">¿Necesita traslado?</label>
                <select name="traslado" class="form-select">
                    <option value="no">No</option>
                    <option value="si">Sí</option>
                </select>
            </div>
        </div>

        <!-- ── GASTOS EXTRAS ─────────────────────────────── -->
        <div style="border-top: 1px solid var(--line); padding-top: 28px; margin-bottom: 28px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <div>
                    <h4 style="font-size: 15px; font-weight: 700; color: var(--ink); margin: 0;">Gastos Extras</h4>
                    <p style="font-size: 13px; color: var(--muted); margin: 4px 0 0;">Agrega cualquier gasto adicional que requiera el viaje (automóvil, peajes, viáticos, etc.)</p>
                </div>
                <button type="button" onclick="agregarGasto()" style="background: #eff6ff; color: #1d4ed8; border: none; border-radius: 8px; padding: 8px 16px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Agregar gasto
                </button>
            </div>

            <div id="gastosContainer">
                <!-- Ítem inicial -->
                <div class="gasto-item" style="display: grid; grid-template-columns: 1fr auto; gap: 12px; align-items: end; margin-bottom: 12px;">
                    <div>
                        <label class="form-label">Descripción del gasto</label>
                        <input type="text" name="gastos[0][descripcion]" class="form-control" placeholder="Ej: Arriendo de automóvil, peaje, viático...">
                    </div>
                    <button type="button" onclick="this.closest('.gasto-item').remove()" style="background: #fff1f2; color: #be123c; border: none; border-radius: 8px; padding: 10px 12px; cursor: pointer; margin-bottom: 0;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <div class="mb-3" style="margin-top: 16px;">
                <label class="form-label">Observaciones generales de gastos</label>
                <textarea name="observaciones_gastos" class="form-control" rows="2" placeholder="Cualquier información adicional sobre los gastos..."></textarea>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end;">
            <button type="submit" class="btn-primary" style="min-width: 200px;">Enviar Solicitud</button>
        </div>
    </form>
</div>

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
    div.style.cssText = 'display: grid; grid-template-columns: 1fr auto; gap: 12px; align-items: end; margin-bottom: 12px;';
    div.innerHTML = `
        <div>
            <label class="form-label">Descripción del gasto</label>
            <input type="text" name="gastos[${gastoIndex}][descripcion]" class="form-control" placeholder="Ej: Arriendo de automóvil, peaje, viático...">
        </div>
        <button type="button" onclick="this.closest('.gasto-item').remove()" style="background: #fff1f2; color: #be123c; border: none; border-radius: 8px; padding: 10px 12px; cursor: pointer; margin-bottom: 0;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    `;
    container.appendChild(div);
    gastoIndex++;
}
// Mostrar sección PV solo si CECO 20136 está seleccionado
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
        let n = parseInt(cantidadPV.value) || 0;
        pvBlocks.innerHTML = '';
        for (let i = 0; i < n; i++) {
            let block = document.createElement('div');
                block.style.display = 'flex';
                block.style.flexDirection = 'column';
                block.style.alignItems = 'flex-start';
                block.style.marginBottom = '8px';
                block.style.maxWidth = '140px';
                block.style.flex = '1 0 120px';
            block.innerHTML = `
                    <label class="form-label" style="font-size:12px;margin-bottom:4px;">PV #${i+1}</label>
                    <input type="number" name="pv[${i}]" class="form-control" placeholder="Solo números" style="width:120px;" min="0" step="1" pattern="[0-9]*" inputmode="numeric">
                `;
            pvBlocks.appendChild(block);
        }
    });
});
</script>
@endsection
