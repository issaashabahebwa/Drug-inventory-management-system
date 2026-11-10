<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DrugController;
use App\Http\Controllers\StockBatchController;
use App\Http\Controllers\DispensingController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Public routes (unauthenticated)
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => auth()->check()
    ? redirect()->route('dashboard')
    : view('welcome'));

Route::get('/home', fn() => view('welcome'))->name('home');

Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated routes (all roles)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // ── Dashboard ──────────────────────────────────────────────────────
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Profile ────────────────────────────────────────────────────────
    Route::get('/profile',                  [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile/avatar',          [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/profile/avatar',        [ProfileController::class, 'removeAvatar'])->name('profile.avatar.remove');
    Route::put('/profile/password',         [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::put('/profile/name',             [ProfileController::class, 'updateName'])->name('profile.name');

    // ── Drug / Inventory management ────────────────────────────────────
    Route::resource('drugs', DrugController::class)->except(['destroy']);
    Route::delete('/drugs/{drug}', [DrugController::class, 'destroy'])->name('drugs.destroy')
        ->middleware('role:administrator,stores_in_charge');

    // ── Stock Batches (receive / view) ─────────────────────────────────
    Route::resource('stock', StockBatchController::class)->except(['destroy']);

    // ── Dispensing ─────────────────────────────────────────────────────
    Route::get('/dispensing',         [DispensingController::class, 'index'])->name('dispensing.index');
    Route::get('/dispensing/create',  [DispensingController::class, 'create'])->name('dispensing.create');
    Route::post('/dispensing',        [DispensingController::class, 'store'])->name('dispensing.store');
    Route::get('/dispensing/{dispensingRecord}', [DispensingController::class, 'show'])->name('dispensing.show');

    // AJAX: get available batches for a drug (FEFO)
    Route::get('/api/drugs/{drug}/batches', [DispensingController::class, 'getBatches'])->name('api.drug.batches');

    // ── Suppliers ──────────────────────────────────────────────────────
    Route::get('/suppliers',           [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('/suppliers/create',    [SupplierController::class, 'create'])->name('suppliers.create');
    Route::post('/suppliers',          [SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');

    // ── Procurement Requests ───────────────────────────────────────────
    Route::get('/procurement',          [SupplierController::class, 'procurementIndex'])->name('procurement.index');
    Route::get('/procurement/create',   [SupplierController::class, 'procurementCreate'])->name('procurement.create');
    Route::post('/procurement',         [SupplierController::class, 'procurementStore'])->name('procurement.store');
    Route::get('/procurement/{procurementRequest}', [SupplierController::class, 'procurementShow'])->name('procurement.show');
    Route::patch('/procurement/{procurementRequest}/status', [SupplierController::class, 'procurementUpdateStatus'])
        ->name('procurement.updateStatus')
        ->middleware('role:administrator,stores_in_charge');

    // ── Reports ────────────────────────────────────────────────────────
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/stock-balance',  [ReportController::class, 'stockBalance'])->name('stock-balance');
        Route::get('/expiry',         [ReportController::class, 'expiry'])->name('expiry');
        Route::get('/dispensing',     [ReportController::class, 'dispensing'])->name('dispensing');
        Route::get('/usage-pattern',  [ReportController::class, 'usagePattern'])->name('usage-pattern');
    });

    // ── User Management (admin only) ───────────────────────────────────
    Route::middleware('role:administrator')->group(function () {
        Route::resource('users', UserController::class)->except(['show', 'destroy']);
        Route::patch('/users/{user}/reset-password', [UserController::class, 'resetPassword'])
            ->name('users.reset-password');
    });

});
