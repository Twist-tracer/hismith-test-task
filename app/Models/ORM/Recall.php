<?php

namespace Models\ORM;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class - Guest
 * Model for Recalls
 *
 * @Entity
 * @Table(name="recalls")
 */
class Recall {

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @Column(type="string", length=45)
     */
    protected $text;

    /**
     * @Column(type="string", length=15)
     */
    protected $author_id;

    /**
     * @OneToMany(targetEntity="Recall", mappedBy="recall")
     */
    protected $date_create;

    /**
     * @OneToMany(targetEntity="Like", mappedBy="recall")
     */
    protected $likes;

    /**
     * @OneToOne(targetEntity="Guest", mappedBy="recall")
     */
    protected $author;

    /**
     * Get id
     *
     * @return integer
     */
    public function get_id()
    {
        return $this->id;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function get_text()
    {
        return $this->text;
    }

    /**
     * Get author_id
     *
     * @return integer
     */
    public function get_author_id()
    {
        return $this->author_id;
    }

    /**
     * Get likes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function get_likes()
    {
        return $this->likes;
    }

    /**
     * Get author
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function get_author()
    {
        return $this->author;
    }
}