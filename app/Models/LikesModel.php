<?php

namespace Models;

use Silex\Application;

class LikesModel extends BaseModel
{
    public static $table_name = 'likes';

    public $fields = [
        'own' => [
            'recall_id' => 'ID отзыва',
            'guest_id' => 'ID гостя',
        ]
    ];

    public function __construct(Application $app) {
        parent::__construct($app);
    }

    public function save() {
        $success = FALSE;
        $action = '';

        if($this->validate()) {
            if(!$this->is_exists()) {
                $success = parent::save();
                $action = 'like';
            } else {
                $success = parent::delete();
                $action = 'dislike';
            }
        } else {
            $success = FALSE;
        }

        return [
            'success' => $success ? TRUE : FALSE,
            'action' => $action
        ];
    }

    public function is_exists() {
        $result = $this->db
            ->createQueryBuilder()
            ->select('COUNT(*) as count')
            ->from(self::$table_name)
            ->where('recall_id = ? AND guest_id = ?')
            ->setParameter(0, $this->recall_id)
            ->setParameter(1, $this->guest_id)
            ->setMaxResults(1)
            ->execute()
            ->fetch();

        return !empty($result) && $result['count'] > 0;
    }

    public function validate() {
        $result = FALSE;

        if(!empty($this->recall_id)) {
            $result = $this->validate_id($this->recall_id);
        }

        if(!empty($this->guset_id)) {
            $result = $this->validate_id($this->guset_id);
        }

        return $result;
    }
}