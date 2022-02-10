<?php

namespace App\Models;

use App\Models\Model;
use App\System\Auth;
use App\System\Filters;
use App\System\Notification;

class FilesOld
{
    // -- Do not delete! These fields are related to settings --
    private static $KB = 1024;
    private static $MB = 1048576;
    private static $GB = 1073741824;
    private static $TB = 1099511627776;
    // ---------------------------------------------------------

    private const ALLOWED_EXTENSION = array('jpg', 'png', 'pdf', 'doc', 'docx', 'rtf');

    public const TABLE = 'files';

    public static function readfile($file_path)
    {
        if (file_exists($file_path)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_path));
            readfile($file_path);
            return true;
        }
        return false;
    }
    
    public static function get($category_id, $source_id, $internal_id = 0)
    {
        if (empty($category_id) || empty($source_id)) {
            return false;
        }
        global $sql;
        $query = $sql->query('SELECT * FROM ' . static::TABLE . ' 
            WHERE category_id = "' . $category_id . '" 
            AND source_id = "' . $source_id . '"
            AND internal_id = "' . $internal_id . '"') or die($sql->error);

        if (mysqli_num_rows($query) < 1) {
            return false;
        }

        $file = mysqli_fetch_assoc($query);
        $file_path = FILES . $file['path'] . $file['name'] . '.' . $file['extension'];

        return self::readfile($file_path);
    }

    public static function getByHash($file_hash)
    {
        if (empty($file_hash)) {
            return false;
        }

        global $sql;

        $path = '/' . substr($file_hash, 0, 2) . '/' . substr($file_hash, 2, 2) . '/';
        $name = substr($file_hash, 4);

        $query = $sql->query('SELECT * FROM ' . static::TABLE . ' 
        WHERE path = "' . $path . '" 
        AND name = "' . $name . '"') or die($sql->error);

        if (mysqli_num_rows($query) < 1) {
            return false;
        }

        $file = mysqli_fetch_assoc($query);

        $file_path = FILES . $file['path'] . $file['name'] . '.' . $file['extension'];

        if (file_exists($file_path)) {
            return $file_path;
        }

        return false;
    }

    public static function getTemplates($id)
    {
        if (empty($id)) {
            return false;
        }

        $id = Filters::escape($id);
        global $sql;

        $query = $sql->query('SELECT * FROM ' . CharacteristicTypes::getTableName() . ' 
            WHERE id = "' . $id . '"') or die($sql->error);

        if (mysqli_num_rows($query) < 1) {
            return false;
        }

        $template = mysqli_fetch_assoc($query);

        $file_path = FILES . $template['file_path'];

        if (file_exists($file_path)) {
            return self::readfile($file_path);
        }

        return false;
    }

    public static function removeByHash($file_hash)
    {
        if (empty($file_hash)) {
            return false;
        }

        global $sql;

        $path = '/' . substr($file_hash, 0, 2) . '/' . substr($file_hash, 2, 2) . '/';
        $name = substr($file_hash, 4);

        $query = $sql->query('SELECT * FROM ' . static::TABLE . ' 
        WHERE path = "' . $path . '" 
        AND name = "' . $name . '"') or die($sql->error);

        if (mysqli_num_rows($query) < 1) {
            return false;
        }

        $file = mysqli_fetch_assoc($query);

        return self::removeById($file['id']);
    }

    public static function getHash($file_id)
    {
        if (empty($file_id)) {
            return false;
        }

        global $sql;

        $query = $sql->query('SELECT path, name FROM ' . static::TABLE . ' 
        WHERE id = "' . $file_id . '"') or die($sql->error);

        if (mysqli_num_rows($query) < 1) {
            return false;
        }

        $file = mysqli_fetch_assoc($query);
        $file_hash = $file['path'] . $file['name'];

        return str_replace('/', '', $file_hash);
    }

    public static function check($category_id, $source_id, $internal_id = 0)
    {
        if (empty($category_id) || empty($source_id)) {
            return false;
        }
        global $sql;
        $query = $sql->query('SELECT * FROM ' . static::TABLE . ' 
            WHERE category_id = "' . $category_id . '" 
            AND source_id = "' . $source_id . '"
            AND internal_id = "' . $internal_id . '"') or die($sql->error);

        if (mysqli_num_rows($query) < 1) {
            return false;
        }

        $file = mysqli_fetch_assoc($query);
        $file_path = FILES . $file['path'] . $file['name'] . '.' . $file['extension'];

        if (file_exists($file_path)) {
            return $file['id'];
        }
        return false;
    }

    public static function remove($category_id, $source_id, $internal_id = 0)
    {
        if (empty($category_id) || empty($source_id)) {
            return false;
        }
        global $sql;
        $query = 'SELECT * FROM ' . static::TABLE . ' 
        WHERE category_id = "' . $category_id . '" 
        AND source_id = "' . $source_id . '"';

        if (is_array($internal_id)) {
            $query .= ' AND internal_id IN(' . implode(',', $internal_id) . ')';
        } else {
            $query .= ' AND internal_id = "' . $internal_id . '"';
        }

        $response = $sql->query($query) or die($sql->error);

        if (mysqli_num_rows($response) < 1) {
            return false;
        }

        while ($file = mysqli_fetch_assoc($response)) {
            $sql->query('DELETE FROM ' . static::TABLE . ' WHERE id = ' . $file['id']);
            unlink(FILES . $file['path'] . $file['name'] . '.' . $file['extension']);
            return true;
        }
        return false;
    }


    public static function removeById($id)
    {
        if (empty($id)) {
            return false;
        }
        global $sql;
        $query = $sql->query('SELECT * FROM ' . static::TABLE . ' 
            WHERE id = ' . $id);

        if (mysqli_num_rows($query) < 1) {
            return false;
        }

        $file = mysqli_fetch_assoc($query);
        $file_path = FILES . $file['path'] . $file['name'] . '.' . $file['extension'];

        if (file_exists($file_path)) {
            unlink($file_path);
        }

        $sql->query('DELETE FROM ' . static::TABLE . ' 
            WHERE id = ' . $id) or die($sql->error);

        return true;
    }

    public static function check_error($post_name)
    {
        if ($_FILES) {
            if ($_FILES[$post_name]["error"] == UPLOAD_ERR_OK) {
                return false;
            }
            return true;
        }
        return true;
    }

    public static function check_size($post_name)
    {
        if ($_FILES) {
            global $file_settings;
            if ($_FILES[$post_name]['size'] > $file_settings['max_file_size'] * static::${$file_settings['unit']}) {
                return true;
            }
            return false;
        }
        return true;
    }

    public static function save($post_name, $category_id, $source_id, $internal_id = 0)
    {
        if (empty($post_name) || empty($category_id) || empty($source_id)) {
            return false;
        }

        if (empty($_FILES[$post_name])) {
            Notification::add('Файл не был загружен');
            return false;
        }

        $ext = pathinfo($_FILES[$post_name]['name'], PATHINFO_EXTENSION);
        if (!in_array($ext, static::ALLOWED_EXTENSION)) {
            Notification::add('Недопустимое расширение файла');
            return false;
        }

        $file_hash = md5(rand(PHP_INT_MIN, PHP_INT_MAX));
        $path = '/' . substr($file_hash, 0, 2) . '/' . substr($file_hash, 2, 2) . '/';
        $name = substr($file_hash, 4);

        if (!file_exists(FILES . $path)) {
            mkdir(FILES . $path, 0777, true);
        }

        if (move_uploaded_file($_FILES[$post_name]['tmp_name'], FILES . $path . $name . '.' . $ext)) {
            global $sql;

            if (empty($internal_id)) {
                self::remove($category_id, $source_id, $internal_id);
            }

            $query = $sql->query('INSERT INTO `' . static::TABLE . '` SET 
                    path = "' . $path . '",
                    name = "' . $name . '",
                    extension = "' . $ext . '",
                    category_id = "' . $category_id . '",
                    source_id = "' . $source_id . '",
                    internal_id = "' . $internal_id . '"') or die($sql->error);

            return (bool)$query;
        }

        Notification::add('Неудалось сохранить файл');
        return false;
    }
}
