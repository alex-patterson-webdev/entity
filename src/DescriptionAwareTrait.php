<?php

declare(strict_types=1);

namespace Arp\Entity;

trait DescriptionAwareTrait
{
    protected string $description = '';

    public function hasDescription(): bool
    {
        return !empty($this->description);
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}
