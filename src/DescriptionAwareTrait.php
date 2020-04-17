<?php

declare(strict_types=1);

namespace Arp\Entity;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity
 */
trait DescriptionAwareTrait
{
    /**
     * @var string
     */
    protected $description = '';

    /**
     * @return boolean
     */
    public function hasDescription(): bool
    {
        return empty($this->description) ? false : true;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}
