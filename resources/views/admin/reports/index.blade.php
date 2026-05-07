<x-app-layout>
    <div class="py-12 px-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h2 class="text-4xl font-black text-slate-900 tracking-tight mb-2">Panel de Control Global</h2>
                    <p class="text-slate-500 font-medium">Visualiza el estado de todas las plataformas y exporta datos consolidados.</p>
                </div>
                <div class="flex gap-3">
                    <button class="px-6 py-3 bg-white border border-slate-200 rounded-2xl font-bold text-slate-700 hover:bg-slate-50 transition-all flex items-center gap-2 shadow-sm">
                        <i class="fas fa-calendar"></i>
                        <span>Últimos 30 días</span>
                    </button>
                    <button class="px-6 py-3 bg-blue-600 rounded-2xl font-bold text-white hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 flex items-center gap-2">
                        <i class="fas fa-sync"></i>
                        <span>Actualizar</span>
                    </button>
                </div>
            </div>

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-4 text-xl">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="text-3xl font-black text-slate-900">{{ $stats['total_users'] }}</div>
                    <div class="text-sm font-bold text-slate-400 uppercase tracking-widest mt-1">Usuarios Totales</div>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-4 text-xl">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="text-3xl font-black text-slate-900">{{ $stats['oc_count'] }}</div>
                    <div class="text-sm font-bold text-slate-400 uppercase tracking-widest mt-1">Solicitudes OC</div>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                    <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center mb-4 text-xl">
                        <i class="fas fa-plane"></i>
                    </div>
                    <div class="text-3xl font-black text-slate-900">{{ $stats['viajes_count'] }}</div>
                    <div class="text-sm font-bold text-slate-400 uppercase tracking-widest mt-1">Viajes Activos</div>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-4 text-xl">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <div class="text-3xl font-black text-slate-900">{{ $stats['rendicion_count'] }}</div>
                    <div class="text-sm font-bold text-slate-400 uppercase tracking-widest mt-1">Rendiciones</div>
                </div>
            </div>

            <!-- Application Management -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- OC CARD -->
                <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden flex flex-col">
                    <div class="p-8 bg-blue-600 text-white">
                        <div class="flex justify-between items-start mb-6">
                            <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-2xl">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-bold uppercase tracking-widest">Activo</span>
                        </div>
                        <h3 class="text-2xl font-black mb-1">Órdenes de Compra</h3>
                        <p class="text-blue-100/80 font-medium">Módulo de adquisiciones y proveedores.</p>
                    </div>
                    <div class="p-8 flex-1 flex flex-col">
                        <div class="space-y-4 mb-8">
                            <div class="flex justify-between items-center text-sm font-bold">
                                <span class="text-slate-400 uppercase tracking-widest">Estado Servidor</span>
                                <span class="text-emerald-500 flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    ONLINE
                                </span>
                            </div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-blue-500 h-full w-[85%] rounded-full"></div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mt-auto">
                            <a href="{{ route('admin.reports.download', 'oc') }}" class="flex flex-col items-center justify-center p-4 bg-slate-50 rounded-2xl border-2 border-transparent hover:border-blue-500/20 hover:bg-white transition-all group">
                                <i class="fas fa-download text-blue-600 mb-2 group-hover:scale-110 transition-transform"></i>
                                <span class="text-xs font-black text-slate-900">DATOS</span>
                            </a>
                            <button class="flex flex-col items-center justify-center p-4 bg-slate-50 rounded-2xl border-2 border-transparent hover:border-blue-500/20 hover:bg-white transition-all group">
                                <i class="fas fa-paper-plane text-blue-600 mb-2 group-hover:scale-110 transition-transform"></i>
                                <span class="text-xs font-black text-slate-900">ENVIAR</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- VIAJES CARD -->
                <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden flex flex-col">
                    <div class="p-8 bg-indigo-600 text-white">
                        <div class="flex justify-between items-start mb-6">
                            <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-2xl">
                                <i class="fas fa-globe-americas"></i>
                            </div>
                            <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-bold uppercase tracking-widest">Activo</span>
                        </div>
                        <h3 class="text-2xl font-black mb-1">Gestión de Viajes</h3>
                        <p class="text-indigo-100/80 font-medium">Control de viáticos y solicitudes.</p>
                    </div>
                    <div class="p-8 flex-1 flex flex-col">
                        <div class="space-y-4 mb-8">
                            <div class="flex justify-between items-center text-sm font-bold">
                                <span class="text-slate-400 uppercase tracking-widest">Estado Servidor</span>
                                <span class="text-emerald-500 flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    ONLINE
                                </span>
                            </div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-indigo-500 h-full w-[92%] rounded-full"></div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mt-auto">
                            <a href="{{ route('admin.reports.download', 'viajes') }}" class="flex flex-col items-center justify-center p-4 bg-slate-50 rounded-2xl border-2 border-transparent hover:border-indigo-500/20 hover:bg-white transition-all group">
                                <i class="fas fa-download text-indigo-600 mb-2 group-hover:scale-110 transition-transform"></i>
                                <span class="text-xs font-black text-slate-900">DATOS</span>
                            </a>
                            <button class="flex flex-col items-center justify-center p-4 bg-slate-50 rounded-2xl border-2 border-transparent hover:border-indigo-500/20 hover:bg-white transition-all group">
                                <i class="fas fa-paper-plane text-indigo-600 mb-2 group-hover:scale-110 transition-transform"></i>
                                <span class="text-xs font-black text-slate-900">ENVIAR</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- RENDICION CARD -->
                <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden flex flex-col">
                    <div class="p-8 bg-emerald-600 text-white">
                        <div class="flex justify-between items-start mb-6">
                            <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-2xl">
                                <i class="fas fa-calculator"></i>
                            </div>
                            <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-bold uppercase tracking-widest">Activo</span>
                        </div>
                        <h3 class="text-2xl font-black mb-1">Rendición Gastos</h3>
                        <p class="text-emerald-100/80 font-medium">Flujos de caja y fondos fijos.</p>
                    </div>
                    <div class="p-8 flex-1 flex flex-col">
                        <div class="space-y-4 mb-8">
                            <div class="flex justify-between items-center text-sm font-bold">
                                <span class="text-slate-400 uppercase tracking-widest">Estado Servidor</span>
                                <span class="text-emerald-500 flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    ONLINE
                                </span>
                            </div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-emerald-500 h-full w-[78%] rounded-full"></div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mt-auto">
                            <a href="{{ route('admin.reports.download', 'rendicion') }}" class="flex flex-col items-center justify-center p-4 bg-slate-50 rounded-2xl border-2 border-transparent hover:border-emerald-500/20 hover:bg-white transition-all group">
                                <i class="fas fa-download text-emerald-600 mb-2 group-hover:scale-110 transition-transform"></i>
                                <span class="text-xs font-black text-slate-900">DATOS</span>
                            </a>
                            <button class="flex flex-col items-center justify-center p-4 bg-slate-50 rounded-2xl border-2 border-transparent hover:border-emerald-500/20 hover:bg-white transition-all group">
                                <i class="fas fa-paper-plane text-emerald-600 mb-2 group-hover:scale-110 transition-transform"></i>
                                <span class="text-xs font-black text-slate-900">ENVIAR</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
