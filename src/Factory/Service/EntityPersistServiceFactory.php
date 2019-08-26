<?php

namespace Arp\Entity\Factory\Service;

use Arp\Entity\Service\EntityPersistService;
use Arp\Stdlib\Exception\ServiceNotCreatedException;
use Arp\Stdlib\Factory\AbstractServiceFactory;
use Interop\Container\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * EntityPersistServiceFactory
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Factory\Service
 */
class EntityPersistServiceFactory extends AbstractServiceFactory
{
    /**
     * $defaultClassName
     *
     * @var EntityPersistService
     */
    protected $defaultClassName = EntityPersistService::class;

    /**
     * create
     *
     * @param ContainerInterface $container     The dependency injection container.
     * @param string             $requestedName The name of the service requested to the container.
     * @param array              $config        The optional factory configuration options.
     * @param string|null        $className     The name of the class that is being created.
     *
     * @return mixed
     *
     * @throws ServiceNotCreatedException  If the service cannot be created.
     */
    public function create(ContainerInterface $container, $requestedName, array $config = [], $className = null)
    {
        $entityManager = isset($config['entity_manager']) ? $config['entity_manager'] : false;

        if (empty($entityManager)) {
            throw new ServiceNotCreatedException('The required \'entity_manager\' configuration option is missing.');
        }

        return new $className(
            $this->getService($container, $entityManager, EntityManagerInterface::class)
        );
    }

}