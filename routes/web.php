<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| File utama routes yang memuat route files lainnya
*/

// Homepage
Route::get('/', function () {
    return view('welcome');
});

// Load route files
require __DIR__ . '/auth.php';
require __DIR__ . '/main.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/staff.php';
