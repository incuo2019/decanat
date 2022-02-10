<?php

namespace App\Logics;

use App\Models\Group;
use App\Models\User;
use App\System\Filters;
use App\System\Notification;

class GroupLogic
{
    public static function validation($group = null)
    {
        if (!empty($_POST)) {
            if (empty($_POST['name']) || empty($_POST['department_id'])) {
                Notification::add('Одно или несколько полей не были переданы');
                return false;
            }

            $name = Filters::toString($_POST['name']);
            $department_id = Filters::toInt($_POST['department_id']);
            $semester = Filters::toInt($_POST['semester']);

            if (mb_strlen($name) < 6 || empty($department_id) || empty($semester)) {
                Notification::add('Одно или несколько полей некорректны');
                return false;
            }

            if (!empty($_POST['id'])) {
                $id = Filters::toInt($_POST['id']);
            }

            if ($check_group = Group::getByName($name)) {
                if (empty($id) || $check_group->id != $id) {
                    Notification::add('Такая группа уже создана');
                    return false;
                }
            }

            if (empty($group)) {
                $group = new Group();
                $group->init();
            }

            $group->name = $name;
            $group->department_id = $department_id;
            $group->semester = $semester;

            if (!empty($id)) {
                $group->id = $id;
            }
            return $group;
        }
        return false;
    }
}
