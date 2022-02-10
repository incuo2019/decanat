<?php

use App\Controllers\MainController;
use App\Models\Files;
use App\System\Filters;
use App\System\Notification;
use App\System\System;
use App\System\View;

$request_url = $_SERVER['REQUEST_URI'];

if (strpos($request_url, '?')) {
    $request_url = substr($request_url, 0, strrpos($request_url, "?"));
}

$request_url = ltrim($request_url, '/');
$request_url = rtrim($request_url, '/');
$url = explode('/', $request_url);

$controller = ucfirst($url[0]);
$controller = (!empty($url[0])) ? ('App\Controllers\\' . $controller . 'Controller') : '';

$method = (!empty($url[1])) ? $url[1] : '';
$id = (!empty($url[2])) ? $url[2] : '';

if (empty($controller)) {
    $controller = new MainController();
    $controller();
    exit;
}

if (!empty($controller) && class_exists($controller)) {
    $reflectionClass = new ReflectionClass($controller);

    if ($reflectionClass->isAbstract()) {
        (new View())->view('Exceptions/403');
        exit;
    }

    if (empty($method)) {
        $controller = new $controller();
        $controller();
        exit;
    }

    if (strcasecmp($controller, NS_CONTROLLERS . 'ExceptionsController') == 0) {
        if (!empty($method)) {
            $method = 'e_' . $method;
        }
    }

    if ($method == '__construct' || $method == '__destruct') {
        (new View())->view('Exceptions/403');
        exit;
    }

    $controller = new $controller();
    if (method_exists($controller, $method)) {
        if (empty($id)) {
            $reflectionMethod = new ReflectionMethod($controller, $method);
            if ($reflectionMethod->getNumberOfRequiredParameters() == 0) {
                $controller->$method();
                exit;
            }
            if (System::isDebugMode()) {
                Notification::add('Route 1: ' . $method . ' / ' . $id . "\nrequest_url: " . $request_url);
            }
        }

        if ($id > 0) {
            $controller->$method($id);
            exit;
        }

        (new View())->view('Exceptions/404');
        exit;
    }
}

if (System::isDebugMode()) {
    Notification::add('Route 2: ' . $method . ' / ' . $id . "\nrequest_url: " . $request_url);
}

(new View())->view('Exceptions/404');
exit;
