<?php

namespace App\Controllers;

use App\Models\Files;
use App\Models\User;
use App\System\Auth;
use App\System\Filters;
use App\System\Notification;
use App\System\View;

class FilesController extends BaseController
{
    protected static $require_auth = true;

    public function get($file_hash)
    {
        if (empty($file_hash = Filters::toString($file_hash))) {
            (new View())->view('Exceptions/index');
            exit;
        }

        if (!($file = Files::getByHash($file_hash))) {
            Notification::add('Файл не найден');
            exit(header("Location: /"));
        }

        if (!Auth::isModerator() && Auth::getId() != $file->creator_id && Auth::getId() != $file->for_id) {
            Notification::add('Доступ запрещён');
            exit(header("Location: /"));
        }

        if (!$file->readFile()) {
            Notification::add('Ошибка при загрузке файла');
            exit(header("Location: /"));
        }
    }

    public function remove($file_hash)
    {
        if (empty($file_hash = Filters::toString($file_hash))) {
            (new View())->view('Exceptions/index');
            exit;
        }

        if (!($file = Files::getByHash($file_hash))) {
            Notification::add('Файл не найден');
            exit(header("Location: /"));
        }

        if (!Auth::isModerator() && Auth::getId() != $file->creator_id) {
            Notification::add('Доступ запрещён');
            exit(header("Location: /"));
        }

        if ($file->delete()) {
            Notification::add('Файл успешно удалён');
            exit(header("Location: " . $_SERVER['HTTP_REFERER']));
        } else {
            Notification::add('Доступ запрещён');
            exit(header("Location: /"));
        }
    }

    public function static($id)
    {
        $id = Filters::escape($id);
        $id = str_replace("../", "", $id);

        if (empty($id)) {
            (new View())->view('Exceptions/index');
            exit;
        }

        if (!Files::getStatic($id)) {
            Notification::add('Доступ запрещён');
            exit(header("Location: /"));
        }
    }

    public function test($id)
    {
        echo '<pre>';
        print_r(basename($id));
        echo '</pre>';
        exit;
    }
}
