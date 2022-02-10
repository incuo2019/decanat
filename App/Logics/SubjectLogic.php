<?php

namespace App\Logics;

use App\Models\Subject;
use App\Models\User;
use App\System\Filters;
use App\System\Notification;

class SubjectLogic
{
    public static function validation($subject = null)
    {
        if (!empty($_POST)) {
            if (empty($_POST['name'])) {
                Notification::add('Одно или несколько полей не были переданы');
                return false;
            }

            $name = Filters::toString($_POST['name']);

            if (mb_strlen($name) < 6) {
                Notification::add('Одно или несколько полей некорректны');
                return false;
            }

            if (!empty($_POST['id'])) {
                $id = Filters::toInt($_POST['id']);
            }

            if ($check_subject = Subject::getByName($name)) {
                if (empty($id) || $check_subject->id != $id) {
                    Notification::add('Такая дисциплина уже создана');
                    return false;
                }
            }
            
            if (empty($subject)) {
                $subject = new Subject();
                $subject->init();
            }

            $subject->name = $name;

            if (!empty($id)) {
                $subject->id = $id;
            }
            return $subject;
        }
        return false;
    }
}
