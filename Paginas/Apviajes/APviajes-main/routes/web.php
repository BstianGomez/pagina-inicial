<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\GestionController;

// ── Autenticación (pública) ────────────────────────
Route::get('/', function () {
    return auth()->check()
        ? redirect('/mis-solicitudes')
        : redirect('/login');
});
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout',[AuthController::class, 'logout'])->name('logout');

// ── Rutas protegidas (requieren login) ─────────────
Route::middleware('auth')->group(function () {

    // Solicitudes (todos los roles)
    Route::get('/solicitudes',     fn () => view('solicitudes'));
    Route::post('/solicitudes',    [SolicitudController::class, 'store'])->name('solicitudes.store');
    Route::get('/mis-solicitudes', [SolicitudController::class, 'index'])->name('mis-solicitudes');

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
    Route::get('/reportes', fn () => view('reportes'));
    require base_path('routes/reportes.php');

    Route::get('/usuarios',           [UserController::class, 'index']);
    Route::post('/usuarios',          [UserController::class, 'store']);
    Route::put('/usuarios/{user}',    [UserController::class, 'update']);
    Route::delete('/usuarios/{user}', [UserController::class, 'destroy']);
});
