<?php

namespace Arp\Entity\Service;

use Arp\Entity\EntityInterface;
use Arp\Entity\Exception\EntityServiceException;

/**
 * EntityServiceInterface
 *
 * Service class to manage the searching and persistence of a objects implementing EntityInterface.
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Service
 */
interface EntityServiceInterface
{
    /**
     * getEntityName
     *
     * @return string
     */
    public function getEntityName() : string;

    /**
     * getOptions
     *
     * Return the entity service options; with optional overrides.
     *
     * @param array $mergeOptions  Optional options that should be merged.
     *
     * @return EntityServiceOptionsInterface
     */
    public function getOptions($mergeOptions = []) : EntityServiceOptionsInterface;

    /**
     * getOneById
     *
     * Return a single entity matching the provided identity.
     *
     * @param integer  $id       The entity identity to find.
     * @param array    $options  The optional result set options.
     *
     * @return EntityInterface|null
     *
     * @throws EntityServiceException
     */
    public function getOneById($id, array $options = []) :?EntityInterface;

    /**
     * getOne
     *
     * Return a single entity matching the provided criteria.
     *
     * @param mixed  $criteria  The search criteria to filter by.
     * @param array  $options   The optional search options.
     *
     * @return EntityInterface|null
     *
     * @throws EntityServiceException
     */
    public function getOne($criteria, array $options = []) :?EntityInterface;

    /**
     * getCollection
     *
     * Return a collection of entities matching the provided $criteria.
     *
     * @param mixed  $criteria  The search criteria to filter by.
     * @param array  $options   The optional search options.
     *
     * @return EntityInterface[]
     *
     * @throws EntityServiceException
     */
    public function getCollection($criteria, array $options = []);

    /**
     * createInstance
     *
     * Create a new instance of the managed class.
     *
     * @param array  $data     The optional hydration data.
     * @param array  $options  The optional instance options.
     *
     * @return EntityInterface
     *
     * @throws EntityServiceException
     */
    public function createInstance(array $data = [], array $options = []) : EntityInterface;

    /**
     * save
     *
     * Create or update a entity instance.
     *
     * @param EntityInterface  $entity   The entity instance that should be saved.
     * @param array            $options  The optional save options.
     *
     * @return EntityInterface
     *
     * @throws EntityServiceException  If the entity cannot be saved.
     */
    public function save(EntityInterface $entity, array $options = []) : EntityInterface;

    /**
     * saveCollection
     *
     * Save a collection of entity instances.
     *
     * @param EntityInterface[]  $collection  The collection of entities that should be saved.
     * @param array              $options     The optional saving options,
     *
     * @return EntityInterface[]
     *
     * @throws EntityServiceException  If the collection cannot be saved.
     */
    public function saveCollection($collection, array $options = []);

    /**
     * delete
     *
     * Delete the entity instance.
     *
     * @param EntityInterface  $entity   The entity that should be deleted.
     * @param array            $options  The optional entity delete options.
     *
     * @return boolean
     *
     * @throws EntityServiceException  If the entity cannot be deleted.
     */
    public function delete(EntityInterface $entity, array $options = []) : bool;

    /**
     * deleteCollection
     *
     * Remove a collection of entities in a single transaction.
     *
     * @param EntityInterface[] $collection  The collection of entities that should be removed.
     * @param array             $options     The optional delete options.
     *
     * @return EntityInterface[]
     *
     * @throws EntityServiceException  If the collection cannot be deleted.
     */
    public function deleteCollection($collection, array $options = []);

}