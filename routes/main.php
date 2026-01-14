<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('main')->name('main.')->group(function () {

    Route::get('/', fn() => view('main.index'))->name('index');
    Route::get('/peminjaman', fn() => view('main.peminjaman.index'))->name('peminjaman.index');
    Route::get('/riwayat', fn() => view('main.riwayat.index'))->name('riwayat.index');
    Route::get('/katalog', fn() => view('main.katalog.index'))->name('katalog.index');
    Route::get('/profil', fn() => view('main.profil.index'))->name('profil.index');

});

Route::middleware('auth')->get('/dashboard', fn() => redirect()->route('main.index'))->name('dashboard');
