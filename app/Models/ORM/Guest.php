<?php

namespace Models\ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * Class - Guest
 * Model for Guests
 *
 * @Entity
 * @Table(name="guests")
 */
class Guest {

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @Column(type="string", length=45)
     */
    protected $name;

    /**
     * @Column(type="string", length=15)
     */
    protected $ip;

    /**
     * @OneToMany(targetEntity="Recall", mappedBy="guest")
     */
    protected $recalls;

    /**
     * @OneToMany(targetEntity="Like", mappedBy="guest")
     */
    protected $likes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

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
     * Get name
     *
     * @return integer
     */
    public function get_name()
    {
        return $this->name;
    }

    /**
     * Get ip
     *
     * @return integer
     */
    public function get_ip()
    {
        return $this->ip;
    }

    /**
     * Add recall
     *
     * @param \Models\ORM\Recall $recall
     *
     * @return Guest
     */
    public function add_recall(\Models\ORM\Recall $recall)
    {
        $this->recalls[] = $recall;
        return $this;
    }

    /**
     * Get recalls
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function get_recalls()
    {
        return $this->recalls;
    }
}