-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for pos_qris
CREATE DATABASE IF NOT EXISTS `pos_qris` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `pos_qris`;

-- Dumping structure for table pos_qris.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pos_qris.cache: ~0 rows (approximately)
DELETE FROM `cache`;

-- Dumping structure for table pos_qris.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pos_qris.cache_locks: ~0 rows (approximately)
DELETE FROM `cache_locks`;

-- Dumping structure for table pos_qris.cetak_struk
CREATE TABLE IF NOT EXISTS `cetak_struk` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaksi_id` bigint unsigned NOT NULL,
  `waktu_cetak` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cetak_struk_transaksi_id_foreign` (`transaksi_id`),
  CONSTRAINT `cetak_struk_transaksi_id_foreign` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pos_qris.cetak_struk: ~0 rows (approximately)
DELETE FROM `cetak_struk`;

-- Dumping structure for table pos_qris.detail_transaksi
CREATE TABLE IF NOT EXISTS `detail_transaksi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaksi_id` bigint unsigned NOT NULL,
  `produk_id` bigint unsigned NOT NULL,
  `jumlah` int NOT NULL,
  `harga_satuan` decimal(12,2) NOT NULL,
  `subtotal` decimal(14,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `detail_transaksi_transaksi_id_produk_id_unique` (`transaksi_id`,`produk_id`),
  KEY `detail_transaksi_produk_id_foreign` (`produk_id`),
  CONSTRAINT `detail_transaksi_produk_id_foreign` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detail_transaksi_transaksi_id_foreign` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pos_qris.detail_transaksi: ~22 rows (approximately)
DELETE FROM `detail_transaksi`;
INSERT INTO `detail_transaksi` (`id`, `transaksi_id`, `produk_id`, `jumlah`, `harga_satuan`, `subtotal`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 2, 15000.00, 30000.00, '2025-12-14 22:43:08', '2025-12-14 22:43:08'),
	(2, 1, 3, 2, 18000.00, 36000.00, '2025-12-14 22:43:08', '2025-12-14 22:43:08'),
	(3, 2, 7, 2, 15000.00, 30000.00, '2025-12-14 22:43:20', '2025-12-14 22:43:20'),
	(5, 3, 1, 3, 15000.00, 45000.00, '2025-12-14 22:44:27', '2025-12-14 22:44:27'),
	(9, 5, 1, 1, 15000.00, 15000.00, '2025-12-31 05:13:45', '2025-12-31 05:13:45'),
	(10, 6, 3, 1, 18000.00, 18000.00, '2026-01-19 18:00:30', '2026-01-19 18:00:30'),
	(11, 6, 4, 1, 5000.00, 5000.00, '2026-01-19 18:00:30', '2026-01-19 18:00:30'),
	(12, 7, 1, 1, 15000.00, 15000.00, '2026-01-19 18:01:11', '2026-01-19 18:01:11'),
	(13, 7, 2, 1, 12000.00, 12000.00, '2026-01-19 18:01:11', '2026-01-19 18:01:11'),
	(14, 8, 9, 1, 6000.00, 6000.00, '2026-01-19 20:21:59', '2026-01-19 20:21:59'),
	(15, 8, 4, 1, 5000.00, 5000.00, '2026-01-19 20:21:59', '2026-01-19 20:21:59'),
	(16, 8, 1, 1, 15000.00, 15000.00, '2026-01-19 20:21:59', '2026-01-19 20:21:59'),
	(29, 14, 9, 1, 6000.00, 6000.00, '2026-01-21 09:16:55', '2026-01-21 09:16:55'),
	(30, 14, 5, 1, 7000.00, 7000.00, '2026-01-21 09:16:55', '2026-01-21 09:16:55'),
	(31, 15, 5, 1, 7000.00, 7000.00, '2026-01-21 09:18:43', '2026-01-21 09:18:43'),
	(32, 15, 4, 1, 5000.00, 5000.00, '2026-01-21 09:18:43', '2026-01-21 09:18:43'),
	(33, 15, 6, 1, 10000.00, 10000.00, '2026-01-21 09:18:43', '2026-01-21 09:18:43'),
	(34, 16, 5, 2, 7000.00, 14000.00, '2026-01-21 09:23:24', '2026-01-21 09:23:24'),
	(35, 17, 7, 1, 15000.00, 15000.00, '2026-01-21 09:31:18', '2026-01-21 09:31:18'),
	(36, 17, 18, 1, 7000.00, 7000.00, '2026-01-21 09:31:18', '2026-01-21 09:31:18'),
	(37, 18, 7, 1, 15000.00, 15000.00, '2026-01-21 09:38:54', '2026-01-21 09:38:54'),
	(38, 18, 5, 1, 7000.00, 7000.00, '2026-01-21 09:38:54', '2026-01-21 09:38:54'),
	(39, 18, 18, 1, 7000.00, 7000.00, '2026-01-21 09:38:54', '2026-01-21 09:38:54'),
	(40, 19, 18, 1, 7000.00, 7000.00, '2026-01-21 09:44:23', '2026-01-21 09:44:23'),
	(41, 19, 1, 1, 15000.00, 15000.00, '2026-01-21 09:44:23', '2026-01-21 09:44:23');

-- Dumping structure for table pos_qris.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pos_qris.failed_jobs: ~0 rows (approximately)
DELETE FROM `failed_jobs`;

-- Dumping structure for table pos_qris.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pos_qris.jobs: ~0 rows (approximately)
DELETE FROM `jobs`;

-- Dumping structure for table pos_qris.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pos_qris.job_batches: ~0 rows (approximately)
DELETE FROM `job_batches`;

-- Dumping structure for table pos_qris.kategori
CREATE TABLE IF NOT EXISTS `kategori` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kategori_nama_unique` (`nama`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pos_qris.kategori: ~3 rows (approximately)
DELETE FROM `kategori`;
INSERT INTO `kategori` (`id`, `nama`, `created_at`, `updated_at`) VALUES
	(1, 'Makanan', '2025-12-14 22:40:44', '2025-12-14 22:40:44'),
	(2, 'Minuman', '2025-12-14 22:40:44', '2025-12-14 22:40:44'),
	(3, 'Snack', '2025-12-14 22:40:44', '2025-12-14 22:40:44');

-- Dumping structure for table pos_qris.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pos_qris.migrations: ~12 rows (approximately)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_12_13_033442_create_pengguna_table', 1),
	(5, '2025_12_13_033843_create_kategori_table', 1),
	(6, '2025_12_13_033939_create_produk_table', 1),
	(7, '2025_12_13_034034_create_transaksi_table', 1),
	(8, '2025_12_13_034129_create_detail_transaksi_table', 1),
	(9, '2025_12_13_034239_create_pembayaran_qris_table', 1),
	(10, '2025_12_13_034320_create_cetak_struk_table', 1),
	(11, '2025_12_22_114208_change_detail_transaksi_foreign_key_cascade', 2),
	(12, '2025_12_22_114818_update_foreign_key_detail_transaksi_cascade', 3);

-- Dumping structure for table pos_qris.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pos_qris.password_reset_tokens: ~0 rows (approximately)
DELETE FROM `password_reset_tokens`;

-- Dumping structure for table pos_qris.pembayaran_qris
CREATE TABLE IF NOT EXISTS `pembayaran_qris` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaksi_id` bigint unsigned NOT NULL,
  `invoice_qris` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qris_string` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `qris_gambar` text COLLATE utf8mb4_unicode_ci,
  `nominal` decimal(14,2) NOT NULL,
  `status` enum('menunggu','berhasil','gagal','kedaluwarsa') COLLATE utf8mb4_unicode_ci NOT NULL,
  `waktu_callback` datetime DEFAULT NULL,
  `data_callback` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pembayaran_qris_invoice_qris_unique` (`invoice_qris`),
  KEY `pembayaran_qris_transaksi_id_foreign` (`transaksi_id`),
  CONSTRAINT `pembayaran_qris_transaksi_id_foreign` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pos_qris.pembayaran_qris: ~6 rows (approximately)
DELETE FROM `pembayaran_qris`;
INSERT INTO `pembayaran_qris` (`id`, `transaksi_id`, `invoice_qris`, `qris_string`, `qris_gambar`, `nominal`, `status`, `waktu_callback`, `data_callback`, `created_at`, `updated_at`) VALUES
	(1, 14, 'INV-1769012215', 'c289219a-896b-4e65-8663-8cc9fd642a1f', NULL, 13000.00, 'menunggu', NULL, NULL, '2026-01-21 09:16:56', '2026-01-21 09:16:56'),
	(2, 15, 'INV-1769012323', 'af25b7b0-4c06-43bd-aa70-658edc16bcbe', NULL, 22000.00, 'menunggu', NULL, NULL, '2026-01-21 09:18:44', '2026-01-21 09:18:44'),
	(3, 16, 'INV-1769012604', '289f4efc-d3be-4983-bf74-1a40437a09a2', NULL, 14000.00, 'menunggu', NULL, NULL, '2026-01-21 09:23:25', '2026-01-21 09:23:25'),
	(4, 17, 'INV-1769013078', 'e2f5f5f4-f8fd-476c-b0a6-8175efddd003', NULL, 22000.00, 'menunggu', NULL, NULL, '2026-01-21 09:31:18', '2026-01-21 09:31:18'),
	(5, 18, 'INV-1769013534', '6df20379-fce0-4b3d-97a1-6ec7cdb8274b', NULL, 29000.00, 'menunggu', NULL, NULL, '2026-01-21 09:38:54', '2026-01-21 09:38:54'),
	(6, 19, 'INV-1769013863', 'a116be27-ecb3-4590-992e-a0829564cd01', NULL, 22000.00, 'menunggu', NULL, NULL, '2026-01-21 09:44:23', '2026-01-21 09:44:23');

-- Dumping structure for table pos_qris.pengguna
CREATE TABLE IF NOT EXISTS `pengguna` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kata_sandi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `peran` enum('admin','kasir') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pengguna_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pos_qris.pengguna: ~2 rows (approximately)
DELETE FROM `pengguna`;
INSERT INTO `pengguna` (`id`, `nama`, `email`, `kata_sandi`, `peran`, `created_at`, `updated_at`) VALUES
	(1, 'Admin', 'admin@pos.test', '$2y$12$hRy7o.OcyK1gjhSRQcN/Q.ICM19MBLaUN1mLbNNwkNLQz5otWXe2q', 'admin', '2025-12-13 01:54:35', '2025-12-13 09:50:31'),
	(2, 'Kasir', 'kasir@pos.test', '$2y$12$wHGlD4RLdENu4NEMgSMdHuRK1uP4aYkaHlHF9k4/DQSEvyiVt9G.a', 'kasir', '2025-12-13 01:54:36', '2025-12-13 01:54:36');

-- Dumping structure for table pos_qris.produk
CREATE TABLE IF NOT EXISTS `produk` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kategori_id` bigint unsigned NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `stok` int NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `produk_kategori_id_foreign` (`kategori_id`),
  CONSTRAINT `produk_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pos_qris.produk: ~10 rows (approximately)
DELETE FROM `produk`;
INSERT INTO `produk` (`id`, `kategori_id`, `nama`, `harga`, `stok`, `deskripsi`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Nasi Goreng', 15000.00, 41, 'Nasi goreng spesial dengan telur', '2025-12-14 22:40:45', '2026-01-21 09:44:23'),
	(2, 1, 'Mie Ayam', 12000.00, 39, 'Mie ayam dengan pangsit', '2025-12-14 22:40:45', '2026-01-19 18:01:11'),
	(3, 1, 'Ayam Geprek', 18000.00, 27, 'Ayam crispy dengan sambal geprek', '2025-12-14 22:40:45', '2026-01-19 18:00:30'),
	(4, 2, 'Es Teh Manis', 5000.00, 97, 'Es teh manis segar', '2025-12-14 22:40:45', '2026-01-21 09:18:43'),
	(5, 2, 'Es Jeruk', 7000.00, 75, 'Es jeruk peras segar', '2025-12-14 22:40:45', '2026-01-21 09:38:54'),
	(6, 2, 'Kopi Susu', 10000.00, 59, 'Kopi susu hangat', '2025-12-14 22:40:45', '2026-01-21 09:18:43'),
	(7, 2, 'Cappuccino', 15000.00, 46, 'Cappuccino premium', '2025-12-14 22:40:45', '2026-01-21 09:38:54'),
	(9, 3, 'Biskuit Cokelat', 6000.00, 88, 'Biskuit rasa cokelat', '2025-12-14 22:40:45', '2026-01-21 09:16:55'),
	(18, 2, 'Wedang Jahe', 7000.00, 197, NULL, '2025-12-22 04:55:59', '2026-01-21 09:44:23');

-- Dumping structure for table pos_qris.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pos_qris.sessions: ~5 rows (approximately)
DELETE FROM `sessions`;
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('3ylRyxznrdGOsXIdipsPg3VX7OUmBKXsfMExu75k', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZHljYkkwOHBPbGdUanNlU2lKN0VrSEUyUDhkT2ZIajRJbGRuZHVtZSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1769014427),
	('7rtFYncysOMTpyQUGgLVe7cRTIwpNISx9J1hKOQz', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUUJWUnFhbEF2SXN5VlNWdVdMam9QaUIzOHloMjVkODJiTWdmWXNOaSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1769014466),
	('j1tDb7l5mf47nE358Let0DtBbvkQqprFvZb2NTf6', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQWVicmxZTEZyeDFLWUg3dDZlNHpTN2dDeUxUZlJGekwyZnpGM1UweiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1769011534),
	('OQpARX2JzeUxZySA9Gq3CIACtZP8YHGuLSj0Q6Ql', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidjh5NWJuRFAyWUdnbjdRUnZSblRiVjIwV1p2ejJuSWZHd1BCakVSOCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1769014412),
	('wrn4jqBurLGk2CYshDeSVsoynts5jopcOyByTfHa', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiSzB5QzRsdmF1c2ZQazNOdEdNQUtiYjU3T2xLSkZwSWl4elhqekZuTyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9rYXNpci90cmFuc2Frc2kiO3M6NToicm91dGUiO3M6MTU6Imthc2lyLnRyYW5zYWtzaSI7fXM6NzoidXNlcl9pZCI7aToyO3M6OToidXNlcl9yb2xlIjtzOjU6Imthc2lyIjtzOjk6InVzZXJfbmFtZSI7czo1OiJLYXNpciI7fQ==', 1769014488);

-- Dumping structure for table pos_qris.transaksi
CREATE TABLE IF NOT EXISTS `transaksi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pengguna_id` bigint unsigned NOT NULL,
  `nomor_invoice` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_transaksi` datetime NOT NULL,
  `total_pembayaran` decimal(14,2) NOT NULL,
  `metode_pembayaran` enum('tunai','qris') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','dibayar','dibatalkan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transaksi_nomor_invoice_unique` (`nomor_invoice`),
  KEY `transaksi_pengguna_id_foreign` (`pengguna_id`),
  CONSTRAINT `transaksi_pengguna_id_foreign` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pos_qris.transaksi: ~14 rows (approximately)
DELETE FROM `transaksi`;
INSERT INTO `transaksi` (`id`, `pengguna_id`, `nomor_invoice`, `tanggal_transaksi`, `total_pembayaran`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES
	(1, 2, 'INV-1765777388', '2025-12-15 05:43:08', 66000.00, 'qris', 'pending', '2025-12-14 22:43:08', '2025-12-14 22:43:08'),
	(2, 2, 'INV-1765777400', '2025-12-15 05:43:20', 780000.00, 'qris', 'pending', '2025-12-14 22:43:20', '2025-12-14 22:43:20'),
	(3, 2, 'INV-1765777467', '2025-12-15 05:44:27', 195000.00, 'qris', 'pending', '2025-12-14 22:44:27', '2025-12-14 22:44:27'),
	(4, 2, 'INV-1766402928', '2025-12-22 11:28:48', 5000.00, 'qris', 'pending', '2025-12-22 04:28:48', '2025-12-22 04:28:48'),
	(5, 2, 'INV-1767183225', '2025-12-31 12:13:45', 15000.00, 'qris', 'pending', '2025-12-31 05:13:45', '2025-12-31 05:13:45'),
	(6, 2, 'INV-1768870830', '2026-01-20 01:00:30', 23000.00, 'qris', 'pending', '2026-01-19 18:00:30', '2026-01-19 18:00:30'),
	(7, 2, 'INV-1768870871', '2026-01-20 01:01:11', 27000.00, 'tunai', 'pending', '2026-01-19 18:01:11', '2026-01-19 18:01:11'),
	(8, 2, 'INV-1768879319', '2026-01-20 03:21:59', 26000.00, 'qris', 'pending', '2026-01-19 20:21:59', '2026-01-19 20:21:59'),
	(14, 2, 'INV-1769012215', '2026-01-21 16:16:55', 13000.00, 'qris', 'pending', '2026-01-21 09:16:55', '2026-01-21 09:16:55'),
	(15, 2, 'INV-1769012323', '2026-01-21 16:18:43', 22000.00, 'qris', 'pending', '2026-01-21 09:18:43', '2026-01-21 09:18:43'),
	(16, 2, 'INV-1769012604', '2026-01-21 16:23:24', 14000.00, 'qris', 'pending', '2026-01-21 09:23:24', '2026-01-21 09:23:24'),
	(17, 2, 'INV-1769013078', '2026-01-21 16:31:18', 22000.00, 'qris', 'pending', '2026-01-21 09:31:18', '2026-01-21 09:31:18'),
	(18, 2, 'INV-1769013534', '2026-01-21 16:38:54', 29000.00, 'qris', 'pending', '2026-01-21 09:38:54', '2026-01-21 09:38:54'),
	(19, 2, 'INV-1769013863', '2026-01-21 16:44:23', 22000.00, 'qris', 'pending', '2026-01-21 09:44:23', '2026-01-21 09:44:23');

-- Dumping structure for table pos_qris.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pos_qris.users: ~0 rows (approximately)
DELETE FROM `users`;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
