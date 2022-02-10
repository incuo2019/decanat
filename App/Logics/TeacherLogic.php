<?php

namespace App\Logics;

use App\Models\Teacher;
use App\Models\User;
use App\System\Filters;
use App\System\Notification;

class TeacherLogic
{
    public static function validation($teacher = null)
    {
        if (!empty($_POST)) {
            if (empty($_POST['firstname']) || empty($_POST['middlename']) || empty($_POST['lastname'])) {
                Notification::add('Одно или несколько полей не были переданы');
                return false;
            }

            $firstname = Filters::toString($_POST['firstname']);
            $middlename = Filters::toString($_POST['middlename']);
            $lastname = Filters::toString($_POST['lastname']);

            if (mb_strlen($firstname) < 2 || mb_strlen($middlename) < 2 || mb_strlen($lastname) < 2) {
                Notification::add('Одно или несколько полей некорректны');
                return false;
            }

            if (!empty($_POST['id'])) {
                $id = Filters::toInt($_POST['id']);
            }

            if ($check_teacher = Teacher::getByName($firstname, $middlename, $lastname)) {
                if (empty($id) || $check_teacher->id != $id) {
                    Notification::add('Преподаватель с таким данными уже создан');
                    return false;
                }
            }

            if (empty($teacher)) {
                $teacher = new Teacher();
                $teacher->init();
            }

            $teacher->firstname = $firstname;
            $teacher->middlename = $middlename;
            $teacher->lastname = $lastname;

            if (!empty($id)) {
                $teacher->id = $id;
            }
            return $teacher;
        }
        return false;
    }
}
