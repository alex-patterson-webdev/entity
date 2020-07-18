<?php

declare(strict_types=1);

namespace ArpTest\Entity;

use Arp\Entity\DateCreatedAwareTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Arp\Entity\DateCreatedAwareTrait
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\Entity
 */
final class DateCreatedAwareTraitTest extends TestCase
{
    /**
     * @var DateCreatedAwareTrait|MockObject
     */
    private $dateCreatedAwareTrait;

    /**
     * Prepare the test case dependencies.
     */
    public function setUp(): void
    {
        $this->dateCreatedAwareTrait = $this->getMockForTrait(DateCreatedAwareTrait::class);
    }

    /**
     * Assert that the created date time can be set and returned calling setDateCreated() and getDateCreated().
     */
    public function testSetAndGetDateCreated(): void
    {
        $this->assertNull($this->dateCreatedAwareTrait->getDateCreated());

        $dateCreated = new \DateTime();

        $this->dateCreatedAwareTrait->setDateCreated($dateCreated);

        $this->assertSame($dateCreated, $this->dateCreatedAwareTrait->getDateCreated());
    }

    /**
     * Assert that the default value returned from hasDateCreated() is false.
     */
    public function testHasDateCreatedIsDefaultFalse(): void
    {
        $this->assertFalse($this->dateCreatedAwareTrait->hasDateCreated());
    }

    /**
     * Assert that if a created date has been set, the call to hasDate() will return true.
     */
    public function testHasDateCreatedWillReturnTrueWhenDateIsSet(): void
    {
        $createdDate = new \DateTime();

        $this->dateCreatedAwareTrait->setDateCreated($createdDate);

        $this->assertTrue($this->dateCreatedAwareTrait->hasDateCreated());
    }
}
