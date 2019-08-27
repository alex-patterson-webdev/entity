<?php

namespace Arp\Entity;

/**
 * DescriptionAwareInterface
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity
 */
interface DescriptionAwareInterface
{
    /**
     * hasDescription
     *
     * @return boolean
     */
    public function hasDescription() : bool ;

    /**
     * getDescription
     *
     * @return string
     */
    public function getDescription() : string ;

    /**
     * setDescription
     *
     * @param string $description
     */
    public function setDescription(string $description);
}