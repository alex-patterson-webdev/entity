<?php

declare(strict_types=1);

namespace Arp\Entity;

interface DateDeletedAwareInterface extends EntityInterface
{
    public function hasDateDeleted(): bool;

    public function getDateDeleted(): ?\DateTimeInterface;

    public function setDateDeleted(?\DateTimeInterface $dateTime): void;
}
