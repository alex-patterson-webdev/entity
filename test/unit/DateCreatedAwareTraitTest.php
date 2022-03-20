<?php

declare(strict_types=1);

namespace ArpTest\Entity;

use ArpTest\Entity\Double\DateCreatedAwareFake;
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
     * @var DateCreatedAwareFake
     */
    private DateCreatedAwareFake $dateCreatedAwareTrait;

    public function setUp(): void
    {
        $this->dateCreatedAwareTrait = new DateCreatedAwareFake();
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
        $this->dateCreatedAwareTrait->setDateCreated(new \DateTime());

        $this->assertTrue($this->dateCreatedAwareTrait->hasDateCreated());
    }
}
