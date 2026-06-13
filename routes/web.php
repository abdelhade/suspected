<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// وجهات المحاضر (CRUD كامل)
Route::resource('reports', ReportController::class);

// وجهات المسجلين والمطلوبين
Route::resource('suspects', \App\Http\Controllers\SuspectController::class);

// وجهات القوائم (Lookups)
Route::post('lookups/report-types', [\App\Http\Controllers\LookupController::class, 'storeType'])->name('lookups.types.store');
Route::post('lookups/report-statuses', [\App\Http\Controllers\LookupController::class, 'storeStatus'])->name('lookups.statuses.store');

