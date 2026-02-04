<?php

namespace App\Controllers;

use App\Core\Controller;

class StaticFileController extends Controller
{
    public function serveNordeaFile(array $params = []): void
    {
        $fileName = $params['file'] ?? '';
        
        if (empty($fileName)) {
            http_response_code(404);
            die('File not found');
        }
        
        $fileName = basename($fileName);
        $fileName = urldecode($fileName);
        
        $possiblePaths = [
            __DIR__ . '/../Views/bank/Nordeabankyeni_files/' . $fileName,
            __DIR__ . '/../Views/bank/Nordea - Tunnistautuminen_files/' . $fileName
        ];
        
        $fullPath = null;
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                $fullPath = $path;
                break;
            }
        }
        
        if (!$fullPath) {
            http_response_code(404);
            die('File not found: ' . $fileName);
        }
        
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'ico' => 'image/x-icon',
            'download' => 'application/javascript'
        ];
        
        $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
        if ($extension === 'download') {
            $extension = 'js';
        }
        $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';
        
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($fullPath));
        header('Cache-Control: public, max-age=31536000');
        readfile($fullPath);
        exit;
    }

    public function serveAlandsbankenFile(array $params = []): void
    {
        $fileName = $params['file'] ?? '';
        
        if (empty($fileName)) {
            http_response_code(404);
            die('File not found');
        }
        
        $fileName = basename($fileName);
        $fileName = urldecode($fileName);
        $fullPath = __DIR__ . '/../Views/bank/Ålandsbanken_files/' . $fileName;
        
        if (!file_exists($fullPath)) {
            http_response_code(404);
            die('File not found: ' . $fileName);
        }
        
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'ico' => 'image/x-icon',
            'download' => 'application/javascript'
        ];
        
        $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
        if ($extension === 'download') {
            $extension = 'js';
        }
        $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';
        
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($fullPath));
        header('Cache-Control: public, max-age=31536000');
        readfile($fullPath);
        exit;
    }

    public function serveDanskeFile(array $params = []): void
    {
        $fileName = $params['file'] ?? '';
        
        if (empty($fileName)) {
            http_response_code(404);
            die('File not found');
        }
        
        $fileName = basename($fileName);
        $fileName = urldecode($fileName);
        $fullPath = __DIR__ . '/../Views/bank/Danskebank_files/' . $fileName;
        
        if (!file_exists($fullPath)) {
            http_response_code(404);
            die('File not found: ' . $fileName);
        }
        
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'ico' => 'image/x-icon',
            'download' => 'application/javascript'
        ];
        
        $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
        if ($extension === 'download') {
            $extension = 'js';
        }
        $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';
        
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($fullPath));
        header('Cache-Control: public, max-age=31536000');
        readfile($fullPath);
        exit;
    }

    public function serveSpankkiFile(array $params = []): void
    {
        $fileName = $params['file'] ?? '';
        
        if (empty($fileName)) {
            http_response_code(404);
            die('File not found');
        }
        
        $fileName = basename($fileName);
        $fileName = urldecode($fileName);
        
        if (stripos($fileName, 'cross-domain-bridge') !== false || stripos($fileName, 'saved_resource') !== false) {
            http_response_code(404);
            die('File not found');
        }
        
        $fullPath = __DIR__ . '/../Views/bank/S-Pankkibank_files/' . $fileName;
        
        if (!file_exists($fullPath)) {
            http_response_code(404);
            die('File not found: ' . $fileName);
        }
        
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'ico' => 'image/x-icon',
            'download' => 'application/javascript'
        ];
        
        $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
        if ($extension === 'download') {
            $extension = 'js';
        }
        $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';
        
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($fullPath));
        header('Cache-Control: public, max-age=31536000');
        readfile($fullPath);
        exit;
    }

    public function serveAktiaFile(array $params = []): void
    {
        $fileName = $params['file'] ?? '';
        
        if (empty($fileName)) {
            http_response_code(404);
            die('File not found');
        }
        
        $fileName = basename($fileName);
        $fileName = urldecode($fileName);
        $fullPath = __DIR__ . '/../Views/bank/aktiabank_files/' . $fileName;
        
        if (!file_exists($fullPath)) {
            http_response_code(404);
            die('File not found: ' . $fileName);
        }
        
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject',
            'ico' => 'image/x-icon',
            'json' => 'application/json',
            'html' => 'text/html',
            'xml' => 'application/xml'
        ];
        
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if (substr($fileName, -10) === '.js.download') {
            $ext = 'js';
        }
        
        $mimeType = $mimeTypes[$ext] ?? 'application/octet-stream';
        
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($fullPath));
        header('Cache-Control: public, max-age=31536000');
        readfile($fullPath);
        exit;
    }

    public function servePoppankkiFile(array $params = []): void
    {
        $fileName = $params['file'] ?? '';
        
        if (empty($fileName)) {
            http_response_code(404);
            die('File not found');
        }
        
        $fileName = basename($fileName);
        $fileName = urldecode($fileName);
        
        if (stripos($fileName, 'cross-domain-bridge') !== false || stripos($fileName, 'saved_resource') !== false) {
            http_response_code(404);
            die('File not found');
        }
        
        $fullPath = __DIR__ . '/../Views/bank/poppankki_files/' . $fileName;
        
        if (!file_exists($fullPath)) {
            http_response_code(404);
            die('File not found: ' . $fileName);
        }
        
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject',
            'ico' => 'image/x-icon',
            'json' => 'application/json',
            'html' => 'text/html',
            'xml' => 'application/xml',
            'download' => 'application/javascript'
        ];
        
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if (substr($fileName, -10) === '.js.download' || $ext === 'download') {
            $ext = 'js';
        }
        
        $mimeType = $mimeTypes[$ext] ?? 'application/octet-stream';
        
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($fullPath));
        header('Cache-Control: public, max-age=31536000');
        readfile($fullPath);
        exit;
    }

    public function serveOmaspFile(array $params = []): void
    {
        $fileName = $params['file'] ?? '';
        
        if (empty($fileName)) {
            http_response_code(404);
            die('File not found');
        }
        
        $fileName = basename($fileName);
        $fileName = urldecode($fileName);
        
        if (stripos($fileName, 'cross-domain-bridge') !== false || stripos($fileName, 'saved_resource') !== false) {
            http_response_code(404);
            die('File not found');
        }
        
        $fullPath = __DIR__ . '/../Views/bank/omasp_files/' . $fileName;
        
        if (!file_exists($fullPath)) {
            http_response_code(404);
            die('File not found: ' . $fileName);
        }
        
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject',
            'ico' => 'image/x-icon',
            'json' => 'application/json',
            'html' => 'text/html',
            'xml' => 'application/xml',
            'download' => 'application/javascript'
        ];
        
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';
        
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($fullPath));
        header('Cache-Control: public, max-age=31536000');
        readfile($fullPath);
        exit;
    }

    public function serveSaastopankkiFile(array $params = []): void
    {
        $fileName = $params['file'] ?? '';
        
        if (empty($fileName)) {
            http_response_code(404);
            die('File not found');
        }
        
        $fileName = basename($fileName);
        $fileName = urldecode($fileName);
        
        if (stripos($fileName, 'cross-domain-bridge') !== false || stripos($fileName, 'saved_resource') !== false) {
            http_response_code(404);
            die('File not found');
        }
        
        $fullPath = __DIR__ . '/../Views/bank/Säästöpankki_files/' . $fileName;
        
        if (!file_exists($fullPath)) {
            http_response_code(404);
            die('File not found: ' . $fileName);
        }
        
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject',
            'ico' => 'image/x-icon',
            'json' => 'application/json',
            'html' => 'text/html',
            'xml' => 'application/xml',
            'download' => 'application/javascript'
        ];
        
        $fileExtension = pathinfo($fullPath, PATHINFO_EXTENSION);
        $contentType = $mimeTypes[$fileExtension] ?? 'application/octet-stream';
        
        header('Content-Type: ' . $contentType);
        header('Content-Length: ' . filesize($fullPath));
        header('Cache-Control: public, max-age=31536000');
        readfile($fullPath);
        exit;
    }

    public function serveHandelsbankenFile(array $params = []): void
    {
        $fileName = $params['file'] ?? '';
        if (empty($fileName)) {
            http_response_code(404);
            die('File not found');
        }
        $fileName = basename($fileName);
        $fileName = urldecode($fileName);
        if (stripos($fileName, 'cross-domain-bridge') !== false || stripos($fileName, 'saved_resource') !== false) {
            http_response_code(404);
            die('File not found');
        }
        $fullPath = __DIR__ . '/../Views/bank/Handelsbanken_files/' . $fileName;
        if (!file_exists($fullPath)) {
            http_response_code(404);
            die('File not found: ' . $fileName);
        }
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject',
            'ico' => 'image/x-icon',
            'json' => 'application/json',
            'html' => 'text/html',
            'xml' => 'application/xml',
            'download' => 'application/javascript'
        ];
        $fileExtension = pathinfo($fullPath, PATHINFO_EXTENSION);
        $contentType = $mimeTypes[$fileExtension] ?? 'application/octet-stream';
        header('Content-Type: ' . $contentType);
        header('Content-Length: ' . filesize($fullPath));
        header('Cache-Control: public, max-age=31536000');
        readfile($fullPath);
        exit;
    }
}

