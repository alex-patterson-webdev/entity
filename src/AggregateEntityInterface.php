<?php

declare(strict_types=1);

namespace Arp\Entity;

/**
 * Defines an entity as an aggregate root entity. Aggregate Root owns an Aggregate and serves as a gateway
 * for all modifications within the Aggregate
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity
 */
interface AggregateEntityInterface extends EntityInterface
{
}
