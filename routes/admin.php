<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\RuanganController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\KelasController;

use App\Http\Controllers\Admin\DashboardController;

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('index');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/trash', [UserController::class, 'trash'])->name('users.trash');
    Route::put('/users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::delete('/users/{id}/force', [UserController::class, 'forceDelete'])->name('users.force_delete');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // Generate kode routes (before resource routes)
    // Ruangan Trash
    Route::get('/ruangan/trash', [RuanganController::class, 'trash'])->name('ruangan.trash');
    Route::put('/ruangan/{id}/restore', [RuanganController::class, 'restore'])->name('ruangan.restore');
    Route::delete('/ruangan/{id}/force', [RuanganController::class, 'forceDelete'])->name('ruangan.force_delete');

    Route::get('/ruangan/generate-kode', [RuanganController::class, 'generateKode'])->name('ruangan.generateKode');
    // Kategori Trash
    Route::get('/kategori/trash', [KategoriController::class, 'trash'])->name('kategori.trash');
    Route::put('/kategori/{id}/restore', [KategoriController::class, 'restore'])->name('kategori.restore');
    Route::delete('/kategori/{id}/force', [KategoriController::class, 'forceDelete'])->name('kategori.force_delete');

    Route::get('/kategori/generate-kode', [KategoriController::class, 'generateKode'])->name('kategori.generateKode');

    Route::resource('kategori', KategoriController::class);
    Route::resource('ruangan', RuanganController::class);
    Route::put('/barang/unit/{id}', [BarangController::class, 'updateUnit'])->name('barang.updateUnit');
    Route::resource('barang', BarangController::class);
    // Kelas Trash
    Route::get('/kelas/trash', [KelasController::class, 'trash'])->name('kelas.trash');
    Route::put('/kelas/{id}/restore', [KelasController::class, 'restore'])->name('kelas.restore');
    Route::delete('/kelas/{id}/force', [KelasController::class, 'forceDelete'])->name('kelas.force_delete');

    Route::resource('kelas', KelasController::class);

    // Peminjaman Management
    Route::get('/peminjaman', [App\Http\Controllers\Admin\PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::post('/peminjaman/{id}/approve', [App\Http\Controllers\Admin\PeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::post('/peminjaman/{id}/reject', [App\Http\Controllers\Admin\PeminjamanController::class, 'reject'])->name('peminjaman.reject');
    Route::post('/peminjaman/{id}/activate', [App\Http\Controllers\Admin\PeminjamanController::class, 'activate'])->name('peminjaman.activate');
    Route::get('/peminjaman/{id}/bukti', [App\Http\Controllers\Admin\PeminjamanController::class, 'cetakBukti'])->name('peminjaman.bukti');

    // Return Logic
    Route::get('/peminjaman/{id}/return', [App\Http\Controllers\Admin\PeminjamanController::class, 'returnForm'])->name('peminjaman.return');
    Route::post('/peminjaman/{id}/return', [App\Http\Controllers\Admin\PeminjamanController::class, 'storeReturn'])->name('peminjaman.storeReturn');

    // Pengaduan Management
    Route::get('/pengaduan', [App\Http\Controllers\Admin\PengaduanController::class, 'index'])->name('pengaduan.index');
    Route::get('/pengaduan/{id}', [App\Http\Controllers\Admin\PengaduanController::class, 'show'])->name('pengaduan.show');
    Route::put('/pengaduan/{id}/status', [App\Http\Controllers\Admin\PengaduanController::class, 'updateStatus'])->name('pengaduan.status');
    Route::post('/pengaduan/{id}/response', [App\Http\Controllers\Admin\PengaduanController::class, 'storeResponse'])->name('pengaduan.response');

    // Laporan (Reports)
    Route::get('/laporan', [App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('laporan.index');
    Route::post('/laporan/peminjaman', [App\Http\Controllers\Admin\LaporanController::class, 'peminjaman'])->name('laporan.peminjaman');
    Route::post('/laporan/pengaduan', [App\Http\Controllers\Admin\LaporanController::class, 'pengaduan'])->name('laporan.pengaduan');
    Route::get('/laporan/barang', [App\Http\Controllers\Admin\LaporanController::class, 'barang'])->name('laporan.barang');

});
