<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Owner\BahanController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\KaryawanController;
use App\Http\Controllers\Owner\KeuanganController;
use App\Http\Controllers\Owner\PenjualanController;
use App\Http\Controllers\Owner\ProdukController;
use App\Http\Controllers\Owner\ResepHppController;
use App\Http\Controllers\PelangganAuthController;
use App\Http\Controllers\PelangganPesananController;
use App\Http\Controllers\Staf\AktivitasController as StafAktivitasController;
use App\Http\Controllers\Staf\BantuanController as StafBantuanController;
use App\Http\Controllers\Staf\DashboardController as StafDashboardController;
use App\Http\Controllers\Staf\KasirController as StafKasirController;
use App\Http\Controllers\Staf\PesananController as StafPesananController;
use App\Http\Controllers\Staf\ProduksiController as StafProduksiController;
use App\Http\Controllers\Staf\ProfilController as StafProfilController;
use App\Http\Controllers\Staf\StokController as StafStokController;
use App\Models\Produk;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Menu favorit dihitung dari total cup pada pesanan yang sudah selesai.
    $menuFavorit = Produk::where('aktif', 1)
        ->withSum(['itemPesanan as jumlah_terjual' => function ($query) {
            $query->whereHas('pesanan', function ($pesanan) {
                $pesanan->where('status_pesanan', 'selesai');
            });
        }], 'jumlah')
        ->orderByDesc('jumlah_terjual')
        ->take(4)
        ->get();

    return view('beranda', compact('menuFavorit'));
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

        // K-02: Dashboard Operasional Staf (Admin otomatis diarahkan ke web admin)
        Route::get('/', function () {
            if (auth()->user()?->adalahAdmin()) {
                return redirect()->route('panel.admin.beranda');
            }

            return app(StafDashboardController::class)->index();
        })->name('beranda');

        // K-03, K-04, K-05, K-06, K-12: Manajemen Pesanan Masuk & Verifikasi
        Route::prefix('pesanan')->name('staf.pesanan.')->group(function () {
            Route::get('/', [StafPesananController::class, 'index'])->name('index');
            Route::get('/{id}', [StafPesananController::class, 'detail'])->name('detail');
            Route::post('/{id}/verifikasi-bayar', [StafPesananController::class, 'verifikasiPembayaran'])->name('verifikasi-bayar');
            Route::post('/{id}/status', [StafPesananController::class, 'updateStatus'])->name('update-status');
            Route::post('/{id}/batal', [StafPesananController::class, 'batalkan'])->name('batal');
        });

        // K-06: Antrean Racik Jus & KDS Mode (Kitchen Display System)
        Route::prefix('produksi')->name('staf.produksi.')->group(function () {
            Route::get('/', [StafProduksiController::class, 'index'])->name('index');
            Route::post('/{id}/mulai-racik', [StafProduksiController::class, 'mulaiRacik'])->name('mulai-racik');
            Route::post('/{id}/selesai-racik', [StafProduksiController::class, 'selesaiRacik'])->name('selesai-racik');
            Route::post('/catat-gagal', [StafProduksiController::class, 'catatGagal'])->name('catat-gagal');
        });

        // K-07: Penjualan Langsung di Kasir (POS)
        Route::prefix('kasir')->name('staf.kasir.')->group(function () {
            Route::get('/', [StafKasirController::class, 'index'])->name('index');
            Route::post('/transaksi', [StafKasirController::class, 'simpanTransaksi'])->name('simpan');
            Route::get('/struk/{id}', [StafKasirController::class, 'struk'])->name('struk');
        });

        // K-09, K-10, K-11: Manajemen Stok Bahan Baku Segar & Kerusakan
        Route::prefix('stok')->name('staf.stok.')->group(function () {
            Route::get('/', [StafStokController::class, 'index'])->name('index');
            Route::post('/pasokan', [StafStokController::class, 'pasokanMasuk'])->name('pasokan');
            Route::post('/pembuangan', [StafStokController::class, 'catatPembuangan'])->name('pembuangan');
            Route::post('/{id}/toggle-aktif', [StafStokController::class, 'toggleAktif'])->name('toggle-aktif');
            Route::post('/{id}/audit', [StafStokController::class, 'auditBahan'])->name('audit');
            Route::post('/{id}/penyesuaian', [StafStokController::class, 'penyesuaian'])->name('penyesuaian');
        });

        // K-14: Riwayat Aktivitas Audit Log
        Route::get('/aktivitas', [StafAktivitasController::class, 'index'])->name('staf.aktivitas.index');

        Route::get('/bantuan', [StafBantuanController::class, 'index'])->name('staf.bantuan.index');

        // K-01: Profil & Ubah Password
        Route::get('/profil', [StafProfilController::class, 'index'])->name('staf.profil.index');
        Route::post('/profil/password', [StafProfilController::class, 'updatePassword'])->name('staf.profil.password');
        Route::post('/profil/foto', [StafProfilController::class, 'updateFoto'])->name('staf.profil.foto');

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
                // O-03: Kelola Menu/Produk & Upload Gambar (otomatis terkompres maks. 100 KB)
                Route::get('/menu', [ProdukController::class, 'index'])->name('menu.index');
                Route::get('/menu/tambah', [ProdukController::class, 'create'])->name('menu.create');
                Route::post('/menu', [ProdukController::class, 'store'])->name('menu.store');
                Route::get('/menu/{id}/edit', [ProdukController::class, 'edit'])->name('menu.edit');
                Route::put('/menu/{id}', [ProdukController::class, 'update'])->name('menu.update');
                Route::delete('/menu/{id}', [ProdukController::class, 'destroy'])->name('menu.destroy');
                Route::post('/menu/hitung-ulang', [ProdukController::class, 'hitungUlang'])->name('menu.hitung-ulang');
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

// ==========================================
// FITUR SISI PEMBELI (TANPA LOGIN - BAB 3)
// ==========================================
Route::prefix('order')->name('pelanggan.')->group(function () {
    // P-02: Katalog Menu (bisa dilihat tanpa login)
    Route::get('/menu', [PelangganPesananController::class, 'katalog'])->name('katalog');

    // Login/daftar pelanggan (nomor HP + password) - opsional
    Route::get('/login', [PelangganAuthController::class, 'tampilkanLogin'])->name('login');
    Route::post('/login/cek', [PelangganAuthController::class, 'cekTelepon'])->name('login.cek');
    Route::post('/login', [PelangganAuthController::class, 'masuk'])->name('login.proses');
    Route::post('/daftar', [PelangganAuthController::class, 'daftar'])->name('daftar.proses');
    Route::post('/logout', [PelangganAuthController::class, 'keluar'])->name('logout');

    // P-04: Keranjang Belanja (guest boleh, pakai session/device)
    Route::post('/keranjang/tambah', [PelangganPesananController::class, 'tambahKeranjang'])->name('keranjang.tambah');
    Route::post('/keranjang/update', [PelangganPesananController::class, 'updateKeranjang'])->name('keranjang.update');
    Route::get('/keranjang', [PelangganPesananController::class, 'lihatKeranjang'])->name('keranjang');
    Route::delete('/keranjang/{key}', [PelangganPesananController::class, 'hapusKeranjang'])->name('keranjang.hapus');

    // P-05: Checkout (guest boleh)
    Route::get('/checkout', [PelangganPesananController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [PelangganPesananController::class, 'prosesCheckout'])->name('checkout.proses');

    // P-06 & P-07: Pembayaran & Konfirmasi
    Route::get('/pembayaran/{kode}', [PelangganPesananController::class, 'halamanPembayaran'])->name('pembayaran');
    Route::post('/pembayaran/{kode}/konfirmasi', [PelangganPesananController::class, 'konfirmasiBayar'])->name('pembayaran.konfirmasi');

    // P-08: Cek Status Pesanan Real-time (guest by device token, login by akun)
    Route::get('/lacak', [PelangganPesananController::class, 'lacakStatus'])->name('lacak');
});
