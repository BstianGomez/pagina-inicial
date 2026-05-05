<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportesController;

Route::get('/reportes/data', [ReportesController::class, 'data']);
