<?php
/**
 * Admin Auth Controller
 */

namespace Admin\Controllers;

use Admin\Core\Controller;
use Admin\Models\AdminUser;

class AuthController extends Controller
{
    protected bool $skipAuth = true;

    /**
     * Login sayfası
     */
    public function login(): void
    {
        // Session zaten admin/index.php'de başlatıldı
        
        // Zaten giriş yapmışsa dashboard'a yönlendir
        if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
            $this->redirect('/admin/dashboard');
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                $error = 'Kullanıcı adı ve şifre gereklidir.';
            } else {
                $adminModel = new AdminUser();
                $admin = $adminModel->login($username, $password);

                if ($admin) {
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_username'] = $admin['username'];
                    $this->redirect('/admin/dashboard');
                } else {
                    $error = 'Kullanıcı adı veya şifre hatalı.';
                }
            }
        }

        $this->view('auth/login', ['error' => $error]);
    }

    /**
     * Logout
     */
    public function logout(): void
    {
        // Session zaten admin/index.php'de başlatıldı
        session_destroy();
        $this->redirect('/admin/login');
    }
}

