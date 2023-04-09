<?php

declare(strict_types=1);

namespace Arp\Entity;

trait DateCreatedAwareTrait
{
    protected ?\DateTimeInterface $dateCreated = null;

    public function hasDateCreated(): bool
    {
        return isset($this->dateCreated);
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(?\DateTimeInterface $dateCreated): void
    {
        $this->dateCreated = $dateCreated;
    }
}
