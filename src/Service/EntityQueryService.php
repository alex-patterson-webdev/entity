<?php

namespace Arp\Entity\Service;

use Arp\DoctrineQueryFilter\Service\QueryBuilderInterface;
use Arp\Entity\EntityInterface;
use Arp\Entity\Exception\EntityQueryException;
use Arp\Entity\QueryFilter\EntityCriteria;
use Arp\Entity\QueryFilter\EntityId;
use Arp\DoctrineQueryFilter\Service\QueryFilterFactoryInterface;
use Arp\Stdlib\Service\OptionsAwareInterface;
use Arp\Stdlib\Service\OptionsAwareTrait;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;

/**
 * EntityQueryService
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Service
 */
class EntityQueryService implements EntityQueryServiceInterface, OptionsAwareInterface
{
    /**
     * @trait OptionsAwareTrait
     */
    use OptionsAwareTrait;

    /**
     * $entityName
     *
     * @var string
     */
    protected $entityName;

    /**
     * $entityManager
     *
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * $queryFilterFactory
     *
     * @var QueryFilterFactoryInterface
     */
    protected $filterFactory;

    /**
     * __construct
     *
     * @param                             $entityName
     * @param EntityManager               $entityManager
     * @param QueryFilterFactoryInterface $filterFactory
     * @param array                       $options
     */
    public function __construct(
        $entityName,
        EntityManager $entityManager,
        QueryFilterFactoryInterface $filterFactory,
        array $options = []
    ){
        $this->entityName    = $entityName;
        $this->entityManager = $entityManager;
        $this->filterFactory = $filterFactory;

        if (! empty($options)) {
            $this->setOptions($options);
        }
    }

    /**
     * getSingleResultOrNull
     *
     * @param AbstractQuery|QueryBuilderInterface  $queryOrBuilder
     * @param array                                $options
     *
     * @return EntityInterface|null
     *
     * @throws EntityQueryException
     */
    protected function getSingleResultOrNull($queryOrBuilder, array $options = [])
    {
        $results = $this->execute($queryOrBuilder, $options);

        if (! empty($results) && count($results) == 1) {
            return current($results);
        }

        return null;
    }

    /**
     * execute
     *
     * Construct and execute the query.
     *
     * @param AbstractQuery|QueryBuilderInterface  $queryOrBuilder
     * @param array                                $options
     *
     * @return mixed
     *
     * @throws EntityQueryException
     */
    protected function execute($queryOrBuilder, array $options = [])
    {
        try {
            return $queryOrBuilder->execute();
        }
        catch (\Exception $e) {

            throw new EntityQueryException(
                sprintf(
                    'Failed to execute query : %s',
                    $e->getMessage()
                ),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * findOneById
     *
     * Find a single entity matching the provided identity.
     *
     * @param mixed $id      The identity of the entity to match.
     * @param array $options The optional query options.
     *
     * @return EntityInterface|null
     *
     * @throws EntityQueryException
     */
    public function findOneById($id, array $options = []) : ?EntityInterface
    {
        $queryBuilder = $this->createQueryBuilder('x');
        $filterName   = $this->getOption('id_filter', EntityId::class);

        try {
            $queryBuilder->where(
                $this->factory()->create($filterName, [$id])
            );
        }
        catch (QueryBuilderException $e) {

            throw new EntityQueryException(
                sprintf(
                    'Failed to perform \'findOneById\' : %s',
                    $e->getMessage()
                ),
                $e->getCode(),
                $e
            );
        }

        return $this->getSingleResultOrNull($queryBuilder, $options);
    }

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
    public function findOne($queryFilter, array $options = []) : ?EntityInterface
    {
        $queryOptions  = isset($options['query_options'])  ? $options['query_options'] : [];
        $resultOptions = isset($options['result_options']) ? $options['result_options'] : [];

        $criteria = $this->createCriteriaFilter($queryFilter, $queryOptions);

        try {
            $queryBuilder = $this->createQueryBuilder('x')->where($criteria)->limit(1);
        }
        catch (QueryBuilderException $e) {

            throw new EntityQueryException(
                sprintf(
                    'Failed to perform \'findOne\' : %s',
                    $e->getMessage()
                ),
                $e->getCode(),
                $e
            );
        }

        return $this->getSingleResultOrNull($queryBuilder, $resultOptions);
    }

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
    public function findMany($queryFilter, array $options = [])
    {
        $criteria = $this->createCriteriaFilter($queryFilter);

        try {
            $queryBuilder = $this->createQueryBuilder('x')->where($criteria);
        }
        catch (QueryBuilderException $e) {

            throw new EntityQueryException(
                sprintf(
                    'Failed to perform \'findMany\' : %s',
                    $e->getMessage()
                ),
                $e->getCode(),
                $e
            );
        }

        return $this->execute($queryBuilder, $options);
    }


    /**
     * createQueryBuilder
     *
     * @param string $alias
     * @param array  $options
     *
     * @return QueryBuilderInterface
     */
    protected function createQueryBuilder($alias, array $options = []) : QueryBuilderInterface
    {
        $queryBuilder = new \Arp\DoctrineQueryFilter\Service\QueryBuilder(
            $this->entityManager->createQueryBuilder(),
            $this->filterFactory
        );

        return $queryBuilder->select($alias)->from($this->entityName, $alias);
    }

    /**
     * factory
     *
     * @return QueryFilterFactoryInterface
     */
    public function factory() : QueryFilterFactoryInterface
    {
        return $this->filterFactory;
    }
}