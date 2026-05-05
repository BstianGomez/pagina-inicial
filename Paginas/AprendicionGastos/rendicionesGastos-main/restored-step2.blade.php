<x-layouts.app
    page_title="Nueva Rendición"
    page_subtitle="Paso 2 de 2 — Selección de gastos.">
    <x-slot name="header_title">Nueva Rendición - Paso 2</x-slot>

    <style>
        .expense-row {
            border: 2px solid #e2e8f0;
            border-radius: 1rem;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            background: #fff;
            transition: border-color 180ms ease, background 180ms ease, opacity 180ms ease;
        }
        .expense-row.is-available { cursor: pointer; }
        .expense-row.is-available:hover { border-color: #0f6bb6; background: #f0f7ff; }
        .expense-row.is-assigned  { border-color: #86efac; background: #f0fdf4; cursor: default; }
        .expense-row.is-loading   { opacity: 0.5; pointer-events: none; }
        .cat-badge {
            display: inline-flex; align-items: center;
            padding: 0.2rem 0.55rem; border-radius: 0.5rem;
            font-size: 0.6rem; font-weight: 800; text-transform: uppercase;
            letter-spacing: 0.06em; white-space: nowrap; border: 1px solid #e2e8f0;
        }
        .step-btn {
            height: 2.5rem; width: 2.5rem; border-radius: 9999px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 0.95rem;
            transition: transform 180ms ease; text-decoration: none;
        }
        .step-btn:hover { transform: scale(1.1); }
        #assigned-panel { display: none; }
        #assigned-panel.has-items { display: block; }
        #no-next-hint { display: block; }
        #btn-next { display: none; }
        #step2-dot  { background: #e2e8f0; color: #94a3b8; cursor: not-allowed; }
        #connector  { background: #cbd5e1; }
    </style>

    <div class="max-w-3xl mx-auto space-y-6">

        {{-- Step Indicator --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('expenses.createStep1', $report->id) }}" title="Volver a información general"
                        style="display:flex;align-items:center;justify-content:center;height:2.5rem;width:2.5rem;border-radius:9999px;background:#22c55e;color:#fff;font-weight:800;text-decoration:none;flex-shrink:0;font-size:0.95rem;transition:transform 180ms ease;"
                        onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">1</a>
                    <div id="connector" style="height:0.25rem;width:5rem;border-radius:9999px;flex-shrink:0;background:#10b981;"></div>
                    <span id="step2-dot" class="step-btn" style="background:#0f6bb6;color:#fff;">2</span>
                </div>
                <span class="text-sm font-medium text-slate-500">Paso 2 de 2</span>
            </div>
            <h3 class="text-xl font-bold text-slate-800">Selección de Gastos</h3>
            <p class="text-slate-500 mt-1">Selecciona los gastos que deseas incluir en esta rendición.</p>
        </div>

        {{-- Gastos ya asignados --}}
        <div id="assigned-panel" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden {{ $assignedExpenses->count() > 0 ? 'has-items' : '' }}">
            <div class="px-6 py-4 border-b border-slate-100 bg-emerald-50/50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="h-5 w-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h4 class="text-sm font-black text-emerald-700 uppercase tracking-widest">
                        Incluidos (<span id="assigned-count">{{ $assignedExpenses->count() }}</span>)
                    </h4>
                </div>
                <span id="assigned-total" class="text-sm font-black text-emerald-700 bg-emerald-100 border border-emerald-200 px-3 py-1 rounded-lg">
                    ${{ number_format($assignedExpenses->sum('amount'), 0, ',', '.') }}
                </span>
            </div>
            <div id="assigned-list" class="p-4 space-y-3"></div>
        </div>

        {{-- Gastos disponibles --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                <svg class="h-5 w-5 text-sofofa-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <h4 class="text-sm font-black text-slate-600 uppercase tracking-widest">
                    Disponibles (<span id="available-count">{{ $availableExpenses->count() }}</span>)
                </h4>
            </div>
            <div id="available-list" class="p-4 space-y-3">
                @if($availableExpenses->isEmpty() && $assignedExpenses->isEmpty())
                <div class="py-14 text-center">
                    <p class="text-sm font-bold text-slate-400">No hay gastos disponibles.</p>
                    <p class="text-xs text-slate-400 mt-1">Los gastos en rendiciones enviadas o reembolsadas no pueden moverse.</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Acciones --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <a href="{{ route('dashboard') }}" class="text-sm font-bold text-slate-400 hover:text-red-500 transition-colors">Cancelar</a>
            <span id="no-next-hint" class="text-xs text-slate-400 font-semibold">Selecciona al menos un gasto para continuar.</span>
            
            <form action="{{ route('expenses.storeStep2', $report) }}" method="POST" id="form-submit-report" class="m-0">
                @csrf
                <input type="hidden" name="action" value="submit">
                <button type="submit" id="btn-next"
                   class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-sofofa-blue text-white font-black text-[10px] uppercase tracking-widest shadow-lg shadow-blue-500/20 hover:bg-sofofa-blue-dark active:scale-95 transition-all">
                    Enviar Rendición
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    {{-- Pre-render all expense cards as JSON for JS --}}
    @php
        $cmap = [
            'alimentacion'            => ['bg'=>'#fef3c7','text'=>'#92400e','border'=>'#fcd34d'],
            'movilizacion'            => ['bg'=>'#dcfce7','text'=>'#166534','border'=>'#86efac'],
            'hospedaje'               => ['bg'=>'#fee2e2','text'=>'#991b1b','border'=>'#fca5a5'],
            'papeleria'               => ['bg'=>'#ffedd5','text'=>'#9a3412','border'=>'#fdba74'],
            'insumos computacionales' => ['bg'=>'#ede9fe','text'=>'#5b21b6','border'=>'#c4b5fd'],
            'pasajes aereos'          => ['bg'=>'#e0f2fe','text'=>'#0c4a6e','border'=>'#7dd3fc'],
        ];
        $allExpenses = $assignedExpenses->map(fn($e) => ['id'=>$e->id,'assigned'=>true,'amount'=>$e->amount,'url'=>route('reports.toggleExpense',[$report,$e]),'cat'=>$e->category->name??'Gasto','ceco'=>$e->ceco->code??'—','reason'=>$e->reason,'provider'=>$e->provider_name,'date'=>\Carbon\Carbon::parse($e->expense_date)->format('d/m/Y'),'doctype'=>$e->document_type,'from'=>''])
            ->concat($availableExpenses->map(fn($e) => ['id'=>$e->id,'assigned'=>false,'amount'=>$e->amount,'url'=>route('reports.toggleExpense',[$report,$e]),'cat'=>$e->category->name??'Gasto','ceco'=>$e->ceco->code??'—','reason'=>$e->reason,'provider'=>$e->provider_name,'date'=>\Carbon\Carbon::parse($e->expense_date)->format('d/m/Y'),'doctype'=>$e->document_type,'from'=>$e->report->title??'—']));
    @endphp

    <script>
    (function () {
        const CSRF   = document.querySelector('meta[name="csrf-token"]').content;
        const EXPENSES = @json($allExpenses->values());
        const colorMap = @json($cmap);

        const assignedList   = document.getElementById('assigned-list');
        const availableList  = document.getElementById('available-list');
        const assignedPanel  = document.getElementById('assigned-panel');
        const assignedCount  = document.getElementById('assigned-count');
        const assignedTotal  = document.getElementById('assigned-total');
        const availableCount = document.getElementById('available-count');
        const btnNext        = document.getElementById('btn-next');
        const noNextHint     = document.getElementById('no-next-hint');
        const step2dot       = document.getElementById('step2-dot');
        const connector      = document.getElementById('connector');

        function getColor(catName) {
            const key = catName.normalize('NFD').replace(/[\u0300-\u036f]/g,'').toLowerCase().trim();
            return colorMap[key] || { bg:'#e0e7ff', text:'#3730a3', border:'#a5b4fc' };
        }

        function buildCard(exp) {
            const cp = getColor(exp.cat);
            const badgeStyle = `background:${cp.bg};color:${cp.text};border-color:${cp.border};`;
            const amount = '$' + Number(exp.amount).toLocaleString('es-CL');
            const fromTag = exp.assigned ? '' : `<span style="font-size:0.6rem;font-weight:700;color:#cbd5e1;text-transform:uppercase;letter-spacing:0.06em;">De: ${exp.from}</span>`;

            const iconAssigned = `<div class="h-9 w-9 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0"><svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg></div>`;
            const iconAvail    = `<div class="h-9 w-9 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center flex-shrink-0"><svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg></div>`;

            const removeBtn = `<button type="button" onclick="toggle(${exp.id})" title="Quitar" class="ml-2 h-8 w-8 rounded-xl bg-rose-50 border border-rose-100 text-rose-400 hover:bg-rose-100 hover:text-rose-600 flex items-center justify-center transition-all flex-shrink-0"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg></button>`;

            const cls = exp.assigned ? 'is-assigned' : 'is-available';
            const clickAttr = exp.assigned ? '' : `onclick="toggle(${exp.id})"`;
            const actionEl = exp.assigned ? removeBtn : '';

            return `<div id="exp-${exp.id}" class="expense-row ${cls}" ${clickAttr}>
                ${exp.assigned ? iconAssigned : iconAvail}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="cat-badge" style="${badgeStyle}">${exp.cat}</span>
                        <span style="font-size:0.625rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em;">${exp.ceco}</span>
                        ${fromTag}
                    </div>
                    <p class="text-sm font-bold text-slate-800 mt-0.5">${exp.reason}</p>
                    <p class="text-xs text-slate-500">${exp.provider} · ${exp.date}</p>
                </div>
                <div style="text-align:right;flex-shrink:0;">
                    <p style="font-size:1rem;font-weight:900;color:#0f172a;">${amount}</p>
                    <p style="font-size:0.6rem;color:#94a3b8;font-weight:600;">${exp.doctype}</p>
                </div>
                ${actionEl}
            </div>`;
        }

        // Initial render
        const state = {};
        EXPENSES.forEach(exp => { state[exp.id] = exp; });

        function renderAll() {
            assignedList.innerHTML  = '';
            availableList.innerHTML = '';
            let totalAmt = 0, assignedNum = 0, availableNum = 0;

            Object.values(state).forEach(exp => {
                if (exp.assigned) {
                    assignedList.insertAdjacentHTML('beforeend', buildCard(exp));
                    totalAmt += Number(exp.amount);
                    assignedNum++;
                } else {
                    availableList.insertAdjacentHTML('beforeend', buildCard(exp));
                    availableNum++;
                }
            });

            if (availableNum === 0 && assignedNum === 0) {
                availableList.innerHTML = '<div class="py-14 text-center"><p class="text-sm font-bold text-slate-400">No hay gastos disponibles.</p></div>';
            }

            assignedCount.textContent  = assignedNum;
            availableCount.textContent = availableNum;
            assignedTotal.textContent  = '$' + totalAmt.toLocaleString('es-CL');

            if (assignedNum > 0) {
                assignedPanel.classList.add('has-items');
                btnNext.style.display    = 'inline-flex';
                noNextHint.style.display = 'none';
                step2dot.style.cssText   = 'background:#22c55e;color:#fff;cursor:default;';
                step2dot.onclick = () => { /* noop on the button submit flow */ };
                connector.style.background = '#22c55e';
            } else {
                assignedPanel.classList.remove('has-items');
                btnNext.style.display    = 'none';
                noNextHint.style.display = 'block';
                step2dot.style.cssText   = 'background:#e2e8f0;color:#94a3b8;cursor:not-allowed;';
                step2dot.onclick = null;
                connector.style.background = '#cbd5e1';
            }
        }

        window.toggle = function(id) {
            const exp = state[id];
            if (!exp) return;
            const el = document.getElementById('exp-' + id);
            if (el) el.classList.add('is-loading');

            fetch(exp.url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    state[id].assigned = !state[id].assigned;
                    state[id].from     = state[id].assigned ? '' : (state[id].from || '—');
                    renderAll();
                } else {
                    if (el) el.classList.remove('is-loading');
                }
            })
            .catch(() => {
                if (el) el.classList.remove('is-loading');
            });
        };

        renderAll();
    })();
    </script>
</x-layouts.app>
