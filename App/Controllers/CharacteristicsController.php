<?php

namespace App\Controllers;

use App\Logics\CharacteristicLogic;
use App\Models\Characteristic;
use App\Models\CharacteristicTypes;
use App\Models\DocumentStatus;
use App\Models\DocumentType;
use App\Models\Files;
use App\Models\User;
use App\System\Auth;
use App\System\Filters;
use App\System\Notification;
use App\System\Output;
use App\System\View;

class CharacteristicsController extends BaseController
{
    protected static $require_auth = true;

    public function __invoke()
    {
        $view = new View();

        if (Auth::isModerator()) {
            $view->characteristics = Characteristic::getByStatusId([1, 2]);
        } else {
            $view->characteristics = Characteristic::getByUserId(Auth::getId(), [1, 2, 3]);
        }
        $view->title = 'Запросы характеристик';
        $view->addSection('Characteristics/index');
        $view->View();
    }

    public function completed()
    {
        if (!Auth::isModerator()) {
            Notification::add('Доступ запрещён');
            exit(header('Location: /'));
        }

        $view = new View();
        $view->characteristics = Characteristic::getByStatusId([3]);
        $view->is_completed = true;
        $view->title = 'Обработанные запросы характеристик';
        $view->addSection('Characteristics/index');
        $view->View();
    }

    public function new()
    {
        if (!empty($_POST)) {
            if ($сharacteristic = CharacteristicLogic::validation()) {
                $characteristic_id = $сharacteristic->save();
                Notification::add('Запрос характеристики успешно создан');

                if (!CharacteristicLogic::uploadFile($сharacteristic, 0)) {
                    Notification::add('Не удалось загрузить файл 1');
                    exit(header('Location: /characteristics/edit/' . $characteristic_id));
                }

                exit(header('Location: /characteristics'));
            }
        }

        if (Characteristic::getByUserId(Auth::getId(), [1, 2])) {
            Notification::add('Ваш предыдущий запрос ещё не обработан');
            exit(header('Location: /characteristics'));
        }

        $view = new View();
        $view->user = Auth::get();
        $view->dates = Output::getDate();
        $view->is_new = true;
        $view->title = 'Новый запрос характеристики';
        $view->view('Characteristics/edit');
    }

    public function edit($id)
    {
        $id = Filters::toInt($id);

        $characteristic = Characteristic::getById($id);

        if (empty($characteristic)) {
            Notification::add('Запрос характеристики не найден');
            exit(header('Location: /characteristics'));
        }

        if (!empty($_POST)) {
            if ($edited_characteristic = CharacteristicLogic::validation($characteristic)) {
                $edited_characteristic->save();
                Notification::add('Запрос характеристики успешно изменён');

                if (!CharacteristicLogic::uploadFile($edited_characteristic, 0)) {
                    Notification::add('Не удалось загрузить файл');
                    exit(header('Location: /characteristics/edit/' . $edited_characteristic->id));
                }

                exit(header('Location: /characteristics'));
            }
        }

        if ($characteristic->creator_id != Auth::getId() && !Auth::isModerator()) {
            Notification::add('Доступ запрещён');
            exit(header('Location: /characteristics'));
        }

        if ($characteristic->status_id != 1) {
            Notification::add('Нельзя изменить заявку принятую в обработку');
            exit(header('Location: /characteristics'));
        }

        $view = new View();
        $view->characteristic = $characteristic;
        $view->user = User::getById($characteristic->creator_id);
        $view->dates = Output::getDate($characteristic->date_create);

        if ($file_id = Files::check(2, $characteristic->id ?? '', 0)) {
            if ($file_hash = Files::getHash($file_id)) {
                $view->file_hash = $file_hash;
            }
        }

        $view->title = 'Редактирование запроса характеристики';
        $view->View('Characteristics/edit');
    }

    public function remove($id)
    {
        $id = Filters::toInt($id);
        $characteristic = Characteristic::getById($id);

        if (empty($characteristic)) {
            Notification::add('Запрос характеристики не найден');
            exit(header('Location: /characteristics'));
        }

        if ($characteristic->creator_id != Auth::getId() && !Auth::isModerator()) {
            Notification::add('Доступ запрещён');
            exit(header('Location: /characteristics'));
        }

        if ($characteristic->status_id != 1) {
            Notification::add('Нельзя удалить заявку принятую в обработку');
            exit(header('Location: /characteristics'));
        }

        $characteristic->delete();
        Notification::add('Запрос характеристики успешно удалён');
        exit(header('Location: /characteristics'));
    }

    public function view($id)
    {
        $id = Filters::toInt($id);
        $characteristic = Characteristic::getById($id);

        if (empty($characteristic)) {
            Notification::add('Запрос характеристики не найден');
            exit(header('Location: /characteristics'));
        }

        if ($characteristic->creator_id != Auth::getId() && !Auth::isModerator()) {
            Notification::add('Доступ запрещён');
            exit(header('Location: /characteristics'));
        }

        $view = new View();
        $view->characteristic = $characteristic;
        $view->user = User::getById($characteristic->creator_id);
        $view->title = 'Запрос характеристики';

        if ($file_id = Files::check(2, $characteristic->id ?? '', 0)) {
            if ($file_hash = Files::getHash($file_id)) {
                $view->first_file_hash = $file_hash;
            }
        }

        if (DocumentType::isDigital($characteristic->document_type_id ?? '')) {
            if ($file_id = Files::check(2, $characteristic->id ?? '', 1)) {
                if ($file_hash = Files::getHash($file_id)) {
                    $view->second_file_hash = $file_hash;
                }
            }
        }

        $view->addSection('Characteristics/view');
        $view->addSection('Documents/block_buttons_change');
        $view->view();
    }

    public function accept($id)
    {
        if (!Auth::isModerator()) {
            Notification::add('Доступ запрешён');
            exit(header('Location: /auth'));
        }

        $id = Filters::toInt($id);
        $characteristic = Characteristic::getById($id);

        if (empty($characteristic)) {
            Notification::add('Запрос характеристики не найден');
            exit(header('Location: /characteristics'));
        }

        if (DocumentStatus::isNotProcessed($characteristic->status_id)) {
            Notification::add('Запрос принят в обработку');
            $characteristic->changeStatus(2);
        } else if (DocumentStatus::isAccepted($characteristic->status_id)) {
            Notification::add('Статус запроса успешно изменён');
            $characteristic->changeStatus(1);
        } else {
            Notification::add('Запрос уже обработан');
        }

        exit(header('Location: /characteristics'));
    }

    public function response($id)
    {
        if (!Auth::isModerator()) {
            Notification::add('Доступ запрешён');
            exit(header('Location: /'));
        }

        $id = Filters::toInt($id);
        $characteristic = Characteristic::getById($id);


        if (empty($characteristic)) {
            Notification::add('Запрос характеристики не найден');
            exit(header('Location: /characteristics'));
        }

        if (DocumentStatus::isNotProcessed($characteristic->status_id)) {
            Notification::add('Запрос должен быть в обработке');
            exit(header('Location: /characteristics'));
        }

        if (DocumentStatus::isCompleted($characteristic->status_id)) {
            Notification::add('Запрос уже обработан');
            exit(header('Location: /characteristics'));
        }

        if ($edited_characteristic = CharacteristicLogic::validationResponse($characteristic)) {
            $edited_characteristic->save();
            Notification::add('Запрос характеристики успешно обработан');
            exit(header('Location: /characteristics'));
        }

        $view = new View();

        if ($file_id = Files::check(2, $characteristic->id ?? '', 0)) {
            if ($file_hash = Files::getHash($file_id)) {
                $view->file_hash = $file_hash;
            }
        }

        $view->characteristic = $characteristic;
        $view->user = User::getById($characteristic->creator_id);
        $view->title = 'Обработка запроса характеристики';
        $view->view('Characteristics/response');
    }

    public function templates()
    {
        $view = new View();
        $view->title = 'Шаблоны характеристик';
        $view->is_flex = false;
        $view->templates = CharacteristicTypes::getAll();
        $view->view('Characteristics/templates');
    }
}
