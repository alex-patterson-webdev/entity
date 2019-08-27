<?php

namespace Arp\Entity;

/**
 * EntityInterface
 *
 * Generic interface for Entity data transfer object.
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity
 */
interface EntityInterface
{
    /**
     * getId
     *
     * Return the entity's identity.
     *
     * @return mixed
     */
    public function getId();

    /**
     * setId
     *
     * Set the entity's identity.
     *
     * @param mixed  $id  The identity that should be set.
     */
    public function setId($id);

    /**
     * hasId
     *
     * Check if the identity has been set.
     *
     * @return boolean
     */
    public function hasId() : bool;

    /**
     * isId
     *
     * Check if the provided id matches the $id provided.
     *
     * @param mixed $id  The identity that should be compared.
     *
     * @return boolean
     */
    public function isId($id) : bool;
}