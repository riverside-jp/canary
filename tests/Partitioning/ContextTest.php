<?php

declare(strict_types=1);

namespace RiversideHotel\Canary\Tests\Partitioning;

use PHPUnit\Framework\TestCase;
use RiversideHotel\Canary\Partitioning\Context;
use stdClass;

class ContextTest extends TestCase
{
    public function testValidValue(): void
    {
        self::assertSame('1', (new Context('1'))->value());
        self::assertSame(1.0, (new Context(1.0))->value());
        self::assertSame(1, (new Context(1))->value());
        self::assertSame(true, (new Context(true))->value());
        self::assertNull((new Context())->value());
    }

    public function testInvalidValue(): void
    {
        self::assertNull((new Context(new stdClass()))->value());
    }
}
