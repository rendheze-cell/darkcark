<?php
/**
 * Veritabanı Bağlantı Sınıfı
 */

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;
    private static array $config = [];

    /**
     * Singleton pattern ile veritabanı bağlantısı
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            self::$config = require __DIR__ . '/../../config/database.php';
            
            try {
                $dsn = sprintf(
                    "mysql:host=%s;dbname=%s;charset=%s",
                    self::$config['host'],
                    self::$config['dbname'],
                    self::$config['charset']
                );

                self::$instance = new PDO(
                    $dsn,
                    self::$config['username'],
                    self::$config['password'],
                    self::$config['options']
                );
            } catch (PDOException $e) {
                throw new \Exception("Veritabanı bağlantı hatası: " . $e->getMessage());
            }
        }

        return self::$instance;
    }

    /**
     * Bağlantıyı kapat
     */
    public static function close(): void
    {
        self::$instance = null;
    }
}

