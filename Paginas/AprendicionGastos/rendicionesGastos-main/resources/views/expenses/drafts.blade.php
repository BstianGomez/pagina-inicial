<x-layouts.app page_title="Borradores de Gastos" page_subtitle="Gestiona y completa tus gastos antes de rendirlos.">
    <style>
        .draft-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .draft-header-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 24px;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .draft-title-group h2 {
            font-size: 1.5rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }

        .draft-title-group p {
            color: #64748b;
            font-size: 0.95rem;
        }

        .draft-actions-group {
            display: flex;
            gap: 12px;
        }

        .btn-premium {
            padding: 12px 24px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-premium-primary {
            background: linear-gradient(135deg, #0f6bb6 0%, #1e40af 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(15, 107, 182, 0.3);
        }

        .btn-premium-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(15, 107, 182, 0.4);
        }

        .btn-premium-outline {
            background: white;
            color: #0f6bb6;
            border: 2px solid #e2e8f0;
        }

        .btn-premium-outline:hover {
            border-color: #0f6bb6;
            background: #f0f7ff;
        }

        .draft-table-wrapper {
            background: white;
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
            overflow: hidden;
        }

        .table-premium {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-premium th {
            background: #f8fafc;
            padding: 18px 24px;
            text-align: left;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #64748b;
            border-bottom: 1px solid #e2e8f0;
        }

        .table-premium td {
            padding: 20px 24px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .table-premium tr:last-child td {
            border-bottom: none;
        }

        .table-premium tr:hover td {
            background-color: #fcfdfe;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            gap: 6px;
        }

        .status-ready {
            background-color: #dcfce7;
            color: #15803d;
        }

        .status-ready::before {
            content: '';
            width: 6px;
            height: 6px;
            background: #15803d;
            border-radius: 50%;
        }

        .status-draft {
            background-color: #fef3c7;
            color: #b45309;
        }

        .status-draft::before {
            content: '';
            width: 6px;
            height: 6px;
            background: #b45309;
            border-radius: 50%;
        }

        .category-chip {
            background: #f1f5f9;
            color: #475569;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .amount-text {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: #0f172a;
        }

        .action-btn {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .btn-edit {
            background: #eff6ff;
            color: #2563eb;
        }

        .btn-edit:hover {
            background: #2563eb;
            color: white;
            transform: scale(1.1);
        }

        .btn-delete {
            background: #fff1f2;
            color: #e11d48;
        }

        .btn-delete:hover {
            background: #e11d48;
            color: white;
            transform: scale(1.1);
        }

        .empty-state {
            padding: 80px 40px;
            text-align: center;
        }

        .empty-icon {
            width: 80px;
            height: 80px;
            background: #f1f5f9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            color: #94a3b8;
        }

        .empty-state h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .empty-state p {
            color: #64748b;
            max-width: 320px;
            margin: 0 auto 24px;
        }
    </style>

    <div class="draft-container">
        <div class="draft-header-card">
            <div class="draft-title-group">
                <h2>Mis Gastos Pendientes</h2>
                <p>Gestiona tus gastos antes de incluirlos en una rendición oficial.</p>
            </div>
            <div class="draft-actions-group">
                <a href="{{ route('expenses.index') }}" class="btn-premium btn-premium-outline">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2.5" stroke-linecap="round"/></svg>
                    Carga Masiva
                </a>
                <a href="{{ route('expenses.create') }}" class="btn-premium btn-premium-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Crear Rendición
                </a>
            </div>
        </div>

        <div class="draft-table-wrapper">
            <table class="table-premium">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Categoría</th>
                        <th>Motivo</th>
                        <th style="text-align: right;">Monto</th>
                        <th style="text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                        <tr>
                            <td>
                                <div style="display: flex; flex-direction: column;">
                                    <span style="font-weight: 700; color: #1e293b; font-size: 0.9rem;">
                                        {{ $expense->expense_date ? $expense->expense_date->format('d/m/Y') : 'Sin definir' }}
                                    </span>
                                    <span style="font-size: 0.75rem; color: #94a3b8;">
                                        Hace {{ $expense->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                @if($expense->status === \App\Models\Expense::STATUS_READY)
                                    <span class="status-badge status-ready">Listo</span>
                                @else
                                    <span class="status-badge status-draft">Borrador</span>
                                @endif
                            </td>
                            <td>
                                <span class="category-chip">
                                    {{ $expense->category ? $expense->category->name : 'Sin categoría' }}
                                </span>
                            </td>
                            <td>
                                <div style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 0.9rem; color: #475569; font-weight: 500;">
                                    {{ $expense->reason ?? 'Sin motivo especificado' }}
                                </div>
                            </td>
                            <td style="text-align: right;">
                                <span class="amount-text">
                                    ${{ number_format($expense->amount ?? 0, 0, ',', '.') }}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <div style="display: flex; justify-content: center; gap: 10px;">
                                    <a href="{{ route('expenses.edit', $expense) }}" class="action-btn btn-edit" title="Editar Información">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('expenses.destroy', $expense) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este gasto permanentemente?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn btn-delete" title="Eliminar Gasto">
                                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                                    </div>
                                    <h3>No hay gastos pendientes</h3>
                                    <p>Tus borradores aparecerán aquí cuando los guardes sin incluirlos en una rendición.</p>
                                    <a href="{{ route('expenses.index') }}" class="btn-premium btn-premium-primary">Empezar a Cargar</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($expenses->hasPages())
            <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end;">
                {{ $expenses->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
