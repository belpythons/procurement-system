<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequestPurchasingController;
use App\Http\Controllers\TechnicalEvaluationController;
use App\Http\Controllers\ReTechnicalEvaluationController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ReportController;

use App\Models\RequestPurchasing;
use App\Models\TechnicalEvaluation;
use App\Models\ReTechnicalEvaluation;
use App\Models\PurchaseOrder;
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
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->get('/dashboard', function () {

    $status = request('status');

    /*
    |----------------------------------------
    | TOTAL DASHBOARD (REAL FROM PROCUREMENT)
    |----------------------------------------
    */
    $totalRP = \App\Models\Procurement::where('status', 'Request Purchasing')->count();
    $totalTE = \App\Models\Procurement::where('status', 'Technical Evaluation')->count();
    $totalRETE = \App\Models\Procurement::where('status', 'Re-Technical Evaluation')->count();
    $totalPO = \App\Models\Procurement::where('status', 'Purchase Order')->count();

    /*
    |----------------------------------------
    | DATA PROCUREMENT (REAL SYSTEM 1 TABLE)
    |----------------------------------------
    */
    $procurements = \App\Models\Procurement::when($status, function ($query) use ($status) {
            return $query->where('status', $status);
        })
        ->orderBy('id', 'desc')
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'kode' => $item->kode,
                'barang' => $item->barang,
                'status' => $item->status,
                'tanggal' => $item->tanggal,
            ];
        });

    /*
    |----------------------------------------
    | RETURN VIEW (Pastikan melempar variabel total dengan benar)
    |----------------------------------------
    */
    return view('dashboard', compact(
        'totalRP',
        'totalTE',
        'totalRETE',
        'totalPO',
        'procurements'
    ));

})->name('dashboard');

/*
|--------------------------------------------------------------------------
| PROCUREMENT CRUD (FULL BARU)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/procurement', [ProcurementController::class, 'index'])
        ->name('procurement.index');

    Route::get('/procurement/create', [ProcurementController::class, 'create'])
        ->name('procurement.create');

    Route::post('/procurement/store', [ProcurementController::class, 'store'])
        ->name('procurement.store');

    Route::get('/procurement/edit/{id}', [ProcurementController::class, 'edit'])
        ->name('procurement.edit');

    // --- PASTIKAN PROSES UPDATE & DELETE MENGARAH KE CONTROLLER YANG BENAR ---
    Route::put('/procurement/update/{id}', [ProcurementController::class, 'update'])
        ->name('procurement.update');

    Route::post('/procurement/delete/{id}', [ProcurementController::class, 'destroy'])
        ->name('procurement.delete');

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
| REQUEST PURCHASING (RP)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    
    Route::get('/rp', [RequestPurchasingController::class, 'index'])
        ->name('rp.index');
    
    Route::post('/rp/store', [RequestPurchasingController::class, 'store'])
        ->name('rp.store');
    
    Route::put('/rp/update/{id}', [RequestPurchasingController::class, 'update'])
        ->name('rp.update');

    Route::delete('/rp/delete/{id}', [RequestPurchasingController::class, 'destroy'])
        ->name('rp.delete');

});

/*
|--------------------------------------------------------------------------
| TECHNICAL EVALUATION (TE)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | HALAMAN TE
    |--------------------------------------------------------------------------
    */
    Route::get('/te', [TechnicalEvaluationController::class, 'index'])
        ->name('te.index');

    /*
    |--------------------------------------------------------------------------
    | FORM CREATE
    |--------------------------------------------------------------------------
    */
    Route::get('/te/create', [TechnicalEvaluationController::class, 'create'])
        ->name('te.create');

    /*
    |--------------------------------------------------------------------------
    | SIMPAN DATA TE
    |--------------------------------------------------------------------------
    */
    Route::post('/te/store', [TechnicalEvaluationController::class, 'store'])
        ->name('te.store');

    /*
    |--------------------------------------------------------------------------
    | EDIT DATA TE
    |--------------------------------------------------------------------------
    */
    Route::get('/te/edit/{id}', [TechnicalEvaluationController::class, 'edit'])
        ->name('te.edit');

    /*
    |--------------------------------------------------------------------------
    | UPDATE DATA TE
    |--------------------------------------------------------------------------
    */
    Route::post('/te/update/{id}', [TechnicalEvaluationController::class, 'update'])
        ->name('te.update');

    /*
    |--------------------------------------------------------------------------
    | DELETE DATA TE
    |--------------------------------------------------------------------------
    */
    Route::delete('/te/delete/{id}', [TechnicalEvaluationController::class, 'destroy'])
        ->name('te.delete');
       

});

/*
|--------------------------------------------------------------------------
| RE-TECHNICAL EVALUATION (RE-TE)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/rete', [ReTechnicalEvaluationController::class, 'index'])
        ->name('rete.index');

    Route::get('/rete/create', [ReTechnicalEvaluationController::class, 'create'])
        ->name('rete.create');

    Route::post('/rete/store', [ReTechnicalEvaluationController::class, 'store'])
        ->name('rete.store');

    Route::get('/rete/edit/{id}', [ReTechnicalEvaluationController::class, 'edit'])
        ->name('rete.edit');

    Route::post('/rete/update/{id}', [ReTechnicalEvaluationController::class, 'update'])
        ->name('rete.update');

    Route::delete('/rete/delete/{id}', [ReTechnicalEvaluationController::class, 'destroy'])
        ->name('rete.delete');

});

/*
|--------------------------------------------------------------------------
| PURCHASE ORDER (PO)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/po', [PurchaseOrderController::class, 'index'])
        ->name('po.index');

    Route::get('/po/create', [PurchaseOrderController::class, 'create'])
        ->name('po.create');

    Route::post('/po/store', [PurchaseOrderController::class, 'store'])
        ->name('po.store');

    Route::get('/po/edit/{id}', [PurchaseOrderController::class, 'edit'])
        ->name('po.edit');

    Route::post('/po/update/{id}', [PurchaseOrderController::class, 'update'])
        ->name('po.update');

    Route::delete('/po/delete/{id}', [PurchaseOrderController::class, 'destroy'])
        ->name('po.delete');

});

/*
|--------------------------------------------------------------------------
| LAPORAN (Legacy Blade)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/laporan', [LaporanController::class, 'index'])
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