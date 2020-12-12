<?php

declare(strict_types=1);

namespace RiversideHotel\Canary\Tests;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use RiversideHotel\Canary\Feature;
use RiversideHotel\Canary\FeatureToggle;
use RiversideHotel\Canary\Partitioning\AllowlistStrategy;

class FeatureToggleTest extends TestCase
{
    use ProphecyTrait;

    public function testEnable(): void
    {
        $toggle = new FeatureToggle(new Feature('foo', false));
        $toggle->enable('foo');

        self::assertTrue($toggle->isEnabled('foo'));
        self::assertFalse($toggle->isDisabled('foo'));
    }

    public function testDisable(): void
    {
        $toggle = new FeatureToggle(new Feature('foo', true));
        $toggle->disable('foo');

        self::assertTrue($toggle->isDisabled('foo'));
        self::assertFalse($toggle->isEnabled('foo'));
    }

    public function testIsEnabled(): void
    {
        $toggle = new FeatureToggle(new Feature('foo', true));

        self::assertTrue($toggle->isEnabled('foo'));
    }

    public function testIsEnabledWithUndefinedFeature(): void
    {
        $toggle = new FeatureToggle();

        self::assertTrue($toggle->isEnabled('foo', true));
        self::assertFalse($toggle->isEnabled('foo', false));
    }

    public function testIsDisabled(): void
    {
        $toggle = new FeatureToggle(new Feature('foo', false));

        self::assertTrue($toggle->isDisabled('foo'));
    }

    public function testIsDisabledWithUndefinedFeature(): void
    {
        $toggle = new FeatureToggle();

        self::assertTrue($toggle->isDisabled('foo', true));
        self::assertFalse($toggle->isDisabled('foo', false));
    }

    public function testIsAvailable(): void
    {
        $toggle = new FeatureToggle(
            new Feature('foo', true, new AllowlistStrategy(['FOO'])),
            new Feature('bar', false, new AllowlistStrategy(['BAR'])),
        );

        self::assertTrue($toggle->isAvailable('foo', false, 'FOO'));
        self::assertFalse($toggle->isAvailable('bar', true, 'BAR'));
    }

    public function testIsAvailableWithUndefinedFeature(): void
    {
        $toggle = new FeatureToggle();

        self::assertTrue($toggle->isAvailable('foo', true));
        self::assertFalse($toggle->isAvailable('foo', false));
    }

    public function testIsAvailableWithoutContext(): void
    {
        $toggle = new FeatureToggle(
            new Feature('foo', true),
            new Feature('bar', false),
        );

        self::assertTrue($toggle->isAvailable('foo'));
        self::assertFalse($toggle->isAvailable('bar'));
    }

    public function testCreateWithConfiguration(): void
    {
        $features = require_once __DIR__ . '/fixtures/config.php';

        $toggle = FeatureToggle::createWithConfiguration($features);

        foreach ($features as $feature) {
            if ($feature['enabled']) {
                self::assertTrue($toggle->isEnabled($feature['name'], false));
            } else {
                self::assertTrue($toggle->isDisabled($feature['name'], true));
            }
        }
    }
}
