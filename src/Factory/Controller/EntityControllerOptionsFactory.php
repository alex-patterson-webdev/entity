<?php

namespace Arp\Entity\Factory\Controller;

use Arp\Entity\Controller\EntityControllerOptions;
use Arp\Stdlib\Exception\ServiceNotCreatedException;
use Arp\Stdlib\Factory\AbstractServiceFactory;
use Interop\Container\ContainerInterface;

/**
 * EntityControllerOptionsFactory
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Factory\Controller
 */
class EntityControllerOptionsFactory extends AbstractServiceFactory
{
    /**
     * $defaultClassName
     *
     * @var string
     */
    protected $defaultClassName = EntityControllerOptions::class;

    /**
     * create
     *
     * @param ContainerInterface $container     The dependency injection container.
     * @param string             $requestedName The name of the service requested to the container.
     * @param array              $config        The optional factory configuration options.
     * @param string|null        $className     The name of the class that is being created.
     *
     * @return EntityControllerOptions
     *
     * @throws ServiceNotCreatedException  If the service cannot be created.
     */
    public function create(ContainerInterface $container, $requestedName, array $config = [], $className = null)
    {
        return new $className($config);
    }

}