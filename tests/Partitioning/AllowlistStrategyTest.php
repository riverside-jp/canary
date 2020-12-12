<?php

declare(strict_types=1);

namespace RiversideHotel\Canary\Tests\Partitioning;

use PHPUnit\Framework\TestCase;
use RiversideHotel\Canary\Partitioning\AllowlistStrategy;
use RiversideHotel\Canary\Partitioning\Context;

class AllowlistStrategyTest extends TestCase
{
    public function testIsExecutable(): void
    {
        $strategy = new AllowlistStrategy(['foo']);

        self::assertTrue($strategy->isExecutable(new Context('foo')));
        self::assertFalse($strategy->isExecutable(new Context('bar')));
        self::assertFalse($strategy->isExecutable(new Context()));
    }
}
