<x-app-layout>
    <style>
        .app-card-premium {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 32px;
            padding: 40px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            text-decoration: none;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }

        .app-card-premium:hover {
            transform: translateY(-12px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            border-color: #4f46e5;
        }

        .icon-box {
            width: 64px;
            height: 64px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 24px;
        }

        .icon-oc { background: #eff6ff; color: #2563eb; }
        .icon-viajes { background: #f5f3ff; color: #7c3aed; }
        .icon-rendicion { background: #ecfdf5; color: #059669; }
        .icon-admin { background: #f8fafc; color: #0f172a; border: 2px dashed #e2e8f0; }

        .btn-enter {
            margin-top: 32px;
            padding: 14px;
            border-radius: 16px;
            background: #f1f5f9;
            color: #0f172a;
            font-weight: 800;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 0.75rem;
            transition: all 0.2s;
        }

        .app-card-premium:hover .btn-enter {
            background: #0f172a;
            color: white;
        }

        /* ===== DARK MODE ===== */
        html.dark .app-card-premium {
            background: #1e293b;
            border-color: #334155;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.3);
        }

        html.dark .app-card-premium:hover {
            border-color: #818cf8;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
        }

        html.dark .icon-oc   { background: #1e3a5f; color: #93c5fd; }
        html.dark .icon-viajes { background: #2e1a5e; color: #c4b5fd; }
        html.dark .icon-rendicion { background: #064e3b; color: #6ee7b7; }
        html.dark .icon-admin { background: #1e293b; color: #94a3b8; border-color: #334155; }

        .card-admin { background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%); border-style: dashed; }
        html.dark .card-admin { background: #1e293b !important; border-color: #475569; }

        html.dark .btn-enter {
            background: #334155;
            color: #e2e8f0;
        }

        html.dark .app-card-premium:hover .btn-enter {
            background: #6366f1;
            color: white;
        }

        html.dark h2, html.dark h3 {
            color: #f1f5f9 !important;
        }

        html.dark p {
            color: #94a3b8 !important;
        }
    </style>

    <div class="py-16 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-5xl font-black text-slate-900 mb-4 tracking-tighter">Selecciona tu Módulo</h2>
                <p class="text-slate-500 font-medium text-lg">Elige la herramienta institucional donde deseas trabajar hoy.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 items-stretch">
                @if(in_array('oc', $apps))
                <a href="{{ url('/oc/oc') }}" class="app-card-premium">
                    <div>
                        <div class="icon-box icon-oc">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900 mb-2">Módulo OC</h3>
                        <p class="text-slate-500 leading-relaxed font-medium">Gestión de órdenes de compra, solicitudes y aprobaciones centralizadas.</p>
                    </div>
                    <div class="btn-enter">Ingresar al Sistema</div>
                </a>
                @endif

                @if(in_array('viajes', $apps))
                <a href="{{ url('/viajes/mis-solicitudes') }}" class="app-card-premium">
                    <div>
                        <div class="icon-box icon-viajes">
                            <i class="fas fa-plane-departure"></i>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900 mb-2">Gestión Viajes</h3>
                        <p class="text-slate-500 leading-relaxed font-medium">Control de itinerarios, viáticos y solicitudes de viaje corporativo.</p>
                    </div>
                    <div class="btn-enter">Ingresar al Sistema</div>
                </a>
                @endif

                @if(in_array('rendicion', $apps))
                <a href="{{ url('/rendicion/dashboard') }}" class="app-card-premium">
                    <div>
                        <div class="icon-box icon-rendicion">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900 mb-2">Rendición Gastos</h3>
                        <p class="text-slate-500 leading-relaxed font-medium">Seguimiento de gastos, reembolsos e informes de transparencia.</p>
                    </div>
                    <div class="btn-enter">Ingresar al Sistema</div>
                </a>
                @endif

                @if(auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.users.index') }}" class="app-card-premium card-admin">
                    <div>
                        <div class="icon-box icon-admin">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900 mb-2">Panel Admin</h3>
                        <p class="text-slate-500 leading-relaxed font-medium">Control global de identidades, permisos y configuración de módulos.</p>
                    </div>
                    <div class="btn-enter" style="background: #ef4444; color: white;">Gestionar Portal</div>
                </a>
                @endif
            </div>
            
            <p class="text-center mt-16 text-slate-400 font-medium text-sm">
                <i class="fas fa-info-circle mr-1"></i> Si no visualizas una aplicación, contacta al administrador de sistemas.
            </p>
        </div>
    </div>
</x-app-layout>
