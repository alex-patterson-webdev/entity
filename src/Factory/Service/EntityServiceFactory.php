<?php

namespace Arp\Entity\Factory\Service;

use Arp\Entity\Service\EntityService;
use Arp\Entity\Hydrator\EntityHydrator;
use Arp\Entity\Service\EntityPersistService;
use Arp\Entity\Service\EntityQueryService;
use Arp\Entity\Service\EntityServiceInterface;
use Arp\Entity\Service\EntityServiceOptions;
use Arp\Entity\Service\EntityPersistServiceInterface;
use Arp\Entity\Service\EntityQueryServiceInterface;
use Arp\Entity\Hydrator\EntityHydratorInterface;
use Arp\Stdlib\Exception\ServiceNotCreatedException;
use Arp\Stdlib\Factory\AbstractServiceFactory;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * EntityServiceFactory
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Factory\Service
 */
class EntityServiceFactory extends AbstractServiceFactory
{
    /**
     * $_factoryConfigKey
     *
     * @var string
     */
    protected $_factoryConfigKey = 'entity_services';

    /**
     * $defaultClassName
     *
     * @var string
     */
    protected $defaultClassName = EntityService::class;

    /**
     * $defaultQueryService
     *
     * @var EntityQueryService
     */
    protected $defaultQueryService = EntityQueryService::class;

    /**
     * create
     *
     * @param ContainerInterface $container     The dependency injection container.
     * @param string             $requestedName The name of the service requested to the container.
     * @param array              $config        The optional factory configuration options.
     * @param string|null        $className     The name of the class that is being created.
     *
     * @return EntityServiceInterface
     *
     * @throws ServiceNotCreatedException  If the service cannot be created.
     */
    public function create(ContainerInterface $container, $requestedName, array $config = [], $className = null)
    {
        $entityName     = isset($config['entity_name'])     ? $config['entity_name']     : $requestedName;
        $persistService = isset($config['persist_service']) ? $config['persist_service'] : EntityPersistService::class;
        $hydrator       = isset($config['hydrator'])        ? $config['hydrator']        : EntityHydrator::class;
        $queryService   = isset($config['query_service'])   ? $config['query_service']   : false;
        $listeners      = isset($config['listeners'])       ? $config['listeners']       : [];
        $options        = isset($config['options'])         ? $config['options']         : [];

        if (empty($entityName)) {
            throw new ServiceNotCreatedException('The required \'entity_name\' configuration option is missing.');
        }

        if (empty($queryService)) {

            $queryServiceConfig = [
                'config' => [
                    'entity_name' => $entityName
                ]
            ];

            /** @var ServiceManager $container */
            $queryService = $container->build($this->defaultQueryService, $queryServiceConfig);
        }

        if (empty($persistService)) {
            throw new ServiceNotCreatedException('The required \'persist_service\' configuration option is missing.');
        }

        if (empty($hydrator)) {
            throw new ServiceNotCreatedException('The required \'hydrator\' configuration option is missing.');
        }

        if (is_array($options)) {
            $options = new EntityServiceOptions($entityName, $options);
        }

        /** @var EntityHydratorInterface $hydrator */
        $hydrator = $container->get('HydratorManager')->get($hydrator);

        /** @var EntityQueryServiceInterface $queryService */
        $queryService = $this->getService(
            $container,
            $queryService,
            EntityQueryServiceInterface::class
        );

        /** @var EntityPersistServiceInterface $persistService */
        $persistService = $this->getService(
            $container,
            $persistService,
            EntityPersistServiceInterface::class
        );

        /** @var EntityServiceInterface $entityService */
        $entityService = new $className($options, $hydrator, $queryService, $persistService);

        if (! empty($listeners) && $entityService instanceof EventManagerAwareInterface) {

            foreach($listeners as $listener) {
                /** @var ListenerAggregateInterface $listener */
                $listener = $this->getService($container, $listener, ListenerAggregateInterface::class);
                $listener->attach($entityService->getEventManager());
            }
        }

        return $entityService;
    }

}