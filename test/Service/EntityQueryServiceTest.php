<?php

namespace ArpTest\Entity\Service;

use Arp\Entity\EntityInterface;
use Arp\Entity\QueryFilter\EntityId;
use Arp\Entity\Service\EntityQueryService;
use Arp\Entity\Service\EntityQueryServiceInterface;
use Arp\QueryFilter\QueryFilterInterface;
use Arp\QueryFilter\Service\QueryBuilderInterface;
use Arp\QueryFilter\Service\QueryFilterFactoryInterface;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * EntityQueryServiceTest
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\Entity\Service
 */
class EntityQueryServiceTest extends TestCase
{
    /**
     * entityName
     *
     * @var string
     */
    protected $entityName;

    /**
     * entityManager
     *
     * @var EntityManager|MockObject
     */
    protected $entityManager;

    /**
     * $filterFactory
     *
     * @var QueryFilterFactoryInterface|MockObject
     */
    protected $filterFactory;

    /**
     * setUp
     *
     * @return void
     */
    public function setUp() : void
    {
        $this->entityName = EntityInterface::class;

        $this->entityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->filterFactory = $this->getMockForAbstractClass(QueryFilterFactoryInterface::class);
    }

    /**
     * testImplementsQueryServiceInterface
     *
     * @test
     */
    public function testImplementsQueryServiceInterface()
    {
        $service = new EntityQueryService(
            $this->entityName,
            $this->entityManager,
            $this->filterFactory
        );

        $this->assertInstanceOf(EntityQueryServiceInterface::class, $service);
    }

    /**
     * testFindOneById
     *
     * Find a single entity matching the provided id.
     *
     * @param integer $id
     * @param array   $options
     *
     * @dataProvider getFindOneByIdData
     * @test
     */
    public function testFindOneById($id, array $options = [])
    {
        $filterName = isset($options['id_filter']) ? $options['id_filter'] : EntityId::class;

        /** @var EntityQueryService|MockObject $queryService */
        $queryService = $this->getMockBuilder(EntityQueryService::class)
            ->setConstructorArgs([
                $this->entityName,
                $this->entityManager,
                $this->filterFactory
            ])->setMethods([
                'createQueryBuilder'
            ])->getMock();

        /** @var QueryBuilderInterface|MockObject $queryBuilder */
        $queryBuilder = $this->getMockForAbstractClass(QueryBuilderInterface::class);

        $queryService->expects($this->once())
            ->method('createQueryBuilder')
            ->with('x')
            ->willReturn($queryBuilder);

        /** @var QueryFilterInterface|MockObject $queryFilter */
        $queryFilter = $this->getMockForAbstractClass(QueryFilterInterface::class);

        $this->filterFactory->expects($this->once())
            ->method('create')
            ->with($filterName, [$id])
            ->willReturn($queryFilter);

        $queryBuilder->expects($this->once())
            ->method('where')
            ->with($queryFilter);

        $queryBuilder->expects($this->once())
            ->method('configure')
            ->with($options);

        /** @var AbstractQuery|MockObject $query */
        $query = $this->getMockBuilder(AbstractQuery::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMockForAbstractClass();

        $queryBuilder->expects($this->once())
            ->method('getQuery')
            ->willReturn($query);

        /** @var EntityInterface|MockObject $entity */
        $entity = $this->getMockForAbstractClass(EntityInterface::class);

        $query->expects($this->once())
            ->method('execute')
            ->willReturn([$entity]);

        $this->assertSame($entity, $queryService->findOneById($id, $options));
    }

    /**
     * getFindOneByIdData
     *
     * @return array
     */
    public function getFindOneByIdData()
    {
        return [
            [
                123,
                []
            ],

            [
                456,
                [
                    'foo'   => 'bar',
                    'hello' => 'world!',
                ]
            ],
        ];
    }

//    /**
//     * testFindOne
//     *
//     * @param mixed  $queryFilter
//     * @param array  $options
//     *
//     * @test
//     */
//    public function testFindOne($queryFilter, array $options = [])
//    {
//        $this->markTestIncomplete();
//
//        /** @var EntityQueryServiceInterface|MockObject $service */
//        $service = $this->getMockBuilder(EntityQueryService::class)
//            ->disableOriginalConstructor()
//            ->getMock();
//
//        $this->assertSame(null, $service->findOne($queryFilter, $options));
//    }


}