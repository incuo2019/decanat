<?php

namespace App\Controllers;

use App\Logics\SheetLogic;
use App\Models\Curriculum;
use App\Models\Sheet;
use App\Models\DocumentStatus;
use App\Models\DocumentType;
use App\Models\Files;
use App\Models\Subject;
use App\Models\User;
use App\System\Auth;
use App\System\Filters;
use App\System\Notification;
use App\System\Output;
use App\System\View;

class SheetsController extends BaseController
{
    protected static $require_auth = true;

    public function __invoke()
    {
        $view = new View();

        if (Auth::isModerator()) {
            $view->sheets = Sheet::getByStatusId([1, 2]);
        } else {
            $view->sheets = Sheet::getByUserId(Auth::getId(), [1, 2, 3]);
        }
        $view->title = 'Запросы индивидуальных экзаменационных ведомостей';
        $view->subjects = Subject::getAll();
        $view->addSection('Sheets/index');
        $view->View();
    }

    public function completed()
    {
        if (!Auth::isModerator()) {
            Notification::add('Доступ запрещён');
            exit(header('Location: /'));
        }

        $view = new View();
        $view->sheets = Sheet::getByStatusId([3]);
        $view->is_completed = true;
        $view->title = 'Обработанные запросы индивидуальных экзаменационных ведомостей';
        $view->subjects = Subject::getAll();
        $view->addSection('Sheets/index');
        $view->View();
    }

    public function new()
    {
        if (!empty($_POST)) {
            if ($sheet = SheetLogic::validation()) {
                $sheet->save();

                Notification::add('Запрос индивидуальной экзаменационной ведомости успешно создан');
                exit(header('Location: /sheets'));
            }
        }

        if (Sheet::getByUserId(Auth::getId(), [1, 2])) {
            Notification::add('Ваш предыдущий запрос ещё не обработан');
            exit(header('Location: /sheets'));
        }

        $view = new View();
        $view->user = Auth::get();
        $view->dates = Output::getDate();
        $view->title = 'Новый запрос индивидуальной экзаменационной ведомости';
        $view->curriculum = Curriculum::getByGroupId(Auth::get()->group_id);
        $view->subjects = Subject::getAll();
        $view->view('Sheets/edit');
    }

    public function edit($id)
    {
        $id = Filters::toInt($id);

        $sheet = Sheet::getById($id);

        if (empty($sheet)) {
            Notification::add('Запрос индивидуальной экзаменационной ведомости не найден');
            exit(header('Location: /sheets'));
        }

        if (!empty($_POST)) {
            if ($edited_sheet = SheetLogic::validation($sheet)) {
                $edited_sheet->save();
                Notification::add('Запрос индивидуальной экзаменационной ведомости успешно изменён');
                exit(header('Location: /sheets'));
            }
        }

        if ($sheet->creator_id != Auth::getId() && !Auth::isModerator()) {
            Notification::add('Доступ запрещён');
            exit(header('Location: /sheets'));
        }

        if ($sheet->status_id != 1) {
            Notification::add('Нельзя изменить заявку принятую в обработку');
            exit(header('Location: /sheets'));
        }

        $view = new View();
        $view->sheet = $sheet;
        $view->user = User::getById($sheet->creator_id);
        $view->dates = Output::getDate($sheet->date_create);
        $view->title = 'Редактирование запроса индивидуальной экзаменационной ведомости';
        $view->curriculum = Curriculum::getByGroupId(User::getById($sheet->creator_id)->group_id);
        $view->subjects = Subject::getAll();
        $view->View('sheets/edit');
    }

    public function remove($id)
    {
        $id = Filters::toInt($id);
        $sheet = Sheet::getById($id);

        if (empty($sheet)) {
            Notification::add('Запрос индивидуальной экзаменационной ведомости не найден');
            exit(header('Location: /sheets'));
        }

        if ($sheet->creator_id != Auth::getId() && !Auth::isModerator()) {
            Notification::add('Доступ запрещён');
            exit(header('Location: /sheets'));
        }

        if ($sheet->status_id != 1) {
            Notification::add('Нельзя удалить заявку принятую в обработку');
            exit(header('Location: /sheets'));
        }

        $sheet->delete();
        Notification::add('Запрос индивидуальной экзаменационной ведомости успешно удалён');
        exit(header('Location: /sheets'));
    }

    public function view($id)
    {
        $id = Filters::toInt($id);
        $sheet = Sheet::getById($id);

        if (empty($sheet)) {
            Notification::add('Запрос индивидуальной экзаменационной ведомости не найден');
            exit(header('Location: /sheets'));
        }

        if ($sheet->creator_id != Auth::getId() && !Auth::isModerator()) {
            Notification::add('Доступ запрещён');
            exit(header('Location: /sheets'));
        }

        $view = new View();
        $view->sheet = $sheet;
        $view->user = User::getById($sheet->creator_id);
        $view->title = 'Запрос индивидуальной экзаменационной ведомости';
        $view->subject = Subject::getById($sheet->subject_id);

        if (DocumentType::isDigital($sheet->document_type_id ?? '')) {
            if ($file_id = Files::check(1, $sheet->id ?? '', 1)) {
                if ($file_hash = Files::getHash($file_id)) {
                    $view->file_hash = $file_hash;
                }
            }
        }

        $view->addSection('Sheets/view');
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
        $sheet = Sheet::getById($id);

        if (empty($sheet)) {
            Notification::add('Запрос индивидуальной экзаменационной ведомости не найден');
            exit(header('Location: /sheets'));
        }

        if (DocumentStatus::isNotProcessed($sheet->status_id)) {
            Notification::add('Запрос принят в обработку');
            $sheet->changeStatus(2);
        } else if (DocumentStatus::isAccepted($sheet->status_id)) {
            Notification::add('Статус запроса успешно изменён');
            $sheet->changeStatus(1);
        } else {
            Notification::add('Запрос уже обработан');
        }

        exit(header('Location: /sheets'));
    }

    public function response($id)
    {
        if (!Auth::isModerator()) {
            Notification::add('Доступ запрешён');
            exit(header('Location: /'));
        }

        $id = Filters::toInt($id);
        $sheet = Sheet::getById($id);


        if (empty($sheet)) {
            Notification::add('Запрос индивидуальной экзаменационной ведомости не найден');
            exit(header('Location: /sheets'));
        }

        if (DocumentStatus::isNotProcessed($sheet->status_id)) {
            Notification::add('Запрос должен быть в обработке');
            exit(header('Location: /sheets'));
        }

        if (DocumentStatus::isCompleted($sheet->status_id)) {
            Notification::add('Запрос уже обработан');
            exit(header('Location: /sheets'));
        }

        if ($edited_sheet = SheetLogic::validationResponse($sheet)) {
            $edited_sheet->save();
            Notification::add('Запрос индивидуальной экзаменационной ведомости успешно обработан');
            exit(header('Location: /sheets'));
        }

        $view = new View();
        $view->sheet = $sheet;
        $view->user = User::getById($sheet->creator_id);
        $view->title = 'Обработка запроса индивидуальной экзаменационной ведомости';
        $view->subject = Subject::getById($sheet->subject_id);
        $view->View('Sheets/response');
    }
}
