<?php

namespace Arp\Entity;

use Arp\DateTime\Entity\DateCreatedAwareTrait;
use Arp\DateTime\Entity\DateUpdatedAwareTrait;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(name="date_created", type="datetime", nullable=true)
     * @var \DateTime|null
     */
    protected $dateCreated;

    /**
     * $dateCreated
     *
     * @ORM\Column(name="date_updated", type="datetime", nullable=true)
     * @var \DateTime|null
     */
    protected $dateUpdated;
}