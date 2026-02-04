<?php
/**
 * Router Sınıfı
 */

namespace App\Core;

class Router
{
    private array $routes = [];

    /**
     * Route ekle
     */
    public function add(string $method, string $path, string $controller, string $action): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }

    /**
     * GET route
     */
    public function get(string $path, string $controller, string $action): void
    {
        $this->add('GET', $path, $controller, $action);
    }

    /**
     * POST route
     */
    public function post(string $path, string $controller, string $action): void
    {
        $this->add('POST', $path, $controller, $action);
    }

    /**
     * Route'ları çalıştır
     */
    public function dispatch(): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $requestUri = rtrim($requestUri, '/') ?: '/';

        $matches = [];

        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }

            if ($this->matchRoute($route['path'], $requestUri)) {
                $specificity = $this->calculateSpecificity($route['path']);
                $matches[] = [
                    'route' => $route,
                    'specificity' => $specificity
                ];
            }
        }

        if (!empty($matches)) {
            usort($matches, function($a, $b) {
                return $b['specificity'] <=> $a['specificity'];
            });
            $this->executeRoute($matches[0]['route']);
                return;
            }

        http_response_code(404);
        echo "404 - Sayfa Bulunamadı";
    }

    /**
     * Route spesifikliğini hesapla (daha yüksek = daha spesifik)
     */
    private function calculateSpecificity(string $routePath): int
    {
        $specificity = 0;
        $segments = explode('/', trim($routePath, '/'));
        
        foreach ($segments as $segment) {
            if (strpos($segment, '{') === false) {
                $specificity += 100;
            } else {
                $specificity += 1;
            }
        }
        
        return $specificity;
    }

    /**
     * Route eşleşmesini kontrol et
     */
    private function matchRoute(string $routePath, string $requestUri): bool
    {
        $routePath = rtrim($routePath, '/') ?: '/';
        
        // Parametre desteği: {id} gibi parametreleri regex'e çevir
        $pattern = preg_replace('/\{(\w+)\}/', '([^/]+)', $routePath);
        $pattern = '#^' . $pattern . '$#';
        
        return (bool) preg_match($pattern, $requestUri);
    }

    /**
     * Route parametrelerini çıkar
     */
    private function extractParams(string $routePath, string $requestUri): array
    {
        $params = [];
        $routePath = rtrim($routePath, '/') ?: '/';
        
        // Parametre isimlerini bul
        preg_match_all('/\{(\w+)\}/', $routePath, $paramNames);
        
        if (!empty($paramNames[1])) {
            // Pattern oluştur ve değerleri çıkar
            $pattern = preg_replace('/\{(\w+)\}/', '([^/]+)', $routePath);
            $pattern = '#^' . $pattern . '$#';
            
            preg_match($pattern, $requestUri, $matches);
            
            // İlk match tüm string, sonrakiler parametreler
            array_shift($matches);
            
            foreach ($paramNames[1] as $index => $paramName) {
                if (isset($matches[$index])) {
                    $params[$paramName] = $matches[$index];
                }
            }
        }
        
        return $params;
    }

    /**
     * Route'u çalıştır
     */
    private function executeRoute(array $route): void
    {
        $controllerClass = "App\\Controllers\\{$route['controller']}";
        
        if (!class_exists($controllerClass)) {
            throw new \Exception("Controller bulunamadı: {$controllerClass}");
        }

        $controller = new $controllerClass();
        $action = $route['action'];

        if (!method_exists($controller, $action)) {
            throw new \Exception("Action bulunamadı: {$action}");
        }

        // Parametreleri çıkar ve controller'a gönder
        $requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $requestUri = rtrim($requestUri, '/') ?: '/';
        $params = $this->extractParams($route['path'], $requestUri);
        
        // Parametreleri controller metoduna gönder
        if (!empty($params)) {
            $controller->$action($params);
        } else {
        $controller->$action();
        }
    }
}

