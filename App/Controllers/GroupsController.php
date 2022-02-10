<?php

namespace App\Controllers;

use App\Logics\GroupLogic;
use App\Models\Group;
use App\System\Auth;
use App\System\Filters;
use App\System\Logs;
use App\System\Notification;
use App\System\View;

class GroupsController extends BaseController
{
    protected static $require_auth = true;
    protected static $require_moderator = true;
    protected static $require_admin = true;

    public function __invoke()
    {
        $groups = Group::getAll();
        $view = new View();
        $view->groups = $groups;
        $view->title = 'Учебные группы';
        $view->view('Groups/index');
    }

    public function add()
    {
        if ($group = GroupLogic::validation()) {
            $group->save();

            Notification::add('Учебная группа успешно добавлена');
            exit(header('Location: /groups/'));
        }

        $view = new View();
        $view->title = 'Добавление учебной группы';
        $view->view('Groups/edit');
    }

    public function edit($id)
    {
        $is_edit = true;
        $id = Filters::toInt($id);
        $group = Group::getById($id);

        if (empty($group)) {
            Notification::add('Учебная группа не найдена');
            exit(header('Location: /groups/'));
        }

        if ($edited_group = GroupLogic::validation($group)) {
            $edited_group->save();

            Notification::add('Данные учебной группы успешно изменены');
            exit(header('Location: /groups/'));
        }

        $view = new View();
        $view->is_edit = $is_edit;
        $view->group = $group;
        $view->title = 'Редактирование учебной группы';
        $view->view('Groups/edit');
    }

    public function remove($id)
    {
        $id = Filters::toInt($id);
        $group = Group::getById($id);

        if (empty($group)) {
            Notification::add('Учебная группа не найдена');
            exit(header('Location: /groups/'));
        }

        $group->delete();

        Notification::add('Учебная группа успешно удалена');
        exit(header('Location: /groups/'));
    }

    public function view($id)
    {
        $id = Filters::toInt($id);
        $group = Group::getById($id);
        if (empty($group)) {
            Notification::add('Учебная группа не найдена');
            exit(header('Location: /groups/'));
        }

        $view = new View();
        $view->group = $group;
        $view->title = 'Учебная группа';
        $view->view('Groups/view');
    }
}
