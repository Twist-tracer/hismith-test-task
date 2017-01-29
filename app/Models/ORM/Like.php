<?php

namespace Models\ORM;

/**
 * Class - Like
 * Model for Likes
 *
 * @Entity
 * @Table(name="likes")
 */
class Like {

    /**
     * @Column(type="integer")
     */
    protected $recall_id;

    /**
     * @Column(type="integer")
     */
    protected $guest_id;

    /**
     * Get recall id
     *
     * @return integer
     */
    public function get_recall_id()
    {
        return $this->recall_id;
    }

    /**
     * Get guest id
     *
     * @return integer
     */
    public function get_guest_id()
    {
        return $this->guest_id;
    }

}