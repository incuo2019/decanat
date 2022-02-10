<?php

namespace App\Controllers;

use App\Logics\CertificateLogic;
use App\Models\Certificate;
use App\Models\Document;
use App\Models\DocumentStatus;
use App\Models\DocumentType;
use App\Models\Files;
use App\Models\User;
use App\System\Auth;
use App\System\Filters;
use App\System\Notification;
use App\System\Output;
use App\System\View;

class CertificatesController extends BaseController
{
    protected static $require_auth = true;

    public function __invoke()
    {
        $view = new View();

        if (Auth::isModerator()) {
            $view->certificates = Certificate::getByStatusId([1, 2]);
        } else {
            $view->certificates = Certificate::getByUserId(Auth::getId(), [1, 2, 3]);
        }
        $view->title = 'Запросы справок об обучении';
        $view->addSection('Certificates/index');
        $view->View();
    }

    public function completed()
    {
        if (!Auth::isModerator()) {
            Notification::add('Доступ запрещён');
            exit(header('Location: /'));
        }

        $view = new View();
        $view->certificates = Certificate::getByStatusId([3]);
        $view->is_completed = true;
        $view->title = 'Обработанные запросы справок об обучении';
        $view->addSection('Certificates/index');
        $view->View();
    }

    public function new()
    {
        if (!empty($_POST)) {
            if ($certificate = CertificateLogic::validation()) {
                $certificate->save();

                Notification::add('Запрос справки об обучении успешно создан');
                exit(header('Location: /certificates'));
            }
        }

        if (Certificate::getByUserId(Auth::getId(), [1, 2])) {
            Notification::add('Ваш предыдущий запрос ещё не обработан');
            exit(header('Location: /certificates'));
        }

        $view = new View();
        $view->user = Auth::get();
        $view->dates = Output::getDate();
        $view->title = 'Новый запрос справки об обучении';
        $view->view('Certificates/edit');
    }

    public function edit($id)
    {
        $id = Filters::toInt($id);

        $certificate = Certificate::getById($id);

        if (empty($certificate)) {
            Notification::add('Запрос справки об обучении не найден');
            exit(header('Location: /certificates'));
        }

        if (!empty($_POST)) {
            if ($edited_certificate = CertificateLogic::validation($certificate)) {
                $edited_certificate->save();
                Notification::add('Запрос справки об обучении успешно изменён');
                exit(header('Location: /certificates'));
            }
        }

        if ($certificate->creator_id != Auth::getId() && !Auth::isModerator()) {
            Notification::add('Доступ запрещён');
            exit(header('Location: /certificates'));
        }

        if ($certificate->status_id != 1) {
            Notification::add('Нельзя изменить заявку принятую в обработку');
            exit(header('Location: /certificates'));
        }

        $view = new View();
        $view->certificate = $certificate;
        $view->user = User::getById($certificate->creator_id);
        $view->dates = Output::getDate($certificate->date_create);
        $view->title = 'Редактирование запроса справки об обучении';
        $view->View('certificates/edit');
    }

    public function remove($id)
    {
        $id = Filters::toInt($id);
        $certificate = Certificate::getById($id);

        if (empty($certificate)) {
            Notification::add('Запрос справки об обучении не найден');
            exit(header('Location: /certificates'));
        }

        if ($certificate->creator_id != Auth::getId() && !Auth::isModerator()) {
            Notification::add('Доступ запрещён');
            exit(header('Location: /certificates'));
        }

        if ($certificate->status_id != 1) {
            Notification::add('Нельзя удалить заявку принятую в обработку');
            exit(header('Location: /certificates'));
        }

        $certificate->delete();
        Notification::add('Запрос справки об обучении успешно удалён');
        exit(header('Location: /certificates'));
    }

    public function view($id)
    {
        $id = Filters::toInt($id);
        $certificate = Certificate::getById($id);

        if (empty($certificate)) {
            Notification::add('Запрос справки об обучении не найден');
            exit(header('Location: /certificates'));
        }

        if ($certificate->creator_id != Auth::getId() && !Auth::isModerator()) {
            Notification::add('Доступ запрещён');
            exit(header('Location: /certificates'));
        }

        $view = new View();
        $view->certificate = $certificate;
        $view->user = User::getById($certificate->creator_id);
        $view->title = 'Запрос справки об обучении';


        if (DocumentType::isDigital($certificate->document_type_id ?? '')) {
            if ($file_id = Files::check(1, $certificate->id ?? '', 1)) {
                if ($file_hash = Files::getHash($file_id)) {
                    $view->file_hash = $file_hash;
                }
            }
        }

        $view->addSection('Certificates/view');
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
        $certificate = Certificate::getById($id);

        if (empty($certificate)) {
            Notification::add('Запрос справки об обучении не найден');
            exit(header('Location: /certificates'));
        }

        if (DocumentStatus::isNotProcessed($certificate->status_id)) {
            Notification::add('Запрос принят в обработку');
            $certificate->changeStatus(2);
        } else if (DocumentStatus::isAccepted($certificate->status_id)) {
            Notification::add('Статус запроса успешно изменён');
            $certificate->changeStatus(1);
        } else {
            Notification::add('Запрос уже обработан');
        }

        exit(header('Location: /certificates'));
    }

    public function response($id)
    {
        if (!Auth::isModerator()) {
            Notification::add('Доступ запрешён');
            exit(header('Location: /'));
        }

        $id = Filters::toInt($id);
        $certificate = Certificate::getById($id);


        if (empty($certificate)) {
            Notification::add('Запрос справки об обучении не найден');
            exit(header('Location: /certificates'));
        }

        if (DocumentStatus::isNotProcessed($certificate->status_id)) {
            Notification::add('Запрос должен быть в обработке');
            exit(header('Location: /certificates'));
        }

        if (DocumentStatus::isCompleted($certificate->status_id)) {
            Notification::add('Запрос уже обработан');
            exit(header('Location: /certificates'));
        }

        if ($edited_certificate = CertificateLogic::validationResponse($certificate)) {
            $edited_certificate->save();
            Notification::add('Запрос справки об обучении успешно обработан');
            exit(header('Location: /certificates'));
        }

        $view = new View();
        $view->certificate = $certificate;
        $view->user = User::getById($certificate->creator_id);
        $view->title = 'Обработка запроса справки об обучении';
        $view->View('Certificates/response');
    }
}
