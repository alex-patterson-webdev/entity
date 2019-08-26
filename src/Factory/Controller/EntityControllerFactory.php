<?php

namespace Arp\Entity\Factory\Controller;

use Arp\Entity\Controller\EntityController;
use Arp\Entity\Controller\EntityControllerOptions;
use Arp\Entity\Service\EntityServiceInterface;
use Arp\Entity\Service\EntityServiceManager;
use Arp\Form\Service\FormElementProviderInterface;
use Arp\Stdlib\Exception\ServiceNotCreatedException;
use Arp\Stdlib\Factory\AbstractServiceFactory;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * EntityControllerFactory
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Factory\Controller
 */
class EntityControllerFactory extends AbstractServiceFactory
{
    /**
     * $_factoryConfigKey
     *
     * @var string
     */
    protected $_factoryConfigKey = 'entity_controllers';

    /**
     * $defaultClassName
     *
     * @var string
     */
    protected $defaultClassName = EntityController::class;

    /**
     * create
     *
     * @param ContainerInterface $container     The dependency injection container.
     * @param string             $requestedName The name of the service requested to the container.
     * @param array              $config        The optional factory configuration options.
     * @param string|null        $className     The name of the class that is being created.
     *
     * @return EntityController
     *
     * @throws ServiceNotCreatedException  If the service cannot be created.
     */
    public function create(ContainerInterface $container, $requestedName, array $config = [], $className = null)
    {
        $entityName   = isset($config['entity_name'])   ? $config['entity_name']   : false;
        $formProvider = isset($config['form_provider']) ? $config['form_provider'] : false;
        $options      = isset($config['options'])       ? $config['options']       : [];

        if (empty($entityName)) {
            throw new ServiceNotCreatedException('The required \'entity_name\' configuration option is missing.');
        }

        if (empty($formProvider)) {
            throw new ServiceNotCreatedException('The required \'form_provider\' configuration option is missing.');
        }

        if (is_array($options)) {
            /** @var ServiceManager $container */
            $options = $container->build(EntityControllerOptions::class, [ 'config' => $options ]);
        }
        elseif (is_string($options)) {
            $options = $container->get($options);
        }

        if (! $options instanceof EntityControllerOptions) {

            throw new ServiceNotCreatedException(sprintf(
                'The entity controller options must be an object of type \'%s\'; \'%s\' provided for entity \'%s\'.',
                EntityControllerOptions::class,
                (is_object($options) ? get_class($options) : gettype($options)),
                $entityName
            ));
        }

        /** @var EntityServiceInterface $entityService */
        $entityService = $this->getService(
            $container->get(EntityServiceManager::class),
            $entityName,
            EntityServiceInterface::class
        );

        /** @var FormElementProviderInterface $formProvider */
        $formProvider = $this->getService(
            $container,
            $formProvider,
            FormElementProviderInterface::class
        );

        return new $className($options, $entityService, $formProvider);
    }


}