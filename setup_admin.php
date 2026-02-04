<?php

$isWeb = isset($_SERVER['HTTP_HOST']);

if ($isWeb) {
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html lang="tr"><head><meta charset="UTF-8"><title>Admin Kurulum</title>';
    echo '<style>body{font-family:Arial,sans-serif;max-width:600px;margin:50px auto;padding:20px;background:#f5f5f5;}';
    echo '.success{background:#d4edda;border:1px solid #c3e6cb;color:#155724;padding:15px;border-radius:5px;margin:10px 0;}';
    echo '.error{background:#f8d7da;border:1px solid #f5c6cb;color:#721c24;padding:15px;border-radius:5px;margin:10px 0;}';
    echo 'h1{color:#333;}code{background:#e9ecef;padding:2px 6px;border-radius:3px;}</style></head><body>';
    echo '<h1>Admin Kurulum</h1>';
}

require_once __DIR__ . '/autoload.php';
$dbConfig = require __DIR__ . '/config/database.php';

try {
    $dsn = sprintf(
        "mysql:host=%s;dbname=%s;charset=%s",
        $dbConfig['host'],
        $dbConfig['dbname'],
        $dbConfig['charset']
    );

    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], $dbConfig['options']);

    $password = 'admin123';
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $checkStmt = $pdo->prepare("SELECT id FROM admin_users WHERE username = ?");
    $checkStmt->execute(['admin']);
    $existingUser = $checkStmt->fetch();

    if ($existingUser) {
        $stmt = $pdo->prepare("UPDATE admin_users SET password = ?, email = ? WHERE username = ?");
        $stmt->execute([$hashedPassword, 'admin@example.com', 'admin']);
    } else {
        $stmt = $pdo->prepare("INSERT INTO admin_users (username, password, email) VALUES (?, ?, ?)");
        $stmt->execute(['admin', $hashedPassword, 'admin@example.com']);
    }

    if ($isWeb) {
        echo '<div class="success">';
        echo '<strong>Başarılı!</strong><br>';
        echo 'Admin kullanıcı oluşturuldu.<br><br>';
        echo '<strong>Giriş Bilgileri:</strong><br>';
        echo 'Kullanıcı Adı: <code>admin</code><br>';
        echo 'Şifre: <code>admin123</code><br><br>';
        echo '<a href="/admin/login" style="display:inline-block;margin-top:10px;padding:10px 20px;background:#007bff;color:white;text-decoration:none;border-radius:5px;">Admin Panele Git</a>';
        echo '</div>';
    } else {
        echo "Admin kullanıcı oluşturuldu.\n";
        echo "Kullanıcı adı: admin\n";
        echo "Şifre: admin123\n";
    }

} catch (PDOException $e) {
    if ($isWeb) {
        echo '<div class="error">';
        echo '<strong>Hata:</strong><br>';
        echo htmlspecialchars($e->getMessage());
        echo '</div>';
    } else {
        echo "Hata: " . $e->getMessage() . "\n";
    }
} catch (Exception $e) {
    if ($isWeb) {
        echo '<div class="error">';
        echo '<strong>Hata:</strong><br>';
        echo htmlspecialchars($e->getMessage());
        echo '</div>';
    } else {
        echo "Hata: " . $e->getMessage() . "\n";
    }
}

if ($isWeb) {
    echo '</body></html>';
}
