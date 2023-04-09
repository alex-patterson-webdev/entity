<?php

declare(strict_types=1);

namespace Arp\Entity;

interface DateUpdatedAwareInterface extends EntityInterface
{
    public function hasDateUpdated(): bool;

    public function getDateUpdated(): ?\DateTimeInterface;

    public function setDateUpdated(?\DateTimeInterface $dateTime): void;
}
