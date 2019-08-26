<?php

namespace Arp\Entity\Service;

use Arp\Entity\EntityInterface;
use Arp\Entity\Exception\EntityPersistException;
use Arp\Database\Service\TransactionServiceInterface;

/**
 * EntityPersistServiceInterface
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Service
 */
interface EntityPersistServiceInterface extends TransactionServiceInterface
{
    /**
     * @const
     */
    const FLUSH_MODE_SINGLE = 'single';
    const FLUSH_MODE_ALL    = 'all';

    /**
     * persist
     *
     * Persist the entity instance.
     *
     * @param EntityInterface $entity   The entity that should be persisted.
     * @param array           $options  The optional persist options.
     *
     * @return EntityInterface
     *
     * @throws EntityPersistException  If the persist cannot be performed.
     */
    public function persist(EntityInterface $entity, array $options = []);

    /**
     * delete
     *
     * Delete an entity instance.
     *
     * @param EntityInterface $entity  The entity that should be deleted.
     * @param array           $options The optional deletion options.
     *
     * @return boolean
     *
     * @throws EntityPersistException  If the collection cannot be deleted.
     */
    public function delete(EntityInterface $entity, array $options = []);

    /**
     * flush
     *
     * Flush the database changes.
     *
     * @param EntityInterface[]|EntityInterface|null  $entityOrCollection
     * @param array                                   $options
     *
     * @return void
     *
     * @throws EntityPersistException  If the entity or collection cannot be flushed.
     */
    public function flush($entityOrCollection = null, array $options = []);

    /**
     * clear
     *
     * Release managed entities from the identity map.
     *
     * @param string|null  $entityName
     *
     * @return void
     */
    public function clear($entityName = null);

}