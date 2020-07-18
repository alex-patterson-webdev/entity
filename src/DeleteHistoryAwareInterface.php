<?php

declare(strict_types=1);

namespace Arp\Entity;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity
 */
interface DeleteHistoryAwareInterface extends DeleteAwareInterface, DateDeletedAwareInterface
{

}
