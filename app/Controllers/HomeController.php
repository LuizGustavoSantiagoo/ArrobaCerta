<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;

class HomeController extends Controller
{
    protected string $layout = 'authentication_layout';


    public function index(): void
    {
        $title = 'ArrobaCerta - Login';
        $this->render('home/index', compact('title'));
    }
}
