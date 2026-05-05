<x-rendicion.layouts.app
    page_title="Nueva Rendición"
    page_subtitle="Paso 2 de 2 — Información general y confirmación.">
    <x-slot name="header_title">Nueva Rendición - Paso 2</x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-8 border-b border-slate-100 bg-slate-50/50">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('rendicion.expenses.createStep2', $report->id) }}" title="Volver al paso 1"
                           style="display:flex;align-items:center;justify-content:center;height:2.5rem;width:2.5rem;border-radius:9999px;background:#22c55e;color:#fff;font-weight:700;text-decoration:none;flex-shrink:0;"
                           onmouseover="this.style.background='#16a34a'" onmouseout="this.style.background='#22c55e'">
                            1
                        </a>
                        <div class="h-1 w-20 bg-emerald-500 rounded"></div>
                        <div class="h-10 w-10 rounded-full bg-sofofa-blue text-white flex items-center justify-center font-bold">2</div>
                    </div>
                    <span class="text-sm font-medium text-slate-500">Paso 2 de 2</span>
                </div>
                <h3 class="text-xl font-bold text-slate-800">Información General</h3>
                <p class="text-slate-500 mt-1">Complete el contexto final de la rendición antes de enviarla.</p>
            </div>

            <form action="{{ route('rendicion.expenses.storeStep1', $report->id) }}" method="POST" class="p-8 space-y-6">
                @csrf
                    <div>
                        <label for="title" class="block text-sm font-semibold text-slate-700 mb-2">Título del Informe</label>
                        <input type="text" name="title" id="title" required placeholder="Ej: Viaje a Concepción Abril 2024"
                            value="{{ old('title', $report->title === 'Borrador de rendición' ? '' : $report->title) }}"
                            class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-sofofa-blue focus:ring-sofofa-blue transition-shadow shadow-sm @error('title') border-red-500 @enderror">
                        @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
    
                    <div>
                        <label for="ceco_id" class="block text-sm font-semibold text-slate-700 mb-2">Centro de Costo (CECO)</label>
                        <select name="ceco_id" id="ceco_id"
                            class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-sofofa-blue focus:ring-sofofa-blue transition-shadow shadow-sm @error('ceco_id') border-red-500 @enderror">
                            <option value="">Seleccione un CECO</option>
                            @foreach($cecos as $ceco)
                                <option value="{{ $ceco->id }}" {{ old('ceco_id', $report->ceco_id) == $ceco->id ? 'selected' : '' }}>{{ $ceco->code }} - {{ $ceco->name }}</option>
                            @endforeach
                        </select>
                        @error('ceco_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                <div class="pt-4 flex flex-col sm:flex-row gap-3 sm:justify-end">
                    <button type="submit" name="action" value="draft" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 font-black text-[10px] uppercase tracking-widest transition-all">
                        Guardar Borrador
                    </button>
                    <button type="submit" name="action" value="submit" class="btn-premium py-3 px-8 flex items-center justify-center space-x-2">
                        <span>Continuar</span>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-rendicion.layouts.app>
