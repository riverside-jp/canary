<?php

declare(strict_types=1);

namespace RiversideHotel\Canary\Tests\Partitioning;

use DateTime;
use PHPUnit\Framework\TestCase;
use RiversideHotel\Canary\Exception\UndefinedStrategyArgumentException;
use RiversideHotel\Canary\Partitioning\AllowlistStrategy;
use RiversideHotel\Canary\Partitioning\DateRangeStrategy;
use RiversideHotel\Canary\Partitioning\DenylistStrategy;
use RiversideHotel\Canary\Partitioning\PercentageStrategy;
use RiversideHotel\Canary\Partitioning\Strategy;
use RiversideHotel\Canary\Partitioning\NullStrategy;
use RiversideHotel\Canary\Partitioning\StrategyBuilder;

class StrategyBuilderTest extends TestCase
{
    public function testBuildDefaultStrategy(): void
    {
        $builder = StrategyBuilder::createWithDefaultStrategy();

        self::assertEquals(new NullStrategy(), $builder->build());
    }

    public function testBuildAllowlistStrategy(): void
    {
        $builder = StrategyBuilder::createWithDefaultStrategy();
        $builder = $builder->setStrategyType(Strategy::TYPE_ALLOWLIST);
        $builder = $builder->setStrategyArgs([['foo']]);

        self::assertEquals(new AllowlistStrategy(['foo']), $builder->build());
    }

    public function testBuildDateRangeStrategy(): void
    {
        $since = new DateTime('2020-01-01 00:00:00');
        $until = new DateTime('2020-01-02 00:00:00');

        $builder = StrategyBuilder::createWithDefaultStrategy();
        $builder = $builder->setStrategyType(Strategy::TYPE_DATE_RANGE);
        $builder = $builder->setStrategyArgs([$since, $until]);

        self::assertEquals(new DateRangeStrategy($since, $until), $builder->build());
    }

    public function testBuildDateRangeStrategyWithoutUntil(): void
    {
        $since = new DateTime('2020-01-01 00:00:00');

        $builder = StrategyBuilder::createWithDefaultStrategy();
        $builder = $builder->setStrategyType(Strategy::TYPE_DATE_RANGE);
        $builder = $builder->setStrategyArgs([$since]);

        self::assertEquals(new DateRangeStrategy($since), $builder->build());
    }

    public function testBuildDateRangeStrategyWithoutSince(): void
    {
        $until = new DateTime('2020-01-01 00:00:00');

        $builder = StrategyBuilder::createWithDefaultStrategy();
        $builder = $builder->setStrategyType(Strategy::TYPE_DATE_RANGE);
        $builder = $builder->setStrategyArgs([null, $until]);

        self::assertEquals(new DateRangeStrategy(null, $until), $builder->build());
    }

    public function testBuildDenylistStrategy(): void
    {
        $builder = StrategyBuilder::createWithDefaultStrategy();
        $builder = $builder->setStrategyType(Strategy::TYPE_DENYLIST);
        $builder = $builder->setStrategyArgs([['foo']]);

        self::assertEquals(new DenylistStrategy(['foo']), $builder->build());
    }

    public function testBuildPercentageStrategy(): void
    {
        $builder = StrategyBuilder::createWithDefaultStrategy();
        $builder = $builder->setStrategyType(Strategy::TYPE_PERCENTAGE);
        $builder = $builder->setStrategyArgs([100]);

        self::assertEquals(new PercentageStrategy(100), $builder->build());
    }

    public function testBuildUndefinedStrategy(): void
    {
        $this->expectException(UndefinedStrategyArgumentException::class);
        $this->expectExceptionMessage('undefined strategy: foo');

        $builder = StrategyBuilder::createWithDefaultStrategy();
        $builder = $builder->setStrategyType('foo');

        $builder->build();
    }
}
