@props([
    'title' => '',
    'subtitle' => '',
    'backRoute' => null,
    'backLabel' => '↩ Volver al listado',
    'showLogout' => true,
    'additionalButtons' => null,
    'module' => 'OC',
    'logoutRoute' => 'oc.logout'
])

<header class="topbar">
    <div class="topbar-inner">
        <button class="mobile-menu-toggle" onclick="toggleMobileSidebar()" aria-label="Abrir menú">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>
        <div class="brand">
            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="line-height: 1.25;">
                    <h1 style="font-size: 16px; font-weight: 800; color: white; margin: 0; letter-spacing: -0.01em;">{{ $module === 'OC' ? 'Aplicación OC' : ($module === 'Viajes' ? 'Portal Viajes' : 'Rendición Gastos') }}</h1>
                    <p style="font-size: 11px; color: rgba(255, 255, 255, 0.8); margin: 0; text-transform: uppercase; font-weight: 700; letter-spacing: 0.02em;">Fundación SOFOFA</p>
                </div>
            </div>
        </div>
        
        <div class="topbar-center">
            <div class="topbar-badge" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15);">
                <span style="font-size: 14px; letter-spacing: 0.05em; text-transform: uppercase; opacity: 0.9;">Portal de Gestión</span>
            </div>
        </div>

        <div class="toolbar-actions" style="display: flex; align-items: center; gap: 12px;">
            @if($additionalButtons)
                <div style="display: flex; gap: 8px; padding-right: 12px; border-right: 1px solid rgba(255,255,255,0.15);">
                    {{ $additionalButtons }}
                </div>
            @endif
            
            @if($backRoute)
                <a href="{{ $backRoute }}" class="btn-logout" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2); text-transform: none; font-weight: 600;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    {{ $backLabel }}
                </a>
            @endif

            @auth
                <div class="user-info-badge">
                    <div class="user-avatar" style="background: var(--brand-2); border: 1px solid rgba(255,255,255,0.2);">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div style="line-height: 1.2; display: block;">
                        <div style="font-size: 12px; font-weight: 700; color: white;">{{ Auth::user()->name }}</div>
                        <div style="font-size: 10px; color: rgba(255,255,255,0.6); text-transform: uppercase; font-weight: 600; letter-spacing: 0.02em;">{{ str_replace('_', ' ', Auth::user()->rol ?? 'usuario') }}</div>
                    </div>
                </div>

                @if($showLogout)
                    <form action="{{ route($logoutRoute) }}" method="POST" style="margin:0;">
                        @csrf
                        <button type="submit" class="btn-logout">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span>Salir</span>
                        </button>
                    </form>
                @endif
            @endauth
        </div>
    </div>
</header>

{{-- Removed redundant banner to align with modernized design --}}
