<?php

declare(strict_types=1);

namespace Arp\Entity;

interface DateCreatedAwareInterface extends EntityInterface
{
    public function hasDateCreated(): bool;

    public function getDateCreated(): ?\DateTimeInterface;

    public function setDateCreated(?\DateTimeInterface $dateCreated): void;
}
