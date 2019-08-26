<?php

namespace Arp\Entity\Factory\Service;

use Arp\Entity\Service\EntityQueryService;
use Arp\Entity\Service\EntityQueryServiceInterface;
use Arp\QueryFilter\Service\QueryFilterFactory;
use Arp\Stdlib\Exception\ServiceNotCreatedException;
use Arp\Stdlib\Factory\AbstractServiceFactory;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * EntityQueryServiceFactory
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Factory\Service
 */
class EntityQueryServiceFactory extends AbstractServiceFactory
{
    /**
     * $defaultClassName
     *
     * @var string
     */
    protected $defaultClassName = EntityQueryService::class;

    /**
     * create
     *
     * @param ContainerInterface $container     The dependency injection container.
     * @param string             $requestedName The name of the service requested to the container.
     * @param array              $config        The optional factory configuration options.
     * @param string|null        $className     The name of the class that is being created.
     *
     * @return EntityQueryServiceInterface
     *
     * @throws ServiceNotCreatedException  If the service cannot be created.
     */
    public function create(ContainerInterface $container, $requestedName, array $config = [], $className = null)
    {
        $entityName    = isset($config['entity_name'])    ? $config['entity_name']    : false;
        $entityManager = isset($config['entity_manager']) ? $config['entity_manager'] : EntityManager::class;
        $options       = isset($config['options'])        ? $config['options']        : [];

        if (empty($entityName)) {
            throw new ServiceNotCreatedException('The required \'entity_name\' configuration option is missing.');
        }

        if (empty($entityManager)) {
            throw new ServiceNotCreatedException('The required \'entity_manager\' configuration option is missing.');
        }

        return new $className(
            $entityName,
            $container->get($entityManager),
            $container->get(QueryFilterFactory::class),
            $options
        );
    }

}