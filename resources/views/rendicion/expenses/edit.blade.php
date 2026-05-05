<x-rendicion.layouts.app
    page_title="Editar Gasto"
    page_subtitle="Modifica la información de tu gasto en borrador.">
    <x-slot name="header_title">Editar Gasto</x-slot>

    <style>
        .edit-expense-page {
            max-width: 800px;
            margin: 0 auto;
        }

        .edit-shell {
            border-radius: 20px;
            border: 1px solid #dfe8f2;
            background: #ffffff;
            overflow: hidden;
            padding: 1.5rem;
        }

        .field {
            display: grid;
            gap: 0.35rem;
            margin-bottom: 1rem;
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
            padding: 0.6rem 0.7rem;
            font-size: 0.86rem;
            color: #0f172a;
            width: 100%;
            box-sizing: border-box;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e9f1fa;
        }

        .btn {
            border-radius: 11px;
            padding: 0.7rem 1.2rem;
            font-size: 0.75rem;
            font-weight: 900;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            cursor: pointer;
            border: none;
        }

        .btn-cancel {
            background: #f1f5f9;
            color: #475569;
            text-decoration: none;
        }

        .btn-submit {
            background: linear-gradient(135deg, #0f6bb6 0%, #1488db 100%);
            color: #fff;
        }

        .error-inline {
            font-size: 0.72rem;
            color: #c81e1e;
            margin-top: 0.2rem;
        }

        .current-file {
            font-size: 0.75rem;
            color: #0f6bb6;
            margin-top: 0.2rem;
            font-weight: 600;
        }
    </style>

    <div class="edit-expense-page">
        <div class="edit-shell">
            <form method="POST" action="{{ route('rendicion.expenses.update', $expense) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid-2">
                    <div class="field">
                        <label>Categoría</label>
                        <select name="category_id" id="category_id" onchange="toggleCustomCategory()">
                            <option value="">Seleccione</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $expense->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="error-inline">{{ $message }}</div> @enderror
                    </div>

                    <div class="field" id="custom-cat-field" style="display: {{ old('category_id', $expense->category_id) == 6 ? 'block' : 'none' }};">
                        <label>Nueva Categoría</label>
                        <input type="text" name="custom_category_name" id="custom_category_name" value="{{ old('custom_category_name') }}" placeholder="Ej: Notaría">
                        @error('custom_category_name') <div class="error-inline">{{ $message }}</div> @enderror
                    </div>


                </div>

                <div class="grid-2">
                    <div class="field">
                        <label>Documento</label>
                        <select name="document_type" required>
                                <option value="">Seleccione</option>
                                @foreach(['Boleta', 'Factura', 'Recibo', 'Ticket', 'Otros'] as $docType)
                                    <option value="{{ $docType }}" {{ old('document_type', $expense->document_type) === $docType ? 'selected' : '' }}>{{ $docType }}</option>
                                @endforeach
                        </select>
                        @error('document_type') <div class="error-inline">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label>Monto</label>
                        <input type="text" oninput="formatCurrency(this)" name="amount" value="{{ old('amount', $expense->amount ? number_format($expense->amount, 0, ',', '.') : '') }}">
                        @error('amount') <div class="error-inline">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="grid-2">
                    <div class="field">
                        <label>Motivo</label>
                        <input type="text" name="reason" value="{{ old('reason', $expense->reason) }}">
                        @error('reason') <div class="error-inline">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label>Fecha</label>
                        <input type="date" name="expense_date" value="{{ old('expense_date', $expense->expense_date ? $expense->expense_date->format('Y-m-d') : '') }}" max="{{ date('Y-m-d') }}">
                        @error('expense_date') <div class="error-inline">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="grid-2">
                    <div class="field">
                        <label>Proveedor</label>
                        <input type="text" name="provider_name" value="{{ old('provider_name', $expense->provider_name) }}">
                        @error('provider_name') <div class="error-inline">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label>RUT proveedor</label>
                        <input type="text" name="provider_rut" value="{{ old('provider_rut', $expense->provider_rut) }}">
                        @error('provider_rut') <div class="error-inline">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="field">
                    <label>Descripción</label>
                    <textarea rows="3" name="description">{{ old('description', $expense->description) }}</textarea>
                    @error('description') <div class="error-inline">{{ $message }}</div> @enderror
                </div>

                <div class="grid-2">
                    <div class="field">
                        <label>Respaldo (Reemplazar archivo actual)</label>
                        <input type="file" name="attachment_path" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="current-file">Archivo actual: {{ basename($expense->attachment_path) }}</div>
                        @error('attachment_path') <div class="error-inline">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label>Comanda (Si aplica)</label>
                        <input type="file" name="comanda_path" accept=".pdf,.jpg,.jpeg,.png">
                        @if($expense->comanda_path)
                            <div class="current-file">Comanda actual: {{ basename($expense->comanda_path) }}</div>
                        @endif
                        @error('comanda_path') <div class="error-inline">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="actions">
                    <a href="{{ route('rendicion.dashboard') }}" class="btn btn-cancel">Cancelar</a>
                    <button type="submit" class="btn btn-submit">Actualizar Gasto</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function formatCurrency(input) {
            let val = input.value.replace(/\D/g, '');
            if (val === '') {
                input.value = '';
                return;
            }
            val = parseInt(val, 10);
            input.value = new Intl.NumberFormat('es-CL').format(val);
        }

        function toggleCustomCategory() {
            const select = document.getElementById('category_id');
            const field = document.getElementById('custom-cat-field');
            const input = document.getElementById('custom_category_name');
            if (select.value == '6') { // 6 = Otros
                field.style.display = 'block';
                input.required = true;
            } else {
                field.style.display = 'none';
                input.required = false;
                input.value = '';
            }
        }
    </script>
</x-rendicion.layouts.app>
