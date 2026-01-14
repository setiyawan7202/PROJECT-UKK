<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Staff\KategoriController;
use App\Http\Controllers\Staff\RuanganController;
use App\Http\Controllers\Staff\BarangController;

Route::middleware('auth')->prefix('staff')->name('staff.')->group(function () {

    Route::get('/', fn() => view('staff.index'))->name('index');

    Route::resource('kategori', KategoriController::class);
    Route::resource('ruangan', RuanganController::class);
    Route::resource('barang', BarangController::class);

});
