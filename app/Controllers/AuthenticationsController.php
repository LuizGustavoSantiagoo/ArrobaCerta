<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;
use Core\Http\Request;
use App\Models\User;
use Lib\Authentication\Auth;
use Lib\FlashMessage;

class AuthenticationsController extends Controller
{

    public function authenticate(Request $request): void
    {
        $email = $request->getParam('email');
        $password = $request->getParam('password');

        $user = User::findByEmail($email);

        if(!$email || !$password) {
            FlashMessage::danger('Por favor, preencha todos os campos.');
            $this->redirectTo(route('root'));
            return;
        }

        if(User::userStatuses($user) === 'inactive') {
            FlashMessage::danger('Esta conta está inativa.');
            $this->redirectTo(route('root'));
            return;
        }

        if (User::validateLogin($email, $password)) {
            Auth::login($user);

            $this->redirectTo(route('dashboard'));
        } else {
            FlashMessage::danger('Email ou senha inválidos.');
            $this->redirectTo(route('root'));
        }
    }

        public function logout(): void
        {
            Auth::logout();
            $this->redirectTo(route('root'));
        }
}