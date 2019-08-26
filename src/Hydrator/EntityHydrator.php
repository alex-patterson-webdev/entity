<?php

namespace Arp\Entity\Hydrator;

use Arp\Stdlib\Service\OptionsAwareTrait;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

/**
 * EntityHydrator
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Hydrator
 */
class EntityHydrator extends DoctrineObject implements EntityHydratorInterface
{
    /**
     * @trait OptionsAwareTrait
     */
    use OptionsAwareTrait;

    /**
     * @const
     */
    const HYDRATE_MODE_VALUE     = 'value';
    const HYDRATE_MODE_REFERENCE = 'reference';

    /**
     * __construct
     *
     * @param ObjectManager $objectManager
     * @param array         $options
     */
    public function __construct(ObjectManager $objectManager, array $options = [])
    {
        $this->setOptions($options);

        $hydrateMode = $this->getOption('hydrate_mode', static::HYDRATE_MODE_VALUE);

        parent::__construct($objectManager, ($hydrateMode === static::HYDRATE_MODE_VALUE));
    }

}