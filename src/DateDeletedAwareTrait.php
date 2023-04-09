<?php

declare(strict_types=1);

namespace Arp\Entity;

trait DateDeletedAwareTrait
{
    protected ?\DateTimeInterface $dateDeleted = null;

    public function hasDateDeleted(): bool
    {
        return isset($this->dateDeleted);
    }

    public function getDateDeleted(): ?\DateTimeInterface
    {
        return $this->dateDeleted;
    }

    public function setDateDeleted(?\DateTimeInterface $dateTime): void
    {
        $this->dateDeleted = $dateTime;
    }
}
