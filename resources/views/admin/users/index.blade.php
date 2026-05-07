<x-app-layout>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
        <style>
            :root {
                --primary: #2563eb;
                --primary-hover: #1d4ed8;
                --bg-page: #f1f5f9;
                --card-bg: rgba(255, 255, 255, 0.95);
                --text-main: #0f172a;
                --text-muted: #64748b;
                --border-color: #e2e8f0;
            }

            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
                background-color: var(--bg-page);
                color: var(--text-main);
            }

            .dashboard-container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 40px 20px;
            }

            .glass-header {
                background: linear-gradient(135deg, #0b5fa5 0%, #0f6bb6 100%);
                border-radius: 24px;
                padding: 32px;
                margin-bottom: 32px;
                box-shadow: 0 10px 25px rgba(15, 107, 182, 0.15);
                border: 1px solid rgba(255, 255, 255, 0.1);
                display: flex;
                justify-content: space-between;
                align-items: center;
                color: white;
                position: relative;
                overflow: hidden;
            }

            .glass-header::after {
                content: '';
                position: absolute;
                top: -50%;
                right: -10%;
                width: 300px;
                height: 300px;
                background: rgba(255, 255, 255, 0.05);
                border-radius: 50%;
                pointer-events: none;
                z-index: 1;
            }

            .page-title {
                font-family: 'Space Grotesk', sans-serif;
                font-size: 2rem;
                font-weight: 700;
                color: white;
                letter-spacing: -0.02em;
                margin: 0;
            }

            .btn-action {
                background: white;
                color: #0f6bb6;
                font-weight: 800;
                padding: 12px 24px;
                border-radius: 12px;
                transition: all 0.2s;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                border: none;
                cursor: pointer;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                text-transform: uppercase;
                font-size: 0.75rem;
                letter-spacing: 0.05em;
                position: relative;
                z-index: 10;
            }

            .btn-action:hover {
                background: #f8fafc;
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            }

            .btn-action:hover {
                background: var(--primary-hover);
                transform: translateY(-1px);
                box-shadow: 0 20px 25px -5px rgba(37, 99, 235, 0.2);
            }

            .main-card {
                background: white;
                border-radius: 24px;
                border: 1px solid var(--border-color);
                box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.05);
                overflow: hidden;
            }

            .table-sofofa {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
            }

            .table-sofofa th {
                background: #f8fafc;
                padding: 16px 24px;
                font-weight: 700;
                font-size: 0.75rem;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: var(--text-muted);
                border-bottom: 1px solid var(--border-color);
                text-align: left;
            }

            .table-sofofa td {
                padding: 20px 24px;
                border-bottom: 1px solid #f1f5f9;
                font-size: 0.875rem;
                vertical-align: middle;
            }

            .user-info {
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .user-initials {
                width: 40px;
                height: 40px;
                border-radius: 10px;
                background: #eff6ff;
                color: var(--primary);
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 700;
                font-size: 0.9rem;
            }

            .badge-custom {
                padding: 6px 12px;
                border-radius: 8px;
                font-size: 0.75rem;
                font-weight: 600;
                display: inline-flex;
                align-items: center;
                gap: 6px;
            }

            .badge-role { background: #f1f5f9; color: #475569; }
            .badge-app { background: #e0e7ff; color: #4338ca; }

            .btn-icon {
                width: 36px;
                height: 36px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.2s;
                border: 1px solid var(--border-color);
                background: white;
                color: var(--text-muted);
                cursor: pointer;
            }

            .btn-icon:hover {
                border-color: var(--primary);
                color: var(--primary);
                background: #eff6ff;
            }

            .btn-delete:hover {
                border-color: #ef4444;
                color: #ef4444;
                background: #fef2f2;
            }

            /* Modal Enhancements */
            .modal-backdrop {
                background: rgba(15, 23, 42, 0.5);
                backdrop-filter: blur(8px);
            }

            .modal-premium {
                background: white;
                border-radius: 28px;
                max-width: 520px;
                width: 100%;
                overflow: hidden;
                box-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.25);
            }

            .modal-header-premium {
                background: #0f172a;
                padding: 20px 32px;
                color: white;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .modal-body-premium {
                padding: 24px 32px;
            }

            .input-group {
                margin-bottom: 16px;
            }

            .label-premium {
                display: block;
                font-weight: 700;
                font-size: 0.75rem;
                color: var(--text-main);
                text-transform: uppercase;
                letter-spacing: 0.025em;
                margin-bottom: 8px;
            }

            .input-premium {
                width: 100%;
                padding: 14px 16px;
                border-radius: 12px;
                border: 2px solid #e2e8f0;
                background: #f8fafc;
                font-size: 0.95rem;
                font-weight: 500;
                color: var(--text-main);
                transition: all 0.2s;
            }

            .input-premium:focus {
                border-color: var(--primary);
                background: white;
                outline: none;
                box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
            }

            .checkbox-tile {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 10px 16px;
                background: #f8fafc;
                border: 2px solid #e2e8f0;
                border-radius: 12px;
                cursor: pointer;
                transition: all 0.2s;
            }

            .checkbox-tile:hover {
                border-color: #cbd5e1;
            }

            .checkbox-tile.active {
                background: #eff6ff;
                border-color: var(--primary);
            }

            .custom-check {
                width: 20px;
                height: 20px;
                border: 2px solid #cbd5e1;
                border-radius: 6px;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.2s;
            }

            .active .custom-check {
                background: var(--primary);
                border-color: var(--primary);
            }

            .active .custom-check i {
                display: block;
                color: white;
                font-size: 10px;
            }

            .custom-check i { display: none; }

            .modal-footer-premium {
                padding: 16px 32px 24px;
                display: flex;
                justify-content: flex-end;
                gap: 16px;
                border-top: 1px solid #f1f5f9;
            }

            .btn-cancel-premium {
                padding: 12px 24px;
                font-weight: 600;
                color: var(--text-muted);
                cursor: pointer;
                border-radius: 12px;
                transition: background 0.2s;
            }

            .btn-cancel-premium:hover {
                background: #f1f5f9;
                color: var(--text-main);
            }
        </style>
    </head>

    <div class="dashboard-container">
        <!-- Header Section -->
        <div class="glass-header">
            <div>
                <h1 class="page-title">Gestión de Usuarios</h1>
                <p style="color: var(--text-muted); margin-top: 4px; font-weight: 500;">Administra el acceso global a todas tus plataformas.</p>
            </div>
            <button type="button" onclick="openModal('create')" class="btn-action">
                <i class="fas fa-plus"></i>
                <span>Nuevo Usuario</span>
            </button>
        </div>

        @if(session('success'))
            <div style="background: #dcfce7; border: 1px solid #bbf7d0; color: #166534; padding: 16px; border-radius: 16px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; font-weight: 600;">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 16px; border-radius: 16px; margin-bottom: 24px; display: flex; align-items: flex-start; gap: 12px; font-weight: 600;">
                <i class="fas fa-exclamation-circle" style="margin-top: 4px;"></i>
                <ul style="margin: 0; padding: 0; list-style: none;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Filters Bar -->
        <div style="background: white; border-radius: 20px; padding: 20px; margin-bottom: 24px; border: 1px solid var(--border-color); box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05);">
            <form action="{{ route('admin.users.index') }}" method="GET" style="display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 200px;">
                    <label style="display: block; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.05em;">Buscar por Correo</label>
                    <div style="position: relative;">
                        <i class="fas fa-search" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 0.9rem;"></i>
                        <input type="text" name="search_email" value="{{ request('search_email') }}" placeholder="Ej: usuario@empresa.cl" style="width: 100%; padding: 12px 12px 12px 42px; border: 1.5px solid var(--border-color); border-radius: 12px; font-size: 0.875rem; font-weight: 600; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border-color)'">
                    </div>
                </div>
                <div style="width: 250px;">
                    <label style="display: block; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.05em;">Filtrar por Sistema</label>
                    <div style="position: relative;">
                        <select name="filter_app" onchange="this.form.submit()" style="width: 100%; padding: 12px 16px; border: 1.5px solid var(--border-color); border-radius: 12px; font-size: 0.875rem; font-weight: 600; appearance: none; background: #fff url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236B7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E') no-repeat right 0.75rem center; background-size: 1.25rem 1.25rem; outline: none;">
                            <option value="">Todos los sistemas</option>
                            <option value="oc" {{ request('filter_app') === 'oc' ? 'selected' : '' }}>OC (Compras)</option>
                            <option value="viajes" {{ request('filter_app') === 'viajes' ? 'selected' : '' }}>Viajes</option>
                            <option value="rendicion" {{ request('filter_app') === 'rendicion' ? 'selected' : '' }}>Rendición</option>
                        </select>
                    </div>
                </div>
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn-action" style="height: 46px; width: 46px; justify-content: center; padding: 0;">
                        <i class="fas fa-filter"></i>
                    </button>
                    @if(request()->anyFilled(['search_email', 'filter_app']))
                        <a href="{{ route('admin.users.index') }}" class="btn-icon btn-delete" style="height: 46px; width: 46px; border-radius: 12px; display: flex; text-decoration: none;" title="Limpiar filtros">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="main-card">
            <table class="table-sofofa">
                <thead>
                    <tr>
                        <th>Identidad</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th>Sistemas</th>
                        <th style="text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <div class="user-info">
                                <div class="user-initials">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                <div style="font-weight: 600; color: var(--text-main);">{{ $user->name }}</div>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 500; color: var(--text-muted);">{{ $user->email }}</div>
                        </td>
                        <td>
                            <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                @php
                                    $getRoleStyles = function($roleName) {
                                        $name = strtolower($roleName);
                                        if (str_contains($name, 'super')) {
                                            return ['bg' => '#1e1b4b', 'color' => '#c7d2fe', 'icon' => 'fa-crown', 'border' => '#312e81'];
                                        }
                                        if (str_contains($name, 'admin')) {
                                            return ['bg' => '#eff6ff', 'color' => '#1d4ed8', 'icon' => 'fa-user-shield', 'border' => '#dbeafe'];
                                        }
                                        if (str_contains($name, 'aprobador')) {
                                            return ['bg' => '#f0fdf4', 'color' => '#15803d', 'icon' => 'fa-check-double', 'border' => '#dcfce7'];
                                        }
                                        if (str_contains($name, 'gestor')) {
                                            return ['bg' => '#fffbeb', 'color' => '#b45309', 'icon' => 'fa-clipboard-list', 'border' => '#fef3c7'];
                                        }
                                        if (str_contains($name, 'usuario')) {
                                            return ['bg' => '#faf5ff', 'color' => '#7e22ce', 'icon' => 'fa-user', 'border' => '#f3e8ff'];
                                        }
                                        if (str_contains($name, 'cliente')) {
                                            return ['bg' => '#f8fafc', 'color' => '#475569', 'icon' => 'fa-user-tie', 'border' => '#e2e8f0'];
                                        }
                                        return ['bg' => '#f1f5f9', 'color' => '#475569', 'icon' => 'fa-tag', 'border' => '#e2e8f0'];
                                    };
                                @endphp

                                {{-- Roles de Spatie --}}
                                @foreach($user->roles as $role)
                                    @php $styles = $getRoleStyles($role->name); @endphp
                                    <span class="badge-custom" style="background: {{ $styles['bg'] }}; color: {{ $styles['color'] }}; border: 1px solid {{ $styles['border'] }};">
                                        <i class="fas {{ $styles['icon'] }}" style="font-size: 0.65rem; opacity: 0.7;"></i>
                                        {{ $role->name }}
                                    </span>
                                @endforeach

                                {{-- Fallback a roles legacy si no tiene roles de Spatie --}}
                                @if($user->roles->isEmpty())
                                    @php
                                        $legacyRol = $user->rol ?: $user->role;
                                        $rolLabel = match(strtolower($legacyRol)) {
                                            'super_admin', 'superadmin' => 'Superadmin',
                                            'admin' => 'Admin',
                                            'aprobador' => 'Aprobador',
                                            'gestor' => 'Gestor',
                                            'usuario' => 'Usuario',
                                            'cliente' => 'Cliente',
                                            default => ucfirst($legacyRol)
                                        };
                                        $styles = $getRoleStyles($legacyRol);
                                    @endphp
                                    @if($legacyRol)
                                        <span class="badge-custom" style="background: {{ $styles['bg'] }}; color: {{ $styles['color'] }}; border: 1px solid {{ $styles['border'] }};">
                                            <i class="fas {{ $styles['icon'] }}" style="font-size: 0.65rem; opacity: 0.7;"></i>
                                            {{ $rolLabel }}
                                        </span>
                                    @endif
                                @endif
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                @php 
                                    $apps = $user->assigned_apps;
                                    if (is_string($apps)) {
                                        $decoded = json_decode($apps, true);
                                        $apps = is_array($decoded) ? $decoded : ($apps ? explode(',', $apps) : []);
                                    }
                                    $apps = $apps ?? [];
                                @endphp
                                @foreach($apps as $app)
                                    <span class="badge-custom badge-app">{{ strtoupper($app) }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                                <button onclick='editUser(@json($user))' class="btn-icon" title="Editar">
                                    <i class="fas fa-pen-to-square"></i>
                                </button>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('¿Eliminar usuario?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-icon btn-delete" title="Eliminar">
                                            <i class="fas fa-trash-can"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="padding: 24px; border-top: 1px solid #f1f5f9;">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <!-- Modal System -->
    <div id="userModal" class="fixed inset-0 modal-backdrop z-50 flex items-center justify-center p-4" style="display: none;">
        <div class="modal-premium" id="modalContainer">
            <div class="modal-header-premium">
                <div>
                    <h3 id="modalTitle" style="font-size: 1.5rem; font-weight: 700; font-family: 'Space Grotesk';">Nuevo Usuario</h3>
                    <p id="modalSubtitle" style="font-size: 0.85rem; color: #94a3b8; margin-top: 2px;">Crea una cuenta con acceso global.</p>
                </div>
                <button onclick="closeModal()" style="color: #94a3b8; cursor: pointer;" class="hover:text-white transition-colors">
                    <i class="fas fa-times fa-lg"></i>
                </button>
            </div>

            <form id="userForm" method="POST" action="{{ route('admin.users.store') }}" onsubmit="return validateForm()">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                
                <div class="modal-body-premium">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="input-group">
                            <label class="label-premium">Nombre Completo</label>
                            <input type="text" name="name" id="userName" required class="input-premium" placeholder="Ingrese nombre y apellido">
                        </div>
                        <div class="input-group">
                            <label class="label-premium">Correo Electrónico</label>
                            <input type="email" name="email" id="userEmail" required class="input-premium" placeholder="ejemplo@test.com">
                        </div>
                    </div>

                    <div class="input-group">
                        <label class="label-premium">Contraseña</label>
                        <input type="password" name="password" id="userPassword" class="input-premium" placeholder="••••••••••••">
                        <p id="passwordHint" style="font-size: 0.75rem; color: var(--primary); font-weight: 600; margin-top: 6px;" class="hidden">
                            <i class="fas fa-info-circle"></i> Dejar en blanco para mantener la actual.
                        </p>
                    </div>

                    <div class="input-group">
                        <label class="label-premium">Nivel de Acceso (Rol)</label>
                        <select name="role_name" id="userRole" required class="input-premium appearance-none">
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="input-group">
                        <label class="label-premium">Sistemas Habilitados</label>
                        <div class="grid grid-cols-3 gap-4 mt-2">
                            <label class="checkbox-tile" id="tile-oc">
                                <input type="checkbox" name="assigned_apps[]" value="oc" class="hidden" onchange="updateTile(this, 'tile-oc')">
                                <div class="custom-check"><i class="fas fa-check"></i></div>
                                <span style="font-size: 0.75rem; font-weight: 700;">OC</span>
                            </label>
                            <label class="checkbox-tile" id="tile-viajes">
                                <input type="checkbox" name="assigned_apps[]" value="viajes" class="hidden" onchange="updateTile(this, 'tile-viajes')">
                                <div class="custom-check"><i class="fas fa-check"></i></div>
                                <span style="font-size: 0.75rem; font-weight: 700;">VIAJES</span>
                            </label>
                            <label class="checkbox-tile" id="tile-rendicion">
                                <input type="checkbox" name="assigned_apps[]" value="rendicion" class="hidden" onchange="updateTile(this, 'tile-rendicion')">
                                <div class="custom-check"><i class="fas fa-check"></i></div>
                                <span style="font-size: 0.75rem; font-weight: 700;">RENDICIÓN</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer-premium">
                    <button type="button" onclick="closeModal()" class="btn-cancel-premium">CANCELAR</button>
                    <button type="submit" class="btn-action" style="padding: 14px 32px;">
                        <i class="fas fa-save"></i>
                        <span>GUARDAR CAMBIOS</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function updateTile(checkbox, tileId) {
            const tile = document.getElementById(tileId);
            if (checkbox.checked) {
                tile.classList.add('active');
            } else {
                tile.classList.remove('active');
            }
        }

        function validateForm() {
            const checkboxes = document.querySelectorAll('input[name="assigned_apps[]"]:checked');
            if (checkboxes.length === 0) {
                alert('¡Atención! Debes habilitar al menos un sistema (OC, VIAJES o RENDICIÓN) para el usuario.');
                return false;
            }
            return true;
        }

        function openModal(mode, user = null) {
            const modal = document.getElementById('userModal');
            const title = document.getElementById('modalTitle');
            const subtitle = document.getElementById('modalSubtitle');
            const form = document.getElementById('userForm');
            const method = document.getElementById('formMethod');
            const passwordHint = document.getElementById('passwordHint');
            const passwordInput = document.getElementById('userPassword');

            modal.style.display = 'flex';

            if (mode === 'create') {
                title.innerText = 'Nuevo Usuario';
                subtitle.innerText = 'Crea una cuenta con acceso global.';
                form.action = "{{ route('admin.users.store') }}";
                method.value = 'POST';
                form.reset();
                passwordInput.required = true;
                passwordHint.classList.add('hidden');
                
                // Reset tiles
                document.querySelectorAll('.checkbox-tile').forEach(t => t.classList.remove('active'));
            } else {
                title.innerText = 'Editar Usuario';
                subtitle.innerText = `Actualizando perfil de ${user.name}`;
                form.action = `/admin/usuarios/${user.id}`;
                method.value = 'PUT';
                passwordInput.required = false;
                passwordHint.classList.remove('hidden');
                
                document.getElementById('userName').value = user.name;
                document.getElementById('userEmail').value = user.email;
                let userRole = user.roles && user.roles[0] ? user.roles[0].name : '';
                if (!userRole) {
                    userRole = user.rol || user.role || '';
                    // Normalizar para que coincida con las opciones del select (ej: super_admin -> Superadmin)
                    if (userRole.toLowerCase().includes('super')) userRole = 'Superadmin';
                    else if (userRole.toLowerCase() === 'admin') userRole = 'Admin';
                    else if (userRole.toLowerCase() === 'aprobador') userRole = 'Aprobador';
                    else if (userRole.toLowerCase() === 'gestor') userRole = 'Gestor';
                    else if (userRole.toLowerCase() === 'usuario') userRole = 'Usuario';
                    else if (userRole.toLowerCase() === 'cliente') userRole = 'Cliente';
                }
                document.getElementById('userRole').value = userRole;
                
                // Set checkboxes and tiles
                const apps = user.assigned_apps;
                let appList = [];
                if (Array.isArray(apps)) appList = apps;
                else if (typeof apps === 'string') {
                    try { appList = JSON.parse(apps); } catch(e) { appList = apps.split(','); }
                }
                
                document.querySelectorAll('input[name="assigned_apps[]"]').forEach(cb => {
                    const val = cb.value;
                    cb.checked = appList.includes(val);
                    const tile = document.getElementById(`tile-${val}`);
                    if (cb.checked) tile.classList.add('active');
                    else tile.classList.remove('active');
                });
            }
        }

        function closeModal() {
            document.getElementById('userModal').style.display = 'none';
        }

        function editUser(user) {
            openModal('edit', user);
        }
    </script>
</x-app-layout>
