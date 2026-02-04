<?php
/**
 * Admin User Model
 */

namespace Admin\Models;

use App\Core\Database;
use PDO;

class AdminUser
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Admin kullanıcı girişi
     */
    public function login(string $username, string $password): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM admin_users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            // Son giriş zamanını güncelle
            $updateStmt = $this->db->prepare("UPDATE admin_users SET last_login = NOW() WHERE id = :id");
            $updateStmt->execute([':id' => $admin['id']]);
            
            return $admin;
        }

        return null;
    }
}

