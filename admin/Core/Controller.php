<?php

namespace Admin\Core;

use PDO;

class Controller
{
    /** @var PDO */
    protected $db;
    protected bool $skipAuth = false;

    public function __construct()
    {
        if (!$this->skipAuth) {
            $this->checkAuth();
        }
        $this->db = \App\Core\Database::getInstance();
    }

    protected function checkAuth(): void
    {
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            $requestUri = $_SERVER['REQUEST_URI'] ?? '';
            if (strpos($requestUri, '/admin/login') === false && strpos($requestUri, '/admin/logout') === false) {
                header('Location: /admin/login');
                exit;
            }
        }
    }

    protected function view(string $view, array $data = []): void
    {
        extract($data);
        $viewFile = __DIR__ . '/../Views/' . $view . '.php';
        
        if (!file_exists($viewFile)) {
            throw new \Exception("View dosyası bulunamadı: {$view}");
        }

        require $viewFile;
    }

    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }
}

