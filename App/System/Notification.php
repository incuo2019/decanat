<?php

namespace App\System;

class Notification
{
    public static function add($notification)
    {
        $notifications = Session::get('notifications');
        if (is_array($notifications)) {
            array_push($notifications, $notification);
        } else {
            $notifications = array($notification);
        }

        Session::set('notifications', $notifications);
    }

    public static function get()
    {
        return Session::get('notifications');
    }

    public static function delete()
    {
        Session::del('notifications');
    }
}
