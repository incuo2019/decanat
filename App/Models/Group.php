<?php

namespace App\Models;

use App\System\Filters;

class Group extends Model
{
    protected const TABLE = 'groups';

    public static function getByName($name) {
        $name = Filters::toString($name);
        global $sql;
        $query = 'SELECT * FROM `' . static::TABLE . '` WHERE `name` = "' . $name . '" AND is_removed = 0';

        $request = $sql->query($query) or die(mysqli_error($sql));

        if (mysqli_num_rows($request) > 0) {
            $group = new Group();
            $group->fields = mysqli_fetch_assoc($request);
            return $group;
        }
        return false;
    }
}
