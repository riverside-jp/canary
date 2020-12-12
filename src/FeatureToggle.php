<?php

declare(strict_types=1);

namespace RiversideHotel\Canary;

use RiversideHotel\Canary\Exception\ExceptionInterface;
use RiversideHotel\Canary\Exception\InvalidConfigurationException;
use RiversideHotel\Canary\Partitioning\Context;

class FeatureToggle implements FeatureToggleInterface
{
    /**
     * @var array<string,Feature>
     */
    private $features = [];

    /**
     * @param Feature ...$features
     */
    public function __construct(Feature ...$features)
    {
        foreach ($features as $feature) {
            $this->appendFeature($feature);
        }
    }

    /**
     * @param array<array<string,mixed>> $configs
     *
     * @throws InvalidConfigurationException
     *
     * @return self
     */
    public static function createWithConfiguration(array $configs = []): self
    {
        $features = [];

        foreach ($configs as $config) {
            try {
                $features[] = Feature::create(
                    $config['name'] ?? '',
                    $config['enabled'] ?? false,
                    $config['strategy']['type'] ?? null,
                    $config['strategy']['args'] ?? null
                );
            } catch (ExceptionInterface $e) {
                throw new InvalidConfigurationException('error occurred during initialization feature toggle: ' . $config['name'], 0, $e);
            }
        }

        return new FeatureToggle(...$features);
    }

    /**
     * @param string $featureName
     *
     * @return void
     */
    public function enable(string $featureName): void
    {
        $feature = $this->get($featureName);

        if ($feature !== null) {
            $this->features[$featureName] = $feature->enable();
        }
    }

    /**
     * @param string $featureName
     *
     * @return void
     */
    public function disable(string $featureName): void
    {
        $feature = $this->get($featureName);

        if ($feature !== null) {
            $this->features[$featureName] = $feature->disable();
        }
    }

    /**
     * @param string $featureName
     * @param bool $default
     *
     * @return bool
     */
    public function isEnabled(string $featureName, bool $default = false): bool
    {
        $feature = $this->get($featureName);

        if ($feature !== null) {
            return $feature->isEnabled();
        }

        return $default;
    }

    /**
     * @param string $featureName
     * @param bool $default
     *
     * @return bool
     */
    public function isDisabled(string $featureName, bool $default = false): bool
    {
        $feature = $this->get($featureName);

        if ($feature !== null) {
            return $feature->isDisabled();
        }

        return $default;
    }

    /**
     * @param string $featureName
     * @param bool $default
     * @param mixed $context
     *
     * @return bool
     */
    public function isAvailable(string $featureName, bool $default = false, $context = null): bool
    {
        $feature = $this->get($featureName);

        if ($feature !== null) {
            return $feature->isAvailable(new Context($context));
        }

        return $default;
    }

    /**
     * @param Feature $feature
     *
     * @return void
     */
    private function appendFeature(Feature $feature): void
    {
        $this->features[$feature->name()] = $feature;
    }

    /**
     * @param string $featureName
     *
     * @return Feature|null
     */
    private function get(string $featureName): ?Feature
    {
        if (array_key_exists($featureName, $this->features)) {
            return $this->features[$featureName];
        }

        return null;
    }
}
