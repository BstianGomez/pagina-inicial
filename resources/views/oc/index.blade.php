@extends('oc.layouts.dashboard')

@section('title', 'Solicitudes de OC')

@section('header')
<div class="ms-banner">
    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
        <div>
            <h1 class="ms-banner-title">Órdenes de Compra</h1>
            <p class="ms-banner-sub">Gestión centralizada de solicitudes y adquisiciones</p>
        </div>
        <div style="display: flex; gap: 1rem;">
            <button id="sendAllGmailBtn" class="ms-btn-reset ms-btn-new">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                Enviar todo a Gmail
            </button>
        </div>
    </div>
</div>
@endsection

@section('content')

<!-- ── KPI SUMMARY ────────────────────────────────────── -->
<div class="ms-kpi-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
    <!-- Solicitadas -->
    <div class="ms-kpi {{ ($filters['estado'] ?? '') === 'Solicitada' ? 'active' : '' }}" 
         onclick="filterByStatus('Solicitada')"
         style="cursor: pointer; border: 2px solid {{ ($filters['estado'] ?? '') === 'Solicitada' ? '#2563eb' : 'transparent' }};">
        <div class="ms-kpi-icon" style="background: rgba(37, 99, 235, 0.1); color: #2563eb;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
        </div>
        <div class="ms-kpi-body">
            <span class="ms-kpi-label">Solicitadas</span>
            <span class="ms-kpi-value">{{ $statusCounts['Solicitada'] ?? 0 }}</span>
        </div>
    </div>

    <!-- Enviadas -->
    <div class="ms-kpi {{ ($filters['estado'] ?? '') === 'Enviada' ? 'active' : '' }}" 
         onclick="filterByStatus('Enviada')"
         style="cursor: pointer; border: 2px solid {{ ($filters['estado'] ?? '') === 'Enviada' ? '#0f6bb6' : 'transparent' }};">
        <div class="ms-kpi-icon" style="background: rgba(15, 107, 182, 0.1); color: #0f6bb6;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2L11 13"></path><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
        </div>
        <div class="ms-kpi-body">
            <span class="ms-kpi-label">Enviadas</span>
            <span class="ms-kpi-value">{{ $statusCounts['Enviada'] ?? 0 }}</span>
        </div>
    </div>

    <!-- Aceptadas -->
    <div class="ms-kpi {{ ($filters['estado'] ?? '') === 'Aceptada' ? 'active' : '' }}" 
         onclick="filterByStatus('Aceptada')"
         style="cursor: pointer; border: 2px solid {{ ($filters['estado'] ?? '') === 'Aceptada' ? '#10b981' : 'transparent' }};">
        <div class="ms-kpi-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
        </div>
        <div class="ms-kpi-body">
            <span class="ms-kpi-label">Aceptadas</span>
            <span class="ms-kpi-value">{{ $statusCounts['Aceptada'] ?? 0 }}</span>
        </div>
    </div>

    <!-- Rechazadas -->
    <div class="ms-kpi {{ ($filters['estado'] ?? '') === 'Rechazada' ? 'active' : '' }}" 
         onclick="filterByStatus('Rechazada')"
         style="cursor: pointer; border: 2px solid {{ ($filters['estado'] ?? '') === 'Rechazada' ? '#dc2626' : 'transparent' }};">
        <div class="ms-kpi-icon" style="background: rgba(220, 38, 38, 0.1); color: #dc2626;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
        </div>
        <div class="ms-kpi-body">
            <span class="ms-kpi-label">Rechazadas</span>
            <span class="ms-kpi-value">{{ $statusCounts['Rechazada'] ?? 0 }}</span>
        </div>
    </div>

    <!-- Facturadas -->
    <div class="ms-kpi {{ ($filters['estado'] ?? '') === 'Facturado' ? 'active' : '' }}" 
         onclick="filterByStatus('Facturado')"
         style="cursor: pointer; border: 2px solid {{ ($filters['estado'] ?? '') === 'Facturado' ? '#0891b2' : 'transparent' }};">
        <div class="ms-kpi-icon" style="background: rgba(8, 145, 178, 0.1); color: #0891b2;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
        </div>
        <div class="ms-kpi-body">
            <span class="ms-kpi-label">Facturadas</span>
            <span class="ms-kpi-value">{{ $statusCounts['Facturado'] ?? 0 }}</span>
        </div>
    </div>
</div>

<!-- ── FILTROS Y BÚSQUEDA ────────────────────────────── -->
<div class="ms-table-card" style="margin-top: 1.5rem; padding: 1.25rem 1.5rem;">
    <form id="filterForm" method="GET" action="{{ route('oc.index') }}" style="display: flex; gap: 1.5rem; align-items: flex-end; flex-wrap: wrap;">
        <input type="hidden" name="estado" id="estadoFilter" value="{{ $filters['estado'] ?? '' }}">
        
        <div style="flex: 1; min-width: 250px;">
            <label class="ms-kpi-label" style="display: block; margin-bottom: 0.5rem;">Rango de Fecha</label>
            <div style="display: flex; gap: 0.75rem;">
                <input type="date" name="from" class="ms-search-input" value="{{ $filters['from'] ?? '' }}" style="padding-left: 1rem;">
                <input type="date" name="to" class="ms-search-input" value="{{ $filters['to'] ?? '' }}" style="padding-left: 1rem;">
            </div>
        </div>

        <div style="width: 220px;">
            <label class="ms-kpi-label" style="display: block; margin-bottom: 0.5rem;">Filtrar por CECO</label>
            <select name="ceco" class="ms-search-input" onchange="this.form.submit()" style="padding-left: 1rem; appearance: auto;">
                <option value="">Todos los CECO</option>
                @foreach($cecos as $c)
                    <option value="{{ $c }}" {{ ($filters['ceco'] ?? '') == $c ? 'selected' : '' }}>{{ $c }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="ms-btn-new" style="padding: 0.75rem 1.5rem; height: 42px;">Aplicar Filtros</button>
        <a href="{{ route('oc.index') }}" class="ms-btn-reset" style="padding: 0.75rem 1rem; border: 1.5px solid #e2e8f0; border-radius: 0.75rem; color: #64748b; font-weight: 700; font-size: 0.85rem;">Limpiar</a>
    </form>
</div>

<!-- ── LISTADO ────────────────────────────────────────── -->
<div class="ms-table-card">
    <div class="ms-table-header">
        <h3 class="ms-table-title">Registros de Solicitudes</h3>
        <a href="{{ route('oc.export', request()->all()) }}" class="ms-btn-reset ms-btn-excel">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
            Exportar Excel
        </a>
    </div>
    <div class="ms-table-wrapper">
        <table class="ms-table">
            <thead>
                <tr>
                    <th>CECO</th>
                    <th>Documento</th>
                    <th>Estado</th>
                    <th>Facturación</th>
                    <th>Proveedor</th>
                    <th>Descripción</th>
                    <th>Monto</th>
                    <th>Fecha</th>
                    <th style="text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rows as $row)
                    @php
                        $estadoStyle = match (strtolower((string) $row->estado)) {
                            'facturado' => 'background: #e0f2fe; color: #0369a1;',
                            'aprobada', 'aceptada' => 'background: #dcfce7; color: #15803d;',
                            'enviada' => 'background: #f3f4f6; color: #4b5563;',
                            'rechazada' => 'background: #fee2e2; color: #b91c1c;',
                            default => 'background: #fef9c3; color: #854d0e;',
                        };
                        $factStyle = ($row->estado_facturacion ?? 'No Facturado') === 'Facturado' 
                            ? 'background: #dcfce7; color: #15803d;' 
                            : 'background: #fef9c3; color: #854d0e;';
                    @endphp
                    <tr>
                        <td style="font-weight: 800; color: var(--brand-primary);">{{ $row->ceco }}</td>
                        <td>
                            <div style="font-weight: 700;">{{ $row->tipo_documento }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $row->tipo_solicitud }}</div>
                        </td>
                        <td><span class="chip" style="{{ $estadoStyle }}">{{ $row->estado }}</span></td>
                        <td><span class="chip" style="{{ $factStyle }}">{{ $row->estado_facturacion ?? 'Pendiente' }}</span></td>
                        <td>
                            <div style="font-weight: 700; color: var(--text-main);">{{ Str::limit($row->proveedor, 25) }}</div>
                        </td>
                        <td title="{{ $row->descripcion }}">
                            <div style="font-size: 0.8rem; color: var(--text-muted); max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $row->descripcion }}
                            </div>
                        </td>
                        <td style="font-weight: 800; color: var(--text-main); font-family: 'Outfit', sans-serif;">
                            ${{ number_format($row->monto, 0, ',', '.') }}
                        </td>
                        <td>{{ \Illuminate\Support\Carbon::parse($row->created_at)->format('d/m/Y') }}</td>
                        <td>
                            <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                <div class="ms-dropdown-container">
                                    <button class="ms-btn-reset ms-dropdown-trigger" style="padding: 0.5rem; border-radius: 0.75rem; background: #f8fafc; color: #64748b; border: 1.5px solid #e2e8f0;" onclick="toggleDropdown(this, event)">
                                        Acciones
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" style="margin-left: 0.25rem;"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                    </button>
                                    <div class="ms-dropdown-menu">
                                        <a href="{{ route('oc.gestor') }}?search={{ $row->id }}" class="ms-dropdown-item">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                            Ver en Gestión
                                        </a>

                                        @if($row->estado === 'Solicitada')
                                            <button onclick="directAceptar({{ $row->id }})" class="ms-dropdown-item" style="color: #15803d;">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                Aceptar y Aprobar
                                            </button>
                                            <button onclick="directRechazar({{ $row->id }})" class="ms-dropdown-item" style="color: #dc2626;">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                                Rechazar
                                            </button>
                                        @endif

                                        @if($row->estado === 'Aceptada')
                                            <a href="{{ route('oc.gestor') }}?search={{ $row->id }}" class="ms-dropdown-item" style="color: #0f6bb6;">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 2L11 13"></path><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                                                Subir / Enviar OC
                                            </a>
                                        @endif

                                        @if($row->estado === 'Enviada')
                                            <button onclick="confirmFacturacion({{ $row->id }})" class="ms-dropdown-item" style="color: #0891b2;">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                                                Marcar Facturado
                                            </button>
                                        @endif

                                        @if(in_array($row->estado, ['Edicion', 'Ajuste']))
                                            <a href="{{ route('oc.solicitudes.editar', $row->id) }}" class="ms-dropdown-item" style="color: #f59e0b;">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                Editar Solicitud
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" style="padding: 4rem; text-align: center; color: var(--text-muted);">No se encontraron solicitudes.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding: 1.5rem; background: #fcfdfe; border-top: 1px solid var(--border-color);">
        {{ $rows->appends(request()->all())->links('pagination::bootstrap-4') }}
    </div>
</div>

<!-- Modal Gmail -->
<div id="gmailModal" class="ms-modal" onclick="closeModalOnFondo(event)">
    <div class="ms-modal-content" onclick="event.stopPropagation()" style="max-width: 450px;">
        <div class="ms-modal-header">
            <h2 class="ms-table-title">Exportar a Gmail</h2>
            <button class="ms-btn-reset" style="padding: 0.5rem;" onclick="closeGmailModal()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <form id="gmailSubmitForm" method="POST" action="{{ route('oc.send_gmail_all') }}" style="padding: 2.5rem;">
            @csrf
            <div style="margin-bottom: 1.5rem;">
                <label class="ms-kpi-label" style="display: block; margin-bottom: 0.75rem;">Correo de destino</label>
                <input type="email" name="gmail" required class="ms-search-input" placeholder="ejemplo@correo.com" style="padding-left: 1rem;">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                <button type="button" class="ms-btn-reset" onclick="closeGmailModal()">Cancelar</button>
                <button type="submit" class="ms-btn-reset ms-btn-new">Enviar Correo</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function filterByStatus(status) {
        const currentEstado = document.getElementById('estadoFilter').value;
        // Si hacemos clic en el que ya está activo, limpiamos el filtro
        if (currentEstado === status) {
            document.getElementById('estadoFilter').value = '';
        } else {
            document.getElementById('estadoFilter').value = status;
        }
        document.getElementById('filterForm').submit();
    }

    async function directAceptar(id) {
        const result = await Swal.fire({
            title: '¿Aceptar y Aprobar?',
            text: 'Se notificará al usuario y se habilitará la subida de la OC.',
            icon: 'success',
            showCancelButton: true,
            confirmButtonColor: '#15803d',
            confirmButtonText: 'Sí, aprobar',
            cancelButtonText: 'Cancelar'
        });
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ url('oc/gestor') }}/${id}/status`;
            form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="accion" value="aceptar">`;
            document.body.appendChild(form);
            form.submit();
        }
    }

    async function directRechazar(id) {
        const { value: text } = await Swal.fire({
            title: '¿Rechazar solicitud?',
            input: 'textarea',
            inputLabel: 'Motivo del rechazo',
            inputPlaceholder: 'Escriba aquí por qué se rechaza...',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Sí, rechazar',
            cancelButtonText: 'Cancelar',
            inputValidator: (value) => {
                if (!value || value.trim() === '') {
                    return 'Debes ingresar un motivo para el rechazo';
                }
            }
        });
        if (text) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ url('oc/gestor') }}/${id}/status`;
            form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="accion" value="rechazar"><input type="hidden" name="comentario" value="${text}">`;
            document.body.appendChild(form);
            form.submit();
        }
    }

    async function confirmFacturacion(id) {
        const result = await Swal.fire({
            title: '¿Marcar como Facturado?',
            text: 'Se actualizará el estado de la solicitud a Facturado.',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#0891b2',
            confirmButtonText: 'Sí, marcar',
            cancelButtonText: 'Cancelar'
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch(`{{ url('oc/gestor') }}/${id}/facturacion`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                if (response.ok) {
                    Swal.fire('¡Actualizado!', 'La solicitud ha sido marcada como facturada.', 'success').then(() => location.reload());
                }
            } catch (e) {
                Swal.fire('Error', 'No se pudo procesar.', 'error');
            }
        }
    }

    function toggleDropdown(btn, e) {
        e.stopPropagation();
        
        const container = btn.parentElement;
        const menu = btn.nextElementSibling;
        
        // Cerrar otros
        document.querySelectorAll('.ms-dropdown-container').forEach(c => {
            if (c !== container) {
                c.classList.remove('active');
                c.querySelector('.ms-dropdown-menu').classList.remove('active');
            }
        });

        const isActive = container.classList.toggle('active');
        menu.classList.toggle('active', isActive);
        
        // Smart positioning (si está muy abajo, abrir hacia arriba)
        const rect = menu.getBoundingClientRect();
        const spaceBelow = window.innerHeight - rect.bottom;
        if (spaceBelow < 50) {
            menu.classList.add('dropup');
        } else {
            menu.classList.remove('dropup');
        }
    }

    // Cerrar al hacer clic fuera
    document.addEventListener('click', () => {
        document.querySelectorAll('.ms-dropdown-container').forEach(c => c.classList.remove('active'));
        document.querySelectorAll('.ms-dropdown-menu').forEach(m => m.classList.remove('active'));
    });

    const gmailModal = document.getElementById('gmailModal');
    const sendAllBtn = document.getElementById('sendAllGmailBtn');

    if (sendAllBtn) {
        sendAllBtn.onclick = function() {
            gmailModal.classList.add('active');
        }
    }

    function closeGmailModal() {
        gmailModal.classList.remove('active');
    }

    function closeModalOnFondo(e) {
        if (e.target.classList.contains('ms-modal')) {
            closeGmailModal();
        }
    }
</script>
@endpush

@section('extra_css')
<style>
    .ms-table-wrapper { overflow: visible !important; }
    .ms-table-card { overflow: visible !important; }
    .ms-table td { position: relative; }
    .ms-table td:has(.ms-dropdown-menu.active) { z-index: 1000; }
</style>
@endsection
