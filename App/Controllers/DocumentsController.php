<?php

namespace App\Controllers;

use App\Logics\DocumentLogic;
use App\Models\Document;
use App\Models\DocumentClass;
use App\Models\DocumentStatus;
use App\Models\Files;
use App\Models\User;
use App\System\Auth;
use App\System\Filters;
use App\System\Logs;
use App\System\Notification;
use App\System\View;

abstract class DocumentsController
{
    protected static int $class_id = 0;

    public function __invoke()
    {
        if (!Auth::isAuth()) {
            Notification::add('Необходимо авторизоваться');
            exit(header('Location: /auth'));
        }

        $view = new View();

        if (Auth::isModerator()) {
            $documents = Document::getByStatusId(static::$class_id, [1, 2]);
        } else {
            $documents = Document::getByUserId(Auth::getId(), static::$class_id, [1, 2, 3]);
        }

        $view->documents = $documents;
        $view->is_processed = false;
        $view->class_id = static::$class_id;
        $view->View('Documents/index');
    }

    public function processed()
    {
        if (!Auth::isModerator()) {
            Notification::add('Доступ запрещён');
            exit(header('Location: /'));
        }

        if (!Auth::isAuth()) {
            Notification::add('Необходимо авторизоваться');
            exit(header('Location: /auth'));
        }

        $view = new View();

        $view->documents = Document::getByStatusId(static::$class_id, 3);
        $view->is_processed = true;
        $view->class_id = static::$class_id;
        $view->View('Documents/index');
    }

    public function new()
    {
        if (!Auth::isAuth()) {
            Notification::add('Необходимо авторизоваться');
            exit(header('Location: /auth'));
        }

        if (Document::getByUserId(Auth::getId(), static::$class_id, [1, 2])) {
            Notification::add('Ваш предыдущий запрос ещё не обработан');
            exit(header('Location: ' . DocumentClass::getById(static::$class_id)->link));
        }

        if ($document = DocumentLogic::validation(static::$class_id)) {
            $document->save();

            if (static::$class_id == 2) {
                if (!empty($_FILES) && $_FILES['file']['error'] != 4) {
                    if (!DocumentLogic::validationFile(static::$class_id, $document->id)) {
                        $document->delete();
                        exit(header('Location: ' . DocumentClass::getById(static::$class_id)->link . 'new/'));
                    }
                }
            }

            Notification::add('Запрос на ' . DocumentClass::getById(static::$class_id)->accusative_case . ' успешно создан');
            exit(header('Location: ' . DocumentClass::getById(static::$class_id)->link));
        }

        $view = new View();
        $view->title = 'Новый запрос ' . DocumentClass::getById(static::$class_id)->genitive_case;
        $view->user = Auth::get();
        $view->class_id = static::$class_id;
        $view->View('Documents/new');
    }

    public function edit($id)
    {
        if (!Auth::isAuth()) {
            Notification::add('Необходимо авторизоваться');
            exit(header('Location: /auth'));
        }

        $id = Filters::toInt($id);
        $document = Document::getById($id);

        if (empty($document)) {
            Notification::add('Запрос на ' . DocumentClass::getById(static::$class_id)->accusative_case . ' не найден');
            exit(header('Location: ' . DocumentClass::getById(static::$class_id)->link));
        }

        if ($edited_document = DocumentLogic::validation(static::$class_id, $document)) {

            if (!empty($_FILES) && $_FILES['file']['error'] != 4) {
                if (!DocumentLogic::validationFile(static::$class_id, $document->id)) {
                    exit(header('Location: ' . DocumentClass::getById(static::$class_id)->link . 'edit/' . $document->id));
                }
            }

            $edited_document->save();

            Notification::add('Запрос на ' . DocumentClass::getById(static::$class_id)->accusative_case . ' успешно изменен');
            exit(header('Location: ' . DocumentClass::getById(static::$class_id)->link));
        }

        if ($document->creator_id != Auth::getId() && !Auth::isModerator()) {
            Notification::add('Доступ запрещён');
            exit(header('Location: ' . DocumentClass::getById(static::$class_id)->link));
        }

        if ($document->status_id != 1) {
            Notification::add('Нельзя изменить заявку в обработке или обработанную заявку');
            exit(header('Location: ' . DocumentClass::getById(static::$class_id)->link));
        }

        $view = new View();
        $view->document = $document;
        $view->user = User::getById($document->creator_id);
        $view->title = 'Редактирование запроса ' . DocumentClass::getById(static::$class_id)->genitive_case;
        $view->View('Documents/edit');
    }
    public function remove($id)
    {
        if (!Auth::isAuth()) {
            Notification::add('Необходимо авторизоваться');
            exit(header('Location: /auth'));
        }

        $id = Filters::toInt($id);
        $document = Document::getById($id);

        if (empty($document)) {
            Notification::add('Запрос на ' . DocumentClass::getById(static::$class_id)->accusative_case . ' не найден');
            exit(header('Location: ' . DocumentClass::getById(static::$class_id)->link));
        }

        if ($document->creator_id != Auth::getId() && !Auth::isModerator()) {
            Notification::add('Доступ запрещён');
            exit(header('Location: ' . DocumentClass::getById(static::$class_id)->link));
        }

        if ($document->status_id != 1) {
            Notification::add('Нельзя удалить заявку в обработке или обработанную заявку');
            exit(header('Location: ' . DocumentClass::getById(static::$class_id)->link));
        }

        Files::remove(static::$class_id, $document->id, [0, 1]);
        $document->delete();
        Notification::add('Запрос на ' . DocumentClass::getById(static::$class_id)->accusative_case . ' успешно удален');
        exit(header('Location: ' . DocumentClass::getById(static::$class_id)->link));
    }

    public function view($id)
    {
        if (!Auth::isAuth()) {
            Notification::add('Необходимо авторизоваться');
            exit(header('Location: /auth'));
        }

        $id = Filters::toInt($id);
        $document = Document::getById($id);

        if (empty($document)) {
            Notification::add('Запрос на ' . DocumentClass::getById(static::$class_id)->accusative_case . ' не найден');
            exit(header('Location: ' . DocumentClass::getById(static::$class_id)->link));
        }

        if ($document->creator_id != Auth::getId() && !Auth::isModerator()) {
            Notification::add('Доступ запрещён');
            exit(header('Location: ' . DocumentClass::getById(static::$class_id)->link));
        }

        $view = new View();
        $view->document = $document;
        $view->user = User::getById($document->creator_id);
        $view->title = 'Запрос ' . DocumentClass::getById(static::$class_id)->genitive_case;
        $view->View('Documents/view');
    }

    public function accept($id)
    {
        if (!Auth::isModerator()) {
            $logs = new Logs();
            $logs("Попытка обращения к DocumentController / accept");

            Notification::add('Доступ запрешён');
            exit(header('Location: /auth'));
        }

        $id = Filters::toInt($id);
        $document = Document::getById($id);

        if (empty($document)) {
            Notification::add('Запрос на ' . DocumentClass::getById(static::$class_id)->accusative_case . ' не найден');
            exit(header('Location: ' . DocumentClass::getById(static::$class_id)->link));
        }

        switch ($document->status_id) {
            case 1: {
                    Notification::add('Запрос принят в обработку');
                    $document->changeStatus(2);
                    break;
                }
            case 2: {
                    Notification::add('Статус запроса успешно изменён');
                    $document->changeStatus(1);
                    break;
                }
            default: {
                    Notification::add('Запрос уже обработан');
                    break;
                }
        }

        exit(header('Location: ' . DocumentClass::getById(static::$class_id)->link));
    }

    public function response($id)
    {
        if (!Auth::isModerator()) {
            $logs = new Logs();
            $logs("Попытка обращения к DocumentController / accept");

            Notification::add('Доступ запрешён');
            exit(header('Location: /'));
        }

        $id = Filters::toInt($id);
        $document = Document::getById($id);

        if (empty($document)) {
            Notification::add('Запрос на ' . DocumentClass::getById(static::$class_id)->accusative_case . ' не найден');
            exit(header('Location: ' . DocumentClass::getById(static::$class_id)->link));
        }

        if (DocumentStatus::isNotProcessed($document->status_id)) {
            Notification::add('Запрос должен быть в обработке');
            exit(header('Location: ' . DocumentClass::getById(static::$class_id)->link));
        }

        if (DocumentStatus::isCompleted($document->status_id)) {
            Notification::add('Запрос уже обработан');
            exit(header('Location: ' . DocumentClass::getById(static::$class_id)->link));
        }

        if ($edited_document = DocumentLogic::validationResponse(static::$class_id, $document, 1)) {
            $edited_document->save();
            Notification::add('Запрос ' . DocumentClass::getById(static::$class_id)->genitive_case . ' успешно обработан');
            exit(header('Location: ' . DocumentClass::getById(static::$class_id)->link));
        }

        $view = new View();
        $view->document = $document;
        $view->user = User::getById($document->creator_id);
        $view->title = 'Обработка запроса ' . DocumentClass::getById(static::$class_id)->genitive_case;
        $view->View('Documents/response');
    }

    public function getFile($id)
    {
        if (!Auth::isAuth()) {
            Notification::add('Необходимо авторизоваться');
            exit(header('Location: /auth'));
        }

        $id = Filters::toInt($id);
        $document = Document::getById($id);

        if (empty($document)) {
            Notification::add('Запрос на ' . DocumentClass::getById(static::$class_id)->accusative_case . ' не найден');
            exit(header('Location: ' . DocumentClass::getById(static::$class_id)->link));
        }

        if ($document->creator_id != Auth::getId() && !Auth::isModerator()) {
            Notification::add('Доступ запрещён');
            exit(header('Location: ' . DocumentClass::getById(static::$class_id)->link));
        }

        if (Files::check(static::$class_id, $document->id)) {
            exit(Files::get(static::$class_id, $document->id));
        } else {
            Notification::add('Произошла ошибка при скачивании файла');
            exit(header('Location: ' . DocumentClass::getById(static::$class_id)->link));
        }
    }

    public function deleteFile($id)
    {
        if (!Auth::isAuth()) {
            Notification::add('Необходимо авторизоваться');
            exit(header('Location: /auth'));
        }

        $id = Filters::toInt($id);
        $document = Document::getById($id);

        if (empty($document)) {
            Notification::add('Запрос на ' . DocumentClass::getById(static::$class_id)->accusative_case . ' не найден');
            exit(header('Location: ' . DocumentClass::getById(static::$class_id)->link));
        }

        if ($document->creator_id != Auth::getId() && !Auth::isModerator()) {
            Notification::add('Доступ запрещён');
            exit(header('Location: ' . DocumentClass::getById(static::$class_id)->link));
        }

        if (Files::check(static::$class_id, $document->id)) {
            Files::remove(static::$class_id, $document->id);
            Notification::add('Файл успешно удалён');
            exit(header('Location: ' . DocumentClass::getById(static::$class_id)->link . 'edit/' . $document->id));
        } else {
            Notification::add('Произошла ошибка при удалении файла');
            exit(header('Location: ' . DocumentClass::getById(static::$class_id)->link . 'edit/' . $document->id));
        }
    }
}
