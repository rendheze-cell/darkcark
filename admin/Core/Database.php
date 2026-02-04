<?php
/**
 * Admin Database (aynı veritabanı bağlantısı)
 */

namespace Admin\Core;

require_once __DIR__ . '/../../../app/Core/Database.php';

class Database extends \App\Core\Database
{
    // Admin panel aynı veritabanını kullanır
}

