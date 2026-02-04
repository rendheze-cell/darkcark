<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../autoload.php';
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/Core/Controller.php';
require_once __DIR__ . '/Models/AdminUser.php';

session_start();
date_default_timezone_set('Europe/Helsinki');

$requestUri = $_GET['route'] ?? '';

if (empty($requestUri)) {
    $fullUri = parse_url($_SERVER['REQUEST_URI'] ?? '/admin', PHP_URL_PATH);
    
    if (strpos($fullUri, '/admin') === 0) {
        $requestUri = substr($fullUri, 6);
    } else {
        $requestUri = $fullUri;
    }
    
    $requestUri = str_replace('index.php', '', $requestUri);
    $requestUri = str_replace('//', '/', $requestUri);
    $requestUri = trim($requestUri, '/');
}

$requestUri = empty($requestUri) ? '/' : '/' . $requestUri;

$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Route mapping
$routes = [
    'GET:/' => ['AuthController', 'login'],
    'POST:/login' => ['AuthController', 'login'],
    'GET:/login' => ['AuthController', 'login'],
    'GET:/logout' => ['AuthController', 'logout'],
    'GET:/dashboard' => ['DashboardController', 'index'],
    'GET:/users' => ['UserController', 'index'],
    'POST:/users/redirect' => ['UserController', 'sendRedirect'],
    'GET:/users/get-page' => ['UserController', 'getPage'],
    'GET:/users/get-user' => ['UserController', 'getUser'],
    'GET:/banks' => ['BankController', 'index'],
    'POST:/banks/save' => ['BankController', 'save'],
    'POST:/banks/delete' => ['BankController', 'delete'],
    'GET:/settings' => ['SettingsController', 'index'],
    'POST:/settings/save' => ['SettingsController', 'save'],
];

$routeKey = $requestMethod . ':' . $requestUri;

if (isset($_GET['debug'])) {
    echo "<pre>";
    echo "Request URI: " . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "\n";
    echo "Parsed URI: " . $requestUri . "\n";
    echo "Route Key: " . $routeKey . "\n";
    echo "Available Routes: " . implode(', ', array_keys($routes)) . "\n";
    echo "</pre>";
}

if (isset($routes[$routeKey])) {
    [$controllerName, $action] = $routes[$routeKey];
    $controllerClass = "Admin\\Controllers\\{$controllerName}";
    
    $controllerFile = __DIR__ . '/Controllers/' . $controllerName . '.php';
    if (file_exists($controllerFile)) {
        require_once $controllerFile;
    } else {
        http_response_code(500);
        die("Controller dosyası bulunamadı: {$controllerFile}");
    }
    
    if (!class_exists($controllerClass)) {
        http_response_code(500);
        die("Controller sınıfı bulunamadı: {$controllerClass}");
    }
    
    $controller = new $controllerClass();
    
    if (!method_exists($controller, $action)) {
        http_response_code(500);
        die("Method bulunamadı: {$controllerClass}::{$action}");
    }
    
    $controller->$action();
    exit;
}

http_response_code(404);
echo "404 - Sayfa Bulunamadı<br>";
echo "Aranan Route: " . htmlspecialchars($routeKey) . "<br>";
echo "Mevcut Route'lar: " . implode(', ', array_keys($routes));

