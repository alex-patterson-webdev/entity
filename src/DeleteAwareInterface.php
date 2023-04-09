<?php

declare(strict_types=1);

namespace Arp\Entity;

interface DeleteAwareInterface extends EntityInterface
{
    public function isDeleted(): bool;

    public function setDeleted(bool $deleted): void;
}
