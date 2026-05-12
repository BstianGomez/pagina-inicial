<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('app-redirect');
    }
    return redirect('/login');
});

// El dashboard ha sido eliminado y reemplazado por /app-redirect


Route::middleware('auth')->group(function () {
    // Redirección de rutas legadas para evitar 404
    Route::get('/usuarios', function() {
        return redirect()->route('oc.users.index');
    });

    // Redirección inteligente post-login
    Route::get('/app-redirect', function () {
        $user = auth()->user();
        
        $appsData = $user->assigned_apps ?? $user->assigned_app ?? [];
        $apps = [];
        if (is_string($appsData)) {
            $decoded = json_decode($appsData, true);
            $apps = is_array($decoded) ? $decoded : [$appsData];
        } else {
            $apps = (array) $appsData;
        }
        $apps = array_filter($apps);

        // Si es Superadmin y no tiene apps, le mostramos todas
        if ($user->isSuperAdmin() && empty($apps)) {
            $apps = ['oc', 'viajes', 'rendicion'];
        }

        if (empty($apps)) {
            abort(403, 'Tu cuenta no tiene aplicaciones asignadas. Contacta al administrador.');
        }

        // Redirección automática si tiene una sola app y NO es Superadmin
        if (count($apps) === 1 && !$user->isSuperAdmin()) {
            $app = $apps[0];
            $target = match($app) {
                'oc' => route('oc.dashboard'),
                'viajes' => route('viajes.mis-solicitudes'),
                'rendicion' => route('rendicion.dashboard'),
                default => null,
            };
            
            if ($target) return redirect($target);
        }

        // Mostrar selección si tiene más de una app o es Superadmin
        return view('select-app', [
            'apps' => $apps, 
            'is_super_admin' => $user->isSuperAdmin()
        ]);
    })->name('app-redirect');

    // Logout forzado (cierre completo de sesión)
    Route::post('/force-logout', [LogoutController::class, 'forceLogout'])->name('force-logout');

    // Rutas para las apps subidas
    // NOTA: oc.dashboard, viajes.mis-solicitudes y rendicion.dashboard
    // se definen en sus respectivos archivos de rutas (oc.php, viajes.php, rendicion.php)
    // Las siguientes son solo aliases de conveniencia sin nombre para evitar conflictos:

    // ...otras rutas originales...

    // Aplicación OC (legacy)
    Route::prefix('oc')->as('oc.')->group(base_path('routes/oc.php'));
    // Aplicación Viajes (legacy)
    Route::prefix('viajes')->as('viajes.')->group(base_path('routes/viajes.php'));
    // Aplicación Rendición de Gastos (legacy)
    Route::prefix('rendicion')->as('rendicion.')->group(base_path('routes/rendicion.php'));

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Administración Global (Solo Superadmin)
    Route::middleware(['role:Superadmin'])->prefix('admin')->as('admin.')->group(function () {
        Route::get('/usuarios', [UserManagementController::class, 'index'])->name('users.index');
        Route::post('/usuarios', [UserManagementController::class, 'store'])->name('users.store');
        Route::put('/usuarios/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/usuarios/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');

        // Reportes y Dashboard separados
        Route::get('/reportes/oc', [ReportController::class, 'oc'])->name('reports.oc');
        Route::get('/reportes/viajes', [ReportController::class, 'viajes'])->name('reports.viajes');
        Route::get('/reportes/rendicion', [ReportController::class, 'rendicion'])->name('reports.rendicion');
        Route::get('/reportes/descargar/{type}', [ReportController::class, 'downloadData'])->name('reports.download');
    });
});

require __DIR__.'/auth.php';
