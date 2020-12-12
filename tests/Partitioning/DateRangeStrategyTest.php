<?php

declare(strict_types=1);

namespace RiversideHotel\Canary\Tests\Partitioning;

use DateTime;
use PHPUnit\Framework\TestCase;
use RiversideHotel\Canary\Exception\InvalidStrategyArgumentException;
use RiversideHotel\Canary\Partitioning\Context;
use RiversideHotel\Canary\Partitioning\DateRangeStrategy;

class DateRangeStrategyTest extends TestCase
{
    /**
     * @var string
     */
    private const NOW = '2020-01-01 00:00:00';

    /**
     * @var string
     */
    private const NOW_ONE_SEC_AGO = '2019-12-31 23:59:59';

    /**
     * @var string
     */
    private const NOW_ONE_SEC_LATER = '2020-01-01 00:00:01';

    public function testIsExecutable(): void
    {
        $strategy = new DateRangeStrategy(new DateTime(self::NOW_ONE_SEC_AGO), new DateTime(self::NOW_ONE_SEC_LATER));

        self::assertTrue($strategy->isExecutable(new Context(strtotime(self::NOW))));
        self::assertTrue($strategy->isExecutable(new Context(strtotime(self::NOW_ONE_SEC_AGO))));
        self::assertFalse($strategy->isExecutable(new Context(strtotime(self::NOW_ONE_SEC_LATER))));
    }

    public function testIsExecutableWithoutUntil(): void
    {
        $strategy = new DateRangeStrategy(new DateTime(self::NOW), null);

        self::assertTrue($strategy->isExecutable(new Context(strtotime(self::NOW))));
        self::assertFalse($strategy->isExecutable(new Context(strtotime(self::NOW_ONE_SEC_AGO))));
        self::assertTrue($strategy->isExecutable(new Context(strtotime(self::NOW_ONE_SEC_LATER))));
    }

    public function testIsExecutableWithoutSince(): void
    {
        $strategy = new DateRangeStrategy(null, new DateTime(self::NOW));

        self::assertFalse($strategy->isExecutable(new Context(strtotime(self::NOW))));
        self::assertTrue($strategy->isExecutable(new Context(strtotime(self::NOW_ONE_SEC_AGO))));
        self::assertFalse($strategy->isExecutable(new Context(strtotime(self::NOW_ONE_SEC_LATER))));
    }

    public function testInstantiateWithInvalidArguments(): void
    {
        $since = new DateTime(self::NOW_ONE_SEC_LATER);
        $until = new DateTime(self::NOW_ONE_SEC_AGO);

        $this->expectException(InvalidStrategyArgumentException::class);
        $this->expectExceptionMessage('invalid until date: ' . $until->format(DateTime::ATOM));

        new DateRangeStrategy($since, $until);
    }
}
