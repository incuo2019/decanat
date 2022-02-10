<?php

namespace App\Controllers;

use App\System\View;

class ExceptionsController //extends BaseController
{
    public function __invoke($description = '')
    {
        $view = new View();
        $view->description = $description;
        $view->title = 'Ошибка';
        $view->view('Exceptions/index');
    }
    public function e_404($description = '')
    {
        $view = new View();
        $view->description = $description;
        $view->title = 'Доступ запрещён';
        $view->view('Exceptions/404');
    }

    public function e_403($description = '')
    {
        $view = new View();
        $view->description = $description;
        $view->title = 'Страница не найдена';
        $view->view('Exceptions/403');
    }

    public function e_disabled($description = '')
    {
        $view = new View();
        $view->description = $description;
        $view->title = 'Доступ к сайту временно заблокирован';
        $view->view('Exceptions/disabled');
    }
}
