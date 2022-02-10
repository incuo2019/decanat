<?php

namespace App\Logics;

use App\Models\Certificate;
use App\Models\DocumentType;
use App\Models\Files;
use App\Models\Group;
use App\Models\User;
use App\System\Auth;
use App\System\Filters;
use App\System\Notification;
use App\System\Output;

class CertificateLogic
{
    public static function validation($certificate = null)
    {
        if (!empty($_POST)) {
            if (empty($_POST['document_type_id'])) {
                Notification::add('Одно или несколько полей не были переданы');
                return false;
            }

            $document_type_id = Filters::toInt($_POST['document_type_id']);

            if (!DocumentType::isPaper($document_type_id)) {
                $count = null;
                $date = null;
            } else {
                if (empty($_POST['count']) || empty($_POST['date'])) {
                    Notification::add('Одно или несколько полей не были переданы');
                    return false;
                }

                $count = Filters::toInt($_POST['count']);
                $date = Filters::toDate($_POST['date']);

                if ($count > 5 || $count < 1 || !Filters::toDate($date)) {
                    Notification::add('Одно или несколько полей некорректны');
                    return false;
                }

                if (!Output::checkDateInInterval($date)) {
                    Notification::add('Вы указали некорректную дату');
                    return false;
                }
            }

            if (empty($certificate) && Certificate::getByUserId(Auth::getId(), [1, 2])) {
                Notification::add('Ваша заявка ещё не обработана');
                exit(header('Location: /certificates/'));
            }

            if (empty($certificate)) {
                $certificate = new Certificate();
                $certificate->init();
                $certificate->creator_id = Auth::getId();
            }

            $certificate->document_type_id = $document_type_id;
            $certificate->date_preorder = $date;
            $certificate->count = $count;
            $certificate->date_create = date('Y-m-d H:i:s');
            $certificate->status_id = 1;

            return $certificate;
        }
        return false;
    }

    public static function validationResponse($certificate)
    {
        if (!empty($_POST)) {

            if (!DocumentType::isDigital($certificate->document_type_id)) {
                if (empty($_POST['comment'])) {
                    Notification::add('Укажите комментарий');
                    return false;
                }
                $comment = Filters::toString($_POST['comment']);
                if (empty($comment)) {
                    Notification::add('Одно или несколько полей некорректны');
                    return false;
                }

                $certificate->comment = $comment;
            } else {
                if (Files::check_error('file')) {
                    Notification::add('Не удалось загрузить файл');
                    return false;
                }

                if (Files::check_size('file')) {
                    Notification::add('Вы пытаетесь загрузить слишком большой файл');
                    return false;
                }

                $file = new Files();
                $file->init();
                $file->creator_id = Auth::getId();
                $file->for_id = $certificate->creator_id;
                $file->category_id = 1;
                $file->source_id = $certificate->id;
                $file->internal_id = 1;
                $file->date_create = date('Y-m-d H:i:s');

                if (!$file->save('file')) {
                    return false;
                }

                if (!empty($_POST['comment'] && !empty($comment = Filters::toString($_POST['comment'])))) {
                    $certificate->comment = $comment;
                }
            }

            $certificate->status_id = 3;

            if (!empty($_POST['id']) && !empty($id = Filters::toInt($_POST['id']))) {
                $certificate->id = $id;
            }

            return $certificate;
        }
        return false;
    }
}
