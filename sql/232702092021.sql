-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 02, 2021 at 08:26 PM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `omnibizz`
--

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `address_1` varchar(191) NOT NULL,
  `address_2` varchar(191) NOT NULL,
  `mobile` varchar(64) NOT NULL,
  `landline` varchar(16) DEFAULT NULL,
  `official_email` varchar(191) DEFAULT NULL,
  `prefix` varchar(3) NOT NULL,
  `ntn_number` varchar(32) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `license` varchar(191) DEFAULT NULL,
  `website` varchar(191) DEFAULT NULL,
  `overview` varchar(1000) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `name`, `address_1`, `address_2`, `mobile`, `landline`, `official_email`, `prefix`, `ntn_number`, `avatar`, `license`, `website`, `overview`, `active`, `created_at`, `updated_at`, `updated_by`, `created_by`, `deleted_at`) VALUES
(1, 'Apple', 'UK', 'UK', '03212345123', '0211345623', 'info@apple.com', 'GHJ', '938467', 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/Apple_logo_black.svg/1724px-Apple_logo_black.svg.png', '12345', 'https://apple.com', 'Everything Apple', 1, '2021-05-01 10:33:38', '2021-09-02 18:23:19', NULL, NULL, NULL),
(2, 'Microsoft', 'England', 'England', '03212345127', '0211345624', 'hello@microsoft.com', 'LMN', '394876', 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/44/Microsoft_logo.svg/1200px-Microsoft_logo.svg.png', '56789', 'https://microsoft.com', 'Everything Microsoft', 1, '2021-05-01 10:33:38', '2021-09-02 18:23:22', NULL, NULL, NULL),
(3, 'Facebook', 'Swedan', 'Swedan', '03212345120', '0211345626', 'info@facebook.com', 'KOP', '394879', 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/44/Microsoft_logo.svg/1200px-Microsoft_logo.svg.png', '56789', 'https://facebook.com', 'Everythin Facebook', 0, '2021-05-01 10:33:38', '2021-08-18 17:31:52', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `client_department`
--

CREATE TABLE `client_department` (
  `client_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `client_department`
--

INSERT INTO `client_department` (`client_id`, `department_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, '2021-05-27 02:46:57', '2021-05-27 07:46:57', NULL),
(1, 3, '2021-05-27 02:24:10', '2021-05-27 07:46:02', NULL),
(1, 4, '2021-05-27 02:24:10', '2021-05-27 07:46:02', NULL),
(2, 3, '2021-05-27 02:24:10', '2021-05-27 07:46:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `name` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_1` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_2` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_num` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `personal_email` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `client_id`, `name`, `address_1`, `address_2`, `phone_num`, `personal_email`, `updated_at`, `created_at`, `deleted_at`) VALUES
(3, 1, 'Mustafa sweets', 'uk', 'uk', '021234325423', 'mustafa@omni.com', NULL, '2021-06-20 12:07:05', NULL),
(4, 1, 'lucky cement', 'uk', 'uk', '021234325423', 'Lucky_cemen@gmail.com', NULL, '2021-06-20 12:07:05', NULL),
(5, 1, 'New Company', NULL, 'uk', '021234325423', 'NewCompany@gmail.com', NULL, '2021-06-20 12:07:05', NULL),
(6, 1, 'Imtiaz Dha', NULL, 'uk', '021234325423', 'ImtiazDHA@gmail.com', NULL, '2021-06-20 12:07:05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Management', '2021-05-11 11:00:23', '2021-05-26 16:54:08', NULL),
(3, 'Human Resources', '2021-05-25 14:06:18', '2021-05-26 16:55:04', NULL),
(4, 'Hr', '2021-05-31 16:43:55', '2021-08-05 16:23:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `firstname` varchar(80) NOT NULL,
  `lastname` varchar(80) NOT NULL,
  `personal_email` varchar(211) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `position_id` int(11) DEFAULT NULL,
  `mobile_no` char(11) NOT NULL,
  `location` varchar(512) NOT NULL,
  `working_hours` int(11) NOT NULL,
  `working_time_start` time NOT NULL,
  `working_time_end` time NOT NULL,
  `break_time_start` time NOT NULL,
  `break_time_end` time NOT NULL,
  `salary` int(6) NOT NULL,
  `bank_account_no` bigint(20) NOT NULL,
  `nationality` varchar(512) NOT NULL,
  `nic_no` bigint(13) NOT NULL,
  `gender` enum('female','male','','') NOT NULL,
  `passport_no` bigint(13) DEFAULT NULL,
  `birth_date` date NOT NULL,
  `birth_place` varchar(11) NOT NULL,
  `birth_country` varchar(11) NOT NULL,
  `martial_status` enum('single','married','','') NOT NULL,
  `children` int(11) DEFAULT NULL,
  `emergency_contact` char(11) NOT NULL,
  `visa_number` int(8) DEFAULT NULL,
  `working_permit_number` bigint(11) DEFAULT NULL,
  `visa_expire_date` date DEFAULT NULL,
  `education_level` enum('nothing','matriculation','intermediate','bachelor''s','master''s','doctorate') NOT NULL,
  `education_field` varchar(512) DEFAULT NULL,
  `educational_institute` varchar(512) DEFAULT NULL,
  `income_tax` int(1) NOT NULL DEFAULT 5,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `client_id`, `firstname`, `lastname`, `personal_email`, `picture`, `position_id`, `mobile_no`, `location`, `working_hours`, `working_time_start`, `working_time_end`, `break_time_start`, `break_time_end`, `salary`, `bank_account_no`, `nationality`, `nic_no`, `gender`, `passport_no`, `birth_date`, `birth_place`, `birth_country`, `martial_status`, `children`, `emergency_contact`, `visa_number`, `working_permit_number`, `visa_expire_date`, `education_level`, `education_field`, `educational_institute`, `income_tax`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Mubashir', 'Ahmed', 'mubashir123@gmai.com', 'dsggsgsgs', 2, '03212323232', 'Bahria town', 9, '19:28:14', '00:00:00', '00:00:00', '00:00:00', 100000, 35235162462, 'pakistani', 4220152150901, 'male', 0, '1999-10-10', 'pakistan', 'pakistani', 'single', 0, '03212323239', 0, 0, '0000-00-00', 'nothing', '', '', 5, 92, 1, '2021-05-11 06:01:29', '2021-08-08 15:24:29', NULL),
(2, 1, 'Bano', 'Bibi', 'banobibi@gmail.com', 'fdgdbhdet', 8, '03481320286', 'Azsgxdfhcfgjhkg', 9, '00:00:00', '00:00:00', '00:00:00', '00:00:00', 12242354, 122423523543435, 'pakistani', 4220152150905, 'female', 0, '2021-05-29', 'pakistan', 'pakistani', 'single', 0, '03481350226', 0, 0, '0000-00-00', 'nothing', '', '', 5, 92, NULL, '2021-05-11 06:04:24', NULL, NULL),
(3, 1, 'Steve', 'Jobs', 'employee@personal.com', 'fdgdbhdet', 7, '03481111111', 'Ahaahhaahha', 9, '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 3481232456, 'pakistani', 4220152150905, 'female', 0, '2021-05-29', 'pakistan', 'pakistani', 'single', 0, '03481232406', 0, 0, '0000-00-00', 'nothing', '', '', 5, 92, 3, '2021-05-11 06:04:24', '2021-08-08 15:09:31', NULL),
(4, 1, 'Faizan', 'Imran', 'faizanimran286@gmail.com', NULL, 7, '03211234511', 'Sarab ghott', 9, '09:00:00', '20:00:00', '12:00:00', '13:00:00', 253325, 24356546697089, 'pakistani', 4220152150900, 'male', NULL, '1999-06-09', 'pakistan', 'pakistani', 'married', 0, '03481350283', NULL, NULL, NULL, 'bachelor\'s', 'Computer Science', 'Bahria University', 5, NULL, NULL, '2021-06-08 11:50:12', NULL, NULL),
(5, 1, 'Faizan1', 'Imran1', 'faizanimran21186@gmail.com', NULL, 1, '03211234511', 'Sarab ghott', 9, '09:00:00', '20:00:00', '12:00:00', '13:00:00', 253325, 24356546697089, 'pakistani', 4220152150900, 'male', NULL, '1999-06-09', 'pakistan', 'pakistani', 'married', 0, '03481350283', NULL, NULL, NULL, 'bachelor\'s', 'Computer Science', 'Bahria University', 5, NULL, NULL, '2021-06-08 11:50:12', NULL, NULL),
(6, 1, 'Super', 'Admin', 'anumsaeed126@gmail.com', NULL, 3, '03481350211', 'azsgxdfhcfgjhkg', 9, '00:00:00', '00:00:00', '12:00:00', '00:00:00', 2131, 24356546697089, 'pakistani', 1234567879123, 'male', NULL, '1999-06-09', 'pakistan', 'pakistani', 'single', 1, '03481350299', NULL, NULL, NULL, 'nothing', NULL, NULL, 5, 1, NULL, '2021-08-19 12:19:56', '2021-08-23 08:31:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `product_supplier_and_buyer` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `total_amount` int(11) NOT NULL,
  `updated_by` int(1) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `client_id`, `employee_id`, `product_supplier_and_buyer`, `total_amount`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 4, 'Mini Mart', 1285, NULL, '2021-06-27 07:20:10', '2021-08-03 10:11:16', NULL),
(2, 1, 4, 'Mini Mart', 1285, NULL, '2021-07-20 07:27:37', '2021-08-03 10:11:23', NULL),
(3, 1, 2, 'Mini Mart', 1285, NULL, '2021-07-20 07:28:26', '2021-08-03 10:11:29', NULL),
(5, 1, 2, 'imtiaz dha', 8900, NULL, '2021-07-20 07:29:06', '2021-08-03 10:11:47', NULL),
(6, 1, 3, 'imtiaz dha', 8900, NULL, '2021-07-20 07:29:21', '2021-08-08 10:52:13', NULL),
(7, 1, 3, 'imtiaz dha', 8900, NULL, '2021-07-20 07:29:33', '2021-08-08 10:52:15', NULL),
(8, 1, NULL, 'imtiaz dha', 8900, NULL, '2021-07-20 07:29:51', '2021-07-20 07:29:51', NULL),
(9, 1, NULL, 'imtiaz dha', 8900, NULL, '2021-07-20 07:30:06', '2021-07-20 07:30:06', NULL),
(10, 1, NULL, 'imtiaz dha', 8900, NULL, '2021-07-20 07:33:08', '2021-07-20 07:33:08', NULL),
(11, 1, NULL, 'Mini Mart', 1285, NULL, '2021-07-20 07:33:24', '2021-07-20 07:33:24', NULL),
(12, 1, NULL, 'imtiaz dha', 8900, NULL, '2021-07-20 07:38:02', '2021-07-20 07:38:02', NULL),
(13, 1, NULL, 'Mini Mart', 1285, NULL, '2021-07-20 08:42:48', '2021-07-20 08:42:48', NULL),
(14, 1, NULL, 'Mini Mart', 1285, NULL, '2021-07-20 08:42:59', '2021-07-20 08:42:59', NULL),
(15, 1, NULL, 'imtiaz dha', 8900, NULL, '2021-07-21 13:36:48', '2021-07-21 13:36:48', NULL),
(16, 1, NULL, 'Mustafa sweets', 200000, NULL, '2021-07-21 14:03:16', '2021-07-21 14:03:16', NULL),
(17, 1, NULL, 'Mustafa sweets', 200000, NULL, '2021-07-21 14:03:31', '2021-07-21 14:03:31', NULL),
(18, 1, NULL, 'imtiaz dha', 8900, NULL, '2021-08-01 05:16:53', '2021-08-01 05:16:53', NULL),
(19, 1, NULL, 'New Company', 43605, NULL, '2021-08-01 05:53:58', '2021-08-01 05:53:58', NULL),
(20, 1, NULL, 'Mini Mart', 2250, NULL, '2021-08-04 09:18:35', '2021-08-04 09:18:35', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` int(11) NOT NULL,
  `total_price` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice_id`, `product_id`, `quantity`, `unit_price`, `total_price`, `created_at`, `updated_at`, `deleted_at`) VALUES
(36, 1, 31, 3, 45, 135, '2021-06-27 07:20:10', '2021-06-27 07:20:10', NULL),
(37, 1, 7, 46, 25, 1150, '2021-06-27 07:20:10', '2021-06-27 07:20:10', NULL),
(38, 2, 31, 3, 45, 135, '2021-07-20 07:27:37', '2021-07-20 07:27:37', NULL),
(39, 2, 7, 46, 25, 1150, '2021-07-20 07:27:37', '2021-07-20 07:27:37', NULL),
(40, 3, 31, 3, 45, 135, '2021-07-20 07:28:26', '2021-07-20 07:28:26', NULL),
(41, 3, 7, 46, 25, 1150, '2021-07-20 07:28:26', '2021-07-20 07:28:26', NULL),
(44, 5, 7, 34, 43, 1462, '2021-07-20 07:29:06', '2021-07-20 07:29:06', NULL),
(45, 5, 29, 34, 43, 1462, '2021-07-20 07:29:06', '2021-07-20 07:29:06', NULL),
(46, 6, 7, 34, 43, 1462, '2021-07-20 07:29:21', '2021-07-20 07:29:21', NULL),
(47, 6, 29, 34, 43, 1462, '2021-07-20 07:29:21', '2021-07-20 07:29:21', NULL),
(48, 7, 7, 34, 43, 1462, '2021-07-20 07:29:33', '2021-07-20 07:29:33', NULL),
(49, 7, 29, 34, 43, 1462, '2021-07-20 07:29:33', '2021-07-20 07:29:33', NULL),
(50, 8, 7, 34, 43, 1462, '2021-07-20 07:29:51', '2021-07-20 07:29:51', NULL),
(51, 8, 29, 34, 43, 1462, '2021-07-20 07:29:51', '2021-07-20 07:29:51', NULL),
(52, 9, 7, 34, 43, 1462, '2021-07-20 07:30:06', '2021-07-20 07:30:06', NULL),
(53, 9, 29, 34, 43, 1462, '2021-07-20 07:30:06', '2021-07-20 07:30:06', NULL),
(54, 10, 7, 34, 43, 1462, '2021-07-20 07:33:08', '2021-07-20 07:33:08', NULL),
(55, 10, 29, 34, 43, 1462, '2021-07-20 07:33:08', '2021-07-20 07:33:08', NULL),
(56, 11, 31, 3, 45, 135, '2021-07-20 07:33:24', '2021-07-20 07:33:24', NULL),
(57, 11, 7, 46, 25, 1150, '2021-07-20 07:33:24', '2021-07-20 07:33:24', NULL),
(58, 12, 7, 34, 43, 1462, '2021-07-20 07:38:02', '2021-07-20 07:38:02', NULL),
(59, 12, 29, 34, 43, 1462, '2021-07-20 07:38:02', '2021-07-20 07:38:02', NULL),
(60, 13, 31, 3, 45, 135, '2021-07-20 08:42:48', '2021-07-20 08:42:48', NULL),
(61, 13, 7, 46, 25, 1150, '2021-07-20 08:42:48', '2021-07-20 08:42:48', NULL),
(62, 14, 31, 3, 45, 135, '2021-07-20 08:42:59', '2021-07-20 08:42:59', NULL),
(63, 14, 7, 46, 25, 1150, '2021-07-20 08:42:59', NULL, NULL),
(64, 15, 7, 34, 43, 1462, '2021-07-21 13:36:49', NULL, NULL),
(65, 15, 29, 34, 43, 1462, '2021-07-21 13:36:49', NULL, NULL),
(66, 16, 25, 40, 5000, 200000, '2021-07-21 14:03:16', NULL, NULL),
(67, 17, 25, 40, 5000, 200000, '2021-07-21 14:03:31', NULL, NULL),
(68, 18, 7, 34, 43, 1462, '2021-08-01 05:16:53', NULL, NULL),
(69, 18, 29, 34, 43, 1462, '2021-08-01 05:16:53', NULL, NULL),
(70, 19, 26, 57, 765, 43605, '2021-08-01 05:53:58', NULL, NULL),
(71, 20, 31, 45, 50, 2250, '2021-08-04 09:18:35', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `job_positions`
--

CREATE TABLE `job_positions` (
  `id` int(11) NOT NULL,
  `title` varchar(191) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `job_positions`
--

INSERT INTO `job_positions` (`id`, `title`, `department_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Cashier', 3, '2021-05-11 11:01:10', '2021-05-26 16:57:08', NULL),
(2, 'Admin', 1, '2021-05-11 11:01:02', '2021-05-27 12:00:12', NULL),
(3, 'Accountant', 4, '2021-05-11 11:00:53', '2021-08-05 15:34:16', NULL),
(7, 'manager', 3, '2021-05-11 11:00:53', '2021-06-04 17:19:51', NULL),
(8, 'HR', 1, '2021-06-23 03:28:44', '2021-08-05 14:49:52', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `title` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `route` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `controller` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  `position` int(11) NOT NULL DEFAULT 1,
  `sub_module_id` int(11) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  `method` enum('get','put','post','update','patch','delete') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'get'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `title`, `route`, `controller`, `parent`, `position`, `sub_module_id`, `module_id`, `method`) VALUES
(1, 'Dashboard', 'dashboard', 'Modules/DashboardController@index', NULL, 1, NULL, NULL, 'get'),
(2, 'Employees', 'employees', 'Modules/EmployeeController@index', NULL, 1, 4, 1, 'get'),
(3, 'Add Employees', 'employee.add', 'Modules/EmployeeController@index', 2, 1, 4, 1, 'get'),
(4, 'Edit Employees', 'employee.edit', 'Modules/EmployeeController@index', 2, 1, 4, 1, 'get'),
(5, 'Delete Employees', 'employee.delete', 'Modules/EmployeeController@index', 2, 1, 4, 1, 'get'),
(6, 'View Employees', 'employee.list', 'Modules/EmployeeController@index', 2, 1, 4, 1, 'get'),
(7, 'Departments', 'departments', 'Modules/DepartmentController@index', NULL, 1, 2, 1, 'get'),
(8, 'Add Department', 'department.add', 'Modules/DepartmentController@index', 7, 1, 2, 1, 'get'),
(9, 'Edit Department', 'department.edit', 'Modules/DepartmentController@index', 7, 1, 2, 1, 'get'),
(10, 'Delete Department', 'department.delete', 'Modules/DepartmentController@index', 7, 1, 2, 1, 'get'),
(11, 'View Department', 'department.list', 'Modules/DepartmentController@index', 7, 1, 2, 1, 'get'),
(12, 'Position', 'position', 'Modules/PositionController@index', NULL, 1, 3, 1, 'get'),
(13, 'Add Position', 'position.add', 'Modules/PositionController@index', 12, 1, 3, 1, 'get'),
(14, 'Edit Position', 'position.edit', 'Modules/PositionController@index', 12, 1, 3, 1, 'get'),
(15, 'Delete Position', 'position.delete', 'Modules/PositionController@index', 12, 1, 3, 1, 'get'),
(16, 'View Position', 'position.list', 'Modules/PositionController@index', 12, 1, 3, 1, 'get'),
(17, 'Sales', 'sale', 'Modules/SaleController@index', NULL, 1, 5, 2, 'get'),
(18, 'Add Sale Order', 'sale.add', 'Modules/SaleController@index', 17, 1, 5, 2, 'get'),
(19, 'Edit Sale order', 'sale.edit', 'Modules/SaleController@index', 17, 1, 5, 2, 'get'),
(20, 'Delete Sale Order', 'sale.delete', 'Modules/SaleController@index', 17, 1, 5, 2, 'get'),
(21, 'View Sale Order', 'sale.list', 'Modules/SaleController@index', 17, 1, 5, 2, 'get'),
(22, 'Overview Sale', 'sale.overview', 'Modules/SaleController@index', 17, 1, 5, 2, 'get'),
(23, 'Purchase', 'purchase', 'Modules/PurchaseController@index', NULL, 1, 6, 2, 'get'),
(24, 'Add Purchase Order', 'purchase.add', 'Modules/PurchaseController@index', 23, 1, 6, 2, 'get'),
(25, 'Edit Purchase order', 'purchase.edit', 'Modules/PurchaseController@index', 23, 1, 6, 2, 'get'),
(26, 'Delete Purchase Order', 'purchase.delete', 'Modules/PurchaseController@index', 23, 1, 6, 2, 'get'),
(27, 'View Purchase Order', 'purchase.list', 'Modules/PurchaseController@index', 23, 1, 6, 2, 'get'),
(28, 'Overview Purchase ', 'purchase.overview', 'Modules/PurchaseController@index', 23, 1, 6, 2, 'get'),
(29, 'Products', 'product', 'Modules/ProductController@index', NULL, 1, 8, 2, 'get'),
(30, 'Add Product', 'product.add', 'Modules/ProductController@index', 29, 1, 8, 2, 'get'),
(31, 'Edit Product', 'product.edit', 'Modules/ProductController@index', 29, 1, 8, 2, 'get'),
(32, 'Delete Product', 'product.delete', 'Modules/ProductController@index', 29, 1, 8, 2, 'get'),
(33, 'View Product', 'product.list', 'Modules/ProductController@index', 29, 1, 8, 2, 'get'),
(34, 'Categories', 'category', 'Modules/CategoryController@index', NULL, 1, 9, 2, 'get'),
(35, 'Add Category', 'category.add', 'Modules/CategoryController@index', 34, 1, 9, 2, 'get'),
(36, 'Edit Category', 'category.edit', 'Modules/CategoryController@index', 34, 1, 9, 2, 'get'),
(37, 'Delete Category', 'category.delete', 'Modules/CategoryController@index', 34, 1, 9, 2, 'get'),
(38, 'View Category', 'category.list', 'Modules/CategoryController@index', 34, 1, 9, 2, 'get'),
(39, 'Quotations', 'quotations', 'Modules/QuotationController@index', NULL, 1, 10, 2, 'get'),
(40, 'Add Quotation', 'quotation.add', 'Modules/QuotationController@index', 39, 1, 10, 2, 'get'),
(41, 'Edit Quotation', 'quotation.edit', 'Modules/QuotationController@index', 39, 1, 10, 2, 'get'),
(42, 'Delete Quotation', 'quotation.delete', 'Modules/QuotationController@index', 39, 1, 10, 2, 'get'),
(43, 'View Quotation', 'quotation.list', 'Modules/QuotationController@index', 39, 1, 10, 2, 'get'),
(44, 'Products', 'product', 'Modules/ProductController@index', NULL, 1, 12, 3, 'get'),
(45, 'Add Product', 'product.add', 'Modules/ProductController@index', 44, 1, 12, 3, 'get'),
(46, 'Edit Product', 'product.edit', 'Modules/ProductController@index', 44, 1, 12, 3, 'get'),
(47, 'Delete Product', 'product.delete', 'Modules/ProductController@index', 44, 1, 12, 3, 'get'),
(48, 'View Product', 'product.list', 'Modules/ProductController@index', 44, 1, 12, 3, 'get'),
(49, 'Categories', 'category', 'Modules/CategoryController@index', NULL, 1, 13, 3, 'get'),
(50, 'Add Category', 'category.add', 'Modules/CategoryController@index', 49, 1, 13, 3, 'get'),
(51, 'Edit Category', 'category.edit', 'Modules/CategoryController@index', 49, 1, 13, 3, 'get'),
(52, 'Delete Category', 'category.delete', 'Modules/CategoryController@index', 49, 1, 13, 3, 'get'),
(53, 'View Category', 'category.list', 'Modules/CategoryController@index', 49, 1, 13, 3, 'get'),
(54, 'User', 'user', NULL, NULL, 1, 14, 6, 'get'),
(55, 'Add User', 'user.add', NULL, 54, 1, 14, 6, 'get'),
(56, 'Edit User', 'user.edit', NULL, 54, 1, 14, 6, 'get'),
(57, 'Delete User', 'user.delete', NULL, 54, 1, 14, 6, 'get'),
(58, 'View User', 'user.list', NULL, 54, 1, 14, 6, 'get'),
(59, 'Setting', 'setting', NULL, NULL, 1, 15, 6, 'get'),
(60, 'Add Setting', 'setting.add', NULL, 59, 1, 15, 6, 'get'),
(61, 'Edit Setting', 'setting.edit', NULL, 59, 1, 15, 6, 'get'),
(62, 'Delete Setting', 'setting.delete', NULL, 59, 1, 15, 6, 'get'),
(63, 'View Setting', 'setting.view', NULL, 59, 1, 15, 6, 'get');

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` int(11) NOT NULL,
  `name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `optional` tinyint(1) NOT NULL DEFAULT 1,
  `parent` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `name`, `slug`, `optional`, `parent`) VALUES
(1, 'Company & HR', 'company.hr', 0, NULL),
(2, 'Sales, Purchase and Inventory', 'sales.purchase.inventory', 1, NULL),
(3, 'Inventory', 'inventory', 1, NULL),
(4, 'Sales, Purchase, Inventory And Accounts & Finance', 'sales.purchase.inventory.accounts.finance', 1, NULL),
(5, 'Accounts & Finance', 'accounts.finance', 1, NULL),
(6, 'Administrator', 'admin', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `module_assignment`
--

CREATE TABLE `module_assignment` (
  `user_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `sub_module_id` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `module_assignment`
--

INSERT INTO `module_assignment` (`user_id`, `module_id`, `sub_module_id`, `active`) VALUES
(13, 3, 12, 1),
(13, 3, 13, 1),
(1, 1, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `unit` enum('lb','g','kg','ton','ml','lt','pc') NOT NULL DEFAULT 'kg',
  `in_stock` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `client_id`, `name`, `unit`, `in_stock`, `created_at`, `updated_at`, `deleted_at`) VALUES
(7, 1, 'chickpeas', 'kg', 373, '2021-05-10 21:48:21', '2021-06-27 06:08:18', NULL),
(25, 1, 'cheesy', 'ton', 45, '2021-05-11 05:28:48', '2021-05-30 22:08:09', NULL),
(26, 1, 'Ketchup', 'ton', 170, '2021-05-11 07:41:24', '2021-06-19 02:23:26', NULL),
(27, 2, 'Tomato1', 'kg', 1343, '2021-05-27 12:57:04', '2021-08-04 11:38:06', NULL),
(28, 1, 'Biryani Masala', 'kg', 563, '2021-06-10 13:19:54', '2021-06-10 13:19:54', NULL),
(29, 1, 'chilli', 'ton', 7, '2021-06-12 14:10:03', '2021-06-12 14:10:03', NULL),
(31, 1, 'Tomato', 'kg', 6, '2021-06-12 16:47:19', '2021-06-27 06:08:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `title` varchar(80) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `client_id`, `title`, `created_at`, `updated_at`, `deleted_at`) VALUES
(3, 1, 'dry product', '2021-05-10 23:40:10', '2021-06-12 19:12:52', NULL),
(4, 2, 'Electric Product', '2021-05-10 18:40:48', '2021-06-10 17:44:00', NULL),
(7, 1, 'Vegetables', '2021-05-10 19:03:41', '2021-05-27 17:56:20', NULL),
(8, 1, 'spice', '2021-05-11 07:42:25', '2021-08-07 15:32:47', NULL),
(9, 1, 'Fruits', '2021-05-27 11:07:27', '2021-06-10 13:14:44', NULL),
(10, 1, 'hr.o', '2021-08-07 07:00:27', '2021-08-07 10:26:05', '2021-08-07 10:26:05'),
(11, 1, 'ghf', '2021-08-07 07:01:27', '2021-08-08 13:32:50', '2021-08-08 13:32:50');

-- --------------------------------------------------------

--
-- Table structure for table `product_categories_assigned`
--

CREATE TABLE `product_categories_assigned` (
  `category_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product_categories_assigned`
--

INSERT INTO `product_categories_assigned` (`category_id`, `product_id`, `updated_at`, `created_at`, `deleted_at`) VALUES
(3, 7, NULL, '2021-06-12 19:06:26', NULL),
(3, 25, NULL, '2021-06-12 14:10:03', NULL),
(7, 26, NULL, '2021-05-11 07:41:24', NULL),
(7, 27, NULL, '2021-05-27 12:57:04', NULL),
(7, 31, NULL, '2021-06-12 16:47:19', NULL),
(8, 28, NULL, '2021-06-10 13:19:54', NULL),
(8, 29, NULL, '2021-06-12 14:10:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` int(16) NOT NULL,
  `purchase` varchar(32) NOT NULL,
  `client_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `quotation_id` int(11) DEFAULT NULL,
  `supplier_name` varchar(191) NOT NULL,
  `tax_value` smallint(2) NOT NULL,
  `total_amount` float NOT NULL,
  `due_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `delivered_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`id`, `purchase`, `client_id`, `employee_id`, `invoice_id`, `quotation_id`, `supplier_name`, `tax_value`, `total_amount`, `due_date`, `delivered_at`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '4aa7af59893346ff98ed17267bf163a5', 1, 1, NULL, 15, 'Anchor Mart', 5, 17204, '2021-08-05 11:50:11', NULL, NULL, '2021-05-30 17:05:51', '2021-05-30 17:05:51', NULL),
(2, '4aa7af59893346ff98ed17267bf163a5', 1, 3, NULL, 10, 'Mustafa sweets', 5, 500000, '2021-08-02 09:47:09', NULL, NULL, '2021-05-30 17:06:01', '2021-05-30 17:06:01', NULL),
(3, 'eac4a49edc144f7a85c97ca2c95a3517', 1, 4, 16, 10, 'Mustafa sweets', 5, 200000, '2021-08-03 16:06:05', NULL, NULL, '2021-07-21 14:03:16', '2021-07-21 14:03:16', NULL),
(4, 'c14e9a175bbf4488963ae290b94813f5', 1, 2, 17, 10, 'Mustafa sweets', 5, 200000, '2021-08-05 11:50:25', NULL, NULL, '2021-07-21 14:03:31', '2021-07-21 14:03:31', NULL),
(5, '0348d7e546274bf6806adb4954667eca', 1, 4, 19, 19, 'New Company', 5, 43605, '2021-08-03 16:38:18', NULL, NULL, '2021-08-02 23:53:58', '2021-08-01 05:53:58', NULL),
(6, 'aaad636e7ce847618c71c97d8eba1f78', 1, 1, 20, 21, 'Mini Mart', 5, 2250, '2021-08-05 11:50:14', NULL, NULL, '2021-08-04 09:18:35', '2021-08-04 09:18:35', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_line`
--

CREATE TABLE `purchase_order_line` (
  `id` int(16) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `purchase_id` int(16) DEFAULT NULL,
  `quantity` int(16) NOT NULL,
  `unit_price` float NOT NULL,
  `total_price` float NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `purchase_order_line`
--

INSERT INTO `purchase_order_line` (`id`, `product_id`, `purchase_id`, `quantity`, `unit_price`, `total_price`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 25, 1, 5, 100000, 345325, '2021-05-30 17:06:01', '2021-06-24 19:13:54', NULL),
(2, 25, 2, 5, 100000, 345325, '2021-05-30 17:06:01', '2021-06-24 19:13:54', NULL),
(3, 25, 3, 40, 5000, 200000, '2021-07-21 14:03:16', '2021-07-21 14:03:16', NULL),
(4, 25, 4, 40, 5000, 200000, '2021-07-21 14:03:31', '2021-07-21 14:03:31', NULL),
(5, 26, 5, 57, 765, 43605, '2021-08-01 05:53:58', '2021-08-01 05:53:58', NULL),
(6, 31, 6, 45, 50, 2250, '2021-08-04 09:18:35', '2021-08-04 09:18:35', NULL),
(7, 28, 1, 5, 100000, 345325, '2021-05-30 17:06:01', '2021-06-24 19:13:54', NULL),
(8, 31, 3, 40, 5000, 200000, '2021-07-21 14:03:16', '2021-07-21 14:03:16', NULL),
(9, 7, 3, 40, 5000, 200000, '2021-07-21 14:03:16', '2021-07-21 14:03:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

CREATE TABLE `quotations` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `company` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `total_amount` int(11) NOT NULL,
  `quotation_type` enum('rcvd','sent') COLLATE utf8_unicode_ci NOT NULL,
  `gst` int(2) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `accepted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `quotations`
--

INSERT INTO `quotations` (`id`, `client_id`, `employee_id`, `company`, `total_amount`, `quotation_type`, `gst`, `updated_by`, `rejected_at`, `created_at`, `accepted_at`, `updated_at`, `deleted_at`) VALUES
(10, 1, 2, 'Mustafa sweets', 200000, 'rcvd', 5, 92, NULL, '2021-05-14 15:37:00', '2021-05-13 19:00:00', '2021-06-11 09:18:51', NULL),
(14, 1, 4, 'lucky cement', 70000, 'sent', 5, 92, '2021-05-12 19:00:00', '2021-05-14 15:37:00', NULL, '2021-08-03 16:08:20', NULL),
(15, 1, 2, 'Anchor Mart', 50000, 'rcvd', 5, NULL, '2021-05-11 19:00:00', '2021-05-29 15:53:50', NULL, '2021-06-11 18:24:32', NULL),
(16, 1, 1, 'imtiaz dha', 8900, 'sent', 5, NULL, NULL, '2021-05-29 15:54:23', '2020-03-31 19:00:00', '2021-06-22 14:33:45', NULL),
(17, 1, 2, 'New Company', 308933, 'sent', 5, NULL, NULL, '2021-05-30 17:10:52', NULL, '2021-06-11 18:23:53', NULL),
(18, 1, 3, 'Bahria', 165049, 'sent', 5, NULL, NULL, '2021-06-12 04:13:19', '2021-07-21 13:34:31', '2021-08-03 15:55:30', NULL),
(19, 1, 3, 'New Company', 43605, 'rcvd', 5, NULL, NULL, '2021-06-19 02:23:26', '2021-07-21 13:57:36', '2021-08-03 15:55:33', NULL),
(20, 1, 4, 'Mini Mart', 1285, 'sent', 5, NULL, NULL, '2021-08-03 06:08:18', '2021-06-25 19:00:00', '2021-08-03 16:54:38', NULL),
(21, 1, 2, 'Mini Mart', 2250, 'rcvd', 5, NULL, NULL, '2021-08-03 06:08:18', NULL, '2021-08-05 20:33:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `quotation_items`
--

CREATE TABLE `quotation_items` (
  `id` int(11) NOT NULL,
  `quotation_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` int(11) NOT NULL,
  `total_price` float NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `quotation_items`
--

INSERT INTO `quotation_items` (`id`, `quotation_id`, `product_id`, `quantity`, `unit_price`, `total_price`, `created_at`, `updated_at`, `deleted_at`) VALUES
(9, 10, 25, 40, 5000, 200000, '2021-05-14 15:37:00', '2021-05-30 22:09:26', NULL),
(15, 14, 26, 5, 10000, 4543, '2021-05-14 15:37:00', '2021-05-30 18:17:15', NULL),
(16, 14, 27, 34, 43, 1462, '2021-05-29 15:54:23', '2021-05-30 19:19:26', NULL),
(17, 16, 7, 34, 43, 1462, '2021-05-29 15:54:23', '2021-05-30 18:07:09', NULL),
(18, 17, 7, 45, 35, 1575, '2021-05-30 17:10:52', '2021-05-30 17:10:52', NULL),
(19, 17, 26, 677, 454, 307358, '2021-05-30 17:10:52', '2021-05-30 17:10:52', NULL),
(20, 18, 26, 35, 46, 1610, '2021-06-12 04:13:19', '2021-06-22 12:26:11', NULL),
(21, 18, 7, 463, 353, 163439, '2021-06-12 04:13:19', '2021-06-22 12:26:14', NULL),
(22, 19, 26, 57, 765, 43605, '2021-06-19 02:23:26', '2021-06-19 02:23:26', NULL),
(23, 16, 29, 34, 43, 1462, '2021-05-29 15:54:23', '2021-05-30 18:07:09', NULL),
(24, 20, 31, 3, 45, 135, '2021-06-27 06:08:18', '2021-06-27 06:08:18', NULL),
(25, 20, 7, 46, 25, 1150, '2021-06-27 06:08:18', '2021-06-27 06:08:18', NULL),
(26, 21, 31, 45, 50, 2250, '2021-06-27 06:08:18', '2021-08-04 14:18:24', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(60) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'super admin'),
(2, 'admin'),
(3, 'client'),
(4, 'manager'),
(5, 'employee'),
(6, 'accountant');

-- --------------------------------------------------------

--
-- Table structure for table `sale_orders`
--

CREATE TABLE `sale_orders` (
  `id` int(16) NOT NULL,
  `sale` varchar(32) NOT NULL,
  `client_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `quotation_id` int(11) DEFAULT NULL,
  `buyer_name` varchar(191) NOT NULL,
  `tax_value` smallint(2) NOT NULL,
  `total_amount` float DEFAULT NULL,
  `due_date` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sale_orders`
--

INSERT INTO `sale_orders` (`id`, `sale`, `client_id`, `employee_id`, `invoice_id`, `quotation_id`, `buyer_name`, `tax_value`, `total_amount`, `due_date`, `delivered_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(63, '050cc8e0c58d4538882251b2e9aa7ffe', 1, 4, 1, 20, 'Mini Mart', 5, 1285, '2021-06-22 19:00:00', NULL, '2021-08-03 19:00:00', '2021-06-27 07:20:10', NULL),
(64, 'f57a761b530f43e4a4ba21cd8d4df201', 1, 4, 2, 20, 'Mini Mart', 5, 1285, '2021-07-19 19:00:00', NULL, '2021-08-03 07:27:37', '2021-07-20 07:27:37', NULL),
(65, '211b0f89d49e41e7be5aa3f8ff6ed9d6', 1, 2, 3, 20, 'Mini Mart', 5, 1285, '2021-07-19 19:00:00', NULL, '2020-10-20 07:28:26', '2021-07-20 07:28:26', NULL),
(66, 'b1ffea2399f641cb807cd317c4fac7d6', 1, 2, NULL, 20, 'Mini Mart', 5, 1285, '2021-07-19 19:00:00', NULL, '2020-11-20 07:28:47', '2021-07-20 07:28:47', NULL),
(67, '8b3ead7c4ad743cca0ed27284f553426', 1, 2, 5, 16, 'imtiaz dha', 5, 8900, '2021-07-18 19:00:00', NULL, '2020-12-20 07:29:06', '2021-07-20 07:29:06', NULL),
(68, '957a5a5c96c642f896e05a2a7c10a5f0', 1, 1, 6, 16, 'imtiaz dha', 5, 8900, '2021-07-19 19:00:00', NULL, '2021-01-20 07:29:21', '2021-07-20 07:29:21', NULL),
(69, '29da15475bc242a88e30bb268b905352', 1, 4, 7, 16, 'imtiaz dha', 5, 8900, '2021-07-18 19:00:00', NULL, '2021-02-20 07:29:33', '2021-07-20 07:29:33', NULL),
(70, '45930465cd17468aaf05639f7adab8ba', 1, 4, 8, 16, 'imtiaz dha', 5, 8900, '2021-07-19 19:00:00', NULL, '2021-03-20 07:29:51', '2021-07-20 07:29:51', NULL),
(71, '7818fc032030429aa7ab01dc97b3ea15', 1, 4, 9, 16, 'imtiaz dha', 5, 8900, '2021-07-19 19:00:00', NULL, '2021-04-20 07:30:06', '2021-07-20 07:30:06', NULL),
(72, '52b82a77f31b4e618d0f7d083930a7a3', 1, 4, 10, 16, 'imtiaz dha', 5, 8900, '2021-07-19 19:00:00', NULL, '2021-05-20 07:33:08', '2021-07-20 07:33:08', NULL),
(73, 'adc66816c208491e86e548cdb308bcfa', 1, 1, 11, 20, 'Mini Mart', 5, 1285, '2021-07-20 19:00:00', NULL, '2021-06-20 07:33:24', '2021-07-20 07:33:25', NULL),
(74, '144a7c48d95540fb9d950b966ff67036', 1, 3, 12, 16, 'imtiaz dha', 5, 8900, '2021-06-29 19:00:00', NULL, '2021-07-20 07:38:02', '2021-07-20 07:38:02', NULL),
(75, 'af7b26643ebd498dbfe77d8c1ba1453d', 1, 1, 13, 20, 'Mini Mart', 5, 1285, '2021-07-19 19:00:00', NULL, '2021-07-18 08:42:48', '2021-07-20 08:42:48', NULL),
(76, '62b8e062f86d4a2587c3aeecf78e2c92', 1, 1, 14, 20, 'Mini Mart', 5, 1285, '2021-07-17 19:00:00', NULL, '2021-07-22 08:42:59', '2021-07-20 08:42:59', NULL),
(77, 'da028ec8f9de4aa99a4edf9116c4ea4b', 1, NULL, 15, 16, 'imtiaz dha', 5, 8900, '2021-07-29 19:00:00', NULL, '2021-07-22 13:36:48', '2021-07-21 13:36:49', NULL),
(78, '38faed55c2ff46419b42f1d9b66a2850', 1, 4, 18, 16, 'imtiaz dha', 5, 8900, '2021-08-02 19:00:00', NULL, '2021-08-01 05:16:53', '2021-08-01 05:16:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sale_order_line`
--

CREATE TABLE `sale_order_line` (
  `id` int(16) NOT NULL,
  `product_id` int(11) NOT NULL,
  `sale_id` int(16) NOT NULL,
  `quantity` int(16) NOT NULL,
  `unit_price` float NOT NULL,
  `total_price` float DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sale_order_line`
--

INSERT INTO `sale_order_line` (`id`, `product_id`, `sale_id`, `quantity`, `unit_price`, `total_price`, `created_at`, `updated_at`, `deleted_at`) VALUES
(77, 31, 63, 3, 45, 135, '2021-08-03 19:00:00', '2021-08-04 13:29:42', NULL),
(78, 7, 63, 46, 25, 1150, '2021-08-03 19:00:00', '2021-08-04 13:29:45', NULL),
(79, 31, 64, 3, 45, 135, '2021-08-01 07:27:37', '2021-08-04 14:02:33', NULL),
(80, 7, 64, 46, 25, 1150, '2021-08-01 07:27:37', '2021-08-04 14:02:39', NULL),
(81, 31, 65, 3, 45, 135, '2021-07-20 07:28:26', '2021-07-20 07:28:26', NULL),
(82, 7, 65, 46, 25, 1150, '2021-07-20 07:28:26', '2021-07-20 07:28:26', NULL),
(83, 31, 66, 3, 45, 135, '2021-07-20 07:28:47', '2021-07-20 07:28:47', NULL),
(84, 7, 66, 46, 25, 1150, '2021-07-20 07:28:47', '2021-07-20 07:28:47', NULL),
(85, 7, 67, 34, 43, 1462, '2021-07-20 07:29:06', '2021-07-20 07:29:06', NULL),
(86, 29, 67, 34, 43, 1462, '2021-07-20 07:29:06', '2021-07-20 07:29:06', NULL),
(87, 7, 68, 34, 43, 1462, '2021-07-20 07:29:21', '2021-07-20 07:29:21', NULL),
(88, 29, 68, 34, 43, 1462, '2021-07-20 07:29:21', '2021-07-20 07:29:21', NULL),
(89, 7, 69, 34, 43, 1462, '2021-07-20 07:29:33', '2021-07-20 07:29:33', NULL),
(90, 29, 69, 34, 43, 1462, '2021-07-20 07:29:33', '2021-07-20 07:29:33', NULL),
(91, 7, 70, 34, 43, 1462, '2021-07-20 07:29:51', '2021-07-20 07:29:51', NULL),
(92, 29, 70, 34, 43, 1462, '2021-07-20 07:29:51', '2021-07-20 07:29:51', NULL),
(93, 7, 71, 34, 43, 1462, '2021-07-20 07:30:06', '2021-07-20 07:30:06', NULL),
(94, 29, 71, 34, 43, 1462, '2021-07-20 07:30:06', '2021-07-20 07:30:06', NULL),
(95, 7, 72, 34, 43, 1462, '2021-07-20 07:33:08', '2021-07-20 07:33:08', NULL),
(96, 29, 72, 34, 43, 1462, '2021-07-20 07:33:08', '2021-07-20 07:33:08', NULL),
(97, 31, 73, 3, 45, 135, '2021-07-20 07:33:24', '2021-07-20 07:33:24', NULL),
(98, 7, 73, 46, 25, 1150, '2021-07-20 07:33:24', '2021-07-20 07:33:24', NULL),
(99, 7, 74, 34, 43, 1462, '2021-07-20 07:38:02', '2021-07-20 07:38:02', NULL),
(100, 29, 74, 34, 43, 1462, '2021-07-20 07:38:02', '2021-07-20 07:38:02', NULL),
(101, 31, 75, 3, 45, 135, '2021-07-20 08:42:48', '2021-07-20 08:42:48', NULL),
(102, 7, 75, 46, 25, 1150, '2021-07-20 08:42:48', '2021-07-20 08:42:48', NULL),
(103, 31, 76, 3, 45, 135, '2021-07-20 08:42:59', '2021-07-20 08:42:59', NULL),
(104, 7, 76, 46, 25, 1150, '2021-07-20 08:42:59', '2021-07-20 08:42:59', NULL),
(105, 7, 77, 34, 43, 1462, '2021-07-21 13:36:48', '2021-07-21 13:36:48', NULL),
(106, 29, 77, 34, 43, 1462, '2021-07-21 13:36:48', '2021-07-21 13:36:48', NULL),
(107, 7, 78, 34, 43, 1462, '2021-08-03 05:16:53', '2021-08-03 14:37:25', NULL),
(108, 29, 78, 34, 43, 1462, '2021-08-03 05:16:53', '2021-08-03 14:37:31', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(9) NOT NULL,
  `setting` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting`, `value`, `deleted_at`) VALUES
(1, 'incometax', '17', NULL),
(2, 'logo', 'http://omnibiz.com/public/images/newlogo2023.png', NULL),
(3, 'sitename', 'Monalisa Biz', NULL),
(4, 'gst', '5', NULL),
(7, 'sale', '10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subscription`
--

CREATE TABLE `subscription` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `next_payment_date` timestamp NULL DEFAULT NULL,
  `last_payment_date` timestamp NULL DEFAULT NULL,
  `last_paid_amount` int(5) NOT NULL,
  `type_of_subscription` enum('yearly','monthly') COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sub_modules`
--

CREATE TABLE `sub_modules` (
  `id` int(11) NOT NULL,
  `name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `module_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sub_modules`
--

INSERT INTO `sub_modules` (`id`, `name`, `parent`, `module_id`) VALUES
(1, 'Client', NULL, 1),
(2, 'Department', NULL, 1),
(3, 'Position', NULL, 1),
(4, 'Employee Management', NULL, 1),
(5, 'Sales Management', NULL, 2),
(6, 'Purchase Management', NULL, 2),
(7, 'Inventory Management', NULL, 2),
(8, 'Products', 7, 2),
(9, 'Category', 7, 2),
(10, 'Quotations', NULL, 2),
(11, 'Inventory Management', NULL, 3),
(12, 'Products', 11, 3),
(13, 'Category', 11, 3),
(14, 'User', NULL, 6),
(15, 'Setting', NULL, 6);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `email` varchar(191) NOT NULL,
  `username` varchar(80) NOT NULL,
  `password` varchar(512) NOT NULL,
  `user_role` enum('super admin','admin','client','manager','accountant','employee') NOT NULL,
  `rights` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `client_id`, `employee_id`, `email`, `username`, `password`, `user_role`, `rights`, `created_by`, `updated_by`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, NULL, 'admin@omnibiz.com', 'admin', '$2y$10$FLPSYJNo92ZYmIggxfJKSudh2H.4MBQXUv889jVh6HV4x.daq9w/6', 'admin', NULL, NULL, 1, 'DQlpPZL4DkxFJI05jqIPe8R9ZB6OUZZDyb8jJ5XRviwrVdQLdt7MWzdDA3Wy', '2021-04-28 15:45:25', '2021-09-02 18:14:21', NULL),
(2, 1, NULL, 'omnibiz878@gmail.com', 'client', '$2y$10$FLPSYJNo92ZYmIggxfJKSudh2H.4MBQXUv889jVh6HV4x.daq9w/6', 'client', NULL, NULL, NULL, 'xTik8IOyoAe3g2krL0HgeAap05XxZMwPSNjBKUnY2G1k9ccp973JqLn4Uk4f', '2021-05-11 06:01:29', '2021-08-19 18:55:51', NULL),
(3, 1, 1, 'manager@omnibiz.com', 'manager', '$2y$10$FLPSYJNo92ZYmIggxfJKSudh2H.4MBQXUv889jVh6HV4x.daq9w/6', 'manager', NULL, NULL, 1, 'qpNuJVNmfk4jWaCZIJ0LnHieKLxmWPHCyuwhnrT4zctB5y6x7hrvQQE3qrHD', '2021-05-11 06:04:24', '2021-08-19 18:58:26', NULL),
(5, 1, 2, 'accountant@omnibiz.com', 'accountant', '$2y$10$FLPSYJNo92ZYmIggxfJKSudh2H.4MBQXUv889jVh6HV4x.daq9w/6', 'accountant', NULL, NULL, 1, NULL, '2021-05-25 20:03:43', NULL, NULL),
(6, 1, 3, 'employee@omnibiz.com', 'employee', '$2y$10$FLPSYJNo92ZYmIggxfJKSudh2H.4MBQXUv889jVh6HV4x.daq9w/6', 'employee', NULL, NULL, 3, 'o7GrKq4wVxPllE3cfUyTEaaN83BGe5oFLsjrlxrpahZvxlNQpSudMOSaCQgp', '2021-05-25 20:03:43', '2021-08-19 18:58:38', NULL),
(11, 3, NULL, 'anumsaeed28@gmail.com', 'testing1233', '$2y$10$kAtabF7J5xrmlbX.ig73QerJNsrU95/HLmgAlhcq4B4j7WSwrl9He', 'client', NULL, 1, 1, NULL, '2021-05-27 13:25:47', '2021-08-19 17:44:13', NULL),
(12, 1, 4, 'faizanmanager@omnibiz.com', 'faizan12', '$2y$10$dOjoLHCMi/1TiuOnQ7TrtOkaZxDd/8dP17kBDT1ZcXMDdwQx0OeYO', 'manager', NULL, 1, 1, NULL, '2021-06-08 11:50:12', NULL, NULL),
(13, 2, NULL, 'anued286@gmail.com', 'ano232', '$2y$10$PsIHSNel1AAaYPofVqdH/uY0JOSrB7T6bHJymm7cmx6oD/RFJwuoO', 'client', NULL, 1, 1, NULL, '2021-06-12 09:36:10', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_department`
--
ALTER TABLE `client_department`
  ADD PRIMARY KEY (`client_id`,`department_id`),
  ADD KEY `department_id_foreign_key` (`department_id`),
  ADD KEY `client_id_2` (`client_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clientidforeignkey` (`client_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `position_id_foreign_key` (`position_id`),
  ADD KEY `CF` (`client_id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employeeid_fk` (`employee_id`),
  ADD KEY `clientid_fk` (`client_id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id_fk_iinvoice` (`product_id`),
  ADD KEY `invoice_id_fkkk` (`invoice_id`);

--
-- Indexes for table `job_positions`
--
ALTER TABLE `job_positions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dept_id_foreign_key` (`department_id`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_items_to_module_id` (`module_id`),
  ADD KEY `menu_items_to_sub_module_id` (`sub_module_id`),
  ADD KEY `menu_items_to_parent` (`parent`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `module_assignment`
--
ALTER TABLE `module_assignment`
  ADD KEY `module_assignment_to_user_id` (`user_id`),
  ADD KEY `module_assignment_to_sub_module_id` (`sub_module_id`),
  ADD KEY `module_assignment_to_module_id` (`module_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_client_id_foreign_key` (`client_id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_clientid_foreign_key` (`client_id`);

--
-- Indexes for table `product_categories_assigned`
--
ALTER TABLE `product_categories_assigned`
  ADD PRIMARY KEY (`category_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Client_fk` (`client_id`),
  ADD KEY `employeeidforeign_key` (`employee_id`),
  ADD KEY `invoice_id_fkk` (`invoice_id`),
  ADD KEY `quotation_fk` (`quotation_id`);

--
-- Indexes for table `purchase_order_line`
--
ALTER TABLE `purchase_order_line`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_foreign_key` (`product_id`),
  ADD KEY `purchase_id_fk` (`purchase_id`);

--
-- Indexes for table `quotations`
--
ALTER TABLE `quotations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_fk` (`employee_id`),
  ADD KEY `client_id_fk` (`client_id`);

--
-- Indexes for table `quotation_items`
--
ALTER TABLE `quotation_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `productid_foreign_key` (`product_id`),
  ADD KEY `quotation_id` (`quotation_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_orders`
--
ALTER TABLE `sale_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_dforeign_key` (`client_id`),
  ADD KEY `employee_id_fk` (`employee_id`),
  ADD KEY `quotationid_fk` (`quotation_id`),
  ADD KEY `invoice_id_fk` (`invoice_id`);

--
-- Indexes for table `sale_order_line`
--
ALTER TABLE `sale_order_line`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_id` (`sale_id`),
  ADD KEY `product_id_fk` (`product_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting` (`setting`);

--
-- Indexes for table `subscription`
--
ALTER TABLE `subscription`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_modules`
--
ALTER TABLE `sub_modules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sub_module_to_module_id` (`module_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id_foreign_key` (`client_id`),
  ADD KEY `employee_id_foreign_key` (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `job_positions`
--
ALTER TABLE `job_positions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `purchase_order_line`
--
ALTER TABLE `purchase_order_line`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `quotations`
--
ALTER TABLE `quotations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `quotation_items`
--
ALTER TABLE `quotation_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sale_orders`
--
ALTER TABLE `sale_orders`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `sale_order_line`
--
ALTER TABLE `sale_order_line`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `subscription`
--
ALTER TABLE `subscription`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sub_modules`
--
ALTER TABLE `sub_modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `client_department`
--
ALTER TABLE `client_department`
  ADD CONSTRAINT `client_id_foreignkey` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `department_id_foreign_key` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `companies`
--
ALTER TABLE `companies`
  ADD CONSTRAINT `clientidforeignkey` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `CF` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `position_id_foreign_key` FOREIGN KEY (`position_id`) REFERENCES `job_positions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `clientid_fk` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employeeid_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_id_fkkk` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `invoice_id_foreign_key` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`),
  ADD CONSTRAINT `product_id_fk_iinvoice` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `job_positions`
--
ALTER TABLE `job_positions`
  ADD CONSTRAINT `dept_id_foreign_key` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `menu_items_to_module_id` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `menu_items_to_parent` FOREIGN KEY (`parent`) REFERENCES `menu_items` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `menu_items_to_sub_module_id` FOREIGN KEY (`sub_module_id`) REFERENCES `sub_modules` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `module_assignment`
--
ALTER TABLE `module_assignment`
  ADD CONSTRAINT `module_assignment_to_module_id` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `module_assignment_to_sub_module_id` FOREIGN KEY (`sub_module_id`) REFERENCES `sub_modules` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `module_assignment_to_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `product_client_id_foreign_key` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD CONSTRAINT `product_category_client_id_foreign_key` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_categories_assigned`
--
ALTER TABLE `product_categories_assigned`
  ADD CONSTRAINT `category_id_foreign_key` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `Client_fk` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employeeidforeign_key` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `invoice_id_fkk` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `quotation_fk` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `purchase_order_line`
--
ALTER TABLE `purchase_order_line`
  ADD CONSTRAINT `product_foreign_key` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `purchase_id_fk` FOREIGN KEY (`purchase_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quotations`
--
ALTER TABLE `quotations`
  ADD CONSTRAINT `client_id_fk` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `quotation_items`
--
ALTER TABLE `quotation_items`
  ADD CONSTRAINT `productid_foreign_key` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `quotation_id` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sale_orders`
--
ALTER TABLE `sale_orders`
  ADD CONSTRAINT `client_dforeign_key` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employee_id_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `invoice_id_fk` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `quotationid_fk` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `sale_order_line`
--
ALTER TABLE `sale_order_line`
  ADD CONSTRAINT `product_id_fk` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `sale_id` FOREIGN KEY (`sale_id`) REFERENCES `sale_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sub_modules`
--
ALTER TABLE `sub_modules`
  ADD CONSTRAINT `sub_module_to_module_id` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `client_id_foreign_key` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `employee_id_foreign_key` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
