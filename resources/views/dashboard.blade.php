<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/80 backdrop-blur-xl border border-slate-200 overflow-hidden shadow-2xl shadow-indigo-500/5 sm:rounded-[2rem]">
                <div class="p-12 text-center">
                    <div class="w-20 h-20 bg-indigo-600 rounded-3xl mx-auto flex items-center justify-center text-white text-3xl shadow-xl shadow-indigo-500/40 mb-8">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h2 class="text-4xl font-black text-slate-900 mb-4 tracking-tight">¡Bienvenido al Portal, {{ Auth::user()->name }}!</h2>
                    <p class="text-slate-500 font-medium text-lg max-w-xl mx-auto">Has iniciado sesión correctamente. Desde aquí puedes acceder a todos tus sistemas y gestionar tus accesos globales.</p>
                    
                    <div class="mt-12 flex justify-center gap-4">
                        <a href="{{ route('app-redirect') }}" class="px-8 py-4 bg-slate-900 text-white rounded-2xl font-bold hover:bg-slate-800 transition-all shadow-lg">
                            Ir a mis aplicaciones
                        </a>
                        @if(Auth::user()->isSuperAdmin())
                            <a href="{{ route('admin.users.index') }}" class="px-8 py-4 bg-white border border-slate-200 text-slate-900 rounded-2xl font-bold hover:bg-slate-50 transition-all shadow-sm">
                                Gestionar Usuarios
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
