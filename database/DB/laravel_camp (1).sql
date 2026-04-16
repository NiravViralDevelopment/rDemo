-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 14, 2026 at 12:38 PM
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
-- Database: `laravel_camp`
--

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

DROP TABLE IF EXISTS `banks`;
CREATE TABLE IF NOT EXISTS `banks` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `bank_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ifsc_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_holder_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `opening_balance` decimal(12,2) NOT NULL DEFAULT '0.00',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banks`
--

INSERT INTO `banks` (`id`, `bank_name`, `ifsc_code`, `branch_name`, `account_number`, `account_holder_name`, `opening_balance`, `description`, `created_at`, `updated_at`) VALUES
(1, 'HDFC', '324234', 'kalol', '23423423443', 'ramesh bhai', 0.00, NULL, '2026-04-14 12:26:13', '2026-04-14 12:26:13');

-- --------------------------------------------------------

--
-- Table structure for table `bank_transactions`
--

DROP TABLE IF EXISTS `bank_transactions`;
CREATE TABLE IF NOT EXISTS `bank_transactions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `bank_id` bigint UNSIGNED NOT NULL,
  `type` enum('credit','debit') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `transaction_date` date NOT NULL,
  `gst_number` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gst_percent` decimal(5,2) DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bank_transactions_bank_id_foreign` (`bank_id`),
  KEY `bank_transactions_type_index` (`type`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bank_transactions`
--

INSERT INTO `bank_transactions` (`id`, `bank_id`, `type`, `amount`, `transaction_date`, `gst_number`, `gst_percent`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'credit', 5000.00, '2026-04-14', NULL, NULL, NULL, '2026-04-14 12:30:52', '2026-04-14 12:30:52'),
(2, 1, 'debit', 2999.99, '2026-04-14', NULL, NULL, NULL, '2026-04-14 12:31:00', '2026-04-14 12:31:00'),
(3, 1, 'credit', 10000.00, '2026-04-14', NULL, NULL, NULL, '2026-04-14 12:31:15', '2026-04-14 12:31:15'),
(4, 1, 'credit', 7000.00, '2026-04-14', NULL, NULL, NULL, '2026-04-14 12:31:39', '2026-04-14 12:31:39'),
(5, 1, 'credit', 5000.00, '2026-04-14', NULL, 5.00, NULL, '2026-04-14 12:37:22', '2026-04-14 12:37:22');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` bigint UNSIGNED DEFAULT NULL,
  `property_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `customer_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `status` enum('pending','confirmed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'confirmed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bookings_property_id_foreign` (`property_id`),
  KEY `bookings_user_id_foreign` (`user_id`),
  KEY `bookings_status_index` (`status`),
  KEY `bookings_project_id_foreign` (`project_id`)
) ENGINE=MyISAM AUTO_INCREMENT=124 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `project_id`, `property_id`, `user_id`, `customer_name`, `full_name`, `customer_email`, `customer_phone`, `description`, `start_date`, `end_date`, `total_amount`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 165, NULL, 'Daniela Runolfsdottir', NULL, NULL, '1-754-890-0611', NULL, '2026-04-20', '2026-04-27', 38530.03, 'pending', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(2, 2, 212, NULL, 'Miss Roma Turcotte', NULL, NULL, '+1 (917) 707-3459', NULL, '2026-03-13', '2026-03-20', 57381.31, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(3, 3, 225, NULL, 'Dwight Bechtelar', NULL, NULL, '1-564-243-3534', NULL, '2026-03-26', '2026-04-03', 18914.72, 'pending', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(4, 1, 35, NULL, 'Dr. Alessandro Dach Sr.', NULL, NULL, '+1 (212) 620-4290', NULL, '2026-03-02', '2026-03-12', 60940.60, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(5, 1, 145, NULL, 'Elvis Fay Jr.', NULL, NULL, '+16157458987', NULL, '2026-03-12', '2026-03-22', 40708.10, 'cancelled', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(6, 1, 41, NULL, 'Orlando Cronin', NULL, NULL, '1-260-315-1507', NULL, '2026-03-15', '2026-03-25', 38864.30, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(7, 1, 88, NULL, 'Mr. Cedrick Fay DDS', NULL, NULL, '+1-865-928-5948', NULL, '2026-02-24', '2026-03-04', 12494.72, 'cancelled', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(8, 1, 53, NULL, 'Missouri Marvin', NULL, NULL, '(360) 346-5014', NULL, '2026-03-17', '2026-03-22', 22130.45, 'cancelled', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(9, 1, 95, NULL, 'Mr. Andrew Koch', NULL, NULL, '1-347-577-3416', NULL, '2026-03-19', '2026-03-27', 21736.64, 'pending', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(10, 2, 153, NULL, 'Dr. Samir Weimann DDS', NULL, NULL, '+15744595910', NULL, '2026-03-22', '2026-03-27', 14833.65, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(11, 2, 148, NULL, 'Phoebe Auer', NULL, NULL, '+1 (617) 412-9692', NULL, '2026-04-01', '2026-04-04', 7757.58, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(12, 3, 234, NULL, 'Prof. Oscar Bailey', NULL, NULL, '1-360-950-5817', NULL, '2026-03-26', '2026-03-31', 24011.85, 'pending', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(13, 1, 73, NULL, 'Katelynn Legros', NULL, NULL, '+1.847.495.1112', NULL, '2026-04-11', '2026-04-13', 3628.72, 'cancelled', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(14, 1, 7, NULL, 'Ms. Karianne Lebsack DDS', NULL, NULL, '+1-830-978-6599', NULL, '2026-03-01', '2026-03-03', 3852.14, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(15, 2, 169, NULL, 'Darien Terry', NULL, NULL, '458.510.5689', NULL, '2026-03-23', '2026-03-27', 24194.60, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(16, 1, 83, NULL, 'Cielo O\'Keefe', NULL, NULL, '1-586-593-7932', NULL, '2026-03-05', '2026-03-09', 14037.00, 'cancelled', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(17, 1, 66, NULL, 'Kenna Ullrich DDS', NULL, NULL, '+1 (740) 274-2362', NULL, '2026-03-24', '2026-03-26', 9324.64, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(18, 1, 124, NULL, 'Florine Kris', NULL, NULL, '1-929-383-9570', NULL, '2026-03-13', '2026-03-20', 45671.08, 'cancelled', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(19, 3, 223, NULL, 'Mrs. Virginie Kuphal', NULL, NULL, '662.440.0609', NULL, '2026-03-13', '2026-03-17', 23126.32, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(20, 1, 63, NULL, 'Victoria Williamson', NULL, NULL, '930-304-3147', NULL, '2026-04-21', '2026-04-30', 43277.13, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(21, 1, 9, NULL, 'Prof. Aaron Pfeffer MD', NULL, NULL, '1-909-423-2396', NULL, '2026-03-29', '2026-03-30', 5202.26, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(22, 1, 96, NULL, 'Karina Rogahn', NULL, NULL, '1-915-671-5674', NULL, '2026-04-06', '2026-04-13', 22863.05, 'cancelled', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(23, 1, 87, NULL, 'Charlie Monahan', NULL, NULL, '915.966.3895', NULL, '2026-03-25', '2026-03-27', 4739.78, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(24, 1, 51, NULL, 'Catherine Pfeffer Sr.', NULL, NULL, '+1-636-560-9301', NULL, '2026-03-20', '2026-03-30', 27941.20, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(25, 1, 74, NULL, 'Mrs. Annamae Blick', NULL, NULL, '1-260-897-1673', NULL, '2026-04-07', '2026-04-15', 22432.72, 'pending', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(26, 1, 127, NULL, 'Prof. Meda Kuphal DVM', NULL, NULL, '+1-562-531-1101', NULL, '2026-04-10', '2026-04-17', 11114.95, 'pending', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(27, 1, 40, NULL, 'Caleb Murphy', NULL, NULL, '+1-351-874-7371', NULL, '2026-04-30', '2026-05-08', 29950.48, 'cancelled', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(28, 1, 22, NULL, 'Susana Dickens DDS', NULL, NULL, '(754) 238-2370', NULL, '2026-04-07', '2026-04-11', 18043.96, 'pending', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(29, 1, 15, NULL, 'Talia Gleichner', NULL, NULL, '(434) 598-8491', NULL, '2026-03-17', '2026-03-18', 6088.48, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(30, 2, 171, NULL, 'Okey Cartwright', NULL, NULL, '1-405-960-7636', NULL, '2026-04-25', '2026-05-02', 37336.88, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(31, 1, 76, NULL, 'Dr. Raven Kessler', NULL, NULL, '1-763-971-8403', NULL, '2026-03-10', '2026-03-14', 15537.28, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(32, 1, 93, NULL, 'Vella Wolf V', NULL, NULL, '+1-205-755-1642', NULL, '2026-04-30', '2026-05-06', 25443.78, 'pending', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(33, 2, 211, NULL, 'Jewell Beer', NULL, NULL, '1-321-305-4620', NULL, '2026-03-23', '2026-03-28', 4230.45, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(34, 1, 32, NULL, 'Ms. Otha Watsica', NULL, NULL, '(929) 866-7022', NULL, '2026-04-06', '2026-04-09', 18295.44, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(35, 1, 20, NULL, 'Prof. Autumn Brekke V', NULL, NULL, '682.577.4428', NULL, '2026-03-17', '2026-03-21', 7249.48, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(36, 1, 39, NULL, 'Lorena Reilly', NULL, NULL, '+1-443-206-1903', NULL, '2026-04-15', '2026-04-16', 5061.67, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(37, 1, 18, NULL, 'Imelda Pfeffer', NULL, NULL, '770-316-7094', NULL, '2026-03-27', '2026-04-01', 23927.55, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(38, 3, 226, NULL, 'Emanuel Wolff', NULL, NULL, '1-646-380-7769', NULL, '2026-04-19', '2026-04-26', 41808.13, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(39, 2, 194, NULL, 'Natasha Kuhic', NULL, NULL, '541.867.6129', NULL, '2026-03-06', '2026-03-13', 27942.88, 'cancelled', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(40, 3, 247, NULL, 'Prof. Kamryn Herman', NULL, NULL, '(757) 422-7327', NULL, '2026-03-23', '2026-04-01', 64957.41, 'cancelled', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(41, 3, 218, NULL, 'Mr. Curt Doyle', NULL, NULL, '+19563906461', NULL, '2026-04-13', '2026-04-18', 8967.00, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(42, 2, 149, NULL, 'Cynthia Altenwerth', NULL, NULL, '828-878-7005', NULL, '2026-03-12', '2026-03-14', 3904.62, 'cancelled', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(43, 1, 100, NULL, 'Rubye Kutch', NULL, NULL, '+16696971215', NULL, '2026-03-13', '2026-03-16', 4696.59, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(44, 3, 228, NULL, 'Prof. Adell Mante Sr.', NULL, NULL, '(318) 683-6147', NULL, '2026-04-17', '2026-04-27', 28847.30, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(45, 2, 167, NULL, 'Hector Collins', NULL, NULL, '859.557.9798', NULL, '2026-03-29', '2026-04-02', 18919.60, 'pending', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(46, 2, 162, NULL, 'Neva Runolfsdottir', NULL, NULL, '+1-954-494-5003', NULL, '2026-04-05', '2026-04-08', 15677.91, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(47, 1, 30, NULL, 'Ms. Jakayla Mayer', NULL, NULL, '+18606351289', NULL, '2026-04-21', '2026-04-26', 22826.00, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(48, 2, 193, NULL, 'Ms. Alda Gutkowski III', NULL, NULL, '(469) 400-4628', NULL, '2026-03-26', '2026-03-29', 18979.35, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(49, 2, 151, NULL, 'Joanny Hackett', NULL, NULL, '+1 (712) 855-5405', NULL, '2026-04-18', '2026-04-22', 13754.40, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(50, 1, 133, NULL, 'Seamus Heathcote', NULL, NULL, '732.714.4530', NULL, '2026-04-16', '2026-04-23', 16331.63, 'pending', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(51, 1, 123, NULL, 'Mrs. Claudia Ritchie', NULL, NULL, '+1-813-960-3729', NULL, '2026-03-20', '2026-03-29', 31346.82, 'pending', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(52, 3, 221, NULL, 'Miss Jammie Douglas', NULL, NULL, '(830) 491-7975', NULL, '2026-04-15', '2026-04-17', 5819.28, 'pending', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(53, 3, 246, NULL, 'Prof. Alessandro Dietrich', NULL, NULL, '320.949.5415', NULL, '2026-04-11', '2026-04-16', 34206.85, 'cancelled', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(54, 3, 248, NULL, 'Pierce Kilback', NULL, NULL, '+18309368252', NULL, '2026-03-08', '2026-03-15', 48228.18, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(55, 1, 97, NULL, 'Beverly Ward', NULL, NULL, '1-458-661-7636', NULL, '2026-04-08', '2026-04-10', 2698.32, 'cancelled', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(56, 2, 215, NULL, 'Hillary Hermiston', NULL, NULL, '854.694.6260', NULL, '2026-03-22', '2026-03-30', 12824.48, 'cancelled', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(57, 2, 201, NULL, 'Dr. Alexis Harvey', NULL, NULL, '609.380.2714', NULL, '2026-04-13', '2026-04-20', 20385.54, 'pending', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(58, 2, 195, NULL, 'Mollie Schinner MD', NULL, NULL, '+15615639062', NULL, '2026-03-24', '2026-03-26', 12673.04, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(59, 2, 163, NULL, 'Meggie Dietrich', NULL, NULL, '(440) 717-6740', NULL, '2026-04-03', '2026-04-13', 25437.10, 'cancelled', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(60, 1, 101, NULL, 'Otha Eichmann', NULL, NULL, '520.986.7179', NULL, '2026-04-25', '2026-05-05', 15203.80, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(61, 2, 214, NULL, 'Ike Leuschke III', NULL, NULL, '1-980-517-2232', NULL, '2026-03-08', '2026-03-17', 20454.39, 'cancelled', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(62, 3, 242, NULL, 'Mrs. Jailyn Blick IV', NULL, NULL, '754-486-0398', NULL, '2026-03-28', '2026-04-05', 37395.84, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(63, 1, 119, NULL, 'Clemmie Windler', NULL, NULL, '(240) 710-5536', NULL, '2026-04-18', '2026-04-26', 12599.20, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(64, 1, 140, NULL, 'Dereck Wisozk', NULL, NULL, '(616) 803-8952', NULL, '2026-03-26', '2026-04-05', 14380.50, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(65, 2, 197, NULL, 'Micheal Abernathy', NULL, NULL, '+1-681-655-9680', NULL, '2026-03-22', '2026-03-24', 11560.82, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(66, 2, 188, NULL, 'Imani Parker V', NULL, NULL, '731-318-2477', NULL, '2026-05-02', '2026-05-07', 7117.20, 'pending', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(67, 1, 68, NULL, 'Rashad Beier Sr.', NULL, NULL, '743-827-3119', NULL, '2026-04-25', '2026-04-26', 1212.88, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(68, 1, 136, NULL, 'Mrs. Antonina Thiel', NULL, NULL, '657-745-4632', NULL, '2026-04-13', '2026-04-23', 60106.80, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(69, 1, 71, NULL, 'Daren Rau DVM', NULL, NULL, '440.872.3739', NULL, '2026-04-10', '2026-04-14', 17843.52, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(70, 1, 90, NULL, 'Armando Gutkowski II', NULL, NULL, '+1 (304) 754-4938', NULL, '2026-03-12', '2026-03-18', 30875.46, 'pending', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(71, 1, 120, NULL, 'Quinten Parisian', NULL, NULL, '+1 (772) 951-9202', NULL, '2026-04-22', '2026-04-24', 12325.70, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(72, 3, 236, NULL, 'Janae Boehm', NULL, NULL, '580.923.8644', NULL, '2026-03-22', '2026-03-28', 18403.44, 'cancelled', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(73, 1, 59, NULL, 'Ross Krajcik MD', NULL, NULL, '484.426.8795', NULL, '2026-04-05', '2026-04-07', 6547.10, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(74, 1, 80, NULL, 'Vicky Kuhlman', NULL, NULL, '(240) 338-3365', NULL, '2026-03-03', '2026-03-06', 4306.80, 'pending', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(75, 2, 180, NULL, 'Ara Miller', NULL, NULL, '+1-918-209-6162', NULL, '2026-03-12', '2026-03-19', 18690.56, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(76, 2, 185, NULL, 'Ms. Lexi Goldner I', NULL, NULL, '+1-207-736-4553', NULL, '2026-03-26', '2026-04-03', 46449.52, 'pending', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(77, 3, 235, NULL, 'Darwin Frami', NULL, NULL, '+15597399280', NULL, '2026-04-10', '2026-04-20', 60929.80, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(78, 1, 75, NULL, 'Enid Nitzsche I', NULL, NULL, '630.453.2296', NULL, '2026-03-25', '2026-04-03', 55333.44, 'pending', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(79, 1, 2, NULL, 'Jonas Effertz', NULL, NULL, '(949) 432-5518', NULL, '2026-04-21', '2026-04-29', 48461.04, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(80, 2, 176, NULL, 'Geovanny Witting', NULL, NULL, '(657) 444-6095', NULL, '2026-03-25', '2026-03-31', 32801.58, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(81, 1, 27, NULL, 'Alycia Schoen', NULL, NULL, '+1.352.815.4065', NULL, '2026-03-20', '2026-03-27', 35486.43, 'cancelled', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(82, 1, 67, NULL, 'Mrs. Zita Romaguera DDS', NULL, NULL, '+1-641-241-7068', NULL, '2026-03-15', '2026-03-24', 16551.36, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(83, 1, 135, NULL, 'Miss Abigale Borer I', NULL, NULL, '1-915-694-2945', NULL, '2026-03-14', '2026-03-20', 18561.60, 'cancelled', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(84, 3, 216, NULL, 'Hayden Schmeler', NULL, NULL, '856.991.4400', NULL, '2026-03-21', '2026-03-28', 23389.52, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(85, 2, 186, NULL, 'Alycia Pouros', NULL, NULL, '+17479149560', NULL, '2026-03-23', '2026-03-28', 19295.05, 'cancelled', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(86, 1, 70, NULL, 'Gloria Larson', NULL, NULL, '+1.936.359.6914', NULL, '2026-04-17', '2026-04-23', 21386.64, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(87, 1, 65, NULL, 'Prof. Unique Thiel V', NULL, NULL, '+1.772.825.2015', NULL, '2026-04-08', '2026-04-17', 34331.58, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(88, 1, 118, NULL, 'Maureen Turcotte', NULL, NULL, '+1 (907) 700-9691', NULL, '2026-04-06', '2026-04-12', 21591.24, 'cancelled', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(89, 1, 31, NULL, 'Cedrick Hayes', NULL, NULL, '+1-651-262-8315', NULL, '2026-03-21', '2026-03-31', 36597.30, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(90, 2, 206, NULL, 'Lea Blanda I', NULL, NULL, '903-255-4128', NULL, '2026-04-12', '2026-04-16', 27075.52, 'cancelled', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(91, 1, 138, NULL, 'Prof. Ray Price I', NULL, NULL, '+1.347.361.4980', NULL, '2026-03-16', '2026-03-18', 11670.70, 'cancelled', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(92, 2, 189, NULL, 'Ephraim O\'Connell', NULL, NULL, '434.287.2856', NULL, '2026-04-13', '2026-04-19', 19416.42, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(93, 2, 184, NULL, 'Asha Ratke', NULL, NULL, '442-222-5091', NULL, '2026-04-10', '2026-04-19', 51572.34, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(94, 3, 232, NULL, 'Vernice Sawayn', NULL, NULL, '463.498.5988', NULL, '2026-04-30', '2026-05-04', 17698.92, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(95, 2, 159, NULL, 'Mrs. Martina Terry', NULL, NULL, '+12196748730', NULL, '2026-03-16', '2026-03-23', 35655.06, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(96, 2, 182, NULL, 'Everette Koss', NULL, NULL, '1-909-705-3308', NULL, '2026-03-29', '2026-04-03', 7228.80, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(97, 1, 72, NULL, 'Jameson Brown', NULL, NULL, '+15349950269', NULL, '2026-03-27', '2026-04-06', 51680.90, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(98, 1, 131, NULL, 'Lauren Fadel', NULL, NULL, '+19303740057', NULL, '2026-03-23', '2026-03-28', 4393.60, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(99, 1, 84, NULL, 'Lenny Feil', NULL, NULL, '+1 (351) 878-0398', NULL, '2026-04-01', '2026-04-04', 17404.53, 'pending', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(100, 2, 156, NULL, 'Madisen Huels', NULL, NULL, '+1-806-938-9342', NULL, '2026-03-14', '2026-03-16', 11890.44, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(101, 1, 49, NULL, 'Jarvis Hegmann DDS', NULL, NULL, '+1 (678) 347-7539', NULL, '2026-04-17', '2026-04-18', 2560.35, 'pending', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(102, 1, 134, NULL, 'Percy Hirthe', NULL, NULL, '616-466-9882', NULL, '2026-03-20', '2026-03-24', 11323.76, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(103, 2, 204, NULL, 'Kennedi Mertz II', NULL, NULL, '(304) 815-2437', NULL, '2026-04-13', '2026-04-17', 14515.88, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(104, 1, 37, NULL, 'Prof. Ramona Gottlieb', NULL, NULL, '+1 (352) 385-8767', NULL, '2026-03-06', '2026-03-14', 42872.08, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(105, 1, 110, NULL, 'Bonita Bosco', NULL, NULL, '+1-207-229-2196', NULL, '2026-03-09', '2026-03-15', 28340.28, 'cancelled', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(106, 3, 219, NULL, 'Simone Aufderhar', NULL, NULL, '737.488.9652', NULL, '2026-03-15', '2026-03-19', 9130.76, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(107, 1, 5, NULL, 'Jameson Schamberger', NULL, NULL, '862-777-6587', NULL, '2026-03-23', '2026-03-25', 3017.92, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(108, 1, 12, NULL, 'Daphnee Christiansen', NULL, NULL, '+1.843.306.8099', NULL, '2026-03-13', '2026-03-21', 15420.24, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(109, 1, 69, NULL, 'Savannah Grimes', NULL, NULL, '1-765-823-5663', NULL, '2026-03-11', '2026-03-18', 9438.94, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(110, 1, 24, NULL, 'Maye Bailey', NULL, NULL, '929-773-2970', NULL, '2026-04-10', '2026-04-15', 15245.95, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(111, 1, 13, NULL, 'Demetrius Kiehn', NULL, NULL, '352.990.1659', NULL, '2026-04-02', '2026-04-10', 31465.52, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(112, 2, 158, NULL, 'Carleton Schaefer', NULL, NULL, '(283) 873-2134', NULL, '2026-03-05', '2026-03-15', 60913.40, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(113, 1, 128, NULL, 'Jannie Howe III', NULL, NULL, '252-741-4128', NULL, '2026-03-19', '2026-03-22', 8248.14, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(114, 1, 137, NULL, 'Jailyn Fahey', NULL, NULL, '(423) 427-2798', NULL, '2026-02-28', '2026-03-05', 41344.35, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(115, 1, 142, NULL, 'Carol Nader I', NULL, NULL, '+1-251-628-9497', NULL, '2026-04-23', '2026-04-26', 19833.51, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(116, 2, 181, NULL, 'Daphnee Beer DDS', NULL, NULL, '+1-325-257-9057', NULL, '2026-04-01', '2026-04-08', 17447.15, 'pending', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(117, 1, 112, NULL, 'Mariano Walter', NULL, NULL, '+1-678-557-7330', NULL, '2026-03-20', '2026-03-28', 29050.08, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(118, 1, 117, NULL, 'Soledad Will', NULL, NULL, '+1-540-268-1156', NULL, '2026-04-22', '2026-05-02', 50154.50, 'confirmed', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(119, 2, 166, NULL, 'Martine Wyman', NULL, NULL, '689.930.8486', NULL, '2026-04-07', '2026-04-14', 22249.50, 'pending', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(120, 1, 78, NULL, 'Dr. Leon Ryan', NULL, NULL, '815-286-9141', NULL, '2026-03-17', '2026-03-24', 30690.31, 'cancelled', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(121, 5, 275, 6, 'viralkumar ode', 'viralkumar ode', NULL, '4343434343', NULL, '2026-04-14', '2026-04-14', 11000.00, 'confirmed', '2026-04-14 11:20:15', '2026-04-14 12:20:46'),
(122, 5, 266, 6, 'Manoj Kumar', 'Manoj Kumar', NULL, '2323232323', NULL, '2026-04-14', '2026-04-14', 14000.00, 'confirmed', '2026-04-14 12:16:33', '2026-04-14 12:16:33'),
(123, 5, 267, 6, '2323', '2323', NULL, '2323', NULL, '2026-04-16', '2026-04-16', 222.00, 'confirmed', '2026-04-14 12:20:20', '2026-04-14 12:20:20');

-- --------------------------------------------------------

--
-- Table structure for table `booking_payments`
--

DROP TABLE IF EXISTS `booking_payments`;
CREATE TABLE IF NOT EXISTS `booking_payments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `paid_on` date NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_payments_booking_id_foreign` (`booking_id`),
  KEY `booking_payments_created_by_foreign` (`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_payments`
--

INSERT INTO `booking_payments` (`id`, `booking_id`, `amount`, `paid_on`, `description`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 121, 20000.00, '2026-04-14', NULL, 6, '2026-04-14 12:06:52', '2026-04-14 12:06:52'),
(2, 121, 5000.00, '2026-04-14', NULL, 6, '2026-04-14 12:07:06', '2026-04-14 12:07:06'),
(3, 121, 30000.00, '2026-04-14', NULL, 6, '2026-04-14 12:07:24', '2026-04-14 12:07:24'),
(4, 121, 6000.00, '2026-04-14', NULL, 6, '2026-04-14 12:07:43', '2026-04-14 12:07:43'),
(5, 123, 50000.00, '2026-04-14', NULL, 6, '2026-04-14 12:22:52', '2026-04-14 12:22:52'),
(6, 121, 500000.00, '2026-04-14', NULL, 6, '2026-04-14 12:28:05', '2026-04-14 12:28:05');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:20:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:9:\"role-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:11:\"role-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:9:\"role-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:11:\"role-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:12:\"product-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:14:\"product-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:12:\"product-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:14:\"product-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:10:\"Order-Item\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:12:\"Manage Order\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:9:\"user-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:19:\"Manage Order Create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:19:\"Manage Order Delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:17:\"Manage Order Edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:19:\"Manage Order status\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:9:\"Dashboard\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:16:\"Manage Countries\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:13:\"Manage Cities\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:10:\"Demo Excel\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:6:\"Import\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:1:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"Admin\";s:1:\"c\";s:3:\"web\";}}}', 1776249238);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `age` tinyint UNSIGNED DEFAULT NULL,
  `salary` decimal(12,2) DEFAULT NULL,
  `gender` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `country_name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
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
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(36, '2026_04_14_000006_add_gst_fields_to_bank_transactions_table', 11);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `partners`
--

DROP TABLE IF EXISTS `partners`;
CREATE TABLE IF NOT EXISTS `partners` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_balance` decimal(12,2) NOT NULL DEFAULT '0.00',
  `percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `details` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `partners_project_id_foreign` (`project_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `partners`
--

INSERT INTO `partners` (`id`, `project_id`, `name`, `phone`, `total_balance`, `percentage`, `details`, `created_at`, `updated_at`) VALUES
(1, 4, 'Bhumika Patel', '9824625955', 1100000.00, 0.20, NULL, '2026-04-09 08:52:27', '2026-04-09 08:52:27'),
(2, 4, 'Rathva Pankaj Rameshbhai', '7572881840', 3300000.00, 6.00, NULL, '2026-04-09 08:53:11', '2026-04-09 08:53:11');

-- --------------------------------------------------------

--
-- Table structure for table `partner_payments`
--

DROP TABLE IF EXISTS `partner_payments`;
CREATE TABLE IF NOT EXISTS `partner_payments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `partner_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_date` date DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `partner_payments_partner_id_foreign` (`partner_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `partner_payments`
--

INSERT INTO `partner_payments` (`id`, `partner_id`, `amount`, `payment_date`, `note`, `created_at`, `updated_at`) VALUES
(1, 2, 10000.00, '2026-04-09', NULL, '2026-04-09 08:57:07', '2026-04-09 08:57:07');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(20, 'Import', 'web', '2026-04-09 07:08:13', '2026-04-09 07:08:13');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` text COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
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
-- Table structure for table `properties`
--

DROP TABLE IF EXISTS `properties`;
CREATE TABLE IF NOT EXISTS `properties` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` bigint UNSIGNED DEFAULT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('house','shop') COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bedrooms` int UNSIGNED DEFAULT NULL,
  `area_sqft` int UNSIGNED DEFAULT NULL,
  `price_per_day` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` enum('available','booked','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `properties_code_unique` (`code`),
  KEY `properties_type_index` (`type`),
  KEY `properties_status_index` (`status`),
  KEY `properties_project_id_foreign` (`project_id`)
) ENGINE=MyISAM AUTO_INCREMENT=281 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`id`, `project_id`, `code`, `type`, `title`, `city`, `address`, `bedrooms`, `area_sqft`, `price_per_day`, `status`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'HARIHAR-H-0001', 'house', 'House 1', 'Delhi', '34605 Becker Passage', 5, 1798, 5589.69, 'available', 'Quisquam deleniti odit natus totam dignissimos accusantium occaecati et ipsam reiciendis non ullam quia placeat.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(2, 1, 'HARIHAR-H-0002', 'house', 'House 2', 'Jaipur', '444 Buford River Suite 467', 4, 1570, 6057.63, 'booked', 'Excepturi aut est accusantium quis et occaecati sed ut aperiam dignissimos deserunt quidem quisquam.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(3, 1, 'HARIHAR-H-0003', 'house', 'House 3', 'Mumbai', '6069 Runolfsdottir Expressway Apt. 358', 1, 1117, 4454.77, 'available', 'Molestias accusamus facilis aut doloremque est incidunt numquam fuga optio quaerat dicta quaerat minima id.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(4, 1, 'HARIHAR-H-0004', 'house', 'House 4', 'Jaipur', '455 Bins Divide Apt. 185', 4, 1249, 1795.77, 'available', 'Ad ut laudantium ratione voluptatibus commodi voluptatem amet ut qui.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(5, 1, 'HARIHAR-H-0005', 'house', 'House 5', 'Indore', '2209 Stamm Dale Suite 752', 3, 1356, 1508.96, 'booked', 'Officia nihil ea assumenda repudiandae neque ea porro cum aspernatur facere harum officia.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(6, 1, 'HARIHAR-H-0006', 'house', 'House 6', 'Indore', '7348 Verda Branch Suite 804', 3, 3259, 4435.55, 'available', 'Deserunt quia neque dolor maiores quasi eum rerum adipisci necessitatibus fugiat minus architecto perferendis cupiditate qui.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(7, 1, 'HARIHAR-H-0007', 'house', 'House 7', 'Ahmedabad', '66254 Kim Overpass Suite 936', 4, 3186, 1926.07, 'booked', 'Est voluptates et distinctio earum iusto id quisquam cumque quo maiores iste libero.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(8, 1, 'HARIHAR-H-0008', 'house', 'House 8', 'Pune', '8674 Cecilia Coves', 6, 3258, 3456.85, 'available', 'Quis reprehenderit earum qui temporibus sunt eum tempora libero quia a et sed saepe tenetur unde quis.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(9, 1, 'HARIHAR-H-0009', 'house', 'House 9', 'Ahmedabad', '4208 Runolfsdottir Field', 5, 1716, 5202.26, 'booked', 'Dolores et nostrum autem magnam reiciendis illo ea et consequuntur fugiat.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(10, 1, 'HARIHAR-H-0010', 'house', 'House 10', 'Mumbai', '78569 Rosenbaum Estates', 3, 2550, 5215.55, 'available', 'Possimus sed fugit ab nihil voluptatibus est veniam.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(11, 1, 'HARIHAR-H-0011', 'house', 'House 11', 'Jaipur', '238 Weimann Ranch Apt. 065', 2, 2127, 4212.34, 'available', 'Nulla rem omnis in quia quo nihil earum perspiciatis culpa modi recusandae magni est officiis sint.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(12, 1, 'HARIHAR-H-0012', 'house', 'House 12', 'Ahmedabad', '34557 Candice Underpass', 3, 3205, 1927.53, 'booked', 'In est quasi animi quisquam quibusdam nobis delectus.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(13, 1, 'HARIHAR-H-0013', 'house', 'House 13', 'Pune', '229 Ross Square', 4, 1319, 3933.19, 'booked', 'Magnam harum et voluptatem veritatis consequatur labore quia.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(14, 1, 'HARIHAR-H-0014', 'house', 'House 14', 'Pune', '26362 Barton Inlet', 6, 2067, 1254.38, 'available', 'Unde deserunt non perferendis aut cum in in quasi non ea nihil consequatur nam.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(15, 1, 'HARIHAR-H-0015', 'house', 'House 15', 'Jaipur', '151 Hoyt Squares Suite 527', 1, 2697, 6088.48, 'booked', 'Quaerat ab enim distinctio dolorem sed doloribus tempora voluptatum unde dolores eaque est cumque unde ex.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(16, 1, 'HARIHAR-H-0016', 'house', 'House 16', 'Delhi', '687 Rod Valley Apt. 218', 6, 3046, 6042.91, 'available', 'Non rerum dolorem molestiae qui est vitae consequatur distinctio eum.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(17, 1, 'HARIHAR-H-0017', 'house', 'House 17', 'Pune', '285 Kiley Street Apt. 712', 6, 3554, 1980.21, 'available', 'Ut saepe ea corporis quidem nemo repellendus assumenda accusantium voluptas iste quam nihil reiciendis sed eveniet recusandae.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(18, 1, 'HARIHAR-H-0018', 'house', 'House 18', 'Delhi', '22910 Pablo Turnpike', 3, 3183, 4785.51, 'booked', 'Fuga sed amet praesentium et illo eligendi ut praesentium qui esse sapiente eligendi nam.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(19, 1, 'HARIHAR-H-0019', 'house', 'House 19', 'Surat', '85310 Dickinson Oval Apt. 218', 5, 3692, 2245.17, 'available', 'Unde eligendi aut neque amet consectetur qui nam atque hic dolor optio et qui architecto voluptate.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(20, 1, 'HARIHAR-H-0020', 'house', 'House 20', 'Pune', '75689 Rau Isle Suite 544', 3, 1064, 1812.37, 'booked', 'Nihil quam iure laboriosam neque quos assumenda sed magni quae.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(21, 1, 'HARIHAR-H-0021', 'house', 'House 21', 'Jaipur', '67468 Legros Mission Suite 325', 3, 789, 4075.82, 'available', 'Sunt perferendis quisquam eos magnam ea eum enim aut repellendus nesciunt.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(22, 1, 'HARIHAR-H-0022', 'house', 'House 22', 'Jaipur', '315 Abbott Parks Suite 401', 6, 1069, 4510.99, 'booked', 'Optio fuga quos qui et id est omnis eos amet est excepturi voluptatem.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(23, 1, 'HARIHAR-H-0023', 'house', 'House 23', 'Jaipur', '6460 D\'Amore Rest Suite 026', 4, 2597, 4084.44, 'available', 'Dolores consequatur iste natus non in molestias deleniti vitae ipsum neque doloribus deserunt culpa omnis est qui.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(24, 1, 'HARIHAR-H-0024', 'house', 'House 24', 'Surat', '79258 Crawford Tunnel', 1, 2646, 3049.19, 'booked', 'Incidunt perspiciatis voluptatem et magnam aliquam voluptas cupiditate sit.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(25, 1, 'HARIHAR-H-0025', 'house', 'House 25', 'Jaipur', '424 Blanca Causeway Apt. 432', 2, 1215, 4462.73, 'available', 'Neque sapiente culpa incidunt iusto eum ea excepturi reiciendis numquam sit et impedit at.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(26, 1, 'HARIHAR-H-0026', 'house', 'House 26', 'Ahmedabad', '1464 Konopelski Forge Suite 968', 2, 3487, 1677.24, 'available', 'Earum perferendis itaque natus odit et ut nihil tempora culpa suscipit dignissimos.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(27, 1, 'HARIHAR-H-0027', 'house', 'House 27', 'Indore', '8481 Swaniawski Port', 3, 3504, 5069.49, 'available', 'Esse reprehenderit ipsam est qui sequi excepturi odio sed maiores et iure quia qui consequatur facilis dolores.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(28, 1, 'HARIHAR-H-0028', 'house', 'House 28', 'Delhi', '7255 Lauretta Trace', 1, 1981, 1362.15, 'available', 'Fugit ut suscipit dolores quia nobis qui aut consequatur ipsa.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(29, 1, 'HARIHAR-H-0029', 'house', 'House 29', 'Indore', '570 Henry Crescent Suite 140', 1, 3308, 1448.93, 'available', 'Tempora fugit sunt laborum dolore assumenda quia eligendi voluptatem impedit similique ipsum asperiores.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(30, 1, 'HARIHAR-H-0030', 'house', 'House 30', 'Surat', '67609 Mante Spur', 4, 3075, 4565.20, 'booked', 'Doloremque quis inventore atque aut et soluta voluptate rem nesciunt accusamus sit iure nihil quibusdam.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(31, 1, 'HARIHAR-H-0031', 'house', 'House 31', 'Surat', '683 Dickens Hill Apt. 227', 3, 1856, 3659.73, 'booked', 'Nihil fuga magnam adipisci qui aut sunt similique ab et dolor qui corrupti corporis ea cupiditate esse.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(32, 1, 'HARIHAR-H-0032', 'house', 'House 32', 'Delhi', '79836 Heath Lock', 2, 2033, 6098.48, 'booked', 'Nesciunt ipsam et perferendis est provident aliquid blanditiis voluptatem sed minima velit aliquam odit autem fugit.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(33, 1, 'HARIHAR-H-0033', 'house', 'House 33', 'Jaipur', '2202 Trenton Square', 3, 981, 6180.58, 'available', 'Voluptatibus cum id et harum magnam nemo qui dolorem et ipsum repudiandae aut sunt repudiandae rerum.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(34, 1, 'HARIHAR-H-0034', 'house', 'House 34', 'Mumbai', '4109 Willms Ville', 2, 944, 2659.71, 'available', 'Aliquam voluptatum eius quas velit eum veritatis est ad.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(35, 1, 'HARIHAR-H-0035', 'house', 'House 35', 'Mumbai', '856 Boehm Row Suite 461', 2, 1116, 6094.06, 'booked', 'Corrupti quisquam sit sit eligendi aperiam officia fugiat consectetur.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(36, 1, 'HARIHAR-H-0036', 'house', 'House 36', 'Pune', '26693 Vilma Knoll', 6, 2933, 3992.06, 'available', 'Aspernatur quo ullam possimus hic voluptatibus aut eum voluptas perferendis porro accusantium numquam adipisci molestiae numquam impedit.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(37, 1, 'HARIHAR-H-0037', 'house', 'House 37', 'Pune', '7123 Caesar Road', 4, 2137, 5359.01, 'booked', 'Ut dignissimos alias et id cumque illum fugiat dicta enim repellat fugit deleniti.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(38, 1, 'HARIHAR-H-0038', 'house', 'House 38', 'Ahmedabad', '9250 Rath Streets', 2, 2768, 5814.52, 'available', 'Incidunt modi in qui praesentium non et qui aspernatur eum vitae voluptas suscipit possimus aliquam et.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(39, 1, 'HARIHAR-H-0039', 'house', 'House 39', 'Mumbai', '83414 Weimann Ramp', 5, 2436, 5061.67, 'booked', 'Magni mollitia et in assumenda et reprehenderit quo quia iste repellat excepturi dolores omnis esse omnis.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(40, 1, 'HARIHAR-H-0040', 'house', 'House 40', 'Delhi', '622 Rodolfo Lake', 1, 1092, 3743.81, 'available', 'Deserunt et vel temporibus ullam eveniet vel et praesentium beatae rem cupiditate non provident nobis hic iste.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(41, 1, 'HARIHAR-H-0041', 'house', 'House 41', 'Ahmedabad', '104 Ara Freeway Apt. 931', 1, 1029, 3886.43, 'booked', 'Non ut aut tenetur rem similique aut expedita culpa voluptates qui porro.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(42, 1, 'HARIHAR-H-0042', 'house', 'House 42', 'Ahmedabad', '721 Gislason Trail', 5, 3550, 1749.47, 'available', 'Distinctio tenetur unde saepe qui voluptas aut hic consectetur.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(43, 1, 'HARIHAR-H-0043', 'house', 'House 43', 'Delhi', '94883 Rudolph Lakes Apt. 944', 2, 1918, 6186.46, 'available', 'In at ratione quod aut sunt adipisci id sapiente laboriosam.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(44, 1, 'HARIHAR-H-0044', 'house', 'House 44', 'Indore', '516 Blanda Haven', 1, 2285, 2051.14, 'available', 'Vel error quibusdam voluptate odio et dolorem ea nobis maiores ipsum sit iste omnis possimus minus labore.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(45, 1, 'HARIHAR-H-0045', 'house', 'House 45', 'Surat', '934 Leannon Views Apt. 686', 1, 2100, 1251.36, 'available', 'Tempora pariatur et consectetur id vitae totam accusantium impedit quae omnis aspernatur alias corporis impedit.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(46, 1, 'HARIHAR-H-0046', 'house', 'House 46', 'Jaipur', '818 Karina Hollow', 4, 2269, 3110.37, 'available', 'Eos deleniti mollitia ducimus tempora a consequatur ut quibusdam repellendus vel vitae.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(47, 1, 'HARIHAR-H-0047', 'house', 'House 47', 'Indore', '80543 Kelton Burgs Apt. 593', 6, 3253, 4935.34, 'available', 'Iste dolorem voluptatem debitis sapiente perferendis aut suscipit tempore tempora reiciendis minima voluptatem quia cupiditate vero saepe.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(48, 1, 'HARIHAR-H-0048', 'house', 'House 48', 'Mumbai', '60427 Tre River Suite 734', 1, 2117, 2788.99, 'available', 'Et ut consequuntur ab perspiciatis iure doloribus qui sunt corporis.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(49, 1, 'HARIHAR-H-0049', 'house', 'House 49', 'Indore', '87336 Renner Fall', 5, 2649, 2560.35, 'booked', 'Eius dignissimos velit quaerat eligendi ut quis quia et omnis mollitia delectus possimus.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(50, 1, 'HARIHAR-H-0050', 'house', 'House 50', 'Ahmedabad', '8515 Loma View Apt. 106', 3, 2565, 4246.34, 'available', 'Tempora dolores magni quo voluptas modi ut qui corporis omnis.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(51, 1, 'HARIHAR-H-0051', 'house', 'House 51', 'Ahmedabad', '10701 Selmer Tunnel Suite 911', 2, 3044, 2794.12, 'booked', 'Voluptatem porro id vitae vel corrupti aut fuga commodi consequatur quia quis autem et molestiae sit occaecati.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(52, 1, 'HARIHAR-H-0052', 'house', 'House 52', 'Ahmedabad', '177 Lavern Spring', 1, 1237, 3042.46, 'available', 'Reprehenderit pariatur sint ipsa unde rem autem pariatur ut aut.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(53, 1, 'HARIHAR-H-0053', 'house', 'House 53', 'Pune', '887 Waters Ridge Apt. 559', 2, 3738, 4426.09, 'available', 'Quia ea odit qui minus vel et necessitatibus quos aut temporibus quam consequuntur facilis iusto consequatur.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(54, 1, 'HARIHAR-H-0054', 'house', 'House 54', 'Jaipur', '4356 Jast Valley Suite 068', 6, 806, 3836.93, 'available', 'Explicabo alias neque repudiandae et illo illum consequatur ea sed modi quia.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(55, 1, 'HARIHAR-H-0055', 'house', 'House 55', 'Pune', '326 Malvina Forges Apt. 027', 2, 1427, 6041.96, 'available', 'Et quisquam quam magni voluptatem iure eveniet aliquid praesentium impedit vero eos earum non.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(56, 1, 'HARIHAR-H-0056', 'house', 'House 56', 'Surat', '8165 Brielle Highway', 1, 2672, 3134.17, 'available', 'Delectus numquam qui soluta pariatur voluptates doloribus sed consequatur dolor ut.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(57, 1, 'HARIHAR-H-0057', 'house', 'House 57', 'Pune', '159 Aidan Fall Suite 068', 5, 2532, 3516.66, 'available', 'Ipsum sit fugiat dolores eius qui magnam sunt hic.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(58, 1, 'HARIHAR-H-0058', 'house', 'House 58', 'Surat', '4666 O\'Kon Loop Suite 667', 1, 1691, 1837.28, 'available', 'Quasi fuga qui quos nisi vero nam omnis delectus quia accusamus.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(59, 1, 'HARIHAR-H-0059', 'house', 'House 59', 'Ahmedabad', '3573 Bernier Cliffs', 4, 3174, 3273.55, 'booked', 'Nihil placeat nesciunt vel voluptas dolor laborum ea temporibus sint inventore et.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(60, 1, 'HARIHAR-H-0060', 'house', 'House 60', 'Indore', '6856 Brakus Junction Apt. 107', 6, 3326, 1589.07, 'available', 'Molestias unde sunt reprehenderit id impedit id aut voluptates omnis excepturi ducimus dicta impedit consequatur.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(61, 1, 'HARIHAR-H-0061', 'house', 'House 61', 'Jaipur', '865 Neha Stream', 4, 1678, 2691.78, 'available', 'Qui corrupti et eaque ea id voluptas repellendus quia cupiditate voluptate similique.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(62, 1, 'HARIHAR-H-0062', 'house', 'House 62', 'Delhi', '465 Rohan Valleys Suite 738', 6, 1457, 2577.48, 'available', 'Voluptatem ut autem reprehenderit esse porro odit vel id dolor quo nobis id dicta.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(63, 1, 'HARIHAR-H-0063', 'house', 'House 63', 'Indore', '222 McKenzie Branch Suite 200', 4, 2944, 4808.57, 'booked', 'Eum veritatis quasi ipsa aut ex iusto tempore necessitatibus id dolor nemo voluptas sed magnam illo.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(64, 1, 'HARIHAR-H-0064', 'house', 'House 64', 'Mumbai', '35684 Leuschke Parkway Suite 193', 5, 1550, 1447.10, 'available', 'Itaque eaque officia ut qui aliquam doloribus fugiat dolores rerum atque illum sed quas.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(65, 1, 'HARIHAR-H-0065', 'house', 'House 65', 'Pune', '293 Rogahn Drive', 1, 1498, 3814.62, 'booked', 'Itaque ad magni maxime soluta occaecati est excepturi debitis quaerat sit quia eaque similique.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(66, 1, 'HARIHAR-H-0066', 'house', 'House 66', 'Pune', '6958 Tom Knoll Apt. 466', 4, 3469, 4662.32, 'booked', 'Non ut dolore adipisci amet sunt culpa dolores eos.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(67, 1, 'HARIHAR-H-0067', 'house', 'House 67', 'Pune', '87086 Rhea Villages', 6, 1615, 1839.04, 'booked', 'Nemo eaque incidunt vero eum quos laboriosam dolore quia ut voluptate ut et autem nihil beatae non.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(68, 1, 'HARIHAR-H-0068', 'house', 'House 68', 'Delhi', '859 Wilkinson Track Apt. 585', 5, 1884, 1212.88, 'booked', 'Labore doloribus illo officiis velit nam dicta a necessitatibus veritatis aliquid ut esse.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(69, 1, 'HARIHAR-H-0069', 'house', 'House 69', 'Pune', '9070 Natalia Radial', 1, 1471, 1348.42, 'booked', 'Incidunt in repellendus nihil reprehenderit et facilis eum est enim.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(70, 1, 'HARIHAR-H-0070', 'house', 'House 70', 'Jaipur', '6699 Hank Squares', 2, 1848, 3564.44, 'booked', 'Consequatur sit reiciendis iure incidunt vero placeat veritatis sit asperiores nulla.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(71, 1, 'HARIHAR-H-0071', 'house', 'House 71', 'Indore', '243 Anastasia Pines', 5, 979, 4460.88, 'booked', 'Et impedit sit commodi doloremque eligendi occaecati exercitationem incidunt suscipit fugit aut pariatur quas.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(72, 1, 'HARIHAR-H-0072', 'house', 'House 72', 'Mumbai', '28627 Jonathon Courts Suite 595', 3, 1121, 5168.09, 'booked', 'Rem nam voluptate qui aut et facilis qui.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(73, 1, 'HARIHAR-H-0073', 'house', 'House 73', 'Indore', '86490 Keon Ports Suite 499', 1, 2410, 1814.36, 'available', 'Odit aut quae modi eos rerum sit iusto consectetur autem aut earum eveniet quia voluptas temporibus debitis.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(74, 1, 'HARIHAR-H-0074', 'house', 'House 74', 'Jaipur', '75686 Kovacek Trail', 2, 2194, 2804.09, 'booked', 'Perferendis harum praesentium ut ipsa numquam consequatur ut accusamus assumenda quia est laudantium maiores similique ipsum.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(75, 1, 'HARIHAR-H-0075', 'house', 'House 75', 'Indore', '56675 Lind Throughway', 2, 3055, 6148.16, 'booked', 'Facere unde et qui vel rerum numquam saepe ea in quia reiciendis eum nam illum assumenda.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(76, 1, 'HARIHAR-H-0076', 'house', 'House 76', 'Indore', '72424 Aiden Valleys', 6, 3057, 3884.32, 'booked', 'Provident temporibus aut vel blanditiis perferendis nostrum quidem et voluptatum.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(77, 1, 'HARIHAR-H-0077', 'house', 'House 77', 'Mumbai', '6158 Schroeder Forks', 3, 2709, 5863.20, 'available', 'Quae laborum labore reiciendis similique sit qui rerum molestiae hic dicta sunt illo blanditiis sunt eum.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(78, 1, 'HARIHAR-H-0078', 'house', 'House 78', 'Ahmedabad', '93464 Jast Trail Suite 111', 4, 3699, 4384.33, 'available', 'Expedita omnis exercitationem et enim voluptatem autem vero et eos totam aut numquam nobis unde in sequi.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(79, 1, 'HARIHAR-H-0079', 'house', 'House 79', 'Pune', '1858 Amparo Gateway Apt. 619', 3, 635, 4130.76, 'available', 'Quo perferendis mollitia animi non et consequatur exercitationem quae quas eligendi quam odio.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(80, 1, 'HARIHAR-H-0080', 'house', 'House 80', 'Pune', '291 Aufderhar Route', 1, 2438, 1435.60, 'booked', 'Et explicabo ut molestias dicta consectetur ipsam doloremque excepturi tempore pariatur quas.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(81, 1, 'HARIHAR-H-0081', 'house', 'House 81', 'Mumbai', '4702 Brett Mission', 5, 2429, 4217.52, 'available', 'Illum est ea rerum odio velit id sequi iste distinctio voluptas ab aliquid doloremque et nesciunt.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(82, 1, 'HARIHAR-H-0082', 'house', 'House 82', 'Jaipur', '6685 Dayne Via', 1, 1070, 6336.83, 'available', 'Amet officia non error doloremque aliquam vel ducimus.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(83, 1, 'HARIHAR-H-0083', 'house', 'House 83', 'Mumbai', '987 Newton Drive Suite 844', 5, 3422, 3509.25, 'available', 'Esse asperiores voluptatem quo enim voluptatem sint harum et veniam quaerat ut.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(84, 1, 'HARIHAR-H-0084', 'house', 'House 84', 'Jaipur', '657 Christine Mount', 4, 3651, 5801.51, 'booked', 'Dolore eius a praesentium molestiae et eligendi aut et modi expedita ut quo.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(85, 1, 'HARIHAR-H-0085', 'house', 'House 85', 'Mumbai', '9595 Alaina River', 2, 3400, 4461.10, 'available', 'Voluptas distinctio iusto iusto voluptates repellat vel repellendus voluptas nam tempora inventore enim.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(86, 1, 'HARIHAR-H-0086', 'house', 'House 86', 'Ahmedabad', '411 Gulgowski Plains Suite 060', 6, 2573, 5590.92, 'available', 'Sit aut reiciendis atque non autem amet doloribus voluptas natus.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(87, 1, 'HARIHAR-H-0087', 'house', 'House 87', 'Delhi', '9102 Roberts Harbors', 1, 2156, 2369.89, 'booked', 'Impedit minima sit quia architecto pariatur voluptatem ut non.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(88, 1, 'HARIHAR-H-0088', 'house', 'House 88', 'Surat', '74609 Jerde Crossing Suite 110', 3, 3620, 1561.84, 'available', 'Quia explicabo quasi rerum fugit voluptatem ut architecto est animi deserunt odio est voluptatum voluptate vero.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(89, 1, 'HARIHAR-H-0089', 'house', 'House 89', 'Delhi', '295 Lexi Flat Apt. 209', 4, 2686, 2529.27, 'available', 'Et nisi modi deserunt molestiae fugit eum at fuga earum assumenda velit.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(90, 1, 'HARIHAR-H-0090', 'house', 'House 90', 'Pune', '13990 Madeline Fields Suite 721', 3, 3054, 5145.91, 'booked', 'Ut et rerum ducimus eos consequatur eos voluptate ad non.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(91, 1, 'HARIHAR-H-0091', 'house', 'House 91', 'Ahmedabad', '657 Destiney Villages Apt. 845', 5, 2878, 4145.11, 'available', 'Sed deserunt dignissimos facilis assumenda tempora doloremque dolor iure eos rerum deserunt.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(92, 1, 'HARIHAR-H-0092', 'house', 'House 92', 'Delhi', '2772 Sporer Court Suite 756', 2, 1229, 3398.67, 'available', 'Id ut eos provident dolores culpa tenetur et magnam suscipit fugiat quia.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(93, 1, 'HARIHAR-H-0093', 'house', 'House 93', 'Ahmedabad', '230 Bayer Islands Apt. 223', 4, 1314, 4240.63, 'booked', 'Quos ut voluptatem deserunt perferendis qui aut exercitationem odio libero accusantium.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(94, 1, 'HARIHAR-H-0094', 'house', 'House 94', 'Pune', '22272 Wehner Points', 5, 2715, 5342.51, 'available', 'Aut vel omnis esse voluptate ut aut fugiat ab.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(95, 1, 'HARIHAR-H-0095', 'house', 'House 95', 'Ahmedabad', '83239 Juliana Stream Suite 901', 6, 3604, 2717.08, 'booked', 'Pariatur vero quisquam quia soluta cum est et expedita.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(96, 1, 'HARIHAR-H-0096', 'house', 'House 96', 'Delhi', '87982 Heaney Trace Apt. 548', 2, 1483, 3266.15, 'available', 'Adipisci minima ut pariatur nemo et aut voluptatem iusto possimus esse est earum.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(97, 1, 'HARIHAR-H-0097', 'house', 'House 97', 'Ahmedabad', '1816 Toy Underpass Apt. 643', 3, 821, 1349.16, 'available', 'Molestiae ex eos libero consequatur itaque rerum quasi perspiciatis ea occaecati ea quia et quis labore.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(98, 1, 'HARIHAR-H-0098', 'house', 'House 98', 'Ahmedabad', '25381 Deckow Harbor', 2, 1856, 2903.62, 'available', 'Qui aliquam enim qui voluptatem sed quia fugit quae perferendis voluptates.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(99, 1, 'HARIHAR-H-0099', 'house', 'House 99', 'Indore', '2366 Santos Summit', 2, 2600, 4128.53, 'available', 'Nemo ab assumenda omnis sit sequi repellendus excepturi asperiores.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(100, 1, 'HARIHAR-H-0100', 'house', 'House 100', 'Jaipur', '7076 Botsford Isle', 1, 3591, 1565.53, 'booked', 'Qui vero et aut fugiat nesciunt eius laborum maxime ut facere et perferendis blanditiis quia et.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(101, 1, 'HARIHAR-H-0101', 'house', 'House 101', 'Jaipur', '42412 Madonna Ramp', 5, 1667, 1520.38, 'booked', 'Quasi quo aperiam ab ullam in ut eveniet labore nihil aspernatur molestias itaque temporibus et harum.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(102, 1, 'HARIHAR-H-0102', 'house', 'House 102', 'Jaipur', '1232 Romaine Courts', 3, 1056, 6027.48, 'available', 'Modi adipisci minus alias iure voluptatem mollitia quidem dolor inventore provident iste quia assumenda.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(103, 1, 'HARIHAR-H-0103', 'house', 'House 103', 'Surat', '42906 Ziemann Shore Suite 529', 1, 779, 5960.66, 'available', 'Sed et voluptatem voluptas qui explicabo sit dolore explicabo consequatur a quis voluptatem possimus distinctio incidunt.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(104, 1, 'HARIHAR-H-0104', 'house', 'House 104', 'Pune', '224 Hegmann Orchard Suite 506', 2, 1082, 5904.44, 'available', 'Et ex in accusantium expedita repellendus dolores ab reprehenderit nihil voluptas quisquam.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(105, 1, 'HARIHAR-H-0105', 'house', 'House 105', 'Jaipur', '522 Dedric Skyway Apt. 900', 3, 2669, 1915.73, 'available', 'Molestiae exercitationem temporibus voluptas officia maxime tenetur qui similique dolores placeat laborum.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(106, 1, 'HARIHAR-H-0106', 'house', 'House 106', 'Pune', '31142 Schaden Pines', 4, 1317, 4685.03, 'available', 'Accusantium culpa enim excepturi tenetur optio fuga nisi doloribus ullam molestiae ipsa sunt voluptates dolor.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(107, 1, 'HARIHAR-H-0107', 'house', 'House 107', 'Surat', '6040 Gislason Bridge Suite 954', 5, 1386, 5337.60, 'available', 'Quisquam ea ut enim officiis ullam autem eaque eum in dolorum ut a quo.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(108, 1, 'HARIHAR-H-0108', 'house', 'House 108', 'Indore', '8884 Trenton Pines Apt. 179', 6, 1327, 5238.08, 'available', 'Non eaque quod quia aliquam debitis dolor neque et aut.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(109, 1, 'HARIHAR-H-0109', 'house', 'House 109', 'Mumbai', '248 Loraine Keys Suite 881', 3, 2159, 5395.14, 'available', 'Culpa beatae facilis facere incidunt hic ipsum totam sed enim eum dicta voluptatibus corrupti aperiam ut ratione.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(110, 1, 'HARIHAR-H-0110', 'house', 'House 110', 'Mumbai', '399 Peyton Locks', 6, 3753, 4723.38, 'available', 'Dolore praesentium omnis ut alias ducimus odio animi quam voluptatem corporis aut.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(111, 1, 'HARIHAR-H-0111', 'house', 'House 111', 'Pune', '928 Zetta Radial Suite 081', 5, 2644, 4342.08, 'available', 'Aut in impedit rerum blanditiis sit voluptas enim officiis dolorem ullam debitis voluptatum.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(112, 1, 'HARIHAR-H-0112', 'house', 'House 112', 'Jaipur', '802 Kunde Coves Suite 116', 6, 3613, 3631.26, 'booked', 'Cupiditate placeat nihil sit in enim consectetur qui optio laboriosam quis pariatur nobis.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(113, 1, 'HARIHAR-H-0113', 'house', 'House 113', 'Delhi', '1090 Schamberger Rest Suite 340', 4, 2630, 4536.04, 'available', 'Illo ab qui sapiente et facere ullam minima et ad sint neque aut adipisci vel nobis non.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(114, 1, 'HARIHAR-H-0114', 'house', 'House 114', 'Mumbai', '90679 Shayna Burgs', 2, 3229, 3882.43, 'available', 'Aspernatur tenetur ducimus saepe qui accusamus assumenda eaque molestias.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(115, 1, 'HARIHAR-H-0115', 'house', 'House 115', 'Jaipur', '380 Summer Village', 4, 2427, 1377.27, 'available', 'Dolores qui est exercitationem officiis doloribus sequi consequatur tenetur doloribus.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(116, 1, 'HARIHAR-H-0116', 'house', 'House 116', 'Jaipur', '60151 Tyler Orchard Suite 081', 6, 1176, 5851.42, 'available', 'Quas consectetur voluptatem ipsa officia sit explicabo id perferendis inventore aut adipisci excepturi accusamus quam est.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(117, 1, 'HARIHAR-H-0117', 'house', 'House 117', 'Ahmedabad', '477 Schaefer Way Apt. 928', 2, 2000, 5015.45, 'booked', 'Dolorem aut rerum quo distinctio harum sapiente voluptas ipsa voluptate amet earum.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(118, 1, 'HARIHAR-H-0118', 'house', 'House 118', 'Ahmedabad', '16304 Hagenes Forge', 5, 1329, 3598.54, 'available', 'Sint enim nesciunt natus et et dolor blanditiis qui.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(119, 1, 'HARIHAR-H-0119', 'house', 'House 119', 'Jaipur', '50964 Krajcik Haven Suite 360', 4, 1832, 1574.90, 'booked', 'Aspernatur doloribus quibusdam explicabo suscipit repellat maiores alias.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(120, 1, 'HARIHAR-H-0120', 'house', 'House 120', 'Jaipur', '8068 Fermin Field Suite 605', 6, 650, 6162.85, 'booked', 'Quam a porro officiis ut reiciendis suscipit cupiditate quia harum sunt molestiae aut sit est voluptas.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(121, 1, 'HARIHAR-S-0001', 'shop', 'Shop 1', 'Delhi', '4842 Doyle Neck', NULL, 317, 5057.63, 'available', 'Iure accusamus ratione ut illum quas atque necessitatibus ducimus eaque sed suscipit aut occaecati.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(122, 1, 'HARIHAR-S-0002', 'shop', 'Shop 2', 'Jaipur', '4263 Brant Ford', NULL, 947, 8175.32, 'available', 'Ut magni dolore et deleniti facere est delectus delectus odio porro.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(123, 1, 'HARIHAR-S-0003', 'shop', 'Shop 3', 'Ahmedabad', '268 Mossie Viaduct Apt. 301', NULL, 1117, 3482.98, 'booked', 'Quam qui modi aspernatur iste unde ut possimus.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(124, 1, 'HARIHAR-S-0004', 'shop', 'Shop 4', 'Ahmedabad', '6288 Kristy Forest', NULL, 519, 6524.44, 'available', 'Nesciunt et nobis ut enim omnis nostrum expedita qui fugiat modi.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(125, 1, 'HARIHAR-S-0005', 'shop', 'Shop 5', 'Mumbai', '582 Bayer Rapid Apt. 897', NULL, 917, 3515.77, 'available', 'Voluptatibus a aut voluptatem ullam modi quidem dicta eos.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(126, 1, 'HARIHAR-S-0006', 'shop', 'Shop 6', 'Surat', '42736 Aimee Ridges', NULL, 373, 5942.35, 'available', 'Dolores quod illum consequuntur voluptates architecto voluptatem repellendus est et accusamus.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(127, 1, 'HARIHAR-S-0007', 'shop', 'Shop 7', 'Delhi', '778 Clementina Common Suite 596', NULL, 1118, 1587.85, 'booked', 'Dolor magnam qui vel cupiditate ut eos accusantium quia possimus fugit quaerat.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(128, 1, 'HARIHAR-S-0008', 'shop', 'Shop 8', 'Jaipur', '784 Frederik Crossroad', NULL, 1152, 2749.38, 'booked', 'In excepturi rerum qui officia adipisci voluptas incidunt inventore asperiores ut ut qui architecto ad eius dolore.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(129, 1, 'HARIHAR-S-0009', 'shop', 'Shop 9', 'Surat', '8018 Irving Corner Apt. 790', NULL, 364, 6482.78, 'available', 'Veniam quod quod itaque et adipisci accusamus fugit cupiditate.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(130, 1, 'HARIHAR-S-0010', 'shop', 'Shop 10', 'Jaipur', '2780 Mertz Lake', NULL, 687, 5918.11, 'available', 'Iusto dignissimos accusamus saepe eum tenetur accusantium ut fugiat et ipsa facere minus iusto natus quo.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(131, 1, 'HARIHAR-S-0011', 'shop', 'Shop 11', 'Delhi', '1646 Deon Drive Suite 740', NULL, 906, 878.72, 'booked', 'Officiis earum maiores sequi minima ut consequuntur aut distinctio unde nulla in et qui.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(132, 1, 'HARIHAR-S-0012', 'shop', 'Shop 12', 'Surat', '101 Angus Park', NULL, 1108, 7079.06, 'available', 'Quo dolores harum quaerat ipsum molestiae exercitationem enim est facere.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(133, 1, 'HARIHAR-S-0013', 'shop', 'Shop 13', 'Indore', '1502 Hodkiewicz Lights', NULL, 620, 2333.09, 'booked', 'Voluptatem et atque quo libero ut soluta ipsam voluptate sunt voluptates distinctio dignissimos velit ratione.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(134, 1, 'HARIHAR-S-0014', 'shop', 'Shop 14', 'Delhi', '41894 Mandy Forks Apt. 835', NULL, 395, 2830.94, 'booked', 'Cupiditate consequatur mollitia qui aut cumque ipsa beatae rerum commodi maxime earum neque rem ut ut voluptatem.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(135, 1, 'HARIHAR-S-0015', 'shop', 'Shop 15', 'Delhi', '269 Cruickshank Union', NULL, 318, 3093.60, 'available', 'Itaque dolor commodi aut sint magni ea asperiores voluptatem et dolor.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(136, 1, 'HARIHAR-S-0016', 'shop', 'Shop 16', 'Delhi', '5607 Pearline Prairie', NULL, 262, 6010.68, 'booked', 'Omnis aut debitis ab autem exercitationem consequatur quisquam odit distinctio quibusdam eius laudantium eum ut iure.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(137, 1, 'HARIHAR-S-0017', 'shop', 'Shop 17', 'Pune', '1462 Howell Summit', NULL, 425, 8268.87, 'booked', 'Perspiciatis consequatur numquam sint error blanditiis id saepe rerum modi similique tempore nisi.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(138, 1, 'HARIHAR-S-0018', 'shop', 'Shop 18', 'Ahmedabad', '60141 Nelson Spring Suite 139', NULL, 219, 5835.35, 'available', 'Velit eos eum magnam veritatis voluptas possimus ex saepe ut corporis minus harum facere tempore autem.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(139, 1, 'HARIHAR-S-0019', 'shop', 'Shop 19', 'Indore', '3182 Paucek Forge Suite 312', NULL, 930, 4396.83, 'available', 'Ex officia reprehenderit veniam et distinctio explicabo quia sint necessitatibus corporis consectetur voluptatum.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(140, 1, 'HARIHAR-S-0020', 'shop', 'Shop 20', 'Ahmedabad', '829 Eichmann Gardens Apt. 623', NULL, 1047, 1438.05, 'booked', 'Ut ratione eligendi ullam numquam et ducimus iste.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(141, 1, 'HARIHAR-S-0021', 'shop', 'Shop 21', 'Surat', '60220 Pearl Crossing Apt. 458', NULL, 428, 5047.81, 'available', 'Itaque voluptatem aliquam necessitatibus est possimus nisi culpa animi quam rerum magni recusandae esse.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(142, 1, 'HARIHAR-S-0022', 'shop', 'Shop 22', 'Indore', '10052 Ruecker Canyon', NULL, 500, 6611.17, 'booked', 'Quis expedita harum et aut minima rerum cumque cumque possimus itaque sequi excepturi.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(143, 1, 'HARIHAR-S-0023', 'shop', 'Shop 23', 'Surat', '9366 Waters Pass', NULL, 388, 2783.62, 'available', 'Odio voluptate ea vel dolorem ipsum quas dolor temporibus molestiae enim ab reprehenderit qui corrupti nihil.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(144, 1, 'HARIHAR-S-0024', 'shop', 'Shop 24', 'Delhi', '254 Wilkinson Roads Suite 083', NULL, 1000, 3821.49, 'available', 'Quaerat maiores a quia aperiam eligendi iure excepturi ullam recusandae quia minus.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(145, 1, 'HARIHAR-S-0025', 'shop', 'Shop 25', 'Pune', '545 Forrest View', NULL, 847, 4070.81, 'available', 'Autem qui beatae quia dignissimos ab laboriosam eius quam non fugiat sequi facilis quod.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(146, 2, 'HARMONY-H-0001', 'house', 'House 1', 'Delhi', '10956 Darlene Lock Suite 653', 2, 3787, 4987.13, 'available', 'Officia qui temporibus amet voluptatum repellendus velit blanditiis.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(147, 2, 'HARMONY-H-0002', 'house', 'House 2', 'Surat', '7348 Schiller Drive Suite 316', 2, 1031, 2967.41, 'available', 'Vel sunt cumque quia iure nobis reiciendis dignissimos sit laborum nihil id sint quisquam velit non.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(148, 2, 'HARMONY-H-0003', 'house', 'House 3', 'Pune', '105 Kari Union Suite 739', 3, 3122, 2585.86, 'booked', 'Illum accusamus cupiditate quis ea assumenda illum nam vero reiciendis eos.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(149, 2, 'HARMONY-H-0004', 'house', 'House 4', 'Jaipur', '564 Herman Springs', 6, 2998, 1952.31, 'available', 'Impedit quia et assumenda rerum atque quidem aut dolores laudantium quia repellat nihil.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(150, 2, 'HARMONY-H-0005', 'house', 'House 5', 'Jaipur', '77373 Adams Springs', 1, 801, 3546.23, 'available', 'Voluptatem expedita qui quas quis dignissimos atque recusandae dolores est est similique assumenda sunt ut.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(151, 2, 'HARMONY-H-0006', 'house', 'House 6', 'Mumbai', '57120 Sanford Rapid', 2, 1686, 3438.60, 'booked', 'Magni beatae officia totam pariatur nam rerum aut sint itaque possimus.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(152, 2, 'HARMONY-H-0007', 'house', 'House 7', 'Mumbai', '752 Shanny Brook Apt. 884', 1, 2017, 2424.23, 'available', 'Alias ea vel architecto et quam amet repellendus quis sed labore maiores adipisci vel quia dolores.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(153, 2, 'HARMONY-H-0008', 'house', 'House 8', 'Pune', '122 Williamson Brook', 2, 866, 2966.73, 'booked', 'Alias architecto est ex voluptates quidem laudantium et.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(154, 2, 'HARMONY-H-0009', 'house', 'House 9', 'Indore', '71778 Stehr Lakes', 2, 2313, 3406.01, 'available', 'Officiis et qui velit eos culpa delectus corporis ut eius doloremque officia aspernatur sit.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(155, 2, 'HARMONY-H-0010', 'house', 'House 10', 'Jaipur', '3223 Nelle Cliffs', 2, 725, 3189.24, 'available', 'Sunt aut qui laboriosam est accusantium iusto illum repudiandae sed distinctio sunt beatae dolorem sunt.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(156, 2, 'HARMONY-H-0011', 'house', 'House 11', 'Delhi', '5366 Walter Estates Apt. 605', 6, 918, 5945.22, 'booked', 'Sunt vel omnis occaecati ducimus odio in non ut eius saepe et id temporibus natus.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(157, 2, 'HARMONY-H-0012', 'house', 'House 12', 'Pune', '256 Blanda Road', 6, 3131, 5783.49, 'available', 'Harum ipsa molestiae numquam saepe iste aut sit molestias ab possimus molestiae iure dicta esse exercitationem.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(158, 2, 'HARMONY-H-0013', 'house', 'House 13', 'Jaipur', '776 Reid Mountains Suite 570', 2, 3406, 6091.34, 'booked', 'Dicta sit fugiat voluptatibus eum quasi rerum iste velit consequatur doloremque et magni sint fugit.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(159, 2, 'HARMONY-H-0014', 'house', 'House 14', 'Surat', '291 Schaden Views Suite 437', 4, 1899, 5093.58, 'booked', 'Dolor nam error ea non beatae molestias autem quaerat et corporis.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(160, 2, 'HARMONY-H-0015', 'house', 'House 15', 'Mumbai', '15927 Rowe Stravenue Suite 989', 2, 2080, 2520.03, 'available', 'Mollitia nobis sequi autem quod architecto sint atque ipsam.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(161, 2, 'HARMONY-H-0016', 'house', 'House 16', 'Surat', '420 Hackett Keys Suite 734', 3, 1004, 5660.11, 'available', 'Culpa ducimus dicta rerum mollitia corrupti nostrum voluptatibus molestias voluptatem alias assumenda doloribus ea est omnis.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(162, 2, 'HARMONY-H-0017', 'house', 'House 17', 'Delhi', '53855 Frederique Track', 3, 762, 5225.97, 'booked', 'Impedit odio nesciunt aut placeat eius fugit minus eos.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(163, 2, 'HARMONY-H-0018', 'house', 'House 18', 'Indore', '3699 Axel Court Apt. 442', 5, 2163, 2543.71, 'available', 'Eos porro quia voluptatem consectetur necessitatibus id et.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(164, 2, 'HARMONY-H-0019', 'house', 'House 19', 'Delhi', '83765 Okuneva Well', 2, 3676, 3757.12, 'available', 'Exercitationem voluptatem quaerat est eligendi pariatur quos amet vel soluta rem.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(165, 2, 'HARMONY-H-0020', 'house', 'House 20', 'Mumbai', '543 Carroll Prairie Apt. 421', 1, 2411, 5504.29, 'booked', 'Et excepturi commodi quia nihil voluptatibus nam reiciendis qui.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(166, 2, 'HARMONY-H-0021', 'house', 'House 21', 'Indore', '2108 Rau Lakes Apt. 965', 1, 1836, 3178.50, 'booked', 'Laboriosam vel vitae architecto ut sunt molestiae itaque voluptate qui omnis voluptatem.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(167, 2, 'HARMONY-H-0022', 'house', 'House 22', 'Surat', '74201 Volkman Junctions', 1, 646, 4729.90, 'booked', 'Alias voluptas ducimus eum qui omnis ut omnis similique et.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(168, 2, 'HARMONY-H-0023', 'house', 'House 23', 'Ahmedabad', '6274 Abelardo Island', 1, 3480, 4482.04, 'available', 'Dignissimos minus harum eaque et et non et itaque eos odio totam omnis in deleniti fugit.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(169, 2, 'HARMONY-H-0024', 'house', 'House 24', 'Indore', '90120 Tillman Port', 6, 1318, 6048.65, 'booked', 'Quas molestias dignissimos voluptatem voluptas rem temporibus excepturi deserunt architecto optio omnis consequatur.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(170, 2, 'HARMONY-H-0025', 'house', 'House 25', 'Ahmedabad', '9597 Friesen Knoll Suite 878', 5, 3742, 6022.85, 'available', 'Accusamus odit non dolorum aut explicabo dolor magnam aut quisquam voluptatem consequuntur sed quia incidunt.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(171, 2, 'HARMONY-H-0026', 'house', 'House 26', 'Jaipur', '602 Schamberger Spring Suite 491', 2, 3702, 5333.84, 'booked', 'Beatae ut cumque necessitatibus et sit cupiditate dolor.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(172, 2, 'HARMONY-H-0027', 'house', 'House 27', 'Surat', '4500 Volkman Lake', 3, 2310, 3696.92, 'available', 'Eligendi ab in nemo sint blanditiis harum et nostrum dolores quia perspiciatis.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(173, 2, 'HARMONY-H-0028', 'house', 'House 28', 'Surat', '8331 Chyna Glen', 1, 2162, 6242.05, 'available', 'Qui consequuntur voluptatibus expedita quam accusantium non consectetur est eligendi facere deserunt quos et ut et.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(174, 2, 'HARMONY-H-0029', 'house', 'House 29', 'Jaipur', '781 Adella Island Suite 517', 4, 1629, 1876.16, 'available', 'Non enim rerum at dicta eius tempora sit ducimus expedita qui ex.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(175, 2, 'HARMONY-H-0030', 'house', 'House 30', 'Delhi', '76501 Krystal Square', 3, 3568, 5061.06, 'available', 'Perferendis ex aperiam provident velit qui repudiandae aut sed sapiente est et odit est maxime qui.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(176, 2, 'HARMONY-H-0031', 'house', 'House 31', 'Pune', '3794 Marks Squares Apt. 286', 2, 1969, 5466.93, 'booked', 'Et distinctio velit sit molestiae dolor ut blanditiis in ipsa.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(177, 2, 'HARMONY-H-0032', 'house', 'House 32', 'Mumbai', '923 Grimes Garden', 2, 2129, 4706.01, 'available', 'Doloremque doloribus velit tenetur voluptas qui modi sint ab vel et doloremque est.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(178, 2, 'HARMONY-H-0033', 'house', 'House 33', 'Jaipur', '67864 Ruecker Canyon Apt. 015', 3, 2810, 2019.78, 'available', 'Sequi dolorem sint ipsam et voluptatem blanditiis praesentium quibusdam error nemo.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(179, 2, 'HARMONY-H-0034', 'house', 'House 34', 'Mumbai', '275 Rahul Lake Apt. 748', 3, 1239, 4146.19, 'available', 'Facilis provident fugiat maxime praesentium praesentium dignissimos vel nam incidunt.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(180, 2, 'HARMONY-H-0035', 'house', 'House 35', 'Jaipur', '7149 Sabryna Crossing Suite 178', 2, 2357, 2670.08, 'booked', 'Aspernatur voluptate neque minus et praesentium vero rem ut.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(181, 2, 'HARMONY-H-0036', 'house', 'House 36', 'Surat', '92233 Morar Lane Suite 036', 3, 2974, 2492.45, 'booked', 'Nesciunt illum aut quae illo quia vero qui accusantium mollitia necessitatibus aliquid nemo.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(182, 2, 'HARMONY-H-0037', 'house', 'House 37', 'Indore', '6604 Hills Tunnel Apt. 073', 3, 2159, 1445.76, 'booked', 'Dignissimos unde temporibus vel modi veritatis sit corporis rerum temporibus laudantium error et ducimus ea.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(183, 2, 'HARMONY-H-0038', 'house', 'House 38', 'Jaipur', '86492 Schamberger Course', 1, 768, 1366.56, 'available', 'Perspiciatis ipsum similique odit sit voluptatum velit qui.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(184, 2, 'HARMONY-H-0039', 'house', 'House 39', 'Delhi', '693 Beulah Ridge', 3, 2695, 5730.26, 'booked', 'Repudiandae reiciendis quisquam culpa aperiam placeat velit est placeat et maxime ut quam.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(185, 2, 'HARMONY-H-0040', 'house', 'House 40', 'Jaipur', '165 Nettie Canyon Suite 495', 4, 1665, 5806.19, 'booked', 'Voluptatem enim adipisci omnis perferendis earum nesciunt fugiat unde voluptate corporis ex consequatur.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(186, 2, 'HARMONY-H-0041', 'house', 'House 41', 'Indore', '1690 Lehner Path', 5, 3265, 3859.01, 'available', 'Eligendi maiores error et nihil asperiores excepturi et dolores laborum nihil delectus.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(187, 2, 'HARMONY-H-0042', 'house', 'House 42', 'Pune', '3055 Marcel Terrace Apt. 751', 5, 3035, 3084.47, 'available', 'Eos deleniti error voluptatibus in voluptatem quos nobis sapiente ut ut ratione.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(188, 2, 'HARMONY-H-0043', 'house', 'House 43', 'Jaipur', '90776 Zulauf Park', 5, 2772, 1423.44, 'booked', 'Fuga ratione autem hic consequatur quasi quasi quis impedit rerum exercitationem quis ad doloribus consequatur eos.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(189, 2, 'HARMONY-H-0044', 'house', 'House 44', 'Ahmedabad', '52564 Erich Tunnel', 4, 2231, 3236.07, 'booked', 'Culpa consequatur quos enim voluptas placeat et dolor ex itaque molestias impedit eaque eum optio harum dolores.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(190, 2, 'HARMONY-H-0045', 'house', 'House 45', 'Mumbai', '7464 Lebsack View Suite 856', 2, 2007, 5408.86, 'available', 'Consequuntur laborum quia enim dolorum harum delectus nemo voluptatibus dolorem illum dolores officiis eos culpa ut sunt.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(191, 2, 'HARMONY-H-0046', 'house', 'House 46', 'Pune', '80566 Ullrich Stravenue Suite 577', 6, 2321, 4052.69, 'available', 'Qui maiores reprehenderit quia vel et et nam nulla et qui.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(192, 2, 'HARMONY-H-0047', 'house', 'House 47', 'Surat', '908 Durgan Gardens Apt. 494', 5, 1358, 4621.31, 'available', 'Sit perspiciatis ut id qui ex ratione fugit libero dolores nesciunt assumenda porro autem.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(193, 2, 'HARMONY-H-0048', 'house', 'House 48', 'Delhi', '876 Einar Pines', 3, 1812, 6326.45, 'booked', 'Maxime eum nostrum nesciunt aut corrupti voluptatem enim minima.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(194, 2, 'HARMONY-H-0049', 'house', 'House 49', 'Surat', '29977 Sterling Light', 2, 1898, 3991.84, 'available', 'Repudiandae et quia eos tenetur cum animi modi facere voluptatem voluptatem error omnis dolores.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(195, 2, 'HARMONY-H-0050', 'house', 'House 50', 'Mumbai', '239 Schmeler Terrace Apt. 651', 4, 3729, 6336.52, 'booked', 'Et aut saepe possimus aspernatur sunt aliquid qui ducimus impedit est exercitationem molestias occaecati.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(196, 2, 'HARMONY-H-0051', 'house', 'House 51', 'Surat', '87860 Cristina Parkway', 4, 3260, 4848.54, 'available', 'Aut rerum quae autem repellendus vel sit impedit qui voluptas doloribus excepturi ipsa molestiae sunt.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(197, 2, 'HARMONY-H-0052', 'house', 'House 52', 'Mumbai', '47241 Rowan Roads Apt. 899', 6, 3160, 5780.41, 'booked', 'Nisi voluptatibus ut consequatur sit quia tempore repellat voluptate ipsa est natus ab.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(198, 2, 'HARMONY-H-0053', 'house', 'House 53', 'Jaipur', '695 Reichel Manor Suite 073', 1, 1739, 5536.01, 'available', 'Expedita eum deleniti voluptas dolor voluptas sapiente nam animi eum et quis voluptatem facere non accusamus omnis.', '2026-04-09 07:08:13', '2026-04-09 07:08:13');
INSERT INTO `properties` (`id`, `project_id`, `code`, `type`, `title`, `city`, `address`, `bedrooms`, `area_sqft`, `price_per_day`, `status`, `description`, `created_at`, `updated_at`) VALUES
(199, 2, 'HARMONY-H-0054', 'house', 'House 54', 'Jaipur', '913 O\'Keefe Viaduct', 4, 3394, 5029.02, 'available', 'Sint sequi voluptas voluptatem perferendis iste quasi quas atque tempora ut non magni quas.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(200, 2, 'HARMONY-H-0055', 'house', 'House 55', 'Surat', '682 Roxanne Way', 3, 1753, 1701.76, 'available', 'Explicabo voluptatem maiores consectetur consequatur laudantium placeat enim aut aut consequatur.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(201, 2, 'HARMONY-S-0001', 'shop', 'Shop 1', 'Surat', '73436 Arnoldo Mountain Apt. 074', NULL, 221, 2912.22, 'booked', 'Eius nostrum eum tempora facere voluptas est qui rerum sapiente facilis labore at.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(202, 2, 'HARMONY-S-0002', 'shop', 'Shop 2', 'Indore', '8639 Kris Corners Apt. 594', NULL, 766, 1133.08, 'available', 'Ut id autem et omnis numquam nam ipsa vel laudantium totam reiciendis non sequi magni ea.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(203, 2, 'HARMONY-S-0003', 'shop', 'Shop 3', 'Mumbai', '8748 Cristian Creek', NULL, 829, 5179.70, 'available', 'Dolorem repellat cupiditate et sapiente laborum modi qui odit id dolores repudiandae tempora.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(204, 2, 'HARMONY-S-0004', 'shop', 'Shop 4', 'Indore', '9914 Jordan Row', NULL, 950, 3628.97, 'booked', 'Inventore nemo molestiae numquam qui qui enim voluptatem nobis ex nihil qui possimus quibusdam blanditiis quo.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(205, 2, 'HARMONY-S-0005', 'shop', 'Shop 5', 'Mumbai', '80817 Larson Street', NULL, 1162, 2105.32, 'available', 'Ad incidunt cumque consequuntur asperiores pariatur a quis dolorem modi soluta consectetur alias fuga consequuntur et.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(206, 2, 'HARMONY-S-0006', 'shop', 'Shop 6', 'Indore', '26218 Marks Flat Apt. 088', NULL, 888, 6768.88, 'available', 'Consequatur doloribus ipsa incidunt dolores odio qui at omnis quam hic sit.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(207, 2, 'HARMONY-S-0007', 'shop', 'Shop 7', 'Surat', '37901 Alford Spur', NULL, 473, 2113.42, 'available', 'Quia recusandae voluptates non non magni voluptatem debitis quaerat dolor.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(208, 2, 'HARMONY-S-0008', 'shop', 'Shop 8', 'Surat', '63018 Hammes Oval Suite 050', NULL, 354, 6505.12, 'available', 'Et quidem blanditiis est corporis mollitia officia rem iure dolorem nostrum consequatur consequuntur earum vitae omnis.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(209, 2, 'HARMONY-S-0009', 'shop', 'Shop 9', 'Mumbai', '2525 Madilyn Branch Apt. 999', NULL, 1164, 6563.52, 'available', 'Consequatur similique a qui quibusdam eius enim quam ut et adipisci.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(210, 2, 'HARMONY-S-0010', 'shop', 'Shop 10', 'Surat', '29908 Towne Cove Apt. 048', NULL, 1046, 4799.02, 'available', 'Reprehenderit aspernatur ut aliquam est aut cum ut ratione.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(211, 2, 'HARMONY-S-0011', 'shop', 'Shop 11', 'Pune', '5486 Candelario Groves', NULL, 530, 846.09, 'booked', 'Unde nobis labore rem beatae excepturi id accusantium facilis debitis nulla quam et dolorem voluptatem sit.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(212, 2, 'HARMONY-S-0012', 'shop', 'Shop 12', 'Pune', '58973 Andreanne Plaza', NULL, 638, 8197.33, 'booked', 'Iusto consectetur voluptatem quia accusantium voluptatem quis ullam facilis nemo ut ducimus repellendus quod voluptatem quod dolor.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(213, 2, 'HARMONY-S-0013', 'shop', 'Shop 13', 'Surat', '175 Shanahan Isle Apt. 577', NULL, 1053, 8643.90, 'available', 'Impedit sed ratione excepturi consequuntur velit qui et perspiciatis ducimus.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(214, 2, 'HARMONY-S-0014', 'shop', 'Shop 14', 'Indore', '448 Prosacco Forge', NULL, 427, 2272.71, 'available', 'Tempora magni necessitatibus molestiae enim non corrupti facere vitae qui harum nemo incidunt assumenda quam quibusdam.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(215, 2, 'HARMONY-S-0015', 'shop', 'Shop 15', 'Ahmedabad', '400 Alfreda Neck', NULL, 732, 1603.06, 'available', 'Nemo error laboriosam officiis in tempora iusto exercitationem earum.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(216, 3, 'SWASTIC-H-0001', 'house', 'House 1', 'Jaipur', '70161 Jaycee Mission Apt. 403', 4, 3768, 3341.36, 'booked', 'Voluptatem labore a perferendis et architecto quia ullam ut et labore quas non sapiente adipisci labore ut.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(217, 3, 'SWASTIC-H-0002', 'house', 'House 2', 'Jaipur', '516 Hugh Ville', 1, 915, 4667.21, 'available', 'Qui nulla architecto ratione sed quasi sit eum laborum porro ut ipsum illo est.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(218, 3, 'SWASTIC-H-0003', 'house', 'House 3', 'Delhi', '83517 Trantow Inlet Apt. 405', 4, 2427, 1793.40, 'booked', 'Qui consequatur enim repudiandae est explicabo quos sequi at reiciendis.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(219, 3, 'SWASTIC-H-0004', 'house', 'House 4', 'Mumbai', '50763 Katrine Way Apt. 887', 5, 2875, 2282.69, 'booked', 'Qui est ullam in expedita iste alias eum rerum et odio.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(220, 3, 'SWASTIC-H-0005', 'house', 'House 5', 'Pune', '664 Morissette Bypass Apt. 868', 5, 3712, 4239.91, 'available', 'Ut quos quod voluptatem quo dignissimos eligendi voluptate maxime.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(221, 3, 'SWASTIC-H-0006', 'house', 'House 6', 'Jaipur', '114 Lizeth Union Suite 723', 1, 2700, 2909.64, 'booked', 'Culpa aut voluptas praesentium reiciendis dolore ut ullam veritatis ab eum amet explicabo ullam.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(222, 3, 'SWASTIC-H-0007', 'house', 'House 7', 'Jaipur', '63012 Johns Branch Suite 204', 1, 2297, 4924.57, 'available', 'Enim reiciendis beatae optio sint fuga quod dolorum modi est sed iure sit recusandae consequatur.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(223, 3, 'SWASTIC-H-0008', 'house', 'House 8', 'Jaipur', '909 Braun Well', 3, 2004, 5781.58, 'booked', 'Ea et facilis aut laudantium mollitia incidunt quam cupiditate itaque iure eum at dolorum omnis.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(224, 3, 'SWASTIC-H-0009', 'house', 'House 9', 'Ahmedabad', '4549 Ortiz Stravenue Suite 180', 3, 1957, 2890.49, 'available', 'Odio nulla hic iste voluptatem deserunt illum dolor ut doloremque et ad.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(225, 3, 'SWASTIC-H-0010', 'house', 'House 10', 'Pune', '78083 Deion Curve Apt. 389', 5, 3048, 2364.34, 'booked', 'Non voluptatem error quo labore eius cupiditate ullam ut voluptas quasi ab cum non repudiandae aut.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(226, 3, 'SWASTIC-H-0011', 'house', 'House 11', 'Jaipur', '318 Marisa Cove', 1, 3217, 5972.59, 'booked', 'Debitis vero temporibus ipsum consequatur nam laborum omnis magnam odio.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(227, 3, 'SWASTIC-H-0012', 'house', 'House 12', 'Surat', '85790 Erica Hills Suite 321', 1, 766, 2529.09, 'available', 'Illum velit rerum dolorem nihil quasi id quis.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(228, 3, 'SWASTIC-H-0013', 'house', 'House 13', 'Delhi', '751 Murazik Lights', 2, 2122, 2884.73, 'booked', 'Qui nisi aliquid animi necessitatibus ipsa eveniet doloribus.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(229, 3, 'SWASTIC-H-0014', 'house', 'House 14', 'Surat', '814 Ullrich Fall', 4, 1620, 2750.53, 'available', 'Rerum fuga cupiditate eos rerum quae sunt tempora libero laborum minus provident dolores omnis ea est.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(230, 3, 'SWASTIC-H-0015', 'house', 'House 15', 'Mumbai', '174 Howell Oval', 2, 3413, 5594.67, 'available', 'Est quos explicabo velit qui sequi quis molestiae nulla fuga doloribus adipisci eveniet inventore.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(231, 3, 'SWASTIC-H-0016', 'house', 'House 16', 'Jaipur', '43555 Gleichner Crossing', 4, 1502, 6452.79, 'available', 'Incidunt dignissimos non quas illo ab nobis velit perspiciatis beatae veniam maiores tempora vero est fuga.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(232, 3, 'SWASTIC-H-0017', 'house', 'House 17', 'Jaipur', '427 Stefan Well', 4, 3768, 4424.73, 'booked', 'Dolor quisquam qui veniam dolore dicta culpa fuga ipsum ipsa sint dolorem expedita ut.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(233, 3, 'SWASTIC-H-0018', 'house', 'House 18', 'Delhi', '5521 Arielle Dale Apt. 695', 5, 3045, 5080.98, 'available', 'Voluptas quia fugit libero nihil totam dignissimos dolores sint dolor.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(234, 3, 'SWASTIC-H-0019', 'house', 'House 19', 'Indore', '8713 Otto Mount', 6, 3289, 4802.37, 'booked', 'Expedita nam omnis accusamus et dignissimos et eligendi dolorum possimus nostrum neque.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(235, 3, 'SWASTIC-H-0020', 'house', 'House 20', 'Indore', '178 Lionel Station Suite 764', 2, 1162, 6092.98, 'booked', 'Nisi voluptatum accusantium labore impedit non et vero alias et id doloribus accusantium quod consequatur.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(236, 3, 'SWASTIC-H-0021', 'house', 'House 21', 'Jaipur', '138 Kshlerin Divide Suite 678', 5, 3509, 3067.24, 'available', 'Nisi unde officia enim numquam reiciendis ad modi cum hic eaque architecto consequatur ea sed quas.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(237, 3, 'SWASTIC-H-0022', 'house', 'House 22', 'Jaipur', '643 Batz Groves', 5, 722, 2077.79, 'available', 'Enim rerum reprehenderit corrupti ut rerum sit necessitatibus omnis repellendus delectus odio libero quod.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(238, 3, 'SWASTIC-H-0023', 'house', 'House 23', 'Surat', '46973 Trantow Villages', 2, 3232, 2253.99, 'available', 'Quia non accusantium pariatur voluptas error ut voluptatem.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(239, 3, 'SWASTIC-H-0024', 'house', 'House 24', 'Ahmedabad', '34667 Donnelly Parkway Apt. 350', 5, 2378, 5221.51, 'available', 'Doloremque harum quas eos eaque modi et enim corrupti doloremque ab quam animi.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(240, 3, 'SWASTIC-H-0025', 'house', 'House 25', 'Jaipur', '50703 Cynthia Island', 5, 1342, 3429.61, 'available', 'Voluptatum ea delectus perspiciatis aut veniam odio mollitia odio dolor occaecati.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(241, 3, 'SWASTIC-S-0001', 'shop', 'Shop 1', 'Jaipur', '73979 Bailey Unions', NULL, 774, 4211.26, 'available', 'Eos repellendus nemo doloribus vel eos cumque et voluptas omnis quasi et nesciunt sit quod praesentium nisi.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(242, 3, 'SWASTIC-S-0002', 'shop', 'Shop 2', 'Ahmedabad', '8458 Mitchell Route', NULL, 208, 4674.48, 'booked', 'Vel ut aliquam esse tempora quibusdam molestias aspernatur est quidem quo.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(243, 3, 'SWASTIC-S-0003', 'shop', 'Shop 3', 'Jaipur', '29817 Davis Hill', NULL, 587, 3708.22, 'available', 'Culpa atque est pariatur enim ut pariatur sunt ad quam et eius dolorem.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(244, 3, 'SWASTIC-S-0004', 'shop', 'Shop 4', 'Indore', '74792 Merle Stream', NULL, 834, 1643.87, 'available', 'Qui nam dolorem necessitatibus iusto atque laborum quibusdam dolores tempore neque vel.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(245, 3, 'SWASTIC-S-0005', 'shop', 'Shop 5', 'Pune', '17453 Kemmer Corners', NULL, 845, 4902.53, 'available', 'Nihil doloremque iusto vel nulla nulla sed nobis.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(246, 3, 'SWASTIC-S-0006', 'shop', 'Shop 6', 'Jaipur', '43929 Antwan Mill', NULL, 1017, 6841.37, 'available', 'Harum perferendis sunt est voluptas velit modi voluptatem repudiandae dolores commodi similique.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(247, 3, 'SWASTIC-S-0007', 'shop', 'Shop 7', 'Mumbai', '120 Christa Parkway', NULL, 1162, 7217.49, 'available', 'Ipsam quisquam consectetur corrupti et doloribus ut et explicabo a ullam earum.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(248, 3, 'SWASTIC-S-0008', 'shop', 'Shop 8', 'Mumbai', '928 McGlynn Burgs Suite 708', NULL, 537, 6889.74, 'booked', 'Suscipit iure quasi deleniti rem aliquid sunt maiores et perferendis est qui quia itaque ad voluptatum.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(249, 3, 'SWASTIC-S-0009', 'shop', 'Shop 9', 'Delhi', '804 Blanda Freeway Suite 085', NULL, 656, 2632.35, 'available', 'Molestias cupiditate quidem temporibus dolor laborum laboriosam dolorem vel nihil assumenda placeat in iusto deleniti est ullam.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(250, 3, 'SWASTIC-S-0010', 'shop', 'Shop 10', 'Ahmedabad', '54541 Tiana Mountains Suite 070', NULL, 1107, 1626.00, 'available', 'Nobis eum dolorem quis dolor non non est voluptas non ipsa architecto aut reprehenderit recusandae.', '2026-04-09 07:08:13', '2026-04-09 07:08:13'),
(251, 4, 'MANOKAMNA-H-0001', 'house', 'House 1', NULL, NULL, 2, 900, 0.00, 'available', NULL, '2026-04-09 07:28:33', '2026-04-09 07:28:33'),
(252, 4, 'MANOKAMNA-H-0002', 'house', 'House 2', NULL, NULL, 2, 900, 0.00, 'available', NULL, '2026-04-09 07:28:33', '2026-04-09 07:28:33'),
(253, 4, 'MANOKAMNA-H-0003', 'house', 'House 3', NULL, NULL, 2, 900, 0.00, 'available', NULL, '2026-04-09 07:28:33', '2026-04-09 07:28:33'),
(254, 4, 'MANOKAMNA-H-0004', 'house', 'House 4', NULL, NULL, 2, 900, 0.00, 'available', NULL, '2026-04-09 07:28:33', '2026-04-09 07:28:33'),
(255, 4, 'MANOKAMNA-H-0005', 'house', 'House 5', NULL, NULL, 2, 900, 0.00, 'available', NULL, '2026-04-09 07:28:33', '2026-04-09 07:28:33'),
(256, 4, 'MANOKAMNA-H-0006', 'house', 'House 6', NULL, NULL, 2, 900, 0.00, 'available', NULL, '2026-04-09 07:28:33', '2026-04-09 07:28:33'),
(257, 4, 'MANOKAMNA-H-0007', 'house', 'House 7', NULL, NULL, 2, 900, 0.00, 'available', NULL, '2026-04-09 07:28:33', '2026-04-09 07:28:33'),
(258, 4, 'MANOKAMNA-H-0008', 'house', 'House 8', NULL, NULL, 2, 900, 0.00, 'available', NULL, '2026-04-09 07:28:33', '2026-04-09 07:28:33'),
(259, 4, 'MANOKAMNA-H-0009', 'house', 'House 9', NULL, NULL, 2, 900, 2300000.00, 'available', NULL, '2026-04-09 07:28:33', '2026-04-09 07:36:13'),
(260, 4, 'MANOKAMNA-H-0010', 'house', 'House 10', NULL, NULL, 2, 900, 0.00, 'available', NULL, '2026-04-09 07:28:33', '2026-04-09 07:28:33'),
(261, 4, 'MANOKAMNA-S-0001', 'shop', 'Shop 1', NULL, NULL, NULL, 350, 0.00, 'available', NULL, '2026-04-09 07:28:33', '2026-04-09 07:28:33'),
(262, 4, 'MANOKAMNA-S-0002', 'shop', 'Shop 2', NULL, NULL, NULL, 350, 0.00, 'available', NULL, '2026-04-09 07:28:33', '2026-04-09 07:28:33'),
(263, 4, 'MANOKAMNA-S-0003', 'shop', 'Shop 3', NULL, NULL, NULL, 350, 0.00, 'available', NULL, '2026-04-09 07:28:33', '2026-04-09 07:28:33'),
(264, 4, 'MANOKAMNA-S-0004', 'shop', 'Shop 4', NULL, NULL, NULL, 350, 0.00, 'available', NULL, '2026-04-09 07:28:33', '2026-04-09 07:28:33'),
(265, 4, 'MANOKAMNA-S-0005', 'shop', 'Shop 5', NULL, NULL, NULL, 350, 0.00, 'available', NULL, '2026-04-09 07:28:33', '2026-04-09 07:28:33'),
(266, 5, 'SWASTIK-H-0001', 'house', 'House 1', NULL, NULL, 2, 900, 0.00, 'booked', NULL, '2026-04-14 10:38:50', '2026-04-14 12:20:41'),
(267, 5, 'SWASTIK-H-0002', 'house', 'House 2', NULL, NULL, 2, 900, 0.00, 'booked', NULL, '2026-04-14 10:38:50', '2026-04-14 12:20:20'),
(268, 5, 'SWASTIK-H-0003', 'house', 'House 3', NULL, NULL, 2, 900, 0.00, 'available', NULL, '2026-04-14 10:38:50', '2026-04-14 10:38:50'),
(269, 5, 'SWASTIK-H-0004', 'house', 'House 4', NULL, NULL, 2, 900, 0.00, 'available', NULL, '2026-04-14 10:38:50', '2026-04-14 10:38:50'),
(270, 5, 'SWASTIK-H-0005', 'house', 'House 5', NULL, NULL, 2, 900, 0.00, 'available', NULL, '2026-04-14 10:38:50', '2026-04-14 10:38:50'),
(271, 5, 'SWASTIK-H-0006', 'house', 'House 6', NULL, NULL, 2, 900, 0.00, 'available', NULL, '2026-04-14 10:38:50', '2026-04-14 10:38:50'),
(272, 5, 'SWASTIK-H-0007', 'house', 'House 7', NULL, NULL, 2, 900, 0.00, 'available', NULL, '2026-04-14 10:38:50', '2026-04-14 10:38:50'),
(273, 5, 'SWASTIK-H-0008', 'house', 'House 8', NULL, NULL, 2, 900, 0.00, 'available', NULL, '2026-04-14 10:38:50', '2026-04-14 10:38:50'),
(274, 5, 'SWASTIK-H-0009', 'house', 'House 9', NULL, NULL, 2, 900, 0.00, 'available', NULL, '2026-04-14 10:38:50', '2026-04-14 10:38:50'),
(275, 5, 'SWASTIK-H-0010', 'house', 'House 10', NULL, NULL, 2, 900, 2300000.00, 'booked', NULL, '2026-04-14 10:38:50', '2026-04-14 12:20:46'),
(276, 5, 'SWASTIK-S-0001', 'shop', 'Shop 1', NULL, NULL, NULL, 350, 0.00, 'available', NULL, '2026-04-14 10:38:50', '2026-04-14 10:38:50'),
(277, 5, 'SWASTIK-S-0002', 'shop', 'Shop 2', NULL, NULL, NULL, 350, 0.00, 'available', NULL, '2026-04-14 10:38:50', '2026-04-14 10:38:50'),
(278, 5, 'SWASTIK-S-0003', 'shop', 'Shop 3', NULL, NULL, NULL, 350, 0.00, 'available', NULL, '2026-04-14 10:38:50', '2026-04-14 10:38:50'),
(279, 5, 'SWASTIK-S-0004', 'shop', 'Shop 4', NULL, NULL, NULL, 350, 0.00, 'available', NULL, '2026-04-14 10:38:50', '2026-04-14 10:38:50'),
(280, 5, 'SWASTIK-S-0005', 'shop', 'Shop 5', NULL, NULL, NULL, 350, 0.00, 'available', NULL, '2026-04-14 10:38:50', '2026-04-14 10:38:50');

-- --------------------------------------------------------

--
-- Table structure for table `raw_data_files`
--

DROP TABLE IF EXISTS `raw_data_files`;
CREATE TABLE IF NOT EXISTS `raw_data_files` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stored_upload` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stored_derived_csv` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `month` tinyint UNSIGNED DEFAULT NULL,
  `year` smallint UNSIGNED DEFAULT NULL,
  `file_type` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source_type` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `rules_applied_at` timestamp NULL DEFAULT NULL,
  `status` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'imported',
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
  `source_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seller_gstin` text COLLATE utf8mb4_unicode_ci,
  `invoice_number` text COLLATE utf8mb4_unicode_ci,
  `invoice_date` text COLLATE utf8mb4_unicode_ci,
  `transaction_type` text COLLATE utf8mb4_unicode_ci,
  `order_id` text COLLATE utf8mb4_unicode_ci,
  `shipment_id` text COLLATE utf8mb4_unicode_ci,
  `shipment_date` text COLLATE utf8mb4_unicode_ci,
  `order_date` text COLLATE utf8mb4_unicode_ci,
  `shipment_item_id` text COLLATE utf8mb4_unicode_ci,
  `quantity` text COLLATE utf8mb4_unicode_ci,
  `item_description` text COLLATE utf8mb4_unicode_ci,
  `asin` text COLLATE utf8mb4_unicode_ci,
  `hsn_sac` text COLLATE utf8mb4_unicode_ci,
  `sku` text COLLATE utf8mb4_unicode_ci,
  `product_name` text COLLATE utf8mb4_unicode_ci,
  `product_tax_code` text COLLATE utf8mb4_unicode_ci,
  `bill_from_city` text COLLATE utf8mb4_unicode_ci,
  `bill_from_state` text COLLATE utf8mb4_unicode_ci,
  `bill_from_country` text COLLATE utf8mb4_unicode_ci,
  `bill_from_postal_code` text COLLATE utf8mb4_unicode_ci,
  `ship_from_city` text COLLATE utf8mb4_unicode_ci,
  `ship_from_state` text COLLATE utf8mb4_unicode_ci,
  `ship_from_country` text COLLATE utf8mb4_unicode_ci,
  `ship_from_postal_code` text COLLATE utf8mb4_unicode_ci,
  `ship_to_city` text COLLATE utf8mb4_unicode_ci,
  `ship_to_state` text COLLATE utf8mb4_unicode_ci,
  `ship_to_country` text COLLATE utf8mb4_unicode_ci,
  `ship_to_postal_code` text COLLATE utf8mb4_unicode_ci,
  `invoice_amount` text COLLATE utf8mb4_unicode_ci,
  `tax_exclusive_gross` text COLLATE utf8mb4_unicode_ci,
  `total_tax_amount` text COLLATE utf8mb4_unicode_ci,
  `cgst_rate` text COLLATE utf8mb4_unicode_ci,
  `sgst_rate` text COLLATE utf8mb4_unicode_ci,
  `utgst_rate` text COLLATE utf8mb4_unicode_ci,
  `igst_rate` text COLLATE utf8mb4_unicode_ci,
  `compensatory_cess_rate` text COLLATE utf8mb4_unicode_ci,
  `principal_amount` text COLLATE utf8mb4_unicode_ci,
  `principal_amount_basis` text COLLATE utf8mb4_unicode_ci,
  `cgst_tax` text COLLATE utf8mb4_unicode_ci,
  `sgst_tax` text COLLATE utf8mb4_unicode_ci,
  `utgst_tax` text COLLATE utf8mb4_unicode_ci,
  `igst_tax` text COLLATE utf8mb4_unicode_ci,
  `compensatory_cess_tax` text COLLATE utf8mb4_unicode_ci,
  `shipping_amount` text COLLATE utf8mb4_unicode_ci,
  `shipping_amount_basis` text COLLATE utf8mb4_unicode_ci,
  `shipping_cgst_tax` text COLLATE utf8mb4_unicode_ci,
  `shipping_sgst_tax` text COLLATE utf8mb4_unicode_ci,
  `shipping_utgst_tax` text COLLATE utf8mb4_unicode_ci,
  `shipping_igst_tax` text COLLATE utf8mb4_unicode_ci,
  `shipping_cess_tax` text COLLATE utf8mb4_unicode_ci,
  `gift_wrap_amount` text COLLATE utf8mb4_unicode_ci,
  `gift_wrap_amount_basis` text COLLATE utf8mb4_unicode_ci,
  `gift_wrap_cgst_tax` text COLLATE utf8mb4_unicode_ci,
  `gift_wrap_sgst_tax` text COLLATE utf8mb4_unicode_ci,
  `gift_wrap_utgst_tax` text COLLATE utf8mb4_unicode_ci,
  `gift_wrap_igst_tax` text COLLATE utf8mb4_unicode_ci,
  `gift_wrap_compensatory_cess_tax` text COLLATE utf8mb4_unicode_ci,
  `item_promo_discount` text COLLATE utf8mb4_unicode_ci,
  `item_promo_discount_basis` text COLLATE utf8mb4_unicode_ci,
  `item_promo_tax` text COLLATE utf8mb4_unicode_ci,
  `shipping_promo_discount` text COLLATE utf8mb4_unicode_ci,
  `shipping_promo_discount_basis` text COLLATE utf8mb4_unicode_ci,
  `shipping_promo_tax` text COLLATE utf8mb4_unicode_ci,
  `gift_wrap_promo_discount` text COLLATE utf8mb4_unicode_ci,
  `gift_wrap_promo_discount_basis` text COLLATE utf8mb4_unicode_ci,
  `gift_wrap_promo_tax` text COLLATE utf8mb4_unicode_ci,
  `tcs_cgst_rate` text COLLATE utf8mb4_unicode_ci,
  `tcs_cgst_amount` text COLLATE utf8mb4_unicode_ci,
  `tcs_sgst_rate` text COLLATE utf8mb4_unicode_ci,
  `tcs_sgst_amount` text COLLATE utf8mb4_unicode_ci,
  `tcs_utgst_rate` text COLLATE utf8mb4_unicode_ci,
  `tcs_utgst_amount` text COLLATE utf8mb4_unicode_ci,
  `tcs_igst_rate` text COLLATE utf8mb4_unicode_ci,
  `tcs_igst_amount` text COLLATE utf8mb4_unicode_ci,
  `warehouse_id` text COLLATE utf8mb4_unicode_ci,
  `fulfillment_channel` text COLLATE utf8mb4_unicode_ci,
  `payment_method_code` text COLLATE utf8mb4_unicode_ci,
  `bill_to_city` text COLLATE utf8mb4_unicode_ci,
  `bill_to_state` text COLLATE utf8mb4_unicode_ci,
  `bill_to_country` text COLLATE utf8mb4_unicode_ci,
  `bill_to_postalcode` text COLLATE utf8mb4_unicode_ci,
  `customer_bill_to_gstid` text COLLATE utf8mb4_unicode_ci,
  `customer_ship_to_gstid` text COLLATE utf8mb4_unicode_ci,
  `receiver_gstin` text COLLATE utf8mb4_unicode_ci,
  `buyer_name` text COLLATE utf8mb4_unicode_ci,
  `credit_note_no` text COLLATE utf8mb4_unicode_ci,
  `credit_note_date` text COLLATE utf8mb4_unicode_ci,
  `irn_number` text COLLATE utf8mb4_unicode_ci,
  `irn_filing_status` text COLLATE utf8mb4_unicode_ci,
  `irn_date` text COLLATE utf8mb4_unicode_ci,
  `irn_error_code` text COLLATE utf8mb4_unicode_ci,
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
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
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
(20, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('fLGOpRI7MZmiX5nFQCXCOK1DDORUk16TRgoELXWY', 6, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiQk00dWtERHFveFY3Z0Rlb2ZCSElDamtLamRWOTI0NGttUGZnUk92TSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly8xMjcuMC4wLjE6OTA5MC9iYW5rcy8xL3RyYW5zYWN0aW9ucyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjY7czo0OiJhdXRoIjthOjE6e3M6MjE6InBhc3N3b3JkX2NvbmZpcm1lZF9hdCI7aToxNzc2MTY3Njc1O319', 1776169998),
('RyDrv70FFUUzQp35yN0By3zzdcAFJzJuele0nYOf', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQnphZHFEZWlFYUFPY096TmJwTUtVeFhjZ0IybjVEQ1ZmajczZWhDdSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6OTA5MCI7fX0=', 1776170270),
('u3nqIz1OZo4OwFCnjpy7U4MB7fCG8Uq0uFg54OZD', 6, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiaU14VHQxVHZsWFNaRElnNjR0YVN5Q2hvRFF3TlAzMEYydHU5Zld5dCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6OTA5MC9ib29raW5ncyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjY7czo0OiJhdXRoIjthOjE6e3M6MjE6InBhc3N3b3JkX2NvbmZpcm1lZF9hdCI7aToxNzc2MTYzMTM4O319', 1776166272);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `show_password` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
-- Table structure for table `vendors`
--

DROP TABLE IF EXISTS `vendors`;
CREATE TABLE IF NOT EXISTS `vendors` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` bigint UNSIGNED DEFAULT NULL,
  `vendor_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gst_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vendors_project_id_foreign` (`project_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `project_id`, `vendor_name`, `phone`, `gst_number`, `description`, `created_at`, `updated_at`) VALUES
(1, 4, 'SA-1 BRICKS', '9537166990', '9377127829', NULL, '2026-04-09 09:21:05', '2026-04-09 09:21:05'),
(2, 4, 'DURGA STEEL TRADING', '9898196802', '24AHPPP3742G1Z6', NULL, '2026-04-09 09:21:24', '2026-04-09 09:21:24'),
(3, 4, 'JK CEMENT WORKS', '220553', '24AABCJ0355R1ZD', NULL, '2026-04-09 09:21:42', '2026-04-09 09:21:42'),
(4, 4, 'WONDER CEMENT LIMITED', '9978538303', '24AAACW6009L1ZA', NULL, '2026-04-09 09:22:01', '2026-04-09 09:22:01');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_materials`
--

DROP TABLE IF EXISTS `vendor_materials`;
CREATE TABLE IF NOT EXISTS `vendor_materials` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `challan_no` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qty` decimal(12,2) NOT NULL,
  `material_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vendor_materials_vendor_id_foreign` (`vendor_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendor_materials`
--

INSERT INTO `vendor_materials` (`id`, `vendor_id`, `date`, `challan_no`, `qty`, `material_name`, `remark`, `created_at`, `updated_at`) VALUES
(1, 4, '2026-04-09', '2019', 6.00, 'chuno', NULL, '2026-04-09 09:32:25', '2026-04-09 09:32:25'),
(2, 4, '2026-04-09', '2020', 3.00, 'reti', NULL, '2026-04-09 09:32:54', '2026-04-09 09:32:54'),
(3, 2, '2026-04-09', '2019', 200.00, 'normal reti', NULL, '2026-04-09 09:38:56', '2026-04-09 09:38:56');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_transactions`
--

DROP TABLE IF EXISTS `vendor_transactions`;
CREATE TABLE IF NOT EXISTS `vendor_transactions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `type` enum('credit','debit') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `transaction_date` date DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vendor_transactions_vendor_id_foreign` (`vendor_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendor_transactions`
--

INSERT INTO `vendor_transactions` (`id`, `vendor_id`, `type`, `amount`, `transaction_date`, `note`, `created_at`, `updated_at`) VALUES
(1, 4, 'credit', 10000.00, '2026-04-09', NULL, '2026-04-09 09:25:55', '2026-04-09 09:25:55'),
(2, 4, 'debit', 5000.00, '2026-04-09', NULL, '2026-04-09 09:26:05', '2026-04-09 09:26:05');

-- --------------------------------------------------------

--
-- Table structure for table `working_data`
--

DROP TABLE IF EXISTS `working_data`;
CREATE TABLE IF NOT EXISTS `working_data` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `b2b_b2c` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seller_gstin` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ship_from_state` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_number` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `customer_bill_to_gstid` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `buyer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_to_city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_to_state` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `for_sap` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipment_date` date DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `quantity` int UNSIGNED DEFAULT NULL,
  `item_description` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hsn_sac` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sku` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_from_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_from_state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_from_country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_from_postal_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ship_from_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ship_from_country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ship_from_postal_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ship_to_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ship_to_state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ship_to_country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ship_to_postal_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `shipping_amount_basis` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_cgst_tax` decimal(15,2) DEFAULT NULL,
  `shipping_sgst_tax` decimal(15,2) DEFAULT NULL,
  `shipping_utgst_tax` decimal(15,2) DEFAULT NULL,
  `shipping_igst_tax` decimal(15,2) DEFAULT NULL,
  `shipping_cess_tax` decimal(15,2) DEFAULT NULL,
  `gift_wrap_amount` decimal(15,2) DEFAULT NULL,
  `gift_wrap_amount_basis` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gift_wrap_cgst_tax` decimal(15,2) DEFAULT NULL,
  `gift_wrap_sgst_tax` decimal(15,2) DEFAULT NULL,
  `gift_wrap_utgst_tax` decimal(15,2) DEFAULT NULL,
  `gift_wrap_igst_tax` decimal(15,2) DEFAULT NULL,
  `gift_wrap_compensatory_cess_tax` decimal(15,2) DEFAULT NULL,
  `item_promo_discount` decimal(15,2) DEFAULT NULL,
  `item_promo_discount_basis` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_promo_tax` decimal(15,2) DEFAULT NULL,
  `shipping_promo_discount` decimal(15,2) DEFAULT NULL,
  `shipping_promo_discount_basis` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_promo_tax` decimal(15,2) DEFAULT NULL,
  `gift_wrap_promo_discount` decimal(15,2) DEFAULT NULL,
  `gift_wrap_promo_discount_basis` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gift_wrap_promo_tax` decimal(15,2) DEFAULT NULL,
  `tcs_cgst_rate` decimal(8,2) DEFAULT NULL,
  `tcs_cgst_amount` decimal(15,2) DEFAULT NULL,
  `tcs_sgst_rate` decimal(8,2) DEFAULT NULL,
  `tcs_sgst_amount` decimal(15,2) DEFAULT NULL,
  `tcs_utgst_rate` decimal(8,2) DEFAULT NULL,
  `tcs_utgst_amount` decimal(15,2) DEFAULT NULL,
  `tcs_igst_rate` decimal(8,2) DEFAULT NULL,
  `tcs_igst_amount` decimal(15,2) DEFAULT NULL,
  `warehouse_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fulfillment_channel` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_to_country` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_to_postalcode` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_ship_to_gstid` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiver_gstin` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `credit_note_no` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `credit_note_date` date DEFAULT NULL,
  `irn_number` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `irn_filing_status` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `irn_date` date DEFAULT NULL,
  `irn_error_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
