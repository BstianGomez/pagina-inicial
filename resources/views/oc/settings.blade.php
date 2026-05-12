@extends('oc.layouts.dashboard')

@section('title', 'Configuración de Proyectos')
@section('subtitle', 'Gestión de maestros y parámetros del sistema')

@section('header')
<div class="ms-banner">
    <div>
        <h1 class="ms-banner-title">Configuración de Maestros</h1>
        <p class="ms-banner-sub">Administre los coordinadores, servicios y tipos de proyecto</p>
    </div>
</div>
@endsection

@section('content')
<style>
    .settings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
        gap: 2rem;
    }
    
    .settings-card {
        background: white;
        border-radius: 1.5rem;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-premium);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    .settings-card:hover {
        transform: translateY(-4px);
    }
    
    .card-header {
        padding: 1.5rem;
        background: #fcfdfe;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .card-icon {
        width: 40px;
        height: 40px;
        background: rgba(15, 107, 182, 0.1);
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--brand-primary);
    }
    
    .card-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--text-main);
        margin: 0;
    }
    
    .card-body {
        padding: 1.5rem;
        flex: 1;
    }
    
    .items-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        max-height: 400px;
        overflow-y: auto;
    }
    
    .item-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1rem;
        background: #f8fafc;
        border-radius: 0.75rem;
        border: 1px solid transparent;
        transition: all 0.2s;
    }
    
    .item-row:hover {
        background: white;
        border-color: var(--brand-primary);
        box-shadow: 0 4px 12px rgba(15, 107, 182, 0.08);
    }
    
    .item-name {
        font-size: 0.9rem;
        font-weight: 600;
        color: #475569;
    }
    
    .add-form {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        background: #f1f5f9;
        padding: 1rem;
        border-radius: 1rem;
    }

    .ms-input-small {
        flex: 1;
        padding: 0.6rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid var(--border-color);
        font-size: 0.9rem;
    }

    .btn-delete {
        width: 32px;
        height: 32px;
        color: #94a3b8;
        background: white;
        border: 1px solid #e2e8f0;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.5rem;
        transition: all 0.2s;
    }
    
    .btn-delete:hover {
        background: #fee2e2;
        color: #ef4444;
        border-color: #fecaca;
    }
</style>

<div class="settings-grid">
    <!-- Coordinadores -->
    <div class="settings-card">
        <div class="card-header">
            <div class="card-icon">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1m-4 6h-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2h5m10-10a4 4 0 10-8 0 4 4 0 008 0z"/></svg>
            </div>
            <h3 class="card-title">Coordinadores de Proyecto</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('oc.config.coordinador.store') }}" method="POST" class="add-form">
                @csrf
                <input type="text" name="nombre" class="ms-input-small" placeholder="Nombre completo..." required>
                <button type="submit" class="ms-btn-reset ms-btn-new" style="padding: 0.6rem 1.2rem;">Añadir</button>
            </form>
            
            <div class="items-list">
                @forelse($coordinadores as $item)
                    <div class="item-row">
                        <span class="item-name">{{ $item->nombre }}</span>
                        <form action="{{ route('oc.config.coordinador.destroy', $item->id) }}" method="POST" onsubmit="return confirm('¿Está seguro de eliminar este coordinador?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" title="Eliminar">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                @empty
                    <div style="text-align: center; padding: 2rem; color: var(--text-muted); font-style: italic;">No hay coordinadores registrados</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Tipos de Servicio -->
    <div class="settings-card">
        <div class="card-header">
            <div class="card-icon">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="card-title">Tipos de Servicio</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('oc.config.tipo-servicio.store') }}" method="POST" class="add-form">
                @csrf
                <input type="text" name="nombre" class="ms-input-small" placeholder="Nombre del servicio..." required>
                <button type="submit" class="ms-btn-reset ms-btn-new" style="padding: 0.6rem 1.2rem;">Añadir</button>
            </form>
            
            <div class="items-list">
                @forelse($tipoServicios as $item)
                    <div class="item-row">
                        <span class="item-name">{{ $item->nombre }}</span>
                        <form action="{{ route('oc.config.tipo-servicio.destroy', $item->id) }}" method="POST" onsubmit="return confirm('¿Está seguro de eliminar este tipo de servicio?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" title="Eliminar">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                @empty
                    <div style="text-align: center; padding: 2rem; color: var(--text-muted); font-style: italic;">No hay tipos de servicio registrados</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Tipos de Proyecto -->
    <div class="settings-card">
        <div class="card-header">
            <div class="card-icon">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            </div>
            <h3 class="card-title">Tipos de Proyecto</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('oc.config.tipo-proyecto.store') }}" method="POST" class="add-form">
                @csrf
                <input type="text" name="nombre" class="ms-input-small" placeholder="Nombre del proyecto..." required>
                <button type="submit" class="ms-btn-reset ms-btn-new" style="padding: 0.6rem 1.2rem;">Añadir</button>
            </form>
            
            <div class="items-list">
                @forelse($tipoProyectos as $item)
                    <div class="item-row">
                        <span class="item-name">{{ $item->nombre }}</span>
                        <form action="{{ route('oc.config.tipo-proyecto.destroy', $item->id) }}" method="POST" onsubmit="return confirm('¿Está seguro de eliminar este tipo de proyecto?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" title="Eliminar">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                @empty
                    <div style="text-align: center; padding: 2rem; color: var(--text-muted); font-style: italic;">No hay tipos de proyecto registrados</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
