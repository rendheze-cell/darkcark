<?php
/**
 * Basit Autoloader
 */

spl_autoload_register(function ($class) {
    // Namespace'i kaldır
    $class = str_replace('\\', '/', $class);
    
    // App namespace
    if (strpos($class, 'App/') === 0) {
        $file = __DIR__ . '/' . str_replace('App/', 'app/', $class) . '.php';
        if (file_exists($file)) {
            require $file;
            return;
        }
    }
    
    // Admin namespace
    if (strpos($class, 'Admin/') === 0) {
        // Admin/Controllers/AuthController -> admin/Controllers/AuthController.php
        $classPath = str_replace('Admin/', 'admin/', $class);
        
        // Parçalara ayır
        $parts = explode('/', $classPath);
        
        // Denenecek yollar
        $paths = [];
        
        // 1. Direkt yol (admin/Controllers/AuthController.php)
        $paths[] = __DIR__ . '/' . $classPath . '.php';
        
        // 2. İkinci kısmı büyük harfe çevir (admin/Controllers/AuthController.php)
        if (count($parts) >= 2) {
            $parts[1] = ucfirst(strtolower($parts[1])); // controllers -> Controllers
            $paths[] = __DIR__ . '/' . implode('/', $parts) . '.php';
        }
        
        // 3. Tüm parçaları büyük harfe çevir (sadece klasör isimleri)
        $parts = explode('/', $classPath);
        foreach ($parts as $i => $part) {
            if ($i > 0 && $i < count($parts) - 1) { // Sadece klasör isimleri (Controllers, Models, Core)
                $parts[$i] = ucfirst(strtolower($part));
            }
        }
        $paths[] = __DIR__ . '/' . implode('/', $parts) . '.php';
        
        // Her yolu dene
        foreach ($paths as $file) {
            if (file_exists($file)) {
                require $file;
                return;
            }
        }
    }
});

