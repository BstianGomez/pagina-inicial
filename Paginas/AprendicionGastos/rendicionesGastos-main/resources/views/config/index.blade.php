<x-layouts.app
    page_title="Configuración del Sistema"
    page_subtitle="Gestiona los catálogos de categorías de gastos y centros de costos (CECO).">

    <style>
        .cfg-modal-wrap {
            z-index: 260;
            align-items: flex-start;
            justify-content: center;
            padding-top: 68px;
            padding-bottom: 16px;
            padding-left: 106px;
            padding-right: 16px;
        }

        .cfg-modal-panel {
            width: min(480px, calc(100vw - 2rem));
            max-height: calc(100vh - 102px);
            overflow: auto;
            border-radius: 20px;
            border: 1px solid #dbe3ef;
            background: #ffffff;
            box-shadow: 0 30px 80px -32px rgba(15, 23, 42, 0.45);
        }

        .cfg-modal-head {
            background: linear-gradient(135deg, #0b5fa5 0%, #0f6bb6 100%);
            padding: 1.1rem 1.2rem 1rem;
        }

        .cfg-modal-title {
            margin: 0;
            font-size: 1.08rem;
            line-height: 1.1;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.01em;
        }

        .cfg-modal-form {
            padding: 1.1rem 1.2rem;
            display: grid;
            gap: 0.82rem;
        }

        .cfg-field {
            display: grid;
            gap: 0.42rem;
        }

        .cfg-label {
            margin: 0;
            font-size: 0.67rem;
            font-weight: 800;
            letter-spacing: 0.11em;
            text-transform: uppercase;
            color: #5b6473;
        }

        .cfg-check {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            background: #eff6ff;
            border: 1px solid #dbeafe;
            border-radius: 12px;
            padding: 0.7rem 0.8rem;
            font-size: 0.9rem;
            font-weight: 700;
            color: #334155;
        }

        .cfg-actions {
            margin-top: 0.15rem;
            display: grid;
            grid-template-columns: 1fr 1.3fr;
            gap: 0.7rem;
        }

        .cfg-cancel,
        .cfg-save {
            border-radius: 12px;
            padding: 0.58rem 0.8rem;
            font-size: 0.92rem;
            font-weight: 800;
            cursor: pointer;
            transition: all 140ms ease;
        }

        .cfg-cancel {
            border: 1px solid #dbe3ef;
            background: #f8fafc;
            color: #44556c;
        }

        .cfg-cancel:hover {
            background: #eef2f7;
        }

        .cfg-save {
            border: none;
            color: #ffffff;
            background: linear-gradient(135deg, #0b5fa5 0%, #0f6bb6 100%);
            box-shadow: 0 12px 24px -14px rgba(15, 107, 182, 0.85);
        }

        .cfg-save:hover {
            background: linear-gradient(135deg, #0a4f86 0%, #0b5fa5 100%);
            transform: translateY(-1px);
        }

        .cfg-row-actions {
            display: inline-flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.42rem;
        }

        .cfg-action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            border-radius: 10px;
            padding: 0.42rem 0.58rem;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            border: 1px solid transparent;
            transition: all 140ms ease;
            cursor: pointer;
        }

        .cfg-action-btn svg {
            width: 0.8rem;
            height: 0.8rem;
        }

        .cfg-action-edit {
            color: #0f6bb6;
            background: #eff6ff;
            border-color: #bfdbfe;
        }

        .cfg-action-edit:hover {
            color: #0b5fa5;
            background: #dbeafe;
            border-color: #93c5fd;
        }

        .cfg-action-delete {
            color: #b91c1c;
            background: #fef2f2;
            border-color: #fecaca;
        }

        .cfg-action-delete:hover {
            color: #991b1b;
            background: #fee2e2;
            border-color: #fca5a5;
        }

        .cfg-delete-wrap {
            z-index: 270;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .cfg-delete-panel {
            width: min(420px, calc(100vw - 2rem));
            border-radius: 18px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            box-shadow: 0 28px 70px -30px rgba(15, 23, 42, 0.52);
            overflow: hidden;
        }

        .cfg-delete-head {
            padding: 1rem 1.15rem 0.6rem;
            border-bottom: 1px solid #eef2f7;
        }

        .cfg-delete-title {
            margin: 0;
            color: #0f172a;
            font-size: 1.02rem;
            font-weight: 800;
        }

        .cfg-delete-body {
            padding: 1rem 1.15rem 1.2rem;
            color: #475569;
            font-size: 0.94rem;
            line-height: 1.5;
        }

        .cfg-delete-footer {
            padding: 0 1.15rem 1rem;
            display: flex;
            justify-content: flex-end;
            gap: 0.6rem;
        }

        .cfg-delete-cancel,
        .cfg-delete-confirm {
            border-radius: 11px;
            padding: 0.54rem 0.9rem;
            font-size: 0.83rem;
            font-weight: 800;
            letter-spacing: 0.02em;
            transition: all 140ms ease;
            cursor: pointer;
        }

        .cfg-delete-cancel {
            border: 1px solid #dbe3ef;
            background: #f8fafc;
            color: #44556c;
        }

        .cfg-delete-cancel:hover {
            background: #eef2f7;
        }

        .cfg-delete-confirm {
            border: 1px solid #fecaca;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: #ffffff;
            box-shadow: 0 12px 24px -14px rgba(220, 38, 38, 0.9);
        }

        .cfg-delete-confirm:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            transform: translateY(-1px);
        }

        @media (max-width: 1024px) {
            .cfg-modal-wrap {
                padding-left: 16px;
            }
        }

        @media (max-width: 640px) {
            .cfg-modal-wrap {
                padding-top: 68px;
            }

            .cfg-modal-head,
            .cfg-modal-form {
                padding: 1rem;
            }

            .cfg-actions {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        <!-- Categories Section -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-slate-900 flex items-center">
                    <svg class="h-5 w-5 mr-3 text-sofofa-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M13 7h.01M13 11h.01M13 15h.01M17 7h.01M17 11h.01M17 15h.01M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    Categorías de Gastos
                </h3>
                <button onclick="openCategoryModal()" class="inline-flex items-center text-xs font-bold text-white bg-sofofa-blue hover:bg-sofofa-blue-dark px-3 py-1.5 rounded-xl transition-all shadow-md">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Nueva
                </button>
            </div>
            
            <div class="card-main shadow-lg">
                <table class="table-sofofa">
                    <thead>
                        <tr>
                            <th class="text-left">CATEGORÍA</th>
                            <th class="text-left">COMANDA</th>
                            <th class="text-right">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @forelse($categories as $category)
                        <tr class="hover:bg-slate-50/80 transition-colors duration-150">
                            <td class="font-semibold text-slate-900">{{ $category->name }}</td>
                            <td>
                                @if($category->requires_comanda)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[9px] font-bold bg-orange-100 text-orange-700 border border-orange-200 uppercase">Obligatoria</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[9px] font-bold bg-slate-100 text-slate-600 border border-slate-200 uppercase">Opcional</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="cfg-row-actions">
                                    <button type="button" onclick='editCategory(@json($category))' class="cfg-action-btn cfg-action-edit" title="Editar categoría">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Editar
                                    </button>
                                    <form method="POST" action="{{ route('config.category.destroy', $category) }}" class="inline-flex">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="openDeleteModal(event, this.form, '¿Seguro que deseas eliminar la categoría {{ addslashes($category->name) }}?');" class="cfg-action-btn cfg-action-delete" title="Eliminar categoría">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-7 0h8"/></svg>
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-8 text-slate-500">No hay categorías registradas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- CECO Section -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-slate-900 flex items-center">
                    <svg class="h-5 w-5 mr-3 text-sofofa-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Centros de Costos (CECO)
                </h3>
                <button onclick="openCecoModal()" class="inline-flex items-center text-xs font-bold text-white bg-sofofa-blue hover:bg-sofofa-blue-dark px-3 py-1.5 rounded-xl transition-all shadow-md">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Nuevo
                </button>
            </div>
            
            <div class="card-main shadow-lg">
                <table class="table-sofofa">
                    <thead>
                        <tr>
                            <th class="text-left">CÓDIGO</th>
                            <th class="text-left">NOMBRE</th>
                            <th class="text-right">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @forelse($cecos as $ceco)
                        <tr class="hover:bg-slate-50/80 transition-colors duration-150">
                            <td class="font-mono text-[12px] font-bold text-sofofa-blue">{{ $ceco->code }}</td>
                            <td class="text-slate-700 font-medium">{{ $ceco->name }}</td>
                            <td class="text-right">
                                <div class="cfg-row-actions">
                                    <button type="button" onclick='editCeco(@json($ceco))' class="cfg-action-btn cfg-action-edit" title="Editar CECO">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Editar
                                    </button>
                                    <form method="POST" action="{{ route('config.ceco.destroy', $ceco) }}" class="inline-flex">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="openDeleteModal(event, this.form, '¿Seguro que deseas eliminar el CECO {{ addslashes($ceco->code) }}?');" class="cfg-action-btn cfg-action-delete" title="Eliminar CECO">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-7 0h8"/></svg>
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-8 text-slate-500">No hay CECOs registrados</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Category Modal -->
    <div id="category-modal" class="cfg-modal-wrap hidden fixed inset-0 flex p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeCategoryModal()"></div>
        <div class="cfg-modal-panel relative z-10">
            <div class="cfg-modal-head">
                <h4 id="category-modal-title" class="cfg-modal-title">Nueva Categoria</h4>
            </div>
            <form id="category-form" method="POST" class="cfg-modal-form">
                @csrf
                <input type="hidden" name="_method" id="category-method" value="POST">
                <div class="cfg-field">
                    <label class="cfg-label" for="cat-name">Nombre</label>
                    <input type="text" name="name" id="cat-name" required class="input-pill" placeholder="Ej: Pasajes Aéreos">
                </div>
                <div class="cfg-check">
                    <input type="checkbox" name="requires_comanda" id="cat-comanda" value="1" class="h-4 w-4 text-sofofa-blue border-slate-300 rounded focus:ring-sofofa-blue">
                    <label for="cat-comanda">Comanda obligatoria</label>
                </div>
                <div class="cfg-actions">
                    <button type="button" onclick="closeCategoryModal()" class="cfg-cancel">Cancelar</button>
                    <button type="submit" class="cfg-save">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- CECO Modal -->
    <div id="ceco-modal" class="cfg-modal-wrap hidden fixed inset-0 flex p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeCecoModal()"></div>
        <div class="cfg-modal-panel relative z-10">
            <div class="cfg-modal-head">
                <h4 id="ceco-modal-title" class="cfg-modal-title">Nuevo CECO</h4>
            </div>
            <form id="ceco-form" method="POST" class="cfg-modal-form">
                @csrf
                <input type="hidden" name="_method" id="ceco-method" value="POST">
                <div class="cfg-field">
                    <label class="cfg-label" for="ceco-code">Codigo</label>
                    <input type="text" name="code" id="ceco-code" required class="input-pill" placeholder="EJ: ADM-101">
                </div>
                <div class="cfg-field">
                    <label class="cfg-label" for="ceco-name">Nombre</label>
                    <input type="text" name="name" id="ceco-name" required class="input-pill" placeholder="Ej: Gerencia General">
                </div>
                <div class="cfg-actions">
                    <button type="button" onclick="closeCecoModal()" class="cfg-cancel">Cancelar</button>
                    <button type="submit" class="cfg-save">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <div id="delete-modal" class="cfg-delete-wrap hidden fixed inset-0 flex">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
        <div class="cfg-delete-panel relative z-10">
            <div class="cfg-delete-head">
                <h4 class="cfg-delete-title">Confirmar eliminación</h4>
            </div>
            <div class="cfg-delete-body">
                <p id="delete-modal-message" class="m-0">¿Seguro que deseas eliminar este registro?</p>
            </div>
            <div class="cfg-delete-footer">
                <button type="button" class="cfg-delete-cancel" onclick="closeDeleteModal()">Cancelar</button>
                <button type="button" class="cfg-delete-confirm" onclick="confirmDeleteAction()">Sí, eliminar</button>
            </div>
        </div>
    </div>

    <script>
        let deleteFormToSubmit = null;

        function openDeleteModal(event, formEl, message) {
            if (event) event.preventDefault();
            deleteFormToSubmit = formEl;
            document.getElementById('delete-modal-message').innerText = message;
            document.getElementById('delete-modal').classList.remove('hidden');
            return false;
        }

        function closeDeleteModal() {
            deleteFormToSubmit = null;
            document.getElementById('delete-modal').classList.add('hidden');
        }

        function confirmDeleteAction() {
            if (deleteFormToSubmit) {
                deleteFormToSubmit.submit();
            }
        }

        // Category Functions
        function openCategoryModal() {
            document.getElementById('category-modal-title').innerText = 'Nueva Categoría';
            document.getElementById('category-form').action = "{{ route('config.category.store') }}";
            document.getElementById('category-method').value = 'POST';
            document.getElementById('cat-name').value = '';
            document.getElementById('cat-comanda').checked = false;
            document.getElementById('category-modal').classList.remove('hidden');
        }
        function closeCategoryModal() { document.getElementById('category-modal').classList.add('hidden'); }
        function editCategory(cat) {
            document.getElementById('category-modal-title').innerText = 'Editar Categoría';
            document.getElementById('category-form').action = `/configuracion/categoria/${cat.id}`;
            document.getElementById('category-method').value = 'PUT';
            document.getElementById('cat-name').value = cat.name;
            document.getElementById('cat-comanda').checked = !!cat.requires_comanda;
            document.getElementById('category-modal').classList.remove('hidden');
        }

        // CECO Functions
        function openCecoModal() {
            document.getElementById('ceco-modal-title').innerText = 'Nuevo CECO';
            document.getElementById('ceco-form').action = "{{ route('config.ceco.store') }}";
            document.getElementById('ceco-method').value = 'POST';
            document.getElementById('ceco-code').value = '';
            document.getElementById('ceco-name').value = '';
            document.getElementById('ceco-modal').classList.remove('hidden');
        }
        function closeCecoModal() { document.getElementById('ceco-modal').classList.add('hidden'); }
        function editCeco(ceco) {
            document.getElementById('ceco-modal-title').innerText = 'Editar CECO';
            document.getElementById('ceco-form').action = `/configuracion/ceco/${ceco.id}`;
            document.getElementById('ceco-method').value = 'PUT';
            document.getElementById('ceco-code').value = ceco.code;
            document.getElementById('ceco-name').value = ceco.name;
            document.getElementById('ceco-modal').classList.remove('hidden');
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDeleteModal();
            }
        });
    </script>
</x-layouts.app>
