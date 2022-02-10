<?php

namespace App\System;

use DateTime;

class Filters
{
    public static function toString($value)
    {
        return preg_replace('/[^a-zA-Zа-яА-Я0-9 -]/u', '', $value);
    }

    public static function toInt($value)
    {
        return intval($value);
    }

    public static function toDouble($value)
    {
        return doubleval($value);
    }

    public static function toPhone($value)
    {
        $value = preg_replace('/[^0-9]/u', '', $value);
        $phone =  '7' . mb_substr($value, -10);
        return (iconv_strlen($phone) == 11) ? $phone : false;
    }

    public static function toNumberCard($value)
    {
        $value = $value = preg_replace('/[^0-9]/u', '', $value);
        return (iconv_strlen($value) == 6) ? $value : false;
    }

    public static function toDate($value)
    {
        if ($timestamp = strtotime($value)) {
            return date('Y-m-d', $timestamp);
        }

        if ($date = DateTime::createFromFormat('d.m.Yг', $value)) {
            return $date->format("Y-m-d");
        }

        return false;
    }

    public static function toDateTime($value)
    {
        if ($timestamp = strtotime($value)) {
            return date('Y-m-d H:i:s', $timestamp);
        }

        return false;
    }

    public static function toPassword($value)
    {
        return md5(md5($value));
    }

    public static function escape($value)
    {
        global $sql;
        return mysqli_real_escape_string($sql, $value);
    }

    public static function mb_ucfirst($string, $encoding = 'UTF-8')
    {
        $firstChar = mb_strtoupper(mb_substr($string, 0, 1, $encoding), $encoding);
        return $firstChar . mb_substr($string, 1, mb_strlen($string, $encoding), $encoding);
    }
}
