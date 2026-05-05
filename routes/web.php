<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LogoutController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    // Redirección inteligente post-login
    Route::get('/app-redirect', function () {
        $user = auth()->user();
        
        // Super admins siempre ven la selección (o pueden ir directo al panel admin)
        if ($user->isSuperAdmin()) {
            $apps = $user->assigned_apps ?? ['oc', 'viajes', 'rendicion'];
            return view('select-app', ['apps' => $apps, 'is_super_admin' => true]);
        }

        $apps = $user->assigned_apps ?? $user->assigned_app ?? [];
        if (is_string($apps)) {
            $decoded = json_decode($apps, true);
            $apps = is_array($decoded) ? $decoded : [$apps];
        }
        if (!$apps || count($apps) === 0) {
            abort(403, 'No tienes aplicaciones asignadas.');
        }
        if (count($apps) === 1) {
            $app = $apps[0];
            return redirect(
                $app === 'oc' ? '/oc/oc' :
                ($app === 'viajes' ? '/viajes/mis-solicitudes' :
                ($app === 'rendicion' ? '/rendicion/informes' : '/dashboard'))
            );
        }
        // Si tiene más de una app, mostrar selección
        return view('select-app', ['apps' => $apps, 'is_super_admin' => false]);
    })->name('app-redirect');

    // Logout forzado (cierre completo de sesión)
    Route::post('/force-logout', [LogoutController::class, 'forceLogout'])->name('force-logout');

    // Rutas para las apps subidas
    Route::get('/oc/dashboard', function () {
        return view('oc.dashboard');
    })->name('oc.dashboard');

    Route::get('/viajes/mis-solicitudes', function () {
        return view('mis-solicitudes');
    })->name('viajes.mis-solicitudes');

    Route::get('/rendicion/dashboard', function () {
        return view('dashboard');
    })->name('rendicion.dashboard');

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

    // Administración Global
    Route::prefix('admin')->as('admin.')->group(function () {
        Route::get('/usuarios', [\App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('users.index');
        Route::post('/usuarios', [\App\Http\Controllers\Admin\UserManagementController::class, 'store'])->name('users.store');
        Route::put('/usuarios/{user}', [\App\Http\Controllers\Admin\UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/usuarios/{user}', [\App\Http\Controllers\Admin\UserManagementController::class, 'destroy'])->name('users.destroy');
    });
});

require __DIR__.'/auth.php';
