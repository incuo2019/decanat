<?php

namespace App\Controllers;

use App\Logics\AuthLogic;
use App\Models\User;
use App\System\Auth;
use App\System\Notification;
use App\System\View;

class AuthController extends BaseController
{
    protected static $require_auth = false;

    public function __invoke()
    {
        $this->login();
    }

    public function registration()
    {
        if (Auth::isAuth()) {
            Notification::add('Вы уже авторизованы');
            exit(header('Location: /'));
        }

        if (Auth::get())
            exit(header('Location: /auth/password/'));

        if ($user = AuthLogic::validationLogin()) {
            if (!empty($user->password)) {
                Auth::create($user);
                Notification::add('Вы уже зарегистрированы');
                exit(header('Location: /auth/password/'));
            }

            if (AuthLogic::validationConsentPersonalData()) {
                Auth::create($user);
                exit(header('Location: /auth/password/'));
            }
            Notification::add('Вы не дали согласие на обработку персональных данных');
        }

        $view = new View();
        $view->title = 'Регистрация';
        $view->view('Auth/reg');
    }

    public function login()
    {
        if (Auth::isAuth()) {
            Notification::add('Вы уже авторизованы');
            exit(header('Location: /'));
        }

        if (Auth::get())
            $this->logout();

        if (!empty($_POST)) {
            if ($user = AuthLogic::validationLogin()) {
                if (empty($user->password)) {
                    Notification::add('Для начала зарегистрируйтесь');
                    exit(header('Location: /auth/registration/'));
                }
                Auth::create($user);
                exit(header('Location: /auth/password/'));
            }
        } else {
            Auth::saveReference();
        }

        $view = new View();
        $view->title = 'Авторизация';
        $view->view('Auth/login');
    }

    public function password()
    {
        if (Auth::isAuth()) {
            Notification::add('Вы уже авторизованы');
            exit(header('Location: /'));
        }

        if (empty(Auth::get()))
            exit(header('Location: /auth/login/'));

        if ($user = AuthLogic::validationPassword()) {
            if (Auth::isAuth()) {
                exit(header('Location: ' . Auth::getReference()));
            } else {
                Auth::update($user);
                exit(header('Location: /auth/password'));
            }
        }

        $view = new View();
        $view->title = 'Пароль';
        $view->view('Auth/password');
    }

    public function logout()
    {
        Auth::logout();
        exit(header('Location: /'));
    }
}
