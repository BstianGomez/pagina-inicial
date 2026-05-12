<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-950">
            <div class="mb-8">
                <a href="/">
                    <x-application-logo class="w-24 h-24 fill-current text-white drop-shadow-lg" />
                </a>
                <h1 class="text-white text-3xl font-bold mt-4 text-center tracking-tight">Portal Unificado</h1>
                <p class="text-blue-200 text-center text-sm mt-1">Acceso centralizado a tus herramientas</p>
            </div>

            <div class="w-full sm:max-w-3xl px-8 py-10 bg-white/95 backdrop-blur-md shadow-2xl overflow-hidden sm:rounded-2xl border border-white/20 mx-auto">
                {{ $slot ?? '' }}
                @yield('content')
            </div>
            
            <footer class="mt-8 text-blue-300/50 text-xs font-medium uppercase tracking-widest">
                &copy; {{ date('Y') }} Fundación Sofofa
            </footer>
        </div>
    </body>
</html>
