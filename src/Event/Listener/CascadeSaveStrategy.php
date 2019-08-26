<?php

namespace Arp\Entity\Event\Listener;

use Arp\Entity\EntityInterface;
use Arp\Entity\Event\EntityEvent;
use Arp\Entity\Service\EntityServiceInterface;
use Arp\Stdlib\Service\OptionsAwareInterface;
use Arp\Stdlib\Service\OptionsAwareTrait;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Proxy\Proxy;

/**
 * CascadeSaveStrategy
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Event\Listener
 */
class CascadeSaveStrategy implements ListenerAggregateInterface, OptionsAwareInterface
{
    /**
     * @trait ListenerAggregateTrait
     * @trait OptionsAwareTrait
     */
    use ListenerAggregateTrait,
        OptionsAwareTrait;

    /**
     * $methodNameMap
     *
     * @var array
     */
    protected $methodNameMap = [];

    /**
     * $entityServices
     *
     * @var EntityServiceInterface[]
     */
    protected $entityServices = [];

    /**
     * __construct
     *
     * @param array                    $methodNameMap
     * @param EntityServiceInterface[] $entityServices
     */
    public function __construct(array $methodNameMap, array $entityServices)
    {
        $this->methodNameMap  = $methodNameMap;
        $this->entityServices = $entityServices;
    }

    /**
     * attach
     *
     * Attach the event listeners to the provided event manager.
     *
     * @param EventManagerInterface $eventManager  The event manager to attach to.
     * @param integer               $priority      The default event priority.
     */
    public function attach(EventManagerInterface $eventManager, $priority = 1)
    {
        $this->listeners[] = $eventManager->attach(
            EntityEvent::EVENT_CREATE_POST,
            [$this, 'onSave'],
            1000
        );

        $this->listeners[] = $eventManager->attach(
            EntityEvent::EVENT_UPDATE_POST,
            [$this, 'onSave'],
            1000
        );
    }

    /**
     * onSave
     *
     * @param EntityEvent $event
     */
    public function onSave(EntityEvent $event)
    {
        $entity = $event->getEntity();

        foreach($this->methodNameMap as $methodName => $entityServiceName) {

            if (empty($this->entityServices[$entityServiceName])) {
                continue;
            }

            $foreignEntityService = $this->entityServices[$entityServiceName];

            if (! method_exists($entity, $methodName)) {
                continue;
            }

            $result = $entity->{$methodName}();

            if ($result instanceof EntityInterface) {
                $this->onSaveEntity($foreignEntityService, $result);
            }
            elseif (is_array($result) || $result instanceof \Traversable) {
                $this->onSaveCollection($foreignEntityService, $result);
            }
        }
    }

    /**
     * onSaveEntity
     *
     * Save a single foreign entity.
     *
     * @param EntityServiceInterface $entityService
     * @param EntityInterface        $entity
     *
     * @return EntityInterface|boolean
     */
    public function onSaveEntity(EntityServiceInterface $entityService, EntityInterface $entity)
    {
        $entityName = $entityService->getEntityName();

        if (! $entity instanceof $entityName) {
            return false;
        }
        elseif ($entity instanceof Proxy && ! $entity->__isInitialized()) {
            return false;
        }

        $options = [
            'flush'       => true,
            'transaction' => false,
        ];

        return $entityService->save($entity, $options);
    }

    /**
     * onSaveCollection
     *
     * @param EntityServiceInterface $entityService
     * @param EntityInterface[]      $collection
     * @param array                  $options
     *
     * @return EntityInterface[]
     */
    protected function onSaveCollection(EntityServiceInterface $entityService, $collection, array $options = [])
    {
        if ($collection instanceof Collection) {

            $initialized = true;
            $dirty       = true;

            if (method_exists($collection, 'isInitialized') && false === $collection->isInitialized()) {
                $initialized = false;
            }

            if (method_exists($collection, 'isDirty') && false === $collection->isDirty()) {
                $dirty = false;
            }

            if (! $initialized && ! $dirty) {
                return [];
            }

            $collection = $collection->toArray();
        }

        if (! is_array($collection) || empty($collection)) {
            return [];
        }

        return $entityService->saveCollection($collection, $options);
    }

}