<?php

declare(strict_types=1);

namespace RiversideHotel\Canary\Partitioning;

use RiversideHotel\Canary\Exception\InvalidStrategyArgumentException;

class PercentageStrategy implements Strategy
{
    /**
     * @var int
     */
    private $max;

    /**
     * @param int $max
     *
     * @throws InvalidStrategyArgumentException
     */
    public function __construct(int $max = 100)
    {
        $this->setMax($max);
    }

    /**
     * @param Context $context
     *
     * @return bool
     */
    public function isExecutable(Context $context): bool
    {
        /** @var string|null $value */
        $value = $context->value();

        if ($value === null) {
            return false; // fail-safe
        }

        return abs(crc32((string)$value) % 100) <= $this->max;
    }

    /**
     * @param int $max
     *
     * @return void
     */
    private function setMax(int $max): void
    {
        if ($max < 0 || 100 < $max) {
            throw new InvalidStrategyArgumentException('invalid max percentage: ' . $max);
        }

        $this->max = $max;
    }
}
