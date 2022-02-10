<?php

namespace App\Controllers;

use App\Logics\UserLogic;
use App\Models\User;
use App\System\Auth;
use App\System\Filters;
use App\System\Logs;
use App\System\Notification;
use App\System\View;

class UsersController //extends BaseController
{
    public function __invoke()
    {
        if (!Auth::isAuth() || !Auth::isAdmin()) {
            Notification::add('Доступ запрещён');

            $logs = new Logs();
            $logs("Попытка обращения к UsersController / __invoke");

            exit(header('Location: /'));
        }

        $users = User::getAll();
        $view = new View();
        $view->users = $users;
        $view->title = 'Пользователи';
        $view->view('Users/index');
    }

    public function add()
    {
        if (!Auth::isAuth() || !Auth::isAdmin()) {
            Notification::add('Доступ запрещён');

            $logs = new Logs();
            $logs("Попытка обращения к UsersController / add");

            exit(header('Location: /'));
        }

        if ($user = UserLogic::validation()) {
            $user->save();

            Notification::add('Пользователь успешно добавлен');
            exit(header('Location: /users/'));
        }

        $view = new View();
        $view->title = 'Добавление пользователя';
        $view->view('Users/add');
    }

    public function edit($id)
    {
        if (!Auth::isAuth()) {
            Notification::add('Доступ запрещён');

            $logs = new Logs();
            $logs("Попытка обращения к UsersController / add");

            exit(header('Location: /'));
        }

        if (Auth::getId() != $id && !Auth::isAdmin()) {
            Notification::add('Доступ запрещён');

            $logs = new Logs();
            $logs("Попытка обращения к UsersController / add");

            exit(header('Location: /'));
        }

        $id = Filters::toInt($id);
        $user = User::getById($id);
        if (empty($user)) {
            Notification::add('Пользователь не найден');
            exit(header('Location: /users/'));
        }

        if ($edited_user = UserLogic::validation($user)) {
            $edited_user->save();

            Notification::add('Данные пользователя успешно изменены');
            if (Auth::isAdmin()) {
                exit(header('Location: /users/'));
            }
            exit(header('Location: /'));
        }

        $view = new View();
        $view->user = $user;
        $view->title = 'Редактирование пользователя';
        $view->view('Users/edit');
    }

    public function remove($id)
    {
        if (!Auth::isAuth() || !Auth::isAdmin()) {
            Notification::add('Доступ запрещён');

            $logs = new Logs();
            $logs("Попытка обращения к UsersController / add");

            exit(header('Location: /'));
        }

        $id = Filters::toInt($id);
        $user = User::getById($id);

        if (empty($user)) {
            Notification::add('Пользователь не найден');
            exit(header('Location: /users/'));
        }

        $user->delete();

        Notification::add('Пользователь успешно удалён');
        exit(header('Location: /users/'));
    }

    public function view($id)
    {
        if (!Auth::isAuth() || (Auth::getId() != $id && !Auth::isAdmin())) {
            Notification::add('Доступ запрещён');

            $logs = new Logs();
            $logs("Попытка обращения к UsersController / view");

            exit(header('Location: /'));
        }

        $id = Filters::toInt($id);
        $user = User::getById($id);
        if (empty($user)) {
            Notification::add('Пользователь не найден');
            exit(header('Location: /users/'));
        }

        $view = new View();
        $view->user = $user;
        $view->title = 'Пользователь';
        $view->view('Users/view');
    }
}
