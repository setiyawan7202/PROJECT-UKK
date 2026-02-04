<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Staff\KategoriController;
use App\Http\Controllers\Staff\RuanganController;
use App\Http\Controllers\Staff\BarangController;
use App\Http\Controllers\Staff\PeminjamanController;
use App\Http\Controllers\Staff\PengaduanController;

Route::middleware(['auth', 'petugas'])->prefix('staff')->name('staff.')->group(function () {

    Route::get('/', fn() => view('staff.index'))->name('index');

    Route::resource('kategori', KategoriController::class);
    Route::resource('ruangan', RuanganController::class);
    Route::resource('barang', BarangController::class);

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

});
