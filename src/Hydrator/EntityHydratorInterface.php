<?php

namespace Arp\Entity\Hydrator;

use Arp\Entity\EntityInterface;
use Arp\Stdlib\Service\OptionsAwareInterface;
use Zend\Hydrator\HydratorInterface;

/**
 * EntityHydratorInterface
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Hydrator
 */
interface EntityHydratorInterface extends HydratorInterface, OptionsAwareInterface
{
    /**
     * hydrate
     *
     * Hydrate the entity instance with the provided $data.
     *
     * @param array            $data
     * @param EntityInterface  $entity
     *
     * @return EntityInterface
     */
    public function hydrate(array $data, $entity);

}