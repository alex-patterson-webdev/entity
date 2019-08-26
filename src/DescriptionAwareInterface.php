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
    public function hasDescription();

    /**
     * getDescription
     *
     * @return string
     */
    public function getDescription();

    /**
     * setDescription
     *
     * @param string $description
     */
    public function setDescription($description);
}