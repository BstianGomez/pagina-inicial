@extends('oc.layouts.dashboard')

@section('title', 'Inicio Solicitante')
@section('subtitle', 'Panel de creación y seguimiento de órdenes de compra')

@section('header')
<div class="ms-banner">
    <div>
        <h1 class="ms-banner-title">Bienvenido al Portal de OC</h1>
        <p class="ms-banner-sub">Seleccione el tipo de solicitud que desea realizar para comenzar.</p>
    </div>
</div>
@endsection

@section('content')
<style>
    .options-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .option-card {
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

    .option-card:hover {
        transform: translateY(-5px);
        border-color: var(--brand-primary);
        box-shadow: 0 15px 30px rgba(15, 107, 182, 0.1);
    }

    .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.5rem;
    }

    .icon-cliente { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .icon-interna { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .icon-negocio { background: rgba(34, 197, 94, 0.1); color: #22c55e; }

    .option-card h3 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    .option-card p {
        margin: 0;
        color: var(--text-muted);
        font-size: 0.9rem;
        line-height: 1.5;
        flex-grow: 1;
    }

    .card-footer {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--brand-primary);
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        margin-top: 1rem;
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

    .manager-note {
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
        padding: 1rem 2rem;
    }

    .note-content {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        font-size: 0.85rem;
        color: var(--text-main);
        display: flex;
        gap: 0.75rem;
        align-items: flex-start;
    }
</style>

<div class="options-grid">
    <a href="{{ route('oc.cliente') }}" class="option-card">
        <div class="icon-box icon-cliente">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        </div>
        <h3>OC Cliente</h3>
        <p>Solicitud de orden para Clientes Externos.</p>
        <div class="card-footer">
            Comenzar solicitud →
        </div>
    </a>

    <a href="{{ route('oc.interna') }}" class="option-card">
        <div class="icon-box icon-interna">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
        </div>
        <h3>OC Interna</h3>
        <p>Uso interno de la fundación.</p>
        <div class="card-footer">
            Comenzar solicitud →
        </div>
    </a>

    <a href="{{ route('oc.negocio') }}" class="option-card">
        <div class="icon-box icon-negocio">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
        </div>
        <h3>OC Negocio</h3>
        <p>Para Unidades de Negocio (OT, OP, DI, OR, etc.)</p>
        <div class="card-footer">
            Comenzar solicitud →
        </div>
    </a>
</div>

<div class="panel-card">
    <div class="panel-header">
        <h2 class="panel-title">Sus últimas solicitudes</h2>
        <a href="{{ route('oc.index') }}" class="ms-btn-reset" style="color: var(--brand-primary); font-weight: 700; font-size: 0.9rem;">
            Ver historial completo →
        </a>
    </div>
    <div class="ms-table-wrapper">
        <table class="ms-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Proveedor</th>
                    <th>Monto</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentRequests as $request)
                    <tr style="{{ $request->manager_comment ? 'border-bottom: none;' : '' }}">
                        <td style="font-weight: 800; color: var(--brand-primary);">#{{ $request->id }}</td>
                        <td><div style="font-weight: 600;">{{ Str::limit($request->proveedor ?? 'N/A', 30) }}</div></td>
                        <td style="font-weight: 700; color: var(--text-main);">${{ number_format($request->monto, 0, ',', '.') }}</td>
                        <td>
                            @php
                                $statusClass = match(strtolower((string)($request->estado ?? ''))) {
                                    'aceptada', 'aprobada', 'enviada', 'facturado' => 'background: #dcfce7; color: #15803d;',
                                    'rechazada' => 'background: #fee2e2; color: #b91c1c;',
                                    default => 'background: #fef9c3; color: #854d0e;',
                                };
                            @endphp
                            <span class="chip" style="{{ $statusClass }}">{{ $request->estado ?? 'Solicitada' }}</span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($request->created_at)->format('d/m/Y') }}</td>
                    </tr>
                    @if($request->manager_comment)
                        <tr class="manager-note">
                            <td colspan="5" style="padding: 0 1.5rem 1.5rem 2.5rem;">
                                <div class="note-content">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-top: 2px; flex-shrink: 0;"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                                    <div>
                                        <div style="font-size: 0.7rem; font-weight: 800; color: var(--brand-primary); text-transform: uppercase; margin-bottom: 2px;">Mensaje del gestor</div>
                                        <div>{{ $request->manager_comment }}</div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="5" style="padding: 4rem; text-align: center; color: var(--text-muted);">
                            <div style="margin-bottom: 1rem; opacity: 0.3;">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                            </div>
                            Aún no ha realizado ninguna solicitud.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
