<?php

namespace Arp\Entity\Factory\Hydrator;

use Arp\Entity\Hydrator\EntityHydrator;
use Arp\Entity\Hydrator\EntityHydratorInterface;
use Arp\Stdlib\Exception\ServiceNotCreatedException;
use Arp\Doctrine\Factory\FactoryEntityManagerProviderTrait;
use Arp\Stdlib\Factory\AbstractServiceFactory;
use Zend\Hydrator\NamingStrategy\NamingStrategyInterface;
use Zend\Hydrator\NamingStrategyEnabledInterface;
use Zend\Hydrator\Strategy\StrategyInterface;
use Zend\Hydrator\StrategyEnabledInterface;
use Interop\Container\ContainerInterface;

/**
 * EntityHydratorFactory
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Factory\Hydrator
 */
class EntityHydratorFactory extends AbstractServiceFactory
{
    /**
     * @trait FactoryEntityManagerProviderTrait
     */
    use FactoryEntityManagerProviderTrait;

    /**
     * $_factoryConfigKey
     *
     * @var string
     */
    protected $_factoryConfigKey = 'hydrators';

    /**
     * $defaultClassName
     *
     * @var string
     */
    protected $defaultClassName = EntityHydrator::class;

    /**
     * create
     *
     * @param ContainerInterface $container     The dependency injection container.
     * @param string             $requestedName The name of the service requested to the container.
     * @param array              $config        The optional factory configuration options.
     * @param string|null        $className     The name of the class that is being created.
     *
     * @return EntityHydratorInterface
     *
     * @throws ServiceNotCreatedException  If the service cannot be created.
     */
    public function create(ContainerInterface $container, $requestedName, array $config = [], $className = null)
    {
        $entityManager  = isset($config['entity_manager'])  ? $config['entity_manager']  : false;
        $namingStrategy = isset($config['naming_strategy']) ? $config['naming_strategy'] : null;
        $strategies     = isset($config['strategies'])      ? $config['strategies']      : [];
        $options        = isset($config['options'])         ? $config['options']         : [];

        if (empty($entityManager)) {

            throw new ServiceNotCreatedException(
                'The required \'entity_manager\' configuration option is missing.'
            );
        }

        /** @var EntityHydratorInterface $hydrator */
        $hydrator = new $className(
            $this->getEntityManager($container, $entityManager),
            $options
        );

        if (! empty($namingStrategy) && $hydrator instanceof NamingStrategyEnabledInterface) {

            $hydrator->setNamingStrategy(
                $this->getService($container, $namingStrategy, NamingStrategyInterface::class)
            );
        }

        if (! empty($strategies) && $hydrator instanceof StrategyEnabledInterface) {

            foreach($strategies as $name => $strategy) {

                $hydrator->addStrategy(
                    $name,
                    $this->getService($container, $strategy, StrategyInterface::class)
                );
            }
        }

        return $hydrator;
    }



}