<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManajemenPenggunaController;
use App\Http\Controllers\PenerimaBantuanController;
use App\Http\Controllers\PetaDistribusiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SyaratController;
use App\Http\Controllers\AturanController;
use App\Http\Controllers\KelayakanController;


Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login-submit', [AuthController::class, 'login'])->name('login.submit');
});

Route::middleware('auth')->group(function () {

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::put('/profil/update', [ProfileController::class, 'update'])->name('profil.update');

    Route::middleware(['role:Admin'])
        ->get('/dashboard/admin', [DashboardController::class, 'admin'])
        ->name('dashboard.admin');

    Route::middleware(['role:Staf'])
        ->get('/dashboard/staf', [DashboardController::class, 'staf'])
        ->name('dashboard.staf');

    Route::middleware(['role:Pimpinan'])
        ->get('/dashboard/pimpinan', [DashboardController::class, 'pimpinan'])
        ->name('dashboard.pimpinan');

    // Manajemen pengguna (khusus Admin)
    Route::middleware(['role:Admin'])->group(function () {
        Route::get('/manajemen-pengguna', [ManajemenPenggunaController::class, 'index'])->name('manajemen.index');
        Route::post('/manajemen-pengguna', [ManajemenPenggunaController::class, 'store'])->name('manajemen.store');
        Route::put('/manajemen-pengguna/{id}', [ManajemenPenggunaController::class, 'update'])->name('manajemen.update');
        Route::delete('/manajemen-pengguna/{id}', [ManajemenPenggunaController::class, 'destroy'])->name('manajemen.destroy');
        Route::post('/manajemen-pengguna/{id}/reset', [ManajemenPenggunaController::class, 'resetPassword'])->name('manajemen.reset');
    });

    // Data Bansos
    Route::get('/data-bansos', [PenerimaBantuanController::class, 'index'])->name('penerima.index');
    Route::get('/data-bansos/create', [PenerimaBantuanController::class, 'create'])->name('penerima.create');
    Route::post('/data-bansos', [PenerimaBantuanController::class, 'store'])->name('penerima.store');
    Route::delete('/data-bansos/{id}', [PenerimaBantuanController::class, 'destroy'])->name('penerimabantuan.destroy');
    Route::put('/data-bansos/{id}', [PenerimaBantuanController::class, 'update'])->name('tambahdata.update');

    // Peta Distribusi
    Route::get('/peta-distribusi', [PetaDistribusiController::class, 'index'])->name('peta.distribusi');
    Route::get('/peta-distribusi/search', [PetaDistribusiController::class, 'search'])->name('peta.search');
    Route::post('/peta-distribusi/upload-foto/{nik}', [PetaDistribusiController::class, 'uploadFoto']);
    Route::post('/peta-distribusi/hapus-foto/{nik}', [PetaDistribusiController::class, 'hapusFoto'])
    ->name('peta-distribusi.hapus-foto');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export/pdf', [LaporanController::class, 'exportPDF'])->name('laporan.export.pdf');
    Route::get('/laporan/export/excel', [LaporanController::class, 'exportExcel'])->name('laporan.export.excel');

    // (Opsional) Debug middleware per role
    Route::middleware(['role:Admin'])->get('/__check-admin', fn () => 'OK: Admin only');

    Route::get('/__debug-mw', function () {
        $router = app('router');
        return [
            'admin'    => optional($router->getRoutes()->getByName('dashboard.admin'))->gatherMiddleware(),
            'staf'     => optional($router->getRoutes()->getByName('dashboard.staf'))->gatherMiddleware(),
            'pimpinan' => optional($router->getRoutes()->getByName('dashboard.pimpinan'))->gatherMiddleware(),
        ];
    });

    /* ==========================================================
       SISTEM PAKAR (Forward Chaining) — prefix: /sp, name: sp.
       ========================================================== */
    Route::prefix('sp')->name('sp.')->group(function () {

        // Admin boleh kelola Data Syarat & Data Aturan
        Route::middleware(['role:Admin'])->group(function () {
            Route::resource('syarat', SyaratController::class)->except(['show']); // sp.syarat.index, create, store, edit, update, destroy
            Route::resource('aturan', AturanController::class)->except(['show']); // sp.aturan.index, ...
        });

        // Cek Kelayakan bisa diakses semua role (ubah jika perlu)
        Route::get('cek',        [KelayakanController::class, 'index'])->name('kelayakan.index');
        Route::post('cek/start', [KelayakanController::class, 'start'])->name('kelayakan.start');
        Route::post('cek/answer',[KelayakanController::class, 'answer'])->name('kelayakan.answer');
        Route::get('riwayat', [KelayakanController::class, 'history'])->name('kelayakan.history');
    });
});
