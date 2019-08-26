<?php

namespace Arp\Entity\Factory\Form\Element;

use Arp\Entity\EntityInterface;
use Arp\Entity\Form\Element\EntitySelect;
use Arp\Entity\Service\EntityServiceInterface;
use Arp\Entity\Service\EntityServiceManager;
use Arp\Stdlib\Exception\ServiceNotCreatedException;
use Arp\Stdlib\Factory\AbstractServiceFactory;
use Interop\Container\ContainerInterface;
use Zend\Form\Element\Select;

/**
 * EntitySelectFactory
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Factory\Form\Element
 */
class EntitySelectFactory extends AbstractServiceFactory
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
    protected $defaultClassName = EntitySelect::class;

    /**
     * create
     *
     * @param ContainerInterface $container     The dependency injection container.
     * @param string             $requestedName The name of the service requested to the container.
     * @param array              $config        The optional factory configuration options.
     * @param string|null        $className     The name of the class that is being created.
     *
     * @return Select
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

        /** @var EntityServiceInterface $entityService */
        $entityService = $this->getService(
            $container->get(EntityServiceManager::class),
            $entityName,
            EntityServiceInterface::class
        );

        if (empty($options['empty_option'])) {
            $options['empty_option'] = 'Please select...';
        }

        /** @var Select $select */
        $select = new $className(null, $options);

        $listCriteria    = isset($options['list_criteria']) ? $options['list_criteria'] : [];
        $listOptions     = isset($options['list_options'])  ? $options['list_options']  : [];
        $valueMethodName = isset($options['value_method'])  ? $options['value_method']  : 'getId';
        $labelMethodName = isset($options['label_method'])  ? $options['label_method']  : 'getName';
        $groupBy         = isset($options['group_by'])      ? $options['group_by']      : '';

        $collection = $entityService->getCollection($listCriteria, $listOptions);

        $valueOptions = [];

        foreach($collection as $entity) {

            $value = call_user_func([$entity, $valueMethodName]);
            $label = call_user_func([$entity, $labelMethodName]);

            if (! empty($groupBy) && method_exists($entity, $groupBy)) {
                $group = call_user_func([$entity, $groupBy]);

                if ($group) {
                    $groupValue = call_user_func([$group, $valueMethodName]);

                    if (empty($valueOptions[$groupValue])) {

                        $valueOptions[$groupValue] = [
                            'label'   => call_user_func([$group, $labelMethodName]),
                            'options' => [],
                        ];
                    }

                    $valueOptions[$groupValue]['options'][$value] = $label;
                    continue;
                }
            }

            $valueOptions[$value] = $label;
        }

        $select->setValueOptions($valueOptions);

        return $select;
    }

}