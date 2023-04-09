<?php

declare(strict_types=1);

namespace Arp\Entity;

interface DeleteHistoryAwareInterface extends DeleteAwareInterface, DateDeletedAwareInterface
{
}
