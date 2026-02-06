<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Staff\DashboardController;
use App\Http\Controllers\Staff\KategoriController;
use App\Http\Controllers\Staff\RuanganController;
use App\Http\Controllers\Staff\BarangController;
use App\Http\Controllers\Staff\PeminjamanController;
use App\Http\Controllers\Staff\PengaduanController;
use App\Http\Controllers\Staff\KelasController;
use App\Http\Controllers\Staff\UserController;
use App\Http\Controllers\Staff\ScanController;

Route::middleware(['auth', 'petugas'])->prefix('staff')->name('staff.')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart');
    Route::get('/scan', [ScanController::class, 'index'])->name('scan.index');

    // Kategori
    Route::resource('kategori', KategoriController::class)->only(['index']);
    Route::middleware('permission:manage_kategori')->resource('kategori', KategoriController::class)->except(['index', 'show']);

    // Ruangan
    Route::resource('ruangan', RuanganController::class)->only(['index']);
    Route::middleware('permission:manage_ruangan')->resource('ruangan', RuanganController::class)->except(['index', 'show']);

    // Barang
    Route::resource('barang', BarangController::class)->only(['index', 'show']);
    Route::middleware('permission:manage_barang')->group(function () {
        Route::resource('barang', BarangController::class)->except(['index', 'show']);
        Route::put('barang/unit/{id}', [BarangController::class, 'updateUnit'])->name('barang.updateUnit');
    });

    // Peminjaman Routes
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
    Route::post('/peminjaman/{id}/approve', [PeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::post('/peminjaman/{id}/reject', [PeminjamanController::class, 'reject'])->name('peminjaman.reject');

    Route::middleware(['block.weekend'])->group(function () {
        Route::post('/peminjaman/{id}/activate', [PeminjamanController::class, 'activate'])->name('peminjaman.activate');
        Route::get('/peminjaman/{id}/return', [PeminjamanController::class, 'returnForm'])->name('peminjaman.return');
        Route::post('/peminjaman/{id}/return', [PeminjamanController::class, 'storeReturn'])->name('peminjaman.storeReturn');
    });

    Route::get('/peminjaman/{id}/bukti', [PeminjamanController::class, 'cetakBukti'])->name('peminjaman.bukti');

    // Pengaduan Routes
    Route::get('/pengaduan', [PengaduanController::class, 'index'])->name('pengaduan.index');
    Route::get('/pengaduan/{id}', [PengaduanController::class, 'show'])->name('pengaduan.show');
    Route::put('/pengaduan/{id}/status', [PengaduanController::class, 'updateStatus'])->name('pengaduan.status');
    Route::post('/pengaduan/{id}/response', [PengaduanController::class, 'storeResponse'])->name('pengaduan.response');

    // Users
    Route::resource('users', UserController::class)->only(['index']);
    Route::middleware('permission:manage_users')->resource('users', UserController::class)->except(['index', 'show']);

    // Kelas
    Route::resource('kelas', KelasController::class)->only(['index']);
    Route::middleware('permission:manage_kelas')->resource('kelas', KelasController::class)->except(['index']);

    // Fallback URL for QR Code Scanning (PMJ-XXXXX) to handle text-only scans
    Route::get('/{kode}', [PeminjamanController::class, 'redirectByKode'])
        ->where('kode', 'PMJ-[0-9]+')
        ->name('redirectByKode');

});
