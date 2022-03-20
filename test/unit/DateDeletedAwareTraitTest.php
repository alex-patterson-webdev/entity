<?php

declare(strict_types=1);

namespace ArpTest\Entity;

use ArpTest\Entity\Double\DateDeletedAwareFake;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Arp\Entity\DateDeletedAwareTrait
 * @covers \ArpTest\Entity\Double\DateDeletedAwareFake
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\Entity
 */
final class DateDeletedAwareTraitTest extends TestCase
{
    public function testDefaultDateIsNull(): void
    {
        $dateDeleted = new DateDeletedAwareFake();

        $this->assertNull($dateDeleted->getDateDeleted());
    }

    public function testHasDateDeletedWillReturnFalseWithNoDeletedDate(): void
    {
        $dateDeleted = new DateDeletedAwareFake();

        $this->assertFalse($dateDeleted->hasDateDeleted());
    }

    public function testGetAndSetDateDeleted(): void
    {
        $dateDeleted = new DateDeletedAwareFake();

        $this->assertNull($dateDeleted->getDateDeleted());

        $deletedDate = new \DateTime();

        $dateDeleted->setDateDeleted($deletedDate);

        $this->assertSame($deletedDate, $dateDeleted->getDateDeleted());

        $dateDeleted->setDateDeleted(null);

        $this->assertNull($dateDeleted->getDateDeleted());
    }
}
