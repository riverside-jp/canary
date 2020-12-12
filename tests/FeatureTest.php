<?php

declare(strict_types=1);

namespace RiversideHotel\Canary\Tests;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use RiversideHotel\Canary\Exception\InvalidFeatureArgumentException;
use RiversideHotel\Canary\Feature;
use RiversideHotel\Canary\Partitioning\Context;
use RiversideHotel\Canary\Partitioning\Strategy;

class FeatureTest extends TestCase
{
    use ProphecyTrait;

    public function testInstantiateWithEmptyName(): void
    {
        $this->expectException(InvalidFeatureArgumentException::class);
        $this->expectExceptionMessage('empty feature name');

        new Feature('', false);
    }

    public function testName(): void
    {
        self::assertSame('foo', (new Feature('foo', false))->name());
    }

    public function testEnable(): void
    {
        self::assertSame($feature = new Feature('foo', true), $feature->enable());
        self::assertEquals(new Feature('foo', true), (new Feature('foo', false))->enable());
    }

    public function testDisable(): void
    {
        self::assertSame($feature = new Feature('foo', false), $feature->disable());
        self::assertEquals(new Feature('foo', false), (new Feature('foo', true))->disable());
    }

    public function testIsEnabled(): void
    {
        self::assertTrue((new Feature('foo', true))->isEnabled());
        self::assertFalse((new Feature('foo', false))->isEnabled());
    }

    public function testIsDisabled(): void
    {
        self::assertTrue((new Feature('foo', false))->isDisabled());
        self::assertFalse((new Feature('foo', true))->isDisabled());
    }

    public function testIsAvailableWhenFeatureIsEnabled(): void
    {
        $context = new Context('bar');

        $executableStrategy = $this->prophesize(Strategy::class);
        $executableStrategy->isExecutable($context)->willReturn(true)->shouldBeCalled();

        self::assertTrue((new Feature('foo', true, $executableStrategy->reveal()))->isAvailable($context));

        $nonExecutableStrategy = $this->prophesize(Strategy::class);
        $nonExecutableStrategy->isExecutable($context)->willReturn(false)->shouldBeCalled();

        self::assertFalse((new Feature('foo', true, $nonExecutableStrategy->reveal()))->isAvailable($context));
    }

    public function testIsAvailableWhenFeatureIsDisabled(): void
    {
        $context = new Context('bar');

        $executableStrategy = $this->prophesize(Strategy::class);
        $executableStrategy->isExecutable($context)->willReturn(true)->shouldNotBeCalled();

        self::assertFalse((new Feature('foo', false, $executableStrategy->reveal()))->isAvailable($context));

        $nonExecutableStrategy = $this->prophesize(Strategy::class);
        $nonExecutableStrategy->isExecutable($context)->willReturn(false)->shouldNotBeCalled();

        self::assertFalse((new Feature('foo', false, $nonExecutableStrategy->reveal()))->isAvailable($context));
    }
}
