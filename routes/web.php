<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\ReportController;

use App\Models\Procurement;

/*
|--------------------------------------------------------------------------
| HALAMAN AWAL
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});

/*
|--------------------------------------------------------------------------
| DASHBOARD & PROCUREMENT CRUD (FULL VILT)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', \App\Http\Middleware\HandleInertiaRequests::class])->group(function () {

    Route::get('/dashboard', [ProcurementController::class, 'index'])
        ->name('dashboard');

    Route::get('/procurement/create', [ProcurementController::class, 'create'])
        ->name('procurement.create');

    Route::post('/procurement/store', [ProcurementController::class, 'store'])
        ->name('procurement.store');

    Route::get('/procurement/edit/{id}', [ProcurementController::class, 'edit'])
        ->name('procurement.edit');

    Route::put('/procurement/update/{id}', [ProcurementController::class, 'update'])
        ->name('procurement.update');

    Route::post('/procurement/delete/{id}', [ProcurementController::class, 'destroy'])
        ->name('procurement.delete');

    Route::post('/procurement/approve-phase/{id}', [ProcurementController::class, 'approvePhase'])
        ->name('procurement.approve_phase');

    Route::post('/procurement/reject-phase/{id}', [ProcurementController::class, 'rejectPhase'])
        ->name('procurement.reject_phase');

});

/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});

/*
|--------------------------------------------------------------------------
| LAPORAN (Inertia Vue Redirect)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', \App\Http\Middleware\HandleInertiaRequests::class])->group(function () {

    Route::get('/laporan', [ReportController::class, 'index'])
        ->name('laporan.index');

});

/*
|--------------------------------------------------------------------------
| REPORT (Inertia Vue — Import/Export)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', \App\Http\Middleware\HandleInertiaRequests::class])->prefix('report')->group(function () {

    Route::get('/', [ReportController::class, 'index'])
        ->name('report.index');

    Route::post('/import', [ReportController::class, 'import'])
        ->name('report.import');

    Route::get('/template', [ReportController::class, 'downloadTemplate'])
        ->name('report.template');

    Route::get('/export/excel', [ReportController::class, 'exportExcel'])
        ->name('report.export.excel');

    Route::get('/export/pdf', [ReportController::class, 'exportPdf'])
        ->name('report.export.pdf');

    Route::get('/error-log/{importLog}', [ReportController::class, 'downloadErrorLog'])
        ->name('report.errorlog');

});

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';