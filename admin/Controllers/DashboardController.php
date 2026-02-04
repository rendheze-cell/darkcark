<?php
/**
 * Admin Dashboard Controller
 */

namespace Admin\Controllers;

use Admin\Core\Controller;
use App\Models\User;
use App\Models\Bank;

class DashboardController extends Controller
{
    /**
     * Dashboard
     */
    public function index(): void
    {
        $userModel = new User();
        $bankModel = new Bank();

        $totalUsers = $userModel->getTotalCount();
        $recentUsers = $userModel->getAll(10, 0);
        $banks = $bankModel->getAll();

        $this->view('dashboard/index', [
            'totalUsers' => $totalUsers,
            'recentUsers' => $recentUsers,
            'banks' => $banks
        ]);
    }
}

