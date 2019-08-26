<?php

namespace Arp\Entity\Service;

use Arp\Entity\EntityInterface;
use Arp\Entity\Event\EntityEvent;
use Arp\Entity\DeleteAwareInterface;
use Arp\Entity\Exception\EntityServiceException;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerAwareTrait;

/**
 * AbstractEntityEventService
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Service
 */
abstract class AbstractEntityEventService extends AbstractEntityService implements EventManagerAwareInterface
{
    /**
     * @trait EventManagerAwareTrait
     */
    use EventManagerAwareTrait;

    /**
     * create
     *
     * @param EntityInterface $entity
     * @param array           $options
     *
     * @return EntityInterface
     *
     * @throws EntityServiceException
     */
    protected function create(EntityInterface $entity, array $options = []) : EntityInterface
    {
        $options = $this->getOptions($options);

        $transaction = $options->isTransactionEnabled();
        $events      = $options->isEventTriggerEnabled();

        try {
            if ($transaction) {
                $this->persistService->beginTransaction();
            }

            if ($events) {
                $this->triggerEvent(EntityEvent::EVENT_CREATE_PRE, $entity);
            }

            $entity = $this->persistService->persist($entity, $options->getOption('persist_options', []));

            if ($options->isFlushEnabled()) {
                $this->persistService->flush($entity, $options->getFlushOptions());
            }

            if ($transaction) {
                $this->persistService->commitTransaction();
            }

            if ($events) {
                $this->triggerEvent(EntityEvent::EVENT_CREATE_POST, $entity);
            }
        }
        catch (\Exception $e) {

            if ($transaction) {
                $this->persistService->rollbackTransaction();
            }

            if ($events) {
                $this->triggerErrorEvent(EntityEvent::EVENT_CREATE_ERROR, $e);
            }

            $this->handleException(
                $e,
                sprintf(
                    'Failed to create entity of type \'%s\' : %s',
                    $this->getEntityName(),
                    $e->getMessage()
                )
            );
        }

        return $entity;
    }

    /**
     * update
     *
     * @param EntityInterface $entity
     * @param array           $options
     *
     * @return EntityInterface
     *
     * @throws EntityServiceException
     */
    protected function update(EntityInterface $entity, array $options = []) : EntityInterface
    {
        $options = $this->getOptions($options);

        $transaction = $options->isTransactionEnabled();
        $events      = $options->isEventTriggerEnabled();

        try {
            if ($transaction) {
                $this->persistService->beginTransaction();
            }

            if ($events) {
                $this->triggerEvent(EntityEvent::EVENT_UPDATE_PRE, $entity);
            }

            if ($options->isFlushEnabled()) {
                $this->persistService->flush($entity, $options->getFlushOptions());
            }

            if ($events) {
                $this->triggerEvent(EntityEvent::EVENT_UPDATE_POST, $entity);
            }

            if ($transaction) {
                $this->persistService->commitTransaction();
            }
        }
        catch (\Exception $e) {

            if ($transaction) {
                $this->persistService->rollbackTransaction();
            }

            if ($events) {
                $this->triggerErrorEvent(EntityEvent::EVENT_UPDATE_ERROR, $e);
            }

            $this->handleException(
                $e,
                sprintf(
                    'Failed to update entity of type \'%s\' : %s',
                    $this->getEntityName(),
                    $e->getMessage()
                )
            );
        }

        return $entity;
    }

    /**
     * delete
     *
     * Delete the entity instance.
     *
     * @param EntityInterface $entity  The entity that should be deleted.
     * @param array           $options The optional entity delete options.
     *
     * @return boolean
     *
     * @throws EntityServiceException  If the entity cannot be deleted.
     */
    public function delete(EntityInterface $entity, array $options = []) : bool
    {
        $options = $this->getOptions($options);

        $transaction = $options->isTransactionEnabled();
        $events      = $options->isEventTriggerEnabled();

        try {
            if ($transaction) {
                $this->persistService->beginTransaction();
            }

            if ($events) {
                $this->triggerEvent(EntityEvent::EVENT_DELETE_PRE, $entity);
            }

            $persistOptions = $options->getOption('persist_options', []);

            if ($entity instanceof DeleteAwareInterface) {
                $this->update($entity, $persistOptions);
            }
            else {
                $this->persistService->delete($entity, $persistOptions);
            }

            if ($options->isFlushEnabled()) {
                $this->persistService->flush($entity, $options->getFlushOptions());
            }

            if ($events) {
                $this->triggerEvent(EntityEvent::EVENT_DELETE_POST, $entity);
            }

            if ($transaction) {
                $this->persistService->commitTransaction();
            }
        }
        catch (\Exception $e) {

            if ($transaction) {
                $this->persistService->rollbackTransaction();
            }

            if ($events) {
                $this->triggerErrorEvent(EntityEvent::EVENT_DELETE_ERROR, $e);
            }

            $this->handleException(
                $e,
                sprintf(
                    'Failed to delete entity of type \'%s\' : %s',
                    $this->getEntityName(),
                    $e->getMessage()
                )
            );
        }

        return true;
    }

    /**
     * triggerEvent
     *
     * @param EntityEvent|string  $event    The event instance or name.
     * @param EntityInterface     $entity   The entity that is being persisted.
     * @param array               $options  The optional event options.
     *
     * @return mixed|null
     */
    protected function triggerEvent($event, EntityInterface $entity, $options = [])
    {
        if (is_string($event)) {
            $event = $this->createEvent($event);
        }

        if (! $event instanceof EntityEvent) {

            throw new EntityServiceException(sprintf(
                'The \'event\' argument must be an object of type \'%s\'; \'%s\' provided in \'%s\'.',
                EntityEvent::class,
                (is_object($event) ? get_class($event) : gettype($event)),
                __METHOD__
            ));
        }

        $event->setTarget($this);
        $event->setEntity($entity);
        $event->setOptions($options);

        try {
            $this->getEventManager()->triggerEvent($event);
        }
        catch(EntityServiceException $e) {

            throw $e;
        }
        catch (\Exception $e) {

            throw new EntityServiceException(
                sprintf(
                    'An error occurred while triggering \'%s\' event for entity \'%s\' : %s',
                    $event->getName(),
                    $this->getEntityName(),
                    $e->getMessage()
                ),
                $e->getCode(),
                $e
            );
        }

        return null;
    }

    /**
     * triggerErrorEvent
     *
     * @param string     $name       The error event name.
     * @param \Exception $exception  The caught exception instance.
     */
    protected function triggerErrorEvent(string $name, \Exception $exception)
    {
        $event = $this->createEvent($name);
        $event->setException($exception);

        $this->getEventManager()->triggerEvent($event);
    }

    /**
     * createEvent
     *
     * @param null|string  $name     The name of the event that should be triggered.
     * @param array        $options  The optional event options.
     *
     * @return EntityEvent
     */
    protected function createEvent(string $name = null, array $options = []) : EntityEvent
    {
        $eventClassName = $this->options->getOption('event_class_name', EntityEvent::class);

        /** @var EntityEvent $event */
        $event = new $eventClassName;

        $event->setTarget($this);

        if ($name) {
            $event->setName($name);
        }

        if (! empty($options)) {
            $event->setOptions($options);
        }

        return $event;
    }
}