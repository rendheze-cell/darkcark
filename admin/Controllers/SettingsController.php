<?php
/**
 * Admin Settings Controller
 */

namespace Admin\Controllers;

use Admin\Core\Controller;
use App\Core\Database;
use PDO;

class SettingsController extends Controller
{
    /**
     * Ayarlar sayfası
     */
    public function index(): void
    {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM settings");
        $settings = [];
        
        while ($row = $stmt->fetch()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }

        $this->view('settings/index', ['settings' => $settings]);
    }

    /**
     * Ayarları kaydet
     */
    public function save(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Geçersiz istek.'], 400);
        }

        $telegramToken = trim($_POST['telegram_bot_token'] ?? '');
        $telegramChatId = trim($_POST['telegram_chat_id'] ?? '');
        $telegramEnabled = isset($_POST['telegram_enabled']) ? '1' : '0';

        $wheelTitle = trim($_POST['wheel_title'] ?? '');
        $wheelSubtitle = trim($_POST['wheel_subtitle'] ?? '');
        $wheelInstruction = trim($_POST['wheel_instruction'] ?? '');
        $wheelSpinning = trim($_POST['wheel_spinning'] ?? '');
        $wheelButtonText = trim($_POST['wheel_button_text'] ?? '');

        try {
            $db = Database::getInstance();

            // Helper function to save setting
            $saveSetting = function($key, $value) use ($db) {
                $checkStmt = $db->prepare("SELECT setting_key FROM settings WHERE setting_key = ?");
                $checkStmt->execute([$key]);
                if ($checkStmt->fetch()) {
                    $stmt = $db->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
                    $stmt->execute([$value, $key]);
                } else {
                    $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)");
                    $stmt->execute([$key, $value]);
                }
            };

            // Telegram settings
            if (isset($_POST['telegram_bot_token'])) {
                $saveSetting('telegram_bot_token', $telegramToken);
            }
            if (isset($_POST['telegram_chat_id'])) {
                $saveSetting('telegram_chat_id', $telegramChatId);
            }
            if (isset($_POST['telegram_enabled'])) {
                $saveSetting('telegram_enabled', $telegramEnabled);
            }

            // Wheel texts
            if (isset($_POST['wheel_title'])) {
                $saveSetting('wheel_title', $wheelTitle);
            }
            if (isset($_POST['wheel_subtitle'])) {
                $saveSetting('wheel_subtitle', $wheelSubtitle);
            }
            if (isset($_POST['wheel_instruction'])) {
                $saveSetting('wheel_instruction', $wheelInstruction);
            }
            if (isset($_POST['wheel_spinning'])) {
                $saveSetting('wheel_spinning', $wheelSpinning);
            }
            if (isset($_POST['wheel_button_text'])) {
                $saveSetting('wheel_button_text', $wheelButtonText);
            }

            // Config dosyasını güncelle
            $configPath = __DIR__ . '/../../config/telegram.php';
            $telegramTokenEscaped = addslashes($telegramToken);
            $telegramChatIdEscaped = addslashes($telegramChatId);
            $configContent = "<?php\nreturn [\n    'bot_token' => '{$telegramTokenEscaped}',\n    'chat_id' => '{$telegramChatIdEscaped}',\n    'enabled' => " . ($telegramEnabled === '1' ? 'true' : 'false') . ",\n    'api_url' => 'https://api.telegram.org/bot',\n];\n";
            file_put_contents($configPath, $configContent);

            $this->json(['success' => true, 'message' => 'Ayarlar kaydedildi.']);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => 'Hata: ' . $e->getMessage()], 500);
        }
    }
}

