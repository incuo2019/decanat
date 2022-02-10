<?php

use App\Controllers\ExceptionsController;
use App\Models\User;
use App\System\Logs;

require_once $_SERVER['DOCUMENT_ROOT'] . '/App/init.php';

if($app_settings['is_enabled'] == false) {
    $controller = new ExceptionsController();
    $controller->e_disabled();
    exit;
}

require_once SYSTEM . 'Route.php';