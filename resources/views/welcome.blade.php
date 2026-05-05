<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal Unificado | Fundación Sofofa</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Instrument Sans', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="bg-[#0f172a] text-white overflow-hidden">
    <div class="relative min-h-screen flex items-center justify-center p-6">
        <!-- Background Patterns -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-600/20 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-indigo-600/20 rounded-full blur-[120px]"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 w-full max-w-6xl">
            <!-- Header -->
            <header class="flex justify-between items-center mb-16">
                <img src="/img/logo.png" alt="Sofofa" class="h-12 brightness-0 invert">
                <a href="/login" class="px-6 py-2 rounded-full bg-blue-600 hover:bg-blue-700 font-bold transition-all shadow-lg shadow-blue-600/20">
                    Iniciar Sesión
                </a>
            </header>

            <main class="flex flex-col items-center justify-center min-h-[60vh]">
                <h1 class="text-6xl font-black tracking-tighter mb-6 leading-[1.1]">
                    Portal <span class="text-blue-500">Unificado</span> de Gestión.
                </h1>
                <p class="text-xl text-slate-400 mb-10 leading-relaxed max-w-lg text-center">
                    Accede a todas las herramientas corporativas de la Fundación Sofofa desde un solo lugar. Inicia sesión para continuar.
                </p>
                <a href="/login" class="px-8 py-4 rounded-full bg-blue-600 hover:bg-blue-700 font-bold text-xl transition-all shadow-lg shadow-blue-600/20">
                    Iniciar Sesión
                </a>
            </main>

            <footer class="mt-20 pt-10 border-t border-white/5 text-slate-500 text-xs font-bold uppercase tracking-widest text-center">
                &copy; {{ date('Y') }} Fundación Sofofa &bull; Sistema de Gestión Integrado
            </footer>
        </div>
    </div>
</body>
</html>
