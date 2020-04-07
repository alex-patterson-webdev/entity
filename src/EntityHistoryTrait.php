<?php

declare(strict_types=1);

namespace Arp\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity
 */
trait EntityHistoryTrait
{
    use DateCreatedAwareTrait;
    use DateUpdatedAwareTrait;

    /**
     * @ORM\Column(name="date_created", type="datetime", nullable=true)
     * @var \DateTime|null
     */
    protected $dateCreated;

    /**
     * @ORM\Column(name="date_updated", type="datetime", nullable=true)
     * @var \DateTime|null
     */
    protected $dateUpdated;
}
