<?php
/**
 * Bank Model
 */

namespace App\Models;

use App\Core\Database;
use PDO;

class Bank
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Aktif bankaları getir
     */
    public function getActive(): array
    {
        $stmt = $this->db->query("
            SELECT * FROM banks 
            WHERE is_active = 1 
            ORDER BY display_order ASC, name ASC
        ");

        return $stmt->fetchAll();
    }

    /**
     * ID ile banka getir
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM banks WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    /**
     * Tüm bankaları getir (Admin için)
     */
    public function getAll(): array
    {
        $stmt = $this->db->query("
            SELECT * FROM banks 
            ORDER BY display_order ASC, name ASC
        ");

        return $stmt->fetchAll();
    }

    /**
     * Banka oluştur
     */
    public function create(string $name, ?string $logo = null, int $displayOrder = 0, string $color = '#FF6B6B', ?string $wheelText = null): ?int
    {
        $stmt = $this->db->prepare("
            INSERT INTO banks (name, logo, display_order, color, wheel_text) 
            VALUES (:name, :logo, :display_order, :color, :wheel_text)
        ");

        $stmt->execute([
            ':name' => $name,
            ':logo' => $logo,
            ':display_order' => $displayOrder,
            ':color' => $color,
            ':wheel_text' => $wheelText
        ]);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Banka güncelle
     */
    public function update(int $id, array $data): bool
    {
        $fields = [];
        $params = [':id' => $id];

        foreach ($data as $key => $value) {
            $fields[] = "{$key} = :{$key}";
            $params[":{$key}"] = $value;
        }

        $sql = "UPDATE banks SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute($params);
    }

    /**
     * Banka sil
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM banks WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}

