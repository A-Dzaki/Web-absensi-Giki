-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 04, 2025 at 06:36 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `web_absensi_giki`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensis`
--

CREATE TABLE `absensis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `siswa_id` bigint(20) UNSIGNED NOT NULL,
  `jadwal_id` bigint(20) UNSIGNED DEFAULT NULL,
  `kelas` varchar(255) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `mata_pelajaran` varchar(255) NOT NULL,
  `guru_id` bigint(20) UNSIGNED DEFAULT NULL,
  `jam` time DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `absensis`
--

INSERT INTO `absensis` (`id`, `siswa_id`, `jadwal_id`, `kelas`, `tanggal`, `mata_pelajaran`, `guru_id`, `jam`, `status`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 32, 4, '7E', '2025-12-03', 'Matematika', 72, '23:13:57', 'H', NULL, '2025-12-03 16:13:57', '2025-12-03 16:13:57'),
(2, 33, 4, '7E', '2025-12-03', 'Matematika', 72, '23:13:57', 'H', NULL, '2025-12-03 16:13:57', '2025-12-03 16:13:57'),
(3, 34, 4, '7E', '2025-12-03', 'Matematika', 72, '23:13:57', 'H', NULL, '2025-12-03 16:13:57', '2025-12-03 16:13:57'),
(4, 35, 4, '7E', '2025-12-03', 'Matematika', 72, '23:13:57', 'H', NULL, '2025-12-03 16:13:57', '2025-12-03 16:13:57'),
(5, 36, 4, '7E', '2025-12-03', 'Matematika', 72, '23:13:57', 'H', NULL, '2025-12-03 16:13:57', '2025-12-03 16:13:57'),
(6, 37, 4, '7E', '2025-12-03', 'Matematika', 72, '23:13:57', 'H', NULL, '2025-12-03 16:13:57', '2025-12-03 16:13:57'),
(7, 38, 4, '7E', '2025-12-03', 'Matematika', 72, '23:13:57', 'H', NULL, '2025-12-03 16:13:57', '2025-12-03 16:13:57'),
(8, 39, 4, '7E', '2025-12-03', 'Matematika', 72, '23:13:57', 'H', NULL, '2025-12-03 16:13:57', '2025-12-03 16:13:57'),
(9, 40, 4, '7E', '2025-12-03', 'Matematika', 72, '23:13:57', 'H', NULL, '2025-12-03 16:13:57', '2025-12-03 16:13:57'),
(10, 41, 4, '7E', '2025-12-03', 'Matematika', 72, '23:13:57', 'H', NULL, '2025-12-03 16:13:57', '2025-12-03 16:13:57'),
(11, 42, 4, '7E', '2025-12-03', 'Matematika', 72, '23:13:57', 'H', NULL, '2025-12-03 16:13:57', '2025-12-03 16:13:57'),
(12, 43, 4, '7E', '2025-12-03', 'Matematika', 72, '23:13:57', 'H', NULL, '2025-12-03 16:13:58', '2025-12-03 16:13:58'),
(13, 44, 4, '7E', '2025-12-03', 'Matematika', 72, '23:13:58', 'H', NULL, '2025-12-03 16:13:58', '2025-12-03 16:13:58'),
(14, 45, 4, '7E', '2025-12-03', 'Matematika', 72, '23:13:58', 'H', NULL, '2025-12-03 16:13:58', '2025-12-03 16:13:58'),
(15, 46, 4, '7E', '2025-12-03', 'Matematika', 72, '23:13:58', 'H', NULL, '2025-12-03 16:13:58', '2025-12-03 16:13:58'),
(16, 47, 4, '7E', '2025-12-03', 'Matematika', 72, '23:13:58', 'H', NULL, '2025-12-03 16:13:58', '2025-12-03 16:13:58'),
(17, 48, 4, '7E', '2025-12-03', 'Matematika', 72, '23:13:58', 'H', NULL, '2025-12-03 16:13:58', '2025-12-03 16:13:58'),
(18, 49, 4, '7E', '2025-12-03', 'Matematika', 72, '23:13:58', 'H', NULL, '2025-12-03 16:13:58', '2025-12-03 16:13:58'),
(19, 50, 4, '7E', '2025-12-03', 'Matematika', 72, '23:13:58', 'H', NULL, '2025-12-03 16:13:58', '2025-12-03 16:13:58'),
(20, 51, 4, '7E', '2025-12-03', 'Matematika', 72, '23:13:58', 'H', NULL, '2025-12-03 16:13:58', '2025-12-03 16:13:58'),
(21, 52, 4, '7E', '2025-12-03', 'Matematika', 72, '23:13:58', 'H', NULL, '2025-12-03 16:13:58', '2025-12-03 16:13:58'),
(22, 53, 4, '7E', '2025-12-03', 'Matematika', 72, '23:13:58', 'H', NULL, '2025-12-03 16:13:58', '2025-12-03 16:13:58'),
(23, 54, 4, '7E', '2025-12-03', 'Matematika', 72, '23:13:58', 'H', NULL, '2025-12-03 16:13:58', '2025-12-03 16:13:58'),
(24, 55, 4, '7E', '2025-12-03', 'Matematika', 72, '23:13:58', 'H', NULL, '2025-12-03 16:13:58', '2025-12-03 16:13:58'),
(25, 56, 4, '7E', '2025-12-03', 'Matematika', 72, '23:13:58', 'H', NULL, '2025-12-03 16:13:58', '2025-12-03 16:13:58'),
(26, 57, 4, '7E', '2025-12-03', 'Matematika', 72, '23:13:58', 'H', NULL, '2025-12-03 16:13:58', '2025-12-03 16:13:58'),
(27, 58, 4, '7E', '2025-12-03', 'Matematika', 72, '23:13:58', 'H', NULL, '2025-12-03 16:13:58', '2025-12-03 16:13:58'),
(28, 59, 4, '7E', '2025-12-03', 'Matematika', 72, '23:13:58', 'H', NULL, '2025-12-03 16:13:58', '2025-12-03 16:13:58'),
(29, 60, 4, '7E', '2025-12-03', 'Matematika', 72, '23:13:58', 'H', NULL, '2025-12-03 16:13:58', '2025-12-03 16:13:58'),
(30, 61, 4, '7E', '2025-12-03', 'Matematika', 72, '23:13:58', 'H', NULL, '2025-12-03 16:13:58', '2025-12-03 16:13:58'),
(31, 62, 4, '7E', '2025-12-03', 'Matematika', 72, '23:13:58', 'H', NULL, '2025-12-03 16:13:58', '2025-12-03 16:13:58'),
(32, 73, 4, '7E', '2025-12-03', 'Matematika', 72, '23:13:58', 'H', NULL, '2025-12-03 16:13:58', '2025-12-03 16:13:58'),
(33, 63, 4, '7E', '2025-12-03', 'Matematika', 72, '23:13:58', 'H', NULL, '2025-12-03 16:13:58', '2025-12-03 16:13:58'),
(34, 64, 4, '7E', '2025-12-03', 'Matematika', 72, '23:13:58', 'H', NULL, '2025-12-03 16:13:58', '2025-12-03 16:13:58'),
(35, 32, 3, '7E', '2025-12-04', 'Matematika', 72, '00:17:16', 'H', NULL, '2025-12-03 17:17:16', '2025-12-03 17:17:16'),
(36, 33, 3, '7E', '2025-12-04', 'Matematika', 72, '00:17:16', 'S', NULL, '2025-12-03 17:17:16', '2025-12-03 17:17:16'),
(37, 34, 3, '7E', '2025-12-04', 'Matematika', 72, '00:17:16', 'S', NULL, '2025-12-03 17:17:16', '2025-12-03 17:17:16'),
(38, 35, 3, '7E', '2025-12-04', 'Matematika', 72, '00:17:16', 'S', NULL, '2025-12-03 17:17:16', '2025-12-03 17:17:16'),
(39, 36, 3, '7E', '2025-12-04', 'Matematika', 72, '00:17:16', 'H', NULL, '2025-12-03 17:17:16', '2025-12-03 17:17:16'),
(40, 37, 3, '7E', '2025-12-04', 'Matematika', 72, '00:17:16', 'H', NULL, '2025-12-03 17:17:16', '2025-12-03 17:17:16'),
(41, 38, 3, '7E', '2025-12-04', 'Matematika', 72, '00:17:16', 'H', NULL, '2025-12-03 17:17:16', '2025-12-03 17:17:16'),
(42, 39, 3, '7E', '2025-12-04', 'Matematika', 72, '00:17:16', 'H', NULL, '2025-12-03 17:17:16', '2025-12-03 17:17:16'),
(43, 40, 3, '7E', '2025-12-04', 'Matematika', 72, '00:17:16', 'H', NULL, '2025-12-03 17:17:16', '2025-12-03 17:17:16'),
(44, 41, 3, '7E', '2025-12-04', 'Matematika', 72, '00:17:16', 'H', NULL, '2025-12-03 17:17:16', '2025-12-03 17:17:16'),
(45, 42, 3, '7E', '2025-12-04', 'Matematika', 72, '00:17:16', 'H', NULL, '2025-12-03 17:17:16', '2025-12-03 17:17:16'),
(46, 43, 3, '7E', '2025-12-04', 'Matematika', 72, '00:17:16', 'H', NULL, '2025-12-03 17:17:16', '2025-12-03 17:17:16'),
(47, 44, 3, '7E', '2025-12-04', 'Matematika', 72, '00:17:16', 'H', NULL, '2025-12-03 17:17:16', '2025-12-03 17:17:16'),
(48, 45, 3, '7E', '2025-12-04', 'Matematika', 72, '00:17:16', 'H', NULL, '2025-12-03 17:17:16', '2025-12-03 17:17:16'),
(49, 46, 3, '7E', '2025-12-04', 'Matematika', 72, '00:17:16', 'H', NULL, '2025-12-03 17:17:16', '2025-12-03 17:17:16'),
(50, 47, 3, '7E', '2025-12-04', 'Matematika', 72, '00:17:16', 'H', NULL, '2025-12-03 17:17:16', '2025-12-03 17:17:16'),
(51, 48, 3, '7E', '2025-12-04', 'Matematika', 72, '00:17:16', 'H', NULL, '2025-12-03 17:17:16', '2025-12-03 17:17:16'),
(52, 49, 3, '7E', '2025-12-04', 'Matematika', 72, '00:17:16', 'H', NULL, '2025-12-03 17:17:16', '2025-12-03 17:17:16'),
(53, 50, 3, '7E', '2025-12-04', 'Matematika', 72, '00:17:16', 'H', NULL, '2025-12-03 17:17:16', '2025-12-03 17:17:16'),
(54, 51, 3, '7E', '2025-12-04', 'Matematika', 72, '00:17:16', 'H', NULL, '2025-12-03 17:17:16', '2025-12-03 17:17:16'),
(55, 52, 3, '7E', '2025-12-04', 'Matematika', 72, '00:17:16', 'H', NULL, '2025-12-03 17:17:16', '2025-12-03 17:17:16'),
(56, 53, 3, '7E', '2025-12-04', 'Matematika', 72, '00:17:16', 'H', NULL, '2025-12-03 17:17:16', '2025-12-03 17:17:16'),
(57, 54, 3, '7E', '2025-12-04', 'Matematika', 72, '00:17:16', 'H', NULL, '2025-12-03 17:17:16', '2025-12-03 17:17:16'),
(58, 55, 3, '7E', '2025-12-04', 'Matematika', 72, '00:17:16', 'H', NULL, '2025-12-03 17:17:16', '2025-12-03 17:17:16'),
(59, 56, 3, '7E', '2025-12-04', 'Matematika', 72, '00:17:16', 'H', NULL, '2025-12-03 17:17:16', '2025-12-03 17:17:16'),
(60, 57, 3, '7E', '2025-12-04', 'Matematika', 72, '00:17:16', 'H', NULL, '2025-12-03 17:17:16', '2025-12-03 17:17:16'),
(61, 58, 3, '7E', '2025-12-04', 'Matematika', 72, '00:17:16', 'H', NULL, '2025-12-03 17:17:16', '2025-12-03 17:17:16'),
(62, 59, 3, '7E', '2025-12-04', 'Matematika', 72, '00:17:16', 'H', NULL, '2025-12-03 17:17:16', '2025-12-03 17:17:16'),
(63, 60, 3, '7E', '2025-12-04', 'Matematika', 72, '00:17:16', 'H', NULL, '2025-12-03 17:17:16', '2025-12-03 17:17:16'),
(64, 61, 3, '7E', '2025-12-04', 'Matematika', 72, '00:17:16', 'H', NULL, '2025-12-03 17:17:16', '2025-12-03 17:17:16'),
(65, 62, 3, '7E', '2025-12-04', 'Matematika', 72, '00:17:16', 'H', NULL, '2025-12-03 17:17:16', '2025-12-03 17:17:16'),
(66, 73, 3, '7E', '2025-12-04', 'Matematika', 72, '00:17:16', 'H', NULL, '2025-12-03 17:17:16', '2025-12-03 17:17:16'),
(67, 63, 3, '7E', '2025-12-04', 'Matematika', 72, '00:17:16', 'H', NULL, '2025-12-03 17:17:16', '2025-12-03 17:17:16'),
(68, 64, 3, '7E', '2025-12-04', 'Matematika', 72, '00:17:16', 'H', NULL, '2025-12-03 17:17:16', '2025-12-03 17:17:16');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwals`
--

CREATE TABLE `jadwals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `guru_id` bigint(20) UNSIGNED NOT NULL,
  `kelas_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `mata_pelajaran` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jadwals`
--

INSERT INTO `jadwals` (`id`, `guru_id`, `kelas_id`, `tanggal`, `jam_mulai`, `jam_selesai`, `mata_pelajaran`, `created_at`, `updated_at`) VALUES
(1, 16, 1, '2025-12-05', '07:30:00', '09:30:00', 'Bahasa Indonesia', '2025-12-03 09:18:45', '2025-12-03 09:18:45'),
(2, 14, 1, '2025-12-12', '07:31:00', '10:00:00', 'Agama Islam', '2025-12-03 15:20:32', '2025-12-03 15:20:32'),
(3, 72, 1, '2025-12-04', '08:00:00', '10:00:00', 'Matematika', '2025-12-03 15:35:58', '2025-12-03 16:03:30'),
(4, 72, 1, '2025-12-03', '07:15:00', '09:13:00', 'Matematika', '2025-12-03 16:13:21', '2025-12-03 16:13:21'),
(5, 72, 1, '2025-12-03', '11:19:00', '12:19:00', 'Bahasa Indonesia', '2025-12-03 16:19:26', '2025-12-03 16:19:26'),
(6, 72, 3, '2025-12-04', '11:14:00', '12:14:00', 'Agama Islam', '2025-12-03 17:14:44', '2025-12-03 17:14:44');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
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
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_kelas` varchar(255) NOT NULL,
  `tingkat` varchar(255) DEFAULT NULL,
  `walikelas` varchar(255) DEFAULT NULL,
  `jurusan` varchar(255) DEFAULT NULL,
  `jumlah_siswa` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id`, `nama_kelas`, `tingkat`, `walikelas`, `jurusan`, `jumlah_siswa`, `created_at`, `updated_at`) VALUES
(1, '7E', '7', 'AGUS SUCAHYO COKROAMINOTO,S.Pd', NULL, 0, '2025-12-03 08:48:51', '2025-12-03 08:48:51'),
(2, '7F', '7', 'AL\' WAFA SHANDY HERMAWAN, S.Pd', NULL, 0, '2025-12-03 15:33:37', '2025-12-03 15:33:37'),
(3, '7B', '7', 'DAVID ANANIAS WIDARIYONO,S.Sn.MM', NULL, 0, '2025-12-03 17:04:21', '2025-12-03 17:38:34'),
(4, '9A', '9', 'ARINA DEWI MASITHO ,M.Pd', NULL, 0, '2025-12-03 17:10:55', '2025-12-03 17:10:55');

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
(25, '0001_01_01_000000_create_users_table', 1),
(26, '0001_01_01_000001_create_cache_table', 1),
(27, '0001_01_01_000002_create_jobs_table', 1),
(28, '2025_11_18_183914_add_username_to_users_table', 1),
(29, '2025_11_18_184315_add_fields_to_users_table', 1),
(30, '2025_11_18_194133_create_sessions_table', 1),
(31, '2025_11_18_195232_add_guru_fields_to_users_table', 1),
(32, '2025_11_18_195419_create_kelas_table', 1),
(33, '2025_11_18_201831_create_absensis_table', 1),
(34, '2025_11_18_202713_create_jadwals_table', 1),
(35, '2025_11_18_203723_add_kelas_to_absensis_table', 1),
(36, '2025_11_18_204427_rename_nisnip_to_nis_in_users_table', 1),
(37, '2025_12_03_153959_add_guru_fields_to_users_table', 1),
(38, '2025_12_03_154809_add_walikelas_to_kelas_table', 2),
(39, '2025_12_04_000000_add_jadwal_and_guru_to_absensis_table', 3),
(40, '2025_12_04_034004_add_jenis_kelamin_to_users_table', 4);

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
('NFAs4VGiA3Z2sMMTx7hgSSNBerUwnlW2STm6xaG8', 70, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSEd6ZXVXTUVLWjdGTWpqZ1RQNktmS0NWSk5DQTBCUkVDNm1WN2RUaSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC90YXRhdXNhaGEvZGF0YS1rZWxhcyI7czo1OiJyb3V0ZSI7czoyMDoidGF0YXVzYWhhLmRhdGEta2VsYXMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo3MDt9', 1764826399);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `no_telp` varchar(255) DEFAULT NULL,
  `mapel` varchar(255) DEFAULT NULL,
  `kelas_diampu` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `nis` varchar(255) DEFAULT NULL,
  `nip` varchar(255) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `kelas` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `jenis_kelamin`, `email`, `no_telp`, `mapel`, `kelas_diampu`, `foto`, `username`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `nis`, `nip`, `role`, `kelas`) VALUES
(4, 'AGUS SUCAHYO COKROAMINOTO,S.Pd', NULL, 'aguscahyo19@gmail.com', NULL, NULL, NULL, NULL, 'agusc', NULL, '$2y$12$ZsWdYW8gtKOb2XvNzm3XL.LdOAcR7PnHLGzgiuwMO5G39jx.CvruS', NULL, '2025-12-03 08:41:57', '2025-12-03 08:41:57', NULL, NULL, 'guru', NULL),
(5, 'IKA DAMIYANTI,S.Pd', NULL, 'ikada0205@gmail.com', NULL, NULL, NULL, NULL, 'ikad', NULL, '$2y$12$BlpuSskTFnsOw4ZoCxyAqO.7BUw.VTA2QD4u/ZFfbaZ3Y/8etz8l6', NULL, '2025-12-03 08:41:58', '2025-12-03 08:41:58', NULL, NULL, 'guru', NULL),
(6, 'PETRUS HARJONO,SE', NULL, 'petrusharjono71@gmail.com', NULL, NULL, NULL, NULL, 'petrush', NULL, '$2y$12$9bxIQVn3cc7ye3ntaTlRWuEZf8pQiRGSnghsqhldIEPAONyjUpNwW', NULL, '2025-12-03 08:41:58', '2025-12-03 08:41:58', NULL, NULL, 'guru', NULL),
(7, 'NOVI BRETA INDAH.L,S.Pd', NULL, 'novibretagiki2@gmail.com', NULL, NULL, NULL, NULL, 'novii', NULL, '$2y$12$2AqL3Odn/yxJQ9TxsuGzP.OCDmHLD.zfcMaRRv1jH6cot8eFy0LO.', NULL, '2025-12-03 08:41:59', '2025-12-03 08:41:59', NULL, NULL, 'guru', NULL),
(8, 'DRS.AGUS GUNAWAN', NULL, 'agusgunawan1968@gmail.com', NULL, NULL, NULL, NULL, 'drs.agusg', NULL, '$2y$12$bWTmMq6wlfU8gBDvmTMOU.8uPPQWELJ/G8EDjIzkynuLCm4Uj3BZy', NULL, '2025-12-03 08:41:59', '2025-12-03 08:41:59', NULL, NULL, 'guru', NULL),
(9, 'ELVANDARI,S.Pd', NULL, 'ndarielva@gmail.com', NULL, NULL, NULL, NULL, 'elvandari,s.pd', NULL, '$2y$12$pM.zDD12ug.HB9EHBsgQiu63SQnsHcMshDBRHzcaa8LySByMYjy16', NULL, '2025-12-03 08:42:00', '2025-12-03 08:42:00', NULL, NULL, 'guru', NULL),
(10, 'NENY DWI LESTARI ,S.Si', NULL, 'nenymenik@gmail.com', NULL, NULL, NULL, NULL, 'neny,', NULL, '$2y$12$77oYfz.rt122GSFQaK2XZu7SGL9XNwhq9k395.pzyTTLMi5pJY62y', NULL, '2025-12-03 08:42:00', '2025-12-03 08:42:00', NULL, NULL, 'guru', NULL),
(11, 'FAKHRUL MUZAQI,S.Pd', NULL, 'aroelmuzaki@gmail.com', NULL, NULL, NULL, NULL, 'fakhrulm', NULL, '$2y$12$J4QJpWSUReTEyGrHxna.VePKJW872tJ6ZW9IGRzmw/vTJ8PhAhwkO', NULL, '2025-12-03 08:42:01', '2025-12-03 08:42:01', NULL, NULL, 'guru', NULL),
(12, 'SUHERNO SOPATER,S.Miss.M.Th', NULL, 'suhernosoparter@gmail.com', NULL, NULL, NULL, NULL, 'suhernos', NULL, '$2y$12$Q3TKij4PWQ2hu9mLfT8ueuV3VuGj7ygKCz9MkslIQ7eNMv6IBP7oC', NULL, '2025-12-03 08:42:01', '2025-12-03 08:42:01', NULL, NULL, 'guru', NULL),
(13, 'ALBERTUS NUGRAHA DEMON,S.S.Lic.Th', NULL, 'nugrohodemon45@gmail.com', NULL, NULL, NULL, NULL, 'albertusd', NULL, '$2y$12$RgO.c4xEBIDq7aOzXx1XmuJZHePunRYM67q33Wd.0cOi/qyBasnvy', NULL, '2025-12-03 08:42:02', '2025-12-03 08:42:02', NULL, NULL, 'guru', NULL),
(14, 'ARINA DEWI MASITHO ,M.Pd', NULL, 'masithohdewi21@gmail.com', NULL, NULL, NULL, NULL, 'arina,', NULL, '$2y$12$c1LCv.P6ganP/T8M3s7m9.9PDJBstaNUCf3K7GytOosx5zpyCHGee', NULL, '2025-12-03 08:42:03', '2025-12-03 08:42:03', NULL, NULL, 'guru', NULL),
(15, 'ANDI NURMALASARI,S.M', NULL, 'andismpgiki2@gmail.com', NULL, NULL, NULL, NULL, 'andin', NULL, '$2y$12$tOB312eXB/SQEcBQJ8IKm.QHz.kK/NRXKX0CbPGigOetdsVruJXMW', NULL, '2025-12-03 08:42:03', '2025-12-03 08:42:03', NULL, NULL, 'guru', NULL),
(16, 'NINING MAHMUDAH,S.Pd', NULL, 'niningmahmudah183@gmail.com', NULL, NULL, NULL, NULL, 'niningm', NULL, 'nining123', NULL, '2025-12-03 08:42:03', '2025-12-03 08:42:03', NULL, NULL, 'guru', NULL),
(17, 'AL\' WAFA SHANDY HERMAWAN, S.Pd', NULL, 'alwafasandy@gmail.com', NULL, NULL, NULL, NULL, 'al\'s', NULL, '$2y$12$d30UIwPVnu.ZKvPvQTgNp.dX6Zx0TKnIYpcu/78Fcx36d9z0v3X6W', NULL, '2025-12-03 08:42:04', '2025-12-03 08:42:04', NULL, NULL, 'guru', NULL),
(18, 'AMMAR NAUVALINO B,S.Pd', NULL, 'ammaramar203@gmail.com', NULL, NULL, NULL, NULL, 'ammarb', NULL, '$2y$12$gJakVrw4sJf2vMFNopdMu.YhOFgWxv9dMDzHe.hqLSlEgJ5wEkCUK', NULL, '2025-12-03 08:42:04', '2025-12-03 08:42:04', NULL, NULL, 'guru', NULL),
(19, 'ALFAN MAULANA,S.Pd', NULL, 'alfanmaulan993@gmail.com', NULL, NULL, NULL, NULL, 'alfanm', NULL, '$2y$12$05gKJHIMecVtzDRrPL1gFesfLl4.XxsOFSFvRSaUbXxoWaR4JMqbu', NULL, '2025-12-03 08:42:05', '2025-12-03 08:42:05', NULL, NULL, 'guru', NULL),
(20, 'YUGA SENA PRASETYA, S.Pd', NULL, 'prasetyayuga80@gmail.com', NULL, NULL, NULL, NULL, 'yugas', NULL, '$2y$12$QxWt3xgCU36w6pclaS49vuI7elYchrpXgJJBBc9FZBbL65xwKZ/XK', NULL, '2025-12-03 08:42:05', '2025-12-03 08:42:05', NULL, NULL, 'guru', NULL),
(21, 'NABILA NUR AGUSTINA,S.Pd', NULL, 'nabilaagustina450@gmail.com', NULL, NULL, NULL, NULL, 'nabilaa', NULL, '$2y$12$huy9pV2RWaGRTLIivmGYfuSmGUKr1/C5oUgCmbtxcnXKWYEF6ulMy', NULL, '2025-12-03 08:42:05', '2025-12-03 08:42:05', NULL, NULL, 'guru', NULL),
(22, 'ANISA PUTRI AYU,S.Pd', NULL, 'anisaputriananta0@gmail.com', NULL, NULL, NULL, NULL, 'anisaa', NULL, '$2y$12$u8GzjAJT.DB9IC9Rl06OVegT16fyBvOg0BWebQCvmLCKZq7WyM1pe', NULL, '2025-12-03 08:42:06', '2025-12-03 08:42:06', NULL, NULL, 'guru', NULL),
(23, 'DIAH RETNO PALUPI, S.Pd', NULL, 'diahretnopalupi13@gmail.com', NULL, NULL, NULL, NULL, 'diahs', NULL, '$2y$12$FzCIA1rfUCiAyHIby47lCuMdke3r3wJgOVpzwztCplw9UaFKqfN9u', NULL, '2025-12-03 08:42:06', '2025-12-03 08:42:06', NULL, NULL, 'guru', NULL),
(24, 'SYIHABUL KHOIR ,S,Pd', NULL, 'syihabulk@gmail.com', NULL, NULL, NULL, NULL, 'syihabul,', NULL, '$2y$12$sgUKwQzZuNZPnOKOz2A3pu3rVsetK.Vx7ePDmubwDTYWshVEja12y', NULL, '2025-12-03 08:42:07', '2025-12-03 08:42:07', NULL, NULL, 'guru', NULL),
(25, 'FIRST HARDIAN DEOTAMA ,S.Pd', NULL, 'firsthardiandeotama@gmail.com', NULL, NULL, NULL, NULL, 'first,', NULL, '$2y$12$oqPNmVHfauwD/1xrDOo5.enX1k7yMyMh8cWqWDmEyV5mThmMKke8.', NULL, '2025-12-03 08:42:07', '2025-12-03 08:42:07', NULL, NULL, 'guru', NULL),
(26, 'MOCHAMMAD AIDUL PUTRA ARIEF,S.Pd', NULL, 'aidulputra42@gmail.com', NULL, NULL, NULL, NULL, 'mochammada', NULL, '$2y$12$OlRxsZ4Fy86WiPE61DKo6e9toNhaikksU/vGFChIdPk3hm2cnHI9G', NULL, '2025-12-03 08:42:07', '2025-12-03 08:42:07', NULL, NULL, 'guru', NULL),
(27, 'DAVID ANANIAS WIDARIYONO,S.Sn.MM', NULL, 'davidpanjakcak@gmail.com', NULL, NULL, NULL, NULL, 'davidw', NULL, '$2y$12$YSv1.UWOcnbpDY2PKb.97.C0rPGQyT8bddh7t0YjDzjk04PEsn4Cu', NULL, '2025-12-03 08:42:08', '2025-12-03 08:42:08', NULL, NULL, 'guru', NULL),
(28, 'DHEA AHMANDA PUTRI ,S.Pd', NULL, 'newdhea49@gmail.com', NULL, NULL, NULL, NULL, 'dhea,', NULL, '$2y$12$2m0VSTUKDuW9MoJUmcz0r.BKjY/l.l2.RtHK4yZr4TaURGrXzG3lK', NULL, '2025-12-03 08:42:08', '2025-12-03 08:42:08', NULL, NULL, 'guru', NULL),
(29, 'PANDU ARDY SUTANTO,S.Pd', NULL, 'pandu16.pa@gmail.com', NULL, NULL, NULL, NULL, 'pandus', NULL, '$2y$12$L5cVGwA1H585HgtCCKeps.ERFY1gyvQz.SSOU6oEm7NmCAOXCMQIu', NULL, '2025-12-03 08:42:09', '2025-12-03 08:42:09', NULL, NULL, 'guru', NULL),
(30, 'ANDREAS KURNIAWAN  P .S.Psi', NULL, 'andreasputra003@gmail.com', NULL, NULL, NULL, NULL, 'andreas.', NULL, '$2y$12$ctTVJEnYa0H1isu9G9fxlec9EkM4XSSL4USJke5bhjzBqSBtyOu5C', NULL, '2025-12-03 08:42:09', '2025-12-03 08:42:09', NULL, NULL, 'guru', NULL),
(32, 'Adena Chairunisa', NULL, '6483@siswa.giki.local', NULL, NULL, NULL, NULL, '6483', NULL, '$2y$12$9BFhrZyWXbVA5olkFlvzi.I1GEfro30FVVpxDQy5RAAHGPWmvVaBG', NULL, '2025-12-03 08:58:09', '2025-12-03 08:58:09', '6483', NULL, 'siswa', '7E'),
(33, 'Airlangga Prawira Sakti', NULL, '6484@siswa.giki.local', NULL, NULL, NULL, NULL, '6484', NULL, '$2y$12$6Tkro6ZGJ7Ja9KqT9AkV6eCxvoq9iuEVOIdwFyA57FeT8EhNJ2lAK', NULL, '2025-12-03 08:58:09', '2025-12-03 08:58:09', '6484', NULL, 'siswa', '7E'),
(34, 'Amoret Claresta Princess Prianto', NULL, '6485@siswa.giki.local', NULL, NULL, NULL, NULL, '6485', NULL, '$2y$12$Ym7fwtlBl8CgS8ED8hSvZe/xvUfxxQNaR5jmJw8gbQInQoXemZfjO', NULL, '2025-12-03 08:58:10', '2025-12-03 08:58:10', '6485', NULL, 'siswa', '7E'),
(35, 'Atha laudza Azzarah', NULL, '6486@siswa.giki.local', NULL, NULL, NULL, NULL, '6486', NULL, '$2y$12$rBij4hPGN7OjutWq0tK2b.Lxk45//6OPVUG8YEFoaANhh.HvXBnB6', NULL, '2025-12-03 08:58:10', '2025-12-03 08:58:10', '6486', NULL, 'siswa', '7E'),
(36, 'Azarah Meira Rahmziah', NULL, '6487@siswa.giki.local', NULL, NULL, NULL, NULL, '6487', NULL, '$2y$12$ud0qr8j5hKs3NToULNp2nuw.5WSwgmdLyhzmSFwjvyU5JcPPckIYG', NULL, '2025-12-03 08:58:10', '2025-12-03 08:58:10', '6487', NULL, 'siswa', '7E'),
(37, 'Bagus Hendro Syahputra', NULL, '6488@siswa.giki.local', NULL, NULL, NULL, NULL, '6488', NULL, '$2y$12$63i9J8bhYOZOahPuyq4LD.WLoKztX2fRILr40PD.O0rkXX/4Wv8bm', NULL, '2025-12-03 08:58:11', '2025-12-03 08:58:11', '6488', NULL, 'siswa', '7E'),
(38, 'Bayu Muhammad Ramadhan', NULL, '6489@siswa.giki.local', NULL, NULL, NULL, NULL, '6489', NULL, '$2y$12$BYKNzKnkuPj3jCDWJMGqYu2yXHjWZNaChU5s2HNh96got7/Raeiyu', NULL, '2025-12-03 08:58:11', '2025-12-03 08:58:11', '6489', NULL, 'siswa', '7E'),
(39, 'Carolus Alvaro Chrisna Putra', NULL, '6490@siswa.giki.local', NULL, NULL, NULL, NULL, '6490', NULL, '$2y$12$nova4OQshLQ4Ob5zh6Ip9uD2vLKoqHJda5JMg6OWApejqDGn8eYEq', NULL, '2025-12-03 08:58:12', '2025-12-03 08:58:12', '6490', NULL, 'siswa', '7E'),
(40, 'Dealova Chelsea Febrianca', NULL, '6491@siswa.giki.local', NULL, NULL, NULL, NULL, '6491', NULL, '$2y$12$6h7qL4hnhgWDTRDfac/OquKlKil93/jeE9Zd0iOIX5/x7v8cl5r9.', NULL, '2025-12-03 08:58:12', '2025-12-03 08:58:12', '6491', NULL, 'siswa', '7E'),
(41, 'Fraz Auriyan', NULL, '6492@siswa.giki.local', NULL, NULL, NULL, NULL, '6492', NULL, '$2y$12$WctJbENDyQ6o4cYuDNz0oeYp6/nLmlPsVspTFQE2afM4TPYXVO6.q', NULL, '2025-12-03 08:58:12', '2025-12-03 08:58:12', '6492', NULL, 'siswa', '7E'),
(42, 'Gabriel Feebriyo Brusen', NULL, '6493@siswa.giki.local', NULL, NULL, NULL, NULL, '6493', NULL, '$2y$12$ZtQpxva4YVnMR2x.y6jl5ucyGKW6cfJf5j9WsPyD32CSxOHZ/UYLi', NULL, '2025-12-03 08:58:13', '2025-12-03 08:58:13', '6493', NULL, 'siswa', '7E'),
(43, 'Galuh Fajri Ramadhan', NULL, '6494@siswa.giki.local', NULL, NULL, NULL, NULL, '6494', NULL, '$2y$12$XHXlKyWsIfRfdnUa1Yfhtu5Axc.6lvx1jYuqY6vj02nWhkLS2JUA.', NULL, '2025-12-03 08:58:13', '2025-12-03 08:58:13', '6494', NULL, 'siswa', '7E'),
(44, 'Gilang Ardiansyah Ramadhan', NULL, '6495@siswa.giki.local', NULL, NULL, NULL, NULL, '6495', NULL, '$2y$12$KbLmRCzPXEBwt0ywMVjc1.KSRivoj.UgoqsX4GzwGuE6P4LSOdBWO', NULL, '2025-12-03 08:58:14', '2025-12-03 08:58:14', '6495', NULL, 'siswa', '7E'),
(45, 'Ilham Maulana Suprapto', NULL, '6496@siswa.giki.local', NULL, NULL, NULL, NULL, '6496', NULL, '$2y$12$e18.lH26JKrT9Os/jB.N3.Y.TYssJZDwDBpcl3ly5ze3etlOpwSpK', NULL, '2025-12-03 08:58:14', '2025-12-03 08:58:14', '6496', NULL, 'siswa', '7E'),
(46, 'Keisya Artha Mevia', NULL, '6497@siswa.giki.local', NULL, NULL, NULL, NULL, '6497', NULL, '$2y$12$HZgRbujqDNh/iii50ycnfenHoCX23XWUc7nXdJ504iyyvpon.NipC', NULL, '2025-12-03 08:58:14', '2025-12-03 08:58:14', '6497', NULL, 'siswa', '7E'),
(47, 'Krisna Putra Reonardo', NULL, '6498@siswa.giki.local', NULL, NULL, NULL, NULL, '6498', NULL, '$2y$12$K5SYzVegxBlB3THso5XUXuDsUg3TPEuy3KPUI1VJhpVBYE6nX4KwG', NULL, '2025-12-03 08:58:15', '2025-12-03 08:58:15', '6498', NULL, 'siswa', '7E'),
(48, 'Kukuh Triatmojo', NULL, '6499@siswa.giki.local', NULL, NULL, NULL, NULL, '6499', NULL, '$2y$12$UIPGDlzZRSQXjFvPpbm25eNI.8nJr.lepj70XLNiUeAlbWOOsiJpK', NULL, '2025-12-03 08:58:15', '2025-12-03 08:58:15', '6499', NULL, 'siswa', '7E'),
(49, 'Lingga Bian Arteta', NULL, '6500@siswa.giki.local', NULL, NULL, NULL, NULL, '6500', NULL, '$2y$12$/I31bCxol3upBq6PqnHhoeFruQhS0RsJU0DTHmQsN9DWXzy.I0lQu', NULL, '2025-12-03 08:58:16', '2025-12-03 08:58:16', '6500', NULL, 'siswa', '7E'),
(50, 'Livia Cleosae', NULL, '6501@siswa.giki.local', NULL, NULL, NULL, NULL, '6501', NULL, '$2y$12$DorszK.ZUYcA9CnY1Bj/kuAOapTink3scCdNXqgy8mRjB2jWpFzLK', NULL, '2025-12-03 08:58:16', '2025-12-03 08:58:16', '6501', NULL, 'siswa', '7E'),
(51, 'Marsyah Rikishe Lanoha', NULL, '6502@siswa.giki.local', NULL, NULL, NULL, NULL, '6502', NULL, '$2y$12$vuyoIt1IjoI1s.aQi6ehKODJdpojaWiQ6zKRx8dEbSEcIEVFZIEsi', NULL, '2025-12-03 08:58:16', '2025-12-03 08:58:16', '6502', NULL, 'siswa', '7E'),
(52, 'Meysa Zahira Putri', NULL, '6503@siswa.giki.local', NULL, NULL, NULL, NULL, '6503', NULL, '$2y$12$EVhlJU9jNlG0dvzsQVzMuevVKMrWOSO5fsNMgCsN/9qpv8dIoYZj.', NULL, '2025-12-03 08:58:17', '2025-12-03 08:58:17', '6503', NULL, 'siswa', '7E'),
(53, 'Michelle Angel Precilya', NULL, '6504@siswa.giki.local', NULL, NULL, NULL, NULL, '6504', NULL, '$2y$12$zO8xkNMvCvsawbD8SDbU9.sq.RXl6Z5o33smopeTyIAWCiZg76UeK', NULL, '2025-12-03 08:58:17', '2025-12-03 08:58:17', '6504', NULL, 'siswa', '7E'),
(54, 'Moch. Rizki Zaylani', NULL, '6505@siswa.giki.local', NULL, NULL, NULL, NULL, '6505', NULL, '$2y$12$IUNuF1z.z30LsXvFFS71suR.FpD/AclC72YoIfI3SmvzWRXVk3YYa', NULL, '2025-12-03 08:58:17', '2025-12-03 08:58:17', '6505', NULL, 'siswa', '7E'),
(55, 'Moch. Tirta Rizky Aditya', NULL, '6506@siswa.giki.local', NULL, NULL, NULL, NULL, '6506', NULL, '$2y$12$t50/yTOsR0rBfB2SbwEpv.Clc5Ts4Vhv6.V0aEa2W2lFtKTdccJku', NULL, '2025-12-03 08:58:18', '2025-12-03 08:58:18', '6506', NULL, 'siswa', '7E'),
(56, 'Muammar Barra Alimuddin', NULL, '6507@siswa.giki.local', NULL, NULL, NULL, NULL, '6507', NULL, '$2y$12$ZXhA/XH6A.f7Ce47NjFJve.AUTYOkpzDpLcZ0yqO8IumS4Z2qY6lu', NULL, '2025-12-03 08:58:18', '2025-12-03 08:58:18', '6507', NULL, 'siswa', '7E'),
(57, 'Nadyla Siti Zahra Haryani', NULL, '6508@siswa.giki.local', NULL, NULL, NULL, NULL, '6508', NULL, '$2y$12$mfKlFrEwq9VltsO.eWVugekskfnjPn5WN6FYNVRN4Adido9jh8GXu', NULL, '2025-12-03 08:58:19', '2025-12-03 08:58:19', '6508', NULL, 'siswa', '7E'),
(58, 'Reyhan Putra Oktafianto', NULL, '6509@siswa.giki.local', NULL, NULL, NULL, NULL, '6509', NULL, '$2y$12$3.yzrUgFqoVhLsAtibM1F.Mbmv9Aec4UijCho9H/7tM4kPbotdh1C', NULL, '2025-12-03 08:58:19', '2025-12-03 08:58:19', '6509', NULL, 'siswa', '7E'),
(59, 'Septa Tri Valentino', NULL, '6510@siswa.giki.local', NULL, NULL, NULL, NULL, '6510', NULL, '$2y$12$c2fbFItyzzZDjUJbtikZCeyuKyUlPmvedwUkb/0SoMP/S4bo5r3FG', NULL, '2025-12-03 08:58:19', '2025-12-03 08:58:19', '6510', NULL, 'siswa', '7E'),
(60, 'Shania Donita Purwandani', NULL, '6511@siswa.giki.local', NULL, NULL, NULL, NULL, '6511', NULL, '$2y$12$3RXRibJ4Q5RgnCg4w7RqvuOhQZr16urlea262QGCDC5VrFiSpoh3W', NULL, '2025-12-03 08:58:20', '2025-12-03 08:58:20', '6511', NULL, 'siswa', '7E'),
(61, 'Sheila Nur Afifah', NULL, '6512@siswa.giki.local', NULL, NULL, NULL, NULL, '6512', NULL, '$2y$12$DiKu.3B2hGXTJ3XqPSDpLugelwM2FUJqR9Rscrx8fm9ijfxw.vyBO', NULL, '2025-12-03 08:58:20', '2025-12-03 08:58:20', '6512', NULL, 'siswa', '7E'),
(62, 'Sherinna Putri Sugiarto', NULL, '6513@siswa.giki.local', NULL, NULL, NULL, NULL, '6513', NULL, '$2y$12$JIhhNybdes6cG5H0yFVEyeD4dH6PFwXJXvfvqp22yaMckmjexkOvS', NULL, '2025-12-03 08:58:21', '2025-12-03 08:58:21', '6513', NULL, 'siswa', '7E'),
(63, 'Zahira Yulia Ramadhani', NULL, '6514@siswa.giki.local', NULL, NULL, NULL, NULL, '6514', NULL, '$2y$12$TVEmTmh97wF6mjKW5zVM8.cioRqAcHFk9pY5qb6xaDmgljpaZEYVy', NULL, '2025-12-03 08:58:21', '2025-12-03 08:58:21', '6514', NULL, 'siswa', '7E'),
(64, 'Zhalwana Kinanti Putri Setiawan', NULL, '6515@siswa.giki.local', NULL, NULL, NULL, NULL, '6515', NULL, '$2y$12$hSd.7HpjpW/4oujr/1qIkO6.4BT/.kOSu58X3ru4bGRlsqiyJix6y', NULL, '2025-12-03 08:58:22', '2025-12-03 08:58:22', '6515', NULL, 'siswa', '7E'),
(70, 'Tata Usaha Contoh', NULL, 'tatausahacontoh@gmail.com', NULL, NULL, NULL, NULL, 'Tata Usaha', NULL, '$2y$12$VIhUEotHWeN.0BzXs5njEu4J2uJLbHLzFeMHAeQeWYuIZ1uxohemK', NULL, '2025-12-03 15:08:57', '2025-12-03 15:08:57', '12345678', NULL, 'tatausaha', 'Pegawai 1'),
(72, 'Guru Contoh', NULL, 'gurucontoh@gmail.com', NULL, NULL, NULL, NULL, 'Guru', NULL, '$2y$12$C72/wEGXHaiXG9NB0LkRC.Eb56MTR/FrMl.LxkWoG/fbv6r2N0xLG', NULL, '2025-12-03 15:34:40', '2025-12-03 15:34:40', '87654321', NULL, 'guru', 'Matematika'),
(73, 'Siswa Contoh', NULL, 'siswacontoh@gmail.com', NULL, NULL, NULL, NULL, 'Siswa', NULL, '$2y$12$KnlrXl4txBRX2yOKry7ieOwvaGPXa1.AxBYPQEDI.yITw6DDCjb..', NULL, '2025-12-03 15:49:51', '2025-12-03 15:49:51', '124578', NULL, 'siswa', '7E'),
(78, 'testing', NULL, '123456@siswa.giki.local', NULL, NULL, NULL, NULL, '123456', NULL, '$2y$12$sglDPelmPIaTocSLSxhb4eAEtsfRra.oK3av.SGch8gT2diCQAjdK', NULL, '2025-12-03 18:00:38', '2025-12-03 18:00:38', '123456', NULL, 'siswa', '7E'),
(79, 'Arsy Shifa Nuril Azmi', NULL, '1204220005@siswa.giki.local', NULL, NULL, NULL, NULL, '1204220005', NULL, '$2y$12$GirrkazM5MhZULXUHGfaV.JnvcZw6YbF6Y2co3AdIVnAANIJSO766', NULL, '2025-12-03 19:10:16', '2025-12-03 19:10:16', '1204220005', NULL, 'siswa', '7B');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensis`
--
ALTER TABLE `absensis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `absensis_siswa_id_foreign` (`siswa_id`),
  ADD KEY `absensis_jadwal_id_foreign` (`jadwal_id`),
  ADD KEY `absensis_guru_id_foreign` (`guru_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jadwals`
--
ALTER TABLE `jadwals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jadwals_guru_id_foreign` (`guru_id`),
  ADD KEY `jadwals_kelas_id_foreign` (`kelas_id`);

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
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_nip_unique` (`nip`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensis`
--
ALTER TABLE `absensis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwals`
--
ALTER TABLE `jadwals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensis`
--
ALTER TABLE `absensis`
  ADD CONSTRAINT `absensis_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `absensis_jadwal_id_foreign` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwals` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `absensis_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jadwals`
--
ALTER TABLE `jadwals`
  ADD CONSTRAINT `jadwals_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwals_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
