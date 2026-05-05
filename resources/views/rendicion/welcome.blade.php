<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Fundacion SOFOFA') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f8fbfe]">
    <div class="relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-12 sm:px-6">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_-20%,rgba(15,107,182,0.12),transparent_70%)] -z-0"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_120%,rgba(15,107,182,0.06),transparent_70%)] -z-0"></div>

        <div class="relative mx-auto z-10 w-full max-w-2xl ui-system">
            <div class="guest-shell overflow-hidden">
                <div class="h-1.5 w-full bg-sofofa-blue"></div>
                <div class="px-8 py-12 sm:px-12 sm:py-14 text-center">
                    <img src="{{ asset('rendicion/images/logo-sofofa-dark.png') }}" alt="Logo" class="mx-auto h-16 w-auto mb-6">
                    <h1 class="text-4xl font-black text-slate-800 tracking-tight">Portal de Rendicion de Gastos</h1>
                    <p class="mt-3 text-slate-500 font-medium">Gestion centralizada de solicitudes, aprobaciones y reembolsos.</p>

                    <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                        @auth
                            <a href="{{ route('rendicion.dashboard') }}" class="btn-premium">Ir al Dashboard</a>
                        @else
                            <a href="{{ route('rendicion.login') }}" class="btn-premium">Iniciar Sesion</a>
                        @endauth
                    </div>
                </div>
            </div>

            <p class="mt-8 text-center text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">© 2026 Fundacion SOFOFA</p>
        </div>
    </div>
</body>
</html>
