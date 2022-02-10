<?php

namespace App\Models;

use App\System\Logs;

abstract class Model implements \JsonSerializable
{
    protected const TABLE = '';
    protected array $no_fields = ['id'];
    protected array $fields = [];

    public function __get($field)
    {
        if (!empty($this->fields[$field])) {
            return $this->fields[$field];
        }

        return false;
    }

    public function __set($field, $value)
    {
        if (isset($this->fields[$field])) {
            $this->fields[$field] = $value;
        }
    }

    public function __isset($field)
    {
        return isset($this->fields[$field]);
    }

    protected function is_empty()
    {
        return empty($this->fields);
    }

    public function init()
    {
        global $sql;
        $query = 'SHOW COLUMNS FROM `' . static::TABLE . '`';
        $request = $sql->query($query) or die(mysqli_error($sql));
        $columns = [];

        while ($row = mysqli_fetch_assoc($request)) {
            $columns[$row['Field']] = '';
        }

        return $this->fields = $columns;
    }

    public function insert()
    {
        global $sql;
        $query = 'INSERT INTO `' . static::TABLE . '` SET ';
        $keys = array_keys($this->fields);
        $last_key = end($keys);

        foreach ($this->fields as $key => $value) {
            if (!in_array($key, $this->no_fields)) {
                $query .= "`" . $key . "` = '" . $value . "'";
                if ($key != $last_key) {
                    $query .= ',';
                }
            }
        }

        $sql->query($query) or die(mysqli_error($sql));

        $this->id = $sql->insert_id;
        return $this->id;
    }

    public function update()
    {
        global $sql;
        $query = 'UPDATE `' . static::TABLE . '` SET ';

        $keys = array_keys($this->fields);
        $last_key = end($keys);

        foreach ($this->fields as $key => $value) {
            if (!in_array($key, $this->no_fields)) {
                $query .= "`" . $key . "` = '" . $value . "'";
                if ($key != $last_key) {
                    $query .= ',';
                }
            }
        }

        $query .= " WHERE `id` = '" . $this->fields['id'] . "'";

        $result = $sql->query($query) or die(mysqli_error($sql));

        return $result;
    }

    public function save()
    {
        if (empty($this->fields['id'])) {
            return $this->insert();
        }

        return $this->update();
    }

    public function delete()
    {
        global $sql;
        $query = 'UPDATE `' . static::TABLE . '` SET is_removed = 1 WHERE `id` = ' . intval($this->fields['id']);
        $sql->query($query) or die(mysqli_error($sql));
    }

    public static function getAll()
    {
        global $sql;
        $query = 'SELECT * FROM `' . static::TABLE . '` WHERE is_removed = 0';
        $request = $sql->query($query) or die(mysqli_error($sql));

        if (mysqli_num_rows($request) > 0) {
            $list = [];
            while ($element = mysqli_fetch_assoc($request)) {
                $model = new static();
                $model->fields = $element;
                $list[$model->id] = $model;
            }
            return $list;
        }
        return false;
    }

    public static function getById($id)
    {
        if (empty($id)) {
            return false;
        }

        global $sql;
        $query = 'SELECT * FROM `' . static::TABLE . '` WHERE `id` = ' . intval($id) . ' AND is_removed = 0 LIMIT 1';
        $request = $sql->query($query) or die(mysqli_error($sql));

        if (mysqli_num_rows($request) > 0) {
            $model = new static();
            $model->fields = mysqli_fetch_assoc($request);
            return $model;
        }
        return false;
    }

    protected static function truncate()
    {
        global $sql;
        $query = 'TRUNCATE TABLE `' . static::TABLE . '`';
        $request = $sql->query($query) or die(mysqli_error($sql));
    }

    public function jsonSerialize(): string
    {
        return json_encode(get_object_vars($this) ?? [], JSON_UNESCAPED_UNICODE);
    }

    public static function getTableName()
    {
        return static::TABLE;
    }
}
