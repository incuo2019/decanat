<?php

namespace App\Controllers;

use App\Models\Certificate;
use App\Models\Characteristic;
use App\Models\Document;
use App\Models\Sheet;
use App\Models\Subject;
use App\System\Auth;
use App\System\Notification;
use App\System\View;

class AppealsController extends BaseController
{
    protected static $require_auth = true;

    public function __invoke()
    {
        $view = new View();
        $view->certificates = Certificate::getByUserId(Auth::getId(), [1, 2, 3]);
        $view->characteristics = Characteristic::getByUserId(Auth::getId(), [1, 2, 3]);
        $view->sheets = Sheet::getByUserId(Auth::getId(), [1, 2, 3]);

        $view->certificates_title = 'Запросы справок об обучении';
        $view->characteristics_title = 'Запросы характеристик';
        $view->sheets_title = 'Запросы инд. экз. ведомостей';
        $view->subjects = Subject::getAll();
        $view->addSection('Certificates/index');
        $view->addSection('Characteristics/index');
        $view->addSection('Sheets/index');
        $view->view();
    }
}
