<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\RuanganController;
use App\Http\Controllers\Admin\BarangController;

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', fn() => view('admin.index'))->name('index');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::resource('kategori', KategoriController::class);
    Route::resource('ruangan', RuanganController::class);
    Route::resource('barang', BarangController::class);

});
