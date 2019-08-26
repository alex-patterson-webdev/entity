<?php

namespace Arp\Entity;

use Arp\DateTime\Entity\DateDeletedAwareInterface;

/**
 * DeleteHistoryAwareInterface
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity
 */
interface DeleteHistoryAwareInterface extends DeleteAwareInterface, DateDeletedAwareInterface
{}