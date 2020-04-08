<?php

declare(strict_types=1);

namespace Arp\Entity;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity
 */
trait EntityHistoryTrait
{
    use DateCreatedAwareTrait;
    use DateUpdatedAwareTrait;
}
