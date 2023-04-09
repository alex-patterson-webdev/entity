<?php

declare(strict_types=1);

namespace Arp\Entity;

trait DateTimeAwareTrait
{
    use DateCreatedAwareTrait;
    use DateUpdatedAwareTrait;
}
