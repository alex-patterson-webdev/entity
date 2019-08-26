<?php

namespace Arp\Entity\Service;

use Arp\Entity\EntityInterface;
use Arp\Entity\Exception\EntityQueryException;
use Arp\Entity\Exception\EntityServiceException;
use Arp\Entity\Hydrator\EntityHydratorInterface;

/**
 * AbstractEntityService
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Service
 */
abstract class AbstractEntityService implements EntityServiceInterface
{
    /**
     * $options
     *
     * @var EntityServiceOptionsInterface
     */
    protected $options;

    /**
     * $hydrator
     *
     * @var EntityHydratorInterface
     */
    protected $hydrator;

    /**
     * $queryService
     *
     * @var EntityQueryServiceInterface
     */
    protected $queryService;

    /**
     * $persistService
     *
     * @var EntityPersistServiceInterface
     */
    protected $persistService;

    /**
     * __construct
     *
     * @param EntityServiceOptionsInterface $options
     * @param EntityHydratorInterface       $hydrator
     * @param EntityQueryServiceInterface   $queryService
     * @param EntityPersistServiceInterface $persistService
     */
    public function __construct(
        EntityServiceOptionsInterface $options,
        EntityHydratorInterface $hydrator,
        EntityQueryServiceInterface $queryService,
        EntityPersistServiceInterface $persistService
    ){
        $this->options        = $options;
        $this->hydrator       = $hydrator;
        $this->queryService   = $queryService;
        $this->persistService = $persistService;
    }

    /**
     * getEntityName
     *
     * @return string
     */
    public function getEntityName() : string
    {
        return $this->options->getClassName();
    }

    /**
     * getOptions
     *
     * Return the entity service options; with optional overrides.
     *
     * @param array $mergeOptions Optional options that should be merged.
     *
     * @return EntityServiceOptionsInterface
     */
    public function getOptions($mergeOptions = []) : EntityServiceOptionsInterface
    {
        $options = $this->options->getOptions();

        if (! empty($mergeOptions)) {
            $options = $this->options->getOptionsReplaced($mergeOptions);
        }

        return $this->options->factory($options);
    }

    /**
     * getOneById
     *
     * Return a single entity matching the provided identity.
     *
     * @param integer $id      The entity identity to find.
     * @param array   $options The optional result set options.
     *
     * @return EntityInterface|null
     *
     * @throws EntityServiceException
     */
    public function getOneById($id, array $options = []) :?EntityInterface
    {
        try {
            return $this->queryService->findOneById($id, $options);
        }
        catch (\Exception $e) {

            throw new EntityServiceException(
                sprintf(
                    'Call to getOneById() failed for entity \'%s\' : %s',
                    $this->getEntityName(),
                    $e->getMessage()
                ),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * getOne
     *
     * Return a single entity matching the provided criteria.
     *
     * @param mixed $criteria The search criteria to filter by.
     * @param array $options  The optional search options.
     *
     * @return EntityInterface|null
     *
     * @throws EntityServiceException
     */
    public function getOne($criteria, array $options = []) : ?EntityInterface
    {
        try {
            return $this->queryService->findOne($criteria, $options);
        }
        catch (EntityQueryException $e) {

            throw new EntityServiceException(
                sprintf(
                    'Call to getOne() failed for entity \'%s\' : %s',
                    $this->getEntityName(),
                    $e->getMessage()
                ),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * getCollection
     *
     * Return a collection of entities matching the provided $criteria.
     *
     * @param mixed  $criteria The search criteria to filter by.
     * @param array  $options  The optional search options.
     *
     * @return EntityInterface[]
     *
     * @throws EntityServiceException
     */
    public function getCollection($criteria, array $options = [])
    {
        try {
            return $this->queryService->findMany($criteria, $options);
        }
        catch (EntityQueryException $e) {

            throw new EntityServiceException(sprintf(
                'Call to getCollection() failed for entity \'%s\' : %s',
                $this->getEntityName(),
                $e->getMessage()
            ));
        }
    }

    /**
     * save
     *
     * Create or update a entity instance.
     *
     * @param EntityInterface $entity  The entity instance that should be saved.
     * @param array           $options The optional save options.
     *
     * @return EntityInterface
     *
     * @throws EntityServiceException  If the entity cannot be saved.
     */
    public function save(EntityInterface $entity, array $options = []) : EntityInterface
    {
        if ($entity->hasId()) {
            $entity = $this->update($entity, $options);
        }
        else {
            $entity = $this->create($entity, $options);
        }

        return $entity;
    }

    /**
     * saveCollection
     *
     * Save a collection of entity instances.
     *
     * @param EntityInterface[] $collection The collection of entities that should be saved.
     * @param array             $options    The optional saving options,
     *
     * @return EntityInterface[]
     *
     * @throws EntityServiceException  If the collection cannot be saved.
     */
    public function saveCollection($collection, array $options = [])
    {
        $options = $this->getOptions($options);

        $transaction = $options->isTransactionEnabled();

        try {
            if ($transaction) {
                $this->persistService->beginTransaction();
            }

            $entityOptions = array_replace_recursive(
                [
                    'transaction_enabled' => false,
                    'flush_enabled'       => false,
                ],
                $options->getOption('save_options', [])
            );

            foreach($collection as $index => $entity) {
                $collection[$index] = $this->save($entity, $entityOptions);
            }

            if ($options->isFlushEnabled()) {
                $this->persistService->flush($collection, $options->getFlushOptions());
            }

            if ($transaction) {
                $this->persistService->commitTransaction();
            }
        }
        catch (\ Exception $e) {

            if ($transaction) {
                $this->persistService->rollbackTransaction();
            }

            $this->handleException($e);
        }

        return $collection;
    }

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
    public function deleteCollection($collection, array $options = [])
    {
        $options = $this->getOptions($options);

        $transaction = $options->isTransactionEnabled();

        try {
            if ($transaction) {
                $this->persistService->beginTransaction();
            }

            $entityOptions = array_replace_recursive(
                [
                    'transaction_enabled' => false,
                    'flush_enabled'       => false,
                ],
                $options->getOption('delete_options', [])
            );

            foreach($collection as $index => $entity) {
                $this->delete($entity, $entityOptions);
            }

            if ($options->isFlushEnabled()) {
                $this->persistService->flush($collection, $options->getFlushOptions());
            }

            if ($transaction) {
                $this->persistService->commitTransaction();
            }
        }
        catch (\Exception $e) {

            if ($transaction) {
                $this->persistService->rollbackTransaction();
            }

            $this->handleException($e);
        }

        return $collection;
    }

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
    abstract protected function create(EntityInterface $entity, array $options = []) : EntityInterface;

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
    abstract protected function update(EntityInterface $entity, array $options = []) : EntityInterface;

    /**
     * createInstance
     *
     * Create a new instance of the managed class.
     *
     * @param array $data    The optional hydration data.
     * @param array $options The optional instance options.
     *
     * @return EntityInterface
     *
     * @throws EntityServiceException
     */
    public function createInstance(array $data = [], array $options = []) : EntityInterface
    {
        $entityName = $this->getEntityName();
        $entity = new $entityName;

        if (! empty($data)) {
            $entity = $this->hydrate($entity, $data);
        }

        return $entity;
    }

    /**
     * hydrate
     *
     * Hydrate the entity instance.
     *
     * @param EntityInterface $entity The entity that should be hydrated.
     * @param array           $data   The hydration data.
     *
     * @return EntityInterface
     *
     * @throws EntityServiceException  If the entity cannot be hydrated.
     */
    public function hydrate(EntityInterface $entity, array $data) : EntityInterface
    {
        $entityName = $this->getEntityName();

        if (! $entity instanceof $entityName) {

            throw new EntityServiceException(sprintf(
                'The \'entity\' argument must be an object of type \'%s\'; \'%s\' provided in \'%s\'.',
                $entityName,
                get_class($entity),
                __METHOD__
            ));
        }

        return $this->hydrator->hydrate($data, $entity);
    }

    /**
     * handleException
     *
     * @param \Exception  $exception
     * @param string|null $message
     *
     * @throws EntityServiceException
     */
    protected function handleException(\Exception $exception, $message = null)
    {
        if ($exception instanceof EntityServiceException) {
            throw $exception;
        }
        else {

            if (empty($message)) {
                $message = $exception->getMessage();
            }

            throw new EntityServiceException(
                $message,
                $exception->getCode(),
                $exception
            );
        }
    }
}
