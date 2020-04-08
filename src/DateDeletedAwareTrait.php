<?php

declare(strict_types=1);

namespace Arp\Entity;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity
 */
class DateDeletedAwareTrait
{
    /**
     * @var \DateTime|null
     */
    protected $dateDeleted;

    /**
     * Check if the deleted date has been set.
     *
     * @return boolean
     */
    public function hasDateDeleted(): bool
    {
        return isset($this->dateDeleted);
    }

    /**
     * Return the deleted date.
     *
     * @return \DateTime|null
     */
    public function getDateDeleted(): ?\DateTime
    {
        return $this->dateDeleted;
    }

    /**
     * Set the deleted date.
     *
     * @param \DateTime|null $dateTime
     */
    public function setDateDeleted(?\DateTime $dateTime): void
    {
        $this->dateDeleted = $dateTime;
    }
}
