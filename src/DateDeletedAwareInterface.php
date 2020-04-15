<?php

declare(strict_types=1);

namespace Arp\Entity;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity
 */
interface DateDeletedAwareInterface extends EntityInterface
{
    /**
     * Check if the deleted date has been set.
     *
     * @return boolean
     */
    public function hasDateDeleted(): bool;

    /**
     * Return the deleted date.
     *
     * @return \DateTime|null
     */
    public function getDateDeleted(): ?\DateTime;

    /**
     * Set the deleted date.
     *
     * @param \DateTime|null $dateTime
     */
    public function setDateDeleted(?\DateTime $dateTime): void;
}
