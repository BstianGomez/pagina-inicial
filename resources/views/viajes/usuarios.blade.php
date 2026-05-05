@extends('viajes.layouts.dashboard')

@section('title', 'Gestión de Usuarios')
@section('subtitle', 'Administra los accesos al sistema')

@section('header')
<div class="banner">
    <h1>Gestión de Usuarios</h1>
    <p>Crea, edita y administra los accesos al sistema.</p>
</div>
@endsection

@section('content')

@if(session('success'))
<div style="background: #dcfce7; border: 1px solid #86efac; color: #15803d; padding: 14px 20px; border-radius: 12px; margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div style="background: #fff1f2; border: 1px solid #fecdd3; color: #be123c; padding: 14px 20px; border-radius: 12px; margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('error') }}
</div>
@endif

<!-- Botón Nuevo Usuario + Modal -->
<div style="display: flex; justify-content: flex-end; margin-bottom: 20px;">
    <button onclick="document.getElementById('modalCrear').style.display='flex'" class="btn-primary" style="display: flex; align-items: center; gap: 8px;">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nuevo Usuario
    </button>
</div>

<!-- Tabla de usuarios -->
<div class="card" style="padding: 0; overflow: hidden;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #f8fafc; border-bottom: 2px solid var(--line);">
                <th style="padding: 14px 20px; text-align: left; font-size: 12px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px;">Nombre</th>
                <th style="padding: 14px 20px; text-align: left; font-size: 12px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px;">Correo</th>
                <th style="padding: 14px 20px; text-align: left; font-size: 12px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px;">Rol</th>
                <th style="padding: 14px 20px; text-align: left; font-size: 12px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px;">Registrado</th>
                <th style="padding: 14px 20px; text-align: center; font-size: 12px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr style="border-bottom: 1px solid var(--line); transition: background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                <td style="padding: 16px 20px;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #0b5fa5, #0f6bb6); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 14px; flex-shrink: 0;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <span style="font-weight: 600; color: var(--ink);">{{ $user->name }}</span>
                    </div>
                </td>
                <td style="padding: 16px 20px; color: var(--muted); font-size: 14px;">{{ $user->email }}</td>
                <td style="padding: 16px 20px;">
                    @php
                        $rolColors = [
                            'super_admin' => ['bg' => '#1e1b4b', 'color' => '#c7d2fe', 'label' => 'Super Admin'],
                            'admin'       => ['bg' => '#eff6ff', 'color' => '#1d4ed8', 'label' => 'Administrador'],
                            'aprobador'   => ['bg' => '#f0fdf4', 'color' => '#15803d', 'label' => 'Aprobador'],
                            'gestor'      => ['bg' => '#fefce8', 'color' => '#a16207', 'label' => 'Gestor'],
                            'usuario'     => ['bg' => '#faf5ff', 'color' => '#7e22ce', 'label' => 'Usuario'],
                        ];
                        $rc = $rolColors[$user->rol ?? 'usuario'] ?? ['bg' => '#f1f5f9', 'color' => '#475569', 'label' => $user->rol ?? '—'];
                        $esProtegido = $user->rol === 'super_admin' && (Auth::user()->rol ?? '') === 'admin';
                    @endphp
                    <span style="background: {{ $rc['bg'] }}; color: {{ $rc['color'] }}; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700;">
                        {{ $rc['label'] }}
                    </span>
                </td>
                <td style="padding: 16px 20px; color: var(--muted); font-size: 13px;">{{ $user->created_at?->format('d/m/Y') ?? '—' }}</td>
                <td style="padding: 16px 20px; text-align: center;">
                    <div style="display: flex; gap: 8px; justify-content: center;">
                        @if(!$esProtegido)
                        <!-- Editar -->
                        <button onclick="abrirEditar({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->email }}', '{{ $user->rol ?? '' }}', @json($user->assigned_apps ?? ($user->assigned_app ? [$user->assigned_app] : [])))"
                            style="background: #eff6ff; color: #1d4ed8; border: none; border-radius: 8px; padding: 7px 14px; font-size: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 5px; transition: all 0.2s;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Editar
                        </button>
                        <!-- Eliminar -->
                        <form action="{{ route('viajes.usuarios.destroy', $user) }}" method="POST" onsubmit="return confirm('¿Eliminar a {{ $user->name }}?');">
                            @csrf @method('DELETE')
                            <button type="submit"
                                style="background: #fff1f2; color: #be123c; border: none; border-radius: 8px; padding: 7px 14px; font-size: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 5px; transition: all 0.2s;">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Eliminar
                            </button>
                        </form>
                        @else
                        <span style="font-size:12px;color:var(--muted);font-style:italic;">Protegido</span>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding: 60px 20px; text-align: center; color: var(--muted);">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:48px;height:48px;margin:0 auto 12px;display:block;opacity:0.3;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    No hay usuarios registrados aún.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- ── MODAL CREAR USUARIO ──────────────────────────── -->
<div id="modalCrear" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); backdrop-filter:blur(4px); z-index:2000; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:20px; padding:36px; width:100%; max-width:500px; box-shadow:0 25px 60px rgba(0,0,0,0.2); position:relative;">
        <button onclick="document.getElementById('modalCrear').style.display='none'" style="position:absolute;top:16px;right:16px;background:none;border:none;cursor:pointer;color:var(--muted);">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:22px;height:22px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <h3 style="font-size:20px;font-weight:700;margin:0 0 24px;">Nuevo Usuario</h3>
        <form action="{{ route('viajes.usuarios.store') }}" method="POST">
            @csrf
            <div style="display:grid;gap:16px;">
                <div class="form-field">
                    <label class="form-label">Nombre completo</label>
                    <input type="text" name="name" class="form-control" placeholder="Ej: Juan Pérez" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" name="email" class="form-control" placeholder="usuario@empresa.cl" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Rol</label>
                    <select name="rol" class="form-select" required>
                        <option value="">Selecciona un rol...</option>
                        @if(Auth::check() && Auth::user()->rol === 'super_admin')
                        <option value="super_admin">Super Administrador</option>
                        @endif
                        <option value="admin">Administrador</option>
                        <option value="aprobador">Aprobador</option>
                        <option value="gestor">Gestor</option>
                        <option value="usuario">Usuario</option>
                    </select>
                </div>
                <div class="form-field">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" placeholder="Mínimo 8 caracteres" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Repite la contraseña" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Páginas asignadas</label>
                    <div style="display:flex;gap:10px;flex-wrap:wrap;">
                        <label style="display:inline-flex;align-items:center;gap:6px;font-size:14px;"><input type="checkbox" name="assigned_apps[]" value="oc" {{ is_array(old('assigned_apps')) && in_array('oc', old('assigned_apps')) ? 'checked' : '' }}> OC</label>
                        <label style="display:inline-flex;align-items:center;gap:6px;font-size:14px;"><input type="checkbox" name="assigned_apps[]" value="viajes" {{ is_array(old('assigned_apps', ['viajes'])) && in_array('viajes', old('assigned_apps', ['viajes'])) ? 'checked' : '' }}> Viajes</label>
                        <label style="display:inline-flex;align-items:center;gap:6px;font-size:14px;"><input type="checkbox" name="assigned_apps[]" value="rendicion" {{ is_array(old('assigned_apps')) && in_array('rendicion', old('assigned_apps')) ? 'checked' : '' }}> Rendición</label>
                    </div>
                </div>
            </div>
            <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:28px;">
                <button type="button" onclick="document.getElementById('modalCrear').style.display='none'"
                    style="background:#f1f5f9;color:var(--ink);border:none;padding:10px 20px;border-radius:10px;font-weight:600;cursor:pointer;">
                    Cancelar
                </button>
                <button type="submit" class="btn-primary">Crear Usuario</button>
            </div>
        </form>
    </div>
</div>

<!-- ── MODAL EDITAR USUARIO ──────────────────────────── -->
<div id="modalEditar" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); backdrop-filter:blur(4px); z-index:2000; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:20px; padding:36px; width:100%; max-width:500px; box-shadow:0 25px 60px rgba(0,0,0,0.2); position:relative;">
        <button onclick="document.getElementById('modalEditar').style.display='none'" style="position:absolute;top:16px;right:16px;background:none;border:none;cursor:pointer;color:var(--muted);">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:22px;height:22px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <h3 style="font-size:20px;font-weight:700;margin:0 0 24px;">Editar Usuario</h3>
        <form id="formEditar" action="" method="POST">
            @csrf @method('PUT')
            <div style="display:grid;gap:16px;">
                <div class="form-field">
                    <label class="form-label">Nombre completo</label>
                    <input type="text" id="editNombre" name="name" class="form-control" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" id="editEmail" name="email" class="form-control" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Rol</label>
                    <select id="editRol" name="rol" class="form-select" required>
                        @if(Auth::check() && Auth::user()->rol === 'super_admin')
                        <option value="super_admin">Super Administrador</option>
                        @endif
                        <option value="admin">Administrador</option>
                        <option value="aprobador">Aprobador</option>
                        <option value="gestor">Gestor</option>
                        <option value="usuario">Usuario</option>
                    </select>
                </div>
                <div class="form-field">
                    <label class="form-label">Nueva contraseña <span style="color:var(--muted);font-weight:400;">(opcional)</span></label>
                    <input type="password" name="password" class="form-control" placeholder="Dejar vacío para no cambiar">
                </div>
                <div class="form-field">
                    <label class="form-label">Confirmar nueva contraseña</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
                <div class="form-field">
                    <label class="form-label">Páginas asignadas</label>
                    <div style="display:flex;gap:10px;flex-wrap:wrap;">
                        <label style="display:inline-flex;align-items:center;gap:6px;font-size:14px;"><input type="checkbox" name="assigned_apps[]" value="oc" {{ is_array(old('assigned_apps')) && in_array('oc', old('assigned_apps')) ? 'checked' : '' }}> OC</label>
                        <label style="display:inline-flex;align-items:center;gap:6px;font-size:14px;"><input type="checkbox" name="assigned_apps[]" value="viajes" {{ is_array(old('assigned_apps')) && in_array('viajes', old('assigned_apps')) ? 'checked' : '' }}> Viajes</label>
                        <label style="display:inline-flex;align-items:center;gap:6px;font-size:14px;"><input type="checkbox" name="assigned_apps[]" value="rendicion" {{ is_array(old('assigned_apps')) && in_array('rendicion', old('assigned_apps')) ? 'checked' : '' }}> Rendición</label>
                    </div>
                </div>
            </div>
            <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:28px;">
                <button type="button" onclick="document.getElementById('modalEditar').style.display='none'"
                    style="background:#f1f5f9;color:var(--ink);border:none;padding:10px 20px;border-radius:10px;font-weight:600;cursor:pointer;">
                    Cancelar
                </button>
                <button type="submit" class="btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<script>
function abrirEditar(id, nombre, email, rol, assignedApps) {
    document.getElementById('editNombre').value = nombre;
    document.getElementById('editEmail').value = email;
    document.getElementById('editRol').value = rol;
    document.getElementById('formEditar').action = '{{ url('viajes/usuarios') }}/' + id;
    document.querySelectorAll('#formEditar input[name="assigned_apps[]"]').forEach(input => {
        input.checked = Array.isArray(assignedApps) && assignedApps.includes(input.value);
    });
    document.getElementById('modalEditar').style.display = 'flex';
}
// Cerrar modal al hacer clic en el fondo
['modalCrear','modalEditar'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) this.style.display = 'none';
    });
});
</script>

@endsection
