<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermitController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\Admin\UserController;

// ══════════════════════════════════════════════════════════════
// GUEST ROUTES (Unauthenticated)
// ══════════════════════════════════════════════════════════════

Route::get('/', fn () => redirect('/login'));

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);


// ══════════════════════════════════════════════════════════════
// ADMIN ROUTES
// Prefix: /admin  |  Middleware: auth + role:admin
// ══════════════════════════════════════════════════════════════

Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

    // Manajemen User
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    Route::post('/users/update/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/delete/{id}', [UserController::class, 'delete'])->name('users.delete');

    // Profile
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/update-profile', [UserController::class, 'updateProfile'])->name('profile.update');

    // Monitoring Permit (aktif: Pending & Disetujui)
    Route::get('/monitoring', [PermitController::class, 'monitoring'])->name('monitoring');

    // Riwayat Permit (final: Selesai & Ditolak)
    Route::get('/riwayat', [PermitController::class, 'riwayat'])->name('riwayat');

    // Laporan (semua permit + filter)
    Route::get('/laporan', [PermitController::class, 'laporan'])->name('laporan');

    // Detail Permit
    Route::get('/permit/{permit}', [PermitController::class, 'detail'])->name('permit.detail');

    // Export (Excel & PDF)
    Route::get('/laporan/export-excel', [ReportController::class, 'exportExcel'])->name('laporan.export.excel');
    Route::get('/laporan/export-pdf', [ReportController::class, 'exportPdf'])->name('laporan.export.pdf');

    // Import (Khusus Admin)
    Route::get('/laporan/template', [ImportController::class, 'downloadTemplate'])->name('laporan.template');
    Route::post('/laporan/import', [ImportController::class, 'import'])->name('laporan.import');
});


// ══════════════════════════════════════════════════════════════
// PEKERJA ROUTES
// Prefix: /pekerja  |  Middleware: auth + role:pekerja
// ══════════════════════════════════════════════════════════════

Route::prefix('pekerja')->middleware(['auth', 'role:pekerja'])->name('pekerja.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'pekerja'])->name('dashboard');

    // Buat Permit (form + submit)
    Route::get('/buat-permit', [PermitController::class, 'create'])->name('permit.create');
    Route::post('/buat-permit', [PermitController::class, 'store'])->name('permit.store');

    // Edit Permit (hanya jika masih Pending)
    Route::get('/edit-permit/{permit}', [PermitController::class, 'edit'])->name('permit.edit');
    Route::put('/edit-permit/{permit}', [PermitController::class, 'update'])->name('permit.update');

    // Riwayat Permit
    Route::get('/riwayat', [PermitController::class, 'riwayat'])->name('riwayat');

    // Laporan Permit
    Route::get('/laporan', [PermitController::class, 'laporan'])->name('laporan');

    // Detail Permit
    Route::get('/permit/{permit}', [PermitController::class, 'detail'])->name('permit.detail');

    // Export (Excel & PDF)
    Route::get('/laporan/export-excel', [ReportController::class, 'exportExcel'])->name('laporan.export.excel');
    Route::get('/laporan/export-pdf', [ReportController::class, 'exportPdf'])->name('laporan.export.pdf');
});


// ══════════════════════════════════════════════════════════════
// SUPERVISOR ROUTES
// Prefix: /supervisor  |  Middleware: auth + role:supervisor
// ══════════════════════════════════════════════════════════════

Route::prefix('supervisor')->middleware(['auth', 'role:supervisor'])->name('supervisor.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'supervisor'])->name('dashboard');

    // Monitoring Permit
    Route::get('/monitoring', [PermitController::class, 'monitoring'])->name('monitoring');

    // Riwayat Permit
    Route::get('/riwayat', [PermitController::class, 'riwayat'])->name('riwayat');

    // Laporan Permit
    Route::get('/laporan', [PermitController::class, 'laporan'])->name('laporan');

    // Detail Permit
    Route::get('/permit/{permit}', [PermitController::class, 'detail'])->name('permit.detail');

    // Action: Approve / Reject / Selesai
    Route::post('/permit/{permit}/approve', [PermitController::class, 'approve'])->name('permit.approve');
    Route::post('/permit/{permit}/reject', [PermitController::class, 'reject'])->name('permit.reject');
    Route::post('/permit/{permit}/selesai', [PermitController::class, 'selesai'])->name('permit.selesai');

    // Export (Excel & PDF)
    Route::get('/laporan/export-excel', [ReportController::class, 'exportExcel'])->name('laporan.export.excel');
    Route::get('/laporan/export-pdf', [ReportController::class, 'exportPdf'])->name('laporan.export.pdf');
});


// ══════════════════════════════════════════════════════════════
// SAFETY OFFICER ROUTES
// Prefix: /safety-officer  |  Middleware: auth + role:safety_officer
// (Middleware menormalisasi "safety_officer" → "safety officer")
// ══════════════════════════════════════════════════════════════

Route::prefix('safety-officer')->middleware(['auth', 'role:safety_officer'])->name('safety_officer.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'safetyOfficer'])->name('dashboard');

    // Monitoring Permit
    Route::get('/monitoring', [PermitController::class, 'monitoring'])->name('monitoring');

    // Riwayat Permit
    Route::get('/riwayat', [PermitController::class, 'riwayat'])->name('riwayat');

    // Laporan Permit
    Route::get('/laporan', [PermitController::class, 'laporan'])->name('laporan');

    // Detail Permit
    Route::get('/permit/{permit}', [PermitController::class, 'detail'])->name('permit.detail');

    // Action: Evaluasi Risiko
    Route::post('/permit/{permit}/evaluasi', [PermitController::class, 'evaluasi'])->name('permit.evaluasi');

    // Export (Excel & PDF)
    Route::get('/laporan/export-excel', [ReportController::class, 'exportExcel'])->name('laporan.export.excel');
    Route::get('/laporan/export-pdf', [ReportController::class, 'exportPdf'])->name('laporan.export.pdf');
});