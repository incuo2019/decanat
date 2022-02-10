<?php

namespace App\Models;

class Document extends Model
{
    protected const TABLE = 'documents';

    public static function getByStatusId($status_id = 0)
    {
        if (empty($status_id)) {
            return static::getAll();
        }

        global $sql;

        $query = 'SELECT * FROM `' . static::TABLE . '` WHERE is_removed = "0" ';

        if (!empty($status_id)) {
            if (is_array($status_id)) {
                $query .= ' AND status_id IN(' . implode(',', $status_id) . ')';
            } else {
                $query .= ' AND status_id = "' . $status_id . '"';
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

    public static function getByUserId($id, $status_id = 0)
    {
        if (empty($id)) {
            return false;
        }

        global $sql;
        $query = 'SELECT * FROM `' . static::TABLE . '` 
            WHERE is_removed = "0"
            AND creator_id = "' . $id . '" ';

        if (!empty($status_id)) {
            if (is_array($status_id)) {
                $query .= 'AND status_id IN(' . implode(',', $status_id) . ')';
            } else {
                $query .= 'AND status_id = "' . $status_id . '"';
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

    public static function getCountByStatusId($status_id = 0)
    {
        global $sql;
        $query = 'SELECT COUNT(id) as count FROM `' . static::TABLE . '` WHERE is_removed = 0 ';
        
        if (!empty($status_id)) {
            if (is_array($status_id)) {
                $query .= ' AND status_id IN(' . implode(',', $status_id) . ')';
            } else {
                $query .= ' AND status_id = "' . $status_id . '"';
            }
        }

        $request = $sql->query($query) or die(mysqli_error($sql));
        if (mysqli_num_rows($request) > 0) {
            return mysqli_fetch_assoc($request)['count'];
        }

        return false;
    }

    public function changeStatus($status_id)
    {
        global $sql;
        $query = 'UPDATE `' . static::TABLE . '` 
            SET status_id = "' . $status_id . '"
            WHERE id = "' . $this->id . '"
            AND is_removed = "0"';

        $sql->query($query) or die(mysqli_error($sql));
    }
}
