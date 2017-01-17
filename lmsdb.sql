-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 03, 2017 at 08:34 AM
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

INSERT INTO `borrowers` (`id`, `borrower_type`, `borrower_status`, `borrower_last_name`, `borrower_first_name`, `borrower_middle_name`, `borrower_home_address`, `borrower_email`, `borrower_civil_status`, `borrower_birth_date`, `borrower_employment_date`, `borrower_assignment_date`, `borrower_resignation_date`, `borrower_spouse_name`, `borrower_no_of_children`, `company_id`, `created_at`, `updated_at`) VALUES
(1, 'Regular', 'Active', 'Fernandez', 'Denimar', 'Fajardo', 'Valenzuela City 3S (Karuhatan Branch), Karuhatan, Philippines', 'fdenimar@gmail.com', 'Single', '1995-06-23', '2016-07-01', '2016-07-02', NULL, NULL, NULL, 2, '2016-12-11 22:10:42', '2016-12-11 22:10:42'),
(2, 'Regular', 'Active', 'De Mesa', 'Luke', NULL, 'Shaw Boulevard, Mandaluyong, Philippines', 'lukevincentdemesa@gmail.com', 'Single', '1995-06-21', '2016-12-21', '2016-12-25', NULL, NULL, NULL, 2, '2016-12-13 18:31:12', '2016-12-13 18:31:12'),
(3, 'Regular', 'Active', 'Dela Cruz', 'Juan', NULL, 'Camp Crame, Quezon CIty', 'jcdelacruz@gmail.com', 'Single', '1991-03-24', '2016-12-20', '2016-12-22', NULL, NULL, NULL, 2, '2017-01-02 01:40:44', '2017-01-02 01:40:44');

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
  `company_address` text COLLATE utf8_unicode_ci NOT NULL,
  `company_contact_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `company_name`, `company_code`, `company_address`, `company_contact_no`, `company_email`, `company_website`, `created_at`, `updated_at`) VALUES
(1, 'Moo Loans, Inc.', 'MLI', 'Alabang-Zapote Road, Muntinlupa, Philippines', NULL, NULL, NULL, '2016-12-11 21:36:15', '2016-12-11 21:36:15'),
(2, 'i-PROMOTE People Enterprise, Inc.', 'IPPEI', 'Shaw Boulevard, Mandaluyong, Philippines', NULL, NULL, NULL, '2016-12-11 21:36:31', '2016-12-11 21:36:31');

-- --------------------------------------------------------

--
-- Table structure for table `loan_applications`
--

CREATE TABLE `loan_applications` (
  `id` int(10) UNSIGNED NOT NULL,
  `loan_application_amount` double NOT NULL,
  `loan_application_purpose` text COLLATE utf8_unicode_ci NOT NULL,
  `loan_application_status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `loan_application_filing_fee` double NOT NULL,
  `loan_application_service_fee` double NOT NULL,
  `loan_application_remarks` text COLLATE utf8_unicode_ci,
  `loan_application_comaker_id1` int(11) NOT NULL,
  `loan_application_comaker_id2` int(11) NOT NULL,
  `loan_borrower_id` int(11) NOT NULL,
  `payment_term_id` int(11) NOT NULL,
  `loan_interest_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `loan_applications`
--

INSERT INTO `loan_applications` (`id`, `loan_application_amount`, `loan_application_purpose`, `loan_application_status`, `loan_application_filing_fee`, `loan_application_service_fee`, `loan_application_remarks`, `loan_application_comaker_id1`, `loan_application_comaker_id2`, `loan_borrower_id`, `payment_term_id`, `loan_interest_id`, `created_at`, `updated_at`) VALUES
(1, 25000, 'For my niece''s birthday', 'Declined', 100, 100, 'Too much loan amount', 2, 3, 1, 1, 1, '2017-01-02 01:48:23', '2017-01-02 23:26:36');

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
(1, '1 percent', 1, '2016-12-11 22:33:42', '2016-12-11 22:33:42');

-- --------------------------------------------------------

--
-- Table structure for table `loan_payments`
--

CREATE TABLE `loan_payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `loan_application_id` int(11) NOT NULL,
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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `loan_payment_terms`
--

INSERT INTO `loan_payment_terms` (`id`, `loan_payment_term_name`, `loan_payment_term_no_of_months`, `created_at`, `updated_at`) VALUES
(1, '1 year', 12, '2016-12-11 22:44:30', '2016-12-11 22:44:30'),
(2, '2 years', 24, '2016-12-11 22:44:38', '2016-12-11 22:44:38');

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
(46, '2016_12_09_082930_create_loan_payment_terms_table', 1);

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
-- Table structure for table `payment_terms`
--

CREATE TABLE `payment_terms` (
  `id` int(10) UNSIGNED NOT NULL,
  `payment_term_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payment_term_no_of_months` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
(1, 'Super Administrator', '2016-12-13 08:21:33', '2016-12-13 08:21:33'),
(2, 'Encoder', '2016-12-13 08:21:43', '2016-12-13 08:21:43'),
(3, 'Approving Body', '2016-12-13 08:22:02', '2016-12-13 08:22:02'),
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
(1, 1);

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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Ron Rapanot', 'ronaldrapanot@yahoo.com', '$2y$10$7XLCinSl61mOHsiN5yUPTOX7xKLn9RIcQz66L.LZvKI5ZLl5.qRfq', 'YyKJUwecfx9QmcLqxvK9ByFJxHBOZlib0eE48RuBA7DzilwqK5F9QFQ40cTO', '2016-12-11 21:33:09', '2017-01-02 01:36:15');

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `loan_interests`
--
ALTER TABLE `loan_interests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `loan_payments`
--
ALTER TABLE `loan_payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `loan_payment_terms`
--
ALTER TABLE `loan_payment_terms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT for table `payment_terms`
--
ALTER TABLE `payment_terms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
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
