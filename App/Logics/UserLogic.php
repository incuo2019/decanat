<?php

namespace App\Logics;

use App\Models\User;
use App\System\Auth;
use App\System\Filters;
use App\System\Notification;

class UserLogic
{
    public static function validation($user = null)
    {
        if (!empty($_POST)) {
            if (
                empty($_POST['number_card']) || empty($_POST['firstname']) || empty($_POST['middlename'])
                || empty($_POST['lastname']) || empty($_POST['department_id'] || empty($_POST['birth_date']))
            ) {
                Notification::add('Одно или несколько полей не были переданы');
                return false;
            }

            $number_card = Filters::toNumberCard($_POST['number_card']);
            $firstname = Filters::toString($_POST['firstname']);
            $middlename = Filters::toString($_POST['middlename']);
            $lastname = Filters::toString($_POST['lastname']);
            $department_id = Filters::toInt($_POST['department_id']);
            $group_id = Filters::toInt($_POST['group_id']);
            $birth_date = Filters::toDate($_POST['birth_date']);

            if (
                empty($number_card) || empty($firstname) || empty($middlename) || empty($lastname)
                || empty($department_id) || empty($birth_date) || mb_strlen($number_card) != 6
            ) {
                Notification::add('Одно или несколько полей некорректны');
                return false;
            }

            if (!empty($_POST['id'])) {
                $id = Filters::toInt($_POST['id']);
            }

            if (!empty($_POST['phone'])) {
                $phone = Filters::toPhone($_POST['phone']);
                if (empty($phone)) {
                    Notification::add('Одно или несколько полей некорректны');
                    return false;
                }
            }

            if (empty($user)) {
                $user = new User();
                $user->init();
            }

            $user->number_card = $number_card;
            $user->firstname = $firstname;
            $user->middlename = $middlename;
            $user->lastname = $lastname;
            $user->department_id = $department_id;
            $user->group_id = $group_id;
            $user->birth_date = $birth_date;

            if (!empty($id)) {
                $user->id = $id;
            }
            if (!empty($phone)) {
                $user->phone = $phone;
            }

            return $user;
        }
        return false;
    }
}
