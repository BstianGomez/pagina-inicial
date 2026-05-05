<x-layouts.app
    page_title="Listado de Gastos"
    page_subtitle="Carga multiples gastos en una sola rendicion.">
    <x-slot name="header_title">Gastos</x-slot>

    <style>
        .expenses-page {
            display: grid;
            gap: 1rem;
        }

        .bulk-shell {
            border-radius: 20px;
            border: 1px solid #dfe8f2;
            background: #ffffff;
            overflow: hidden;
        }

        .bulk-head {
            padding: 1rem 1.2rem;
            background: #ffffff;
            border-bottom: 1px solid #e4edf7;
        }

        .bulk-head h2 {
            margin: 0;
            font-size: 1rem;
            font-weight: 900;
            color: #0f3f67;
        }

        .bulk-head p {
            margin: 0.2rem 0 0;
            font-size: 0.8rem;
            color: #5a738c;
        }

        .bulk-body {
            padding: 1rem 1.1rem 1.2rem;
            display: grid;
            gap: 0.9rem;
        }

        .bulk-main-grid {
            display: grid;
            grid-template-columns: 2.2fr 1fr;
            gap: 1.2rem;
            margin-bottom: 0.8rem;
        }

        .field {
            display: grid;
            gap: 0.35rem;
            min-width: 0;
        }

        .field label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            font-weight: 800;
            color: #64748b;
        }

        .field input,
        .field select,
        .field textarea {
            border: 1px solid #d9e5f2;
            border-radius: 10px;
            background: #fff;
            padding: 0.55rem 0.65rem;
            font-size: 0.86rem;
            color: #0f172a;
            width: 100%;
            min-width: 0;
            max-width: 100%;
            box-sizing: border-box;
        }

        .field input.file-selected {
            background-color: #f0fdf4 !important;
            border-color: #10b981 !important;
            color: #047857 !important;
            font-weight: bold;
        }

        .field input[type="file"] {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .rows-wrap {
            display: grid;
            gap: 0.75rem;
        }

        .expense-row {
            border: 1px solid #deebf8;
            border-radius: 14px;
            padding: 0.8rem;
            background: #fbfdff;
            display: grid;
            gap: 0.7rem;
        }

        .expense-row-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.6rem;
        }

        .expense-row-title {
            font-size: 0.75rem;
            font-weight: 900;
            color: #1d4f77;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .row-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 0.65rem;
        }

        .row-grid-3 {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.65rem;
        }

        .btn {
            border-radius: 11px;
            padding: 0.56rem 0.9rem;
            font-size: 0.7rem;
            font-weight: 900;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 140ms ease;
        }

        .btn-add {
            background: #f0f7ff;
            border-color: #cfe3f6;
            color: #1f5d8e;
        }

        .btn-add:hover {
            background: #e4f1ff;
        }

        .btn-remove {
            background: #fff6f5;
            border-color: #ffd3cf;
            color: #b42318;
        }

        .btn-remove:hover {
            background: #ffeceb;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            flex-wrap: wrap;
            border-top: 1px solid #e9f1fa;
            padding-top: 1.2rem;
            margin-top: 1rem;
        }

        .btn-draft {
            background: #ffffff;
            border-color: #d9e5f2;
            color: #4a6785;
        }

        .btn-draft:hover {
            background: #f9fbfd;
        }

        .btn-submit {
            background: linear-gradient(135deg, #0f6bb6 0%, #1488db 100%);
            border-color: #0f6bb6;
            color: #fff;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #0d5e9f 0%, #1278c2 100%);
        }

        .error-inline {
            font-size: 0.72rem;
            color: #c81e1e;
            margin-top: 0.2rem;
        }

        @media (max-width: 768px) {
            .bulk-main-grid,
            .row-grid,
            .row-grid-3 {
                grid-template-columns: 1fr;
            }
        }
    </style>

    @php
        $oldRows = old('expenses', []);
        $initialRows = count($oldRows) > 0 ? $oldRows : [[
            'category_id' => '',
            'ceco_id' => '',
            'reason' => '',
            'description' => '',
            'expense_date' => '',
            'amount' => '',
            'provider_name' => '',
            'provider_rut' => '',
            'document_type' => 'Boleta',
        ]];
    @endphp

    <div class="expenses-page">
        <section class="bulk-shell">
            <div class="bulk-head">
                <h2>Carga Masiva de Gastos</h2>
                <p>Agrega varios gastos y envialos en una sola rendicion.</p>
            </div>

            <form method="POST" action="{{ route('expenses.storeBulk') }}" enctype="multipart/form-data" class="bulk-body" id="bulkExpensesForm">
                @csrf

                <div class="bulk-main-grid">
                    <div class="field">
                        <label>Título de Rendición (Opcional si es borrador)</label>
                        <input type="text" name="report_title" value="{{ old('report_title') }}" placeholder="Ej: Viaje a Concepción Abril 2024">
                        @error('report_title') <div class="error-inline">{{ $message }}</div> @enderror
                    </div>
                    <div class="field">
                        <label>Total de filas</label>
                        <input id="rowsCounter" type="text" value="{{ count($initialRows) }} gasto(s)" readonly>
                    </div>
                </div>

                @error('expenses') <div class="error-inline">{{ $message }}</div> @enderror

                <div class="rows-wrap" id="rowsWrap">
                    @foreach($initialRows as $i => $row)
                        <div class="expense-row" data-row-index="{{ $i }}">
                            <div class="expense-row-head">
                                <div class="expense-row-title">Gasto #<span class="row-number">{{ $i + 1 }}</span></div>
                                <button type="button" class="btn btn-remove remove-row-btn">Quitar</button>
                            </div>

                            <div class="row-grid">
                                <div class="field">
                                    <label>Categoria</label>
                                    <select name="expenses[{{ $i }}][category_id]" onchange="toggleCustomCategory(this, {{ $i }})">
                                        <option value="">Seleccione</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ (string) ($row['category_id'] ?? '') === (string) $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('expenses.' . $i . '.category_id') <div class="error-inline">{{ $message }}</div> @enderror
                                </div>

                                <div class="field" id="custom-cat-field-{{ $i }}" style="{{ (string) ($row['category_id'] ?? '') === '6' ? '' : 'display: none;' }}">
                                    <label>Nueva Categoría</label>
                                    <input type="text" name="expenses[{{ $i }}][custom_category_name]" value="{{ $row['custom_category_name'] ?? '' }}" placeholder="Ej: Notaría">
                                    @error('expenses.' . $i . '.custom_category_name') <div class="error-inline">{{ $message }}</div> @enderror
                                </div>



                                <div class="field">
                                    <label>Documento</label>
                                    <select name="expenses[{{ $i }}][document_type]">
                                        @foreach(['Boleta', 'Factura', 'Recibo', 'Ticket', 'Otros'] as $docType)
                                            <option value="{{ $docType }}" {{ ($row['document_type'] ?? 'Boleta') === $docType ? 'selected' : '' }}>{{ $docType }}</option>
                                        @endforeach
                                    </select>
                                    @error('expenses.' . $i . '.document_type') <div class="error-inline">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="row-grid-3">
                                <div class="field">
                                    <label>Motivo</label>
                                    <input type="text" name="expenses[{{ $i }}][reason]" value="{{ $row['reason'] ?? '' }}">
                                    @error('expenses.' . $i . '.reason') <div class="error-inline">{{ $message }}</div> @enderror
                                </div>
                                <div class="field">
                                    <label>Fecha</label>
                                    <input type="date" name="expenses[{{ $i }}][expense_date]" value="{{ $row['expense_date'] ?? '' }}" max="{{ date('Y-m-d') }}">
                                    @error('expenses.' . $i . '.expense_date') <div class="error-inline">{{ $message }}</div> @enderror
                                </div>
                                <div class="field">
                                    <label>Monto</label>
                                    <input type="text" data-type="currency" oninput="formatCurrency(this)" name="expenses[{{ $i }}][amount]" value="{{ isset($row['amount']) && is_numeric($row['amount']) ? number_format($row['amount'], 0, ',', '.') : ($row['amount'] ?? '') }}">
                                    @error('expenses.' . $i . '.amount') <div class="error-inline">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="row-grid-3">
                                <div class="field">
                                    <label>Proveedor</label>
                                    <input type="text" name="expenses[{{ $i }}][provider_name]" value="{{ $row['provider_name'] ?? '' }}">
                                    @error('expenses.' . $i . '.provider_name') <div class="error-inline">{{ $message }}</div> @enderror
                                </div>
                                <div class="field">
                                    <label>RUT proveedor</label>
                                    <input type="text" name="expenses[{{ $i }}][provider_rut]" value="{{ $row['provider_rut'] ?? '' }}">
                                    @error('expenses.' . $i . '.provider_rut') <div class="error-inline">{{ $message }}</div> @enderror
                                </div>
                                <div class="field">
                                    <label>Respaldo (PDF/JPG/PNG)</label>
                                    <input type="file" name="expenses[{{ $i }}][attachment_path]" accept=".pdf,.jpg,.jpeg,.png">
                                    @error('expenses.' . $i . '.attachment_path') <div class="error-inline">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="row-grid">
                                <div class="field" style="grid-column: span 2;">
                                    <label>Descripcion</label>
                                    <textarea rows="2" name="expenses[{{ $i }}][description]">{{ $row['description'] ?? '' }}</textarea>
                                    @error('expenses.' . $i . '.description') <div class="error-inline">{{ $message }}</div> @enderror
                                </div>
                                <div class="field" style="grid-column: span 2;">
                                    <label>Comanda (solo si aplica)</label>
                                    <input type="file" name="expenses[{{ $i }}][comanda_path]" accept=".pdf,.jpg,.jpeg,.png">
                                    @error('expenses.' . $i . '.comanda_path') <div class="error-inline">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div>
                    <button type="button" class="btn btn-add" id="addExpenseRowBtn">Agregar otro gasto</button>
                </div>

                <div class="actions">
                    <button type="submit" name="action" value="draft" class="btn btn-draft">Guardar como Borrador</button>
                    <button type="submit" name="action" value="submit" class="btn btn-submit">Guardar</button>
                </div>
            </form>
        </section>

    </div>

    <template id="expenseRowTemplate">
        <div class="expense-row" data-row-index="__INDEX__">
            <div class="expense-row-head">
                <div class="expense-row-title">Gasto #<span class="row-number">__NUM__</span></div>
                <button type="button" class="btn btn-remove remove-row-btn">Quitar</button>
            </div>

            <div class="row-grid">
                <div class="field">
                    <label>Categoria</label>
                    <select name="expenses[__INDEX__][category_id]" onchange="toggleCustomCategory(this, '__INDEX__')">
                        <option value="">Seleccione</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field" id="custom-cat-field-__INDEX__" style="display: none;">
                    <label>Nueva Categoría</label>
                    <input type="text" name="expenses[__INDEX__][custom_category_name]" placeholder="Ej: Notaría">
                </div>

                <div class="field">
                    <label>Documento</label>
                    <select name="expenses[__INDEX__][document_type]">
                        @foreach(['Boleta', 'Factura', 'Recibo', 'Ticket', 'Otros'] as $docType)
                            <option value="{{ $docType }}">{{ $docType }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row-grid-3">
                <div class="field">
                    <label>Motivo</label>
                    <input type="text" name="expenses[__INDEX__][reason]">
                </div>
                <div class="field">
                    <label>Fecha</label>
                    <input type="date" name="expenses[__INDEX__][expense_date]" max="{{ date('Y-m-d') }}">
                </div>
                <div class="field">
                    <label>Monto</label>
                    <input type="text" data-type="currency" oninput="formatCurrency(this)" name="expenses[__INDEX__][amount]">
                </div>
            </div>

            <div class="row-grid-3">
                <div class="field">
                    <label>Proveedor</label>
                    <input type="text" name="expenses[__INDEX__][provider_name]">
                </div>
                <div class="field">
                    <label>RUT proveedor</label>
                    <input type="text" name="expenses[__INDEX__][provider_rut]">
                </div>
                <div class="field">
                    <label>Respaldo (PDF/JPG/PNG)</label>
                    <input type="file" name="expenses[__INDEX__][attachment_path]" accept=".pdf,.jpg,.jpeg,.png">
                </div>
            </div>

            <div class="row-grid">
                <div class="field" style="grid-column: span 2;">
                    <label>Descripcion</label>
                    <textarea rows="2" name="expenses[__INDEX__][description]"></textarea>
                </div>
                <div class="field" style="grid-column: span 2;">
                    <label>Comanda (solo si aplica)</label>
                    <input type="file" name="expenses[__INDEX__][comanda_path]" accept=".pdf,.jpg,.jpeg,.png">
                </div>
            </div>
        </div>
    </template>

    <script>
        (function() {
            function toggleCustomCategory(select, index) {
                const field = document.getElementById(`custom-cat-field-${index}`);
                if (select.value == '6') { // 6 es ID de 'Otros'
                    field.style.display = 'grid';
                    field.querySelector('input').required = true;
                } else {
                    field.style.display = 'none';
                    field.querySelector('input').required = false;
                }
            }
            window.toggleCustomCategory = toggleCustomCategory;

            const rowsWrap = document.getElementById('rowsWrap');
            const addBtn = document.getElementById('addExpenseRowBtn');
            const template = document.getElementById('expenseRowTemplate');
            const rowsCounter = document.getElementById('rowsCounter');
            let nextRowIndex = rowsWrap.querySelectorAll('.expense-row').length;

            function updateRowsMeta() {
                const rows = rowsWrap.querySelectorAll('.expense-row');
                rows.forEach((row, idx) => {
                    row.dataset.rowIndex = String(idx);
                    const numberEl = row.querySelector('.row-number');
                    if (numberEl) {
                        numberEl.textContent = String(idx + 1);
                    }
                });
                rowsCounter.value = rows.length + ' gasto(s)';
            }

            function bindRemoveButtons() {
                rowsWrap.querySelectorAll('.remove-row-btn').forEach((btn) => {
                    btn.onclick = function () {
                        const rows = rowsWrap.querySelectorAll('.expense-row');
                        if (rows.length <= 1) {
                            return;
                        }

                        const row = this.closest('.expense-row');
                        if (row) {
                            row.remove();
                            updateRowsMeta();
                        }
                    };
                });
            }

            addBtn.addEventListener('click', function () {
                const nextIndex = nextRowIndex;
                let html = template.innerHTML;
                html = html.replaceAll('__INDEX__', String(nextIndex));
                html = html.replaceAll('__NUM__', String(nextIndex + 1));
                rowsWrap.insertAdjacentHTML('beforeend', html);
                nextRowIndex += 1;
                bindRemoveButtons();
                updateRowsMeta();
            });

            bindRemoveButtons();
            updateRowsMeta();

            // File input logic
            document.addEventListener('change', function(e) {
                if (e.target && e.target.type === 'file') {
                    if (e.target.files && e.target.files.length > 0) {
                        e.target.classList.add('file-selected');
                    } else {
                        e.target.classList.remove('file-selected');
                    }
                }
            });

            // Validation for 0 amount
            const form = document.getElementById('bulkExpensesForm');
            let lastSubmitAction = 'draft';

            if (form) {
                // Capturar qué botón se presionó
                form.querySelectorAll('button[type="submit"]').forEach(btn => {
                    btn.addEventListener('click', function() {
                        lastSubmitAction = this.value;
                    });
                });

                form.addEventListener('submit', function(e) {
                    // Si es borrador, no validamos el monto 0 ni campos vacíos
                    if (lastSubmitAction === 'draft') {
                        return;
                    }

                    const amountInputs = form.querySelectorAll('input[data-type="currency"]');
                    let hasZero = false;
                    
                    amountInputs.forEach(input => {
                        const rawValue = input.value.replace(/\./g, '').replace(/,/g, '.');
                        const numValue = parseFloat(rawValue);
                        if (isNaN(numValue) || numValue <= 0) {
                            hasZero = true;
                            input.style.borderColor = '#ef4444';
                            input.style.backgroundColor = '#fef2f2';
                        } else {
                            input.style.borderColor = '';
                            input.style.backgroundColor = '';
                        }
                    });

                    if (hasZero) {
                        e.preventDefault();
                        alert('No se permiten gastos con monto 0 para completar el guardado. Por favor, ingrese un monto válido.');
                    }
                });

                // Auto-guardar como borrador al cerrar pestaña
                window.addEventListener('beforeunload', function(e) {
                    // Solo si hay algún dato escrito
                    const anyInput = form.querySelector('input[type="text"]:not([readonly]), textarea, select');
                    if (anyInput && anyInput.value.length > 0) {
                        // Crear un campo hidden para forzar la acción 'draft'
                        const actionInput = document.createElement('input');
                        actionInput.type = 'hidden';
                        actionInput.name = 'action';
                        actionInput.value = 'draft';
                        form.appendChild(actionInput);
                        
                        // Intentar enviar con fetch keepalive para que siga aunque se cierre
                        const formData = new FormData(form);
                        fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            keepalive: true
                        });
                    }
                });
            }
        })();
    </script>
</x-layouts.app>
