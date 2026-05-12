@php
    $user = auth()->user();
    $rol = $user->rol ?? 'usuario';
    $isAdmin = $user->isAdmin();
    $isAprobador = $user->isAprobador();
    $isGestor = $user->isGestor();
@endphp

<aside class="sidebar" id="sidebar">
    <div class="sidebar-header" style="padding: 20px; display: flex; justify-content: center; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.1);">
        <div class="brand-icon" style="width: 120px; height: 40px; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.1); border-radius: 12px; border: 1px solid rgba(255,255,255,0.2); padding: 0.5rem;">
            <img src="{{ asset('viajes_legacy/img/sofofa-logo.png') }}" alt="SOFOFA Logo" style="max-width: 100%; max-height: 100%; object-fit: contain;">
        </div>
        <button class="mobile-close-btn" onclick="toggleMobileSidebar()">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>

    <nav class="sidebar-nav">
        {{-- Mis Solicitudes / Todas las Solicitudes --}}
        <a href="{{ route('viajes.mis-solicitudes') }}" 
           class="nav-item {{ request()->routeIs('viajes.mis-solicitudes') ? 'active' : '' }}"
           title="Mis Solicitudes">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="nav-label">{{ in_array($rol, ['admin', 'super_admin', 'aprobador', 'gestor']) ? 'Todas las Solicitudes' : 'Mis Solicitudes' }}</span>
        </a>

        {{-- Solicitar Viaje --}}
        <a href="{{ url('viajes/solicitudes') }}" 
           class="nav-item {{ request()->is('viajes/solicitudes') ? 'active' : '' }}" 
           title="Solicitar Viaje">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
            </svg>
            <span class="nav-label">Solicitar Viaje</span>
        </a>

        {{-- Estadísticas --}}
        <a href="{{ url('viajes/reportes') }}" 
           class="nav-item {{ request()->is('viajes/reportes') ? 'active' : '' }}" 
           title="Estadísticas">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span class="nav-label">Estadísticas</span>
        </a>

        {{-- Aprobaciones --}}
        @if($isAprobador || $isAdmin)
        <a href="{{ route('viajes.aprobador') }}" 
           class="nav-item {{ request()->routeIs('viajes.aprobador') ? 'active' : '' }}" 
           title="Aprobaciones">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="nav-label">Aprobaciones</span>
        </a>
        @endif

        {{-- Gestión --}}
        @if($isGestor || $isAdmin)
        <a href="{{ route('viajes.gestion') }}" 
           class="nav-item {{ request()->routeIs('viajes.gestion') ? 'active' : '' }}" 
           title="Gestión">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="nav-label">Gestión</span>
        </a>
        @endif

        {{-- Usuarios --}}
        @if($isAdmin)
        <a href="{{ url('viajes/usuarios') }}" 
           class="nav-item {{ request()->is('viajes/usuarios') ? 'active' : '' }}" 
           title="Usuarios">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="nav-label">Usuarios</span>
        </a>
        @endif
    </nav>

    <button class="toggle-btn" onclick="toggleSidebar()" title="Expandir/Contraer">
        <span class="material-icons toggle-icon">chevron_left</span>
    </button>
</aside>

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<style>
    .mobile-close-btn {
        display: none;
        background: transparent;
        border: none;
        color: white;
        cursor: pointer;
        padding: 8px;
        margin-left: auto;
    }
    @media (max-width: 1024px) {
        .mobile-close-btn {
            display: block;
        }
    }
</style>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const icon = document.querySelector('.toggle-icon');
        const isCollapsed = sidebar.classList.toggle('collapsed');
        
        localStorage.setItem('sidebarCollapsed', isCollapsed);
        if (icon) {
            icon.textContent = isCollapsed ? 'chevron_right' : 'chevron_left';
        }
    }

    function toggleMobileSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('active');
    }

    (function() {
        const sidebar = document.getElementById('sidebar');
        const icon = document.querySelector('.toggle-icon');
        if (!sidebar) return;

        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (isCollapsed) {
            sidebar.classList.add('collapsed');
            if (icon) icon.textContent = 'chevron_right';
        }
    })();
</script>
