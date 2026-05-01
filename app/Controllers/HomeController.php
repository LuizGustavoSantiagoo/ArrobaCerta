<?php

namespace App\Controllers;

use App\Models\User;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\Authentication\Auth;

class HomeController extends Controller
{

    protected string $layout = 'authentication_layout';


    public function index(): void
    {
        $title = 'Home Page';
        $this->render('home/index', compact('title'));
    }
}
