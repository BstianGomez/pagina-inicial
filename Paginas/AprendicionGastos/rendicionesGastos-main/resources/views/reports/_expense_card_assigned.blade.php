@php
    $catName = $expense->category->name ?? 'Gasto';
    $normalized = \Illuminate\Support\Str::of($catName)->ascii()->lower()->trim()->value();
    $colorMap = [
        'alimentacion'            => ['bg' => '#fef3c7', 'text' => '#92400e', 'border' => '#fcd34d'],
        'movilizacion'            => ['bg' => '#dcfce7', 'text' => '#166534', 'border' => '#86efac'],
        'hospedaje'               => ['bg' => '#fee2e2', 'text' => '#991b1b', 'border' => '#fca5a5'],
        'papeleria'               => ['bg' => '#ffedd5', 'text' => '#9a3412', 'border' => '#fdba74'],
        'insumos computacionales' => ['bg' => '#ede9fe', 'text' => '#5b21b6', 'border' => '#c4b5fd'],
        'pasajes aereos'          => ['bg' => '#e0f2fe', 'text' => '#0c4a6e', 'border' => '#7dd3fc'],
    ];
    $cp = $colorMap[$normalized] ?? ['bg' => '#e0e7ff', 'text' => '#3730a3', 'border' => '#a5b4fc'];

    // Build the "available card" HTML that will be injected into the DOM if this expense is removed
    $availableCardHtml = view('reports._expense_card_available', ['expense' => $expense, 'report' => $report])->render();
    $toggleUrl = route('reports.toggleExpense', [$report, $expense]);
@endphp

<div class="assigned-expense-card" data-amount="{{ $expense->amount }}">
    <div class="h-9 w-9 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
        <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
        </svg>
    </div>
    <div class="flex-1 min-w-0">
        <div class="flex items-center gap-2 flex-wrap">
            <span class="cat-badge" style="background:{{ $cp['bg'] }};color:{{ $cp['text'] }};border-color:{{ $cp['border'] }};">{{ $catName }}</span>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $expense->ceco->code ?? '—' }}</span>
        </div>
        <p class="text-sm font-bold text-slate-800 mt-0.5 truncate">{{ $expense->reason }}</p>
        <p class="text-xs text-slate-500">{{ $expense->provider_name }} · {{ \Carbon\Carbon::parse($expense->expense_date)->format('d/m/Y') }}</p>
    </div>
    <div class="text-right flex-shrink-0">
        <p class="text-base font-black text-slate-900">${{ number_format($expense->amount, 0, ',', '.') }}</p>
        <p class="text-[10px] text-slate-400 font-semibold">{{ $expense->document_type }}</p>
    </div>
    <button type="button" title="Quitar de esta rendición"
        onclick='removeExpense(this, @json($toggleUrl), @json($availableCardHtml))'
        class="ml-2 h-8 w-8 rounded-xl bg-rose-50 border border-rose-100 text-rose-400 hover:bg-rose-100 hover:text-rose-600 flex items-center justify-center transition-all flex-shrink-0">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>
