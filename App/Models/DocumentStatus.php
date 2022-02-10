<?php

namespace App\Models;

class DocumentStatus extends Model
{
    protected const TABLE = 'document_statuses';

    public static function isCompleted($id)
    {
        $id = intval($id);
        if (!$status = static::getById($id)) {
            return false;
        }

        return boolval($status->is_completed);
    }

    public static function isNotProcessed($id)
    {
        $id = intval($id);
        if (!$status = static::getById($id)) {
            return false;
        }

        return boolval($status->is_not_processed);
    }

    public static function isAccepted($id)
    {
        $id = intval($id);
        if (!$status = static::getById($id)) {
            return false;
        }

        return boolval($status->is_accepted);
    }
}
