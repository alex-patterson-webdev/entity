<?php

namespace ArpTest\Entity\Service;

use Arp\Entity\EntityInterface;
use Arp\Entity\Exception\EntityPersistException;
use Arp\Entity\Service\EntityPersistService;
use Arp\Entity\Service\EntityPersistServiceInterface;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

/**
 * EntityPersistServiceTest
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\Entity\Service
 */
class EntityPersistServiceTest extends TestCase
{
    /**
     * $entityManager
     *
     * @var EntityManager|MockObject
     */
    protected $entityManager;

    /**
     * setUp
     *
     * Set up the test dependencies.
     *
     * @return void
     */
    public function setUp() : void
    {
        $this->entityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * testImplementsEntityPersistServiceInterface
     *
     * Ensure that the persist service implements EntityPersistServiceInterface.
     *
     * @test
     */
    public function testImplementsEntityPersistServiceInterface()
    {
        $service = new EntityPersistService($this->entityManager);

        $this->assertInstanceOf(EntityPersistServiceInterface::class, $service);
    }

    /**
     * testPersistWillPersistEntityWithoutId
     *
     * Ensure that the entity will be persisted if it has no id.
     *
     * @param  array $options
     *
     * @test
     */
    public function testPersistWillPersistEntityWithoutId(array $options = [])
    {
        $service = new EntityPersistService($this->entityManager);

        /** @var EntityInterface|MockObject $entity */
        $entity = $this->getMockForAbstractClass(EntityInterface::class);

        $entity->expects($this->once())
            ->method('hasId')
            ->willReturn(false);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($entity);

        $this->assertSame($entity, $service->persist($entity, $options));
    }

    /**
     * testPersistWillIgnorePersistOfEntityWithId
     *
     * Prevent the call to persist if the provided entity has an id.
     *
     * @param array $options
     *
     * @test
     */
    public function testPersistWillIgnorePersistOfEntityWithId(array $options = [])
    {
        $service = new EntityPersistService($this->entityManager);

        /** @var EntityInterface|MockObject $entity */
        $entity = $this->getMockForAbstractClass(EntityInterface::class);

        $entity->expects($this->once())
            ->method('hasId')
            ->willReturn(true);

        // We never what to call persist.
        $this->entityManager->expects($this->never())->method('persist');

        $this->assertSame($entity, $service->persist($entity, $options));
    }

    /**
     * testPersistWillCatchAndRethrowEntityPersistException
     *
     * @param array $options
     *
     * @test
     */
    public function testPersistWillCatchAndRethrowEntityPersistException(array $options = [])
    {
        $service = new EntityPersistService($this->entityManager);

        /** @var EntityInterface|MockObject $entity */
        $entity = $this->getMockForAbstractClass(EntityInterface::class);

        $entity->expects($this->once())
            ->method('hasId')
            ->willReturn(false);

        $exceptionMessage = 'This is an exception message test.';
        $exception = new \Exception($exceptionMessage);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->willThrowException($exception);

        $this->expectException(EntityPersistException::class);
        $this->expectExceptionMessage($exceptionMessage);
        $this->expectExceptionCode($exception->getCode());

        $this->assertSame($entity, $service->persist($entity, $options));
    }

    /**
     * testFlushWithEntity
     *
     * Test flush with different configurable arguments to ensure we perform the correct flush on the entity manager.
     *
     * @param array  $options
     *
     * @dataProvider getFlushWithEntityData
     * @test
     */
    public function testFlushWithEntity(array $options = [])
    {
        $service = new EntityPersistService($this->entityManager);

        /** @var EntityInterface|MockObject $entity */
        $entity = $this->getMockForAbstractClass(EntityInterface::class);

        $mode = isset($options['mode']) ? $options['mode'] : 'single';

        if ($mode === 'single') {
            $this->entityManager->expects($this->once())
                ->method('flush')
                ->with($entity);
        } else {
            $this->entityManager->expects($this->once())
                ->method('flush');
        }

        $service->flush($entity, $options);
    }

    /**
     * getFlushWithEntityData
     *
     * @return array
     */
    public function getFlushWithEntityData()
    {
        return [
            [
                // no arguments
            ],

            [
                [], // Empty options
            ],

            [
                [
                    'mode' => 'SINGLE',
                ]
            ],

            [
                [
                    'mode' => 'ALL',
                ]
            ],

        ];
    }

    /**
     * testFlushWithEntityCollection
     *
     * @param array $options
     *
     * @dataProvider getFlushWithEntityCollectionData
     * @test
     */
    public function testFlushWithEntityCollection(array $options = [])
    {
        $service = new EntityPersistService($this->entityManager);

        /** @var EntityInterface[]|MockObject[] $collection */
        $collection = [
            $this->getMockForAbstractClass(EntityInterface::class),
            $this->getMockForAbstractClass(EntityInterface::class),
            $this->getMockForAbstractClass(EntityInterface::class),
            $this->getMockForAbstractClass(EntityInterface::class),
            $this->getMockForAbstractClass(EntityInterface::class),
        ];

        $mode = isset($options['mode']) ? $options['mode'] : 'single';

        if ($mode === 'single') {
            $this->entityManager->expects($this->once())
                ->method('flush')
                ->with($collection);
        } else {
            $this->entityManager->expects($this->once())
                ->method('flush');
        }

        $service->flush($collection, $options);
    }

    /**
     * getFlushWithEntityCollectionData
     *
     * @return array
     */
    public function getFlushWithEntityCollectionData()
    {
        return [
            [
                // no arguments
            ],

            [
                [], // Empty options
            ],

            [
                [
                    'mode' => 'SINGLE',
                ]
            ],

            [
                [
                    'mode' => 'ALL',
                ]
            ],

        ];
    }

    /**
     * testClear
     *
     * @param null|string $entityName
     *
     * @dataProvider getClearData
     * @test
     */
    public function testClear($entityName = null)
    {
        $service = new EntityPersistService($this->entityManager);

        $this->entityManager->expects($this->once())
            ->method('clear')
            ->with($entityName);

        $service->clear($entityName);
    }

    /**
     * getClearData
     *
     * @return array
     */
    public function getClearData()
    {
        return [
            [
                null,
            ],
            [
                EntityInterface::class,
            ],
        ];
    }

    /**
     * testDelete
     *
     * Ensure that when deleting an entity we pass it to the entityManager->remove() and return true.
     *
     * @param array $options
     *
     * @test
     */
    public function testDelete(array $options = [])
    {
        $service = new EntityPersistService($this->entityManager);

        /** @var EntityInterface|MockObject $entity */
        $entity = $this->getMockForAbstractClass(EntityInterface::class);

        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with($entity);

        $this->assertTrue($service->delete($entity, $options));
    }

    /**
     * testDeleteWillThrowEntityPersistException
     *
     * Ensure that an exception is caught an rethrown as a EntityPersistException.
     *
     * @param array $options
     *
     * @test
     */
    public function testDeleteWillThrowEntityPersistException(array $options = [])
    {
        $service = new EntityPersistService($this->entityManager);

        /** @var EntityInterface|MockObject $entity */
        $entity = $this->getMockForAbstractClass(EntityInterface::class);

        $message   = 'This is a test exception message';
        $exception = new \Exception($message);

        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with($entity)
            ->willThrowException($exception);

        $this->expectException(EntityPersistException::class);
        $this->expectExceptionCode($exception->getCode());
        $this->expectExceptionMessage($message);

        $this->assertTrue($service->delete($entity, $options));
    }

    /**
     * testBeingTransaction
     *
     * Ensure the call to beingTransaction() is proxied to the EntityManager.
     *
     * @test
     */
    public function testBeingTransaction()
    {
        $service = new EntityPersistService($this->entityManager);

        $this->entityManager->expects($this->once())->method('beginTransaction');

        $service->beginTransaction();
    }

    /**
     * testCommitTransaction
     *
     * Test that when calling commitTransaction() will proxy the call to the EntityManager.
     *
     * @test
     */
    public function testCommitTransaction()
    {
        $service = new EntityPersistService($this->entityManager);

        $this->entityManager->expects($this->once())->method('commit');

        $service->commitTransaction();
    }

    /**
     * testRollbackTransaction
     *
     * Ensure that the call to rollback transaction will be proxied to the EntityManager.
     *
     * @test
     */
    public function testRollbackTransaction()
    {
        $service = new EntityPersistService($this->entityManager);

        $this->entityManager->expects($this->once())->method('rollback');

        $service->rollbackTransaction();
    }

}