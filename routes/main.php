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
    Route::get('/katalog/{id}', [KatalogController::class, 'show'])->name('katalog.show');

    // Cart Routes
    Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/remove', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/checkout', [App\Http\Controllers\CartController::class, 'checkout'])->name('cart.checkout');

    // Profil
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');

    // Riwayat Alias
    Route::get('/riwayat', fn() => redirect()->route('peminjaman.index'))->name('riwayat.index');

    // Pengaduan Routes
    Route::get('/pengaduan', [App\Http\Controllers\PengaduanController::class, 'index'])->name('pengaduan.index');
    Route::get('/pengaduan/create', [App\Http\Controllers\PengaduanController::class, 'create'])->name('pengaduan.create');
    Route::post('/pengaduan', [App\Http\Controllers\PengaduanController::class, 'store'])->name('pengaduan.store');
    Route::get('/pengaduan/{id}', [App\Http\Controllers\PengaduanController::class, 'show'])->name('pengaduan.show');
    Route::post('/pengaduan/{id}/response', [App\Http\Controllers\PengaduanController::class, 'addResponse'])->name('pengaduan.response');

    // AJAX endpoints for cascade dropdown
    Route::get('/pengaduan/ruangan/{ruangan_id}/barang', [App\Http\Controllers\PengaduanController::class, 'getBarangByRuangan'])->name('pengaduan.barang');
    Route::get('/pengaduan/barang/{barang_id}/units', [App\Http\Controllers\PengaduanController::class, 'getBarangUnits'])->name('pengaduan.units');
});

Route::middleware('auth')->get('/dashboard', fn() => redirect()->route('main.index'))->name('dashboard');
