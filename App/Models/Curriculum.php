<?php

namespace App\Models;

use App\System\Filters;

class Curriculum extends Model
{
    protected const TABLE = 'curriculum';
    protected array $no_fields = [];

    public static function get()
    {
        global $sql;
        $query = 'SELECT * FROM `' . static::TABLE . '` WHERE is_removed = 0';
        $request = $sql->query($query) or die(mysqli_error($sql));

        if (mysqli_num_rows($request) > 0) {
            $curriculum = [];
            while ($temp = mysqli_fetch_assoc($request)) {
                $curriculum_row = new Curriculum();
                $curriculum_row->fields = $temp;

                $curriculum[] = $curriculum_row;
            }
            return $curriculum;
        }
        return false;
    }

    public static function save_rows($curriculum_rows)
    {
        Curriculum::truncate();
        foreach ($curriculum_rows as $row) {
            $row->insert();
        }
    }

    public static function getByGroupId($group_id = 0)
    {
        if (empty($group_id)) {
            return static::getAll();
        }

        global $sql;

        $query = 'SELECT * FROM `' . static::TABLE . '` ';

        if (!empty($group_id)) {
            if (is_array($group_id)) {
                $query .= 'WHERE group_id IN (' . implode(',', $group_id) . ')';
            } else {
                $query .= 'WHERE group_id = "' . $group_id . '"';
            }
        }

        $query .= ' ORDER BY id DESC';

        $request = $sql->query($query) or die(mysqli_error($sql));
        if (mysqli_num_rows($request) > 0) {
            $list = [];
            while ($element = mysqli_fetch_assoc($request)) {
                $model = new static();
                $model->fields = $element;
                $list[] = $model;
            }
            return $list;
        }
        return false;
    }

    public static function getBySubjectId($subject_id = 0)
    {
        if (empty($subject_id)) {
            return static::getAll();
        }

        global $sql;

        $query = 'SELECT * FROM `' . static::TABLE . '` ';

        if (!empty($subject_id)) {
            if (is_array($subject_id)) {
                $query .= 'WHERE subject_id IN (' . implode(',', $subject_id) . ')';
            } else {
                $query .= 'WHERE subject_id = "' . $subject_id . '"';
            }
        }

        $query .= ' ORDER BY id DESC';

        $request = $sql->query($query) or die(mysqli_error($sql));
        if (mysqli_num_rows($request) > 0) {
            $list = [];
            while ($element = mysqli_fetch_assoc($request)) {
                $model = new static();
                $model->fields = $element;
                $list[] = $model;
            }
            return $list;
        }
        return false;
    }

    public static function getByTeacherId($teacher_id = 0)
    {
        if (empty($teacher_id)) {
            return static::getAll();
        }

        global $sql;

        $query = 'SELECT * FROM `' . static::TABLE . '` ';

        if (!empty($teacher_id)) {
            if (is_array($teacher_id)) {
                $query .= 'WHERE teacher_id IN (' . implode(',', $teacher_id) . ')';
            } else {
                $query .= 'WHERE teacher_id = "' . $teacher_id . '"';
            }
        }

        $query .= ' ORDER BY id DESC';

        $request = $sql->query($query) or die(mysqli_error($sql));
        if (mysqli_num_rows($request) > 0) {
            $list = [];
            while ($element = mysqli_fetch_assoc($request)) {
                $model = new static();
                $model->fields = $element;
                $list[] = $model;
            }
            return $list;
        }
        return false;
    }
}
