<?php

namespace Arp\Entity;

use Arp\DateTime\Entity\DateCreatedAwareInterface;
use Arp\DateTime\Entity\DateUpdatedAwareInterface;

/**
 * EntityHistoryInterface
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity
 */
interface EntityHistoryInterface extends DateCreatedAwareInterface, DateUpdatedAwareInterface
{

}