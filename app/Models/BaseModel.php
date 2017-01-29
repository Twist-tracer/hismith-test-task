<?php

namespace Models;

use Silex\Application;

class BaseModel
{
    public static $table_name = NULL;

    public $fields = [];

    public $db;

    public function __construct(Application $app) {
        $this->db = $app['db'];

        foreach ($this->fields['own'] as $key => $name) {
            $this->__set($key);
        }
    }

    public function save() {
        $values = $this->get_values();
        return $this->db->insert(static::$table_name, $values);
    }

    public function update($where = []) {
        $values = $this->get_values();
        if(empty($where)) {
            if(isset($this->id) && !empty($this->id)) {
                $where = ['id' => $this->id];
            } else {
                return FALSE;
            }
        }

        return $this->db->update(static::$table_name, $values, $where);
    }

    public function delete() {
        $values = $this->get_values();
        return $this->db->delete(static::$table_name, $values);
    }

    protected function get_values() {
        $values = [];
        foreach($this->fields['own'] as $key => $name) {
            if(!empty($this->$key)) {
                $values[$key] = $this->$key;
            }
        }

        return $values;
    }

    public function __set($name, $value = NULL)
    {
        $this->$name = $value;
    }

    protected function validate_id($id) {
        if (!is_numeric($id)) return FALSE;
        if ($id <= 0) return FALSE;
        return TRUE;
    }

    protected function validate_timestamp($timestamp) {
        if (!is_numeric($timestamp)) return FALSE;
        if ($timestamp < 0) return FALSE;
        return TRUE;
    }

}