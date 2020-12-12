<?php

declare(strict_types=1);

namespace RiversideHotel\Canary;

interface FeatureToggleInterface
{
    /**
     * @param string $featureName
     */
    public function enable(string $featureName): void;

    /**
     * @param string $featureName
     */
    public function disable(string $featureName): void;

    /**
     * @param string $featureName
     * @param bool $default
     *
     * @return bool
     */
    public function isEnabled(string $featureName, bool $default = false): bool;

    /**
     * @param string $featureName
     * @param bool $default
     *
     * @return bool
     */
    public function isDisabled(string $featureName, bool $default = false): bool;

    /**
     * @param string $featureName
     * @param bool $default
     * @param mixed $context
     *
     * @return bool
     */
    public function isAvailable(string $featureName, bool $default = false, $context = null): bool;
}
