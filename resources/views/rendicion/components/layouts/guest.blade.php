<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Fundación SOFOFA') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .auth-page {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 14px;
                position: relative;
                overflow: hidden;
                background: linear-gradient(180deg, #dbe3ec 0%, #e7eef6 100%);
            }

            .auth-page::before {
                content: '';
                position: absolute;
                inset: -20% -10% auto -10%;
                height: 360px;
                background: radial-gradient(circle at 50% 0%, rgba(15, 107, 182, 0.18), transparent 70%);
                pointer-events: none;
            }

            .auth-wrap {
                position: relative;
                width: 100%;
                max-width: 500px;
                z-index: 1;
            }

            .guest-shell {
                border: 1px solid #d7deea;
                border-radius: 22px;
                background: #ffffff;
                box-shadow: 0 20px 45px -24px rgba(15, 23, 42, 0.28);
                overflow: hidden;
            }

            .auth-footer {
                margin-top: 16px;
                text-align: center;
                font-size: 10px;
                font-weight: 800;
                letter-spacing: 0.18em;
                text-transform: uppercase;
                color: #8a97aa;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="auth-page">
            <div class="auth-wrap">
                <div class="guest-shell overflow-hidden">
                    <div class="h-1.5 w-full bg-sofofa-blue"></div>
                    <div class="px-7 py-7 sm:px-8 sm:py-8 ui-system">
                        {{ $slot }}
                    </div>
                </div>
                <p class="auth-footer">© 2026 Fundación SOFOFA</p>
            </div>
        </div>
    </body>
</html>
