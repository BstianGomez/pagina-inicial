<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión · Sistema OC</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|dm-sans:400,500,600" rel="stylesheet" />

    <style>
        :root {
            --bg: #f5f7fb;
            --ink: #101828;
            --muted: #5b6473;
            --brand: #0f6bb6;
            --brand-light: #1b7dc8;
            --brand-dark: #0a4f86;
            --line: #e3e8f0;
            --card: #ffffff;
            --error: #dc2626;
            --error-bg: #fef2f2;
            --error-border: #fecaca;
        }

        * { 
            box-sizing: border-box; 
            margin: 0;
            padding: 0;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        body {
            margin: 0;
            font-family: "DM Sans", "Space Grotesk", ui-sans-serif, system-ui, sans-serif;
            color: var(--ink);
            background: 
                radial-gradient(circle at 20% 20%, rgba(15, 107, 182, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(27, 125, 200, 0.06) 0%, transparent 50%),
                linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        /* Decorative background elements */
        body::before,
        body::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            opacity: 0.4;
            z-index: 0;
        }

        body::before {
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(15, 107, 182, 0.15) 0%, transparent 70%);
            top: -200px;
            right: -100px;
            animation: float 8s ease-in-out infinite;
        }

        body::after {
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(27, 125, 200, 0.12) 0%, transparent 70%);
            bottom: -150px;
            left: -50px;
            animation: float 10s ease-in-out infinite 2s;
        }

        .page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 24px;
            position: relative;
            z-index: 1;
        }

        .container {
            width: 100%;
            max-width: 440px;
            animation: fadeIn 0.6s ease-out;
        }


        .card {
            width: 100%;
            background: var(--card);
            border: 1px solid rgba(15, 107, 182, 0.1);
            border-radius: 20px;
            box-shadow: 
                0 20px 40px rgba(16, 24, 40, 0.08),
                0 0 0 1px rgba(15, 107, 182, 0.05);
            padding: 60px 32px 40px;
            position: relative;
            overflow: hidden;
            z-index: 5;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: var(--brand);
        }

        .logo-login {
            width: 100px;
            margin: 0 auto 24px;
            display: block;
        }

        .header {
            text-align: center;
            margin-bottom: 32px;
        }

        .title {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--ink);
            font-family: "Space Grotesk", sans-serif;
            letter-spacing: -0.5px;
        }

        .subtitle {
            font-size: 14px;
            color: var(--muted);
            line-height: 1.5;
        }

        .alert {
            background: var(--error-bg);
            color: var(--error);
            border: 1px solid var(--error-border);
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 13px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: fadeIn 0.3s ease-out;
        }

        .alert::before {
            content: '⚠';
            font-size: 16px;
            flex-shrink: 0;
        }

        .form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .label {
            font-size: 13px;
            color: var(--ink);
            font-weight: 600;
            letter-spacing: -0.1px;
        }

        .input-wrapper {
            position: relative;
        }

        .input {
            width: 100%;
            padding: 13px 16px;
            border: 1.5px solid var(--line);
            border-radius: 12px;
            background: #fafbfc;
            font-size: 14px;
            color: var(--ink);
            transition: all 0.2s ease;
            font-family: "DM Sans", sans-serif;
        }

        .input:hover {
            border-color: rgba(15, 107, 182, 0.3);
            background: #fff;
        }

        .input:focus {
            outline: none;
            border-color: var(--brand);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(15, 107, 182, 0.08);
        }

        .input::placeholder {
            color: #94a3b8;
        }

        .btn {
            width: 100%;
            padding: 14px 20px;
            border-radius: 12px;
            border: none;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            background: linear-gradient(135deg, var(--brand-light) 0%, var(--brand) 100%);
            color: #fff;
            box-shadow: 
                0 4px 14px rgba(15, 107, 182, 0.35),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            transition: all 0.2s ease;
            margin-top: 8px;
            font-family: "DM Sans", sans-serif;
            letter-spacing: 0.2px;
        }

        .btn:hover {
            background: linear-gradient(135deg, var(--brand) 0%, var(--brand-dark) 100%);
            transform: translateY(-2px);
            box-shadow: 
                0 6px 20px rgba(15, 107, 182, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }

        .btn:active {
            transform: translateY(0);
            box-shadow: 
                0 2px 8px rgba(15, 107, 182, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }

        
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 20px 0 10px;
            color: #94a3b8;
            font-size: 13px;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid var(--line);
        }
        .divider:not(:empty)::before {
            margin-right: .5em;
        }
        .divider:not(:empty)::after {
            margin-left: .5em;
        }

        .btn-oauth {
            width: 100%;
            padding: 12px 20px;
            border-radius: 12px;
            background: #ffffff;
            border: 1.5px solid var(--line);
            color: var(--ink);
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: "DM Sans", sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .btn-oauth:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
        }
        
        .btn-oauth img {
            width: 18px;
            height: 18px;
        }

        .footer {
            margin-top: 24px;
            text-align: center;
            font-size: 13px;
            color: var(--muted);
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .card {
                padding: 32px 24px;
            }

            .title {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <x-global-alerts />
    <div class="page">
        <div class="container">
            <div class="card">
                <div class="header">
                    <img src="{{ asset('images/Logos sofofa (1) (1).png') }}" alt="Logo Fundación SOFOFA" class="logo-login">
                    <h1 class="title">Iniciar Sesión</h1>
                    <p class="subtitle">Ingresa tus credenciales para acceder al sistema</p>
                </div>

                @if($errors->any())
                    <div class="alert">
                        {{ $errors->first() }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}" class="form">
                    @csrf
                    <div class="field">
                        <label class="label" for="email">Correo Electrónico</label>
                        <div class="input-wrapper">
                            <input 
                                class="input" 
                                id="email" 
                                name="email" 
                                type="email" 
                                value="{{ old('email') }}" 
                                placeholder="tu@correo.com"
                                required 
                                autofocus
                            />
                        </div>
                    </div>

                    <div class="field">
                        <label class="label" for="password">Contraseña</label>
                        <div class="input-wrapper">
                            <input 
                                class="input" 
                                id="password" 
                                name="password" 
                                type="password" 
                                placeholder="••••••••"
                                required 
                            />
                        </div>
                    </div>

                    
                    <button class="btn" type="submit">Ingresar al Sistema</button>

                    <div class="divider">o continúa con</div>

                    <button class="btn-oauth" type="button">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="Google">
                        Iniciar sesión con Google
                    </button>
                    
                    <button class="btn-oauth" type="button" style="margin-bottom: 0;">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/4/44/Microsoft_logo.svg" alt="Microsoft">
                        Iniciar sesión con Microsoft
                    </button>

                </form>

                <div class="footer">
                    Sistema de Gestión de Órdenes de Compra
                </div>
            </div>
        </div>
    </div>
</body>
</html>
