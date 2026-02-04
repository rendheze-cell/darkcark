<?php
/**
 * Admin Bank Management Controller
 */

namespace Admin\Controllers;

use Admin\Core\Controller;
use App\Models\Bank;

class BankController extends Controller
{
    /**
     * Banka listesi
     */
    public function index(): void
    {
        $bankModel = new Bank();
        $banks = $bankModel->getAll();

        $this->view('banks/index', ['banks' => $banks]);
    }

    /**
     * Banka ekle/güncelle
     */
    public function save(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Geçersiz istek.'], 400);
        }

        $id = (int) ($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $displayOrder = (int) ($_POST['display_order'] ?? 0);
        $color = trim($_POST['color'] ?? '#FF6B6B');
        $wheelText = trim($_POST['wheel_text'] ?? '');

        if (empty($name)) {
            $this->json(['success' => false, 'message' => 'Banka adı gereklidir.'], 400);
        }

        if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $color)) {
            $color = '#FF6B6B';
        }

        if (empty($wheelText)) {
            $wheelText = (string)($id > 0 ? $id : time());
        }

        try {
            $bankModel = new Bank();

            if ($id > 0) {
                // Güncelle
                $bankModel->update($id, [
                    'name' => $name,
                    'is_active' => $isActive,
                    'display_order' => $displayOrder,
                    'color' => $color,
                    'wheel_text' => $wheelText
                ]);
                $message = 'Banka güncellendi.';
            } else {
                // Yeni ekle
                $bankModel->create($name, null, $displayOrder, $color, $wheelText);
                $message = 'Banka eklendi.';
            }

            $this->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => 'Hata: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Banka sil
     */
    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Geçersiz istek.'], 400);
        }

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            $this->json(['success' => false, 'message' => 'Geçersiz banka ID.'], 400);
        }

        try {
            $bankModel = new Bank();
            $bankModel->delete($id);
            $this->json(['success' => true, 'message' => 'Banka silindi.']);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => 'Hata: ' . $e->getMessage()], 500);
        }
    }
}

