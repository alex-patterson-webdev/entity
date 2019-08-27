<?php

namespace Arp\Entity;

/**
 * DescriptionAwareTrait
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity
 */
trait DescriptionAwareTrait
{
    /**
     * $description
     *
     * @var string
     */
    protected $description = '';

    /**
     * hasDescription
     *
     * @return boolean
     */
    public function hasDescription() : bool
    {
        return empty($this->description) ? false : true;
    }

    /**
     * getDescription
     *
     * @return string
     */
    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * setDescription
     *
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

}