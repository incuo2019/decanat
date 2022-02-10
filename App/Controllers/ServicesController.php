<?php

namespace App\Controllers;

use App\System\Auth;
use App\System\View;

class ServicesController //extends BaseController
{
    public function __invoke()
    {
        $view = new View();
        $view->title = 'Сервисы';
        $view->View('/Services/index');
        $view->is_auth = Auth::isAuth();
        $view->is_moderator = Auth::isModerator();
    }
}
