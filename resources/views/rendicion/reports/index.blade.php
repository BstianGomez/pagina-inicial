<x-rendicion.layouts.app
    page_title="Listado de Rendiciones"
    page_subtitle="Controla tus solicitudes y filtrado inteligente.">
    <x-slot name="header_title">Rendiciones</x-slot>

    <style>
        .reports-index-page {
            display: grid;
            gap: 1rem;
        }

        .reports-shell {
            border-radius: 20px;
            border: 1px solid #dfe8f2;
            background: #ffffff;
            box-shadow: 0 10px 24px -18px rgba(15, 23, 42, 0.28);
            overflow: hidden;
        }

        .reports-toolbar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.65rem;
            margin-bottom: 1rem;
        }

        .reports-toolbar-group {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 0.65rem;
            width: 100%;
        }

        .reports-toolbar-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.55rem;
            border-radius: 18px;
            padding: 0.78rem 1.35rem;
            min-height: 44px;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.03em;
            line-height: 1.2;
            transition: all 160ms ease;
            text-decoration: none;
            border: 1px solid transparent;
            white-space: nowrap;
        }

        .reports-toolbar-btn svg {
            width: 1rem;
            height: 1rem;
            flex-shrink: 0;
        }

        .reports-toolbar-btn-neutral {
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            border-color: #dbe6f2;
            color: #4b5f7a;
            box-shadow: 0 6px 16px -14px rgba(15, 23, 42, 0.35);
        }

        .reports-toolbar-btn-neutral:hover:not(:disabled) {
            background: #ffffff;
            border-color: #bfd3e8;
            color: #334155;
            transform: translateY(-1px);
            box-shadow: 0 10px 20px -14px rgba(15, 23, 42, 0.4);
        }

        .reports-toolbar-btn-neutral:disabled {
            opacity: 0.52;
            cursor: not-allowed;
        }

        .reports-toolbar-btn-primary {
            background: linear-gradient(135deg, #0f6bb6 0%, #1488db 100%);
            border-color: #0f6bb6;
            color: #ffffff;
            box-shadow: 0 14px 24px -16px rgba(20, 136, 219, 0.75);
        }

        .reports-toolbar-btn-primary:hover {
            background: linear-gradient(135deg, #0d5e9f 0%, #1278c2 100%);
            border-color: #0d5e9f;
            transform: translateY(-1px);
            box-shadow: 0 18px 26px -16px rgba(18, 120, 194, 0.75);
        }

        .reports-toolbar-btn:active {
            transform: translateY(0);
        }

        @media (max-width: 640px) {
            .reports-toolbar {
                justify-content: stretch;
            }

            .reports-toolbar-group {
                justify-content: stretch;
            }

            .reports-toolbar-btn {
                flex: 1 1 180px;
            }
        }

        .reports-filter-box {
            border: 1px solid #e5edf6;
            box-shadow: inset 0 1px 2px rgba(15, 23, 42, 0.03);
            background: #f8fbff;
            border-radius: 14px;
            padding: 1rem;
        }

        .reports-field label {
            display: block;
            font-size: 0.68rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            color: #64748b;
            margin-bottom: 0.4rem;
            text-transform: uppercase;
        }

        .reports-field input,
        .reports-field select {
            width: 100%;
            border: 1px solid #d7e2ef;
            border-radius: 12px;
            background: #ffffff;
            color: #0f172a;
            padding: 0.68rem 0.82rem;
            font-size: 0.95rem;
            font-weight: 600;
            outline: none;
            transition: all 140ms ease;
        }

        .reports-field input:focus,
        .reports-field select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
        }

        .reports-result-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.7rem;
            padding: 0.95rem 1.1rem;
            border-top: 1px solid #e5edf6;
            border-bottom: 1px solid #e5edf6;
            background: #f8fbff;
        }

        .reports-table-wrap {
            overflow-x: auto;
            padding: 0.8rem 0.9rem 1rem;
            background: #ffffff;
        }

        .reports-table-frame {
            display: block;
            width: 100%;
            min-width: 100%;
            border-radius: 1rem;
            overflow: hidden;
            background: #ffffff;
            border: 1px solid #e2e8f0;
        }

        .reports-table {
            min-width: 1160px;
            width: 100%;
            border-collapse: collapse;
        }

        .reports-table thead th {
            background: #f8fbff;
            color: #64748b;
            font-size: 0.66rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.09em;
            border-bottom: 1px solid #e5edf6;
            padding: 0.8rem 0.7rem;
            text-align: left;
        }

        .reports-table tbody td {
            border-bottom: 1px solid #edf2f7;
            padding: 0.9rem 0.7rem;
            vertical-align: middle;
            background: #ffffff;
        }

        .reports-table tbody tr {
            transition: background-color 120ms ease;
        }

        .reports-table tbody tr:hover td {
            background: #f8fbff;
        }

        .reports-title-cell {
            font-size: 0.98rem;
            font-weight: 800;
            color: #0f172a;
        }

        .reports-amount {
            font-size: 1rem;
            font-weight: 800;
            color: #0f172a;
            text-align: right;
            white-space: nowrap;
        }

        .is-duplicate-row {
            background-color: #fff1f2 !important;
            border-left: 4px solid #e11d48 !important;
        }

        .is-duplicate-row:hover {
            background-color: #ffe4e6 !important;
        }

        .badge-duplicate {
            display: inline-flex;
            align-items: center;
            padding: 0.15rem 0.4rem;
            border-radius: 0.25rem;
            font-size: 0.55rem;
            font-weight: 900;
            background: #e11d48;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 0.3rem;
            max-width: fit-content;
        }
    </style>

    <form id="export-form" action="{{ route('rendicion.reports.export') }}" method="POST">
        @csrf
        
        <div class="reports-index-page">
            <div class="reports-shell flex flex-col">
                <!-- Internal Card Header -->
                <div class="px-6 pt-7 pb-6 sm:px-8 lg:px-10 border-b border-slate-200/80 bg-gradient-to-b from-white to-slate-50/30">
                    <div class="reports-toolbar">
                        <div class="reports-toolbar-group">
                            <button type="submit" id="export-btn" disabled class="reports-toolbar-btn reports-toolbar-btn-neutral">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                <span>Exportar</span>
                            </button>
                            <a href="{{ route('rendicion.expenses.create', $isProject ? ['project' => 1] : []) }}" class="reports-toolbar-btn reports-toolbar-btn-primary">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                                <span>Nueva Solicitud</span>
                            </a>
                        </div>
                    </div>

                    <!-- Modern Filter Grid -->
                    <div class="reports-filter-box grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="reports-field">
                            <label>Búsqueda rápida</label>
                            <div class="relative group">
                                <input type="text" id="search-input" placeholder="Proveedor, descripción o rut..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="reports-field">
                            <label>Categoría</label>
                            <select id="category-select">
                                <option value="">Todas las Categorías</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="reports-field">
                            <label>Centro de costo</label>
                            <select id="ceco-select">
                                <option value="">Todos los CECO</option>
                                @foreach($cecos as $ceco)
                                    <option value="{{ $ceco->id }}" {{ request('ceco') == $ceco->id ? 'selected' : '' }}>{{ $ceco->code }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="reports-field">
                            <label>Estado actual</label>
                            <select id="status-select">
                                <option value="">Todos los Estados</option>
                                @foreach($statuses as $st)
                                    <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col">
                    <div class="reports-result-bar">
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-[0.08em] leading-none">Resultados</span>
                            <div class="h-2 w-2 rounded-full bg-blue-400"></div>
                        </div>
                        <span class="text-[11px] font-bold text-slate-500 uppercase tracking-[0.08em] tabular-nums">
                            {{ $reports->total() }} registros
                        </span>
                    </div>

                    <div class="reports-table-wrap">
                        <div class="reports-table-frame">
                        <table class="reports-table">
                            <thead>
                                <tr class="bg-slate-50/80">
                                    <th class="w-12 text-center">
                                        <input type="checkbox" id="select-all" class="h-5 w-5 text-sofofa-blue border-slate-200 rounded-lg focus:ring-sofofa-blue cursor-pointer transition-all">
                                    </th>
                                    <th class="min-w-[70px]">ID</th>
                                    <th class="min-w-[80px]">CECO</th>
                                    <th class="min-w-[120px]">Categoría</th>
                                    <th class="min-w-[200px]">Título</th>
                                    <th class="min-w-[150px]">Estado</th>
                                    <th class="min-w-[150px]">Solicitante</th>
                                    <th class="min-w-[120px] text-right">Monto Total</th>
                                    <th class="w-12"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports as $report)
                                @php
                                    $isDuplicate = $report->has_duplicate_expenses;
                                    $rowClasses = $isDuplicate ? 'is-duplicate-row group cursor-pointer transition-all duration-200 hover:-translate-y-0.5' : 'group cursor-pointer transition-all duration-200 hover:-translate-y-0.5';
                                    $targetUrl = $report->status === \App\Models\Rendicion\Report::STATUS_DRAFT ? route('rendicion.expenses.createStep1', $report) : route('rendicion.reports.show', $report);
                                @endphp
                                <tr class="{{ $rowClasses }}" onclick="if(!event.target.closest('input')) window.location='{{ $targetUrl }}'">
                                    @php
                                        $category = $report->expenses->first()->category ?? null;
                                        $categoryName = $category->name ?? 'Gasto';

                                        $baseCategories = ['insumos computacionales', 'alimentacion', 'movilizacion', 'hospedaje', 'papeleria', 'otros'];
                                        $normalizedCategory = \Illuminate\Support\Str::of($categoryName)->ascii()->lower()->trim()->value();
                                        $isCustomCategory = (bool) ($category?->is_custom ?? false) || !in_array($normalizedCategory, $baseCategories, true);

                                        if ($isCustomCategory) {
                                            $categoryColor = [
                                                'bg' => '#fff7ed',
                                                'text' => '#9a3412',
                                                'border' => '#fdba74',
                                            ];
                                        } else {
                                            $categoryColorMap = [
                                                'alimentacion' => ['bg' => '#fef3c7', 'text' => '#92400e', 'border' => '#fcd34d'],
                                                'pasajes aereos' => ['bg' => '#e0f2fe', 'text' => '#0c4a6e', 'border' => '#7dd3fc'],
                                                'insumos computacionales' => ['bg' => '#ede9fe', 'text' => '#5b21b6', 'border' => '#c4b5fd'],
                                                'movilizacion' => ['bg' => '#dcfce7', 'text' => '#166534', 'border' => '#86efac'],
                                                'hospedaje' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'border' => '#fca5a5'],
                                                'papeleria' => ['bg' => '#ffedd5', 'text' => '#9a3412', 'border' => '#fdba74'],
                                                'otros' => ['bg' => '#e0e7ff', 'text' => '#3730a3', 'border' => '#a5b4fc'],
                                            ];

                                            if (isset($categoryColorMap[$normalizedCategory])) {
                                                $categoryColor = $categoryColorMap[$normalizedCategory];
                                            } else {
                                                $categoryPalette = [
                                                    ['bg' => '#e0eeff', 'text' => '#0b4f86', 'border' => '#8fc0f0'],
                                                    ['bg' => '#e9f7ef', 'text' => '#166534', 'border' => '#86efac'],
                                                    ['bg' => '#fef3c7', 'text' => '#92400e', 'border' => '#fcd34d'],
                                                    ['bg' => '#ede9fe', 'text' => '#5b21b6', 'border' => '#c4b5fd'],
                                                    ['bg' => '#fee2e2', 'text' => '#991b1b', 'border' => '#fca5a5'],
                                                    ['bg' => '#d1fae5', 'text' => '#065f46', 'border' => '#6ee7b7'],
                                                ];
                                                $paletteIndex = ((int) sprintf('%u', crc32($normalizedCategory))) % count($categoryPalette);
                                                $categoryColor = $categoryPalette[$paletteIndex];
                                            }
                                        }
                                    @endphp
                                    <td class="text-center">
                                        <input type="checkbox" name="selected_reports[]" value="{{ $report->id }}" class="report-checkbox h-5 w-5 text-sofofa-blue border-slate-200 rounded-lg focus:ring-sofofa-blue cursor-pointer transition-all">
                                    </td>
                                    <td>
                                        <span class="font-bold text-slate-400 text-[11px] whitespace-nowrap">
                                            #{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-[11px] font-black text-slate-700 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200 shadow-sm leading-none whitespace-nowrap">
                                            {{ $report->expenses->first()->ceco->code ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span style="display:inline-flex;align-items:center;padding:0.38rem 0.7rem;border-radius:0.72rem;font-size:0.6rem;font-weight:900;background:{{ $categoryColor['bg'] }};color:{{ $categoryColor['text'] }};border:1px solid {{ $categoryColor['border'] }};text-transform:uppercase;letter-spacing:0.06em;white-space:nowrap;">
                                            {{ $categoryName }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="reports-title-cell block" style="{{ $isDuplicate ? 'color: #be123c;' : '' }}">{{ $report->title }}</span>
                                        @if($isDuplicate)
                                            @php
                                                $dupes = $report->duplicate_reports;
                                                $dupeText = $dupes->count() > 0 ? 'Repetida con rep. #' . $dupes->pluck('id')->implode(', #') : 'Repetida';
                                                $titleText = $dupes->count() > 0 ? 'Coincide con las rendiciones: #' . $dupes->pluck('id')->implode(', #') : 'Posible solicitud duplicada por coincidir mismo RUT y monto.';
                                            @endphp
                                            <span class="badge-duplicate" title="{{ $titleText }}">
                                                <svg style="width:10px;height:10px;margin-right:3px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                {{ $dupeText }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusMap = [
                                                'Borrador' => ['dot' => '#eab308', 'bg' => '#fffbeb', 'text' => '#92400e', 'border' => '#fde68a'],
                                                'Enviado' => ['dot' => '#2563eb', 'bg' => '#eff6ff', 'text' => '#1e40af', 'border' => '#bfdbfe'],
                                                'Pendiente aprobación jefatura' => ['dot' => '#2563eb', 'bg' => '#eff6ff', 'text' => '#1e40af', 'border' => '#bfdbfe'],
                                                'Subsanada por solicitante (jefatura)' => ['dot' => '#2563eb', 'bg' => '#eff6ff', 'text' => '#1e40af', 'border' => '#bfdbfe'],
                                                'Aprobada por jefatura' => ['dot' => '#1d4ed8', 'bg' => '#dbeafe', 'text' => '#1e3a8a', 'border' => '#93c5fd'],
                                                'En revisión' => ['dot' => '#4f46e5', 'bg' => '#eef2ff', 'text' => '#3730a3', 'border' => '#c7d2fe'],
                                                'Subsanada por solicitante (Gestor)' => ['dot' => '#4f46e5', 'bg' => '#eef2ff', 'text' => '#3730a3', 'border' => '#c7d2fe'],
                                                'Aprobada por gestor' => ['dot' => '#0f766e', 'bg' => '#ccfbf1', 'text' => '#115e59', 'border' => '#99f6e4'],
                                                'Pendiente pago' => ['dot' => '#d97706', 'bg' => '#fff7ed', 'text' => '#9a3412', 'border' => '#fed7aa'],
                                                'Reembolsada' => ['dot' => '#15803d', 'bg' => '#dcfce7', 'text' => '#166534', 'border' => '#86efac'],
                                                'Rechazada por jefatura' => ['dot' => '#dc2626', 'bg' => '#fef2f2', 'text' => '#b91c1c', 'border' => '#fecaca'],
                                                'Rechazada por gestor' => ['dot' => '#dc2626', 'bg' => '#fef2f2', 'text' => '#b91c1c', 'border' => '#fecaca'],
                                                'Observada por jefatura' => ['dot' => '#ea580c', 'bg' => '#fff7ed', 'text' => '#c2410c', 'border' => '#fed7aa'],
                                                'Observada por gestor' => ['dot' => '#ea580c', 'bg' => '#fff7ed', 'text' => '#c2410c', 'border' => '#fed7aa'],
                                                'Cerrada' => ['dot' => '#475569', 'bg' => '#f1f5f9', 'text' => '#334155', 'border' => '#cbd5e1'],
                                                'Anulada' => ['dot' => '#52525b', 'bg' => '#f4f4f5', 'text' => '#3f3f46', 'border' => '#d4d4d8'],
                                            ];
                                            $cfg = $statusMap[$report->status] ?? $statusMap['Borrador'];
                                        @endphp
                                        <span style="display:inline-flex;align-items:center;padding:0.5rem 0.7rem;border-radius:0.72rem;font-size:0.66rem;font-weight:900;background:{{ $cfg['bg'] }};color:{{ $cfg['text'] }};border:1px solid {{ $cfg['border'] }};box-shadow:0 1px 3px rgba(15,23,42,0.07);white-space:nowrap;">
                                            <span style="height:0.45rem;width:0.45rem;border-radius:999px;background:{{ $cfg['dot'] }};margin-right:0.45rem;"></span>
                                            {{ $report->status }}
                                        </span>
                                    </td>
                                    <td class="font-bold text-slate-500 text-[12px] whitespace-nowrap">
                                        {{ $report->user->name }}
                                    </td>
                                    <td>
                                        <div class="reports-amount">${{ number_format($report->total_amount, 0, ',', '.') }}</div>
                                    </td>
                                    <td class="text-center">
                                        <div class="text-slate-300 group-hover:text-sofofa-blue transition-all group-hover:translate-x-2 duration-300">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-8 py-24 text-center">
                                        <div class="flex flex-col items-center gap-6 grayscale opacity-20">
                                            <div class="bg-slate-50 p-8 rounded-full border border-slate-100">
                                                <svg class="h-16 w-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                            </div>
                                            <p class="text-slate-400 font-black uppercase tracking-[0.3em] text-[12px]">Sin registros encontrados</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        </div>
                    </div>
                    @if($reports->hasPages())
                    <div class="px-10 py-8 border-t border-slate-50 bg-slate-50/30">
                        {{ $reports->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.report-checkbox');
            const exportBtn = document.getElementById('export-btn');
            const clearBtn = document.getElementById('clear-filters');
            
            function updateExportBtn() {
                const checkedCount = document.querySelectorAll('.report-checkbox:checked').length;
                exportBtn.disabled = checkedCount === 0;
                if (checkedCount > 0) {
                    exportBtn.classList.remove('reports-toolbar-btn-neutral');
                    exportBtn.classList.add('reports-toolbar-btn-primary');
                } else {
                    exportBtn.classList.add('reports-toolbar-btn-neutral');
                    exportBtn.classList.remove('reports-toolbar-btn-primary');
                }
            }

            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    checkboxes.forEach(cb => {
                        cb.checked = selectAll.checked;
                    });
                    updateExportBtn();
                });
            }

            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateExportBtn);
            });

            // Filter logic
            const categorySelect = document.getElementById('category-select');
            const cecoSelect = document.getElementById('ceco-select');
            const statusSelect = document.getElementById('status-select');
            const searchInput = document.getElementById('search-input');

            function applyFilters() {
                const url = new URL(window.location.href);
                if (categorySelect && categorySelect.value) url.searchParams.set('category', categorySelect.value);
                else url.searchParams.delete('category');

                if (cecoSelect.value) url.searchParams.set('ceco', cecoSelect.value);
                else url.searchParams.delete('ceco');
                
                if (statusSelect.value) url.searchParams.set('status', statusSelect.value);
                else url.searchParams.delete('status');

                if (searchInput.value) url.searchParams.set('search', searchInput.value);
                else url.searchParams.delete('search');

                url.searchParams.delete('page'); // reset to page 1
                window.location.href = url.href;
            }

            if(clearBtn) {
                clearBtn.addEventListener('click', function() {
                    window.location.href = window.location.pathname;
                });
            }

            if (categorySelect) categorySelect.addEventListener('change', applyFilters);
            if (cecoSelect) cecoSelect.addEventListener('change', applyFilters);
            if (statusSelect) statusSelect.addEventListener('change', applyFilters);
            if (searchInput) searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    applyFilters();
                }
            });
        });
    </script>
</x-rendicion.layouts.app>
