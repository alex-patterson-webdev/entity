<?php

declare(strict_types=1);

namespace ArpTest\Entity;

use Arp\Entity\EntityTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers EntityTrait
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\Entity
 */
final class EntityTraitTest extends TestCase
{
    /** @var EntityTrait|MockObject */
    private $entityTrait;

    /**
     * Set up the test dependencies.
     */
    public function setUp(): void
    {
        $this->entityTrait = $this->getMockForTrait(EntityTrait::class);
    }

    /**
     * Assert that the identity is not set by default.
     */
    public function testGetIdWillReturnNullByDefault(): void
    {
        $this->assertNull($this->entityTrait->getId());
    }

    /**
     * Assert that we can set and get the id using setId() and getId().
     */
    public function testGetSetId(): void
    {
        $this->assertNull($this->entityTrait->getId());

        $id = 'A';
        $this->entityTrait->setId($id);

        $this->assertSame($id, $this->entityTrait->getId());
    }

    /**
     * Assert that when calling hasId() without any id set, boolean FALSE is returned.
     */
    public function testHasIdWillReturnFalseByDefault(): void
    {
        $this->assertFalse($this->entityTrait->hasId());
    }

    /**
     * Assert that when the id is set that calls to hasId() will return boolean TRUE.
     */
    public function testHasIdWillReturnTrueWhenIdIsNotNull(): void
    {
        $id = 'ABC';
        $this->entityTrait->setId($id);

        $this->assertTrue($this->entityTrait->hasId());
    }
}
