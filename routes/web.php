<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\WeaponController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\PendingApprovalsController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// ============================================================
// المحاضر
// ============================================================
Route::resource('reports', ReportController::class);
Route::post('reports/suggestions', [ReportController::class, 'suggestions'])->name('reports.suggestions');

// ============================================================
// المسجلين والمطلوبين
// ============================================================
Route::resource('suspects', \App\Http\Controllers\SuspectController::class);
Route::get('suspects/export', [\App\Http\Controllers\SuspectController::class, 'export'])
    ->name('suspects.export');

// ============================================================
// الأسلحة والمضبوطات النارية
// ============================================================
Route::resource('weapons', WeaponController::class);

// ============================================================
// بانتظار الاعتماد
// ============================================================
Route::get('pending-approvals', [PendingApprovalsController::class, 'index'])
    ->name('pending-approvals.index');

Route::post('pending-approvals/{suspect}/approve', [PendingApprovalsController::class, 'approve'])
    ->name('pending-approvals.approve');

Route::post('pending-approvals/{suspect}/reject', [PendingApprovalsController::class, 'reject'])
    ->name('pending-approvals.reject');

// ============================================================
// سجل التدقيق
// ============================================================
Route::get('audit-log',       [AuditLogController::class, 'index'])->name('audit-log.index');
Route::get('audit-log/{auditLog}', [AuditLogController::class, 'show'])->name('audit-log.show');

// ============================================================
// القوائم (Lookups)
// ============================================================
Route::post('lookups/report-types',    [\App\Http\Controllers\LookupController::class, 'storeType'])  ->name('lookups.types.store');
Route::post('lookups/report-statuses', [\App\Http\Controllers\LookupController::class, 'storeStatus'])->name('lookups.statuses.store');
Route::post('lookups/options',         [\App\Http\Controllers\LookupController::class, 'storeOption'])->name('lookups.options.store');
