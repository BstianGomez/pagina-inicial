<?php

use App\Http\Controllers\Rendicion\AuthController;
use App\Http\Controllers\Rendicion\DashboardController;
use App\Http\Controllers\Rendicion\ReportController;
use App\Http\Controllers\Rendicion\ExpenseController;
use App\Http\Controllers\Rendicion\UserController;
use App\Http\Controllers\Rendicion\ConfigController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('rendicion.dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [DashboardController::class, 'analytics'])->name('analytics.index');
    Route::post('/analytics/send-gmail', [DashboardController::class, 'sendGmail'])->name('analytics.sendGmail');
    Route::get('/bandeja', [ReportController::class, 'inbox'])->name('reports.inbox');
    Route::get('/gastos', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::get('/gastos/borradores', [ExpenseController::class, 'drafts'])->name('expenses.drafts');
    Route::post('/gastos', [ExpenseController::class, 'storeBulk'])->name('expenses.storeBulk');
    Route::get('/gastos/{expense}/editar', [ExpenseController::class, 'edit'])->name('expenses.edit');
    Route::put('/gastos/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    
    // Reports & Expenses
    // ...existing code...
    Route::get('/gastos/crear', [ReportController::class, 'create'])->name('expenses.create');
    Route::get('/gastos/crear/detalles/{report}', [ReportController::class, 'createStep2'])->name('expenses.createStep2');
    Route::post('/gastos/crear/detalles/{report}', [ReportController::class, 'storeStep2'])->name('expenses.storeStep2');
    Route::post('/gastos/crear/detalles/{report}/toggle/{expense}', [ReportController::class, 'toggleExpense'])->name('reports.toggleExpense');
    Route::get('/gastos/crear/informacion/{report}', [ReportController::class, 'createStep1'])->name('expenses.createStep1');
    Route::post('/gastos/crear/informacion/nuevo', [ReportController::class, 'storeStep1New'])->name('expenses.storeStep1.new');
    Route::post('/gastos/crear/informacion/{report}', [ReportController::class, 'storeStep1'])->name('expenses.storeStep1');
    
    Route::get('/informes', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/informes/exportar', [ReportController::class, 'bulkExport'])->name('reports.export');
    Route::get('/informes/{report}', [ReportController::class, 'show'])->name('reports.show');
    Route::post('/informes/{report}/approve', [ReportController::class, 'approve'])->name('reports.approve');
    Route::post('/informes/{report}/reject', [ReportController::class, 'reject'])->name('reports.reject');
    
    // Signed routes for email one-click actions
    Route::get('/informes/{report}/approve-signed', [ReportController::class, 'approve'])->name('reports.approve.signed')->middleware('signed');
    Route::get('/informes/{report}/reject-signed', [ReportController::class, 'reject'])->name('reports.reject.signed')->middleware('signed');
    
    Route::post('/informes/{report}/observe', [ReportController::class, 'observe'])->name('reports.observe');
    Route::post('/informes/{report}/pay', [ReportController::class, 'pay'])->name('reports.pay');
        Route::post('/informes/{report}/cancel', [ReportController::class, 'cancel'])->name('reports.cancel');
        Route::post('/informes/{report}/enable-edit', [ReportController::class, 'enableEdit'])->name('reports.enableEdit');
        Route::delete('/informes/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');
    
    // New Actions for compliance
    Route::get('/informes/{report}/agregar-gasto', [ReportController::class, 'addExpense'])->name('expenses.add');
    Route::post('/informes/{report}/agregar-gasto', [ReportController::class, 'storeNewExpense'])->name('expenses.storeNewExpense');
    Route::post('/gastos/{expense}/validate', [ReportController::class, 'updateExpenseValidation'])->name('expenses.updateValidation');
    Route::delete('/gastos/{expense}', [ReportController::class, 'destroyExpense'])->name('expenses.destroy');

    // Gestión de Usuarios
    Route::resource('usuarios', UserController::class)
        ->parameters(['usuarios' => 'user'])
        ->names('users');

    // Configuración de Catálogos
    Route::get('configuracion', [ConfigController::class, 'index'])->name('config.index');
    Route::post('configuracion/categoria', [ConfigController::class, 'storeCategory'])->name('config.category.store');
    Route::put('configuracion/categoria/{category}', [ConfigController::class, 'updateCategory'])->name('config.category.update');
    Route::delete('configuracion/categoria/{category}', [ConfigController::class, 'destroyCategory'])->name('config.category.destroy');
    Route::post('configuracion/ceco', [ConfigController::class, 'storeCeco'])->name('config.ceco.store');
    Route::put('configuracion/ceco/{ceco}', [ConfigController::class, 'updateCeco'])->name('config.ceco.update');
    Route::delete('configuracion/ceco/{ceco}', [ConfigController::class, 'destroyCeco'])->name('config.ceco.destroy');
});
