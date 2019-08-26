<?php

namespace Arp\Entity\Factory\Form;

use Arp\Entity\Form\EntityCreateForm;
use Arp\Stdlib\Exception\ServiceNotCreatedException;
use Arp\Stdlib\Factory\AbstractServiceFactory;
use Interop\Container\ContainerInterface;

/**
 * EntityCreateFormFactory
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Factory\Form
 */
class EntityCreateFormFactory extends AbstractServiceFactory
{
    /**
     * $_factoryConfigKey
     *
     * @var string
     */
    protected $_factoryConfigKey = 'form_elements';

    /**
     * defaultClassName
     *
     * @var string
     */
    protected $defaultClassName = EntityCreateForm::class;

    /**
     * create
     *
     * @param ContainerInterface $container     The dependency injection container.
     * @param string             $requestedName The name of the service requested to the container.
     * @param array              $config        The optional factory configuration options.
     * @param string|null        $className     The name of the class that is being created.
     *
     * @return EntityCreateForm
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

        return new $className($entityName, $options);
    }

}