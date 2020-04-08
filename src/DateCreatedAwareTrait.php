<?php

declare(strict_types=1);

namespace Arp\Entity;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity
 */
trait DateCreatedAwareTrait
{
    /**
     * @var \DateTime|null
     */
    protected $dateCreated;

    /**
     * Check if the created date has been defined.
     *
     * @return bool
     */
    public function hasDateCreated(): bool
    {
        return isset($this->dateCreated);
    }

    /**
     * Return the created date.
     *
     * @return \DateTime|null
     */
    public function getDateCreated(): ?\DateTime
    {
        return $this->dateCreated;
    }

    /**
     * Set the created date.
     *
     * @param \DateTime|null $dateCreated
     */
    public function setDateCreated(?\DateTime $dateCreated): void
    {
        $this->dateCreated = $dateCreated;
    }
}
