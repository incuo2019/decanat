<?php

namespace App\System;

class System
{
    public static function isDebugMode()
    {
        if (!empty(Session::get('is_debug')) && Session::get('is_debug') == true) {
            return true;
        }
        return false;
    }

    public static function debugModeOn()
    {
        if (static::isDebugMode()) {
            return false;
        }

        Session::set('is_debug', true);
        return true;
    }

    public static function debugModeOff()
    {
        if (!static::isDebugMode()) {
            return false;
        }

        Session::del('is_debug');
        return true;
    }
}
