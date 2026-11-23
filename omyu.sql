-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 23, 2026 at 06:20 AM
-- Server version: 13.0.2-MariaDB
-- PHP Version: 8.5.13

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
-- Table structure for table `akun`
--

CREATE TABLE `akun` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `golongan` enum('aset_lancar','aset_tetap','pengurang_aset','kewajiban','ekuitas','pengurang_ekuitas','pendapatan','pengurang_pendapatan','harga_pokok','beban_operasional','pendapatan_lain','beban_pajak') NOT NULL,
  `saldo_normal` enum('debit','kredit') NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `akun`
--

INSERT INTO `akun` (`id`, `kode`, `nama`, `golongan`, `saldo_normal`, `aktif`, `created_at`, `updated_at`) VALUES
(1, '1101', 'Kas Laci Kasir', 'aset_lancar', 'debit', 1, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(2, '1102', 'Bank', 'aset_lancar', 'debit', 1, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(3, '1103', 'Dompet Digital dan QRIS', 'aset_lancar', 'debit', 1, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(4, '1201', 'Persediaan Bahan Baku', 'aset_lancar', 'debit', 1, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(5, '1202', 'Persediaan Kemasan', 'aset_lancar', 'debit', 1, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(6, '1501', 'Peralatan', 'aset_tetap', 'debit', 1, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(7, '1502', 'Akumulasi Penyusutan Peralatan', 'pengurang_aset', 'kredit', 1, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(8, '2101', 'Utang Usaha', 'kewajiban', 'kredit', 1, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(9, '2102', 'Uang Muka Pelanggan', 'kewajiban', 'kredit', 1, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(10, '2103', 'Utang Pajak Penghasilan', 'kewajiban', 'kredit', 1, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(11, '2104', 'Utang Pajak Daerah', 'kewajiban', 'kredit', 1, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(12, '3101', 'Modal Pemilik', 'ekuitas', 'kredit', 1, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(13, '3102', 'Prive', 'pengurang_ekuitas', 'debit', 1, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(14, '3103', 'Laba Ditahan', 'ekuitas', 'kredit', 1, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(15, '4101', 'Penjualan Minuman', 'pendapatan', 'kredit', 1, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(16, '4102', 'Penjualan Makanan dan Snack', 'pendapatan', 'kredit', 1, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(17, '4201', 'Diskon Penjualan', 'pengurang_pendapatan', 'debit', 1, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(18, '5101', 'HPP Bahan Baku', 'harga_pokok', 'debit', 1, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(19, '5102', 'HPP Kemasan', 'harga_pokok', 'debit', 1, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(20, '6101', 'Beban Gaji dan Upah', 'beban_operasional', 'debit', 1, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(21, '6102', 'Beban Sewa', 'beban_operasional', 'debit', 1, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(22, '6103', 'Beban Listrik, Air, dan Gas', 'beban_operasional', 'debit', 1, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(23, '6104', 'Beban Biaya Transaksi Pembayaran Digital', 'beban_operasional', 'debit', 1, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(24, '6105', 'Beban Bahan Rusak dan Terbuang', 'beban_operasional', 'debit', 1, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(25, '6106', 'Beban Selisih Persediaan', 'beban_operasional', 'debit', 1, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(26, '6107', 'Beban Selisih Kas', 'beban_operasional', 'debit', 1, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(27, '6108', 'Beban Penyusutan', 'beban_operasional', 'debit', 1, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(28, '6109', 'Beban Pemasaran', 'beban_operasional', 'debit', 1, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(29, '6110', 'Beban Lain-lain', 'beban_operasional', 'debit', 1, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(30, '7101', 'Pendapatan Lain', 'pendapatan_lain', 'kredit', 1, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(31, '7201', 'Beban Pajak Penghasilan Final', 'beban_pajak', 'debit', 1, '2026-11-10 01:51:33', '2026-11-10 01:51:33');

-- --------------------------------------------------------

--
-- Table structure for table `bahan`
--

CREATE TABLE `bahan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis` enum('buah','bahan_lain','kemasan') NOT NULL DEFAULT 'bahan_lain',
  `satuan_dasar` enum('g','ml','pcs') NOT NULL,
  `satuan_beli` varchar(20) NOT NULL,
  `konversi` decimal(14,3) NOT NULL DEFAULT 1.000,
  `rendemen_standar` decimal(5,2) NOT NULL DEFAULT 100.00,
  `batas_minimum` decimal(14,3) NOT NULL DEFAULT 0.000,
  `stok` decimal(14,3) NOT NULL DEFAULT 0.000,
  `stok_dicadangkan` decimal(14,3) NOT NULL DEFAULT 0.000,
  `biaya_rata_rata` decimal(14,4) NOT NULL DEFAULT 0.0000,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bahan`
--

INSERT INTO `bahan` (`id`, `nama`, `jenis`, `satuan_dasar`, `satuan_beli`, `konversi`, `rendemen_standar`, `batas_minimum`, `stok`, `stok_dicadangkan`, `biaya_rata_rata`, `aktif`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Melon', 'buah', 'g', 'kg', 1000.000, 70.00, 1000.000, 4900.000, 1600.000, 21.1900, 1, '2026-11-10 02:51:34', '2026-11-23 06:05:48', NULL),
(2, 'Gula Pasir', 'bahan_lain', 'g', 'kg', 1000.000, 100.00, 500.000, 1000.000, 120.000, 15.0000, 1, '2026-11-10 02:51:34', '2026-11-23 06:05:48', NULL),
(3, 'Es Batu', 'bahan_lain', 'g', 'kg', 1000.000, 100.00, 1000.000, 5000.000, 800.000, 3.0000, 1, '2026-11-10 02:51:34', '2026-11-23 06:05:48', NULL),
(4, 'Cup & Sedotan', 'kemasan', 'pcs', 'pack', 50.000, 100.00, 20.000, 100.000, 8.000, 1200.0000, 1, '2026-11-10 02:51:34', '2026-11-23 06:05:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-18ef183b66c0aa8d4e1d4b392f7171c6245cd546', 'i:1;', 1795329125),
('laravel-cache-18ef183b66c0aa8d4e1d4b392f7171c6245cd546:timer', 'i:1795329125;', 1795329125),
('laravel-cache-1d5dbe5259e04d21e28ff6d4fd4faddc37f159e3', 'i:1;', 1795414386),
('laravel-cache-1d5dbe5259e04d21e28ff6d4fd4faddc37f159e3:timer', 'i:1795414386;', 1795414386),
('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba', 'i:1;', 1795409673),
('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba:timer', 'i:1795409673;', 1795409673),
('laravel-cache-9665cf3b6da3b189133f3f4cc9286c9a0f4d6671', 'i:1;', 1795330643),
('laravel-cache-9665cf3b6da3b189133f3f4cc9286c9a0f4d6671:timer', 'i:1795330643;', 1795330643),
('laravel-cache-da4b9237bacccdf19c0760cab7aec4a8359010b0', 'i:1;', 1795322500),
('laravel-cache-da4b9237bacccdf19c0760cab7aec4a8359010b0:timer', 'i:1795322500;', 1795322500);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cadangan_bahan`
--

CREATE TABLE `cadangan_bahan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pesanan_id` bigint(20) UNSIGNED NOT NULL,
  `bahan_id` bigint(20) UNSIGNED NOT NULL,
  `jumlah` decimal(14,3) NOT NULL,
  `status` enum('aktif','dilepas','terpakai') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gambar_produk`
--

CREATE TABLE `gambar_produk` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `produk_id` bigint(20) UNSIGNED NOT NULL,
  `path` varchar(255) NOT NULL,
  `urutan` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_pesanan`
--

CREATE TABLE `item_pesanan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pesanan_id` bigint(20) UNSIGNED NOT NULL,
  `produk_id` bigint(20) UNSIGNED DEFAULT NULL,
  `varian_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nama_produk` varchar(150) NOT NULL,
  `nama_varian` varchar(50) DEFAULT NULL,
  `harga` bigint(20) UNSIGNED NOT NULL,
  `jumlah` int(10) UNSIGNED NOT NULL,
  `subtotal` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `resep_id` bigint(20) UNSIGNED DEFAULT NULL,
  `hpp_bahan` decimal(14,2) DEFAULT NULL,
  `hpp_kemasan` decimal(14,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `item_pesanan`
--

INSERT INTO `item_pesanan` (`id`, `pesanan_id`, `produk_id`, `varian_id`, `nama_produk`, `nama_varian`, `harga`, `jumlah`, `subtotal`, `created_at`, `updated_at`, `deleted_at`, `resep_id`, `hpp_bahan`, `hpp_kemasan`) VALUES
(1, 1, 1, 1, 'Jus Melon Segar', 'Reguler', 12000, 1, 12000, '2026-11-23 04:52:33', '2026-11-23 04:52:33', NULL, 1, 4763.00, 1200.00),
(2, 2, 1, 1, 'Jus Melon Segar', 'Reguler', 12000, 1, 12000, '2026-11-23 04:58:30', '2026-11-23 04:58:30', NULL, 1, 4763.00, 1200.00),
(3, 3, 1, 1, 'Jus Melon Segar', 'Reguler', 12000, 2, 24000, '2026-11-23 05:26:12', '2026-11-23 05:26:12', NULL, 1, 4763.00, 1200.00),
(4, 4, 1, 1, 'Jus Melon Segar', 'Reguler', 12000, 1, 12000, '2026-11-23 05:33:23', '2026-11-23 05:33:23', NULL, 1, 4763.00, 1200.00),
(5, 5, 1, 1, 'Jus Melon Segar', 'Reguler', 12000, 1, 12000, '2026-11-23 05:38:50', '2026-11-23 05:38:50', NULL, 1, 4763.00, 1200.00),
(6, 6, 1, 1, 'Jus Melon Segar', 'Reguler', 12000, 1, 12000, '2026-11-23 06:05:24', '2026-11-23 06:17:54', NULL, 1, 0.00, 0.00),
(7, 7, 1, 1, 'Jus Melon Segar', 'Reguler', 12000, 1, 12000, '2026-11-23 06:05:48', '2026-11-23 06:05:48', NULL, 1, 4763.00, 1200.00);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jurnal`
--

CREATE TABLE `jurnal` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nomor` varchar(30) NOT NULL,
  `tanggal` date NOT NULL,
  `periode` char(7) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `rujukan_tipe` varchar(30) DEFAULT NULL,
  `rujukan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `koreksi_dari_id` bigint(20) UNSIGNED DEFAULT NULL,
  `dibuat_oleh` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jurnal_baris`
--

CREATE TABLE `jurnal_baris` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `jurnal_id` bigint(20) UNSIGNED NOT NULL,
  `akun_id` bigint(20) UNSIGNED NOT NULL,
  `debit` decimal(16,2) NOT NULL DEFAULT 0.00,
  `kredit` decimal(16,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kas_transaksi`
--

CREATE TABLE `kas_transaksi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `shift_id` bigint(20) UNSIGNED NOT NULL,
  `jenis` enum('masuk','keluar') NOT NULL,
  `jumlah` bigint(20) UNSIGNED NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `pengguna_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
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
  `id` bigint(20) UNSIGNED NOT NULL,
  `pengguna_id` bigint(20) UNSIGNED DEFAULT NULL,
  `aksi` varchar(50) NOT NULL,
  `tipe_objek` varchar(50) DEFAULT NULL,
  `id_objek` bigint(20) UNSIGNED DEFAULT NULL,
  `deskripsi` varchar(255) DEFAULT NULL,
  `nilai_sebelum` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`nilai_sebelum`)),
  `nilai_sesudah` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`nilai_sesudah`)),
  `alasan` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id`, `pengguna_id`, `aksi`, `tipe_objek`, `id_objek`, `deskripsi`, `nilai_sebelum`, `nilai_sesudah`, `alasan`, `ip_address`, `created_at`) VALUES
(1, NULL, 'tes', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-11-09 03:10:52'),
(2, NULL, 'pesanan_online_baru', 'pesanan', 1, 'Pesanan online baru dibuat: OMYU-ORD-20261123-J2VB oleh angel total Rp 12.000', NULL, NULL, NULL, NULL, '2026-11-23 04:52:33'),
(3, NULL, 'konfirmasi_pembayaran_pembeli', 'pesanan', 1, 'Pembeli angel mengonfirmasi pembayaran untuk OMYU-ORD-20261123-J2VB.', NULL, NULL, NULL, NULL, '2026-11-23 04:52:54'),
(4, 2, 'batalkan_pesanan', 'pesanan', 1, 'Membatalkan / menolak pesanan OMYU-ORD-20261123-J2VB: Nominal pembayaran tidak sesuai, mohon verifikasi ulang', NULL, NULL, 'Nominal pembayaran tidak sesuai, mohon verifikasi ulang', '127.0.0.1', '2026-11-23 04:56:44'),
(5, NULL, 'pesanan_online_baru', 'pesanan', 2, 'Pesanan online baru dibuat: OMYU-ORD-20261123-HM4M oleh angel total Rp 12.000', NULL, NULL, NULL, NULL, '2026-11-23 04:58:30'),
(6, NULL, 'konfirmasi_pembayaran_pembeli', 'pesanan', 2, 'Pembeli angel mengonfirmasi pembayaran untuk OMYU-ORD-20261123-HM4M.', NULL, NULL, NULL, NULL, '2026-11-23 04:59:11'),
(7, NULL, 'pesanan_online_baru', 'pesanan', 3, 'Pesanan online baru dibuat: OMYU-ORD-20261123-JXW7 oleh abid total Rp 24.000', NULL, NULL, NULL, NULL, '2026-11-23 05:26:12'),
(8, NULL, 'pesanan_online_baru', 'pesanan', 4, 'Pesanan online baru dibuat: OMYU-ORD-20261123-FSDL oleh aa total Rp 12.000', NULL, NULL, NULL, NULL, '2026-11-23 05:33:23'),
(9, NULL, 'konfirmasi_pembayaran_pembeli', 'pesanan', 4, 'Pembeli aa mengonfirmasi pembayaran untuk OMYU-ORD-20261123-FSDL.', NULL, NULL, NULL, NULL, '2026-11-23 05:33:46'),
(10, NULL, 'pesanan_online_baru', 'pesanan', 5, 'Pesanan online baru dibuat: OMYU-ORD-20261123-N33M oleh oo total Rp 12.000', NULL, NULL, NULL, NULL, '2026-11-23 05:38:50'),
(11, NULL, 'konfirmasi_pembayaran_pembeli', 'pesanan', 5, 'Pembeli oo mengonfirmasi pembayaran untuk OMYU-ORD-20261123-N33M.', NULL, NULL, NULL, NULL, '2026-11-23 05:38:56'),
(12, NULL, 'pesanan_online_baru', 'pesanan', 6, 'Pesanan online baru dibuat: OMYU-ORD-20261123-FHOS oleh h total Rp 12.000', NULL, NULL, NULL, NULL, '2026-11-23 06:05:24'),
(13, NULL, 'konfirmasi_pembayaran_pembeli', 'pesanan', 6, 'Pembeli h mengonfirmasi pembayaran untuk OMYU-ORD-20261123-FHOS.', NULL, NULL, NULL, NULL, '2026-11-23 06:05:29'),
(14, NULL, 'pesanan_online_baru', 'pesanan', 7, 'Pesanan online baru dibuat: OMYU-ORD-20261123-TDVG oleh gim total Rp 12.000', NULL, NULL, NULL, NULL, '2026-11-23 06:05:48'),
(15, NULL, 'konfirmasi_pembayaran_pembeli', 'pesanan', 7, 'Pembeli gim mengonfirmasi pembayaran untuk OMYU-ORD-20261123-TDVG.', NULL, NULL, NULL, NULL, '2026-11-23 06:05:53'),
(16, 2, 'verifikasi_pembayaran', 'pesanan', 6, 'Kasir Budi memverifikasi pembayaran pesanan OMYU-ORD-20261123-FHOS', '{\"status_pembayaran\":\"menunggu\",\"status_pesanan\":\"menunggu_verifikasi\"}', '{\"status_pembayaran\":\"lunas\",\"status_pesanan\":\"dikonfirmasi\"}', NULL, '114.8.229.218', '2026-11-23 06:14:51'),
(17, 2, 'selesai_racik_produksi', 'pesanan', 6, 'Barista Budi menyelesaikan racikan pesanan #OMYU-ORD-20261123-FHOS (Siap Diambil)', NULL, '{\"status_pesanan\":\"siap_diambil\"}', NULL, '114.8.229.218', '2026-11-23 06:17:54');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_11_18_085815_add_barcode_to_produk_table', 1),
(5, '2026_11_18_085901_backfill_barcode_produk', 1),
(6, '2026_11_21_130000_add_foto_to_pengguna_table', 2),
(7, '2026_11_23_124048_add_device_token_to_pesanan_table', 2),
(8, '2026_11_23_124834_create_pelanggans_table', 3),
(9, '2026_11_23_124845_add_pelanggan_id_to_pesanan_table', 3),
(10, '2026_11_23_125511_add_password_to_pelanggan_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `opname`
--

CREATE TABLE `opname` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `petugas_id` bigint(20) UNSIGNED DEFAULT NULL,
  `catatan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `opname_item`
--

CREATE TABLE `opname_item` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `opname_id` bigint(20) UNSIGNED NOT NULL,
  `bahan_id` bigint(20) UNSIGNED NOT NULL,
  `stok_sistem` decimal(14,3) NOT NULL,
  `stok_fisik` decimal(14,3) NOT NULL,
  `selisih` decimal(14,3) NOT NULL,
  `nilai_selisih` decimal(16,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `telepon` varchar(20) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pemasok`
--

CREATE TABLE `pemasok` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `catatan` varchar(255) DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pesanan_id` bigint(20) UNSIGNED NOT NULL,
  `gerbang` varchar(30) NOT NULL DEFAULT 'manual',
  `referensi_gerbang` varchar(100) DEFAULT NULL,
  `metode` varchar(50) DEFAULT NULL,
  `bukti_path` varchar(255) DEFAULT NULL,
  `jumlah` bigint(20) UNSIGNED NOT NULL,
  `status` enum('menunggu','menunggu_verifikasi','lunas','ditolak','kedaluwarsa','gagal','dikembalikan') NOT NULL DEFAULT 'menunggu',
  `dibayar_pada` datetime DEFAULT NULL,
  `data_mentah` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data_mentah`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `biaya_transaksi` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `diverifikasi_oleh` bigint(20) UNSIGNED DEFAULT NULL,
  `diverifikasi_pada` datetime DEFAULT NULL,
  `catatan_verifikasi` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id`, `pesanan_id`, `gerbang`, `referensi_gerbang`, `metode`, `bukti_path`, `jumlah`, `status`, `dibayar_pada`, `data_mentah`, `created_at`, `updated_at`, `deleted_at`, `biaya_transaksi`, `diverifikasi_oleh`, `diverifikasi_pada`, `catatan_verifikasi`) VALUES
(1, 1, 'manual', NULL, 'qris', NULL, 12000, 'menunggu_verifikasi', NULL, NULL, '2026-11-23 04:52:33', '2026-11-23 04:52:54', NULL, 0, NULL, NULL, NULL),
(2, 2, 'manual', NULL, 'qris', NULL, 12000, 'menunggu_verifikasi', NULL, NULL, '2026-11-23 04:58:30', '2026-11-23 04:59:11', NULL, 0, NULL, NULL, NULL),
(3, 3, 'manual', NULL, 'qris', NULL, 24000, 'menunggu', NULL, NULL, '2026-11-23 05:26:12', '2026-11-23 05:26:12', NULL, 0, NULL, NULL, NULL),
(4, 4, 'manual', NULL, 'transfer', 'bukti_bayar/Hm5zomY3DSUSgCczaP7gesjg1iuCSelBLC6HxyHA.png', 12000, 'menunggu_verifikasi', NULL, NULL, '2026-11-23 05:33:23', '2026-11-23 05:33:46', NULL, 0, NULL, NULL, NULL),
(5, 5, 'manual', NULL, 'qris', 'bukti_bayar/1WMvOHo30lMOlqIZYMij5HFhCal8jiqQKwkyndl9.png', 12000, 'menunggu_verifikasi', NULL, NULL, '2026-11-23 05:38:50', '2026-11-23 05:38:56', NULL, 0, NULL, NULL, NULL),
(6, 6, 'manual', NULL, 'qris', 'bukti_bayar/CKTsuPR72dv5FvhtXUVdQXbx4e7KcZEC5cJw9BAf.png', 12000, 'lunas', '2026-11-23 13:14:51', NULL, '2026-11-23 06:05:24', '2026-11-23 06:14:51', NULL, 0, 2, '2026-11-23 13:14:51', 'Diverifikasi oleh kasir'),
(7, 7, 'manual', NULL, 'transfer', 'bukti_bayar/2SXq2wTQpvPvOntfzzbDPDcZYEVpq4lp5RFGowzt.png', 12000, 'menunggu_verifikasi', NULL, NULL, '2026-11-23 06:05:48', '2026-11-23 06:05:53', NULL, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pembelian_bahan`
--

CREATE TABLE `pembelian_bahan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tanggal_beli` date NOT NULL,
  `bahan_id` bigint(20) UNSIGNED NOT NULL,
  `pemasok_id` bigint(20) UNSIGNED DEFAULT NULL,
  `jumlah_beli` decimal(14,3) NOT NULL,
  `satuan_beli` varchar(20) NOT NULL,
  `total_harga` bigint(20) UNSIGNED NOT NULL,
  `rendemen_dipakai` decimal(5,2) NOT NULL DEFAULT 100.00,
  `jumlah_bersih` decimal(14,3) NOT NULL,
  `biaya_per_satuan` decimal(14,4) NOT NULL,
  `cara_bayar` enum('tunai','bank','utang') NOT NULL DEFAULT 'tunai',
  `kedaluwarsa_pada` date DEFAULT NULL,
  `dicatat_oleh` bigint(20) UNSIGNED DEFAULT NULL,
  `catatan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan`
--

CREATE TABLE `pengaturan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kunci` varchar(60) NOT NULL,
  `nilai` text DEFAULT NULL,
  `keterangan` varchar(150) DEFAULT NULL,
  `diubah_oleh` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pengaturan`
--

INSERT INTO `pengaturan` (`id`, `kunci`, `nilai`, `keterangan`, `diubah_oleh`, `created_at`, `updated_at`) VALUES
(1, 'nama_usaha', 'OMYU', 'Nama usaha', NULL, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(2, 'jam_buka', NULL, 'Jam buka (HH:MM)', NULL, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(3, 'jam_tutup', NULL, 'Jam tutup (HH:MM)', NULL, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(4, 'persiapan_menit', NULL, 'Waktu persiapan minimum sebelum jam ambil', NULL, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(5, 'durasi_rentang_menit', NULL, 'Lebar satu rentang jam pengambilan', NULL, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(6, 'kapasitas_cup_per_rentang', NULL, 'Kapasitas pesanan (cup) per rentang jam', NULL, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(7, 'batas_bayar_menit', NULL, 'Batas waktu pembayaran pesanan online', NULL, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(8, 'ambang_stok_cup', NULL, 'Peringatan stok menipis (dalam cup)', NULL, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(9, 'tarif_biaya_qris_persen', NULL, 'Tarif biaya transaksi QRIS/dompet digital (%)', NULL, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(10, 'pakai_kode_unik', '0', '1 = tambah kode unik 3 digit pada nominal', NULL, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(11, 'qris_gambar', NULL, 'Path gambar QRIS yang diunggah owner', NULL, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(12, 'bank_nama', NULL, 'Nama bank untuk transfer', NULL, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(13, 'bank_rekening', NULL, 'Nomor rekening', NULL, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(14, 'bank_atas_nama', NULL, 'Nama pemilik rekening', NULL, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(15, 'mekanisme_persetujuan', 'langsung', 'langsung | persetujuan_owner (perubahan harga/resep)', NULL, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(16, 'pajak_daerah_aktif', '0', '1 = pajak daerah berlaku', NULL, '2026-11-10 01:51:33', '2026-11-10 01:51:33'),
(17, 'harga_termasuk_pajak', '1', '1 = harga tampil sudah termasuk pajak', NULL, '2026-11-10 01:51:33', '2026-11-10 01:51:33');

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `peran` enum('admin','karyawan') NOT NULL DEFAULT 'karyawan',
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `bisa_lihat_hpp` tinyint(1) NOT NULL DEFAULT 0,
  `telepon` varchar(20) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id`, `nama`, `email`, `password`, `peran`, `aktif`, `bisa_lihat_hpp`, `telepon`, `foto`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Admin OMYU', 'admin@omyu.test', '$2y$12$4z5vlWXSeBm9gDIqsL71N.zCH9rXsAG0zHj0Mne/vPXL8ZBuSAb9e', 'admin', 1, 0, '085807106570', NULL, 'vL1K65lFOIrgUviNsvbEbnpyg7Atqg7eOENCzUvJDcvGnwYxasBki1qgRbye', '2026-11-01 07:54:03', '2026-11-22 02:06:00', NULL),
(2, 'Budi', 'budi@omyu.test', '$2y$12$YM87AsU/W4VyrM/ljLEgxeaxu62Q8oWs7bwTvt3tUaSp8LBsvZ9XS', 'karyawan', 1, 0, '081234567890', NULL, 'SIxFO1BcLzXcRs7PyYFDaeyCXekBDqochlPhYjwFTNxYbJoCBD5CR3lIc0yL', '2026-11-09 03:23:35', '2026-11-09 03:23:35', NULL),
(3, 'Siti', 'siti@omyu.test', '$2y$12$Eg63F494Cnwtc/q1FnDqCOiKeSIA/cEyG2MydxiFjd20KFA4JMyK6', 'karyawan', 1, 0, NULL, NULL, NULL, '2026-11-09 03:23:35', '2026-11-09 03:23:35', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pergerakan_stok`
--

CREATE TABLE `pergerakan_stok` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bahan_id` bigint(20) UNSIGNED NOT NULL,
  `jenis` enum('pembelian','pemakaian_resep','pemakaian_lain','rusak','opname','penyesuaian') NOT NULL,
  `jumlah` decimal(14,3) NOT NULL,
  `nilai` decimal(16,2) NOT NULL DEFAULT 0.00,
  `stok_setelah` decimal(14,3) NOT NULL,
  `biaya_rata_rata_setelah` decimal(14,4) NOT NULL,
  `rujukan_tipe` varchar(30) DEFAULT NULL,
  `rujukan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pengguna_id` bigint(20) UNSIGNED DEFAULT NULL,
  `alasan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `periode_akuntansi`
--

CREATE TABLE `periode_akuntansi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `periode` char(7) NOT NULL,
  `status` enum('buka','kunci') NOT NULL DEFAULT 'buka',
  `dikunci_oleh` bigint(20) UNSIGNED DEFAULT NULL,
  `dikunci_pada` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_pesanan` varchar(30) NOT NULL,
  `token_akses` char(64) NOT NULL,
  `device_token` varchar(80) DEFAULT NULL,
  `pelanggan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `jenis` enum('online','langsung') NOT NULL DEFAULT 'online',
  `nama_pembeli` varchar(100) DEFAULT NULL,
  `telepon_pembeli` varchar(20) DEFAULT NULL,
  `email_pembeli` varchar(150) DEFAULT NULL,
  `alamat_pengiriman` text DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `subtotal` bigint(20) UNSIGNED NOT NULL,
  `ongkir` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `total` bigint(20) UNSIGNED NOT NULL,
  `metode_bayar` enum('qris','transfer','tunai','bayar_ditempat') DEFAULT NULL,
  `kode_unik` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `status_pembayaran` enum('menunggu','lunas','kedaluwarsa','gagal','dikembalikan') NOT NULL DEFAULT 'menunggu',
  `status_pesanan` enum('menunggu_pembayaran','menunggu_verifikasi','dikonfirmasi','diproses','siap_diambil','selesai','dibatalkan','kedaluwarsa') NOT NULL DEFAULT 'menunggu_pembayaran',
  `kedaluwarsa_pada` datetime DEFAULT NULL,
  `dibayar_pada` datetime DEFAULT NULL,
  `ditangani_oleh` bigint(20) UNSIGNED DEFAULT NULL,
  `nomor_resi` varchar(60) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `jadwal_ambil_mulai` datetime DEFAULT NULL,
  `jadwal_ambil_selesai` datetime DEFAULT NULL,
  `uang_diterima` bigint(20) UNSIGNED DEFAULT NULL,
  `kembalian` bigint(20) UNSIGNED DEFAULT NULL,
  `shift_id` bigint(20) UNSIGNED DEFAULT NULL,
  `alasan_batal` varchar(255) DEFAULT NULL,
  `diselesaikan_pada` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id`, `kode_pesanan`, `token_akses`, `device_token`, `pelanggan_id`, `jenis`, `nama_pembeli`, `telepon_pembeli`, `email_pembeli`, `alamat_pengiriman`, `catatan`, `subtotal`, `ongkir`, `total`, `metode_bayar`, `kode_unik`, `status_pembayaran`, `status_pesanan`, `kedaluwarsa_pada`, `dibayar_pada`, `ditangani_oleh`, `nomor_resi`, `created_at`, `updated_at`, `deleted_at`, `jadwal_ambil_mulai`, `jadwal_ambil_selesai`, `uang_diterima`, `kembalian`, `shift_id`, `alasan_batal`, `diselesaikan_pada`) VALUES
(1, 'OMYU-ORD-20261123-J2VB', 'aa2079dab22749f8c9eaf56905b3f636095679850d29df771332101ef2a35acf', NULL, NULL, 'online', 'angel', '089524121369', NULL, 'Ambil Sendiri di Outlet (Self Pickup)', 'Jadwal Ambil: 2026-11-23 pukul 15.30-16.00 WIB | Catatan: banyakin gulanya | [Ditolak: Nominal pembayaran tidak sesuai, mohon verifikasi ulang]', 12000, 0, 12000, 'qris', 0, 'menunggu', 'dibatalkan', '2026-11-23 13:52:33', NULL, 2, NULL, '2026-11-23 04:52:33', '2026-11-23 04:56:44', NULL, NULL, NULL, NULL, NULL, NULL, 'Nominal pembayaran tidak sesuai, mohon verifikasi ulang', NULL),
(2, 'OMYU-ORD-20261123-HM4M', 'a3e197de5d2d11cb890a5a7b4f90608c6e4fe698819507ac79f89f971f236b13', NULL, NULL, 'online', 'angel', '089524121369', NULL, 'Ambil Sendiri di Outlet (Self Pickup)', 'Jadwal Ambil: 2026-11-23 pukul 15.30-16.00 WIB | Catatan: banyakin esnya', 12000, 0, 12000, 'qris', 0, 'menunggu', 'menunggu_verifikasi', '2026-11-23 13:58:30', NULL, NULL, NULL, '2026-11-23 04:58:30', '2026-11-23 04:59:11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'OMYU-ORD-20261123-JXW7', '5c013e6c1e2bd0c66c35412b5ebf9c7eb32b780264ab79b806d7b80ceb33d473', NULL, NULL, 'online', 'abid', '098', NULL, 'Ambil Sendiri di Outlet (Self Pickup)', 'Jadwal Ambil: 2026-11-23 pukul 15.30-16.00 WIB', 24000, 0, 24000, 'qris', 0, 'menunggu', 'menunggu_pembayaran', '2026-11-23 14:26:12', NULL, NULL, NULL, '2026-11-23 05:26:12', '2026-11-23 05:26:12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'OMYU-ORD-20261123-FSDL', 'eecd0496ac30efe99cd725a56797de354a4e59a3243511e4384a11476a116ada', NULL, NULL, 'online', 'aa', '890', NULL, 'Ambil Sendiri di Outlet (Self Pickup)', 'Jadwal Ambil: 2026-11-23 pukul 15.30-16.00 WIB', 12000, 0, 12000, 'transfer', 0, 'menunggu', 'menunggu_verifikasi', '2026-11-23 14:33:23', NULL, NULL, NULL, '2026-11-23 05:33:23', '2026-11-23 05:33:46', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'OMYU-ORD-20261123-N33M', '090d84ac070858cddb48c8293f6d940a098ab74debb6cfec9695a674de42d792', NULL, NULL, 'online', 'oo', '09', NULL, 'Ambil Sendiri di Outlet (Self Pickup)', 'Jadwal Ambil: 2026-11-23 pukul 15.30-16.00 WIB', 12000, 0, 12000, 'qris', 0, 'menunggu', 'menunggu_verifikasi', '2026-11-23 14:38:50', NULL, NULL, NULL, '2026-11-23 05:38:50', '2026-11-23 05:38:56', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'OMYU-ORD-20261123-FHOS', 'e3ec1071eda543158088982b76f4715ba32ee9e5d549fe545a0b10fcb2c27b05', '91af36f991835c5b3bf6941156ce77aba7ec00af72af5eba6f119c1e2c41a788', NULL, 'online', 'h', '098', NULL, 'Ambil Sendiri di Outlet (Self Pickup)', 'Jadwal Ambil: 2026-11-23 pukul 15.30-16.00 WIB', 12000, 0, 12000, 'qris', 0, 'lunas', 'siap_diambil', '2026-11-23 15:05:24', '2026-11-23 13:14:51', 2, NULL, '2026-11-23 06:05:24', '2026-11-23 06:17:54', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 'OMYU-ORD-20261123-TDVG', '72f6c7a7008f2bca04d20663a7b1631f7c5c2a49fb32364342712b41363c90c8', '91af36f991835c5b3bf6941156ce77aba7ec00af72af5eba6f119c1e2c41a788', NULL, 'online', 'gim', '908', NULL, 'Ambil Sendiri di Outlet (Self Pickup)', 'Jadwal Ambil: 2026-11-23 pukul 15.30-16.00 WIB', 12000, 0, 12000, 'transfer', 0, 'menunggu', 'menunggu_verifikasi', '2026-11-23 15:05:48', NULL, NULL, NULL, '2026-11-23 06:05:48', '2026-11-23 06:05:53', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kategori_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nama` varchar(150) NOT NULL,
  `slug` varchar(170) NOT NULL,
  `barcode` varchar(64) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` bigint(20) UNSIGNED NOT NULL,
  `stok` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `berat_gram` int(10) UNSIGNED DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `dibuat_oleh` bigint(20) UNSIGNED DEFAULT NULL,
  `diubah_oleh` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `kategori_id`, `nama`, `slug`, `barcode`, `deskripsi`, `harga`, `stok`, `berat_gram`, `gambar`, `aktif`, `dibuat_oleh`, `diubah_oleh`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Jus Melon Segar', 'jus-melon-segar', '2000000000001', 'Jus melon segar tanpa pengawet', 12000, 15, NULL, NULL, 1, NULL, NULL, '2026-11-10 02:51:34', '2026-11-23 06:17:54', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `resep`
--

CREATE TABLE `resep` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `varian_id` bigint(20) UNSIGNED NOT NULL,
  `versi` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `berlaku_mulai` datetime NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `alasan_perubahan` varchar(255) DEFAULT NULL,
  `dibuat_oleh` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `resep`
--

INSERT INTO `resep` (`id`, `varian_id`, `versi`, `berlaku_mulai`, `aktif`, `alasan_perubahan`, `dibuat_oleh`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2026-11-10 09:51:34', 1, 'Resep standar awal', NULL, '2026-11-10 02:51:34', '2026-11-10 02:51:34');

-- --------------------------------------------------------

--
-- Table structure for table `resep_bahan`
--

CREATE TABLE `resep_bahan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `resep_id` bigint(20) UNSIGNED NOT NULL,
  `bahan_id` bigint(20) UNSIGNED NOT NULL,
  `takaran` decimal(14,3) NOT NULL,
  `keterangan` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `resep_bahan`
--

INSERT INTO `resep_bahan` (`id`, `resep_id`, `bahan_id`, `takaran`, `keterangan`) VALUES
(1, 1, 1, 200.000, NULL),
(2, 1, 2, 15.000, NULL),
(3, 1, 3, 100.000, NULL),
(4, 1, 4, 1.000, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_stok`
--

CREATE TABLE `riwayat_stok` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `produk_id` bigint(20) UNSIGNED NOT NULL,
  `pengguna_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pesanan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `jenis` enum('masuk','keluar_pesanan','kembali_kedaluwarsa','penyesuaian') NOT NULL,
  `jumlah` int(11) NOT NULL,
  `stok_setelah` int(10) UNSIGNED NOT NULL,
  `catatan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `riwayat_stok`
--

INSERT INTO `riwayat_stok` (`id`, `produk_id`, `pengguna_id`, `pesanan_id`, `jenis`, `jumlah`, `stok_setelah`, `catatan`, `created_at`, `deleted_at`) VALUES
(1, 1, 2, 6, 'keluar_pesanan', -1, 15, 'Produksi pesanan OMYU-ORD-20261123-FHOS', '2026-11-23 06:17:54', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('3yNjOCrFwuCIlY1IdVenKYh8Kr7OwPvfXuWB7SOh', 2, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:160.0) Gecko/20100101 Firefox/160.0', 'eyJfdG9rZW4iOiJLNGdzS2VwY25ReTdybk9VUGFvM0k5VGtWM2VxV0ZMS2pJcDVKSHlaIiwidXJsIjpbXSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9wYW5lbFwvcGVzYW5hbj90YWI9bWVudW5nZ3VfY2VrIiwicm91dGUiOiJwYW5lbC5zdGFmLnBlc2FuYW4uaW5kZXgifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MiwicGFzc3dvcmRfaGFzaF93ZWIiOiJhNzMwZmUxMTI2ZTJhYzNkYzIwNWQ2NWM3NWI1ZGYyMWVkNDM3YWNiMjdmZTg1MzIzMzA5ZGMyZjYxY2U4MWNjIn0=', 1795411607),
('4PmjgWlboVxTbxL1j0sEGKXPzBZhqDMY2itMq43L', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:160.0) Gecko/20100101 Firefox/160.0', 'eyJfdG9rZW4iOiJXb21KRVhackQxS0V3ZDNTajFJaEdJWDRtTmJ5ODUxRUhmMzUyNldPIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL29yZGVyXC9tZW51Iiwicm91dGUiOiJwZWxhbmdnYW4ua2F0YWxvZyJ9LCJrb2RlX3Blc2FuYW5fdGVyYWtoaXIiOiJPTVlVLU9SRC0yMDI2MTEyMy1URFZHIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL3BhbmVsXC9hZG1pbiJ9fQ==', 1795414760),
('8EViSNeJ0NIbchtPxoWfpB7fF2BoP9ZYwp3ZtYcz', NULL, '103.31.118.11', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36', 'eyJfdG9rZW4iOiJobWw0aDhpMHZ3RVI5RFNtQlB2Mkt6c1hYTVhQT1czMDdTRXFsdW5zIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9iaXpyYXRlLXBhbGVzdGluaWFuLWNpdGUtcHJvZmVzc2lvbmFscy50cnljbG91ZGZsYXJlLmNvbSIsInJvdXRlIjpudWxsfSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1795409516),
('aCdcjR256EpSnWbyPHk7RpFgKFtq1cOTYOyyP5jv', NULL, '103.31.118.11', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36', 'eyJfdG9rZW4iOiJ2VThManZ2TFdyaDZza1k3MkNSeWI0bTlYSTIwYUh4Q2RqSXNRdE9NIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9iaXpyYXRlLXBhbGVzdGluaWFuLWNpdGUtcHJvZmVzc2lvbmFscy50cnljbG91ZGZsYXJlLmNvbSIsInJvdXRlIjpudWxsfSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1795409488),
('AMG6Ttl65thWXZaz0tDCI1niLZ2MI7ZmqHRwYFEs', NULL, '114.8.229.218', 'WhatsApp/2.23.20.0', 'eyJfdG9rZW4iOiJwWFFSRGtVR1BuOFpON2dIU0VUV3J5cFJRMFJnS3RIbkcycnhYNWEwIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL2JpenJhdGUtcGFsZXN0aW5pYW4tY2l0ZS1wcm9mZXNzaW9uYWxzLnRyeWNsb3VkZmxhcmUuY29tXC9wYW5lbCJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cHM6XC9cL2JpenJhdGUtcGFsZXN0aW5pYW4tY2l0ZS1wcm9mZXNzaW9uYWxzLnRyeWNsb3VkZmxhcmUuY29tXC9wYW5lbCIsInJvdXRlIjoicGFuZWwuYmVyYW5kYSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1795414294),
('cgQ4sHTAVy87y3s6STO9kL0XQ7E0x8PZTwyHs0SC', NULL, '103.31.118.11', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJ1U0dVcHBTNzZ6dHRWSTFSOUwxcmlpQmUxVTBaWW1hdnBjU0FtN3NpIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL2JpenJhdGUtcGFsZXN0aW5pYW4tY2l0ZS1wcm9mZXNzaW9uYWxzLnRyeWNsb3VkZmxhcmUuY29tXC9wYW5lbCJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cHM6XC9cL2JpenJhdGUtcGFsZXN0aW5pYW4tY2l0ZS1wcm9mZXNzaW9uYWxzLnRyeWNsb3VkZmxhcmUuY29tXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1795414370),
('FeVjgS9SCP7GnYCuEBstOIfZa7uaFp7ImSkPys4l', NULL, '114.10.155.248', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36', 'eyJfdG9rZW4iOiJkOGVwQnkySXliWDZjVEZZRE94RW1ZN3UzV0twVWRBa3J5UzFraUtBIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9iaXpyYXRlLXBhbGVzdGluaWFuLWNpdGUtcHJvZmVzc2lvbmFscy50cnljbG91ZGZsYXJlLmNvbSIsInJvdXRlIjpudWxsfSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1795409998),
('Fu1RtbNDntuzYnyMt1UJWx95ZAHKIgij8CiltwEy', NULL, '103.31.118.11', 'WhatsApp/2.23.20.0', 'eyJfdG9rZW4iOiJuNmx1Ykt6eG1CMk5Lb1lTN25DbDZiRzNxNHIwU1RRN2ZqSFhVVkFZIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9iaXpyYXRlLXBhbGVzdGluaWFuLWNpdGUtcHJvZmVzc2lvbmFscy50cnljbG91ZGZsYXJlLmNvbSIsInJvdXRlIjpudWxsfSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1795408629),
('I5xk1WPiL8uvLcLEQvkZwyCcXGCgWR8apZtuftIK', 2, '114.8.229.218', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Mobile Safari/537.36', 'eyJfdG9rZW4iOiJMUTNTa3V5ZWZic2pqYTVOMzR2S21Zbktyb0RpaEF6Mm1XMlhQc3hFIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9iaXpyYXRlLXBhbGVzdGluaWFuLWNpdGUtcHJvZmVzc2lvbmFscy50cnljbG91ZGZsYXJlLmNvbVwvcGFuZWxcL3Blc2FuYW5cL3NjYW4/dG9rZW49T01ZVS1PUkQtMjAyNjExMjMtRkhPUyIsInJvdXRlIjoicGFuZWwuc3RhZi5wZXNhbmFuLnNjYW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJrZXJhbmphbmciOnsiMV8xIjp7InByb2R1a19pZCI6MSwidmFyaWFuX2lkIjoxLCJuYW1hX3Byb2R1ayI6Ikp1cyBNZWxvbiBTZWdhciIsIm5hbWFfdmFyaWFuIjoiUmVndWxlciIsImhhcmdhIjoxMjAwMCwicXR5IjoyLCJjYXRhdGFuIjoiLSJ9fSwidXJsIjpbXSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjIsInBhc3N3b3JkX2hhc2hfd2ViIjoiYTczMGZlMTEyNmUyYWMzZGMyMDVkNjVjNzViNWRmMjFlZDQzN2FjYjI3ZmU4NTMyMzMwOWRjMmY2MWNlODFjYyJ9', 1795414705),
('iD8kWWeVJrBKER2qTUxg1CwzjO1JvwQENtxUmOpx', NULL, '114.8.229.218', 'WhatsApp/2.23.20.0', 'eyJfdG9rZW4iOiJrZzRSNVY4R2tVZjFBMEw3eGJMdjZ5QkFRTHZRV0YxWTRtbkRKN2xoIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9iaXpyYXRlLXBhbGVzdGluaWFuLWNpdGUtcHJvZmVzc2lvbmFscy50cnljbG91ZGZsYXJlLmNvbSIsInJvdXRlIjpudWxsfSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1795414291),
('qo7GyoSCDIL7WiJO44gdg3PYWgYuf4CKhoByNQXy', NULL, '182.6.84.241', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'eyJfdG9rZW4iOiJMUUtlTThQa2RZZkFuUG51V2ZDVExWdk5xbmF5dUZoOTlMaWpRN2QwIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9iaXpyYXRlLXBhbGVzdGluaWFuLWNpdGUtcHJvZmVzc2lvbmFscy50cnljbG91ZGZsYXJlLmNvbSIsInJvdXRlIjpudWxsfSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1795408941),
('Wc5qKX7YsWKZWt1FKVNKGxMYd0iI655LR05L1aHh', NULL, '103.31.118.11', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Mobile Safari/537.36', 'eyJfdG9rZW4iOiJmTmNSSXdmdm9Lb3Zhcm5HbHN6SFVDTVAxSWtkbXVzdWJ2ejFJYVREIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9iaXpyYXRlLXBhbGVzdGluaWFuLWNpdGUtcHJvZmVzc2lvbmFscy50cnljbG91ZGZsYXJlLmNvbVwvb3JkZXJcL2xhY2FrP2tvZGU9T01ZVS1PUkQtMjAyNjExMjMtSE00TSIsInJvdXRlIjoicGVsYW5nZ2FuLmxhY2FrIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1795410019),
('zhOFp8jKp7gqWNjp3o7zMInnk3w2kqeekdt2OZWc', NULL, '114.8.229.218', 'WhatsApp/2.23.20.0', 'eyJfdG9rZW4iOiIyOGlva2RUeGo4T0JTMjExR2RpOGpLUFJGZGtaczA0eFJnUFc1aGpYIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9iaXpyYXRlLXBhbGVzdGluaWFuLWNpdGUtcHJvZmVzc2lvbmFscy50cnljbG91ZGZsYXJlLmNvbVwvbG9naW4iLCJyb3V0ZSI6ImxvZ2luIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1795414295),
('zYmMn95nPOzYCHhHxjdhHgfXxMYjSGXg385HKEhv', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:160.0) Gecko/20100101 Firefox/160.0', 'eyJfdG9rZW4iOiJqQndvN0VXSjFnWG1xZEJDSDJhUmJkcTc0ZHVNNTdBVFNRTzdsY1VuIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9fQ==', 1795414201);

-- --------------------------------------------------------

--
-- Table structure for table `shift_kas`
--

CREATE TABLE `shift_kas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pengguna_id` bigint(20) UNSIGNED NOT NULL,
  `dibuka_pada` datetime NOT NULL,
  `ditutup_pada` datetime DEFAULT NULL,
  `modal_awal` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `penerimaan_tunai` bigint(20) UNSIGNED DEFAULT NULL,
  `pengeluaran_tunai` bigint(20) UNSIGNED DEFAULT NULL,
  `kas_seharusnya` bigint(20) DEFAULT NULL,
  `kas_fisik` bigint(20) UNSIGNED DEFAULT NULL,
  `selisih` bigint(20) DEFAULT NULL,
  `status` enum('buka','tutup') NOT NULL DEFAULT 'buka',
  `catatan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `varian_produk`
--

CREATE TABLE `varian_produk` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `produk_id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(50) NOT NULL,
  `harga` bigint(20) UNSIGNED NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `varian_produk`
--

INSERT INTO `varian_produk` (`id`, `produk_id`, `nama`, `harga`, `aktif`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Reguler', 12000, 1, '2026-11-10 02:51:34', '2026-11-10 02:51:34', NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_jurnal_tidak_seimbang`
-- (See below for the actual view)
--
CREATE TABLE `v_jurnal_tidak_seimbang` (
`jurnal_id` bigint(20) unsigned
,`total_debit` decimal(38,2)
,`total_kredit` decimal(38,2)
);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `akun`
--
ALTER TABLE `akun`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indexes for table `bahan`
--
ALTER TABLE `bahan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `cadangan_bahan`
--
ALTER TABLE `cadangan_bahan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cadangan_bahan` (`bahan_id`,`status`),
  ADD KEY `fk_cadangan_pesanan` (`pesanan_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

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
  ADD KEY `fk_item_produk` (`produk_id`),
  ADD KEY `fk_item_varian` (`varian_id`),
  ADD KEY `fk_item_resep` (`resep_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jurnal`
--
ALTER TABLE `jurnal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor` (`nomor`),
  ADD KEY `idx_jurnal_periode` (`periode`),
  ADD KEY `idx_jurnal_rujukan` (`rujukan_tipe`,`rujukan_id`),
  ADD KEY `fk_jurnal_koreksi` (`koreksi_dari_id`),
  ADD KEY `fk_jurnal_pembuat` (`dibuat_oleh`);

--
-- Indexes for table `jurnal_baris`
--
ALTER TABLE `jurnal_baris`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_barisjurnal_akun` (`akun_id`),
  ADD KEY `fk_barisjurnal_jurnal` (`jurnal_id`);

--
-- Indexes for table `kas_transaksi`
--
ALTER TABLE `kas_transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_kastrx_shift` (`shift_id`),
  ADD KEY `fk_kastrx_pengguna` (`pengguna_id`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `opname`
--
ALTER TABLE `opname`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_opname_petugas` (`petugas_id`);

--
-- Indexes for table `opname_item`
--
ALTER TABLE `opname_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_opnameitem_opname` (`opname_id`),
  ADD KEY `fk_opnameitem_bahan` (`bahan_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pelanggan_telepon_unique` (`telepon`);

--
-- Indexes for table `pemasok`
--
ALTER TABLE `pemasok`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `referensi_gerbang` (`referensi_gerbang`),
  ADD KEY `fk_pembayaran_pesanan` (`pesanan_id`),
  ADD KEY `fk_pembayaran_verifikator` (`diverifikasi_oleh`);

--
-- Indexes for table `pembelian_bahan`
--
ALTER TABLE `pembelian_bahan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pembelian_tanggal` (`tanggal_beli`),
  ADD KEY `fk_pembelian_bahan` (`bahan_id`),
  ADD KEY `fk_pembelian_pemasok` (`pemasok_id`),
  ADD KEY `fk_pembelian_pencatat` (`dicatat_oleh`);

--
-- Indexes for table `pengaturan`
--
ALTER TABLE `pengaturan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kunci` (`kunci`),
  ADD KEY `fk_pengaturan_pengubah` (`diubah_oleh`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_pengguna_peran` (`peran`);

--
-- Indexes for table `pergerakan_stok`
--
ALTER TABLE `pergerakan_stok`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pergerakan_bahan` (`bahan_id`,`created_at`),
  ADD KEY `idx_pergerakan_rujukan` (`rujukan_tipe`,`rujukan_id`),
  ADD KEY `fk_pergerakan_pengguna` (`pengguna_id`);

--
-- Indexes for table `periode_akuntansi`
--
ALTER TABLE `periode_akuntansi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `periode` (`periode`),
  ADD KEY `fk_periode_pengunci` (`dikunci_oleh`);

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
  ADD KEY `fk_pesanan_penangan` (`ditangani_oleh`),
  ADD KEY `idx_pesanan_jadwal` (`jadwal_ambil_mulai`),
  ADD KEY `fk_pesanan_shift` (`shift_id`),
  ADD KEY `pesanan_device_token_index` (`device_token`),
  ADD KEY `pesanan_pelanggan_id_foreign` (`pelanggan_id`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD UNIQUE KEY `produk_barcode_unique` (`barcode`),
  ADD KEY `idx_produk_aktif` (`aktif`),
  ADD KEY `fk_produk_kategori` (`kategori_id`),
  ADD KEY `fk_produk_pembuat` (`dibuat_oleh`),
  ADD KEY `fk_produk_pengubah` (`diubah_oleh`);

--
-- Indexes for table `resep`
--
ALTER TABLE `resep`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_resep_versi` (`varian_id`,`versi`),
  ADD KEY `fk_resep_pembuat` (`dibuat_oleh`);

--
-- Indexes for table `resep_bahan`
--
ALTER TABLE `resep_bahan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_resep_bahan` (`resep_id`,`bahan_id`),
  ADD KEY `fk_resepbahan_bahan` (`bahan_id`);

--
-- Indexes for table `riwayat_stok`
--
ALTER TABLE `riwayat_stok`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_riwayat_produk` (`produk_id`),
  ADD KEY `fk_riwayat_pengguna` (`pengguna_id`),
  ADD KEY `fk_riwayat_pesanan` (`pesanan_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `shift_kas`
--
ALTER TABLE `shift_kas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_shift_status` (`status`),
  ADD KEY `fk_shift_pengguna` (`pengguna_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `varian_produk`
--
ALTER TABLE `varian_produk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_varian_produk` (`produk_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `akun`
--
ALTER TABLE `akun`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `bahan`
--
ALTER TABLE `bahan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cadangan_bahan`
--
ALTER TABLE `cadangan_bahan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gambar_produk`
--
ALTER TABLE `gambar_produk`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_pesanan`
--
ALTER TABLE `item_pesanan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jurnal`
--
ALTER TABLE `jurnal`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jurnal_baris`
--
ALTER TABLE `jurnal_baris`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kas_transaksi`
--
ALTER TABLE `kas_transaksi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `opname`
--
ALTER TABLE `opname`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `opname_item`
--
ALTER TABLE `opname_item`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pemasok`
--
ALTER TABLE `pemasok`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pembelian_bahan`
--
ALTER TABLE `pembelian_bahan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengaturan`
--
ALTER TABLE `pengaturan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pergerakan_stok`
--
ALTER TABLE `pergerakan_stok`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `periode_akuntansi`
--
ALTER TABLE `periode_akuntansi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `resep`
--
ALTER TABLE `resep`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `resep_bahan`
--
ALTER TABLE `resep_bahan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `riwayat_stok`
--
ALTER TABLE `riwayat_stok`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shift_kas`
--
ALTER TABLE `shift_kas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `varian_produk`
--
ALTER TABLE `varian_produk`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

-- --------------------------------------------------------

--
-- Structure for view `v_jurnal_tidak_seimbang`
--
DROP TABLE IF EXISTS `v_jurnal_tidak_seimbang`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_jurnal_tidak_seimbang`  AS SELECT `jurnal_baris`.`jurnal_id` AS `jurnal_id`, sum(`jurnal_baris`.`debit`) AS `total_debit`, sum(`jurnal_baris`.`kredit`) AS `total_kredit` FROM `jurnal_baris` GROUP BY `jurnal_baris`.`jurnal_id` HAVING sum(`jurnal_baris`.`debit`) <> sum(`jurnal_baris`.`kredit`) ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cadangan_bahan`
--
ALTER TABLE `cadangan_bahan`
  ADD CONSTRAINT `fk_cadangan_bahan` FOREIGN KEY (`bahan_id`) REFERENCES `bahan` (`id`),
  ADD CONSTRAINT `fk_cadangan_pesanan` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `fk_item_produk` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_item_resep` FOREIGN KEY (`resep_id`) REFERENCES `resep` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_item_varian` FOREIGN KEY (`varian_id`) REFERENCES `varian_produk` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `jurnal`
--
ALTER TABLE `jurnal`
  ADD CONSTRAINT `fk_jurnal_koreksi` FOREIGN KEY (`koreksi_dari_id`) REFERENCES `jurnal` (`id`),
  ADD CONSTRAINT `fk_jurnal_pembuat` FOREIGN KEY (`dibuat_oleh`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `jurnal_baris`
--
ALTER TABLE `jurnal_baris`
  ADD CONSTRAINT `fk_barisjurnal_akun` FOREIGN KEY (`akun_id`) REFERENCES `akun` (`id`),
  ADD CONSTRAINT `fk_barisjurnal_jurnal` FOREIGN KEY (`jurnal_id`) REFERENCES `jurnal` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kas_transaksi`
--
ALTER TABLE `kas_transaksi`
  ADD CONSTRAINT `fk_kastrx_pengguna` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_kastrx_shift` FOREIGN KEY (`shift_id`) REFERENCES `shift_kas` (`id`);

--
-- Constraints for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `fk_log_pengguna` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `opname`
--
ALTER TABLE `opname`
  ADD CONSTRAINT `fk_opname_petugas` FOREIGN KEY (`petugas_id`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `opname_item`
--
ALTER TABLE `opname_item`
  ADD CONSTRAINT `fk_opnameitem_bahan` FOREIGN KEY (`bahan_id`) REFERENCES `bahan` (`id`),
  ADD CONSTRAINT `fk_opnameitem_opname` FOREIGN KEY (`opname_id`) REFERENCES `opname` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `fk_pembayaran_pesanan` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pembayaran_verifikator` FOREIGN KEY (`diverifikasi_oleh`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `pembelian_bahan`
--
ALTER TABLE `pembelian_bahan`
  ADD CONSTRAINT `fk_pembelian_bahan` FOREIGN KEY (`bahan_id`) REFERENCES `bahan` (`id`),
  ADD CONSTRAINT `fk_pembelian_pemasok` FOREIGN KEY (`pemasok_id`) REFERENCES `pemasok` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_pembelian_pencatat` FOREIGN KEY (`dicatat_oleh`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `pengaturan`
--
ALTER TABLE `pengaturan`
  ADD CONSTRAINT `fk_pengaturan_pengubah` FOREIGN KEY (`diubah_oleh`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `pergerakan_stok`
--
ALTER TABLE `pergerakan_stok`
  ADD CONSTRAINT `fk_pergerakan_bahan` FOREIGN KEY (`bahan_id`) REFERENCES `bahan` (`id`),
  ADD CONSTRAINT `fk_pergerakan_pengguna` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `periode_akuntansi`
--
ALTER TABLE `periode_akuntansi`
  ADD CONSTRAINT `fk_periode_pengunci` FOREIGN KEY (`dikunci_oleh`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `fk_pesanan_penangan` FOREIGN KEY (`ditangani_oleh`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_pesanan_shift` FOREIGN KEY (`shift_id`) REFERENCES `shift_kas` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pesanan_pelanggan_id_foreign` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `fk_produk_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_produk_pembuat` FOREIGN KEY (`dibuat_oleh`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_produk_pengubah` FOREIGN KEY (`diubah_oleh`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `resep`
--
ALTER TABLE `resep`
  ADD CONSTRAINT `fk_resep_pembuat` FOREIGN KEY (`dibuat_oleh`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_resep_varian` FOREIGN KEY (`varian_id`) REFERENCES `varian_produk` (`id`);

--
-- Constraints for table `resep_bahan`
--
ALTER TABLE `resep_bahan`
  ADD CONSTRAINT `fk_resepbahan_bahan` FOREIGN KEY (`bahan_id`) REFERENCES `bahan` (`id`),
  ADD CONSTRAINT `fk_resepbahan_resep` FOREIGN KEY (`resep_id`) REFERENCES `resep` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `riwayat_stok`
--
ALTER TABLE `riwayat_stok`
  ADD CONSTRAINT `fk_riwayat_pengguna` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_riwayat_pesanan` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_riwayat_produk` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shift_kas`
--
ALTER TABLE `shift_kas`
  ADD CONSTRAINT `fk_shift_pengguna` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`);

--
-- Constraints for table `varian_produk`
--
ALTER TABLE `varian_produk`
  ADD CONSTRAINT `fk_varian_produk` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
