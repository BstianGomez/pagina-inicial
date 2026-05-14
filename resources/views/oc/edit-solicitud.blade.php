@extends('oc.layouts.dashboard')

@section('title', 'Editar Solicitud')
@section('subtitle', 'Modificación de orden de compra #' . $solicitud->id)

@section('header')
<div class="ms-banner">
    <div>
        <h1 class="ms-banner-title">Modo Edición: Solicitud #{{ $solicitud->id }}</h1>
        <p class="ms-banner-sub">Actualice los datos necesarios para reenviar la solicitud a revisión.</p>
    </div>
    <div style="background: rgba(255,255,255,0.1); padding: 0.75rem 1.5rem; border-radius: 2rem; font-weight: 800; font-size: 0.8rem; letter-spacing: 0.05em;">
        ESTADO: {{ strtoupper($solicitud->estado) }}
    </div>
</div>
@endsection

@section('content')
<style>
    .form-card-premium {
        background: white;
        border-radius: 1.5rem;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-premium);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .card-section-header {
        padding: 1.5rem 2rem;
        background: #fcfdfe;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-section-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--text-main);
        margin: 0;
    }

    .card-body-padding {
        padding: 2rem;
    }

    .form-grid-modern {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.25rem;
        margin-bottom: 1.25rem;
    }

    .form-group-modern {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .label-flex-modern {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .label-modern {
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-muted);
    }

    .btn-add-option-modern {
        background: rgba(15, 107, 182, 0.1);
        color: var(--brand-primary);
        border: none;
        width: 18px;
        height: 18px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-add-option-modern:hover {
        background: var(--brand-primary);
        color: white;
    }

    .input-premium {
        width: 100%;
        padding: 0.7rem 0.9rem;
        border: 1.5px solid var(--border-color);
        border-radius: 0.75rem;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.2s;
        outline: none;
        background: #fff;
    }

    .input-premium:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(15, 107, 182, 0.1);
    }

    .textarea-premium {
        min-height: 80px;
        resize: vertical;
    }

    .form-footer-premium {
        padding: 1.5rem 2rem;
        background: #fcfdfe;
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }

    .rechazo-alert {
        background: #fff1f2;
        border: 1px solid #fecdd3;
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
        display: flex;
        gap: 1rem;
        align-items: flex-start;
    }

    .required-star { color: #ef4444; }
</style>

@if($solicitud->observacion_rechazo)
    <div class="rechazo-alert">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#e11d48" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        <div>
            <div style="font-weight: 800; color: #9f1239; font-size: 0.9rem; text-transform: uppercase; margin-bottom: 0.25rem;">Motivo de ajuste / Observación del gestor</div>
            <div style="color: #be123c; font-size: 0.95rem; line-height: 1.5;">{{ $solicitud->observacion_rechazo }}</div>
        </div>
    </div>
@endif

@if ($errors->any())
    <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 1.25rem; border-radius: 1rem; margin-bottom: 2rem; display: flex; align-items: flex-start; gap: 1rem; font-weight: 600;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        <div>
            <strong>Errores de validación:</strong>
            <ul style="margin: 0.5rem 0 0; padding-left: 1.25rem; font-size: 0.9rem; font-weight: 500;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

@php
    $extras = json_decode($solicitud->datos_extra, true) ?? [];
@endphp

<form action="{{ route('oc.solicitudes.actualizar', $solicitud->id) }}" method="POST">
    @csrf
    
    <div class="form-card-premium">
        <!-- SECCION I: PROYECTO -->
        <div class="card-section-header">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
            <h2 class="card-section-title">Información del Proyecto</h2>
        </div>
        
        <div class="card-body-padding">
            <div class="form-grid-modern">
                <div class="form-group-modern">
                    <label class="label-modern">CECO <span class="required-star">*</span></label>
                    <select class="input-premium select-premium" name="ceco" required>
                        @foreach($cecos->groupBy('tipo') as $tipo => $items)
                            <optgroup label="── {{ $tipo }} ──">
                                @foreach($items as $ceco)
                                    <option value="{{ $ceco->codigo }}" {{ $solicitud->ceco == $ceco->codigo ? 'selected' : '' }}>
                                        {{ $ceco->codigo }} - {{ $ceco->nombre }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                <div class="form-group-modern">
                    <div class="label-flex-modern">
                        <label class="label-modern">Coordinador <span class="required-star">*</span></label>
                        <button type="button" class="btn-add-option-modern" onclick="addNewOption('coordinador', 'coordinador', 'Nuevo Coordinador')">+</button>
                    </div>
                    <select class="input-premium select-premium" id="coordinador" name="coordinador" required>
                        @foreach($coordinadores as $item)
                            <option value="{{ $item->nombre }}" {{ ($extras['coordinador'] ?? '') == $item->nombre ? 'selected' : '' }}>
                                {{ $item->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group-modern">
                    <div class="label-flex-modern">
                        <label class="label-modern">Tipo de Servicio <span class="required-star">*</span></label>
                        <button type="button" class="btn-add-option-modern" onclick="addNewOption('tipo_servicio', 'tipo_servicio', 'Nuevo Tipo de Servicio')">+</button>
                    </div>
                    <select class="input-premium select-premium" id="tipo_servicio" name="tipo_servicio" required>
                        @foreach($tipoServicios as $item)
                            <option value="{{ $item->nombre }}" {{ ($extras['tipo_servicio'] ?? '') == $item->nombre ? 'selected' : '' }}>
                                {{ $item->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-grid-modern">
                <div class="form-group-modern">
                    <div class="label-flex-modern">
                        <label class="label-modern">Tipo de Proyecto <span class="required-star">*</span></label>
                        <button type="button" class="btn-add-option-modern" onclick="addNewOption('tipo_proyecto', 'tipo_proyecto', 'Nuevo Tipo de Proyecto')">+</button>
                    </div>
                    <select class="input-premium select-premium" id="tipo_proyecto" name="tipo_proyecto" required>
                        @foreach($tipoProyectos as $item)
                            <option value="{{ $item->nombre }}" {{ ($extras['tipo_proyecto'] ?? '') == $item->nombre ? 'selected' : '' }}>
                                {{ $item->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group-modern">
                    <label class="label-modern">N° de Proyecto / OT / OP <span class="required-star">*</span></label>
                    <div style="display: flex; gap: 0.5rem;">
                        <select name="project_prefix" class="input-premium select-premium" style="width: 85px !important; min-width: 85px !important; padding-right: 1.5rem;" required>
                            @php
                                $currentFull = $extras['numero_proyecto'] ?? $extras['numero_ot'] ?? '';
                                $currentPrefix = $extras['project_prefix'] ?? (str_contains($currentFull, '-') ? explode('-', $currentFull)[0] : 'OC');
                                $currentNumber = $extras['project_number'] ?? (str_contains($currentFull, '-') ? explode('-', $currentFull)[1] : $currentFull);
                            @endphp
                            <option value="OC" {{ $currentPrefix === 'OC' ? 'selected' : '' }}>OC</option>
                            <option value="OT" {{ $currentPrefix === 'OT' ? 'selected' : '' }}>OT</option>
                            <option value="OP" {{ $currentPrefix === 'OP' ? 'selected' : '' }}>OP</option>
                        </select>
                        <input type="text" class="input-premium" name="project_number" value="{{ old('project_number', $currentNumber) }}" required placeholder="Solo número" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                    </div>
                </div>
                <div class="form-group-modern">
                    <label class="label-modern">Tipo de Documento <span class="required-star">*</span></label>
                    <select class="input-premium select-premium" name="tipo_documento" required>
                        <option value="Factura" {{ $solicitud->tipo_documento == 'Factura' ? 'selected' : '' }}>Factura</option>
                        <option value="Boleta" {{ $solicitud->tipo_documento == 'Boleta' ? 'selected' : '' }}>Boleta</option>
                        <option value="Otro" {{ $solicitud->tipo_documento == 'Otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- SECCION II: PROVEEDOR Y MONTOS -->
        <div class="card-section-header" style="border-top: 1px solid var(--border-color);">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            <h2 class="card-section-title">Proveedor y Montos</h2>
        </div>

        <div class="card-body-padding">
            <div class="form-grid-modern">
                <div class="form-group-modern">
                    <label class="label-modern">RUT Proveedor <span class="required-star">*</span></label>
                    <input type="text" class="input-premium" name="rut_proveedor" value="{{ old('rut_proveedor', $solicitud->rut) }}" required>
                </div>
                <div class="form-group-modern" style="grid-column: span 2;">
                    <label class="label-modern">Nombre Proveedor <span class="required-star">*</span></label>
                    <input type="text" class="input-premium" name="nombre_proveedor" value="{{ old('nombre_proveedor', $solicitud->proveedor) }}" required>
                </div>
            </div>

            <div class="form-group-modern" style="margin-bottom: 1.5rem;">
                <label class="label-modern">Descripción de la compra <span class="required-star">*</span></label>
                <textarea class="input-premium textarea-premium" name="descripcion" required>{{ old('descripcion', $solicitud->descripcion) }}</textarea>
            </div>

            <div class="form-grid-modern">
                <div class="form-group-modern">
                    <label class="label-modern">Cantidad <span class="required-star">*</span></label>
                    <input type="number" class="input-premium" name="cantidad" value="{{ old('cantidad', $solicitud->cantidad) }}" required min="1">
                </div>
                <div class="form-group-modern">
                    <label class="label-modern">Monto Unitario <span class="required-star">*</span></label>
                    <input type="text" class="input-premium" id="montoDisplay" value="$ {{ number_format($solicitud->monto, 0, ',', '.') }}" required>
                    <input type="hidden" name="monto" id="montoValue" value="{{ $solicitud->monto }}">
                </div>
            </div>
        </div>

        <div class="form-footer-premium">
            <a href="{{ route('oc.dashboard') }}" class="ms-btn-reset">
                Cancelar y Volver
            </a>
            <button type="submit" class="ms-btn-reset ms-btn-new">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                Guardar Cambios
            </button>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    async function addNewOption(type, selectId, title) {
        const { value: name } = await Swal.fire({
            title: title,
            input: 'text',
            inputLabel: 'Nombre del nuevo elemento',
            showCancelButton: true,
            inputValidator: (value) => {
                if (!value) return 'El nombre es obligatorio';
            }
        });

        if (name) {
            Swal.fire({ title: 'Guardando...', didOpen: () => { Swal.showLoading(); } });
            
            try {
                const response = await fetch('/oc/configuracion/ajax-add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ type, nombre: name })
                });

                const data = await response.json();

                if (data.success) {
                    const select = document.getElementById(selectId);
                    const option = new Option(name, name, true, true);
                    select.add(option);
                    Swal.fire({ icon: 'success', title: 'Añadido correctamente', timer: 1500, showConfirmButton: false });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message });
                }
            } catch (error) {
                Swal.fire({ icon: 'error', title: 'Error de red' });
            }
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const montoDisplay = document.getElementById('montoDisplay');
        const montoValue = document.getElementById('montoValue');

        montoDisplay.addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^\d]/g, '');
            if (value) {
                e.target.value = '$ ' + parseInt(value).toLocaleString('es-CL');
                montoValue.value = value;
            } else {
                e.target.value = '';
                montoValue.value = '';
            }
        });
    });
</script>
@endpush
