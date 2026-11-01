<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => 'Halaman katalog (akan dibuat di Tahap 4)');

// login staf
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'tampilkanLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'masuk'])
        ->middleware('throttle:5,1')
        ->name('login.proses');
});

Route::post('/keluar', [AuthController::class, 'keluar'])
    ->middleware('auth')
    ->name('keluar');

// panel: karyawan dan admin
Route::middleware(['auth', 'peran:admin,karyawan'])
    ->prefix('panel')->name('panel.')
    ->group(function () {
        Route::view('/', 'panel.beranda')->name('beranda');

        // khusus admin
        Route::middleware('peran:admin')
            ->prefix('admin')->name('admin.')
            ->group(function () {
                Route::view('/', 'panel.admin.beranda')->name('beranda');
            });
    });