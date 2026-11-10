<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\PenjualanController;
use App\Http\Controllers\Owner\ResepHppController;
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

                // khusus admin (owner)
        Route::middleware('peran:admin')
            ->prefix('admin')->name('admin.')
            ->group(function () {
                Route::get('/', [DashboardController::class, 'index'])->name('beranda');
                Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
                Route::get('/penjualan/{id}', [PenjualanController::class, 'detail'])->name('penjualan.detail');
                                // O-03: Modul Produk, Resep & HPP per Cup
                Route::get('/resep', [ResepHppController::class, 'index'])->name('resep.index');
                Route::get('/resep/{varianId}', [ResepHppController::class, 'detail'])->name('resep.detail');
            });
    });