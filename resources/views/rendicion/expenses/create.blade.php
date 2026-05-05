<x-rendicion.layouts.app
    page_title="Nueva Rendición de Gasto"
    page_subtitle="Complete la información solicitada para procesar su devolución.">
    <x-slot name="header_title">Nueva Rendición de Gasto</x-slot>

    <div class="max-w-3xl mx-auto">

        <div class="card-premium">
            <div class="p-8 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-xl font-bold text-slate-800">Detalles del Gasto</h3>
                <p class="text-sm text-slate-500 mt-1">Complete la información requerida para procesar su devolución.</p>
            </div>

            <form method="GET" action="/dashboard" class="p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Fecha -->
                    <div>
                        <label for="date" class="block text-sm font-semibold text-slate-700 mb-2">Fecha del Gasto</label>
                        <input type="date" name="date" id="date" class="input-premium" max="{{ date('Y-m-d') }}" required>
                    </div>

                    <!-- Monto -->
                    <div>
                        <label for="amount" class="block text-sm font-semibold text-slate-700 mb-2">Monto ($)</label>
                        <div class="relative group">
                        <input type="text" name="amount" id="amount" data-type="currency" oninput="formatCurrency(this)"
                            value="{{ old('amount', ($expense->amount ?? 0) > 0 ? number_format($expense->amount, 0, ',', '.') : '') }}"
                            class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl py-4 pl-6 pr-6 text-sm font-bold text-slate-700 outline-none focus:border-sofofa-blue focus:bg-white transition-all peer placeholder-transparent"
                            placeholder="Ej: 15.000" required>
                    </div>
                    </div>
                </div>

                <!-- Categoría -->
                <div>
                    <label for="category" class="block text-sm font-semibold text-slate-700 mb-2">Categoría</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="category" value="food" class="peer sr-only">
                            <div class="p-4 border rounded-xl flex flex-col items-center space-y-2 hover:border-sofofa-blue peer-checked:border-sofofa-blue peer-checked:bg-sofofa-blue/5 transition-all">
                                <svg class="h-6 w-6 text-slate-400 peer-checked:text-sofofa-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span class="text-xs font-medium text-slate-600">Alimentación</span>
                            </div>
                        </label>

                        <label class="relative cursor-pointer">
                            <input type="radio" name="category" value="transport" class="peer sr-only">
                            <div class="p-4 border rounded-xl flex flex-col items-center space-y-2 hover:border-sofofa-blue peer-checked:border-sofofa-blue peer-checked:bg-sofofa-blue/5 transition-all">
                                <svg class="h-6 w-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                <span class="text-xs font-medium text-slate-600">Transporte</span>
                            </div>
                        </label>

                        <label class="relative cursor-pointer">
                            <input type="radio" name="category" value="lodging" class="peer sr-only">
                            <div class="p-4 border rounded-xl flex flex-col items-center space-y-2 hover:border-sofofa-blue peer-checked:border-sofofa-blue peer-checked:bg-sofofa-blue/5 transition-all">
                                <svg class="h-6 w-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                <span class="text-xs font-medium text-slate-600">Alojamiento</span>
                            </div>
                        </label>

                        <label class="relative cursor-pointer">
                            <input type="radio" name="category" value="other" class="peer sr-only" checked>
                            <div class="p-4 border rounded-xl flex flex-col items-center space-y-2 hover:border-sofofa-blue peer-checked:border-sofofa-blue peer-checked:bg-sofofa-blue/5 transition-all">
                                <svg class="h-6 w-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                </svg>
                                <span class="text-xs font-medium text-slate-600">Otros</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Descripción -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-slate-700 mb-2">Descripción / Motivo</label>
                    <textarea name="description" id="description" rows="3" class="input-premium resize-none" placeholder="Indique brevemente el motivo del gasto..."></textarea>
                </div>

                <!-- Comprobante -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Cargar Comprobante (Boleta/Factura)</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl hover:border-sofofa-blue transition-colors cursor-pointer group">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-slate-400 group-hover:text-sofofa-blue transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-slate-600">
                                <span class="relative cursor-pointer bg-white rounded-md font-semibold text-sofofa-blue hover:text-sofofa-blue-dark">Suelte el archivo aquí</span>
                                <p class="pl-1">o haga clic para buscar</p>
                            </div>
                            <p class="text-xs text-slate-500">PNG, JPG, PDF hasta 10MB</p>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-slate-100">
                    <button type="button" onclick="window.history.back()" class="px-6 py-3 text-sm font-semibold text-slate-600 hover:text-slate-800 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-premium px-8">
                        Enviar Rendición
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-rendicion.layouts.app>
