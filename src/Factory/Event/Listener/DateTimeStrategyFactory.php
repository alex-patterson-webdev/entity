<?php

namespace Arp\Entity\Factory\Event\Listener;

use Arp\DateTime\Service\DateTimeFactory;
use Arp\DateTime\Service\DateTimeFactoryInterface;
use Arp\Entity\Event\Listener\DateTimeStrategy;
use Arp\Stdlib\Exception\ServiceNotCreatedException;
use Arp\Stdlib\Factory\AbstractServiceFactory;
use Interop\Container\ContainerInterface;

/**
 * DateTimeStrategyFactory
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Factory\Event\Listener
 */
class DateTimeStrategyFactory extends AbstractServiceFactory
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
    protected $defaultClassName = DateTimeStrategy::class;

    /**
     * create
     *
     * @param ContainerInterface $container     The dependency injection container.
     * @param string             $requestedName The name of the service requested to the container.
     * @param array              $config        The optional factory configuration options.
     * @param string|null        $className     The name of the class that is being created.
     *
     * @return DateTimeStrategy
     *
     * @throws ServiceNotCreatedException  If the service cannot be created.
     */
    public function create(ContainerInterface $container, $requestedName, array $config = [], $className = null)
    {
        $dateTimeFactory = isset($config['date_time_factory'])
            ? $config['date_time_factory']
            : DateTimeFactory::class;

        return new $className(
            $this->getService($container, $dateTimeFactory, DateTimeFactoryInterface::class)
        );
    }

}