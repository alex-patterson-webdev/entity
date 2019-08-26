<?php

namespace Arp\Entity\Event;

use Arp\Entity\EntityInterface;
use Arp\Entity\Service\EntityServiceInterface;
use Arp\Event\Event;

/**
 * EntityEvent
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Event
 */
class EntityEvent extends Event
{
    /**
     * @const
     */
    const EVENT_CREATE_PRE   = 'entity.create.pre';
    const EVENT_CREATE_POST  = 'entity.create.post';
    const EVENT_CREATE_ERROR = 'entity.create.error';

    const EVENT_UPDATE_PRE   = 'entity.update.pre';
    const EVENT_UPDATE_POST  = 'entity.update.post';
    const EVENT_UPDATE_ERROR = 'entity.update.error';

    const EVENT_DELETE_PRE   = 'entity.delete.pre';
    const EVENT_DELETE_POST  = 'entity.delete.post';
    const EVENT_DELETE_ERROR = 'entity.delete.error';

    /**
     * $entity
     *
     * @var EntityInterface
     */
    protected $entity;

    /**
     * getEntity
     *
     * Return the value of the Entity property.
     *
     * @return EntityInterface
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * setEntity
     *
     * Set the value of the Entity property.
     *
     * @param  EntityInterface|null $entity
     */
    public function setEntity(EntityInterface $entity = null)
    {
        $this->entity = $entity;
    }

    /**
     * getTarget
     *
     * @return EntityServiceInterface
     */
    public function getTarget()
    {
        /** @var EntityServiceInterface $entityService */
        $entityService = $this->target;

        return $entityService;
    }

}