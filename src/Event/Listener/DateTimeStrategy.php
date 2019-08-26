<?php

namespace Arp\Entity\Event\Listener;

use Arp\DateTime\Exception\DateTimeFactoryException;
use Arp\Entity\Event\EntityEvent;
use Arp\DateTime\Entity\DateCreatedAwareInterface;
use Arp\DateTime\Entity\DateDeletedAwareInterface;
use Arp\DateTime\Entity\DateUpdatedAwareInterface;
use Arp\DateTime\Service\DateTimeFactoryInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;

/**
 * DateTimeStrategy
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Event\Listener
 */
class DateTimeStrategy implements ListenerAggregateInterface
{
    /**
     * @trait ListenerAggregateTrait
     */
    use ListenerAggregateTrait;

    /**
     * $dateTimeFactory
     *
     * @var DateTimeFactoryInterface
     */
    protected $dateTimeFactory;

    /**
     * __construct
     *
     * @param DateTimeFactoryInterface $dateTimeFactory
     */
    public function __construct(DateTimeFactoryInterface $dateTimeFactory)
    {
        $this->dateTimeFactory = $dateTimeFactory;
    }

    /**
     * attach
     *
     * Attach the required event listeners to the provided event manager.
     *
     * @param EventManagerInterface $eventManager
     * @param integer               $priority
     */
    public function attach(EventManagerInterface $eventManager, $priority = 1)
    {
        $this->listeners[] = $eventManager->attach(
            EntityEvent::EVENT_CREATE_PRE,
            [$this, 'onCreate'],
            $priority
        );

        $this->listeners[] = $eventManager->attach(
            EntityEvent::EVENT_UPDATE_PRE,
            [$this, 'onUpdate'],
            $priority
        );

        $this->listeners[] = $eventManager->attach(
            EntityEvent::EVENT_DELETE_PRE,
            [$this, 'onDelete'],
            $priority
        );
    }

    /**
     * onCreate
     *
     * Set the date that the entity was created.
     *
     * @param EntityEvent $event  The entity event instance.
     *
     * @throws DateTimeFactoryException
     */
    public function onCreate(EntityEvent $event)
    {
        $entity = $event->getEntity();

        if (! $entity instanceof DateCreatedAwareInterface) {
            return;
        }

        $entity->setDateCreated($this->dateTimeFactory->createDateTime());
    }

    /**
     * onUpdate
     *
     * Set the date that the entity was updated.
     *
     * @param EntityEvent $event  The entity event instance.
     *
     * @throws DateTimeFactoryException
     */
    public function onUpdate(EntityEvent $event)
    {
        $entity = $event->getEntity();

        if (! $entity instanceof DateUpdatedAwareInterface) {
            return;
        }

        $entity->setDateUpdated($this->dateTimeFactory->createDateTime());
    }

    /**
     * onDelete
     *
     * Set the date and time the entity was deleted.
     *
     * @param EntityEvent $event  The entity event instance.
     *
     * @throws DateTimeFactoryException
     */
    public function onDelete(EntityEvent $event)
    {
        $entity = $event->getEntity();

        if (! $entity instanceof DateDeletedAwareInterface) {
            return;
        }

        $entity->setDateDeleted($this->dateTimeFactory->createDateTime());
    }

}