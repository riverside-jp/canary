<?php

declare(strict_types=1);

namespace RiversideHotel\Canary\Tests\Partitioning;

use PHPUnit\Framework\TestCase;
use RiversideHotel\Canary\Partitioning\Context;
use RiversideHotel\Canary\Partitioning\NullStrategy;

class NullStrategyTest extends TestCase
{
    public function testIsExecutable(): void
    {
        self::assertTrue((new NullStrategy())->isExecutable(new Context()));
    }
}
