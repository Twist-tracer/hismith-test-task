<?php

namespace Models;

use Silex\Application;

class GuestsModel extends BaseModel
{
    public static $table_name = 'guests';

    public $fields = [
        'own' => [
            'id' => 'ID',
            'name' => 'Имя гостя',
            'ip' => 'IP Гостя'
        ]
    ];

    public function __construct(Application $app) {
        parent::__construct($app);
    }

    public function save() {
        if(!$this->is_exists()) {
            parent::save();
        } else {
            return TRUE;
        }
    }

    public function is_exists() {
        $guest = $this->get_by_ip();

        return !empty($guest);
    }

    public function get_by_ip() {
        return $this->db
                    ->createQueryBuilder()
                    ->select('*')
                    ->from(self::$table_name)
                    ->where('ip = ?')
                    ->setParameter(0, $this->ip)
                    ->execute()
                    ->fetch();
    }

    public function get_id_by_ip() {
        $result = $this->db
                    ->createQueryBuilder()
                    ->select('id')
                    ->from(self::$table_name)
                    ->where('ip = ?')
                    ->setParameter(0, $this->ip)
                    ->execute()
                    ->fetch();

        return $result ? $result['id'] : FALSE;
    }

    public function validate() {
        $result = FALSE;

        if(!empty($this->id)) {
            $result = $this->validate_id($this->id);
        }

        if($result && !empty($this->name)) {
            $result = $this->validate_name($this->name);
        }

        if($result && !empty($this->ip)) {
            $result = $this->validate_ip($this->name);
        }

        return $result;
    }

    public function validate_name($name) {
        if(mb_strlen($name) > 50) return FALSE;

        return TRUE;
    }

    public function validate_ip($name) {
        if(mb_strlen($name) > 15) return FALSE;

        return TRUE;
    }

    public function __set($name, $value = NULL)
    {
        $this->$name = $value;
    }

}