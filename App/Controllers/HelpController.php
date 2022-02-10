<?php

namespace App\Controllers;

use App\System\Auth;
use App\System\View;

class HelpController //extends BaseController
{
    public function __invoke()
    {
        $view = new View();
        $view->title = 'Помощь';
        $view->is_auth = Auth::isAuth();
        $view->is_moderator = Auth::isModerator();
        $view->current_url = $_SERVER['REQUEST_URI'];
        $view->View('/Contacts/index');
    }
}
