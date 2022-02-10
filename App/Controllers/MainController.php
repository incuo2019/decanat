<?php

namespace App\Controllers;

use App\System\Auth;
use App\System\View;

class MainController extends BaseController
{
    protected static $require_auth = false;
    public function __invoke()
    {
        $view = new View();
        $view->addSection('/base/css');
        $view->addSection('/Main/get_start');
        $view->addSection('/Services/index');
        $view->addSection('/Informations/index');
        $view->addSection('/Contacts/index');
        $view->title = 'Главная';
        $view->is_auth = Auth::isAuth();
        $view->is_moderator = Auth::isModerator();

        $view->View();
    }
}
