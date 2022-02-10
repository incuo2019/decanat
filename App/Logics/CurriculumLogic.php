<?php

namespace App\Logics;

use App\Models\Curriculum;
use App\Models\Group;
use App\Models\User;
use App\System\Filters;
use App\System\Notification;

class CurriculumLogic
{
    public static function validation()
    {
        if (!empty($_POST)) {
            $groups = $_POST['group'] ?? null;
            $teachers = $_POST['teacher'] ?? null;
            $subjects = $_POST['subject'] ?? null;

            if (is_null($groups) || is_null($teachers) || is_null($subjects)) {
                Notification::add('Одно или несколько полей не были переданы');
                return false;
            }

            $curriculum_rows = [];

            for ($index = 0; $index <= count($groups); $index++) {
                $group_id = intval($groups[$index] ?? 0);
                $teacher_id = intval($teachers[$index] ?? 0);
                $subject_id = intval($subjects[$index] ?? 0);

                if (empty($group_id) || empty($teacher_id) || empty($subject_id)) {
                    continue;
                }

                $curriculum = new Curriculum();
                $curriculum->init();

                $curriculum->id = $index;
                $curriculum->group_id = $group_id;
                $curriculum->teacher_id = $teacher_id;
                $curriculum->subject_id = $subject_id;

                $curriculum_rows[] = $curriculum;
            }

            return $curriculum_rows;
        }
        return false;
    }
}
