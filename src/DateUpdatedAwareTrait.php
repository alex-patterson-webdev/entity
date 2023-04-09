<?php

declare(strict_types=1);

namespace Arp\Entity;

trait DateUpdatedAwareTrait
{
    protected ?\DateTimeInterface $dateUpdated = null;

    public function hasDateUpdated(): bool
    {
        return isset($this->dateUpdated);
    }

    public function getDateUpdated(): ?\DateTimeInterface
    {
        return $this->dateUpdated;
    }

    public function setDateUpdated(?\DateTimeInterface $dateTime): void
    {
        $this->dateUpdated = $dateTime;
    }
}
