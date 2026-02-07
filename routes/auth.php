<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ActivationController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Activation Routes
Route::get('/activation', [ActivationController::class, 'showForm'])->name('activation.form');
Route::post('/activation/check', [ActivationController::class, 'check'])->name('activation.check');
Route::post('/activation/submit', [ActivationController::class, 'submit'])->name('activation.submit');

// Redirect legacy register route
Route::get('/register', function () {
    return redirect()->route('activation.form');
})->name('register');
