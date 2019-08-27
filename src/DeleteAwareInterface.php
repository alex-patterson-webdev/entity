<?php

namespace Arp\Entity;

/**
 * DeleteAwareInterface
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity
 */
interface DeleteAwareInterface
{
    /**
     * isDeleted
     *
     * @return boolean
     */
    public function isDeleted() : bool;

    /**
     * setDeleted
     *
     * @param boolean $deleted
     */
    public function setDeleted(bool $deleted);

}