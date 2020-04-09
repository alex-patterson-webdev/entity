<?php

declare(strict_types=1);

namespace Arp\Entity;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity
 */
trait DateTimeAwareTrait
{
    use DateCreatedAwareTrait;
    use DateUpdatedAwareTrait;
}
