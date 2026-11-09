-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 09, 2026 at 04:19 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `omyu`
--

-- --------------------------------------------------------

--
-- Table structure for table `gambar_produk`
--

CREATE TABLE `gambar_produk` (
  `id` bigint UNSIGNED NOT NULL,
  `produk_id` bigint UNSIGNED NOT NULL,
  `path` varchar(255) NOT NULL,
  `urutan` smallint UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_pesanan`
--

CREATE TABLE `item_pesanan` (
  `id` bigint UNSIGNED NOT NULL,
  `pesanan_id` bigint UNSIGNED NOT NULL,
  `produk_id` bigint UNSIGNED DEFAULT NULL,
  `nama_produk` varchar(150) NOT NULL,
  `harga` bigint UNSIGNED NOT NULL,
  `jumlah` int UNSIGNED NOT NULL,
  `subtotal` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama`, `slug`, `aktif`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Keripik', 'keripik', 1, '2026-11-01 00:23:30', '2026-11-09 03:10:45', NULL),
(2, 'Tes', 'tes', 1, '2026-11-09 03:09:51', '2026-11-09 03:09:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id` bigint UNSIGNED NOT NULL,
  `pengguna_id` bigint UNSIGNED DEFAULT NULL,
  `aksi` varchar(50) NOT NULL,
  `tipe_objek` varchar(50) DEFAULT NULL,
  `id_objek` bigint UNSIGNED DEFAULT NULL,
  `deskripsi` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id`, `pengguna_id`, `aksi`, `tipe_objek`, `id_objek`, `deskripsi`, `created_at`, `deleted_at`) VALUES
(1, NULL, 'tes', NULL, NULL, NULL, '2026-11-09 03:10:52', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` bigint UNSIGNED NOT NULL,
  `pesanan_id` bigint UNSIGNED NOT NULL,
  `gerbang` varchar(30) NOT NULL,
  `referensi_gerbang` varchar(100) DEFAULT NULL,
  `metode` varchar(50) DEFAULT NULL,
  `jumlah` bigint UNSIGNED NOT NULL,
  `status` enum('menunggu','lunas','kedaluwarsa','gagal','dikembalikan') NOT NULL DEFAULT 'menunggu',
  `dibayar_pada` datetime DEFAULT NULL,
  `data_mentah` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `peran` enum('admin','karyawan') NOT NULL DEFAULT 'karyawan',
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `telepon` varchar(20) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id`, `nama`, `email`, `password`, `peran`, `aktif`, `telepon`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Admin OMYU', 'admin@omyu.test', '$2y$12$4z5vlWXSeBm9gDIqsL71N.zCH9rXsAG0zHj0Mne/vPXL8ZBuSAb9e', 'admin', 1, NULL, NULL, '2026-11-01 07:54:03', '2026-11-09 03:23:35', NULL),
(2, 'Budi', 'budi@omyu.test', '$2y$12$YM87AsU/W4VyrM/ljLEgxeaxu62Q8oWs7bwTvt3tUaSp8LBsvZ9XS', 'karyawan', 1, '081234567890', '0vu6y4jAHYC5EAp7W7qz73qmAC0Ob1oHbm1bOwUvV5sAeXwFjn47nWohAexo', '2026-11-09 03:23:35', '2026-11-09 03:23:35', NULL),
(3, 'Siti', 'siti@omyu.test', '$2y$12$Eg63F494Cnwtc/q1FnDqCOiKeSIA/cEyG2MydxiFjd20KFA4JMyK6', 'karyawan', 1, NULL, NULL, '2026-11-09 03:23:35', '2026-11-09 03:23:35', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_pesanan` varchar(30) NOT NULL,
  `token_akses` char(64) NOT NULL,
  `nama_pembeli` varchar(100) NOT NULL,
  `telepon_pembeli` varchar(20) NOT NULL,
  `email_pembeli` varchar(150) DEFAULT NULL,
  `alamat_pengiriman` text NOT NULL,
  `catatan` text,
  `subtotal` bigint UNSIGNED NOT NULL,
  `ongkir` bigint UNSIGNED NOT NULL DEFAULT '0',
  `total` bigint UNSIGNED NOT NULL,
  `status_pembayaran` enum('menunggu','lunas','kedaluwarsa','gagal','dikembalikan') NOT NULL DEFAULT 'menunggu',
  `status_pesanan` enum('baru','diproses','dikirim','selesai','dibatalkan') NOT NULL DEFAULT 'baru',
  `kedaluwarsa_pada` datetime NOT NULL,
  `dibayar_pada` datetime DEFAULT NULL,
  `ditangani_oleh` bigint UNSIGNED DEFAULT NULL,
  `nomor_resi` varchar(60) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` bigint UNSIGNED NOT NULL,
  `kategori_id` bigint UNSIGNED DEFAULT NULL,
  `nama` varchar(150) NOT NULL,
  `slug` varchar(170) NOT NULL,
  `deskripsi` text,
  `harga` bigint UNSIGNED NOT NULL,
  `stok` int UNSIGNED NOT NULL DEFAULT '0',
  `berat_gram` int UNSIGNED DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `dibuat_oleh` bigint UNSIGNED DEFAULT NULL,
  `diubah_oleh` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_stok`
--

CREATE TABLE `riwayat_stok` (
  `id` bigint UNSIGNED NOT NULL,
  `produk_id` bigint UNSIGNED NOT NULL,
  `pengguna_id` bigint UNSIGNED DEFAULT NULL,
  `pesanan_id` bigint UNSIGNED DEFAULT NULL,
  `jenis` enum('masuk','keluar_pesanan','kembali_kedaluwarsa','penyesuaian') NOT NULL,
  `jumlah` int NOT NULL,
  `stok_setelah` int UNSIGNED NOT NULL,
  `catatan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gambar_produk`
--
ALTER TABLE `gambar_produk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_gambar_produk` (`produk_id`);

--
-- Indexes for table `item_pesanan`
--
ALTER TABLE `item_pesanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_item_pesanan` (`pesanan_id`),
  ADD KEY `fk_item_produk` (`produk_id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_log_pengguna` (`pengguna_id`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `referensi_gerbang` (`referensi_gerbang`),
  ADD KEY `fk_pembayaran_pesanan` (`pesanan_id`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_pengguna_peran` (`peran`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_pesanan` (`kode_pesanan`),
  ADD UNIQUE KEY `token_akses` (`token_akses`),
  ADD KEY `idx_pesanan_status_bayar` (`status_pembayaran`),
  ADD KEY `idx_pesanan_status` (`status_pesanan`),
  ADD KEY `idx_pesanan_dibuat` (`created_at`),
  ADD KEY `idx_pesanan_telepon` (`telepon_pembeli`),
  ADD KEY `fk_pesanan_penangan` (`ditangani_oleh`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_produk_aktif` (`aktif`),
  ADD KEY `fk_produk_kategori` (`kategori_id`),
  ADD KEY `fk_produk_pembuat` (`dibuat_oleh`),
  ADD KEY `fk_produk_pengubah` (`diubah_oleh`);

--
-- Indexes for table `riwayat_stok`
--
ALTER TABLE `riwayat_stok`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_riwayat_produk` (`produk_id`),
  ADD KEY `fk_riwayat_pengguna` (`pengguna_id`),
  ADD KEY `fk_riwayat_pesanan` (`pesanan_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gambar_produk`
--
ALTER TABLE `gambar_produk`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_pesanan`
--
ALTER TABLE `item_pesanan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `riwayat_stok`
--
ALTER TABLE `riwayat_stok`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `gambar_produk`
--
ALTER TABLE `gambar_produk`
  ADD CONSTRAINT `fk_gambar_produk` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item_pesanan`
--
ALTER TABLE `item_pesanan`
  ADD CONSTRAINT `fk_item_pesanan` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_item_produk` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `fk_log_pengguna` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `fk_pembayaran_pesanan` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `fk_pesanan_penangan` FOREIGN KEY (`ditangani_oleh`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `fk_produk_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_produk_pembuat` FOREIGN KEY (`dibuat_oleh`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_produk_pengubah` FOREIGN KEY (`diubah_oleh`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `riwayat_stok`
--
ALTER TABLE `riwayat_stok`
  ADD CONSTRAINT `fk_riwayat_pengguna` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_riwayat_pesanan` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_riwayat_produk` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
