<x-auth-split-layout>
    <style>
        /* ── Reset viewport ─────────────────────────── */
        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden;
        }

        /* ── Root wrapper ───────────────────────────── */
        .login-root {
            display: flex;
            height: 100vh;
            width: 100%;
            overflow: hidden;
        }

        /* ── Left panel ─────────────────────────────── */
        .login-left {
            display: none;
            width: 50%;
            background: linear-gradient(135deg, #1d4ed8 0%, #1e3a8a 50%, #312e81 100%);
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: clamp(1.5rem, 4vw, 3rem);
            position: relative;
            overflow: hidden;
            flex-shrink: 0;
        }

        /* ── Right panel ─────────────────────────────── */
        .login-right {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
            overflow-y: auto;
            padding: clamp(1rem, 3vw, 2rem);
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            background: #fff;
            border-radius: 2rem;
            padding: clamp(1.25rem, 3vw, 2rem);
            box-shadow: 0 20px 60px -10px rgba(15,23,42,.08);
        }

        /* ── Logo box ─────────────────────────────────── */
        .logo-box {
            display: flex;
            justify-content: center;
            margin-bottom: clamp(0.75rem, 2vh, 1.5rem);
        }
        .logo-box-inner {
            width: 180px;
            height: 80px;
            background: #fff;
            border-radius: 1.25rem;
            box-shadow: 0 8px 24px rgba(15,23,42,.08);
            border: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ── Heading ─────────────────────────────────── */
        .login-heading {
            text-align: center;
            margin-bottom: clamp(0.75rem, 2vh, 1.25rem);
        }
        .login-heading h1 {
            font-size: clamp(1.75rem, 4vw, 2.5rem);
            font-weight: 900;
            color: #0f172a;
            margin: 0 0 .25rem;
            letter-spacing: -0.03em;
        }
        .login-heading p {
            font-size: clamp(.8rem, 1.5vw, 1rem);
            color: #64748b;
            font-weight: 600;
            margin: 0;
        }

        /* ── Form ────────────────────────────────────── */
        .form-stack { display: flex; flex-direction: column; gap: .75rem; }

        .field-label {
            display: block;
            font-size: 10px;
            font-weight: 900;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .15em;
            margin-bottom: .4rem;
        }
        .input-wrap { position: relative; }
        .input-icon {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            padding-left: 1rem;
            display: flex;
            align-items: center;
            pointer-events: none;
            color: #cbd5e1;
        }
        .field-input {
            display: block;
            width: 100%;
            box-sizing: border-box;
            padding: .75rem 1rem .75rem 3rem;
            border: 2px solid #f1f5f9;
            border-radius: 1.25rem;
            background: #f8fafc;
            font-size: .9rem;
            font-weight: 700;
            color: #334155;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
            font-family: inherit;
        }
        .field-input::placeholder { color: #cbd5e1; font-weight: 500; }
        .field-input:focus {
            border-color: rgba(59,130,246,.5);
            box-shadow: 0 0 0 4px rgba(59,130,246,.08);
        }

        /* ── Remember / forgot ───────────────────────── */
        .row-between {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .remember-label {
            display: flex;
            align-items: center;
            gap: .6rem;
            cursor: pointer;
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
        }
        .remember-label input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: #2563eb;
            border-radius: 6px;
            border: 2px solid #e2e8f0;
        }
        .forgot-link {
            font-size: 12px;
            font-weight: 900;
            color: #2563eb;
            text-decoration: none;
        }
        .forgot-link:hover { text-decoration: underline; }

        /* ── Submit button ───────────────────────────── */
        .submit-btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            padding: .9rem 1.5rem;
            border: none;
            border-radius: 1.25rem;
            background: linear-gradient(90deg, #2563eb, #3b82f6);
            color: #fff;
            font-size: 1rem;
            font-weight: 900;
            cursor: pointer;
            box-shadow: 0 12px 32px -8px rgba(37,99,235,.45);
            transition: transform .2s, box-shadow .2s, filter .2s;
            font-family: inherit;
        }
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 40px -8px rgba(37,99,235,.55);
            filter: brightness(1.05);
        }
        .submit-btn:active { transform: scale(.98); }

        /* ── Quick access ────────────────────────────── */
        .quick-section {
            margin-top: clamp(.6rem, 1.5vh, 1rem);
            padding-top: clamp(.6rem, 1.5vh, 1rem);
            border-top: 1px solid #f1f5f9;
        }
        .quick-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: .6rem;
        }
        .quick-title {
            font-size: 10px;
            font-weight: 900;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .15em;
        }
        .quick-badge {
            font-size: 10px;
            font-weight: 700;
            color: #3b82f6;
            background: #eff6ff;
            padding: 2px 8px;
            border-radius: 999px;
        }
        .quick-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: .5rem;
        }
        .quick-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: .6rem .25rem;
            background: #f8fafc;
            border: 2px solid transparent;
            border-radius: 1rem;
            cursor: pointer;
            transition: all .25s;
            font-family: inherit;
        }
        .quick-btn:hover {
            background: #fff;
            border-color: rgba(59,130,246,.2);
            box-shadow: 0 4px 16px rgba(59,130,246,.06);
        }
        .quick-icon {
            width: 36px; height: 36px;
            background: #fff;
            border-radius: .75rem;
            box-shadow: 0 2px 8px rgba(15,23,42,.06);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: .35rem;
            transition: transform .2s;
        }
        .quick-btn:hover .quick-icon { transform: scale(1.1); }
        .quick-name {
            font-size: 10px;
            font-weight: 900;
            color: #0f172a;
            text-align: center;
            line-height: 1.2;
        }
        .quick-email {
            font-size: 9px;
            font-weight: 700;
            color: #94a3b8;
            text-align: center;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            width: 100%;
            padding: 0 2px;
        }

        /* ── Footer ──────────────────────────────────── */
        .login-footer {
            text-align: center;
            margin-top: clamp(.5rem, 1.5vh, .9rem);
            font-size: 10px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .12em;
        }

        /* ── Left panel decorations ───────────────────── */
        .dot-grid {
            position: absolute;
            inset: 0;
            opacity: .08;
            background-image: radial-gradient(#fff 1px, transparent 1px);
            background-size: 28px 28px;
        }
        .blob-tr {
            position: absolute;
            top: -60px; right: -60px;
            width: 300px; height: 300px;
            background: rgba(96,165,250,.1);
            border-radius: 50%;
            filter: blur(60px);
        }
        .blob-bl {
            position: absolute;
            bottom: -60px; left: -60px;
            width: 250px; height: 250px;
            background: rgba(99,102,241,.1);
            border-radius: 50%;
            filter: blur(60px);
        }
        .left-inner {
            position: relative;
            z-index: 10;
            max-width: 480px;
        }
        .left-badge {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .5rem 1rem;
            border-radius: 1rem;
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.2);
            backdrop-filter: blur(8px);
            margin-bottom: clamp(1rem, 3vh, 2rem);
        }
        .left-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: #93c5fd;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%,100% { opacity:1; transform:scale(1); }
            50%      { opacity:.6; transform:scale(.85); }
        }
        .left-badge-text {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .15em;
            color: #bfdbfe;
        }
        .left-h2 {
            font-size: clamp(2rem, 4vw, 3.5rem);
            font-weight: 900;
            line-height: 1.1;
            margin: 0 0 clamp(.75rem, 2vh, 1.5rem);
            letter-spacing: -.03em;
            color: #fff;
        }
        .left-h2 span { color: #93c5fd; }
        .left-desc {
            font-size: clamp(.8rem, 1.4vw, 1rem);
            color: rgba(191,219,254,.7);
            line-height: 1.6;
            margin: 0 0 clamp(1rem, 3vh, 2rem);
        }
        .feature-list { display: flex; flex-direction: column; gap: clamp(.6rem, 1.5vh, 1rem); }
        .feature-item { display: flex; align-items: center; gap: 1rem; }
        .feature-icon {
            width: 44px; height: 44px;
            border-radius: 1rem;
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.2);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: transform .2s, background .2s;
        }
        .feature-item:hover .feature-icon {
            transform: scale(1.1);
            background: rgba(255,255,255,.2);
        }
        .feature-title {
            font-size: clamp(.85rem, 1.3vw, 1rem);
            font-weight: 800;
            color: #fff;
            margin: 0 0 .1rem;
        }
        .feature-sub {
            font-size: clamp(.7rem, 1vw, .8rem);
            color: rgba(191,219,254,.6);
            margin: 0;
        }

        /* ── Responsive breakpoints ───────────────────── */
        @media (min-width: 1024px) {
            .login-left  { display: flex; }
            .login-right { width: 50%; }
            .login-card  {
                background: transparent;
                box-shadow: none;
                border-radius: 0;
                padding: 0;
                max-width: 420px;
            }
        }

        /* Small screens: compact spacing */
        @media (max-height: 700px) {
            .logo-box        { margin-bottom: .4rem; }
            .logo-box-inner  { width: 140px; height: 60px; }
            .login-heading   { margin-bottom: .4rem; }
            .form-stack      { gap: .5rem; }
            .field-input     { padding: .6rem 1rem .6rem 3rem; }
            .submit-btn      { padding: .7rem 1.5rem; }
            .quick-section   { margin-top: .4rem; padding-top: .4rem; }
            .quick-icon      { width: 30px; height: 30px; }
        }
    </style>

    <div class="login-root">

        {{-- ══ LEFT PANEL ══════════════════════════════════ --}}
        <div class="login-left">
            <div class="dot-grid"></div>
            <div class="blob-tr"></div>
            <div class="blob-bl"></div>

            <div class="left-inner">
                <div class="left-badge">
                    <div class="left-dot"></div>
                    <span class="left-badge-text">Portal Unificado Fundación SOFOFA</span>
                </div>

                <h2 class="left-h2">
                    Gestión Inteligente<br>
                    <span>en un solo lugar.</span>
                </h2>

                <p class="left-desc">
                    Acceda de forma segura a todos los módulos operativos:
                    Órdenes de Compra, Gestión de Viajes y Rendición de Gastos.
                    Optimizado para un flujo de trabajo ágil.
                </p>

                <div class="feature-list">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg width="22" height="22" fill="none" stroke="#93c5fd" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="feature-title">Máxima Agilidad</p>
                            <p class="feature-sub">Aprobaciones y flujos en tiempo real</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg width="22" height="22" fill="none" stroke="#93c5fd" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="feature-title">Seguridad Centralizada</p>
                            <p class="feature-sub">Control total de accesos y auditoría</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg width="22" height="22" fill="none" stroke="#93c5fd" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="feature-title">Visibilidad 360°</p>
                            <p class="feature-sub">Análisis y reportes automáticos</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══ RIGHT PANEL ═════════════════════════════════ --}}
        <div class="login-right">
            <div class="login-card">

                {{-- Logo --}}
                <div class="logo-box">
                    <div class="logo-box-inner">
                        <x-application-logo />
                    </div>
                </div>

                {{-- Heading --}}
                <div class="login-heading">
                    <h1>Bienvenido</h1>
                    <p>Ingresa tus credenciales para continuar</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="form-stack">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="field-label">Correo Electrónico</label>
                        <div class="input-wrap">
                            <span class="input-icon">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/>
                                </svg>
                            </span>
                            <input id="email" type="email" name="email" class="field-input"
                                   value="{{ old('email') }}" required autofocus
                                   placeholder="usuario@empresa.cl">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="field-label">Contraseña</label>
                        <div class="input-wrap">
                            <span class="input-icon">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </span>
                            <input id="password" type="password" name="password" class="field-input"
                                   required placeholder="••••••••">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    {{-- Remember / Forgot --}}
                    <div class="row-between">
                        <label class="remember-label">
                            <input type="checkbox" name="remember">
                            Mantener sesión
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">Recuperar acceso</a>
                        @endif
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="submit-btn">
                        <span>Iniciar Sesión</span>
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </button>
                </form>

                {{-- Quick Access --}}
                <div class="quick-section">
                    <div class="quick-header">
                        <span class="quick-title">Acceso Rápido</span>
                        <span class="quick-badge">Pass: password123</span>
                    </div>
                    <div class="quick-grid">
                        <button type="button" class="quick-btn" onclick="quickLogin('oc@example.com','password123')">
                            <div class="quick-icon">
                                <svg width="18" height="18" fill="none" stroke="#3b82f6" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                            <span class="quick-name">Módulo OC</span>
                            <span class="quick-email">oc@example.com</span>
                        </button>
                        <button type="button" class="quick-btn" onclick="quickLogin('viajes@example.com','password123')">
                            <div class="quick-icon">
                                <svg width="18" height="18" fill="none" stroke="#6366f1" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                            </div>
                            <span class="quick-name">Viajes</span>
                            <span class="quick-email">viajes@example.com</span>
                        </button>
                        <button type="button" class="quick-btn" onclick="quickLogin('rendicion@example.com','password123')">
                            <div class="quick-icon">
                                <svg width="18" height="18" fill="none" stroke="#10b981" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <span class="quick-name">Rendición</span>
                            <span class="quick-email">rendicion@example.com</span>
                        </button>
                        <button type="button" class="quick-btn" onclick="quickLogin('multi2@example.com','password123')">
                            <div class="quick-icon">
                                <svg width="18" height="18" fill="none" stroke="#f59e0b" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <span class="quick-name">Admin Total</span>
                            <span class="quick-email">multi2@example.com</span>
                        </button>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="login-footer">
                    Fundación SOFOFA &copy; {{ date('Y') }}
                </div>

            </div>
        </div>
    </div>

    <script>
        function quickLogin(email, password) {
            const emailInput    = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            emailInput.value    = email;
            passwordInput.value = password;
            emailInput.style.borderColor    = 'rgba(59,130,246,.5)';
            passwordInput.style.borderColor = 'rgba(59,130,246,.5)';
            setTimeout(() => {
                emailInput.style.borderColor    = '';
                passwordInput.style.borderColor = '';
            }, 1000);
            emailInput.focus();
        }
    </script>
</x-auth-split-layout>
