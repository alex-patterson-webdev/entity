<?php

namespace Arp\Entity\Factory\Form;

use Arp\Entity\Form\EntityFieldset;
use Arp\Entity\Hydrator\EntityHydrator;
use Arp\Entity\Service\EntityServiceInterface;
use Arp\Entity\Service\EntityServiceManager;
use Arp\Stdlib\Exception\ServiceNotCreatedException;
use Arp\Stdlib\Factory\AbstractServiceFactory;
use Interop\Container\ContainerInterface;
use Zend\Hydrator\HydratorInterface;
use Zend\Form\Factory;

/**
 * EntityFieldsetFactory
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Factory\Form
 */
class EntityFieldsetFactory extends AbstractServiceFactory
{
    /**
     * $_factoryConfigKey
     *
     * @var string
     */
    protected $_factoryConfigKey = 'form_elements';

    /**
     * $defaultClassName
     *
     * @var string
     */
    protected $defaultClassName = EntityFieldset::class;

    /**
     * create
     *
     * @param ContainerInterface $container     The dependency injection container.
     * @param string             $requestedName The name of the service requested to the container.
     * @param array              $config        The optional factory configuration options.
     * @param string|null        $className     The name of the class that is being created.
     *
     * @return EntityFieldset
     *
     * @throws ServiceNotCreatedException  If the service cannot be created.
     */
    public function create(ContainerInterface $container, $requestedName, array $config = [], $className = null)
    {
        $name       = isset($config['name'])        ? $config['name']        : null;
        $entityName = isset($config['entity_name']) ? $config['entity_name'] : false;
        $hydrator   = isset($config['hydrator'])    ? $config['hydrator']    : EntityHydrator::class;
        $object     = isset($config['object'])      ? $config['object']      : $entityName;
        $options    = isset($config['options'])     ? $config['options']     : [];

        if (empty($entityName)) {
            throw new ServiceNotCreatedException('The required \'entity_name\' configuration option is missing.');
        }

        /** @var EntityServiceInterface $entityService */
        $entityService = $this->getService(
            $container->get(EntityServiceManager::class),
            $entityName
        );

        /** @var EntityFieldset $fieldset */
        $fieldset = new $className($name, $options);

        /** @var Factory $factory */
        $factory = $this->getService($container, 'FormFactory');

        /** @var HydratorInterface $hydrator */
        $hydrator = $this->getService($container->get('HydratorManager'), $hydrator);

        $fieldset->setFormFactory($factory);
        $fieldset->setHydrator($hydrator);
        $fieldset->setObject(isset($object) ? new $object : $entityService->createInstance());

        return $fieldset;
    }


}