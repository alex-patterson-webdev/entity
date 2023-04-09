<?php

declare(strict_types=1);

namespace Arp\Entity;

trait EntityTrait
{
    protected ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function hasId(): bool
    {
        return isset($this->id);
    }

    public function isId(int $id): bool
    {
        return ($id === $this->id);
    }
}
