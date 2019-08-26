<?php

namespace ArpTest\Entity\Event\Listener;

use Arp\DateTime\Service\DateTimeFactoryInterface;
use Arp\Entity\Event\Listener\DateTimeStrategy;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Zend\EventManager\ListenerAggregateInterface;

/**
 * DateTimeStrategyTest
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\Entity\Event\Listener
 */
class DateTimeStrategyTest extends TestCase
{
    /**
     * $dateTimeFactory
     *
     * @var DateTimeFactoryInterface|MockObject
     */
    protected $dateTimeFactory;

    /**
     * setUp
     *
     * @return void
     */
    public function setUp() : void
    {
        $this->dateTimeFactory = $this->createMock(DateTimeFactoryInterface::class);
    }

    /**
     * testImplementsListenerAwareInterface
     *
     * Ensure that the class implement ListenerAwareInterface.
     *
     * @test
     */
    public function testImplementsListenerAwareInterface() : void
    {
        $strategy = new DateTimeStrategy($this->dateTimeFactory);

        $this->assertInstanceOf(ListenerAggregateInterface::class, $strategy);
    }


}