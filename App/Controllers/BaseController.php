<?php

namespace App\Controllers;

use App\System\Auth;
use App\System\Notification;

abstract class BaseController
{
    protected static $require_auth = true;
    protected static $require_moderator = false;
    protected static $require_admin = false;

    public function __construct()
    {
        if (static::$require_auth === true) {
            if (!Auth::isAuth()) {
                Notification::add('Необходимо авторизоваться');
                exit(header('Location: /auth'));
            }
        }
        if (static::$require_moderator === true) {
            if (!Auth::isModerator()) {
                Notification::add('Доступ запрещён');
                exit(header('Location: /'));
            }
        }
        if (static::$require_admin === true) {
            if (!Auth::isAdmin()) {
                Notification::add('Доступ запрещён');
                exit(header('Location: /'));
            }
        }
    }

    public function __invoke()
    {
        Notification::add('Отсутствует метод');
        exit(header('Location: /'));
    }
}
