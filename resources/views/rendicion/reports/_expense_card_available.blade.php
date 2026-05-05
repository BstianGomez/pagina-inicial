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

    // Build the "assigned card" HTML that will be injected into the DOM if this expense is added
    $assignedCardHtml = view('reports._expense_card_assigned', ['expense' => $expense, 'report' => $report])->render();
    $toggleUrl = route('rendicion.reports.toggleExpense', [$report, $expense]);
@endphp

<div class="expense-card" onclick='addExpense(this, @json($toggleUrl), @json($assignedCardHtml))'>
    <div class="h-9 w-9 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0 border border-blue-100">
        <svg class="h-5 w-5 text-sofofa-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
        </svg>
    </div>
    <div class="flex-1 min-w-0">
        <div class="flex items-center gap-2 flex-wrap">
            <span class="cat-badge" style="background:{{ $cp['bg'] }};color:{{ $cp['text'] }};border-color:{{ $cp['border'] }};">{{ $catName }}</span>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $expense->ceco->code ?? '—' }}</span>
            @if($expense->status === \App\Models\Rendicion\Expense::STATUS_DRAFT)
                <a href="{{ route('rendicion.expenses.edit', $expense) }}" onclick="event.stopPropagation();" class="text-[9px] font-bold text-sofofa-blue uppercase tracking-widest hover:underline bg-blue-50 px-2 py-0.5 rounded">Editar</a>
            @else
                <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">De: {{ $expense->report->title ?? '—' }}</span>
            @endif
        </div>
        <p class="text-sm font-bold text-slate-800 mt-0.5 truncate">{{ $expense->reason }}</p>
        <p class="text-xs text-slate-500">{{ $expense->provider_name }} · {{ \Carbon\Carbon::parse($expense->expense_date)->format('d/m/Y') }}</p>
    </div>
    <div class="text-right flex-shrink-0">
        <p class="text-base font-black text-slate-900">${{ number_format($expense->amount, 0, ',', '.') }}</p>
        <p class="text-[10px] text-slate-400 font-semibold">{{ $expense->document_type }}</p>
    </div>
    <div class="ml-2 h-8 w-8 rounded-xl bg-blue-50 border border-blue-100 text-sofofa-blue flex items-center justify-center flex-shrink-0 transition-all pointer-events-none">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
        </svg>
    </div>
</div>
