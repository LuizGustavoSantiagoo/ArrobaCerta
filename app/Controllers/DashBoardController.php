<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;

class DashBoardController extends Controller
{
    protected string $layout = 'application';

    public function index(): void
    {
        $title = 'Dashboard';
        $user = $this->current_user->findBy(['id' => $this->current_user->id]);
        $this->render('dashboard/index', compact('title', 'user'));
    }
}
