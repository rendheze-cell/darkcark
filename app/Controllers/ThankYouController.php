<?php

namespace App\Controllers;

use App\Core\Controller;

class ThankYouController extends Controller
{
    public function index(): void
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/');
        }

        $this->view('thankyou/index');
    }
}

