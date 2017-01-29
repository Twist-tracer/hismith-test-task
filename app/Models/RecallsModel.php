<?php

namespace Models;

use Silex\Application;

class RecallsModel extends BaseModel
{
    public static $table_name = 'recalls';

    public $fields = [
        'own' => [
            'id' => 'ID',
            'text' => 'Текст отзыва',
            'guest_id' => 'ID автора',
            'date_create' => 'Дата создания',
        ],
        'external' => [
            'author_name' => 'Имя автора',
            'likes' => 'Количество лайков'
        ]
    ];

    /**
     * Возвращает отзыв по id
     * @param $id
     * @return mixed
     */
    public function get_by_id($id) {
        return $this->db
            ->createQueryBuilder()
            ->select('r.id', 'r.text', 'r.date_create', 'g.name as author_name', 'COUNT(l.recall_id) as likes')
            ->from(self::$table_name, 'r')
            ->innerJoin('r', GuestsModel::$table_name, 'g', 'r.guest_id = g.id')
            ->leftJoin('r', LikesModel::$table_name, 'l', 'r.id = l.recall_id')
            ->where('r.id = ?')
            ->setParameter(0, $id)
            ->groupBy('r.id')
            ->execute()
            ->fetch();
    }

    public function get_next_id($id) {
        $result = $this->db
                    ->createQueryBuilder()
                    ->select('id')
                    ->from(self::$table_name)
                    ->where('id > ?')
                    ->setParameter(0, $id)
                    ->setMaxResults(1)
                    ->execute()
                    ->fetch();

        return $result['id'];
    }

    public function get_prev_id($id) {
        $result = $this->db
            ->createQueryBuilder()
            ->select('id')
            ->from(self::$table_name)
            ->where('id < ?')
            ->setParameter(0, $id)
            ->orderBy('id', 'DESC')
            ->setMaxResults(1)
            ->execute()
            ->fetch();

        return $result['id'];
    }

    /**
     * @param array $order
     * @return mixed
     */
    public function get_all($order = []) {
        $results = $this->db
                    ->createQueryBuilder()
                    ->select('r.id', 'r.text', 'r.date_create', 'g.name as author_name', 'COUNT(l.recall_id) as likes')
                    ->from(self::$table_name, 'r')
                    ->innerJoin('r', GuestsModel::$table_name, 'g', 'r.guest_id = g.id')
                    ->leftJoin('r', LikesModel::$table_name, 'l', 'r.id = l.recall_id')
                    ->groupBy('r.id');

        if(!empty($order)) {
            $results = $results->orderBy($order['sort'], $order['order']);
        }

        return $results->execute()->fetchAll();
    }

    public function get_titles_by_results($results) {
        $titles = [];
        if(isset($results[0])) {
            $results = $results[0];
        }
        foreach($results as $k => $v) {
            if(isset($this->fields['own'][$k])) {
                $titles[$k] = $this->fields['own'][$k];
            } else {
                $titles[$k] = $this->fields['external'][$k];
            }
        }

        return $titles;
    }

    public function save()
    {
        $this->text = htmlspecialchars($this->text);

        return parent::save();
    }

    public function validate() {
        $result = FALSE;

        if(!empty($this->id)) {
            $result = $this->validate_id($this->id);
        }

        $result = $this->validate_text($this->text);

        if($result && !empty($this->guest_id)) {
            $result = $this->validate_id($this->guest_id);
        }

        if($result && !empty($this->date_create)) {
            $result = $this->validate_timestamp($this->date_create);
        }

        return $result;
    }

    public function validate_text($text) {
        if(empty($text)) return FALSE;
        if(mb_strlen($text) > 5000) return FALSE;

        return TRUE;
    }

    public function validate_order($request) {
        $result = FALSE;

        if(isset($request['sort']) && isset($request['order'])) {
            if(
                (in_array($_GET['sort'], array_flip($this->fields['own'])) || (in_array($_GET['sort'], array_flip($this->fields['external']))))
                && in_array($_GET['order'], ['asc', 'desc'])
            ) {
                $result = TRUE;
            }
        }


        return $result;
    }
}