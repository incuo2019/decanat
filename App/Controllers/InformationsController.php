<?php

namespace App\Controllers;

use App\System\Auth;
use App\System\View;

class InformationsController //extends BaseController
{
    public function __invoke()
    {
        $view = new View();
        $view->title = 'Информация';
        $view->is_auth = Auth::isAuth();
        $view->is_moderator = Auth::isModerator();
        $view->View('/Informations/index');
    }
}
