@extends('oc.layouts.dashboard')

@section('title', 'Inicio de Usuario')
@section('subtitle', 'Panel personal de gestión de órdenes de compra')

@section('header')
<div class="ms-banner">
    <div>
        <h1 class="ms-banner-title">Bienvenido, {{ auth()->user()->name }}</h1>
        <p class="ms-banner-sub">Gestione sus solicitudes y realice nuevas adquisiciones desde aquí.</p>
    </div>
</div>
@endsection

@section('content')
<style>
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .quick-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 1.25rem;
        padding: 1.75rem;
        text-decoration: none;
        color: var(--text-main);
        box-shadow: var(--shadow-premium);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .quick-card:hover {
        transform: translateY(-5px);
        border-color: var(--brand-primary);
        box-shadow: 0 15px 30px rgba(15, 107, 182, 0.1);
    }

    .quick-icon {
        width: 48px;
        height: 48px;
        background: rgba(15, 107, 182, 0.1);
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--brand-primary);
        margin-bottom: 0.5rem;
    }

    .quick-title {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    .quick-subtitle {
        margin: 0;
        color: var(--text-muted);
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .panel-card {
        background: white;
        border-radius: 1.5rem;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-premium);
        overflow: hidden;
    }

    .panel-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #fcfdfe;
    }

    .panel-title {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--text-main);
    }
</style>

<div class="quick-actions">
    <a class="quick-card" href="{{ route('oc.cliente') }}">
        <div class="quick-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        </div>
        <h3 class="quick-title">Nueva OC Cliente</h3>
        <p class="quick-subtitle">Crear solicitud para servicios externos de clientes.</p>
    </a>
    <a class="quick-card" href="{{ route('oc.interna') }}">
        <div class="quick-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
        </div>
        <h3 class="quick-title">Nueva OC Interna</h3>
        <p class="quick-subtitle">Generar solicitud para necesidades internas del equipo.</p>
    </a>
    <a class="quick-card" href="{{ route('oc.negocio') }}">
        <div class="quick-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="20" x2="12" y2="10"></line><line x1="18" y1="20" x2="18" y2="4"></line><line x1="6" y1="20" x2="6" y2="16"></line></svg>
        </div>
        <h3 class="quick-title">Nueva OC Negocio</h3>
        <p class="quick-subtitle">Solicitar órdenes vinculadas a unidades de negocio.</p>
    </a>
</div>

<div class="panel-card">
    <div class="panel-header">
        <h2 class="panel-title">Tus últimas solicitudes</h2>
        <a href="{{ route('oc.enviadas') }}" class="ms-btn-reset" style="color: var(--brand-primary); font-weight: 700; font-size: 0.9rem;">
            Ver historial completo →
        </a>
    </div>
    <div class="ms-table-wrapper">
        <table class="ms-table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Proveedor</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentRequests as $request)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($request->created_at)->format('d/m/Y') }}</td>
                        <td style="font-weight: 700;">{{ $request->tipo_solicitud ?? 'N/A' }}</td>
                        <td><div style="font-weight: 600;">{{ Str::limit($request->proveedor ?? 'N/A', 30) }}</div></td>
                        <td>
                            <div style="font-size: 0.85rem; color: var(--text-muted); max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $request->descripcion ?? 'Sin descripción' }}
                            </div>
                        </td>
                        <td>
                            @php
                                $statusClass = match(strtolower((string)($request->estado ?? ''))) {
                                    'aceptada', 'aprobada' => 'background: #dcfce7; color: #15803d;',
                                    'rechazada' => 'background: #fee2e2; color: #b91c1c;',
                                    'enviada' => 'background: #f3f4f6; color: #4b5563;',
                                    default => 'background: #fef9c3; color: #854d0e;',
                                };
                            @endphp
                            <span class="chip" style="{{ $statusClass }}">{{ $request->estado ?? 'Solicitada' }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 4rem; text-align: center; color: var(--text-muted);">
                            <div style="margin-bottom: 1rem; opacity: 0.3;">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                            </div>
                            Aún no tienes solicitudes registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
