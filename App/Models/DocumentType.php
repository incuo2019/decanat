<?php

namespace App\Models;

class DocumentType extends Model
{
    protected const TABLE = 'document_types';

    public static function isPaper($id)
    {
        $id = intval($id);
        if (!$type = static::getById($id)) {
            return false;
        }

        return boolval($type->is_paper);
    }
    public static function isDigital($id)
    {
        $id = intval($id);
        if (!$type = static::getById($id)) {
            return false;
        }

        return boolval($type->is_digital);
    }
}
