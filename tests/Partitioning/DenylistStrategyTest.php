<?php

declare(strict_types=1);

namespace RiversideHotel\Canary\Tests\Partitioning;

use PHPUnit\Framework\TestCase;
use RiversideHotel\Canary\Partitioning\Context;
use RiversideHotel\Canary\Partitioning\DenylistStrategy;

class DenylistStrategyTest extends TestCase
{
    public function testIsExecutable(): void
    {
        $strategy = new DenylistStrategy(['foo']);

        self::assertFalse($strategy->isExecutable(new Context('foo')));
        self::assertTrue($strategy->isExecutable(new Context('bar')));
        self::assertTrue($strategy->isExecutable(new Context()));
    }
}
