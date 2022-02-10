<?php

namespace App\System;

class Output
{
    public static function toPhone($str)
    {
        if (empty($str)) {
            return false;
        }
        $cleaned = preg_replace('/[^[:digit:]]/', '', $str);
        preg_match('/(\d{1})(\d{3})(\d{3})(\d{2})(\d{2})/', $cleaned, $matches);
        return "+{$matches[1]} ({$matches[2]}) {$matches[3]}-{$matches[4]}-{$matches[5]}";
    }
    public static function toDate($str, $separator = true)
    {
        if (empty($str)) {
            return false;
        }

        $cleaned = preg_replace('/[^[:digit:]]/', '', $str);
        preg_match('/(\d{4})(\d{2})(\d{2})/', $cleaned, $matches);
        return ($separator) ?
            "{$matches[3]}.{$matches[2]}.{$matches[1]}г"
            : "{$matches[3]}.{$matches[2]}.{$matches[1]}";
    }

    public static function getDate($date = null)
    {
        global $time_settings;
        $interval = $time_settings['interval_of_days'];
        $max_time = $time_settings['max_time_to_process_today'];

        if (empty($date)) {
            $time_now = date('H:i');
        } else {
            $time_now = date('H:i', strtotime($date));
        }

        $response = ['min_date' => '', 'max_date' => ''];
        if (strtotime($time_now) > strtotime($max_time)) {
            if (empty($date)) {
                $response['min_date'] = date('Y-m-d', strtotime('+1 day 00:00:00'));
                $response['max_date'] = date('Y-m-d', strtotime('+' . $interval . 'day 00:00:00'));
            } else {
                $response['min_date'] = date('Y-m-d', strtotime('+1 day 00:00:00', strtotime($date)));
                $response['max_date'] = date('Y-m-d', strtotime('+' . $interval . 'day 00:00:00', strtotime($date)));
            }
        } else {
            if (empty($date)) {
                $response['min_date'] = date("Y-m-d");
                $response['max_date'] = date('Y-m-d', strtotime('+' . ($interval - 1) . 'day 00:00:00'));
            } else {
                $response['min_date'] = date("Y-m-d", strtotime($date));
                $response['max_date'] = date('Y-m-d', strtotime('+' . ($interval - 1) . 'day 00:00:00', strtotime($date)));
            }
        }

        return $response;
    }

    public static function checkDateInInterval($checked_date, $reference_date = null)
    {
        if (!$checked_date = Filters::toDate($checked_date)) {
            return false;
        }

        if (is_null($reference_date)) {
            $interval_date = static::getDate();
        } else {
            $reference_date = Filters::toDate($reference_date);
            $interval_date = static::getDate($reference_date);
        }

        if (
            strtotime($checked_date) >= strtotime($interval_date['min_date'])
            && strtotime($checked_date) <= strtotime($interval_date['max_date'])
        ) {
            return true;
        }
        return false;
    }
}
