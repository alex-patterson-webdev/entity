<?php

namespace Arp\Entity;

/**
 * EntityTrait
 *
 * Default trait implementation of the \Arp\Entity\EntityInterface
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity
 */
trait EntityTrait
{
    /**
     * $id
     *
     * @var integer|string
     */
    protected $id;

    /**
     * getId
     *
     * Return the entity's identity.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * setId
     *
     * Set the entity's identity.
     *
     * @param mixed  $id  The identity that should be set.
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * hasId
     *
     * Check if the identity has been set.
     *
     * @return boolean
     */
    public function hasId() : bool
    {
        return empty($this->id) ? false : true;
    }

    /**
     * isId
     *
     * Check if the provided id matches the $id provided.
     *
     * @param mixed  $id  The identity that should be compared.
     *
     * @return boolean
     */
    public function isId($id) : bool
    {
        return ($id === $this->id);
    }

}