<?php

namespace App\Controllers;

use App\System\Auth;
use App\System\Notification;
use App\System\View;

class AdminController extends BaseController
{
    protected static $require_auth = true;

    public function __construct()
    {
        if (Auth::getAccessLevel() < 1) {
            Notification::add('Доступ запрещён');
            exit(header('Location: /'));
        }
    }
    public function __invoke()
    {
        Auth::saveReference();
        Auth::admin();
        exit(header('Location: ' . Auth::getReference()));
    }

    public function settings()
    {
        if (!Auth::isAdmin()) {
            Notification::add('Доступ запрещён');
            exit(header('Location: /'));
        }

        $view = new View();
        $view->is_flex = false;
        $view->view('Admin/settings');
    }
}
