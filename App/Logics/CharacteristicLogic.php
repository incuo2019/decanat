<?php

namespace App\Logics;

use App\Models\Characteristic;
use App\Models\DocumentType;
use App\Models\Files;
use App\Models\Group;
use App\Models\User;
use App\System\Auth;
use App\System\Filters;
use App\System\Notification;
use App\System\Output;

class CharacteristicLogic
{
    public static function validation($characteristic = null)
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

                if ($count > 5 || $count < 1 || empty($date)) {
                    Notification::add('Одно или несколько полей некорректны');
                    return false;
                }

                if (!empty($characteristic) && !empty($characteristic->date_create)) {
                    if (!Output::checkDateInInterval($date, $characteristic->date_create)) {
                        Notification::add('Вы указали некорректную дату');
                        return false;
                    }
                } else {
                    if (!Output::checkDateInInterval($date)) {
                        Notification::add('Вы указали некорректную дату');
                        return false;
                    }
                }
            }

            if (empty($characteristic) && Characteristic::getByUserId(Auth::getId(), [1, 2])) {
                Notification::add('Ваша заявка ещё не обработана');
                exit(header('Location: /characteristics/'));
            }

            if (empty($characteristic)) {
                $characteristic = new Characteristic();
                $characteristic->init();
                $characteristic->creator_id = Auth::getId();
            }

            $characteristic->document_type_id = $document_type_id;
            $characteristic->date_preorder = $date;
            $characteristic->count = $count;
            $characteristic->date_create = date('Y-m-d H:i:s');
            $characteristic->status_id = 1;



            return $characteristic;
        }
        return false;
    }

    public static function validationResponse($characteristic)
    {
        if (!empty($_POST)) {
            if (!DocumentType::isDigital($characteristic->document_type_id)) {
                if (empty($_POST['comment'])) {
                    Notification::add('Укажите комментарий');
                    return false;
                }
                $comment = Filters::toString($_POST['comment']);
                if (empty($comment)) {
                    Notification::add('Одно или несколько полей некорректны');
                    return false;
                }

                $characteristic->comment = $comment;
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
                $file->for_id = $characteristic->creator_id;
                $file->category_id = 2;
                $file->source_id = $characteristic->id;
                $file->internal_id = 1;
                $file->date_create = date('Y-m-d H:i:s');

                if (!$file->save('file')) {
                    return false;
                }

                if (!empty($_POST['comment'] && !empty($comment = Filters::toString($_POST['comment'])))) {
                    $characteristic->comment = $comment;
                }
            }

            $characteristic->status_id = 3;

            if (!empty($_POST['id']) && !empty($id = Filters::toInt($_POST['id']))) {
                $characteristic->id = $id;
            }

            return $characteristic;
        }
        return false;
    }

    public static function uploadFile($characteristic, $internal_id)
    {
        if (isset($_FILES['file']) && $_FILES['file']['error'] != UPLOAD_ERR_NO_FILE) {
            if (Files::check_error('file')) {
                Notification::add('Не удалось загрузить файл');
                return false;
            }

            if (Files::check_size('file')) {
                Notification::add('Вы пытаетесь загрузить слишком большой файл');
                return false;
            }

            if ($old_file_id = Files::check(2, $characteristic->id ?? '', 0)) {
                $old_file = Files::getById($old_file_id);
                $old_file->delete();
            }

            $file = new Files();
            $file->init();
            $file->creator_id = Auth::getId();
            $file->for_id = $characteristic->creator_id;
            $file->category_id = 2;
            $file->source_id = $characteristic->id;
            $file->internal_id = $internal_id;
            $file->date_create = date('Y-m-d H:i:s');

            if (!$file->save('file')) {
                return false;
            }

            return true;
        }
        return true;
    }
}
