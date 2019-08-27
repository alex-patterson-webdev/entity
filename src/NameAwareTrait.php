<?php

namespace Arp\Entity;

/**
 * NameAwareTrait
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity
 */
trait NameAwareTrait
{
    /**
     * $name
     *
     * @var string
     */
    protected $name;

    /**
     * getName
     *
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * setName
     *
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }
}