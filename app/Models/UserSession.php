<?php
/**
 * User Session Model
 * Kullanıcı aktif sayfa takibi
 */

namespace App\Models;

use App\Core\Database;
use PDO;

class UserSession
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Kullanıcının aktif sayfasını güncelle
     */
    public function updateCurrentPage(int $userId, string $currentPage, ?string $pageTitle = null): bool
    {
        // Önce var mı kontrol et
        $checkStmt = $this->db->prepare("SELECT id FROM user_sessions WHERE user_id = ?");
        $checkStmt->execute([$userId]);
        $existing = $checkStmt->fetch();

        if ($existing) {
            // Güncelle
            $stmt = $this->db->prepare("
                UPDATE user_sessions 
                SET current_page = ?, page_title = ?, last_activity = NOW()
                WHERE user_id = ?
            ");
            return $stmt->execute([$currentPage, $pageTitle, $userId]);
        } else {
            // Yeni ekle
            $stmt = $this->db->prepare("
                INSERT INTO user_sessions (user_id, current_page, page_title) 
                VALUES (?, ?, ?)
            ");
            return $stmt->execute([$userId, $currentPage, $pageTitle]);
        }
    }

    /**
     * Kullanıcının aktif sayfasını getir
     */
    public function getCurrentPage(int $userId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM user_sessions 
            WHERE user_id = ? 
            ORDER BY last_activity DESC 
            LIMIT 1
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Tüm aktif kullanıcıların sayfalarını getir
     */
    public function getAllActivePages(): array
    {
        $stmt = $this->db->query("
            SELECT us.*, u.full_name, u.phone
            FROM user_sessions us
            INNER JOIN users u ON us.user_id = u.id
            WHERE us.last_activity > DATE_SUB(NOW(), INTERVAL 30 MINUTE)
            ORDER BY us.last_activity DESC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Admin panelden yönlendirme komutu kaydet
     */
    public function setRedirectCommand(int $userId, string $redirectTo): bool
    {
        // Önce var mı kontrol et
        $checkStmt = $this->db->prepare("SELECT id FROM user_sessions WHERE user_id = ?");
        $checkStmt->execute([$userId]);
        $existing = $checkStmt->fetch();

        if ($existing) {
            // Güncelle
            $stmt = $this->db->prepare("
                UPDATE user_sessions 
                SET redirect_to = ?, redirect_at = NOW()
                WHERE user_id = ?
            ");
            return $stmt->execute([$redirectTo, $userId]);
        } else {
            // Yeni ekle
            $stmt = $this->db->prepare("
                INSERT INTO user_sessions (user_id, current_page, redirect_to, redirect_at) 
                VALUES (?, '/', ?, NOW())
            ");
            return $stmt->execute([$userId, $redirectTo]);
        }
    }

    /**
     * Yönlendirme komutunu al ve sil
     */
    public function getAndClearRedirect(int $userId): ?string
    {
        $stmt = $this->db->prepare("
            SELECT redirect_to FROM user_sessions 
            WHERE user_id = ? AND redirect_to IS NOT NULL
        ");
        $stmt->execute([$userId]);
        $result = $stmt->fetch();

        if ($result && !empty($result['redirect_to'])) {
            // Komutu temizle
            $clearStmt = $this->db->prepare("
                UPDATE user_sessions 
                SET redirect_to = NULL, redirect_at = NULL
                WHERE user_id = ?
            ");
            $clearStmt->execute([$userId]);

            return $result['redirect_to'];
        }

        return null;
    }
}

