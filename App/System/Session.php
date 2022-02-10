<?php

namespace App\System;

class Session
{
    private static $lifetime = 7200; // секунд (2 часа)
    private static $cookieName = "cid";
    private static $started = false;

    public static function isCreated()
    {
        return (!empty($_COOKIE[self::$cookieName]) and ctype_alnum($_COOKIE[self::$cookieName])) ? true : false;
    }

    public static function start()
    {
        if (!self::$started) {
            if (!empty($_COOKIE[self::$cookieName]) and !ctype_alnum($_COOKIE[self::$cookieName])) {
                unset($_COOKIE[self::$cookieName]);
            }
            session_set_cookie_params(self::$lifetime, '/');
            session_name(self::$cookieName);
            session_start();
            self::$started = true;
        }
    }

    public static function update()
    {
        if (self::$started) {
            setcookie(session_name(), session_id(), time() + self::$lifetime);
        }
    }

    public static function set($name, $value)
    {
        if (self::$started) {
            $_SESSION[$name] = $value;
        } else {
            trigger_error('You should start Session first', E_USER_WARNING);
        }
    }

    public static function get($name)
    {
        if (self::$started) {
            return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
        } else {
            trigger_error('You should start Session first', E_USER_WARNING);
        }
    }

    public static function del($name)
    {
        if (self::$started) {
            unset($_SESSION[$name]);
        } else {
            trigger_error('You should start Session first', E_USER_WARNING);
        }
    }

    public static function clear()
    {
        if (self::$started) {
            unset($_SESSION);
        } else {
            trigger_error('You should start Session first', E_USER_WARNING);
        }
    }

    public static function destroy()
    {
        if (self::$started) {
            self::$started = false;
            unset($_COOKIE[self::$cookieName]);
            setcookie(self::$cookieName, '', 1, '/');
            session_destroy();
        } else {
            trigger_error('Session is not started!', E_USER_WARNING);
        }
    }

    public static function restart()
    {
        self::destroy();
        self::start();
    }

    public static function getSession()
    {
        if (self::$started) {
            return $_SESSION;
        } else {
            trigger_error('You should start Session first', E_USER_WARNING);
        }
    }

    public static function commit()
    {
        if (self::$started) {
            session_write_close();
            self::$started = false;
        } else {
            trigger_error('You should start Session first', E_USER_WARNING);
        }
    }
}
