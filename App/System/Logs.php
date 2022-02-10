<?php

namespace App\System;

class Logs
{
    private static string $directory_path = APP . '/logs/';

    public function __invoke($log)
    {
        if (!file_exists(static::$directory_path)) {
            mkdir(static::$directory_path, 0777, true);
        }

        if ($id = Auth::getId()) {

            $log_text = "\n =============== " . date('d.m.Y H:i:s') . " =============== \n"
                . $log . "\n";

            $fp = fopen(static::$directory_path . $id . ".txt", "a");
            fwrite($fp, $log_text);
            fclose($fp);
        }
    }
}
