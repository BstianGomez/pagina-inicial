<x-auth-split-layout>
    <div class="flex min-h-screen flex-col lg:flex-row">
        <!-- Left Side: Information (Hidden on mobile) -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-700 via-blue-800 to-indigo-900 items-center justify-center p-12 text-white relative overflow-hidden">
            <!-- Background Decoration -->
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 30px 30px;"></div>
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-blue-400/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-indigo-500/10 rounded-full blur-3xl"></div>
            
            <div class="relative z-10 max-w-lg">
                <div class="mb-8 inline-flex items-center gap-3 px-4 py-2 rounded-2xl bg-white/10 border border-white/20 backdrop-blur-md">
                    <div class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></div>
                    <span class="text-xs font-bold uppercase tracking-widest text-blue-100">Portal Unificado Fundación SOFOFA</span>
                </div>

                <h2 class="text-6xl font-extrabold leading-[1.1] mb-8 tracking-tight">
                    Gestión Inteligente <br/>
                    <span class="text-blue-300">en un solo lugar.</span>
                </h2>
                <p class="text-xl text-blue-100/70 mb-12 leading-relaxed">
                    Acceda de forma segura a todos los módulos operativos: Órdenes de Compra, Gestión de Viajes y Rendición de Gastos. Optimizado para un flujo de trabajo ágil.
                </p>
                
                <div class="space-y-6">
                    <div class="flex items-center gap-5 group">
                        <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center border border-white/20 transition-all group-hover:scale-110 group-hover:bg-white/20">
                            <svg class="w-6 h-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-white">Máxima Agilidad</h4>
                            <p class="text-sm text-blue-100/60">Aprobaciones y flujos en tiempo real</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-5 group">
                        <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center border border-white/20 transition-all group-hover:scale-110 group-hover:bg-white/20">
                            <svg class="w-6 h-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-white">Seguridad Centralizada</h4>
                            <p class="text-sm text-blue-100/60">Control total de accesos y auditoría</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-5 group">
                        <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center border border-white/20 transition-all group-hover:scale-110 group-hover:bg-white/20">
                            <svg class="w-6 h-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-white">Visibilidad 360°</h4>
                            <p class="text-sm text-blue-100/60">Análisis y reportes automáticos</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 bg-[#f8fafc] lg:bg-white">
            <div class="w-full max-w-md bg-white p-8 lg:p-0 rounded-[2.5rem] shadow-xl shadow-slate-200/50 lg:shadow-none lg:rounded-none">
                <!-- Logo -->
                <div class="flex justify-center mb-6 lg:mb-8">
                    <div class="p-4 bg-white rounded-[2rem] shadow-2xl shadow-blue-500/10 border border-slate-50">
                        <x-application-logo class="w-20 h-20 fill-current text-blue-600 drop-shadow-sm" />
                    </div>
                </div>

                <div class="text-center mb-6">
                    <h1 class="text-4xl font-black text-slate-900 mb-3 tracking-tight">Bienvenido</h1>
                    <p class="text-slate-500 font-medium">Ingresa tus credenciales para continuar</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2.5 ml-1">Correo Electrónico</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none transition-colors group-focus-within:text-blue-500">
                                <svg class="h-5 w-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"></path></svg>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="usuario@empresa.cl" class="block w-full pl-14 pr-6 py-4 border-2 border-slate-100 rounded-[1.5rem] leading-5 bg-slate-50/50 placeholder-slate-300 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all font-bold text-slate-700">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2.5 ml-1">Contraseña</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none transition-colors group-focus-within:text-blue-500">
                                <svg class="h-5 w-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <input id="password" type="password" name="password" required placeholder="••••••••" class="block w-full pl-14 pr-6 py-4 border-2 border-slate-100 rounded-[1.5rem] leading-5 bg-slate-50/50 placeholder-slate-300 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all font-bold text-slate-700">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between px-1">
                        <label class="flex items-center cursor-pointer group">
                            <input type="checkbox" name="remember" class="rounded-lg border-2 border-slate-200 text-blue-600 shadow-sm focus:ring-blue-500/20 w-5 h-5 transition-all group-hover:border-blue-400">
                            <span class="ml-3 text-[13px] text-slate-500 font-bold transition-colors group-hover:text-slate-700">Mantener sesión</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-[13px] font-black text-blue-600 hover:text-blue-700 transition-all hover:underline decoration-2 underline-offset-4">Recuperar acceso</a>
                        @endif
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-[1.5rem] shadow-[0_20px_40px_-12px_rgba(37,99,235,0.4)] text-lg font-black text-white bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all transform hover:-translate-y-1 active:scale-[0.98]">
                            <span>Iniciar Sesión</span>
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </button>
                    </div>
                </form>

                <!-- Quick Access Section -->
                <div class="mt-6 pt-6 border-t border-slate-100">
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Acceso Rápido</p>
                        <span class="text-[10px] font-bold text-blue-500 bg-blue-50 px-2 py-0.5 rounded-full">Pass: password123</span>
                    </div>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <button type="button" onclick="quickLogin('oc@example.com', 'password123')" 
                            class="flex flex-col items-center p-3 sm:p-4 rounded-[1.25rem] bg-slate-50/50 border-2 border-transparent hover:border-blue-500/20 hover:bg-white hover:shadow-lg hover:shadow-blue-500/5 transition-all duration-300 group">
                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            </div>
                            <span class="text-[11px] font-black text-slate-900 mb-0.5 text-center">Módulo OC</span>
                            <span class="text-[9px] text-slate-400 font-bold truncate w-full px-1 text-center">oc@example.com</span>
                        </button>

                        <button type="button" onclick="quickLogin('viajes@example.com', 'password123')" 
                            class="flex flex-col items-center p-3 sm:p-4 rounded-[1.25rem] bg-slate-50/50 border-2 border-transparent hover:border-blue-500/20 hover:bg-white hover:shadow-lg hover:shadow-blue-500/5 transition-all duration-300 group">
                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            </div>
                            <span class="text-[11px] font-black text-slate-900 mb-0.5 text-center">Viajes</span>
                            <span class="text-[9px] text-slate-400 font-bold truncate w-full px-1 text-center">viajes@example.com</span>
                        </button>
                        
                        <button type="button" onclick="quickLogin('rendicion@example.com', 'password123')" 
                            class="flex flex-col items-center p-3 sm:p-4 rounded-[1.25rem] bg-slate-50/50 border-2 border-transparent hover:border-emerald-500/20 hover:bg-white hover:shadow-lg hover:shadow-emerald-500/5 transition-all duration-300 group">
                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            </div>
                            <span class="text-[11px] font-black text-slate-900 mb-0.5 text-center">Rendición</span>
                            <span class="text-[9px] text-slate-400 font-bold truncate w-full px-1 text-center">rendicion@example.com</span>
                        </button>

                        <button type="button" onclick="quickLogin('multi2@example.com', 'password123')" 
                            class="flex flex-col items-center p-3 sm:p-4 rounded-[1.25rem] bg-slate-50/50 border-2 border-transparent hover:border-amber-500/20 hover:bg-white hover:shadow-lg hover:shadow-amber-500/5 transition-all duration-300 group">
                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <span class="text-[11px] font-black text-slate-900 mb-0.5 text-center">Admin Total</span>
                            <span class="text-[9px] text-slate-400 font-bold truncate w-full px-1 text-center">multi2@example.com</span>
                        </button>
                    </div>
                </div>

                <script>
                    function quickLogin(email, password) {
                        const emailInput = document.getElementById('email');
                        const passwordInput = document.getElementById('password');

                        emailInput.value = email;
                        passwordInput.value = password;

                        // Add a small visual feedback
                        emailInput.classList.add('ring-4', 'ring-blue-500/20', 'border-blue-500/50');
                        passwordInput.classList.add('ring-4', 'ring-blue-500/20', 'border-blue-500/50');
                        
                        setTimeout(() => {
                            emailInput.classList.remove('ring-4', 'ring-blue-500/20', 'border-blue-500/50');
                            passwordInput.classList.remove('ring-4', 'ring-blue-500/20', 'border-blue-500/50');
                        }, 1000);

                        // Focus the email input
                        emailInput.focus();
                    }
                </script>

                <!-- Support Footer -->
                <div class="mt-6 text-center">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                        Fundación SOFOFA &copy; {{ date('Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-auth-split-layout>
