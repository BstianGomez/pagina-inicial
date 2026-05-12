<x-auth-split-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        /* ── Reset viewport ─────────────────────────── */
        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* ── Root wrapper ───────────────────────────── */
        .login-root {
            display: flex;
            height: 100vh;
            width: 100%;
            overflow: hidden;
            position: relative;
        }

        /* ── Left panel ─────────────────────────────── */
        .login-left {
            display: none;
            width: 50%;
            background: #0f172a;
            background: radial-gradient(circle at 0% 0%, #1e40af 0%, transparent 50%),
                        radial-gradient(circle at 100% 0%, #1e3a8a 0%, transparent 50%),
                        radial-gradient(circle at 100% 100%, #1e40af 0%, transparent 50%),
                        radial-gradient(circle at 0% 100%, #312e81 0%, transparent 50%),
                        linear-gradient(135deg, #1d4ed8 0%, #1e3a8a 100%);
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: clamp(1.5rem, 3vw, 3rem);
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
            padding: clamp(1rem, 2vh, 2rem);
            position: relative;
            height: 100vh;
            overflow: hidden;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            display: flex;
            flex-direction: column;
            height: 100%;
            justify-content: center;
        }

        @media (max-width: 1023px) {
            .login-right {
                background: radial-gradient(circle at top right, #eff6ff, #f8fafc);
            }
            .login-card {
                background: #fff;
                border-radius: 2rem;
                padding: 1.5rem;
                box-shadow: 0 20px 40px rgba(15, 23, 42, 0.05);
                height: auto;
                max-height: 95vh;
            }
        }

        /* ── Logo box ─────────────────────────────────── */
        .logo-box {
            display: flex;
            justify-content: center;
            margin-bottom: clamp(0.75rem, 2vh, 1.25rem);
        }
        .logo-box-inner {
            width: 100%;
            max-width: 160px;
            height: 60px;
            background: #fff;
            border-radius: 1.25rem;
            box-shadow: 0 8px 24px rgba(15,23,42,0.04);
            border: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s ease;
        }
        .logo-box-inner:hover {
            transform: translateY(-3px);
        }

        /* ── Heading ─────────────────────────────────── */
        .login-heading {
            text-align: center;
            margin-bottom: clamp(0.75rem, 2vh, 1.5rem);
        }
        .login-heading h1 {
            font-family: 'Outfit', sans-serif;
            font-size: clamp(1.75rem, 4vh, 2.25rem);
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 .25rem;
            letter-spacing: -0.04em;
        }
        .login-heading p {
            font-size: clamp(.85rem, 1.5vh, 1rem);
            color: #64748b;
            font-weight: 500;
            margin: 0;
        }

        /* ── Form ────────────────────────────────────── */
        .form-stack { display: flex; flex-direction: column; gap: 0.75rem; }

        .field-label {
            display: block;
            font-size: 10px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .1em;
            margin-bottom: .4rem;
            margin-left: .5rem;
        }
        .input-wrap { position: relative; }
        .input-icon {
            position: absolute;
            top: 50%;
            left: 1rem;
            transform: translateY(-50%);
            display: flex;
            align-items: center;
            pointer-events: none;
            color: #94a3b8;
            transition: color 0.2s;
        }
        .field-input {
            display: block;
            width: 100%;
            box-sizing: border-box;
            padding: 0.75rem 1rem 0.75rem 3rem;
            border: 1.5px solid #f1f5f9;
            border-radius: 1rem;
            background: #f8fafc;
            font-size: 0.95rem;
            font-weight: 600;
            color: #1e293b;
            outline: none;
            transition: all 0.2s ease;
            font-family: inherit;
        }
        .field-input:focus {
            border-color: #2563eb;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.05);
        }

        /* ── Remember / forgot ───────────────────────── */
        .row-between {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 0.25rem;
            margin-top: 0.25rem;
        }
        .remember-label {
            display: flex;
            align-items: center;
            gap: .5rem;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
        }
        .forgot-link {
            font-size: 12px;
            font-weight: 700;
            color: #2563eb;
            text-decoration: none;
        }

        /* ── Submit button ───────────────────────────── */
        .submit-btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            padding: 0.9rem 1.5rem;
            border: none;
            border-radius: 1.1rem;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.3);
            transition: all 0.3s;
            margin-top: 0.5rem;
        }
        .submit-btn:hover {
            transform: translateY(-2px);
            filter: brightness(1.1);
        }

        /* ── Quick access ────────────────────────────── */
        .quick-section {
            margin-top: clamp(0.75rem, 2vh, 1.5rem);
            padding-top: clamp(0.75rem, 2vh, 1.25rem);
            border-top: 1px solid #f1f5f9;
        }
        .quick-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.75rem;
        }
        .quick-title {
            font-size: 10px;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
        }
        .quick-badge {
            font-size: 10px;
            font-weight: 700;
            color: #2563eb;
            background: #eff6ff;
            padding: 2px 8px;
            border-radius: 999px;
        }
        .quick-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.5rem;
            width: 100%;
        }
        @media (max-width: 1200px) {
            .quick-grid { grid-template-columns: repeat(2, 1fr); }
        }
        .quick-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0.75rem 0.25rem;
            background: #fff;
            border: 1.5px solid #f1f5f9;
            border-radius: 1.1rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .quick-btn:hover {
            border-color: #2563eb;
            transform: translateY(-3px);
        }
        .quick-icon {
            width: 32px; height: 32px;
            background: #f8fafc;
            border-radius: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.4rem;
        }
        .quick-name {
            font-size: 11px;
            font-weight: 800;
            color: #1e293b;
            text-align: center;
        }
        .quick-email {
            font-size: 9px;
            font-weight: 600;
            color: #94a3b8;
            width: 100%;
            text-align: center;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* ── Footer ──────────────────────────────────── */
        .login-footer {
            text-align: center;
            margin-top: 1rem;
            font-size: 10px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .1em;
        }

        /* ── Left panel decorations ───────────────────── */
        .dot-grid {
            position: absolute;
            inset: 0;
            opacity: .1;
            background-image: radial-gradient(#fff 1px, transparent 1px);
            background-size: 40px 40px;
        }
        .blob-tr {
            position: absolute;
            top: -100px; right: -100px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.2) 0%, transparent 70%);
            filter: blur(80px);
            animation: float 20s infinite alternate;
        }
        .blob-bl {
            position: absolute;
            bottom: -150px; left: -150px;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 70%);
            filter: blur(100px);
            animation: float 25s infinite alternate-reverse;
        }
        @keyframes float {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }

        .left-inner {
            position: relative;
            z-index: 10;
            max-width: 560px;
        }
        .left-badge {
            display: inline-flex;
            align-items: center;
            gap: .75rem;
            padding: .6rem 1.25rem;
            border-radius: 1.25rem;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            backdrop-filter: blur(12px);
            margin-bottom: 2.5rem;
            animation: fadeInDown 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
        }
        .left-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            background: #60a5fa;
            box-shadow: 0 0 15px #60a5fa;
            animation: pulse 2.5s infinite;
        }
        @keyframes pulse {
            0%,100% { opacity:1; transform:scale(1); }
            50%      { opacity:0.4; transform:scale(0.8); }
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .left-badge-text {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .2em;
            color: #dbeafe;
        }
        .left-h2 {
            font-family: 'Outfit', sans-serif;
            font-size: clamp(2.5rem, 5vw, 4.5rem);
            font-weight: 900;
            line-height: 1;
            margin: 0 0 2rem;
            letter-spacing: -.05em;
            color: #fff;
            animation: fadeInLeft 1s cubic-bezier(0.2, 0.8, 0.2, 1) 0.2s backwards;
        }
        .left-h2 span { 
            display: block;
            background: linear-gradient(90deg, #93c5fd, #60a5fa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        @keyframes fadeInLeft {
            from { opacity: 0; transform: translateX(-40px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .left-desc {
            font-size: 1.15rem;
            color: rgba(219, 234, 254, 0.7);
            line-height: 1.6;
            margin: 0 0 3rem;
            max-width: 500px;
            animation: fadeInLeft 1s cubic-bezier(0.2, 0.8, 0.2, 1) 0.4s backwards;
        }
        .feature-list { 
            display: flex; 
            flex-direction: column; 
            gap: 1.5rem; 
            animation: fadeInLeft 1s cubic-bezier(0.2, 0.8, 0.2, 1) 0.6s backwards;
        }
        .feature-item { display: flex; align-items: center; gap: 1.5rem; }
        .feature-icon {
            width: 56px; height: 56px;
            border-radius: 1.25rem;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.3s;
            backdrop-filter: blur(8px);
        }
        .feature-item:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
            background: rgba(255,255,255,0.15);
            border-color: rgba(255,255,255,0.3);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .feature-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.1rem;
            font-weight: 800;
            color: #fff;
            margin: 0 0 .15rem;
        }
        .feature-sub {
            font-size: 0.9rem;
            color: rgba(147, 197, 253, 0.6);
            margin: 0;
        }

        /* ── Responsive breakpoints ───────────────────── */
        @media (min-width: 1024px) {
            .login-left  { display: flex; }
            .login-right { width: 50%; }
            .login-card  {
                max-width: 480px;
                width: 90%;
            }
        }

        /* Ajuste para pantallas medianas (tablets en landscape o laptops pequeñas) */
        @media (min-width: 1024px) and (max-width: 1280px) {
            .login-left { padding: 2rem; }
            .login-right { padding: 2rem; }
            .left-h2 { font-size: 3rem; }
            .quick-grid { gap: 0.5rem; }
            .quick-btn { padding: 1rem 0.25rem; }
            .quick-name { font-size: 11px; }
            .quick-email { font-size: 9px; }
            .quick-icon { width: 40px; height: 40px; }
        }

        @media (max-width: 480px) {
            .quick-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .login-heading h1 { font-size: 1.75rem; }
        }

        /* Evitar scroll horizontal */
        .login-root {
            max-width: 100vw;
            overflow-x: hidden;
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
