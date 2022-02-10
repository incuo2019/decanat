<?php

use App\Models\User;
use App\System\Auth;
use App\System\System;

//error_reporting(0); // Print error off
error_reporting(E_ALL); // Print error on

$_SERVER['DOCUMENT_ROOT'] = $_SERVER['DOCUMENT_ROOT'] . '/';
require_once $_SERVER['DOCUMENT_ROOT'] . '/App/autoload.php';

define('ROOT', $_SERVER['DOCUMENT_ROOT']);
define('APP', $_SERVER['DOCUMENT_ROOT'] . 'App/');
define('FILES', $_SERVER['DOCUMENT_ROOT'] . 'Files/');
define('STATIC_FILES', $_SERVER['DOCUMENT_ROOT'] . 'StaticFiles/');
define('MODELS', $_SERVER['DOCUMENT_ROOT'] . 'App/Models/');
define('CONTROLLERS', $_SERVER['DOCUMENT_ROOT'] . 'App/Controllers/');
define('SYSTEM', $_SERVER['DOCUMENT_ROOT'] . 'App/System/');
define('NS_CONTROLLERS', 'App\\Controllers\\');

$settings = include_once(APP . 'settings.php');

foreach ($settings as $section => $setting) {
    ${$section} = $setting;
}

$db_settings = ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') ? $settings['db_local_settings'] : $settings['db_settings'];

$sql = mysqli_connect(
    $db_settings['hostname'],
    $db_settings['username'],
    $db_settings['password'],
    $db_settings['database']
);

$sql->set_charset("utf8mb4");
Auth::check();
$user = new User();

if (Auth::isAuth() && Auth::isAdmin() && !empty($_GET['debug']) && $_GET['debug'] == 'on') {
    System::debugModeOn();
}

if (Auth::isAuth() && Auth::isAdmin() && !empty($_GET['debug']) && $_GET['debug'] == 'off') {
    System::debugModeOff();
}
