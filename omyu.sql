-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 10, 2026 at 02:51 AM
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
-- Table structure for table `akun`
--

CREATE TABLE `akun` (
  `id` bigint UNSIGNED NOT NULL,
  `kode` varchar(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `golongan` enum('aset_lancar','aset_tetap','pengurang_aset','kewajiban','ekuitas','pengurang_ekuitas','pendapatan','pengurang_pendapatan','harga_pokok','beban_operasional','pendapatan_lain','beban_pajak') NOT NULL,
  `saldo_normal` enum('debit','kredit') NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
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
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis` enum('buah','bahan_lain','kemasan') NOT NULL DEFAULT 'bahan_lain',
  `satuan_dasar` enum('g','ml','pcs') NOT NULL,
  `satuan_beli` varchar(20) NOT NULL,
  `konversi` decimal(14,3) NOT NULL DEFAULT '1.000',
  `rendemen_standar` decimal(5,2) NOT NULL DEFAULT '100.00',
  `batas_minimum` decimal(14,3) NOT NULL DEFAULT '0.000',
  `stok` decimal(14,3) NOT NULL DEFAULT '0.000',
  `stok_dicadangkan` decimal(14,3) NOT NULL DEFAULT '0.000',
  `biaya_rata_rata` decimal(14,4) NOT NULL DEFAULT '0.0000',
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ;

--
-- Dumping data for table `bahan`
--

INSERT INTO `bahan` (`id`, `nama`, `jenis`, `satuan_dasar`, `satuan_beli`, `konversi`, `rendemen_standar`, `batas_minimum`, `stok`, `stok_dicadangkan`, `biaya_rata_rata`, `aktif`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Melon', 'buah', 'g', 'kg', '1000.000', '70.00', '1000.000', '4900.000', '0.000', '21.1900', 1, '2026-11-10 02:51:34', '2026-11-10 02:51:34', NULL),
(2, 'Gula Pasir', 'bahan_lain', 'g', 'kg', '1000.000', '100.00', '500.000', '1000.000', '0.000', '15.0000', 1, '2026-11-10 02:51:34', '2026-11-10 02:51:34', NULL),
(3, 'Es Batu', 'bahan_lain', 'g', 'kg', '1000.000', '100.00', '1000.000', '5000.000', '0.000', '3.0000', 1, '2026-11-10 02:51:34', '2026-11-10 02:51:34', NULL),
(4, 'Cup & Sedotan', 'kemasan', 'pcs', 'pack', '50.000', '100.00', '20.000', '100.000', '0.000', '1200.0000', 1, '2026-11-10 02:51:34', '2026-11-10 02:51:34', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cadangan_bahan`
--

CREATE TABLE `cadangan_bahan` (
  `id` bigint UNSIGNED NOT NULL,
  `pesanan_id` bigint UNSIGNED NOT NULL,
  `bahan_id` bigint UNSIGNED NOT NULL,
  `jumlah` decimal(14,3) NOT NULL,
  `status` enum('aktif','dilepas','terpakai') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `varian_id` bigint UNSIGNED DEFAULT NULL,
  `nama_produk` varchar(150) NOT NULL,
  `nama_varian` varchar(50) DEFAULT NULL,
  `harga` bigint UNSIGNED NOT NULL,
  `jumlah` int UNSIGNED NOT NULL,
  `subtotal` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `resep_id` bigint UNSIGNED DEFAULT NULL,
  `hpp_bahan` decimal(14,2) DEFAULT NULL,
  `hpp_kemasan` decimal(14,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jurnal`
--

CREATE TABLE `jurnal` (
  `id` bigint UNSIGNED NOT NULL,
  `nomor` varchar(30) NOT NULL,
  `tanggal` date NOT NULL,
  `periode` char(7) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `rujukan_tipe` varchar(30) DEFAULT NULL,
  `rujukan_id` bigint UNSIGNED DEFAULT NULL,
  `koreksi_dari_id` bigint UNSIGNED DEFAULT NULL,
  `dibuat_oleh` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jurnal_baris`
--

CREATE TABLE `jurnal_baris` (
  `id` bigint UNSIGNED NOT NULL,
  `jurnal_id` bigint UNSIGNED NOT NULL,
  `akun_id` bigint UNSIGNED NOT NULL,
  `debit` decimal(16,2) NOT NULL DEFAULT '0.00',
  `kredit` decimal(16,2) NOT NULL DEFAULT '0.00'
) ;

-- --------------------------------------------------------

--
-- Table structure for table `kas_transaksi`
--

CREATE TABLE `kas_transaksi` (
  `id` bigint UNSIGNED NOT NULL,
  `shift_id` bigint UNSIGNED NOT NULL,
  `jenis` enum('masuk','keluar') NOT NULL,
  `jumlah` bigint UNSIGNED NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `pengguna_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
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
  `nilai_sebelum` json DEFAULT NULL,
  `nilai_sesudah` json DEFAULT NULL,
  `alasan` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id`, `pengguna_id`, `aksi`, `tipe_objek`, `id_objek`, `deskripsi`, `nilai_sebelum`, `nilai_sesudah`, `alasan`, `ip_address`, `created_at`) VALUES
(1, NULL, 'tes', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-11-09 03:10:52');

-- --------------------------------------------------------

--
-- Table structure for table `opname`
--

CREATE TABLE `opname` (
  `id` bigint UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `petugas_id` bigint UNSIGNED DEFAULT NULL,
  `catatan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `opname_item`
--

CREATE TABLE `opname_item` (
  `id` bigint UNSIGNED NOT NULL,
  `opname_id` bigint UNSIGNED NOT NULL,
  `bahan_id` bigint UNSIGNED NOT NULL,
  `stok_sistem` decimal(14,3) NOT NULL,
  `stok_fisik` decimal(14,3) NOT NULL,
  `selisih` decimal(14,3) NOT NULL,
  `nilai_selisih` decimal(16,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pemasok`
--

CREATE TABLE `pemasok` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `catatan` varchar(255) DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` bigint UNSIGNED NOT NULL,
  `pesanan_id` bigint UNSIGNED NOT NULL,
  `gerbang` varchar(30) NOT NULL DEFAULT 'manual',
  `referensi_gerbang` varchar(100) DEFAULT NULL,
  `metode` varchar(50) DEFAULT NULL,
  `bukti_path` varchar(255) DEFAULT NULL,
  `jumlah` bigint UNSIGNED NOT NULL,
  `status` enum('menunggu','menunggu_verifikasi','lunas','ditolak','kedaluwarsa','gagal','dikembalikan') NOT NULL DEFAULT 'menunggu',
  `dibayar_pada` datetime DEFAULT NULL,
  `data_mentah` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `biaya_transaksi` bigint UNSIGNED NOT NULL DEFAULT '0',
  `diverifikasi_oleh` bigint UNSIGNED DEFAULT NULL,
  `diverifikasi_pada` datetime DEFAULT NULL,
  `catatan_verifikasi` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembelian_bahan`
--

CREATE TABLE `pembelian_bahan` (
  `id` bigint UNSIGNED NOT NULL,
  `tanggal_beli` date NOT NULL,
  `bahan_id` bigint UNSIGNED NOT NULL,
  `pemasok_id` bigint UNSIGNED DEFAULT NULL,
  `jumlah_beli` decimal(14,3) NOT NULL,
  `satuan_beli` varchar(20) NOT NULL,
  `total_harga` bigint UNSIGNED NOT NULL,
  `rendemen_dipakai` decimal(5,2) NOT NULL DEFAULT '100.00',
  `jumlah_bersih` decimal(14,3) NOT NULL,
  `biaya_per_satuan` decimal(14,4) NOT NULL,
  `cara_bayar` enum('tunai','bank','utang') NOT NULL DEFAULT 'tunai',
  `kedaluwarsa_pada` date DEFAULT NULL,
  `dicatat_oleh` bigint UNSIGNED DEFAULT NULL,
  `catatan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan`
--

CREATE TABLE `pengaturan` (
  `id` bigint UNSIGNED NOT NULL,
  `kunci` varchar(60) NOT NULL,
  `nilai` text,
  `keterangan` varchar(150) DEFAULT NULL,
  `diubah_oleh` bigint UNSIGNED DEFAULT NULL,
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
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `peran` enum('admin','karyawan') NOT NULL DEFAULT 'karyawan',
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `bisa_lihat_hpp` tinyint(1) NOT NULL DEFAULT '0',
  `telepon` varchar(20) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id`, `nama`, `email`, `password`, `peran`, `aktif`, `bisa_lihat_hpp`, `telepon`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Admin OMYU', 'admin@omyu.test', '$2y$12$4z5vlWXSeBm9gDIqsL71N.zCH9rXsAG0zHj0Mne/vPXL8ZBuSAb9e', 'admin', 1, 0, NULL, 'Tva6YrinOUeLhxkx8aHckO9osKbtcLfGCCSNO0MxTTxocB4tOjF1DfBjxQ6P', '2026-11-01 07:54:03', '2026-11-09 03:23:35', NULL),
(2, 'Budi', 'budi@omyu.test', '$2y$12$YM87AsU/W4VyrM/ljLEgxeaxu62Q8oWs7bwTvt3tUaSp8LBsvZ9XS', 'karyawan', 1, 0, '081234567890', '0vu6y4jAHYC5EAp7W7qz73qmAC0Ob1oHbm1bOwUvV5sAeXwFjn47nWohAexo', '2026-11-09 03:23:35', '2026-11-09 03:23:35', NULL),
(3, 'Siti', 'siti@omyu.test', '$2y$12$Eg63F494Cnwtc/q1FnDqCOiKeSIA/cEyG2MydxiFjd20KFA4JMyK6', 'karyawan', 1, 0, NULL, NULL, '2026-11-09 03:23:35', '2026-11-09 03:23:35', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pergerakan_stok`
--

CREATE TABLE `pergerakan_stok` (
  `id` bigint UNSIGNED NOT NULL,
  `bahan_id` bigint UNSIGNED NOT NULL,
  `jenis` enum('pembelian','pemakaian_resep','pemakaian_lain','rusak','opname','penyesuaian') NOT NULL,
  `jumlah` decimal(14,3) NOT NULL,
  `nilai` decimal(16,2) NOT NULL DEFAULT '0.00',
  `stok_setelah` decimal(14,3) NOT NULL,
  `biaya_rata_rata_setelah` decimal(14,4) NOT NULL,
  `rujukan_tipe` varchar(30) DEFAULT NULL,
  `rujukan_id` bigint UNSIGNED DEFAULT NULL,
  `pengguna_id` bigint UNSIGNED DEFAULT NULL,
  `alasan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `periode_akuntansi`
--

CREATE TABLE `periode_akuntansi` (
  `id` bigint UNSIGNED NOT NULL,
  `periode` char(7) NOT NULL,
  `status` enum('buka','kunci') NOT NULL DEFAULT 'buka',
  `dikunci_oleh` bigint UNSIGNED DEFAULT NULL,
  `dikunci_pada` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_pesanan` varchar(30) NOT NULL,
  `token_akses` char(64) NOT NULL,
  `jenis` enum('online','langsung') NOT NULL DEFAULT 'online',
  `nama_pembeli` varchar(100) DEFAULT NULL,
  `telepon_pembeli` varchar(20) DEFAULT NULL,
  `email_pembeli` varchar(150) DEFAULT NULL,
  `alamat_pengiriman` text,
  `catatan` text,
  `subtotal` bigint UNSIGNED NOT NULL,
  `ongkir` bigint UNSIGNED NOT NULL DEFAULT '0',
  `total` bigint UNSIGNED NOT NULL,
  `metode_bayar` enum('qris','transfer','tunai','bayar_ditempat') DEFAULT NULL,
  `kode_unik` smallint UNSIGNED NOT NULL DEFAULT '0',
  `status_pembayaran` enum('menunggu','lunas','kedaluwarsa','gagal','dikembalikan') NOT NULL DEFAULT 'menunggu',
  `status_pesanan` enum('menunggu_pembayaran','menunggu_verifikasi','dikonfirmasi','diproses','siap_diambil','selesai','dibatalkan','kedaluwarsa') NOT NULL DEFAULT 'menunggu_pembayaran',
  `kedaluwarsa_pada` datetime DEFAULT NULL,
  `dibayar_pada` datetime DEFAULT NULL,
  `ditangani_oleh` bigint UNSIGNED DEFAULT NULL,
  `nomor_resi` varchar(60) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `jadwal_ambil_mulai` datetime DEFAULT NULL,
  `jadwal_ambil_selesai` datetime DEFAULT NULL,
  `uang_diterima` bigint UNSIGNED DEFAULT NULL,
  `kembalian` bigint UNSIGNED DEFAULT NULL,
  `shift_id` bigint UNSIGNED DEFAULT NULL,
  `alasan_batal` varchar(255) DEFAULT NULL,
  `diselesaikan_pada` datetime DEFAULT NULL
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

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `kategori_id`, `nama`, `slug`, `deskripsi`, `harga`, `stok`, `berat_gram`, `gambar`, `aktif`, `dibuat_oleh`, `diubah_oleh`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Jus Melon Segar', 'jus-melon-segar', 'Jus melon segar tanpa pengawet', 12000, 24, NULL, NULL, 1, NULL, NULL, '2026-11-10 02:51:34', '2026-11-10 02:51:34', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `resep`
--

CREATE TABLE `resep` (
  `id` bigint UNSIGNED NOT NULL,
  `varian_id` bigint UNSIGNED NOT NULL,
  `versi` int UNSIGNED NOT NULL DEFAULT '1',
  `berlaku_mulai` datetime NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `alasan_perubahan` varchar(255) DEFAULT NULL,
  `dibuat_oleh` bigint UNSIGNED DEFAULT NULL,
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
  `id` bigint UNSIGNED NOT NULL,
  `resep_id` bigint UNSIGNED NOT NULL,
  `bahan_id` bigint UNSIGNED NOT NULL,
  `takaran` decimal(14,3) NOT NULL,
  `keterangan` varchar(100) DEFAULT NULL
) ;

--
-- Dumping data for table `resep_bahan`
--

INSERT INTO `resep_bahan` (`id`, `resep_id`, `bahan_id`, `takaran`, `keterangan`) VALUES
(1, 1, 1, '200.000', NULL),
(2, 1, 2, '15.000', NULL),
(3, 1, 3, '100.000', NULL),
(4, 1, 4, '1.000', NULL);

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

-- --------------------------------------------------------

--
-- Table structure for table `shift_kas`
--

CREATE TABLE `shift_kas` (
  `id` bigint UNSIGNED NOT NULL,
  `pengguna_id` bigint UNSIGNED NOT NULL,
  `dibuka_pada` datetime NOT NULL,
  `ditutup_pada` datetime DEFAULT NULL,
  `modal_awal` bigint UNSIGNED NOT NULL DEFAULT '0',
  `penerimaan_tunai` bigint UNSIGNED DEFAULT NULL,
  `pengeluaran_tunai` bigint UNSIGNED DEFAULT NULL,
  `kas_seharusnya` bigint DEFAULT NULL,
  `kas_fisik` bigint UNSIGNED DEFAULT NULL,
  `selisih` bigint DEFAULT NULL,
  `status` enum('buka','tutup') NOT NULL DEFAULT 'buka',
  `catatan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `varian_produk`
--

CREATE TABLE `varian_produk` (
  `id` bigint UNSIGNED NOT NULL,
  `produk_id` bigint UNSIGNED NOT NULL,
  `nama` varchar(50) NOT NULL,
  `harga` bigint UNSIGNED NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
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
`jurnal_id` bigint unsigned
,`total_debit` decimal(38,2)
,`total_kredit` decimal(38,2)
);

-- --------------------------------------------------------

--
-- Structure for view `v_jurnal_tidak_seimbang`
--
DROP TABLE IF EXISTS `v_jurnal_tidak_seimbang`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_jurnal_tidak_seimbang`  AS SELECT `jurnal_baris`.`jurnal_id` AS `jurnal_id`, sum(`jurnal_baris`.`debit`) AS `total_debit`, sum(`jurnal_baris`.`kredit`) AS `total_kredit` FROM `jurnal_baris` GROUP BY `jurnal_baris`.`jurnal_id` HAVING (sum(`jurnal_baris`.`debit`) <> sum(`jurnal_baris`.`kredit`))  ;

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
-- Indexes for table `cadangan_bahan`
--
ALTER TABLE `cadangan_bahan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cadangan_bahan` (`bahan_id`,`status`),
  ADD KEY `fk_cadangan_pesanan` (`pesanan_id`);

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
  ADD KEY `fk_pesanan_shift` (`shift_id`);

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
-- Indexes for table `shift_kas`
--
ALTER TABLE `shift_kas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_shift_status` (`status`),
  ADD KEY `fk_shift_pengguna` (`pengguna_id`);

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `bahan`
--
ALTER TABLE `bahan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cadangan_bahan`
--
ALTER TABLE `cadangan_bahan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `jurnal`
--
ALTER TABLE `jurnal`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jurnal_baris`
--
ALTER TABLE `jurnal_baris`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kas_transaksi`
--
ALTER TABLE `kas_transaksi`
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
-- AUTO_INCREMENT for table `opname`
--
ALTER TABLE `opname`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `opname_item`
--
ALTER TABLE `opname_item`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pemasok`
--
ALTER TABLE `pemasok`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pembelian_bahan`
--
ALTER TABLE `pembelian_bahan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengaturan`
--
ALTER TABLE `pengaturan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pergerakan_stok`
--
ALTER TABLE `pergerakan_stok`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `periode_akuntansi`
--
ALTER TABLE `periode_akuntansi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `resep`
--
ALTER TABLE `resep`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `resep_bahan`
--
ALTER TABLE `resep_bahan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `riwayat_stok`
--
ALTER TABLE `riwayat_stok`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shift_kas`
--
ALTER TABLE `shift_kas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `varian_produk`
--
ALTER TABLE `varian_produk`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  ADD CONSTRAINT `fk_pesanan_shift` FOREIGN KEY (`shift_id`) REFERENCES `shift_kas` (`id`) ON DELETE SET NULL;

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
