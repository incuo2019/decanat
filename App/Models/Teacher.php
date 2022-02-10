<?php

namespace App\Models;

use App\System\Filters;

class Teacher extends Model
{
    protected const TABLE = 'teachers';

    public static function getByName($firstname, $middlename, $lastname) {
        $firstname = Filters::toString($firstname);
        $middlename = Filters::toString($middlename);
        $lastname = Filters::toString($lastname);
        
        global $sql;
        $query = 'SELECT * FROM `' . static::TABLE . '` 
            WHERE `firstname` = "' . $firstname . '" 
            AND `middlename` = "' . $middlename . '"
            AND `lastname` = "' . $lastname . '"
            AND is_removed = 0';
        $request = $sql->query($query) or die(mysqli_error($sql));

        if (mysqli_num_rows($request) > 0) {
            $group = new Group();
            $group->fields = mysqli_fetch_assoc($request);
            return $group;
        }
        return false;
    }
}
