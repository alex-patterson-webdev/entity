<?php

declare(strict_types=1);

namespace ArpTest\Entity;

use ArpTest\Entity\Double\DescriptionAwareFake;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Arp\Entity\DescriptionAwareTrait
 * @covers \ArpTest\Entity\Double\DescriptionAwareFake
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\Entity
 */
final class DescriptionAwareTraitTest extends TestCase
{
    public function testHasDescriptionWillReturnFalseIfTheDescriptionIsEmpty(): void
    {
        $descriptionAware = new DescriptionAwareFake();

        $this->assertFalse($descriptionAware->hasDescription());
    }

    public function testGetDescriptionWillReturnEmptyStringWhenNotSet(): void
    {
        $descriptionAware = new DescriptionAwareFake();

        $this->assertSame('', $descriptionAware->getDescription());
    }

    public function testHasDescriptionWillReturnFalseWhenNotSet(): void
    {
        $descriptionAware = new DescriptionAwareFake();

        $this->assertFalse($descriptionAware->hasDescription());
    }

    public function testHasDescriptionWillReturnTrueWhenSet(): void
    {
        $descriptionAware = new DescriptionAwareFake();

        $descriptionAware->setDescription('This is a test description text');

        $this->assertTrue($descriptionAware->hasDescription());
    }

    public function testSetAndGetDescription(): void
    {
        $descriptionAware = new DescriptionAwareFake();

        $this->assertSame('', $descriptionAware->getDescription());

        $description = 'Hello World!';
        $descriptionAware->setDescription($description);

        $this->assertSame($description, $descriptionAware->getDescription());
    }
}
