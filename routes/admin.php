<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\RuanganController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\KelasController;

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', fn() => view('admin.index'))->name('index');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // Generate kode routes (before resource routes)
    Route::get('/ruangan/generate-kode', [RuanganController::class, 'generateKode'])->name('ruangan.generateKode');
    Route::get('/kategori/generate-kode', [KategoriController::class, 'generateKode'])->name('kategori.generateKode');

    Route::resource('kategori', KategoriController::class);
    Route::resource('ruangan', RuanganController::class);
    Route::put('/barang/unit/{id}', [BarangController::class, 'updateUnit'])->name('barang.updateUnit');
    Route::resource('barang', BarangController::class);
    Route::resource('kelas', KelasController::class);

    // Peminjaman Management
    Route::get('/peminjaman', [App\Http\Controllers\Admin\PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::post('/peminjaman/{id}/approve', [App\Http\Controllers\Admin\PeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::post('/peminjaman/{id}/reject', [App\Http\Controllers\Admin\PeminjamanController::class, 'reject'])->name('peminjaman.reject');
    Route::post('/peminjaman/{id}/activate', [App\Http\Controllers\Admin\PeminjamanController::class, 'activate'])->name('peminjaman.activate');
    Route::get('/peminjaman/{id}/bukti', [App\Http\Controllers\Admin\PeminjamanController::class, 'cetakBukti'])->name('peminjaman.bukti');

});
