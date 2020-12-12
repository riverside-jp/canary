<?php

declare(strict_types=1);

namespace RiversideHotel\Canary\Tests\Partitioning;

use PHPUnit\Framework\TestCase;
use RiversideHotel\Canary\Exception\InvalidStrategyArgumentException;
use RiversideHotel\Canary\Partitioning\Context;
use RiversideHotel\Canary\Partitioning\PercentageStrategy;

class PercentageStrategyTest extends TestCase
{
    public function testIsExecutable(): void
    {
        $strategy = new PercentageStrategy(1);

        // abs(crc32('40') % 100) returns '0'
        self::assertTrue($strategy->isExecutable(new Context('40')));

        // abs(crc32('73') % 100) returns '1'
        self::assertTrue($strategy->isExecutable(new Context('73')));

        // abs(crc32('74') % 100) returns '2'
        self::assertFalse($strategy->isExecutable(new Context('74')));
    }

    public function testInstantiateWithNegativeInteger(): void
    {
        $this->expectException(InvalidStrategyArgumentException::class);
        $this->expectExceptionMessage('invalid max percentage: -1');

        new PercentageStrategy(-1);
    }

    public function testInstantiateWithGreaterThan100(): void
    {
        $this->expectException(InvalidStrategyArgumentException::class);
        $this->expectExceptionMessage('invalid max percentage: 101');

        new PercentageStrategy(101);
    }
}
