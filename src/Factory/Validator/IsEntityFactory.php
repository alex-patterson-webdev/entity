<?php

namespace Arp\Entity\Factory\Validator;

use Arp\Entity\Validator\IsEntity;
use Arp\Entity\Service\EntityServiceManager;
use Arp\Stdlib\Exception\ServiceNotCreatedException;
use Arp\Stdlib\Factory\AbstractServiceFactory;
use Interop\Container\ContainerInterface;

/**
 * IsEntityFactory
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Factory\Validator
 */
class IsEntityFactory extends AbstractServiceFactory
{
    /**
     * $_factoryConfigKey
     *
     * @var string
     */
    protected $_factoryConfigKey = 'validators';

    /**
     * $defaultClassName
     *
     * @var string
     */
    protected $defaultClassName = IsEntity::class;

    /**
     * create
     *
     * @param ContainerInterface $container     The dependency injection container.
     * @param string             $requestedName The name of the service requested to the container.
     * @param array              $config        The optional factory configuration options.
     * @param string|null        $className     The name of the class that is being created.
     *
     * @return IsEntity
     *
     * @throws ServiceNotCreatedException  If the service cannot be created.
     */
    public function create(ContainerInterface $container, $requestedName, array $config = [], $className = null)
    {
        $entityName = isset($config['entity_name']) ? $config['entity_name'] : false;
        $fieldNames = isset($config['field_names']) ? $config['field_names'] : false;
        $options    = isset($config['options'])     ? $config['options']     : [];

        if (empty($entityName)) {
            throw new ServiceNotCreatedException('The required \'entity_name\' configuration option is missing.');
        }

        if (empty($entityName)) {
            $fieldNames[] = 'id';
        }

        if (! array_key_exists('useContext', $options)) {
            $options['useContext'] = true;
        }

        return new $className(
            $this->getService($container->get(EntityServiceManager::class), $entityName),
            $fieldNames,
            $options
        );
    }

}