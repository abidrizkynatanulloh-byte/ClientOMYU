<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\PenjualanController;
use App\Http\Controllers\Owner\ResepHppController;
use App\Http\Controllers\Owner\BahanController;
use App\Http\Controllers\Owner\KeuanganController;
use App\Http\Controllers\Owner\KaryawanController;
use App\Http\Controllers\Staf\AktivitasController as StafAktivitasController;
use App\Http\Controllers\Staf\DashboardController as StafDashboardController;
use App\Http\Controllers\Staf\KasirController as StafKasirController;
use App\Http\Controllers\Staf\PesananController as StafPesananController;
use App\Http\Controllers\Staf\ProfilController as StafProfilController;
use App\Http\Controllers\Staf\StokController as StafStokController;
use App\Models\Produk;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $daftarProduk = Produk::where('aktif', 1)->take(8)->get();
    return view('beranda', compact('daftarProduk'));
});

// Otentikasi Staf & Admin
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'tampilkanLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'masuk'])
        ->middleware('throttle:5,1')
        ->name('login.proses');
});

Route::post('/keluar', [AuthController::class, 'keluar'])
    ->middleware('auth')
    ->name('keluar');

// Panel Operasional: Staf Karyawan & Admin
Route::middleware(['auth', 'peran:admin,karyawan'])
    ->prefix('panel')->name('panel.')
    ->group(function () {

        // K-02: Dashboard Operasional Staf
        Route::get('/', [StafDashboardController::class, 'index'])->name('beranda');

        // K-03, K-04, K-05, K-06, K-12: Manajemen Pesanan Masuk & Verifikasi
        Route::prefix('pesanan')->name('staf.pesanan.')->group(function () {
            Route::get('/', [StafPesananController::class, 'index'])->name('index');
            Route::get('/{id}', [StafPesananController::class, 'detail'])->name('detail');
            Route::post('/{id}/verifikasi-bayar', [StafPesananController::class, 'verifikasiPembayaran'])->name('verifikasi-bayar');
            Route::post('/{id}/status', [StafPesananController::class, 'updateStatus'])->name('update-status');
            Route::post('/{id}/batal', [StafPesananController::class, 'batalkan'])->name('batal');
        });

        // K-07: Penjualan Langsung di Kasir (POS)
        Route::prefix('kasir')->name('staf.kasir.')->group(function () {
            Route::get('/', [StafKasirController::class, 'index'])->name('index');
            Route::post('/transaksi', [StafKasirController::class, 'simpanTransaksi'])->name('simpan');
            Route::get('/struk/{id}', [StafKasirController::class, 'struk'])->name('struk');
        });

        // K-09, K-10, K-11: Manajemen Stok & Kerusakan Produk
        Route::prefix('stok')->name('staf.stok.')->group(function () {
            Route::get('/', [StafStokController::class, 'index'])->name('index');
            Route::post('/{id}/penyesuaian', [StafStokController::class, 'penyesuaian'])->name('penyesuaian');
        });

        // K-14: Riwayat Aktivitas Audit Log
        Route::get('/aktivitas', [StafAktivitasController::class, 'index'])->name('staf.aktivitas.index');

        // K-01: Profil & Ubah Password
        Route::get('/profil', [StafProfilController::class, 'index'])->name('staf.profil.index');
        Route::post('/profil/password', [StafProfilController::class, 'updatePassword'])->name('staf.profil.password');

        // Khusus Admin (Owner)
        Route::middleware('peran:admin')
            ->prefix('admin')->name('admin.')
            ->group(function () {
                Route::get('/', [OwnerDashboardController::class, 'index'])->name('beranda');
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
