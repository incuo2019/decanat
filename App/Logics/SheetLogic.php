<?php

namespace App\Logics;

use App\Models\Sheet;
use App\Models\DocumentType;
use App\Models\Files;
use App\System\Auth;
use App\System\Filters;
use App\System\Notification;
use App\System\Output;

class SheetLogic
{
    public static function validation($sheet = null)
    {
        if (!empty($_POST)) {
            if (empty($_POST['subject_id']) || empty($_POST['date'])) {
                Notification::add('Одно или несколько полей не были переданы');
                return false;
            }

            $subject_id = Filters::toInt($_POST['subject_id']);
            $date = Filters::toDate($_POST['date']);

            if (empty($subject_id) || empty($date)) {
                Notification::add('Одно или несколько полей некорректны');
                return false;
            }

            if (!Output::checkDateInInterval($date)) {
                Notification::add('Вы указали некорректную дату');
                return false;
            }

            if (empty($sheet) && Sheet::getByUserId(Auth::getId(), [1, 2])) {
                Notification::add('Ваша заявка ещё не обработана');
                exit(header('Location: /sheets/'));
            }

            if (empty($sheet)) {
                $sheet = new Sheet();
                $sheet->init();
                $sheet->creator_id = Auth::getId();
            }

            $sheet->subject_id = $subject_id;
            $sheet->date_preorder = $date;
            $sheet->date_create = date('Y-m-d H:i:s');
            $sheet->status_id = 1;

            return $sheet;
        }
        return false;
    }

    public static function validationResponse($sheet)
    {
        if (!empty($_POST)) {
            $comment = Filters::toString($_POST['comment']);

            $sheet->comment = $comment;

            $sheet->status_id = 3;

            if (!empty($_POST['id']) && !empty($id = Filters::toInt($_POST['id']))) {
                $sheet->id = $id;
            }

            return $sheet;
        }
        return false;
    }
}
