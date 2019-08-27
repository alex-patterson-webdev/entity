<?php

namespace Arp\Entity;

/**
 * NameAwareInterface
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity
 */
interface NameAwareInterface
{
    /**
     * getName
     *
     * @return string
     */
    public function getName() : string;

    /**
     * setName
     *
     * @param string $name
     */
    public function setName(string $name);

}