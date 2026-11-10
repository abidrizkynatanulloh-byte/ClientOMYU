<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\PenjualanController;
use App\Http\Controllers\Owner\ResepHppController;
use App\Http\Controllers\Owner\BahanController;
use App\Http\Controllers\Owner\KeuanganController;
use App\Http\Controllers\Owner\KaryawanController;
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
                Route::get('/bahan', [BahanController::class, 'index'])->name('bahan.index');
                Route::get('/bahan/tambah', [BahanController::class, 'create'])->name('bahan.create');
                Route::post('/bahan', [BahanController::class, 'store'])->name('bahan.store');
                Route::get('/bahan/{id}/edit', [BahanController::class, 'edit'])->name('bahan.edit');
                Route::put('/bahan/{id}', [BahanController::class, 'update'])->name('bahan.update');
                Route::delete('/bahan/{id}', [BahanController::class, 'destroy'])->name('bahan.destroy');
                                // O-05: Modul Keuangan & Laporan Laba Rugi
                Route::get('/keuangan/laba-rugi', [KeuanganController::class, 'labaRugi'])->name('keuangan.laba_rugi');
                Route::get('/keuangan/buku-kas', [KeuanganController::class, 'bukuKas'])->name('keuangan.buku_kas');
                                // O-09: Modul Manajemen Karyawan
                Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
                Route::get('/karyawan/tambah', [KaryawanController::class, 'create'])->name('karyawan.create');
                Route::post('/karyawan', [KaryawanController::class, 'store'])->name('karyawan.store');
                Route::get('/karyawan/{id}/edit', [KaryawanController::class, 'edit'])->name('karyawan.edit');
                Route::put('/karyawan/{id}', [KaryawanController::class, 'update'])->name('karyawan.update');
                Route::patch('/karyawan/{id}/toggle-status', [KaryawanController::class, 'toggleStatus'])->name('karyawan.toggle_status');
            });
    });