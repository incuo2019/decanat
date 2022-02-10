<?php

namespace App\Models;

use App\System\Notification;

class Files extends Model
{
    protected const TABLE = 'files';

    // -- Do not delete! These fields are related to settings --
    private static $KB = 1024;
    private static $MB = 1048576;
    private static $GB = 1073741824;
    private static $TB = 1099511627776;
    // ---------------------------------------------------------

    // global $file_settings['allowed_extension'];

    public static function getByHash($file_hash)
    {
        if (iconv_strlen($file_hash) != 32) {
            return false;
        }

        global $sql;
        $query = "SELECT * FROM " . static::TABLE . " WHERE file_hash = '" . $file_hash . "'";
        $request = $sql->query($query) or die(mysqli_error($sql));

        if (mysqli_num_rows($request) == 0) {
            return false;
        }

        $model = new static();
        $model->fields = mysqli_fetch_assoc($request);
        return $model;
    }

    public static function getHash($file_id)
    {
        if (empty($file_id)) {
            return false;
        }

        global $sql;

        $query = $sql->query('SELECT file_hash FROM ' . static::TABLE . ' 
        WHERE id = "' . $file_id . '"') or die($sql->error);

        if (mysqli_num_rows($query) < 1) {
            return false;
        }

        $file = mysqli_fetch_assoc($query);

        return $file['file_hash'];
    }

    public static function getStatic($id)
    {
        $file_path = realpath(STATIC_FILES . $id);

        // Вышли за пределы STATIC_FILES/ (../)
        if (dirname($file_path) != realpath(STATIC_FILES)) {
            return false;
        }

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

    public static function check($category_id, $source_id, $internal_id = 0)
    {
        if (empty($category_id) || empty($source_id)) {
            return false;
        }

        global $sql;
        $query = $sql->query('SELECT * FROM ' . static::TABLE . ' 
            WHERE category_id = "' . $category_id . '" 
            AND source_id = "' . $source_id . '"
            AND internal_id = "' . $internal_id . '"
            AND is_removed = "0"') or die($sql->error);

        if (mysqli_num_rows($query) < 1) {
            return false;
        }

        $file = mysqli_fetch_assoc($query);

        $path = '/' . substr($file['file_hash'], 0, 2) . '/' . substr($file['file_hash'], 2, 2) . '/';
        $name = substr($file['file_hash'], 4);

        $file_path = FILES . $path . $name . '.' . $file['extension'];

        if (file_exists($file_path)) {
            return $file['id'];
        }
        return false;
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

    public function save($post_name = 'files')
    {
        if (empty($post_name) || empty($this->category_id) || empty($this->source_id)) {
            return false;
        }

        if (empty($_FILES[$post_name])) {
            Notification::add('Файл не был загружен');
            return false;
        }

        global $file_settings;

        $this->extension = pathinfo($_FILES[$post_name]['name'], PATHINFO_EXTENSION);

        if (!in_array($this->extension, $file_settings['allowed_extension'])) {
            Notification::add('Недопустимое расширение файла');
            return false;
        }

        $this->file_hash = md5(rand(PHP_INT_MIN, PHP_INT_MAX));
        $path = '/' . substr($this->file_hash, 0, 2) . '/' . substr($this->file_hash, 2, 2) . '/';
        $name = substr($this->file_hash, 4);

        if (!file_exists(FILES . $path)) {
            mkdir(FILES . $path, 0777, true);
        }

        if (move_uploaded_file($_FILES[$post_name]['tmp_name'], FILES . $path . $name . '.' . $this->extension)) {
            return parent::save();
        }

        Notification::add('Неудалось сохранить файл');
        return false;
    }

    public function delete()
    {
        parent::delete();
        return true;
    }

    public function readFile()
    {
        $path = '/' . substr($this->file_hash, 0, 2) . '/' . substr($this->file_hash, 2, 2) . '/';
        $name = substr($this->file_hash, 4);
        $file_path = FILES . $path . $name . '.' . $this->extension;

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
}
