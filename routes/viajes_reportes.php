<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Viajes\ReportesController;

Route::get('/reportes/data', [ReportesController::class, 'data']);
Route::post('/reportes/send-gmail', [ReportesController::class, 'sendGmail']);
