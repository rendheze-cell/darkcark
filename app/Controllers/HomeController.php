<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\TelegramService;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->view('home/index');
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
        }

        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        if (empty($firstName) || empty($lastName) || empty($phone)) {
            $this->json([
                'success' => false,
                'message' => 'Lütfen tüm alanları doldurun.'
            ], 400);
        }

        $fullName = $firstName . ' ' . $lastName;

        $phone = preg_replace('/[^0-9+]/', '', $phone);

        if (strlen($phone) < 10) {
            $this->json([
                'success' => false,
                'message' => 'Geçerli bir telefon numarası girin.'
            ], 400);
        }

        try {
            $userModel = new User();
            $ipAddress = $this->getClientIp();
            $userId = $userModel->create($fullName, $phone, $ipAddress);

            if ($userId) {
                $telegram = new TelegramService();
                $telegram->notifyUserLogin($fullName, $phone, $ipAddress);

                $_SESSION['user_id'] = $userId;
                $_SESSION['user_name'] = $fullName;

                $this->json([
                    'success' => true,
                    'message' => 'Kirjautuminen onnistui!',
                    'redirect' => '/wheel'
                ]);
            } else {
                $this->json([
                'success' => false,
                'message' => 'Tapahtui virhe. Yritä uudelleen.'
                ], 500);
            }
        } catch (\Exception $e) {
            $this->json([
            'success' => false,
            'message' => 'Tapahtui virhe: ' . $e->getMessage()
            ], 500);
        }
    }
}

