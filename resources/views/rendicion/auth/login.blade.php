<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión · Fundación SOFOFA</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --secondary: #1e293b;
            --bg-gray: #f8fafc;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --white: #ffffff;
            --border: #e2e8f0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--white);
            color: var(--text-main);
            min-height: 100vh;
            overflow-x: hidden;
        }

        h1, h2, h3 {
            font-family: 'Outfit', sans-serif;
        }

        .login-page {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* Hero Section */
        .hero-section {
            flex: 1;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
            color: var(--white);
            position: relative;
            overflow: hidden;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }

        @media (max-width: 1024px) {
            .hero-section {
                display: none;
            }
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -10%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero-content {
            max-width: 500px;
            position: relative;
            z-index: 1;
        }

        .hero-title {
            font-size: 42px;
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 18px;
            letter-spacing: -1px;
        }

        .hero-description {
            font-size: 15px;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 36px;
            line-height: 1.6;
        }

        .features-list {
            list-style: none;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 18px;
            font-size: 14px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.9);
        }

        .feature-icon {
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Form Section */
        .form-section {
            width: 550px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 30px;
            background: var(--white);
        }

        @media (max-width: 1024px) {
            .form-section {
                width: 100%;
            }
        }

        .form-container {
            width: 100%;
            max-width: 400px;
        }

        .logo {
            width: 150px;
            margin-bottom: 32px;
        }

        .welcome-text {
            margin-bottom: 24px;
        }

        .welcome-text h2 {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 6px;
        }

        .welcome-text p {
            color: var(--text-muted);
            font-size: 13px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            width: 18px;
            height: 18px;
        }

        .form-input {
            width: 100%;
            padding: 12px 14px 12px 48px;
            background: #f8fafc;
            border: 1px solid var(--border);
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.2s;
            font-family: inherit;
            color: var(--text-main);
        }

        .form-input:focus {
            outline: none;
            background: var(--white);
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            font-size: 12px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            color: var(--text-muted);
        }

        .remember-me input {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            border: 1px solid var(--border);
        }

        .login-btn {
            width: 100%;
            padding: 14px;
            background: var(--primary);
            color: var(--white);
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
        }

        .login-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.35);
        }

        /* Test Accounts Section */
        .test-accounts {
            margin-top: 48px;
            border-top: 1px solid var(--border);
            padding-top: 24px;
        }

        .test-accounts-title {
            text-align: center;
            font-size: 12px;
            font-weight: 700;
            color: var(--text-muted);
            margin-bottom: 24px;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
        }

        .test-accounts-title::before,
        .test-accounts-title::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 60px;
            height: 1px;
            background: var(--border);
        }

        .test-accounts-title::before { left: 0; }
        .test-accounts-title::after { right: 0; }

        .account-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .account-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            padding: 8px 12px;
            background: #f8fafc;
            border-radius: 8px;
        }

        .account-email {
            color: var(--text-main);
            font-weight: 500;
        }

        .account-badge {
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-superadmin { background: #fee2e2; color: #991b1b; }
        .badge-admin { background: #dbeafe; color: #1e40af; }
        .badge-approver { background: #dcfce7; color: #166534; }
        .badge-manager { background: #fef9c3; color: #854d0e; }
        .badge-user { background: #f3e8ff; color: #6b21a8; }

        .alert {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* SVG Icons */
        .icon {
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
    </style>
</head>
<body>
    <div class="login-page">
        <!-- Hero Section -->
        <div class="hero-section">
            <div class="hero-content">
                <h1 class="hero-title">Gestiona tus rendiciones de forma eficiente.</h1>
                <p class="hero-description">
                    Plataforma centralizada para solicitar, aprobar y gestionar rendiciones de gastos. Acceso rápido, seguro y desde cualquier dispositivo.
                </p>

                <ul class="features-list">
                    <li class="feature-item">
                        <div class="feature-icon">
                            <svg class="icon" viewBox="0 0 24 24" width="20" height="20">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </div>
                        Aprobación de rendiciones en tiempo real
                    </li>
                    <li class="feature-item">
                        <div class="feature-icon">
                            <svg class="icon" viewBox="0 0 24 24" width="20" height="20">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                        </div>
                        Control total de costos y reembolsos
                    </li>
                    <li class="feature-item">
                        <div class="feature-icon">
                            <svg class="icon" viewBox="0 0 24 24" width="20" height="20">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        Gestión de usuarios con roles y permisos
                    </li>
                </ul>
            </div>
        </div>

        <!-- Form Section -->
        <div class="form-section">
            <div class="form-container">
                <img src="{{ asset('rendicion/images/Logos sofofa (1) (1).png') }}" alt="Fundación SOFOFA" class="logo">
                
                <div class="welcome-text">
                    <h2>Bienvenido de vuelta</h2>
                    <p>Ingresa tus credenciales para continuar</p>
                </div>

                @if($errors->any())
                    <div class="alert">
                        <svg class="icon" viewBox="0 0 24 24" width="18" height="18">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="/login">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="email">Correo Electrónico</label>
                        <div class="input-wrapper">
                            <svg class="input-icon icon" viewBox="0 0 24 24">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="form-input" 
                                placeholder="usuario@empresa.cl" 
                                value="{{ old('email') }}"
                                required 
                                autofocus
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Contraseña</label>
                        <div class="input-wrapper">
                            <svg class="input-icon icon" viewBox="0 0 24 24">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="form-input" 
                                placeholder="••••••••" 
                                required
                            >
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="remember-me">
                            <input type="checkbox" name="remember">
                            Recordarme
                        </label>
                    </div>

                    <button type="submit" class="login-btn">Iniciar Sesión</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
