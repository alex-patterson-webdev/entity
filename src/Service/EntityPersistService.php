<?php

namespace Arp\Entity\Service;

use Arp\Entity\EntityInterface;
use Arp\Entity\Exception\EntityPersistException;
use Doctrine\ORM\EntityManager;

/**
 * EntityPersistService
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Service
 */
class EntityPersistService implements EntityPersistServiceInterface
{
    /**
     * $entityManager
     *
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * __construct

     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * persist
     *
     * Persist the entity instance.
     *
     * @param EntityInterface $entity  The entity that should be persisted.
     * @param array           $options The optional persist options.
     *
     * @return EntityInterface
     *
     * @throws EntityPersistException  If the persist cannot be performed.
     */
    public function persist(EntityInterface $entity, array $options = [])
    {
        try {
            if (! $entity->hasId()) {
                $this->entityManager->persist($entity);
            }
        }
        catch (\Exception $e) {

            throw new EntityPersistException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }

        return $entity;
    }

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
    public function delete(EntityInterface $entity, array $options = [])
    {
        try {
            $this->entityManager->remove($entity);
        }
        catch (\Exception $e) {

            throw new EntityPersistException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }

        return true;
    }

    /**
     * flush
     *
     * Flush the database changes.
     *
     * @param EntityInterface[]|EntityInterface|null $entityOrCollection
     * @param array                                  $options
     *
     * @return void
     *
     * @throws EntityPersistException  If the entity or collection cannot be flushed.
     */
    public function flush($entityOrCollection = null, array $options = [])
    {
        $mode = isset($options['mode']) ? $options['mode'] : static::FLUSH_MODE_SINGLE;

        if (! empty($entityOrCollection) && static::FLUSH_MODE_SINGLE === $mode) {
            $this->entityManager->flush($entityOrCollection);
        }
        else {
            $this->entityManager->flush();
        }
    }

    /**
     * clear
     *
     * Release managed entities from the identity map.
     *
     * @param string|null  $entityName
     *
     * @return void
     */
    public function clear($entityName = null)
    {
        $this->entityManager->clear($entityName);
    }

    /**
     * beginTransaction
     *
     * @return void
     */
    public function beginTransaction()
    {
        $this->entityManager->beginTransaction();
    }

    /**
     * commitTransaction
     *
     * @return void
     */
    public function commitTransaction()
    {
        $this->entityManager->commit();
    }

    /**
     * rollbackTransaction
     *
     * @return void
     */
    public function rollbackTransaction()
    {
        $this->entityManager->rollback();
    }

}