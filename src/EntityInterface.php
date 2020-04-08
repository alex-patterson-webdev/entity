<?php

declare(strict_types=1);

namespace Arp\Entity;

/**
 * Generic interface for Entity data transfer object.
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity
 */
interface EntityInterface
{
    /**
     * Return the entity's identity.
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Set the entity's identity.
     *
     * @param string $id The identity that should be set
     *
     * @return void
     */
    public function setId(string $id): void;

    /**
     * Check if the identity has been set.
     *
     * @return boolean
     */
    public function hasId(): bool;

    /**
     * Check if the provided id matches the $id provided.
     *
     * @param string $id The identity that should be compared.
     *
     * @return boolean
     */
    public function isId(string $id): bool;
}
