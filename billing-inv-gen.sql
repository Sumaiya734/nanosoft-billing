-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 16, 2025 at 10:34 AM
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
-- Database: `billing`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED DEFAULT NULL,
  `action` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `table_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `record_id` int DEFAULT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `c_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED DEFAULT NULL,
  `customer_id` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `connection_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `id_type` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`c_id`),
  UNIQUE KEY `customer_id` (`customer_id`),
  KEY `user_id` (`user_id`),
  KEY `idx_customers_email` (`email`),
  KEY `idx_customers_phone` (`phone`)
) ENGINE=MyISAM AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`c_id`, `user_id`, `customer_id`, `name`, `email`, `phone`, `address`, `connection_address`, `id_type`, `id_number`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 2, 'CUST1001', 'Sumaiya Akter', 'sumaiya@example.com', '01730844732', 'Gazipur, Dhaka', 'House 12, Gazipur', 'NID', '123456789', 1, '2025-01-04 23:50:20', '2025-01-05 04:35:13'),
(2, 3, 'CUST1002', 'Nayeem Hasan', 'nayeem@example.com', '01711112222', 'Mirpur, Dhaka', 'House 44, Mirpur', 'NID', '987654321', 1, '2025-01-09 23:50:20', '2025-01-10 04:35:13'),
(3, 4, 'CUST1003', 'Afsana Rahman', 'afsana@example.com', '01888883333', 'Uttara, Dhaka', 'Road 10, Uttara', 'NID', '555888333', 1, '2025-01-14 23:50:20', '2025-01-15 04:35:13'),
(4, 5, 'CUST1004', 'Nadia Zaman', 'nadiyazaman@gmail.com', '01723456789', 'Gazipur', 'Joydebpur', 'nid', '111222333', 1, '2025-01-20 00:10:27', '2025-01-20 00:12:47'),
(5, 6, 'CUST1005', 'Rahim Khan', 'rahim@example.com', '01712345678', 'Dhanmondi, Dhaka', 'Road 8, Dhanmondi', 'NID', '444555666', 1, '2025-01-25 00:10:27', '2025-01-25 00:12:47'),
(6, 7, 'CUST1006', 'Fatima Begum', 'fatima@example.com', '01812345678', 'Mohakhali, Dhaka', 'House 25, Mohakhali', 'NID', '777888999', 1, '2025-02-05 00:10:27', '2025-02-05 00:12:47'),
(7, 8, 'CUST1007', 'Kamal Hossain', 'kamal@example.com', '01912345678', 'Banani, Dhaka', 'Road 11, Banani', 'NID', '111333555', 1, '2025-02-12 00:10:27', '2025-02-12 00:12:47'),
(8, 9, 'CUST1008', 'Sadia Islam', 'sadia@example.com', '01798765432', 'Gulshan, Dhaka', 'House 45, Gulshan', 'NID', '222444666', 1, '2025-02-20 00:10:27', '2025-02-20 00:12:47'),
(9, 10, 'CUST1009', 'Arif Mahmud', 'arif@example.com', '01898765432', 'Baridhara, Dhaka', 'Road 6, Baridhara', 'NID', '333555777', 1, '2025-03-08 00:10:27', '2025-03-08 00:12:47'),
(10, 11, 'CUST1010', 'Tania Ahmed', 'tania@example.com', '01998765432', 'Bashundhara, Dhaka', 'House 33, Bashundhara', 'NID', '444666888', 1, '2025-03-18 00:10:27', '2025-03-18 00:12:47'),
(11, 12, 'CUST1011', 'Sohel Rana', 'sohel@example.com', '01755556666', 'Mirpur DOHS, Dhaka', 'Road 4, Mirpur DOHS', 'NID', '555777999', 1, '2025-04-05 00:10:27', '2025-04-05 00:12:47'),
(12, 13, 'CUST1012', 'Nusrat Jahan', 'nusrat@example.com', '01855556666', 'Uttara Sector 4', 'House 78, Uttara', 'NID', '666888000', 1, '2025-04-15 00:10:27', '2025-04-15 00:12:47'),
(13, 14, 'CUST1013', 'Imran Hossain', 'imran@example.com', '01955556666', 'Mohammadpur, Dhaka', 'Road 12, Mohammadpur', 'NID', '777999111', 1, '2025-05-10 00:10:27', '2025-05-10 00:12:47'),
(14, 15, 'CUST1014', 'Moumita Rahman', 'moumita@example.com', '01766667777', 'Shyamoli, Dhaka', 'House 56, Shyamoli', 'NID', '888000222', 1, '2025-05-20 00:10:27', '2025-05-20 00:12:47'),
(15, 16, 'CUST1015', 'Faisal Ahmed', 'faisal@example.com', '01866667777', 'Malibagh, Dhaka', 'Road 9, Malibagh', 'NID', '999111333', 1, '2025-06-12 00:10:27', '2025-06-12 00:12:47'),
(16, 17, 'CUST1016', 'Sabrina Chowdhury', 'sabrina@example.com', '01966667777', 'Rampura, Dhaka', 'House 67, Rampura', 'NID', '000222444', 1, '2025-07-08 00:10:27', '2025-07-08 00:12:47'),
(17, 18, 'CUST1017', 'Rashidul Islam', 'rashidul@example.com', '01777778888', 'Badda, Dhaka', 'Road 7, Badda', 'NID', '111333555', 1, '2025-08-05 00:10:27', '2025-08-05 00:12:47'),
(18, 19, 'CUST1018', 'Anika Tasnim', 'anika@example.com', '01877778888', 'Khilgaon, Dhaka', 'House 89, Khilgaon', 'NID', '222444666', 1, '2025-08-18 00:10:27', '2025-08-18 00:12:47'),
(19, 20, 'CUST1019', 'Shahriar Manzoor', 'shahriar@example.com', '01977778888', 'Demra, Dhaka', 'Road 5, Demra', 'NID', '333555777', 1, '2025-09-10 00:10:27', '2025-09-10 00:12:47'),
(20, 21, 'CUST1020', 'Jannatul Ferdous', 'jannatul@example.com', '01788889999', 'Jatrabari, Dhaka', 'House 34, Jatrabari', 'NID', '444666888', 1, '2025-09-22 00:10:27', '2025-09-22 00:12:47'),
(21, 22, 'CUST1021', 'Rafiqul Islam', 'rafiqul@example.com', '01799990000', 'Mirpur 10', 'House 23, Mirpur', 'NID', '555777111', 1, '2025-10-01 00:10:27', '2025-10-01 00:12:47'),
(22, 23, 'CUST1022', 'Sharmin Akter', 'sharmin@example.com', '01899990000', 'Uttara 11', 'Road 7, Uttara', 'NID', '666888222', 1, '2025-10-02 00:10:27', '2025-10-02 00:12:47'),
(23, 24, 'CUST1023', 'Nasir Uddin', 'nasir@example.com', '01999990000', 'Dhanmondi 15', 'House 45, Dhanmondi', 'NID', '777999333', 1, '2025-10-03 00:10:27', '2025-10-03 00:12:47'),
(24, 25, 'CUST1024', 'Mitu Rahman', 'mitu@example.com', '01788881111', 'Gulshan 2', 'Road 9, Gulshan', 'NID', '888000444', 1, '2025-10-04 00:10:27', '2025-10-04 00:12:47'),
(25, 26, 'CUST1025', 'Sajal Hossain', 'sajal@example.com', '01888881111', 'Banani 11', 'House 67, Banani', 'NID', '999111555', 1, '2025-10-05 00:10:27', '2025-10-05 00:12:47'),
(26, 27, 'CUST1026', 'Poly Begum', 'poly@example.com', '01988881111', 'Mohakhali DOHS', 'Road 3, Mohakhali', 'NID', '000222666', 1, '2025-10-06 00:10:27', '2025-10-06 00:12:47'),
(27, 28, 'CUST1027', 'Bappi Sarker', 'bappi@example.com', '01777772222', 'Baridhara DOHS', 'House 89, Baridhara', 'NID', '111333777', 1, '2025-10-07 00:10:27', '2025-10-07 00:12:47'),
(28, 29, 'CUST1028', 'Tumpa Akter', 'tumpa@example.com', '01877772222', 'Bashundhara R/A', 'Road 12, Bashundhara', 'NID', '222444888', 1, '2025-10-08 00:10:27', '2025-10-08 00:12:47'),
(29, 30, 'CUST1029', 'Mamun Or Rashid', 'mamun@example.com', '01977772222', 'Mirpur 13', 'House 34, Mirpur', 'NID', '333555999', 1, '2025-10-09 00:10:27', '2025-10-09 00:12:47'),
(30, 31, 'CUST1030', 'Shirin Sultana', 'shirin@example.com', '01766663333', 'Uttara 8', 'Road 5, Uttara', 'NID', '444666000', 1, '2025-10-10 00:10:27', '2025-10-10 00:12:47'),
(31, 32, 'CUST1031', 'Rokonuzzaman', 'rokon@example.com', '01866663333', 'Mohammadpur', 'House 56, Mohammadpur', 'NID', '555777111', 1, '2025-10-11 00:10:27', '2025-10-11 00:12:47'),
(32, 33, 'CUST1032', 'Farhana Yesmin', 'farhana@example.com', '01966663333', 'Shyamoli', 'Road 8, Shyamoli', 'NID', '666888222', 1, '2025-10-12 00:10:27', '2025-10-12 00:12:47'),
(33, 34, 'CUST1033', 'Alamgir Hossain', 'alamgir@example.com', '01755554444', 'Malibagh', 'House 67, Malibagh', 'NID', '777999333', 1, '2025-10-13 00:10:27', '2025-10-13 00:12:47'),
(34, 35, 'CUST1034', 'Nazma Begum', 'nazma@example.com', '01855554444', 'Rampura', 'Road 10, Rampura', 'NID', '888000444', 1, '2025-10-14 00:10:27', '2025-10-14 00:12:47'),
(35, 36, 'CUST1035', 'Sazzad Hossain', 'sazzad@example.com', '01955554444', 'Badda', 'House 78, Badda', 'NID', '999111555', 1, '2025-10-15 00:10:27', '2025-10-15 00:12:47'),
(36, 37, 'CUST1036', 'Morshed Alam', 'morshed@example.com', '01744445555', 'Khilgaon', 'Road 12, Khilgaon', 'NID', '000222666', 1, '2025-10-16 00:10:27', '2025-10-16 00:12:47'),
(37, 38, 'CUST1037', 'Shahinur Rahman', 'shahinur@example.com', '01844445555', 'Demra', 'House 89, Demra', 'NID', '111333777', 1, '2025-10-17 00:10:27', '2025-10-17 00:12:47'),
(38, 39, 'CUST1038', 'Nargis Akter', 'nargis@example.com', '01944445555', 'Jatrabari', 'Road 15, Jatrabari', 'NID', '222444888', 1, '2025-10-18 00:10:27', '2025-10-18 00:12:47'),
(39, 40, 'CUST1039', 'Babul Miah', 'babul@example.com', '01733336666', 'Mirpur 1', 'House 34, Mirpur', 'NID', '333555999', 1, '2025-10-19 00:10:27', '2025-10-19 00:12:47'),
(40, 41, 'CUST1040', 'Rina Akter', 'rina@example.com', '01833336666', 'Uttara 3', 'Road 6, Uttara', 'NID', '444666000', 1, '2025-10-20 00:10:27', '2025-10-20 00:12:47'),
(41, 42, 'CUST1041', 'Shafiqul Islam', 'shafiqul@example.com', '01933336666', 'Dhanmondi 27', 'House 45, Dhanmondi', 'NID', '555777111', 1, '2025-10-21 00:10:27', '2025-10-21 00:12:47'),
(42, 43, 'CUST1042', 'Mousumi Rahman', 'mousumi@example.com', '01722227777', 'Gulshan 1', 'Road 8, Gulshan', 'NID', '666888222', 1, '2025-10-22 00:10:27', '2025-10-22 00:12:47'),
(43, 44, 'CUST1043', 'Jamal Uddin', 'jamal@example.com', '01822227777', 'Banani 12', 'House 56, Banani', 'NID', '777999333', 1, '2025-10-23 00:10:27', '2025-10-23 00:12:47'),
(44, 45, 'CUST1044', 'Ruma Akter', 'ruma@example.com', '01922227777', 'Mohakhali', 'Road 11, Mohakhali', 'NID', '888000444', 1, '2025-10-24 00:10:27', '2025-10-24 00:12:47'),
(45, 46, 'CUST1045', 'Salam Miah', 'salam@example.com', '01711118888', 'Baridhara', 'House 67, Baridhara', 'NID', '999111555', 1, '2025-10-25 00:10:27', '2025-10-25 00:12:47'),
(46, 47, 'CUST1046', 'Nipa Moni', 'nipa@example.com', '01811118888', 'Bashundhara', 'Road 14, Bashundhara', 'NID', '000222666', 1, '2025-10-26 00:10:27', '2025-10-26 00:12:47'),
(47, 48, 'CUST1047', 'Khalid Hasan', 'khalid@example.com', '01911118888', 'Mirpur 6', 'House 78, Mirpur', 'NID', '111333777', 1, '2025-10-27 00:10:27', '2025-10-27 00:12:47'),
(48, 49, 'CUST1048', 'Mitu Akter', 'mitu2@example.com', '01700009999', 'Uttara 7', 'Road 9, Uttara', 'NID', '222444888', 1, '2025-10-28 00:10:27', '2025-10-28 00:12:47'),
(49, 50, 'CUST1049', 'Ratan Sarker', 'ratan@example.com', '01800009999', 'Dhanmondi 32', 'House 89, Dhanmondi', 'NID', '333555999', 1, '2025-10-29 00:10:27', '2025-10-29 00:12:47'),
(50, 51, 'CUST1050', 'Shamima Nasrin', 'shamima@example.com', '01900009999', 'Gulshan 2', 'Road 16, Gulshan', 'NID', '444666000', 1, '2025-10-30 00:10:27', '2025-10-30 00:12:47'),
(51, 52, 'CUST1051', 'Bappi Das', 'bappi2@example.com', '01799998888', 'Banani 11', 'House 34, Banani', 'NID', '555777111', 1, '2025-10-31 00:10:27', '2025-10-31 00:12:47'),
(52, 53, 'CUST1052', 'Moriom Begum', 'moriom@example.com', '01899998888', 'Mohakhali DOHS', 'Road 5, Mohakhali', 'NID', '666888222', 1, '2025-10-31 00:10:27', '2025-10-31 00:12:47'),
(53, 54, 'CUST1053', 'Raju Ahmed', 'raju@example.com', '01999998888', 'Baridhara DOHS', 'House 45, Baridhara', 'NID', '777999333', 1, '2025-10-31 00:10:27', '2025-10-31 00:12:47'),
(54, 55, 'CUST1054', 'Shila Akter', 'shila@example.com', '01788887777', 'Bashundhara R/A', 'Road 8, Bashundhara', 'NID', '888000444', 1, '2025-10-31 00:10:27', '2025-10-31 00:12:47'),
(55, 56, 'CUST1055', 'Mizanur Rahman', 'mizan@example.com', '01888887777', 'Mirpur 10', 'House 56, Mirpur', 'NID', '999111555', 1, '2025-10-31 00:10:27', '2025-10-31 00:12:47'),
(56, 57, 'CUST1056', 'Naznin Sultanaaaa', 'naznin@example.com', '01988887777', 'Uttara 4', 'Road 7, Uttara', 'NID', '000222666', 1, '2025-10-31 00:10:27', '2025-11-11 06:03:54'),
(57, 58, 'CUST1057', 'Alauddin Hasan', 'alauddin@example.com', '01777776666', 'Dhanmondi 15', 'House 67, Dhanmondi', 'NID', '111333777', 1, '2025-10-31 00:10:27', '2025-11-11 04:30:56'),
(58, 59, 'CUST1058', 'Rokeya Begum', 'rokeya@example.com', '01877776666', 'Gulshan 1', 'Road 12, Gulshan', 'NID', '222444888', 1, '2025-10-31 00:10:27', '2025-10-31 00:12:47'),
(59, 60, 'CUST1059', 'Sohag Miah', 'sohag@example.com', '01977776666', 'Banani 12', 'House 78, Banani', 'NID', '333555999', 1, '2025-10-31 00:10:27', '2025-10-31 00:12:47'),
(60, 61, 'CUST1060', 'Laboni Akter', 'laboni@example.com', '01766665555', 'Mohakhali', 'Road 9, Mohakhali', 'NID', '444666000', 1, '2025-10-31 00:10:27', '2025-10-31 00:12:47'),
(61, 62, 'CUST1061', 'Shahidul Islam', 'shahidul@example.com', '01866665555', 'Baridhara', 'House 89, Baridhara', 'NID', '555777111', 1, '2025-10-31 00:10:27', '2025-10-31 00:12:47'),
(62, 63, 'CUST1062', 'Mita Rahman', 'mita@example.com', '01966665555', 'Bashundhara', 'Road 14, Bashundhara', 'NID', '666888222', 1, '2025-10-31 00:10:27', '2025-10-31 00:12:47'),
(63, 64, 'CUST1063', 'Rony Ahmed', 'rony@example.com', '01755554444', 'Mirpur 13', 'House 34, Mirpur', 'NID', '777999333', 1, '2025-10-31 00:10:27', '2025-10-31 00:12:47'),
(64, 65, 'CUST1064', 'Tania Islam', 'tania2@example.com', '01855554444', 'Uttara 8', 'Road 6, Uttara', 'NID', '888000444', 1, '2025-10-31 00:10:27', '2025-10-31 00:12:47'),
(65, 66, 'CUST1065', 'Sajib Hasan', 'sajib@example.com', '01955554444', 'Dhanmondi 27', 'House 45, Dhanmondi', 'NID', '999111555', 1, '2025-10-31 00:10:27', '2025-10-31 00:12:47'),
(66, 67, 'CUST256999', 'Imteaz', 'imteaz@gmail.com', '01236547899', 'Mirpur10', 'Mirpur11', NULL, NULL, 1, '2025-11-13 00:53:26', '2025-11-13 00:53:26'),
(74, 68, 'CUSTMIM9877364', 'Mim', 'mim@gmail.com', '01452369877', 'Kazipara', NULL, NULL, NULL, 1, '2025-11-14 12:05:09', '2025-11-14 12:05:09'),
(73, 74, 'CUSTZIA3690887', 'Zia', 'zia@gmail.com', '01477413690', 'Barisal', NULL, NULL, NULL, 1, '2025-11-14 09:55:02', '2025-11-14 09:55:02'),
(75, 75, 'CUSTJENI5678988', 'Jeni Khan', 'jenikhan@gmail.com', '01712345678', 'Dhaka, Joydebpur', NULL, NULL, NULL, 1, '2025-11-16 01:39:22', '2025-11-16 01:39:22'),
(76, 76, 'CUST257213', 'Araf Khan', 'arafkhan@gmail.com', '01987654321', 'Mirpur', 'Mirpur 11', NULL, NULL, 1, '2025-11-16 02:15:33', '2025-11-16 02:15:33'),
(77, 77, 'CUST259540', 'Shafi Islam', 'shafi@gmail.com', '01987654334', 'Badda', 'Badda', NULL, NULL, 1, '2025-11-16 03:56:13', '2025-11-16 03:56:13');

-- --------------------------------------------------------

--
-- Table structure for table `customer_to_products`
--

DROP TABLE IF EXISTS `customer_to_products`;
CREATE TABLE IF NOT EXISTS `customer_to_products` (
  `cp_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `c_id` int UNSIGNED NOT NULL,
  `p_id` int UNSIGNED NOT NULL,
  `assign_date` date NOT NULL,
  `billing_cycle_months` int NOT NULL DEFAULT '1',
  `due_date` date DEFAULT NULL,
  `status` enum('active','pending','expired') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `invoice_id` bigint UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`cp_id`),
  KEY `fk_customer_packages_customer` (`c_id`),
  KEY `fk_customer_packages_package` (`p_id`)
) ENGINE=MyISAM AUTO_INCREMENT=133 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_to_products`
--

INSERT INTO `customer_to_products` (`cp_id`, `c_id`, `p_id`, `assign_date`, `billing_cycle_months`, `due_date`, `status`, `is_active`, `created_at`, `updated_at`, `invoice_id`) VALUES
(1, 1, 1, '2025-01-05', 1, '2025-02-05', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(2, 1, 5, '2025-01-05', 6, '2025-07-05', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(3, 2, 2, '2025-01-10', 1, '2025-02-10', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(4, 2, 4, '2025-01-10', 3, '2025-04-10', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(5, 2, 6, '2025-01-10', 6, '2025-07-10', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(6, 3, 3, '2025-01-15', 1, '2025-02-28', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 01:22:34', NULL),
(7, 4, 1, '2025-01-20', 1, '2025-02-20', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(8, 4, 5, '2025-01-20', 1, '2025-02-20', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(9, 5, 2, '2025-01-25', 1, '2025-02-25', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(10, 5, 4, '2025-01-25', 3, '2025-04-25', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(11, 5, 5, '2025-01-25', 6, '2025-07-25', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(12, 6, 1, '2025-02-05', 6, '2025-08-05', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(13, 7, 3, '2025-02-12', 1, '2025-03-12', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(14, 7, 5, '2025-02-12', 12, '2026-02-12', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(15, 8, 2, '2025-02-20', 1, '2025-03-20', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(16, 8, 4, '2025-02-20', 3, '2025-05-20', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(17, 8, 6, '2025-02-20', 6, '2025-08-20', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(18, 9, 1, '2025-03-08', 1, '2025-04-08', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(19, 10, 3, '2025-03-18', 6, '2025-09-18', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(20, 10, 6, '2025-03-18', 6, '2025-09-18', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(21, 11, 1, '2025-04-05', 1, '2025-05-05', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(22, 11, 4, '2025-04-05', 3, '2025-07-05', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(23, 11, 5, '2025-04-05', 6, '2025-10-05', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(24, 12, 2, '2025-04-15', 1, '2025-05-15', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(25, 13, 3, '2025-05-10', 1, '2025-06-10', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(26, 13, 4, '2025-05-10', 3, '2025-08-10', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(27, 14, 1, '2025-05-20', 1, '2025-06-20', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(28, 14, 5, '2025-05-20', 6, '2025-11-20', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(29, 14, 6, '2025-05-20', 12, '2026-05-20', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(30, 15, 2, '2025-06-12', 1, '2025-07-12', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(31, 16, 3, '2025-07-08', 1, '2025-08-08', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(32, 16, 5, '2025-07-08', 12, '2026-07-08', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(33, 17, 1, '2025-08-05', 1, '2025-09-05', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(34, 17, 4, '2025-08-05', 3, '2025-11-05', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(35, 17, 6, '2025-08-05', 6, '2026-02-05', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(36, 18, 2, '2025-08-18', 1, '2025-09-18', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(37, 19, 3, '2025-09-10', 1, '2025-10-10', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(38, 19, 4, '2025-09-10', 3, '2025-12-10', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(39, 20, 1, '2025-09-22', 1, '2025-10-22', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(40, 20, 5, '2025-09-22', 6, '2026-03-22', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(41, 20, 6, '2025-09-22', 12, '2026-09-22', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(42, 21, 2, '2025-10-01', 1, '2025-11-01', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(43, 22, 1, '2025-10-02', 1, '2025-11-02', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(44, 22, 4, '2025-10-02', 3, '2026-01-02', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(45, 23, 3, '2025-10-03', 1, '2025-11-03', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(46, 23, 5, '2025-10-03', 6, '2026-04-03', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(47, 24, 1, '2025-10-04', 1, '2025-11-04', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(48, 24, 4, '2025-10-04', 3, '2026-01-04', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(49, 24, 6, '2025-10-04', 6, '2026-04-04', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(50, 25, 2, '2025-10-05', 1, '2025-11-05', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(51, 26, 3, '2025-10-06', 1, '2025-11-06', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(52, 26, 5, '2025-10-06', 12, '2026-10-06', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(53, 27, 1, '2025-10-07', 1, '2025-11-07', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(54, 27, 4, '2025-10-07', 3, '2026-01-07', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(55, 28, 2, '2025-10-08', 1, '2025-11-08', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(56, 28, 6, '2025-10-08', 6, '2026-04-08', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(57, 29, 3, '2025-10-09', 1, '2025-11-09', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(58, 30, 1, '2025-10-10', 1, '2025-11-10', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(59, 30, 5, '2025-10-10', 6, '2026-04-10', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(60, 31, 2, '2025-10-11', 1, '2025-11-11', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(61, 32, 3, '2025-10-12', 1, '2025-11-12', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(62, 32, 4, '2025-10-12', 3, '2026-01-12', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(64, 33, 5, '2025-10-13', 6, '2026-04-13', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(65, 33, 6, '2025-10-13', 12, '2026-10-01', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 01:18:22', NULL),
(66, 34, 2, '2025-10-14', 1, '2025-11-14', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(67, 35, 3, '2025-10-15', 1, '2025-11-15', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(68, 35, 4, '2025-10-15', 3, '2026-01-15', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(69, 36, 1, '2025-10-16', 1, '2025-11-16', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(70, 37, 2, '2025-10-17', 1, '2025-11-17', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(71, 37, 6, '2025-10-17', 6, '2026-04-17', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(72, 38, 3, '2025-10-18', 1, '2025-11-18', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(73, 39, 1, '2025-10-19', 1, '2025-11-19', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(74, 39, 5, '2025-10-19', 6, '2026-04-19', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(75, 40, 2, '2025-10-20', 1, '2025-11-20', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(76, 40, 4, '2025-10-20', 3, '2026-01-20', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(77, 41, 3, '2025-10-21', 1, '2025-11-21', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(80, 43, 2, '2025-10-23', 1, '2025-11-23', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(82, 44, 3, '2025-10-24', 1, '2025-11-24', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(83, 45, 1, '2025-10-25', 1, '2025-11-25', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(85, 46, 2, '2025-10-26', 1, '2025-11-26', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(86, 47, 3, '2025-10-27', 1, '2025-11-27', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(87, 47, 4, '2025-10-27', 3, '2026-01-27', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(88, 48, 1, '2025-10-28', 1, '2025-11-28', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(89, 48, 6, '2025-10-28', 6, '2026-04-28', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(90, 49, 2, '2025-10-29', 1, '2025-11-29', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(91, 50, 3, '2025-10-30', 1, '2025-11-30', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(92, 50, 5, '2025-10-30', 12, '2026-10-30', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(93, 51, 1, '2025-10-31', 1, '2025-11-30', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(94, 51, 4, '2025-10-31', 3, '2026-01-31', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(95, 52, 2, '2025-10-31', 1, '2025-11-30', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(96, 53, 3, '2025-10-31', 1, '2025-11-30', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(97, 53, 6, '2025-10-31', 6, '2026-04-30', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(98, 54, 1, '2025-10-31', 1, '2025-11-30', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(99, 55, 2, '2025-10-31', 1, '2025-11-30', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(101, 56, 3, '2025-10-31', 1, '2025-11-30', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(102, 57, 1, '2025-10-31', 1, '2025-11-30', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(103, 57, 5, '2025-10-31', 6, '2026-04-30', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(104, 58, 2, '2025-10-31', 1, '2025-11-30', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(105, 59, 3, '2025-10-31', 1, '2025-11-30', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(106, 59, 4, '2025-10-31', 3, '2026-01-31', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(107, 60, 1, '2025-10-31', 1, '2025-11-30', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(108, 61, 2, '2025-10-31', 1, '2025-11-30', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(109, 61, 6, '2025-10-31', 12, '2026-10-31', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(110, 62, 3, '2025-10-31', 1, '2025-11-30', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(111, 63, 1, '2025-10-31', 1, '2025-11-30', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(112, 63, 5, '2025-10-31', 6, '2026-04-30', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(113, 64, 2, '2025-10-31', 1, '2025-11-30', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(114, 64, 4, '2025-10-31', 3, '2026-01-31', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(115, 65, 3, '2025-10-31', 1, '2025-11-30', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(116, 65, 6, '2025-10-31', 6, '2026-04-30', 'active', 1, '2025-11-03 11:52:05', '2025-11-16 06:26:44', NULL),
(117, 3, 4, '2025-11-06', 3, '2026-02-28', 'active', 1, '2025-11-05 22:35:01', '2025-11-16 01:10:14', NULL),
(118, 3, 9, '2025-11-11', 2, '2026-01-26', 'active', 1, '2025-11-11 05:38:13', '2025-11-16 01:14:16', NULL),
(119, 57, 11, '2025-09-01', 6, '2026-03-01', 'active', 1, '2025-11-11 06:14:21', '2025-11-16 06:26:44', NULL),
(120, 66, 8, '2025-11-13', 3, '2026-02-13', 'active', 1, '2025-11-13 00:59:42', '2025-11-16 06:26:44', NULL),
(121, 57, 12, '2025-11-13', 3, '2026-02-13', 'active', 1, '2025-11-13 01:17:45', '2025-11-16 06:26:44', NULL),
(122, 66, 1, '2025-11-13', 6, '2026-05-13', 'active', 1, '2025-11-13 01:19:25', '2025-11-16 06:26:44', NULL),
(124, 66, 2, '2025-11-13', 12, '2026-11-13', 'active', 1, '2025-11-13 02:06:51', '2025-11-16 06:26:44', NULL),
(125, 73, 6, '2025-11-14', 6, '2026-05-14', 'active', 1, '2025-11-14 09:55:38', '2025-11-16 06:26:44', NULL),
(126, 74, 10, '2025-11-14', 3, '2026-02-14', 'active', 1, '2025-11-14 12:06:20', '2025-11-16 06:26:44', NULL),
(127, 74, 7, '2025-11-14', 2, '2026-01-14', 'active', 1, '2025-11-14 12:07:46', '2025-11-16 06:26:44', NULL),
(128, 73, 4, '2025-11-16', 6, '2026-05-16', 'active', 1, '2025-11-15 22:34:03', '2025-11-16 06:26:44', NULL),
(129, 75, 4, '2024-11-16', 3, '2025-02-15', 'active', 1, '2025-11-16 01:40:52', '2025-11-16 01:40:52', NULL),
(130, 76, 4, '2024-12-16', 3, '2025-03-01', 'active', 1, '2025-11-16 02:16:18', '2025-11-16 02:18:20', NULL),
(131, 76, 7, '2025-11-16', 3, '2026-02-04', 'active', 1, '2025-11-16 02:26:24', '2025-11-16 02:26:24', NULL),
(132, 77, 9, '2025-11-16', 6, '2026-05-12', 'active', 1, '2025-11-16 03:59:19', '2025-11-16 03:59:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
CREATE TABLE IF NOT EXISTS `invoices` (
  `invoice_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `c_id` int UNSIGNED NOT NULL,
  `issue_date` date NOT NULL,
  `previous_due` decimal(12,2) DEFAULT '0.00',
  `service_charge` decimal(12,2) DEFAULT '50.00',
  `vat_percentage` decimal(5,2) DEFAULT '5.00',
  `vat_amount` decimal(12,2) DEFAULT '0.00',
  `subtotal` decimal(12,2) DEFAULT '0.00',
  `total_amount` decimal(12,2) DEFAULT '0.00',
  `received_amount` decimal(12,2) DEFAULT '0.00',
  `next_due` decimal(12,2) DEFAULT '0.00',
  `status` enum('unpaid','paid','partial','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'unpaid',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`invoice_id`),
  UNIQUE KEY `invoice_number` (`invoice_number`),
  KEY `idx_invoices_customer` (`c_id`),
  KEY `idx_invoices_status` (`status`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=269 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`invoice_id`, `invoice_number`, `c_id`, `issue_date`, `previous_due`, `service_charge`, `vat_percentage`, `vat_amount`, `subtotal`, `total_amount`, `received_amount`, `next_due`, `status`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'INV-2025-0001', 1, '2025-02-05', 0.00, 50.00, 5.00, 52.50, 1050.00, 1102.50, 1102.50, 0.00, 'paid', 'Monthly billing cycle - Package assigned Jan 5', 1, '2025-02-04 22:00:00', '2025-02-10 02:30:00'),
(2, 'INV-2025-0002', 2, '2025-02-10', 0.00, 50.00, 5.00, 52.50, 1050.00, 1102.50, 1102.50, 0.00, 'paid', 'Monthly billing cycle - Package assigned Jan 10', 1, '2025-02-09 22:00:00', '2025-02-14 23:20:00'),
(3, 'INV-2025-0003', 3, '2025-02-15', 0.00, 50.00, 5.00, 52.50, 1050.00, 1102.50, 500.00, 602.50, 'partial', 'Monthly billing cycle - Package assigned Jan 15', 1, '2025-02-14 22:00:00', '2025-02-24 21:45:00'),
(4, 'INV-2025-0004', 4, '2025-02-20', 0.00, 50.00, 5.00, 52.50, 1050.00, 1102.50, 0.00, 1102.50, 'unpaid', 'Monthly billing cycle - Package assigned Jan 20', 1, '2025-02-19 22:00:00', '2025-02-19 22:00:00'),
(5, 'INV-2025-0005', 1, '2025-03-05', 0.00, 50.00, 5.00, 52.50, 1050.00, 1102.50, 1102.50, 0.00, 'paid', '2nd monthly cycle', 1, '2025-03-04 21:00:00', '2025-03-10 02:30:00'),
(6, 'INV-2025-0006', 2, '2025-03-10', 0.00, 50.00, 5.00, 52.50, 1050.00, 1102.50, 1102.50, 0.00, 'paid', '2nd monthly cycle', 1, '2025-03-09 21:00:00', '2025-03-14 23:20:00'),
(7, 'INV-2025-0007', 3, '2025-03-15', 602.50, 50.00, 5.00, 52.50, 1050.00, 1704.50, 1704.50, 0.00, 'paid', '2nd monthly cycle - cleared previous due', 1, '2025-03-14 21:00:00', '2025-03-25 04:20:00'),
(8, 'INV-2025-0008', 4, '2025-03-20', 1102.50, 50.00, 5.00, 52.50, 1050.00, 2205.00, 0.00, 2205.00, 'unpaid', '2nd monthly cycle - overdue from Feb', 1, '2025-03-19 21:00:00', '2025-03-19 21:00:00'),
(9, 'INV-2025-0009', 1, '2025-04-05', 0.00, 50.00, 5.00, 52.50, 1050.00, 1102.50, 1102.50, 0.00, 'paid', '3rd monthly cycle', 1, '2025-04-04 21:00:00', '2025-04-10 02:30:00'),
(10, 'INV-2025-0010', 2, '2025-04-10', 0.00, 50.00, 5.00, 157.50, 1800.00, 1897.50, 1897.50, 0.00, 'paid', '3-month billing cycle completed', 1, '2025-04-09 21:00:00', '2025-04-14 23:20:00'),
(11, 'INV-2025-0011', 5, '2025-02-25', 0.00, 50.00, 5.00, 52.50, 1050.00, 1102.50, 1102.50, 0.00, 'paid', 'First monthly bill', 1, '2025-02-24 21:00:00', '2025-03-01 02:30:00'),
(12, 'INV-2025-0012', 1, '2025-07-05', 0.00, 50.00, 5.00, 157.50, 3150.00, 3307.50, 3307.50, 0.00, 'paid', '6-month billing cycle completed', 1, '2025-07-04 21:00:00', '2025-07-10 02:30:00'),
(13, 'INV-2025-0013', 2, '2025-07-10', 0.00, 50.00, 5.00, 157.50, 3150.00, 3307.50, 0.00, 3307.50, 'unpaid', '6-month billing cycle - pending payment', 1, '2025-07-09 21:00:00', '2025-07-09 21:00:00'),
(14, 'INV-2025-0014', 6, '2025-03-05', 0.00, 50.00, 5.00, 52.50, 1050.00, 1102.50, 1102.50, 0.00, 'paid', 'First monthly bill', 1, '2025-03-04 21:00:00', '2025-03-09 23:20:00'),
(15, 'INV-2025-0015', 7, '2025-03-12', 0.00, 50.00, 5.00, 52.50, 1050.00, 1102.50, 1102.50, 0.00, 'paid', 'First monthly bill', 1, '2025-03-11 21:00:00', '2025-03-17 02:30:00'),
(16, 'INV-2025-0016', 8, '2025-03-20', 0.00, 50.00, 5.00, 52.50, 1050.00, 1102.50, 0.00, 1102.50, 'unpaid', 'First monthly bill - overdue', 1, '2025-03-19 21:00:00', '2025-03-19 21:00:00'),
(17, 'INV-2025-0017', 9, '2025-04-08', 0.00, 50.00, 5.00, 52.50, 1050.00, 1102.50, 1102.50, 0.00, 'paid', 'First monthly bill', 1, '2025-04-07 21:00:00', '2025-04-12 23:20:00'),
(18, 'INV-2025-0018', 10, '2025-09-18', 0.00, 50.00, 5.00, 157.50, 3150.00, 3307.50, 0.00, 3307.50, 'unpaid', '6-month cycle bill - pending', 1, '2025-09-17 21:00:00', '2025-09-17 21:00:00'),
(19, 'INV-2025-0019', 11, '2025-05-05', 0.00, 50.00, 5.00, 52.50, 1050.00, 1102.50, 1102.50, 0.00, 'paid', 'First monthly bill', 1, '2025-05-04 21:00:00', '2025-05-10 02:30:00'),
(20, 'INV-2025-0020', 12, '2025-05-15', 0.00, 50.00, 5.00, 52.50, 1050.00, 1102.50, 500.00, 602.50, 'partial', 'First monthly bill - partial payment', 1, '2025-05-14 21:00:00', '2025-05-24 21:45:00'),
(21, 'INV-2025-0021', 13, '2025-06-10', 0.00, 50.00, 5.00, 52.50, 1050.00, 1102.50, 1102.50, 0.00, 'paid', 'First monthly bill', 1, '2025-06-09 21:00:00', '2025-06-14 23:20:00'),
(22, 'INV-2025-0022', 14, '2025-11-20', 0.00, 50.00, 5.00, 157.50, 3150.00, 3307.50, 100.00, 3207.50, 'partial', '6-month cycle bill - future', 1, '2025-11-19 21:00:00', '2025-11-15 23:38:07'),
(23, 'INV-2025-0023', 15, '2025-07-12', 0.00, 50.00, 5.00, 52.50, 1050.00, 1102.50, 1102.50, 0.00, 'paid', 'First monthly bill', 1, '2025-07-11 21:00:00', '2025-07-17 02:30:00'),
(24, 'INV-2025-0024', 16, '2026-07-08', 0.00, 50.00, 5.00, 157.50, 3150.00, 3307.50, 0.00, 3307.50, 'unpaid', '12-month cycle bill - future', 1, '2026-07-07 21:00:00', '2026-07-07 21:00:00'),
(25, 'INV-2025-0025', 17, '2025-09-05', 0.00, 50.00, 5.00, 52.50, 1050.00, 1102.50, 1102.50, 0.00, 'paid', 'First monthly bill', 1, '2025-09-04 21:00:00', '2025-09-09 23:20:00'),
(26, 'INV-2025-0026', 4, '2025-07-20', 2205.00, 50.00, 5.00, 52.50, 1050.00, 3307.50, 3307.50, 0.00, 'paid', 'Cleared all overdue amounts', 1, '2025-07-19 21:00:00', '2025-07-30 04:45:00'),
(27, 'INV-2025-0027', 18, '2025-09-18', 0.00, 50.00, 5.00, 52.50, 1050.00, 1102.50, 1102.50, 0.00, 'paid', 'First monthly bill', 1, '2025-09-17 21:00:00', '2025-09-23 02:30:00'),
(28, 'INV-2025-0028', 19, '2025-10-10', 0.00, 50.00, 5.00, 52.50, 1050.00, 1102.50, 1016.07, 86.43, 'partial', 'First monthly bill - new customer', 1, '2025-10-09 21:00:00', '2025-11-14 23:47:47'),
(29, 'INV-2025-0029', 20, '2026-03-22', 0.00, 50.00, 5.00, 157.50, 3150.00, 3307.50, 0.00, 3307.50, 'unpaid', '6-month cycle bill - future', 1, '2026-03-21 21:00:00', '2026-03-21 21:00:00'),
(30, 'INV-2025-0030', 8, '2025-08-20', 1102.50, 50.00, 5.00, 52.50, 1050.00, 2205.00, 2205.00, 0.00, 'paid', 'Cleared overdue amount', 1, '2025-08-19 21:00:00', '2025-08-28 03:20:00'),
(31, 'INV-2025-0031', 21, '2025-11-01', 0.00, 50.00, 5.00, 52.50, 1050.00, 1102.50, 500.00, 602.50, 'partial', 'First monthly bill - new Oct customer', 1, '2025-10-31 21:00:00', '2025-11-14 23:56:53'),
(32, 'INV-2025-0032', 22, '2025-11-02', 0.00, 50.00, 5.00, 52.50, 1050.00, 1102.50, 457.85, 644.65, 'partial', 'First monthly bill - new Oct customer', 1, '2025-11-01 21:00:00', '2025-11-15 22:38:33'),
(33, 'INV-2025-0033', 23, '2025-11-03', 0.00, 50.00, 5.00, 52.50, 1050.00, 1102.50, 1102.50, 0.00, 'paid', 'First monthly bill - new Oct customer', 1, '2025-11-02 21:00:00', '2025-11-13 03:49:27'),
(34, 'INV-2025-0034', 2, '2025-09-10', 3307.50, 50.00, 5.00, 52.50, 1050.00, 4410.00, 4410.00, 0.00, 'paid', 'Cleared 6-month cycle overdue', 1, '2025-09-09 21:00:00', '2025-09-25 02:15:00'),
(35, 'INV-TEST-001', 75, '2025-11-16', 0.00, 50.00, 5.00, 2.50, 52.50, 52.50, 0.00, 52.50, 'unpaid', 'Test invoice for Jeni Khan', 1, '2025-11-16 02:12:02', '2025-11-16 02:12:02'),
(36, 'INV-2025-0035', 1, '2025-11-30', 0.00, 50.00, 5.00, 82.50, 1650.00, 1732.50, 0.00, 1732.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Basic Plan, Streaming Plus', 1, '2025-11-16 03:35:30', '2025-11-16 03:35:30'),
(37, 'INV-2025-0036', 2, '2025-11-30', 3307.50, 50.00, 5.00, 172.50, 3450.00, 6930.00, 0.00, 6930.00, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Standard Plan, Gaming Boost, Family Pack (Includes ৳3,307.50 previous due)', 1, '2025-11-16 03:35:30', '2025-11-16 03:35:30'),
(38, 'INV-2025-0037', 3, '2025-11-30', 602.50, 50.00, 5.00, 307.50, 6150.00, 7060.00, 0.00, 7060.00, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Premium Plan, Gaming Boost, Turbo MAX (Includes ৳602.50 previous due)', 1, '2025-11-16 03:35:30', '2025-11-16 03:35:30'),
(39, 'INV-2025-0038', 4, '2025-11-30', 3307.50, 50.00, 5.00, 45.00, 900.00, 4252.50, 0.00, 4252.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Basic Plan, Streaming Plus (Includes ৳3,307.50 previous due)', 1, '2025-11-16 03:35:30', '2025-11-16 03:35:30'),
(40, 'INV-2025-0039', 5, '2025-11-30', 0.00, 50.00, 5.00, 127.50, 2550.00, 2677.50, 0.00, 2677.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Standard Plan, Gaming Boost, Streaming Plus', 1, '2025-11-16 03:35:30', '2025-11-16 03:35:30'),
(41, 'INV-2025-0040', 6, '2025-11-30', 0.00, 50.00, 5.00, 212.50, 4250.00, 4462.50, 0.00, 4462.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Basic Plan', 1, '2025-11-16 03:35:30', '2025-11-16 03:35:30'),
(42, 'INV-2025-0041', 7, '2025-11-30', 0.00, 50.00, 5.00, 167.50, 3350.00, 3517.50, 0.00, 3517.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Premium Plan, Streaming Plus', 1, '2025-11-16 03:35:30', '2025-11-16 03:35:30'),
(43, 'INV-2025-0042', 8, '2025-11-30', 1102.50, 50.00, 5.00, 172.50, 3450.00, 4725.00, 0.00, 4725.00, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Standard Plan, Gaming Boost, Family Pack (Includes ৳1,102.50 previous due)', 1, '2025-11-16 03:35:30', '2025-11-16 03:35:30'),
(44, 'INV-2025-0043', 9, '2025-11-30', 0.00, 50.00, 5.00, 37.50, 750.00, 787.50, 0.00, 787.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Basic Plan', 1, '2025-11-16 03:35:30', '2025-11-16 03:35:30'),
(45, 'INV-2025-0044', 10, '2025-11-30', 3307.50, 50.00, 5.00, 542.50, 10850.00, 14700.00, 0.00, 14700.00, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Premium Plan, Family Pack (Includes ৳3,307.50 previous due)', 1, '2025-11-16 03:35:30', '2025-11-16 03:35:30'),
(46, 'INV-2025-0045', 11, '2025-11-30', 0.00, 50.00, 5.00, 112.50, 2250.00, 2362.50, 0.00, 2362.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Basic Plan, Gaming Boost, Streaming Plus', 1, '2025-11-16 03:35:30', '2025-11-16 03:35:30'),
(47, 'INV-2025-0046', 12, '2025-11-30', 602.50, 50.00, 5.00, 52.50, 1050.00, 1705.00, 0.00, 1705.00, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Standard Plan (Includes ৳602.50 previous due)', 1, '2025-11-16 03:35:30', '2025-11-16 03:35:30'),
(48, 'INV-2025-0047', 13, '2025-11-30', 0.00, 50.00, 5.00, 107.50, 2150.00, 2257.50, 0.00, 2257.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Premium Plan, Gaming Boost', 1, '2025-11-16 03:35:30', '2025-11-16 03:35:30'),
(49, 'INV-2025-0048', 15, '2025-11-30', 0.00, 50.00, 5.00, 52.50, 1050.00, 1102.50, 0.00, 1102.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Standard Plan', 1, '2025-11-16 03:35:30', '2025-11-16 03:35:30'),
(50, 'INV-2025-0049', 16, '2025-11-30', 3307.50, 50.00, 5.00, 167.50, 3350.00, 6825.00, 0.00, 6825.00, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Premium Plan, Streaming Plus (Includes ৳3,307.50 previous due)', 1, '2025-11-16 03:35:30', '2025-11-16 03:35:30'),
(51, 'INV-2025-0050', 17, '2025-11-30', 0.00, 50.00, 5.00, 157.50, 3150.00, 3307.50, 0.00, 3307.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Basic Plan, Gaming Boost, Family Pack', 1, '2025-11-16 03:35:30', '2025-11-16 03:35:30'),
(52, 'INV-2025-0051', 18, '2025-11-30', 0.00, 50.00, 5.00, 52.50, 1050.00, 1102.50, 0.00, 1102.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Standard Plan', 1, '2025-11-16 03:35:30', '2025-11-16 03:35:30'),
(53, 'INV-2025-0052', 19, '2025-11-30', 86.43, 50.00, 5.00, 107.50, 2150.00, 2343.93, 0.00, 2343.93, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Premium Plan, Gaming Boost (Includes ৳86.43 previous due)', 1, '2025-11-16 03:35:30', '2025-11-16 03:35:30'),
(54, 'INV-2025-0053', 20, '2025-11-30', 3307.50, 50.00, 5.00, 262.50, 5250.00, 8820.00, 0.00, 8820.00, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Basic Plan, Streaming Plus, Family Pack (Includes ৳3,307.50 previous due)', 1, '2025-11-16 03:35:30', '2025-11-16 03:35:30'),
(55, 'INV-2025-0054', 24, '2025-11-30', 0.00, 50.00, 5.00, 157.50, 3150.00, 3307.50, 0.00, 3307.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Basic Plan, Gaming Boost, Family Pack', 1, '2025-11-16 03:35:30', '2025-11-16 03:35:30'),
(56, 'INV-2025-0055', 25, '2025-11-30', 0.00, 50.00, 5.00, 52.50, 1050.00, 1102.50, 0.00, 1102.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Standard Plan', 1, '2025-11-16 03:35:30', '2025-11-16 03:35:30'),
(57, 'INV-2025-0056', 26, '2025-11-30', 0.00, 50.00, 5.00, 167.50, 3350.00, 3517.50, 0.00, 3517.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Premium Plan, Streaming Plus', 1, '2025-11-16 03:35:30', '2025-11-16 03:35:30'),
(58, 'INV-2025-0057', 27, '2025-11-30', 0.00, 50.00, 5.00, 67.50, 1350.00, 1417.50, 0.00, 1417.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Basic Plan, Gaming Boost', 1, '2025-11-16 03:35:30', '2025-11-16 03:35:30'),
(59, 'INV-2025-0058', 28, '2025-11-30', 0.00, 50.00, 5.00, 142.50, 2850.00, 2992.50, 0.00, 2992.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Standard Plan, Family Pack', 1, '2025-11-16 03:35:30', '2025-11-16 03:35:30'),
(60, 'INV-2025-0059', 29, '2025-11-30', 0.00, 50.00, 5.00, 77.50, 1550.00, 1627.50, 0.00, 1627.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Premium Plan', 1, '2025-11-16 03:35:30', '2025-11-16 03:35:30'),
(61, 'INV-2025-0060', 30, '2025-11-30', 0.00, 50.00, 5.00, 82.50, 1650.00, 1732.50, 0.00, 1732.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Basic Plan, Streaming Plus', 1, '2025-11-16 03:35:30', '2025-11-16 03:35:30'),
(62, 'INV-2025-0061', 31, '2025-11-30', 0.00, 50.00, 5.00, 52.50, 1050.00, 1102.50, 0.00, 1102.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Standard Plan', 1, '2025-11-16 03:35:30', '2025-11-16 03:35:30'),
(63, 'INV-2025-0062', 32, '2025-11-30', 0.00, 50.00, 5.00, 107.50, 2150.00, 2257.50, 0.00, 2257.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Premium Plan, Gaming Boost', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(64, 'INV-2025-0063', 33, '2025-11-30', 0.00, 50.00, 5.00, 227.50, 4550.00, 4777.50, 0.00, 4777.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Streaming Plus, Family Pack', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(65, 'INV-2025-0064', 34, '2025-11-30', 0.00, 50.00, 5.00, 52.50, 1050.00, 1102.50, 0.00, 1102.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Standard Plan', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(66, 'INV-2025-0065', 35, '2025-11-30', 0.00, 50.00, 5.00, 107.50, 2150.00, 2257.50, 0.00, 2257.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Premium Plan, Gaming Boost', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(67, 'INV-2025-0066', 36, '2025-11-30', 0.00, 50.00, 5.00, 37.50, 750.00, 787.50, 0.00, 787.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Basic Plan', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(68, 'INV-2025-0067', 37, '2025-11-30', 0.00, 50.00, 5.00, 142.50, 2850.00, 2992.50, 0.00, 2992.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Standard Plan, Family Pack', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(69, 'INV-2025-0068', 38, '2025-11-30', 0.00, 50.00, 5.00, 77.50, 1550.00, 1627.50, 0.00, 1627.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Premium Plan', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(70, 'INV-2025-0069', 39, '2025-11-30', 0.00, 50.00, 5.00, 82.50, 1650.00, 1732.50, 0.00, 1732.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Basic Plan, Streaming Plus', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(71, 'INV-2025-0070', 40, '2025-11-30', 0.00, 50.00, 5.00, 82.50, 1650.00, 1732.50, 0.00, 1732.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Standard Plan, Gaming Boost', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(72, 'INV-2025-0071', 41, '2025-11-30', 0.00, 50.00, 5.00, 77.50, 1550.00, 1627.50, 0.00, 1627.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Premium Plan', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(73, 'INV-2025-0072', 43, '2025-11-30', 0.00, 50.00, 5.00, 52.50, 1050.00, 1102.50, 0.00, 1102.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Standard Plan', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(74, 'INV-2025-0073', 44, '2025-11-30', 0.00, 50.00, 5.00, 77.50, 1550.00, 1627.50, 0.00, 1627.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Premium Plan', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(75, 'INV-2025-0074', 45, '2025-11-30', 0.00, 50.00, 5.00, 37.50, 750.00, 787.50, 0.00, 787.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Basic Plan', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(76, 'INV-2025-0075', 46, '2025-11-30', 0.00, 50.00, 5.00, 52.50, 1050.00, 1102.50, 0.00, 1102.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Standard Plan', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(77, 'INV-2025-0076', 47, '2025-11-30', 0.00, 50.00, 5.00, 107.50, 2150.00, 2257.50, 0.00, 2257.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Premium Plan, Gaming Boost', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(78, 'INV-2025-0077', 48, '2025-11-30', 0.00, 50.00, 5.00, 127.50, 2550.00, 2677.50, 0.00, 2677.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Basic Plan, Family Pack', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(79, 'INV-2025-0078', 49, '2025-11-30', 0.00, 50.00, 5.00, 52.50, 1050.00, 1102.50, 0.00, 1102.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Standard Plan', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(80, 'INV-2025-0079', 50, '2025-11-30', 0.00, 50.00, 5.00, 167.50, 3350.00, 3517.50, 0.00, 3517.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Premium Plan, Streaming Plus', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(81, 'INV-2025-0080', 51, '2025-11-30', 0.00, 50.00, 5.00, 67.50, 1350.00, 1417.50, 0.00, 1417.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Basic Plan, Gaming Boost', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(82, 'INV-2025-0081', 52, '2025-11-30', 0.00, 50.00, 5.00, 52.50, 1050.00, 1102.50, 0.00, 1102.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Standard Plan', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(83, 'INV-2025-0082', 53, '2025-11-30', 0.00, 50.00, 5.00, 167.50, 3350.00, 3517.50, 0.00, 3517.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Premium Plan, Family Pack', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(84, 'INV-2025-0083', 54, '2025-11-30', 0.00, 50.00, 5.00, 37.50, 750.00, 787.50, 0.00, 787.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Basic Plan', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(85, 'INV-2025-0084', 55, '2025-11-30', 0.00, 50.00, 5.00, 52.50, 1050.00, 1102.50, 0.00, 1102.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Standard Plan', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(86, 'INV-2025-0085', 56, '2025-11-30', 0.00, 50.00, 5.00, 77.50, 1550.00, 1627.50, 0.00, 1627.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Premium Plan', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(87, 'INV-2025-0086', 57, '2025-11-30', 0.00, 50.00, 5.00, 2332.50, 46650.00, 48982.50, 0.00, 48982.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Basic Plan, Streaming Plus, WS, WS', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(88, 'INV-2025-0087', 58, '2025-11-30', 0.00, 50.00, 5.00, 52.50, 1050.00, 1102.50, 0.00, 1102.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Standard Plan', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(89, 'INV-2025-0088', 59, '2025-11-30', 0.00, 50.00, 5.00, 107.50, 2150.00, 2257.50, 0.00, 2257.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Premium Plan, Gaming Boost', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(90, 'INV-2025-0089', 60, '2025-11-30', 0.00, 50.00, 5.00, 37.50, 750.00, 787.50, 0.00, 787.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Basic Plan', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(91, 'INV-2025-0090', 61, '2025-11-30', 0.00, 50.00, 5.00, 232.50, 4650.00, 4882.50, 0.00, 4882.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Standard Plan, Family Pack', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(92, 'INV-2025-0091', 62, '2025-11-30', 0.00, 50.00, 5.00, 77.50, 1550.00, 1627.50, 0.00, 1627.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Premium Plan', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(93, 'INV-2025-0092', 63, '2025-11-30', 0.00, 50.00, 5.00, 82.50, 1650.00, 1732.50, 0.00, 1732.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Basic Plan, Streaming Plus', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(94, 'INV-2025-0093', 64, '2025-11-30', 0.00, 50.00, 5.00, 82.50, 1650.00, 1732.50, 0.00, 1732.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Standard Plan, Gaming Boost', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(95, 'INV-2025-0094', 65, '2025-11-30', 0.00, 50.00, 5.00, 167.50, 3350.00, 3517.50, 0.00, 3517.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Premium Plan, Family Pack', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(96, 'INV-2025-0095', 66, '2025-11-30', 0.00, 50.00, 5.00, 902.50, 18050.00, 18952.50, 0.00, 18952.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Turbo, Basic Plan, Standard Plan', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(97, 'INV-2025-0096', 74, '2025-11-30', 0.00, 50.00, 5.00, 57.50, 1150.00, 1207.50, 0.00, 1207.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: star, Test Package', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(98, 'INV-2025-0097', 73, '2025-11-30', 0.00, 50.00, 5.00, 152.50, 3050.00, 3202.50, 0.00, 3202.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Family Pack, Gaming Boost', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(99, 'INV-2025-0098', 76, '2025-11-30', 0.00, 50.00, 5.00, 47.50, 950.00, 997.50, 0.00, 997.50, 'unpaid', 'Auto-generated invoice for November 2025 - Products: Gaming Boost, Test Package', 1, '2025-11-16 03:35:31', '2025-11-16 03:35:31'),
(100, 'INV-2025-0099', 76, '2025-03-31', 997.50, 50.00, 5.00, 32.50, 650.00, 1680.00, 0.00, 1680.00, 'unpaid', 'Auto-generated: 3-Month billing for 3 month(s) - Due for March 2025 (Includes ৳997.50 previous due)', 1, '2025-11-16 03:49:55', '2025-11-16 03:49:55'),
(101, 'INV-2025-0100', 9, '2025-03-31', 787.50, 50.00, 5.00, 37.50, 750.00, 1575.00, 0.00, 1575.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for March 2025 (Includes ৳787.50 previous due)', 1, '2025-11-16 03:49:55', '2025-11-16 03:49:55'),
(102, 'INV-2025-0101', 75, '2025-03-31', 52.50, 50.00, 5.00, 32.50, 650.00, 735.00, 0.00, 735.00, 'unpaid', 'Auto-generated: 3-Month billing for 3 month(s) - Due for March 2025 (Includes ৳52.50 previous due)', 1, '2025-11-16 03:49:55', '2025-11-16 03:49:55'),
(103, 'INV-2025-0102', 5, '2025-03-31', 2677.50, 50.00, 5.00, 127.50, 2550.00, 5355.00, 0.00, 5355.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s), 6-Month billing for 6 month(s) - Due for March 2025 (Includes ৳2,677.50 previous due)', 1, '2025-11-16 03:49:55', '2025-11-16 03:49:55'),
(104, 'INV-2025-0103', 10, '2025-03-31', 18007.50, 50.00, 5.00, 542.50, 10850.00, 29400.00, 0.00, 29400.00, 'unpaid', 'Auto-generated: 6-Month billing for 6 month(s), 6-Month billing for 6 month(s) - Due for March 2025 (Includes ৳18,007.50 previous due)', 1, '2025-11-16 03:49:55', '2025-11-16 03:49:55'),
(105, 'INV-2025-0104', 76, '2025-02-28', 2677.50, 50.00, 5.00, 32.50, 650.00, 3360.00, 0.00, 3360.00, 'unpaid', 'Auto-generated: 3-Month billing for 3 month(s) - Due for February 2025 (Includes ৳2,677.50 previous due)', 1, '2025-11-16 03:51:03', '2025-11-16 03:51:03'),
(106, 'INV-2025-0105', 6, '2025-02-28', 4462.50, 50.00, 5.00, 212.50, 4250.00, 8925.00, 0.00, 8925.00, 'unpaid', 'Auto-generated: 6-Month billing for 6 month(s) - Due for February 2025 (Includes ৳4,462.50 previous due)', 1, '2025-11-16 03:51:03', '2025-11-16 03:51:03'),
(107, 'INV-2025-0106', 75, '2025-02-28', 787.50, 50.00, 5.00, 32.50, 650.00, 1470.00, 0.00, 1470.00, 'unpaid', 'Auto-generated: 3-Month billing for 3 month(s) - Due for February 2025 (Includes ৳787.50 previous due)', 1, '2025-11-16 03:51:04', '2025-11-16 03:51:04'),
(108, 'INV-2025-0107', 7, '2025-02-28', 3517.50, 50.00, 5.00, 167.50, 3350.00, 7035.00, 0.00, 7035.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 12-Month billing for 12 month(s) - Due for February 2025 (Includes ৳3,517.50 previous due)', 1, '2025-11-16 03:51:04', '2025-11-16 03:51:04'),
(109, 'INV-2025-0108', 8, '2025-02-28', 5827.50, 50.00, 5.00, 172.50, 3450.00, 9450.00, 0.00, 9450.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s), 6-Month billing for 6 month(s) - Due for February 2025 (Includes ৳5,827.50 previous due)', 1, '2025-11-16 03:51:04', '2025-11-16 03:51:04'),
(110, 'INV-2025-0109', 3, '2025-04-30', 7662.50, 50.00, 5.00, 77.50, 1550.00, 9290.00, 0.00, 9290.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for April 2025 (Includes ৳7,662.50 previous due)', 1, '2025-11-16 03:51:59', '2025-11-16 03:51:59'),
(111, 'INV-2025-0110', 76, '2025-04-30', 6037.50, 50.00, 5.00, 32.50, 650.00, 6720.00, 0.00, 6720.00, 'unpaid', 'Auto-generated: 3-Month billing for 3 month(s) - Due for April 2025 (Includes ৳6,037.50 previous due)', 1, '2025-11-16 03:51:59', '2025-11-16 03:51:59'),
(112, 'INV-2025-0111', 6, '2025-04-30', 13387.50, 50.00, 5.00, 212.50, 4250.00, 17850.00, 0.00, 17850.00, 'unpaid', 'Auto-generated: 6-Month billing for 6 month(s) - Due for April 2025 (Includes ৳13,387.50 previous due)', 1, '2025-11-16 03:51:59', '2025-11-16 03:51:59'),
(113, 'INV-2025-0112', 75, '2025-04-30', 2257.50, 50.00, 5.00, 32.50, 650.00, 2940.00, 0.00, 2940.00, 'unpaid', 'Auto-generated: 3-Month billing for 3 month(s) - Due for April 2025 (Includes ৳2,257.50 previous due)', 1, '2025-11-16 03:51:59', '2025-11-16 03:51:59'),
(114, 'INV-2025-0113', 7, '2025-04-30', 10552.50, 50.00, 5.00, 167.50, 3350.00, 14070.00, 0.00, 14070.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 12-Month billing for 12 month(s) - Due for April 2025 (Includes ৳10,552.50 previous due)', 1, '2025-11-16 03:51:59', '2025-11-16 03:51:59'),
(115, 'INV-2025-0114', 4, '2025-04-30', 7560.00, 50.00, 5.00, 45.00, 900.00, 8505.00, 0.00, 8505.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 1-Month billing for 1 month(s) - Due for April 2025 (Includes ৳7,560.00 previous due)', 1, '2025-11-16 03:51:59', '2025-11-16 03:51:59'),
(116, 'INV-2025-0115', 12, '2025-04-30', 2307.50, 50.00, 5.00, 52.50, 1050.00, 3410.00, 0.00, 3410.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for April 2025 (Includes ৳2,307.50 previous due)', 1, '2025-11-16 03:51:59', '2025-11-16 03:51:59'),
(117, 'INV-2025-0116', 5, '2025-04-30', 8032.50, 50.00, 5.00, 127.50, 2550.00, 10710.00, 0.00, 10710.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s), 6-Month billing for 6 month(s) - Due for April 2025 (Includes ৳8,032.50 previous due)', 1, '2025-11-16 03:51:59', '2025-11-16 03:51:59'),
(118, 'INV-2025-0117', 8, '2025-04-30', 15277.50, 50.00, 5.00, 172.50, 3450.00, 18900.00, 0.00, 18900.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s), 6-Month billing for 6 month(s) - Due for April 2025 (Includes ৳15,277.50 previous due)', 1, '2025-11-16 03:51:59', '2025-11-16 03:51:59'),
(119, 'INV-2025-0118', 11, '2025-04-30', 2362.50, 50.00, 5.00, 112.50, 2250.00, 4725.00, 0.00, 4725.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s), 6-Month billing for 6 month(s) - Due for April 2025 (Includes ৳2,362.50 previous due)', 1, '2025-11-16 03:51:59', '2025-11-16 03:51:59'),
(120, 'INV-2025-0119', 10, '2025-04-30', 47407.50, 50.00, 5.00, 542.50, 10850.00, 58800.00, 0.00, 58800.00, 'unpaid', 'Auto-generated: 6-Month billing for 6 month(s), 6-Month billing for 6 month(s) - Due for April 2025 (Includes ৳47,407.50 previous due)', 1, '2025-11-16 03:51:59', '2025-11-16 03:51:59'),
(121, 'INV-2025-0120', 3, '2025-10-31', 16952.50, 50.00, 5.00, 77.50, 1550.00, 18580.00, 0.00, 18580.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for October 2025 (Includes ৳16,952.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(122, 'INV-2025-0121', 33, '2025-10-31', 4777.50, 50.00, 5.00, 227.50, 4550.00, 9555.00, 0.00, 9555.00, 'unpaid', 'Auto-generated: 6-Month billing for 6 month(s), 12-Month billing for 12 month(s) - Due for October 2025 (Includes ৳4,777.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(123, 'INV-2025-0122', 57, '2025-10-31', 48982.50, 50.00, 5.00, 1582.50, 31650.00, 82215.00, 0.00, 82215.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 6-Month billing for 6 month(s), 6-Month billing for 6 month(s) - Due for October 2025 (Includes ৳48,982.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(124, 'INV-2025-0123', 18, '2025-10-31', 1102.50, 50.00, 5.00, 52.50, 1050.00, 2205.00, 0.00, 2205.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for October 2025 (Includes ৳1,102.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(125, 'INV-2025-0124', 76, '2025-10-31', 12757.50, 50.00, 5.00, 32.50, 650.00, 13440.00, 0.00, 13440.00, 'unpaid', 'Auto-generated: 3-Month billing for 3 month(s) - Due for October 2025 (Includes ৳12,757.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(126, 'INV-2025-0125', 9, '2025-10-31', 2362.50, 50.00, 5.00, 37.50, 750.00, 3150.00, 0.00, 3150.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for October 2025 (Includes ৳2,362.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(127, 'INV-2025-0126', 39, '2025-10-31', 1732.50, 50.00, 5.00, 82.50, 1650.00, 3465.00, 0.00, 3465.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 6-Month billing for 6 month(s) - Due for October 2025 (Includes ৳1,732.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(128, 'INV-2025-0127', 51, '2025-10-31', 1417.50, 50.00, 5.00, 67.50, 1350.00, 2835.00, 0.00, 2835.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s) - Due for October 2025 (Includes ৳1,417.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(129, 'INV-2025-0128', 27, '2025-10-31', 1417.50, 50.00, 5.00, 67.50, 1350.00, 2835.00, 0.00, 2835.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s) - Due for October 2025 (Includes ৳1,417.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(130, 'INV-2025-0129', 15, '2025-10-31', 1102.50, 50.00, 5.00, 52.50, 1050.00, 2205.00, 0.00, 2205.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for October 2025 (Includes ৳1,102.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(131, 'INV-2025-0130', 32, '2025-10-31', 2257.50, 50.00, 5.00, 107.50, 2150.00, 4515.00, 0.00, 4515.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s) - Due for October 2025 (Includes ৳2,257.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(132, 'INV-2025-0131', 6, '2025-10-31', 31237.50, 50.00, 5.00, 212.50, 4250.00, 35700.00, 0.00, 35700.00, 'unpaid', 'Auto-generated: 6-Month billing for 6 month(s) - Due for October 2025 (Includes ৳31,237.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(133, 'INV-2025-0132', 13, '2025-10-31', 2257.50, 50.00, 5.00, 107.50, 2150.00, 4515.00, 0.00, 4515.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s) - Due for October 2025 (Includes ৳2,257.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(134, 'INV-2025-0133', 43, '2025-10-31', 1102.50, 50.00, 5.00, 52.50, 1050.00, 2205.00, 0.00, 2205.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for October 2025 (Includes ৳1,102.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(135, 'INV-2025-0134', 20, '2025-10-31', 12127.50, 50.00, 5.00, 262.50, 5250.00, 17640.00, 0.00, 17640.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 6-Month billing for 6 month(s), 12-Month billing for 12 month(s) - Due for October 2025 (Includes ৳12,127.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(136, 'INV-2025-0135', 75, '2025-10-31', 5197.50, 50.00, 5.00, 32.50, 650.00, 5880.00, 0.00, 5880.00, 'unpaid', 'Auto-generated: 3-Month billing for 3 month(s) - Due for October 2025 (Includes ৳5,197.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(137, 'INV-2025-0136', 7, '2025-10-31', 24622.50, 50.00, 5.00, 167.50, 3350.00, 28140.00, 0.00, 28140.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 12-Month billing for 12 month(s) - Due for October 2025 (Includes ৳24,622.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(138, 'INV-2025-0137', 47, '2025-10-31', 2257.50, 50.00, 5.00, 107.50, 2150.00, 4515.00, 0.00, 4515.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s) - Due for October 2025 (Includes ৳2,257.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(139, 'INV-2025-0138', 60, '2025-10-31', 787.50, 50.00, 5.00, 37.50, 750.00, 1575.00, 0.00, 1575.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for October 2025 (Includes ৳787.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(140, 'INV-2025-0139', 29, '2025-10-31', 1627.50, 50.00, 5.00, 77.50, 1550.00, 3255.00, 0.00, 3255.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for October 2025 (Includes ৳1,627.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(141, 'INV-2025-0140', 62, '2025-10-31', 1627.50, 50.00, 5.00, 77.50, 1550.00, 3255.00, 0.00, 3255.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for October 2025 (Includes ৳1,627.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(142, 'INV-2025-0141', 48, '2025-10-31', 2677.50, 50.00, 5.00, 127.50, 2550.00, 5355.00, 0.00, 5355.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 6-Month billing for 6 month(s) - Due for October 2025 (Includes ৳2,677.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(143, 'INV-2025-0142', 24, '2025-10-31', 3307.50, 50.00, 5.00, 157.50, 3150.00, 6615.00, 0.00, 6615.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s), 6-Month billing for 6 month(s) - Due for October 2025 (Includes ৳3,307.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(144, 'INV-2025-0143', 55, '2025-10-31', 1102.50, 50.00, 5.00, 52.50, 1050.00, 2205.00, 0.00, 2205.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for October 2025 (Includes ৳1,102.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(145, 'INV-2025-0144', 52, '2025-10-31', 1102.50, 50.00, 5.00, 52.50, 1050.00, 2205.00, 0.00, 2205.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for October 2025 (Includes ৳1,102.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(146, 'INV-2025-0145', 36, '2025-10-31', 787.50, 50.00, 5.00, 37.50, 750.00, 1575.00, 0.00, 1575.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for October 2025 (Includes ৳787.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(147, 'INV-2025-0146', 14, '2025-10-31', 3207.50, 50.00, 5.00, 262.50, 5250.00, 8720.00, 0.00, 8720.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 6-Month billing for 6 month(s), 12-Month billing for 12 month(s) - Due for October 2025 (Includes ৳3,207.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(148, 'INV-2025-0147', 4, '2025-10-31', 16065.00, 50.00, 5.00, 45.00, 900.00, 17010.00, 0.00, 17010.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 1-Month billing for 1 month(s) - Due for October 2025 (Includes ৳16,065.00 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(149, 'INV-2025-0148', 38, '2025-10-31', 1627.50, 50.00, 5.00, 77.50, 1550.00, 3255.00, 0.00, 3255.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for October 2025 (Includes ৳1,627.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(150, 'INV-2025-0149', 23, '2025-10-31', 0.00, 50.00, 5.00, 122.50, 2450.00, 2572.50, 0.00, 2572.50, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 6-Month billing for 6 month(s) - Due for October 2025', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(151, 'INV-2025-0150', 2, '2025-10-31', 10237.50, 50.00, 5.00, 172.50, 3450.00, 13860.00, 0.00, 13860.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s), 6-Month billing for 6 month(s) - Due for October 2025 (Includes ৳10,237.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(152, 'INV-2025-0151', 34, '2025-10-31', 1102.50, 50.00, 5.00, 52.50, 1050.00, 2205.00, 0.00, 2205.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for October 2025 (Includes ৳1,102.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(153, 'INV-2025-0152', 56, '2025-10-31', 1627.50, 50.00, 5.00, 77.50, 1550.00, 3255.00, 0.00, 3255.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for October 2025 (Includes ৳1,627.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(154, 'INV-2025-0153', 46, '2025-10-31', 1102.50, 50.00, 5.00, 52.50, 1050.00, 2205.00, 0.00, 2205.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for October 2025 (Includes ৳1,102.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(155, 'INV-2025-0154', 12, '2025-10-31', 5717.50, 50.00, 5.00, 52.50, 1050.00, 6820.00, 0.00, 6820.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for October 2025 (Includes ৳5,717.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(156, 'INV-2025-0155', 26, '2025-10-31', 3517.50, 50.00, 5.00, 167.50, 3350.00, 7035.00, 0.00, 7035.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 12-Month billing for 12 month(s) - Due for October 2025 (Includes ৳3,517.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(157, 'INV-2025-0156', 21, '2025-10-31', 602.50, 50.00, 5.00, 52.50, 1050.00, 1705.00, 0.00, 1705.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for October 2025 (Includes ৳602.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(158, 'INV-2025-0157', 5, '2025-10-31', 18742.50, 50.00, 5.00, 127.50, 2550.00, 21420.00, 0.00, 21420.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s), 6-Month billing for 6 month(s) - Due for October 2025 (Includes ৳18,742.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(159, 'INV-2025-0158', 53, '2025-10-31', 3517.50, 50.00, 5.00, 167.50, 3350.00, 7035.00, 0.00, 7035.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 6-Month billing for 6 month(s) - Due for October 2025 (Includes ৳3,517.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(160, 'INV-2025-0159', 17, '2025-10-31', 3307.50, 50.00, 5.00, 157.50, 3150.00, 6615.00, 0.00, 6615.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s), 6-Month billing for 6 month(s) - Due for October 2025 (Includes ৳3,307.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(161, 'INV-2025-0160', 49, '2025-10-31', 1102.50, 50.00, 5.00, 52.50, 1050.00, 2205.00, 0.00, 2205.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for October 2025 (Includes ৳1,102.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(162, 'INV-2025-0161', 40, '2025-10-31', 1732.50, 50.00, 5.00, 82.50, 1650.00, 3465.00, 0.00, 3465.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s) - Due for October 2025 (Includes ৳1,732.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(163, 'INV-2025-0162', 58, '2025-10-31', 1102.50, 50.00, 5.00, 52.50, 1050.00, 2205.00, 0.00, 2205.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for October 2025 (Includes ৳1,102.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(164, 'INV-2025-0163', 31, '2025-10-31', 1102.50, 50.00, 5.00, 52.50, 1050.00, 2205.00, 0.00, 2205.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for October 2025 (Includes ৳1,102.50 previous due)', 1, '2025-11-16 03:52:23', '2025-11-16 03:52:23'),
(165, 'INV-2025-0164', 63, '2025-10-31', 1732.50, 50.00, 5.00, 82.50, 1650.00, 3465.00, 0.00, 3465.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 6-Month billing for 6 month(s) - Due for October 2025 (Includes ৳1,732.50 previous due)', 1, '2025-11-16 03:52:24', '2025-11-16 03:52:24'),
(166, 'INV-2025-0165', 44, '2025-10-31', 1627.50, 50.00, 5.00, 77.50, 1550.00, 3255.00, 0.00, 3255.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for October 2025 (Includes ৳1,627.50 previous due)', 1, '2025-11-16 03:52:24', '2025-11-16 03:52:24'),
(167, 'INV-2025-0166', 16, '2025-10-31', 10132.50, 50.00, 5.00, 167.50, 3350.00, 13650.00, 0.00, 13650.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 12-Month billing for 12 month(s) - Due for October 2025 (Includes ৳10,132.50 previous due)', 1, '2025-11-16 03:52:24', '2025-11-16 03:52:24'),
(168, 'INV-2025-0167', 8, '2025-10-31', 34177.50, 50.00, 5.00, 172.50, 3450.00, 37800.00, 0.00, 37800.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s), 6-Month billing for 6 month(s) - Due for October 2025 (Includes ৳34,177.50 previous due)', 1, '2025-11-16 03:52:24', '2025-11-16 03:52:24'),
(169, 'INV-2025-0168', 25, '2025-10-31', 1102.50, 50.00, 5.00, 52.50, 1050.00, 2205.00, 0.00, 2205.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for October 2025 (Includes ৳1,102.50 previous due)', 1, '2025-11-16 03:52:24', '2025-11-16 03:52:24'),
(170, 'INV-2025-0169', 65, '2025-10-31', 3517.50, 50.00, 5.00, 167.50, 3350.00, 7035.00, 0.00, 7035.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 6-Month billing for 6 month(s) - Due for October 2025 (Includes ৳3,517.50 previous due)', 1, '2025-11-16 03:52:24', '2025-11-16 03:52:24'),
(171, 'INV-2025-0170', 45, '2025-10-31', 787.50, 50.00, 5.00, 37.50, 750.00, 1575.00, 0.00, 1575.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for October 2025 (Includes ৳787.50 previous due)', 1, '2025-11-16 03:52:24', '2025-11-16 03:52:24'),
(172, 'INV-2025-0171', 35, '2025-10-31', 2257.50, 50.00, 5.00, 107.50, 2150.00, 4515.00, 0.00, 4515.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s) - Due for October 2025 (Includes ৳2,257.50 previous due)', 1, '2025-11-16 03:52:24', '2025-11-16 03:52:24'),
(173, 'INV-2025-0172', 41, '2025-10-31', 1627.50, 50.00, 5.00, 77.50, 1550.00, 3255.00, 0.00, 3255.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for October 2025 (Includes ৳1,627.50 previous due)', 1, '2025-11-16 03:52:24', '2025-11-16 03:52:24'),
(174, 'INV-2025-0173', 61, '2025-10-31', 4882.50, 50.00, 5.00, 232.50, 4650.00, 9765.00, 0.00, 9765.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 12-Month billing for 12 month(s) - Due for October 2025 (Includes ৳4,882.50 previous due)', 1, '2025-11-16 03:52:24', '2025-11-16 03:52:24'),
(175, 'INV-2025-0174', 37, '2025-10-31', 2992.50, 50.00, 5.00, 142.50, 2850.00, 5985.00, 0.00, 5985.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 6-Month billing for 6 month(s) - Due for October 2025 (Includes ৳2,992.50 previous due)', 1, '2025-11-16 03:52:24', '2025-11-16 03:52:24'),
(176, 'INV-2025-0175', 50, '2025-10-31', 3517.50, 50.00, 5.00, 167.50, 3350.00, 7035.00, 0.00, 7035.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 12-Month billing for 12 month(s) - Due for October 2025 (Includes ৳3,517.50 previous due)', 1, '2025-11-16 03:52:24', '2025-11-16 03:52:24'),
(177, 'INV-2025-0176', 22, '2025-10-31', 644.65, 50.00, 5.00, 67.50, 1350.00, 2062.15, 0.00, 2062.15, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s) - Due for October 2025 (Includes ৳644.65 previous due)', 1, '2025-11-16 03:52:24', '2025-11-16 03:52:24'),
(178, 'INV-2025-0177', 54, '2025-10-31', 787.50, 50.00, 5.00, 37.50, 750.00, 1575.00, 0.00, 1575.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for October 2025 (Includes ৳787.50 previous due)', 1, '2025-11-16 03:52:24', '2025-11-16 03:52:24'),
(179, 'INV-2025-0178', 30, '2025-10-31', 1732.50, 50.00, 5.00, 82.50, 1650.00, 3465.00, 0.00, 3465.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 6-Month billing for 6 month(s) - Due for October 2025 (Includes ৳1,732.50 previous due)', 1, '2025-11-16 03:52:24', '2025-11-16 03:52:24'),
(180, 'INV-2025-0179', 59, '2025-10-31', 2257.50, 50.00, 5.00, 107.50, 2150.00, 4515.00, 0.00, 4515.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s) - Due for October 2025 (Includes ৳2,257.50 previous due)', 1, '2025-11-16 03:52:24', '2025-11-16 03:52:24'),
(181, 'INV-2025-0180', 11, '2025-10-31', 7087.50, 50.00, 5.00, 112.50, 2250.00, 9450.00, 0.00, 9450.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s), 6-Month billing for 6 month(s) - Due for October 2025 (Includes ৳7,087.50 previous due)', 1, '2025-11-16 03:52:24', '2025-11-16 03:52:24'),
(182, 'INV-2025-0181', 1, '2025-10-31', 1732.50, 50.00, 5.00, 82.50, 1650.00, 3465.00, 0.00, 3465.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 6-Month billing for 6 month(s) - Due for October 2025 (Includes ৳1,732.50 previous due)', 1, '2025-11-16 03:52:24', '2025-11-16 03:52:24'),
(183, 'INV-2025-0182', 10, '2025-10-31', 106207.50, 50.00, 5.00, 542.50, 10850.00, 117600.00, 0.00, 117600.00, 'unpaid', 'Auto-generated: 6-Month billing for 6 month(s), 6-Month billing for 6 month(s) - Due for October 2025 (Includes ৳106,207.50 previous due)', 1, '2025-11-16 03:52:24', '2025-11-16 03:52:24'),
(184, 'INV-2025-0183', 64, '2025-10-31', 1732.50, 50.00, 5.00, 82.50, 1650.00, 3465.00, 0.00, 3465.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s) - Due for October 2025 (Includes ৳1,732.50 previous due)', 1, '2025-11-16 03:52:24', '2025-11-16 03:52:24'),
(185, 'INV-2025-0184', 28, '2025-10-31', 2992.50, 50.00, 5.00, 142.50, 2850.00, 5985.00, 0.00, 5985.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 6-Month billing for 6 month(s) - Due for October 2025 (Includes ৳2,992.50 previous due)', 1, '2025-11-16 03:52:24', '2025-11-16 03:52:24'),
(186, 'INV-2025-0185', 3, '2025-09-30', 35532.50, 50.00, 5.00, 77.50, 1550.00, 37160.00, 0.00, 37160.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for September 2025 (Includes ৳35,532.50 previous due)', 1, '2025-11-16 03:52:46', '2025-11-16 03:52:46'),
(187, 'INV-2025-0186', 57, '2025-09-30', 131197.50, 50.00, 5.00, 1502.50, 30050.00, 162750.00, 0.00, 162750.00, 'unpaid', 'Auto-generated: 6-Month billing for 6 month(s) - Due for September 2025 (Includes ৳131,197.50 previous due)', 1, '2025-11-16 03:52:46', '2025-11-16 03:52:46'),
(188, 'INV-2025-0187', 76, '2025-09-30', 26197.50, 50.00, 5.00, 32.50, 650.00, 26880.00, 0.00, 26880.00, 'unpaid', 'Auto-generated: 3-Month billing for 3 month(s) - Due for September 2025 (Includes ৳26,197.50 previous due)', 1, '2025-11-16 03:52:46', '2025-11-16 03:52:46'),
(189, 'INV-2025-0188', 9, '2025-09-30', 5512.50, 50.00, 5.00, 37.50, 750.00, 6300.00, 0.00, 6300.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for September 2025 (Includes ৳5,512.50 previous due)', 1, '2025-11-16 03:52:46', '2025-11-16 03:52:46'),
(190, 'INV-2025-0189', 15, '2025-09-30', 3307.50, 50.00, 5.00, 52.50, 1050.00, 4410.00, 0.00, 4410.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for September 2025 (Includes ৳3,307.50 previous due)', 1, '2025-11-16 03:52:46', '2025-11-16 03:52:46'),
(191, 'INV-2025-0190', 6, '2025-09-30', 66937.50, 50.00, 5.00, 212.50, 4250.00, 71400.00, 0.00, 71400.00, 'unpaid', 'Auto-generated: 6-Month billing for 6 month(s) - Due for September 2025 (Includes ৳66,937.50 previous due)', 1, '2025-11-16 03:52:46', '2025-11-16 03:52:46'),
(192, 'INV-2025-0191', 13, '2025-09-30', 6772.50, 50.00, 5.00, 107.50, 2150.00, 9030.00, 0.00, 9030.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s) - Due for September 2025 (Includes ৳6,772.50 previous due)', 1, '2025-11-16 03:52:46', '2025-11-16 03:52:46'),
(193, 'INV-2025-0192', 20, '2025-09-30', 29767.50, 50.00, 5.00, 262.50, 5250.00, 35280.00, 0.00, 35280.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 6-Month billing for 6 month(s), 12-Month billing for 12 month(s) - Due for September 2025 (Includes ৳29,767.50 previous due)', 1, '2025-11-16 03:52:46', '2025-11-16 03:52:46'),
(194, 'INV-2025-0193', 75, '2025-09-30', 11077.50, 50.00, 5.00, 32.50, 650.00, 11760.00, 0.00, 11760.00, 'unpaid', 'Auto-generated: 3-Month billing for 3 month(s) - Due for September 2025 (Includes ৳11,077.50 previous due)', 1, '2025-11-16 03:52:46', '2025-11-16 03:52:46');
INSERT INTO `invoices` (`invoice_id`, `invoice_number`, `c_id`, `issue_date`, `previous_due`, `service_charge`, `vat_percentage`, `vat_amount`, `subtotal`, `total_amount`, `received_amount`, `next_due`, `status`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(195, 'INV-2025-0194', 7, '2025-09-30', 52762.50, 50.00, 5.00, 167.50, 3350.00, 56280.00, 0.00, 56280.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 12-Month billing for 12 month(s) - Due for September 2025 (Includes ৳52,762.50 previous due)', 1, '2025-11-16 03:52:46', '2025-11-16 03:52:46'),
(196, 'INV-2025-0195', 14, '2025-09-30', 11927.50, 50.00, 5.00, 262.50, 5250.00, 17440.00, 0.00, 17440.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 6-Month billing for 6 month(s), 12-Month billing for 12 month(s) - Due for September 2025 (Includes ৳11,927.50 previous due)', 1, '2025-11-16 03:52:46', '2025-11-16 03:52:46'),
(197, 'INV-2025-0196', 4, '2025-09-30', 33075.00, 50.00, 5.00, 45.00, 900.00, 34020.00, 0.00, 34020.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 1-Month billing for 1 month(s) - Due for September 2025 (Includes ৳33,075.00 previous due)', 1, '2025-11-16 03:52:46', '2025-11-16 03:52:46'),
(198, 'INV-2025-0197', 12, '2025-09-30', 12537.50, 50.00, 5.00, 52.50, 1050.00, 13640.00, 0.00, 13640.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for September 2025 (Includes ৳12,537.50 previous due)', 1, '2025-11-16 03:52:46', '2025-11-16 03:52:46'),
(199, 'INV-2025-0198', 5, '2025-09-30', 40162.50, 50.00, 5.00, 127.50, 2550.00, 42840.00, 0.00, 42840.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s), 6-Month billing for 6 month(s) - Due for September 2025 (Includes ৳40,162.50 previous due)', 1, '2025-11-16 03:52:46', '2025-11-16 03:52:46'),
(200, 'INV-2025-0199', 16, '2025-09-30', 23782.50, 50.00, 5.00, 167.50, 3350.00, 27300.00, 0.00, 27300.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 12-Month billing for 12 month(s) - Due for September 2025 (Includes ৳23,782.50 previous due)', 1, '2025-11-16 03:52:46', '2025-11-16 03:52:46'),
(201, 'INV-2025-0200', 8, '2025-09-30', 71977.50, 50.00, 5.00, 172.50, 3450.00, 75600.00, 0.00, 75600.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s), 6-Month billing for 6 month(s) - Due for September 2025 (Includes ৳71,977.50 previous due)', 1, '2025-11-16 03:52:46', '2025-11-16 03:52:46'),
(202, 'INV-2025-0201', 19, '2025-09-30', 2430.36, 50.00, 5.00, 107.50, 2150.00, 4687.86, 0.00, 4687.86, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s) - Due for September 2025 (Includes ৳2,430.36 previous due)', 1, '2025-11-16 03:52:46', '2025-11-16 03:52:46'),
(203, 'INV-2025-0202', 11, '2025-09-30', 16537.50, 50.00, 5.00, 112.50, 2250.00, 18900.00, 0.00, 18900.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s), 6-Month billing for 6 month(s) - Due for September 2025 (Includes ৳16,537.50 previous due)', 1, '2025-11-16 03:52:46', '2025-11-16 03:52:46'),
(204, 'INV-2025-0203', 1, '2025-09-30', 5197.50, 50.00, 5.00, 82.50, 1650.00, 6930.00, 0.00, 6930.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 6-Month billing for 6 month(s) - Due for September 2025 (Includes ৳5,197.50 previous due)', 1, '2025-11-16 03:52:46', '2025-11-16 03:52:46'),
(205, 'INV-2025-0204', 3, '2025-08-31', 72692.50, 50.00, 5.00, 77.50, 1550.00, 74320.00, 0.00, 74320.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for August 2025 (Includes ৳72,692.50 previous due)', 1, '2025-11-16 03:53:21', '2025-11-16 03:53:21'),
(206, 'INV-2025-0205', 18, '2025-08-31', 3307.50, 50.00, 5.00, 52.50, 1050.00, 4410.00, 0.00, 4410.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for August 2025 (Includes ৳3,307.50 previous due)', 1, '2025-11-16 03:53:21', '2025-11-16 03:53:21'),
(207, 'INV-2025-0206', 76, '2025-08-31', 53077.50, 50.00, 5.00, 32.50, 650.00, 53760.00, 0.00, 53760.00, 'unpaid', 'Auto-generated: 3-Month billing for 3 month(s) - Due for August 2025 (Includes ৳53,077.50 previous due)', 1, '2025-11-16 03:53:21', '2025-11-16 03:53:21'),
(208, 'INV-2025-0207', 9, '2025-08-31', 11812.50, 50.00, 5.00, 37.50, 750.00, 12600.00, 0.00, 12600.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for August 2025 (Includes ৳11,812.50 previous due)', 1, '2025-11-16 03:53:21', '2025-11-16 03:53:21'),
(209, 'INV-2025-0208', 15, '2025-08-31', 7717.50, 50.00, 5.00, 52.50, 1050.00, 8820.00, 0.00, 8820.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for August 2025 (Includes ৳7,717.50 previous due)', 1, '2025-11-16 03:53:21', '2025-11-16 03:53:21'),
(210, 'INV-2025-0209', 6, '2025-08-31', 138337.50, 50.00, 5.00, 212.50, 4250.00, 142800.00, 0.00, 142800.00, 'unpaid', 'Auto-generated: 6-Month billing for 6 month(s) - Due for August 2025 (Includes ৳138,337.50 previous due)', 1, '2025-11-16 03:53:21', '2025-11-16 03:53:21'),
(211, 'INV-2025-0210', 13, '2025-08-31', 15802.50, 50.00, 5.00, 107.50, 2150.00, 18060.00, 0.00, 18060.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s) - Due for August 2025 (Includes ৳15,802.50 previous due)', 1, '2025-11-16 03:53:21', '2025-11-16 03:53:21'),
(212, 'INV-2025-0211', 75, '2025-08-31', 22837.50, 50.00, 5.00, 32.50, 650.00, 23520.00, 0.00, 23520.00, 'unpaid', 'Auto-generated: 3-Month billing for 3 month(s) - Due for August 2025 (Includes ৳22,837.50 previous due)', 1, '2025-11-16 03:53:21', '2025-11-16 03:53:21'),
(213, 'INV-2025-0212', 7, '2025-08-31', 109042.50, 50.00, 5.00, 167.50, 3350.00, 112560.00, 0.00, 112560.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 12-Month billing for 12 month(s) - Due for August 2025 (Includes ৳109,042.50 previous due)', 1, '2025-11-16 03:53:21', '2025-11-16 03:53:21'),
(214, 'INV-2025-0213', 14, '2025-08-31', 29367.50, 50.00, 5.00, 262.50, 5250.00, 34880.00, 0.00, 34880.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 6-Month billing for 6 month(s), 12-Month billing for 12 month(s) - Due for August 2025 (Includes ৳29,367.50 previous due)', 1, '2025-11-16 03:53:21', '2025-11-16 03:53:21'),
(215, 'INV-2025-0214', 4, '2025-08-31', 67095.00, 50.00, 5.00, 45.00, 900.00, 68040.00, 0.00, 68040.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 1-Month billing for 1 month(s) - Due for August 2025 (Includes ৳67,095.00 previous due)', 1, '2025-11-16 03:53:21', '2025-11-16 03:53:21'),
(216, 'INV-2025-0215', 2, '2025-08-31', 24097.50, 50.00, 5.00, 172.50, 3450.00, 27720.00, 0.00, 27720.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s), 6-Month billing for 6 month(s) - Due for August 2025 (Includes ৳24,097.50 previous due)', 1, '2025-11-16 03:53:21', '2025-11-16 03:53:21'),
(217, 'INV-2025-0216', 12, '2025-08-31', 26177.50, 50.00, 5.00, 52.50, 1050.00, 27280.00, 0.00, 27280.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for August 2025 (Includes ৳26,177.50 previous due)', 1, '2025-11-16 03:53:21', '2025-11-16 03:53:21'),
(218, 'INV-2025-0217', 5, '2025-08-31', 83002.50, 50.00, 5.00, 127.50, 2550.00, 85680.00, 0.00, 85680.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s), 6-Month billing for 6 month(s) - Due for August 2025 (Includes ৳83,002.50 previous due)', 1, '2025-11-16 03:53:21', '2025-11-16 03:53:21'),
(219, 'INV-2025-0218', 17, '2025-08-31', 9922.50, 50.00, 5.00, 157.50, 3150.00, 13230.00, 0.00, 13230.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s), 6-Month billing for 6 month(s) - Due for August 2025 (Includes ৳9,922.50 previous due)', 1, '2025-11-16 03:53:21', '2025-11-16 03:53:21'),
(220, 'INV-2025-0219', 16, '2025-08-31', 51082.50, 50.00, 5.00, 167.50, 3350.00, 54600.00, 0.00, 54600.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 12-Month billing for 12 month(s) - Due for August 2025 (Includes ৳51,082.50 previous due)', 1, '2025-11-16 03:53:21', '2025-11-16 03:53:21'),
(221, 'INV-2025-0220', 11, '2025-08-31', 35437.50, 50.00, 5.00, 112.50, 2250.00, 37800.00, 0.00, 37800.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s), 6-Month billing for 6 month(s) - Due for August 2025 (Includes ৳35,437.50 previous due)', 1, '2025-11-16 03:53:21', '2025-11-16 03:53:21'),
(222, 'INV-2025-0221', 1, '2025-08-31', 12127.50, 50.00, 5.00, 82.50, 1650.00, 13860.00, 0.00, 13860.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 6-Month billing for 6 month(s) - Due for August 2025 (Includes ৳12,127.50 previous due)', 1, '2025-11-16 03:53:21', '2025-11-16 03:53:21'),
(223, 'INV-2025-0222', 10, '2025-08-31', 223807.50, 50.00, 5.00, 542.50, 10850.00, 235200.00, 0.00, 235200.00, 'unpaid', 'Auto-generated: 6-Month billing for 6 month(s), 6-Month billing for 6 month(s) - Due for August 2025 (Includes ৳223,807.50 previous due)', 1, '2025-11-16 03:53:21', '2025-11-16 03:53:21'),
(224, 'INV-2025-0223', 3, '2025-07-31', 147012.50, 50.00, 5.00, 77.50, 1550.00, 148640.00, 0.00, 148640.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for July 2025 (Includes ৳147,012.50 previous due)', 1, '2025-11-16 03:53:39', '2025-11-16 03:53:39'),
(225, 'INV-2025-0224', 76, '2025-07-31', 106837.50, 50.00, 5.00, 32.50, 650.00, 107520.00, 0.00, 107520.00, 'unpaid', 'Auto-generated: 3-Month billing for 3 month(s) - Due for July 2025 (Includes ৳106,837.50 previous due)', 1, '2025-11-16 03:53:39', '2025-11-16 03:53:39'),
(226, 'INV-2025-0225', 9, '2025-07-31', 24412.50, 50.00, 5.00, 37.50, 750.00, 25200.00, 0.00, 25200.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for July 2025 (Includes ৳24,412.50 previous due)', 1, '2025-11-16 03:53:39', '2025-11-16 03:53:39'),
(227, 'INV-2025-0226', 6, '2025-07-31', 281137.50, 50.00, 5.00, 212.50, 4250.00, 285600.00, 0.00, 285600.00, 'unpaid', 'Auto-generated: 6-Month billing for 6 month(s) - Due for July 2025 (Includes ৳281,137.50 previous due)', 1, '2025-11-16 03:53:39', '2025-11-16 03:53:39'),
(228, 'INV-2025-0227', 13, '2025-07-31', 33862.50, 50.00, 5.00, 107.50, 2150.00, 36120.00, 0.00, 36120.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s) - Due for July 2025 (Includes ৳33,862.50 previous due)', 1, '2025-11-16 03:53:39', '2025-11-16 03:53:39'),
(229, 'INV-2025-0228', 75, '2025-07-31', 46357.50, 50.00, 5.00, 32.50, 650.00, 47040.00, 0.00, 47040.00, 'unpaid', 'Auto-generated: 3-Month billing for 3 month(s) - Due for July 2025 (Includes ৳46,357.50 previous due)', 1, '2025-11-16 03:53:39', '2025-11-16 03:53:39'),
(230, 'INV-2025-0229', 7, '2025-07-31', 221602.50, 50.00, 5.00, 167.50, 3350.00, 225120.00, 0.00, 225120.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 12-Month billing for 12 month(s) - Due for July 2025 (Includes ৳221,602.50 previous due)', 1, '2025-11-16 03:53:39', '2025-11-16 03:53:39'),
(231, 'INV-2025-0230', 14, '2025-07-31', 64247.50, 50.00, 5.00, 262.50, 5250.00, 69760.00, 0.00, 69760.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 6-Month billing for 6 month(s), 12-Month billing for 12 month(s) - Due for July 2025 (Includes ৳64,247.50 previous due)', 1, '2025-11-16 03:53:39', '2025-11-16 03:53:39'),
(232, 'INV-2025-0231', 12, '2025-07-31', 53457.50, 50.00, 5.00, 52.50, 1050.00, 54560.00, 0.00, 54560.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for July 2025 (Includes ৳53,457.50 previous due)', 1, '2025-11-16 03:53:39', '2025-11-16 03:53:39'),
(233, 'INV-2025-0232', 5, '2025-07-31', 168682.50, 50.00, 5.00, 127.50, 2550.00, 171360.00, 0.00, 171360.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s), 6-Month billing for 6 month(s) - Due for July 2025 (Includes ৳168,682.50 previous due)', 1, '2025-11-16 03:53:39', '2025-11-16 03:53:39'),
(234, 'INV-2025-0233', 16, '2025-07-31', 105682.50, 50.00, 5.00, 167.50, 3350.00, 109200.00, 0.00, 109200.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 12-Month billing for 12 month(s) - Due for July 2025 (Includes ৳105,682.50 previous due)', 1, '2025-11-16 03:53:39', '2025-11-16 03:53:39'),
(235, 'INV-2025-0234', 8, '2025-07-31', 147577.50, 50.00, 5.00, 172.50, 3450.00, 151200.00, 0.00, 151200.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s), 6-Month billing for 6 month(s) - Due for July 2025 (Includes ৳147,577.50 previous due)', 1, '2025-11-16 03:53:39', '2025-11-16 03:53:39'),
(236, 'INV-2025-0235', 11, '2025-07-31', 73237.50, 50.00, 5.00, 112.50, 2250.00, 75600.00, 0.00, 75600.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s), 6-Month billing for 6 month(s) - Due for July 2025 (Includes ৳73,237.50 previous due)', 1, '2025-11-16 03:53:39', '2025-11-16 03:53:39'),
(237, 'INV-2025-0236', 10, '2025-07-31', 459007.50, 50.00, 5.00, 542.50, 10850.00, 470400.00, 0.00, 470400.00, 'unpaid', 'Auto-generated: 6-Month billing for 6 month(s), 6-Month billing for 6 month(s) - Due for July 2025 (Includes ৳459,007.50 previous due)', 1, '2025-11-16 03:53:39', '2025-11-16 03:53:39'),
(238, 'INV-2025-0237', 3, '2025-06-30', 295652.50, 50.00, 5.00, 77.50, 1550.00, 297280.00, 0.00, 297280.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for June 2025 (Includes ৳295,652.50 previous due)', 1, '2025-11-16 03:54:29', '2025-11-16 03:54:29'),
(239, 'INV-2025-0238', 76, '2025-06-30', 214357.50, 50.00, 5.00, 32.50, 650.00, 215040.00, 0.00, 215040.00, 'unpaid', 'Auto-generated: 3-Month billing for 3 month(s) - Due for June 2025 (Includes ৳214,357.50 previous due)', 1, '2025-11-16 03:54:29', '2025-11-16 03:54:29'),
(240, 'INV-2025-0239', 9, '2025-06-30', 49612.50, 50.00, 5.00, 37.50, 750.00, 50400.00, 0.00, 50400.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for June 2025 (Includes ৳49,612.50 previous due)', 1, '2025-11-16 03:54:29', '2025-11-16 03:54:29'),
(241, 'INV-2025-0240', 15, '2025-06-30', 16537.50, 50.00, 5.00, 52.50, 1050.00, 17640.00, 0.00, 17640.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for June 2025 (Includes ৳16,537.50 previous due)', 1, '2025-11-16 03:54:29', '2025-11-16 03:54:29'),
(242, 'INV-2025-0241', 6, '2025-06-30', 566737.50, 50.00, 5.00, 212.50, 4250.00, 571200.00, 0.00, 571200.00, 'unpaid', 'Auto-generated: 6-Month billing for 6 month(s) - Due for June 2025 (Includes ৳566,737.50 previous due)', 1, '2025-11-16 03:54:29', '2025-11-16 03:54:29'),
(243, 'INV-2025-0242', 75, '2025-06-30', 93397.50, 50.00, 5.00, 32.50, 650.00, 94080.00, 0.00, 94080.00, 'unpaid', 'Auto-generated: 3-Month billing for 3 month(s) - Due for June 2025 (Includes ৳93,397.50 previous due)', 1, '2025-11-16 03:54:29', '2025-11-16 03:54:29'),
(244, 'INV-2025-0243', 7, '2025-06-30', 446722.50, 50.00, 5.00, 167.50, 3350.00, 450240.00, 0.00, 450240.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 12-Month billing for 12 month(s) - Due for June 2025 (Includes ৳446,722.50 previous due)', 1, '2025-11-16 03:54:29', '2025-11-16 03:54:29'),
(245, 'INV-2025-0244', 14, '2025-06-30', 134007.50, 50.00, 5.00, 262.50, 5250.00, 139520.00, 0.00, 139520.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 6-Month billing for 6 month(s), 12-Month billing for 12 month(s) - Due for June 2025 (Includes ৳134,007.50 previous due)', 1, '2025-11-16 03:54:29', '2025-11-16 03:54:29'),
(246, 'INV-2025-0245', 4, '2025-06-30', 135135.00, 50.00, 5.00, 45.00, 900.00, 136080.00, 0.00, 136080.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 1-Month billing for 1 month(s) - Due for June 2025 (Includes ৳135,135.00 previous due)', 1, '2025-11-16 03:54:29', '2025-11-16 03:54:29'),
(247, 'INV-2025-0246', 2, '2025-06-30', 51817.50, 50.00, 5.00, 172.50, 3450.00, 55440.00, 0.00, 55440.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s), 6-Month billing for 6 month(s) - Due for June 2025 (Includes ৳51,817.50 previous due)', 1, '2025-11-16 03:54:29', '2025-11-16 03:54:29'),
(248, 'INV-2025-0247', 12, '2025-06-30', 108017.50, 50.00, 5.00, 52.50, 1050.00, 109120.00, 0.00, 109120.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for June 2025 (Includes ৳108,017.50 previous due)', 1, '2025-11-16 03:54:29', '2025-11-16 03:54:29'),
(249, 'INV-2025-0248', 5, '2025-06-30', 340042.50, 50.00, 5.00, 127.50, 2550.00, 342720.00, 0.00, 342720.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s), 6-Month billing for 6 month(s) - Due for June 2025 (Includes ৳340,042.50 previous due)', 1, '2025-11-16 03:54:29', '2025-11-16 03:54:29'),
(250, 'INV-2025-0249', 8, '2025-06-30', 298777.50, 50.00, 5.00, 172.50, 3450.00, 302400.00, 0.00, 302400.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s), 6-Month billing for 6 month(s) - Due for June 2025 (Includes ৳298,777.50 previous due)', 1, '2025-11-16 03:54:29', '2025-11-16 03:54:29'),
(251, 'INV-2025-0250', 11, '2025-06-30', 148837.50, 50.00, 5.00, 112.50, 2250.00, 151200.00, 0.00, 151200.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s), 6-Month billing for 6 month(s) - Due for June 2025 (Includes ৳148,837.50 previous due)', 1, '2025-11-16 03:54:29', '2025-11-16 03:54:29'),
(252, 'INV-2025-0251', 1, '2025-06-30', 25987.50, 50.00, 5.00, 82.50, 1650.00, 27720.00, 0.00, 27720.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 6-Month billing for 6 month(s) - Due for June 2025 (Includes ৳25,987.50 previous due)', 1, '2025-11-16 03:54:29', '2025-11-16 03:54:29'),
(253, 'INV-2025-0252', 10, '2025-06-30', 929407.50, 50.00, 5.00, 542.50, 10850.00, 940800.00, 0.00, 940800.00, 'unpaid', 'Auto-generated: 6-Month billing for 6 month(s), 6-Month billing for 6 month(s) - Due for June 2025 (Includes ৳929,407.50 previous due)', 1, '2025-11-16 03:54:29', '2025-11-16 03:54:29'),
(254, 'INV-2025-0253', 3, '2025-05-31', 592932.50, 50.00, 5.00, 77.50, 1550.00, 594560.00, 0.00, 594560.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for May 2025 (Includes ৳592,932.50 previous due)', 1, '2025-11-16 03:54:55', '2025-11-16 03:54:55'),
(255, 'INV-2025-0254', 76, '2025-05-31', 429397.50, 50.00, 5.00, 32.50, 650.00, 430080.00, 0.00, 430080.00, 'unpaid', 'Auto-generated: 3-Month billing for 3 month(s) - Due for May 2025 (Includes ৳429,397.50 previous due)', 1, '2025-11-16 03:54:55', '2025-11-16 03:54:55'),
(256, 'INV-2025-0255', 9, '2025-05-31', 100012.50, 50.00, 5.00, 37.50, 750.00, 100800.00, 0.00, 100800.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s) - Due for May 2025 (Includes ৳100,012.50 previous due)', 1, '2025-11-16 03:54:56', '2025-11-16 03:54:56'),
(257, 'INV-2025-0256', 6, '2025-05-31', 1137937.50, 50.00, 5.00, 212.50, 4250.00, 1142400.00, 0.00, 1142400.00, 'unpaid', 'Auto-generated: 6-Month billing for 6 month(s) - Due for May 2025 (Includes ৳1,137,937.50 previous due)', 1, '2025-11-16 03:54:56', '2025-11-16 03:54:56'),
(258, 'INV-2025-0257', 13, '2025-05-31', 69982.50, 50.00, 5.00, 107.50, 2150.00, 72240.00, 0.00, 72240.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s) - Due for May 2025 (Includes ৳69,982.50 previous due)', 1, '2025-11-16 03:54:56', '2025-11-16 03:54:56'),
(259, 'INV-2025-0258', 75, '2025-05-31', 187477.50, 50.00, 5.00, 32.50, 650.00, 188160.00, 0.00, 188160.00, 'unpaid', 'Auto-generated: 3-Month billing for 3 month(s) - Due for May 2025 (Includes ৳187,477.50 previous due)', 1, '2025-11-16 03:54:56', '2025-11-16 03:54:56'),
(260, 'INV-2025-0259', 7, '2025-05-31', 896962.50, 50.00, 5.00, 167.50, 3350.00, 900480.00, 0.00, 900480.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 12-Month billing for 12 month(s) - Due for May 2025 (Includes ৳896,962.50 previous due)', 1, '2025-11-16 03:54:56', '2025-11-16 03:54:56'),
(261, 'INV-2025-0260', 14, '2025-05-31', 273527.50, 50.00, 5.00, 262.50, 5250.00, 279040.00, 0.00, 279040.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 6-Month billing for 6 month(s), 12-Month billing for 12 month(s) - Due for May 2025 (Includes ৳273,527.50 previous due)', 1, '2025-11-16 03:54:56', '2025-11-16 03:54:56'),
(262, 'INV-2025-0261', 4, '2025-05-31', 271215.00, 50.00, 5.00, 45.00, 900.00, 272160.00, 0.00, 272160.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 1-Month billing for 1 month(s) - Due for May 2025 (Includes ৳271,215.00 previous due)', 1, '2025-11-16 03:54:56', '2025-11-16 03:54:56'),
(263, 'INV-2025-0262', 2, '2025-05-31', 107257.50, 50.00, 5.00, 172.50, 3450.00, 110880.00, 0.00, 110880.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s), 6-Month billing for 6 month(s) - Due for May 2025 (Includes ৳107,257.50 previous due)', 1, '2025-11-16 03:54:56', '2025-11-16 03:54:56'),
(264, 'INV-2025-0263', 5, '2025-05-31', 682762.50, 50.00, 5.00, 127.50, 2550.00, 685440.00, 0.00, 685440.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s), 6-Month billing for 6 month(s) - Due for May 2025 (Includes ৳682,762.50 previous due)', 1, '2025-11-16 03:54:56', '2025-11-16 03:54:56'),
(265, 'INV-2025-0264', 8, '2025-05-31', 601177.50, 50.00, 5.00, 172.50, 3450.00, 604800.00, 0.00, 604800.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 3-Month billing for 3 month(s), 6-Month billing for 6 month(s) - Due for May 2025 (Includes ৳601,177.50 previous due)', 1, '2025-11-16 03:54:56', '2025-11-16 03:54:56'),
(266, 'INV-2025-0265', 1, '2025-05-31', 53707.50, 50.00, 5.00, 82.50, 1650.00, 55440.00, 0.00, 55440.00, 'unpaid', 'Auto-generated: 1-Month billing for 1 month(s), 6-Month billing for 6 month(s) - Due for May 2025 (Includes ৳53,707.50 previous due)', 1, '2025-11-16 03:54:56', '2025-11-16 03:54:56'),
(267, 'INV-2025-0266', 10, '2025-05-31', 1870207.50, 50.00, 5.00, 542.50, 10850.00, 1881600.00, 0.00, 1881600.00, 'unpaid', 'Auto-generated: 6-Month billing for 6 month(s), 6-Month billing for 6 month(s) - Due for May 2025 (Includes ৳1,870,207.50 previous due)', 1, '2025-11-16 03:54:56', '2025-11-16 03:54:56'),
(268, 'INV-2025-0267', 77, '2025-11-30', 0.00, 50.00, 5.00, 602.50, 12050.00, 12652.50, 12000.00, 652.50, 'partial', 'Auto-generated: 6-Month billing for 6 month(s) - Due for November 2025', 1, '2025-11-16 04:27:52', '2025-11-16 04:33:08');

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
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_11_06_100942_sync_with_current_database', 1),
(2, '2025_11_06_101329_create_audit_logs_table', 1),
(3, '2025_11_06_101329_create_customer_to_packages_table', 1),
(4, '2025_11_06_101329_create_customers_table', 1),
(5, '2025_11_06_101329_create_failed_jobs_table', 1),
(6, '2025_11_06_101329_create_invoices_table', 1),
(7, '2025_11_06_101329_create_notifications_table', 1),
(8, '2025_11_06_101329_create_packages_table', 1),
(9, '2025_11_06_101329_create_password_resets_table', 1),
(10, '2025_11_06_101329_create_payments_table', 1),
(11, '2025_11_06_101329_create_personal_access_tokens_table', 1),
(12, '2025_11_06_101329_create_settings_table', 1),
(13, '2025_11_06_101329_create_subscriptions_table', 1),
(14, '2025_11_06_101329_create_system_settings_table', 1),
(15, '2025_11_06_101329_create_users_table', 1),
(16, '2025_11_06_101330_create_monthly_revenue_summary_view', 1),
(17, '2025_11_06_101332_add_foreign_keys_to_customer_to_packages_table', 1),
(18, '2025_11_06_101332_add_foreign_keys_to_customers_table', 1),
(19, '2025_11_10_060151_create_sessions_table', 1),
(20, '2025_11_10_082250_add_missing_columns_to_payments_table', 1),
(21, '2025_11_10_082359_add_transaction_id_to_payments_table', 1),
(22, '2025_11_10_082423_add_transaction_id_to_payments_table', 1),
(23, '2025_11_11_080509_rename_packages_to_products', 1),
(24, '2025_11_11_080538_rename_customer_to_packages_to_customer_to_products', 1),
(25, '2025_11_11_080621_update_foreign_keys_in_customer_to_products', 1),
(26, '2025_11_11_090000_create_product_types_table', 2),
(27, '2025_11_11_090001_fix_product_type_id_column_in_products_table', 3),
(28, '2025_11_11_090002_change_product_type_id_to_varchar_in_products_table', 4),
(29, '2025_11_11_090003_rename_product_types_to_product_type_and_add_descriptions', 4),
(30, '2025_11_11_090004_change_product_type_to_product_type_id_and_add_foreign_key', 5),
(31, '2025_11_11_115949_fix_payments_foreign_keys', 6),
(32, '2025_11_13_110116_add_due_day_to_customer_to_products_table', 7),
(33, '2025_11_13_110413_set_default_due_day_for_existing_records', 8),
(34, '2025_11_15_060245_add_invoice_id_to_customer_to_products_table', 9),
(35, '2025_11_16_150000_update_customer_to_products_due_date', 10);

-- --------------------------------------------------------

--
-- Stand-in structure for view `monthly_revenue_summary`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `monthly_revenue_summary`;
CREATE TABLE IF NOT EXISTS `monthly_revenue_summary` (
`collected_revenue` decimal(34,2)
,`invoice_count` bigint
,`month_year` varchar(7)
,`pending_revenue` decimal(35,2)
,`total_revenue` decimal(34,2)
);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `payment_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_id` int UNSIGNED NOT NULL,
  `c_id` int UNSIGNED NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_method` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_date` datetime NOT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `collected_by` int UNSIGNED DEFAULT NULL,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`payment_id`),
  KEY `idx_invoice_id` (`invoice_id`),
  KEY `idx_c_id` (`c_id`),
  KEY `payments_collected_by_foreign` (`collected_by`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `invoice_id`, `c_id`, `amount`, `payment_method`, `payment_date`, `note`, `created_at`, `updated_at`, `collected_by`, `status`, `notes`) VALUES
(1, 28, 19, 1002.50, 'cash', '2025-11-10 00:00:00', NULL, '2025-11-09 21:14:20', '2025-11-09 21:14:20', NULL, 'completed', NULL),
(2, 33, 23, 1002.50, 'card', '2025-11-13 00:00:00', NULL, '2025-11-13 00:50:17', '2025-11-13 00:50:17', 1, 'completed', 'qqq'),
(3, 33, 23, 1002.50, 'card', '2025-11-13 00:00:00', NULL, '2025-11-13 00:50:17', '2025-11-13 00:50:17', 1, 'completed', 'qqq'),
(4, 33, 23, 100.00, 'cash', '2025-11-13 00:00:00', NULL, '2025-11-13 03:49:27', '2025-11-13 03:49:27', 1, 'completed', 'okok'),
(5, 33, 23, 100.00, 'cash', '2025-11-13 00:00:00', NULL, '2025-11-13 03:49:27', '2025-11-13 03:49:27', 1, 'completed', 'okok'),
(6, 28, 19, 13.57, 'cash', '2025-11-15 00:00:00', NULL, '2025-11-14 23:47:47', '2025-11-14 23:47:47', 1, 'completed', 'pore'),
(7, 28, 19, 13.57, 'cash', '2025-11-15 00:00:00', NULL, '2025-11-14 23:47:47', '2025-11-14 23:47:47', 1, 'completed', 'pore'),
(8, 31, 21, 500.00, 'mobile_banking', '2025-11-15 00:00:00', NULL, '2025-11-14 23:56:53', '2025-11-14 23:56:53', 1, 'completed', 'next week'),
(9, 31, 21, 500.00, 'mobile_banking', '2025-11-15 00:00:00', NULL, '2025-11-14 23:56:53', '2025-11-14 23:56:53', 1, 'completed', 'next week'),
(10, 32, 22, 200.00, 'cash', '2025-11-15 00:00:00', NULL, '2025-11-14 23:57:29', '2025-11-14 23:57:29', 1, 'completed', 'next week'),
(11, 32, 22, 200.00, 'cash', '2025-11-15 00:00:00', NULL, '2025-11-14 23:57:29', '2025-11-14 23:57:29', 1, 'completed', 'next week'),
(12, 32, 22, 107.69, 'mobile_banking', '2025-11-15 00:00:00', NULL, '2025-11-15 00:09:17', '2025-11-15 00:09:17', 1, 'completed', 'ouuouo'),
(13, 32, 22, 107.69, 'mobile_banking', '2025-11-15 00:00:00', NULL, '2025-11-15 00:09:17', '2025-11-15 00:09:17', 1, 'completed', 'ouuouo'),
(14, 32, 22, 34.32, 'online', '2025-11-15 00:00:00', NULL, '2025-11-15 00:20:09', '2025-11-15 00:20:09', 1, 'completed', 'sdsdsds'),
(15, 32, 22, 34.32, 'online', '2025-11-15 00:00:00', NULL, '2025-11-15 00:20:09', '2025-11-15 00:20:09', 1, 'completed', 'sdsdsds'),
(16, 32, 22, 115.84, 'mobile_banking', '2025-11-16 00:00:00', NULL, '2025-11-15 22:38:33', '2025-11-15 22:38:33', 1, 'completed', 'cvcv'),
(17, 32, 22, 115.84, 'mobile_banking', '2025-11-16 00:00:00', NULL, '2025-11-15 22:38:33', '2025-11-15 22:38:33', 1, 'completed', 'cvcv'),
(18, 22, 14, 100.00, 'online', '2025-11-16 00:00:00', NULL, '2025-11-15 23:38:07', '2025-11-15 23:38:07', 1, 'completed', 'ww'),
(19, 22, 14, 100.00, 'online', '2025-11-16 00:00:00', NULL, '2025-11-15 23:38:07', '2025-11-15 23:38:07', 1, 'completed', 'ww'),
(20, 268, 77, 12000.00, 'cash', '2025-11-16 00:00:00', NULL, '2025-11-16 04:33:08', '2025-11-16 04:33:08', 1, 'completed', 'qqq'),
(21, 268, 77, 12000.00, 'cash', '2025-11-16 00:00:00', NULL, '2025-11-16 04:33:08', '2025-11-16 04:33:08', 1, 'completed', 'qqq');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `tokenable_id` (`tokenable_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `p_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_type_id` bigint UNSIGNED DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `monthly_price` decimal(12,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`p_id`),
  KEY `products_product_type_id_foreign` (`product_type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`p_id`, `name`, `product_type_id`, `description`, `monthly_price`, `created_at`, `updated_at`) VALUES
(1, 'Basic Plan', 1, 'Home broadband 10 Mbps connection', 700.00, '2024-12-31 17:50:20', NULL),
(2, 'Standard Plan', 1, 'Home broadband 25 Mbps connection', 1000.00, '2024-12-31 17:50:20', NULL),
(3, 'Premium Plan', 1, 'Home broadband 50 Mbps connection', 1500.00, '2024-12-31 17:50:20', NULL),
(4, 'Gaming Boost', 2, 'Enhanced gaming experience with optimized connectivity', 200.00, '2024-12-31 19:01:22', '2024-12-31 19:01:22'),
(5, 'Streaming Plus', 2, 'Improved streaming quality and bandwidth for video content', 150.00, '2024-12-31 19:01:22', '2024-12-31 19:01:22'),
(6, 'Family Pack', 2, 'Additional connections and shared benefits for family members', 300.00, '2024-12-31 19:01:22', '2024-12-31 19:01:22'),
(7, 'Test Package', 2, 'Test Description', 100.00, '2025-11-09 23:32:34', '2025-11-09 23:32:34'),
(8, 'Turbo', 2, 'zzzz', 600.00, '2025-11-09 23:44:15', '2025-11-09 23:44:15'),
(9, 'Turbo MAX', 4, 'max', 2000.00, '2025-11-10 00:01:08', '2025-11-11 05:01:53'),
(10, 'star', 7, 'demo', 300.00, '2025-11-11 05:19:20', '2025-11-11 05:19:20'),
(11, 'WS', 10, 'yifyfhv', 5000.00, '2025-11-11 06:06:16', '2025-11-11 06:06:16'),
(12, 'WS', 10, 'yifyfhv', 5000.00, '2025-11-11 06:06:30', '2025-11-11 06:06:30');

-- --------------------------------------------------------

--
-- Table structure for table `product_type`
--

DROP TABLE IF EXISTS `product_type`;
CREATE TABLE IF NOT EXISTS `product_type` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descriptions` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_type`
--

INSERT INTO `product_type` (`id`, `name`, `descriptions`, `created_at`, `updated_at`) VALUES
(1, 'regular', NULL, '2025-11-09 18:35:49', '2025-11-09 18:35:49'),
(2, 'special', NULL, '2025-11-09 18:35:49', '2025-11-09 18:35:49'),
(4, 'Silver', NULL, '2025-11-10 00:06:48', '2025-11-10 00:06:48'),
(5, 'test01', NULL, '2025-11-10 04:13:14', '2025-11-10 04:13:14'),
(6, 'type02', NULL, '2025-11-10 05:44:09', '2025-11-10 05:44:09'),
(7, 'Dimond', 'demo', '2025-11-11 05:02:47', '2025-11-11 05:02:47'),
(8, 'Platinum', 'demo', '2025-11-11 05:07:21', '2025-11-11 05:07:21'),
(9, 'Turbo MAX', 'demo', '2025-11-11 05:18:48', '2025-11-11 05:18:48'),
(10, 'business', 'test', '2025-11-11 06:04:50', '2025-11-11 06:04:50');

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
('JPj2I7i9aS9n5LeRmzTMMDRrWw7Jgy1TddkB7YvI', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiS3FMYldiSkRVSHcycnlvWXhIbGhMUjRSeG9FVWFEWWwyM09lTGwwQSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly9sb2NhbGhvc3QvbmV0YmlsbC1iZC9wdWJsaWMiO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1763289216);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
CREATE TABLE IF NOT EXISTS `subscriptions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` bigint UNSIGNED NOT NULL,
  `package_id` bigint UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subscriptions_customer_id_foreign` (`customer_id`),
  KEY `subscriptions_package_id_foreign` (`package_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

DROP TABLE IF EXISTS `system_settings`;
CREATE TABLE IF NOT EXISTS `system_settings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `system_settings_key_unique` (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'customer',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin@netbill.com', NULL, '$2y$12$QLo9cr29855m64bGJ7aQyeha4XXzLGjUk7UKdArCUiih0k3k/0N8.', 'admin', NULL, '2025-01-01 00:40:02', '2024-12-31 18:49:19'),
(2, 'Sumaiya Akter', 'sumaiya@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-01-05 00:40:02', NULL),
(3, 'Nayeem Hasan', 'nayeem@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-01-10 00:40:02', NULL),
(4, 'Afsana Rahman', 'afsana@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-01-15 00:40:02', NULL),
(5, 'Nadia Zaman', 'nadiyazaman@gmail.com', NULL, '$2y$12$UtWg4WGBYipkf95ev43UFOoOjjfwxFEsJosw6vmveZXKZyCiX7wSa', 'customer', NULL, '2025-01-20 00:10:27', '2025-01-20 00:10:27'),
(6, 'Rahim Khan', 'rahim@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-01-25 00:40:02', NULL),
(7, 'Fatima Begum', 'fatima@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-02-05 00:40:02', NULL),
(8, 'Kamal Hossain', 'kamal@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-02-12 00:40:02', NULL),
(9, 'Sadia Islam', 'sadia@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-02-20 00:40:02', NULL),
(10, 'Arif Mahmud', 'arif@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-03-08 00:40:02', NULL),
(11, 'Tania Ahmed', 'tania@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-03-18 00:40:02', NULL),
(12, 'Sohel Rana', 'sohel@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-04-05 00:40:02', NULL),
(13, 'Nusrat Jahan', 'nusrat@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-04-15 00:40:02', NULL),
(14, 'Imran Hossain', 'imran@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-05-10 00:40:02', NULL),
(15, 'Moumita Rahman', 'moumita@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-05-20 00:40:02', NULL),
(16, 'Faisal Ahmed', 'faisal@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-06-12 00:40:02', NULL),
(17, 'Sabrina Chowdhury', 'sabrina@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-07-08 00:40:02', NULL),
(18, 'Rashidul Islam', 'rashidul@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-08-05 00:40:02', NULL),
(19, 'Anika Tasnim', 'anika@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-08-18 00:40:02', NULL),
(20, 'Shahriar Manzoor', 'shahriar@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-09-10 00:40:02', NULL),
(21, 'Jannatul Ferdous', 'jannatul@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-09-22 00:40:02', NULL),
(22, 'Rafiqul Islam', 'rafiqul@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-01 00:40:02', NULL),
(23, 'Sharmin Akter', 'sharmin@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-02 00:40:02', NULL),
(24, 'Nasir Uddin', 'nasir@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-03 00:40:02', NULL),
(25, 'Mitu Rahman', 'mitu@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-04 00:40:02', NULL),
(26, 'Sajal Hossain', 'sajal@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-05 00:40:02', NULL),
(27, 'Poly Begum', 'poly@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-06 00:40:02', NULL),
(28, 'Bappi Sarker', 'bappi@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-07 00:40:02', NULL),
(29, 'Tumpa Akter', 'tumpa@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-08 00:40:02', NULL),
(30, 'Mamun Or Rashid', 'mamun@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-09 00:40:02', NULL),
(31, 'Shirin Sultana', 'shirin@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-10 00:40:02', NULL),
(32, 'Rokonuzzaman', 'rokon@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-11 00:40:02', NULL),
(33, 'Farhana Yesmin', 'farhana@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-12 00:40:02', NULL),
(34, 'Alamgir Hossain', 'alamgir@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-13 00:40:02', NULL),
(35, 'Nazma Begum', 'nazma@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-14 00:40:02', NULL),
(36, 'Sazzad Hossain', 'sazzad@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-15 00:40:02', NULL),
(37, 'Morshed Alam', 'morshed@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-16 00:40:02', NULL),
(38, 'Shahinur Rahman', 'shahinur@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-17 00:40:02', NULL),
(39, 'Nargis Akter', 'nargis@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-18 00:40:02', NULL),
(40, 'Babul Miah', 'babul@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-19 00:40:02', NULL),
(41, 'Rina Akter', 'rina@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-20 00:40:02', NULL),
(42, 'Shafiqul Islam', 'shafiqul@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-21 00:40:02', NULL),
(43, 'Mousumi Rahman', 'mousumi@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-22 00:40:02', NULL),
(44, 'Jamal Uddin', 'jamal@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-23 00:40:02', NULL),
(45, 'Ruma Akter', 'ruma@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-24 00:40:02', NULL),
(46, 'Salam Miah', 'salam@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-25 00:40:02', NULL),
(47, 'Nipa Moni', 'nipa@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-26 00:40:02', NULL),
(48, 'Khalid Hasan', 'khalid@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-27 00:40:02', NULL),
(49, 'Mitu Akter', 'mitu2@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-28 00:40:02', NULL),
(50, 'Ratan Sarker', 'ratan@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-29 00:40:02', NULL),
(51, 'Shamima Nasrin', 'shamima@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-30 00:40:02', NULL),
(52, 'Bappi Das', 'bappi2@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-31 00:40:02', NULL),
(53, 'Moriom Begum', 'moriom@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-31 00:40:02', NULL),
(54, 'Raju Ahmed', 'raju@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-31 00:40:02', NULL),
(55, 'Shila Akter', 'shila@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-31 00:40:02', NULL),
(56, 'Mizanur Rahman', 'mizan@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-31 00:40:02', NULL),
(57, 'Naznin Sultanaaaa', 'naznin@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-31 00:40:02', '2025-11-11 06:03:54'),
(58, 'Alauddin Hasan', 'alauddin@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-31 00:40:02', '2025-11-11 04:30:56'),
(59, 'Rokeya Begum', 'rokeya@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-31 00:40:02', NULL),
(60, 'Sohag Miah', 'sohag@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-31 00:40:02', NULL),
(61, 'Laboni Akter', 'laboni@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-31 00:40:02', NULL),
(62, 'Shahidul Islam', 'shahidul@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-31 00:40:02', NULL),
(63, 'Mita Rahman', 'mita@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-31 00:40:02', NULL),
(64, 'Rony Ahmed', 'rony@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-31 00:40:02', NULL),
(65, 'Tania Islam', 'tania2@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-31 00:40:02', NULL),
(66, 'Sajib Hasan', 'sajib@example.com', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NULL, '2025-10-31 00:40:02', NULL),
(67, 'Imteaz', 'imteaz@gmail.com', NULL, '$2y$12$MkFG9f3l2UmDvnGDleelweiSYEKHjiOk/REV53NSrjZ13pHynWClC', 'customer', NULL, '2025-11-13 00:53:26', '2025-11-13 00:53:26'),
(68, 'Mim', 'mim@gmail.com', NULL, '$2y$12$yzCfhLTgLcmQuWO0EXTXk.eFBieO0KGbJXeHv5tm4Rz6bWygQZF6.', 'customer', NULL, '2025-11-14 08:49:21', '2025-11-14 08:49:21'),
(69, 'Emon', 'emon@gmail.com', NULL, '$2y$12$OB4fHw7vfVBCBirQDQcuOejK0krTvZpMKPEE1xtqQDM13r/fZI6hy', 'customer', NULL, '2025-11-14 09:20:46', '2025-11-14 09:20:46'),
(70, 'Emon', 'emon1@gmail.com', NULL, '$2y$12$QUjexNJXJvR1fCirNYu6z.8cqYdYasDUHmMVkPvvwvjjXbsVut11m', 'customer', NULL, '2025-11-14 09:21:34', '2025-11-14 09:21:34'),
(71, 'ishaq', 'ishaq@gmail.com', NULL, '$2y$12$/DTS4hCOhE/val.J7.u/Pe/BbnUvU1gKLSwN9cNgVJxPF31UzNRbu', 'customer', NULL, '2025-11-14 09:34:04', '2025-11-14 09:34:04'),
(72, 'Ratul', 'ratul@gmail.com', NULL, '$2y$12$vNXrx4bJoq.gF/PtgORzDOQMYAmzqmNgl.QTULRZPUARbnzj14O46', 'customer', NULL, '2025-11-14 09:35:45', '2025-11-14 09:35:45'),
(73, 'Ashraful', 'ashraful@gmail.com', NULL, '$2y$12$qx30GRv4KGK.g6wbxUB0feNGULm.sZvcGnC.7/Q7aJx2mSO214Jpi', 'customer', NULL, '2025-11-14 09:47:42', '2025-11-14 09:47:42'),
(74, 'Zia', 'zia@gmail.com', NULL, '$2y$12$fGCENnUugDUBPhW9dLDMFunsPKwlNNAOpCncpLfMiK/ka8TDwj1uq', 'customer', NULL, '2025-11-14 09:55:02', '2025-11-14 09:55:02'),
(75, 'Jeni Khan', 'jenikhan@gmail.com', NULL, '$2y$12$NyDm9Fe/5iXr0ueLpBfZk.Dwx8fVZJ2ECKyaUdNP.J1fzg65pxlBK', 'customer', NULL, '2025-11-16 01:39:23', '2025-11-16 01:39:23'),
(76, 'Araf Khan', 'arafkhan@gmail.com', NULL, '$2y$12$FcT2GzWifMRzSia3O8hX3Ox6GVN52LgjMUsPeePykHI.zIo4IJFrG', 'customer', NULL, '2025-11-16 02:15:33', '2025-11-16 02:15:33'),
(77, 'Shafi Islam', 'shafi@gmail.com', NULL, '$2y$12$ajlZdgjg5qbRMqbCqFIY8.b/2QdxW74lDget5o1OTwlszJGid49Uq', 'customer', NULL, '2025-11-16 03:56:13', '2025-11-16 03:56:13');

-- --------------------------------------------------------

--
-- Structure for view `monthly_revenue_summary`
--
DROP TABLE IF EXISTS `monthly_revenue_summary`;

DROP VIEW IF EXISTS `monthly_revenue_summary`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `monthly_revenue_summary`  AS SELECT date_format(`i`.`issue_date`,'%Y-%m') AS `month_year`, count(`i`.`invoice_id`) AS `invoice_count`, sum(`i`.`total_amount`) AS `total_revenue`, sum(`i`.`received_amount`) AS `collected_revenue`, sum((`i`.`total_amount` - `i`.`received_amount`)) AS `pending_revenue` FROM `invoices` AS `i` GROUP BY date_format(`i`.`issue_date`,'%Y-%m') ORDER BY `month_year` DESC ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
