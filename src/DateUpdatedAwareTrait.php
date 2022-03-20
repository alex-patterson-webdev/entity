<?php

declare(strict_types=1);

namespace Arp\Entity;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity
 */
trait DateUpdatedAwareTrait
{
    /**
     * @var \DateTimeInterface|null
     */
    protected ?\DateTimeInterface $dateUpdated = null;

    /**
     * Check if the updated date has been set.
     *
     * @return boolean
     */
    public function hasDateUpdated(): bool
    {
        return isset($this->dateUpdated);
    }

    /**
     * Return the updated date.
     *
     * @return \DateTimeInterface|null
     */
    public function getDateUpdated(): ?\DateTimeInterface
    {
        return $this->dateUpdated;
    }

    /**
     * Set the updated date.
     *
     * @param \DateTimeInterface|null $dateTime
     */
    public function setDateUpdated(?\DateTimeInterface $dateTime): void
    {
        $this->dateUpdated = $dateTime;
    }
}
