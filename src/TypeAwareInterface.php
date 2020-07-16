<?php

declare(strict_types=1);

namespace Arp\Entity;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity
 */
interface TypeAwareInterface
{
    /**
     * Compare the type with the provided $type to see if they match.
     *
     * @param string $type
     *
     * @return bool
     */
    public function isType(string $type): bool;

    /**
     * @return string
     */
    public function getType(): string;
}
