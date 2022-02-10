<?php

namespace App\Models;

use App\System\Filters;

class User extends Model
{
    protected const TABLE = 'users';

    public static function getByPhone($phone)
    {
        $phone = Filters::toPhone($phone);
        global $sql;
        $query = 'SELECT * FROM `' . static::TABLE . '` WHERE `phone` = "' . $phone . '" AND is_removed = 0';
        $request = $sql->query($query) or die(mysqli_error($sql));

        if (mysqli_num_rows($request) > 0) {
            $user = new User();
            $user->fields = mysqli_fetch_assoc($request);
            return $user;
        }
        return false;
    }

    public static function getByNumberCard($number_card)
    {
        $number_card = Filters::toNumberCard($number_card);
        global $sql;
        $query = 'SELECT * FROM `' . static::TABLE . '` WHERE `number_card` = "' . $number_card . '" AND is_removed = 0';
        $request = $sql->query($query) or die(mysqli_error($sql));
        if (mysqli_num_rows($request) > 0) {
            $user = new User();
            $user->fields = mysqli_fetch_assoc($request);
            return $user;
        }
        return false;
    }
}
