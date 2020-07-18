<?php

declare(strict_types=1);

namespace ArpTest\Entity;

use Arp\Entity\EntityTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers  \Arp\Entity\EntityTrait
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
     * Assert that we can set and get the id using setId() and getId().
     */
    public function testGetSetId(): void
    {
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

    /**
     * Assert that calls to isId() will return the correct boolean value.
     *
     * @param string  $id
     * @param string  $testId
     * @param boolean $expected
     *
     * @dataProvider getIsIdData
     */
    public function testIsId(string $id, string $testId, bool $expected): void
    {
        $this->entityTrait->setId($id);

        $result = $this->entityTrait->isId($testId);
        if ($expected) {
            $this->assertTrue($result);
        } else {
            $this->assertFalse($result);
        }
    }

    /**
     * @return array|array[]
     */
    public function getIsIdData(): array
    {
        return [
            ['', '', true],
            ['ABC', 'ABC', true],
            ['', 'ABC', false],
            ['HELLO', 'TEST', false],
            ['TEST123', 'TEST123', true],
        ];
    }
}
