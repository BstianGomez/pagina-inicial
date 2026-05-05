<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de Viajes — Iniciar Sesión</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700|space-grotesk:500,600,700,800" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: "DM Sans", sans-serif;
            height: 100vh;
            min-height: 100vh;
            display: flex;
            background: #060d1f;
            overflow: hidden;
        }

        /* ── Panel izquierdo ─────────────────────────────── */
        .left-panel {
            flex: 1.5;
            height: 100vh;
            background:
                radial-gradient(ellipse at 20% 50%, rgba(59,130,246,0.4) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 20%, rgba(147,197,253,0.3) 0%, transparent 50%),
                linear-gradient(160deg, #1e40af 0%, #1e3a8a 50%, #111827 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            padding: 5% 6%;
            position: relative;
            overflow: hidden;
        }

        /* Decorative mesh circles */
        .left-panel::before {
            content: '';
            position: absolute;
            width: 600px; height: 600px;
            border: 1px solid rgba(80,150,255,0.08);
            border-radius: 50%;
            top: -200px; right: -200px;
        }
        .left-panel::after {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            border: 1px solid rgba(80,150,255,0.06);
            border-radius: 50%;
            bottom: -150px; left: -100px;
        }
        .mesh-2 {
            position: absolute;
            width: 300px; height: 300px;
            border: 1px solid rgba(80,150,255,0.05);
            border-radius: 50%;
            top: 50%; right: 80px;
            transform: translateY(-50%);
        }
        /* Glow blob */
        .glow-blob {
            position: absolute;
            width: 350px; height: 350px;
            background: radial-gradient(circle, rgba(29,78,216,0.18) 0%, transparent 70%);
            top: 40%; left: 20%;
            transform: translate(-50%, -50%);
            pointer-events: none;
        }

        .left-content {
            position: relative;
            z-index: 1;
            width: 100%;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 64px;
        }
        .brand-logo-icon {
            width: 46px; height: 46px;
            background: linear-gradient(135deg, rgba(59,130,246,0.3), rgba(29,78,216,0.4));
            border: 1px solid rgba(147,197,253,0.2);
            border-radius: 13px;
            display: flex; align-items: center; justify-content: center;
            backdrop-filter: blur(10px);
            box-shadow: 0 0 20px rgba(59,130,246,0.15);
        }
        .brand-logo-name {
            font-family: "Space Grotesk", sans-serif;
            font-size: 20px; font-weight: 700;
            color: white;
            letter-spacing: -0.3px;
        }

        .left-headline {
            font-family: "Space Grotesk", sans-serif;
            font-size: clamp(34px, 4vw, 52px);
            font-weight: 800;
            color: #fff;
            line-height: 1.15;
            margin-bottom: 22px;
            letter-spacing: -1.5px;
        }
        .left-headline span {
            background: linear-gradient(90deg, #60a5fa, #93c5fd);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .left-desc {
            font-size: 15px;
            color: rgba(148,163,184,0.85);
            line-height: 1.75;
            max-width: 380px;
            margin-bottom: 52px;
        }

        .features { display: flex; flex-direction: column; gap: 14px; }
        .feature-item {
            display: flex;
            align-items: center;
            gap: 14px;
            color: rgba(203,213,225,0.8);
            font-size: 14px;
            font-weight: 500;
        }
        .feature-icon {
            width: 34px; height: 34px;
            background: rgba(59,130,246,0.1);
            border: 1px solid rgba(59,130,246,0.2);
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        /* ── Panel derecho ───────────────────────────────── */
        .right-panel {
            width: 38%;
            min-width: 380px;
            max-width: 500px;
            height: 100vh;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 44px;
            position: relative;
            overflow: hidden;
            box-shadow: -40px 0 80px rgba(0,0,0,0.15);
        }

        /* Subtle top accent bar */
        .right-panel::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, #1d4ed8, #3b82f6, #60a5fa);
        }

        .login-box { width: 100%; }

        .login-logo {
            margin-bottom: clamp(12px, 2vh, 24px);
            text-align: center;
        }
        .login-logo img {
            height: clamp(48px, 7vh, 72px);
            width: auto;
            object-fit: contain;
        }

        .login-title {
            font-family: "Space Grotesk", sans-serif;
            font-size: clamp(20px, 2.5vh, 28px);
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
            letter-spacing: -0.5px;
            text-align: center;
        }
        .login-subtitle {
            font-size: clamp(12px, 1.5vh, 14px);
            color: #94a3b8;
            margin-bottom: clamp(16px, 2.5vh, 28px);
            font-weight: 400;
            text-align: center;
        }

        .form-group { margin-bottom: clamp(10px, 1.5vh, 16px); }

        .form-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            width: 100%;
            padding: clamp(8px, 1.2vh, 12px) 16px;
            border: 1.5px solid #e8edf2;
            border-radius: 10px;
            font-size: 13px;
            font-family: "DM Sans", sans-serif;
            color: #0f172a;
            background: #f8fafc;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
            outline: none;
        }
        .form-control:focus {
            border-color: #3b82f6;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(59,130,246,0.08);
        }
        .form-control.is-invalid {
            border-color: #ef4444;
            box-shadow: 0 0 0 4px rgba(239,68,68,0.08);
        }
        .form-control::placeholder { color: #cbd5e1; }

        .input-wrapper { position: relative; }
        .input-icon {
            position: absolute;
            left: 14px; top: 50%;
            transform: translateY(-50%);
            color: #cbd5e1;
            width: 17px; height: 17px;
            transition: color 0.2s;
            pointer-events: none;
        }
        .input-wrapper:focus-within .input-icon { color: #3b82f6; }
        .input-wrapper .form-control { padding-left: 42px; }

        .form-error {
            margin-top: 6px; font-size: 12px;
            color: #ef4444; font-weight: 500;
        }

        .form-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: clamp(10px, 1.8vh, 20px);
        }
        .remember-label {
            display: flex; align-items: center; gap: 9px;
            font-size: 13px; color: #64748b; cursor: pointer;
            user-select: none;
        }
        .remember-label input {
            accent-color: #1d4ed8;
            width: 15px; height: 15px;
            border-radius: 4px; cursor: pointer;
        }

        .btn-login {
            width: 100%;
            padding: clamp(10px, 1.5vh, 14px);
            background: linear-gradient(135deg, #1d4ed8 0%, #3b82f6 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px; font-weight: 700;
            font-family: "DM Sans", sans-serif;
            cursor: pointer;
            transition: all 0.25s;
            box-shadow: 0 4px 20px rgba(29,78,216,0.3), 0 1px 3px rgba(0,0,0,0.1);
            letter-spacing: 0.2px;
            position: relative;
            overflow: hidden;
        }
        .btn-login::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 60%);
            pointer-events: none;
        }
        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 28px rgba(29,78,216,0.4), 0 2px 8px rgba(0,0,0,0.1);
        }
        .btn-login:active { transform: translateY(0); box-shadow: 0 2px 10px rgba(29,78,216,0.3); }

        .divider {
            display: flex; align-items: center; gap: 12px;
            margin: clamp(10px, 1.5vh, 20px) 0;
        }
        .divider::before, .divider::after {
            content: ''; flex: 1;
            height: 1px; background: #f1f5f9;
        }
        .divider span { font-size: 11px; color: #cbd5e1; font-weight: 600; letter-spacing: 0.5px; }

        /* Cuentas de prueba */
        .test-accounts {
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            padding: clamp(8px, 1.2vh, 14px) 12px;
        }
        .test-accounts-title {
            font-size: 9.5px; font-weight: 700;
            color: #b0bfd0;
            text-transform: uppercase; letter-spacing: 1.2px;
            margin-bottom: clamp(4px, 0.8vh, 10px);
        }
        .test-account-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: clamp(4px, 0.7vh, 7px) 10px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.15s;
            margin-bottom: 1px;
        }
        .test-account-item:hover { background: #eff6ff; }
        .test-account-item:hover .test-email { color: #1d4ed8; }
        .test-email {
            font-size: 12.5px; color: #475569; font-weight: 500;
            transition: color 0.15s;
        }
        .test-role {
            font-size: 10.5px; font-weight: 700;
            padding: 3px 9px; border-radius: 20px;
        }

        /* Alert de error */
        .alert-error {
            background: #fff1f2; border: 1px solid #fecdd3;
            color: #be123c; padding: 12px 14px; border-radius: 10px;
            font-size: 13px; font-weight: 500; margin-bottom: 20px;
            display: flex; align-items: center; gap: 10px;
        }
        /* ── Responsividad ──────────────────────────────── */

        /* Pantallas grandes (> 1400px) */
        @media (min-width: 1400px) {
            .left-headline { font-size: 54px; }
            .right-panel { padding: 60px 60px; }
        }

        /* Laptop grande (1200px) */
        @media (max-width: 1200px) {
            .left-headline { font-size: 32px; }
            .left-desc { font-size: 14px; max-width: 320px; }
            .features { gap: 10px; }
            .feature-item { font-size: 13px; }
            .right-panel { min-width: 360px; padding: 40px 36px; }
        }

        /* Tablet landscape (950px) */
        @media (max-width: 950px) {
            .left-panel { padding: 40px 36px; }
            .left-headline { font-size: 26px; }
            .right-panel { min-width: 320px; max-width: 400px; padding: 40px 28px; }
            .login-title { font-size: 24px; }
        }

        /* Tablet portrait / mobile landscape (800px) */
        @media (max-width: 800px) {
            body {
                flex-direction: column;
                height: auto;
                min-height: 100vh;
                overflow-y: auto;
            }
            .left-panel {
                height: auto;
                min-height: 45vh;
                flex: none;
                width: 100%;
                padding: 48px 32px;
                align-items: center;
                text-align: center;
                justify-content: center;
            }
            .left-headline { font-size: clamp(22px, 5vw, 34px); }
            .left-desc { margin-left: auto; margin-right: auto; max-width: 480px; }
            .features {
                align-items: center;
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: center;
                gap: 16px;
            }
            .right-panel {
                height: auto;
                width: 100%;
                min-width: 0;
                max-width: none;
                padding: 48px 32px 60px;
                box-shadow: none;
                overflow-y: visible;
            }
            .right-panel::before { display: none; }
            .login-box { max-width: 440px; margin: 0 auto; }
        }

        /* Mobile (600px) */
        @media (max-width: 600px) {
            .left-panel { padding: 36px 24px; min-height: 38vh; }
            .features { flex-direction: column; align-items: center; gap: 10px; }
            .right-panel { padding: 36px 20px 48px; }
            .login-title { font-size: 22px; }
            .login-subtitle { font-size: 13px; }
        }

        /* Mobile pequeño (480px) */
        @media (max-width: 480px) {
            .left-panel { display: none; }
            body { background: #ffffff; min-height: 100vh; height: auto; }
            .right-panel { padding: 40px 20px 52px; }
            .login-title { font-size: 22px; }
        }
    </style>
</head>
<body>

    <!-- Panel Izquierdo -->
    <div class="left-panel">
        <div class="glow-blob"></div>
        <div class="mesh-2"></div>
        <div class="left-content">

            <h1 class="left-headline">
                Gestiona tus viajes<br>
                <span>de forma eficiente.</span>
            </h1>
            <p class="left-desc">
                Plataforma centralizada para solicitar, aprobar y gestionar viajes corporativos. Acceso rápido, seguro y desde cualquier dispositivo.
            </p>

            <div class="features">
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg fill="none" stroke="#60a5fa" viewBox="0 0 24 24" style="width:16px;height:16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    Aprobación de solicitudes en tiempo real
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg fill="none" stroke="#60a5fa" viewBox="0 0 24 24" style="width:16px;height:16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    Control total de costos y gastos extras
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg fill="none" stroke="#60a5fa" viewBox="0 0 24 24" style="width:16px;height:16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    Gestión de usuarios con roles y permisos
                </div>
            </div>
        </div>
    </div>

    <!-- Panel Derecho -->
    <div class="right-panel">
        <div class="login-box">
            <div class="login-logo">
                <img src="/img/sofofa-full.png" alt="Fundación SOFOFA">
            </div>
            <h2 class="login-title">Bienvenido de vuelta</h2>
            <p class="login-subtitle">Ingresa tus credenciales para continuar</p>

            @if ($errors->any())
            <div class="alert-error">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;flex-shrink:0;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $errors->first() }}
            </div>
            @endif

            <form action="/login" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Correo electrónico</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <input type="email" id="email" name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="usuario@empresa.cl"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Contraseña</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <input type="password" id="password" name="password"
                            class="form-control"
                            placeholder="••••••••"
                            autocomplete="current-password"
                            required>
                    </div>
                </div>

                <div class="form-row">
                    <label class="remember-label">
                        <input type="checkbox" name="remember">
                        Recordarme
                    </label>
                </div>

                <button type="submit" class="btn-login">Iniciar Sesión</button>
            </form>

            <div class="divider"><span>CUENTAS DE PRUEBA</span></div>

            <!-- Cuentas de prueba rápida -->
            <div class="test-accounts">
                <div class="test-accounts-title">Contraseña: password</div>
                <div class="test-account-item" onclick="fillLogin('superadmin@test.com')">
                    <span class="test-email">superadmin@test.com</span>
                    <span class="test-role" style="background:#1e1b4b;color:#c7d2fe;">Super Admin</span>
                </div>
                <div class="test-account-item" onclick="fillLogin('admin@test.com')">
                    <span class="test-email">admin@test.com</span>
                    <span class="test-role" style="background:#dbeafe;color:#1d4ed8;">Admin</span>
                </div>
                <div class="test-account-item" onclick="fillLogin('aprobador@test.com')">
                    <span class="test-email">aprobador@test.com</span>
                    <span class="test-role" style="background:#dcfce7;color:#15803d;">Aprobador</span>
                </div>
                <div class="test-account-item" onclick="fillLogin('gestor@test.com')">
                    <span class="test-email">gestor@test.com</span>
                    <span class="test-role" style="background:#fef9c3;color:#a16207;">Gestor</span>
                </div>
                <div class="test-account-item" onclick="fillLogin('usuario@test.com')">
                    <span class="test-email">usuario@test.com</span>
                    <span class="test-role" style="background:#f3e8ff;color:#7c3aed;">Usuario</span>
                </div>
            </div>
        </div>
    </div>

    <script>
    function fillLogin(email) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = 'password';
        document.getElementById('email').dispatchEvent(new Event('input'));
    }
    </script>
</body>
</html>
