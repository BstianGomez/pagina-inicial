@props([
    'title' => '',
    'subtitle' => '',
    'backRoute' => null,
    'backLabel' => '↩ Volver al listado',
    'showLogout' => true,
    'additionalButtons' => null
])

<header class="topbar">
    <div class="topbar-inner">
        <div class="brand">
            <div class="logo-container" style="height: 45px; display: flex; align-items: center; justify-content: center; padding: 6px;">
                <img src="{{ asset('images/Logos sofofa (1) (1).png') }}" alt="Logo" style="filter: brightness(0) invert(1); height: 100%; width: auto; object-fit: contain;">
            </div>
            <div class="header-titles" style="display: flex; flex-direction: column; justify-content: center; padding-left: 20px; margin-left: 20px;">
                <h1 style="font-size: clamp(18px, 4vw, 24px); font-weight: 700; color: #fff; margin: 0; line-height: 1.2;">{{ $title }}</h1>
                @if($subtitle)
                    <p style="font-size: 13px; font-weight: 500; color: rgba(255, 255, 255, 0.8); margin: 2px 0 0;">{{ $subtitle }}</p>
                @endif
            </div>
        </div>
        <div class="toolbar-actions">
            @if($additionalButtons)
                {{ $additionalButtons }}
            @endif
            
            @if($backRoute)
                <a href="{{ $backRoute }}" class="btn btn-ghost">{{ $backLabel }}</a>
            @endif
            
            @if($showLogout)
                @auth
                    <form action="{{ route('logout') }}" method="POST" style="display: inline-flex; margin: 0; padding: 0; height: 100%;">
                        @csrf
                        <button type="submit" class="btn btn-logout">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            Cerrar sesión
                        </button>
                    </form>
                @endauth
            @endif
        </div>
    </div>
</header>
