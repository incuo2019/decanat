<?php

namespace App\Controllers;

use App\Logics\SubjectLogic;
use App\Models\Subject;
use App\System\Auth;
use App\System\Filters;
use App\System\Logs;
use App\System\Notification;
use App\System\View;

class SubjectsController extends BaseController
{
    protected static $require_auth = true;
    protected static $require_moderator = true;
    protected static $require_admin = true;

    public function __invoke()
    {
        $subjects = Subject::getAll();
        $view = new View();
        $view->subjects = $subjects;
        $view->title = 'Учебные дисциплины';
        $view->view('Subjects/index');
    }

    public function add()
    {
        if ($subject = SubjectLogic::validation()) {
            $subject->save();

            Notification::add('Учебная дисциплина успешно добавлена');
            exit(header('Location: /subjects/'));
        }

        $view = new View();
        $view->title = 'Добавление учебной дисциплины';
        $view->view('Subjects/edit');
    }

    public function edit($id)
    {
        $id = Filters::toInt($id);
        $subject = Subject::getById($id);

        if (empty($subject)) {
            Notification::add('Учебная дисциплина не найдена');
            exit(header('Location: /subjects/'));
        }

        if ($edited_subject = SubjectLogic::validation($subject)) {
            $edited_subject->save();

            Notification::add('Данные учебной дисциплины успешно изменены');
            exit(header('Location: /subjects/'));
        }

        $view = new View();
        $view->subject = $subject;
        $view->title = 'Редактирование учебной дисциплины';
        $view->view('Subjects/edit');
    }

    public function remove($id)
    {
        $id = Filters::toInt($id);
        $subject = Subject::getById($id);

        if (empty($subject)) {
            Notification::add('Учебная дисциплина не найдена');
            exit(header('Location: /subjects/'));
        }

        $subject->delete();

        Notification::add('Учебная дисциплина успешно удалена');
        exit(header('Location: /subjects/'));
    }

    public function view($id)
    {
        $id = Filters::toInt($id);
        $subject = Subject::getById($id);
        if (empty($subject)) {
            Notification::add('Учебная дисциплина не найдена');
            exit(header('Location: /subjects/'));
        }

        $view = new View();
        $view->subject = $subject;
        $view->title = 'Учебная дисциплина';
        $view->view('Subjects/view');
    }
}
