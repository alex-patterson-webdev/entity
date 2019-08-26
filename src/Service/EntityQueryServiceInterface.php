<?php

namespace Arp\Entity\Service;

use Arp\Entity\EntityInterface;
use Arp\QueryFilter\Service\QueryFilterFactoryInterface;
use Arp\Entity\Exception\EntityQueryException;
use Arp\QueryFilter\QueryFilterInterface;

/**
 * EntityQueryServiceInterface
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Service
 */
interface EntityQueryServiceInterface
{
    /**
     * $factory
     *
     * Return the query filter factory.
     *
     * @return QueryFilterFactoryInterface
     */
    public function factory() : QueryFilterFactoryInterface;

    /**
     * findOneById
     *
     * Find a single entity matching the provided identity.
     *
     * @param mixed  $id        The identity of the entity to match.
     * @param array  $options   The optional query options.
     *
     * @return EntityInterface|null
     *
     * @throws EntityQueryException
     */
    public function findOneById($id, array $options = []) :?EntityInterface;

    /**
     * findOne
     *
     * Find a single entity matching the provided criteria.
     *
     * @param array|QueryFilterInterface  $queryFilter  The search criteria that should be matched on.
     * @param array                       $options      The optional query options.
     *
     * @return EntityInterface|null
     *
     * @throws EntityQueryException
     */
    public function findOne($queryFilter, array $options = []) :?EntityInterface;

    /**
     * findMany
     *
     * Find a collection of entities that match the provided criteria.
     *
     * @param array|QueryFilterInterface  $queryFilter  The search criteria that should be matched on.
     * @param array                       $options      The optional query options.
     *
     * @return EntityInterface[]
     *
     * @throws EntityQueryException
     */
    public function findMany($queryFilter, array $options = []);

}