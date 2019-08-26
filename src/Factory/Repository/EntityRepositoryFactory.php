<?php

namespace Arp\Entity\Factory\Repository;

use Arp\App\Factory\AbstractServiceFactory;
use Arp\Stdlib\Exception\ServiceNotCreatedException;
use Interop\Container\ContainerInterface;

/**
 * EntityRepositoryFactory
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Factory\Repository
 */
class EntityRepositoryFactory extends AbstractServiceFactory
{
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
        $entityName = isset($config['entity_name']) ? $config['entity_name'] : false;
        $options    = isset($config['options'])     ? $config['options']     : [];

        if (empty($entityName)) {
            throw new ServiceNotCreatedException('The required \'entity_name\' configuration option is missing.');
        }




        return new $className($options);
    }


}