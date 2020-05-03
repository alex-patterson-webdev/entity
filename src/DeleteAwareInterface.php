<?php

declare(strict_types=1);

namespace Arp\Entity;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity
 */
interface DeleteAwareInterface extends EntityInterface
{
    /**
     * @return bool
     */
    public function isDeleted(): bool;

    /**
     * @param bool $deleted
     */
    public function setDeleted(bool $deleted): void;
}
