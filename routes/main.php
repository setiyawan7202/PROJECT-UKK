<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Peminjaman;

use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\ProfilController;

Route::middleware(['auth'])->group(function () {
    Route::get('/main', function () {
        $user_id = Auth::id();
        $stats = [
            'active' => Peminjaman::where('user_id', $user_id)->where('status', 'active')->count(),
            'pending' => Peminjaman::where('user_id', $user_id)->where('status', 'pending')->count(),
            'completed' => Peminjaman::where('user_id', $user_id)->where('status', 'completed')->count(),
        ];
        return view('main.index', compact('stats'));
    })->name('main.index');

    // User Peminjaman Routes
    Route::resource('peminjaman', PeminjamanController::class)->only(['index', 'create', 'store', 'show']);

    // Katalog
    Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog.index');

    // Profil
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');

    // Riwayat Alias
    Route::get('/riwayat', fn() => redirect()->route('peminjaman.index'))->name('riwayat.index');
});

Route::middleware('auth')->get('/dashboard', fn() => redirect()->route('main.index'))->name('dashboard');
