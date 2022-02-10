<?php

namespace App\Controllers;

use App\Logics\TeacherLogic;
use App\Models\Teacher;
use App\System\Auth;
use App\System\Filters;
use App\System\Logs;
use App\System\Notification;
use App\System\View;

class TeachersController extends BaseController
{
    protected static $require_auth = true;
    protected static $require_moderator = true;
    protected static $require_admin = true;

    public function __invoke()
    {
        $teachers = Teacher::getAll();
        $view = new View();
        $view->teachers = $teachers;
        $view->title = 'Преподаватели';
        $view->view('Teachers/index');
    }

    public function add()
    {
        if ($teacher = TeacherLogic::validation()) {
            $teacher->save();

            Notification::add('Преподаватель успешно добавлен');
            exit(header('Location: /teachers/'));
        } else {
        }

        $view = new View();
        $view->title = 'Добавление преподавателя';
        $view->view('Teachers/edit');
    }

    public function edit($id)
    {
        $id = Filters::toInt($id);
        $teacher = Teacher::getById($id);

        if (empty($teacher)) {
            Notification::add('Преподаватель не найден');
            exit(header('Location: /teachers/'));
        }

        if ($edited_teacher = TeacherLogic::validation($teacher)) {
            $edited_teacher->save();

            Notification::add('Данные преподавателя успешно изменены');
            exit(header('Location: /teachers/'));
        }

        $view = new View();
        $view->teacher = $teacher;
        $view->title = 'Редактирование данных преподавателя';
        $view->view('Teachers/edit');
    }

    public function remove($id)
    {
        $id = Filters::toInt($id);
        $teacher = Teacher::getById($id);

        if (empty($teacher)) {
            Notification::add('Преподаватель не найден');
            exit(header('Location: /teachers/'));
        }

        $teacher->delete();

        Notification::add('Преподаватель успешно удален');
        exit(header('Location: /teachers/'));
    }

    public function view($id)
    {
        $id = Filters::toInt($id);
        $teacher = Teacher::getById($id);
        if (empty($teacher)) {
            Notification::add('Преподаватель не найден');
            exit(header('Location: /teachers/'));
        }

        $view = new View();
        $view->teacher = $teacher;
        $view->title = 'Преподаватель';
        $view->view('Teachers/view');
    }
}
