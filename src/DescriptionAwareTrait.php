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
    protected $description;

    /**
     * hasDescription
     *
     * @return boolean
     */
    public function hasDescription()
    {
        return empty($this->description) ? false : true;
    }

    /**
     * getDescription
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * setDescription
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

}