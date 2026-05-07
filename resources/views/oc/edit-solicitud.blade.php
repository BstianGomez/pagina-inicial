<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Solicitud OC</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|dm-sans:400,500,600" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <link rel="stylesheet" href="/assets/oc/css/common.css">
    
    <style>
        .edit-container {
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .section-header {
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .alert-info {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
            padding: 16px 20px;
            border-radius: 16px;
            margin-bottom: 24px;
            display: flex;
            gap: 16px;
            align-items: flex-start;
            box-shadow: 0 4px 6px -1px rgba(30, 64, 175, 0.05);
        }

        .alert-info svg {
            flex-shrink: 0;
            margin-top: 2px;
        }

        .form-section-title {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--muted);
            margin: 32px 0 16px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .form-section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--line);
        }

        .input-group {
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 38px;
            color: var(--muted);
            pointer-events: none;
        }

        .input-with-icon {
            padding-left: 40px !important;
        }

        .required-star {
            color: #ef4444;
            margin-left: 2px;
        }

        .card-inner {
            padding: 40px;
        }

        .header-custom {
            background: linear-gradient(135deg, #0b5fa5 0%, #0f6bb6 100%);
            padding: 24px 40px 40px;
            border-radius: 20px;
            margin: 20px 24px -20px;
            position: relative;
            z-index: 1;
            box-shadow: 0 10px 15px -3px rgba(15, 107, 182, 0.2);
        }

        .header-content {
            max-width: 1000px;
            margin: 0 auto;
            color: white;
        }

        .header-content h1 {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
        }

        .header-content p {
            margin-top: 4px;
            opacity: 0.8;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="page">
        @include('oc.partials.sidebar', ['active' => 'dashboard'])
        
        <div class="main-content">
            <header class="topbar">
                <div class="topbar-inner">
                    <div class="topbar-badge">
                        <img src="/assets/oc/img/logo-sofofa.png" alt="Logo">
                        <span>Portal OC</span>
                    </div>
                    <div class="topbar-center">
                        <div style="background: rgba(255,255,255,0.1); padding: 8px 24px; border-radius: 50px; font-weight: 600; font-size: 13px;">
                            MODO EDICIÓN
                        </div>
                    </div>
                    <div style="display: flex; gap: 12px; align-items: center;">
                        <a href="{{ route('oc.dashboard') }}" class="btn-topbar">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                            <span>Volver al listado</span>
                        </a>
                        <div class="user-info-badge">
                            <div class="user-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                            <div style="display: flex; flex-direction: column;">
                                <span style="font-size: 11px; font-weight: 700; color: white;">{{ auth()->user()->name }}</span>
                                <span style="font-size: 9px; color: rgba(255,255,255,0.7); text-transform: uppercase;">{{ auth()->user()->role ?? auth()->user()->rol }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="content" style="position: relative; z-index: 2; padding-top: 20px;">
                <div class="edit-container">
                    <form class="card" method="POST" action="{{ route('oc.solicitudes.actualizar', $solicitud->id) }}">
                        <div class="card-header">
                            <div>
                                <h1 class="card-title">Editar Solicitud</h1>
                                <p class="card-subtitle">Modifica los detalles de tu solicitud para reenviarla a revisión.</p>
                            </div>
                        </div>

                        <div class="card-body">
                            @csrf
                            <div class="section-header">
                                <div>
                                    <h2 style="font-size: 20px; font-weight: 700; color: var(--ink); margin: 0;">Solicitud #{{ $solicitud->id }}</h2>
                                    <div style="display: flex; align-items: center; gap: 8px; margin-top: 4px;">
                                        <span class="status pending"><span class="dot"></span>{{ $solicitud->estado }}</span>
                                        <span style="font-size: 12px; color: var(--muted);">Creada el {{ \Illuminate\Support\Carbon::parse($solicitud->created_at)->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </div>

                            @if($solicitud->observacion_rechazo)
                                <div class="alert-info">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #2563eb;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                                    <div>
                                        <div style="font-weight: 700; font-size: 15px; margin-bottom: 4px;">Motivo de ajuste/edición:</div>
                                        <div style="font-size: 14px; line-height: 1.5;">{{ $solicitud->observacion_rechazo }}</div>
                                    </div>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-error">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                    <div>
                                        <div style="font-weight: 700;">Han ocurrido errores de validación:</div>
                                        <ul class="alert-list">
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

                            <div class="form-section-title">Información del Proyecto</div>
                            
                            <div class="form-grid three-col">
                                <div class="form-field">
                                    <label class="form-label">CECO <span class="required-star">*</span></label>
                                    <select class="select" name="ceco" required>
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

                                <div class="form-field">
                                    <label class="form-label">
                                        Coordinador <span class="required-star">*</span>
                                        <button type="button" class="btn-add-option" title="Agregar coordinador" onclick="addNewOption('coordinador', 'coordinador', 'Nuevo Coordinador')">+</button>
                                    </label>
                                    <select class="select" id="coordinador" name="coordinador" required>
                                        @foreach($coordinadores as $item)
                                            <option value="{{ $item->nombre }}" {{ ($extras['coordinador'] ?? '') == $item->nombre ? 'selected' : '' }}>
                                                {{ $item->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-field">
                                    <label class="form-label">
                                        Tipo de servicio <span class="required-star">*</span>
                                        <button type="button" class="btn-add-option" title="Agregar tipo de servicio" onclick="addNewOption('tipo_servicio', 'tipo_servicio', 'Nuevo Tipo de Servicio')">+</button>
                                    </label>
                                    <select class="select" id="tipo_servicio" name="tipo_servicio" required>
                                        @foreach($tipoServicios as $item)
                                            <option value="{{ $item->nombre }}" {{ ($extras['tipo_servicio'] ?? '') == $item->nombre ? 'selected' : '' }}>
                                                {{ $item->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-grid two-col">
                                <div class="form-field">
                                    <label class="form-label">
                                        Tipo de proyecto <span class="required-star">*</span>
                                        <button type="button" class="btn-add-option" title="Agregar tipo de proyecto" onclick="addNewOption('tipo_proyecto', 'tipo_proyecto', 'Nuevo Tipo de Proyecto')">+</button>
                                    </label>
                                    <select class="select" id="tipo_proyecto" name="tipo_proyecto" required>
                                        @foreach($tipoProyectos as $item)
                                            <option value="{{ $item->nombre }}" {{ ($extras['tipo_proyecto'] ?? '') == $item->nombre ? 'selected' : '' }}>
                                                {{ $item->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-field">
                                    <label class="form-label">Tipo de documento <span class="required-star">*</span></label>
                                    <select class="select" name="tipo_documento" required>
                                        <option value="Factura" {{ $solicitud->tipo_documento == 'Factura' ? 'selected' : '' }}>Factura</option>
                                        <option value="Boleta" {{ $solicitud->tipo_documento == 'Boleta' ? 'selected' : '' }}>Boleta</option>
                                        <option value="Otro" {{ $solicitud->tipo_documento == 'Otro' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-section-title">Detalles del Proveedor</div>

                            <div class="form-grid two-col">
                                <div class="form-field">
                                    <label class="form-label">RUT Proveedor <span class="required-star">*</span></label>
                                    <input type="text" class="input" name="rut_proveedor" value="{{ old('rut_proveedor', $solicitud->rut) }}" required placeholder="12.345.678-9" />
                                </div>
                                <div class="form-field">
                                    <label class="form-label">Nombre Proveedor <span class="required-star">*</span></label>
                                    <input type="text" class="input" name="nombre_proveedor" value="{{ old('nombre_proveedor', $solicitud->proveedor) }}" required placeholder="Nombre de la empresa" />
                                </div>
                            </div>

                            <div class="form-section-title">Ítems y Montos</div>

                            <div class="form-field" style="margin-bottom: 20px;">
                                <label class="form-label">Descripción de la compra <span class="required-star">*</span></label>
                                <textarea class="textarea" name="descripcion" placeholder="Explica brevemente qué se está comprando..." required>{{ old('descripcion', $solicitud->descripcion) }}</textarea>
                            </div>

                            <div class="form-grid two-col">
                                <div class="form-field">
                                    <label class="form-label">Cantidad <span class="required-star">*</span></label>
                                    <input type="number" class="input" name="cantidad" value="{{ old('cantidad', $solicitud->cantidad) }}" required min="1" />
                                </div>
                                <div class="form-field">
                                    <label class="form-label">Monto Total <span class="required-star">*</span></label>
                                    <div style="position: relative;">
                                        <span style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-weight: 700; color: var(--muted);">$</span>
                                        <input type="text" class="input" name="monto" value="{{ old('monto', number_format($solicitud->monto, 0, ',', '.')) }}" required style="padding-left: 30px !important;" />
                                    </div>
                                </div>
                            </div>

                            <div class="actions">
                                <a href="{{ route('oc.dashboard') }}" class="btn-ghost">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                    Cancelar
                                </a>
                                <button type="submit" class="btn-primary">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                                    Guardar Cambios y Reenviar
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </main>
        </div>
    </div>

    <script>
        async function addNewOption(selectId, dbType, title) {
            const { value: newValue } = await Swal.fire({
                title: title,
                input: 'text',
                inputLabel: 'Ingrese el nombre:',
                inputPlaceholder: 'Escriba aquí...',
                showCancelButton: true,
                confirmButtonText: 'Agregar y Guardar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    confirmButton: 'btn-primary',
                    cancelButton: 'btn-ghost'
                },
                inputValidator: (value) => {
                    if (!value) {
                        return '¡Debes escribir algo!'
                    }
                }
            });

            if (newValue) {
                try {
                    const response = await fetch('{{ route("oc.config.ajax.add") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            type: dbType,
                            nombre: newValue
                        })
                    });
                    const data = await response.json();
                    if (data.success && data.item) {
                        const select = document.getElementById(selectId);
                        const option = new Option(data.item.nombre, data.item.nombre, true, true);
                        select.add(option);
                        Swal.fire({ 
                            icon: 'success', 
                            title: '¡Guardado!', 
                            text: 'El nuevo registro se ha creado correctamente.', 
                            toast: true, 
                            position: 'top-end', 
                            timer: 3000, 
                            timerProgressBar: true, 
                            showConfirmButton: false 
                        });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Error al guardar.' });
                    }
                } catch (error) {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Error de conexión con el servidor.' });
                }
            }
        }

        // Formatear monto en tiempo real
        const montoInput = document.querySelector('input[name="monto"]');
        if (montoInput) {
            montoInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, "");
                if (value) {
                    e.target.value = new Intl.NumberFormat('es-CL').format(value);
                }
            });
        }
    </script>
</body>
</html>
