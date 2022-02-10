<?php

use App\Controllers\ExceptionsController;

spl_autoload_register(
    function ($class) {
        $file = $_SERVER['DOCUMENT_ROOT'] . str_replace("\\", "/", $class) . ".php";
        if (file_exists($file)) {
            require $file;
        } else {
            $controller = new ExceptionsController();
            $controller->e_404('autoload: filename - ' . $file);
        }
    }
);
