@extends('oc.layouts.dashboard')

@section('title', 'Solicitud OC Negocio')
@section('subtitle', 'Creación de orden de compra para unidades de negocio')

@section('header')
<div class="ms-banner">
    <div>
        <h1 class="ms-banner-title">Solicitud OC Negocio</h1>
        <p class="ms-banner-sub">Gestione solicitudes vinculadas a proyectos y unidades de negocio específicas.</p>
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

    .input-premium:read-only {
        background-color: #f8fafc;
        cursor: not-allowed;
    }

    .select-premium {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        padding-right: 2.5rem;
    }

    .textarea-premium {
        min-height: 80px;
        resize: vertical;
    }

    .attachment-premium {
        border: 2px dashed var(--border-color);
        border-radius: 1rem;
        padding: 1.25rem;
        text-align: center;
        transition: all 0.2s;
        background: #f8fafc;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.4rem;
    }

    .attachment-premium:hover {
        border-color: var(--brand-primary);
        background: rgba(15, 107, 182, 0.02);
    }

    .form-footer-premium {
        padding: 1.5rem 2rem;
        background: #fcfdfe;
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }

    .modal-premium {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.5);
        backdrop-filter: blur(8px);
        z-index: 2000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
    }

    .modal-premium-content {
        background: white;
        width: 100%;
        max-width: 650px;
        border-radius: 2rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        overflow: hidden;
    }

    .required-star { color: #ef4444; }
</style>

@if ($errors->any())
    <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 1.25rem; border-radius: 1rem; margin-bottom: 2rem; display: flex; align-items: flex-start; gap: 1rem; font-weight: 600;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        <div>
            <strong>Errores en el formulario:</strong>
            <ul style="margin: 0.5rem 0 0; padding-left: 1.25rem; font-size: 0.9rem; font-weight: 500;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<form action="{{ route('oc.negocio') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div style="display:none;" aria-hidden="true">
        <input type="text" name="my_company_website" tabindex="-1" autocomplete="off">
    </div>

    <div class="form-card-premium">
        <!-- SECCION I: PROYECTO -->
        <div class="card-section-header">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
            <h2 class="card-section-title">Información del Proyecto</h2>
        </div>
        
        <div class="card-body-padding">
            <div class="form-grid-modern">
                <div class="form-group-modern">
                    <label class="label-modern">CECO de Imputación <span class="required-star">*</span></label>
                    <select class="input-premium select-premium" name="ceco" required>
                        <option value="">Seleccionar CECO...</option>
                        @foreach($cecos->groupBy('tipo') as $tipo => $items)
                            <optgroup label="── {{ $tipo }} ──">
                                @foreach($items as $ceco)
                                    <option value="{{ $ceco->codigo }}">{{ $ceco->codigo }} - {{ $ceco->nombre }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                <div class="form-group-modern">
                    <div class="label-flex-modern">
                        <label class="label-modern">Coordinador <span class="required-star">*</span></label>
                        <button type="button" class="btn-add-option-modern" onclick="addNewOption('coordinador', 'coordinador', 'Nuevo Coordinador')" title="Añadir coordinador">+</button>
                    </div>
                    <select class="input-premium select-premium" name="coordinador" id="coordinador" required>
                        <option value="">Seleccionar...</option>
                        @foreach($coordinadores as $item)
                            <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group-modern">
                    <div class="label-flex-modern">
                        <label class="label-modern">Tipo de Servicio <span class="required-star">*</span></label>
                        <button type="button" class="btn-add-option-modern" onclick="addNewOption('tipo_servicio', 'tipo_servicio', 'Nuevo Tipo de Servicio')" title="Añadir tipo de servicio">+</button>
                    </div>
                    <select class="input-premium select-premium" name="tipo_servicio" id="tipo_servicio" required>
                        <option value="">Seleccionar...</option>
                        @foreach($tipoServicios as $item)
                            <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-grid-modern">
                <div class="form-group-modern">
                    <div class="label-flex-modern">
                        <label class="label-modern">Tipo de Proyecto <span class="required-star">*</span></label>
                        <button type="button" class="btn-add-option-modern" onclick="addNewOption('tipo_proyecto', 'tipo_proyecto', 'Nuevo Tipo de Proyecto')" title="Añadir tipo de proyecto">+</button>
                    </div>
                    <select class="input-premium select-premium" name="tipo_proyecto" id="tipo_proyecto" required>
                        <option value="">Seleccionar...</option>
                        @foreach($tipoProyectos as $item)
                            <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group-modern">
                    <label class="label-modern">N° de Proyecto / OT / OP <span class="required-star">*</span></label>
                    <div style="display: flex; gap: 0.5rem;">
                        <select name="project_prefix" class="input-premium select-premium" style="width: 85px !important; min-width: 85px !important; padding-right: 1.5rem;" required>
                            <option value="OC" {{ old('project_prefix') === 'OC' ? 'selected' : '' }}>OC</option>
                            <option value="OT" {{ old('project_prefix') === 'OT' ? 'selected' : '' }}>OT</option>
                            <option value="OP" {{ old('project_prefix') === 'OP' ? 'selected' : '' }}>OP</option>
                        </select>
                        <input type="text" class="input-premium" name="project_number" value="{{ old('project_number') }}" required placeholder="Solo número" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                    </div>
                </div>
                <div class="form-group-modern">
                    <label class="label-modern">Tipo Documento <span class="required-star">*</span></label>
                    <select class="input-premium select-premium" name="tipo_documento" required>
                        <option value="">Seleccionar...</option>
                        <option value="Factura">Factura</option>
                        <option value="Boleta de Honorarios">Boleta de Honorarios</option>
                        <option value="Boleta">Boleta</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- SECCION II: PROVEEDOR Y PAGOS -->
        <div class="card-section-header" style="border-top: 1px solid var(--border-color);">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            <h2 class="card-section-title">Proveedor y Detalles de Pago</h2>
        </div>

        <div class="card-body-padding">
            <div class="form-grid-modern">
                <div class="form-group-modern" style="grid-column: span 2;">
                    <label class="label-modern">Proveedor <span class="required-star">*</span></label>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <select class="input-premium select-premium" name="nombre_proveedor" id="nombreProveedor" required>
                            <option value="">Seleccionar proveedor...</option>
                            @foreach($proveedores as $proveedor)
                                <option value="{{ $proveedor->nombre }}" data-rut="{{ $proveedor->rut }}">{{ $proveedor->nombre }}</option>
                            @endforeach
                        </select>
                        <a href="#" id="btnOpenProveedorModal" style="font-size: 0.75rem; color: var(--brand-primary); font-weight: 700; text-decoration: none;">+ ¿No existe el proveedor? Registrar aquí</a>
                    </div>
                </div>
                <div class="form-group-modern">
                    <label class="label-modern">RUT Proveedor</label>
                    <input type="text" class="input-premium" name="rut_proveedor" id="rutProveedor" readonly placeholder="Auto-completado">
                </div>
            </div>

            <div class="form-grid-modern">
                <div class="form-group-modern">
                    <label class="label-modern">Módulos / Cantidad <span class="required-star">*</span></label>
                    <input type="number" class="input-premium" name="cantidad_modulos" value="1" min="1" required>
                </div>
                <div class="form-group-modern">
                    <label class="label-modern">Monto Total <span class="required-star">*</span></label>
                    <input type="text" class="input-premium" id="montoDisplay" placeholder="$ 0" required>
                    <input type="hidden" name="monto" id="montoValue">
                </div>
                <div class="form-group-modern">
                    <label class="label-modern">Email Envío OC <span class="required-star">*</span></label>
                    <input type="email" class="input-premium" name="email" required placeholder="ejemplo@correo.com">
                </div>
            </div>

            <div class="form-group-modern" style="margin-bottom: 1.5rem;">
                <label class="label-modern">Documentación Adjunta (Cotización)</label>
                <div class="attachment-premium" onclick="document.getElementById('adjunto_input').click()">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--brand-primary);"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                    <p id="file_status" style="margin:0; font-weight: 700; font-size: 0.9rem;">Adjuntar Cotización</p>
                    <span style="font-size: 0.75rem; color: var(--text-muted);">PDF, Word o Excel (Máx 10MB)</span>
                    <input type="file" name="adjunto" id="adjunto_input" accept=".pdf,.doc,.docx,.xls,.xlsx" style="display: none;">
                </div>
            </div>

            <div class="form-group-modern" style="margin-bottom: 1.5rem;">
                <label class="label-modern">Instrucciones Especiales para Negocio</label>
                <textarea class="input-premium textarea-premium" name="observacion2" placeholder="Detalles de facturación o entrega específicos..."></textarea>
            </div>

            <div class="form-group-modern">
                <label class="label-modern">Observaciones Generales</label>
                <textarea class="input-premium textarea-premium" name="observacion" placeholder="Comentarios para el aprobador..."></textarea>
            </div>
        </div>

        <div class="form-footer-premium">
            <a href="{{ route('oc.index') }}" class="ms-btn-reset">
                Cancelar
            </a>
            <button type="submit" class="ms-btn-reset ms-btn-new">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                Enviar Solicitud
            </button>
        </div>
    </div>
</form>

<!-- Modal Nuevo Proveedor -->
<div id="proveedorModal" class="modal-premium">
    <div class="modal-premium-content">
        <div style="padding: 1.5rem 2rem; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; background: #fcfdfe;">
            <h3 style="margin:0; font-weight: 800; color: var(--text-main);">Registrar Nuevo Proveedor</h3>
            <button type="button" class="ms-btn-reset" style="padding: 0.4rem; border-radius: 50%;" id="btnCloseProveedorModal">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <form id="formNuevoProveedor">
            <div style="padding: 2rem; max-height: 70vh; overflow-y: auto;">
                <div id="proveedorStep1">
                    <p style="font-weight: 800; color: var(--brand-primary); margin-bottom: 1.5rem; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">I. Información Básica</p>
                    <div class="form-grid-modern">
                        <div class="form-group-modern"><label class="label-modern">RUT <span class="required-star">*</span></label><input type="text" class="input-premium" id="prov_rut" required placeholder="12.345.678-9"></div>
                        <div class="form-group-modern"><label class="label-modern">Nombre Comercial <span class="required-star">*</span></label><input type="text" class="input-premium" id="prov_nombre" required></div>
                    </div>
                    <div class="form-group-modern" style="margin-bottom: 1.5rem;"><label class="label-modern">Razón Social <span class="required-star">*</span></label><input type="text" class="input-premium" id="prov_razon_social" required></div>
                    <div class="form-grid-modern">
                        <div class="form-group-modern"><label class="label-modern">Dirección <span class="required-star">*</span></label><input type="text" class="input-premium" id="prov_direccion" required></div>
                        <div class="form-group-modern"><label class="label-modern">Comuna <span class="required-star">*</span></label><input type="text" class="input-premium" id="prov_comuna" required></div>
                    </div>
                    <div class="form-grid-modern">
                        <div class="form-group-modern"><label class="label-modern">Región <span class="required-star">*</span></label><input type="text" class="input-premium" id="prov_region" required></div>
                        <div class="form-group-modern"><label class="label-modern">Teléfono <span class="required-star">*</span></label><input type="text" class="input-premium" id="prov_telefono" required></div>
                    </div>
                </div>
                <div id="proveedorStep2" style="display: none;">
                    <p style="font-weight: 800; color: var(--brand-primary); margin-bottom: 1.5rem; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">II. Datos Bancarios</p>
                    <div class="form-grid-modern">
                        <div class="form-group-modern"><label class="label-modern">N° Cuenta <span class="required-star">*</span></label><input type="text" class="input-premium" id="prov_numero_cuenta" required></div>
                        <div class="form-group-modern"><label class="label-modern">Tipo Cuenta <span class="required-star">*</span></label><select class="input-premium select-premium" id="prov_tipo_cuenta" required><option value="">Seleccionar...</option><option value="Cuenta Corriente">Cuenta Corriente</option><option value="Cuenta Vista">Cuenta Vista</option><option value="Cuenta RUT">Cuenta RUT</option></select></div>
                    </div>
                    <div class="form-grid-modern">
                        <div class="form-group-modern"><label class="label-modern">Banco <span class="required-star">*</span></label><input type="text" class="input-premium" id="prov_banco" required></div>
                        <div class="form-group-modern"><label class="label-modern">Correo <span class="required-star">*</span></label><input type="email" class="input-premium" id="prov_correo" required></div>
                    </div>
                    <div class="form-grid-modern">
                        <div class="form-group-modern"><label class="label-modern">Nombre Titular <span class="required-star">*</span></label><input type="text" class="input-premium" id="prov_nombre_titular" required></div>
                        <div class="form-group-modern"><label class="label-modern">RUT Titular <span class="required-star">*</span></label><input type="text" class="input-premium" id="prov_rut_titular" required placeholder="12.345.678-9"></div>
                    </div>
                    <div class="form-group-modern" style="margin-top: 1rem;"><label class="label-modern">Certificado Bancario (PDF) <span class="required-star">*</span></label><input type="file" class="input-premium" id="prov_certificado" required accept=".pdf"></div>
                </div>
            </div>
            <div style="padding: 1.5rem 2rem; background: #fcfdfe; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 1rem;">
                <div id="footerStep1" style="display: flex; gap: 1rem;">
                    <button type="button" class="ms-btn-reset" id="btnCancelProveedor">Cancelar</button>
                    <button type="button" class="ms-btn-reset ms-btn-new" id="btnNextStep1">Siguiente →</button>
                </div>
                <div id="footerStep2" style="display: none; gap: 1rem;">
                    <button type="button" class="ms-btn-reset" id="btnPrevStep2">← Atrás</button>
                    <button type="submit" class="ms-btn-reset ms-btn-new">Finalizar Registro</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    async function addNewOption(type, selectId, title) {
        const { value: name } = await Swal.fire({
            title: title,
            input: 'text',
            inputLabel: 'Nombre del nuevo elemento',
            inputPlaceholder: 'Escriba aquí...',
            showCancelButton: true,
            confirmButtonText: 'Añadir',
            cancelButtonText: 'Cancelar',
            inputValidator: (value) => {
                if (!value) return 'El nombre es obligatorio';
            }
        });

        if (name) {
            Swal.fire({ title: 'Procesando...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
            
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
                    Swal.fire({ icon: 'success', title: 'Añadido', text: 'El elemento se agregó correctamente.', timer: 1500, showConfirmButton: false });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'No se pudo añadir el elemento.' });
                }
            } catch (error) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Ocurrió un problema con la comunicación al servidor.' });
            }
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const nombreProveedorSelect = document.getElementById('nombreProveedor');
        const rutProveedorInput = document.getElementById('rutProveedor');
        
        nombreProveedorSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            rutProveedorInput.value = selectedOption.getAttribute('data-rut') || '';
        });

        // Money formatting
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

        // File status update
        const fileInput = document.getElementById('adjunto_input');
        const fileStatus = document.getElementById('file_status');
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                fileStatus.innerText = '✅ ' + e.target.files[0].name;
                fileStatus.parentElement.style.borderColor = 'var(--brand-primary)';
            }
        });

        // Modal Logic
        const modal = document.getElementById('proveedorModal');
        const btnOpen = document.getElementById('btnOpenProveedorModal');
        const btnClose = document.getElementById('btnCloseProveedorModal');
        const btnCancel = document.getElementById('btnCancelProveedor');
        const step1 = document.getElementById('proveedorStep1');
        const step2 = document.getElementById('proveedorStep2');
        const footer1 = document.getElementById('footerStep1');
        const footer2 = document.getElementById('footerStep2');

        btnOpen.onclick = (e) => { e.preventDefault(); modal.style.display = 'flex'; };
        btnClose.onclick = btnCancel.onclick = () => { modal.style.display = 'none'; };

        document.getElementById('btnNextStep1').onclick = () => {
            const inputs = step1.querySelectorAll('input[required]');
            let valid = true;
            inputs.forEach(i => { if(!i.checkValidity()){ valid = false; i.reportValidity(); } });
            if(valid) {
                step1.style.display = 'none';
                step2.style.display = 'block';
                footer1.style.display = 'none';
                footer2.style.display = 'flex';
            }
        };

        document.getElementById('btnPrevStep2').onclick = () => {
            step2.style.display = 'none';
            step1.style.display = 'block';
            footer2.style.display = 'none';
            footer1.style.display = 'flex';
        };

        // AJAX Create Provider
        document.getElementById('formNuevoProveedor').onsubmit = function(e) {
            e.preventDefault();
            Swal.fire({ title: 'Guardando...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

            const formData = new FormData();
            formData.append('rut', document.getElementById('prov_rut').value);
            formData.append('nombre', document.getElementById('prov_nombre').value);
            formData.append('razon_social', document.getElementById('prov_razon_social').value);
            formData.append('direccion', document.getElementById('prov_direccion').value);
            formData.append('comuna', document.getElementById('prov_comuna').value);
            formData.append('region', document.getElementById('prov_region').value);
            formData.append('telefono', document.getElementById('prov_telefono').value);
            formData.append('numero_cuenta', document.getElementById('prov_numero_cuenta').value);
            formData.append('tipo_cuenta', document.getElementById('prov_tipo_cuenta').value);
            formData.append('banco', document.getElementById('prov_banco').value);
            formData.append('correo', document.getElementById('prov_correo').value);
            formData.append('nombre_titular', document.getElementById('prov_nombre_titular').value);
            formData.append('rut_titular', document.getElementById('prov_rut_titular').value);
            const cert = document.getElementById('prov_certificado').files[0];
            if(cert) formData.append('certificado_bancario', cert);

            fetch('/proveedores/ajax-create', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const opt = new Option(data.proveedor.nombre, data.proveedor.nombre, true, true);
                    opt.setAttribute('data-rut', data.proveedor.rut);
                    nombreProveedorSelect.add(opt);
                    rutProveedorInput.value = data.proveedor.rut;
                    Swal.fire({ icon: 'success', title: 'Proveedor Guardado', text: 'El proveedor ha sido registrado exitosamente.', timer: 2000, showConfirmButton: false });
                    modal.style.display = 'none';
                    this.reset();
                    step2.style.display = 'none';
                    step1.style.display = 'block';
                    footer2.style.display = 'none';
                    footer1.style.display = 'flex';
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Verifique los datos.' });
                }
            })
            .catch(err => {
                Swal.fire({ icon: 'error', title: 'Error de Red', text: 'No se pudo contactar con el servidor.' });
            });
        };
    });
</script>
@endpush
