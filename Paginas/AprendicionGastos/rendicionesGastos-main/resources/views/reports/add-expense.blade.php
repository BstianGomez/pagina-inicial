<x-layouts.app
    page_title="Vincular Gastos"
    page_subtitle="Selecciona gastos que ya tienes listos para añadir a esta rendición.">
    
    <style>
        .linking-container {
            max-width: 850px;
            margin: 0 auto;
        }

        .expense-list-item {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 1.25rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        }

        .expense-list-item:hover {
            border-color: #0f6bb6;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }

        .expense-icon-box {
            width: 56px;
            height: 56px;
            background: #f1f5f9;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #475569;
            flex-shrink: 0;
            transition: all 0.3s;
        }

        .expense-list-item:hover .expense-icon-box {
            background: #0f6bb6;
            color: white;
        }

        .expense-info {
            flex: 1;
        }

        .expense-amount {
            font-size: 1.25rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.02em;
        }

        .link-btn {
            background: #f1f5f9;
            color: #475569;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 0.85rem 1.5rem;
            border-radius: 14px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            border: none;
            cursor: pointer;
        }

        .link-btn:hover {
            background: #0f6bb6;
            color: white;
            box-shadow: 0 4px 12px rgba(15, 107, 182, 0.2);
        }

        .link-btn.linked {
            background: #22c55e;
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 5rem 2rem;
            background: white;
            border-radius: 32px;
            border: 2px dashed #e2e8f0;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.02);
        }

        .header-card {
            background: linear-gradient(135deg, #0f6bb6 0%, #0b5fa5 100%);
            border-radius: 32px;
            padding: 3rem;
            color: white;
            margin-bottom: 3rem;
            text-align: center;
            box-shadow: 0 20px 25px -5px rgba(15, 107, 182, 0.2);
        }
    </style>

    <div class="linking-container pb-20">
        <div class="header-card">
            <span class="text-[10px] font-black uppercase tracking-[0.2em] opacity-70 mb-4 block">Rendición #{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</span>
            <h2 class="text-3xl font-black mb-3 tracking-tight uppercase">{{ $report->title }}</h2>
            <p class="text-blue-100 font-medium">Selecciona los gastos que deseas vincular a esta rendición.</p>
        </div>

        <div class="bg-slate-50/50 rounded-[2.5rem] p-4 md:p-8">
            <div class="flex items-center justify-between mb-8 px-4">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Gastos Disponibles ({{ $availableExpenses->count() }})</h3>
                <a href="{{ route('expenses.drafts') }}" class="text-[10px] font-bold text-blue-600 hover:text-blue-700 uppercase tracking-widest flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Gestionar Borradores
                </a>
            </div>

            @if($availableExpenses->isEmpty())
                <div class="empty-state">
                    <div class="h-20 w-20 bg-blue-50 rounded-3xl flex items-center justify-center text-blue-500 mx-auto mb-6">
                        <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    </div>
                    <h4 class="text-xl font-black text-slate-900 mb-2">No tienes gastos listos</h4>
                    <p class="text-slate-500 font-medium max-w-xs mx-auto mb-8">Todos tus gastos ya están rendidos o aún están incompletos en borradores.</p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-8">
                        <a href="{{ route('expenses.drafts') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-blue-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/20">
                            Ir a completar borradores
                        </a>
                        <a href="{{ route('reports.show', $report) }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white border-2 border-slate-200 text-slate-600 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-slate-50 transition-all">
                            Aceptar y Volver
                        </a>
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($availableExpenses as $exp)
                        <div class="expense-list-item" id="expense-row-{{ $exp->id }}">
                            <div class="expense-icon-box">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                            </div>
                            <div class="expense-info">
                                <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.1em] mb-1">{{ $exp->category->name }}</p>
                                <p class="text-base font-bold text-slate-900 mb-1">{{ $exp->reason }}</p>
                                <div class="flex items-center gap-3 text-xs text-slate-500 font-medium">
                                    <span>{{ \Carbon\Carbon::parse($exp->expense_date)->format('d/m/Y') }}</span>
                                    <span class="h-1 w-1 bg-slate-300 rounded-full"></span>
                                    <span>{{ $exp->provider_name }}</span>
                                </div>
                            </div>
                            <div class="text-right px-6 border-l border-slate-100">
                                <p class="expense-amount">${{ number_format($exp->amount, 0, ',', '.') }}</p>
                            </div>
                            <button onclick="toggleExpenseLinking({{ $exp->id }}, this)" class="link-btn">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
                                Vincular
                            </button>
                        </div>
                    @endforeach
                </div>

                <div class="mt-16 text-center">
                    <a href="{{ route('reports.show', $report) }}" class="inline-flex items-center gap-3 px-12 py-5 bg-slate-900 text-white rounded-2xl text-xs font-black uppercase tracking-[0.2em] hover:bg-slate-800 transition-all shadow-2xl shadow-slate-200">
                        Finalizar y Ver Informe
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        async function toggleExpenseLinking(expenseId, btn) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            btn.disabled = true;
            btn.innerHTML = 'Procesando...';

            try {
                const response = await fetch(`/gastos/crear/detalles/{{ $report->id }}/toggle/${expenseId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                if (data.success) {
                    if (data.action === 'added') {
                        btn.innerHTML = '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg> Vinculado';
                        btn.classList.add('linked');
                    } else {
                        btn.innerHTML = '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg> Vincular';
                        btn.classList.remove('linked');
                    }
                } else {
                    alert('Error: ' + (data.message || 'No se pudo vincular el gasto'));
                }
            } catch (error) {
                console.error(error);
                alert('Ocurrió un error al vincular el gasto.');
            } finally {
                btn.disabled = false;
            }
        }
    </script>
</x-layouts.app>
