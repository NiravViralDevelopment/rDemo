-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 16, 2026 at 12:48 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `r_demo`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `age` tinyint UNSIGNED DEFAULT NULL,
  `salary` decimal(12,2) DEFAULT NULL,
  `gender` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `mobile`, `email`, `city`, `state`, `age`, `salary`, `gender`, `status`, `created_at`) VALUES
(1, 'Raj Sharma', '9782954150', 'raj9447@gmail.com', 'Mumbai', 'Maharashtra', 20, 8591.00, 'Female', 'Active', '2026-04-09 07:08:13'),
(2, 'John Smith', '9690888158', 'john9691@gmail.com', 'Lucknow', 'Rajasthan', 23, 32374.00, 'Male', 'Inactive', '2026-04-09 07:08:13'),
(3, 'John Singh', '9169429584', 'john1527@gmail.com', 'Indore', 'Gujarat', 26, 15336.00, 'Male', 'Active', '2026-04-09 07:08:13');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
CREATE TABLE IF NOT EXISTS `countries` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `country_name` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_05_10_144003_create_permission_tables', 1),
(5, '2024_05_10_144032_create_products_table', 1),
(6, '2026_03_30_120000_create_raw_data_imports_table', 1),
(7, '2026_03_30_130000_create_raw_data_files_table', 1),
(8, '2026_03_30_140000_add_raw_data_file_id_to_raw_data_imports_table', 1),
(9, '2026_03_30_140100_create_working_data_table', 1),
(10, '2026_03_30_150000_create_contacts_table', 1),
(11, '2026_03_30_150100_add_show_password_and_country_id_to_users_table', 1),
(12, '2026_04_02_120000_add_stored_paths_to_raw_data_files_table', 1),
(13, '2026_04_06_120000_add_month_year_to_raw_data_files_table', 1),
(14, '2026_04_06_120000_add_receiver_gstin_to_raw_data_imports_and_working_data', 1),
(15, '2026_04_06_130000_add_file_type_to_raw_data_files_table', 1),
(16, '2026_04_06_140000_add_workflow_timestamps_to_raw_data_files_table', 1),
(17, '2026_04_06_160000_add_status_to_raw_data_files_table', 1),
(18, '2026_04_06_170000_add_source_type_to_raw_data_files_table', 1),
(19, '2026_04_06_180000_add_receiver_gstin_to_raw_data_imports_and_working_data_tables', 1),
(20, '2026_04_09_121937_create_properties_table', 1),
(21, '2026_04_09_121944_create_bookings_table', 1),
(22, '2026_04_09_122554_create_projects_table', 1),
(23, '2026_04_09_122608_add_project_id_to_users_properties_and_bookings_tables', 1),
(24, '2026_04_11_100000_add_raw_data_import_id_to_working_data_table', 1),
(25, '2026_04_09_124119_create_countries_table', 2),
(26, '2026_04_09_141920_create_partners_table', 3),
(27, '2026_04_09_142503_create_partner_payments_table', 4),
(28, '2026_04_09_144617_create_vendors_table', 5),
(29, '2026_04_09_145329_create_vendor_transactions_table', 6),
(30, '2026_04_09_145944_create_vendor_materials_table', 7),
(31, '2026_04_14_000001_add_customer_details_to_bookings_table', 8),
(32, '2026_04_14_000002_create_booking_payments_table', 8),
(33, '2026_04_14_000003_create_banks_table', 9),
(34, '2026_04_14_000004_add_opening_balance_to_banks_table', 10),
(35, '2026_04_14_000005_create_bank_transactions_table', 10),
(36, '2026_04_14_000006_add_gst_fields_to_bank_transactions_table', 11),
(37, '2026_04_16_000001_remove_housing_partner_vendor_booking_bank_tables', 12);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 5),
(2, 'App\\Models\\User', 6);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'role-list', 'web', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(2, 'role-create', 'web', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(3, 'role-edit', 'web', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(4, 'role-delete', 'web', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(5, 'product-list', 'web', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(6, 'product-create', 'web', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(7, 'product-edit', 'web', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(8, 'product-delete', 'web', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(9, 'Order-Item', 'web', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(10, 'Manage Order', 'web', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(11, 'user-list', 'web', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(12, 'Manage Order Create', 'web', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(13, 'Manage Order Delete', 'web', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(14, 'Manage Order Edit', 'web', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(15, 'Manage Order status', 'web', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(16, 'Dashboard', 'web', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(17, 'Manage Countries', 'web', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(18, 'Manage Cities', 'web', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(19, 'Demo Excel', 'web', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(20, 'Import', 'web', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(21, 'permission-list', 'web', '2026-04-16 12:42:48', '2026-04-16 12:42:48'),
(22, 'permission-create', 'web', '2026-04-16 12:42:48', '2026-04-16 12:42:48'),
(23, 'permission-edit', 'web', '2026-04-16 12:42:48', '2026-04-16 12:42:48'),
(24, 'permission-delete', 'web', '2026-04-16 12:42:48', '2026-04-16 12:42:48');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `projects_code_unique` (`code`),
  KEY `projects_status_index` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `name`, `code`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Harihar', 'HARIHAR', 'active', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(2, 'Harmony', 'HARMONY', 'active', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(3, 'Swastic', 'SWASTIC', 'active', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(4, 'manokamna', 'MANOKAMNA', 'active', '2026-04-09 07:28:33', '2026-04-09 07:28:33'),
(5, 'swastik', 'SWASTIK', 'active', '2026-04-14 10:38:49', '2026-04-14 10:38:49');

-- --------------------------------------------------------

--
-- Table structure for table `raw_data_files`
--

DROP TABLE IF EXISTS `raw_data_files`;
CREATE TABLE IF NOT EXISTS `raw_data_files` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `stored_upload` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stored_derived_csv` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `month` tinyint UNSIGNED DEFAULT NULL,
  `year` smallint UNSIGNED DEFAULT NULL,
  `file_type` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source_type` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `rules_applied_at` timestamp NULL DEFAULT NULL,
  `status` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'imported',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `raw_data_imports`
--

DROP TABLE IF EXISTS `raw_data_imports`;
CREATE TABLE IF NOT EXISTS `raw_data_imports` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `raw_data_file_id` bigint UNSIGNED DEFAULT NULL,
  `source_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seller_gstin` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `invoice_number` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `invoice_date` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `transaction_type` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `order_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `shipment_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `shipment_date` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `order_date` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `shipment_item_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `quantity` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `item_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `asin` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `hsn_sac` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sku` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `product_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `product_tax_code` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `bill_from_city` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `bill_from_state` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `bill_from_country` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `bill_from_postal_code` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ship_from_city` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ship_from_state` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ship_from_country` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ship_from_postal_code` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ship_to_city` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ship_to_state` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ship_to_country` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ship_to_postal_code` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `invoice_amount` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tax_exclusive_gross` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `total_tax_amount` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cgst_rate` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sgst_rate` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `utgst_rate` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `igst_rate` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `compensatory_cess_rate` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `principal_amount` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `principal_amount_basis` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cgst_tax` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sgst_tax` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `utgst_tax` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `igst_tax` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `compensatory_cess_tax` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `shipping_amount` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `shipping_amount_basis` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `shipping_cgst_tax` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `shipping_sgst_tax` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `shipping_utgst_tax` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `shipping_igst_tax` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `shipping_cess_tax` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `gift_wrap_amount` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `gift_wrap_amount_basis` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `gift_wrap_cgst_tax` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `gift_wrap_sgst_tax` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `gift_wrap_utgst_tax` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `gift_wrap_igst_tax` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `gift_wrap_compensatory_cess_tax` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `item_promo_discount` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `item_promo_discount_basis` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `item_promo_tax` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `shipping_promo_discount` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `shipping_promo_discount_basis` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `shipping_promo_tax` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `gift_wrap_promo_discount` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `gift_wrap_promo_discount_basis` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `gift_wrap_promo_tax` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tcs_cgst_rate` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tcs_cgst_amount` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tcs_sgst_rate` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tcs_sgst_amount` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tcs_utgst_rate` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tcs_utgst_amount` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tcs_igst_rate` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tcs_igst_amount` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `warehouse_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `fulfillment_channel` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payment_method_code` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `bill_to_city` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `bill_to_state` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `bill_to_country` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `bill_to_postalcode` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `customer_bill_to_gstid` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `customer_ship_to_gstid` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `receiver_gstin` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `buyer_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `credit_note_no` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `credit_note_date` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `irn_number` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `irn_filing_status` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `irn_date` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `irn_error_code` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `raw_data_imports_raw_data_file_id_index` (`raw_data_file_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'web', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(2, 'Project User', 'web', '2026-04-09 07:08:13', '2026-04-09 07:08:13');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('cvhQPddEThx6zTCn5ydvYe71R4q8U7gLkgmvgaDK', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoicFlpbkI2eWFrWktZZXI5NTlmeDMyTmZtMEVma2xHNjQ3TTJ6Z2w1byI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC91c2VycyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czo0OiJhdXRoIjthOjE6e3M6MjE6InBhc3N3b3JkX2NvbmZpcm1lZF9hdCI7aToxNzc2MzQyMzEzO319', 1776343117);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `show_password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` bigint UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_project_id_foreign` (`project_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `project_id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `show_password`, `country_id`) VALUES
(1, NULL, 'Hardik Savani', 'admin@gmail.com', NULL, '$2y$12$8ZOXo2l4Ivlw6VLbfprTSezdyTXof8026mzGW..vPLD/yREwXwPV6', NULL, '2026-04-09 07:08:13', '2026-04-09 07:08:13', NULL, NULL),
(2, 1, 'Harihar Manager', 'harihar.manager@gmail.com', NULL, '$2y$12$YrBAn84RfheQ910bw.oqVem6UloL6Kh2/uUKLL6K8ySsMw4fZBe9a', NULL, '2026-04-09 07:08:14', '2026-04-09 07:08:14', NULL, NULL),
(3, 2, 'Harmony Manager', 'harmony.manager@gmail.com', NULL, '$2y$12$Lso/DecOR4NNFMiYnOvbi.Ia.QXcUP2TSf/GD4eqkuGg1jqchuM76', NULL, '2026-04-09 07:08:15', '2026-04-09 07:08:15', NULL, NULL),
(4, 3, 'Swastic Manager', 'swastic.manager@gmail.com', NULL, '$2y$12$iNIJGA.O9IBZfl/dz//iXud52O6ELpbFOUVZj6OmnmBidKC5Ha3iC', NULL, '2026-04-09 07:08:15', '2026-04-09 07:08:15', NULL, NULL),
(5, 4, 'manokamna', 'manokamna@gmail.com', NULL, '$2y$12$PmbaCSn1sAKz/vdvwmQ3L.Ri.aJ5NHaIWhl8RijrI3AT0neAqFnzO', NULL, '2026-04-09 07:28:33', '2026-04-09 07:28:33', '123456', NULL),
(6, 5, 'swastik', 'swastik@gmail.com', NULL, '$2y$12$tgdfIhUDd3p8A14mykHmi.lWV8yMpDPbaFSyH2N7AB/wY/0hSmbu.', NULL, '2026-04-14 10:38:50', '2026-04-14 10:38:50', '123456', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `working_data`
--

DROP TABLE IF EXISTS `working_data`;
CREATE TABLE IF NOT EXISTS `working_data` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `b2b_b2c` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seller_gstin` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ship_from_state` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `customer_bill_to_gstid` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `buyer_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_to_city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_to_state` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `for_sap` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipment_date` date DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `quantity` int UNSIGNED DEFAULT NULL,
  `item_description` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hsn_sac` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sku` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_from_city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_from_state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_from_country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_from_postal_code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ship_from_city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ship_from_country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ship_from_postal_code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ship_to_city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ship_to_state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ship_to_country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ship_to_postal_code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_amount` decimal(15,2) DEFAULT NULL,
  `tax_exclusive_gross_taxable_value` decimal(15,2) DEFAULT NULL,
  `total_tax_amount` decimal(15,2) DEFAULT NULL,
  `gst_rate` decimal(8,2) DEFAULT NULL,
  `cgst` decimal(15,2) DEFAULT NULL,
  `sgst` decimal(15,2) DEFAULT NULL,
  `igst` decimal(15,2) DEFAULT NULL,
  `total_gst` decimal(15,2) DEFAULT NULL,
  `compensatory_cess_tax` decimal(15,2) DEFAULT NULL,
  `shipping_amount` decimal(15,2) DEFAULT NULL,
  `shipping_amount_basis` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_cgst_tax` decimal(15,2) DEFAULT NULL,
  `shipping_sgst_tax` decimal(15,2) DEFAULT NULL,
  `shipping_utgst_tax` decimal(15,2) DEFAULT NULL,
  `shipping_igst_tax` decimal(15,2) DEFAULT NULL,
  `shipping_cess_tax` decimal(15,2) DEFAULT NULL,
  `gift_wrap_amount` decimal(15,2) DEFAULT NULL,
  `gift_wrap_amount_basis` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gift_wrap_cgst_tax` decimal(15,2) DEFAULT NULL,
  `gift_wrap_sgst_tax` decimal(15,2) DEFAULT NULL,
  `gift_wrap_utgst_tax` decimal(15,2) DEFAULT NULL,
  `gift_wrap_igst_tax` decimal(15,2) DEFAULT NULL,
  `gift_wrap_compensatory_cess_tax` decimal(15,2) DEFAULT NULL,
  `item_promo_discount` decimal(15,2) DEFAULT NULL,
  `item_promo_discount_basis` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_promo_tax` decimal(15,2) DEFAULT NULL,
  `shipping_promo_discount` decimal(15,2) DEFAULT NULL,
  `shipping_promo_discount_basis` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_promo_tax` decimal(15,2) DEFAULT NULL,
  `gift_wrap_promo_discount` decimal(15,2) DEFAULT NULL,
  `gift_wrap_promo_discount_basis` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gift_wrap_promo_tax` decimal(15,2) DEFAULT NULL,
  `tcs_cgst_rate` decimal(8,2) DEFAULT NULL,
  `tcs_cgst_amount` decimal(15,2) DEFAULT NULL,
  `tcs_sgst_rate` decimal(8,2) DEFAULT NULL,
  `tcs_sgst_amount` decimal(15,2) DEFAULT NULL,
  `tcs_utgst_rate` decimal(8,2) DEFAULT NULL,
  `tcs_utgst_amount` decimal(15,2) DEFAULT NULL,
  `tcs_igst_rate` decimal(8,2) DEFAULT NULL,
  `tcs_igst_amount` decimal(15,2) DEFAULT NULL,
  `warehouse_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fulfillment_channel` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method_code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_to_country` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_to_postalcode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_ship_to_gstid` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiver_gstin` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `credit_note_no` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `credit_note_date` date DEFAULT NULL,
  `irn_number` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `irn_filing_status` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `irn_date` date DEFAULT NULL,
  `irn_error_code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `raw_data_file_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `raw_data_import_id` bigint UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `working_data_raw_data_import_id_index` (`raw_data_import_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
