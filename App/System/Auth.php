<?php

namespace App\System;

use App\Models\User;

class Auth
{
    protected static User|null $user = null;
    protected static bool $auth = false;
    protected static bool $admin = false;
    protected static bool $moderator = false;
    protected static string $reference = '';

    public static function check()
    {
        Session::start();
        Session::update();
        if ($data = Session::get('User')) {
            $data = unserialize($data);

            self::$user = $data['user'] ?? null;
            self::$auth = $data['auth'] ?? false;
            self::$admin = $data['admin'] ?? false;
            self::$moderator = $data['moderator'] ?? false;
            self::$reference = $data['reference'] ?? '';

            if (!empty(self::$user)) {
                self::updateUser();
                self::addToSession();
            }
        } else {
            Session::restart();
            self::logout();
            Notification::add('Вы слишком долго бездействовали в системе');
        }
    }

    public static function isAuth()
    {
        return self::$auth;
    }

    public static function isAdmin()
    {
        return self::$admin;
    }

    public static function isModerator()
    {
        return self::$moderator;
    }

    public static function create(User $user)
    {
        self::$user = $user;
        self::$auth  = false;
        self::$admin  = false;
        self::$moderator  = false;
        self::addToSession();
    }

    public static function update(User $user)
    {
        self::$user = $user;
        self::addToSession();
    }

    public static function auth()
    {
        self::$auth  = true;
        self::addToSession();

        $logs = new Logs();
        $logs("Авторизация");
    }

    public static function admin()
    {
        if (self::isModerator()) {
            self::$admin = false;
            self::$moderator = false;

            $logs = new Logs();
            $logs("Режим пользователя ON");
        } else {
            if (self::getAccessLevel() > 1) {
                self::$admin = true;
                self::$moderator = true;

                $logs = new Logs();
                $logs("Режим администратора ON");
            } elseif (self::getAccessLevel() == 1) {
                self::$moderator = true;

                $logs = new Logs();
                $logs("Режим модератора ON");
            }
        }

        self::addToSession();
        return self::$admin;
    }

    public static function moderator()
    {
        if (self::getAccessLevel() > 0) {
            self::$moderator  = !self::$moderator;
            self::addToSession();

            if (self::$moderator) {
                $logs = new Logs();
                $logs("Режим модератора ON");
            } else {
                $logs = new Logs();
                $logs("Режим пользователя ON");
            }
            return self::$moderator;
        }
    }

    public static function logout()
    {
        $logs = new Logs();
        $logs("Выход из системы");

        self::$user = null;
        self::$auth  = false;
        self::$admin  = false;
        self::$moderator  = false;
        self::$reference  = '';
        self::addToSession();
    }

    public static function get()
    {
        return self::$user;
    }
    
    public static function getId()
    {
        return self::$user->id ?? null;
    }

    public static function getAccessLevel()
    {
        return self::$user->access_level ?? null;
    }

    public static function updateAccessLevel($access_level)
    {
        self::$user->access_level = $access_level;
        self::addToSession();
    }

    private static function addToSession()
    {
        Session::set("User", serialize(array(
            "user" => self::$user,
            "auth" => self::$auth,
            "admin" => self::$admin,
            "moderator" => self::$moderator,
            "reference" => self::$reference
        )));
    }

    public static function getReference()
    {
        if (!empty(self::$reference)) {
            $reference = self::$reference;
            self::$reference = '';
            self::addToSession();
            return $reference;
        }

        return $_SERVER['HTTP_REFERER'] ?? '/';
    }

    public static function saveReference()
    {
        self::$reference = $_SERVER['HTTP_REFERER'] ?? '';
        self::addToSession();
    }

    private static function updateUser()
    {
        self::$user = User::getById(self::$user->id);
        if (empty(self::$user)) {
            exit('session error');
        }
        if (self::isAdmin() && self::getAccessLevel() < 2) {
            self::$admin = false;
        }
        if (self::isModerator() && self::getAccessLevel() < 1) {
            self::$moderator = false;
        }
    }
}
