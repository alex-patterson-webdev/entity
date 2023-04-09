<?php

declare(strict_types=1);

namespace Arp\Entity;

interface DescriptionAwareInterface extends EntityInterface
{
    public function hasDescription(): bool;

    public function getDescription(): string;

    public function setDescription(string $description): void;
}
