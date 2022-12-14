<?php

declare(strict_types=1);

namespace Arp\Entity;

interface EntityInterface
{
    public function getId(): ?int;

    public function setId(?int $id): void;

    public function hasId(): bool;

    public function isId(int $id): bool;
}
