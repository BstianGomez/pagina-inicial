@extends('oc.layouts.dashboard')

@section('title', 'Gestor de Aprobaciones')

@section('header')
<div class="ms-banner">
    <div>
        <h1 class="ms-banner-title">Gestión de Aprobaciones</h1>
        <p class="ms-banner-sub">Control y seguimiento de solicitudes de órdenes de compra</p>
    </div>
    <div style="display: flex; gap: 1rem;">
        <a href="{{ route('oc.export') }}" class="ms-btn-reset ms-btn-excel">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
            Exportar Todo
        </a>
    </div>
</div>
@endsection

@section('content')

@if(session('success'))
    <div class="ms-alert ms-alert-success">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="ms-alert ms-alert-error">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="ms-alert ms-alert-error">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<style>
    /* Asegurar que el menú de acciones no se corte por el contenedor de la tabla */
    .ms-table-wrapper {
        overflow: visible !important;
    }
    .ms-table-card {
        overflow: visible !important;
    }
    .ms-table td {
        position: relative;
    }
    .ms-table td:has(.ms-dropdown.active) {
        z-index: 1000;
    }

    /* Responsividad del Grid de Detalles */
    @media (max-width: 1024px) {
        .modal-body-grid {
            grid-template-columns: 1fr !important;
        }
        .ms-kpi-grid {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)) !important;
        }
    }

    /* Alert Styles */
    .ms-alert {
        padding: 1rem 1.5rem;
        border-radius: 1rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 600;
        font-size: 0.9rem;
        animation: slideInDown 0.3s ease;
    }
    .ms-alert-success {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }
    .ms-alert-error {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }
    @keyframes slideInDown {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    /* Sticky Modal Elements */
    .sticky-modal-header {
        position: sticky;
        top: 0;
        z-index: 20;
        background: white;
        border-bottom: 1px solid #e2e8f0;
    }
    .sticky-modal-footer {
        position: sticky;
        bottom: 0;
        z-index: 20;
        background: white;
        border-top: 1px solid #e2e8f0;
    }
</style>

<!-- ── KPI SUMMARY ────────────────────────────────────── -->
<div class="ms-kpi-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
    <!-- Solicitadas -->
    <a href="{{ route('oc.gestor', ['estado' => 'Solicitada']) }}" class="ms-kpi {{ request('estado') === 'Solicitada' ? 'active' : '' }}" style="text-decoration: none; border: 2px solid {{ request('estado') === 'Solicitada' ? '#2563eb' : 'transparent' }};">
        <div class="ms-kpi-icon" style="background: rgba(37, 99, 235, 0.1); color: #2563eb;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
        </div>
        <div class="ms-kpi-body">
            <span class="ms-kpi-label">Solicitadas</span>
            <span class="ms-kpi-value">{{ $rows->where('estado', 'Solicitada')->count() + ($rows instanceof \Illuminate\Pagination\LengthAwarePaginator ? \DB::table('oc_solicitudes')->where('estado', 'Solicitada')->count() - $rows->where('estado', 'Solicitada')->count() : 0) }}</span>
        </div>
    </a>

    <!-- Enviadas -->
    <a href="{{ route('oc.gestor', ['estado' => 'Enviada']) }}" class="ms-kpi {{ request('estado') === 'Enviada' ? 'active' : '' }}" style="text-decoration: none; border: 2px solid {{ request('estado') === 'Enviada' ? '#0f6bb6' : 'transparent' }};">
        <div class="ms-kpi-icon" style="background: rgba(15, 107, 182, 0.1); color: #0f6bb6;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2L11 13"></path><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
        </div>
        <div class="ms-kpi-body">
            <span class="ms-kpi-label">Enviadas</span>
            <span class="ms-kpi-value">{{ \DB::table('oc_solicitudes')->where('estado', 'Enviada')->count() }}</span>
        </div>
    </a>

    <!-- Aceptadas -->
    <a href="{{ route('oc.gestor', ['estado' => 'Aceptada']) }}" class="ms-kpi {{ request('estado') === 'Aceptada' ? 'active' : '' }}" style="text-decoration: none; border: 2px solid {{ request('estado') === 'Aceptada' ? '#10b981' : 'transparent' }};">
        <div class="ms-kpi-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
        </div>
        <div class="ms-kpi-body">
            <span class="ms-kpi-label">Aceptadas</span>
            <span class="ms-kpi-value">{{ \DB::table('oc_solicitudes')->where('estado', 'Aceptada')->count() }}</span>
        </div>
    </a>

    <!-- Rechazadas -->
    <a href="{{ route('oc.gestor', ['estado' => 'Rechazada']) }}" class="ms-kpi {{ request('estado') === 'Rechazada' ? 'active' : '' }}" style="text-decoration: none; border: 2px solid {{ request('estado') === 'Rechazada' ? '#dc2626' : 'transparent' }};">
        <div class="ms-kpi-icon" style="background: rgba(220, 38, 38, 0.1); color: #dc2626;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
        </div>
        <div class="ms-kpi-body">
            <span class="ms-kpi-label">Rechazadas</span>
            <span class="ms-kpi-value">{{ \DB::table('oc_solicitudes')->where('estado', 'Rechazada')->count() }}</span>
        </div>
    </a>

    <!-- Facturadas -->
    <a href="{{ route('oc.gestor', ['estado' => 'Facturado']) }}" class="ms-kpi {{ request('estado') === 'Facturado' ? 'active' : '' }}" style="text-decoration: none; border: 2px solid {{ request('estado') === 'Facturado' ? '#0891b2' : 'transparent' }};">
        <div class="ms-kpi-icon" style="background: rgba(8, 145, 178, 0.1); color: #0891b2;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
        </div>
        <div class="ms-kpi-body">
            <span class="ms-kpi-label">Facturadas</span>
            <span class="ms-kpi-value">{{ \DB::table('oc_solicitudes')->where('estado', 'Facturado')->count() }}</span>
        </div>
    </a>
</div>

<!-- ── FILTROS Y BÚSQUEDA ────────────────────────────── -->
<div class="ms-table-card" style="margin-top: 2rem; padding: 1.25rem 1.5rem;">
    <form action="{{ route('oc.gestor') }}" method="GET" style="display: flex; gap: 1.5rem; align-items: flex-end; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 250px;">
            <label class="ms-kpi-label" style="display: block; margin-bottom: 0.5rem;">Buscar Solicitud</label>
            <div style="position: relative;">
                <input type="text" name="search" value="{{ request('search') }}" class="ms-search-input" placeholder="ID, CECO, Proveedor o Descripción..." style="padding-left: 2.75rem;">
                <svg style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8;" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </div>
        </div>

        <div style="width: 200px;">
            <label class="ms-kpi-label" style="display: block; margin-bottom: 0.5rem;">Filtrar por Estado</label>
            <select name="estado" class="ms-search-input" onchange="this.form.submit()" style="padding-left: 1rem; appearance: auto;">
                <option value="">Todas (Pendientes y Aceptadas)</option>
                <option value="Solicitada" {{ request('estado') === 'Solicitada' ? 'selected' : '' }}>Pendientes</option>
                <option value="Aceptada" {{ request('estado') === 'Aceptada' ? 'selected' : '' }}>Aceptadas</option>
                <option value="Rechazada" {{ request('estado') === 'Rechazada' ? 'selected' : '' }}>Rechazadas</option>
                <option value="Facturado" {{ request('estado') === 'Facturado' ? 'selected' : '' }}>Facturadas</option>
                <option value="Enviada" {{ request('estado') === 'Enviada' ? 'selected' : '' }}>Enviadas (OC Generada)</option>
            </select>
        </div>

        <div style="width: 180px;">
            <label class="ms-kpi-label" style="display: block; margin-bottom: 0.5rem;">Orden cronológico</label>
            <select name="sort" class="ms-search-input" onchange="this.form.submit()" style="padding-left: 1rem; appearance: auto;">
                <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Más actual primero</option>
                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Más antiguo primero</option>
            </select>
        </div>

        <button type="submit" class="ms-btn-new" style="padding: 0.75rem 1.5rem; height: 42px;">Aplicar</button>
        <a href="{{ route('oc.gestor') }}" class="ms-btn-reset" style="padding: 0.75rem 1rem; border: 1.5px solid #e2e8f0; border-radius: 0.75rem; color: #64748b; font-weight: 700; font-size: 0.85rem;">Limpiar</a>
    </form>
</div>

<!-- ── LISTADO PRINCIPAL ──────────────────────────────── -->
<div class="ms-table-card" style="margin-top: 1.5rem;">
    <div class="ms-table-header">
        <h3 class="ms-table-title">Solicitudes ({{ $rows->total() }})</h3>
    </div>
    <div class="ms-table-wrapper">
        <table class="ms-table">
            <thead>
                <tr>
                    <th>CECO</th>
                    <th>Proveedor</th>
                    <th>Descripción</th>
                    <th>Monto</th>
                    <th>Estado</th>
                    <th style="text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $solicitud)
                <tr>
                    <td style="font-weight: 800; color: var(--brand-primary);">{{ $solicitud->ceco }}</td>
                    <td><div style="font-weight: 700;">{{ Str::limit($solicitud->proveedor, 30) }}</div></td>
                    <td>
                        <div style="font-size: 0.8rem; color: var(--text-muted); max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ $solicitud->descripcion }}
                        </div>
                    </td>
                    <td style="font-weight: 800; font-family: 'Outfit';">
                        ${{ number_format($solicitud->monto, 0, ',', '.') }}
                    </td>
                    <td>
                        @php
                            $chipStyle = match(strtolower($solicitud->estado)) {
                                'solicitada' => 'background: #fef9c3; color: #854d0e;',
                                'aceptada' => 'background: #dcfce7; color: #15803d;',
                                'rechazada' => 'background: #fee2e2; color: #b91c1c;',
                                default => 'background: #f3f4f6; color: #4b5563;'
                            };
                        @endphp
                        <span class="chip" style="{{ $chipStyle }}">{{ $solicitud->estado }}</span>
                    </td>
                    <td style="text-align: center;">
                        <div class="ms-dropdown">
                            <button class="ms-dropdown-toggle" onclick="toggleDropdown(this)">
                                Acciones
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                            </button>
                            <div class="ms-dropdown-menu">
                                <button onclick="openGestionModal({{ json_encode($solicitud) }})" class="ms-dropdown-item">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="3"></circle><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path></svg>
                                    Ver Detalles
                                </button>

                                @if($solicitud->estado === 'Solicitada')
                                    <button onclick="directAceptar({{ $solicitud->id }})" class="ms-dropdown-item" style="color: #15803d;">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        Aceptar y Aprobar
                                    </button>
                                    <button onclick="directRechazar({{ $solicitud->id }})" class="ms-dropdown-item" style="color: #dc2626;">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                        Rechazar Solicitud
                                    </button>
                                @endif

                                @if($solicitud->estado === 'Aceptada')
                                    <button onclick="openEnviarOcModalDirect({{ json_encode($solicitud) }})" class="ms-dropdown-item" style="color: #0f6bb6;">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 2L11 13"></path><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                                        Subir / Enviar OC
                                    </button>
                                    <button onclick="directRechazar({{ $solicitud->id }})" class="ms-dropdown-item" style="color: #dc2626;">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                        Rechazar Solicitud
                                    </button>
                                @endif

                                @if($solicitud->estado === 'Enviada')
                                    <button onclick="confirmFacturacion({{ $solicitud->id }})" class="ms-dropdown-item" style="color: #0891b2;">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                                        Marcar Facturado
                                    </button>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="padding: 4rem; text-align: center; color: var(--text-muted);">No hay solicitudes pendientes de gestión.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding: 1.5rem; background: #fcfdfe; border-top: 1px solid var(--border-color);">
        {{ $rows->appends(request()->all())->links('pagination::bootstrap-4') }}
    </div>
</div>

<!-- Modal de Gestión (Expediente de Gasto) -->
<div id="gestionModal" class="ms-modal" onclick="closeModalOnFondo(event)">
    <div class="ms-modal-content" onclick="event.stopPropagation()" style="max-width: 1300px; background: #f4f7fa; border-radius: 2rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
        <!-- STICKY HEADER -->
        <div class="sticky-modal-header" style="padding: 1rem 2rem; border-radius: 2rem 2rem 0 0;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 class="ms-table-title" style="font-size: 1.6rem;">Detalle de Solicitud OC-2026-<span id="modalId"></span></h2>
                </div>
                <button class="ms-btn-reset" style="padding: 0.5rem; background: #f1f5f9; border-radius: 50%;" onclick="closeModal()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
        </div>
        
        <!-- SCROLLABLE BODY (Now part of the main modal scroll) -->
        <div class="modal-body-grid" style="padding: 1.25rem 2rem; display: grid; grid-template-columns: 1.8fr 1fr; gap: 1.25rem; align-items: start;">
            
            <!-- COLUMNA IZQUIERDA -->
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                
                <!-- Card Principal Proveedor -->
                <div class="ms-table-card" style="padding: 1rem 1.5rem; display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div id="modalInitial" style="width: 48px; height: 48px; background: #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: 800; color: #475569;">A</div>
                        <div>
                            <h3 id="modalProveedor" style="font-size: 1.2rem; font-weight: 800; color: #1e293b; line-height: 1.2;"></h3>
                            <div style="display: flex; align-items: center; gap: 0.75rem; margin-top: 0.15rem;">
                                <span id="modalFecha" style="font-size: 0.8rem; color: #64748b; font-weight: 600;"></span>
                                <span id="modalStatusBadge" class="chip" style="font-size: 0.65rem; padding: 0.2rem 0.6rem;"></span>
                            </div>
                        </div>
                    </div>
                    <button class="ms-btn-reset" style="border: 1.5px solid #e2e8f0; color: #64748b; font-size: 0.65rem; font-weight: 800; padding: 0.4rem 0.8rem; border-radius: 0.5rem; text-transform: uppercase;">Anular Informe</button>
                </div>

                <!-- Listado de Comprobantes -->
                <div class="ms-table-card" style="padding: 0;">
                    <div style="padding: 0.75rem 1.25rem; border-bottom: 1px solid #f1f5f9;">
                        <h4 style="text-transform: uppercase; font-size: 0.7rem; font-weight: 800; color: #64748b; letter-spacing: 0.02em;">Listado de Comprobantes</h4>
                    </div>
                    <div style="padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; gap: 0.85rem; align-items: center;">
                            <div style="width: 36px; height: 36px; background: #f1f5f9; border-radius: 8px;"></div>
                            <div>
                                <h5 id="modalProveedorSec" style="font-weight: 800; color: #1e293b; font-size: 1rem;"></h5>
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.05rem;">
                                    <span style="font-size: 0.75rem; font-weight: 600; color: #64748b;">RUT: <span id="modalRut"></span></span>
                                    <span id="modalTipoDocBadge" class="chip" style="font-size: 0.6rem; padding: 0.1rem 0.4rem; background: #eff6ff; color: #1e40af; border: none;"></span>
                                </div>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-size: 0.7rem; color: #64748b; font-weight: 600;" id="modalFechaSec"></div>
                            <div style="font-size: 1.6rem; font-weight: 800; color: #0f172a; font-family: 'Outfit';" id="modalMonto"></div>
                        </div>
                    </div>
                    <div style="padding: 1.25rem;">
                        <div style="display: grid; grid-template-columns: 1.6fr 1fr; gap: 1rem;">
                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 0.6rem 1rem; border-radius: 0.5rem; font-size: 0.85rem; color: #475569;" id="modalDesc"></div>
                                <div style="background: #fffbeb; border: 1px solid #fef3c7; padding: 0.6rem 1rem; border-radius: 0.5rem; font-size: 0.85rem; color: #92400e;" id="modalObs"></div>
                            </div>
                            <div style="display: flex; align-items: stretch;">
                                <div style="flex: 1; background: #f1f5f9; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #94a3b8; font-size: 0.8rem;" id="modalAdjunto">Sin adjunto</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: #eff6ff; padding: 0.75rem; border-radius: 0 0 1rem 1rem; text-align: center;">
                        <p style="text-transform: uppercase; font-size: 0.6rem; font-weight: 800; color: #1e40af; margin-bottom: 0.15rem;">Monto Total Rendición</p>
                        <p style="font-size: 0.7rem; color: #60a5fa; font-weight: 600;">Calculado automáticamente</p>
                    </div>
                </div>

                <div class="ms-table-card" style="padding: 1rem 1.25rem;">
                    <h4 style="text-transform: uppercase; font-size: 0.7rem; font-weight: 800; color: #64748b; letter-spacing: 0.02em;">Datos Adicionales</h4>
                </div>
            </div>

            <!-- COLUMNA DERECHA -->
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                
                <!-- Gestión -->
                <div class="ms-table-card" style="padding: 1.25rem;">
                    <h4 style="text-transform: uppercase; font-size: 0.65rem; font-weight: 800; color: #64748b; letter-spacing: 0.05em; margin-bottom: 1rem;">Gestión</h4>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <button onclick="habilitarEdicion()" class="ms-btn-reset" style="width: 100%; border: 1.5px solid rgba(15, 107, 182, 0.2); color: #0f6bb6; background: rgba(15, 107, 182, 0.04); font-weight: 800; justify-content: center; font-size: 0.85rem; padding: 0.75rem; border-radius: 0.85rem; transition: all 0.2s;" onmouseover="this.style.background='rgba(15, 107, 182, 0.1)'; this.style.transform='translateY(-1px)'" onmouseout="this.style.background='rgba(15, 107, 182, 0.04)'; this.style.transform='none'">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-right: 0.5rem;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            Habilitar edición al solicitante
                        </button>
                        <button onclick="solicitarAjuste()" class="ms-btn-reset" style="width: 100%; border: 1.5px solid rgba(182, 153, 80, 0.2); color: #b69950; background: rgba(182, 153, 80, 0.04); font-weight: 800; justify-content: center; font-size: 0.85rem; padding: 0.75rem; border-radius: 0.85rem; transition: all 0.2s;" onmouseover="this.style.background='rgba(182, 153, 80, 0.1)'; this.style.transform='translateY(-1px)'" onmouseout="this.style.background='rgba(182, 153, 80, 0.04)'; this.style.transform='none'">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-right: 0.5rem;"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                            Solicitar ajuste
                        </button>
                    </div>
                </div>

                <!-- Información de Transferencia -->
                <div class="ms-table-card" style="padding: 1.25rem;">
                    <h4 style="text-transform: uppercase; font-size: 0.65rem; font-weight: 800; color: #64748b; letter-spacing: 0.05em; margin-bottom: 1rem;">Información de Transferencia</h4>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <div>
                            <h5 id="modalProveedorTransfer" style="font-weight: 800; color: #1e293b; font-size: 1.1rem; line-height: 1.2; margin-bottom: 0.4rem;"></h5>
                            <div style="display: grid; grid-template-columns: 70px 1fr; gap: 0.4rem; font-size: 0.8rem;">
                                <span style="font-weight: 800; color: #64748b; text-transform: uppercase;">RUT</span>
                                <span id="modalRutTransfer" style="font-weight: 600; color: #475569;"></span>
                                
                                <span style="font-weight: 800; color: #64748b; text-transform: uppercase;">Banco</span>
                                <span id="modalBanco" style="font-weight: 600; color: #475569;"></span>
                                
                                <span style="font-weight: 800; color: #64748b; text-transform: uppercase;">Cuenta</span>
                                <span id="modalCuenta" style="font-weight: 600; color: #475569;"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Resumen -->
                <div class="ms-table-card" style="padding: 1.25rem; background: white;">
                    <h4 style="text-transform: uppercase; font-size: 0.65rem; font-weight: 800; color: #64748b; letter-spacing: 0.05em; margin-bottom: 1rem;">Resumen</h4>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                        <div style="background: #f8fafc; border: 1px solid #eff6ff; padding: 0.5rem 0.75rem; border-radius: 0.6rem;">
                            <p style="text-transform: uppercase; font-size: 0.55rem; font-weight: 800; color: #94a3b8; margin-bottom: 0.1rem;">ID Interno</p>
                            <p style="font-weight: 800; color: #334155; font-size: 0.85rem;">#<span id="modalIdResumen"></span></p>
                        </div>
                        <div style="background: #f8fafc; border: 1px solid #eff6ff; padding: 0.5rem 0.75rem; border-radius: 0.6rem;">
                            <p style="text-transform: uppercase; font-size: 0.55rem; font-weight: 800; color: #94a3b8; margin-bottom: 0.1rem;">CECO</p>
                            <p style="font-weight: 800; color: #334155; font-size: 0.85rem;" id="modalCecoResumen"></p>
                        </div>
                        <div style="background: #f8fafc; border: 1px solid #eff6ff; padding: 0.5rem 0.75rem; border-radius: 0.6rem;">
                            <p style="text-transform: uppercase; font-size: 0.55rem; font-weight: 800; color: #94a3b8; margin-bottom: 0.1rem;">Tipo Solicitud</p>
                            <p style="font-weight: 800; color: #334155; font-size: 0.85rem;" id="modalTipoSolicitudResumen"></p>
                        </div>
                        <div style="background: #f8fafc; border: 1px solid #eff6ff; padding: 0.5rem 0.75rem; border-radius: 0.6rem;">
                            <p style="text-transform: uppercase; font-size: 0.55rem; font-weight: 800; color: #94a3b8; margin-bottom: 0.1rem;">Email</p>
                            <p style="font-weight: 800; color: #334155; font-size: 0.85rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" id="modalEmailProveedorResumen"></p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- STICKY FOOTER -->
        <div class="sticky-modal-footer" style="padding: 1rem 2rem; display: flex; justify-content: flex-end; gap: 1rem; border-radius: 0 0 2rem 2rem;">
            <div id="footerActions" style="display: flex; gap: 1rem;">
                <!-- Botones inyectados por JS -->
            </div>
            <button class="ms-btn-reset" style="background: #64748b; color: white; padding: 0.65rem 1.75rem; border-radius: 0.65rem; font-weight: 800; font-size: 0.95rem;" onclick="closeModal()">Cerrar Detalle</button>
        </div>
    </div>
</div>

<!-- Modal Enviar OC (Subir PDF) -->
<div id="enviarOcModal" class="ms-modal" onclick="closeModalOnFondo(event)">
    <div class="ms-modal-content" onclick="event.stopPropagation()" style="max-width: 500px; border-radius: 1.5rem;">
        <div class="ms-modal-header" style="padding: 1.5rem 2rem;">
            <h3 class="ms-table-title">Enviar Orden de Compra</h3>
            <button class="ms-btn-reset" onclick="closeEnviarOcModal()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <form id="enviarOcForm" method="POST" action="{{ route('oc.send') }}" enctype="multipart/form-data" style="padding: 2rem;">
            @csrf
            <input type="hidden" name="oc_solicitud_id" id="enviarOcId">
            <input type="hidden" name="ceco" id="enviarOcCeco">
            <input type="hidden" name="tipo_solicitud" id="enviarOcTipo">
            <input type="hidden" name="proveedor" id="enviarOcProveedor">
            <input type="hidden" name="rut" id="enviarOcRut">
            <input type="hidden" name="descripcion" id="enviarOcDesc">
            <input type="hidden" name="monto" id="enviarOcMonto">
            
            <input type="hidden" name="email_proveedor" id="enviarOcEmail">

            <div style="text-align: center; margin-bottom: 2rem;">
                <div style="width: 64px; height: 64px; background: #eff6ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#0f6bb6" stroke-width="2.5"><path d="M22 2L11 13"></path><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                </div>
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #1e293b;">¿Enviar OC <span id="displayNumeroOc" style="color: #0f6bb6;"></span>?</h3>
                <p style="font-size: 0.9rem; color: #64748b; margin-top: 0.5rem;">Adjunte el archivo PDF de la OC para notificar al proveedor.</p>
                <input type="hidden" name="numero_oc" id="enviarOcNumero">
            </div>

            <div style="margin-bottom: 2rem;">
                <label class="ms-kpi-label" style="display: block; margin-bottom: 0.5rem;">Archivo PDF de la OC *</label>
                <div class="attachment-box" id="dropzone" style="padding: 1.5rem; border: 2px dashed #cbd5e1; border-radius: 1rem; text-align: center; background: #f8fafc; transition: all 0.2s;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" style="margin-bottom: 0.5rem;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                    <p style="font-size: 0.85rem; color: #64748b; font-weight: 600; margin-bottom: 0.5rem;">Arrastra el PDF aquí o haz clic para buscar</p>
                    <input type="file" name="oc_file" id="oc_file" required accept="application/pdf" style="display: none;">
                    <button type="button" onclick="document.getElementById('oc_file').click()" style="background: white; border: 1px solid #cbd5e1; padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 0.8rem; font-weight: 700; color: #475569; cursor: pointer;">Seleccionar Archivo</button>
                    <p id="fileName" style="margin-top: 0.75rem; font-size: 0.8rem; color: #0f6bb6; font-weight: 700; display: none;"></p>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" class="ms-btn-reset" onclick="closeEnviarOcModal()" style="font-weight: 700;">Cancelar</button>
                <button type="submit" class="ms-btn-new" style="padding: 0.75rem 1.5rem;">Finalizar y Enviar</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let currentSolicitudId = null;

    // --- Dropdown Logic ---
    function toggleDropdown(btn) {
        const dropdown = btn.parentElement;
        const menu = dropdown.querySelector('.ms-dropdown-menu');
        const isActive = dropdown.classList.contains('active');
        
        // Cerrar todos los demás
        document.querySelectorAll('.ms-dropdown').forEach(d => {
            d.classList.remove('active');
            const m = d.querySelector('.ms-dropdown-menu');
            if (m) m.classList.remove('active');
        });
        
        if (!isActive) {
            // Detectar posición para abrir hacia arriba si es necesario
            const rect = btn.getBoundingClientRect();
            const spaceBelow = window.innerHeight - rect.bottom;
            
            if (spaceBelow < 280) { // Umbral para abrir hacia arriba
                menu.classList.add('dropup');
            } else {
                menu.classList.remove('dropup');
            }
            
            dropdown.classList.add('active');
            menu.classList.add('active');
        }
    }

    // Close dropdowns on click outside
    window.addEventListener('click', function(e) {
        if (!e.target.closest('.ms-dropdown')) {
            document.querySelectorAll('.ms-dropdown').forEach(d => {
                d.classList.remove('active');
                const m = d.querySelector('.ms-dropdown-menu');
                if (m) m.classList.remove('active');
            });
        }
    });

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
            confirmButtonColor: '#dc2626',
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
                    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Marcada como facturada', showConfirmButton: false, timer: 2000 }).then(() => location.reload());
                }
            } catch (e) {
                Swal.fire('Error', 'No se pudo procesar.', 'error');
            }
        }
    }

    function openGestionModal(solicitud) {
        currentSolicitudId = solicitud.id;
        let extra = {};
        try {
            extra = solicitud.datos_extra ? JSON.parse(solicitud.datos_extra) : {};
        } catch (e) { console.error("Error parsing datos_extra", e); }

        // Header e Identificadores
        document.getElementById('modalId').innerText = solicitud.id;
        document.getElementById('modalIdResumen').innerText = solicitud.id;
        
        // Proveedor y Estética
        const provName = solicitud.proveedor || 'No especificado';
        document.getElementById('modalProveedor').innerText = provName;
        document.getElementById('modalProveedorSec').innerText = provName;
        document.getElementById('modalProveedorTransfer').innerText = provName;
        document.getElementById('modalInitial').innerText = provName.charAt(0).toUpperCase();
        
        // Fechas y Status
        const fechaObj = new Date(solicitud.created_at);
        const fechaStr = fechaObj.toLocaleDateString('es-CL', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
        document.getElementById('modalFecha').innerText = fechaStr;
        document.getElementById('modalFechaSec').innerText = fechaStr;
        
        const badge = document.getElementById('modalStatusBadge');
        badge.innerText = solicitud.estado;
        badge.className = 'chip';
        if(solicitud.estado === 'Solicitada') badge.style.cssText = 'background: #fef9c3; color: #854d0e; font-weight: 700;';
        else if(solicitud.estado === 'Aceptada') badge.style.cssText = 'background: #dcfce7; color: #15803d; font-weight: 700;';
        else if(solicitud.estado === 'Enviada') badge.style.cssText = 'background: #e0f2fe; color: #0369a1; font-weight: 700;';
        else if(solicitud.estado === 'Rechazada') badge.style.cssText = 'background: #fee2e2; color: #b91c1c; font-weight: 700;';
        else badge.style.cssText = 'background: #f1f5f9; color: #475569; font-weight: 700;';

        // Detalles de la Solicitud
        document.getElementById('modalRut').innerText = solicitud.rut || extra.rut_proveedor || 'N/A';
        document.getElementById('modalRutTransfer').innerText = solicitud.rut || extra.rut_proveedor || 'N/A';
        document.getElementById('modalTipoDocBadge').innerText = solicitud.tipo_documento || 'OC';
        document.getElementById('modalMonto').innerText = '$' + new Intl.NumberFormat('es-CL').format(solicitud.monto);
        document.getElementById('modalDesc').innerText = solicitud.descripcion || 'Sin descripción detallada';
        document.getElementById('modalObs').innerText = extra.observacion || 'Sin observaciones adicionales';
        
        // Datos Bancarios
        document.getElementById('modalBanco').innerText = extra.banco || 'No especificado';
        document.getElementById('modalCuenta').innerText = extra.numero_cuenta ? `${extra.tipo_cuenta || 'Cuenta'}: ${extra.numero_cuenta}` : 'No especificada';

        // Resumen Lateral
        document.getElementById('modalCecoResumen').innerText = solicitud.ceco;
        document.getElementById('modalTipoSolicitudResumen').innerText = solicitud.tipo_solicitud;
        document.getElementById('modalEmailProveedorResumen').innerText = extra.email_proveedor || extra.correo_contacto || 'No especificado';

        // Gestión de Adjuntos
        const adjContainer = document.getElementById('modalAdjunto');
        if (extra.ruta_adjunto) {
            adjContainer.innerHTML = `<a href="/oc/adjunto/${extra.ruta_adjunto}" target="_blank" style="color: #0f6bb6; text-decoration: none; display: flex; align-items: center; gap: 0.5rem;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path></svg>
                Ver Adjunto
            </a>`;
            adjContainer.style.background = '#eff6ff';
        } else {
            adjContainer.innerText = 'Sin adjunto';
            adjContainer.style.background = '#f1f5f9';
        }

        // --- Configuración de Botones de Acción ---
        const footerActions = document.getElementById('footerActions');
        footerActions.innerHTML = ''; // Limpiar

        if (solicitud.estado === 'Solicitada') {
            footerActions.innerHTML = `
                <form id="rechazarForm" method="POST" action="{{ url('oc/gestor') }}/${solicitud.id}/status">@csrf<input type="hidden" name="accion" value="rechazar"><input type="hidden" name="comentario" id="comentarioRechazo"><button type="button" onclick="confirmRechazo()" class="ms-btn-reset" style="background: #fef2f2; color: #dc2626; border: 1.5px solid #fecaca; font-weight: 800; padding: 0.65rem 1.25rem; border-radius: 0.65rem; font-size: 0.9rem;">Rechazar Solicitud</button></form>
                <form id="aceptarForm" method="POST" action="{{ url('oc/gestor') }}/${solicitud.id}/status">@csrf<input type="hidden" name="accion" value="aceptar"><button type="button" onclick="submitAceptar()" class="ms-btn-new" style="padding: 0.65rem 1.75rem; font-size: 0.95rem;">Aceptar y Aprobar</button></form>
            `;
        } else if (solicitud.estado === 'Aceptada') {
            footerActions.innerHTML = `
                <form id="rechazarForm" method="POST" action="{{ url('oc/gestor') }}/${solicitud.id}/status">@csrf<input type="hidden" name="accion" value="rechazar"><input type="hidden" name="comentario" id="comentarioRechazo"><button type="button" onclick="confirmRechazo()" class="ms-btn-reset" style="background: #fef2f2; color: #dc2626; border: 1.5px solid #fecaca; font-weight: 800; padding: 0.65rem 1.25rem; border-radius: 0.65rem; font-size: 0.9rem;">Rechazar</button></form>
                <button type="button" onclick="openEnviarOcModal()" class="ms-btn-new" style="padding: 0.65rem 1.75rem; font-size: 0.95rem; background: #0f6bb6;">Subir y Enviar OC</button>
            `;
        } else if (solicitud.estado === 'Enviada') {
            footerActions.innerHTML = `
                <button type="button" onclick="confirmFacturacion(${solicitud.id})" class="ms-btn-new" style="padding: 0.65rem 1.75rem; font-size: 0.95rem; background: #0891b2;">Marcar como Facturado</button>
            `;
        }

        // Datos para Modal Enviar OC
        const year = new Date().getFullYear();
        const formattedId = solicitud.id.toString().padStart(3, '0');
        const autoNumeroOc = `OC-${year}-${formattedId}`;
        
        document.getElementById('enviarOcId').value = solicitud.id;
        document.getElementById('enviarOcCeco').value = solicitud.ceco;
        document.getElementById('enviarOcTipo').value = solicitud.tipo_solicitud;
        document.getElementById('enviarOcProveedor').value = provName;
        document.getElementById('enviarOcEmail').value = extra.email_proveedor || extra.correo_contacto || '';
        document.getElementById('enviarOcRut').value = solicitud.rut || extra.rut_proveedor || '';
        document.getElementById('enviarOcDesc').value = solicitud.descripcion;
        document.getElementById('enviarOcMonto').value = solicitud.monto;
        document.getElementById('enviarOcNumero').value = autoNumeroOc;
        document.getElementById('displayNumeroOc').innerText = autoNumeroOc;

        document.getElementById('gestionModal').classList.add('active');
    }

    function openEnviarOcModal() {
        document.getElementById('enviarOcModal').classList.add('active');
    }

    function closeEnviarOcModal() {
        document.getElementById('enviarOcModal').classList.remove('active');
    }

    function closeModal() {
        document.getElementById('gestionModal').classList.remove('active');
    }

    function closeModalOnFondo(e) {
        if (e.target.classList.contains('ms-modal')) {
            closeModal();
            closeEnviarOcModal();
        }
    }

    // Lógica de archivos
    document.getElementById('oc_file').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name;
        const info = document.getElementById('fileName');
        if (fileName) {
            info.innerText = 'Archivo seleccionado: ' + fileName;
            info.style.display = 'block';
        } else {
            info.style.display = 'none';
        }
    });

    function openEnviarOcModalDirect(solicitud) {
        // Pre-poblamos los datos necesarios antes de abrir
        const provName = solicitud.proveedor || 'No especificado';
        let extra = {};
        try { extra = solicitud.datos_extra ? JSON.parse(solicitud.datos_extra) : {}; } catch (e) {}

        const year = new Date().getFullYear();
        const formattedId = solicitud.id.toString().padStart(3, '0');
        const autoNumeroOc = `OC-${year}-${formattedId}`;

        document.getElementById('enviarOcId').value = solicitud.id;
        document.getElementById('enviarOcCeco').value = solicitud.ceco;
        document.getElementById('enviarOcTipo').value = solicitud.tipo_solicitud;
        document.getElementById('enviarOcProveedor').value = provName;
        document.getElementById('enviarOcEmail').value = extra.email_proveedor || extra.correo_contacto || '';
        document.getElementById('enviarOcRut').value = solicitud.rut || extra.rut_proveedor || '';
        document.getElementById('enviarOcDesc').value = solicitud.descripcion;
        document.getElementById('enviarOcMonto').value = solicitud.monto;
        document.getElementById('enviarOcNumero').value = autoNumeroOc;
        document.getElementById('displayNumeroOc').innerText = autoNumeroOc;

        openEnviarOcModal();
    }

    async function confirmFacturacion(id) {
        const result = await Swal.fire({
            title: '¿Marcar como Facturado?',
            text: 'Se actualizará el estado de la solicitud a Facturado.',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#0f172a',
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
                    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Solicitud actualizada', showConfirmButton: false, timer: 2000 }).then(() => location.reload());
                }
            } catch (e) {
                Swal.fire('Error', 'No se pudo procesar.', 'error');
            }
        }
    }

    async function habilitarEdicion() {
        if (!currentSolicitudId) return;
        const result = await Swal.fire({
            title: '¿Habilitar edición?',
            text: 'La solicitud volverá al solicitante para que pueda realizar cambios.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0f6bb6',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Sí, habilitar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch(`{{ url('oc/gestor') }}/${currentSolicitudId}/habilitar-edicion`, {
                    method: 'POST',
                    headers: { 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                if (response.ok) {
                    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Edición habilitada correctamente', showConfirmButton: false, timer: 2000 }).then(() => location.reload());
                } else {
                    throw new Error();
                }
            } catch (e) {
                Swal.fire('Error', 'No se pudo procesar la solicitud.', 'error');
            }
        }
    }

    async function solicitarAjuste() {
        if (!currentSolicitudId) return;
        const { value: text } = await Swal.fire({
            title: 'Solicitar Ajuste',
            input: 'textarea',
            inputLabel: 'Indique los cambios o ajustes necesarios',
            inputPlaceholder: 'Ej: Favor corregir el CECO o adjuntar documento...',
            inputAttributes: { 'aria-label': 'Motivos del ajuste' },
            showCancelButton: true,
            confirmButtonColor: '#b69950',
            confirmButtonText: 'Solicitar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true,
            inputValidator: (value) => {
                if (!value || value.trim() === '') {
                    return 'Debes indicar qué cambios son necesarios';
                }
            }
        });

        if (text) {
            try {
                const response = await fetch(`{{ url('oc/gestor') }}/${currentSolicitudId}/solicitar-ajuste`, {
                    method: 'POST',
                    headers: { 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ comentario: text })
                });
                if (response.ok) {
                    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Ajuste solicitado al usuario', showConfirmButton: false, timer: 2000 }).then(() => location.reload());
                } else {
                    throw new Error();
                }
            } catch (e) {
                Swal.fire('Error', 'No se pudo procesar la solicitud.', 'error');
            }
        }
    }

    async function confirmRechazo() {
        const { value: text } = await Swal.fire({
            title: '¿Rechazar solicitud?',
            text: 'Esta acción notificará al solicitante y detendrá el proceso.',
            input: 'textarea',
            inputLabel: 'Motivo del rechazo',
            inputPlaceholder: 'Escriba aquí por qué se rechaza la solicitud...',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Sí, rechazar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true,
            inputValidator: (value) => {
                if (!value || value.trim() === '') {
                    return 'Debes ingresar un motivo para el rechazo';
                }
            }
        });

        if (text) {
            const form = document.getElementById('rechazarForm');
            const formData = new FormData(form);
            formData.set('comentario', text);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: formData
                });
                if (response.ok) {
                    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Solicitud rechazada', showConfirmButton: false, timer: 2000 }).then(() => location.reload());
                }
            } catch (e) {
                Swal.fire('Error', 'No se pudo procesar el rechazo.', 'error');
            }
        }
    }

    async function submitAceptar() {
        const form = document.getElementById('aceptarForm');
        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: formData
            });
            if (response.ok) {
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Solicitud aprobada correctamente', showConfirmButton: false, timer: 2000 }).then(() => location.reload());
            }
        } catch (e) {
            Swal.fire('Error', 'No se pudo procesar la aprobación.', 'error');
        }
    }

    // --- Manejo de Envío de OC por AJAX ---
    document.getElementById('enviarOcForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const form = this;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerText;
        
        // Validar que se haya seleccionado un archivo
        const fileInput = document.getElementById('oc_file');
        if (fileInput.files.length === 0) {
            Swal.fire('Aviso', 'Debe seleccionar el archivo PDF de la OC.', 'warning');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="display:inline-block; vertical-align:middle; width:16px; height:16px;">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Enviando...
        `;

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.success) {
                closeEnviarOcModal();
                closeModal();
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'OC enviada correctamente al proveedor',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error', data.message || 'Ocurrió un error al enviar la OC.', 'error');
                submitBtn.disabled = false;
                submitBtn.innerText = originalBtnText;
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire('Error', 'Error de red o servidor al procesar la solicitud.', 'error');
            submitBtn.disabled = false;
            submitBtn.innerText = originalBtnText;
        }
    });
</script>
@endpush
