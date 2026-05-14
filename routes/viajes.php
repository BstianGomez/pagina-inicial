<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Viajes\AuthController;
use App\Http\Controllers\Viajes\UserController;
use App\Http\Controllers\Viajes\SolicitudController;
use App\Http\Controllers\Viajes\GestionController;

Route::get('/', function () {
    return redirect()->route('viajes.mis-solicitudes');
});

Route::post('/logout', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');

// ── Rutas protegidas (requieren login) ─────────────
Route::middleware(['auth'])->group(function () {

    // Solicitudes (todos los roles)
    Route::get('/solicitudes',     fn () => view('viajes.solicitudes'));
    Route::post('/solicitudes',    [SolicitudController::class, 'store'])->name('solicitudes.store');
    Route::get('/mis-solicitudes', [SolicitudController::class, 'index'])->name('mis-solicitudes');

    // Solicitudes con Proyecto
    Route::get('/solicitudes-proyecto',  [SolicitudController::class, 'createProyecto'])->name('solicitudes-proyecto.create');
    Route::post('/solicitudes-proyecto', [SolicitudController::class, 'storeProyecto'])->name('solicitudes-proyecto.store');

    // Aprobaciones
    Route::get('/aprobador',  [SolicitudController::class, 'panelAprobador'])->name('aprobador');
    Route::post('/solicitudes/{solicitud}/aprobar',  [SolicitudController::class, 'aprobar'])->name('solicitudes.aprobar');
    Route::post('/solicitudes/{solicitud}/rechazar', [SolicitudController::class, 'rechazar'])->name('solicitudes.rechazar');

    // Gestión
    Route::get('/gestion',               [GestionController::class, 'index'])->name('gestion');
    Route::post('/gestion/{solicitud}/estimar', [GestionController::class, 'storeEstimacion'])->name('gestion.estimar');
    Route::post('/gestion/{solicitud}',  [GestionController::class, 'store'])->name('gestion.store');

    // Archivos (descarga protegida)
    Route::get('/archivos/{archivo}', [GestionController::class, 'descargar'])->name('archivos.descargar');

    // Reportes / Usuarios
    Route::get('/reportes', fn () => view('viajes.reportes'));
    require base_path('routes/viajes_reportes.php');

    Route::get('/usuarios',           [UserController::class, 'index'])->name('usuarios.index');
    Route::post('/usuarios',          [UserController::class, 'store'])->name('usuarios.store');
    Route::put('/usuarios/{user}',    [UserController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{user}', [UserController::class, 'destroy'])->name('usuarios.destroy');
});
