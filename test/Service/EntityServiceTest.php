<?php

namespace ArpTest\Entity\Service;

use Arp\Entity\EntityInterface;
use Arp\Entity\Exception\EntityQueryException;
use Arp\Entity\Exception\EntityServiceException;
use Arp\Entity\Service\EntityService;
use Arp\Entity\Service\EntityServiceInterface;
use Arp\Entity\Service\EntityServiceOptionsInterface;
use Arp\Entity\Service\EntityPersistServiceInterface;
use Arp\Entity\Service\EntityQueryServiceInterface;
use Arp\Entity\Hydrator\EntityHydratorInterface;
use PHPUnit\Framework\TestCase;

/**
 * EntityServiceTest
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\Entity\Service
 */
class EntityServiceTest extends TestCase
{
    /**
     * $options
     *
     * @var EntityServiceOptionsInterface|MockObject
     */
    protected $options;

    /**
     * $hydrator
     *
     * @var EntityHydratorInterface|MockObject
     */
    protected $hydrator;

    /**
     * $queryService
     *
     * @var EntityQueryServiceInterface|MockObject
     */
    protected $queryService;

    /**
     * $persistService
     *
     * @var EntityPersistServiceInterface|MockObject
     */
    protected $persistService;

    /**
     * setUp
     *
     * Set up the test case dependencies.
     *
     * @return void
     */
    public function setUp() : void
    {
        $this->options = $this->getMockForAbstractClass(EntityServiceOptionsInterface::class);

        $this->hydrator = $this->getMockForAbstractClass(EntityHydratorInterface::class);

        $this->queryService = $this->getMockForAbstractClass(EntityQueryServiceInterface::class);

        $this->persistService = $this->getMockForAbstractClass(EntityPersistServiceInterface::class);
    }

    /**
     * testImplementsEntityServiceInterface
     *
     * Ensure that the service implements EntityServiceInterface.
     *
     * @test
     */
    public function testImplementsEntityServiceInterface()
    {
        $service = new EntityService(
            $this->options,
            $this->hydrator,
            $this->queryService,
            $this->persistService
        );

        $this->assertInstanceOf(EntityServiceInterface::class, $service);
    }

    /**
     * testGetEntityName
     *
     * Ensure that calls to getEntityName() will proxy to the EntityServiceOptions->getClassName().
     *
     * @test
     */
    public function testGetEntityName()
    {
        $service = new EntityService(
            $this->options,
            $this->hydrator,
            $this->queryService,
            $this->persistService
        );

        $className = EntityServiceInterface::class;

        $this->options->expects($this->once())
            ->method('getClassName')
            ->willReturn($className);

        $this->assertSame($className, $service->getEntityName());
    }

    /**
     * testGetOptions
     *
     * Ensure that the default options are returned when calling getOptions(); or optionally merged with the provided
     * $mergeOptions argument.
     *
     * @param array $mergeOptions  The optional options to merge with the defaults.
     *
     * @dataProvider getGetOptionsData
     * @test
     */
    public function testGetOptions(array $mergeOptions = [])
    {
        $options = [
            'foo'  => 'bar',
            'test' => 'Hello',
            'Alex' => 'Patterson',
        ];

        $service = new EntityService(
            $this->options,
            $this->hydrator,
            $this->queryService,
            $this->persistService
        );

        $this->options->expects($this->once())
            ->method('getOptions')
            ->willReturn($options);

        if (! empty($mergeOptions)) {
            $options = array_replace_recursive($options, $mergeOptions);

            $this->options->expects($this->once())
                ->method('getOptionsReplaced')
                ->with($mergeOptions)
                ->willReturn($options);
        }

        /** @var EntityServiceOptionsInterface|MockObject $optionsInstance */
        $optionsInstance = $this->getMockForAbstractClass(EntityServiceOptionsInterface::class);

        $this->options->expects($this->once())
            ->method('factory')
            ->with($options)
            ->willReturn($optionsInstance);

        $result = $service->getOptions($mergeOptions);

        $this->assertSame($optionsInstance, $result);
    }

    /**
     * getGetOptionsData
     *
     * @return array
     */
    public function getGetOptionsData()
    {
        return [
            // Empty test
            [

            ],

            // New options
            [
                [
                    'hello' => 123,
                    'new_option' => 'nice test data!',
                    'thanks' => 'you are so kind.',
                ]
            ],

            // Replace options
            [
                [
                    'testing123' => 'Blahhh',
                    'foo'  => 'bar',
                    'Alex' => 'Pat!!'
                ]
            ]
        ];
    }

    /**
     * testGetOneById
     *
     * @param array $options
     *
     * @test
     */
    public function testGetOneById(array $options = [])
    {
        $id = 123;

        $service = new EntityService(
            $this->options,
            $this->hydrator,
            $this->queryService,
            $this->persistService
        );

        /** @var EntityInterface|MockObject $entity */
        $entity = $this->getMockForAbstractClass(EntityInterface::class);

        $this->queryService->expects($this->once())
            ->method('findOneById')
            ->with($id, $options)
            ->willReturn($entity);

        $this->assertSame($entity, $service->getOneById($id, $options));
    }

    /**
     * testGetOneByIdWillCatchAndThrowEntityServiceException
     *
     * @param array $options
     *
     * @test
     */
    public function testGetOneByIdWillCatchAndThrowEntityServiceException(array $options = [])
    {
        $entityName = EntityInterface::class;
        $id = 123;

        /** @var EntityService|MockObject $service */
        $service = $this->getMockBuilder(EntityService::class)
            ->setConstructorArgs([
                $this->options,
                $this->hydrator,
                $this->queryService,
                $this->persistService
            ])->setMethods([
                'getEntityName'
            ])->getMock();

        $exceptionMessage = 'Test Exception Message';
        $exception = new \Exception($exceptionMessage);

        $this->queryService->expects($this->once())
            ->method('findOneById')
            ->with($id, $options)
            ->willThrowException($exception);

        $service->expects($this->once())
            ->method('getEntityName')
            ->willReturn($entityName);

        $this->expectException(EntityServiceException::class);
        $this->expectExceptionMessage(sprintf(
            'Call to getOneById() failed for entity \'%s\' : %s',
            $entityName,
            $exceptionMessage
        ));

        $service->getOneById($id, $options);
    }

    /**
     * testGetOneWillReturnSingleEntityInterface
     *
     * Ensure that calls to getOne() will return a valid entity instance.
     *
     * @param array $criteria  The search criteria.
     * @param array $options   The optional search options.
     *
     * @dataProvider getGetOneWillReturnSingleEntityInterfaceData
     * @test
     */
    public function testGetOneWillReturnSingleEntityInterface(array $criteria = [], array $options = [])
    {
        $service = new EntityService(
            $this->options,
            $this->hydrator,
            $this->queryService,
            $this->persistService
        );

        /** @var EntityInterface|MockObject $entity */
        $entity = $this->getMockForAbstractClass(EntityInterface::class);

        $this->queryService->expects($this->once())
            ->method('findOne')
            ->with($criteria, $options)
            ->willReturn($entity);

        $this->assertSame($entity, $service->getOne($criteria, $options));
    }

    /**
     * getGetOneWillReturnSingleEntityInterfaceData
     *
     * @return array
     */
    public function getGetOneWillReturnSingleEntityInterfaceData()
    {
        return [
            [],
        ];
    }

    /**
     * testGetOneWillThrowEntityServiceException
     *
     * Ensure that should a EntityQueryException be raised when calling getOne() we catch and rethrow as
     * a new EntityServiceException.
     *
     * @test
     */
    public function testGetOneWillThrowEntityServiceException()
    {
        $service = new EntityService(
            $this->options,
            $this->hydrator,
            $this->queryService,
            $this->persistService
        );

        $criteria = [
            'foo' => 'bar',
        ];
        $options = [
            'test' => 12345,
        ];

        $entityName = EntityInterface::class;

        $this->options->expects($this->once())
            ->method('getClassName')
            ->willReturn($entityName);

        $exceptionMessage = 'This is a test exception message';
        $exception = new EntityQueryException($exceptionMessage);

        $this->queryService->expects($this->once())
            ->method('findOne')
            ->with($criteria, $options)
            ->willThrowException($exception);

        $this->expectException(EntityServiceException::class);
        $this->expectExceptionMessage(sprintf(
            'Call to getOne() failed for entity \'%s\' : %s',
            $entityName,
            $exceptionMessage
        ));

        $service->getOne($criteria, $options);
    }


    /**
     * testGetCollection
     *
     *
     *
     * @test
     */
    public function testGetCollection()
    {
        $service = new EntityService(
            $this->options,
            $this->hydrator,
            $this->queryService,
            $this->persistService
        );

        $criteria = [
            'foo' => 'bar',
        ];
        $options = [
            'test' => 12345,
        ];

        /** @var EntityInterface[]|MockObject[] $collection */
        $collection = [
            $this->getMockForAbstractClass(EntityInterface::class),
            $this->getMockForAbstractClass(EntityInterface::class),
            $this->getMockForAbstractClass(EntityInterface::class),
        ];

        $this->queryService->expects($this->once())
            ->method('findMany')
            ->with($criteria, $options)
            ->willReturn($collection);

        $this->assertSame($collection, $service->getCollection($criteria, $options));
    }

    /**
     * testGetCollectionWillThrowEntityServiceException
     *
     * Ensure that the getCollection() will handle exceptions thrown by findMany() call by catching it and
     * re throwing a EntityServiceException exception.
     *
     * @test
     */
    public function testGetCollectionWillThrowEntityServiceException()
    {
        $service = new EntityService(
            $this->options,
            $this->hydrator,
            $this->queryService,
            $this->persistService
        );

        $criteria = [
            'foo' => 1234,
            'bar' => 'Test',
        ];

        $options = [
            'hello' => 'sdkv',
        ];

        $exceptionMessage = 'This is a test exception message';
        $exception = new EntityQueryException($exceptionMessage);

        $this->queryService->expects($this->once())
            ->method('findMany')
            ->with($criteria, $options)
            ->willThrowException($exception);

        $entityName = EntityInterface::class;

        $this->options->expects($this->once())
            ->method('getClassName')
            ->willReturn($entityName);


        $this->expectException(EntityServiceException::class);
        $this->expectExceptionMessage(sprintf(
            'Call to getCollection() failed for entity \'%s\' : %s',
            $entityName,
            $exceptionMessage
        ));

        $service->getCollection($criteria, $options);
    }

    /**
     * testSaveWillUpdateEntityWithId
     *
     * Ensure that the entity provided to save() will be updated if it has an identity.
     *
     * @param  array  $options  The options that should be used.
     *
     * @test
     */
    public function testSaveWillUpdateEntityWithId(array $options = [])
    {
        /** @var EntityService|MockObject $service */
        $service = $this->getMockBuilder(EntityService::class)
            ->setConstructorArgs([
                $this->options,
                $this->hydrator,
                $this->queryService,
                $this->persistService
            ])->setMethods([
                'update',
            ])->getMock();

        /** @var EntityInterface|MockObject $entity */
        $entity = $this->getMockForAbstractClass(EntityInterface::class);

        $entity->expects($this->once())
            ->method('hasId')
            ->willReturn(true);

        $service->expects($this->once())
            ->method('update')
            ->with($entity, $options)
            ->willReturn($entity);

        $this->assertSame($entity, $service->save($entity, $options));
    }

    /***
     * testSaveWillCreateEntityWithoutId
     *
     * Ensure that the entity provided to save() will be created if it does not have an identity.
     *
     * @param array $options
     *
     * @test
     */
    public function testSaveWillCreateEntityWithoutId(array $options = [])
    {
        /** @var EntityService|MockObject $service */
        $service = $this->getMockBuilder(EntityService::class)
            ->setConstructorArgs([
                $this->options,
                $this->hydrator,
                $this->queryService,
                $this->persistService
            ])->setMethods([
                'create',
            ])->getMock();

        /** @var EntityInterface|MockObject $entity */
        $entity = $this->getMockForAbstractClass(EntityInterface::class);

        $entity->expects($this->once())
            ->method('hasId')
            ->willReturn(false);

        $service->expects($this->once())
            ->method('create')
            ->with($entity, $options)
            ->willReturn($entity);

        $this->assertSame($entity, $service->save($entity, $options));
    }

    /**
     * testSaveCollection
     *
     * Ensure that we save a collection of entity instances by passing them each to save() with the correct
     * transaction options.
     *
     * @param boolean  $transactionsEnabled  If we should test transaction handling.
     * @param boolean  $flushEnabled         If we should test the flush
     * @param array    $options              Client code options argument.
     *
     * @test
     */
    public function testSaveCollection($transactionsEnabled = true, $flushEnabled = true, array $options = [])
    {
        /** @var EntityService|MockObject $service */
        $service = $this->getMockBuilder(EntityService::class)
            ->setConstructorArgs([
                $this->options,
                $this->hydrator,
                $this->queryService,
                $this->persistService
            ])->setMethods([
                'getOptions',
                'save'
            ])->getMock();

        /** @var EntityInterface[]|MockObject[] $collection */
        $collection = [
            $this->getMockForAbstractClass(EntityInterface::class),
            $this->getMockForAbstractClass(EntityInterface::class),
            $this->getMockForAbstractClass(EntityInterface::class),
            $this->getMockForAbstractClass(EntityInterface::class),
            $this->getMockForAbstractClass(EntityInterface::class),
        ];

        $service->expects($this->once())
            ->method('getOptions')
            ->with($options)
            ->willReturn($this->options);

        $this->options->expects($this->once())
            ->method('isTransactionEnabled')
            ->willReturn($transactionsEnabled);

        if ($transactionsEnabled) {
            $this->persistService->expects($this->once())->method('beginTransaction');
        }

        $saveOptions = array_merge_recursive(
            [
                'transaction_enabled' => false,
                'flush_enabled'       => false,
            ],
            (isset($options['save_options']) ? $options['save_options'] : [])
        );

        $this->options->expects($this->once())
            ->method('getOption')
            ->with('save_options', [])
            ->willReturn($saveOptions);


        $saveArgs = [];

        foreach($collection as $index => $entity) {

            $saveArgs[] = [
                $entity,
                $saveOptions
            ];
        }

        $service->expects($this->exactly(count($collection)))
            ->method('save')
            ->withConsecutive(...$saveArgs)
            ->willReturn(...$collection);

        $this->options->expects($this->once())
            ->method('isFlushEnabled')
            ->willReturn($flushEnabled);

        $flushOptions = isset($options['flush_options']) ? $options['flush_options'] : [];

        if ($flushEnabled) {
            $this->options->expects($this->once())
                ->method('getFlushOptions')
                ->willReturn($flushOptions);

            $this->persistService->expects($this->once())
                ->method('flush')
                ->with($collection, $flushOptions);
        }

        if ($transactionsEnabled) {
            $this->persistService->expects($this->once())->method('commitTransaction');
        }

        $this->assertSame($collection, $service->saveCollection($collection, $options));
    }

    /**
     * testSaveCollectionWillThrowEntityServiceExceptionNoError
     *
     * @param boolean  $transactionsEnabled
     * @param boolean  $throwExceptions
     * @param array    $options
     *
     * @test
     */
    public function testSaveCollectionWillThrowEntityServiceExceptionNoError(
        $transactionsEnabled = true,
        $throwExceptions = true,
        array $options = []
    ){
        /** @var EntityService|MockObject $service */
        $service = $this->getMockBuilder(EntityService::class)
            ->setConstructorArgs([
                $this->options,
                $this->hydrator,
                $this->queryService,
                $this->persistService
            ])->setMethods([
                'getOptions',
                'save'
            ])->getMock();

        /** @var EntityInterface[]|MockObject[] $collection */
        $collection = [
            $this->getMockForAbstractClass(EntityInterface::class),
            $this->getMockForAbstractClass(EntityInterface::class),
            $this->getMockForAbstractClass(EntityInterface::class),
            $this->getMockForAbstractClass(EntityInterface::class),
            $this->getMockForAbstractClass(EntityInterface::class),
        ];

        $service->expects($this->once())
            ->method('getOptions')
            ->with($options)
            ->willReturn($this->options);

        $this->options->expects($this->once())
            ->method('isTransactionEnabled')
            ->willReturn($transactionsEnabled);

        if ($transactionsEnabled) {
            $this->persistService->expects($this->once())->method('beginTransaction');
        }

        $saveOptions = array_merge_recursive(
            [
                'transaction_enabled' => false,
                'flush_enabled'       => false,
            ],
            (isset($options['save_options']) ? $options['save_options'] : [])
        );

        $this->options->expects($this->exactly(2))
            ->method('getOption')
            ->withConsecutive(
                ['save_options', []],
                ['throw_exceptions', true]
            )->willReturnOnConsecutiveCalls(
                $saveOptions,
                $throwExceptions
            );

        $exceptionMessage = 'This is a test message';
        $exception = new \Exception($exceptionMessage);

        $service->expects($this->exactly(1))
            ->method('save')
            ->with($collection[0], $saveOptions)
            ->willThrowException($exception);

        if ($transactionsEnabled) {
            $this->persistService->expects($this->once())->method('rollbackTransaction');
        }

        if ($throwExceptions) {

            $this->expectException(EntityServiceException::class);
            $this->expectExceptionMessage($exceptionMessage);
        }

        $this->assertSame($collection, $service->saveCollection($collection, $options));
    }



}