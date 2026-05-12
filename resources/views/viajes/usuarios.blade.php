@extends('viajes.layouts.dashboard')

@section('title', 'Gestión de Usuarios')
@section('subtitle', 'Administración centralizada de accesos y roles del sistema')

@section('header')
<div class="ms-banner">
    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
        <div>
            <h1 class="ms-banner-title">Control de Accesos</h1>
            <p class="ms-banner-sub">Gestione los perfiles de usuario, roles administrativos y permisos de módulo.</p>
        </div>
        <button onclick="document.getElementById('modalCrear').classList.add('active')" class="ms-btn-new" style="background: white; color: var(--brand-primary); border: none;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Nuevo Usuario
        </button>
    </div>
</div>
@endsection

@section('content')

<!-- ── KPI SUMMARY ────────────────────────────────────── -->
<div class="ms-kpi-grid" style="margin-bottom: 2rem;">
    <div class="ms-kpi active">
        <div class="ms-kpi-icon" style="background: rgba(15, 107, 182, 0.1); color: var(--brand-primary);">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        </div>
        <div class="ms-kpi-body">
            <span class="ms-kpi-label">Usuarios Totales</span>
            <span class="ms-kpi-value">{{ $users->count() }}</span>
            <span class="ms-kpi-desc">Cuentas activas en sistema</span>
        </div>
    </div>
</div>

<!-- ── LISTADO DE USUARIOS ────────────────────────────── -->
<div class="ms-table-card">
    <div class="ms-table-header">
        <h3 class="ms-table-title">Directorio de Usuarios</h3>
        <div class="ms-search-wrap" style="width: 300px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="ms-search-icon"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            <input type="text" id="userSearch" placeholder="Buscar por nombre o email..." class="ms-search-input" onkeyup="filterUsers()">
        </div>
    </div>
    <div class="ms-table-wrapper">
        <table class="ms-table" id="usersTable">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Perfil / Rol</th>
                    <th>Registrado</th>
                    <th style="text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                @php
                    $rolColors = [
                        'super_admin' => ['bg' => '#1e1b4b', 'color' => '#c7d2fe', 'label' => 'Super Admin'],
                        'admin'       => ['bg' => '#eff6ff', 'color' => '#1d4ed8', 'label' => 'Administrador'],
                        'aprobador'   => ['bg' => '#f0fdf4', 'color' => '#15803d', 'label' => 'Aprobador'],
                        'gestor'      => ['bg' => '#fefce8', 'color' => '#a16207', 'label' => 'Gestor'],
                        'usuario'     => ['bg' => '#faf5ff', 'color' => '#7e22ce', 'label' => 'Usuario'],
                    ];
                    $rc = $rolColors[$user->rol ?? 'usuario'] ?? ['bg' => '#f1f5f9', 'color' => '#475569', 'label' => $user->rol ?? '—'];
                    $esProtegido = $user->isSuperAdmin() && !Auth::user()->isSuperAdmin();
                @endphp
                <tr data-search="{{ strtolower($user->name . ' ' . $user->email) }}">
                    <td>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #0f6bb6 0%, #0b5fa5 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 0.9rem; box-shadow: 0 4px 10px rgba(15, 107, 182, 0.2);">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span style="font-weight: 800; color: var(--text-main);">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600;">{{ $user->email }}</td>
                    <td>
                        <span class="chip" style="background: {{ $rc['bg'] }}; color: {{ $rc['color'] }};">
                            {{ $rc['label'] }}
                        </span>
                    </td>
                    <td style="font-size: 0.85rem; color: var(--text-muted);">{{ $user->created_at?->format('d/m/Y') ?? '—' }}</td>
                    <td>
                        <div style="display: flex; gap: 0.5rem; justify-content: center;">
                            @if(!$esProtegido)
                                <button onclick="abrirEditar({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->email }}', '{{ $user->rol ?? '' }}')" class="ms-btn-reset" style="padding: 0.5rem; border-radius: 0.75rem; color: var(--brand-primary); background: #eff6ff;" title="Editar">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </button>
                                <form action="{{ route('viajes.usuarios.destroy', $user) }}" method="POST" onsubmit="return confirm('¿Eliminar definitivamente a {{ $user->name }}?');" style="display: inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="ms-btn-reset" style="padding: 0.5rem; border-radius: 0.75rem; color: #dc2626; background: #fef2f2;" title="Eliminar">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                    </button>
                                </form>
                            @else
                                <span style="font-size: 0.75rem; font-style: italic; color: var(--text-muted);">Protegido</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="padding: 4rem; text-align: center; color: var(--text-muted);">Sin usuarios registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ── MODAL CREAR ────────────────────────────────────── -->
<div id="modalCrear" class="ms-modal" onclick="cerrarModales(event)">
    <div class="ms-modal-content" onclick="event.stopPropagation()" style="max-width: 500px;">
        <div class="ms-modal-header">
            <h2 class="ms-table-title">Registrar Nuevo Usuario</h2>
            <button class="ms-btn-reset" style="padding: 0.5rem;" onclick="document.getElementById('modalCrear').classList.remove('active')">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <form action="{{ route('viajes.usuarios.store') }}" method="POST" style="padding: 2rem;">
            @csrf
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                <div class="form-field">
                    <label class="form-label">Nombre Completo</label>
                    <input type="text" name="name" class="ms-search-input" placeholder="Juan Pérez" required style="padding-left: 1rem;">
                </div>
                <div class="form-field">
                    <label class="form-label">Correo Electrónico</label>
                    <input type="email" name="email" class="ms-search-input" placeholder="correo@empresa.cl" required style="padding-left: 1rem;">
                </div>
                <div class="form-field">
                    <label class="form-label">Perfil de Usuario</label>
                    <select name="rol" class="ms-select" required style="width: 100%;">
                        <option value="">Seleccione Rol...</option>
                        @if(Auth::user()->isSuperAdmin()) <option value="super_admin">Super Administrador</option> @endif
                        <option value="admin">Administrador</option>
                        <option value="aprobador">Aprobador</option>
                        <option value="gestor">Gestor</option>
                        <option value="usuario">Usuario</option>
                    </select>
                </div>
                <div class="form-field">
                    <label class="form-label">Contraseña Inicial</label>
                    <input type="password" name="password" class="ms-search-input" placeholder="Mín. 8 caracteres" required style="padding-left: 1rem;">
                </div>
            </div>
            <div style="margin-top: 2rem; display: flex; justify-content: flex-end; gap: 1rem;">
                <button type="button" class="ms-btn-reset" onclick="document.getElementById('modalCrear').classList.remove('active')">Cancelar</button>
                <button type="submit" class="ms-btn-new" style="min-width: 150px; justify-content: center;">Crear Cuenta</button>
            </div>
        </form>
    </div>
</div>

<!-- ── MODAL EDITAR ────────────────────────────────────── -->
<div id="modalEditar" class="ms-modal" onclick="cerrarModales(event)">
    <div class="ms-modal-content" onclick="event.stopPropagation()" style="max-width: 500px;">
        <div class="ms-modal-header">
            <h2 class="ms-table-title">Modificar Usuario</h2>
            <button class="ms-btn-reset" style="padding: 0.5rem;" onclick="document.getElementById('modalEditar').classList.remove('active')">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <form id="formEditar" method="POST" style="padding: 2rem;">
            @csrf @method('PUT')
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                <div class="form-field">
                    <label class="form-label">Nombre</label>
                    <input type="text" id="editNombre" name="name" class="ms-search-input" required style="padding-left: 1rem;">
                </div>
                <div class="form-field">
                    <label class="form-label">Email</label>
                    <input type="email" id="editEmail" name="email" class="ms-search-input" required style="padding-left: 1rem;">
                </div>
                <div class="form-field">
                    <label class="form-label">Rol</label>
                    <select id="editRol" name="rol" class="ms-select" required style="width: 100%;">
                        @if(Auth::user()->isSuperAdmin()) <option value="super_admin">Super Administrador</option> @endif
                        <option value="admin">Administrador</option>
                        <option value="aprobador">Aprobador</option>
                        <option value="gestor">Gestor</option>
                        <option value="usuario">Usuario</option>
                    </select>
                </div>
                <div class="form-field">
                    <label class="form-label">Nueva Contraseña <span style="opacity: 0.6; font-weight: 400;">(opcional)</span></label>
                    <input type="password" name="password" class="ms-search-input" placeholder="Dejar vacío para mantener actual" style="padding-left: 1rem;">
                </div>
            </div>
            <div style="margin-top: 2rem; display: flex; justify-content: flex-end; gap: 1rem;">
                <button type="button" class="ms-btn-reset" onclick="document.getElementById('modalEditar').classList.remove('active')">Cancelar</button>
                <button type="submit" class="ms-btn-new" style="min-width: 150px; justify-content: center;">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function filterUsers() {
    const q = document.getElementById('userSearch').value.toLowerCase();
    document.querySelectorAll('#usersTable tbody tr').forEach(tr => {
        tr.style.display = (tr.getAttribute('data-search') || '').includes(q) ? '' : 'none';
    });
}

function abrirEditar(id, nombre, email, rol) {
    document.getElementById('editNombre').value = nombre;
    document.getElementById('editEmail').value = email;
    document.getElementById('editRol').value = rol;
    document.getElementById('formEditar').action = `{{ url('viajes/usuarios') }}/${id}`;
    document.getElementById('modalEditar').classList.add('active');
}

function cerrarModales(e) {
    document.querySelectorAll('.ms-modal').forEach(m => m.classList.remove('active'));
}
</script>
@endpush

@endsection
