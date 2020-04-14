<?php

declare(strict_types=1);

namespace Arp\Entity;

/**
 * Default trait implementation of the \Arp\Entity\EntityInterface
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity
 */
trait EntityTrait
{
    /**
     * @var string|null
     */
    protected $id;

    /**
     * Return the entity's identity.
     *
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Set the entity's identity.
     *
     * @param string|null $id The identity that should be set.
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    /**
     * Check if the identity has been set.
     *
     * @return bool
     */
    public function hasId(): bool
    {
        return isset($this->id);
    }

    /**
     * Check if the provided id matches the $id provided.
     *
     * @param string $id The identity that should be compared.
     *
     * @return bool
     */
    public function isId(string $id): bool
    {
        return ($id === $this->id);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getId();
    }
}
