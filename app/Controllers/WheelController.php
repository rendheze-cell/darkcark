<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Bank;

class WheelController extends Controller
{
    public function index(): void
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/');
        }

        $db = \App\Core\Database::getInstance();
        $stmt = $db->query("SELECT setting_key, setting_value FROM settings WHERE setting_key IN ('wheel_title', 'wheel_subtitle', 'wheel_instruction', 'wheel_spinning', 'wheel_button_text')");
        $wheelTexts = [];
        while ($row = $stmt->fetch()) {
            $wheelTexts[$row['setting_key']] = $row['setting_value'];
        }

        $wheelTexts['wheel_title'] = $wheelTexts['wheel_title'] ?? 'Onnea!';
        $wheelTexts['wheel_subtitle'] = $wheelTexts['wheel_subtitle'] ?? 'Pyöräytä pyörää ja voita';
        $wheelTexts['wheel_instruction'] = $wheelTexts['wheel_instruction'] ?? 'Klikkaa pyörää tai keskimmäistä nappia aloittaaksesi';
        $wheelTexts['wheel_spinning'] = $wheelTexts['wheel_spinning'] ?? 'Pyörä pyörii...';
        $wheelTexts['wheel_button_text'] = $wheelTexts['wheel_button_text'] ?? 'SPIN';

        $bankModel = new Bank();
        $banks = $bankModel->getActive();

        $defaultColors = [
            '#FF6B6B', '#4ECDC4', '#45B7D1', '#FFA07A', 
            '#98D8C8', '#F7DC6F', '#C44569', '#F8B500',
            '#6C5CE7', '#00D2D3', '#FF6348', '#2ED573'
        ];

        $segments = [];
        foreach ($banks as $index => $bank) {
            $segments[] = [
                'id' => $bank['id'],
                'name' => $bank['name'],
                'wheel_text' => $bank['wheel_text'] ?? $bank['name'],
                'color' => $bank['color'] ?? $defaultColors[$index % count($defaultColors)]
            ];
        }

        if (empty($segments)) {
            $segments = [
                ['id' => 0, 'name' => 'Nordea', 'wheel_text' => '1', 'color' => '#FF6B6B'],
                ['id' => 0, 'name' => 'OP', 'wheel_text' => '2', 'color' => '#4ECDC4'],
                ['id' => 0, 'name' => 'Danske', 'wheel_text' => '3', 'color' => '#45B7D1'],
            ];
        }

        $this->view('wheel/index', [
            'segments' => $segments,
            'wheelTexts' => $wheelTexts
        ]);
    }
}

