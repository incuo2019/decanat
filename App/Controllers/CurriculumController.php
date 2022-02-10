<?php

namespace App\Controllers;

use App\Logics\CurriculumLogic;
use App\Models\Curriculum;
use App\Models\Group;
use App\Models\Subject;
use App\Models\Teacher;
use App\System\Auth;
use App\System\Filters;
use App\System\Logs;
use App\System\Notification;
use App\System\View;

class CurriculumController extends BaseController
{
    protected static $require_auth = true;
    //protected static $require_moderator = true;

    public function __invoke()
    {
        if (Auth::isModerator()) {
            $curriculum = Curriculum::get();
        } else {
            $curriculum = Curriculum::getByGroupId(Auth::get()->group_id);
        }
        $view = new View();
        $view->curriculum = $curriculum;
        $view->groups = Group::getAll();
        $view->subjects = Subject::getAll();
        $view->teachers = Teacher::getAll();
        $view->title = 'Учебный план';
        $view->view('Curriculum/index');
    }

    public function edit()
    {
        if (!Auth::isModerator()) {
            Notification::add('Доступ запрещён');
            exit(header('Location: /'));
        }

        $is_edit = true;

        if ($curriculum_rows = CurriculumLogic::validation()) {
            Curriculum::save_rows($curriculum_rows);
            Notification::add('Данные учебного плана успешно изменены');
            exit(header('Location: /curriculum/'));
        }

        $view = new View();
        $view->is_edit = $is_edit;
        $view->curriculum = Curriculum::get();
        $view->groups = Group::getAll();
        $view->subjects = Subject::getAll();
        $view->teachers = Teacher::getAll();
        $view->title = 'Редактирование учебного плана';
        $view->view('Curriculum/edit');
    }
}
