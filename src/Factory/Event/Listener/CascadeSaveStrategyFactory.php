<?php

namespace Arp\Entity\Factory\Event\Listener;

use Arp\Entity\Event\Listener\CascadeSaveStrategy;
use Arp\Entity\Service\EntityServiceInterface;
use Arp\Entity\Service\EntityServiceManager;
use Arp\Stdlib\Exception\ServiceNotCreatedException;
use Arp\Stdlib\Factory\AbstractServiceFactory;
use Interop\Container\ContainerInterface;

/**
 * CascadeSaveStrategyFactory
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Factory\Event\Listener
 */
class CascadeSaveStrategyFactory extends AbstractServiceFactory
{
    /**
     * $_factoryConfigKey
     *
     * @var string
     */
    protected $_factoryConfigKey = 'listeners';

    /**
     * $defaultClassName
     *
     * @var string
     */
    protected $defaultClassName = CascadeSaveStrategy::class;

    /**
     * create
     *
     * @param ContainerInterface $container     The dependency injection container.
     * @param string             $requestedName The name of the service requested to the container.
     * @param array              $config        The optional factory configuration options.
     * @param string|null        $className     The name of the class that is being created.
     *
     * @return CascadeSaveStrategy
     *
     * @throws ServiceNotCreatedException  If the service cannot be created.
     */
    public function create(ContainerInterface $container, $requestedName, array $config = [], $className = null)
    {
        $entityName = isset($config['entity_name']) ? $config['entity_name'] : false;
        $methodMap  = isset($config['method_map'])  ? $config['method_map']  : [];

        $entityServices = [];

        foreach(array_values($methodMap) as $entityServiceName) {
            $entityServices[$entityServiceName] = $this->getService(
                $container->get(EntityServiceManager::class),
                $entityServiceName,
                EntityServiceInterface::class
            );
        }

        return new $className($methodMap, $entityServices);
    }

}