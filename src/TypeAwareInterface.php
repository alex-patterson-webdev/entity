<?php

declare(strict_types=1);

namespace Arp\Entity;

interface TypeAwareInterface
{
    public function isType(string $type): bool;

    public function getType(): string;
}
