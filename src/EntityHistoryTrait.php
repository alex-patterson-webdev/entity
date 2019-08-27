<?php

namespace Arp\Entity;

use Arp\DateTime\Entity\DateCreatedAwareTrait;
use Arp\DateTime\Entity\DateUpdatedAwareTrait;

/**
 * EntityHistoryTrait
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity
 */
trait EntityHistoryTrait
{
    /**
     * @trait DateCreatedAwareTrait
     * @trait DateUpdatedAwareTrait
     */
    use DateCreatedAwareTrait,
        DateUpdatedAwareTrait;

    /**
     * $dateCreated
     *
     * @var \DateTime|null
     */
    protected $dateCreated;

    /**
     * $dateCreated
     *
     * @var \DateTime|null
     */
    protected $dateUpdated;
}