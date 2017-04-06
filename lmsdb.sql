-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 21, 2017 at 12:30 PM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lmsdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `borrowers`
--

CREATE TABLE `borrowers` (
  `id` int(10) UNSIGNED NOT NULL,
  `borrower_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `borrower_status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `borrower_last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `borrower_first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `borrower_middle_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `borrower_home_address` text COLLATE utf8_unicode_ci NOT NULL,
  `borrower_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `borrower_civil_status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `borrower_birth_date` date NOT NULL,
  `borrower_employment_date` date NOT NULL,
  `borrower_assignment_date` date NOT NULL,
  `borrower_salary_gross_pay` double NOT NULL,
  `borrower_monthly_expenses` double NOT NULL,
  `borrower_resignation_date` date DEFAULT NULL,
  `borrower_spouse_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `borrower_no_of_children` int(10) UNSIGNED DEFAULT NULL,
  `company_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `borrowers`
--

INSERT INTO `borrowers` (`id`, `borrower_type`, `borrower_status`, `borrower_last_name`, `borrower_first_name`, `borrower_middle_name`, `borrower_home_address`, `borrower_email`, `borrower_civil_status`, `borrower_birth_date`, `borrower_employment_date`, `borrower_assignment_date`, `borrower_salary_gross_pay`, `borrower_monthly_expenses`, `borrower_resignation_date`, `borrower_spouse_name`, `borrower_no_of_children`, `company_id`, `created_at`, `updated_at`) VALUES
(1, 'Regular', 'Active', 'Fernandez', 'Denimar', 'Fajardo', 'Valenzuela City 3S (Karuhatan Branch), Karuhatan, Philippines', 'fdenimar@gmail.com', 'Single', '1995-06-23', '2016-07-01', '2016-07-02', 0, 0, NULL, NULL, NULL, 2, '2016-12-11 22:10:42', '2016-12-11 22:10:42'),
(2, 'Regular', 'Active', 'De Mesa', 'Luke', NULL, 'Shaw Boulevard, Mandaluyong, Philippines', 'lukevincentdemesa@gmail.com', 'Single', '1995-06-23', '2016-12-21', '2016-12-25', 0, 0, NULL, NULL, NULL, 2, '2016-12-13 18:31:12', '2017-02-24 16:20:52'),
(3, 'Regular', 'Active', 'Dela Cruz', 'Juan', NULL, 'Camp Crame, Quezon CIty', 'jcdelacruz@gmail.com', 'Single', '1991-03-24', '2016-12-20', '2016-12-22', 0, 0, NULL, NULL, NULL, 2, '2017-01-02 01:40:44', '2017-01-02 01:40:44'),
(4, 'Regular', 'Active', 'Caelum', 'Noctis', NULL, 'Crown City, Ohio, United States of America', 'noctis@ffxv.net', 'Single', '1991-04-07', '2017-01-03', '2017-01-07', 0, 0, NULL, NULL, NULL, 2, '2017-02-01 18:16:32', '2017-02-01 18:16:32'),
(5, 'Regular', 'Active', 'Fernandez', 'Nanako', NULL, 'Tanada Valenzuela City', 'papavich@gmail.com', 'Single', '1967-12-29', '2014-12-20', '2015-01-03', 0, 0, NULL, NULL, NULL, 2, '2017-02-01 18:24:52', '2017-02-01 18:24:52'),
(6, 'Regular', 'Active', 'Sumitomo', 'Fushimii', NULL, '127 Kaligayahan St., Caloocan City', 'fsumitomo@gmail.com', 'Single', '1989-05-24', '2016-02-24', '2016-07-18', 15000, 6000, NULL, NULL, NULL, 1, '2017-02-24 16:37:53', '2017-02-24 16:39:08'),
(7, 'Regular', 'Active', 'Cancejo', 'Jaime', 'Estelle', '67 Kross Drive, Quezon City', 'ejcancejo@gmail.com', 'Single', '1989-02-11', '2010-01-02', '2010-01-07', 28500, 14500, NULL, NULL, NULL, 1, '2017-02-27 12:13:55', '2017-02-27 12:13:55'),
(8, 'Deployed', 'Active', 'Palomar', 'Ivy', 'Bemino', 'B210 area D purok 9 San Jose Del Monte Bulacan', 'na', 'Single', '1992-03-24', '2016-08-16', '2016-08-20', 13000, 1000, '2017-03-20', 'Na', NULL, 2, '2017-02-27 15:05:08', '2017-02-27 15:05:08'),
(9, 'Deployed', 'Active', 'Suarez', 'Dexter', 'Sanchez', 'B3 L1 Phase 6B San Jose Del Monte Bulacan', 'na', 'Single', '1990-06-14', '2016-08-16', '2016-08-20', 13000, 1000, '2017-03-20', 'Na', NULL, 2, '2017-02-27 15:42:59', '2017-02-27 15:42:59'),
(10, 'Deployed', 'Active', 'Revres', 'enitsirhc', 'otterrab', '148 Malvar St., Ayala Alabang Village, Muntinlup', 'smo168@gmail.com', 'Married', '1968-05-23', '2017-01-01', '2017-01-09', 20000, 5000, NULL, 'dlanor tonapar', 5, 1, '2017-02-28 08:50:02', '2017-02-28 08:50:02'),
(11, 'Deployed', 'Active', 'Gomez ', 'Alfred', 'Ilano', 'Phase 3 pkg 2 blk 54 no. 216 bagong silang Caloocan ', 'na', 'Single', '1994-07-09', '2016-08-16', '2016-08-20', 13000, 1000, '2017-03-20', 'Na', NULL, 2, '2017-02-28 11:05:08', '2017-02-28 11:05:08');

-- --------------------------------------------------------

--
-- Table structure for table `borrower_credentials`
--

CREATE TABLE `borrower_credentials` (
  `id` int(10) UNSIGNED NOT NULL,
  `loan_borrower_id` int(11) NOT NULL,
  `borrower_credential_filename` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `company_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `company_income_share` int(11) NOT NULL,
  `company_address` text COLLATE utf8_unicode_ci NOT NULL,
  `company_contact_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_notes` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `company_name`, `company_code`, `company_income_share`, `company_address`, `company_contact_no`, `company_email`, `company_website`, `company_notes`, `created_at`, `updated_at`) VALUES
(1, 'Moo Loans  Inc.', 'MLI', 100, '5/F Richville Tower, 1107 Alabang-Zapote Road, Muntinlupa, Philippines 1780', '9603830', 'mooloans@yahoo.com', NULL, NULL, '2016-12-11 21:36:15', '2017-02-10 12:27:19'),
(2, 'i-Promote People Enterprise Inc.', 'iPromote', 40, 'Unit 210 Jovan Condominium Bldg, 600 Shaw Boulevard Corner Samat St, Mandaluyong City', '5328818', 'lagc_1828@yahoo.com', 'http://ipromotepeople.com', NULL, '2017-02-21 13:01:22', '2017-02-21 13:02:15');

-- --------------------------------------------------------

--
-- Table structure for table `loan_applications`
--

CREATE TABLE `loan_applications` (
  `id` int(10) UNSIGNED NOT NULL,
  `loan_application_is_active` bit(1) NOT NULL DEFAULT b'1',
  `loan_application_amount` double NOT NULL,
  `loan_application_total_amount` double NOT NULL,
  `loan_application_interest` double NOT NULL,
  `loan_application_periodic_rate` double NOT NULL,
  `loan_application_purpose` text COLLATE utf8_unicode_ci NOT NULL,
  `loan_application_status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `loan_application_filing_fee` double NOT NULL,
  `loan_application_service_fee` double NOT NULL,
  `loan_application_remarks` text COLLATE utf8_unicode_ci,
  `loan_application_disbursement_date` date DEFAULT NULL,
  `loan_application_comaker_id1` int(11) NOT NULL,
  `loan_application_comaker_id2` int(11) NOT NULL,
  `loan_borrower_id` int(11) NOT NULL,
  `payment_term_id` int(11) NOT NULL,
  `loan_interest_id` int(11) NOT NULL,
  `payment_schedule_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loan_interests`
--

CREATE TABLE `loan_interests` (
  `id` int(10) UNSIGNED NOT NULL,
  `loan_interest_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `loan_interest_rate` float NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `loan_interests`
--

INSERT INTO `loan_interests` (`id`, `loan_interest_name`, `loan_interest_rate`, `created_at`, `updated_at`) VALUES
(1, '5 percent', 5, '2016-12-11 22:33:42', '2016-12-11 22:33:42'),
(2, '3 percent', 3, '2017-02-21 13:03:49', '2017-02-21 13:03:49'),
(3, '1.5 percent', 1.5, '2017-02-21 13:06:31', '2017-02-21 13:06:31'),
(4, '.585 percent', 0.585, '2017-02-21 13:06:54', '2017-02-21 13:06:54');

-- --------------------------------------------------------

--
-- Table structure for table `loan_payments`
--

CREATE TABLE `loan_payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `loan_application_id` int(11) NOT NULL,
  `loan_payment_amount` double NOT NULL,
  `loan_payment_count` int(11) NOT NULL,
  `remarks` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loan_payment_terms`
--

CREATE TABLE `loan_payment_terms` (
  `id` int(10) UNSIGNED NOT NULL,
  `loan_payment_term_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `loan_payment_term_no_of_months` int(10) UNSIGNED NOT NULL,
  `loan_payment_term_collection_cycle` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `loan_payment_terms`
--

INSERT INTO `loan_payment_terms` (`id`, `loan_payment_term_name`, `loan_payment_term_no_of_months`, `loan_payment_term_collection_cycle`, `created_at`, `updated_at`) VALUES
(2, '2 years', 24, 0, '2016-12-11 22:44:38', '2016-12-11 22:44:38'),
(3, '6 months', 6, 0, '2017-02-10 12:29:56', '2017-02-10 12:29:56'),
(4, '1 year', 12, 0, '2017-02-21 13:07:21', '2017-02-22 10:28:14');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(36, '2014_10_12_000000_create_users_table', 1),
(37, '2014_10_12_100000_create_password_resets_table', 1),
(38, '2016_05_10_130540_create_permission_tables', 1),
(39, '2016_12_05_065435_create_companies_table', 1),
(40, '2016_12_08_051046_create_borrowers_table', 1),
(41, '2016_12_09_064047_create_borrower_credentials_table', 1),
(42, '2016_12_09_064226_create_loan_interests_table', 1),
(43, '2016_12_09_064506_create_payment_terms_table', 1),
(44, '2016_12_09_064630_create_loan_applications_table', 1),
(45, '2016_12_09_065035_create_loan_payments_table', 1),
(46, '2016_12_09_082930_create_loan_payment_terms_table', 1),
(47, '2017_03_02_130302_create_payment_schedules_table', 2),
(48, '2017_03_17_163215_create_payment_collections_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_collections`
--

CREATE TABLE `payment_collections` (
  `id` int(10) UNSIGNED NOT NULL,
  `payment_collection_date` date NOT NULL,
  `payment_collection_amount` date NOT NULL,
  `loan_application_id` int(11) NOT NULL,
  `is_paid` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_schedules`
--

CREATE TABLE `payment_schedules` (
  `id` int(10) UNSIGNED NOT NULL,
  `payment_schedule_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payment_schedule_days_interval` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `payment_schedules`
--

INSERT INTO `payment_schedules` (`id`, `payment_schedule_name`, `payment_schedule_days_interval`, `created_at`, `updated_at`) VALUES
(1, 'Bi-Weekly', 15, '2017-03-08 15:32:46', '2017-03-08 15:32:46');

-- --------------------------------------------------------

--
-- Table structure for table `payment_terms`
--

CREATE TABLE `payment_terms` (
  `id` int(10) UNSIGNED NOT NULL,
  `payment_term_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payment_term_no_of_months` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `payment_terms`
--

INSERT INTO `payment_terms` (`id`, `payment_term_name`, `payment_term_no_of_months`, `created_at`, `updated_at`) VALUES
(1, '12 months', 12, '2017-01-17 16:45:17', '2017-01-17 16:45:17');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Encode All Master Entry', '2016-12-13 08:23:27', '2016-12-13 08:23:27'),
(2, 'Encode Company Master Entry', '2016-12-13 08:23:40', '2016-12-13 08:23:40'),
(3, 'Apply All Loan Application', '2016-12-13 08:24:03', '2016-12-13 08:24:03'),
(4, 'Apply Loan Application to assigned company', '2016-12-13 08:24:34', '2016-12-13 08:24:34'),
(5, 'Approve/Reject All Loan Applications', '2016-12-13 08:25:07', '2016-12-13 08:25:16'),
(6, 'Approve/Reject Loan Applications to assigned company', '2016-12-13 08:25:37', '2016-12-13 08:25:37'),
(7, 'Process Loan Payment to All', '2016-12-13 08:26:07', '2016-12-13 08:26:07'),
(8, 'Process Loan Payment to Assigned Company', '2016-12-13 08:26:21', '2016-12-13 08:26:21'),
(9, 'View Loan Application Status', '2016-12-13 08:26:48', '2016-12-13 08:26:48'),
(10, 'View Reports', '2016-12-13 08:27:05', '2016-12-13 08:27:05');

-- --------------------------------------------------------

--
-- Table structure for table `permission_roles`
--

CREATE TABLE `permission_roles` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `permission_roles`
--

INSERT INTO `permission_roles` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 2),
(3, 1),
(4, 2),
(5, 1),
(6, 1),
(6, 3),
(7, 1),
(8, 2),
(9, 1),
(9, 2),
(9, 3),
(9, 4),
(10, 1),
(10, 2),
(10, 3),
(10, 4);

-- --------------------------------------------------------

--
-- Table structure for table `permission_users`
--

CREATE TABLE `permission_users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `permission_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `permission_users`
--

INSERT INTO `permission_users` (`user_id`, `permission_id`) VALUES
(2, 8);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Super_Administrator', '2016-12-13 08:21:33', '2017-03-02 05:48:29'),
(2, 'Encoder', '2016-12-13 08:21:43', '2016-12-13 08:21:43'),
(3, 'Approving_Body', '2016-12-13 08:22:02', '2017-03-02 05:48:43'),
(4, 'Viewing', '2016-12-13 08:22:49', '2016-12-13 08:22:49');

-- --------------------------------------------------------

--
-- Table structure for table `role_users`
--

CREATE TABLE `role_users` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `role_users`
--

INSERT INTO `role_users` (`role_id`, `user_id`) VALUES
(1, 1),
(2, 3),
(3, 2),
(4, 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_id` int(11) NOT NULL,
  `notes` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `company_id`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'Ron Rapanot', 'ronaldrapanot@yahoo.com', '$2y$10$7XLCinSl61mOHsiN5yUPTOX7xKLn9RIcQz66L.LZvKI5ZLl5.qRfq', 'ETawxnKlAOuc4ugKorqGpZsRw5vSsFjA1B3FYdKv9rD27cmTYwXjoTS90Man', 1, NULL, '2016-12-11 21:33:09', '2017-03-02 05:51:15'),
(2, 'Micah Castillo', 'castillo.mics@gmail.com', '$2y$10$wnw1EWbah0hLgLeecMOjv.ee.Id6B319WoVj5xl8dmKvEjsBsh7Zi', 'zKvQgYqzezaNxlrOO9YsudtwOsugXkYgjTaWfUJkMyNqBcrS1NXr3HE4GedN', 2, NULL, '2017-02-21 11:19:59', '2017-03-01 16:33:16'),
(3, 'Luke Vincent de Mesa', 'luke@skywalker.com', '$2y$10$zJ4sdLj1cC4GnYTr3kaIQeccsJcsxIsRCWc7/zrc.yW8EZzLhb8xS', '4W5AivN5RILvtODglcG9ic8oJfBS5Wxl0kiThBVVuHOXvQ1sCmGkYa7MsQDB', 2, NULL, '2017-02-21 13:13:13', '2017-03-01 10:24:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `borrowers`
--
ALTER TABLE `borrowers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `borrower_credentials`
--
ALTER TABLE `borrower_credentials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_applications`
--
ALTER TABLE `loan_applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_interests`
--
ALTER TABLE `loan_interests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_payments`
--
ALTER TABLE `loan_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_payment_terms`
--
ALTER TABLE `loan_payment_terms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`),
  ADD KEY `password_resets_token_index` (`token`);

--
-- Indexes for table `payment_collections`
--
ALTER TABLE `payment_collections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_schedules`
--
ALTER TABLE `payment_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_terms`
--
ALTER TABLE `payment_terms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_unique` (`name`);

--
-- Indexes for table `permission_roles`
--
ALTER TABLE `permission_roles`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `permission_roles_role_id_foreign` (`role_id`);

--
-- Indexes for table `permission_users`
--
ALTER TABLE `permission_users`
  ADD PRIMARY KEY (`user_id`,`permission_id`),
  ADD KEY `permission_users_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `role_users`
--
ALTER TABLE `role_users`
  ADD PRIMARY KEY (`role_id`,`user_id`),
  ADD KEY `role_users_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `borrowers`
--
ALTER TABLE `borrowers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `borrower_credentials`
--
ALTER TABLE `borrower_credentials`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `loan_applications`
--
ALTER TABLE `loan_applications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `loan_interests`
--
ALTER TABLE `loan_interests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `loan_payments`
--
ALTER TABLE `loan_payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `loan_payment_terms`
--
ALTER TABLE `loan_payment_terms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
--
-- AUTO_INCREMENT for table `payment_collections`
--
ALTER TABLE `payment_collections`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payment_schedules`
--
ALTER TABLE `payment_schedules`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `payment_terms`
--
ALTER TABLE `payment_terms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `permission_roles`
--
ALTER TABLE `permission_roles`
  ADD CONSTRAINT `permission_roles_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `permission_users`
--
ALTER TABLE `permission_users`
  ADD CONSTRAINT `permission_users_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_users`
--
ALTER TABLE `role_users`
  ADD CONSTRAINT `role_users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
