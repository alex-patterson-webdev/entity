<?php

declare(strict_types=1);

namespace Arp\Entity;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity
 */
interface DateCreatedAwareInterface extends EntityInterface
{
    /**
     * Check if the created date has been defined.
     *
     * @return boolean
     */
    public function hasDateCreated(): bool;

    /**
     * Return the created date.
     *
     * @return \DateTimeInterface|null
     */
    public function getDateCreated(): ?\DateTimeInterface;

    /**
     * Set the created date.
     *
     * @param \DateTimeInterface|null $dateCreated
     */
    public function setDateCreated(?\DateTimeInterface $dateCreated): void;
}
