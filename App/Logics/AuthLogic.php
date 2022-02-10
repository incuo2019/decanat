<?php

namespace App\Logics;

use App\Models\User;
use App\System\Auth;
use App\System\Filters;
use App\System\Logs;
use App\System\Notification;

class AuthLogic
{
    public static function validationConsentPersonalData() {
        if(!empty($_POST) && !empty($_POST['personal_data']) && $_POST['personal_data'] == '1') {
            return true;
        }
        return false;
    }
    public static function validationLogin()
    {
        if (!empty($_POST) && !empty($_POST['login'])) {
            $login = $_POST['login'];

            if ($phone = Filters::toPhone($login)) {
                if ($user = User::getByPhone($phone)) {
                    return $user;
                }
            }

            if ($number_card = Filters::toNumberCard($login)) {
                if ($user = User::getByNumberCard($number_card)) {
                    return $user;
                }
            }
            Notification::add('Вы ввели неверный логин');
        }
        return false;
    }

    public static function validationPassword()
    {
        if (!empty($_POST)) {
            if (!empty($_POST['password'])) {
                $password = $_POST['password'];
                $password = Filters::toPassword($password);

                $user = Auth::get();

                if (!empty($user->password)) {
                    if ($password === $user->password) {
                        Auth::auth();
                        return $user;
                    }
                    Notification::add('Вы ввели неверный пароль');
                    return false;
                }

                $confirm_password = $_POST['confirm_password'];
                $confirm_password = Filters::toPassword($confirm_password);

                if ($password != $confirm_password) {
                    Notification::add('Пароли не совпадают');
                    return false;
                }

                $user->password = $password;
                $user->update();

                Notification::add('Вы успешно зарегистрировались');

                return $user;
            }
            Notification::add('Введите пароль');
            return false;
        }
        return false;
    }
}
