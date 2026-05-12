<x-rendicion.layouts.app
    page_title="Gestión de Usuarios"
    page_subtitle="Administra los accesos y roles de la plataforma institucional.">
    @if($errors->any())
        <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-800">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="mb-6 flex justify-end">
        <button onclick="openModal()" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 px-4 py-2.5 text-xs font-black text-white transition-all shadow-lg shadow-blue-500/20 hover:shadow-blue-500/40 hover:-translate-y-0.5 active:scale-95 uppercase tracking-widest whitespace-nowrap">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
            Nuevo Usuario
        </button>
    </div>

    <style>
        .user-modal-wrap {
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 12px;
        }

        .user-modal-panel {
            width: min(680px, calc(100vw - 2rem));
            max-height: calc(100vh - 90px);
            overflow: auto;
            border-radius: 22px;
            border: 1px solid #dbe3ef;
            background: #ffffff;
            box-shadow: 0 32px 80px -28px rgba(15, 23, 42, 0.45);
        }

        .user-modal-head {
            background: linear-gradient(135deg, #0b5fa5 0%, #0f6bb6 100%);
            padding: 1rem 1.1rem 0.86rem;
        }

        .user-modal-title {
            margin: 0;
            font-size: 1.2rem;
            line-height: 1.1;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #ffffff;
        }

        .user-modal-subtitle {
            margin: 0.25rem 0 0;
            font-size: 0.72rem;
            color: rgba(219, 234, 254, 0.95);
            font-weight: 500;
        }

        .user-modal-form {
            padding: 1rem 1.1rem 0.95rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.62rem 0.8rem;
        }

        .user-field {
            display: grid;
            gap: 0.34rem;
        }

        .user-field-full {
            grid-column: 1 / -1;
        }

        .user-label {
            display: block;
            margin: 0;
            font-size: 0.64rem;
            font-weight: 800;
            letter-spacing: 0.11em;
            text-transform: uppercase;
            color: #5c6a7e;
        }

        .user-note {
            margin: 0.1rem 0 0;
            font-size: 0.72rem;
            color: #6c7b8f;
        }

        .user-input {
            width: 100%;
            border: 1.5px solid #d5deea;
            border-radius: 14px;
            background: #fbfdff;
            color: #1f2937;
            padding: 0.56rem 0.74rem;
            font-size: 0.86rem;
            font-weight: 600;
            outline: none;
            transition: all 140ms ease;
        }

        .user-input:focus {
            border-color: #0f6bb6;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(15, 107, 182, 0.14);
        }

        .user-modal-actions {
            grid-column: 1 / -1;
            margin-top: 0.1rem;
            display: grid;
            grid-template-columns: 1fr 1.3fr;
            gap: 0.6rem;
        }

        #password-section.hidden {
            display: none;
        }

        #fixed-fund-amount-section.hidden {
            display: none;
        }

        .user-cancel-btn {
            border: 1px solid #dbe3ef;
            border-radius: 12px;
            background: #f8fafc;
            color: #44556c;
            font-weight: 800;
            font-size: 0.9rem;
            padding: 0.62rem 0.8rem;
            cursor: pointer;
            transition: all 140ms ease;
        }

        .user-cancel-btn:hover {
            background: #eef2f7;
        }

        .user-save-btn {
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #0b5fa5 0%, #0f6bb6 100%);
            color: #ffffff;
            font-weight: 800;
            font-size: 0.9rem;
            padding: 0.62rem 0.8rem;
            box-shadow: 0 12px 24px -14px rgba(15, 107, 182, 0.85);
            cursor: pointer;
            transition: all 140ms ease;
        }

        .user-save-btn:hover {
            background: linear-gradient(135deg, #0a4f86 0%, #0b5fa5 100%);
            transform: translateY(-1px);
        }

        @media (max-width: 640px) {
            .user-modal-wrap {
                align-items: flex-start;
                padding: 12px;
            }

            .user-modal-form {
                grid-template-columns: 1fr;
                padding: 1rem;
                gap: 0.72rem;
            }

            .user-modal-head {
                padding: 1rem;
            }

            .user-modal-panel {
                max-height: calc(100vh - 18px);
                overflow: auto;
            }

            .user-modal-actions {
                grid-template-columns: 1fr;
            }
        }
    </style>


    <div class="card-main rounded-[2rem] border border-slate-100 shadow-[0_20px_50px_-20px_rgba(0,0,0,0.1)] overflow-hidden bg-white">
        <div class="overflow-x-auto">
            <table class="table-sofofa w-full border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">Usuario</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">Email</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">Roles</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">Fondo Fijo</th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50/80 transition-all duration-200 group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                            @php
                                    $parts = explode(' ', $user->name);
                                    $initials = mb_strtoupper(mb_substr($parts[0], 0, 1) . (isset($parts[1]) ? mb_substr($parts[1], 0, 1) : ''));
                                    $palettes = [
                                        ['bg'=>'#eff6ff','text'=>'#1d4ed8','border'=>'#bfdbfe'],
                                        ['bg'=>'#f5f3ff','text'=>'#6d28d9','border'=>'#ddd6fe'],
                                        ['bg'=>'#ecfdf5','text'=>'#065f46','border'=>'#a7f3d0'],
                                        ['bg'=>'#fff7ed','text'=>'#c2410c','border'=>'#fed7aa'],
                                        ['bg'=>'#fdf4ff','text'=>'#86198f','border'=>'#f0abfc'],
                                    ];
                                    $p = $palettes[$user->id % count($palettes)];
                                @endphp
                                <div style="height:2.5rem;width:2.5rem;min-width:2.5rem;border-radius:50%;background:{{ $p['bg'] }};color:{{ $p['text'] }};border:1px solid {{ $p['border'] }};display:flex;align-items:center;justify-content:center;font-weight:800;font-size:0.75rem;flex-shrink:0;box-shadow:0 2px 6px -2px {{ $p['border'] }};">
                                    {{ $initials }}
                                </div>
                                <div>
                                    <span class="block text-[15px] font-black text-slate-800 leading-tight">{{ $user->name }}</span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">ID: #{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-[13px] font-bold text-slate-500 bg-slate-50 px-3 py-1 rounded-lg border border-slate-100">{{ $user->email }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-wrap gap-2">
                                @foreach($user->roles as $role)
                                @php
                                    $rp = match(strtolower($role->name)) {
                                        'superadmin' => ['bg'=>'#eef2ff','text'=>'#4338ca','border'=>'#c7d2fe'],
                                        'admin'      => ['bg'=>'#eff6ff','text'=>'#1d4ed8','border'=>'#bfdbfe'],
                                        'aprobador'  => ['bg'=>'#fffbeb','text'=>'#b45309','border'=>'#fde68a'],
                                        'gestor'     => ['bg'=>'#f0fdfa','text'=>'#0f766e','border'=>'#99f6e4'],
                                        default      => ['bg'=>'#f8fafc','text'=>'#475569','border'=>'#e2e8f0'],
                                    };
                                @endphp
                                <span style="display:inline-flex;align-items:center;border-radius:0.5rem;padding:0.2rem 0.65rem;font-size:0.625rem;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;background:{{ $rp['bg'] }};color:{{ $rp['text'] }};border:1px solid {{ $rp['border'] }};">
                                    {{ $role->name }}
                                </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            @if($user->has_fixed_fund)
                                <div class="inline-flex items-center rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-1 text-[11px] font-bold text-emerald-700">
                                    Con fondo fijo: ${{ number_format((float) $user->fixed_fund_amount, 0, ',', '.') }}
                                </div>
                            @else
                                <div class="inline-flex items-center rounded-lg border border-slate-200 bg-slate-50 px-3 py-1 text-[11px] font-bold text-slate-500">
                                    Sin fondo fijo
                                </div>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-right">
                            @php
                                $actor = auth()->user();
                                $canManage = $actor->hasRole('Superadmin') || ($actor->hasRole('Admin') && !$user->hasAnyRole(['Superadmin', 'Admin']));
                                $canDelete = $canManage && $user->id !== $actor->id;
                            @endphp
                            <div class="inline-flex items-center gap-2">
                                @if($canManage)
                                    <button type="button" onclick='editUser(@json($user))' class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-400 hover:text-sofofa-blue hover:bg-blue-50 hover:border-sofofa-blue/30 hover:scale-110 active:scale-95 transition-all shadow-sm" title="Editar usuario">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                @endif

                                @if($canDelete)
                                    <form method="POST" action="{{ route('rendicion.users.destroy', $user) }}" class="inline-flex" onsubmit="return confirm('¿Seguro que deseas eliminar al usuario {{ addslashes($user->name) }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-rose-200 bg-rose-50 text-rose-500 hover:bg-rose-100 hover:text-rose-700 hover:border-rose-300 hover:scale-110 active:scale-95 transition-all shadow-sm" title="Eliminar usuario">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-7 0h8"/></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-16 text-center">
                            <div class="flex flex-col items-center gap-3 grayscale opacity-40">
                                <svg class="h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <p class="text-slate-400 font-bold uppercase tracking-[0.2em] text-[11px]">No hay usuarios registrados</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div id="user-modal" class="user-modal-wrap hidden fixed flex">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal()"></div>
        <div class="user-modal-panel relative z-10">
            <div class="user-modal-head">
                <h3 id="modal-title" class="user-modal-title">Crear Usuario</h3>
                <p id="modal-subtitle" class="user-modal-subtitle">Completa los datos del nuevo integrante.</p>
            </div>
            
            <form id="user-form" action="{{ route('rendicion.users.store') }}" method="POST" class="user-modal-form">
                @csrf
                <input type="hidden" name="_method" id="form-method" value="POST">
                <input type="hidden" name="editing_user_id" id="editing-user-id" value="{{ old('editing_user_id') }}">

                @if($errors->any())
                    <div style="background: #fef2f2; color: #dc2626; padding: 12px; border-radius: 8px; font-size: 14px; margin-bottom: 16px; border: 1px solid #fee2e2;">
                        {{ $errors->first() }}
                    </div>
                @endif
                
                <div class="user-field">
                    <label class="user-label" for="user-name">Nombre Completo</label>
                    <input type="text" name="name" id="user-name" value="{{ old('name') }}" required class="user-input" placeholder="Ej: Juan Perez">
                </div>
                
                <div class="user-field">
                    <label class="user-label" for="user-email">Correo Electronico</label>
                    <input type="email" name="email" id="user-email" value="{{ old('email') }}" required class="user-input" placeholder="juan@sofofa.cl">
                </div>
                


                <div id="password-section" class="user-field">
                    <label class="user-label" for="user-password">Contrasena</label>
                    <input type="password" name="password" id="user-password" class="user-input" placeholder="Minimo 8 caracteres">
                    <p class="user-note">Dejar en blanco para mantener la actual en edicion.</p>
                </div>
                
                <div class="user-field">
                    <label class="user-label" for="user-role">Rol Asignado</label>
                    <select name="role" id="user-role" required class="user-input appearance-none bg-[url('rendicion/data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236B7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25rem_1.25rem] bg-[right_0.75rem_center] bg-no-repeat pr-10">
                        @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>



                <div class="user-field">
                    <label class="user-label" for="user-has-fixed-fund">Tipo de Fondo</label>
                    <select name="has_fixed_fund" id="user-has-fixed-fund" required onchange="toggleFixedFundAmount()" class="user-input appearance-none bg-[url('rendicion/data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236B7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25rem_1.25rem] bg-[right_0.75rem_center] bg-no-repeat pr-10">
                        <option value="0" {{ old('has_fixed_fund', '0') === '0' ? 'selected' : '' }}>Sin fondo fijo</option>
                        <option value="1" {{ old('has_fixed_fund') === '1' ? 'selected' : '' }}>Con fondo fijo</option>
                    </select>
                    @error('has_fixed_fund') <p class="user-note" style="color:#c81e1e;">{{ $message }}</p> @enderror
                </div>

                <div id="fixed-fund-amount-section" class="user-field hidden">
                    <label class="user-label" for="user-fixed-fund-amount">Monto Fondo Fijo</label>
                    <input type="text" name="fixed_fund_amount" id="user-fixed-fund-amount" value="{{ old('fixed_fund_amount') }}" inputmode="numeric" class="user-input" placeholder="Ej: 150.000" oninput="formatFixedFundAmount(this)">
                    @error('fixed_fund_amount') <p class="user-note" style="color:#c81e1e;">{{ $message }}</p> @enderror
                </div>

                <div class="user-modal-actions">
                    <button type="button" onclick="closeModal()" class="user-cancel-btn">Cancelar</button>
                    <button type="submit" id="submit-btn" class="user-save-btn">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function formatFixedFundAmount(input) {
            const digits = input.value.replace(/\D/g, '');

            if (!digits) {
                input.value = '';
                return;
            }

            input.value = new Intl.NumberFormat('es-CL').format(Number(digits));
        }

        function normalizeFixedFundAmount(value) {
            return value.replace(/\./g, '').replace(/,/g, '.').trim();
        }

        function toggleFixedFundAmount() {
            const hasFixedFund = document.getElementById('user-has-fixed-fund').value === '1';
            const amountSection = document.getElementById('fixed-fund-amount-section');
            const amountInput = document.getElementById('user-fixed-fund-amount');

            if (hasFixedFund) {
                amountSection.classList.remove('hidden');
                amountInput.required = true;
            } else {
                amountSection.classList.add('hidden');
                amountInput.required = false;
                amountInput.value = '';
            }
        }

        function openModal() {
            document.getElementById('modal-title').innerText = 'Crear Usuario';
            document.getElementById('modal-subtitle').innerText = 'Completa los datos del nuevo integrante.';
            document.getElementById('user-form').action = "{{ route('rendicion.users.store') }}";
            document.getElementById('form-method').value = 'POST';
            document.getElementById('editing-user-id').value = '';
            document.getElementById('user-name').value = '';
            document.getElementById('user-email').value = '';
            document.getElementById('user-password').value = '';
            document.getElementById('user-password').required = true;
            document.getElementById('password-section').classList.remove('hidden');
            document.getElementById('user-role').selectedIndex = 0;

            document.getElementById('user-has-fixed-fund').value = '0';
            document.getElementById('user-fixed-fund-amount').value = '';
            toggleFixedFundAmount();
            document.getElementById('submit-btn').innerText = 'Crear Usuario';
            document.getElementById('user-modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('user-modal').classList.add('hidden');
        }

        function editUser(user) {
            document.getElementById('modal-title').innerText = 'Editar Usuario';
            document.getElementById('modal-subtitle').innerText = 'Actualiza los datos del usuario seleccionado.';
            document.getElementById('user-form').action = `/rendicion/usuarios/${user.id}`;
            document.getElementById('form-method').value = 'PUT';
            document.getElementById('editing-user-id').value = user.id;
            document.getElementById('user-name').value = user.name;
            document.getElementById('user-email').value = user.email;
            document.getElementById('user-password').value = '';
            document.getElementById('user-password').required = false;
            document.getElementById('password-section').classList.remove('hidden');
            document.getElementById('user-role').value = user.roles[0] ? user.roles[0].name : '';

            const assignedApps = Array.isArray(user.assigned_apps)
                ? user.assigned_apps
                : (user.assigned_app ? [user.assigned_app] : []);



            document.getElementById('user-has-fixed-fund').value = user.has_fixed_fund ? '1' : '0';
            document.getElementById('user-fixed-fund-amount').value = user.fixed_fund_amount
                ? new Intl.NumberFormat('es-CL').format(Math.round(Number(user.fixed_fund_amount)))
                : '';
            toggleFixedFundAmount();
            document.getElementById('submit-btn').innerText = 'Actualizar Datos';
            document.getElementById('user-modal').classList.remove('hidden');
        }

        document.getElementById('user-form').addEventListener('submit', function () {
            const amountInput = document.getElementById('user-fixed-fund-amount');
            amountInput.value = normalizeFixedFundAmount(amountInput.value);
        });

        document.addEventListener('DOMContentLoaded', function () {
            toggleFixedFundAmount();

            const oldMethod = @json(old('_method'));
            const editingUserId = @json(old('editing_user_id'));
            const hasErrors = @json($errors->any());

            if (hasErrors && oldMethod === 'PUT' && editingUserId) {
                document.getElementById('modal-title').innerText = 'Editar Usuario';
                document.getElementById('modal-subtitle').innerText = 'Corrige los datos y vuelve a intentar.';
                document.getElementById('user-form').action = `/rendicion/usuarios/${editingUserId}`;
                document.getElementById('form-method').value = 'PUT';
                document.getElementById('user-password').required = false;
                document.getElementById('password-section').classList.remove('hidden');
                document.getElementById('submit-btn').innerText = 'Actualizar Datos';
                document.getElementById('user-modal').classList.remove('hidden');
            } else if (hasErrors) {
                document.getElementById('user-modal').classList.remove('hidden');
            }
        });
    </script>
</x-rendicion.layouts.app>
