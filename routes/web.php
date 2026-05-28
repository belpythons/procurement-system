<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\LaporanController;
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
    $totalRP = Procurement::where('status', Procurement::STATUS_RP)->count();
    $totalTE = Procurement::where('status', Procurement::STATUS_TE)->count();
    $totalRETE = Procurement::where('status', Procurement::STATUS_RETE)->count();
    $totalPO = Procurement::where('status', Procurement::STATUS_PO)->count();

    /*
    |----------------------------------------
    | DATA PROCUREMENT (REAL SYSTEM 1 TABLE)
    |----------------------------------------
    */
    $procurements = Procurement::when($status, function ($query) use ($status) {
            return $query->where('status', $status);
        })
        ->orderBy('id', 'desc')
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'kode_pengadaan' => $item->kode_pengadaan,
                'nama_barang' => $item->nama_barang,
                'vendor' => $item->vendor,
                'status' => $item->status,
                'tanggal' => $item->tanggal?->format('Y-m-d'),
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

    Route::post('/procurement/approve-phase/{id}', [ProcurementController::class, 'approvePhase'])
        ->name('procurement.approve_phase');

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