<x-rendicion.layouts.app
    page_title="Gastos con Proyecto"
    page_subtitle="Ingresa los gastos asociados a un proyecto o número de OT.">
    <x-slot name="header_title">Gastos Proyecto</x-slot>

    <style>
        @php
            $initialRows = old('expenses', [[]]);
        @endphp
        .expenses-page {
            display: grid;
            gap: 1.5rem;
        }

        .bulk-shell {
            border-radius: 20px;
            border: 1px solid #dfe8f2;
            background: #ffffff;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(15, 107, 182, 0.05);
        }

        .bulk-head {
            padding: 1.5rem;
            background: #ffffff;
            border-bottom: 1px solid #e4edf7;
        }

        .bulk-head h2 {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 900;
            color: #0f3f67;
        }

        .bulk-head p {
            margin: 0.3rem 0 0;
            font-size: 0.9rem;
            color: #5a738c;
        }

        .bulk-body {
            padding: 2rem;
        }

        /* Project Section Styling */
        .project-config-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .config-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 120px;
            gap: 1.5rem;
            align-items: end;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .field label {
            font-size: 0.75rem;
            font-weight: 800;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .premium-input, .premium-select-box {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.7rem 1rem;
            font-size: 0.9rem;
            font-weight: 600;
            color: #1e293b;
            transition: all 0.2s ease;
            width: 100%;
        }

        .premium-input:focus {
            border-color: #0f6bb6;
            box-shadow: 0 0 0 4px rgba(15, 107, 182, 0.1);
            outline: none;
        }

        .ot-group {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        /* Expense Rows */
        .expense-row {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            position: relative;
            transition: all 0.2s ease;
        }

        .expense-row:hover {
            border-color: #cbd5e1;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        }

        .row-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.2rem;
            padding-bottom: 0.8rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .row-title {
            font-weight: 900;
            color: #0f172a;
            font-size: 0.9rem;
            text-transform: uppercase;
        }

        .row-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 1.2rem;
            margin-bottom: 1.2rem;
        }

        .row-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.2rem;
        }

        .btn-remove {
            background: #fff1f2;
            color: #e11d48;
            border: none;
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 800;
            cursor: pointer;
            text-transform: uppercase;
        }

        .btn-add {
            background: #f0f9ff;
            color: #0369a1;
            border: 1.5px dashed #0369a1;
            width: 100%;
            padding: 1rem;
            border-radius: 12px;
            font-weight: 800;
            cursor: pointer;
            margin-bottom: 2rem;
            transition: all 0.2s ease;
        }

        .btn-add:hover {
            background: #e0f2fe;
        }

        .actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            padding-top: 2rem;
            border-top: 1px solid #f1f5f9;
        }

        .btn-draft {
            padding: 0.8rem 2rem;
            border-radius: 12px;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            color: #475569;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-submit {
            padding: 0.8rem 3rem;
            border-radius: 12px;
            background: #0f6bb6;
            border: none;
            color: #ffffff;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(15, 107, 182, 0.2);
        }
    </style>

    <div class="expenses-page">
        <section class="bulk-shell">
            <div class="bulk-head">
                <h2>Carga de Gastos de Proyecto</h2>
                <p>Ingresa los gastos asociados a un proyecto o número de OT específico.</p>
            </div>

            <form method="POST" action="{{ route('rendicion.expenses.storeBulk') }}" enctype="multipart/form-data" class="bulk-body" id="projectExpensesForm">
                @csrf
                <input type="hidden" name="is_project" value="1">

                <div class="project-config-card">
                    <div class="config-grid">
                        <div class="field">
                            <label>Título de Rendición (Opcional)</label>
                            <input type="text" name="report_title" value="{{ old('report_title') }}" placeholder="Ej: Gastos Terreno Mayo" class="premium-input">
                        </div>

                        <div class="field">
                            <label>Documento y Número (OT / OC / OP) <span style="color: #ef4444;">*</span></label>
                            <div class="ot-group">
                                <select name="project_prefix" class="premium-select-box" style="width: 70px !important; min-width: 70px !important; height: 42px;" required>
                                    <option value="OT" {{ old('project_prefix') === 'OT' ? 'selected' : '' }}>OT</option>
                                    <option value="OC" {{ old('project_prefix') === 'OC' ? 'selected' : '' }}>OC</option>
                                    <option value="OP" {{ old('project_prefix') === 'OP' ? 'selected' : '' }}>OP</option>
                                </select>
                                <input type="text" name="project_number" value="{{ old('project_number') }}" placeholder="Solo Nº" class="premium-input" style="width: 120px !important; height: 42px;" required oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                            </div>
                        </div>

                        <div class="field">
                            <label>Filas</label>
                            <input id="rowsCounter" type="text" value="{{ count($initialRows) }} gasto(s)" readonly class="premium-input" style="background: #f1f5f9; text-align: center; height: 42px;">
                        </div>
                    </div>
                </div>

                <div class="rows-wrap" id="rowsWrap">
                    @foreach($initialRows as $i => $row)
                        <div class="expense-row" data-row-index="{{ $i }}">
                            <div class="row-head">
                                <div class="row-title">Detalle Gasto #<span class="row-number">{{ $i + 1 }}</span></div>
                                <button type="button" class="btn-remove remove-row-btn">Quitar</button>
                            </div>

                            <div class="row-grid">
                                <div class="field">
                                    <label>Categoria</label>
                                    <select name="expenses[{{ $i }}][category_id]" class="premium-input">
                                        <option value="">Seleccione</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ (string) ($row['category_id'] ?? '') === (string) $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="field">
                                    <label>Documento</label>
                                    <select name="expenses[{{ $i }}][document_type]" class="premium-input">
                                        @foreach(['Boleta', 'Factura', 'Recibo', 'Ticket', 'Otros'] as $docType)
                                            <option value="{{ $docType }}" {{ ($row['document_type'] ?? 'Boleta') === $docType ? 'selected' : '' }}>{{ $docType }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="field">
                                    <label>Fecha</label>
                                    <input type="date" name="expenses[{{ $i }}][expense_date]" value="{{ $row['expense_date'] ?? '' }}" max="{{ date('Y-m-d') }}" class="premium-input">
                                </div>
                            </div>

                            <div class="row-grid">
                                <div class="field">
                                    <label>Motivo</label>
                                    <input type="text" name="expenses[{{ $i }}][reason]" value="{{ $row['reason'] ?? '' }}" class="premium-input">
                                </div>
                                <div class="field">
                                    <label>Monto</label>
                                    <input type="text" data-type="currency" oninput="formatCurrency(this)" name="expenses[{{ $i }}][amount]" value="{{ $row['amount'] ?? '' }}" class="premium-input">
                                </div>
                                <div class="field">
                                    <label>Respaldo (PDF/IMG)</label>
                                    <input type="file" name="expenses[{{ $i }}][attachment_path]" accept=".pdf,.jpg,.jpeg,.png" class="premium-input" style="padding: 0.4rem;">
                                </div>
                            </div>

                            <div class="row-grid-2">
                                <div class="field">
                                    <label>Proveedor</label>
                                    <input type="text" name="expenses[{{ $i }}][provider_name]" value="{{ $row['provider_name'] ?? '' }}" class="premium-input">
                                </div>
                                <div class="field">
                                    <label>RUT proveedor</label>
                                    <input type="text" name="expenses[{{ $i }}][provider_rut]" value="{{ $row['provider_rut'] ?? '' }}" class="premium-input">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" class="btn-add" id="addExpenseRowBtn">
                    + AGREGAR OTRO GASTO
                </button>

                <div class="actions">
                    <button type="submit" name="action" value="draft" class="btn-draft">GUARDAR BORRADOR</button>
                    <button type="submit" name="action" value="submit" class="btn-submit">ENVIAR GASTO</button>
                </div>
            </form>
        </section>
    </div>

    <template id="expenseRowTemplate">
        <div class="expense-row" data-row-index="__INDEX__">
            <div class="row-head">
                <div class="row-title">Detalle Gasto #__NUM__</div>
                <button type="button" class="btn-remove remove-row-btn">Quitar</button>
            </div>

            <div class="row-grid">
                <div class="field">
                    <label>Categoria</label>
                    <select name="expenses[__INDEX__][category_id]" class="premium-input">
                        <option value="">Seleccione</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Documento</label>
                    <select name="expenses[__INDEX__][document_type]" class="premium-input">
                        @foreach(['Boleta', 'Factura', 'Recibo', 'Ticket', 'Otros'] as $docType)
                            <option value="{{ $docType }}">{{ $docType }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Fecha</label>
                    <input type="date" name="expenses[__INDEX__][expense_date]" max="{{ date('Y-m-d') }}" class="premium-input">
                </div>
            </div>

            <div class="row-grid">
                <div class="field">
                    <label>Motivo</label>
                    <input type="text" name="expenses[__INDEX__][reason]" class="premium-input">
                </div>
                <div class="field">
                    <label>Monto</label>
                    <input type="text" data-type="currency" oninput="formatCurrency(this)" name="expenses[__INDEX__][amount]" class="premium-input">
                </div>
                <div class="field">
                    <label>Respaldo (PDF/IMG)</label>
                    <input type="file" name="expenses[__INDEX__][attachment_path]" accept=".pdf,.jpg,.jpeg,.png" class="premium-input" style="padding: 0.4rem;">
                </div>
            </div>

            <div class="row-grid-2">
                <div class="field">
                    <label>Proveedor</label>
                    <input type="text" name="expenses[__INDEX__][provider_name]" class="premium-input">
                </div>
                <div class="field">
                    <label>RUT proveedor</label>
                    <input type="text" name="expenses[__INDEX__][provider_rut]" class="premium-input">
                </div>
            </div>
        </div>
    </template>

    <script>
        function formatCurrency(input) {
            let value = input.value.replace(/\D/g, '');
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            input.value = value;
        }

        (function() {
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
                    if (numberEl) numberEl.textContent = String(idx + 1);
                });
                rowsCounter.value = rows.length + ' gasto(s)';
            }

            function bindRemoveButtons() {
                rowsWrap.querySelectorAll('.remove-row-btn').forEach((btn) => {
                    btn.onclick = function () {
                        const rows = rowsWrap.querySelectorAll('.expense-row');
                        if (rows.length > 1) {
                            this.closest('.expense-row').remove();
                            updateRowsMeta();
                        }
                    };
                });
            }

            addBtn.addEventListener('click', function () {
                let html = template.innerHTML;
                html = html.replaceAll('__INDEX__', String(nextRowIndex));
                html = html.replaceAll('__NUM__', String(nextRowIndex + 1));
                rowsWrap.insertAdjacentHTML('beforeend', html);
                nextRowIndex += 1;
                bindRemoveButtons();
                updateRowsMeta();
            });

            bindRemoveButtons();
        })();
    </script>
</x-rendicion.layouts.app>
